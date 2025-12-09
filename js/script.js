// 当页面加载完成后执行
document.addEventListener("DOMContentLoaded", function() {
    
    // 如果你想手动通过JS控制 Tilt 效果，可以使用以下代码
    // (但在 HTML 中使用 data-tilt 属性通常更方便，此处仅做演示高级配置)
    
    // 可以在这里添加其他的交互逻辑
    // 比如点击菜单切换 active 状态
    
    const menuItems = document.querySelectorAll('.menu a');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 移除所有 active 类
            menuItems.forEach(link => link.classList.remove('active'));
            
            // 给当前点击的添加 active
            this.classList.add('active');
        });
    });

    console.log("Glass UI Loaded Successfully!");
});
