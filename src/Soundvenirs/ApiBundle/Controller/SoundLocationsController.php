<?php

namespace Soundvenirs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SoundLocationsController extends Controller
{
    public function retrieveAllAction()
    {
        $repo = $this->get('soundvenirs_domain.soundlocation_repository');
        $soundLocations = $repo->getAll();

        $responseSoundLocations = array();
        foreach ($soundLocations as $soundLocation) {
            $responseSoundLocation = array();
            $responseSoundLocation['title'] = $soundLocation->title;
            $responseSoundLocation['lat'] = $soundLocation->lat;
            $responseSoundLocation['long'] = $soundLocation->long;
            $responseSoundLocations[] = $responseSoundLocation;
        }

        return new JsonResponse($responseSoundLocations);
    }
}
