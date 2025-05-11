<link rel="stylesheet" href="<?= $this->asset('/app/html/css/admin/login.css') ?>">

<div class="admin-login-page">
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <h1>管理员登录</h1>
            </div>
            <form action="<?= $this->getUrl('/blog/doLogin') ?>" method="post" class="login-form">
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="请输入用户名" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="请输入密码" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="login-btn">登 录</button>
                </div>
                <div class="form-group text-center">
                    <a href="/" class="back-link">返回首页</a>
                </div>
            </form>
        </div>
    </div>
</div>