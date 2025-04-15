<?php
namespace App\Actions;
use App\Models\BlogsModel;
use App\Models\BlogModel;
use App\Core\Action;

/**
 * 博客Action
 */
class Blog extends Action
{

    public function __construct()
    {
        
    }
    
    /**
     * 首页
     * @return string 首页内容
     */
    public function index()
    {
        $this->setLayout('default');
        $this->setTitle('博客列表');
        $blogs = BlogsModel::getList();
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
     * @param int $id 博客ID
     * @return BlogModel|null 博客详情
     */
    public function getBlogDetail($id)
    {
        $this->setLayout('blog');
        $this->setTitle('博客详情');
        $blog = BlogModel::findById($id, true);
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
     * 根据分类获取博客列表
     * @param string $category 分类名
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array 博客列表
     */
    public function getBlogsByCategory($category, $page = 1, $pageSize = 10)
    {
        $filters = [
            'category' => $category,
            'include_private' => false,
            'include_independent' => false
        ];
        
        return BlogsModel::getList($page, $pageSize, $filters);
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
