<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>渠道商后台 - 下级数据</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .data-table {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .empty-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .filter-bar {
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            width: 200px;
        }

        .search-box button {
            padding: 8px 20px;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .search-box button:hover {
            background: #5568d3;
        }

        .time-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .time-filter select,
        .time-filter input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .time-filter select {
            min-width: 120px;
        }

        .time-filter input[type="date"] {
            width: 150px;
        }

        .time-filter button {
            padding: 8px 15px;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .time-filter button:hover {
            background: #5568d3;
        }

        .sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 20px;
        }

        .sortable:hover {
            background: #e9ecef;
        }

        .sortable::after {
            content: '⇅';
            position: absolute;
            right: 5px;
            font-size: 12px;
            opacity: 0.5;
        }

        .sortable.asc::after {
            content: '↑';
            opacity: 1;
        }

        .sortable.desc::after {
            content: '↓';
            opacity: 1;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .logout-btn {
                margin-top: 10px;
            }

            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .data-table {
                overflow-x: auto;
            }

            table {
                min-width: 800px;
            }

            th, td {
                padding: 8px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>渠道商后台管理系统</h1>
                <p>欢迎，<?php echo htmlspecialchars($channel_info['username'] ?: $channel_info['mobile']); ?> (UID: <?php echo $channel_uid; ?>)</p>
            </div>
            <a href="<?php echo G_WEB_PATH; ?>/channel_admin/login/logout" class="logout-btn">退出登录</a>
        </div>
    </div>

    <div class="container">
        <div class="stats-cards">
            <div class="stat-card">
                <h3>下级总数</h3>
                <div class="value"><?php echo $total_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>总充值金额</h3>
                <div class="value"><?php echo number_format($total_recharge, 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>总提现金额</h3>
                <div class="value"><?php echo number_format($total_cashout, 2); ?></div>
            </div>
            <div class="stat-card">
                <h3>筛选范围内充值</h3>
                <div class="value"><?php echo number_format($total_recharge_filtered, 2); ?></div>
            </div>
        </div>

        <div class="filter-bar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="输入用户ID或用户名" value="<?php echo htmlspecialchars($filter_params['search']); ?>">
                <button type="button" id="searchBtn">搜索</button>
            </div>
            <div class="time-filter">
                <select id="timeType">
                    <option value="">全部时间</option>
                    <option value="today" <?php echo $filter_params['time_type'] == 'today' ? 'selected' : ''; ?>>今日</option>
                    <option value="week" <?php echo $filter_params['time_type'] == 'week' ? 'selected' : ''; ?>>本周</option>
                    <option value="month" <?php echo $filter_params['time_type'] == 'month' ? 'selected' : ''; ?>>本月</option>
                    <option value="custom" <?php echo $filter_params['time_type'] == 'custom' ? 'selected' : ''; ?>>自定义时间</option>
                </select>
                <div id="customDateRange" style="display: <?php echo $filter_params['time_type'] == 'custom' ? 'flex' : 'none'; ?>; gap: 10px;">
                    <input type="date" id="startTime" value="<?php echo htmlspecialchars($filter_params['start_time']); ?>">
                    <span>至</span>
                    <input type="date" id="endTime" value="<?php echo htmlspecialchars($filter_params['end_time']); ?>">
                </div>
                <button type="button" id="filterBtn">筛选</button>
            </div>
        </div>

        <div class="data-table">
            <?php if (empty($info)): ?>
                <div class="empty-data">
                    <p>暂无下级数据</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>用户名</th>
                            <th>UID</th>
                            <th>余额</th>
                            <th>注册时间</th>
                            <th>最后登录</th>
                            <th class="sortable <?php echo $filter_params['sort'] == 'sub_recharge' ? strtolower($filter_params['order']) : ''; ?>" data-sort="sub_recharge">下级总充值</th>
                            <th class="sortable <?php echo $filter_params['sort'] == 'sub_register' ? strtolower($filter_params['order']) : ''; ?>" data-sort="sub_register">下级总注册</th>
                            <th>总返利</th>
                            <th>充值总额</th>
                            <th>提现总额</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($info as $v): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($v['name']); ?></td>
                            <td><?php echo $v['uid']; ?></td>
                            <td><?php echo number_format($v['money'], 2); ?></td>
                            <td><?php echo date("Y/m/d H:i", $v['time']); ?></td>
                            <td><?php echo $v['login_time'] ? date("Y/m/d H:i", $v['login_time']) : '未登录'; ?></td>
                            <td><?php echo number_format($v['sub_recharge_total'], 2); ?></td>
                            <td><?php echo $v['sub_register_count']; ?></td>
                            <td><?php echo number_format($v['total_money'], 2); ?></td>
                            <td><?php echo number_format($v['rechange_total'], 2); ?></td>
                            <td><?php echo number_format($v['cashout_total'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <script>
        (function() {
            // 获取当前页面的基础URL（不包含参数）
            function getBaseUrl() {
                var fullUrl = window.location.href;
                var routeIndex = fullUrl.indexOf('/channel_admin/index/init');
                if (routeIndex !== -1) {
                    // 返回基础URL（包含路由部分）
                    return fullUrl.substring(0, routeIndex + '/channel_admin/index/init'.length);
                }
                // 如果找不到，使用当前域名 + 路由
                return window.location.origin + '/channel_admin/index/init';
            }

            // 获取当前所有查询参数
            function getCurrentParams() {
                var params = {};
                var fullUrl = window.location.href;
                
                // 查找路由后的参数部分
                var routeIndex = fullUrl.indexOf('/channel_admin/index');
                if (routeIndex !== -1) {
                    var afterRoute = fullUrl.substring(routeIndex + '/channel_admin/index'.length);
                    
                    // 移除开头的 ? 或 &
                    afterRoute = afterRoute.replace(/^[?&]/, '');
                    if (afterRoute) {
                        var pairs = afterRoute.split('&');
                        for (var i = 0; i < pairs.length; i++) {
                            var pair = pairs[i].split('=');
                            if (pair.length === 2) {
                                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
                            }
                        }
                    }
                }
                return params;
            }

            // 构建完整URL
            function buildUrl(params) {
                var baseUrl = getBaseUrl();
                var queryParts = [];
                for (var key in params) {
                    if (params.hasOwnProperty(key) && params[key] !== null && params[key] !== '' && params[key] !== undefined) {
                        queryParts.push(encodeURIComponent(key) + '=' + encodeURIComponent(params[key]));
                    }
                }
                // 使用标准的查询字符串格式：第一个参数用 ?，后续用 &
                // 但路由系统使用 & 连接，所以我们需要检查 baseUrl 是否已经包含 ?
                var hasQuery = baseUrl.indexOf('?') !== -1;
                var queryString = '';
                if (queryParts.length > 0) {
                    if (hasQuery) {
                        queryString = '&' + queryParts.join('&');
                    } else {
                        // 如果路由中没有 ?，我们需要在路由后添加 ? 或 &
                        // 根据路由系统，应该使用 & 连接
                        queryString = '&' + queryParts.join('&');
                    }
                }
                return baseUrl + queryString;
            }

            function toggleCustomDate() {
                var timeType = document.getElementById('timeType').value;
                var customDateRange = document.getElementById('customDateRange');
                customDateRange.style.display = timeType === 'custom' ? 'flex' : 'none';
            }

            function doSearch(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                var search = document.getElementById('searchInput').value.trim();
                var params = getCurrentParams();
                
                // 更新搜索参数
                if (search) {
                    params['search'] = search;
                } else {
                    delete params['search'];
                }
                
                // 保留时间筛选参数
                var timeType = document.getElementById('timeType').value;
                if (timeType) {
                    params['time_type'] = timeType;
                } else {
                    delete params['time_type'];
                }
                if (timeType === 'custom') {
                    var startTime = document.getElementById('startTime').value;
                    var endTime = document.getElementById('endTime').value;
                    if (startTime) {
                        params['start_time'] = startTime;
                    } else {
                        delete params['start_time'];
                    }
                    if (endTime) {
                        params['end_time'] = endTime;
                    } else {
                        delete params['end_time'];
                    }
                } else {
                    delete params['start_time'];
                    delete params['end_time'];
                }
                
                // 保留排序参数（如果存在）
                // sort 和 order 会自动保留在 getCurrentParams() 中
                
                window.location.href = buildUrl(params);
                return false;
            }

            function doFilter(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                var params = getCurrentParams();
                var timeType = document.getElementById('timeType').value;
                
                // 更新时间筛选参数
                if (timeType) {
                    params['time_type'] = timeType;
                } else {
                    delete params['time_type'];
                }
                
                if (timeType === 'custom') {
                    var startTime = document.getElementById('startTime').value;
                    var endTime = document.getElementById('endTime').value;
                    if (startTime) {
                        params['start_time'] = startTime;
                    } else {
                        delete params['start_time'];
                    }
                    if (endTime) {
                        params['end_time'] = endTime;
                    } else {
                        delete params['end_time'];
                    }
                } else {
                    delete params['start_time'];
                    delete params['end_time'];
                }
                
                // 保留搜索参数
                var search = document.getElementById('searchInput').value.trim();
                if (search) {
                    params['search'] = search;
                } else {
                    delete params['search'];
                }
                
                // 保留排序参数（如果存在）
                // sort 和 order 会自动保留在 getCurrentParams() 中
                
                window.location.href = buildUrl(params);
                return false;
            }

            function sortTable(field, e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                var params = getCurrentParams();
                var currentSort = params['sort'];
                var currentOrder = params['order'] || 'desc';
                
                if (currentSort === field) {
                    // 切换排序方向
                    params['order'] = currentOrder === 'desc' ? 'asc' : 'desc';
                } else {
                    // 设置新的排序字段，默认降序
                    params['sort'] = field;
                    params['order'] = 'desc';
                }
                
                // 保留时间筛选参数
                var timeType = document.getElementById('timeType').value;
                if (timeType) {
                    params['time_type'] = timeType;
                } else {
                    delete params['time_type'];
                }
                if (timeType === 'custom') {
                    var startTime = document.getElementById('startTime').value;
                    var endTime = document.getElementById('endTime').value;
                    if (startTime) {
                        params['start_time'] = startTime;
                    } else {
                        delete params['start_time'];
                    }
                    if (endTime) {
                        params['end_time'] = endTime;
                    } else {
                        delete params['end_time'];
                    }
                } else {
                    delete params['start_time'];
                    delete params['end_time'];
                }
                
                // 保留搜索参数
                var search = document.getElementById('searchInput').value.trim();
                if (search) {
                    params['search'] = search;
                } else {
                    delete params['search'];
                }
                
                window.location.href = buildUrl(params);
                return false;
            }

            // 页面加载完成后绑定事件
            document.addEventListener('DOMContentLoaded', function() {
                // 搜索按钮
                var searchBtn = document.getElementById('searchBtn');
                if (searchBtn) {
                    searchBtn.addEventListener('click', doSearch, true);
                }
                
                // 筛选按钮
                var filterBtn = document.getElementById('filterBtn');
                if (filterBtn) {
                    filterBtn.addEventListener('click', doFilter, true);
                }
                
                // 时间选择器变化
                var timeType = document.getElementById('timeType');
                if (timeType) {
                    timeType.addEventListener('change', toggleCustomDate);
                }
                
                // 排序表头
                var sortableHeaders = document.querySelectorAll('.sortable[data-sort]');
                sortableHeaders.forEach(function(header) {
                    header.addEventListener('click', function(e) {
                        var field = this.getAttribute('data-sort');
                        sortTable(field, e);
                    }, true);
                });
                
                // 支持回车搜索
                var searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            doSearch(e);
                        }
                    });
                }
            });
        })();
    </script>
</body>
</html>

