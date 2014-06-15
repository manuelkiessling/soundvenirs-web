<?php

function createSound(Silex\Application $app, $title)
{
    $uuid = sha1(uniqid('', true));
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
