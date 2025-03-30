<!DOCTYPE html>
<html lang="zh-CN" data-theme="<?= $this->getUserTheme() ?>">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= $this->asset('/app/favicon.ico') ?>">
    <link rel="Bookmark" href="<?= $this->asset('/app/favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<? $this->getDescription() ?>">
    <meta name="keywords" content="<? $this->getKeywords() ?>">
    <meta name="author" content="<? $this->getAuthor() ?>">
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

    <!-- 代码高亮样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/highlight/xcode.min.css') ?>" data-highlight-theme="light" <? $this->isDarkdisabled() ?>>
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/highlight/xcode-dusk.min.css') ?>" data-highlight-theme="dark" <? $this->isDarkdisabled() ?>>
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/responsive-theme.css') ?>">

    <meta property="og:title" content="<? $this->getTitle() ?>">
    <meta property="og:description" content="<? $this->getDescription() ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<? $this->getUrl() ?>">

</head>

<body>
    <div class="container-fluid">
        <link rel="stylesheet" href="<?= $this->asset('/app/html/css/block-navigation.css') ?>">
        <div class="tiny-blog-nav">
            <header class="navbar-top">
            <nav class="navbar">
                <a href="<?= $this->getBasePath() ?>/app/block/index.html">首页</a>
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
        <div class="layout-container">
            <article class="blog-details" itemscope itemtype="http://schema.org/BlogPosting">
            <h1 itemprop="headline"><?php echo htmlspecialchars($blog['title']); ?></h1>
            <div class="meta">
                <span itemprop="articleSection">分类: <?php echo isset($blog['category']) ? htmlspecialchars($blog['category']) : '未分类'; ?></span>
                <?php if (isset($blog['tags'])): ?>
                    <span itemprop="keywords">标签: <?php echo htmlspecialchars(is_array($blog['tags']) ? implode(', ', $blog['tags']) : $blog['tags']); ?></span>
                <?php endif; ?>
                <time itemprop="datePublished" datetime="<?php echo isset($blog['date']) ? date('Y-m-d', strtotime($blog['date'])) : date('Y-m-d'); ?>">
                    写作时间: <?php echo isset($blog['date']) ? htmlspecialchars($blog['date']) : date('Y-m-d'); ?>
                </time>
                <?php if (isset($blog['author'])): ?>
                    <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                        <span itemprop="name">作者: <?php echo htmlspecialchars($blog['author']); ?></span>
                    </span>
                <?php endif; ?>
            </div>
            <div class="markdown-body" itemprop="articleBody">
                <?php echo $blog['content']; ?>
            </div>
            <?php if (!empty($blog['attachment']) && is_string($blog['attachment'])): ?>
                <div class="attachment">
                    <span>附件:</span>
                    <a href="<?php echo htmlspecialchars($blog['attachment']); ?>" download>
                        <?php echo htmlspecialchars(pathinfo($blog['attachment'])["filename"]); ?>
                    </a>
                </div>
            <?php endif; ?>
        </article>

        <!-- 添加相关文章推荐 -->
        <?php if (isset($blog['category'])): ?>
            <section class="related-posts">
                <?php
                $dt = new \App\Utils\DirectoryTraverser();
                $allBlogs = $dt->getAllBlogs()['blogs'];
                $relatedPosts = array_filter($allBlogs, function ($post) use ($blog) {
                    return isset($post['category']) &&
                        $post['category'] === $blog['category'] &&
                        $post['path'] !== $blog['path'];
                });
                $relatedPosts = array_slice($relatedPosts, 0, 3);

                if (empty($relatedPosts)): ?>
                    <h2>相关文章</h2>
                    <div class="no-related">暂无相关文章</div>
                <?php else: ?>
                    <h2>相关文章</h2>
                    <?php foreach ($relatedPosts as $post): ?>
                        <div class="related-post">
                            <h3><a href="<?php echo BASE_PATH; ?>/app/blogs/<?php
                                                                            $path = $post['path'];
                                                                            $path = str_replace('.php', '.html', $path);
                                                                            echo $path;
                                                                            ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a></h3>
                            <?php if (isset($post['subtitle'])): ?>
                                <p><?php echo htmlspecialchars($post['subtitle']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <script src="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/js/interact.min.js"></script>
        <script src="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/js/highlight/highlight.min.js"></script>
        <script>
            function dragMoveListener(event) {
                event.stopPropagation();
                var target = event.target;
                var scale = parseFloat(target.style.transform.split('scale(')[1]);
                if (isNaN(scale)) {
                    scale = 1;
                }
                var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
                target.style.transform = 'translate(' + x + 'px, ' + y + 'px) scale(' + scale + ')';

                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);
            }
            const allowedLanguages = ['javascript', 'php', 'python', 'java',
                'c++', 'c', 'objetc-c', 'js', 'css', 'go', 'html', 'shell'
            ];
            const loadedLanguages = new Set();
            document.addEventListener('DOMContentLoaded', async function() {
                // 应用当前主题的代码高亮样式
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                const highlightStyles = document.querySelectorAll('link[data-highlight-theme]');
                highlightStyles.forEach(link => {
                    if (link.getAttribute('data-highlight-theme') === currentTheme) {
                        link.disabled = false;
                    } else {
                        link.disabled = true;
                    }
                });

                const codeBlocks = document.querySelectorAll('pre code');

                // 创建一个函数来处理代码高亮，这样我们可以在主题切换时重新调用
                window.applyCodeHighlighting = async function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';

                    for (const block of codeBlocks) {
                        // 如果已经应用过高亮，则跳过
                        if (block.classList.contains('hljs')) {
                            continue;
                        }

                        result = hljs.highlightAuto(block.textContent);
                        const detectedLanguage = result.language;
                        hljs.highlightBlock(block);

                        // 其余代码处理逻辑...
                        if (!loadedLanguages.has(detectedLanguage)) {
                            try {
                                // 检查浏览器是否支持动态导入
                                if ('import' in window) {
                                    await import(`<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/js/highlight/languages/${detectedLanguage}.min.js`);
                                } else {
                                    // 如果不支持动态导入，使用传统的 <script> 标签加载脚本
                                    const script = document.createElement('script');
                                    script.src = `<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/js/highlight/languages/${detectedLanguage}.min.js`;
                                    script.onload = function() {
                                        loadedLanguages.add(detectedLanguage);
                                    };
                                    script.onerror = function() {
                                        console.error(`Failed to load ${detectedLanguage} language:`, error);
                                    };
                                    document.head.appendChild(script);
                                }
                                loadedLanguages.add(detectedLanguage);
                            } catch (error) {
                                console.error(`Failed to load ${detectedLanguage} language:`, error);
                            }
                        }

                        // 获取代码块的父元素（通常是 <pre> 标签）
                        const preElement = block.parentElement;
                        if (!preElement.querySelector('.title-bar')) {
                            const titleBar = document.createElement('div');
                            titleBar.classList.add('title-bar');

                            // 创建语言标签元素
                            const languageLabel = document.createElement('span');
                            languageLabel.innerText = detectedLanguage;
                            languageLabel.classList.add('language-label');

                            // 创建折叠按钮
                            const collapseButton = document.createElement('a');
                            collapseButton.innerText = '折叠';
                            collapseButton.classList.add('collapse-button');
                            collapseButton.href = '#';

                            // 创建复制按钮（使用 <a> 标签）
                            const copyButton = document.createElement('a');
                            copyButton.innerText = '复制';
                            copyButton.classList.add('copy-button');
                            copyButton.href = '#';

                            // 将语言标签和复制按钮添加到标题栏中
                            titleBar.appendChild(languageLabel);
                            titleBar.appendChild(collapseButton);
                            titleBar.appendChild(copyButton);

                            // 将标题栏添加到代码块的父元素中
                            preElement.insertBefore(titleBar, block);

                            // 为折叠按钮添加点击事件处理程序
                            collapseButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                if (block.style.display === 'none') {
                                    block.style.display = 'block';
                                    collapseButton.innerText = '折叠';
                                } else {
                                    block.style.display = 'none';
                                    collapseButton.innerText = '展开';
                                }
                            });

                            // 为复制按钮添加点击事件处理程序
                            copyButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                const textarea = document.createElement('textarea');
                                textarea.value = block.textContent;
                                document.body.appendChild(textarea);
                                textarea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textarea);
                            });
                        }
                    }
                };

                // 初始应用代码高亮
                await window.applyCodeHighlighting();

                // 监听主题变化，重新应用高亮
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === "data-theme") {
                            // 主题已更改，重新应用高亮
                            setTimeout(function() {
                                window.applyCodeHighlighting();
                            }, 100);
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ["data-theme"]
                });

                // 为所有大图添加点击和双击事件
                const images = document.querySelectorAll('img');
                images.forEach(image => {
                    image.addEventListener('click', function() {
                        event.stopPropagation();
                        const zoomImage = document.createElement('div');
                        zoomImage.classList.add('zoom-image');
                        const zoomImg = document.createElement('img');
                        zoomImg.src = this.src;
                        zoomImage.appendChild(zoomImg);
                        zoomImg.style.transform = 'scale(1)'
                        document.body.appendChild(zoomImage);
                        interact(zoomImg).draggable({
                            inertia: true,
                            modifiers: [
                                interact.modifiers.restrictRect({
                                    restriction: 'parent',
                                    endOnly: true
                                })
                            ],
                            autoScroll: true,
                            listeners: {
                                move: dragMoveListener,
                                end(event) {
                                    event.stopPropagation();
                                }
                            }
                        });
                        zoomImg.addEventListener('click', function() {
                            event.stopPropagation();
                            document.body.removeChild(zoomImage);
                        });
                    });
                });
            });
        </script>
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