<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";

include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navigation.php");
?>

<div class="container">
    <div class="main-content">
        <?php
        // 获取当前分类
        $currentCategory = isset($_GET['category']) ? $_GET['category'] : null;
        // 获取搜索关键词
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
        // 获取当前页码
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

        $dt = new \App\Utils\DirectoryTraverser();
        $result = $dt->getAllBlogs();
        $allBlogs = $result['blogs'];
        $totalPages = $result['totalPages'];

        // 如果有搜索关键词，过滤博客列表
        if (!empty($searchQuery)) {
            $searchResults = [];
            foreach ($allBlogs as $blog) {
                // 在标题、简介、分类和标签中搜索
                $searchText = strtolower(
                    ($blog['title'] ?? '') . ' ' .
                        ($blog['subtitle'] ?? '') . ' ' .
                        ($blog['category'] ?? '') . ' ' .
                        (is_array($blog['tags'] ?? null) ? implode(' ', $blog['tags']) : ($blog['tags'] ?? ''))
                );
                if (mb_stripos($searchText, strtolower($searchQuery)) !== false) {
                    $searchResults[] = $blog;
                }
            }
            $allBlogs = $searchResults;
        }

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
            $title = "{$currentCategory} - 分类文章";
        } elseif (!empty($searchQuery)) {
            $searchCount = count($allBlogs);
            $description = "搜索" . $searchQuery . "的结果，共找到 " . $searchCount . " 篇相关文章。";
            $title = "搜索：{$searchQuery} - 博客搜索";
        } else {
            $description = "浏览博客的所有分类，查看不同主题下的文章。";
            $title = "文章分类";
        }
        ?>

        <?php
        try {
            // 根据当前分类过滤博客
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
            } elseif (!empty($searchQuery)) {
                echo '<h1>搜索"' . htmlspecialchars($searchQuery) . '"的结果</h1>';
                echo '<p class="search-summary">共找到 ' . count($categoryBlogs) . ' 篇相关文章</p>';
            } else {
                echo '<h1>所有文章分类</h1>';
            }
        ?>

            <section class="blog-posts">
            <?php
            // 显示文章列表
            if (!empty($categoryBlogs)) {
                // 分页处理
                $itemsPerPage = 10; // 每页显示的博客数量
                $totalItems = count($categoryBlogs);
                $totalPages = ceil($totalItems / $itemsPerPage);
                $currentPage = max(1, min($currentPage, $totalPages)); // 确保页码在有效范围内

                // 获取当前页的博客
                $startIndex = ($currentPage - 1) * $itemsPerPage;
                $pageBlogs = array_slice($categoryBlogs, $startIndex, $itemsPerPage);

                foreach ($pageBlogs as $blog) {
                    echo '<article class="blog-post">';
                    echo '<h2>' . htmlspecialchars($blog['title']) . '</h2>';
                    if (isset($blog['subtitle'])) {
                        echo '<p>' . htmlspecialchars($blog['subtitle']) . '</p>';
                    }
                    if (isset($blog['date'])) {
                        echo '<p class="meta"><span>发布于: ' . htmlspecialchars($blog['date']) . '</span>';
                        if (isset($blog['category'])) {
                            echo '<span>分类: ' . htmlspecialchars($blog['category']) . '</span>';
                        }
                        echo '</p>';
                    }
                    echo '<a href="' . BASE_PATH . '/app/blogs/' . str_replace('.php', '.html', $blog['path']) . '">阅读全文 &raquo;</a>';
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
    </div>
</div>

<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>