<?php

require_once "../../autoload.php";

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
define('PROJECT_ROOT', getProjectRoot());
define('BASE_PATH', getBasePath());


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

// 保存博客内容到 Markdown 文件
file_put_contents(PROJECT_ROOT . '/app/blogs/' . $fileName, json_encode(
    [
        'title' => $blogTitle,
        'tags' => $blogTags,
        'category' => $blogCategory,
        'content' => $blogContent,
        'subtitle' => $blogSubtitle,
        'attachment' => $blogAttachment,
        'path' => $fileName,
        'date' => date('Y-m-d H:i:s')
    ],
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
));

// 输出成功信息
showMessage();