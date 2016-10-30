# S3 Extension for Yii

This extension provides few functions for working with Amazon S3 storage. Currently possible actions: upload remove, get bucket names list.

# Installation

Copy 'yii-s3' folder to 'extensions' (protected/extensions) folder. Add settings block to config file under components section. More about how to get required data you can read from [here](http://www.bucketexplorer.com/documentation/amazon-s3--what-is-my-aws-access-and-secret-key.html).

```php
'components' => array(
...
    's3' => array(
        'class' => 'application.extensions.s3.S3Component',//path to S3Component class from extension
        'accessKey' => 'example-access-key',//access key to S3 storage
        'secretKey' => 'example-secret-key',//secret key to S3 storage
        'bucket' => 'example-bucket-name'//name of bucket from S3 storage. not required
    ),
...
```

# Usage

##### Get names of the buckets:
```php
Yii::app()->s3->getBuckets();//return array of bucket names
```

##### Upload file:
```php
Yii::app()->s3->upload('path/to/file/file-name.ext', 'folder/structure/at/bucket/file-name.ext');
//return bool - upload success
```

Example (save 'image.png' file to S3 bucket under 'images' folder)

```php
$imageName = 'image.png';
$filePath = $webRootPath . DIRECTORY_SEPARATOR . imageName;
$bucketPath = 'images/' . imageName;

if(Yii::app()->s3->upload($filePath, $bucketPath)) {
    var_dump('Uploaded successfully :)');
} else {
    var_dump('Not uploaded :(');
}

```

##### Remove file:
```php
Yii::app()->s3->remove('folder/structure/at/bucket/file-name.ext');//return bool - remove success
```

Example (remove 'image.png' file from S3 bucket under 'images' folder)


```php
$bucketPath = 'images/image.png';

if(Yii::app()->s3->remove($bucketPath)) {
    var_dump('Removed successfully :)');
} else {
    var_dump('Not removed :(');
}

```
