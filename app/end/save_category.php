<?php

require_once "../../autoload.php";

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
define('PROJECT_ROOT', getProjectRoot());
define('BASE_PATH', getBasePath());

// 获取表单提交的分类名称
$categoryName = $_POST['category_name'];

// 保存分类信息到文件或数据库
$categoriesFile = PROJECT_ROOT . '/app/blogs/categories.data';

if (!file_exists($categoriesFile)) {
    $categories = [];
} else {
    $categories = json_decode(file_get_contents($categoriesFile), true);
}

$categories[] = $categoryName;
file_put_contents($categoriesFile, json_encode($categories, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

// 输出成功信息
echo '分类保存成功！';
