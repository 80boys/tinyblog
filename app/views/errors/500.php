<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - 服务器错误</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .error-container {
            margin-top: 50px;
            padding: 30px;
            background: #f8f8f8;
            border-radius: 5px;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 10px;
            color: #e74c3c;
        }
        p {
            font-size: 18px;
            color: #666;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>500</h1>
        <p>很抱歉，服务器遇到了错误。</p>
        <p>错误信息：<?= $message ?></p>
        <p><a href="/">返回首页</a></p>
    </div>
</body>
</html> 