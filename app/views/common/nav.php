<link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/navigation.css') ?>" />
<header class="navbar-top">
    <nav class="navbar">
        <a href="<?= $this->getUrl('blog/index') ?>">首页</a>
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropbtn">分类</a>
            <div class="dropdown-content" id="categoryDropdown">
                <?php
                // 获取博客分类列表
                $categories = \App\Models\BlogsModel::getCategories();
                if (!empty($categories)) {
                    foreach ($categories as $categoryName => $categoryData) {
                        // 显示分类名称和博客数量
                        $blogCount = $categoryData['count'] ?? 0;
                        echo '<a href="' . $this->getUrl('blog/index', ['category' => $categoryName]) . '">' . 
                             $categoryName . ' (' . $blogCount . ')' . 
                             '</a>';
                    }
                } else {
                    echo '<a href="javascript:void(0);">暂无分类</a>';
                }
                ?>
            </div>
        </div>
        <a href="<?= $this->getUrl('admin/index') ?>">后台</a>
        <div class="search-container">
            <form action="<?= $this->getUrl('blog/index') ?>" method="get">
                <div class="search-input-wrapper">
                    <input type="text" name="search" placeholder="搜索博客..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
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
<!-- 导航交互脚本，只在有导航栏的页面加载 -->
<script src="<?= $this->asset('/app/html/js/navigation.js') ?>"></script>