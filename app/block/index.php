<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $dt = new \App\Utils\DirectoryTraverser();
    $result = $dt->getAllBlogs();
    $blogs = $result['blogs'];

    // 收集所有分类和标签
    $allCategories = [];
    $allTags = [];
    foreach ($blogs as $blog) {
        if (isset($blog['category'])) {
            $allCategories[$blog['category']] = true;
        }
        if (isset($blog['tags'])) {
            $tags = is_array($blog['tags']) ? $blog['tags'] : explode(',', $blog['tags']);
            foreach ($tags as $tag) {
                $allTags[trim($tag)] = true;
            }
        }
    }

    // 生成描述
    $settings = getBlogsConfig();
    $description = "欢迎访问" . $settings['site_name'] . "！这里有" . count($blogs) . "篇文章，涵盖" .
        count($allCategories) . "个分类。主要包含" .
        implode('、', array_slice(array_keys($allCategories), 0, 5)) .
        "等话题的原创内容。";

    // 生成关键词
    $keywords = array_merge(
        array_keys($allCategories),
        array_slice(array_keys($allTags), 0, 10)
    );
    ?>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars(implode(',', $keywords)); ?>">

    <!-- Open Graph 标签 -->
    <meta property="og:title" content="<?php echo $settings['site_name']; ?> - 首页">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/responsive.css">
    <title><?php echo $settings['site_name']; ?> - <?php echo $settings['site_description']; ?></title>
</head>

<?php include(PROJECT_ROOT . "/app/block/head.php"); ?>
<?php include(PROJECT_ROOT . "/app/block/navigation.php"); ?>
<main class="container">
    <section class="blog-posts">
        <?php
        try {
            $dt = new \App\Utils\DirectoryTraverser();
            $result = $dt->getAllBlogs();
            $blogs = $result['blogs'];
            $totalPages = $result['totalPages'];
            $currentPage = $result['currentPage'];
            foreach ($blogs as $blog) {
                // 渲染博客列表
                echo '<article class="blog-post">';
                echo '<h2>' . $blog['title'] . '</h2>';
                echo '<p>' . $blog['subtitle'] . '</p>';
                echo '<a href="/app/blogs/' . str_replace('.json', '.html', $blog['path']) . '">阅读全文 &raquo;</a>';
                echo '</article>';
            }
        } catch (\InvalidArgumentException $e) {
            //echo $e->getMessage() . "\n";
        }
        ?>
    </section>

    <!-- 包含分页链接 -->
    <?php include(PROJECT_ROOT . "/app/block/pagination.php"); ?>

    <section class="contact">
        <?php
        $settings = getBlogsConfig();
        ?>
        <h2>联系我们</h2>
        <p>如果您有任何问题或建议，请通过以下方式联系我们：</p>
        <p>邮箱：<?php echo $settings['contact_email']; ?></p>
        <p>微信：<?php echo $settings['wechat_id']; ?></p>
    </section>
</main>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>