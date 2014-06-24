<?php

namespace Soundvenirs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SoundsController extends Controller
{
    public function retrieveOneAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SoundvenirsSoundBundle:Sound');
        $sound = $repo->find($id);

        $responseSound = [];
        $responseSound['id'] = $sound->id;
        $responseSound['title'] = $sound->title;
        $responseSound['mp3url'] = 'http://www.soundvenirs.com/download/'.$sound->id.'.mp3';
        $responseSound['location'] = [
            'lat' => $sound->lat,
            'long' => $sound->long
        ];
        return new JsonResponse($responseSound);
    }
}
