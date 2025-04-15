<main class="container">
    <div class="blog-index-content">
        <section class="blog-index-posts">
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
            <?php
            $settings = getBlogsConfig();
            ?>
            <h2>联系我们</h2>
            <p>如果您有任何问题或建议，请通过以下方式联系我们：</p>
            <p>邮箱：<?php echo $settings['contact_email']; ?></p>
            <p>微信：<?php echo $settings['wechat_id']; ?></p>
        </section>
    </div>
</main>
