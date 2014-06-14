<?php

\date_default_timezone_set('Europe/Berlin');

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => '/var/tmp/soundvenirs.production.sqlite',
    ),
));

$app->get(
    '/api/soundLocations',
    function () use ($app) {
        $rows = $app['db']->fetchAll('SELECT title, lat, long FROM sounds WHERE lat IS NOT NULL;');
        $soundLocations = array();
        foreach ($rows as $row) {
            $row['lat'] = (float)$row['lat'];
            $row['long'] = (float)$row['long'];
            $soundLocations[] = $row;
        }
        return $app->json($soundLocations);
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