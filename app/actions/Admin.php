<?php

namespace App\Actions;

use App\Models\BlogsModel;
use App\Models\BlogModel;
use App\Utils\FileManager;
use App\Core\Action;
use App\Core\Router;
use App\Models\CategoryModel;
use App\Models\SettingsModel;

/**
 * 管理员Action
 */
class Admin extends Action
{
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
        $pageSize = 10; // 每页显示的博客数量

        // 获取博客列表数据
        $result = $this->getBlogList($currentPage, $pageSize);

        // 设置数据到视图
        $this->set('blogs', $result['items']);
        $this->set('totalPages', $result['total_pages']);
        $this->set('currentPage', $currentPage);

        $this->setLayout('admin');
        $this->setTitle('管理员');
        $this->render('home/index');
    }

    public function __construct()
    {
        // 确保已登录
        // $this->checkLogin();
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
                $this->redirect(Router::getUrl('admin/login'));
                exit;
            }
            return false;
        }
        return true;
    }

    /**
     * 管理员登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool 是否登录成功
     */
    public function login()
    {
        $this->setTitle('管理员登录');
        $this->render('home/login');
    }

    /**
     * 管理员登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool 是否登录成功
     */
    public function doLogin($username, $password)
    {
        if (SettingsModel::validateAdminLogin($username, $password)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_last_login'] = date('Y-m-d H:i:s');
            $this->redirect(Router::getUrl('admin/index'));
            return true;
        }
        return false;
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
        return true;
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
     * @return array 博客列表
     */
    public function getBlogList($page = 1, $pageSize = 20)
    {
        // 获取所有博客数据
        $caches = BlogsModel::getCaches();
        $blogs = $caches['blogs'];

        // 按日期降序排序
        uasort($blogs, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // 计算分页
        $totalItems = count($blogs);
        $totalPages = ceil($totalItems / $pageSize);
        $offset = ($page - 1) * $pageSize;

        // 获取当前页的博客
        $items = array_slice($blogs, $offset, $pageSize, true);

        return [
            'items' => $items,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'total_items' => $totalItems
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
            'category' => $data['blog_category'] ?? '未分类',
            'tags' => empty($data['blog_tags']) ? [] : array_map('trim', explode(',', $data['blog_tags'])),
            'path' => $data['blog_path'] ?? null
        ];

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

        // 如果是已有博客，保持原有路径
        if (!empty($blogData['path'])) {
            $blog->setPath($blogData['path']);
        }

        // 保存博客并返回布尔值
        $result = $blog->save();
        return $result !== false;
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
     * 上传图片
     * @param array $file $_FILES数组中的文件
     * @return string|bool 成功返回图片URL，失败返回false
     */
    public function uploadImage($file)
    {
        $uploadDir = PROJECT_ROOT . '/public/uploads/images/';

        // 确保上传目录存在
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 检查文件类型
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        // 生成唯一文件名
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid() . '.' . $extension;
        $targetFile = $uploadDir . $newFilename;

        // 移动上传的文件
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/uploads/images/' . $newFilename;
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
}
