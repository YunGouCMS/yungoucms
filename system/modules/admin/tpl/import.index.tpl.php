<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    
    <style>
        /* 页面整体美化 */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            margin: 0;
            font-family: 'Microsoft YaHei', sans-serif;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* 卡片式布局 */
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 30px;
            backdrop-filter: blur(10px);
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* 表单美化 */
        .layui-form {
            margin-bottom: 30px;
        }
        
        .layui-form-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border: none;
            border-radius: 10px 0 0 10px;
            font-weight: 500;
            width: 100px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .layui-input-block {
            margin-left: 130px;
        }
        
        .layui-input {
            border-radius: 0 10px 10px 0;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            height: 42px;
            line-height: 42px;
        }
        
        .layui-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        /* 按钮美化 */
        .layui-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .layui-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        .layui-btn:active {
            transform: translateY(0);
        }
        
        /* 语言列表网格布局 */
        .import {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* 语言卡片样式 */
        .lan-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            animation: cardAppear 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* 为每个卡片设置不同的动画延迟 */
        .lan-card:nth-child(1) { animation-delay: 0.1s; }
        .lan-card:nth-child(2) { animation-delay: 0.2s; }
        .lan-card:nth-child(3) { animation-delay: 0.3s; }
        .lan-card:nth-child(4) { animation-delay: 0.4s; }
        .lan-card:nth-child(5) { animation-delay: 0.5s; }
        .lan-card:nth-child(6) { animation-delay: 0.6s; }
        .lan-card:nth-child(7) { animation-delay: 0.7s; }
        .lan-card:nth-child(8) { animation-delay: 0.8s; }
        .lan-card:nth-child(9) { animation-delay: 0.9s; }
        .lan-card:nth-child(10) { animation-delay: 1s; }
        
        .lan-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }
        
        .lan-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .lan-flag {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .lan-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        
        .lan-code {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }
        
        .lan-action {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .lan-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            opacity: 0.8;
        }
        
        .lan-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        
        .lan-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        .lan-btn:active {
            transform: translateY(0);
        }
        
        /* 隐藏域样式 */
        input[type="hidden"] {
            display: none;
        }
        
        /* 标题美化 */
        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .page-title h2 {
            color: white;
            font-size: 28px;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 2px;
        }
        
        /* .page-title h2:before,
        .page-title h2:after {
            content: "✦";
            margin: 0 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 24px;
        } */
        
        /* 加载动画 */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* 空状态样式 */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
            grid-column: 1 / -1;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        /* 响应式调整 */
        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
            
            .import {
                grid-template-columns: 1fr;
            }
            
            .page-title h2 {
                font-size: 20px;
            }
            
            .page-title h2:before,
            .page-title h2:after {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="page-title">
            <h2><?php echo $title_text;?>数据导入</h2>
        </div>
        
        <div class="card">
            <div class="layui-form layui-form-pane" method="post">
                <div class="layui-form-item" style="display: none;">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <input type="hidden" name="type" id="type" placeholder="请输入type" autocomplete="off" class="layui-input" value="<?php echo $type;?>">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">API Key</label>
                    <div class="layui-input-block">
                        <input type="text" name="apikey" id="apikey" placeholder="请输入您的API Key" autocomplete="off" class="layui-input" value="<?php echo $apikey;?>">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <div class="layui-input-block" style="text-align: center;">
                        <button type="button" class="layui-btn submit" lay-submit="" lay-filter="demo1">
                            <i class="layui-icon layui-icon-search" style="font-size: 16px; margin-right: 5px;"></i>
                            查询可导入列表
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="import">
                <!-- 语言卡片将动态加载到这里 -->
            </div>
        </div>
    </div>

    <script>
        // 显示加载状态
        function showLoading(button) {
            var originalText = $(button).html();
            $(button).html('<span class="loading-spinner"></span> 加载中...').prop('disabled', true);
            return originalText;
        }
        
        // 恢复按钮状态
        function hideLoading(button, originalText) {
            $(button).html(originalText).prop('disabled', false);
        }
        
        // 获取语言首字母作为国旗标识
        function getLanguageFlag(name) {
            if (!name) return '🌐';
            return name.charAt(0).toUpperCase();
        }
        
        // 格式化语言代码显示
        function formatLanguageCode(code) {
            if (!code) return '未知';
            return code.toUpperCase();
        }
        
        $(".submit").click(function() {
            var apikey = $("#apikey").val();
            var type = $("#type").val();
            
            if (!apikey) {
                layer.msg('请输入API Key', {icon: 2, time: 2000});
                return;
            }
            
            var originalText = showLoading(this);
            
            $.post("<?php echo G_MODULE_PATH;?>/import/getImortLan", {"apikey": apikey, "type": type}, function(data) {
                hideLoading($(".submit")[0], originalText);
                
                data = JSON.parse(data);
                var list = data.data.list;
                var html = '';
                
                if (list && list.length > 0) {
                    for (var i = 0; i < list.length; i++) {
                        var language = list[i];
                        var flag = getLanguageFlag(language.name);
                        var code = formatLanguageCode(language.code);
                        
                        html += '<div class="lan-card">';
                        html += '    <div class="lan-info">';
                        html += '        <div class="lan-flag">' + flag + '</div>';
                        html += '        <div class="lan-details">';
                        html += '            <div class="lan-name">' + (language.name || '未知语言') + '</div>';
                        html += '            <div class="lan-code">' + code + '</div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '    <div class="lan-action">';
                        html += '        <span class="lan-badge">语言</span>';
                        html += '        <input type="hidden" class="country-code" value="' + (language.code || '') + '">';
                        html += '        <button type="button" class="lan-btn">';
                        html += '            <i class="layui-icon layui-icon-download-circle" style="font-size: 14px; margin-right: 3px;"></i>';
                        html += '            导入';
                        html += '        </button>';
                        html += '    </div>';
                        html += '</div>';
                    }
                } else {
                    html = '<div class="empty-state">';
                    html += '    <i class="layui-icon layui-icon-face-cry"></i>';
                    html += '    <p>暂无语言数据</p>';
                    html += '</div>';
                }
                
                $(".import").html(html);
                
                // 添加成功提示
                if (list && list.length > 0) {
                    layer.msg('已加载 ' + list.length + ' 种语言', {icon: 1, time: 2000});
                }
            }).fail(function() {
                hideLoading($(".submit")[0], originalText);
                layer.msg('请求失败，请重试', {icon: 2, time: 2000});
            });
        });

        $(".import").on("click", ".lan-btn", function() {
            var apikey = $("#apikey").val();
            var type = $("#type").val();
            var country_code = $(this).closest('.lan-card').find('.country-code').val();
            var languageName = $(this).closest('.lan-card').find('.lan-name').text();
            var $btn = $(this);
            
            if (!apikey) {
                layer.msg('请输入API Key', {icon: 2, time: 2000});
                return;
            }
            
            // 分页批量导入，避免一次请求超时
            var originalText = $btn.html();
            var pageSize = 200;
            var savedTotal = 0;
            var fetchedTotal = 0;
            var total = 0;
            var inProgress = true;

            function updateBtnText() {
                var t = total > 0 ? (fetchedTotal + '/' + total) : (fetchedTotal + '+');
                $btn.html('<span class="loading-spinner"></span> 导入中 ' + t).prop('disabled', true);
            }

            function finishSuccess() {
                inProgress = false;
                $btn.html(originalText).prop('disabled', false);
                layer.msg('✓ ' + languageName + ' 导入成功（保存 ' + savedTotal + ' 条）', {
                    icon: 1,
                    time: 2200,
                    shade: 0.3
                });
                var $card = $btn.closest('.lan-card');
                $card.css({
                    'background': 'linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%)',
                    'border-color': '#52c41a'
                });
                setTimeout(function() {
                    $card.css({'background': '', 'border-color': ''});
                }, 2000);
            }

            function finishFail(msg) {
                inProgress = false;
                $btn.html(originalText).prop('disabled', false);
                layer.msg('✗ ' + languageName + ' 导入失败：' + (msg || '未知错误'), {
                    icon: 2,
                    time: 3500
                });
            }

            function importPage(page) {
                if (!inProgress) return;
                updateBtnText();
                $.post("<?php echo G_MODULE_PATH;?>/import/getImportData", {
                    "apikey": apikey,
                    "type": type,
                    "country_code": country_code,
                    "page": page,
                    "page_size": pageSize,
                    "clear": page === 1 ? 1 : 0
                }, function(resp) {
                    var data = JSON.parse(resp);
                    if (data.code != 200) {
                        finishFail(data.msg);
                        return;
                    }
                    var d = data.data || {};
                    total = parseInt(d.total || 0, 10);
                    fetchedTotal = parseInt(d.fetched_so_far || 0, 10);
                    savedTotal += parseInt(d.saved || 0, 10);

                    if (parseInt(d.has_more || 0, 10) === 1) {
                        // 轻微延迟，降低服务端压力
                        setTimeout(function() {
                            importPage(parseInt(d.next_page || (page + 1), 10));
                        }, 60);
                    } else {
                        finishSuccess();
                    }
                }).fail(function() {
                    finishFail('网络错误，请重试');
                });
            }

            importPage(1);
        });
        
        // 回车键提交
        $("#apikey").keypress(function(e) {
            if (e.which == 13) {
                $(".submit").click();
                return false;
            }
        });
        
        // 初始化动画
        $(document).ready(function() {
            $(".card").css('opacity', '0').animate({opacity: 1}, 500);
        });
    </script>
</body>
</html>