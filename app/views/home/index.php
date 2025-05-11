<link rel="stylesheet" href="<?= $this->asset('/app/html/css/admin/admin.css') ?>">
<div class="main-content">
    <!-- 筛选和搜索工具栏 -->
    <section class="filter-toolbar">
        <form action="<?= $this->getUrl('admin/index') ?>" method="get" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="category-filter">分类筛选:</label>
                    <select id="category-filter" name="category" onchange="this.form.submit()">
                        <option value="">所有分类</option>
                        <?php foreach ($categories as $categoryName => $categoryData): ?>
                            <option value="<?= htmlspecialchars($categoryName) ?>" <?= isset($filters['category']) && $filters['category'] === $categoryName ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoryName) ?> (<?= $categoryData['count'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group search-group">
                    <input type="text" name="search" placeholder="搜索博客标题..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    <button type="submit" class="search-button">搜索</button>
                </div>
                
                <div class="filter-group">
                    <label for="sort-by">排序:</label>
                    <select id="sort-by" name="sort_by" onchange="this.form.submit()">
                        <option value="date" <?= (!isset($filters['sort_by']) || $filters['sort_by'] === 'date') ? 'selected' : '' ?>>发布日期</option>
                        <option value="title" <?= (isset($filters['sort_by']) && $filters['sort_by'] === 'title') ? 'selected' : '' ?>>标题</option>
                    </select>
                    <select id="sort-order" name="sort_order" onchange="this.form.submit()">
                        <option value="desc" <?= (!isset($filters['sort_order']) || $filters['sort_order'] === 'desc') ? 'selected' : '' ?>>降序</option>
                        <option value="asc" <?= (isset($filters['sort_order']) && $filters['sort_order'] === 'asc') ? 'selected' : '' ?>>升序</option>
                    </select>
                </div>
            </div>
            
            <!-- 当前使用的筛选器标签 -->
            <?php if (!empty($filters['category']) || !empty($filters['search'])): ?>
            <div class="active-filters">
                <span>当前筛选:</span>
                <?php if (!empty($filters['category'])): ?>
                    <span class="filter-tag">
                        分类: <?= htmlspecialchars($filters['category']) ?>
                        <a href="<?= $this->getUrl('admin/index', array_merge(
                            array_filter($filters, function($key) { return $key !== 'category'; }, ARRAY_FILTER_USE_KEY),
                            ['page' => 1]
                        )) ?>" class="remove-filter">×</a>
                    </span>
                <?php endif; ?>
                
                <?php if (!empty($filters['search'])): ?>
                    <span class="filter-tag">
                        搜索: <?= htmlspecialchars($filters['search']) ?>
                        <a href="<?= $this->getUrl('admin/index', array_merge(
                            array_filter($filters, function($key) { return $key !== 'search'; }, ARRAY_FILTER_USE_KEY),
                            ['page' => 1]
                        )) ?>" class="remove-filter">×</a>
                    </span>
                <?php endif; ?>
                
                <a href="<?= $this->getUrl('admin/index') ?>" class="clear-all-filters">清除所有筛选</a>
            </div>
            <?php endif; ?>
        </form>
        
        <!-- 显示博客总数 -->
        <div class="blog-count">
            共找到 <?= $totalBlogs ?> 篇博客
        </div>
    </section>

    <section class="blog-posts">
        <?php
        if (!empty($blogs)) {
            foreach ($blogs as $blog) {
                $isPrivate = isset($blog['is_private']) && $blog['is_private'] === true;
                $isIndependent = isset($blog['is_independent']) && $blog['is_independent'] === true;
                echo '<article class="blog-post' . ($isPrivate ? ' private-blog' : '') . ($isIndependent ? ' independent-blog' : '') . '">';
                echo '<h3>' . $blog['title'] . '</h3>';
                echo '<p>' . $blog['subtitle'] . '</p>';
                echo '<p class="meta">';
                echo '<span>发布于: ' . $blog['date'] . '</span>';
                if (isset($blog['category'])) {
                    echo '<span>分类: ' . $blog['category'] . '</span>';
                }
                if ($isPrivate) {
                    echo '<span class="private-label">私有</span>';
                }
                if ($isIndependent) {
                    echo '<span class="independent-label">独立页面</span>';
                }
                echo '</p>';
                echo '<div class="actions">';
                echo '<a href="' . $this->getUrl('blog/getBlogDetail', ['id' => str_replace('.php', '', $blog['path'])]) . '">查看</a> | ';
                echo '<a href="' . $this->getUrl('admin/edit', ['id' => str_replace('.php', '', $blog['path'])]) . '">编辑</a> | ';
                echo '<a href="' . $this->getUrl('admin/deleteBlog', ['id' => str_replace('.php', '', $blog['path'])]) . '">删除</a>';
                echo '</div>';
                echo '</article>';
            }
        } else {
            echo '<div class="no-blogs">暂无博客</div>';
        }
        ?>
    </section>

    <!-- 分页 -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <?php 
        $this->renderPartial('common/pagination', [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'urlPattern' => $urlPattern ?? '?page={page}'
        ]);
        ?>
    <?php endif; ?>
</div>
