<!DOCTYPE html>
<html lang="zh-CN" data-theme="<?= $this->getUserTheme() ?>">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= $this->asset('/app/favicon.ico') ?>">
    <link rel="Bookmark" href="<?= $this->asset('/app/favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->getDescription() ?>">
    <meta name="keywords" content="<?= $this->getKeywords() ?>">
    <meta name="author" content="<?= $this->getAuthor() ?>">
    <title><?= $this->getTitle() ?></title>
    <!-- 编辑器样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/simplemde/simplemde.min.css') ?>">
    <!-- 图标样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/font-awesome/font-awesome.min.css') ?>">
    <!-- 主题样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/theme.css') ?>">
    <!-- 公共样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/main.css') ?>">
    <!-- 主题脚本 -->
    <script src="<?= $this->asset('/app/html/js/theme.js') ?>"></script>

    <!-- 代码高亮样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/highlight/xcode.min.css') ?>" data-highlight-theme="light" <?= $this->isDarkdisabled() ?>>
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/highlight/xcode-dusk.min.css') ?>" data-highlight-theme="dark" <?= $this->isDarkdisabled() ?>>

    <meta property="og:title" content="<? $this->getTitle() ?>">
    <meta property="og:description" content="<? $this->getDescription() ?>">
    <meta property="og:type" content="article">

</head>

<body>
    <?php $this->renderPartial('common/nav'); ?>
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
    <?php $this->renderContent(); ?>
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
    <?php $this->renderPartial('common/footer'); ?>
</body>
</html>