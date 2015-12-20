<?php

namespace Cloud\AmazonBundle\Entity\Dynamo;
/**
 * Created by PhpStorm.
 * User: george
 * Date: 12/19/2015
 * Time: 3:27 PM
 */
class PhotoItemBuilder extends DataBuilderAbstract
{
    protected $itemMappings = array(
        'user_id' => array("dynamoName" => 'UserID', 'type' => 'S'),
        'photo_id' => array("dynamoName" => 'PhotoID', 'type' => 'S'),
        'path_to_s3' => array("dynamoName" => 'PathToS3', 'type' => 'S', 'default' => 'null'), //the null value is a string and it is !== NULL
        'filename' => array("dynamoName" => 'Filename', 'type' => 'S', 'default' => 'null'),
        'status' => array("dynamoName" => 'Status', 'type' => 'S', 'default' => '1'),
        'parent' => array("dynamoName" => 'Parent', 'type' => 'S', 'default' => '0'),
        'added' => array("dynamoName" => 'Added', 'type' => 'S', 'default' => 'null'),
    );

    protected $columnsTypes = array(
        'UserID' => 'S',
        'PhotoID' => 'S',
        'PathToS3' => 'S',
        'Filename' => 'S',
        'Status' => 'S',
        'Parent' => 'S',
        'Added' => 'S',
    );

    public function build($data)
    {
        $dateObj = new \DateTime();
        $data['added'] = $dateObj->format('Y-m-d H:i:s');
        return $this->mapData($data);
    }

    public function getColumnsTypes()
    {
        return $this->columnsTypes;
    }

}