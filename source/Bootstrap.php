<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get(
    '/api/locations',
    function () use ($app) {
        return $app->json(array(
            array(
                'id' => 1,
                'title' => 'foo',
                'lat' => 11.1,
                'long' => 1.11
            ),
            array(
                'id' => 2,
                'title' => 'bar',
                'lat' => 22.2,
                'long' => 2.22
            )
        ));
    }
);

return $app;
