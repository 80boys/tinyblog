<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

// 引入头部和导航栏
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php");
?>

<link rel="stylesheet" href="<?php echo BASE_PATH;?>/app/html/css/admin.css">
<main class="container">
    <article>
        <h2>博客管理</h2>
        <section>
            <?php
                $dt = new \App\Utils\DirectoryTraverser();
                $result = $dt->getAllBlogs();
                $blogs = $result['blogs'];
                $totalPages = $result['totalPages'];
                $currentPage = $result['currentPage'];
                // 遍历博客列表并输出
                foreach ($blogs as $blog) {
                    echo '<section class="blog-item">';
                    echo '<p> 博客标题: <a href="' . BASE_PATH . '/app/blogs/' . rtrim($blog['path'], '.json') . '.html">' . $blog['title'] . '</a></p>';
                    echo '<p> 博客描述: ' . $blog['subtitle'] . '</p>';
                    echo '<p> 编辑时间: ' . $blog['date'] . '</p>';
                    echo '<div class="actions">';
                    echo '  <a href="' . BASE_PATH . '/app/end/edit.html?blog_path=' . $blog['path'] . '">编辑</a> | <a href="' . BASE_PATH . '/app/end/del.html?blog_path=' . $blog['path'] . '">删除</a>';
                    echo '</div>';
                    echo '</section>';
                    echo '<hr>';
                }
            ?>
        </section>

        <!-- 分页 -->
        <?php include(PROJECT_ROOT . "/app/block/pagination.php"); ?>
    </article>
</main>

<?php
// 引入页脚
include(PROJECT_ROOT . "/app/block/footer.php");
?>