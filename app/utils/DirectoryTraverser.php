<?php

namespace App\Utils;

class DirectoryTraverser
{
    /**
     * 获取目录条目列表
     *
     * @param string $dir 要遍历的目录
     * @param bool $recursive 是否递归遍历子目录
     * @param string|array $filter 过滤器，指定要包含的文件扩展名
     * @return array
     */
    public function getDirectoryEntries($dir,$recursive = false, $filter = null)
    {
        if (!$this->isValidDirectory($dir)) {
            throw new \InvalidArgumentException("Invalid directory: $dir");
        }

        return $this->listDirectoryEntries($dir, $recursive,$filter);
    }

    /**
     * 检查目录是否有效
     *
     * @param string $dir 目录路径
     * @return bool
     */
    private function isValidDirectory($dir)
    {
        return is_dir($dir) && is_readable($dir);
    }

    /**
     * 列出目录条目
     *
     * @param string $dir 目录路径
     * @param bool $recursive 是否递归遍历子目录
     * @param string|array $filter 过滤器，指定要包含的文件扩展名
     * @return array
     */
    private function listDirectoryEntries($dir,$recursive, $filter)
    {
        $entries = [];
        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." &&$entry != "..") {
                    $path =$dir . DIRECTORY_SEPARATOR . $entry;
                    if ($this->isDir($path)) {
                        $entries[] = ['type' => 'dir', 'path' =>$path];
                        if ($recursive) {
                            $entries = array_merge($entries, $this->listDirectoryEntries($path, true, $filter));
                        }
                    } else {
                        if (is_null($filter) || $this->matchesFilter($entry,$filter)) {
                            $entries[] = ['type' => 'file', 'path' =>$path];
                        }
                    }
                }
            }
            closedir($handle);
        }
        return $entries;
    }

    /**
     * 检查文件是否匹配过滤器
     *
     * @param string $file 文件名
     * @param string|array $filter 过滤器
     * @return bool
     */
    private function matchesFilter($file, $filter)
    {
        if (is_string($filter)) {
            $filter = [$filter];
        }
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        return in_array($fileExtension, $filter);
    }

    /**
     * 检查路径是否是目录
     *
     * @param string $path 路径
     * @return bool
     */
    private function isDir($path)
    {
        return is_dir($path);
    }

    /**
     * 根据路径取json文件内容
     *
     * @param string $path 路径
     * @return array
     */
    public function getJsonContent($path)
    {
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    public function deleteFile($path) {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function getAllBlogs() {
        $blogs = [];
        $itemsPerPage = 20; // 每页显示的博客数量
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1; // 当前页码
        $totalPages = 0; // 总页数
        try {
            $traverser = new \App\Utils\DirectoryTraverser();
            $entries = $traverser->getDirectoryEntries(PROJECT_ROOT . '/app/blogs', true, ['json']);
            $totalEntries = count($entries);
            $totalPages = ceil($totalEntries / $itemsPerPage); // 计算总页数

            $startIndex = ($currentPage - 1) * $itemsPerPage; // 计算起始索引
            $endIndex = $startIndex + $itemsPerPage; // 计算结束索引

            for ($i = $startIndex; $i < $endIndex && $i < $totalEntries; $i++) {
                $blog = $traverser->getJsonContent($entries[$i]["path"]);
                $blogs[] = $blog;
            }
        } catch (\InvalidArgumentException $e) {
            //echo $e->getMessage() . "\n";
        }

        return [
            "blogs" => $blogs,
            "totalPages" => $totalPages,
            "currentPage" => $currentPage,
            "totalEntries" => $totalEntries,
            "itemsPerPage" => $itemsPerPage,
        ];
    }
}


