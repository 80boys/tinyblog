<?php
namespace App\Core;

/**
 * 视图类
 * 
 * 专门负责视图的渲染和管理
 */
class View
{
    /**
     * 视图目录
     * @var string
     */
    private $viewsDir;

    /**
     * 视图变量
     * @var array
     */
    private $viewData = [];
    
    /**
     * 布局文件
     * @var string|null
     */
    private $layout = null;
    
    /**
     * 页面标题
     * @var string
     */
    private $title = '';
    
    /**
     * 构造函数
     * 
     * @param string|null $viewsDir 视图目录
     */
    public function __construct(?string $viewsDir = null)
    {       
        $this->viewsDir = $viewsDir ?: __DIR__ . '/../views/';
        // 确保目录以斜杠结尾
        if (substr($this->viewsDir, -1) !== "\\" && substr($this->viewsDir, -1) !== "/") {
            $this->viewsDir .= DIRECTORY_SEPARATOR;
        }
    }

    /**
     * 获取备案号
     * 
     * @return string 返回备案号
     */
    public function getBeianNumber()
    {
        return $this->get('beianNumber', '默认备案号');
    }


    /**
     * 获取网站名称
     * 
     * @return string 返回网站名称
     */
    public function getSiteName()
    {
        return $this->get('siteName', '默认网站名称');
    }

    /**
     * 获取网站描述
     * 
     * @return string 返回网站描述
     */
    public function getSiteDescription()
    {
        return $this->get('siteDescription', '默认网站描述');
    }

    /**
     * 获取用户主题设置
     * 
     * @return string|null 返回主题名称,默认为null
     */
    public function getUserTheme()
    {
        return isset($_COOKIE['theme']) ? $_COOKIE['theme'] : "light";
    }

    /**
     * 处理资源路径
     * 
     * @param string $path 资源路径
     * @return string 完整的资源URL
     */
    public function asset($path)
    {        
        return  $path;
        //return  $this->get('baseUrl') . '/' . $path;
    }

    /**
     * 获取分析代码
     * 
     * @return string 返回分析代码
     */
    public function getAnalyticsCode()
    {
        return $this->get('analyticsCode', '');
    }
    
    /**
     * 设置视图变量
     * 
     * @param string|array $name 变量名或变量数组
     * @param mixed $value 变量值
     * @return $this
     */
    public function set($name, $value = null)
    {
        // 如果$name是数组，则批量设置变量
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
    public function get($name, $default = null)
    {
        return isset($this->viewData[$name]) ? $this->viewData[$name] : $default;
    }
    
    /**
     * 设置视图目录
     * 
     * @param string $dir 视图目录
     * @return $this
     */
    public function setViewsDir($dir)
    {
        $this->viewsDir = $dir;
        
        // 确保目录以斜杠结尾
        if (substr($this->viewsDir, -1) !== DIRECTORY_SEPARATOR) {
            $this->viewsDir .= DIRECTORY_SEPARATOR;
        }
        
        return $this;
    }
    
    /**
     * 设置布局文件
     * 
     * @param string|null $layout 布局文件名，不含扩展名
     * @return $this
     */
    public function setLayout($layout)
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
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * 获取页面标题
     * 
     * @return string 返回页面标题
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * 渲染视图
     * 
     * @param string $view 视图文件名，不含扩展名
     * @param array $data 视图变量
     * @return string 渲染后的内容
     */
    public function render($view, $data = [])
    {
        // 合并视图变量
        $data = array_merge($this->viewData, $data);
        
        // 添加标题
        if (!isset($data['title']) && !empty($this->title)) {
            $data['title'] = $this->title;
        }
        
        // 渲染视图内容
        $content = $this->renderFile($view, $data);
       
        // 如果有布局文件，则渲染布局
        if ($this->layout) {
            // 将视图内容作为变量传给布局
            $data['content'] = $content;
            return $this->renderFile('layouts/' . $this->layout, $data);
        }
        
        return $content;
    }
    
    /**
     * 渲染视图文件
     * 
     * @param string $view 视图文件名，不含扩展名
     * @param array $data 视图变量
     * @return string 渲染后的内容
     * @throws \Exception 视图文件不存在时抛出异常
     */
    protected function renderFile($view, $data = [])
    {
        // 确定视图文件路径
        $viewFile = $this->viewsDir . $view . '.php';
        
        // 检查视图文件是否存在
        if (!file_exists($viewFile)) {
            
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        // 提取变量到当前作用域
        extract($data);
        
        // 开始输出缓冲
        ob_start();
        
        // 包含视图文件
        include $viewFile;
        
        // 返回渲染结果
        return ob_get_clean();
    }
    
    /**
     * 渲染错误页面
     * 
     * @param int $code HTTP状态码
     * @param string $message 错误消息
     * @return string 渲染后的内容
     */
    public function renderError($code, $message = '')
    {
        // 设置HTTP状态码
        http_response_code($code);
        
        // 准备错误数据
        $errorData = [
            'code' => $code,
            'message' => $message
        ];
        
        // 尝试加载错误视图
        $errorView = "errors/{$code}";
        
        try {
            return $this->render($errorView, $errorData);
        } catch (\Exception $e) {
            // 如果找不到特定的错误视图，尝试使用通用错误视图
            try {
                return $this->render('errors/error', $errorData);
            } catch (\Exception $e) {
                // 如果连通用错误视图都没有，则返回简单的错误信息
                return "Error {$code}: {$message}";
            }
        }
    }
    
    /**
     * 部分视图渲染（包含）
     * 
     * @param string $view 部分视图文件名
     * @param array $data 视图变量
     * @return string 渲染后的内容
     */
    public function partial($view, $data = [])
    {
        return $this->renderFile('partials/' . $view, $data);
    }
    
    /**
     * 直接输出部分视图
     * 
     * @param string $view 部分视图文件名
     * @param array $data 视图变量
     */
    public function renderPartial($view, $data = [])
    {
        echo $this->partial($view, $data);
    }
}
