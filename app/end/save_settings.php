<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

// 获取表单提交的数据
$websiteName = $_POST['website_name'];
$beianNumber = $_POST['beian_number'];
$contactEmail = $_POST['contact_email'];
$wechatId = $_POST['wechat_id'];

// 构建设置数组
$settings = [
    'website_name' => $websiteName,
    'beian_number' => $beianNumber,
    'contact_email' => $contactEmail,
    'wechat_id' => $wechatId,
];

// 将设置数组保存到 JSON 文件
$settingsFile = PROJECT_ROOT . '/app/blogs/settings.data';
file_put_contents($settingsFile, json_encode($settings, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

// 输出成功信息
showMessage("设置保存成功", isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/app/end/settings.php');
