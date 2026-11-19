// 骨架屏预加载功能
function showIframeSkeleton(container) {
    if (container && !container.querySelector('.iframe-skeleton')) {
        const skeleton = document.createElement('div');
        skeleton.className = 'iframe-skeleton';
        container.appendChild(skeleton);
    }
}

function hideIframeSkeleton(container) {
    if (container) {
        const skeleton = container.querySelector('.iframe-skeleton');
        if (skeleton) {
            skeleton.remove();
        }
    }
}

// 导出函数供其他脚本使用
if (typeof window !== 'undefined') {
    window.showIframeSkeleton = showIframeSkeleton;
    window.hideIframeSkeleton = hideIframeSkeleton;
}

