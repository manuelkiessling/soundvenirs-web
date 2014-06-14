<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Silex\WebTestCase;

class ApiLocationsTest extends WebTestCase
{
    public $app;

    public function createApplication()
    {
        $app = require __DIR__ . '/../../../source/Bootstrap.php';
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

    public function testGetAllSoundLocations()
    {
        $this->resetDatabase();
        $this->app['db']->query('INSERT INTO sounds (uuid, title, lat, long, mp3url) VALUES ("1", "First Song", 11.1, 1.11, "http://foo/bar");');
        $this->app['db']->query('INSERT INTO sounds (uuid, title, lat, long, mp3url) VALUES ("2", "Second Song", 22.2, 2.22, "http://foo/bar");');
        $this->app['db']->query('INSERT INTO sounds (uuid, title, lat, long, mp3url) VALUES ("3", "Third Song", NULL, NULL, "http://foo/bar");');
        $client = $this->createClient();
        $client->request('GET', '/api/soundLocations');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '[{"title":"First Song","lat":11.1,"long":1.11},{"title":"Second Song","lat":22.2,"long":2.22}]',
            $content
        );
    }
}
