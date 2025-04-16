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

        // 安全设置
        'admin_username' => $_POST['admin_username'] ?? 'admin',

        // 其他设置
        'beian_number' => $_POST['beian_number'] ?? '',
        'footer_text' => $_POST['footer_text'] ?? '',
        'analytics_code' => $_POST['analytics_code'] ?? ''
    ];

    try {
        // 获取现有配置
        $currentSettings = getBlogsConfig();

        // 如果提供了新密码，则更新密码
        if (!empty($_POST['admin_password'])) {
            $settings['admin_password'] = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
        } else {
            // 保持原有密码不变
            $settings['admin_password'] = $currentSettings['admin_password'];
        }

        // 确保配置目录存在
        $configDir = PROJECT_ROOT . '/app/blogs';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }

        saveBlogsConfig($settings);

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
