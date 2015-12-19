<?php

namespace Cloud\AmazonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cloud\AmazonBundle\Services;
class DefaultController extends Controller
{
    public function indexAction()
    {
        $obj = new Services\Queue();
        $obj->sendMessage("AWS");
        $obj->getMessage();
        return $this->render('CloudAmazonBundle:Default:index.html.twig');
    }
}
