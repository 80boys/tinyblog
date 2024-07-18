<?php include(PROJECT_ROOT . "/app/block/head.php"); ?>
<style>
    header {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    header h1 {
        margin: 0;
    }
    .blog-posts {
        margin-top: 20px;
    }
    .blog-post {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    .blog-post h2 {
        margin-top: 0;
    }
    .blog-post p {
        margin: 10px 0;
    }
</style>
<main class="container">
    <header>
        <h1>欢迎来到枫桥驿站</h1>
        <p>分享生活，记录时光</p>
    </header>
    
    <section class="blog-posts">
        <article class="blog-post">
            <h2>最新博客文章标题</h2>
            <p>这篇文章简要介绍了博客的主要内容，吸引读者继续阅读全文。</p>
            <a href="#">阅读全文 &raquo;</a>
        </article>
        <!-- 更多文章可以复制上面的结构进行添加 -->
    </section>
    
    <section class="contact">
        <h2>联系我们</h2>
        <p>如果您有任何问题或建议，请通过以下方式联系我们：</p>
        <p>邮箱：example@example.com</p>
        <p>微信：exampleWeChat</p>
    </section>
</main>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>