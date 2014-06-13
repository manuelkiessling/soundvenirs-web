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
                'long' => 1.11,
                'soundId' => 1
            ),
            array(
                'id' => 2,
                'title' => 'bar',
                'lat' => 22.2,
                'long' => 2.22,
                'soundId' => 2
            )
        ));
    }
);

$app->get(
    '/api/sounds/{id}',
    function (\Silex\Application $app, $id) {
        return $app->json(
            array(
                'id' => (int)$id,
                'title' => 'first sound',
                'lat' => 11.1,
                'long' => 1.11,
                'mp3Url' => '/mp3/1/first_sound.mp3'
            )
        );
    }
);

$app->get(
    '/qrcode-demo.png',
    function () {
        require_once __DIR__.'/../legacy-vendor/phpqrcode/qrlib.php';
        $image = QRcode::png('Hello World');
        return new \Symfony\Component\HttpFoundation\Response($image, 200, array('content-type' => 'image/png'));
    }
);

return $app;
