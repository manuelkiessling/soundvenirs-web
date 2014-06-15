<?php

require_once __DIR__ . '/SoundvenirsWebTestCase.php';

class UploadTest extends SoundvenirsWebTestCase
{
    public function test()
    {
        $this->resetDatabase();
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        $uploadForm = $crawler->selectButton('Upload')->form();
        $uploadForm['form[soundfile]']->upload(__DIR__.'/../assets/soundfile.mp3');
        $uploadForm['form[title]'] = 'First Song';
        $crawler = $client->submit($uploadForm);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $row = $this->app['db']->fetchAssoc('SELECT uuid, title FROM sounds LIMIT 1;');
        $this->assertEquals('First Song', $row['title']);
        $this->assertTrue(file_exists('/var/tmp/soundvenirs-'.$row['uuid'].'.mp3'));
        unlink('/var/tmp/soundvenirs-'.$row['uuid'].'.mp3');
    }
}
