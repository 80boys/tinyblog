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
            <!-- 博客列表 -->
            <table>
                <thead>
                    <tr>
                        <th>标题</th>
                        <th>描述</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dt = new \App\Utils\DirectoryTraverser();
                    $result = $dt->getAllBlogs();
                    $blogs = $result['blogs'];
                    $totalPages = $result['totalPages'];
                    $currentPage = $result['currentPage'];
                    // 遍历博客列表并输出
                    foreach ($blogs as $blog) {
                        echo '<tr>';
                        echo '<td width="200px"><a href="' . BASE_PATH . '/app/blogs/' . rtrim($blog['path'], '.json') . '.html">' . $blog['title'] . '</a></td>';
                        echo '<td>' . $blog['subtitle'] . '</td>';
                        echo '<td width="100px" style="text-align: center;" >' . $blog['date'] . '</td>';
                        echo '<td width="80px"><a href="' . BASE_PATH . '/app/end/edit.html?blog_path=' . $blog['path'] . '">编辑</a> | <a href="' . BASE_PATH . '/app/end/del.html?blog_path=' . $blog['path'] . '">删除</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- 分页 -->
            <?php include(PROJECT_ROOT . "/app/block/pagination.php"); ?>
            
        </section>
    </article>
</main>

<?php
// 引入页脚
include(PROJECT_ROOT . "/app/block/footer.php");
?>