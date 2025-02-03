<?php

if (!function_exists('getProjectRoot')) {
    function getProjectRoot() {
        $currentDir  = str_replace('\\', '/', __DIR__);
        $projectRoot = '';

        // 循环向上查找，直到找到包含'app'目录的目录
        while ($currentDir !== '/') {
            if (is_dir($currentDir . '/app')) {
                $projectRoot = $currentDir;
                break;
            }
            $currentDir = dirname($currentDir);
        }

        return $projectRoot;
    }
}

if (!function_exists('getBasePath')) {
    function getBasePath()
    {
        $projectRoot = getProjectRoot();
        return "/" . basename($projectRoot);
    }
}

if (!function_exists('autoload')) {
    function autoload($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = lcfirst(substr($className, 0, $lastNsPos));
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR,$namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR,$className) . '.php';
        $baseDir = getProjectRoot() . DIRECTORY_SEPARATOR; 
        $filePath =$baseDir . $fileName;
        if (file_exists($filePath)) { 
            require_once $filePath;
        }
    }
    spl_autoload_register('autoload');
}

if (!function_exists('showMessage')) {
    function showMessage($title = '操作提示', $text = '操作成功', $seconds = 3, $redirectUrl = '/') {
        ob_start();
        include_once __DIR__ . '/app/block/message.php';
        $content = ob_get_clean();
        echo $content;
    }
}

if (!function_exists('dump')) {
    function dump() {
        $args = func_get_args();
        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo '</pre>';
        die;
    }
}