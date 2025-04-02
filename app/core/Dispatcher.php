<?php
namespace App\Core;

/**
 * 调度器类
 * 
 * 负责整合路由、控制器和视图处理
 */
class Dispatcher
{
    /**
     * 路由器实例
     * @var Router
     */
    private $router;
    
    /**
     * 视图实例
     * @var View
     */
    private $view;

    /**
     * 控制器实例
     * @var Action
     */
    private $controller;
    
    /**
     * 控制器名称
     * @var string
     */
    private $controllerName;
    
    /**
     * 方法名称
     * @var string
     */
    private $actionName;
    
    /**
     * 方法参数
     * @var array
     */
    private $params = [];
    
    /**
     * 控制器命名空间
     * @var string
     */
    private $controllerNamespace = 'App\\Actions\\';
    
    
    /**
     * 构造函数
     * 
     * @param Router|null $router 路由器实例
     * @param View|null $view 视图实例
     */
    public function __construct(?Router $router = null, ?View $view = null)
    {
        $this->router = $router ?: Router::getInstance();
        $this->view = $view ?: new View(getProjectRoot() . '/app/views/');
    }
    
    /**
     * 解析请求
     * 
     * @return bool 是否成功解析
     */
    protected function parseRequest()
    {
        // 让路由器完全负责路由解析
        if ($this->router->parseRequest()) {
            // 从Router获取控制器和方法
            $this->controllerName = $this->router->getController();
            $this->actionName = $this->router->getAction();
            $this->params = $this->router->getParams();
            return true;
        }
        
        return false;
    }
    
    /**
     * 调度并执行请求
     * 
     * @return string 最终输出内容
     */
    public function dispatch()
    {
        try {
            // 解析请求
            if (!$this->parseRequest()) {
                // 如果找不到路由，返回404页面
                return $this->handle404();
            }
            
            // 处理请求
            $response = $this->executeAction();
            
            // 渲染视图
            return $this->renderResponse($response);
        } catch (\Exception $e) {
            // 处理异常
            return $this->handleException($e);
        }
    }
    
    /**
     * 执行控制器动作
     * 
     * @return mixed 控制器动作的返回值
     * @throws \Exception 控制器或方法不存在
     */
    protected function executeAction()
    {
        // 构建完整的控制器类名
        $controllerClass = $this->controllerNamespace . $this->controllerName;
        
        // 检查控制器是否存在
        if (!class_exists($controllerClass)) {
            throw new \Exception("Action not found: {$controllerClass}");
        }
        
        // 实例化控制器
        $this->controller = new $controllerClass();
        
        // 检查方法是否存在
        if (!method_exists($this->controller, $this->actionName)) {
            throw new \Exception("Method not found: {$this->actionName} in {$controllerClass}");
        }
        
        // 整合所有参数到一个数组
        $allParams = [];
        
        // 添加路由参数
        if (!empty($this->params)) {
            $allParams = array_merge($allParams, $this->params);
        }
        
        // 添加 GET 参数
        if (!empty($_GET)) {
            $allParams = array_merge($allParams, $_GET);
            if (isset($allParams['c']))  unset($allParams['c']);
            if (isset($allParams['a']))  unset($allParams['a']);
        }
        
        // 添加 POST 参数
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $allParams = array_merge($allParams, $_POST);
        }

        // 执行方法，只传入一个参数（整合后的数组）
        return call_user_func([$this->controller, $this->actionName], count($allParams) > 1 ? $allParams : current($allParams));
    }
    
    /**
     * 渲染响应
     * 
     * @param mixed $response 控制器方法返回的内容
     * @return string 渲染后的内容
     */
    protected function renderResponse($response)
    {
        // 如果响应是布尔值，显示操作结果消息
        if (is_bool($response)) {
            $text = $response ? '操作成功' : '操作失败';
            $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
            return $this->view->renderMessage('操作提示', $text, $redirectUrl);
        }
        
        // 如果响应已经是字符串，直接返回
        if (is_string($response)) {
            return $response;
        }
        
        if (!$response) {
            $response = $this->controller->getRenderData();
        }
        
        // 如果响应是数组，尝试作为视图数据处理
        if (is_array($response)) {
            // 检查是否包含视图信息
            if (isset($response['view'])) {
                $view = $response['view'];
                $data = isset($response['data']) ? $response['data'] : [];
                
                // 设置布局（如果有）
                if (isset($response['layout'])) {
                    $this->view->setLayout($response['layout']);
                }
                
                // 设置标题（如果有）
                if (isset($response['title'])) {
                    $this->view->setTitle($response['title']);
                }
                
                // 渲染视图
                return $this->view->render($view, $data);
            }
        }
        
        // 如果无法识别响应格式，返回原始内容
        return var_export($response, true);
    }
    
    /**
     * 处理404错误
     * 
     * @return string 404页面内容
     */
    protected function handle404()
    {
        return $this->view->renderError(404, "页面未找到");
    }
    
    /**
     * 处理异常
     * 
     * @param \Exception $e 异常对象
     * @return string 错误页面内容
     */
    protected function handleException(\Exception $e)
    {
        // 如果是路由未找到的异常，显示404页面
        if (strpos($e->getMessage(), 'Action not found') !== false || 
            strpos($e->getMessage(), 'Method not found') !== false) {
            return $this->handle404();
        }
        
        // 否则显示500错误页面
        if (defined('DEBUG') && constant('DEBUG')) {
            // 调试模式下显示详细错误信息
            return $this->view->renderError(500, $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n" . $e->getTraceAsString());
        }
        
        return $this->view->renderError(500, "系统错误，请稍后再试");
    }
    
    /**
     * 设置默认控制器和方法
     * 
     * @param string $controller 控制器名
     * @param string $action 方法名
     * @return $this
     */
    public function setDefault($controller, $action = 'index')
    {
        $this->controllerName = $controller;
        $this->actionName = $action;
        
        // 同时更新路由器的默认设置
        if ($this->router) {
            $this->router->setDefault($controller, $action);
        }
        
        return $this;
    }
    
    /**
     * 设置控制器命名空间
     * 
     * @param string $namespace 命名空间
     * @return $this
     */
    public function setControllerNamespace($namespace)
    {
        // 确保命名空间以反斜杠结尾
        if (substr($namespace, -1) !== '\\') {
            $namespace .= '\\';
        }
        
        $this->controllerNamespace = $namespace;
        return $this;
    }
    
    /**
     * 获取视图实例
     * 
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }
    
    /**
     * 设置视图实例
     * 
     * @param View $view 视图实例
     * @return $this
     */
    public function setView(View $view)
    {
        $this->view = $view;
        return $this;
    }
    
    /**
     * 获取路由器实例
     * 
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }
} 