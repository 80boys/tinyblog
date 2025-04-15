<!DOCTYPE html>
<html lang="zh-CN" data-theme="<?= $this->getUserTheme() ?>">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= $this->asset('/app/favicon.ico') ?>">
    <link rel="Bookmark" href="<?= $this->asset('/app/favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getTitle() ?></title>
    <!-- 编辑器样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/simplemde/simplemde.min.css') ?>">
    <!-- 图标样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/font-awesome/font-awesome.min.css') ?>">
    <!-- 主题样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/theme.css') ?>">
    <!-- 公共样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/main.css') ?>">
    <!-- 主题脚本 -->
    <script src="<?= $this->asset('/app/html/js/theme.js') ?>"></script>
</head>

<body>
    <?php $this->renderPartial('common/bar'); ?>
    <?php $this->renderContent(); ?>
    <?php $this->renderPartial('common/footer'); ?>
</body>

</html>