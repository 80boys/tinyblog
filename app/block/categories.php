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
    $allBlogs = $dt->getAllBlogs()['blogs'];

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
            $dt = new \App\Utils\DirectoryTraverser();
            $allBlogs = $dt->getAllBlogs()['blogs'];

            // 获取当前分类
            $currentCategory = isset($_GET['category']) ? $_GET['category'] : null;

            // 获取当前页码
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $perPage = 10; // 每页显示的文章数

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

            // 计算总页数
            $totalBlogs = count($categoryBlogs);
            $totalPages = ceil($totalBlogs / $perPage);

            // 确保页码不超出范围
            $page = min($page, $totalPages);

            // 获取当前页的文章
            $start = ($page - 1) * $perPage;
            $pageBlogs = array_slice($categoryBlogs, $start, $perPage);

            // 显示当前分类标题
            if ($currentCategory) {
                echo '<h1>' . htmlspecialchars($currentCategory) . ' 分类下的文章</h1>';
            } else {
                echo '<h1>所有文章分类</h1>';
            }

            // 显示文章列表
            if (!empty($pageBlogs)) {
                foreach ($pageBlogs as $blog) {
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

                // 显示分页
                if ($totalPages > 1) {
                    echo '<div class="pagination">';
                    // 上一页
                    if ($page > 1) {
                        $prevUrl = '?page=' . ($page - 1) . ($currentCategory ? '&category=' . urlencode($currentCategory) : '');
                        echo '<a href="' . $prevUrl . '" class="page-link">&laquo; 上一页</a>';
                    }

                    // 页码
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $pageUrl = '?page=' . $i . ($currentCategory ? '&category=' . urlencode($currentCategory) : '');
                        $activeClass = $i === $page ? ' active' : '';
                        echo '<a href="' . $pageUrl . '" class="page-link' . $activeClass . '">' . $i . '</a>';
                    }

                    // 下一页
                    if ($page < $totalPages) {
                        $nextUrl = '?page=' . ($page + 1) . ($currentCategory ? '&category=' . urlencode($currentCategory) : '');
                        echo '<a href="' . $nextUrl . '" class="page-link">下一页 &raquo;</a>';
                    }
                    echo '</div>';
                }
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