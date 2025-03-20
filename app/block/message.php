<?php !defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 50vw;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                width: 90vw;
                text-align: center;
            }
        }

        p {
            font-size: 14px;
        }

        .direct-link {
            display: inline-block;
            margin-top: 10px;
            color: #3498db;
            text-decoration: underline;
            cursor: pointer;
        }

        .direct-link:hover {
            color: #2980b9;
        }
    </style>

</head>

<body>
    <div class="container">
        <h3><?php echo $title; ?></h3>
        <p><?php echo $text; ?></p>
        <p>页面将在 <span id="countdown"><?php echo $seconds; ?></span> 秒后自动跳转。</p>
        <a href="<?php echo $redirectUrl; ?>" class="direct-link">立即跳转</a>
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