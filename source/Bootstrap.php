<?php

use Symfony\Component\HttpFoundation\Request;

\date_default_timezone_set('Europe/Berlin');

require_once __DIR__.'/Functions.php';
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = false;

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => '/var/tmp/soundvenirs.production.sqlite',
    ),
));

$app->before(function (Request $request) {
    // Make JSON fields available just like POST parameters
    if (0 === strpos($request->headers->get('content-type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app['controller.homepage'] = $app->share(function () use ($app) {
    return new \Soundvenirs\Controller\Homepage($app['twig'], $app['form.factory']);
});

$app['controller.api.soundlocations'] = $app->share(function () use ($app) {
    return new \Soundvenirs\Controller\Api\Soundlocations($app['db']);
});

$app['controller.api.sounds'] = $app->share(function () use ($app) {
    return new \Soundvenirs\Controller\Api\Sounds($app['db']);
});

$app->get(
    '/',
    'controller.homepage:indexAction'
);

$app->post(
    '/upload',
    'controller.homepage:uploadAction'
);

$app->get(
    '/download/{uuid}.mp3',
    'controller.homepage:downloadAction'
);

$app->get(
    '/api/soundLocations',
    'controller.api.soundlocations:getAllAction'
);

$app->post(
    '/api/sounds',
    'controller.api.sounds:createAction'
);

$app->post(
    '/api/sounds/{uuid}',
    function (Request $request, $uuid) use ($app) {
        $row = $app['db']->fetchAssoc('SELECT lat FROM sounds WHERE uuid = ?;', array($uuid));
        if ($row['lat'] != null) {
            return $app->json(array('status' => false));
        }
        $app['db']->update(
            'sounds',
            array(
                'lat' => $request->get('lat'),
                'long' => $request->get('long')
            ),
            array('uuid' => $uuid)
        );
        return $app->json(array('status' => true));
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
    '/qrcode/{uuid}',
    function ($uuid) {
        require_once __DIR__.'/../vendor/t0k4rt/phpqrcode/qrlib.php';
        $image = QRcode::png($uuid);
        return new \Symfony\Component\HttpFoundation\Response($image, 200, array('Content-Type' => 'image/png'));
    }
);

return $app;
