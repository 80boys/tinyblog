<?php

namespace App\Models;

use App\Utils\FileManager;

class BlogsModel
{
    // 存储路径配置
    private static $storagePath = 'content/';
    private static $cachePath = 'content/caches.php';

    // 获取缓存
    public static function getCaches()
    {
        $cachePath = PROJECT_ROOT . '/' . self::$cachePath;
        $defaultCache = [
            'last_update' => '',
            'total_blogs' => 0,
            'categories' => [],
            'archives' => [],
            'tags' => [],
            'blogs' => []
        ];

        return FileManager::readPhpConfigFile($cachePath, $defaultCache);
    }

    // 获取博客列表
    public static function getList($page = 1, $limit = 10, $filters = [])
    {
        // 从缓存文件获取博客列表
        $caches = self::getCaches();
        $blogs = array_values($caches['blogs']);

        // 过滤博客
        $blogs = self::filterBlogs($blogs, $filters);

        // 排序
        $sortBy = isset($filters['sort_by']) ? $filters['sort_by'] : 'date';
        $sortOrder = isset($filters['sort_order']) ? $filters['sort_order'] : 'desc';
        self::sortBlogs($blogs, $sortBy, $sortOrder);

        // 计算分页
        $total = count($blogs);
        $totalPages = ceil($total / $limit);
        $currentPage = max(1, min($page, $totalPages));
        $offset = ($currentPage - 1) * $limit;
        $items = array_slice($blogs, $offset, $limit);

        // 加载详细内容
        foreach ($items as &$blog) {
            $blogFilePath = PROJECT_ROOT . '/' . self::$storagePath . $blog['path'];
            $fullBlog = FileManager::readBlogFile($blogFilePath);
            if (!empty($fullBlog)) {
                $fullBlog['path'] = $blog['path'];
                $blog = array_merge($blog, $fullBlog);
                $blog['id'] = basename($blog['path'], '.php');
            }
        }

        return [
            'items' => $items,
            'total' => $total,
            'page' => $currentPage,
            'limit' => $limit,
            'pages' => $totalPages
        ];
    }

    // 过滤博客
    private static function filterBlogs($blogs, $filters)
    {
        return array_filter($blogs, function ($blog) use ($filters) {
            // 过滤私有博客
            if (isset($filters['include_private']) && $filters['include_private'] !== true) {
                if (isset($blog['is_private']) && $blog['is_private'] === true) {
                    return false;
                }
            }

            // 过滤独立页面
            if (isset($filters['include_independent']) && $filters['include_independent'] !== true) {
                if (isset($blog['is_independent']) && $blog['is_independent'] === true) {
                    return false;
                }
            }

            // 按分类过滤
            if (isset($filters['category']) && !empty($filters['category'])) {
                if ($blog['category'] !== $filters['category']) {
                    return false;
                }
            }

            // 按标签过滤
            if (isset($filters['tag']) && !empty($filters['tag'])) {
                if (!in_array($filters['tag'], $blog['tags'])) {
                    return false;
                }
            }

            // 按日期范围过滤
            if (isset($filters['date_from']) && !empty($filters['date_from'])) {
                if (strtotime($blog['date']) < strtotime($filters['date_from'])) {
                    return false;
                }
            }

            if (isset($filters['date_to']) && !empty($filters['date_to'])) {
                if (strtotime($blog['date']) > strtotime($filters['date_to'])) {
                    return false;
                }
            }

            // 按年月归档过滤
            if (isset($filters['year']) && !empty($filters['year'])) {
                $blogYear = date('Y', strtotime($blog['date']));
                if ($blogYear != $filters['year']) {
                    return false;
                }

                if (isset($filters['month']) && !empty($filters['month'])) {
                    $blogMonth = date('m', strtotime($blog['date']));
                    if ($blogMonth != $filters['month']) {
                        return false;
                    }
                }
            }

            // 关键词搜索
            if (isset($filters['search']) && !empty($filters['search'])) {
                $searchFields = ['title', 'subtitle'];
                $found = false;

                foreach ($searchFields as $field) {
                    if (isset($blog[$field]) && stripos($blog[$field], $filters['search']) !== false) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    // 加载博客全文进行搜索
                    $blogFilePath = PROJECT_ROOT . '/' . self::$storagePath . $blog['path'];
                    $fullBlog = FileManager::readBlogFile($blogFilePath);

                    if (!empty($fullBlog) && isset($fullBlog['content'])) {
                        if (stripos(strip_tags($fullBlog['content']), $filters['search']) !== false) {
                            $found = true;
                        }
                    }

                    if (!$found) {
                        return false;
                    }
                }
            }

            return true;
        });
    }

    // 排序博客
    private static function sortBlogs(&$blogs, $sortBy, $sortOrder)
    {
        usort($blogs, function ($a, $b) use ($sortBy, $sortOrder) {
            // 默认值处理
            $valA = isset($a[$sortBy]) ? $a[$sortBy] : '';
            $valB = isset($b[$sortBy]) ? $b[$sortBy] : '';

            // 日期特殊处理
            if ($sortBy === 'date') {
                $valA = strtotime($valA);
                $valB = strtotime($valB);
            }

            // 降序或升序
            if ($sortOrder === 'desc') {
                return $valB <=> $valA;
            } else {
                return $valA <=> $valB;
            }
        });
    }

    // 获取分类列表
    public static function getCategories()
    {
        $caches = self::getCaches();
        return $caches['categories'];
    }

    // 获取公开的分类列表（不包含私有和独立页面的统计）
    public static function getPublicCategories()
    {
        $caches = self::getCaches();
        $categories = $caches['categories'];
        $blogs = $caches['blogs'];

        // 创建一个新的分类统计数组
        $publicCategories = [];

        // 复制原始分类结构
        foreach ($categories as $categoryName => $categoryData) {
            $publicCategories[$categoryName] = [
                'count' => 0,
                'blogs' => []
            ];
        }

        // 重新计算每个分类的公开博客数量
        foreach ($blogs as $path => $blog) {
            // 跳过私有和独立页面
            if ((isset($blog['is_private']) && $blog['is_private'] === true) ||
                (isset($blog['is_independent']) && $blog['is_independent'] === true)
            ) {
                continue;
            }

            $category = $blog['category'];
            if (isset($publicCategories[$category])) {
                $publicCategories[$category]['blogs'][] = $path;
            }
        }

        // 更新计数
        foreach ($publicCategories as &$category) {
            $category['count'] = count($category['blogs']);
        }

        return $publicCategories;
    }

    // 获取标签列表
    public static function getTags()
    {
        $caches = self::getCaches();
        return $caches['tags'];
    }

    // 获取归档列表
    public static function getArchives()
    {
        $caches = self::getCaches();
        return $caches['archives'];
    }

    // 获取博客总数
    public static function getTotalBlogs()
    {
        $caches = self::getCaches();
        return $caches['total_blogs'];
    }

    // 获取最近更新的博客
    public static function getRecentBlogs($limit = 5, $includePrivate = false)
    {
        return self::getList(1, $limit, [
            'include_private' => $includePrivate,
            'include_independent' => false,
            'sort_by' => 'date',
            'sort_order' => 'desc'
        ])['items'];
    }

    // 获取热门标签
    public static function getPopularTags($limit = 10)
    {
        $caches = self::getCaches();
        $tags = $caches['tags'];

        // 按标签数量排序
        uasort($tags, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return array_slice($tags, 0, $limit, true);
    }

    // 获取独立页面
    public static function getIndependentPages()
    {
        $caches = self::getCaches();
        $blogs = $caches['blogs'];

        $independentPages = [];
        foreach ($blogs as $path => $blog) {
            // 确保页面是独立页面且不是私有的
            if (
                isset($blog['is_independent']) && $blog['is_independent'] === true &&
                (!isset($blog['is_private']) || $blog['is_private'] !== true)
            ) {
                $blogFilePath = PROJECT_ROOT . '/' . self::$storagePath . $path;
                $fullBlog = FileManager::readBlogFile($blogFilePath);

                if (!empty($fullBlog)) {
                    $fullBlog['id'] = basename($path, '.php');
                    $fullBlog['path'] = $path;
                    $independentPages[] = $fullBlog;
                }
            }
        }

        return $independentPages;
    }

    // 获取相关博客
    public static function getRelatedBlogs($blogId, $limit = 5)
    {
        $blog = BlogModel::findById($blogId);
        if (!$blog) {
            return [];
        }

        $caches = self::getCaches();
        $blogs = array_values($caches['blogs']);

        // 收集相同标签的博客
        $relatedBlogs = [];
        $tags = $blog->getTags();

        foreach ($blogs as $blogItem) {
            // 排除自身
            if (basename($blogItem['path'], '.php') == $blogId) {
                continue;
            }

            // 排除私有和独立页面
            if ((isset($blogItem['is_private']) && $blogItem['is_private'] === true) ||
                (isset($blogItem['is_independent']) && $blogItem['is_independent'] === true)
            ) {
                continue;
            }

            // 计算共同标签数量
            $commonTags = count(array_intersect($tags, $blogItem['tags']));

            if ($commonTags > 0 || $blogItem['category'] == $blog->getCategory()) {
                $score = $commonTags * 2;
                if ($blogItem['category'] == $blog->getCategory()) {
                    $score += 1;
                }

                $relatedBlogs[] = [
                    'blog' => $blogItem,
                    'score' => $score
                ];
            }
        }

        // 按相关性分数排序
        usort($relatedBlogs, function ($a, $b) {
            if ($b['score'] != $a['score']) {
                return $b['score'] - $a['score'];
            }
            // 如果分数相同，按日期排序
            return strtotime($b['blog']['date']) - strtotime($a['blog']['date']);
        });

        // 提取博客信息
        $result = [];
        foreach (array_slice($relatedBlogs, 0, $limit) as $item) {
            $blogFilePath = PROJECT_ROOT . '/' . self::$storagePath . $item['blog']['path'];
            $fullBlog = FileManager::readBlogFile($blogFilePath);

            if (!empty($fullBlog)) {
                $fullBlog['id'] = basename($item['blog']['path'], '.php');
                $fullBlog['path'] = $item['blog']['path'];
                $result[] = $fullBlog;
            }
        }

        return $result;
    }

    // 重建缓存
    public static function rebuildCache()
    {
        try {
            // 获取所有博客文件
            $excludeFiles = ['caches.php', 'settings.php', 'categories.php'];
            $files = FileManager::getFiles(
                PROJECT_ROOT . '/' . self::$storagePath,
                ['php'],
                $excludeFiles
            );

            $caches = [
                'last_update' => date('Y-m-d H:i:s'),
                'total_blogs' => 0,
                'categories' => [],
                'archives' => [],
                'tags' => [],
                'blogs' => []
            ];

            // 处理每个博客文件
            foreach ($files as $file) {
                try {
                    $relativePath = str_replace(PROJECT_ROOT . '/' . self::$storagePath, '', $file);
                    $blogData = FileManager::readBlogFile($file);

                    if (!is_array($blogData)) {
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

                    // 特殊属性
                    if (isset($blogData['is_independent']) && $blogData['is_independent'] === true) {
                        $caches['blogs'][$relativePath]['is_independent'] = true;
                    }

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
                    continue;
                }
            }

            // 更新计数
            foreach ($caches['categories'] as &$category) {
                $category['count'] = count($category['blogs']);
            }

            foreach ($caches['tags'] as &$tag) {
                $tag['count'] = count($tag['blogs']);
            }

            foreach ($caches['archives'] as &$year) {
                foreach ($year as &$month) {
                    $month['count'] = count($month['blogs']);
                }
            }

            $caches['total_blogs'] = count($caches['blogs']);

            // 保存缓存
            $cacheContent = "<?php\nreturn " . var_export($caches, true) . ";\n";
            file_put_contents(PROJECT_ROOT . '/' . self::$cachePath, $cacheContent);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
