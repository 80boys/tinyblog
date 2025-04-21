<?php

require_once 'autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 创建调度器
$dispatcher = new \App\Core\Dispatcher();

// 分发请求并输出结果
try {
    echo $dispatcher->dispatch();
} catch (\Exception $e) {
    echo $e->getMessage();
}