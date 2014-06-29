<?php

namespace Soundvenirs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SoundsController extends Controller
{
    public function retrieveOneAction($id)
    {
        $repo = $this->get('soundvenirs_domain.sound_repository');
        $sound = $repo->find($id);

        if ($sound === null) {
            return new JsonResponse(null, 404);
        }

        $responseSound = array();
        $responseSound['id'] = $sound->id;
        $responseSound['title'] = $sound->title;
        $responseSound['mp3url'] = 'http://www.soundvenirs.com/download/' . $sound->id . '.mp3';
        $responseSound['location'] = array(
            'lat' => $sound->lat,
            'long' => $sound->long
        );
        return new JsonResponse($responseSound);
    }

    public function createAction()
    {
        $content = $this->get('request')->getContent();
        $params = json_decode($content, true);
        $title = $params['title'];

        $soundRepo = $this->get('soundvenirs_domain.sound_repository');
        $sound = $soundRepo->create($title);
        $soundRepo->persist($sound);

        return new JsonResponse(array('id' => $sound->id));
    }

    public function updateAction($id)
    {
        $repo = $this->get('soundvenirs_domain.sound_repository');
        $sound = $repo->find($id);

        if ($sound === null) {
            return new JsonResponse(null, 404);
        }

        if ($sound->lat !== null) {
            return new JsonResponse(array('status' => false));
        }

        $content = $this->get('request')->getContent();
        $params = json_decode($content, true);
        $lat = $params['lat'];
        $long = $params['long'];

        $sound->lat = (float)$lat;
        $sound->long = (float)$long;

        $repo->persist($sound);

        return new JsonResponse(array('status' => true));
    }
}
