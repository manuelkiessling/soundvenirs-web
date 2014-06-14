<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Silex\WebTestCase;

class ApiSoundsTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../source/Bootstrap.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();
        return $app;
    }

    public function testGetSoundById()
    {
        $client = $this->createClient();
        $client->request('GET', '/api/sounds/1');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '{"id":1,"title":"first sound","lat":11.1,"long":1.11,"mp3Url":"\/mp3\/1\/first_sound.mp3"}',
            $content
        );
    }
}
