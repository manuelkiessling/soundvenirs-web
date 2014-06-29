<?php

namespace Soundvenirs\ApiBundle\Tests\Controller;

use Soundvenirs\DomainBundle\Entity\Sound;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SoundsControllerTest extends WebTestCase
{
    protected $entityManager;

    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');

        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('Sound', true));
    }

    public function testRetrieveOneSound()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $soundRepo = $container->get('soundvenirs_domain.sound_repository');

        $sound = new Sound();
        $sound->id = 'ab12cd';
        $sound->title = 'First Song';
        $sound->lat = 11.1;
        $sound->long = 1.11;
        $soundRepo->persist($sound);

        $client->request('GET', '/api/sounds/ab12cd');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '{"id":"ab12cd","title":"First Song","mp3url":"http:\/\/www.soundvenirs.com\/download\/ab12cd.mp3",'.
            '"location":{"lat":11.1,"long":1.11}}',
            $content
        );
    }

    public function testNonExistantSound()
    {
        $client = static::createClient();
        $client->request('GET', '/api/sounds/foo');
        $content = $client->getResponse()->getContent();
        $status = $client->getResponse()->getStatusCode();
        $this->assertSame('{}', $content);
        $this->assertSame(404, $status);
    }

    public function testCreateSound()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/sounds',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"First Song"}'
        );
        $content = $client->getResponse()->getContent();
        $this->assertRegExp('/^\{"id":"[0-9a-z]{1,6}"\}$/', $content);

        $values = json_decode($content);
        $id = $values->id;

        $repo = $client->getContainer()->get('soundvenirs_domain.sound_repository');
        $sound = $repo->find($id);

        $this->assertSame('First Song', $sound->title);
    }

    public function testSetSoundLocation()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/sounds',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"First Song"}'
        );
        $content = $client->getResponse()->getContent();
        $values = json_decode($content);
        $id = $values->id;

        $client->request(
            'POST',
            '/api/sounds/'.$id,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"lat":56.2,"long":6.0}'
        );
        $content = $client->getResponse()->getContent();
        $values = json_decode($content);
        $status = $values->status;

        $this->assertSame(true, $status);

        $client->request('GET', '/api/sounds/'.$id);
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '{"id":"'.$id.'","title":"First Song","mp3url":"http:\/\/www.soundvenirs.com\/download\/'.$id.'.mp3",'.
            '"location":{"lat":56.2,"long":6}}',
            $content
        );
    }

    public function testSetSoundLocationAlreadyDone()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/sounds',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"First Song"}'
        );
        $content = $client->getResponse()->getContent();
        $values = json_decode($content);
        $id = $values->id;

        $client->request(
            'POST',
            '/api/sounds/'.$id,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"lat":56.0,"long":6.0}'
        );

        $client->request(
            'POST',
            '/api/sounds/'.$id,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"lat":99.0,"long":9.0}'
        );
        $content = $client->getResponse()->getContent();
        $result = json_decode($content);
        $status = $result->status;

        $this->assertEquals(false, $status);
    }
}
