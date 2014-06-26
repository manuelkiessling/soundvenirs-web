default: development server

development: dependencies assets
	php bin/console doctrine:migrations:migrate -n --env dev
	php bin/console doctrine:migrations:migrate -n --env test

dependencies:
	composer install -n
	cd src/Soundvenirs/HomepageBundle ; bower install

assets:
	php bin/console assets:install

server:
	php -S 0.0.0.0:8080 -t web/

travisci: development