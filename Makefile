default: development server

development: dependencies webapp assets dev-migrations test-migrations

production: dependencies assets prod-migrations
	php bin/console cache:clear --env prod

dependencies: php-dependencies
	npm install
	cd src/Soundvenirs/HomepageBundle ; bower install --allow-root
	cd src/Soundvenirs/WebappBundle/Resources/frontend-application ; npm install ; bower install --allow-root

php-dependencies:
	composer install --no-interaction --quiet

migrations: dev-migrations test-migrations

prod-migrations:
	php bin/console doctrine:migrations:migrate -n --env prod

dev-migrations:
	php bin/console doctrine:migrations:migrate -n --env dev

test-migrations:
	php bin/console doctrine:migrations:migrate -n --env test

assets:
	php bin/console assets:install

webapp:
	cd src/Soundvenirs/WebappBundle/Resources/frontend-application ; grunt concat:dist ; grunt copy

webapp-test:
	cd src/Soundvenirs/WebappBundle/Resources/frontend-application ; grunt test

backend-test:
	php bin/console cache:clear --env test
	./vendor/phpunit/phpunit/phpunit

e2e-test:
	./node_modules/protractor/bin/protractor protractor.conf.js

test: backend-test webapp-test e2e-test

server:
	php -S 0.0.0.0:8080 -t web/

ghostdriver:
	./node_modules/phantomjs/bin/phantomjs --webdriver=9515

travisci-packages:
	sudo apt-get update -qq
	sudo apt-get install php5-sqlite php5-gd sqlite3

travisci-before-script: travisci-packages php-dependencies assets test-migrations
	./node_modules/phantomjs/bin/phantomjs --webdriver=9515 &
	php -S 0.0.0.0:8080 -t web/ &

travisci-script: test
	./vendor/squizlabs/php_codesniffer/scripts/phpcs --standard=PSR2 ./src

travisci-after-success:
	bash ./build/create-github-release.sh ${GITHUB_TOKEN} travisci-build-${TRAVIS_BUILD_NUMBER} ${TRAVIS_COMMIT} https://travis-ci.org/manuelkiessling/soundvenirs-backend/builds/${TRAVIS_BUILD_ID}
