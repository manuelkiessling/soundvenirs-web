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

    public function getOneAction($uuid)
    {
        $row = $this->db->fetchAssoc('SELECT * FROM sounds WHERE uuid = ?;', array($uuid));
        $row['location'] = array(
            'lat' => (float)$row['lat'],
            'long' => (float)$row['long'],
        );
        unset($row['lat']);
        unset($row['long']);
        return new JsonResponse($row);
    }

    public function createAction(Request $request, Application $app)
    {
        $uuid = \createSound($app, $request->get('title'));
        return new JsonResponse(array('uuid' => $uuid));
    }

    public function updateAction(Request $request, $uuid)
    {
        $row = $this->db->fetchAssoc('SELECT lat FROM sounds WHERE uuid = ?;', array($uuid));
        if ($row['lat'] != null) {
            return new JsonResponse(array('status' => false));
        }
        $this->db->update(
            'sounds',
            array(
                'lat' => $request->get('lat'),
                'long' => $request->get('long')
            ),
            array('uuid' => $uuid)
        );
        return new JsonResponse(array('status' => true));
    }
}
