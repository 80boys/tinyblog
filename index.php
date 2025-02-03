<?php

require_once 'autoload.php';

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
define('PROJECT_ROOT', getProjectRoot());
define('BASE_PATH', getBasePath());

$baseUrl = "";
$pos = strpos($_SERVER['REQUEST_URI'], 'index.php');
if ($pos !== false) {
    $baseUrl = trim(substr($_SERVER['REQUEST_URI'], $pos + strlen('index.php')), "/");
}
$renderer = new App\Utils\UrlContentRenderer(strtok($baseUrl, '?'), PROJECT_ROOT);
echo $renderer->renderByUrl();
