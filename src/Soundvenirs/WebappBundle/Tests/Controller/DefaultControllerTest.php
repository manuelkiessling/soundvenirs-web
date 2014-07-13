<?php

namespace Soundvenirs\WebappBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app/#/');

        $this->assertTrue($crawler->filter('div')->count() === 1);
    }
}
