<?php

namespace Soundvenirs\Controller;

use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Homepage
{
    protected $twigEnvironment;
    protected $formFactory;

    public function __construct(\Twig_Environment $twigEnvironment, FormFactory $formFactory)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->formFactory = $formFactory;
    }

    public function indexAction()
    {
        $form = $this->formFactory->createBuilder('form')
            ->add('title', 'text')
            ->add('soundfile', 'file', array('attr' => array('onchange' => 'this.form.submit()')))
            ->getForm();
        return $this->twigEnvironment->render('index.twig', array('form' => $form->createView()));
    }

    public function uploadAction(Request $request, Application $app)
    {
        $form = $this->formFactory->createBuilder('form')
            ->add('title', 'text')
            ->add('soundfile', 'file')
            ->getForm();
        $form->submit($request);
        $files = $request->files->get($form->getName());
        $soundfile = $files['soundfile'];
        $data = $form->getData();
        $title = $data['title'];
        $extension = pathinfo($soundfile->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($extension !== 'mp3') {
            return new Response('Only mp3 files are allowed.', 500);
        }
        if (is_null($title)) {
            $title = \basename($soundfile->getClientOriginalName(), '.mp3');
        }
        $uuid = \createSound($app, $title);
        $soundfile->move('/var/tmp/', 'soundvenirs-'.$uuid.'.mp3');
        return $this->twigEnvironment->render('qrcode.twig', array('uuid' => $uuid));
    }

    public function downloadAction($uuid)
    {
        return new Response(
            file_get_contents('/var/tmp/soundvenirs-'.$uuid.'.mp3'),
            200,
            array('Content-Type' => 'audio/mpeg')
        );
    }

    public function qrcodeAction($uuid)
    {
        require_once __DIR__.'/../../vendor/t0k4rt/phpqrcode/qrlib.php';
        $image = \QRcode::png('http://sndvnrs.com/s/'.$uuid);
        return new Response($image, 200, array('Content-Type' => 'image/png'));
    }
}
