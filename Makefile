default: development server

development: dependencies assets dev-migrations test-migrations

production: dependencies assets prod-migrations
	php bin/console cache:clear --env prod

dependencies: php-dependencies
	cd src/Soundvenirs/HomepageBundle ; bower install --allow-root

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

server:
	php -S 0.0.0.0:8080 -t web/

travisci-packages:
	sudo apt-get update -qq
	sudo apt-get install php5-sqlite php5-gd sqlite3

travisci-before-script: travisci-packages php-dependencies assets test-migrations

travisci-script:
	phpunit
	./vendor/squizlabs/php_codesniffer/scripts/phpcs --standard=PSR2 ./src

travisci-after-success:
	bash ./build/create-github-release.sh ${GITHUB_TOKEN} travisci-build-${TRAVIS_BUILD_NUMBER} ${TRAVIS_COMMIT} https://travis-ci.org/manuelkiessling/soundvenirs-backend/builds/${TRAVIS_BUILD_ID}
