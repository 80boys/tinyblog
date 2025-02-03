<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/highlight/atom-one-dark.min.css">
    <style>
        /* 标题栏样式 */
        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #383c44; /* 比 highlight.js 主题颜色浅一点 */
            padding: 5px 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            font-size: 14px;
            color: #c6c9d1; /* 比 highlight.js 主题颜色浅一点 */
        }

        /* 语言标签样式 */
        .language-label {
            font-weight: bold;
        }

        /* 折叠按钮和复制按钮样式 */
        .collapse-button, .copy-button {
            background-color: transparent; /* 透明背景 */
            color: #c6c9d1; /* 与标题栏文本颜色匹配 */
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: color 0.3s ease; /* 过渡效果 */
            text-decoration: none; /* 移除下划线 */
            margin-left: 10px; /* 添加左边距，使按钮之间有间隔 */
        }
        /* 折叠按钮和复制按钮悬停样式 */
        .collapse-button:hover, .copy-button:hover {
            color: #c678dd; /* 鼠标悬停时的颜色 */
        }

        /* 确保 <a> 标签的颜色不受点击影响 */
        .copy-button:visited {
            color: #c6c9d1; /* 与默认状态的颜色相同 */
        }

        /* 博客详情样式 */
        .blog-details {
            padding: 20px;
        }

        .blog-details h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .blog-details .meta {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }

        .blog-details .meta span {
            margin-right: 10px;
        }

        .blog-details .content {
            margin-top: 20px;
        }

        .blog-details .attachment {
            margin-top: 20px;
        }

        .blog-details .attachment a {
            display: inline-block;
            background-color: #383c44;
            color: #c6c9d1;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .blog-details .attachment a:hover {
            background-color: #c678dd;
        }
    </style>
    <title>我的博客</title>
</head>
<body>
    <div class="blog-details">
        <h1><?php echo $blog['title']; ?></h1>
        <div class="meta">
            <span>分类: <?php echo $blog['category']; ?></span>
            <span>标签: <?php echo $blog['tags']; ?></span>
            <span>写作时间: <?php echo $blog['date']; ?></span>
        </div>
        <div class="content">
            <?php echo $blog['content']; ?>
        </div>
        <div class="attachment">
            <?php if (!empty($blog['attachment'])): ?>
                <span>附件:</span>
                <a href="<?php echo BASE_PATH; ?>/app/blogs/<?php echo $blog['attachment']['name']; ?>" download>
                    <?php echo $blog['attachment']['name']; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?php echo BASE_PATH; ?>/app/html/js/highlight/highlight.min.js"></script>
    <script>
        const loadedLanguages = new Set();
        document.addEventListener('DOMContentLoaded', async function () {
            const codeBlocks = document.querySelectorAll('pre code');
            for (const block of codeBlocks) {
                hljs.highlightBlock(block);
                result = hljs.highlightAuto(block.textContent);
                const detectedLanguage = result.language;
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
                collapseButton.addEventListener('click', function () {
                    if (block.style.display === 'none') {
                        block.style.display = 'block';
                        collapseButton.innerText = '折叠';
                    } else {
                        block.style.display = 'none';
                        collapseButton.innerText = '展开';
                    }
                });

                // 为复制按钮添加点击事件处理程序
                copyButton.addEventListener('click', function (e) {
                    e.preventDefault(); // 阻止默认的链接行为

                    // 创建一个临时的 textarea 元素
                    const textarea = document.createElement('textarea');
                    textarea.value = block.textContent;

                    // 将 textarea 添加到页面中
                    document.body.appendChild(textarea);

                    // 选中 textarea 中的内容
                    textarea.select();

                    // 复制选中的内容到剪贴板
                    document.execCommand('copy');

                    // 移除临时的 textarea 元素
                    document.body.removeChild(textarea);
                });
            }
        });
    </script>
</body>
</html>