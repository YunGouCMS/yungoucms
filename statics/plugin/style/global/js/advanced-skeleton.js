/**
 * 高级骨架屏系统
 * 提供完整的预加载效果：Skeleton Screen、Loading Placeholder、Shimmer Effect、Content Placeholder
 */

(function() {
    'use strict';

    // ========== Skeleton Screen（骨架屏）功能 ==========
    
    /**
     * 创建表格骨架屏
     * @param {HTMLElement} container - 容器元素
     * @param {Object} options - 配置选项
     */
    function createTableSkeleton(container, options = {}) {
        const defaultOptions = {
            rows: 5,
            columns: 4,
            showHeader: true
        };
        
        const config = Object.assign({}, defaultOptions, options);
        
        const skeleton = document.createElement('div');
        skeleton.className = 'page-skeleton';
        
        const table = document.createElement('table');
        table.className = 'skeleton-table';
        
        // 表头
        if (config.showHeader) {
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            for (let i = 0; i < config.columns; i++) {
                const th = document.createElement('th');
                const cell = document.createElement('div');
                cell.className = 'skeleton-cell medium skeleton';
                th.appendChild(cell);
                headerRow.appendChild(th);
            }
            thead.appendChild(headerRow);
            table.appendChild(thead);
        }
        
        // 表体
        const tbody = document.createElement('tbody');
        for (let i = 0; i < config.rows; i++) {
            const tr = document.createElement('tr');
            for (let j = 0; j < config.columns; j++) {
                const td = document.createElement('td');
                const cell = document.createElement('div');
                const widths = ['short', 'medium', 'long'];
                cell.className = 'skeleton-cell ' + widths[j % widths.length] + ' skeleton';
                td.appendChild(cell);
                tr.appendChild(td);
            }
            tbody.appendChild(tr);
        }
        table.appendChild(tbody);
        
        skeleton.appendChild(table);
        return skeleton;
    }
    
    /**
     * 创建表单骨架屏
     * @param {HTMLElement} container - 容器元素
     * @param {Object} options - 配置选项
     */
    function createFormSkeleton(container, options = {}) {
        const defaultOptions = {
            rows: 4
        };
        
        const config = Object.assign({}, defaultOptions, options);
        
        const skeleton = document.createElement('div');
        skeleton.className = 'page-skeleton skeleton-form';
        
        for (let i = 0; i < config.rows; i++) {
            const row = document.createElement('div');
            row.className = 'skeleton-form-row';
            
            const label = document.createElement('div');
            label.className = 'skeleton-form-label skeleton';
            row.appendChild(label);
            
            const input = document.createElement('div');
            input.className = 'skeleton-form-input skeleton';
            row.appendChild(input);
            
            skeleton.appendChild(row);
        }
        
        // 提交按钮
        const button = document.createElement('div');
        button.className = 'skeleton-form-button skeleton';
        skeleton.appendChild(button);
        
        return skeleton;
    }
    
    /**
     * 创建列表骨架屏
     * @param {HTMLElement} container - 容器元素
     * @param {Object} options - 配置选项
     */
    function createListSkeleton(container, options = {}) {
        const defaultOptions = {
            items: 5,
            showAvatar: false
        };
        
        const config = Object.assign({}, defaultOptions, options);
        
        const skeleton = document.createElement('div');
        skeleton.className = 'page-skeleton';
        
        const list = document.createElement('ul');
        list.className = 'skeleton-list';
        
        for (let i = 0; i < config.items; i++) {
            const item = document.createElement('li');
            item.className = 'skeleton-list-item';
            
            if (config.showAvatar) {
                const avatar = document.createElement('div');
                avatar.className = 'skeleton-list-avatar skeleton';
                item.appendChild(avatar);
            }
            
            const content = document.createElement('div');
            content.className = 'skeleton-list-content';
            
            const title = document.createElement('div');
            title.className = 'skeleton-list-title skeleton';
            content.appendChild(title);
            
            const text = document.createElement('div');
            text.className = 'skeleton-list-text skeleton';
            content.appendChild(text);
            
            item.appendChild(content);
            list.appendChild(item);
        }
        
        skeleton.appendChild(list);
        return skeleton;
    }
    
    /**
     * 创建卡片骨架屏
     * @param {HTMLElement} container - 容器元素
     * @param {Object} options - 配置选项
     */
    function createCardSkeleton(container, options = {}) {
        const defaultOptions = {
            cards: 3,
            lines: 3
        };
        
        const config = Object.assign({}, defaultOptions, options);
        
        const fragment = document.createDocumentFragment();
        
        for (let i = 0; i < config.cards; i++) {
            const card = document.createElement('div');
            card.className = 'skeleton-card';
            
            const header = document.createElement('div');
            header.className = 'skeleton-card-header skeleton';
            card.appendChild(header);
            
            for (let j = 0; j < config.lines; j++) {
                const content = document.createElement('div');
                content.className = 'skeleton-card-content skeleton';
                if (j === config.lines - 1) {
                    content.style.width = '60%';
                }
                card.appendChild(content);
            }
            
            fragment.appendChild(card);
        }
        
        return fragment;
    }
    
    // ========== Loading Placeholder（加载占位符）功能 ==========
    
    /**
     * 显示加载占位符
     * @param {HTMLElement} element - 目标元素
     */
    function showLoadingPlaceholder(element) {
        if (!element) return;
        
        const originalContent = element.innerHTML;
        element.dataset.originalContent = originalContent;
        
        const placeholder = document.createElement('div');
        placeholder.className = 'loading-placeholder';
        placeholder.style.height = element.offsetHeight + 'px';
        placeholder.style.width = '100%';
        
        element.innerHTML = '';
        element.appendChild(placeholder);
    }
    
    /**
     * 隐藏加载占位符
     * @param {HTMLElement} element - 目标元素
     */
    function hideLoadingPlaceholder(element) {
        if (!element || !element.dataset.originalContent) return;
        
        element.innerHTML = element.dataset.originalContent;
        delete element.dataset.originalContent;
    }
    
    // ========== Content Placeholder（内容占位符）功能 ==========
    
    /**
     * 显示内容占位符
     * @param {HTMLElement} container - 容器元素
     * @param {string} message - 提示消息
     */
    function showContentPlaceholder(container, message = '加载中...') {
        if (!container) return;
        
        const placeholder = document.createElement('div');
        placeholder.className = 'content-placeholder';
        placeholder.textContent = message;
        
        container.innerHTML = '';
        container.appendChild(placeholder);
        
        return placeholder;
    }
    
    /**
     * 隐藏内容占位符
     * @param {HTMLElement} container - 容器元素
     */
    function hideContentPlaceholder(container) {
        if (!container) return;
        
        const placeholder = container.querySelector('.content-placeholder');
        if (placeholder) {
            placeholder.remove();
        }
    }
    
    // ========== 自动检测并应用骨架屏 ==========
    
    /**
     * 自动为页面元素添加骨架屏
     */
    function autoApplySkeleton() {
        // 检测表格
        const tables = document.querySelectorAll('.table-list table:not(.skeleton-table), .table-form table:not(.skeleton-table)');
        tables.forEach(function(table) {
            if (!table.dataset.skeletonApplied) {
                table.dataset.skeletonApplied = 'true';
                const container = table.closest('.table-list, .table-form');
                if (container && !container.querySelector('.skeleton-table')) {
                    // 检查table是否真的是container的子元素
                    if (!container.contains(table)) {
                        console.warn('Skeleton: table不在container中，跳过处理');
                        return;
                    }
                    
                    const skeleton = createTableSkeleton(container, {
                        rows: 5,
                        columns: table.querySelectorAll('thead th, tbody tr:first-child td').length || 4,
                        showHeader: table.querySelector('thead') !== null
                    });
                    
                    // 安全地插入skeleton，检查table是否仍然是container的子元素
                    try {
                        // 双重检查：确保table和container都还存在且table是container的子元素
                        if (container && table && container.contains && table.parentNode && container.contains(table) && table.parentNode === container) {
                            container.insertBefore(skeleton, table);
                            table.style.opacity = '0';
                            table.style.transition = 'opacity 0.3s ease';
                            
                            // 模拟加载完成后显示真实内容
                            setTimeout(function() {
                                if (skeleton && skeleton.parentNode && skeleton.parentNode === container) {
                                    skeleton.style.transition = 'opacity 0.3s ease';
                                    skeleton.style.opacity = '0';
                                    setTimeout(function() {
                                        if (skeleton && skeleton.parentNode) {
                                            skeleton.remove();
                                        }
                                        if (table && table.parentNode) {
                                            table.style.opacity = '1';
                                        }
                                    }, 300);
                                }
                            }, 500);
                        } else {
                            // table已经被移除或不在container中，清理skeleton
                            if (skeleton && skeleton.parentNode) {
                                skeleton.remove();
                            } else if (skeleton) {
                                // skeleton还没有被插入，直接丢弃
                                skeleton = null;
                            }
                        }
                    } catch (e) {
                        // 静默处理错误，避免控制台噪音
                        if (skeleton && skeleton.parentNode) {
                            try {
                                skeleton.remove();
                            } catch(removeError) {
                                // 忽略移除错误
                            }
                        }
                    }
                }
            }
        });
        
        // 检测表单
        const forms = document.querySelectorAll('form:not([data-skeleton-applied])');
        forms.forEach(function(form) {
            if (form.querySelector('input[type="text"], input[type="password"], select, textarea')) {
                form.dataset.skeletonApplied = 'true';
                const container = form.closest('.table-list, .table-form');
                if (container && !container.querySelector('.skeleton-form')) {
                    const skeleton = createFormSkeleton(container, {
                        rows: Math.min(form.querySelectorAll('input, select, textarea').length, 8)
                    });
                    container.insertBefore(skeleton, form);
                    form.style.opacity = '0';
                    form.style.transition = 'opacity 0.3s ease';
                    
                    setTimeout(function() {
                        if (skeleton.parentNode) {
                            skeleton.style.transition = 'opacity 0.3s ease';
                            skeleton.style.opacity = '0';
                            setTimeout(function() {
                                skeleton.remove();
                                form.style.opacity = '1';
                            }, 300);
                        }
                    }, 400);
                }
            }
        });
    }
    
    // ========== 根据页面类型创建个性化骨架屏 ==========
    
    /**
     * 根据页面URL和内容结构创建个性化骨架屏
     * @param {HTMLElement} container - 容器元素
     * @returns {HTMLElement|null} 骨架屏元素
     */
    function createPageSpecificSkeleton(container) {
        const url = window.location.pathname + window.location.search;
        const pageTitle = document.querySelector('title') ? document.querySelector('title').textContent : '';
        
        // 根据URL路径判断页面类型
        let skeleton = null;
        
        if (url.includes('/user/list') || url.includes('/member/list') || url.includes('/lists')) {
            // 列表页面：表格骨架屏
            const table = container.querySelector('table');
            if (table) {
                const rows = table.querySelectorAll('tbody tr').length || 5;
                const cols = table.querySelectorAll('thead th').length || 4;
                skeleton = createTableSkeleton(container, {
                    rows: rows || 5,
                    columns: cols || 4,
                    showHeader: true
                });
            }
        } else if (url.includes('/add') || url.includes('/edit') || url.includes('/insert') || url.includes('/update')) {
            // 表单页面：表单骨架屏
            const form = container.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('input, select, textarea').length;
                skeleton = createFormSkeleton(container, {
                    rows: Math.min(inputs || 6, 10)
                });
            }
        } else if (url.includes('/config') || url.includes('/setting')) {
            // 配置页面：表单骨架屏（多行）
            const form = container.querySelector('form');
            if (form) {
                skeleton = createFormSkeleton(container, {
                    rows: 8
                });
            }
        } else if (container.querySelector('.table-list table')) {
            // 通用表格页面
            const table = container.querySelector('.table-list table');
            if (table) {
                const rows = table.querySelectorAll('tbody tr').length || 5;
                const cols = table.querySelectorAll('thead th').length || 4;
                skeleton = createTableSkeleton(container, {
                    rows: rows || 5,
                    columns: cols || 4,
                    showHeader: true
                });
            }
        }
        
        return skeleton;
    }
    
    // ========== 页面加载完成后自动应用 ==========
    
    function initSkeletonSystem() {
        // 延迟执行，确保页面结构已加载
        setTimeout(function() {
            // 先尝试创建页面特定的骨架屏
            const mainContainer = document.querySelector('.table-list, .lr10, body > div:first-of-type');
            if (mainContainer) {
                const pageSkeleton = createPageSpecificSkeleton(mainContainer);
                if (pageSkeleton) {
                    mainContainer.insertBefore(pageSkeleton, mainContainer.firstChild);
                    // 页面加载完成后移除
                    window.addEventListener('load', function() {
                        if (pageSkeleton && pageSkeleton.parentNode) {
                            pageSkeleton.style.transition = 'opacity 0.3s ease';
                            pageSkeleton.style.opacity = '0';
                            setTimeout(function() {
                                if (pageSkeleton.parentNode) {
                                    pageSkeleton.remove();
                                }
                            }, 300);
                        }
                    });
                }
            }
            // 然后应用通用的自动骨架屏
            autoApplySkeleton();
        }, 200);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSkeletonSystem);
    } else {
        initSkeletonSystem();
    }
    
    // ========== 导出全局API ==========
    
    window.SkeletonSystem = {
        // Skeleton Screen
        createTableSkeleton: createTableSkeleton,
        createFormSkeleton: createFormSkeleton,
        createListSkeleton: createListSkeleton,
        createCardSkeleton: createCardSkeleton,
        
        // Loading Placeholder
        showLoadingPlaceholder: showLoadingPlaceholder,
        hideLoadingPlaceholder: hideLoadingPlaceholder,
        
        // Content Placeholder
        showContentPlaceholder: showContentPlaceholder,
        hideContentPlaceholder: hideContentPlaceholder,
        
        // 自动应用
        autoApply: autoApplySkeleton
    };
    
    // 兼容旧的iframe骨架屏函数
    if (typeof window.showIframeSkeleton === 'undefined') {
        window.showIframeSkeleton = function(container) {
            if (container && !container.querySelector('.iframe-skeleton')) {
                const skeleton = document.createElement('div');
                skeleton.className = 'iframe-skeleton content-placeholder';
                skeleton.textContent = '加载中...';
                container.appendChild(skeleton);
            }
        };
        
        window.hideIframeSkeleton = function(container) {
            if (container) {
                const skeleton = container.querySelector('.iframe-skeleton');
                if (skeleton) {
                    skeleton.remove();
                }
            }
        };
    }
    
})();

