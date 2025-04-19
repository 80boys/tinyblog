<main class="container">
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/blog/index.css') ?>">
    <div class="blog-index-content">
        <section class="blog-index-posts">
            <?php if (empty($blogs['items'])): ?>
                <div class="no-data-tip">
                    <p>暂无博客文章</p>
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
        </section>
        <section class="blog-index-contact">
            <h2>联系我们</h2>
            <p>如果您有任何问题或建议，请通过以下方式联系我们：</p>
            <p>邮箱：<?= $contact_email ?></p>
            <p>微信：<?= $wechat_id ?></p>
        </section>
    </div>
</main>