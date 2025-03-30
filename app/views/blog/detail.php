<?php
/**
 * 博客详情页模板
 * 
 * @var BlogModel $blog 博客对象
 * @var array $relatedBlogs 相关博客列表
 */

// 设置页面标题
$this->startBlock('head');
?>
<style>
    .blog-content {
        line-height: 1.8;
    }
    .blog-content img {
        max-width: 100%;
        height: auto;
    }
    .blog-cover {
        height: 400px;
        object-fit: cover;
        width: 100%;
        border-radius: 8px;
    }
    .tag-link {
        text-decoration: none;
    }
    .related-post {
        transition: transform 0.3s;
    }
    .related-post:hover {
        transform: translateY(-5px);
    }
</style>
<?php $this->endBlock(); ?>

<div class="row">
    <div class="col-lg-8">
        <!-- 博客内容 -->
        <article class="blog-post">
            <h1 class="mb-3"><?= $this->escape($blog->getTitle()) ?></h1>
            
            <?php if ($blog->getSubtitle()): ?>
                <h3 class="text-muted mb-4"><?= $this->escape($blog->getSubtitle()) ?></h3>
            <?php endif; ?>
            
            <div class="blog-meta mb-4">
                <span class="me-3">
                    <i class="bi bi-calendar-date"></i> 
                    <?= $this->formatDate($blog->getCreatedAt(), 'Y-m-d') ?>
                </span>
                
                <?php if ($blog->getCategory()): ?>
                    <span class="me-3">
                        <i class="bi bi-folder"></i> 
                        <a href="<?= $this->url('/category/' . urlencode($blog->getCategory())) ?>" class="text-decoration-none">
                            <?= $this->escape($blog->getCategory()) ?>
                        </a>
                    </span>
                <?php endif; ?>
                
                <?php if ($blog->getAuthor()): ?>
                    <span>
                        <i class="bi bi-person"></i> 
                        <?= $this->escape($blog->getAuthor()) ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ($blog->getCoverImage()): ?>
                <img src="<?= $blog->getCoverImage() ?>" alt="<?= $this->escape($blog->getTitle()) ?>" class="blog-cover mb-4">
            <?php endif; ?>
            
            <div class="blog-content mb-5">
                <?= $blog->getContent() ?>
            </div>
            
            <?php if (!empty($blog->getTags())): ?>
                <div class="blog-tags mb-5">
                    <p><strong>标签：</strong></p>
                    <?php foreach ($blog->getTags() as $tag): ?>
                        <a href="<?= $this->url('/tag/' . urlencode($tag)) ?>" class="badge bg-secondary me-2 mb-2 tag-link">
                            <?= $this->escape($tag) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <hr class="my-5">
            
            <!-- 上一篇/下一篇 -->
            <?php if (isset($prevBlog) || isset($nextBlog)): ?>
                <div class="row g-0 mb-5">
                    <div class="col-6 text-start">
                        <?php if (isset($prevBlog)): ?>
                            <p><small>上一篇</small></p>
                            <a href="<?= $this->url('/blog/' . $prevBlog->getId()) ?>" class="text-decoration-none">
                                &laquo; <?= $this->escape($prevBlog->getTitle()) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 text-end">
                        <?php if (isset($nextBlog)): ?>
                            <p><small>下一篇</small></p>
                            <a href="<?= $this->url('/blog/' . $nextBlog->getId()) ?>" class="text-decoration-none">
                                <?= $this->escape($nextBlog->getTitle()) ?> &raquo;
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- 相关博客 -->
            <?php if (!empty($relatedBlogs)): ?>
                <div class="related-posts mt-5">
                    <h3>相关文章</h3>
                    <div class="row row-cols-1 row-cols-md-2 g-4 mt-2">
                        <?php foreach ($relatedBlogs as $relatedBlog): ?>
                            <div class="col">
                                <div class="card h-100 related-post shadow-sm">
                                    <?php if (!empty($relatedBlog['cover_image'])): ?>
                                        <img src="<?= $relatedBlog['cover_image'] ?>" class="card-img-top" alt="<?= $this->escape($relatedBlog['title']) ?>" style="height: 140px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="<?= $this->url('/blog/' . $relatedBlog['id']) ?>" class="text-decoration-none text-dark">
                                                <?= $this->escape($relatedBlog['title']) ?>
                                            </a>
                                        </h5>
                                        <p class="card-text">
                                            <?= $this->truncate(strip_tags($relatedBlog['content']), 80) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </article>
    </div>
    
    <div class="col-lg-4">
        <!-- 侧边栏 -->
        <div class="position-sticky" style="top: 2rem;">
            <?php if (!empty($categories)): ?>
                <div class="card mb-4">
                    <div class="card-header">分类</div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($categories as $name => $category): ?>
                            <a href="<?= $this->url('/category/' . urlencode($name)) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $blog->getCategory() === $name ? 'active' : '' ?>">
                                <?= $this->escape($name) ?>
                                <span class="badge bg-primary rounded-pill"><?= $category['count'] ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recentBlogs)): ?>
                <div class="card mb-4">
                    <div class="card-header">最近文章</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recentBlogs as $recentBlog): ?>
                            <li class="list-group-item">
                                <a href="<?= $this->url('/blog/' . $recentBlog['id']) ?>" class="text-decoration-none">
                                    <?= $this->escape($recentBlog['title']) ?>
                                </a>
                                <div><small class="text-muted"><?= $this->formatDate($recentBlog['date'], 'Y-m-d') ?></small></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($tags)): ?>
                <div class="card">
                    <div class="card-header">标签云</div>
                    <div class="card-body">
                        <?php foreach ($tags as $name => $tag): ?>
                            <a href="<?= $this->url('/tag/' . urlencode($name)) ?>" class="badge bg-secondary me-2 mb-2 d-inline-block text-decoration-none">
                                <?= $this->escape($name) ?> (<?= $tag['count'] ?>)
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$this->startBlock('scripts');
?>
<script>
    // 可以在这里添加语法高亮等功能的脚本
    document.addEventListener('DOMContentLoaded', function() {
        // 给所有表格添加Bootstrap样式
        const tables = document.querySelectorAll('.blog-content table');
        tables.forEach(table => {
            table.classList.add('table', 'table-bordered');
        });
        
        // 给所有图片添加点击放大功能
        const images = document.querySelectorAll('.blog-content img');
        images.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                window.open(this.src, '_blank');
            });
        });
    });
</script>
<?php $this->endBlock(); ?> 