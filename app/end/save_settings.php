<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 收集所有设置项
    $settings = [
        // 基本信息
        'site_name' => $_POST['site_name'] ?? '',
        'site_description' => $_POST['site_description'] ?? '',
        'author' => $_POST['author'] ?? '',
        'default_keywords' => $_POST['default_keywords'] ?? '',

        // 联系方式
        'contact_email' => $_POST['contact_email'] ?? '',
        'wechat_id' => $_POST['wechat_id'] ?? '',

        // 七牛云配置
        'qiniu_access_key' => $_POST['qiniu_access_key'] ?? '',
        'qiniu_secret_key' => $_POST['qiniu_secret_key'] ?? '',
        'qiniu_bucket' => $_POST['qiniu_bucket'] ?? '',
        'qiniu_accelerate_domain' => $_POST['qiniu_accelerate_domain'] ?? '',
        'qiniu_domain' => $_POST['qiniu_domain'] ?? '',

        // 其他设置
        'beian_number' => $_POST['beian_number'] ?? '',
        'footer_text' => $_POST['footer_text'] ?? '',
        'analytics_code' => $_POST['analytics_code'] ?? ''
    ];

    try {
        // 确保配置目录存在
        $configDir = PROJECT_ROOT . '/app/blogs';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }

        // 保存设置到 PHP 文件
        $content = "<?php\nreturn " . var_export($settings, true) . ";\n";
        $saved = file_put_contents($configDir . '/settings.php', $content);

        if ($saved === false) {
            throw new Exception('无法保存设置文件');
        }

        // 使用统一提示页面
        showMessage('设置保存成功', BASE_PATH . '/app/end/settings.php');
    } catch (Exception $e) {
        showMessage('保存失败', BASE_PATH . '/app/end/settings.php', $e->getMessage());
    }
} else {
    // 如果不是 POST 请求，重定向到设置页面
    header('Location: ' . BASE_PATH . '/app/end/settings.php');
    exit;
}
