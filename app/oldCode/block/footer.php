<footer>
    <?php
    $settings = getBlogsConfig();
    ?>
    <p>© <?php echo date("Y"); ?> <?php echo $settings['site_name']; ?> <a target="_blank" rel="noopener noreferrer" href="https://beian.miit.gov.cn"> <?php echo $settings['beian_number']; ?></a></p>
</footer>

<?php if (!empty($settings['analytics_code'])): ?>
    <?php echo $settings['analytics_code']; ?>
<?php endif; ?>

</body>

</html>