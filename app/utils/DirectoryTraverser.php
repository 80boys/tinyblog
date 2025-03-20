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
    public function getDirectoryEntries($dir, $recursive = false, $filter = null)
    {
        if (!$this->isValidDirectory($dir)) {
            throw new \InvalidArgumentException("Invalid directory: $dir");
        }

        return $this->listDirectoryEntries($dir, $recursive, $filter);
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
    private function listDirectoryEntries($dir, $recursive, $filter)
    {
        $entries = [];
        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $path = $dir . DIRECTORY_SEPARATOR . $entry;
                    if ($this->isDir($path)) {
                        $entries[] = ['type' => 'dir', 'path' => $path];
                        if ($recursive) {
                            $entries = array_merge($entries, $this->listDirectoryEntries($path, true, $filter));
                        }
                    } else {
                        if (is_null($filter) || $this->matchesFilter($entry, $filter)) {
                            $entries[] = ['type' => 'file', 'path' => $path];
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

        // 处理PHP文件
        if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            return $this->getBlogContent($path);
        }

        // 处理JSON文件
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    /**
     * 根据路径获取博客内容（PHP文件）
     *
     * @param string $path 路径
     * @return array
     */
    public function getBlogContent($path)
    {
        if (!file_exists($path)) {
            return [];
        }
        return require($path);
    }

    /**
     * 更新缓存，从缓存中移除指定博客
     * 
     * @param string $blogPath 博客相对路径
     * @return bool 是否成功更新缓存
     */
    public function updateCacheForDeletedBlog($blogPath)
    {
        try {
            // 获取缓存
            $caches = $this->getBlogCaches();

            // 检查博客是否在缓存中
            if (!isset($caches['blogs'][$blogPath])) {
                return false;
            }

            // 从blogs索引中移除博客
            unset($caches['blogs'][$blogPath]);

            // 从categories索引中移除博客
            foreach ($caches['categories'] as $category => &$categoryData) {
                $index = array_search($blogPath, $categoryData['blogs']);
                if ($index !== false) {
                    unset($categoryData['blogs'][$index]);
                    $categoryData['blogs'] = array_values($categoryData['blogs']);
                    $categoryData['count'] = count($categoryData['blogs']);
                }
            }

            // 从tags索引中移除博客
            foreach ($caches['tags'] as $tag => &$tagData) {
                $index = array_search($blogPath, $tagData['blogs']);
                if ($index !== false) {
                    unset($tagData['blogs'][$index]);
                    $tagData['blogs'] = array_values($tagData['blogs']);
                    $tagData['count'] = count($tagData['blogs']);
                }
            }

            // 从archives索引中移除博客
            foreach ($caches['archives'] as $year => &$yearData) {
                foreach ($yearData as $month => &$monthData) {
                    $index = array_search($blogPath, $monthData['blogs']);
                    if ($index !== false) {
                        unset($monthData['blogs'][$index]);
                        $monthData['blogs'] = array_values($monthData['blogs']);
                        $monthData['count'] = count($monthData['blogs']);
                    }
                }
            }

            // 更新博客总数和最后更新时间
            $caches['total_blogs'] = count($caches['blogs']);
            $caches['last_update'] = date('Y-m-d H:i:s');

            // 保存更新后的缓存
            $cachePath = PROJECT_ROOT . '/app/blogs/caches.php';
            $cacheContent = "<?php\nreturn " . var_export($caches, true) . ";\n";
            file_put_contents($cachePath, $cacheContent);

            return true;
        } catch (\Exception $e) {
            error_log("更新缓存失败: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFile($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * 获取所有博客
     * @param bool $includePrivate 是否包含私有博客
     * @return array
     */
    public function getAllBlogs($includePrivate = false)
    {
        $itemsPerPage = 20; // 每页显示的博客数量
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1; // 当前页码

        // 从缓存文件获取博客列表
        $caches = $this->getBlogCaches();
        $blogs = array_values($caches['blogs']);

        // 按日期降序排序
        usort($blogs, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // 过滤私有博客
        if (!$includePrivate) {
            $blogs = array_filter($blogs, function ($blog) {
                $blogFilePath = PROJECT_ROOT . '/app/blogs/' . $blog['path'];
                if (file_exists($blogFilePath)) {
                    $fullBlog = require($blogFilePath);
                    return !isset($fullBlog['is_private']) || $fullBlog['is_private'] === false;
                }
                return true;
            });
            // 重新索引数组
            $blogs = array_values($blogs);
        }

        $totalEntries = count($blogs);
        $totalPages = ceil($totalEntries / $itemsPerPage);

        // 计算当前页的博客
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $pageBlogs = array_slice($blogs, $startIndex, $itemsPerPage);

        // 获取完整的博客内容
        foreach ($pageBlogs as $key => &$blog) {
            $blogFilePath = PROJECT_ROOT . '/app/blogs/' . $blog['path'];
            if (file_exists($blogFilePath)) {
                $fullBlog = require($blogFilePath);
                $blog = array_merge($blog, $fullBlog);
            } else {
                // 如果博客文件不存在，移除该博客
                unset($pageBlogs[$key]);

                // 从缓存中移除不存在的博客
                $this->updateCacheForDeletedBlog($blog['path']);

                // 记录日志
                error_log("删除不存在的博客文件索引: " . $blog['path']);
            }
        }

        // 重新索引数组
        $pageBlogs = array_values($pageBlogs);

        return [
            "blogs" => $pageBlogs,
            "totalPages" => $totalPages,
            "currentPage" => $currentPage,
            "totalEntries" => $totalEntries,
            "itemsPerPage" => $itemsPerPage,
        ];
    }

    /**
     * 获取博客缓存
     * @return array
     */
    public function getBlogCaches()
    {
        $cachePath = PROJECT_ROOT . '/app/blogs/caches.php';
        return file_exists($cachePath) ? require($cachePath) : [
            'last_update' => '',
            'total_blogs' => 0,
            'categories' => [],
            'archives' => [],
            'tags' => [],
            'blogs' => []
        ];
    }

    /**
     * 重建博客缓存
     * @return bool
     */
    public function rebuildCache()
    {
        try {
            $entries = $this->getDirectoryEntries(PROJECT_ROOT . '/app/blogs', true, ['php']);
            $caches = [
                'last_update' => date('Y-m-d H:i:s'),
                'total_blogs' => 0,
                'categories' => [],
                'archives' => [],
                'tags' => [],
                'blogs' => []
            ];

            // 需要排除的配置文件列表
            $excludeFiles = [
                'caches.php',
                'settings.php',
                'categories.php'
            ];

            foreach ($entries as $entry) {
                $filename = basename($entry['path']);
                // 排除配置文件
                if ($entry['type'] === 'file' && !in_array($filename, $excludeFiles)) {
                    try {
                        $relativePath = str_replace(PROJECT_ROOT . '/app/blogs/', '', $entry['path']);
                        $blogData = @require($entry['path']);

                        // 检查是否成功读取数据并且是数组
                        if (!is_array($blogData)) {
                            error_log("Invalid blog data format in file: " . $entry['path']);
                            continue;
                        }

                        // 设置默认值
                        $blogData = array_merge([
                            'title' => '无标题',
                            'subtitle' => '',
                            'category' => '未分类',
                            'tags' => [],
                            'date' => date('Y-m-d H:i:s'),
                            'path' => $relativePath
                        ], $blogData);

                        // 确保tags是数组
                        if (!is_array($blogData['tags'])) {
                            $blogData['tags'] = $blogData['tags'] ? explode(',', $blogData['tags']) : [];
                        }

                        // 更新博客索引
                        $caches['blogs'][$relativePath] = [
                            'title' => $blogData['title'],
                            'subtitle' => $blogData['subtitle'],
                            'category' => $blogData['category'],
                            'tags' => $blogData['tags'],
                            'date' => $blogData['date'],
                            'path' => $relativePath
                        ];

                        // 如果是独立页面，在缓存中添加标记
                        if (isset($blogData['is_independent']) && $blogData['is_independent'] === true) {
                            $caches['blogs'][$relativePath]['is_independent'] = true;
                        }

                        // 如果是私有博客，在缓存中添加标记
                        if (isset($blogData['is_private']) && $blogData['is_private'] === true) {
                            $caches['blogs'][$relativePath]['is_private'] = true;
                        }

                        // 更新分类索引
                        if (!isset($caches['categories'][$blogData['category']])) {
                            $caches['categories'][$blogData['category']] = ['count' => 0, 'blogs' => []];
                        }
                        $caches['categories'][$blogData['category']]['blogs'][] = $relativePath;

                        // 更新标签索引
                        foreach ($blogData['tags'] as $tag) {
                            if (empty($tag)) continue;
                            if (!isset($caches['tags'][$tag])) {
                                $caches['tags'][$tag] = ['count' => 0, 'blogs' => []];
                            }
                            $caches['tags'][$tag]['blogs'][] = $relativePath;
                        }

                        // 更新归档索引
                        $timestamp = strtotime($blogData['date']);
                        if ($timestamp === false) {
                            $timestamp = time();
                            $blogData['date'] = date('Y-m-d H:i:s', $timestamp);
                        }
                        $year = date('Y', $timestamp);
                        $month = date('m', $timestamp);

                        if (!isset($caches['archives'][$year])) {
                            $caches['archives'][$year] = [];
                        }
                        if (!isset($caches['archives'][$year][$month])) {
                            $caches['archives'][$year][$month] = ['count' => 0, 'blogs' => []];
                        }
                        $caches['archives'][$year][$month]['blogs'][] = $relativePath;
                    } catch (\Exception $e) {
                        error_log("Error processing blog file {$entry['path']}: " . $e->getMessage());
                        continue;
                    }
                }
            }

            // 更新计数
            foreach ($caches['categories'] as &$category) {
                $category['count'] = count(array_unique($category['blogs']));
            }
            foreach ($caches['tags'] as &$tag) {
                $tag['count'] = count(array_unique($tag['blogs']));
            }
            foreach ($caches['archives'] as &$year) {
                foreach ($year as &$month) {
                    $month['count'] = count(array_unique($month['blogs']));
                }
            }
            $caches['total_blogs'] = count($caches['blogs']);

            // 保存缓存
            $cacheContent = "<?php\nreturn " . var_export($caches, true) . ";\n";
            file_put_contents(PROJECT_ROOT . '/app/blogs/caches.php', $cacheContent);

            return true;
        } catch (\Exception $e) {
            error_log("Cache rebuild failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取所有设置为独立页面的博客
     * @return array
     */
    public function getIndependentPages()
    {
        // 从缓存文件获取博客列表
        $caches = $this->getBlogCaches();
        $blogs = array_values($caches['blogs']);
        $independentPages = [];

        foreach ($blogs as $blog) {
            $blogFilePath = PROJECT_ROOT . '/app/blogs/' . $blog['path'];
            if (file_exists($blogFilePath)) {
                $fullBlog = require($blogFilePath);
                // 判断是否为独立页面并且不是私有的
                if (isset($fullBlog['is_independent']) && $fullBlog['is_independent'] === true) {
                    $independentPages[] = array_merge($blog, $fullBlog);
                }
            }
        }

        return $independentPages;
    }
}
