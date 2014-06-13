<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get(
    '/api/locations',
    function () {
        return 'Hello World';
    }
);

return $app;
