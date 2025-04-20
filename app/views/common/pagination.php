<?php
// 确保变量存在，如果不存在则设置默认值
$totalPages = isset($totalPages) ? $totalPages : 1;
$currentPage = isset($currentPage) ? $currentPage : 1;
$urlPattern = isset($urlPattern) ? $urlPattern : '?page=%d';

// 计算显示的页码范围
$range = 2; // 当前页码前后显示的页数
$startPage = max(1, $currentPage - $range);
$endPage = min($totalPages, $currentPage + $range);
?>
<link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/pagination.css') ?>">

<?php if ($totalPages > 1): ?>
<nav aria-label="分页导航">
    <ul class="pagination">
        <?php
        // 上一页
        if ($currentPage > 1): ?>
            <li>
                <a href="<?= sprintf($urlPattern, $currentPage - 1) ?>" aria-label="上一页">
                    &laquo;
                </a>
            </li>
        <?php else: ?>
            <li class="disabled">
                <span>&laquo;</span>
            </li>
        <?php endif;

        // 第一页
        if ($startPage > 1): ?>
            <li>
                <a href="<?= sprintf($urlPattern, 1) ?>">1</a>
            </li>
            <?php if ($startPage > 2): ?>
                <li class="disabled">
                    <span>...</span>
                </li>
            <?php endif;
        endif;

        // 页码
        for ($i = $startPage; $i <= $endPage; $i++): ?>
            <li <?= $i === $currentPage ? 'class="active"' : '' ?>>
                <?php if ($i === $currentPage): ?>
                    <span><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= sprintf($urlPattern, $i) ?>"><?= $i ?></a>
                <?php endif; ?>
            </li>
        <?php endfor;

        // 最后一页
        if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
                <li class="disabled">
                    <span>...</span>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?= sprintf($urlPattern, $totalPages) ?>"><?= $totalPages ?></a>
            </li>
        <?php endif;

        // 下一页
        if ($currentPage < $totalPages): ?>
            <li>
                <a href="<?= sprintf($urlPattern, $currentPage + 1) ?>" aria-label="下一页">
                    &raquo;
                </a>
            </li>
        <?php else: ?>
            <li class="disabled">
                <span>&raquo;</span>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?> 