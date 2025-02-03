<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");
?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/login.css">
<main class="container">
    <article>
        <h2>登录后台</h2>
        <form action="<?php echo BASE_PATH; ?>/app/end/login.php" method="post">
            <section class="form-container">
                <div class="form-group">
                    <label for="username">用户名：</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">密码：</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">登录</button>
            </section>
        </form>
    </article>
</main>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>
