<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ". BASE_PATH. "/app/block/login.html");
    exit();
}
?>

<link rel="stylesheet" href="<?php echo BASE_PATH;?>/app/html/css/navigation.css">
<header class="navbar-top">
    <nav class="navbar">
        <a href="<?php echo BASE_PATH; ?>/app/block/index.html">前台</a>
        <a href="<?php echo BASE_PATH; ?>/app/end/index.html">后台</a>
        <a href="<?php echo BASE_PATH; ?>/app/end/edit.html">创建</a>
        <a href="<?php echo BASE_PATH; ?>/app/end/category.html">分类</a>
        <a href="<?php echo BASE_PATH; ?>/app/end/settings.html">设置</a>
        <a href="<?php echo BASE_PATH; ?>/app/end/logout.html">退出</a>
    </nav>
</header>