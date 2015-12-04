<?php
error_reporting(E_ALL & ~ E_NOTICE); 
require_once 'vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

$accessKey = $_GET['ak'];
$secretKey = $_GET['sk'];

$auth = new Auth($accessKey, $secretKey);

$bucket = $_GET['bucket'];

$prefix = $_GET['prefix'];

$opts = array(
	        'saveKey'    => $prefix . '/$(fname)'
            );

$token = $auth->uploadToken($bucket, null, 3600, $opts);

$tokenobj = array('uptoken' => $token);
echo json_encode($tokenobj);
?>