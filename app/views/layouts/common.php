<!DOCTYPE html>
<html lang="zh-CN" data-theme="<?= $this->getUserTheme() ?>">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= $this->asset('/favicon.ico') ?>">
    <link rel="Bookmark" href="<?= $this->asset('/favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->getDescription() ?>">
    <meta name="keywords" content="<?= $this->getKeywords() ?>">
    <meta name="author" content="<?= $this->getAuthor() ?>">
    <meta property="og:title" content="<?= $this->getTitle() ?>">
    <meta property="og:description" content="<?= $this->getDescription() ?>">
    <meta property="og:type" content="article">
    <title><?= $this->getTitle() ?></title>
    <!-- 公共样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/main.css') ?>">
    <!-- 主题样式 -->
    <link rel="stylesheet" href="<?= $this->asset('/app/html/css/common/theme.css') ?>">

</head>
<body>
    <?php $this->renderContent(); ?>
</body>
</html>
