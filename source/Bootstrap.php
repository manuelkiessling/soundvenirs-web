<?php

use Symfony\Component\HttpFoundation\Request;

\date_default_timezone_set('Europe/Berlin');

require_once __DIR__.'/Uuid.php';
require_once __DIR__.'/Functions.php';
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

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

$app->get(
    '/',
    function () use ($app) {
        $form = $app['form.factory']->createBuilder('form')
            ->add('title', 'text')
            ->add('soundfile', 'file', array('attr' => array('onchange' => 'this.form.submit()')))
            ->getForm();
        return $app['twig']->render('index.twig', array('form' => $form->createView()));
    }
);

$app->post(
    '/upload',
    function (Request $request) use ($app) {
        $form = $app['form.factory']->createBuilder('form')
          ->add('title', 'text')
          ->add('soundfile', 'file')
          ->getForm();
        $form->bind($request);
        $files = $request->files->get($form->getName());
        $soundfile = $files['soundfile'];
        $data = $form->getData();
        $title = $data['title'];
        if (is_null($title)) {
            $title = \basename($soundfile->getClientOriginalName(), '.mp3');
        }
        $uuid = createSound($app, $title);
        $soundfile->move('/var/tmp/', 'soundvenirs-'.$uuid.'.mp3');
        return $app['twig']->render('qrcode.twig', array('uuid' => $uuid));
    }
);

$app->get(
    '/download/{uuid}.mp3',
    function ($uuid) {
        return new \Symfony\Component\HttpFoundation\Response(
            file_get_contents('/var/tmp/soundvenirs-'.$uuid.'.mp3'),
            200,
            array('Content-Type' => 'audio/mpeg')
        );
    }
);

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

$app->post(
    '/api/sounds',
    function (Request $request) use ($app) {
        $uuid = createSound($app, $request->get('title'));
        return $app->json(array('uuid' => $uuid));
    }
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
        require_once __DIR__.'/../legacy-vendor/phpqrcode/qrlib.php';
        $image = QRcode::png($uuid);
        return new \Symfony\Component\HttpFoundation\Response($image, 200, array('Content-Type' => 'image/png'));
    }
);

return $app;
