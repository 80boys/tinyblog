<?php

namespace App\Utils;

class UrlContentRenderer
{
    protected $baseUrl;
    protected $basePath;
    protected $allowedExtensions = ['md', 'php', 'js', 'css', 'png', 'html'];

    public function __construct($baseUrl,$basePath)
    {
        $this->baseUrl =$baseUrl;
        $this->basePath =$basePath;
    }

    public function renderByUrl()
    {
        $localPath = str_replace('\\', '/', $this->getLocalPath());
        $fileExtension = pathinfo($localPath, PATHINFO_EXTENSION);
        if (!$fileExtension) {
            return $this->includePhpFile(PROJECT_ROOT . '/app/block/index.php');
        }
        if (!in_array($fileExtension,$this->allowedExtensions)) {
            return file_get_contents(PROJECT_ROOT . "/app/html/404.html");
        }

        if (!$this->isPathWithinBasePath($localPath, PROJECT_ROOT) ) {
            return file_get_contents(PROJECT_ROOT . "/app/html/404.html");
        }

        $fileExtension = "." . $fileExtension;
        if ($this->fileExists($fileExtension, '.md', $localPath)) {
            return $this->markdownToHtml(str_replace($fileExtension, '.md', $localPath));
        }

        if ($this->fileExists($fileExtension, '.markdown', $localPath)) {
            return $this->markdownToHtml(str_replace($fileExtension, '.markdown', $localPath));
        }

        if ($this->fileExists($fileExtension, '.php', $localPath)) {
            return $this->includePhpFile(str_replace($fileExtension, '.php', $localPath));
        }

        if (file_exists($localPath) && in_array($fileExtension, [".js", ".css", ".png"]) ) {
            return $this->includeStaticFile( $localPath );
        }
        return file_get_contents(PROJECT_ROOT . "/app/html/404.html");
    }

    protected function isPathWithinBasePath($path,$basePath) {
        return strpos($path,$basePath) === 0;
    }

    protected function getLocalPath()
    {
        return  str_replace('\\', '/', $this->basePath 
                    . DIRECTORY_SEPARATOR . trim( $this->baseUrl )); 
    }

    protected function fileExists($fileExtension, $suffix, $filePath)
    {
        $path = str_replace($fileExtension, $suffix, $filePath);
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
    
    protected function includeStaticFile($markdownFilePath)
    {
        return file_get_contents($markdownFilePath);
    }

    protected function includePhpFile($phpFilePath)
    {
        ob_start();
        include $phpFilePath;
        return ob_get_clean();
    }
}
