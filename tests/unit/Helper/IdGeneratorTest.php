<?php

require_once __DIR__.'/../../../vendor/autoload.php';

use Soundvenirs\Helper\IdGenerator;

class IdGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
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

    public function testGeneration()
    {
        $idGenerator = new IdGenerator($this->app['db']);
        $id = $idGenerator->generate();
        $this->assertRegExp('/^[0-9a-z]{1,6}$/', $id);
    }

    public function testForce()
    {
        $idGenerator = new IdGenerator($this->app['db']);
        $id = $idGenerator->generate('abcdef');
        $this->assertSame('abcdef', $id);
    }

    public function testCollisionDetection()
    {
        $this->app['db']->query('INSERT INTO sounds (uuid, title) VALUES ("abcdef", "foo");');
        $idGenerator = new IdGenerator($this->app['db']);
        $id = $idGenerator->generate('abcdef');
        $this->assertNotSame('abcdef', $id);
    }
}
