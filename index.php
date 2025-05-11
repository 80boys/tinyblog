<?php

require_once 'autoload.php';

// 分发请求并输出结果
try {
    // 创建调度器
    $dispatcher = new \App\Core\Dispatcher();
    echo $dispatcher->dispatch();
} catch (\Throwable $e) {
    echo $e->getMessage();
}