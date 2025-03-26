<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_PATH . "/app/block/login.html");
    exit();
}
?>

<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/block-navigation.css">
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