<main class="container">
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/blog/index.css') ?>">
    <div class="blog-index-content">
        <section class="blog-index-posts">
            <?php if (isset($searchQuery)): ?>
                <div class="search-result-header">
                    <h1>搜索结果: "<?= htmlspecialchars($searchQuery) ?>"</h1>
                    <p>共找到 <?= $blogs['total'] ?> 篇相关文章</p>
                    <a href="<?= $this->getUrl('blog/index') ?>" class="back-to-all">返回全部文章</a>
                </div>
            <?php endif; ?>
            
            <?php if (empty($blogs['items'])): ?>
                <div class="no-data-tip">
                    <?php if (isset($searchQuery)): ?>
                        <p>没有找到与 "<?= htmlspecialchars($searchQuery) ?>" 相关的文章</p>
                        <a href="<?= $this->getUrl('blog/index') ?>" class="back-to-all">返回全部文章</a>
                    <?php else: ?>
                        <p>暂无博客文章</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php
            try {
                foreach ($blogs['items'] as $blog) {
                    // 渲染博客列表
                    echo '<article class="blog-post">';
                    echo '<h2>' . $blog['title'] . '</h2>';
                    echo '<p>' . $blog['subtitle'] . '</p>';
                    echo '<a href="' . $this->getUrl('blog/getBlogDetail', ['id' => trim($blog['path'], '.php')]) . '">阅读全文 &raquo;</a>';
                    echo '</article>';
                }
            } catch (\InvalidArgumentException $e) {
                echo $e->getMessage() . "\n";
            }
            ?>
            
            <!-- 添加分页组件 -->
            <?php 
                $this->renderPartial('common/pagination', 
                [
                    'currentPage' => $currentPage, 
                    'totalPages' => $totalPages, 
                    'urlPattern' => $urlPattern
                ]);
            ?>
        </section>
        <section class="blog-index-contact">
            <h2>联系我们</h2>
            <p>如果您有任何问题或建议，请通过以下方式联系我们：</p>
            <p>邮箱：<?= $contact_email ?></p>
            <p>微信：<?= $wechat_id ?></p>
        </section>
    </div>
</main>