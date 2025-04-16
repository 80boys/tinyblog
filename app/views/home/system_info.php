<div class="container-fluid px-4">
    <h1 class="mt-4">系统信息</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= $this->getUrl('admin/index'); ?>">首页</a></li>
        <li class="breadcrumb-item active">系统信息</li>
    </ol>

    <div class="row">
        <!-- 博客统计卡片 -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>博客总数</h4>
                    <h2 class="display-4"><?php echo $systemInfo['total_blogs']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>分类总数</h4>
                    <h2 class="display-4"><?php echo $systemInfo['total_categories']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4>标签总数</h4>
                    <h2 class="display-4"><?php echo $systemInfo['total_tags']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4>最后更新</h4>
                    <p class="h5"><?php echo $systemInfo['last_update']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 系统信息表格 -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-server me-1"></i>
                    系统详细信息
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">PHP 版本</th>
                                <td><?php echo $systemInfo['php_version']; ?></td>
                            </tr>
                            <tr>
                                <th>服务器软件</th>
                                <td><?php echo $systemInfo['server_software']; ?></td>
                            </tr>
                            <tr>
                                <th>存储路径</th>
                                <td><?php echo $systemInfo['storage_path']; ?></td>
                            </tr>
                            <tr>
                                <th>缓存文件</th>
                                <td><?php echo $systemInfo['cache_file']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 操作按钮 -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?= $this->getUrl('admin/exportData'); ?>" class="btn btn-success me-2">
                <i class="fas fa-download me-1"></i> 导出数据
            </a>
        </div>
    </div>
</div>