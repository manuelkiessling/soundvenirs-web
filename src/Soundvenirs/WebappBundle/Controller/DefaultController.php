<?php

namespace Soundvenirs\WebappBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SoundvenirsWebappBundle:Default:index.html.twig', array('name' => $name));
    }
}
