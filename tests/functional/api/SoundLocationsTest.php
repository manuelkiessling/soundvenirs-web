<?php

require_once __DIR__ . '/../SoundvenirsWebTestCase.php';

class ApiLocationsTest extends SoundvenirsWebTestCase
{
    public function testGetAllSoundLocations()
    {
        $this->resetDatabase();
        $this->app['db']->query(
            'INSERT INTO sounds (uuid, title, lat, long, mp3url)
            VALUES ("1", "First Song", 11.1, 1.11, "http://foo/bar");'
        );
        $this->app['db']->query(
            'INSERT INTO sounds (uuid, title, lat, long, mp3url)
            VALUES ("2", "Second Song", 22.2, 2.22, "http://foo/bar");'
        );
        $this->app['db']->query(
            'INSERT INTO sounds (uuid, title, lat, long, mp3url)
            VALUES ("3", "Third Song", NULL, NULL, "http://foo/bar");'
        );
        $client = $this->createClient();
        $client->request('GET', '/api/soundLocations');
        $content = $client->getResponse()->getContent();
        $this->assertEquals(
            '[{"title":"First Song","lat":11.1,"long":1.11},{"title":"Second Song","lat":22.2,"long":2.22}]',
            $content
        );
    }
}
