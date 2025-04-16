<?php
// 引入样式
?>
<link rel="stylesheet" href="<?php echo $this->getUrl('html/css/admin.css'); ?>">
<link rel="stylesheet" href="<?php echo $this->getUrl('html/css/admin-theme.css'); ?>">
<link rel="stylesheet" href="<?php echo $this->getUrl('html/css/settings.css'); ?>">
<link rel="stylesheet" href="<?php echo $this->getUrl('html/css/settings-theme.css'); ?>">

<main class="container">
    <article>
        <h2>网站设置</h2>
        <form action="<?php echo $this->getUrl('admin/saveSettings'); ?>" method="post">
            <section class="form-container">
                <!-- 基本信息设置 -->
                <div class="settings-section">
                    <h3>基本信息</h3>
                    <div class="form-group">
                        <label for="site_name">网站名称：</label>
                        <input type="text" id="site_name" name="site_name"
                            value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                        <span class="hint">显示在浏览器标签和首页标题中</span>
                    </div>
                    <div class="form-group">
                        <label for="site_description">网站描述：</label>
                        <textarea id="site_description" name="site_description" required><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                        <span class="hint">用于SEO，显示在搜索结果中的网站简介</span>
                    </div>
                    <div class="form-group">
                        <label for="author">作者名称：</label>
                        <input type="text" id="author" name="author"
                            value="<?php echo htmlspecialchars($settings['author'] ?? ''); ?>">
                        <span class="hint">显示在文章作者信息中</span>
                    </div>
                    <div class="form-group">
                        <label for="default_keywords">默认关键词：</label>
                        <input type="text" id="default_keywords" name="default_keywords"
                            value="<?php echo htmlspecialchars($settings['default_keywords'] ?? ''); ?>">
                        <span class="hint">用于SEO，多个关键词用英文逗号分隔</span>
                    </div>
                </div>

                <!-- 联系方式设置 -->
                <div class="settings-section">
                    <h3>联系方式</h3>
                    <div class="form-group">
                        <label for="contact_email">联系邮箱：</label>
                        <input type="email" id="contact_email" name="contact_email"
                            value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="wechat_id">微信号码：</label>
                        <input type="text" id="wechat_id" name="wechat_id"
                            value="<?php echo htmlspecialchars($settings['wechat_id'] ?? ''); ?>">
                    </div>
                </div>

                <!-- 七牛云设置 -->
                <div class="settings-section">
                    <h3>七牛云配置</h3>
                    <div class="form-group">
                        <label for="qiniu_access_key">七牛 Access Key：</label>
                        <input type="text" id="qiniu_access_key" name="qiniu_access_key"
                            value="<?php echo htmlspecialchars($settings['qiniu_access_key'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="qiniu_secret_key">七牛 Secret Key：</label>
                        <input type="text" id="qiniu_secret_key" name="qiniu_secret_key"
                            value="<?php echo htmlspecialchars($settings['qiniu_secret_key'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="qiniu_bucket">七牛 Bucket：</label>
                        <input type="text" id="qiniu_bucket" name="qiniu_bucket"
                            value="<?php echo htmlspecialchars($settings['qiniu_bucket'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="qiniu_accelerate_domain">七牛加速域名：</label>
                        <input type="text" id="qiniu_accelerate_domain" name="qiniu_accelerate_domain"
                            value="<?php echo htmlspecialchars($settings['qiniu_accelerate_domain'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="qiniu_domain">七牛云存储域名：</label>
                        <input type="text" id="qiniu_domain" name="qiniu_domain"
                            value="<?php echo htmlspecialchars($settings['qiniu_domain'] ?? ''); ?>" required>
                    </div>
                </div>

                <!-- 安全设置 -->
                <div class="settings-section">
                    <h3>安全设置</h3>
                    <div class="form-group">
                        <label for="admin_username">管理员用户名：</label>
                        <input type="text" id="admin_username" name="admin_username"
                            value="<?php echo htmlspecialchars($settings['admin_username'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_password">修改密码：</label>
                        <input type="password" id="admin_password" name="admin_password">
                        <span class="hint">如不修改密码请留空</span>
                    </div>
                </div>

                <!-- 其他设置 -->
                <div class="settings-section">
                    <h3>其他设置</h3>
                    <div class="form-group">
                        <label for="beian_number">ICP备案号：</label>
                        <input type="text" id="beian_number" name="beian_number"
                            value="<?php echo htmlspecialchars($settings['beian_number'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="footer_text">页脚文本：</label>
                        <textarea id="footer_text" name="footer_text"><?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?></textarea>
                        <span class="hint">显示在网站底部的自定义文本</span>
                    </div>
                    <div class="form-group">
                        <label for="analytics_code">统计代码：</label>
                        <textarea id="analytics_code" name="analytics_code"><?php echo htmlspecialchars($settings['analytics_code'] ?? ''); ?></textarea>
                        <span class="hint">百度统计、Google Analytics 等第三方统计代码</span>
                    </div>
                </div>

                <button type="submit">保存设置</button>
            </section>
        </form>
    </article>
</main>
