<?php
namespace App\Models;

use App\Utils\FileManager;
use App\Core\Router;

class BlogModel
{
    // 博客基础信息
    private $id;          // 博客ID
    private $title;       // 博客标题
    private $subtitle;    // 博客副标题
    private $content;     // 博客内容
    private $author;      // 作者
    private $category;    // 分类
    private $tags;        // 标签
    private $cover_image; // 封面图片
    private $created_at;  // 创建时间
    private $updated_at;  // 更新时间
    private $path;        // 文件路径
    private $is_private;  // 是否私有
    private $is_independent; // 是否独立页面

    // 存储路径配置
    private static $storagePath = 'app/blogs/';
    private static $cachePath = 'app/blogs/caches.php';

    // 构造函数
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->id = isset($data['id']) ? $data['id'] : null;
            $this->title = isset($data['title']) ? $data['title'] : '';
            $this->subtitle = isset($data['subtitle']) ? $data['subtitle'] : '';
            $this->content = isset($data['content']) ? $data['content'] : '';
            $this->author = isset($data['author']) ? $data['author'] : '';
            $this->category = isset($data['category']) ? $data['category'] : '';
            $this->tags = isset($data['tags']) ? $data['tags'] : [];
            $this->cover_image = isset($data['cover_image']) ? $data['cover_image'] : '';
            $this->created_at = isset($data['date']) ? $data['date'] : null;
            $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
            $this->path = isset($data['path']) ? $data['path'] : null;
            $this->is_private = isset($data['is_private']) ? $data['is_private'] : false;
            $this->is_independent = isset($data['is_independent']) ? $data['is_independent'] : false;
        }
    }

    // Getter方法
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getSubtitle() { return $this->subtitle; }
    public function getContent() { return $this->content; }
    public function getAuthor() { return $this->author; }
    public function getCategory() { return $this->category; }
    public function getTags() { return $this->tags; }
    public function getCoverImage() { return $this->cover_image; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    public function getPath() { return $this->path; }
    public function isPrivate() { return $this->is_private; }
    public function isIndependent() { return $this->is_independent; }

    // Setter方法
    public function setId($id) { $this->id = $id; }
    public function setTitle($title) { $this->title = $title; }
    public function setSubtitle($subtitle) { $this->subtitle = $subtitle; }
    public function setContent($content) { $this->content = $content; }
    public function setAuthor($author) { $this->author = $author; }
    public function setCategory($category) { $this->category = $category; }
    public function setTags($tags) { $this->tags = $tags; }
    public function setCoverImage($cover_image) { $this->cover_image = $cover_image; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
    public function setPath($path) { $this->path = $path; }
    public function setPrivate($is_private) { $this->is_private = $is_private; }
    public function setIndependent($is_independent) { $this->is_independent = $is_independent; }

    // 从ID加载博客
    public static function findById($id, $array = false)
    {
        // 读取缓存
        $caches = self::getCaches();
        
        // 遍历缓存查找匹配的博客
        foreach ($caches['blogs'] as $path => $blogData) {
            if (basename($path, '.php') == $id) {
                // 找到匹配的博客，读取完整内容
                $fullPath = PROJECT_ROOT . '/' . self::$storagePath . $path;
                $fullData = FileManager::readBlogFile($fullPath);
                if (!empty($fullData)) {
                    $fullData['id'] = $id;
                    $fullData['path'] = $path;
                    return $array ? $fullData : new self($fullData);
                }
            }
        }
        return false;
    }

    // 从路径加载博客
    public static function findByPath($path)
    {
        $fullPath = PROJECT_ROOT . '/' . self::$storagePath . $path;
        $data = FileManager::readBlogFile($fullPath);
        if (!empty($data)) {
            $data['id'] = basename($path, '.php');
            $data['path'] = $path;
            return new self($data);
        }
        
        return null;
    }

    // 保存博客
    public function save()
    {
        // 设置ID和时间
        if (!$this->id) {
            $this->id = uniqid();
        }
        
        $this->updated_at = date('Y-m-d H:i:s');
        if (!$this->created_at) {
            $this->created_at = $this->updated_at;
        }
        
        // 准备要保存的数据
        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => $this->content,
            'author' => $this->author,
            'category' => $this->category,
            'tags' => $this->tags,
            'cover_image' => $this->cover_image,
            'date' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        
        // 添加额外属性
        if ($this->is_private) {
            $data['is_private'] = true;
        }
        
        if ($this->is_independent) {
            $data['is_independent'] = true;
        }
        
        // 准备文件路径
        if (!$this->path) {
            $this->path = $this->id . '.php';
        }
        
        $filePath = PROJECT_ROOT . '/' . self::$storagePath . $this->path;
        
        // 保存文件
        if (!FileManager::saveBlogFile($filePath, $data)) {
            return false;
        }
        
        // 更新缓存
        self::updateCache($this->path, $data);
        
        return $this->id;
    }

    // 删除博客
    public function delete()
    {
        if ($this->path) {
            $filePath = PROJECT_ROOT . '/' . self::$storagePath . $this->path;
            if (FileManager::deleteFile($filePath)) {
                // 从缓存中移除
                self::removeBlogFromCache($this->path);
                return true;
            }
        }
        
        return false;
    }

    // 获取缓存
    private static function getCaches()
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

    // 更新缓存中的博客
    private static function updateCache($path, $blogData)
    {
        $cachePath = PROJECT_ROOT . '/' . self::$cachePath;
        
        // 读取现有缓存
        $caches = self::getCaches();
        
        // 更新最后更新时间
        $caches['last_update'] = date('Y-m-d H:i:s');
        
        // 如果博客已存在于缓存中，先移除旧数据
        if (isset($caches['blogs'][$path])) {
            $oldCategory = $caches['blogs'][$path]['category'];
            $oldTags = $caches['blogs'][$path]['tags'];
            
            // 从旧分类移除
            if (isset($caches['categories'][$oldCategory])) {
                $index = array_search($path, $caches['categories'][$oldCategory]['blogs']);
                if ($index !== false) {
                    unset($caches['categories'][$oldCategory]['blogs'][$index]);
                    $caches['categories'][$oldCategory]['blogs'] = array_values($caches['categories'][$oldCategory]['blogs']);
                    $caches['categories'][$oldCategory]['count'] = count($caches['categories'][$oldCategory]['blogs']);
                }
            }
            
            // 从旧标签移除
            foreach ($oldTags as $tag) {
                if (isset($caches['tags'][$tag])) {
                    $index = array_search($path, $caches['tags'][$tag]['blogs']);
                    if ($index !== false) {
                        unset($caches['tags'][$tag]['blogs'][$index]);
                        $caches['tags'][$tag]['blogs'] = array_values($caches['tags'][$tag]['blogs']);
                        $caches['tags'][$tag]['count'] = count($caches['tags'][$tag]['blogs']);
                    }
                }
            }
        }
        
        // 更新博客索引
        $caches['blogs'][$path] = [
            'title' => $blogData['title'],
            'subtitle' => $blogData['subtitle'],
            'category' => $blogData['category'],
            'tags' => $blogData['tags'],
            'date' => $blogData['date'],
            'path' => $path
        ];
        
        // 添加额外属性
        if (isset($blogData['is_independent']) && $blogData['is_independent'] === true) {
            $caches['blogs'][$path]['is_independent'] = true;
        }
        
        if (isset($blogData['is_private']) && $blogData['is_private'] === true) {
            $caches['blogs'][$path]['is_private'] = true;
        }
        
        // 更新分类索引
        if (!isset($caches['categories'][$blogData['category']])) {
            $caches['categories'][$blogData['category']] = ['count' => 0, 'blogs' => []];
        }
        $caches['categories'][$blogData['category']]['blogs'][] = $path;
        $caches['categories'][$blogData['category']]['count'] = count(array_unique($caches['categories'][$blogData['category']]['blogs']));
        
        // 更新标签索引
        foreach ($blogData['tags'] as $tag) {
            if (empty($tag)) continue;
            
            if (!isset($caches['tags'][$tag])) {
                $caches['tags'][$tag] = ['count' => 0, 'blogs' => []];
            }
            $caches['tags'][$tag]['blogs'][] = $path;
            $caches['tags'][$tag]['count'] = count(array_unique($caches['tags'][$tag]['blogs']));
        }
        
        // 更新归档索引
        $year = date('Y', strtotime($blogData['date']));
        $month = date('m', strtotime($blogData['date']));
        
        if (!isset($caches['archives'][$year])) {
            $caches['archives'][$year] = [];
        }
        if (!isset($caches['archives'][$year][$month])) {
            $caches['archives'][$year][$month] = ['count' => 0, 'blogs' => []];
        }
        $caches['archives'][$year][$month]['blogs'][] = $path;
        $caches['archives'][$year][$month]['count'] = count(array_unique($caches['archives'][$year][$month]['blogs']));
        
        // 更新博客总数
        $caches['total_blogs'] = count($caches['blogs']);
        
        // 保存缓存
        FileManager::savePhpConfigFile($cachePath, $caches);
    }
    
    // 从缓存中移除博客
    private static function removeBlogFromCache($path)
    {
        $cachePath = PROJECT_ROOT . '/' . self::$cachePath;
        
        // 读取现有缓存
        $caches = self::getCaches();
        
        // 检查博客是否在缓存中
        if (!isset($caches['blogs'][$path])) {
            return false;
        }
        
        $blogData = $caches['blogs'][$path];
        
        // 从blogs索引中移除博客
        unset($caches['blogs'][$path]);
        
        // 从categories索引中移除博客
        $category = $blogData['category'];
        if (isset($caches['categories'][$category])) {
            $index = array_search($path, $caches['categories'][$category]['blogs']);
            if ($index !== false) {
                unset($caches['categories'][$category]['blogs'][$index]);
                $caches['categories'][$category]['blogs'] = array_values($caches['categories'][$category]['blogs']);
                $caches['categories'][$category]['count'] = count($caches['categories'][$category]['blogs']);
            }
        }
        
        // 从tags索引中移除博客
        foreach ($blogData['tags'] as $tag) {
            if (isset($caches['tags'][$tag])) {
                $index = array_search($path, $caches['tags'][$tag]['blogs']);
                if ($index !== false) {
                    unset($caches['tags'][$tag]['blogs'][$index]);
                    $caches['tags'][$tag]['blogs'] = array_values($caches['tags'][$tag]['blogs']);
                    $caches['tags'][$tag]['count'] = count($caches['tags'][$tag]['blogs']);
                }
            }
        }
        
        // 从archives索引中移除博客
        $date = strtotime($blogData['date']);
        $year = date('Y', $date);
        $month = date('m', $date);
        
        if (isset($caches['archives'][$year][$month])) {
            $index = array_search($path, $caches['archives'][$year][$month]['blogs']);
            if ($index !== false) {
                unset($caches['archives'][$year][$month]['blogs'][$index]);
                $caches['archives'][$year][$month]['blogs'] = array_values($caches['archives'][$year][$month]['blogs']);
                $caches['archives'][$year][$month]['count'] = count($caches['archives'][$year][$month]['blogs']);
            }
        }
        
        // 更新博客总数
        $caches['total_blogs'] = count($caches['blogs']);
        $caches['last_update'] = date('Y-m-d H:i:s');
        
        // 保存缓存
        FileManager::savePhpConfigFile($cachePath, $caches);
        
        return true;
    }
    
    // 获取博客简介（摘要）
    public function getExcerpt($length = 200)
    {
        $content = strip_tags($this->content);
        if (mb_strlen($content) <= $length) {
            return $content;
        }
        return mb_substr($content, 0, $length) . '...';
    }
    
    // 获取博客URL
    public function getUrl()
    {
        return Router::getUrl('blog/index', ['id' => $this->id]);
    }
    
    // 转换为数组
    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => $this->content,
            'author' => $this->author,
            'category' => $this->category,
            'tags' => $this->tags,
            'cover_image' => $this->cover_image,
            'date' => $this->created_at,
            'updated_at' => $this->updated_at,
            'path' => $this->path,
            'is_private' => $this->is_private,
            'is_independent' => $this->is_independent
        ];
    }
}