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
        $sound->id = 'aaaaaa';
        $sound->title = 'First Song';
        $sound->lat = 11.1;
        $sound->long = 1.11;
        $soundRepo->persist($sound);

        $sound = new Sound();
        $sound->id = 'bbbbbb';
        $sound->title = 'Second Song';
        $sound->lat = 22.2;
        $sound->long = 2.22;
        $soundRepo->persist($sound);

        $sound = new Sound();
        $sound->id = 'cccccc';
        $sound->title = 'Third Song';
        $sound->lat = null;
        $sound->long = null;
        $soundRepo->persist($sound);

        $client->request('GET', '/api/soundLocations');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '[{"title":"First Song","location":{"lat":11.1,"long":1.11}},'.
            '{"title":"Second Song","location":{"lat":22.2,"long":2.22}}]',
            $content
        );
    }
}
