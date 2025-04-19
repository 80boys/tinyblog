// 初始化代码高亮和功能栏
document.addEventListener('DOMContentLoaded', function() {
    // 应用当前主题的代码高亮样式
    function applyHighlightTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const highlightStyles = document.querySelectorAll('link[data-highlight-theme]');
        highlightStyles.forEach(link => {
            link.disabled = link.getAttribute('data-highlight-theme') !== currentTheme;
        });
    }

    // 处理代码块
    function processCodeBlocks() {
        const codeBlocks = document.querySelectorAll('pre code');
        codeBlocks.forEach(block => {
            // 如果已经处理过，跳过
            if (block.classList.contains('processed')) return;

            // 应用代码高亮
            hljs.highlightBlock(block);
            block.classList.add('processed');

            // 获取代码块的父元素
            const preElement = block.parentElement;
            
            // 创建功能栏
            const titleBar = document.createElement('div');
            titleBar.classList.add('title-bar');

            // 创建语言标签
            const languageLabel = document.createElement('span');
            languageLabel.classList.add('language-label');
            languageLabel.textContent = block.classList[0]?.replace('language-', '') || 'text';

            // 创建折叠按钮
            const collapseButton = document.createElement('a');
            collapseButton.textContent = '折叠';
            collapseButton.classList.add('collapse-button');
            collapseButton.href = '#';

            // 创建复制按钮
            const copyButton = document.createElement('a');
            copyButton.textContent = '复制';
            copyButton.classList.add('copy-button');
            copyButton.href = '#';

            // 添加按钮到功能栏
            titleBar.appendChild(languageLabel);
            titleBar.appendChild(collapseButton);
            titleBar.appendChild(copyButton);

            // 将功能栏插入到代码块前
            preElement.insertBefore(titleBar, block);

            // 折叠按钮点击事件
            collapseButton.addEventListener('click', function(e) {
                e.preventDefault();
                const isCollapsed = block.style.display === 'none';
                block.style.display = isCollapsed ? 'block' : 'none';
                this.textContent = isCollapsed ? '折叠' : '展开';
            });

            // 复制按钮点击事件
            copyButton.addEventListener('click', function(e) {
                e.preventDefault();
                const code = block.textContent;
                
                // 使用新的 Clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(code).then(() => {
                        showCopySuccess();
                    });
                } else {
                    // 回退到传统方法
                    const textarea = document.createElement('textarea');
                    textarea.value = code;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    try {
                        document.execCommand('copy');
                        showCopySuccess();
                    } catch (err) {
                        console.error('复制失败:', err);
                    }
                    document.body.removeChild(textarea);
                }
            });
        });
    }

    // 显示复制成功提示
    function showCopySuccess() {
        const toast = document.createElement('div');
        toast.classList.add('copy-success');
        toast.textContent = '复制成功！';
        document.body.appendChild(toast);
        
        // 动画结束后移除提示
        toast.addEventListener('animationend', () => {
            document.body.removeChild(toast);
        });
    }

    // 初始化
    applyHighlightTheme();
    processCodeBlocks();

    // 监听主题变化
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'data-theme') {
                applyHighlightTheme();
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
}); 