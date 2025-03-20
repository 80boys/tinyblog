<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");

use App\Utils\InputValidator;

/**
 * 验证用户函数
 *
 * @param string $username 用户名
 * @param string $password 密码
 * @return int|false 用户ID或false
 */
function validateUser($username, $password)
{
    // 从配置文件获取用户名和密码
    $settings = getBlogsConfig();

    if (
        $settings['admin_username'] === $username &&
        password_verify($password, $settings['admin_password'])
    ) {
        return md5($username); // 使用用户名的md5值作为UID
    }

    return false;
}

// 处理登录逻辑
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = InputValidator::getSafeInput($_POST['username']);
    $password = InputValidator::getSafeInput($_POST['password']);

    $user_id = validateUser($username, $password);

    if ($user_id) {
        // 登录成功，将用户ID存入session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username; // 保存用户名到session
        header('Location: ' . BASE_PATH . '/app/end/index.php');
        exit;
    } else {
        showMessage('登录失败', BASE_PATH . '/app/block/login.html', '用户名或密码错误');
    }
} else {
    // 显示登录表单
    header('Location: ' . BASE_PATH . '/app/block/login.html');
}
