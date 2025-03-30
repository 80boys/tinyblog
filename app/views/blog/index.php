<?php
/**
 * 博客首页模板
 * 
 * @var array $blogs 博客列表
 * @var int $currentPage 当前页码
 * @var int $totalPages 总页数
 * @var int $totalEntries 总条目数
 */

// 设置页面标题
$this->startBlock('head');
?>
<style>
    .blog-card {
        transition: transform 0.3s;
    }
    .blog-card:hover {
        transform: translateY(-5px);
    }
    .blog-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>
<?php $this->endBlock(); ?>

<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4">最新博客</h2>
        
        <?php if (empty($blogs['items'])): ?>
            <div class="alert alert-info">暂无博客内容</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($blogs['items'] as $blog): ?>
                    <div class="col">
                        <div class="card h-100 blog-card shadow-sm">
                            <?php if (!empty($blog['cover_image'])): ?>
                                <img src="<?= $blog['cover_image'] ?>" class="card-img-top" alt="<?= $this->escape($blog['title']) ?>">
                            <?php else: ?>
                                <img src="<?= $this->asset('images/default-cover.jpg') ?>" class="card-img-top" alt="默认封面">
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="<?= $this->url('/blog/' . $blog['id']) ?>" class="text-decoration-none text-dark">
                                        <?= $this->escape($blog['title']) ?>
                                    </a>
                                </h5>
                                
                                <?php if (!empty($blog['subtitle'])): ?>
                                    <h6 class="card-subtitle mb-2 text-muted"><?= $this->escape($blog['subtitle']) ?></h6>
                                <?php endif; ?>
                                
                                <p class="card-text">
                                    <?= $this->truncate(strip_tags($blog['content']), 150) ?>
                                </p>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?= $this->formatDate($blog['date'], 'Y-m-d') ?>
                                    </small>
                                    
                                    <?php if (!empty($blog['category'])): ?>
                                        <a href="<?= $this->url('/category/' . urlencode($blog['category'])) ?>" class="badge bg-primary text-decoration-none">
                                            <?= $this->escape($blog['category']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- 分页 -->
            <?php if ($blogs['pages'] > 1): ?>
                <div class="mt-4 d-flex justify-content-center">
                    <?= $this->pagination($blogs['page'], $blogs['pages'], '/') ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <!-- 侧边栏 -->
        <div class="card mb-4">
            <div class="card-header">关于博客</div>
            <div class="card-body">
                <p>欢迎访问我的个人博客！这里记录了我的技术笔记、生活感悟和各种有趣的事情。</p>
                <a href="/about" class="btn btn-primary">了解更多</a>
            </div>
        </div>
        
        <!-- 分类列表 -->
        <?php if (!empty($categories)): ?>
            <div class="card mb-4">
                <div class="card-header">分类</div>
                <div class="list-group list-group-flush">
                    <?php foreach ($categories as $name => $category): ?>
                        <a href="<?= $this->url('/category/' . urlencode($name)) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?= $this->escape($name) ?>
                            <span class="badge bg-primary rounded-pill"><?= $category['count'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- 标签云 -->
        <?php if (!empty($tags)): ?>
            <div class="card mb-4">
                <div class="card-header">标签云</div>
                <div class="card-body">
                    <?php foreach ($tags as $name => $tag): ?>
                        <a href="<?= $this->url('/tag/' . urlencode($name)) ?>" class="text-decoration-none me-2 mb-2 d-inline-block">
                            <span class="badge bg-secondary"><?= $this->escape($name) ?> (<?= $tag['count'] ?>)</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- 最近归档 -->
        <?php if (!empty($archives)): ?>
            <div class="card">
                <div class="card-header">归档</div>
                <div class="list-group list-group-flush">
                    <?php 
                    $years = array_keys($archives);
                    rsort($years);
                    $count = 0;
                    foreach ($years as $year): 
                        $months = $archives[$year];
                        krsort($months);
                        foreach ($months as $month => $archive):
                            $count++;
                            if ($count > 6) break 2; // 只显示最近6个月
                    ?>
                        <a href="<?= $this->url('/archive/' . $year . '/' . $month) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?= $year ?>年<?= $month ?>月
                            <span class="badge bg-primary rounded-pill"><?= $archive['count'] ?></span>
                        </a>
                    <?php 
                        endforeach;
                    endforeach; 
                    ?>
                    <a href="<?= $this->url('/archives') ?>" class="list-group-item list-group-item-action text-center">
                        查看全部归档
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$this->startBlock('scripts');
?>
<script>
    // 额外的JavaScript代码可以放在这里
</script>
<?php $this->endBlock(); ?> 