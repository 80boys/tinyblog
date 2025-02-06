<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

// 获取表单提交的数据
use App\Utils\InputValidator;

$websiteName = InputValidator::getSafeInput($_POST['website_name']);
$beianNumber = InputValidator::getSafeInput($_POST['beian_number']);
$contactEmail = InputValidator::getSafeInput($_POST['contact_email']);
$wechatId = InputValidator::getSafeInput($_POST['wechat_id']);
$qiniuAccessKey = InputValidator::getSafeInput($_POST['qiniu_access_key']);
$qiniuSecretKey = InputValidator::getSafeInput($_POST['qiniu_secret_key']);
$qiniuBucket = InputValidator::getSafeInput($_POST['qiniu_bucket']);
$qiniuDomain = InputValidator::getSafeInput($_POST['qiniu_domain']);
$qiniuAccelerateDomain = InputValidator::getSafeInput($_POST['qiniu_accelerate_domain']);

// 构建设置数组
$settings = [
    'website_name' => $websiteName,
    'beian_number' => $beianNumber,
    'contact_email' => $contactEmail,
    'wechat_id' => $wechatId,
    'qiniu_access_key' => $qiniuAccessKey,
    'qiniu_secret_key' => $qiniuSecretKey,
    'qiniu_bucket' => $qiniuBucket,
    'qiniu_domain' => $qiniuDomain,
    'qiniu_accelerate_domain' => $qiniuAccelerateDomain,
];

// 将设置数组保存到 PHP 文件
$settingsFile = PROJECT_ROOT . '/app/blogs/settings.php';
file_put_contents($settingsFile, '<?php return ' . var_export($settings, true) . ';');

// 输出成功信息
showMessage("设置保存成功", isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/app/end/settings.php');
