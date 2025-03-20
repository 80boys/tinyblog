<?php
function getBlogsConfig()
{
    $configFile = PROJECT_ROOT . '/app/blogs/settings.php';

    // 如果配置文件不存在，创建默认配置
    if (!file_exists($configFile)) {
        $settings = [
            // 基本信息
            'site_name' => '枫桥驿站',
            'site_description' => '分享技术与生活',
            'author' => '博主',
            'default_keywords' => '博客,技术,生活',

            // 联系方式
            'contact_email' => 'admin@example.com',
            'wechat_id' => 'your_wechat_id',

            // 七牛云配置
            'qiniu_access_key' => '',
            'qiniu_secret_key' => '',
            'qiniu_bucket' => '',
            'qiniu_accelerate_domain' => '',
            'qiniu_domain' => '',

            // 安全设置
            'admin_username' => 'admin', // 默认管理员用户名
            'admin_password' => password_hash('admin888', PASSWORD_DEFAULT), // 默认密码：admin888

            // 其他设置
            'beian_number' => '',
            'footer_text' => '© ' . date('Y') . ' 枫桥驿站 All Rights Reserved.',
            'analytics_code' => ''
        ];

        // 创建配置文件
        if (!is_dir(dirname($configFile))) {
            mkdir(dirname($configFile), 0755, true);
        }

        $content = "<?php\nreturn " . var_export($settings, true) . ";\n";
        file_put_contents($configFile, $content);

        return $settings;
    }

    // 读取配置文件
    return require($configFile);
}

if (!function_exists('error_log')) {
    function error_log($message)
    {
        try {
            // 设置日志目录为 app/logs
            $logDir = dirname(dirname(__FILE__)) . '/app/blogs/logs';

            // 检查目录是否存在，如果不存在则创建
            if (!is_dir($logDir)) {
                if (!@mkdir($logDir, 0755, true)) {
                    return false;
                }
            }

            // 检查目录是否可写
            if (!is_writable($logDir)) {
                return false;
            }

            // 获取当前日期作为文件名
            $today = date('Y-m-d');
            $logFile = $logDir . '/log-' . $today . '.log';

            // 获取当前时间
            $timestamp = date('Y-m-d H:i:s');

            // 格式化日志消息
            $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;

            // 写入日志
            if (@file_put_contents($logFile, $logMessage, FILE_APPEND) === false) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

/**
 * 获取所有分类
 * @return array
 */
function getCategories()
{
    $categoriesFile = PROJECT_ROOT . '/app/blogs/categories.php';
    if (file_exists($categoriesFile)) {
        $categories = require $categoriesFile;
        if (is_array($categories)) {
            return $categories;
        }
    }
    return [];
}

/**
 * 验证用户登录
 * @param string $username 用户名
 * @param string $password 密码
 * @return bool 是否验证成功
 */
function verifyLogin($username, $password)
{
    $settings = getBlogsConfig();

    // 验证用户名和密码
    if (
        $username === $settings['admin_username'] &&
        password_verify($password, $settings['admin_password'])
    ) {
        // 登录成功，设置session
        $_SESSION['user_id'] = md5($username); // 使用用户名的md5值作为UID
        $_SESSION['username'] = $username;
        return true;
    }

    return false;
}

/**
 * 保存博客配置
 * @param array $settings 配置数组
 * @return bool 是否保存成功
 */
function saveBlogsConfig($settings)
{
    $configFile = PROJECT_ROOT . '/app/blogs/settings.php';

    // 确保目录存在
    if (!is_dir(dirname($configFile))) {
        mkdir(dirname($configFile), 0755, true);
    }

    // 生成配置文件内容
    $content = "<?php\nreturn " . var_export($settings, true) . ";\n";

    // 写入文件
    return file_put_contents($configFile, $content) !== false;
}

/**
 * 修改管理员密码
 * @param string $newPassword 新密码
 * @return bool 是否修改成功
 */
function changeAdminPassword($newPassword)
{
    $settings = getBlogsConfig();
    $settings['admin_password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    return saveBlogsConfig($settings);
}
