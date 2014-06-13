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

    public function testGetAllLocations()
    {
        $client = $this->createClient();
        $client->request('GET', '/api/locations');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '[{"id":1,"title":"foo","lat":11.1,"long":1.11},{"id":2,"title":"bar","lat":22.2,"long":2.22}]',
            $content
        );
    }
}
