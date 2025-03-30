<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        header {
            background: #f4f4f4;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        h1 {
            margin: 0;
            color: #333;
        }
        .content {
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        nav {
            margin-bottom: 20px;
        }
        nav a {
            margin-right: 10px;
        }
        .about-content {
            margin-top: 2rem;
        }
        
        .about-content h2 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #343a40;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .about-content ul {
            padding-left: 1.5rem;
        }
        
        .about-content li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $title; ?></h1>
    </header>
    
    <nav>
        <a href="/">首页</a>
        <a href="/home/about">关于</a>
    </nav>
    
    <div class="content">
        <h1>关于 TinyBlog</h1>

        <p><?php echo $content; ?></p>

        <div class="about-content">
            <h2>我们的故事</h2>
            <p>TinyBlog 是一个基于 PHP 开发的简单博客系统，使用了 MVC 架构，专注于轻量级和易用性。</p>
            <p>这个项目的目标是提供一个简单而功能强大的博客平台，适合个人使用或者学习 PHP 开发。</p>
            
            <h2>核心特性</h2>
            <ul>
                <li>轻量级设计：不依赖大型框架，启动快速</li>
                <li>MVC 架构：代码组织清晰，易于维护</li>
                <li>模块化结构：易于扩展和定制</li>
                <li>响应式界面：适配各种设备屏幕</li>
            </ul>
            
            <h2>技术栈</h2>
            <p>TinyBlog 使用了以下技术：</p>
            <ul>
                <li>PHP 7+</li>
                <li>纯原生 JavaScript</li>
                <li>CSS3</li>
                <li>MySQL 数据库</li>
            </ul>
        </div>
    </div>
</body>
</html> 