<?php
// 确保变量存在，如果不存在则设置默认值
$totalPages = isset($totalPages) && is_numeric($totalPages) && $totalPages > 0 ? (int)$totalPages : 1;
$currentPage = isset($currentPage) && is_numeric($currentPage) && $currentPage > 0 ? (int)$currentPage : 1;
$urlPattern = isset($urlPattern) && is_string($urlPattern) ? $urlPattern : '?page={page}';  // 使用{page}作为占位符

// 调试信息 - 先看看传入的URL模式是什么
error_log("原始URL模式: " . $urlPattern);

// 确保URL模式中包含{page}占位符
if (strpos($urlPattern, '{page}') === false) {
    // URL中没有{page}占位符，添加一个
    $urlPattern = strpos($urlPattern, '?') !== false ? 
        $urlPattern . '&page={page}' : 
        $urlPattern . '?page={page}';
}

// 计算显示的页码范围
$range = 2; // 当前页码前后显示的页数
$startPage = max(1, $currentPage - $range);
$endPage = min($totalPages, $currentPage + $range);

// 生成URL的安全函数
function generatePageUrl($pattern, $pageNumber) {
    return str_replace('{page}', $pageNumber, $pattern);
}
?>
<link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/pagination.css') ?>">

<?php if ($totalPages > 1): ?>
<nav aria-label="分页导航">
    <ul class="pagination">
        <?php
        // 上一页
        if ($currentPage > 1): ?>
            <li>
                <a href="<?= htmlspecialchars(generatePageUrl($urlPattern, $currentPage - 1)) ?>" aria-label="上一页">
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
                <a href="<?= htmlspecialchars(generatePageUrl($urlPattern, 1)) ?>">1</a>
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
                    <a href="<?= htmlspecialchars(generatePageUrl($urlPattern, $i)) ?>"><?= $i ?></a>
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
                <a href="<?= htmlspecialchars(generatePageUrl($urlPattern, $totalPages)) ?>"><?= $totalPages ?></a>
            </li>
        <?php endif;

        // 下一页
        if ($currentPage < $totalPages): ?>
            <li>
                <a href="<?= htmlspecialchars(generatePageUrl($urlPattern, $currentPage + 1)) ?>" aria-label="下一页">
                    &raquo;
                </a>
            </li>
        <?php else: ?>
            <li class="disabled">
                <span>&raquo;</span>
            </li>
        <?php endif; ?>
    </ul>
    
    <?php if ($totalPages > 5): ?>
    <div class="pagination-jumper">
        <form action="" method="get" onsubmit="return validatePageJump(this);">
            <label for="page-jump">跳转到:</label>
            <input type="number" id="page-jump" name="page" min="1" max="<?= $totalPages ?>" value="<?= $currentPage ?>">
            <span class="total-info">/ <?= $totalPages ?> 页</span>
            
            <?php
            // 保留现有的GET参数（除了page）
            foreach ($_GET as $key => $value) {
                if ($key !== 'page' && !empty($value)) {
                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                }
            }
            ?>
            
            <button type="submit">确定</button>
        </form>
    </div>
    <?php endif; ?>
</nav>

<script>
function validatePageJump(form) {
    var input = form.querySelector('input[name="page"]');
    var page = parseInt(input.value);
    var min = parseInt(input.getAttribute('min'));
    var max = parseInt(input.getAttribute('max'));
    
    if (isNaN(page) || page < min || page > max) {
        alert('请输入有效的页码（' + min + '-' + max + '）');
        input.value = <?= $currentPage ?>;
        return false;
    }
    return true;
}
</script>
<?php endif; ?>

<style>
.pagination-jumper {
    margin-top: 10px;
    text-align: center;
}
.pagination-jumper form {
    display: inline-flex;
    align-items: center;
}
.pagination-jumper input {
    width: 50px;
    margin: 0 5px;
    padding: 5px;
    text-align: center;
}
.pagination-jumper button {
    margin-left: 5px;
    padding: 5px 10px;
    cursor: pointer;
}
.total-info {
    margin-right: 5px;
    color: #666;
}
</style> 