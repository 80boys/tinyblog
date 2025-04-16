<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- 预先应用主题，避免闪烁 -->
    <script>
        (function() {
            // 从localStorage中读取主题设置
            var savedTheme = localStorage.getItem('theme');
            // 如果有保存的主题设置，立即应用
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                // 直接设置HTML和BODY背景色
                document.documentElement.style.backgroundColor = '#121212';
                document.documentElement.style.color = '#e0e0e0';
            }
            // 如果没有保存的设置，检查系统偏好
            else if (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
                // 直接设置HTML和BODY背景色
                document.documentElement.style.backgroundColor = '#121212';
                document.documentElement.style.color = '#e0e0e0';
            }
        })();
    </script>
    <link rel="stylesheet" href="/app/html/css/theme.css">
    <link rel="stylesheet" href="/app/html/css/message.css">
</head>

<body>
    <div class="container">
        <h3><?php echo $title; ?></h3>
        <p><?php echo $text; ?></p>
        <p>页面将在 <span id="countdown"><?php echo $seconds; ?></span> 秒后自动跳转。</p>
        <a class="direct-link" href="<?php echo $redirectUrl; ?>">立即跳转</a>
    </div>
    <script src="/app/html/js/theme.js"></script>
    <script>
        // 倒计时跳转
        var seconds = <?php echo $seconds; ?>;
        var redirectUrl = "<?php echo $redirectUrl; ?>";

        function countdown() {
            if (seconds <= 0) {
                // 添加淡出效果
                document.body.classList.add('fade-out');
                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 300);
            } else {
                document.getElementById("countdown").innerText = seconds;
                seconds--;
                setTimeout(countdown, 1000);
            }
        }

        // 为立即跳转链接添加淡出效果
        document.querySelector('.direct-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.add('fade-out');
            setTimeout(function() {
                window.location.href = redirectUrl;
            }, 300);
        });

        window.onload = countdown;
    </script>
</body>

</html>