<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

// 清除session中的用户ID
session_unset();
session_destroy();

// 重定向到登录页面
header('Location: ' . BASE_PATH . '/app/block/login.html');
exit;
?>
