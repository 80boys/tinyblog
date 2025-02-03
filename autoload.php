<?php

session_start();

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
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];

        // 移除脚本名及其后的部分
        $basePath = str_replace($scriptName, '', $requestUri);

        // 移除查询字符串
        $basePath = strtok($basePath, '?');

        // 移除末尾的斜杠
        $basePath = trim($basePath, '/');

        // 如果基础路径为空，则返回根路径
        if (empty($basePath)) {
            return '';
        }

        // 如果基础路径包含多个部分，只返回第一个部分
        $parts = [];
        $basePathParts = explode('/', $basePath);
        foreach ($basePathParts as $part) {
            if (empty($part)) {
                continue;
            }
            if ( $part !== 'app') {
                array_push($parts, $part);
            } else {
                return count($parts) ? "/". implode('/', $parts) : '';
            }
        }
        return '';
    }
}


if (!function_exists('autoload')) {
    function autoload($className) {

        $namespace_map = [
            'Qiniu\\' => __DIR__. '/app/utils/Qiniu/',
            'App\\Utils\\' => __DIR__. '/app/utils/'
        ];

        foreach ($namespace_map as $namespace => $base_dir) {
            if (strpos($className, $namespace) === 0) {
                $relative_class = str_replace($namespace, '', $className);
                $relative_class = trim($relative_class, '\\');
                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $file = __DIR__ . DIRECTORY_SEPARATOR . $path . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
    spl_autoload_register('autoload');
}

if (!function_exists('showMessage')) {
    function showMessage($title = '操作提示', $redirectUrl = null, $text = '操作成功', $seconds = 3 ) {
        if ($redirectUrl === null) {
            $redirectUrl = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : '/';
        }
        extract(compact('title', 'text', 'seconds', 'redirectUrl'));
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

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
define('PROJECT_ROOT', getProjectRoot());
define('BASE_PATH', getBasePath());