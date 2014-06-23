<?php

function createSound(Silex\Application $app, $title)
{
    $idGenerator = new Soundvenirs\Helper\IdGenerator($app['db']);
    $uuid = $idGenerator->generate();
    $app['db']->insert(
        'sounds',
        array(
            'uuid' => $uuid,
            'title' => $title,
            'mp3url' => 'http://www.soundvenirs.com/download/'.$uuid.'.mp3',
        )
    );
    return $uuid;
}
