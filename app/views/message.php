<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        .message-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .message-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .message-content {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .message-redirect {
            color: #999;
            font-size: 14px;
        }
        .countdown {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h1 class="message-title"><?= $this->escape($title) ?></h1>
        <p class="message-content"><?= $this->escape($text) ?></p>
        <p class="message-redirect">
            页面将在 <span class="countdown"><?= $seconds ?></span> 秒后跳转到上一页
            <br>
            <a href="<?= $this->escape($redirectUrl) ?>">如果页面没有自动跳转，请点击这里</a>
        </p>
    </div>
    <script>
        // 倒计时跳转
        var seconds = <?= $seconds ?>;
        var countdown = document.querySelector('.countdown');
        var timer = setInterval(function() {
            seconds--;
            countdown.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '<?= $this->escape($redirectUrl) ?>';
            }
        }, 1000);
    </script>
</body>
</html> 