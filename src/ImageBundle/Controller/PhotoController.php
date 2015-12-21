<?php

namespace ImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ImageBundle\Form\ImageUploadForm;
use Cloud\AmazonBundle\Services\S3;
use Cloud\AmazonBundle\Services\Queue;
use Cloud\AmazonBundle\Services\Dynamo;
use Cloud\AmazonBundle\Entity\Dynamo\PhotoItemBuilder;

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
            $photoId = md5(rand(999, 10000) . microtime() . "12345678910");
            $userId = 8;
            $uploadedFile = $form->getData()['imageUpload'];

            $s3Url = $this->addToS3($uploadedFile,$photoId);
            $this->addToQueue($userId,$photoId);
            $this->addToDynamo($uploadedFile,$userId,$photoId,$s3Url);

        }

        return $this->render('ImageBundle:Default:index.html.twig',array('form' => $form->createView()));
    }

    /**
     * This function add to s3 photo and return s3 url for photo.
     *
     * @param UploadedFile $uploadedFile
     * @param $photoId
     * @return string
     */
    protected function addToS3(UploadedFile $uploadedFile, $photoId){
        $s3 = new S3();

        $uploadedFile->move("./photo", $photoId . "." . $uploadedFile->getClientOriginalExtension());
        $photoPath = "./photo/".$photoId.".".$uploadedFile->getClientOriginalExtension();
        $s3Url = $s3->uploadPhoto($photoPath,$photoId);
        //TODO: unlink photo from local
        return $s3Url;
    }

    /**
     * Add to queue message for photo.
     *
     * @param $userId
     * @param $photoId
     */
    protected function addToQueue($userId,$photoId){
        $queue = new Queue();
        $queue->sendMessage($userId.self::SEPARATOR.$photoId);
    }

    /**
     * Add to dynamo photo.
     *
     * @param UploadedFile $uploadedFile
     * @param $userId
     * @param $photoId
     * @param $s3Url
     */
    protected function addToDynamo(UploadedFile $uploadedFile,$userId,$photoId,$s3Url){
        $photoItem = array (
            'user_id' => (string) $userId,
            'photo_id' => (string) $photoId,
            'path_to_s3' => (string) $s3Url,
            'filename' => (string) ($photoId . "." . $uploadedFile->getClientOriginalExtension()),
        );

        $dynamo = new Dynamo(new PhotoItemBuilder(),'ImageProcessingDB');
        $dynamo->addItem($photoItem);
    }
}
