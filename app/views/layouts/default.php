<!DOCTYPE html>
<html lang="zh-CN" data-theme="<?= $this->getUserTheme() ?>">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= $this->asset('/app/favicon.ico') ?>">
    <link rel="Bookmark" href="<?= $this->asset('/app/favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->getDescription() ?>">
    <meta name="keywords" content="<?= $this->getKeywords() ?>">
    <meta name="author" content="<?= $this->getAuthor() ?>">
    <meta property="og:title" content="<?= $this->getTitle() ?>">
    <meta property="og:description" content="<?= $this->getDescription() ?>">
    <meta property="og:type" content="article">
    <title><?= $this->getTitle() ?></title>
    <!-- 编辑器样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/simplemde/simplemde.min.css') ?>">
    <!-- 图标样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/font-awesome/font-awesome.min.css') ?>">
    <!-- 公共样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/main.css') ?>">
    <!-- 主题样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/theme.css') ?>">
    <!-- 主题脚本 -->
    <script src="<?= $this->asset('/app/html/js/theme.js') ?>"></script>
    <!-- 代码高亮样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/highlight/xcode.min.css') ?>" data-highlight-theme="light" <?= $this->isDarkdisabled() ?>>
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/highlight/xcode-dusk.min.css') ?>" data-highlight-theme="dark" <?= $this->isDarkdisabled() ?>>
</head>
<body>
    <?php $this->renderPartial('common/nav'); ?>
    <?php $this->renderContent(); ?>
    <?php $this->renderPartial('common/footer'); ?>
</body>
</html>
