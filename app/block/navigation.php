<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/navigation.css">
<header class="navbar-top">
    <nav class="navbar">
        <a href="<?php echo BASE_PATH; ?>/app/block/index.html">首页</a>
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropbtn">分类</a>
            <div class="dropdown-content" id="categoryDropdown">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.dropbtn');
            const content = dropdown.querySelector('.dropdown-content');
            let isOpen = false;

            // 点击按钮时切换下拉菜单
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                isOpen = !isOpen;

                // 关闭其他打开的下拉菜单
                dropdowns.forEach(other => {
                    if (other !== dropdown) {
                        other.querySelector('.dropdown-content').classList.remove('show');
                    }
                });

                content.classList.toggle('show');
            });

            // 点击下拉菜单内容时阻止关闭
            content.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });

        // 点击页面其他地方时关闭所有下拉菜单
        document.addEventListener('click', () => {
            dropdowns.forEach(dropdown => {
                dropdown.querySelector('.dropdown-content').classList.remove('show');
            });
        });
    });
</script>