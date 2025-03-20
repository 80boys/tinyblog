<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php");

?>
<link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/simplemde.min.css" />
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/edit.css" />
<main class="container">
    <?php
    $blog = [];
    if (isset($_GET['blog_path'])) {
        $blogPath = \App\Utils\InputValidator::getSafeInput($_GET['blog_path']);
        $dt = new \App\Utils\DirectoryTraverser();
        $blog = $dt->getJsonContent(PROJECT_ROOT . "/app/blogs/" . $blogPath);
        // dump($blog);
    }

    ?>
    <article>
        <h2>编写博客</h2>
        <form action="<?php echo BASE_PATH; ?>/app/end/save_blogs.php" method="post" enctype="multipart/form-data">
            <section class="form-container">
                <div class="full-width">
                    <label for="blog-subtitle">博文介绍：</label>
                    <input type="text" id="blog-subtitle" value="<?php echo isset($blog['subtitle']) ? $blog['subtitle'] : ''  ?>" name="blog_subtitle">
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
                    <input type="text" id="blog-tags" value="<?php echo isset($blog['tags']) ? (is_array($blog['tags']) ? implode(', ', $blog['tags']) : $blog['tags']) : ''  ?>" name="blog_tags">
                </div>
                <div class="form-group">
                    <label for="blog-category">博客分类：</label>
                    <select id="blog-category" name="blog_category">
                        <?php
                        $categoriesFile = PROJECT_ROOT . '/app/blogs/categories.php';
                        if (file_exists($categoriesFile)) {
                            $categories = require $categoriesFile;
                            foreach ($categories as $category) {
                                $selected = (isset($blog["category"]) && $blog["category"] == $category) ? "selected" : "";
                                echo "<option value=\"$category\" $selected>$category</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="blog-public">博客公开状态：</label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="blog-public" name="blog_public" <?php echo !isset($blog['is_private']) || $blog['is_private'] === false ? 'checked' : ''; ?>>
                        <label for="blog-public"></label>
                        <span class="toggle-label">公开</span>
                    </div>
                    <p class="form-help">关闭后，博客仅在后台可见，前台不会显示</p>
                </div>
                <div class="form-group">
                    <label for="blog-type">博客类型：</label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="blog-type" name="blog_independent" <?php echo isset($blog['is_independent']) && $blog['is_independent'] === true ? 'checked' : ''; ?>>
                        <label for="blog-type"></label>
                        <span class="toggle-label">独立页面</span>
                    </div>
                    <p class="form-help">开启后，此博客将作为独立页面显示在导航栏</p>
                </div>
                <div class="form-group">
                    <label for="blog-attachment">博客附件：</label>
                    <input type="file" id="blog-attachment" name="blog_attachment">
                </div>
            </section>
        </form>
    </article>
</main>
<script src="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/js/simplemde.min.js"></script>
<script src="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/js/qiniu.min.js"></script>
<script>
    const bucketDoman = '<?php $settings = getBlogsConfig();
                            echo $settings["qiniu_domain"];  ?>';
    async function getQiniuToken() {
        const response = await fetch('/app/end/getQiniuToken.html');
        const data = await response.json();
        console.log(data)
        return data.token;
    }

    async function uploadToQiniu(file, filekey) {
        const token = await getQiniuToken();
        var config = {
            useCdnDomain: true,
            region: qiniu.region.z1
        };
        const observable = qiniu.upload(file, `mapleBridge/${filekey}`, token, null, config);
        return new Promise((resolve, reject) => {
            observable.subscribe({
                next: () => {},
                error: (err) => reject(err),
                complete: (res) => resolve(res.key)
            });
        });
    }
    const simplemde = new SimpleMDE({
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
    });
    // 处理图片粘贴事件
    simplemde.codemirror.on('paste', async (cm, event) => {
        const clipboardData = event.clipboardData || event.originalEvent.clipboardData;
        if (clipboardData.items) {
            for (let i = 0; i < clipboardData.items.length; i++) {
                const item = clipboardData.items[i];
                if (item.type.indexOf('image') !== -1) {
                    const file = await new Promise((resolve) => {
                        // 直接从 clipboardData.files 获取文件
                        const blob = clipboardData.files[i];
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(blob);
                        reader.readAsDataURL(blob);
                    });
                    const key = await uploadToQiniu(file, Date.now() + '.png');
                    const imageUrl = `${bucketDoman}/${key}`;
                    const cursor = cm.getCursor();
                    cm.replaceRange(`![Uploaded Image](${imageUrl})`, cursor);
                }
            }
        }
    });

    // 处理图片拖拽事件
    simplemde.codemirror.on('drop', async (cm, event) => {
        event.preventDefault();
        const files = event.dataTransfer.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.indexOf('image') !== -1) {
                const key = await uploadToQiniu(file, Date.now() + "-" + file.name);
                const imageUrl = `${bucketDoman}/${key}`;
                const cursor = cm.getCursor();
                cm.replaceRange(`![Uploaded Image](${imageUrl})`, cursor);
            }
        }
    });
</script>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>