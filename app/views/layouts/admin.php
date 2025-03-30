<!DOCTYPE html>
<html lang="zh-CN" data-theme="<?= $this->getUserTheme() ?>">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= $this->asset('/app/favicon.ico') ?>">
    <link rel="Bookmark" href="<?= $this->asset('/app/favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getTitle() ?></title>
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
    <!-- 编辑器样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/simplemde.min.css') ?>">
    <!-- 图标样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/font-awesome.min.css') ?>">
    <!-- 主题样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/theme.css') ?>">
    <!-- 公共样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/main.css') ?>">
    <!-- 导航样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/block-navigation.css') ?>">
    <!-- 主题脚本 -->
    <script src="<?= $this->asset('/app/html/js/theme.js') ?>"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="tiny-blog-nav">
            <header class="navbar-top">
                <nav class="navbar">
                    <a href="<?php echo BASE_PATH; ?>/app/block/index.html">前台</a>
                    <a href="<?php echo BASE_PATH; ?>/app/end/index.html">后台</a>
                    <a href="<?php echo BASE_PATH; ?>/app/end/edit.html">创建</a>
                    <a href="<?php echo BASE_PATH; ?>/app/end/category.html">分类</a>
                    <a href="<?php echo BASE_PATH; ?>/app/end/settings.html">设置</a>
                    <a href="<?php echo BASE_PATH; ?>/app/end/refresh_cache.php">刷新</a>
                    <a href="<?php echo BASE_PATH; ?>/app/end/logout.html">退出</a>
                    <!-- 主题切换按钮 -->
                    <button class="theme-toggle" id="themeToggle" aria-label="切换主题">
                        <svg id="lightIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun theme-icon">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg id="darkIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon theme-icon">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>
                </nav>
            </header>
        </div>
        <div class="layout-container">
            <?= $content ?>
        </div>
    </div>

    <!-- 页脚放在 body 内，container-fluid 外 -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> <?= $this->getSiteName() ?>. All rights reserved.</p>
                <p>备案号：<a target="_blank" rel="noopener noreferrer" href="https://beian.miit.gov.cn"> <?= $this->getBeianNumber() ?></a></p>
            </div>
        </div>
        <?= $this->getAnalyticsCode() ?>
    </footer>
</body>
</html>