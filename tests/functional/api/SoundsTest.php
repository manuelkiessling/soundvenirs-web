<?php

require_once __DIR__ . '/../SoundvenirsWebTestCase.php';

class ApiSoundsTest extends SoundvenirsWebTestCase
{
    public function testGetSoundById()
    {
        $this->resetDatabase();
        $this->app['db']->query(
            'INSERT INTO sounds (uuid, title, lat, long, mp3url)
            VALUES ("1", "First Song", 11.1, 1.11, "http://foo/bar");'
        );
        $client = $this->createClient();
        $client->request('GET', '/api/sounds/1');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '{"uuid":"1","title":"First Song","mp3url":"http:\/\/foo\/bar","location":{"lat":11.1,"long":1.11}}',
            $content
        );
    }

    public function testCreateNewSound()
    {
        $this->resetDatabase();
        $client = $this->createClient();
        $client->request('POST', '/api/sounds', array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"title":"First Song"}');
        $content = $client->getResponse()->getContent();

        $this->assertRegExp('/^\{"uuid":"[0-9a-f]{40}"\}$/', $content);

        $row = $this->app['db']->fetchAssoc('SELECT * FROM sounds LIMIT 1;');

        $this->assertEquals(
            array(
                'uuid' => $row['uuid'],
                'title' => 'First Song',
                'lat' => null,
                'long' => null,
                'mp3url' => 'http://www.soundvenirs.com/download/'.$row['uuid'].'.mp3'
            ),
            $row
        );
    }

    public function testSetSoundLocation()
    {
        $this->resetDatabase();
        $client = $this->createClient();
        $client->request('POST', '/api/sounds', array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"title":"First Song"}');
        $content = $client->getResponse()->getContent();
        $result = json_decode($content);
        $uuid = $result->uuid;

        $client->request('POST', '/api/sounds/'.$uuid, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"lat":56.0,"long":6.0}');
        $content = $client->getResponse()->getContent();
        $result = json_decode($content);
        $status = $result->status;

        $this->assertEquals(true, $status);

        $row = $this->app['db']->fetchAssoc('SELECT * FROM sounds LIMIT 1;');

        $this->assertEquals(
            array(
                'uuid' => $row['uuid'],
                'title' => 'First Song',
                'lat' => '56.0',
                'long' => '6.0',
                'mp3url' => 'http://www.soundvenirs.com/download/'.$row['uuid'].'.mp3'
            ),
            $row
        );
    }

    public function testSetSoundLocationAlreadyDone()
    {
        $this->resetDatabase();
        $client = $this->createClient();
        $client->request('POST', '/api/sounds', array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"title":"First Song"}');
        $content = $client->getResponse()->getContent();
        $result = json_decode($content);
        $uuid = $result->uuid;

        $client->request('POST', '/api/sounds/'.$uuid, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"lat":56.0,"long":6.0}');
        $content = $client->getResponse()->getContent();
        $result = json_decode($content);
        $status = $result->status;

        $this->assertEquals(true, $status);

        $client->request('POST', '/api/sounds/'.$uuid, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"lat":56.0,"long":6.0}');
        $content = $client->getResponse()->getContent();
        $result = json_decode($content);
        $status = $result->status;

        $this->assertEquals(false, $status);
    }
}
