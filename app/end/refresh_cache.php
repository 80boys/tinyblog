<?php
require_once __DIR__ . "/../../autoload.php";

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . '/app/block/login.html');
    exit;
}

$dt = new \App\Utils\DirectoryTraverser();
$result = $dt->rebuildCache();

if ($result) {
    showMessage('缓存更新成功', BASE_PATH . '/app/end/index.php');
} else {
    showMessage('缓存更新失败', BASE_PATH . '/app/end/index.php', '请检查日志文件了解详细信息');
}
