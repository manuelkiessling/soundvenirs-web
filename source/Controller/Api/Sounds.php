<?php

namespace Soundvenirs\Controller\Api;

use Doctrine\DBAL\Connection;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Sounds
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function createAction(Request $request, Application $app)
    {
        $uuid = \createSound($app, $request->get('title'));
        return new JsonResponse(array('uuid' => $uuid));
    }
}
