<?php

/**
 * Class S3Component
 *
 * S3Component is a wrapper for S3.php class by Donovan Schönknecht (@link https://github.com/tpyo/amazon-s3-php-class)
 * This wrapper contains upload and remove file possibilities
 *
 * @version 0.1
 *
 * @link https://github.com/stalavitski/yii-s3
 * @uses CFile
 * @author Artsem Stalavistki (a.stalavitski@gmail.com)
 */
class S3Component extends CApplicationComponent
{
    /**
     * @var string AWS Access key
     */
    public $accessKey;

    /**
     * @var string AWS Secret key
     */
    public $secretKey;

    /**
     * @var string AWS bucket name
     */
    public $bucket;

    /**
     * @var string
     */
    public $lastError;

    /**
     * @var S3
     */
    private $_s3;

    /**
     * Get a list of buckets
     * @return array|false
     */
    public function getBuckets()
    {
        return $this->getInstance()->listBuckets();
    }

    /**
     * @param string $file Source of file to upload (valid CFile filename)
     * @param string $uri file URI (can include directory separators)
     * @param string $bucket Bucket name
     * @return bool
     * @throws CException
     */
    public function upload($file, $uri, $bucket = '')
    {
        $bucket = $this->getBucket($bucket);
        $fileValidator = Yii::app()->file->set($file);

        if (!$fileValidator->exists) {
            throw new CException('Origin file not found');
        }

        if (!$fileValidator->size) {
            $this->lastError = 'Attempted to upload empty file.';
            return false;
        }

        if (!$this->getInstance()->putObjectFile($file, $bucket, $uri, S3::ACL_PUBLIC_READ)) {
            $this->lastError = 'Unable to upload file.';
            return false;
        }

        return true;
    }

    /**
     * @param string $uri file URI (can include directory separators)
     * @param string $bucket Bucket name
     * @return bool
     */
    public function remove($uri, $bucket = '')
    {
        $bucket = $this->getBucket($bucket);
        return $this->getInstance()->deleteObject($bucket, $uri);
    }

    /**
     * Setting S3 Object
     */
    private function connect()
    {
        if (! $this->accessKey || ! $this->secretKey) {
            throw new CException('S3 Keys are not set.');
        }

        $this->_s3 = new S3($this->accessKey, $this->secretKey);
    }

    /**
     * @return S3
     * @throws CException
     */
    private function getInstance()
    {
        if (! $this->_s3) {
            $this->connect();
        }

        return $this->_s3;
    }

    /**
     * @param string $bucket
     * @return string
     * @throws CException
     */
    private function getBucket($bucket = '') {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        if(! $bucket) {
            throw new CException('Bucket should be set');
        }

        return $bucket;
    }
}