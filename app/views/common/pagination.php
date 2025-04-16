<?php
// 确保变量存在，如果不存在则设置默认值
$totalPages = isset($totalPages) ? $totalPages : 1;
$currentPage = isset($currentPage) ? $currentPage : 1;
$urlPattern = isset($urlPattern) ? $urlPattern : '?page=%d';

// 计算显示的页码范围
$range = 2; // 当前页码前后显示的页数
$startPage = max(1, $currentPage - $range);
$endPage = min($totalPages, $currentPage + $range);

// 添加分页样式
?>
<style>
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
    padding: 0;
    list-style: none;
}

.pagination li {
    margin: 0 2px;
}

.pagination a,
.pagination span {
    display: inline-block;
    padding: 8px 12px;
    text-decoration: none;
    border: 1px solid var(--border-color, #ddd);
    color: var(--text-primary, #333);
    border-radius: 4px;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background-color: var(--hover-bg, #f5f5f5);
    border-color: var(--hover-border, #ccc);
}

.pagination .active span {
    background-color: var(--active-bg, #007bff);
    border-color: var(--active-border, #007bff);
    color: var(--active-text, #fff);
}

.pagination .disabled span {
    color: var(--disabled-text, #999);
    cursor: not-allowed;
    background-color: var(--disabled-bg, #fff);
}
</style>

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