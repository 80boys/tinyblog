<?php

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('code' => 401, 'msg' => '请先登录', 'token' => ''));
    exit();
}

$settings = getBlogsConfig();
$accessKey = $settings['qiniu_access_key'];
$secretKey = $settings['qiniu_secret_key'];
$bucket = $settings['qiniu_bucket'];

require_once  __DIR__ . '/../utils/Qiniu/functions.php';
require_once  __DIR__ . '/../utils/Qiniu/Http/Middleware/Middleware.php';

$auth = new \Qiniu\Auth($accessKey, $secretKey);
$token = $auth->uploadToken($bucket);

echo json_encode(array('code' => 200,'msg' => '获取成功', 'token' => $token));