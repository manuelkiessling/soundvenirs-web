<?php

namespace Soundvenirs\ApiBundle\Tests\Controller;

use Soundvenirs\DomainBundle\Entity\Sound;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SoundLocationsControllerTest extends WebTestCase
{
    public function testRetrieveSoundLocations()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('Sound', true));

        $soundRepo = $container->get('soundvenirs_domain.sound_repository');

        $sound = new Sound();
        $sound->setId('aaaaaa');
        $sound->setTitle('First Song');
        $sound->setLat(11.1);
        $sound->setLong(1.11);
        $soundRepo->persist($sound);

        $sound = new Sound();
        $sound->setId('bbbbbb');
        $sound->setTitle('Second Song');
        $sound->setLat(22.2);
        $sound->setLong(2.22);
        $soundRepo->persist($sound);

        $sound = new Sound();
        $sound->setId('cccccc');
        $sound->setTitle('Third Song');
        $sound->setLat(null);
        $sound->setLong(null);
        $soundRepo->persist($sound);

        $client->request('GET', '/api/soundLocations');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '[{"title":"First Song","lat":11.1,"long":1.11},'.
            '{"title":"Second Song","lat":22.2,"long":2.22}]',
            $content
        );
    }
}
