<?php

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');

define('PROJECT_ROOT', __DIR__);

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
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR; 
    $filePath =$baseDir . $fileName;
    if (file_exists($filePath)) {
        require $filePath;
    }
}

spl_autoload_register('autoload');

try {
    $traverser = new App\Utils\DirectoryTraverser();
    $entries =$traverser->getDirectoryEntries( __DIR__ . '/app/blogs', true, ['.md', '.markdown']); 
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage() . "\n";
}

$baseUrl = "";
$pos = strpos($_SERVER['REQUEST_URI'], 'index.php');
if ($pos !== false) {
    $baseUrl = trim(substr($_SERVER['REQUEST_URI'], $pos + strlen('index.php')), "/");
}

$renderer = new App\Utils\UrlContentRenderer($baseUrl, __DIR__);

echo $renderer->renderByUrl();
