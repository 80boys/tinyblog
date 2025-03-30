<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'TinyBlog'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        .header h1 {
            margin: 0;
            padding: 0;
        }
        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav {
            display: flex;
        }
        .nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 1rem;
            padding: 0.5rem;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        .nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .content {
            background-color: #fff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>TinyBlog</h1>
            <nav class="nav">
                <a href="/">首页</a>
                <a href="/home/about">关于</a>
                <a href="/blog">博客</a>
                <a href="/contact">联系我们</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <main class="content">
            <?php echo $content; ?>
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> TinyBlog. 保留所有权利。</p>
        </div>
    </footer>
</body>
</html> 