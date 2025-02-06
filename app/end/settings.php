<?php
// 引入自动加载文件
!defined('PROJECT_ROOT') && require_once __DIR__ . "/../../autoload.php";
include(PROJECT_ROOT . "/app/block/head.php");
include(PROJECT_ROOT . "/app/block/navi.php");
?>
<link rel="stylesheet" href="<?php echo ACCELERATE_DOMAIN . BASE_PATH; ?>/app/html/css/settings.css" />
<main class="container">
    <article>
        <h2>网站设置</h2>
        <form action="<?php echo BASE_PATH; ?>/app/end/save_settings.php" method="post">
            <section class="form-container">
                <?php
                // 读取 settings.json 文件
                $settingsFile = PROJECT_ROOT . '/app/blogs/settings.php';
                if (file_exists($settingsFile)) {
                    $settings = require($settingsFile);
                } else {
                    $settings = [];
                }
                ?>
                <div class="form-group">
                    <label for="website_name">网站名称：</label>
                    <input type="text" id="website_name" name="website_name" value="<?php echo isset($settings['website_name']) ? $settings['website_name'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="beian_number">备案号：</label>
                    <input type="text" id="beian_number" name="beian_number" value="<?php echo isset($settings['beian_number']) ? $settings['beian_number'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="contact_email">联系邮箱：</label>
                    <input type="email" id="contact_email" name="contact_email" value="<?php echo isset($settings['contact_email']) ? $settings['contact_email'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="wechat_id">微信号码：</label>
                    <input type="text" id="wechat_id" name="wechat_id" value="<?php echo isset($settings['wechat_id']) ? $settings['wechat_id'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="qiniu_access_key">七牛 Access Key：</label>
                    <input type="text" id="qiniu_access_key" name="qiniu_access_key" value="<?php echo isset($settings['qiniu_access_key']) ? $settings['qiniu_access_key'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="qiniu_secret_key">七牛 Secret Key：</label>
                    <input type="text" id="qiniu_secret_key" name="qiniu_secret_key" value="<?php echo isset($settings['qiniu_secret_key']) ? $settings['qiniu_secret_key'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="qiniu_bucket">七牛 Bucket：</label>
                    <input type="text" id="qiniu_bucket" name="qiniu_bucket" value="<?php echo isset($settings['qiniu_bucket']) ? $settings['qiniu_bucket'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="qiniu_accelerate_domain">七牛加速域名：</label>
                    <input type="text" id="qiniu_accelerate_domain" name="qiniu_accelerate_domain" value="<?php echo isset($settings['qiniu_accelerate_domain']) ? $settings['qiniu_accelerate_domain'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="qiniu_domain">七牛云存储域名：</label>
                    <input type="text" id="qiniu_domain" name="qiniu_domain" value="<?php echo isset($settings['qiniu_domain']) ? $settings['qiniu_domain'] : ''; ?>" required>
                </div>
                <button type="submit">保存设置</button>
            </section>
        </form>
    </article>
</main>
<?php include(PROJECT_ROOT . "/app/block/footer.php"); ?>