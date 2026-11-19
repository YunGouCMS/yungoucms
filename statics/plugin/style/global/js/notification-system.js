/**
 * 现代化通知消息系统
 * 替代layer.msg和layer.alert
 */

(function() {
    'use strict';

    // 创建通知容器
    let container = null;

    function initContainer() {
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        return container;
    }

    /**
     * 显示通知消息
     * @param {string} message - 消息内容
     * @param {string} type - 类型: success, error, warning, info
     * @param {object} options - 配置选项
     * @param {number} options.duration - 显示时长（毫秒），0表示不自动关闭
     * @param {string} options.title - 标题
     * @param {function} options.onClose - 关闭回调
     */
    function showNotification(message, type = 'info', options = {}) {
        const defaultOptions = {
            duration: 3000,
            title: '',
            onClose: null
        };
        
        const config = Object.assign({}, defaultOptions, options);
        const notificationContainer = initContainer();
        
        // 创建通知元素
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // 图标
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        
        const icon = document.createElement('div');
        icon.className = 'notification-icon';
        icon.textContent = icons[type] || icons.info;
        
        // 内容区域
        const content = document.createElement('div');
        content.className = 'notification-content';
        
        if (config.title) {
            const title = document.createElement('div');
            title.className = 'notification-title';
            title.textContent = config.title;
            content.appendChild(title);
        }
        
        const messageEl = document.createElement('div');
        messageEl.className = 'notification-message';
        messageEl.textContent = message;
        content.appendChild(messageEl);
        
        // 关闭按钮
        const closeBtn = document.createElement('button');
        closeBtn.className = 'notification-close';
        closeBtn.innerHTML = '×';
        closeBtn.setAttribute('aria-label', '关闭');
        
        // 进度条
        const progress = document.createElement('div');
        progress.className = 'notification-progress';
        
        // 组装元素
        notification.appendChild(icon);
        notification.appendChild(content);
        notification.appendChild(closeBtn);
        notification.appendChild(progress);
        
        notificationContainer.appendChild(notification);
        
        // 设置进度条动画
        if (config.duration > 0) {
            progress.style.animationDuration = config.duration + 'ms';
        } else {
            progress.style.display = 'none';
        }
        
        // 关闭函数
        function closeNotification() {
            notification.classList.add('removing');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
                if (config.onClose && typeof config.onClose === 'function') {
                    config.onClose();
                }
            }, 300);
        }
        
        // 绑定关闭事件
        closeBtn.addEventListener('click', closeNotification);
        
        // 自动关闭
        if (config.duration > 0) {
            setTimeout(closeNotification, config.duration);
        }
        
        // 点击通知区域也可以关闭（可选）
        notification.addEventListener('click', function(e) {
            if (e.target === notification || e.target === content || e.target === messageEl) {
                // 点击内容区域不关闭，只有点击关闭按钮才关闭
            }
        });
        
        return notification;
    }

    /**
     * 成功消息
     */
    function success(message, options = {}) {
        return showNotification(message, 'success', options);
    }

    /**
     * 错误消息
     */
    function error(message, options = {}) {
        return showNotification(message, 'error', options);
    }

    /**
     * 警告消息
     */
    function warning(message, options = {}) {
        return showNotification(message, 'warning', options);
    }

    /**
     * 信息消息
     */
    function info(message, options = {}) {
        return showNotification(message, 'info', options);
    }

    /**
     * 确认对话框（替代layer.confirm）
     */
    function confirm(message, options = {}) {
        return new Promise((resolve, reject) => {
            const defaultOptions = {
                title: '确认',
                confirmText: '确认',
                cancelText: '取消',
                onConfirm: null,
                onCancel: null
            };
            
            const config = Object.assign({}, defaultOptions, options);
            
            // 创建遮罩层
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.2s ease-out;
            `;
            
            // 创建对话框
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                max-width: 400px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: slideUp 0.3s ease-out;
            `;
            
            const title = document.createElement('div');
            title.style.cssText = 'font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 12px;';
            title.textContent = config.title;
            
            const content = document.createElement('div');
            content.style.cssText = 'font-size: 14px; color: #6b7280; margin-bottom: 20px; line-height: 1.6;';
            content.textContent = message;
            
            const buttons = document.createElement('div');
            buttons.style.cssText = 'display: flex; gap: 12px; justify-content: flex-end;';
            
            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = config.cancelText;
            cancelBtn.style.cssText = `
                padding: 8px 16px;
                border: 1px solid #e5e7eb;
                background: #fff;
                color: #374151;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.2s ease;
            `;
            cancelBtn.onmouseover = function() {
                this.style.background = '#f9fafb';
            };
            cancelBtn.onmouseout = function() {
                this.style.background = '#fff';
            };
            
            const confirmBtn = document.createElement('button');
            confirmBtn.textContent = config.confirmText;
            confirmBtn.style.cssText = `
                padding: 8px 16px;
                border: none;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #fff;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
                transition: all 0.2s ease;
            `;
            confirmBtn.onmouseover = function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 12px rgba(102, 126, 234, 0.4)';
            };
            confirmBtn.onmouseout = function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            };
            
            function closeDialog(result) {
                overlay.style.animation = 'fadeOut 0.2s ease-out';
                dialog.style.animation = 'slideDown 0.2s ease-out';
                setTimeout(() => {
                    document.body.removeChild(overlay);
                    if (result) {
                        if (config.onConfirm) config.onConfirm();
                        resolve(result);
                    } else {
                        if (config.onCancel) config.onCancel();
                        reject(false);
                    }
                }, 200);
            }
            
            cancelBtn.addEventListener('click', () => closeDialog(false));
            confirmBtn.addEventListener('click', () => closeDialog(true));
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    closeDialog(false);
                }
            });
            
            buttons.appendChild(cancelBtn);
            buttons.appendChild(confirmBtn);
            
            dialog.appendChild(title);
            dialog.appendChild(content);
            dialog.appendChild(buttons);
            overlay.appendChild(dialog);
            document.body.appendChild(overlay);
        });
    }

    // 添加CSS动画
    if (!document.getElementById('notification-animations')) {
        const style = document.createElement('style');
        style.id = 'notification-animations';
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
            @keyframes slideUp {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            @keyframes slideDown {
                from {
                    transform: translateY(0);
                    opacity: 1;
                }
                to {
                    transform: translateY(20px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // 导出到全局
    window.NotificationSystem = {
        show: showNotification,
        success: success,
        error: error,
        warning: warning,
        info: info,
        confirm: confirm
    };

    // 兼容layer API - 使用更可靠的方式拦截layer方法
    if (typeof window.layer === 'undefined') {
        window.layer = {};
    }

    // 保存原始函数（如果存在）
    let originalLayerMsg = window.layer.msg;
    let originalLayerAlert = window.layer.alert;
    let originalLayerConfirm = window.layer.confirm;

    // 重写layer.msg - 使用defineProperty确保即使layer.js后加载也能拦截
    function overrideLayerMsg() {
        if (window.layer && typeof window.layer.msg === 'function' && window.layer.msg !== layerMsgWrapper) {
            originalLayerMsg = window.layer.msg;
        }
        
        try {
            Object.defineProperty(window.layer, 'msg', {
                value: layerMsgWrapper,
                writable: true,
                configurable: true
            });
        } catch(e) {
            window.layer.msg = layerMsgWrapper;
        }
    }

    function layerMsgWrapper(message, options = {}) {
        if (typeof options === 'number') {
            options = { duration: options };
        }
        
        let type = 'info';
        if (options.icon === 1) {
            type = 'success';
        } else if (options.icon === 2) {
            type = 'error';
        } else if (options.icon === 3) {
            type = 'warning';
        }
        
        const notification = showNotification(message, type, {
            duration: options.time || options.duration || 3000,
            onClose: options.end || null
        });
        
        // 如果原函数存在且需要回调，调用原函数
        if (originalLayerMsg && typeof originalLayerMsg === 'function' && options.end) {
            setTimeout(() => {
                if (options.end) options.end();
            }, options.time || options.duration || 3000);
        }
        
        return notification;
    }

    // 重写layer.alert
    function overrideLayerAlert() {
        if (window.layer && typeof window.layer.alert === 'function' && window.layer.alert !== layerAlertWrapper) {
            originalLayerAlert = window.layer.alert;
        }
        
        try {
            Object.defineProperty(window.layer, 'alert', {
                value: layerAlertWrapper,
                writable: true,
                configurable: true
            });
        } catch(e) {
            window.layer.alert = layerAlertWrapper;
        }
    }

    function layerAlertWrapper(message, options = {}) {
        if (typeof options === 'function') {
            options = { yes: options };
        }
        
        return confirm(message, {
            title: options.title || '提示',
            confirmText: options.btn || '确定',
            onConfirm: options.yes || null
        });
    }

    // 重写layer.confirm
    function overrideLayerConfirm() {
        if (window.layer && typeof window.layer.confirm === 'function' && window.layer.confirm !== layerConfirmWrapper) {
            originalLayerConfirm = window.layer.confirm;
        }
        
        try {
            Object.defineProperty(window.layer, 'confirm', {
                value: layerConfirmWrapper,
                writable: true,
                configurable: true
            });
        } catch(e) {
            window.layer.confirm = layerConfirmWrapper;
        }
    }

    function layerConfirmWrapper(message, options = {}) {
        if (typeof options === 'function') {
            options = { yes: options };
        }
        
        return confirm(message, {
            title: options.title || '确认',
            confirmText: options.btn && options.btn[0] ? options.btn[0] : '确认',
            cancelText: options.btn && options.btn[1] ? options.btn[1] : '取消',
            onConfirm: options.yes || null,
            onCancel: options.cancel || null
        });
    }

    // 立即执行重写
    overrideLayerMsg();
    overrideLayerAlert();
    overrideLayerConfirm();

    // 监听layer对象的变化，如果layer.js后加载，再次重写
    let layerCheckInterval = setInterval(function() {
        if (window.layer) {
            if (window.layer.msg && window.layer.msg !== layerMsgWrapper) {
                overrideLayerMsg();
            }
            if (window.layer.alert && window.layer.alert !== layerAlertWrapper) {
                overrideLayerAlert();
            }
            if (window.layer.confirm && window.layer.confirm !== layerConfirmWrapper) {
                overrideLayerConfirm();
            }
        }
    }, 100);

    // 5秒后停止检查（layer.js应该已经加载完成）
    setTimeout(function() {
        clearInterval(layerCheckInterval);
    }, 5000);

    // 页面加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initContainer);
    } else {
        initContainer();
    }
})();

