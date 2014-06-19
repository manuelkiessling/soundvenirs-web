<?php

namespace Soundvenirs\Controller\Api;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;

class Soundlocations
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getAction()
    {
        $rows = $this->db->fetchAll('SELECT title, lat, long FROM sounds WHERE lat IS NOT NULL;');
        $soundLocations = array();
        foreach ($rows as $row) {
            $row['location'] = array(
                'lat' => (float)$row['lat'],
                'long' => (float)$row['long'],
            );
            unset($row['lat']);
            unset($row['long']);
            $soundLocations[] = $row;
        }
        return new JsonResponse($soundLocations);
    }
}
