<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员登录 - 我的博客</title>
    <link rel="stylesheet" href="<?= $this->asset('css/bootstrap.min.css') ?>">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }
        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .form-floating:focus-within {
            z-index: 2;
        }
        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center">
    <main class="form-signin">
        <form action="/admin/login" method="post">
            <h1 class="h3 mb-3 fw-normal">管理员登录</h1>
            
            <?php if ($error = $this->flash('error')): ?>
                <div class="alert alert-danger"><?= $this->escape($error) ?></div>
            <?php endif; ?>
            
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="用户名" required>
                <label for="username">用户名</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="密码" required>
                <label for="password">密码</label>
            </div>
            
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember" value="1"> 记住我
                </label>
            </div>
            
            <button class="w-100 btn btn-lg btn-primary" type="submit">登录</button>
            
            <p class="mt-5 mb-3 text-muted">
                <a href="/" class="text-decoration-none">返回首页</a>
            </p>
        </form>
    </main>
</body>
</html> 