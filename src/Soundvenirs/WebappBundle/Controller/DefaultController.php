<?php

namespace Soundvenirs\WebappBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SoundvenirsWebappBundle:Default:index.html.twig');
    }

    public function appconfigAction()
    {
        return $this->render('SoundvenirsWebappBundle:Default:appconfig.js.twig');
    }
}
