<?php

require_once __DIR__ . "/../../autoload.php";
require_once  __DIR__ . '/../utils/Qiniu/functions.php';
require_once  __DIR__ . '/../utils/Qiniu/Http/Middleware/Middleware.php';

use App\Utils\InputValidator;

/**
 * 这个页面一共有2个功能
 * 1. 保存博客内容到 md 文件
 * 2. 保存博客其他信息到缓存文件 以方便后搜索分类用
 */

// 获取表单提交的数据
$blogTitle = InputValidator::getSafeInput($_POST['blog_title']);
$blogTags = InputValidator::getSafeInput($_POST['blog_tags']);
$blogCategory = InputValidator::getSafeInput($_POST['blog_category']);
$blogContent = InputValidator::getInput($_POST['blog_content']);
$blogSubtitle = InputValidator::getSafeInput($_POST['blog_subtitle']);
$blogPath = InputValidator::getSafeInput($_POST['blog_path']);
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


// 如果$fileName已存在, 则取出数据 合并数组 以新数组为准 在保存
if (file_exists(PROJECT_ROOT . '/app/blogs/' . $fileName)) {
    $oldBlog = json_decode(file_get_contents(PROJECT_ROOT . '/app/blogs/' . $fileName), true);
    $saveData = array_merge($oldBlog, $saveData);
}

if (!empty($blogAttachment['tmp_name'])) {
    $uploadedFileUrl = uploadToQiniu($blogAttachment['tmp_name'], $blogAttachment['name']);
    $saveData['attachment'] = $uploadedFileUrl;
} else if (isset($oldBlog['attachment']) && is_string($oldBlog['attachment'])) {
    $saveData['attachment'] = $oldBlog['attachment'];
}

// 保存博客内容到 Markdown 文件
file_put_contents(PROJECT_ROOT . '/app/blogs/' . $fileName, json_encode(
    $saveData,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
));

// 输出成功信息
showMessage();

function uploadToQiniu($filePath, $fileName)
{
    // 这里需要您根据七牛云的API文档来实现文件上传逻辑
    $settings = getBlogsConfig();
    $accessKey = $settings['qiniu_access_key'];
    $secretKey = $settings['qiniu_secret_key'];
    $bucket = $settings['qiniu_bucket'];
    $auth = new \Qiniu\Auth($accessKey, $secretKey);
    $token = $auth->uploadToken($bucket);
    $uploadManager = new \Qiniu\Storage\UploadManager();
    list($ret, $err) = $uploadManager->putFile($token, "mapleBridge/" . time() . "-" . $fileName, $filePath);
    if ($err !== null) {
        //echo ("上传失败: " . $err->message());
    } else {
        return $settings["qiniu_domain"] . "/" . $ret['key'];
    }
}
