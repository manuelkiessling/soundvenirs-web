<?php

namespace Soundvenirs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SoundLocationsController extends Controller
{
    public function retrieveAllAction()
    {
        $repo = $this->get('soundvenirs_domain.sound_repository');
        $sounds = $repo->getConsumableSounds();

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
