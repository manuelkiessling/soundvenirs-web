<?php

namespace Soundvenirs\HomepageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SoundvenirsHomepageBundle:Default:index.html.twig', array('name' => $name));
    }
}
