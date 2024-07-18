<?php include(PROJECT_ROOT . "/app/block/head.php"); ?>
<?php include(PROJECT_ROOT . "/app/block/navi.php"); ?>
    <main class="container">
        <article>
            <h2>编写博客</h2>
            <section>
                <textarea id="my-editor"></textarea>
                <button onclick="submitPost()">发布博客</button>
            </section>
        </article>
    </main>
    <script src="<?php echo BASE_PATH; ?>/app/html/js/simplemde.min.js"></script>
    <script>
        var simplemde = new SimpleMDE({ element: document.getElementById("my-editor") });
        function submitPost() {
            var postContent = simplemde.value();
            console.log(postContent);
        }
    </script>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>