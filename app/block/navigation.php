<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/block-navigation.css">
<div class="tiny-blog-nav">
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
            <?php
            // 获取所有设置为独立页面的博客
            $dt = new \App\Utils\DirectoryTraverser();
            $independentPages = $dt->getIndependentPages();

            // 显示独立页面链接
            if (!empty($independentPages)) {
                foreach ($independentPages as $page) {
                    if (!isset($page['is_private']) || $page['is_private'] === false) {
                        echo '<a href="' . BASE_PATH . '/app/blogs/' . str_replace('.php', '.html', $page['path']) . '">'
                            . htmlspecialchars($page['title'])
                            . '</a>';
                    }
                }
            }
            ?>
            <a href="<?php echo BASE_PATH; ?>/app/end/index.html">后台</a>
            <div class="search-container">
                <form action="<?php echo BASE_PATH; ?>/app/block/categories.html" method="get">
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
        </nav>
    </header>
</div>

<style>
    .search-container {
        display: flex;
        align-items: center;
        margin-left: auto;
        height: 100%;
    }

    .search-container form {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
    }

    .search-container input[type="text"] {
        padding: 8px 36px 8px 12px;
        border: 1px solid #4a4e56;
        border-radius: 20px;
        width: 200px;
        background-color: #2d3139;
        color: #c6c9d1;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .search-container input[type="text"]:focus {
        outline: none;
        border-color: #4a4e57;
        box-shadow: 0 0 0 2px rgba(74, 78, 87, 0.2);
        width: 220px;
    }

    .search-container input[type="text"]::placeholder {
        color: #7a7f8c;
    }

    .search-container button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        padding: 5px;
        color: #c6c9d1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .search-container button:hover {
        background: rgba(74, 78, 87, 0.5);
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .tiny-blog-nav .navbar {
            flex-wrap: nowrap;
            justify-content: flex-start;
        }

        .tiny-blog-nav .navbar-top {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            position: relative;
            margin-left: auto;
        }

        .search-input-wrapper {
            display: flex;
            align-items: center;
        }

        .search-container input[type="text"] {
            height: 36px;
            font-size: 14px;
        }

        .search-container button {
            height: 36px;
            width: 36px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.tiny-blog-nav .dropdown');

        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.dropbtn');
            const content = dropdown.querySelector('.dropdown-content');
            let isOpen = false;

            // 处理点击事件
            button.addEventListener('click', function(e) {
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

                // 确保移动端下拉菜单可见
                if (window.innerWidth <= 768) {
                    // 使用fixed定位，确保下拉菜单浮在页面上方
                    content.style.position = 'fixed';
                    content.style.top = '50px'; // 导航栏高度
                    content.style.left = '50%';
                    content.style.transform = 'translateX(-50%)';
                    content.style.width = '80%';
                    content.style.maxWidth = '300px';
                    content.style.zIndex = '9999';
                    content.style.backgroundColor = '#2d3139';
                    content.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
                }
            });

            // 添加触摸事件支持
            button.addEventListener('touchstart', function(e) {
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

                // 确保移动端下拉菜单可见
                if (window.innerWidth <= 768) {
                    // 使用fixed定位，确保下拉菜单浮在页面上方
                    content.style.position = 'fixed';
                    content.style.top = '50px'; // 导航栏高度
                    content.style.left = '50%';
                    content.style.transform = 'translateX(-50%)';
                    content.style.width = '50%';
                    content.style.maxWidth = '300px';
                    content.style.maxHeight = 'calc(100vh - 50px)';
                    content.style.overflowY = 'auto';
                    content.style.zIndex = '9999';
                    content.style.backgroundColor = '#2d3139';
                    content.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
                }
            });

            // 点击下拉菜单内容时阻止关闭
            content.addEventListener('click', (e) => {
                e.stopPropagation();
            });

            // 添加触摸事件支持
            content.addEventListener('touchstart', (e) => {
                e.stopPropagation();
            });
        });

        // 点击或触摸页面其他地方时关闭所有下拉菜单
        document.addEventListener('click', closeAllDropdowns);
        document.addEventListener('touchstart', closeAllDropdowns);

        function closeAllDropdowns() {
            dropdowns.forEach(dropdown => {
                dropdown.querySelector('.dropdown-content').classList.remove('show');
            });
        }

        // 搜索按钮点击事件 - 适用于所有设备
        const searchContainer = document.querySelector('.search-container');
        const searchButton = searchContainer.querySelector('button');
        const searchInput = searchContainer.querySelector('input[type="text"]');

        // 点击搜索按钮时切换搜索输入框的显示状态
        searchButton.addEventListener('click', function(e) {
            if (!searchContainer.classList.contains('active')) {
                e.preventDefault(); // 阻止表单提交
                searchContainer.classList.add('active');
                searchInput.focus();

                // 点击页面其他地方时隐藏搜索框
                document.addEventListener('click', hideSearchOnClickOutside);
            }
        });

        // 添加触摸事件支持
        searchButton.addEventListener('touchstart', function(e) {
            if (!searchContainer.classList.contains('active')) {
                e.preventDefault();
                searchContainer.classList.add('active');
                searchInput.focus();

                // 点击页面其他地方时隐藏搜索框
                document.addEventListener('touchstart', hideSearchOnClickOutside);
            }
        });

        // 输入框聚焦时保持搜索框打开状态
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
            searchContainer.classList.add('active');
        });

        searchInput.addEventListener('touchstart', function(e) {
            e.stopPropagation();
            searchContainer.classList.add('active');
        });

        // 添加输入框聚焦事件
        searchInput.addEventListener('focus', function() {
            searchContainer.classList.add('active');
        });

        function hideSearchOnClickOutside(e) {
            if (!searchContainer.contains(e.target)) {
                searchContainer.classList.remove('active');
                document.removeEventListener('click', hideSearchOnClickOutside);
                document.removeEventListener('touchstart', hideSearchOnClickOutside);
            }
        }
    });
</script>