<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/navigation.css">
<header class="navbar-top">
    <nav class="navbar">
        <a href="<?php echo BASE_PATH; ?>/app/block/index.html">首页</a>
        <div class="dropdown">
            <a href="<?php echo BASE_PATH; ?>/app/block/categories.html">分类</a>
            <div class="dropdown-content">
                <?php
                $dt = new \App\Utils\DirectoryTraverser();
                $blogsTags = $dt->getAllBlogs()['blogs'];
                $categories = [];

                // 收集所有分类
                foreach ($blogsTags as $blogTag) {
                    if (isset($blogTag['category']) && !empty($blogTag['category'])) {
                        $categories[$blogTag['category']] = isset($categories[$blogTag['category']])
                            ? $categories[$blogTag['category']] + 1
                            : 1;
                    }
                }

                // 显示分类列表
                foreach ($categories as $category => $count) {
                    echo '<a href="' . BASE_PATH . '/app/block/categories.html?category=' . urlencode($category) . '">'
                        . htmlspecialchars($category)
                        . ' (' . $count . ')</a>';
                }

                if (empty($categories)) {
                    echo '<a href="#" style="color: #999;">暂无分类</a>';
                }
                ?>
            </div>
        </div>
        <a href="<?php echo BASE_PATH; ?>/app/end/index.html">后台</a>
        <a href="<?php echo BASE_PATH; ?>/app/block/about.html">关于</a>
    </nav>
</header>