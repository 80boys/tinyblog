<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/navigation.css">
<header class="navbar-top">
    <nav class="navbar">
        <a href="<?php echo BASE_PATH; ?>/app/block/index.html">首页</a>
        <div class="dropdown">
            <a href="<?php echo BASE_PATH; ?>/app/block/categories.html">分类</a>
            <div class="dropdown-content">
                <?php
                $categories = getCategories();
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        echo '<a href="' . BASE_PATH . '/app/block/categories.html?category=' . urlencode($category) . '">'
                            . htmlspecialchars($category)
                            . '</a>';
                    }
                } else {
                    echo '<a href="#" style="color: #999;">暂无分类</a>';
                }
                ?>
            </div>
        </div>
        <a href="<?php echo BASE_PATH; ?>/app/end/index.html">后台</a>
        <a href="<?php echo BASE_PATH; ?>/app/block/about.html">关于</a>
    </nav>
</header>