<link rel="stylesheet" href="<?= $this->asset('/app/html/css/blog/detail.css') ?>">
<main class="blog-detail-container layout-container">
    <?php if (!$blog): ?>
        <div class="error-message">
            <h1>博客不存在</h1>
            <p>抱歉，您请求的博客内容不存在或已被删除。</p>
            <a href="<?php echo BASE_PATH; ?>/blog/index">返回博客列表</a>
        </div>
    <?php else: ?>
        <article class="blog-details" itemscope itemtype="http://schema.org/BlogPosting">
            <h1 itemprop="headline"><?php echo htmlspecialchars($blog['title'] ?? ''); ?></h1>
            <div class="meta">
                <span itemprop="articleSection">分类: <?php echo isset($blog['category']) ? htmlspecialchars($blog['category']) : '未分类'; ?></span>
                <?php if (!empty($blog['tags'])): ?>
                    <span itemprop="keywords">标签: <?php echo htmlspecialchars(is_array($blog['tags']) ? implode(', ', $blog['tags']) : $blog['tags']); ?></span>
                <?php endif; ?>
                <time itemprop="datePublished" datetime="<?php echo isset($blog['date']) ? date('Y-m-d', strtotime($blog['date'])) : date('Y-m-d'); ?>">
                    写作时间: <?php echo isset($blog['date']) ? htmlspecialchars($blog['date']) : date('Y-m-d'); ?>
                </time>
                <?php if (!empty($blog['author'])): ?>
                    <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                        <span itemprop="name">作者: <?php echo htmlspecialchars($blog['author']); ?></span>
                    </span>
                <?php endif; ?>
            </div>
            <div class="markdown-body" itemprop="articleBody">
                <?php echo $blog['content'] ?? ''; ?>
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
        <?php if (!empty($blog['category'])): ?>
            <section class="related-posts">
                <?php
                $allBlogs = [];
                $relatedPosts = array_filter($allBlogs, function ($post) use ($blog) {
                    return !empty($post['category']) &&
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
                            <h3><a href="<?php echo BASE_PATH; ?>/blog/getBlogDetail/id/<?php echo htmlspecialchars(str_replace('.php', '', $post['path'])); ?>">
                                    <?php echo htmlspecialchars($post['title'] ?? ''); ?>
                                </a></h3>
                            <?php if (!empty($post['subtitle'])): ?>
                                <p><?php echo htmlspecialchars($post['subtitle']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</main>