<?php
session_start();

// === 配置区域 ===
$admin_password = "123456"; // 修改这里的密码
$data_file = 'data.json';
// ===============

// 处理登录
if (isset($_POST['login'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['is_admin'] = true;
    } else {
        $error = "密码错误";
    }
}

// 处理保存数据
if (isset($_POST['save']) && isset($_SESSION['is_admin'])) {
    $names = $_POST['name'];
    $urls = $_POST['url'];
    $icons = $_POST['icon'];
    
    $new_data = [];
    for ($i = 0; $i < count($names); $i++) {
        if (!empty($names[$i])) {
            $new_data[] = [
                'name' => $names[$i],
                'url' => $urls[$i],
                'icon' => $icons[$i]
            ];
        }
    }
    
    file_put_contents($data_file, json_encode($new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = "保存成功！";
}

// 读取当前数据
$current_data = json_decode(file_get_contents($data_file), true);
if (!$current_data) $current_data = [];
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #24243e; color: white; font-family: sans-serif; display: flex; justify-content: center; padding: 50px; }
        .admin-panel { background: rgba(255,255,255,0.1); padding: 30px; border-radius: 20px; width: 800px; backdrop-filter: blur(10px); }
        input { background: rgba(255,255,255,0.2); border: none; padding: 10px; color: white; border-radius: 5px; margin: 5px; width: 100%; box-sizing: border-box;}
        .row { display: grid; grid-template-columns: 1fr 2fr 1fr 50px; gap: 10px; margin-bottom: 10px; align-items: center;}
        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; }
        .btn-save { background: #00d2ff; color: #000; display: block; width: 100%; margin-top: 20px;}
        .btn-del { background: #ff4b4b; color: white; width: 100%; text-align: center;}
        .btn-add { background: #28a745; color: white; margin-bottom: 20px; }
        h2 { text-align: center; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 20px; }
        a { color: #00d2ff; text-decoration: none; display: inline-block; margin-bottom: 20px;}
    </style>
</head>
<body>

    <div class="admin-panel">
        <a href="index.php"><i class="fa-solid fa-arrow-left"></i> 返回首页</a>
        <h2>链接管理后台</h2>

        <?php if (!isset($_SESSION['is_admin'])): ?>
            <form method="post" style="max-width: 300px; margin: 0 auto;">
                <p>请输入密码:</p>
                <input type="password" name="password" required>
                <button type="submit" name="login" class="btn-save">登录</button>
                <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
            </form>
        <?php else: ?>
            <?php if (isset($message)) echo "<p style='color:#00d2ff; text-align:center'>$message</p>"; ?>
            
            <form method="post">
                <button type="button" class="btn-add" onclick="addRow()">+ 添加新链接</button>
                
                <div id="link-list">
                    <?php foreach ($current_data as $index => $item): ?>
                    <div class="row">
                        <input type="text" name="name[]" value="<?php echo $item['name']; ?>" placeholder="名称">
                        <input type="text" name="url[]" value="<?php echo $item['url']; ?>" placeholder="链接 URL">
                        <input type="text" name="icon[]" value="<?php echo $item['icon']; ?>" placeholder="图标代码 (fa-xxx)">
                        <button type="button" class="btn-del" onclick="this.parentElement.remove()">X</button>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" name="save" class="btn-save">保存修改</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function addRow() {
            const div = document.createElement('div');
            div.className = 'row';
            div.innerHTML = `
                <input type="text" name="name[]" placeholder="名称">
                <input type="text" name="url[]" placeholder="链接 URL">
                <input type="text" name="icon[]" placeholder="fa-solid fa-link" value="fa-solid fa-link">
                <button type="button" class="btn-del" onclick="this.parentElement.remove()">X</button>
            `;
            document.getElementById('link-list').appendChild(div);
        }
    </script>
</body>
</html>
