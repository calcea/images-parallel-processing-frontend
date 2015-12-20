<?php
namespace Cloud\AmazonBundle\Services;

use Aws\S3\S3Client;

class S3{

    const BUCKET_NAME = 'image.processing.s3';

    protected $client;

    public function __construct()
    {
        $this->client = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => 'AKIAIBAN56SSVUGHIE7A',
                    'secret' => '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',
                ),
                'region' => 'us-east-1',
                'version' => 'latest',
                'scheme' => 'http'
            ));
    }

    public function uploadPhoto($path = null,$filename )
    {
        $result = $this->client->putObject(array(
            'Bucket'     => self::BUCKET_NAME,
            'Key'        => $filename,
            'SourceFile' => 'C:\Users\Naty\Downloads\photo.jpg',
            'ACL'          => 'public-read',
            'Metadata'   => array(
                'Foo' => 'abc',
                'Baz' => '123'
            )
        ));
        dump($result);
        return $result;
    }

    public function deletePhoto($fileName){
        $result = $this->client->deleteObject(array(
            'Bucket' => self::BUCKET_NAME,
            'Key' => $fileName
        ));
        dump($result);
        return $result;
    }

    public function getPhoto($fileName){
       $result = $this->client->getObject(array(
            'Bucket' => self::BUCKET_NAME,
            'Key' => $fileName
        ));
        dump($result);
        return $result;
    }
}

?>