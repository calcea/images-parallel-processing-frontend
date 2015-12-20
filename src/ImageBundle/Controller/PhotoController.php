<?php

namespace ImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ImageBundle\Form\ImageUploadForm;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoController extends Controller
{
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(new ImageUploadForm());
        $form->add('submit','submit');

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UploadedFile
             */
            $uploadedFile = $form->getData()['imageUpload'];

            dump($uploadedFile);die;
        }

        return $this->render('ImageBundle:Default:index.html.twig',array('form' => $form->createView()));
    }
}
