<link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/footer.css') ?>" />
<footer class="footer mt-auto py-3 bg-light">
    <div class="text-center">
        <p>&copy; <?= date('Y') ?> <?= $this->getSiteName() ?>. All rights reserved.</p>
        <p>备案号：<a target="_blank" style="text-decoration: none;" rel="noopener noreferrer" href="https://beian.miit.gov.cn"> <?= $this->getBeianNumber() ?></a></p>
    </div>
    <?= $this->getAnalyticsCode() ?>
</footer>