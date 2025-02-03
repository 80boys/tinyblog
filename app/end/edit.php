<?php !defined('PROJECT_ROOT') && exit();  ?>

<?php include(PROJECT_ROOT . "/app/block/head.php"); ?>
<?php include(PROJECT_ROOT . "/app/block/navi.php"); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH;?>/app/html/css/simplemde.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/end/edit.css" />
    <main class="container">
        <article>
            <h2>编写博客</h2>
            <form action="<?php echo BASE_PATH; ?>/app/end/save.php" method="post" enctype="multipart/form-data">
                <section class="form-container">
                    <div class="full-width">
                        <label for="blog-subtitle">博文介绍：</label>
                        <input type="text" id="blog-subtitle" name="blog_subtitle">
                        <textarea name="blog_content" id="my-editor"></textarea>
                        <button type="submit">发布博客</button>
                        <input type="hidden" name="blog_path" value="<?php echo isset($_GET['blog_path']) ? $_GET['blog_path'] : ''  ?>">
                    </div>
                    <div class="form-group">
                        <label for="blog-title">博客标题：</label>
                        <input type="text" id="blog-title" name="blog_title" required>
                    </div>
                    <div class="form-group">
                        <label for="blog-tags">博客标签：</label>
                        <input type="text" id="blog-tags" name="blog_tags">
                    </div>
                    <div class="form-group">
                        <label for="blog-category">博客分类：</label>
                        <select id="blog-category" name="blog_category">
                            <?php
                            $categoriesFile = PROJECT_ROOT . '/app/blogs/categories.data';
                            if (file_exists($categoriesFile)) {
                                $categories = json_decode(file_get_contents($categoriesFile), true);
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category . '">' . $category . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="blog-attachment">博客附件：</label>
                        <input type="file" id="blog-attachment" name="blog_attachment">
                    </div>
                </section>
            </form>
        </article>
    </main>
    <script src="<?php echo BASE_PATH; ?>/app/html/js/simplemde.min.js"></script>
    <script> 
        new SimpleMDE(
            { 
                element: document.getElementById("my-editor"),
                toolbar: [
                    "bold",
                    "italic",
                    "strikethrough",
                    "heading",
                    "heading-smaller",
                    "heading-bigger",
                    "code",
                    "quote",
                    "unordered-list",
                    "ordered-list",
                    "clean-block",
                    "link",
                    "image",
                    "table",
                    "horizontal-rule",
                    "preview",
                    "side-by-side",
                    "fullscreen",
                    "guide"
                ]
            }
        ); </script>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>