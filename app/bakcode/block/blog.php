<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($blog['subtitle']) ? htmlspecialchars($blog['subtitle']) : htmlspecialchars(mb_substr(strip_tags($blog['content']), 0, 150)); ?>">
    <meta name="keywords" content="<?php
                                    $keywords = [];
                                    if (isset($blog['category'])) $keywords[] = $blog['category'];
                                    if (isset($blog['tags'])) {
                                        $tags = is_array($blog['tags']) ? $blog['tags'] : explode(',', $blog['tags']);
                                        $keywords = array_merge($keywords, $tags);
                                    }
                                    echo htmlspecialchars(implode(',', $keywords));
                                    ?>">
    <meta name="author" content="<?php echo isset($blog['author']) ? htmlspecialchars($blog['author']) : '博客作者'; ?>">

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

    <!-- Open Graph 标签 -->
    <meta property="og:title" content="<?php echo htmlspecialchars($blog['title']); ?>">
    <meta property="og:description" content="<?php echo isset($blog['subtitle']) ? htmlspecialchars($blog['subtitle']) : htmlspecialchars(mb_substr(strip_tags($blog['content']), 0, 150)); ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <?php if (isset($blog['cover_image'])): ?>
        <meta property="og:image" content="<?php echo htmlspecialchars($blog['cover_image']); ?>">
    <?php endif; ?>

    <!-- Twitter Card 标签 -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($blog['title']); ?>">
    <meta name="twitter:description" content="<?php echo isset($blog['subtitle']) ? htmlspecialchars($blog['subtitle']) : htmlspecialchars(mb_substr(strip_tags($blog['content']), 0, 150)); ?>">
    <?php if (isset($blog['cover_image'])): ?>
        <meta name="twitter:image" content="<?php echo htmlspecialchars($blog['cover_image']); ?>">
    <?php endif; ?>

    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/theme.css">

    <!-- 代码高亮样式 -->
    <?php
    // 默认使用浅色主题，并立即加载以避免FOUC (Flash of Unstyled Content)
    $defaultTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
    ?>
    <link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/highlight/xcode.min.css" data-highlight-theme="light" <?php echo $defaultTheme === 'dark' ? 'disabled' : ''; ?>>
    <link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/highlight/xcode-dusk.min.css" data-highlight-theme="dark" <?php echo $defaultTheme !== 'dark' ? 'disabled' : ''; ?>>

    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/responsive-theme.css">
    <script src="<?php echo BASE_PATH; ?>/app/html/js/theme.js"></script>
    <title><?php echo htmlspecialchars($blog['title']); ?> - <?php echo htmlspecialchars(isset($blog['category']) ? $blog['category'] : '博客'); ?></title>
</head>

<body>
    <?php include(PROJECT_ROOT . "/app/block/navigation.php"); ?>
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
                                                                        // 只处理php文件
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
</body>

</html>