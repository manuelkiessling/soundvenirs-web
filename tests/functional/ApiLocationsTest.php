<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Silex\WebTestCase;

class ApiLocationsTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../source/Bootstrap.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();
        return $app;
    }

    public function test()
    {
        $client = $this->createClient();
        $client->request('GET', '/api/locations');
        $content = $client->getResponse()->getContent();
        $this->assertEquals('Hello World', $content);
    }
}