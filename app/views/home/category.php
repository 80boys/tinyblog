<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/admin.css">
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/admin-theme.css">
<style>
    /* 美化输入框 */
    input[type="text"] {
        width: 80%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid var(--input-border);
        border-radius: 4px;
        box-sizing: border-box;
        background-color: var(--input-bg);
        color: var(--input-text);
    }

    /* 美化按钮 */
    button[type="submit"] {
        background-color: var(--bg-navbar);
        color: var(--text-navbar);
        padding: 10px 20px;
        margin: 5px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: var(--dropdown-hover);
    }

    /* 分类列表样式 */
    ul {
        color: var(--text-primary);
    }

    li {
        margin-bottom: 5px;
    }
</style>
<main class="container">
    <article>
        <h2>分类管理</h2>
        <section>
            <form action="<?= $this->getUrl('admin/saveCategory') ?>" method="post">
                <label for="category-name">分类名称：</label>
                <input type="text" id="category-name" name="category_name" required>
                <br>
                <button type="submit">保存分类</button>
            </form>
            <h3>已存在的分类：</h3>
            <ul>
                <?php
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        echo '<li>' . $category . '</li>';
                    }
                } else {
                    echo '<li>暂无分类</li>';
                }
                ?>
            </ul>
        </section>
    </article>
</main>
<?php $this->renderPartial('common/footer'); ?>