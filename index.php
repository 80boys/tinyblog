<?php

require_once 'autoload.php';

// 创建调度器
$dispatcher = new \App\Core\Dispatcher();

// 分发请求并输出结果
echo $dispatcher->dispatch();

