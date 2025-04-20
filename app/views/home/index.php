<link rel="stylesheet" href="<?= $this->asset('/app/html/css/admin/admin.css') ?>">
<div class="main-content">
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
            'urlPattern' => $urlPattern ?? '?page=%d'
        ]);
        ?>
    <?php endif; ?>
</div>