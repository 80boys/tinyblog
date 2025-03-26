<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
// 在包含head.php前添加预加载主题脚本
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 预先应用主题，避免闪烁 -->
    <script>
        (function() {
            // 从localStorage中读取主题设置
            var savedTheme = localStorage.getItem('theme');
            // 如果有保存的主题设置，立即应用
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                // 直接设置HTML和BODY背景色
                document.documentElement.style.backgroundColor = '#121212';
                document.documentElement.style.color = '#e0e0e0';
            }
            // 如果没有保存的设置，检查系统偏好
            else if (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
                // 直接设置HTML和BODY背景色
                document.documentElement.style.backgroundColor = '#121212';
                document.documentElement.style.color = '#e0e0e0';
            }
        })();
    </script>
    <!-- 预先应用暗色模式的基本样式，避免闪烁 -->
    <style>
        /* 首先设置html元素的背景色，这是最早被渲染的 */
        html[data-theme="dark"] {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }

        /* 确保body也是暗色的 */
        html[data-theme="dark"] body {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }

        /* 设置页面过渡，但延迟应用，避免初始加载时的过渡效果 */
        .theme-transition {
            transition: background-color 0.5s ease, color 0.5s ease !important;
        }
    </style>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/login.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/login-theme.css">
    <script src="<?php echo BASE_PATH; ?>/app/html/js/theme.js"></script>
    <title>登录后台 - 枫桥驿站</title>
</head>

<body>
    <div class="container">
        <article>
            <h2>登录后台</h2>
            <form action="<?php echo BASE_PATH; ?>/app/end/login.php" method="post">
                <section class="form-container">
                    <div class="form-group">
                        <label for="username">用户名：</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">密码：</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">登录</button>
                </section>
            </form>
            <div class="home-link">
                <a href="<?php echo BASE_PATH; ?>/app/block/index.html">返回首页</a>
            </div>
        </article>
    </div>
    <script>
        // 在页面完全加载后添加过渡类，避免初始加载时的过渡效果
        window.addEventListener('load', function() {
            document.documentElement.classList.add('theme-transition');
            document.body.classList.add('theme-transition');
        });
    </script>
</body>

</html>