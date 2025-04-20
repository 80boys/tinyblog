<link rel="stylesheet" href="<?php echo $this->asset('/app/html/css/admin/category.css'); ?>">
<main class="category-page">
    <div class="category-container">
        <div class="category-form">
            <h2>添加分类</h2>
            <form action="<?= $this->getUrl('admin/saveCategory') ?>" method="post">
                <div class="form-group">
                    <label for="category-name">分类名称：</label>
                    <input type="text" id="category-name" name="category_name" placeholder="请输入分类名称" required>
                </div>
                <button type="submit">保存分类</button>
            </form>
        </div>
        
        <div class="category-list">
            <h2>已存在的分类</h2>
            <?php if (!empty($categories)): ?>
                <ul>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <span><?= htmlspecialchars($category) ?></span>
                            <button class="delete-button" onclick="if(confirm('确定要删除此分类吗？')) window.location.href='<?= $this->getUrl('admin/deleteCategory', ['name' => urlencode($category)]) ?>'">删除</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="empty-category">暂无分类，请添加新分类</p>
            <?php endif; ?>
        </div>
    </div>
</main>