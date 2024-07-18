<?php

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
define('PROJECT_ROOT', str_replace('\\', '/', __DIR__));

define('BASE_PATH', dirname($_SERVER["SCRIPT_NAME"]));

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
    $baseDir = PROJECT_ROOT . DIRECTORY_SEPARATOR; 
    $filePath =$baseDir . $fileName;
    if (file_exists($filePath)) {
        require $filePath;
    }
}

spl_autoload_register('autoload');

//首页导航
// try {
//     $traverser = new App\Utils\DirectoryTraverser();
//     $entries =$traverser->getDirectoryEntries( PROJECT_ROOT . '/app/blogs', true, ['.md', '.markdown']); 
// } catch (\InvalidArgumentException $e) {
//     echo $e->getMessage() . "\n";
// }

$baseUrl = "";
$pos = strpos($_SERVER['REQUEST_URI'], 'index.php');
if ($pos !== false) {
    $baseUrl = trim(substr($_SERVER['REQUEST_URI'], $pos + strlen('index.php')), "/");
}
$renderer = new App\Utils\UrlContentRenderer($baseUrl, PROJECT_ROOT);
$page = $renderer->renderByUrl();
echo $renderer->renderByUrl();
