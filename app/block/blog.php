<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/highlight/atom-one-dark.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/responsive.css">
    <title>我的博客</title>
    <!-- 添加关键词和描述 -->
    <meta name="keywords" content="<?php echo $blog['blog_tags']; ?>">
    <meta name="description" content="<?php echo $blog['blog_subtitle']; ?>">
</head>

<body>
    <?php include(PROJECT_ROOT . "/app/block/navigation.php"); ?>
    <div class="blog-details">
        <h1><?php echo $blog['title']; ?></h1>
        <div class="meta">
            <span>分类: <?php echo $blog['category']; ?></span>
            <span>标签: <?php echo $blog['tags']; ?></span>
            <span>写作时间: <?php echo $blog['date']; ?></span>
        </div>
        <div class="markdown-body">
            <?php echo $blog['content']; ?>
        </div>
        <div class="attachment">
            <?php if (!empty($blog['attachment']) && is_string($blog['attachment'])): ?>
                <span>附件:</span>
                <a href="<?php
                            if (is_string($blog['attachment'])) {
                                echo $blog['attachment'];
                            }
                            ?>" download>
                    <?php echo pathinfo($blog['attachment'])["filename"]; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?php echo BASE_PATH; ?>/app/html/js/interact.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>/app/html/js/highlight/highlight.min.js"></script>
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
            const codeBlocks = document.querySelectorAll('pre code');
            for (const block of codeBlocks) {
                result = hljs.highlightAuto(block.textContent);
                const detectedLanguage = result.language;
                if (!allowedLanguages.includes(detectedLanguage)) {
                    continue;
                }
                hljs.highlightBlock(block);
                if (!loadedLanguages.has(detectedLanguage)) {
                    try {
                        // 检查浏览器是否支持动态导入
                        if ('import' in window) {
                            await import(`<?php echo BASE_PATH; ?>/app/html/js/highlight/languages/${detectedLanguage}.min.js`);
                        } else {
                            // 如果不支持动态导入，使用传统的 <script> 标签加载脚本
                            const script = document.createElement('script');
                            script.src = `<?php echo BASE_PATH; ?>/app/html/js/highlight/languages/${detectedLanguage}.min.js`;
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
                collapseButton.addEventListener('click', function() {
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