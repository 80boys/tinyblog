<?php

namespace App\Utils;

class UrlContentRenderer
{
    protected $baseUrl;
    protected $basePath;
    protected $allowedExtensions = ['md', 'php', 'js', 'css', 'png', 'html', 'json'];

    public function __construct($baseUrl,$basePath)
    {
        $this->baseUrl =$baseUrl;
        $this->basePath =$basePath;
    }

    public function renderByUrl()
    {
        $localPath = str_replace('\\', '/', $this->getLocalPath());
        if ( rtrim($localPath, "/") == PROJECT_ROOT ) {
            return $this->returnIndex();
        }
        $fileExtension = pathinfo($localPath, PATHINFO_EXTENSION);

        if (!$fileExtension) {
            return $this->return404();
        }

        if (!in_array($fileExtension,$this->allowedExtensions)) {
            return $this->return404();
        }

        if (!$this->isPathWithinBasePath($localPath, PROJECT_ROOT) ) {
            return $this->return404();
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
        
        $getLocalPath = str_replace('.html', '.json', $localPath);
        if (file_exists($getLocalPath) && in_array($fileExtension, [".html"]) ) {
            $blogData  = file_get_contents($getLocalPath);
            $blog = json_decode($blogData, true);
            $blog['content'] = $this->mdDataToHtml($blog['content']);
            return $this->includePhpFile(PROJECT_ROOT. "/app/block/blog.php", ['blog' => $blog]);
        }
        return $this->return404();
    }
    protected function returnIndex() {
        $blogs = [];
        try {
            $traverser = new \App\Utils\DirectoryTraverser();
            $entries = $traverser->getDirectoryEntries(PROJECT_ROOT . '/app/blogs', true, ['json']);
            foreach ($entries as $entry) {
                $blogs[] = $traverser->getJsonContent($entry["path"]);
            }
        } catch (\InvalidArgumentException $e) {
            //echo $e->getMessage() . "\n";
        }
        return $this->includePhpFile(PROJECT_ROOT . '/app/block/index.php', ['blogs' => $blogs]);
    }
    protected function return404() {
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
        if ( $phpFilePath == PROJECT_ROOT . "/index.php" ) {
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
