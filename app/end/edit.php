<?php 
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php"); 

?>
<link rel="stylesheet" href="<?php echo BASE_PATH;?>/app/html/css/simplemde.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/edit.css" />
    <main class="container">
        <?php
        $blog = [];
        if (isset($_GET['blog_path'])) {
            $blogPath = $_GET['blog_path'];
            $dt = new \App\Utils\DirectoryTraverser();
            $blog = $dt->getJsonContent( PROJECT_ROOT . "/app/blogs/" . $blogPath);
            // dump($blog);
        }
        
        ?>
        <article>
            <h2>编写博客</h2>
            <form action="<?php echo BASE_PATH; ?>/app/end/save_blogs.php" method="post" enctype="multipart/form-data">
                <section class="form-container">
                    <div class="full-width">
                        <label for="blog-subtitle">博文介绍：</label>
                        <input type="text" id="blog-subtitle"  value="<?php echo isset($blog['subtitle']) ? $blog['subtitle'] : ''  ?>" name="blog_subtitle">
                        <textarea name="blog_content" id="my-editor"><?php echo isset($blog['content']) ? $blog['content'] : ''  ?></textarea>
                        <button type="submit">发布博客</button>
                        <input type="hidden" name="blog_path" value="<?php echo isset($blog['path']) ? $blog['path'] : ''  ?>">
                    </div>
                    <div class="form-group">
                        <label for="blog-title">博客标题：</label>
                        <input type="text" id="blog-title" value="<?php echo isset($blog['title']) ? $blog['title'] : ''  ?>" name="blog_title" required>
                    </div>
                    <div class="form-group">
                        <label for="blog-tags">博客标签：</label>
                        <input type="text" id="blog-tags" value="<?php echo isset($blog['tags']) ? $blog['tags'] : ''  ?>" name="blog_tags">
                    </div>
                    <div class="form-group">
                        <label for="blog-category">博客分类：</label>
                        <select id="blog-category" name="blog_category">
                            <?php
                            $categoriesFile = PROJECT_ROOT . '/app/blogs/categories.data';
                            if (file_exists($categoriesFile)) {
                                $categories = json_decode(file_get_contents($categoriesFile), true);
                                foreach ($categories as $category) {
                                    $selected = (isset($blog["category"]) && $blog["category"] == $category) ? "selected" : "";
                                    echo "<option value=\"$category\" $selected>$category</option>";
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