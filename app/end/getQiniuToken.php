<?php

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('code' => 401, 'msg' => '请先登录'));
    exit();
}

$accessKey = '79OD2IOqm6PCJsrhbfzWlJUP2ol3m6TZCtdcAR7X';
$secretKey = 'Yyr3kyBUk88mbG5CgVv2P6Pyz5Xwi9Vl3GjciUQL';
$bucket = 'file-static';

require_once  __DIR__ . '/../utils/Qiniu/functions.php';
require_once  __DIR__ . '/../utils/Qiniu/Http/Middleware/Middleware.php';

$auth = new \Qiniu\Auth($accessKey, $secretKey);
$token = $auth->uploadToken($bucket);

echo json_encode(array('code' => 200,'msg' => '获取成功', 'token' => $token));