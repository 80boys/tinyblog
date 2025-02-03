<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body { background-color: #f7f7f7; color: #333; font-family: Arial, sans-serif; }
        .container { margin: 50px auto; max-width: 600px; text-align: center; }
        h1 { font-size: 48px; margin-bottom: 20px; }
        p { font-size: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>
        <p><?php echo $text; ?></p>
        <p>页面将在 <span id="countdown"><?php echo $seconds; ?></span> 秒后自动跳转。</p>
    </div>
    <script>
        // 倒计时跳转
        var seconds = <?php echo $seconds; ?>;
        var redirectUrl = "<?php echo $redirectUrl; ?>";
        function countdown() {
            if (seconds <= 0) {
                window.location.href = redirectUrl;
            } else {
                document.getElementById("countdown").innerText = seconds;
                seconds--;
                setTimeout(countdown, 1000);
            }
        }
        window.onload = countdown;
    </script>
</body>
</html>
