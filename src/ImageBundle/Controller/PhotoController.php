<?php

namespace ImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ImageBundle\Form\ImageUploadForm;
use Cloud\AmazonBundle\Services\S3;
use Cloud\AmazonBundle\Services\Queue;
use Cloud\AmazonBundle\Services\Dynamo;

class PhotoController extends Controller
{
    /**
     * 8-_-kjabsjdjahjsdbjabshd
     * $userId . self::SEPARATOR . $photoId
     */
    const SEPARATOR = "-_-";
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(new ImageUploadForm());
        $form->add('submit','submit');

        $form->handleRequest($request);
        if($form->isValid()){
            $id = md5(rand(999, 10000) . microtime() . "12345678910");
            /**
             * @var UploadedFile
             */
            $uploadedFile = $form->getData()['imageUpload'];
            $s3 = new S3();
            $uploadedFile->move("./", $id . "." . $uploadedFile->getClientOriginalExtension());
            $s3->uploadPhoto($uploadedFile->getRealPath(),12);


        }

        return $this->render('ImageBundle:Default:index.html.twig',array('form' => $form->createView()));
    }
}
