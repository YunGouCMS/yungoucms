// 防抖函数
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// 节流函数
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// 窗口大小调整防抖
if (typeof set_div === 'function') {
    window.addEventListener('resize', debounce(set_div, 150));
}

// iframe 加载优化
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

// iframe 加载完成事件
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.getElementById('iframe_src');
    if (iframe) {
        const container = iframe.parentElement;
        iframe.addEventListener('load', function() {
            hideIframeSkeleton(container);
        });
    }
});

