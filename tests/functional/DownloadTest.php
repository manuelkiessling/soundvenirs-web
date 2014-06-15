<?php

require_once __DIR__ . '/SoundvenirsWebTestCase.php';

class DownloadTest extends SoundvenirsWebTestCase
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
        $row = $this->app['db']->fetchAssoc('SELECT uuid FROM sounds LIMIT 1;');
        $client->request('GET', '/download/'.$row['uuid'].'.mp3');
        $this->assertEquals('foo', $client->getResponse()->getContent());
        unlink('/var/tmp/soundvenirs-'.$row['uuid'].'.mp3');
    }
}
