<?php
// 获取网站配置
$settings = getBlogsConfig();
$siteName = $settings['site_name'];
$siteDescription = isset($settings['site_description']) ? $settings['site_description'] : '分享技术与生活';

// 如果页面没有设置标题，使用默认标题
if (!isset($title)) {
    $title = $siteName;
}

// 如果页面没有设置描述，使用默认描述
if (!isset($description)) {
    $description = $siteDescription;
}

// 如果页面没有设置关键词，使用默认关键词
if (!isset($keywords)) {
    $keywords = isset($settings['default_keywords']) ? $settings['default_keywords'] : '博客,技术,生活';
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo BASE_PATH; ?>/app/favicon.ico">
    <link rel="Bookmark" href="<?php echo BASE_PATH; ?>/app/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>枫桥驿站</title>
    <link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/simplemde.min.css">
    <link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/main.css">
</head>

<body>