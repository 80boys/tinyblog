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

// 生成博客存储路径
$year = date('Y');
$month = date('m');
$fileName = empty($blogPath) ? date('YmdHis') . '.php' : $blogPath;

// 如果是新文件，使用年月目录结构
if (empty($blogPath)) {
    $fileName = $year . '/' . $month . '/' . $fileName;
}

// 确保目录存在
$dirPath = PROJECT_ROOT . '/app/blogs/' . dirname($fileName);
if (!is_dir($dirPath)) {
    mkdir($dirPath, 0755, true);
}

$saveData = [
    'title' => $blogTitle,
    'tags' => array_map('trim', explode(',', $blogTags)), // 将标签字符串转换为数组并去除空格
    'category' => $blogCategory,
    'content' => $blogContent,
    'subtitle' => $blogSubtitle,
    'attachment' => "",
    'path' => $fileName,
    'date' => date('Y-m-d H:i:s')
];

// 如果是更新现有文件，合并旧数据
if (file_exists(PROJECT_ROOT . '/app/blogs/' . $fileName)) {
    $oldBlog = require(PROJECT_ROOT . '/app/blogs/' . $fileName);
    $saveData = array_merge($oldBlog, $saveData);
}

// 处理附件上传
if (!empty($blogAttachment['tmp_name'])) {
    $uploadedFileUrl = uploadToQiniu($blogAttachment['tmp_name'], $blogAttachment['name']);
    $saveData['attachment'] = $uploadedFileUrl;
} else if (isset($oldBlog['attachment']) && is_string($oldBlog['attachment'])) {
    $saveData['attachment'] = $oldBlog['attachment'];
}

// 生成PHP文件内容
$phpContent = "<?php\nreturn " . var_export($saveData, true) . ";\n";

// 保存博客内容
file_put_contents(PROJECT_ROOT . '/app/blogs/' . $fileName, $phpContent);

// 更新缓存
updateBlogCache($saveData, $fileName);

// 输出成功信息
showMessage();

/**
 * 更新博客缓存
 * @param array $blogData 博客数据
 * @param string $filePath 文件路径
 */
function updateBlogCache($blogData, $filePath)
{
    $cachePath = PROJECT_ROOT . '/app/blogs/caches.php';

    // 读取现有缓存
    $caches = file_exists($cachePath) ? require($cachePath) : [
        'last_update' => '',
        'total_blogs' => 0,
        'categories' => [],
        'archives' => [],
        'tags' => [],
        'blogs' => []
    ];

    // 更新最后更新时间
    $caches['last_update'] = date('Y-m-d H:i:s');

    // 更新博客索引
    $caches['blogs'][$filePath] = [
        'title' => $blogData['title'],
        'subtitle' => $blogData['subtitle'],
        'category' => $blogData['category'],
        'tags' => $blogData['tags'],
        'date' => $blogData['date'],
        'path' => $filePath
    ];

    // 更新分类索引
    if (!isset($caches['categories'][$blogData['category']])) {
        $caches['categories'][$blogData['category']] = ['count' => 0, 'blogs' => []];
    }
    $caches['categories'][$blogData['category']]['blogs'][] = $filePath;
    $caches['categories'][$blogData['category']]['count'] =
        count(array_unique($caches['categories'][$blogData['category']]['blogs']));

    // 更新标签索引
    foreach ($blogData['tags'] as $tag) {
        if (!isset($caches['tags'][$tag])) {
            $caches['tags'][$tag] = ['count' => 0, 'blogs' => []];
        }
        $caches['tags'][$tag]['blogs'][] = $filePath;
        $caches['tags'][$tag]['count'] =
            count(array_unique($caches['tags'][$tag]['blogs']));
    }

    // 更新归档索引
    $year = date('Y', strtotime($blogData['date']));
    $month = date('m', strtotime($blogData['date']));
    if (!isset($caches['archives'][$year])) {
        $caches['archives'][$year] = [];
    }
    if (!isset($caches['archives'][$year][$month])) {
        $caches['archives'][$year][$month] = ['count' => 0, 'blogs' => []];
    }
    $caches['archives'][$year][$month]['blogs'][] = $filePath;
    $caches['archives'][$year][$month]['count'] =
        count(array_unique($caches['archives'][$year][$month]['blogs']));

    // 更新博客总数
    $caches['total_blogs'] = count($caches['blogs']);

    // 保存缓存
    $cacheContent = "<?php\nreturn " . var_export($caches, true) . ";\n";
    file_put_contents($cachePath, $cacheContent);
}

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
