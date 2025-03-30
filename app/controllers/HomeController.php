<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * 首页控制器
 * 
 * 处理网站首页和相关功能
 */
class HomeController extends Controller
{
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        // 设置默认布局
        $this->setLayout('default');
    }
    
    /**
     * 首页方法
     * 
     * @return array 渲染信息
     */
    public function index()
    {
        // 设置页面标题
        $this->setTitle('欢迎访问 TinyBlog');
        
        // 设置视图变量
        $this->set('content', '这是一个小型博客系统');
        
        // 渲染视图
        return $this->render('home/index');
    }
    
    /**
     * 关于页方法
     * 
     * @return array 渲染信息
     */
    public function about()
    {
        // 设置页面标题
        $this->setTitle('关于 TinyBlog');
        
        // 设置视图变量
        $this->set('content', 'TinyBlog是一个简单的博客系统');
        
        // 渲染视图
        return $this->render('home/about');
    }
} 