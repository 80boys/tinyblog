<main class="layout-container">
    <article class="blog-details" itemscope itemtype="http://schema.org/BlogPosting">
        <h1 itemprop="headline"><?php echo htmlspecialchars($blog['title']); ?></h1>
        <div class="meta">
            <span itemprop="articleSection">分类: <?php echo isset($blog['category']) ? htmlspecialchars($blog['category']) : '未分类'; ?></span>
            <?php if (isset($blog['tags'])): ?>
                <span itemprop="keywords">标签: <?php echo htmlspecialchars(is_array($blog['tags']) ? implode(', ', $blog['tags']) : $blog['tags']); ?></span>
            <?php endif; ?>
            <time itemprop="datePublished" datetime="<?php echo isset($blog['date']) ? date('Y-m-d', strtotime($blog['date'])) : date('Y-m-d'); ?>">
                写作时间: <?php echo isset($blog['date']) ? htmlspecialchars($blog['date']) : date('Y-m-d'); ?>
            </time>
            <?php if (isset($blog['author'])): ?>
                <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <span itemprop="name">作者: <?php echo htmlspecialchars($blog['author']); ?></span>
                </span>
            <?php endif; ?>
        </div>
        <div class="markdown-body" itemprop="articleBody">
            <?php echo $blog['content']; ?>
        </div>
        <?php if (!empty($blog['attachment']) && is_string($blog['attachment'])): ?>
            <div class="attachment">
                <span>附件:</span>
                <a href="<?php echo htmlspecialchars($blog['attachment']); ?>" download>
                    <?php echo htmlspecialchars(pathinfo($blog['attachment'])["filename"]); ?>
                </a>
            </div>
        <?php endif; ?>
    </article>
    <!-- 添加相关文章推荐 -->
    <?php if (isset($blog['category'])): ?>
        <section class="related-posts">
            <?php
            $dt = new \App\Utils\DirectoryTraverser();
            $allBlogs = $dt->getAllBlogs()['blogs'];
            $relatedPosts = array_filter($allBlogs, function ($post) use ($blog) {
                return isset($post['category']) &&
                    $post['category'] === $blog['category'] &&
                    $post['path'] !== $blog['path'];
            });
            $relatedPosts = array_slice($relatedPosts, 0, 3);

            if (empty($relatedPosts)): ?>
                <h2>相关文章</h2>
                <div class="no-related">暂无相关文章</div>
            <?php else: ?>
                <h2>相关文章</h2>
                <?php foreach ($relatedPosts as $post): ?>
                    <div class="related-post">
                        <h3><a href="<?php echo BASE_PATH; ?>/app/blogs/<?php
                                                                        $path = $post['path'];
                                                                        $path = str_replace('.php', '.html', $path);
                                                                        echo $path;
                                                                        ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a></h3>
                        <?php if (isset($post['subtitle'])): ?>
                            <p><?php echo htmlspecialchars($post['subtitle']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>