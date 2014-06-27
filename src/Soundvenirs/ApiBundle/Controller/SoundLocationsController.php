<?php

namespace Soundvenirs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Soundvenirs\DomainBundle\Factory;

class SoundLocationsController extends Controller
{
    public function retrieveAllAction()
    {
        $repo = $this->get('soundvenirs_domain.sound_repository');

        $query = $repo->createQueryBuilder('s')
            ->where('s.lat IS NOT NULL AND s.long IS NOT NULL')
            ->getQuery();

        $sounds = $query->getResult();

        $soundLocations = array();
        foreach ($sounds as $sound) {
            $soundLocation = array();
            $soundLocation['title'] = $sound->title;
            $soundLocation['location'] = array(
                'lat' => $sound->lat,
                'long' => $sound->long
            );
            $soundLocations[] = $soundLocation;
        }

        return new JsonResponse($soundLocations);
    }
}
