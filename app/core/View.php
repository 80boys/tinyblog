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
        return $this->get('beian_number', '默认备案号');
    }


    /**
     * 获取网站名称
     * 
     * @return string 返回网站名称
     */
    public function getSiteName()
    {
        return $this->get('site_name', '默认网站名称');
    }

    /**
     * 获取网站描述
     * 
     * @return string 返回网站描述
     */
    public function getSiteDescription()
    {
        return $this->get('site_description', '默认网站描述');
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
    }

    /**
     * 获取分析代码
     * 
     * @return string 返回分析代码
     */
    public function getAnalyticsCode()
    {
        return $this->get('analytics_code', '');
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
        $this->viewData['title'] = $title;
    }

    /**
     * 获取页面标题
     * 
     * @return string 返回页面标题
     */
    public function getTitle()
    {
        return $this->viewData['title'] ?? '默认页面标题';
    }

    /** 
     * 获取页面描述
     * 
     * @return string 返回页面描述
     */
    public function getDescription()    
    {
        return $this->viewData['description'] ?? '默认页面描述';
    }

    /**
     * 获取页面关键词
     * 
     * @return string 返回页面关键词
     */
    public function getKeywords()
    {
        return $this->viewData['keywords'] ?? '默认页面关键词';
    }

    /**
     * 获取页面作者
     * 
     * @return string 返回页面作者
     */
    public function getAuthor()
    {
        return $this->viewData['author'] ?? '默认页面作者';
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
        $this->viewData = $data = array_merge($this->viewData, $data);

        // 渲染视图内容
        $content = $this->renderFile($view, $data);

        // 如果有布局文件，则渲染布局
        if ($this->layout) {
            // 将视图内容作为变量传给布局
            $this->viewData['content'] = $content;
            return $this->renderFile('layouts/' . $this->layout, $this->viewData);
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
            throw new \Exception("视图文件不存在: {$viewFile}");
        }

        // 提取变量到当前作用域
        extract($data);

        // 保存当前错误处理程序
        $previousErrorHandler = set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$previousErrorHandler) {
            // 将PHP错误转换为异常
            if (!(error_reporting() & $errno)) {
                // 如果当前错误报告级别不包含此错误，则尊重当前的错误处理
                return $previousErrorHandler ? $previousErrorHandler($errno, $errstr, $errfile, $errline) : false;
            }
            
            // 将错误转换为异常
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            // 开始输出缓冲
            ob_start();
            
            // 包含视图文件
            include $viewFile;
            
            // 获取并清除缓冲区内容
            $content = ob_get_clean();
            
            // 恢复原始错误处理程序
            restore_error_handler();
            
            return $content;
        } catch (\Throwable $e) {

            // 确保缓冲区被清除
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            // 恢复原始错误处理程序
            restore_error_handler();
            
            echo $this->renderErrorView($e, $view);
        }
    }

    /**
     * 渲染错误视图，用于开发环境
     * 
     * @param \Throwable $e 捕获的错误或异常
     * @param string $view 原始视图名称
     * @return string 错误视图HTML
     */
    private function renderErrorView(\Throwable $e, $view)
    {
        $errorType = get_class($e);
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTraceAsString();
        
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>视图渲染错误</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; color: #333; }
                .error-container { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
                .error-title { color: #721c24; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
                .error-info { margin-bottom: 15px; }
                .error-trace { background: #f8f9fa; border: 1px solid #eee; padding: 15px; overflow: auto; font-family: monospace; font-size: 13px; }
                .error-info strong { width: 100px; display: inline-block; }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-title">视图渲染错误</div>
                <div class="error-info">
                    <p><strong>视图:</strong> {$view}.php</p>
                    <p><strong>错误类型:</strong> {$errorType}</p>
                    <p><strong>错误信息:</strong> {$message}</p>
                    <p><strong>文件:</strong> {$file}</p>
                    <p><strong>行号:</strong> {$line}</p>
                </div>
                <div class="error-trace">
                    <pre>{$trace}</pre>
                </div>
            </div>
        </body>
        </html>
        HTML;
        
        return $html;
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
        return $this->renderFile($view, $data);
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

    public function getUrl($path, $params = [])
    {
        return Router::getUrl($path, $params);
    }

    /**
     * 渲染消息页面
     * 
     * @param string $title 消息标题
     * @param string $text 消息内容
     * @param string $redirectUrl 跳转URL
     * @param int $seconds 等待秒数
     * @return string 渲染后的内容
     */
    public function renderMessage($title, $text, $redirectUrl = null, $seconds = 3)
    {
        if ($redirectUrl === null) {
            $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        }
        $this->setLayout('common');
        return $this->render('message', [
            'title' => $title,
            'text' => $text,
            'redirectUrl' => $redirectUrl,
            'seconds' => $seconds
        ]);
    }

    /**
     * 转义HTML特殊字符
     * 
     * @param string $string 需要转义的字符串
     * @return string 转义后的字符串
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * 判断是否为暗黑模式
     * 
     * @return bool 如果为暗黑模式返回true，否则返回false
     */
    public function isDarkdisabled()
    {
        if (isset($_COOKIE['theme'])) {
            return $_COOKIE['theme'] === 'dark';
        }
        return false;
    }

    /**
     * 渲染内容
     */
    public function renderContent()
    {
        echo isset($this->viewData['content']) ? $this->viewData['content'] : '';
    }

    public function isPrettyUrl()
    {
        return Router::getInstance()->prettyUrl;
    }
}
