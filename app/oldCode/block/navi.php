<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_PATH . "/app/block/login.html");
    exit();
}
?>

<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/block-navigation.css">
