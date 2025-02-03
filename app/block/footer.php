<footer>
    <?php
        $settingsFile = PROJECT_ROOT . '/app/blogs/settings.data';
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true);
        } else {
            $settings = [
                'website_name' => '枫桥驿站',
                'beian_number' => '黑ICP备16002822号-5号',
                'contact_email' => '123456@qq.com',
                'wechat_id' => '123456',
            ];
        }
    ?>
    <p>© <?php echo date("Y"); ?> <?php echo $settings['website_name'] ?> <a target="_blank" rel="noopener noreferrer" href="https://beian.miit.gov.cn"> <?php echo $settings['beian_number'] ?></a></p>
</footer>
</body>
</html>