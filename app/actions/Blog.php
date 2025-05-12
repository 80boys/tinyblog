<?php

namespace App\Actions;

use App\Models\BlogsModel;
use App\Models\BlogModel;
use App\Core\Action;
use App\Core\Router;
use App\Models\SettingsModel;


/**
 * 博客Action
 */
class Blog extends Action
{

    public function initialize()
    {
        // 注入通用数据
        $this->injectCommonViewData();
    }

    /**
     * 注入通用的视图数据
     */
    protected function injectCommonViewData()
    {
        // 注入站点基本信息
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
        $this->setLayout('common');
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
     * 首页/分类/搜索页面
     * @return string 页面内容
     */
    public function index()
    {
        $this->setLayout('default');

        // 获取当前页码
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // 确保页码至少为1

        // 设置每页显示的博客数量
        $pageSize = 5;

        // 创建过滤条件
        $filters = [
            'include_private' => false,
            'include_independent' => false
        ];

        // 检查是否有搜索请求
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        if (!empty($search)) {
            $filters['search'] = $search;
            $this->setTitle('搜索: ' . $search);
            $this->set('searchQuery', $search);
            $urlPattern = '?search=' . urlencode($search) . '&page={page}';
        }
        // 检查是否有分类请求
        else if (isset($_GET['category'])) {
            $category = trim($_GET['category']);
            $filters['category'] = $category;
            $this->setTitle('分类: ' . $category);
            $this->set('categoryQuery', $category);
            $urlPattern = '?category=' . urlencode($category) . '&page={page}';
        } else {
            $this->setTitle($this->get('site_name', '博客列表'));
            $urlPattern = '?page={page}';
        }

        // 获取带有分页的博客列表
        $blogs = BlogsModel::getList($page, $pageSize, $filters);

        // 计算总页数
        $totalPages = ceil($blogs['total'] / $pageSize);

        // 传递分页数据到视图
        $this->set('currentPage', $page);
        $this->set('totalPages', $totalPages);
        $this->set('urlPattern', $urlPattern);

        $this->render('blog/index', ['blogs' => $blogs]);
    }

    /**
     * 获取博客列表
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array 博客列表数据
     */
    public function getBlogList($page = 1, $pageSize = 10)
    {
        $filters = [
            'include_private' => false,
            'include_independent' => false
        ];

        return BlogsModel::getList($page, $pageSize, $filters);
    }

    /**
     * 获取博客详情
     * @param string $id 博客ID
     * @return BlogModel|null 博客详情
     */
    public function getBlogDetail($id = null)
    {
        $this->setLayout('blog');
        $this->setTitle('博客详情');

        if (is_array($id)) {
            $id = implode('/', $id);
        }

        $blog = BlogModel::findById($id, true);

        // 如果找到博客,将 Markdown 内容转换为 HTML
        if ($blog && isset($blog['content'])) {
            $parsedown = new \App\Utils\ParsedownExtra();
            $parsedown->setSafeMode(true); // 启用安全模式
            $blog['content'] = $parsedown->text($blog['content']);
        }

        $this->render('blog/detail', ['blog' => $blog]);
    }

    /**
     * 获取博客分类列表
     * @return array 分类列表
     */
    public function getCategories()
    {
        return BlogsModel::getCategories();
    }

    /**
     * 获取博客标签列表
     * @return array 标签列表
     */
    public function getTags()
    {
        return BlogsModel::getTags();
    }


    /**
     * 根据标签获取博客列表
     * @param string $tag 标签名
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array 博客列表
     */
    public function getBlogsByTag($tag, $page = 1, $pageSize = 10)
    {
        $filters = [
            'tag' => $tag,
            'include_private' => false,
            'include_independent' => false
        ];

        return BlogsModel::getList($page, $pageSize, $filters);
    }

    /**
     * 搜索博客
     * @param string $keyword 搜索关键词
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array 搜索结果
     */
    public function searchBlogs($keyword, $page = 1, $pageSize = 10)
    {
        $filters = [
            'search' => $keyword,
            'include_private' => false,
            'include_independent' => false
        ];

        return BlogsModel::getList($page, $pageSize, $filters);
    }

    /**
     * 获取最近的博客
     * @param int $limit 获取数量
     * @return array 博客列表
     */
    public function getRecentBlogs($limit = 5)
    {
        return BlogsModel::getRecentBlogs($limit);
    }

    /**
     * 获取相关博客
     * @param string $blogId 博客ID
     * @param int $limit 获取数量
     * @return array 相关博客列表
     */
    public function getRelatedBlogs($blogId, $limit = 5)
    {
        return BlogsModel::getRelatedBlogs($blogId, $limit);
    }

    /**
     * 获取博客归档
     * @return array 归档数据
     */
    public function getArchives()
    {
        return BlogsModel::getArchives();
    }

    /**
     * 根据年月获取博客列表
     * @param int $year 年份
     * @param int $month 月份
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array 博客列表
     */
    public function getBlogsByDate($year, $month = null, $page = 1, $pageSize = 10)
    {
        $filters = [
            'year' => $year,
            'include_private' => false,
            'include_independent' => false
        ];

        if ($month) {
            $filters['month'] = $month;
        }

        return BlogsModel::getList($page, $pageSize, $filters);
    }
}
