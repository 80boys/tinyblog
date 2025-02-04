<?php

!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

use App\Utils\InputValidator;

if (isset($_GET['blog_path'])) {
    $blogPath = InputValidator::getSafeInput($_GET['blog_path']);
    $dt = new \App\Utils\DirectoryTraverser();
    $blog = $dt->getJsonContent( PROJECT_ROOT . "/app/blogs/" . $blogPath);
    if ($blog) {
        $dt->deleteFile( PROJECT_ROOT. "/app/blogs/". $blogPath);
        showMessage("删除成功",  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/app/end' );
    }
}
