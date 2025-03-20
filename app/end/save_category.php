<?php

!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

use App\Utils\InputValidator;

// 获取表单提交的分类名称
$categoryName = InputValidator::getSafeInput($_POST['category_name']);

// 保存分类信息到文件或数据库
$blogsDir = PROJECT_ROOT . '/app/blogs';
$categoriesFile = $blogsDir . '/categories.php';

// 确保目录存在
if (!is_dir($blogsDir)) {
    mkdir($blogsDir, 0755, true);
}

if (!file_exists($categoriesFile)) {
    $categories = [];
} else {
    $categories = require $categoriesFile;
    if (!is_array($categories)) {
        $categories = [];
    }
}

// 合并数组 并去重
$categories = array_unique(array_merge($categories, [$categoryName]));

// 写入文件
$success = file_put_contents($categoriesFile, '<?php return ' . var_export($categories, true) . ';');

if ($success === false) {
    die("保存分类失败，请检查目录权限！");
}

// 输出成功信息并跳转
header("Location: " . BASE_PATH . "/app/end/category.php");
exit("分类保存成功！");
