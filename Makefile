default: development server

development: dependencies assets
	php bin/console doctrine:migrations:migrate -n --env dev
	php bin/console doctrine:migrations:migrate -n --env test

dependencies: php-dependencies
	cd src/Soundvenirs/HomepageBundle ; bower install

php-dependencies:
    composer install -n

assets:
	php bin/console assets:install

server:
	php -S 0.0.0.0:8080 -t web/

travisci: php-dependencies assets
