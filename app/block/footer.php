<footer>
    <?php
        $settings = getBlogsConfig();
    ?>
    <p>© <?php echo date("Y"); ?> <?php echo $settings['website_name'] ?> <a target="_blank" rel="noopener noreferrer" href="https://beian.miit.gov.cn"> <?php echo $settings['beian_number'] ?></a></p>
</footer>
</body>
</html>