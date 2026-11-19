// 键盘快捷键支持
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K: 聚焦搜索框
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[type="search"], input[placeholder*="搜索"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // ESC: 关闭弹窗
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.layui-layer, .modal');
        modals.forEach(modal => {
            if (modal.style.display !== 'none') {
                modal.style.display = 'none';
            }
        });
    }
});

