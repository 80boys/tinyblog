<?php

namespace App\Utils;

class UrlContentRenderer
{
    protected $baseUrl;
    protected $basePath;

    public function __construct($baseUrl,$basePath)
    {
        $this->baseUrl =$baseUrl;
        $this->basePath =$basePath;
    }

    public function renderByUrl()
    {
        $localPath =$this->getLocalPath();
        
        // 检查 Markdown 文件是否存在
        if ($this->fileExists($localPath, '.md')) {
            return $this->markdownToHtml(str_replace('.html', '.md', $localPath));
        }

        // 检查 Markdown 文件是否存在
        if ($this->fileExists($localPath, '.markdown')) {
            return $this->markdownToHtml(str_replace('.html', '.markdown', $localPath));
        }

        // 检查 PHP 文件是否存在
        if ($this->fileExists($localPath, '.php')) {
            return $this->includePhpFile(str_replace('.html', '.php', $localPath));
        }

        return file_get_contents(PROJECT_ROOT . "/app/html/404.html");
    }

    protected function getLocalPath()
    {
        return  str_replace('\\', '/', $this->basePath 
                    . DIRECTORY_SEPARATOR . trim( $this->baseUrl )); 
    }

    protected function fileExists($filePath, $suffix)
    {
        $path = str_replace('.html', $suffix, $filePath);
        return file_exists($path);
    }

    protected function markdownToHtml($markdownFilePath)
    {
        // 这里只是一个示例，实际应用中你可能需要使用 Markdown 解析库
        $markdownContent = file_get_contents($markdownFilePath);
        // 这里简单地将 Markdown 转换为 HTML，实际应用中请使用 Markdown 解析器
        $htmlContent = "<pre>" . htmlspecialchars($markdownContent) . "</pre>";
        return $htmlContent;
    }

    protected function includePhpFile($phpFilePath)
    {
        ob_start();
        include $phpFilePath;
        $content = ob_get_clean();
        return $content;
    }
}
