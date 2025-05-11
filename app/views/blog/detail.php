<link rel="stylesheet" href="<?= $this->asset('/app/html/css/blog/detail.css') ?>">
<main class="blog-detail-container layout-container">
    <?php if (!$blog || ($blog['is_private'] && !isset($_SESSION['admin_logged_in']))): ?>
        <div class="error-message">
            <h1>博客不存在</h1>
            <p>抱歉，您请求的博客内容不存在或已被删除。</p>
            <a href="<?= $this->getUrl('blog/index') ?>">返回博客列表</a>
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
            <?php if (!empty($blog['blog_attachment']) && is_string($blog['blog_attachment'])): ?>
                <div class="attachment">
                    <span>附件:</span>
                    <a href="<?php echo htmlspecialchars($blog['blog_attachment']); ?>" download>
                        <?php echo htmlspecialchars(pathinfo($blog['blog_attachment'])["filename"]); ?>
                    </a>
                </div>
            <?php endif; ?>
        </article>
        <!-- 添加相关文章推荐 -->
        <?php if (!empty($blog['category'])): ?>
            <section class="related-posts">
                <?php
                $relatedPosts = [];
                $allBlogs = \App\Models\BlogsModel::getList(1, 10000, 
                    [
                        'include_private' => false, 
                        'include_independent' => false
                    ]);
                if (isset($allBlogs["items"])) {
                    $relatedPosts = array_filter($allBlogs["items"], function ($post) use ($blog) {
                        return !empty($post['category']) &&
                            $post['category'] === $blog['category'] &&
                            $post['path'] !== $blog['path'];
                    });
                    $relatedPosts = array_slice($relatedPosts, 0, 3);
                }
               
                if (empty($relatedPosts)): ?>
                    <h2>相关文章</h2>
                    <div class="no-related">暂无相关文章</div>
                <?php else: ?>
                    <h2>相关文章</h2>
                    <?php foreach ($relatedPosts as $post): ?>
                        <div class="related-post">
                            <h3><a href="<?= $this->getUrl('blog/getBlogDetail', ['id' => htmlspecialchars(str_replace('.php', '', $post['path']))] )?>">
                                    <?php echo htmlspecialchars($post['title'] ?? ''); ?>
                                </a></h3>
                            <?php if (!empty($post['subtitle'])): ?>
                                <a href="<?= $this->getUrl('blog/getBlogDetail', ['id' => htmlspecialchars(str_replace('.php', '', $post['path']))] )?>">
                                    <p><?php echo htmlspecialchars($post['subtitle']); ?></p>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</main>