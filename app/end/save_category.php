<?php

!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

use App\Utils\InputValidator;

// 获取表单提交的分类名称
$categoryName = InputValidator::getSafeInput($_POST['category_name']);

// 保存分类信息到文件或数据库
$categoriesFile = PROJECT_ROOT . '/app/blogs/categories.php';

if (!file_exists($categoriesFile)) {
    $categories = [];
} else {
    $categories = require $categoriesFile;
}

// 合并数组 并去重
$categories = array_unique(array_merge($categories, [$categoryName]));
file_put_contents($categoriesFile, '<?php return ' . var_export($categories, true) . ';');

// 输出成功信息
showMessage("分类保存成功！");
