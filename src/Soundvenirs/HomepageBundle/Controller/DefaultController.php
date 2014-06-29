<?php

namespace Soundvenirs\HomepageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $form = $this->createForm('form')
            ->add('title', 'text')
            ->add('soundfile', 'file', array('attr' => array('onchange' => 'this.form.submit()')));

        return $this->render('SoundvenirsHomepageBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

    public function uploadAction(Request $request)
    {
        $form = $this->createForm('form')
            ->add('title', 'text')
            ->add('soundfile', 'file');
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
        $soundRepository = $this->get('soundvenirs_domain.sound_repository');

        $sound = $soundRepository->create($title);
        $soundRepository->persist($sound);

        $soundfile->move($this->container->getParameter('soundvenirs_homepage.soundfiles_path'), $sound->id . '.mp3');
        return $this->render('SoundvenirsHomepageBundle:Default:qrcode.html.twig', array('id' => $sound->id));
    }

    public function qrcodeAction($id)
    {
        require_once __DIR__.'/../../../../vendor/t0k4rt/phpqrcode/qrlib.php';
        $image = \QRcode::png('http://sndvnr.com/s/'.$id);
        return new Response($image, 200, array('Content-Type' => 'image/png'));
    }

    public function downloadAction($id)
    {
        $valid = preg_match('/^[0-9a-z]{1,6}$/', $id);
        if ($valid === 1) {
            $filepath = $this->container->getParameter('soundvenirs_homepage.soundfiles_path') . $id . '.mp3';
            if (file_exists($filepath)) {
                return new Response(
                    file_get_contents($filepath),
                    200,
                    array('Content-Type' => 'audio/mpeg')
                );
            } else {
                return new Response('No such sound', 404);
            }
        } else {
            return new Response('Bad request', 400);
        }
    }
}
