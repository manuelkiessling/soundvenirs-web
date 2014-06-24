<?php

namespace Soundvenirs\ApiBundle\Tests\Controller;

use Soundvenirs\SoundBundle\Entity\Sound;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiSoundsTest extends WebTestCase
{
    protected $client;
    protected $entityManager;

    public function setUp()
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');

        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('Sound', true));
    }

    public function testRetrieveOneSound()
    {
        $client = static::createClient();

        $sound = new Sound();
        $sound->id = 'ab12cd';
        $sound->title = 'First Song';
        $sound->lat = 11.1;
        $sound->long = 1.11;
        $this->entityManager->persist($sound);
        $this->entityManager->flush();

        $client->request('GET', '/api/sounds/ab12cd');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '{"id":"ab12cd","title":"First Song","mp3url":"http:\/\/www.soundvenirs.com\/download\/ab12cd.mp3","location":{"lat":11.1,"long":1.11}}',
            $content
        );
    }

    public function testCreateSound()
    {
        $client = static::createClient();
        $client->request('POST', '/api/sounds', array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"title":"First Song"}');
        $content = $client->getResponse()->getContent();
        $this->assertRegExp('/^\{"id":"[0-9a-z]{1,6}"\}$/', $content);
    }
}
