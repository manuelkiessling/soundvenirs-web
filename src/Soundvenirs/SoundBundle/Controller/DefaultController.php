<?php

namespace Soundvenirs\SoundBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SoundvenirsSoundBundle:Default:index.html.twig', array('name' => $name));
    }
}
