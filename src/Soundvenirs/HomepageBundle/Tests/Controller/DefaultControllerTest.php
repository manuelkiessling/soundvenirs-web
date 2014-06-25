<?php

namespace Soundvenirs\HomepageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('Sound', true));
    }

    public function testUpload()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $uploadForm = $crawler->selectButton('Upload')->form();
        $uploadForm['form[soundfile]']->upload(__DIR__.'/../assets/soundfile.mp3');
        $uploadForm['form[title]'] = 'First Song';
        $client->submit($uploadForm);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $repo = $client->getContainer()->get('soundvenirs_domain.sound_repository');
        $sounds = $repo->findAll();
        $sound = $sounds[0];

        $this->assertEquals('First Song', $sound->title);
        $this->assertTrue(file_exists('/var/tmp/soundvenirs-'.$sound->id.'.mp3'));
        unlink('/var/tmp/soundvenirs-'.$sound->id.'.mp3');
    }
}
