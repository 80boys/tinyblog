<?php
// 获取网站配置
$settings = getBlogsConfig();
$siteName = $settings['site_name'];
$siteDescription = isset($settings['site_description']) ? $settings['site_description'] : '分享技术与生活';

// 如果页面没有设置标题，使用默认标题
if (!isset($title)) {
    $title = $siteName;
}

// 如果页面没有设置描述，使用默认描述
if (!isset($description)) {
    $description = $siteDescription;
}

// 如果页面没有设置关键词，使用默认关键词
if (!isset($keywords)) {
    $keywords = isset($settings['default_keywords']) ? $settings['default_keywords'] : '博客,技术,生活';
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo BASE_PATH; ?>/app/favicon.ico">
    <link rel="Bookmark" href="<?php echo BASE_PATH; ?>/app/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>枫桥驿站</title>
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
    <link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/simplemde.min.css">
    <link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/main.css">
    <script src="<?php echo BASE_PATH; ?>/app/html/js/theme.js"></script>
</head>

<body>
</body>
</rewritten_file>