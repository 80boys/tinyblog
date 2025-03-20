<?php

namespace App\Utils;

use App\Utils\ParsedownExtra;

class UrlContentRenderer
{
    protected $baseUrl;
    protected $basePath;
    protected $allowedExtensions = ['md', 'php', 'js', 'css', 'png', 'html'];

    public function __construct($baseUrl, $basePath)
    {
        $this->baseUrl = $baseUrl;
        $this->basePath = $basePath;
    }

    public function renderByUrl()
    {
        $localPath = str_replace('\\', '/', $this->getLocalPath());
        $pathinfo  = pathinfo($this->baseUrl);
        // 禁止套娃
        if (
            rtrim($localPath, "/") == PROJECT_ROOT
            || ($pathinfo['filename'] == "index" && $pathinfo['dirname'] == "")
            || ($pathinfo['filename'] == "index" && $pathinfo['dirname'] == "/")
        ) {
            return $this->returnIndex();
        }
        $fileExtension = pathinfo($localPath, PATHINFO_EXTENSION);

        if (!$fileExtension) {
            return $this->return404();
        }

        if (!in_array($fileExtension, $this->allowedExtensions)) {
            return $this->return404();
        }

        if (!$this->isPathWithinBasePath($localPath, PROJECT_ROOT)) {
            return $this->return404();
        }

        $fileExtension = "." . $fileExtension;
        if ($this->fileExists($fileExtension, '.md', $localPath)) {
            return $this->markdownToHtml(str_replace($fileExtension, '.md', $localPath));
        }

        if ($this->fileExists($fileExtension, '.markdown', $localPath)) {
            return $this->markdownToHtml(str_replace($fileExtension, '.markdown', $localPath));
        }

        // 处理html文件请求：如果html文件不存在，尝试寻找同名的php文件来处理
        if ($fileExtension === '.html' && !file_exists($localPath)) {
            $phpPath = str_replace('.html', '.php', $localPath);

            // 处理博客路径下的php文件
            if (file_exists($phpPath) && strpos($phpPath, '/app/blogs/') !== false) {
                $blog = require($phpPath);

                if (is_array($blog)) {
                    if (isset($blog['content'])) {
                        $blog['content'] = $this->mdDataToHtml($blog['content']);
                    }
                    return $this->includePhpFile(PROJECT_ROOT . "/app/block/blog.php", ['blog' => $blog]);
                }
            }
            // 处理其他路径下的php文件
            else if (file_exists($phpPath)) {
                return $this->includePhpFile($phpPath);
            }
        }

        if ($this->fileExists($fileExtension, '.php', $localPath) && $this->baseUrl != "index.html") {
            return $this->includePhpFile(str_replace($fileExtension, '.php', $localPath));
        }

        if (file_exists($localPath) && in_array($fileExtension, [".js", ".css", ".png"])) {
            return $this->includeStaticFile($localPath);
        }

        // 尝试加载PHP格式的博客文件
        if ($this->fileExists($fileExtension, '.php', $localPath) && strpos($localPath, '/app/blogs/') !== false) {
            $phpFilePath = str_replace($fileExtension, '.php', $localPath);
            if (file_exists($phpFilePath)) {
                $blog = require($phpFilePath);

                if (!is_array($blog)) {
                    error_log("无法解析博客数据: " . $phpFilePath);
                    return $this->return404();
                }

                if (isset($blog['content'])) {
                    $blog['content'] = $this->mdDataToHtml($blog['content']);
                }

                return $this->includePhpFile(PROJECT_ROOT . "/app/block/blog.php", ['blog' => $blog]);
            }
        }

        error_log("博客文件不存在: " . $localPath);
        return $this->return404();
    }
    protected function returnIndex()
    {
        return $this->includePhpFile(PROJECT_ROOT . '/app/block/index.php');
    }
    protected function return404()
    {
        return file_get_contents(PROJECT_ROOT . "/app/html/404.html");
    }

    protected function isPathWithinBasePath($path, $basePath)
    {
        return strpos($path, $basePath) === 0;
    }

    protected function getLocalPath()
    {
        return  str_replace('\\', '/', $this->basePath
            . DIRECTORY_SEPARATOR . trim($this->baseUrl, "/"));
    }

    protected function fileExists($fileExtension, $suffix, $filePath)
    {
        $path = str_replace($fileExtension, $suffix, $filePath);
        return file_exists($path);
    }

    public function markdownToHtml($markdownFilePath)
    {
        $parsedown = new ParsedownExtra();
        $markdown = file_get_contents($markdownFilePath);
        $htmlContent = $parsedown->text($markdown);
        return $htmlContent;
    }

    public function mdDataToHtml($markdownData)
    {
        $parsedown = new ParsedownExtra();
        $htmlContent = $parsedown->text($markdownData);
        return $htmlContent;
    }

    protected function includeStaticFile($markdownFilePath)
    {
        return file_get_contents($markdownFilePath);
    }

    protected function includePhpFile($phpFilePath, $variables = [])
    {
        if ($phpFilePath == PROJECT_ROOT . "/index.php") {
            return $this->returnIndex();
        }

        // 提取变量
        extract($variables);

        // 包含文件并获取输出
        ob_start();
        include $phpFilePath;
        return ob_get_clean();
    }
}
