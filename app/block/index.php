<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navigation.php");
?>

<div class="container">
    <div class="main-content">
        <section class="blog-posts">
            <?php
            try {
                $dt = new \App\Utils\DirectoryTraverser();
                $result = $dt->getAllBlogs();
                $blogs = $result['blogs'];
                $totalPages = $result['totalPages'];
                $currentPage = $result['currentPage'];
                foreach ($blogs as $blog) {
                    // 渲染博客列表
                    echo '<article class="blog-post">';
                    echo '<h2>' . $blog['title'] . '</h2>';
                    echo '<p>' . $blog['subtitle'] . '</p>';
                    echo '<a href="/app/blogs/' . str_replace('.json', '.html', $blog['path']) . '">阅读全文 &raquo;</a>';
                    echo '</article>';
                }
            } catch (\InvalidArgumentException $e) {
                //echo $e->getMessage() . "\n";
            }
            ?>
        </section>

        <!-- 包含分页链接 -->
        <?php include(PROJECT_ROOT . "/app/block/pagination.php"); ?>

        <section class="contact">
            <?php
            $settings = getBlogsConfig();
            ?>
            <h2>联系我们</h2>
            <p>如果您有任何问题或建议，请通过以下方式联系我们：</p>
            <p>邮箱：<?php echo $settings['contact_email']; ?></p>
            <p>微信：<?php echo $settings['wechat_id']; ?></p>
        </section>
    </div>
</div>

<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>