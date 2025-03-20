<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // 获取当前分类
    $currentCategory = isset($_GET['category']) ? $_GET['category'] : null;

    $dt = new \App\Utils\DirectoryTraverser();
    $result = $dt->getAllBlogs();
    $allBlogs = $result['blogs'];

    // 收集所有分类信息
    $categories = [];
    foreach ($allBlogs as $blog) {
        if (isset($blog['category']) && !empty($blog['category'])) {
            if (!isset($categories[$blog['category']])) {
                $categories[$blog['category']] = 0;
            }
            $categories[$blog['category']]++;
        }
    }

    // 生成描述
    if ($currentCategory) {
        $categoryCount = isset($categories[$currentCategory]) ? $categories[$currentCategory] : 0;
        $description = "查看{$currentCategory}分类下的全部文章，共计{$categoryCount}篇文章。";
        $title = "{$currentCategory} - 博客分类";
    } else {
        $description = "博客文章分类导航，共有" . count($categories) . "个分类，" .
            array_sum($categories) . "篇文章。包括" .
            implode('、', array_slice(array_keys($categories), 0, 5)) .
            "等分类。";
        $title = "全部分类 - 博客分类";
    }

    // 生成关键词
    $keywords = array_keys($categories);
    if ($currentCategory) {
        array_unshift($keywords, $currentCategory);
    }
    ?>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars(implode(',', $keywords)); ?>">

    <!-- Open Graph 标签 -->
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?'); ?>">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/categories.css">
    <title><?php echo htmlspecialchars($title); ?></title>
</head>

<?php include(PROJECT_ROOT . "/app/block/head.php"); ?>
<link rel="stylesheet" href="<?php echo BASE_PATH; ?>/app/html/css/categories.css">
<?php include(PROJECT_ROOT . "/app/block/navigation.php"); ?>

<main class="container">
    <section class="categories">
        <?php
        try {
            // 获取当前分类
            $currentCategory = isset($_GET['category']) ? $_GET['category'] : null;

            // 筛选当前分类的文章
            $categoryBlogs = [];
            foreach ($allBlogs as $blog) {
                if (
                    !$currentCategory ||
                    (isset($blog['category']) && $blog['category'] === $currentCategory)
                ) {
                    $categoryBlogs[] = $blog;
                }
            }

            // 显示当前分类标题
            if ($currentCategory) {
                echo '<h1>' . htmlspecialchars($currentCategory) . ' 分类下的文章</h1>';
            } else {
                echo '<h1>所有文章分类</h1>';
            }

            // 显示文章列表
            if (!empty($categoryBlogs)) {
                foreach ($categoryBlogs as $blog) {
                    echo '<article class="blog-post">';
                    echo '<h2>' . htmlspecialchars($blog['title']) . '</h2>';
                    if (isset($blog['subtitle'])) {
                        echo '<p class="subtitle">' . htmlspecialchars($blog['subtitle']) . '</p>';
                    }
                    if (isset($blog['date'])) {
                        echo '<p class="date">发布于: ' . htmlspecialchars($blog['date']) . '</p>';
                    }
                    if (isset($blog['category'])) {
                        echo '<p class="category">分类: ' . htmlspecialchars($blog['category']) . '</p>';
                    }
                    echo '<a href="/app/blogs/' . str_replace('.json', '.html', $blog['path']) . '" class="read-more">阅读全文 &raquo;</a>';
                    echo '</article>';
                }

                // 使用公共分页组件
                include(PROJECT_ROOT . "/app/block/pagination.php");
            } else {
                echo '<p class="no-posts">该分类下暂无文章</p>';
            }
        } catch (\InvalidArgumentException $e) {
            echo '<p class="error">获取文章列表时出错</p>';
        }
        ?>
    </section>
</main>

<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>