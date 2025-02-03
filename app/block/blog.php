<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/highlight/atom-one-dark.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/responsive.css"> <!-- 新增响应式样式表 -->
    <title>我的博客</title>
</head>
<body>
    <?php include(PROJECT_ROOT. "/app/block/navigation.php");?>
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
            <?php if (!empty($blog['attachment']) && !empty($blog['attachment']['name'])): ?>
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