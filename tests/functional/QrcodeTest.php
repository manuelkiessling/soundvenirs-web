<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Silex\WebTestCase;

class QrcodeTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../source/Bootstrap.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();
        return $app;
    }

    public function testGetCodeImage()
    {
        $client = $this->createClient();
        ob_start();
        $client->request('GET', '/qrcode/1234');
        ob_clean();
        $this->assertEquals('image/png', $client->getResponse()->headers->get('content-type'));
    }
}
