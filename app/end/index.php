<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

// 引入头部和导航栏
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php");
?>

<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/admin.css">
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/admin-theme.css">
<div class="container">
    <div class="main-content">
        <h2>博客管理</h2>
        <div class="admin-actions">
            <a href="<?php echo BASE_PATH; ?>/app/end/edit.html" class="btn btn-primary">添加新博客</a>
        </div>
        <section class="blog-posts">
            <?php
            $dt = new \App\Utils\DirectoryTraverser();
            $result = $dt->getAllBlogs(true); // 后台显示所有博客，包括私有的
            $blogs = $result['blogs'];
            $totalPages = $result['totalPages'];
            $currentPage = $result['currentPage'];
            // 遍历博客列表并输出
            foreach ($blogs as $blog) {
                $isPrivate = isset($blog['is_private']) && $blog['is_private'] === true;
                $isIndependent = isset($blog['is_independent']) && $blog['is_independent'] === true;
                echo '<article class="blog-post' . ($isPrivate ? ' private-blog' : '') . ($isIndependent ? ' independent-blog' : '') . '">';
                echo '<h3>' . $blog['title'] . '</h3>';
                echo '<p>' . $blog['subtitle'] . '</p>';
                echo '<p class="meta">';
                echo '<span>发布于: ' . $blog['date'] . '</span>';
                if (isset($blog['category'])) {
                    echo '<span>分类: ' . $blog['category'] . '</span>';
                }
                if ($isPrivate) {
                    echo '<span class="private-label">私有</span>';
                }
                if ($isIndependent) {
                    echo '<span class="independent-label">独立页面</span>';
                }
                echo '</p>';
                echo '<div class="actions">';
                echo '<a href="' . BASE_PATH . '/app/blogs/' . str_replace('.php', '.html', $blog['path']) . '">查看</a> | ';
                echo '<a href="' . BASE_PATH . '/app/end/edit.html?blog_path=' . $blog['path'] . '">编辑</a> | ';
                echo '<a href="' . BASE_PATH . '/app/end/del.html?blog_path=' . $blog['path'] . '">删除</a>';
                echo '</div>';
                echo '</article>';
            }
            ?>
        </section>

        <!-- 分页 -->
        <?php include(PROJECT_ROOT . "/app/block/pagination.php"); ?>
    </div>
</div>

<?php
// 引入页脚
include(PROJECT_ROOT . "/app/block/footer.php");
?>