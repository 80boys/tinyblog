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