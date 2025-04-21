/**
 * 导航栏交互功能
 */
document.addEventListener('DOMContentLoaded', function() {
    // 搜索框交互
    const searchContainer = document.querySelector('.search-container');
    
    // 确保搜索容器存在
    if (searchContainer) {
        const searchButton = searchContainer.querySelector('button');
        const searchInput = searchContainer.querySelector('input[type="text"]');
        
        // 确保搜索按钮和输入框都存在
        if (searchButton && searchInput) {
            // 处理搜索按钮点击
            searchButton.addEventListener('click', function(e) {
                // 如果搜索框未激活，则阻止表单提交，激活搜索框
                if (!searchContainer.classList.contains('active')) {
                    e.preventDefault();
                    searchContainer.classList.add('active');
                    searchInput.focus();
                } else {
                    // 搜索框已激活，如果输入框为空，阻止提交
                    if (searchInput.value.trim() === '') {
                        e.preventDefault();
                    }
                }
            });
            
            // 按ESC键关闭搜索框
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchContainer.classList.remove('active');
                    searchInput.blur();
                }
            });
        }
        
        // 点击外部区域时关闭搜索框
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                searchContainer.classList.remove('active');
            }
        });
    }
    
    // 处理分类下拉菜单在移动端的点击展开
    const dropdowns = document.querySelectorAll('.dropdown');
    if (dropdowns.length > 0) {
        dropdowns.forEach(dropdown => {
            const dropdownBtn = dropdown.querySelector('.dropbtn');
            const dropdownContent = dropdown.querySelector('.dropdown-content');
            
            // 确保下拉按钮和内容都存在
            if (dropdownBtn && dropdownContent) {
                // 仅在移动视图下添加点击事件
                if (window.innerWidth <= 768) {
                    dropdownBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        dropdownContent.classList.toggle('show');
                    });
                }
            }
        });
    }
}); 