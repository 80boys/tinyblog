<link rel="stylesheet" href="<?= $this->asset('/app/html/css/message.css') ?>">
<div class="message-page">
    <div class="message-container">
        <h1 class="message-title"><?= $this->escape($title) ?></h1>
        <p class="message-content"><?= $this->escape($text) ?></p>
        <p class="message-redirect">
            页面将在 <span class="countdown"><?= $seconds ?></span> 秒后跳转到上一页
            <br>
            <a href="<?= $this->escape($redirectUrl) ?>">如果页面没有自动跳转，请点击这里</a>
        </p>
    </div>
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