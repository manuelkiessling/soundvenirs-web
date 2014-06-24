<?php

namespace Soundvenirs\ApiBundle\Tests\Controller;

use Soundvenirs\SoundBundle\Entity\Sound;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiSoundsTest extends WebTestCase
{
    public function testGetSoundById()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $connection = $entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('Sound', true));

        $sound = new Sound();
        $sound->id = 'ab12cd';
        $sound->title = 'First Song';
        $sound->lat = 11.1;
        $sound->long = 1.11;
        $entityManager->persist($sound);
        $entityManager->flush();

        $client->request('GET', '/api/sounds/1');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '{"id":"ab12cd","title":"First Song","mp3url":"http://www.soundvenirs.com/download/ab12cd.mp3","location":{"lat":11.1,"long":1.11}}',
            $content
        );
    }
}
