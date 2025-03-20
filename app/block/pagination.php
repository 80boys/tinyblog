<?php

!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

if (!isset($totalPages) || !isset($currentPage)) {
    return;
}

// 构建分页URL，保留所有当前参数
$queryParams = $_GET;
unset($queryParams['page']); // 移除当前页码参数，后面会重新添加
$queryString = http_build_query($queryParams);
$baseUrl = '?' . ($queryString ? $queryString . '&' : '');
?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/pagination.css">
<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage - 1; ?>">上一页</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="<?php echo $baseUrl; ?>page=<?php echo $i; ?>" <?php if ($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>
    <?php if ($currentPage < $totalPages): ?>
        <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage + 1; ?>">下一页</a>
    <?php endif; ?>
</div>