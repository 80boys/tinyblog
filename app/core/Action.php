<?php

namespace App\Core;

use App\Models\SettingsModel;

/**
 * Action基类
 * 
 * 所有控制器动作的基类，提供共享功能
 */
abstract class Action
{
    /**
     * 布局名称
     * @var string|null
     */
    protected $layout = null;

    /**
     * 页面标题
     * @var string
     */
    protected $title = '';

    /**
     * 视图变量，在控制器内部暂存
     * @var array
     */
    protected $viewData = [];

    /**
     * 渲染变量，在控制器内部暂存
     * @var array
     */
    protected $renderData = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        // 初始化Action
        $this->initialize();
    }

    /**
     * 初始化方法，可由子类重写
     */
    protected function initialize()
    {
        // 子类可以重写此方法来进行初始化
    }

    /**
     * 设置视图变量
     * 
     * @param string|array $name 变量名或变量数组
     * @param mixed $value 变量值
     * @return $this
     */
    protected function set($name, $value = null)
    {
        // 暂存视图变量在控制器内部
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->viewData[$k] = $v;
            }
        } else {
            $this->viewData[$name] = $value;
        }

        return $this;
    }

    /**
     * 获取视图变量
     * 
     * @param string $name 变量名
     * @param mixed $default 默认值
     * @return mixed
     */
    protected function get($name, $default = null)
    {
        return isset($this->viewData[$name]) ? $this->viewData[$name] : $default;
    }

    /**
     * 设置布局
     * 
     * @param string|null $layout 布局名称
     * @return $this
     */
    protected function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * 设置页面标题
     * 
     * @param string $title 页面标题
     * @return $this
     */
    protected function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 获取站点配置
     * @param string|null $key 配置键名，为null时返回所有配置
     * @param mixed $default 默认值
     * @return mixed 配置值
     */
    protected function getSetting($key = null, $default = null)
    {
        return $key === null ? SettingsModel::getAll() : SettingsModel::get($key, $default);
    }

    /**
     * 注入通用的视图数据
     */
    protected function injectCommonViewData()
    {
        // 注入站点基本信息
        $this->set('site_name', $this->getSetting('site_name', '我的博客'));
        $this->set('site_description', $this->getSetting('site_description'));
        $this->set('author', $this->getSetting('author'));
        $this->set('footer_text', $this->getSetting('footer_text'));
        $this->set('beian_number', $this->getSetting('beian_number'));
        $this->set('analytics_code', $this->getSetting('analytics_code'));
        $this->set('contact_email', $this->getSetting('contact_email'));
        $this->set('wechat_id', $this->getSetting('wechat_id'));
    }

    /**
     * 渲染视图
     * 
     * @param string $view 视图名称，不含扩展名
     * @param array $data 附加数据
     * @return array 包含视图信息的数组
     */
    protected function render($view, $data = [])
    {
        // 注入通用数据
        $this->injectCommonViewData();

        // 合并视图变量
        $data = array_merge($this->viewData, $data);

        // 返回包含视图信息的数组，由调度器处理
        $this->renderData =  [
            'view' => $view,
            'data' => $data,
            'layout' => $this->layout,
            'title' => $this->title
        ];
        return $this->renderData;
    }

    /**
     * 重定向到指定URL
     * 
     * @param string $url 重定向的URL
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * 返回JSON响应
     * 
     * @param mixed $data 要转换为JSON的数据
     * @param int $statusCode HTTP状态码
     * @return string JSON数据
     */
    protected function json($data, $statusCode = 200)
    {
        // 设置HTTP状态码
        http_response_code($statusCode);

        // 设置Content-Type头
        header('Content-Type: application/json');

        // 返回JSON编码后的数据
        return json_encode($data);
    }

    /**
     * 获取所有视图变量
     * 
     * @return array 视图变量数组
     */
    public function getViewData()
    {
        return $this->viewData;
    }

    /**
     * 获取所有渲染变量
     * 
     * @return array 渲染变量数组
     */
    public function getRenderData()
    {
        return $this->renderData;
    }
}
