<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php");
?>
<style>
    /* 美化输入框 */
    input[type="text"] {
        width: 80%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* 美化按钮 */
    button[type="submit"] {
        background-color: #1f8cea;
        color: white;
        padding: 10px 20px;
        margin: 5px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
<main class="container">
    <article>
        <h2>分类管理</h2>
        <section>
            <form action="<?php echo BASE_PATH; ?>/app/end/save_category.php" method="post">
                <label for="category-name">分类名称：</label>
                <input type="text" id="category-name" name="category_name" required>
                <br>
                <button type="submit">保存分类</button>
            </form>
            <h3>已存在的分类：</h3>
            <ul>
                <?php
                $categoriesFile = PROJECT_ROOT . '/app/blogs/categories.php';
                if (file_exists($categoriesFile)) {
                    $categories = require_once $categoriesFile;
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