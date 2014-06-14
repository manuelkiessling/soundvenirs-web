<?php

use Symfony\Component\HttpFoundation\Request;

\date_default_timezone_set('Europe/Berlin');

require_once __DIR__.'/Uuid.php';
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
            $row['location'] = array(
                'lat' => (float)$row['lat'],
                'long' => (float)$row['long'],
            );
            unset($row['lat']);
            unset($row['long']);
            $soundLocations[] = $row;
        }
        return $app->json($soundLocations);
    }
);

$app->put(
    '/api/sounds',
    function (Request $request) use ($app) {
        $uuid = Uuid::generate();
        $app['db']->insert(
            'sounds',
            array(
                'uuid' => $uuid,
                'title' => $request->get('title'),
                'mp3url' => 'http://www.soundvenirs.com/download/'.$uuid.'.mp3',
            )
        );
        return $app->json($uuid);
    }
);

$app->post(
    '/api/sounds/{uuid}',
    function (Request $request, $uuid) use ($app) {
        $row = $app['db']->fetchAssoc('SELECT lat FROM sounds WHERE uuid = ?;', array($uuid));
        if ($row['lat'] != null) {
            return $app->json(false);
        }
        $app['db']->update(
            'sounds',
            array(
                'lat' => $request->get('lat'),
                'long' => $request->get('long')
            ),
            array('uuid' => $uuid)
        );
        return $app->json(true);
    }
);

$app->get(
    '/api/sounds/{uuid}',
    function (\Silex\Application $app, $uuid) {
        $row = $app['db']->fetchAssoc('SELECT * FROM sounds WHERE uuid = ?;', array($uuid));
        $row['location'] = array(
            'lat' => (float)$row['lat'],
            'long' => (float)$row['long'],
        );
        unset($row['lat']);
        unset($row['long']);
        return $app->json($row);
    }
);

$app->get(
    '/qrcode-demo.png',
    function () {
        require_once __DIR__.'/../legacy-vendor/phpqrcode/qrlib.php';
        $image = QRcode::png('4dd7b248-5acd-489f-b292-c3fbace30e2c');
        return new \Symfony\Component\HttpFoundation\Response($image, 200, array('content-type' => 'image/png'));
    }
);

return $app;
