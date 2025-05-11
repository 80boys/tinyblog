<?php

namespace App\Actions;

use App\Models\BlogsModel;
use App\Models\BlogModel;
use App\Utils\FileManager;
use App\Core\Action;
use App\Core\Router;
use App\Models\CategoryModel;
use App\Models\SettingsModel;
require_once  __DIR__ . '/../utils/Qiniu/functions.php';
require_once  __DIR__ . '/../utils/Qiniu/Http/Middleware/Middleware.php';

/**
 * 管理员Action
 */
class Admin extends Action
{

    public function initialize()
    {
        $this->injectCommonViewData();
    }

    private function injectCommonViewData()
    {
        $settings = SettingsModel::getAll(true);
        $this->set('site_name', $settings['site_name']);
        $this->set('site_description', $settings['site_description']);
        $this->set('author', $settings['author']);
        $this->set('footer_text', $settings['footer_text']);
        $this->set('beian_number', $settings['beian_number']);
        $this->set('analytics_code', $settings['analytics_code']);
        $this->set('contact_email', $settings['contact_email']);
        $this->set('wechat_id', $settings['wechat_id']);
        $this->set('bucket_domain', $settings['qiniu_domain']);
        $this->set('bucket_accelerate_domain', $settings['qiniu_accelerate_domain']);

        $this->checkLogin();
    }

    public function category()
    {
        $this->set('categories', CategoryModel::getAll());
        $this->setLayout('admin');
        $this->setTitle('分类管理');
        $this->render('home/category');
    }

    public function edit($id = null)
    {
        if ($id) {
            // 如果ID是数组（来自URL路径参数），将其合并为字符串
            if (is_array($id)) {
                $id = implode('/', $id);
            }
            $blog = BlogModel::findById($id, true);
            if ($blog) {
                $this->set('blog', $blog);
            }
        }

        $this->set('categories', CategoryModel::getAll());
        $this->setLayout('admin');
        $this->setTitle('编辑博客');
        $this->render('home/edit');
    }

    public function settings()
    {
        $this->set('settings', SettingsModel::getAll());
        $this->setLayout('admin');
        $this->setTitle('网站设置');
        $this->render('home/settings');
    }

    /**
     * 保存网站设置
     * @return bool 是否保存成功
     */
    public function saveSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        $settings = [
            'site_name' => $_POST['site_name'] ?? '',
            'site_description' => $_POST['site_description'] ?? '',
            'author' => $_POST['author'] ?? '',
            'default_keywords' => $_POST['default_keywords'] ?? '',
            'contact_email' => $_POST['contact_email'] ?? '',
            'wechat_id' => $_POST['wechat_id'] ?? '',
            'qiniu_access_key' => $_POST['qiniu_access_key'] ?? '',
            'qiniu_secret_key' => $_POST['qiniu_secret_key'] ?? '',
            'qiniu_bucket' => $_POST['qiniu_bucket'] ?? '',
            'qiniu_accelerate_domain' => $_POST['qiniu_accelerate_domain'] ?? '',
            'qiniu_domain' => $_POST['qiniu_domain'] ?? '',
            'admin_username' => $_POST['admin_username'] ?? '',
            'admin_password' => $_POST['admin_password'] ?? '',
            'beian_number' => $_POST['beian_number'] ?? '',
            'footer_text' => $_POST['footer_text'] ?? '',
            'analytics_code' => $_POST['analytics_code'] ?? ''
        ];

        return SettingsModel::save($settings);
    }

    public function index()
    {
        // 获取当前页码
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, $currentPage); // 确保页码至少为1
        $pageSize = 10; // 每页显示的博客数量
        
        // 获取筛选条件
        $filters = [];
        
        // 分类筛选
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        
        // 搜索功能
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        // 排序功能
        if (isset($_GET['sort_by']) && !empty($_GET['sort_by'])) {
            $filters['sort_by'] = $_GET['sort_by'];
        } else {
            $filters['sort_by'] = 'date'; // 默认按日期排序
        }
        
        if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['asc', 'desc'])) {
            $filters['sort_order'] = $_GET['sort_order'];
        } else {
            $filters['sort_order'] = 'desc'; // 默认降序
        }

        // 获取博客列表数据
        $blogs = $this->getBlogList($currentPage, $pageSize, $filters);
        
        // 构建分页URL模式，保留所有筛选参数
        $urlParams = [];
        if (isset($filters['category'])) $urlParams[] = 'category=' . urlencode($filters['category']);
        if (isset($filters['search'])) $urlParams[] = 'search=' . urlencode($filters['search']);
        if (isset($filters['sort_by'])) $urlParams[] = 'sort_by=' . urlencode($filters['sort_by']);
        if (isset($filters['sort_order'])) $urlParams[] = 'sort_order=' . urlencode($filters['sort_order']);
        
        $urlPattern = Router::getUrl('admin/index');
        if (!empty($urlParams)) {
            $urlPattern .= '?' . implode('&', $urlParams) . '&page={page}';
        } else {
            $urlPattern .= '?page={page}';
        }
        
        // 获取所有分类，用于筛选
        $categories = CategoryModel::getAllWithStats();
        
        // 设置数据到视图
        $this->set('blogs', $blogs['items']);
        $this->set('totalPages', ceil($blogs['total'] / $pageSize));
        $this->set('currentPage', $currentPage);
        $this->set('urlPattern', $urlPattern);
        $this->set('categories', $categories);
        $this->set('filters', $filters);
        $this->set('totalBlogs', $blogs['total']);

        $this->setLayout('admin');
        $this->setTitle('博客管理');
        $this->render('home/index');
    }

    /**
     * 检查是否已登录
     * @return bool
     */
    private function checkLogin()
    {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            // 如果未登录且不是登录页面，重定向到登录页面
            $currentUrl = $_SERVER['REQUEST_URI'];
            if (!strpos($currentUrl, 'login')) {
                $this->redirect(Router::getUrl('blog/login'));
                exit;
            }
            return false;
        }
        return true;
    }

    /**
     * 管理员登出
     * @return bool
     */
    public function logout()
    {
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_last_login']);

        session_destroy();
        $this->redirect(Router::getUrl('blog/login'));
    }

    /**
     * 修改管理员密码
     * @param string $oldPassword 旧密码
     * @param string $newPassword 新密码
     * @return bool 是否修改成功
     */
    public function changePassword($oldPassword, $newPassword)
    {
        return SettingsModel::changePassword($oldPassword, $newPassword);
    }

    /**
     * 获取博客列表 (后台版本)
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @param array $filters 过滤条件
     * @return array 博客列表
     */
    public function getBlogList($page = 1, $pageSize = 20, $filters = [])
    {
        // 获取所有博客数据
        $caches = BlogsModel::getCaches();
        $blogs = $caches['blogs'];

        // 应用筛选条件
        if (!empty($filters)) {
            $blogs = array_filter($blogs, function ($blog) use ($filters) {
                // 按分类筛选
                if (isset($filters['category']) && !empty($filters['category'])) {
                    if ($blog['category'] !== $filters['category']) {
                        return false;
                    }
                }
                
                // 按搜索条件筛选
                if (isset($filters['search']) && !empty($filters['search'])) {
                    $search = strtolower($filters['search']);
                    $title = strtolower($blog['title'] ?? '');
                    $subtitle = strtolower($blog['subtitle'] ?? '');
                    
                    if (strpos($title, $search) === false && strpos($subtitle, $search) === false) {
                        return false;
                    }
                }
                
                return true;
            });
        }

        // 应用排序
        $sortBy = $filters['sort_by'] ?? 'date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        uasort($blogs, function ($a, $b) use ($sortBy, $sortOrder) {
            $valA = $a[$sortBy] ?? '';
            $valB = $b[$sortBy] ?? '';
            
            // 日期特殊处理
            if ($sortBy === 'date') {
                $valA = strtotime($valA);
                $valB = strtotime($valB);
            }
            
            // 根据排序方向比较
            if ($sortOrder === 'asc') {
                return $valA <=> $valB;
            } else {
                return $valB <=> $valA;
            }
        });
        
        // 计算分页数据
        $total = count($blogs);
        $offset = ($page - 1) * $pageSize;
        $items = array_slice($blogs, $offset, $pageSize);
        
        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize
        ];
    }

    /**
     * 获取单个博客详情
     * @param string $id 博客ID
     * @return BlogModel|null 博客对象或null
     */
    public function getBlogDetail($id)
    {
        return BlogModel::findById($id);
    }

    /**
     * 保存博客
     * @param array $data 博客数据
     * @return bool 是否保存成功
     */
    public function saveBlogs($data)
    {
        // 处理表单数据
        $blogData = [
            'title' => $data['blog_title'] ?? '',
            'subtitle' => $data['blog_subtitle'] ?? '',
            'content' => $data['blog_content'] ?? '',
            'author' => $data['blog_author'] ?? 'admin',
            'is_private' => $data['is_private'] ?? false,
            'is_independent' => $data['is_independent'] ?? false,
            'category' => $data['blog_category'] ?? '未分类',
            'tags' => empty($data['blog_tags']) ? [] : array_map('trim', explode(',', $data['blog_tags'])),
            'path' => $data['blog_path'] ?? null
        ];
        
        if (isset($_FILES['blog_attachment'])
            && isset($_FILES['blog_attachment']['error'])
            && $_FILES['blog_attachment']['error'] === UPLOAD_ERR_OK
        ) {
            $fileUrl = $this->uploadAttachment($_FILES['blog_attachment']);
            if ($fileUrl) {
                $blogData['blog_attachment'] = $fileUrl;
            }
        }

        // 如果有 path，从中提取 ID
        $id = null;
        if (!empty($blogData['path'])) {
            $id = basename($blogData['path'], '.php');
        }
        
        // 创建或获取博客对象
        $blog = $id ? BlogModel::findById($id) : new BlogModel();

        if (!$blog) {
            $blog = new BlogModel();
        }

        // 设置博客属性
        $blog->setTitle($blogData['title']);
        $blog->setSubtitle($blogData['subtitle']);
        $blog->setContent($blogData['content']);
        $blog->setCategory($blogData['category']);
        $blog->setTags($blogData['tags']);
        $blog->setAuthor($blogData['author']);
        $blog->setIndependent($blogData['is_independent']);
        $blog->setPrivate($blogData['is_private']);
        
        if (isset($blogData['blog_attachment'])) {
            $blog->setBlogAttachment($blogData['blog_attachment']);
        }

        // 如果是已有博客，保持原有路径
        if (!empty($blogData['path'])) {
            $blog->setPath($blogData['path']);
        }

        // 保存博客并返回布尔值
        $result = $blog->save();
        return $result !== false;
    }

    /**
     * 上传附件 通过七牛云
     * @param array $file $_FILES数组中的文件
     * @return string|bool 成功返回图片URL，失败返回false
     */
    public function uploadAttachment($file)
    {
        if (!isset($file['name']) 
            || !isset($file['tmp_name']) 
            || !isset($file['error'])
            || !isset($file['size'])
            || !isset($file['type'])
        ) {
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $settings = SettingsModel::getAll();
        $accessKey = $settings['qiniu_access_key'];
        $secretKey = $settings['qiniu_secret_key'];
        $bucket = $settings['qiniu_bucket'];

        $fileName = $file['name'];
        $filePath = $file['tmp_name'];
        
        $auth = new \Qiniu\Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $uploadManager = new \Qiniu\Storage\UploadManager();
        list($ret, $err) = $uploadManager->putFile($token, "mapleBridge/" . time() . "-" . $fileName, $filePath);
        if ($err === null) {
            return $settings["qiniu_domain"] . "/" . $ret['key'];
        }
        return false;
    }

    /**
     * 删除博客
     * @param string|array $id 博客ID
     * @return bool 是否删除成功
     */
    public function deleteBlog($id)
    {
        // 如果ID是数组（来自URL路径参数），将其合并为字符串
        if (is_array($id)) {
            $id = implode('/', $id);
        }

        $blog = BlogModel::findById($id);
        if ($blog) {
            return $blog->delete();
        }
        return false;
    }

    /**
     * 添加或更新分类
     * @param string $name 分类名称
     * @param string $oldName 旧分类名称 (用于重命名)
     * @return bool 操作是否成功
     */
    public function saveCategory($name, $oldName = '')
    {
        if (empty($name)) {
            return false;
        }

        // 重命名分类
        if (!empty($oldName) && $oldName != $name) {
            return CategoryModel::rename($oldName, $name);
        }

        // 添加新分类
        return CategoryModel::add($name);
    }

    /**
     * 删除分类
     * @param string $name 分类名称
     * @param string $moveTo 将博客移动到的分类
     * @return bool 操作是否成功
     */
    public function deleteCategory($name, $moveTo = '未分类')
    {
        return CategoryModel::delete($name, $moveTo);
    }

    /**
     * 获取系统信息
     * @return array 系统信息
     */
    public function getSystemInfo()
    {
        $caches = BlogsModel::getCaches();

        return [
            'total_blogs' => $caches['total_blogs'],
            'total_categories' => count($caches['categories']),
            'total_tags' => count($caches['tags']),
            'last_update' => $caches['last_update'],
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'storage_path' => PROJECT_ROOT . '/content',
            'cache_file' => PROJECT_ROOT . '/content/caches.php'
        ];
    }

    /**
     * 刷新缓存
     * @return bool 是否刷新成功
     */
    public function refreshCache()
    {
        return BlogsModel::rebuildCache();
    }

    /**
     * 导出博客数据
     * @return string 数据文件路径
     */
    public function exportData()
    {
        $caches = BlogsModel::getCaches();
        $blogs = [];

        // 收集所有博客数据
        foreach ($caches['blogs'] as $path => $blog) {
            $fullBlog = BlogModel::findByPath($path);
            if ($fullBlog) {
                $blogs[] = $fullBlog->toArray();
            }
        }

        // 创建导出数据
        $exportData = [
            'blogs' => $blogs,
            'categories' => $caches['categories'],
            'tags' => $caches['tags'],
            'export_date' => date('Y-m-d H:i:s'),
            'version' => '1.0'
        ];

        // 保存到文件
        $exportDir = PROJECT_ROOT . '/public/exports/';
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        $filename = 'blog_export_' . date('Ymd_His') . '.json';
        $exportPath = $exportDir . $filename;

        if (file_put_contents($exportPath, json_encode($exportData, JSON_PRETTY_PRINT))) {
            return '/exports/' . $filename;
        }

        return false;
    }

    /**
     * 导入博客数据
     * @param string $filePath 导入文件路径
     * @param bool $overwrite 是否覆盖现有内容
     * @return bool 是否导入成功
     */
    public function importData($filePath, $overwrite = false)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $importData = json_decode(file_get_contents($filePath), true);
        if (!$importData || !isset($importData['blogs'])) {
            return false;
        }

        // 导入博客
        foreach ($importData['blogs'] as $blogData) {
            // 如果不覆盖，跳过已存在的博客
            if (!$overwrite && BlogModel::findById($blogData['id'])) {
                continue;
            }

            $blog = new BlogModel($blogData);
            $blog->save();
        }

        // 重建缓存
        return BlogsModel::rebuildCache();
    }

    /**
     * 获取博客设置
     * @return array 设置
     */
    public function getBlogSettings()
    {
        $settingsPath = PROJECT_ROOT . '/content/settings.php';
        return FileManager::readPhpConfigFile($settingsPath, [
            'site_title' => '我的博客',
            'site_description' => '这是一个简单的博客系统',
            'site_keywords' => 'blog,php',
            'posts_per_page' => 10,
            'show_author' => true,
            'date_format' => 'Y-m-d'
        ]);
    }

    /**
     * 保存博客设置
     * @param array $settings 设置数据
     * @return bool 是否保存成功
     */
    public function saveBlogSettings($settings)
    {
        $settingsPath = PROJECT_ROOT . '/content/settings.php';
        return FileManager::savePhpConfigFile($settingsPath, $settings);
    }

    public function systemInfo()
    {
        $systemInfo = $this->getSystemInfo();
        $this->set('systemInfo', $systemInfo);
        $this->setLayout('admin');
        $this->setTitle('系统信息');
        $this->render('home/system_info');
    }

    /**
     * 获取七牛云上传token
     * @return array 包含token的JSON响应
     */
    public function getQiniuToken()
    {
        // 获取七牛云配置
        $settings = SettingsModel::getAll();
        $accessKey = $settings['qiniu_access_key'];
        $secretKey = $settings['qiniu_secret_key'];
        $bucket = $settings['qiniu_bucket'];

        // 如果配置不完整，返回错误
        if (empty($accessKey) || empty($secretKey) || empty($bucket)) {
            header('Content-Type: application/json');
            return $this->json(['error' => '七牛云配置不完整']);
        }

        // 构建上传策略
        $policy = array(
            'scope' => $bucket,
            'deadline' => time() + 3600, // token有效期为1小时
        );

        // 生成上传token
        $encodedPolicy = base64_encode(json_encode($policy));
        $sign = hash_hmac('sha1', $encodedPolicy, $secretKey, true);
        $encodedSign = base64_encode($sign);
        $token = $accessKey . ':' . $encodedSign . ':' . $encodedPolicy;

        // 返回JSON响应
        header('Content-Type: application/json');
        return $this->json(['token' => $token]);
    }
}
