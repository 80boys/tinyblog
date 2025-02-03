<?php

require_once 'autoload.php';

$baseUrl = $_SERVER['REQUEST_URI'];
$pos = strpos($_SERVER['REQUEST_URI'], 'index.php');
if ($pos !== false) {
    $baseUrl = trim(substr($_SERVER['REQUEST_URI'], $pos + strlen('index.php')), "/");
}

//dump($baseUrl);
$renderer = new App\Utils\UrlContentRenderer(strtok($baseUrl, '?'), PROJECT_ROOT);
echo $renderer->renderByUrl();
