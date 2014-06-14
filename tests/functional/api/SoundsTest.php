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
            '{"uuid":1,"title":"First Song","lat":11.1,"long":1.11,"mp3Url":"http:\/\/foo\/bar"}',
            $content
        );
    }
}
