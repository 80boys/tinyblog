<?php 
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php"); 

?>
    <main class="container">
        <article>
            <h2>分类管理</h2>
            <section>
                <form action="<?php echo BASE_PATH; ?>/app/end/save_category.php" method="post">
                    <label for="category-name">分类名称：</label>
                    <input type="text" id="category-name" name="category_name" required>
                    <button type="submit">保存分类</button>
                </form>
                <h3>已存在的分类：</h3>
                <ul>
                    <?php
                    $categoriesFile = PROJECT_ROOT . '/app/blogs/categories.data';
                    if (file_exists($categoriesFile)) {
                        $categories = json_decode(file_get_contents($categoriesFile), true);
                        foreach ($categories as $category) {
                            echo '<li>' . $category . '</li>';
                        }
                    }
                    ?>
                </ul>
            </section>
        </article>
    </main>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>
