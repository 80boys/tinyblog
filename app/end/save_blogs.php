<?php

require_once __DIR__ . "/../../autoload.php";
require_once  __DIR__ . '/../utils/Qiniu/functions.php';
require_once  __DIR__ . '/../utils/Qiniu/Http/Middleware/Middleware.php';


/**
 * 这个页面一共有2个功能
 * 1. 保存博客内容到 md 文件
 * 2. 保存博客其他信息到缓存文件 以方便后搜索分类用
 */

// 获取表单提交的数据
$blogTitle = $_POST['blog_title'];
$blogTags = $_POST['blog_tags'];
$blogCategory = $_POST['blog_category'];
$blogContent = $_POST['blog_content'];
$blogSubtitle = $_POST['blog_subtitle'];
$blogPath = $_POST['blog_path'];
$blogAttachment = $_FILES['blog_attachment'];

// 生成 Markdown 文件的文件名
$fileName =  empty($blogPath) ? date('YmdHis') . '.json' : $blogPath;

$saveData = [
    'title' => $blogTitle,
    'tags' => $blogTags,
    'category' => $blogCategory,
    'content' => $blogContent,
    'subtitle' => $blogSubtitle,
    'attachment' => "",
    'path' => $fileName,
    'date' => date('Y-m-d H:i:s')
];

if (!empty($blogAttachment['tmp_name'])) {
    $uploadedFileUrl = uploadToQiniu($blogAttachment['tmp_name'], $blogAttachment['name']);
    $saveData['attachment'] = $uploadedFileUrl;
}

// 如果$fileName已存在, 则取出数据 合并数组 以新数组为准 在保存
if (file_exists(PROJECT_ROOT. '/app/blogs/'. $fileName)) {
    $blog = json_decode(file_get_contents(PROJECT_ROOT. '/app/blogs/'. $fileName), true);
    $saveData = array_merge($blog, $saveData);
}

// 保存博客内容到 Markdown 文件
file_put_contents(PROJECT_ROOT . '/app/blogs/' . $fileName, json_encode($saveData,
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
));

// 输出成功信息
showMessage();

function uploadToQiniu($filePath, $fileName) {
    // 这里需要您根据七牛云的API文档来实现文件上传逻辑
    // 示例代码如下：
    $accessKey = '79OD2IOqm6PCJsrhbfzWlJUP2ol3m6TZCtdcAR7X';
    $secretKey = 'Yyr3kyBUk88mbG5CgVv2P6Pyz5Xwi9Vl3GjciUQL';
    $bucket = 'file-static';
    $auth = new \Qiniu\Auth($accessKey, $secretKey);
    $token = $auth->uploadToken($bucket);
    $uploadManager = new \Qiniu\Storage\UploadManager();
    list($ret, $err) = $uploadManager->putFile($token, "mapleBridge/" . time() . "-" . $fileName, $filePath);
    if ($err !== null) {
        //echo ("上传失败: " . $err->message());
    } else {
        return "http://cdn.maplebridge.net/" . $ret['key'];
    }
}