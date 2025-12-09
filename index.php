<?php
// 读取数据文件
$json_data = file_get_contents('data.json');
$links = json_decode($json_data, true);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glass Navigation</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <main class="glass-container" data-tilt data-tilt-max="1" data-tilt-speed="400">
        
        <aside class="glass-sidebar">
            <div class="profile">
                <div class="avatar"><i class="fa-solid fa-user-astronaut"></i></div>
                <h2>My Navigation</h2>
                <p>极简 · 拟态 · 导航</p>
            </div>
            <div class="sidebar-footer">
                <a href="admin.php" class="setting-btn"><i class="fa-solid fa-gear"></i> 管理后台</a>
            </div>
        </aside>

        <section class="glass-content">
            <div class="app-grid">
                <?php if (!empty($links)): ?>
                    <?php foreach ($links as $link): ?>
                    <a href="<?php echo $link['url']; ?>" target="_blank" class="app-card">
                        <div class="app-icon">
                            <i class="<?php echo $link['icon']; ?>"></i>
                        </div>
                        <span class="app-name"><?php echo $link['name']; ?></span>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color:white; text-align:center;">暂无链接，请去后台添加。</p>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
</body>
</html>
