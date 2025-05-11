<?php

namespace App\Core;

/**
 * 路由类
 * 
 * 自动根据URL解析控制器和方法，单例模式实现
 */
class Router
{
    /**
     * 单例实例
     * @var Router
     */
    private static $instance = null;

    /**
     * 基础URL
     * @var string
     */
    private $baseUrl = '';

    /**
     * 是否使用伪静态（URL重写）
     * @var bool
     */
    public $prettyUrl = true;

    /**
     * 默认控制器
     * @var string
     */
    private $defaultController = 'Blog';

    /**
     * 默认方法
     * @var string
     */
    private $defaultAction = 'index';

    /**
     * 当前控制器
     * @var string
     */
    private $currentController = '';

    /**
     * 当前方法
     * @var string
     */
    private $currentAction = '';

    /**
     * 当前参数
     * @var array
     */
    private $currentParams = [];

    /**
     * 获取路由器单例实例
     * 
     * @param bool $prettyUrl 是否使用伪静态URL
     * @param string|null $baseUrl 基础URL
     * @return Router
     */
    public static function getInstance(bool $prettyUrl = false, ?string $baseUrl = null)
    {
        if (self::$instance === null) {
            self::$instance = new self($prettyUrl, $baseUrl);
        }
        return self::$instance;
    }

    /**
     * 构造函数（私有化，实现单例）
     * 
     * @param bool $prettyUrl 是否使用伪静态URL
     * @param string|null $baseUrl 基础URL
     */
    private function __construct(bool $prettyUrl = true, ?string $baseUrl = null)
    {
        $this->prettyUrl = $prettyUrl;
        // 判断是否启用了伪静态URL
        if (!isset($_SERVER['REQUEST_URI'])) {
            $this->prettyUrl = false;
        }

        // 检查是否存在index.php,如果存在则不是伪静态
        if (strpos($_SERVER['REQUEST_URI'], 'index.php') !== false) {
            $this->prettyUrl = false;
        }

        // 检查是否存在查询字符串参数c和a,如果存在则不是伪静态
        if (isset($_GET['c']) || isset($_GET['a'])) {
            $this->prettyUrl = false;
        }
        $this->baseUrl = $baseUrl ?: $this->detectBaseUrl();
    }

    /**
     * 防止对象被克隆（单例模式）
     */
    private function __clone() {}

    /**
     * 防止反序列化（单例模式）
     */
    public function __wakeup() {}

    /**
     * 检测基础URL
     * 
     * @return string
     */
    private function detectBaseUrl()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = dirname($scriptName);

        // 移除末尾的斜杠
        if ($scriptDir != '/' && substr($scriptDir, -1) == '/') {
            $scriptDir = substr($scriptDir, 0, -1);
        }

        return $scriptDir;
    }

    /**
     * 设置默认控制器和方法
     * 
     * @param string $controller 控制器名
     * @param string $action 方法名
     * @return Router
     */
    public function setDefault($controller, $action = 'index')
    {
        $this->defaultController = $controller;
        $this->defaultAction = $action;
        return $this;
    }

    /**
     * 解析当前请求的URL
     * 
     * @return bool 是否成功解析
     */
    public function parseRequest()
    {
        // 获取请求路径
        $path = $this->getRequestPath();

        // 解析路径为控制器、方法和参数
        return $this->parseDefaultRoute($path);
    }

    /**
     * 获取请求路径
     * 
     * @return string
     */
    private function getRequestPath()
    {
        // 判断是否启用了伪静态URL
        if ($this->prettyUrl) {
            // 处理伪静态URL
            $requestUri = preg_replace('/^\/index\.php/', '', $_SERVER['REQUEST_URI']);
            $requestUri = parse_url($requestUri, PHP_URL_PATH);
            $path = substr($requestUri, strlen($this->baseUrl));
            return $path ?: '/';
        } else {
            // 处理查询字符串形式的URL
            $controller = isset($_GET['c']) ? $_GET['c'] : $this->defaultController;
            $action = isset($_GET['a']) ? $_GET['a'] : $this->defaultAction;

            // 构造一个虚拟路径
            $path = "/{$controller}/{$action}";

            // 提取其他参数
            $params = $_GET;
            unset($params['c'], $params['a']);

            // 设置控制器、动作和参数
            $this->currentController = $controller;
            $this->currentAction = $action;
            $this->currentParams = $params;

            return $path;
        }
    }

    /**
     * 解析路径为控制器、方法和参数
     * 
     * @param string $path 请求路径
     * @return bool 是否解析成功
     */
    private function parseDefaultRoute($path)
    {
        // 移除前导和尾随斜杠
        $path = trim($path, '/');

        if (empty($path)) {
            // 空路径，使用默认控制器和动作
            $this->currentController = $this->defaultController;
            $this->currentAction = $this->defaultAction;
            $this->currentParams = [];
            return true;
        }

        // 解析路径段
        $segments = explode('/', $path);

        // 确定控制器和方法
        $controller = ucfirst(array_shift($segments));
        $action = !empty($segments) ? array_shift($segments) : $this->defaultAction;

        // 解析剩余参数
        $params = [];

        // 如果有剩余部分，尝试解析为参数
        if (!empty($segments) && count($segments) % 2 === 0) {
            for ($i = 0; $i < count($segments); $i += 2) {
                if (isset($segments[$i + 1])) {
                    $params[$segments[$i]] = $segments[$i + 1];
                }
            }
        } else {
            // 否则，将剩余部分作为位置参数
            $params = $segments;
        }

        $this->currentController = $controller;
        $this->currentAction = $action;
        if (is_array($params) && !empty($params)) {
            $this->currentParams = $params;
        }

        return true;
    }

    /**
     * 生成URL
     * 
     * @param string $controller 控制器名
     * @param string $action 方法名
     * @param array $params 参数
     * @param bool $absolute 是否生成绝对URL
     * @return string 生成的URL
     */
    public function generateUrl($controller, $action = 'index', $params = [], $absolute = false)
    {
        if (!$this->prettyUrl) {
            // 非伪静态URL
            $base = 'index.php';
            $query = http_build_query([
                'c' => $controller,
                'a' => $action
            ] + $params);
            
            if ($absolute) {
                return rtrim($this->getBaseHost(), '/') . '/' . $base . '?' . $query;
            }
            return '/' . $base . '?' . $query;
        }

        // 伪静态URL
        $url = $controller . '/' . $action;

        // 处理参数
        if (!empty($params)) {
            if (array_keys($params) !== range(0, count($params) - 1)) {
                // 关联数组：使用键值对形式
                foreach ($params as $key => $value) {
                    $url .= '/' . $key . '/' . trim($value, '/');
                }
            } else {
                // 索引数组：直接追加值
                foreach ($params as $value) {
                    $url .= '/' . trim($value, '/');
                }
            }
        }

        if ($absolute) {
            return rtrim($this->getBaseHost(), '/') . '/' . $url;
        }
        return '/' . $url;
    }

    /**
     * 获取基础主机名
     * 
     * @return string
     */
    private function getBaseHost()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * 获取当前控制器名
     * 
     * @return string
     */
    public function getController()
    {
        return $this->currentController;
    }

    /**
     * 获取当前方法名
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->currentAction;
    }

    /**
     * 获取当前参数
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->currentParams;
    }

    /**
     * 获取单个参数值
     * 
     * @param string $name 参数名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return isset($this->currentParams[$name]) ? $this->currentParams[$name] : $default;
    }

    /**
     * 设置是否使用伪静态URL
     * 
     * @param bool $prettyUrl
     * @return Router
     */
    public function setPrettyUrl($prettyUrl)
    {
        $this->prettyUrl = $prettyUrl;
        return $this;
    }

    /**
     * 静态方法生成URL
     * 
     * @param string $path 控制器/方法格式的路径
     * @param array $params 参数数组
     * @param bool $absolute 是否生成绝对URL
     * @return string 生成的URL
     */
    public static function getUrl($path, $params = [], $absolute = false)
    {
        $path = trim($path, '/');
        // 获取路由器单例实例
        $router = self::getInstance();
        // 分解路径为控制器和方法
        $parts = explode('/', $path);
        $controller = $parts[0] ?? 'Home';
        $action = $parts[1] ?? 'index';
        // 使用Router生成URL
        return $router->generateUrl($controller, $action, $params, $absolute);
    }
}
