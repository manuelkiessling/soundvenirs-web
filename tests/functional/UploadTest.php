<?php

require_once __DIR__ . '/SoundvenirsWebTestCase.php';

class UploadTest extends SoundvenirsWebTestCase
{
    public function test()
    {
        $this->resetDatabase();
        unlink('/var/tmp/soundfile.mp3');
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        $uploadForm = $crawler->selectButton('Upload')->form();
        $uploadForm['form[soundfile]']->upload(__DIR__.'/../assets/soundfile.mp3');
        $crawler = $client->submit($uploadForm);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue(file_exists('/var/tmp/soundfile.mp3'));
    }
}
