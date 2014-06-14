<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Silex\WebTestCase;

class SoundvenirsWebTestCase extends WebTestCase
{
    public $app;

    public function createApplication()
    {
        $app = require __DIR__ . '/../../source/Bootstrap.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();
        $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver' => 'pdo_sqlite',
                'path' => '/var/tmp/soundvenirs.test.sqlite',
            ),
        ));
        $this->app = $app;
        return $app;
    }

    protected function resetDatabase()
    {
        $this->app['db']->query('DROP TABLE IF EXISTS sounds;');
        $this->app['db']->query(
            'CREATE TABLE sounds(
              uuid CHAR(36) PRIMARY KEY NOT NULL,
              title TEXT NOT NULL,
              lat FLOAT NULL,
              long FLOAT NULL,
              mp3url TEXT
            );'
        );
    }

    public function test()
    {
        // This is just a dummy
    }
}
