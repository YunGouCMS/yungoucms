<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title><?php echo _cfg('web_name') ?: '管理后台'; ?></title>
    <link rel="Shortcut Icon" type="image/x-icon" href="/favicon.ico" />
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/login.css" type="text/css">
    <!-- 后台体验优化样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin-enhancements.css" type="text/css">
    <!-- 按钮和表单优化样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/form-button-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/jquery190.js" language="javascript" type="text/javascript"></script>
    <!--    <script src="--><?php //echo G_TEMPLATES_JS;
                            ?><!--/layui/layui.js" language="javascript" type="text/javascript"></script>-->
    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/base.js" language="javascript" type="text/javascript"></script>
    <script src="<?php echo G_TEMPLATES_JS; ?>/base.js" language="javascript" type="text/javascript"></script>
    <!-- <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/login.js" language="javascript" type="text/javascript"></script> -->
    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/jsencrypt.min.js" language="javascript" type="text/javascript"></script>
    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/gt.js" language="javascript" type="text/javascript"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 通知消息系统脚本（必须在layer之后加载） -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
    <style>
        <?php echo $bg; ?><?php echo $bg_f63; ?>
    </style>

    <style>
        /* 登录页面现代化样式 */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            padding-top: calc(50vh - 300px - 200px) !important;
        }

        .login_box {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(20px);
            border-radius: 24px !important;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.5) !important;
            padding: 0 !important;
            width: 100%;
            max-width: 520px;
            animation: fadeInUp 0.6s ease-out;
            height: auto !important;
            overflow: hidden;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login {
            width: 100%;
        }

        .login .lh50 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            padding: 32px 24px !important;
            text-align: center !important;
            font-size: 26px !important;
            font-weight: 700;
            color: #fff !important;
            margin: 0 !important;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.25);
            letter-spacing: 0.5px;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .login .lh50 * {
            text-align: center !important;
            width: 100% !important;
        }

        .login_ibox {
            padding: 10px 28px 10px 28px !important;
        }

        .input_box {
            width: 422px !important;
            max-width: 100% !important;
            margin-bottom: 10px !important;
            margin-top: 10px !important;
            position: relative;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .input_box:first-child {
            margin-top: 0 !important;
        }

        /* 密码输入框和验证码输入框之间的间距 - 使用更高优先级 */
        .login_box .login_ibox #password-box,
        #form #password-box,
        .input_box#password-box {
            margin-bottom: 10px !important;
            margin-top: 10px !important;
        }

        /* 验证码输入框和登录按钮之间的间距 - 使用更高优先级 */
        .login_box .login_ibox .input_box.wid260,
        .login_box .login_ibox .input_box.wid320,
        #form .input_box.wid260,
        #form .input_box.wid320,
        .input_box.wid260,
        .input_box.wid320 {
            margin-bottom: 25px !important;
            margin-top: 10px !important;
        }

        /* 登录按钮上边距 */
        .login_box .login_ibox .login_btn,
        .login_box .login_ibox .button,
        #form .login_btn,
        #form .button,
        .login_btn#form_but {
            margin-top: 25px !important;
        }

        .input_box:focus-within {
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input_caption {
            display: none !important;
        }

        .input_caption_img {
            display: none !important;
        }

        .input_value {
            position: relative;
            width: 100%;
        }

        .input_input {
            width: 100% !important;
            padding: 16px 50px 16px 16px !important;
            border: 2px solid transparent !important;
            border-radius: 12px !important;
            font-size: 15px !important;
            transition: all 0.3s ease;
            background: transparent !important;
            box-sizing: border-box;
            height: auto !important;
            color: #333 !important;
            line-height: 1.5;
        }

        #password-box .input_input {
            padding-right: 60px !important;
        }

        .input_input:focus {
            border-color: transparent !important;
            box-shadow: none !important;
            outline: none;
            background: transparent !important;
        }

        .input_input::placeholder {
            color: #adb5bd;
            font-size: 15px;
        }

        /* 密码显示/隐藏按钮 */
        #toggleButton {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 3;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            background: transparent;
        }

        #toggleButton:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        #toggleButton svg.password-icon {
            width: 20px;
            height: 20px;
            opacity: 0.6;
            transition: opacity 0.2s ease;
            color: #666;
        }

        #toggleButton:hover svg.password-icon {
            opacity: 0.9;
            color: #667eea;
        }

        .eye-closed {
            display: none;
        }

        .eye-open {
            display: block;
        }

        #toggleButton.show-password .eye-closed {
            display: block;
        }

        #toggleButton.show-password .eye-open {
            display: none;
        }

        /* 登录按钮 - 使用更高优先级的选择器覆盖全局样式 */
        .login_box .login_ibox .login_btn,
        .login_box .login_ibox .button,
        .login_box .login_ibox .layui-btn,
        #form .login_btn,
        #form .button,
        #form .layui-btn,
        .login_btn#form_but,
        .button#form_but,
        .layui-btn#form_but {
            width: 376px !important;
            max-width: 376px !important;
            min-width: 376px !important;
            padding: 16px 24px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 12px !important;
            font-size: 18px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.35) !important;
            margin: 25px 0 10px 0 !important;
            height: auto !important;
            line-height: normal !important;
            letter-spacing: 0.5px !important;
            position: relative !important;
            overflow: hidden !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            float: none !important;
        }

        .login_box .login_ibox .login_btn *,
        .login_box .login_ibox .button *,
        .login_box .login_ibox .layui-btn *,
        #form .login_btn *,
        #form .button *,
        #form .layui-btn *,
        .login_btn#form_but *,
        .button#form_but *,
        .layui-btn#form_but * {
            color: #ffffff !important;
            text-align: center !important;
        }

        .login_box .login_ibox .login_btn .btn-text,
        .login_box .login_ibox .button .btn-text,
        .login_box .login_ibox .layui-btn .btn-text,
        #form .login_btn .btn-text,
        #form .button .btn-text,
        #form .layui-btn .btn-text,
        .login_btn#form_but .btn-text,
        .button#form_but .btn-text,
        .layui-btn#form_but .btn-text {
            text-align: center !important;
            width: 100% !important;
            display: block !important;
            font-size: 18px !important;
        }

        .login_btn::before, .button::before, .layui-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .login_btn:hover::before, .button:hover::before, .layui-btn:hover::before {
            left: 100%;
        }

        .login_btn:hover, .button:hover, .layui-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.45);
        }

        .login_btn:active, .button:active, .layui-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.35);
        }

        /* 验证码输入框 */
        .input_box.wid260, .input_box.wid320 {
            width: 422px !important;
            max-width: 100% !important;
            height: 58px !important;
            min-height: 58px !important;
            max-height: 58px !important;
            margin-bottom: 25px !important;
            margin-top: 10px !important;
            display: flex;
            align-items: center;
            gap: 0 !important;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 5px 4px 5px 4px !important;
            justify-content: flex-start;
            position: relative;
            box-sizing: border-box;
        }

        .input_box.wid260:focus-within, .input_box.wid320:focus-within {
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input_box.wid260 .input_value:first-child,
        .input_box.wid320 .input_value:first-child {
            flex: 1;
            position: relative;
            z-index: 1 !important;
        }

        .input_box.wid260 .input_input, .input_box.wid320 .input_input {
            width: 100% !important;
            margin: 0 !important;
            padding: 12px 16px 12px 16px !important;
            border: none !important;
            background: transparent !important;
            box-sizing: border-box !important;
            height: auto !important;
            line-height: normal !important;
            position: relative !important;
            z-index: 10 !important;
        }
        
        /* 验证码输入框外部图片样式 */
        #checkcode {
            width: 120px !important;
            height: 48px !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            border: 2px solid #e1e8ed !important;
            flex-shrink: 0 !important;
        }
        
        #checkcode:hover {
            border-color: #667eea !important;
            transform: scale(1.02) !important;
        }

        .input_box.wid260 .input_value:last-child,
        .input_box.wid320 .input_value:last-child {
            flex-shrink: 0;
            margin-left: auto !important;
            margin-right: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            order: 999;
            position: absolute !important;
            right: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 5 !important;
            pointer-events: none !important;
        }
        
        .input_box.wid260 .input_value:last-child img,
        .input_box.wid320 .input_value:last-child img,
        .input_box.wid260 #checkcode,
        .input_box.wid320 #checkcode {
            pointer-events: auto !important;
        }

        .input_box.wid260 img, .input_box.wid320 img {
            width: 120px;
            height: 48px;
            max-height: 48px !important;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid #e1e8ed;
            transition: all 0.3s ease;
            flex-shrink: 0;
            object-fit: cover;
            position: relative;
            right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .input_box.wid260 img:hover, .input_box.wid320 img:hover {
            border-color: #667eea;
            transform: scale(1.02);
        }

        #checkcode {
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            display: block !important;
            position: relative !important;
            right: 0 !important;
        }

        /* 获取验证码按钮 */
        .getAdminCode {
            padding: 12px 20px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .getAdminCode:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
        }

        .getAdminCode:active {
            transform: translateY(0);
        }

        #content .userinfo {
            background-image: linear-gradient(-180deg, #ffb366 0, #f26549 100%);
            height: 1.67rem;
        }

        .userinfo {
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
        }

        .userinfo-avatar {
            width: .62rem;
            height: .62rem;
            margin: .28rem .1rem .1rem;
            position: relative;
            border-radius: 100%;
            overflow: hidden;
        }

        .userinfo-nickname {
            color: #fff;
            font-size: .15rem;
        }

        .userinfo-service {
            color: #fff;
            width: 100%;
            margin-top: .05rem;
            text-align: center;
            font-size: .12rem;
        }

        .registerCon {
            text-align: left;
        }

        .inp {
            border: 1px solid gray;
            padding: 0 10px;
            width: 200px;
            height: 30px;
            font-size: 18px;
        }

        .btn {
            border: 1px solid gray;
            width: 100px;
            height: 30px;
            font-size: 18px;
            cursor: pointer;
        }

        #embed-captcha {
            width: 100%;
            margin: 10px auto;
        }

        .geetest_holder.geetest_wind {
            width: 100% !important
        }

        .show {
            display: block;
        }

        .hide {
            display: none;
        }

        #notice {
            color: red;
        }

        .code>input {
            display: inline-block;
            height: 50px;
            width: 70%;
            font-size: 0.7rem;
            border-radius: 10px;
        }

        .getAdminCode {
            width: 95px;
            height: 50px;
            background: #f60;
            border: none;
            color: #fff;
            right: 0;
            cursor: pointer;
            border-radius: 5px;
            top: 4%;
            text-align: center;
        }

        .layui-layer-msg {
            color: aliceblue !important;
        }
        .wid230{
            width: 230px;
        }
        .geetest_holder.geetest_wind,.geetest_radar_tip {
            height: 52px!important;
        }
        .geetest_radar_tip {
            padding-top: 5px!important;
        }
        .geetest_holder.geetest_wind .geetest_logo {
            top: 15px!important;
        }
        .geetest_holder.geetest_wind .geetest_radar {
            margin: 10px!important;
        }
        
        /* ========== 用户体验优化样式 ========== */
        
        /* 输入框验证状态 */
        .input_box.valid .input_input {
            border-color: #10b981 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2310b981' d='M13.78 4.22a.75.75 0 010 1.06l-7.25 7.25a.75.75 0 01-1.06 0L2.22 9.28a.75.75 0 011.06-1.06L6 10.94l6.72-6.72a.75.75 0 011.06 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }
        
        .input_box.invalid .input_input {
            border-color: #ef4444 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23ef4444' d='M8 15A7 7 0 118 1a7 7 0 010 14zm0 1A8 8 0 108 0a8 8 0 000 16zM4.646 4.646a.5.5 0 01.708 0L8 7.293l2.646-2.647a.5.5 0 01.708.708L8.707 8l2.647 2.646a.5.5 0 01-.708.708L8 8.707l-2.646 2.647a.5.5 0 01-.708-.708L7.293 8 4.646 5.354a.5.5 0 010-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }
        
        /* 错误提示文本 */
        .error-message {
            display: none;
            color: #ef4444;
            font-size: 13px;
            margin-top: 8px;
            padding-left: 52px;
            animation: slideDown 0.3s ease-out;
            line-height: 1.4;
        }
        
        .input_box.invalid .error-message {
            display: block;
        }

        .input_box.invalid {
            background: #fef2f2 !important;
        }

        .input_box.invalid .input_input {
            color: #ef4444 !important;
        }

        .input_box.valid {
            background: #f0fdf4 !important;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* 登录按钮加载状态 */
        .login_btn.loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }
        
        .login_btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* 输入框聚焦动画 */
        .input_box {
            position: relative;
        }
        
        .input_box .input_input:focus {
            transform: scale(1.02);
        }
        
        /* 验证码刷新动画 */
        #checkcode {
            transition: transform 0.3s ease;
        }
        
        #checkcode:hover {
            transform: scale(1.05);
        }
        
        #checkcode.refreshing {
            animation: rotate 0.5s ease-in-out;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* 表单容器动画 */
        .login_box {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* 输入框占位符动画 */
        .input_input::placeholder {
            transition: opacity 0.3s ease;
        }
        
        .input_input:focus::placeholder {
            opacity: 0.5;
        }
        
        /* 禁用状态 */
        .login_btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        /* 成功状态提示 */
        .success-indicator {
            display: none;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #10b981;
            font-size: 16px;
            z-index: 3;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            font-weight: bold;
        }
        
        .input_box.valid .success-indicator {
            display: flex;
        }
        /* 多语言切换按钮样式 */
        .language-switcher {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 1000 !important;
        }
        .language-select-wrapper {
            position: relative;
            display: inline-block;
        }
        .current-flag {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .language-select {
            padding: 8px 35px 8px 40px !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 8px !important;
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(10px) !important;
            color: #fff !important;
            font-size: 14px !important;
            cursor: pointer !important;
            outline: none !important;
            transition: all 0.3s ease !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23fff' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 35px !important;
        }
        .language-select:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
        }
        .language-select option {
            background: #667eea !important;
            color: #fff !important;
            padding: 10px !important;
        }
        .flag-icon {
            width: 20px !important;
            height: 15px !important;
            vertical-align: middle !important;
            display: inline-block !important;
            flex-shrink: 0 !important;
        }
        .flag-icon svg {
            width: 100% !important;
            height: 100% !important;
            display: block !important;
            margin-right: 0 !important;
            border-radius: 2px !important;
            flex-shrink: 0 !important;
        }
        .flag-icon img {
            width: 100% !important;
            height: 100% !important;
            display: block !important;
            object-fit: cover !important;
            border-radius: 2px !important;
        }
        /* 自定义下拉框样式 */
        .custom-language-select {
            position: relative;
            min-width: 120px;
        }
        .custom-select-selected {
            padding: 8px 35px 8px 12px !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 8px !important;
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(10px) !important;
            color: #fff !important;
            font-size: 14px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }
        .custom-select-selected:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
        }
        .custom-select-arrow {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 10px !important;
            transition: transform 0.3s ease !important;
            pointer-events: none !important;
            color: #fff !important;
        }
        .custom-language-select.active .custom-select-arrow {
            transform: translateY(-50%) rotate(180deg) !important;
        }
        .custom-select-options {
            position: absolute !important;
            top: calc(100% + 5px) !important;
            left: 0 !important;
            right: 0 !important;
            background: rgba(102, 126, 234, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            max-height: 200px !important;
            overflow-y: auto !important;
            display: none !important;
            z-index: 1001 !important;
        }
        .custom-language-select.active .custom-select-options {
            display: block !important;
        }
        .custom-select-option {
            padding: 10px 12px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            color: #fff !important;
            font-size: 14px !important;
            transition: background 0.2s ease !important;
        }
        .custom-select-option:hover {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        .custom-select-option.selected {
            background: rgba(255, 255, 255, 0.3) !important;
            color: #fff !important;
            font-weight: 600 !important;
        }
        .lang-name {
            white-space: nowrap !important;
            color: #fff !important;
        }
        .language-select-hidden {
            display: none !important;
        }
    </style>
</head>

<body>

    <!-- 多语言切换按钮 -->
    <div class="language-switcher" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <div class="language-select-wrapper">
            <!-- 隐藏的原始select -->
            <select class="language-select-hidden" id="languageSelect" style="display: none;">
                <?php 
                if(isset($languageBackend) && is_array($languageBackend)){
                    foreach($languageBackend as $vl){
                        $selected = '';
                        $currentLang = $this->language_backend ?? 'Chinese';
                        if(isset($currentLang) && $vl['lg_value'] == $currentLang){
                            $selected = "selected";
                        }
                        echo "<option value='{$vl['lg_value']}' data-flag='".htmlspecialchars($vl['national_flag'] ?? '', ENT_QUOTES)."' $selected>{$vl['lg_name']}</option>";
                    }
                }
                ?>
            </select>
            <!-- 自定义下拉框 -->
            <div class="custom-language-select" id="customLanguageSelect">
                <?php 
                // 显示当前选中语言的国旗和名称
                $currentFlag = '';
                $currentLangName = '';
                $currentLang = $this->language_backend ?? 'Chinese';
                if(isset($languageBackend) && is_array($languageBackend) && isset($currentLang)){
                    foreach($languageBackend as $vl){
                        if($vl['lg_value'] == $currentLang){
                            $currentFlag = $vl['national_flag'] ?? '';
                            $currentLangName = $vl['lg_name'] ?? '';
                            // 删除语言名称后的"（国家）"部分 - 使用更严格的正则表达式
                            // 处理各种括号格式：英文括号、中文括号、全角括号等
                            // 使用循环多次处理，确保所有括号都被删除
                            $patterns = array(
                                '/\s*\([^)]*\)\s*$/u',      // 英文括号
                                '/\s*（[^）]*）\s*$/u',        // 中文括号
                                '/\s*【[^】]*】\s*$/u',        // 中文方括号
                                '/\s*\[[^\]]*\]\s*$/u',       // 英文方括号
                                '/\s*\([^)]+\)\s*$/u',       // 英文括号（更宽松）
                                '/\s*（[^）]+）\s*$/u',       // 中文括号（更宽松）
                            );
                            // 循环处理，直到没有更多括号
                            $lastLangName = '';
                            while ($currentLangName !== $lastLangName) {
                                $lastLangName = $currentLangName;
                                foreach ($patterns as $pattern) {
                                    $currentLangName = preg_replace($pattern, '', $currentLangName);
                                }
                            }
                            $currentLangName = trim($currentLangName); // 去除首尾空格
                            break;
                        }
                    }
                }
                ?>
                <div class="custom-select-selected">
                    <?php 
                    // 生成国旗SVG的函数
                    function getFlagSvgInline($lang) {
                        $flagSvgs = array(
                            'Chinese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="20" height="15"><rect width="640" height="480" fill="#de2910"/><path d="M213.3 0h213.4v480H213.3z" fill="#ffde00"/><circle cx="320" cy="240" r="80" fill="#de2910"/><circle cx="320" cy="240" r="60" fill="#ffde00"/><path d="M320 180l20 60-50-40h60l-50 40z" fill="#de2910"/></svg>',
                            'English' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7410 3900" width="20" height="15"><rect width="7410" height="3900" fill="#b22234"/><path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/><rect width="2964" height="2100" fill="#3c3b6e"/></svg>',
                            'Vietnamese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="600" fill="#da251d"/><path d="M450 200l50 150-130-95h160l-130 95z" fill="#ff0"/></svg>',
                            'Myanmar' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="200" fill="#ffcd00"/><rect y="200" width="900" height="200" fill="#34b233"/><rect y="400" width="900" height="200" fill="#ea2839"/><circle cx="450" cy="300" r="100" fill="#fff"/></svg>',
                            'Hindi' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="200" fill="#ff9933"/><rect y="200" width="900" height="200" fill="#fff"/><rect y="400" width="900" height="200" fill="#138808"/><circle cx="450" cy="300" r="60" fill="#000080"/><circle cx="450" cy="300" r="50" fill="#fff"/></svg>',
                            'Portugal' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>',
                            'Portuguese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>',
                        );
                        return isset($flagSvgs[$lang]) ? $flagSvgs[$lang] : $flagSvgs['Chinese'];
                    }
                    
                    // 判断是否使用SVG还是图片URL
                    $useSvg = false;
                    $flagSvgContent = '';
                    if(empty($currentFlag) || (strpos($currentFlag, 'data:image/svg+xml') === 0)){
                        // 使用内联SVG
                        $useSvg = true;
                        $flagSvgContent = getFlagSvgInline($currentLang);
                    }
                    ?>
                    <?php if($useSvg): ?>
                        <span class="flag-icon"><?php echo $flagSvgContent; ?></span>
                    <?php elseif(!empty($currentFlag)): ?>
                        <img src="<?php echo htmlspecialchars($currentFlag); ?>" class="flag-icon" alt="flag" />
                    <?php endif; ?>
                    <span class="lang-name"><?php echo htmlspecialchars($currentLangName); ?></span>
                    <span class="custom-select-arrow">▼</span>
                </div>
                <div class="custom-select-options" id="customSelectOptions">
                    <?php 
                    if(isset($languageBackend) && is_array($languageBackend)){
                        foreach($languageBackend as $vl){
                            $isSelected = (isset($currentLang) && $vl['lg_value'] == $currentLang) ? 'selected' : '';
                            // 使用后端已经处理好的国旗URL（可能是图片路径或SVG data URI）
                            $flagUrl = isset($vl['national_flag']) ? $vl['national_flag'] : '';
                            // 判断是否使用内联SVG
                            $useSvg = false;
                            $flagSvgContent = '';
                            if(empty($flagUrl) || (strpos($flagUrl, 'data:image/svg+xml') === 0)){
                                // 使用内联SVG
                                $useSvg = true;
                                $flagSvgContent = getFlagSvgInline($vl['lg_value'] ?? 'Chinese');
                                $flagUrl = ''; // 清空URL，使用SVG
                            }
                            
                            if($useSvg){
                                $flagDisplay = '<span class="flag-icon">'.$flagSvgContent.'</span>';
                            }elseif(!empty($flagUrl)){
                                // 是普通URL，添加错误处理，如果加载失败则使用SVG备用方案
                                $fallbackSvg = getFlagSvgInline($vl['lg_value'] ?? 'Chinese');
                                $flagDisplay = '<img src="'.htmlspecialchars($flagUrl).'" class="flag-icon" alt="flag" onerror="this.outerHTML=\'<span class=\\\'flag-icon\\\'>'.$fallbackSvg.'</span>\';" />';
                            }else{
                                $flagDisplay = '<span class="flag-icon">'.getFlagSvgInline($vl['lg_value'] ?? 'Chinese').'</span>';
                            }
                            // 删除语言名称后的"（国家）"部分 - 使用更严格的正则表达式
                            $langName = $vl['lg_name'];
                            // 处理各种括号格式：英文括号、中文括号、全角括号等
                            // 使用循环多次处理，确保所有括号都被删除
                            $patterns = array(
                                '/\s*\([^)]*\)\s*$/u',      // 英文括号
                                '/\s*（[^）]*）\s*$/u',        // 中文括号
                                '/\s*【[^】]*】\s*$/u',        // 中文方括号
                                '/\s*\[[^\]]*\]\s*$/u',       // 英文方括号
                                '/\s*\([^)]+\)\s*$/u',       // 英文括号（更宽松）
                                '/\s*（[^）]+）\s*$/u',       // 中文括号（更宽松）
                            );
                            // 循环处理，直到没有更多括号
                            $lastLangName = '';
                            while ($langName !== $lastLangName) {
                                $lastLangName = $langName;
                                foreach ($patterns as $pattern) {
                                    $langName = preg_replace($pattern, '', $langName);
                                }
                            }
                            $langName = trim($langName); // 去除首尾空格
                            echo '<div class="custom-select-option '.$isSelected.'" data-value="'.$vl['lg_value'].'" data-flag="'.htmlspecialchars($flagUrl, ENT_QUOTES).'">';
                            echo $flagDisplay;
                            echo '<span class="lang-name">'.$langName.'</span>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="login_box">

        <div class="login">

            <div class="lh50 tac ft_yh ft20 bg_f63 white" style="height:50px;box-sizing: border-box;"><?php echo _cfg('web_name') ?: $this->lan("Management_backend"); ?></div>

            <div class="login_ibox">

                <form action="#" method="post" id="form">
                    <div class="input_box" id="username-box" style="border: 0!important;">
                        <div class="lf input_value" style="box-shadow: none!important;">
                            <input type="text" id="input-u" class="input_input" style="max-width: stretch !important;" name="username" value="" placeholder="<?php $this->lan("tips"); ?>" autocomplete="username" required onkeypress="return /[a-zA-Z0-9_\-]/.test(event.key) || event.ctrlKey || event.metaKey || ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)" onpaste="event.preventDefault(); var text = (event.clipboardData || window.clipboardData).getData('text').replace(/[^a-zA-Z0-9_\-]/g, ''); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + text + this.value.substring(end); this.setSelectionRange(start + text.length, start + text.length);" oninput="this.value = this.value.replace(/[^a-zA-Z0-9_\-]/g, '');">
                            <span class="success-indicator">✓</span>
                        </div>
                        <div class="cl"></div>
                        <div class="error-message" id="username-error"></div>
                    </div>

                    <div class="input_box" id="password-box" style="margin-bottom: 10px !important; margin-top: 10px !important;border: 0!important;">
                        <div class="lf input_value">
                            <input type="password" id="input-p" class="input_input" style="max-width: stretch !important;" name="password" value="" placeholder="<?php $this->lan("tips1"); ?>" autocomplete="current-password" required >
                            <div id="toggleButton">
                                <!-- 隐藏密码图标 -->
                                <svg id="i_close" class="password-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: block;">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                    <path d="M2.71 3.16L21.29 21.74l-1.41 1.41L1.29 4.57l1.42-1.41z" fill="currentColor" opacity="0.5"/>
                                </svg>
                                <!-- 显示密码图标 -->
                                <svg id="i_open" class="password-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                        <div class="cl"></div>
                        <div class="error-message" id="password-error"></div>
                    </div>
                    <div>
                        <?php if ($type == 1) {; ?>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px !important; margin-top: 10px !important;<?php if (!_cfg("web_verify")) { ?> filter:alpha(opacity=0);-moz-opacity:0; opacity:0;<?php } ?>">
                                <div class="input_box lf wid260" style="margin: 0 !important; width: 300px !important; max-width: 300px !important;border:0!important;padding: 0!important;">
                                    <div class="lf input_value" style="padding:0;">
                                        <input type="text" id="input-c" class="input_input wid100 input-checkcode" name="code" value="" maxlength="6" <?php if (!_cfg("web_verify")) { ?> disabled="disabled" <?php } ?> placeholder="<?php $this->lan("tips2"); ?>" style="z-index: 10 !important; position: relative !important; pointer-events: auto !important; padding-right: 16px !important;">
                                    </div>
                                    <div class="cl"></div>
                                </div>
                                <img id="checkcode" class="vam" src="<?php echo G_MODULE_PATH; ?>/checkcode/image/80_27/" style="width: 120px; height: 48px; border-radius: 8px; cursor: pointer; border: 2px solid #e1e8ed; flex-shrink: 0;" />
                            </div>
                        <?php } else if ($type == 2) { ?>
                            <div class="input_box lf wid260" style="margin-bottom: 25px !important; margin-top: 10px !important;<?php if (!_cfg("web_verify")) { ?> filter:alpha(opacity=0);-moz-opacity:0; opacity:0;<?php } ?>">
                                <input type="hidden" id="phone" value="<?php echo $phone; ?>">
                                <div class="lf input_value">
                                    <input type="text" id="input-c" class="input_input wid100 input-checkcode" name="code" value="" maxlength="6" placeholder="<?php $this->lan("tips2"); ?>" style="z-index: 10 !important; position: relative !important; pointer-events: auto !important;">
                                </div>

                                <button class="getAdminCode" type="button"><?php $this->lan("Get_verification_code"); ?></button>
                                <div class="cl"></div>
                            </div>
                        <?php } else if ($type == 3) { ?>
                            <div class="input_box lf wid320" style="margin-bottom: 25px !important; margin-top: 10px !important;<?php if (!_cfg("web_verify")) { ?> filter:alpha(opacity=0);-moz-opacity:0; opacity:0;<?php } ?>">
                                <div class="lf input_value">
                                    <input type="text" id="input-c" class="input_input wid100 input-checkcode" name="code" value="" maxlength="6" placeholder="<?php $this->lan("tips2"); ?>" style="z-index: 10 !important; position: relative !important; pointer-events: auto !important;">
                                </div>
                                <div class="cl"></div>
                            </div>
                        <?php } else if ($type == 4) { ?>

                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px !important; margin-top: 10px !important;<?php if (!_cfg("web_verify")) { ?> filter:alpha(opacity=0);-moz-opacity:0; opacity:0;<?php } ?>">
                                <div class="input_box lf wid260" style="margin: 0 !important; width: 300px !important; max-width: 300px !important;">
                                    <div class="lf input_value">
                                        <input type="text" id="input-c" class="input_input wid100 input-checkcode" name="code" value="" maxlength="6" <?php if (!_cfg("web_verify")) { ?> disabled="disabled" <?php } ?> placeholder="<?php $this->lan("tips2"); ?>" style="z-index: 10 !important; position: relative !important; pointer-events: auto !important; padding-right: 16px !important;">
                                    </div>
                                    <div class="cl"></div>
                                </div>
                                <img id="checkcode" class="vam" src="<?php echo G_MODULE_PATH; ?>/checkcode/suanshuimg" style="width: 120px; height: 48px; border-radius: 8px; cursor: pointer; border: 2px solid #e1e8ed; flex-shrink: 0;" />
                            </div>
                        <?php } else if ($type == 5) { ?>
                            <div class='Lcodes lf' style="width:100px;margin-top: 10px;">
                                <div id="embed-captcha" style="width:100px"></div>
                                <p id="wait" class="show"></p>
                            </div>
                        <?php } ?>

                        <div class="login_btn bg_f63 ft16 ft_yh" id="form_but" style="margin-top: 25px !important; margin-bottom: 10px !important;">
                            <span class="btn-text"><?php $this->lan("Log_in"); ?></span>
                        </div>

                    </div>
                </form>

            </div>

        </div><!--login end-->

        <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>

        <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>

        <script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.min.js"></script>

        <script type="text/javascript">
       
        // 验证码输入限制：仅允许数字与英文字母，禁止标点与汉字，最多 6 位
        function applyCheckcodeFilter() {
            var inputs = document.querySelectorAll('input.input-checkcode, input[name="code"]');
            inputs.forEach(function(inp) {
                if (inp._checkcodeFilter) return;
                inp._checkcodeFilter = true;
                inp.setAttribute('maxlength', '6');
                function filterValue(val) {
                    return (val || '').replace(/[^0-9A-Za-z]/g, '').slice(0, 6);
                }
                inp.addEventListener('input', function() {
                    this.value = filterValue(this.value);
                });
                inp.addEventListener('paste', function(e) {
                    e.preventDefault();
                    var text = (e.clipboardData || window.clipboardData).getData('text');
                    var filtered = filterValue(text);
                    var start = this.selectionStart, end = this.selectionEnd;
                    var before = this.value.slice(0, start), after = this.value.slice(end);
                    var combined = filterValue(before + filtered + after).slice(0, 6);
                    this.value = combined;
                    var pos = Math.min(before.length + filtered.length, combined.length);
                    this.setSelectionRange(pos, pos);
                });
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            applyCheckcodeFilter();
            var loginBtn = document.getElementById('form_but');
            if (loginBtn) {
                loginBtn.style.setProperty('width', '376px', 'important');
                loginBtn.style.setProperty('min-width', '376px', 'important');
                loginBtn.style.setProperty('max-width', '376px', 'important');
                
                // 强制应用所有输入框宽度为422px
                var allInputBoxes = document.querySelectorAll('.input_box');
                allInputBoxes.forEach(function(box) {
                    box.style.setProperty('width', '422px', 'important');
                    box.style.setProperty('max-width', '422px', 'important');
                });
                
                // 强制应用密码输入框和验证码输入框之间的间距
                var passwordBox = document.getElementById('password-box');
                if (passwordBox) {
                    passwordBox.style.setProperty('margin-bottom', '10px', 'important');
                    passwordBox.style.setProperty('margin-top', '10px', 'important');
                }
                
                // 强制应用验证码输入框宽度和高度，以及间距（如果图片在外部，宽度为300px）
                var verifyInputBoxes = document.querySelectorAll('.input_box.wid260, .input_box.wid320');
                verifyInputBoxes.forEach(function(box) {
                    // 检查是否有外部的验证码图片
                    var parentDiv = box.parentElement;
                    var hasExternalImage = parentDiv && parentDiv.querySelector('#checkcode') && !box.querySelector('#checkcode');
                    if (hasExternalImage) {
                        box.style.setProperty('width', '300px', 'important');
                        box.style.setProperty('max-width', '300px', 'important');
                    } else {
                        box.style.setProperty('width', '422px', 'important');
                        box.style.setProperty('max-width', '422px', 'important');
                    }
                    box.style.setProperty('height', '50px', 'important');
                    box.style.setProperty('min-height', '50px', 'important');
                    box.style.setProperty('max-height', '50px', 'important');
                    box.style.setProperty('margin-bottom', '0', 'important');
                    box.style.setProperty('margin-top', '0', 'important');
                });
                
                // 强制应用登录按钮上边距
                if (loginBtn) {
                    loginBtn.style.setProperty('margin-top', '25px', 'important');
                }
                
                // 确保验证码图片容器绝对定位靠右，但不阻止输入框点击
                var verifyImageContainers = document.querySelectorAll('.input_box.wid260 .input_value:last-child, .input_box.wid320 .input_value:last-child');
                verifyImageContainers.forEach(function(container) {
                    container.style.setProperty('position', 'absolute', 'important');
                    container.style.setProperty('right', '0', 'important');
                    container.style.setProperty('top', '50%', 'important');
                    container.style.setProperty('transform', 'translateY(-50%)', 'important');
                    container.style.setProperty('margin', '0', 'important');
                    container.style.setProperty('padding', '0', 'important');
                    container.style.setProperty('z-index', '2', 'important');
                    container.style.setProperty('pointer-events', 'none', 'important');
                });
                
                // 确保验证码图片本身可以点击
                var verifyImages = document.querySelectorAll('.input_box.wid260 img, .input_box.wid320 img, #checkcode');
                verifyImages.forEach(function(img) {
                    img.style.setProperty('margin', '0', 'important');
                    img.style.setProperty('padding', '0', 'important');
                    img.style.setProperty('pointer-events', 'auto', 'important');
                });
                
                // 确保输入框可以正常点击和输入
                var verifyInputs = document.querySelectorAll('.input_box.wid260 .input_input, .input_box.wid320 .input_input');
                verifyInputs.forEach(function(input) {
                    input.style.setProperty('z-index', '10', 'important');
                    input.style.setProperty('position', 'relative', 'important');
                    input.style.setProperty('pointer-events', 'auto', 'important');
                });
                loginBtn.style.setProperty('padding', '16px 24px', 'important');
                loginBtn.style.setProperty('background', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', 'important');
                loginBtn.style.setProperty('color', '#ffffff', 'important');
                loginBtn.style.setProperty('border', 'none', 'important');
                loginBtn.style.setProperty('border-radius', '12px', 'important');
                loginBtn.style.setProperty('font-size', '18px', 'important');
                loginBtn.style.setProperty('font-weight', '600', 'important');
                loginBtn.style.setProperty('margin', '25px 0 10px 0', 'important');
                loginBtn.style.setProperty('height', 'auto', 'important');
                loginBtn.style.setProperty('line-height', 'normal', 'important');
                loginBtn.style.setProperty('text-align', 'center', 'important');
                loginBtn.style.setProperty('display', 'flex', 'important');
                loginBtn.style.setProperty('align-items', 'center', 'important');
                loginBtn.style.setProperty('justify-content', 'center', 'important');
                loginBtn.style.setProperty('float', 'none', 'important');
                
                var btnText = loginBtn.querySelector('.btn-text');
                if (btnText) {
                    btnText.style.setProperty('text-align', 'center', 'important');
                    btnText.style.setProperty('width', '100%', 'important');
                    btnText.style.setProperty('display', 'block', 'important');
                    btnText.style.setProperty('font-size', '18px', 'important');
                    btnText.style.setProperty('color', '#ffffff', 'important');
                }
            }
        });
        
        // 强制应用间距样式 - 使用setInterval确保执行
        (function() {
            function forceApplySpacing() {
                // 密码输入框和验证码输入框之间的间距
                var passwordBox = document.getElementById('password-box');
                if (passwordBox) {
                    passwordBox.style.setProperty('margin-bottom', '10px', 'important');
                    passwordBox.style.setProperty('margin-top', '10px', 'important');
                }
                
                // 验证码输入框和登录按钮之间的间距（处理外部图片的情况）
                // 查找包含验证码输入框和外部图片的容器
                var verifyContainers = document.querySelectorAll('div[style*="display: flex"]');
                verifyContainers.forEach(function(container) {
                    var hasVerifyInput = container.querySelector('.input_box.wid260, .input_box.wid320');
                    var hasExternalImage = container.querySelector('#checkcode');
                    if (hasVerifyInput && hasExternalImage) {
                        container.style.setProperty('margin-bottom', '25px', 'important');
                        container.style.setProperty('margin-top', '10px', 'important');
                    }
                });
                
                // 对于没有外部容器的验证码输入框，保持原有间距
                var verifyInputBoxes = document.querySelectorAll('.input_box.wid260, .input_box.wid320');
                verifyInputBoxes.forEach(function(box) {
                    var parentDiv = box.parentElement;
                    var hasExternalImage = parentDiv && parentDiv.querySelector('#checkcode') && !box.querySelector('#checkcode');
                    if (!hasExternalImage) {
                        box.style.setProperty('margin-bottom', '25px', 'important');
                        box.style.setProperty('margin-top', '10px', 'important');
                    } else {
                        box.style.setProperty('margin-bottom', '0', 'important');
                        box.style.setProperty('margin-top', '0', 'important');
                    }
                });
                
                // 强制应用验证码输入框的z-index，确保可以输入
                var verifyInputs = document.querySelectorAll('.input_box.wid260 .input_input, .input_box.wid320 .input_input');
                verifyInputs.forEach(function(input) {
                    input.style.setProperty('z-index', '10', 'important');
                    input.style.setProperty('position', 'relative', 'important');
                    input.style.setProperty('pointer-events', 'auto', 'important');
                });
                
                // 登录按钮上边距
                var loginBtn = document.getElementById('form_but');
                if (loginBtn) {
                    loginBtn.style.setProperty('margin-top', '25px', 'important');
                    loginBtn.style.setProperty('margin-bottom', '10px', 'important');
                    loginBtn.style.setProperty('margin-left', '0', 'important');
                    loginBtn.style.setProperty('margin-right', '0', 'important');
                }
            }
            
            // 立即执行
            forceApplySpacing();
            
            // DOMContentLoaded时执行
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', forceApplySpacing);
            } else {
                forceApplySpacing();
            }
            
            // 延迟执行，确保覆盖全局样式
            setTimeout(forceApplySpacing, 100);
            setTimeout(forceApplySpacing, 500);
            setTimeout(forceApplySpacing, 1000);
            
            // 使用MutationObserver监听DOM变化
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    forceApplySpacing();
                });
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        })();
        
            document.addEventListener('DOMContentLoaded', function() {
                // 账号输入框禁止输入标点符号
                const usernameInput = document.getElementById('input-u');
                if (usernameInput) {
                    usernameInput.addEventListener('keypress', function(e) {
                        // 允许的字符：字母、数字、下划线、中划线
                        const allowedChars = /^[a-zA-Z0-9_\-]$/;
                        // 如果按下的键不是允许的字符，阻止输入
                        if (!allowedChars.test(e.key) && !e.ctrlKey && !e.metaKey && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' && e.key !== 'Home' && e.key !== 'End') {
                            e.preventDefault();
                        }
                    });
                    
                    // 处理粘贴事件，过滤标点符号
                    usernameInput.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                        // 只保留字母、数字、下划线、中划线
                        const filteredText = pastedText.replace(/[^a-zA-Z0-9_\-]/g, '');
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        const currentValue = this.value;
                        this.value = currentValue.substring(0, start) + filteredText + currentValue.substring(end);
                        this.setSelectionRange(start + filteredText.length, start + filteredText.length);
                    });
                    
                    // 处理输入事件，实时过滤标点符号
                    usernameInput.addEventListener('input', function(e) {
                        const value = this.value;
                        // 只保留字母、数字、下划线、中划线
                        const filteredValue = value.replace(/[^a-zA-Z0-9_\-]/g, '');
                        if (value !== filteredValue) {
                            this.value = filteredValue;
                        }
                    });
                }
                
                const passwordInput = document.getElementById('input-p');
                const toggleButton = document.getElementById('toggleButton');
                const eyeOpenIcon = document.getElementById("i_open");
                const eyeCloseIcon = document.getElementById("i_close");

                let isVisible = false; // 初始状态设为false，表示密码不可见
                if (eyeOpenIcon) eyeOpenIcon.style.display = "none";
                if (eyeCloseIcon) eyeCloseIcon.style.display = "block";
                
                toggleButton.addEventListener('click', function() {
                    if (isVisible) {
                        // 如果密码当前是可见的，则切换为隐藏状态
                        passwordInput.type = 'password';
                        if (eyeOpenIcon) eyeOpenIcon.style.display = "none";
                        if (eyeCloseIcon) eyeCloseIcon.style.display = "block";
                    } else {
                        // 如果密码当前是隐藏的，则切换为可见状态
                        passwordInput.type = 'text'; // 修改type为text以显示密码
                        if (eyeOpenIcon) eyeOpenIcon.style.display = "block";
                        if (eyeCloseIcon) eyeCloseIcon.style.display = "none";
                    }
                    isVisible = !isVisible; // 切换isVisible的状态
                });
                
               
            });
           
           document.addEventListener('DOMContentLoaded', function() {
                const userInput = document.getElementById('input-u');
                const pasInput = document.getElementById('input-p');
                pasInput.addEventListener('focus', function() {
                    pasInput.parentElement.style.boxShadow="0 0 20px 0 #e2e2e2"
                })
                userInput.addEventListener('focus', function() {
                    userInput.parentElement.style.boxShadow="0 0 20px 0 #e2e2e2"
                })
           });

            var loading;
            var form_but;

            window.onload = function() {

                document.onkeydown = function() {
                    if (event.keyCode == 13) {
                        ajaxsubmit();
                    }
                    return;
                }
                form_but = document.getElementById('form_but');
                form_but.onclick = ajaxsubmit;

                var checkcode = document.getElementById('checkcode');
                if (checkcode) {
                    checkcode.src = checkcode.src + "&" + new Date().getTime();
                    var src = checkcode.src;

                    checkcode.onclick = function() {
                        this.classList.add('refreshing');
                        var currentSrc = this.src.split('&')[0];
                        this.src = currentSrc + '&' + new Date().getTime();
                        setTimeout(function() {
                            checkcode.classList.remove('refreshing');
                        }, 500);
                    }
                    
                    // 添加提示
                    checkcode.title = '点击刷新验证码';
                    checkcode.style.cursor = 'pointer';
                }
            }

            //$(document).ready(function(){$.alt("#input-u");$.alt("#input-p");$.alt("#input-c");});

            // 表单验证函数
            function validateForm() {
                var isValid = true;
                var username = document.getElementById('input-u').value.trim();
                var password = document.getElementById('input-p').value;
                var usernameBox = document.getElementById('username-box');
                var passwordBox = document.getElementById('password-box');
                var usernameError = document.getElementById('username-error');
                var passwordError = document.getElementById('password-error');
                
                // 清除之前的验证状态
                usernameBox.classList.remove('valid', 'invalid');
                passwordBox.classList.remove('valid', 'invalid');
                
                // 验证用户名
                if (!username) {
                    usernameBox.classList.add('invalid');
                    usernameError.textContent = '请输入用户名';
                    isValid = false;
                } else if (username.length < 2) {
                    usernameBox.classList.add('invalid');
                    usernameError.textContent = '用户名至少2个字符';
                    isValid = false;
                } else {
                    usernameBox.classList.add('valid');
                    usernameError.textContent = '';
                }
                
                // 验证密码
                if (!password) {
                    passwordBox.classList.add('invalid');
                    passwordError.textContent = '请输入密码';
                    isValid = false;
                } else if (password.length < 4) {
                    passwordBox.classList.add('invalid');
                    passwordError.textContent = '密码至少4个字符';
                    isValid = false;
                } else {
                    passwordBox.classList.add('valid');
                    passwordError.textContent = '';
                }
                
                return isValid;
            }
            
            // 实时验证
            document.addEventListener('DOMContentLoaded', function() {
                var usernameInput = document.getElementById('input-u');
                var passwordInput = document.getElementById('input-p');
                
                // 用户名实时验证
                usernameInput.addEventListener('blur', function() {
                    var username = this.value.trim();
                    var usernameBox = document.getElementById('username-box');
                    var usernameError = document.getElementById('username-error');
                    
                    if (username) {
                        if (username.length < 2) {
                            usernameBox.classList.remove('valid');
                            usernameBox.classList.add('invalid');
                            usernameError.textContent = '用户名至少2个字符';
                        } else {
                            usernameBox.classList.remove('invalid');
                            usernameBox.classList.add('valid');
                            usernameError.textContent = '';
                        }
                    } else {
                        usernameBox.classList.remove('valid', 'invalid');
                        usernameError.textContent = '';
                    }
                });
                
                // 密码实时验证
                passwordInput.addEventListener('blur', function() {
                    var password = this.value;
                    var passwordBox = document.getElementById('password-box');
                    var passwordError = document.getElementById('password-error');
                    
                    if (password) {
                        if (password.length < 4) {
                            passwordBox.classList.remove('valid');
                            passwordBox.classList.add('invalid');
                            passwordError.textContent = '密码至少4个字符';
                        } else {
                            passwordBox.classList.remove('invalid');
                            passwordBox.classList.add('valid');
                            passwordError.textContent = '';
                        }
                    } else {
                        passwordBox.classList.remove('valid', 'invalid');
                        passwordError.textContent = '';
                    }
                });
                
                // 输入时清除错误状态
                usernameInput.addEventListener('input', function() {
                    if (this.value.trim()) {
                        var usernameBox = document.getElementById('username-box');
                        usernameBox.classList.remove('invalid');
                    }
                });
                
                passwordInput.addEventListener('input', function() {
                    if (this.value) {
                        var passwordBox = document.getElementById('password-box');
                        passwordBox.classList.remove('invalid');
                    }
                });
                
                // 自动聚焦用户名输入框
                setTimeout(function() {
                    usernameInput.focus();
                }, 300);
            });

            function ajaxsubmit() {
                // 先进行表单验证
                if (!validateForm()) {
                    return false;
                }
                
                var name = document.getElementById('form').username.value.trim();
                var pass = document.getElementById('form').password.value;
                <?php if ($type != 5) { ?>
                    var codes = document.getElementById('form').code ? document.getElementById('form').code.value : '';
                    var bv = '';
                    var cv = '';
                    var fv = '';
                <?php } else { ?>

                    var bv = $('input[name="geetest_challenge"]').val();

                    var cv = $('input[name="geetest_validate"]').val();

                    var fv = $('input[name="geetest_seccode"]').val();
                <?php } ?>
                
                // 显示加载状态
                beforeSend();
                
                $.ajaxSetup({
                    async: false
                });
                if (!codes) {
                    var codes = '';
                }
                $.ajax({
                    "url": window.location.href,
                    "type": "POST",
                    "data": ({
                        username: name,
                        password: pass,
                        code: codes,
                        ajax: true,
                        bv: bv,
                        cv: cv,
                        fv: fv,
                    }),
                    "beforeSend": function() {
                        beforeSend();
                    },
                    "success": success,
                    "error": function() {
                        if (typeof NotificationSystem !== 'undefined') {
                            NotificationSystem.error('网络错误，请检查网络连接');
                        } else {
                            layer.msg('网络错误，请检查网络连接', {icon: 2});
                        }
                        form_but.classList.remove('loading');
                        form_but.querySelector('.btn-text').textContent = '<?php $this->lan("Log_in"); ?>';
                        form_but.disabled = false;
                    }
                });

            }

            function beforeSend() {
                form_but.classList.add('loading');
                form_but.disabled = true;
                if (loading) {
                    try {
                        layer.close(loading);
                    } catch(e) {}
                }
            }

            function success(data) {
                form_but.classList.remove('loading');
                form_but.disabled = false;
                
                var obj = jQuery.parseJSON(data);
                if (!obj.error) {
                    // 显示成功消息
                    if (typeof NotificationSystem !== 'undefined') {
                        NotificationSystem.success(obj.msg || '登录成功', {
                            duration: 1500
                        });
                    } else if (obj.codes && obj.codes == 1) {
                        layer.msg(obj.msg || '登录成功', {
                            icon: 1,
                            time: 1500
                        });
                    } else {
                        layer.msg(obj.msg || '登录成功', 1);
                    }
                    
                    // 延迟跳转，让用户看到成功提示
                    setTimeout(function() {
                        window.location.href = obj.text;
                    }, 1500);
                } else {
                    // 显示错误消息
                    if (typeof NotificationSystem !== 'undefined') {
                        NotificationSystem.error(obj.text || '登录失败，请检查用户名和密码');
                    } else {
                        $.layer({
                            type: 0,
                            area: ['auto', 'auto'],
                            title: ['信息', true],
                            border: [5, 0.5, '#7298a6', true],
                            dialog: {
                                msg: obj.text
                            }
                        });
                    }
                    
                    // 刷新验证码
                    var checkcode = document.getElementById('checkcode');
                    if (checkcode) {
                        checkcode.classList.add('refreshing');
                        var src = checkcode.src;
                        checkcode.src = '';
                        setTimeout(function() {
                            checkcode.src = src + '&' + new Date().getTime();
                            checkcode.classList.remove('refreshing');
                        }, 500);
                    }
                    
                    // 清除密码框
                    document.getElementById('input-p').value = '';
                    document.getElementById('input-p').focus();
                    
                    // 清除验证状态
                    document.getElementById('username-box').classList.remove('valid', 'invalid');
                    document.getElementById('password-box').classList.remove('valid', 'invalid');
                }
            }
        </script>
        <script>
            $(function() {
                // 优化键盘操作
                $(document).keydown(function(e) {
                    // Enter键提交表单
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        var activeElement = document.activeElement;
                        // 如果焦点在输入框或按钮上，提交表单
                        if (activeElement.tagName === 'INPUT' || activeElement.tagName === 'BUTTON') {
                            ajaxsubmit();
                        }
                    }
                    
                    // ESC键清除错误状态
                    if (e.keyCode == 27) {
                        document.getElementById('username-box').classList.remove('invalid');
                        document.getElementById('password-box').classList.remove('invalid');
                    }
                });
                
                // Tab键优化：自动聚焦下一个输入框
                $('#input-u, #input-p, #input-c').on('keydown', function(e) {
                    if (e.keyCode === 9) { // Tab键
                        var inputs = $('#input-u, #input-p, #input-c').filter(':visible');
                        var currentIndex = inputs.index(this);
                        if (currentIndex < inputs.length - 1) {
                            e.preventDefault();
                            inputs.eq(currentIndex + 1).focus();
                        }
                    }
                });
            });
        </script>
        <script>
        // 多语言切换功能
        (function() {
            var customSelect = document.getElementById('customLanguageSelect');
            var select = document.getElementById('languageSelect');
            
            if (!customSelect || !select) {
                return;
            }
            
            // 点击自定义下拉框
            customSelect.addEventListener('click', function(e) {
                e.stopPropagation();
                customSelect.classList.toggle('active');
            });
            
            // 点击选项
            var options = customSelect.querySelectorAll('.custom-select-option');
            options.forEach(function(option) {
                option.addEventListener('click', function() {
                    var value = this.getAttribute('data-value');
                    var flagUrl = this.getAttribute('data-flag');
                    
                    // 更新选中状态
                    options.forEach(function(opt) {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    
                    // 更新显示
                    var customSelected = customSelect.querySelector('.custom-select-selected');
                    var flagContainer = customSelected.querySelector('.flag-icon');
                    
                    // 获取选中选项的国旗内容（可能是SVG或img）
                    var selectedFlagContent = this.querySelector('.flag-icon');
                    if(selectedFlagContent) {
                        if(flagContainer) {
                            // 替换现有的国旗
                            flagContainer.innerHTML = selectedFlagContent.innerHTML;
                            // 如果是img标签，更新src
                            var flagImg = flagContainer.querySelector('img');
                            if(flagImg && flagUrl) {
                                flagImg.src = flagUrl;
                            }
                        } else {
                            // 创建新的国旗容器
                            flagContainer = document.createElement('span');
                            flagContainer.className = 'flag-icon';
                            flagContainer.innerHTML = selectedFlagContent.innerHTML;
                            // 如果是img标签，更新src
                            var flagImg = flagContainer.querySelector('img');
                            if(flagImg && flagUrl) {
                                flagImg.src = flagUrl;
                            }
                            customSelected.insertBefore(flagContainer, customSelected.firstChild);
                        }
                    }
                    
                    var langName = this.querySelector('.lang-name').textContent;
                    // 删除语言名称后的"（国家）"部分 - 处理各种括号格式
                    // 使用循环多次处理，确保所有括号都被删除
                    var patterns = [
                        /\s*\([^)]*\)\s*$/u,      // 英文括号
                        /\s*（[^）]*）\s*$/u,        // 中文括号
                        /\s*【[^】]*】\s*$/u,        // 中文方括号
                        /\s*\[[^\]]*\]\s*$/u,       // 英文方括号
                        /\s*\([^)]+\)\s*$/u,       // 英文括号（更宽松）
                        /\s*（[^）]+）\s*$/u,       // 中文括号（更宽松）
                    ];
                    // 循环处理，直到没有更多括号
                    var lastLangName = '';
                    while (langName !== lastLangName) {
                        lastLangName = langName;
                        patterns.forEach(function(pattern) {
                            langName = langName.replace(pattern, '');
                        });
                    }
                    langName = langName.trim(); // 去除首尾空格
                    customSelected.querySelector('.lang-name').textContent = langName;
                    
                    // 更新隐藏的select
                    select.value = value;
                    
                    // 触发change事件
                    var changeEvent = new Event('change', { bubbles: true });
                    select.dispatchEvent(changeEvent);
                    
                    // 关闭下拉框
                    customSelect.classList.remove('active');
                    
                    // 调用语言切换函数
                    changeLanguage(value, flagUrl);
                });
            });
            
            // 点击外部关闭下拉框
            document.addEventListener('click', function(e) {
                if (!customSelect.contains(e.target)) {
                    customSelect.classList.remove('active');
                }
            });
            
            // 语言切换函数
            function changeLanguage(lgValue, flagUrl) {
                var select = document.getElementById('languageSelect');
                if (!select) {
                    return;
                }
                
                // 显示加载中
                var loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay) {
                    loadingOverlay.classList.add('show');
                }
                
                // 发送AJAX请求切换语言
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo G_MODULE_PATH; ?>/user/changeLanguage', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (loadingOverlay) {
                            loadingOverlay.classList.remove('show');
                        }
                        
                        if (xhr.status === 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.code == 200) {
                                    // 设置cookie
                                    document.cookie = 'admin_language=' + lgValue + '; path=/; max-age=31536000';
                                    // 刷新页面
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 500);
                                } else {
                                    alert(response.msg || '语言切换失败');
                                }
                            } catch(e) {
                                console.error('解析响应失败:', e);
                                alert('语言切换失败');
                            }
                        } else {
                            alert('语言切换失败');
                        }
                    }
                };
                xhr.send('language=' + encodeURIComponent(lgValue));
            }
            
            // 监听隐藏select的change事件
            select.addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var flagUrl = selectedOption.getAttribute('data-flag');
                changeLanguage(this.value, flagUrl);
            });
        })();
        </script>

        <?php if ($type == 5) {; ?>
            <script>
                var handlerEmbed = function(captchaObj) {
                    $("#embed-submit").click(function(e) {
                        var validate = captchaObj.getValidate();
                        if (!validate) {
                            $("#notice")[0].className = "show";
                            setTimeout(function() {
                                $("#notice")[0].className = "hide";
                            }, 2000);
                            e.preventDefault();
                        }
                    });
                    // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
                    captchaObj.appendTo("#embed-captcha");
                    captchaObj.onReady(function() {
                        $("#wait")[0].className = "hide";
                    });
                    // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
                };
                $.ajax({
                    // 获取id，challenge，success（是否启用failback）
                    url: "<?php echo WEB_PATH; ?>/mobile/ajax/StartCaptchaServlet/t=" + (new Date()).getTime(), // 加随机数防止缓存
                    type: "get",
                    dataType: "json",
                    success: function(data) {
                        // 使用initGeetest接口
                        // 参数1：配置参数
                        // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                        initGeetest({
                            gt: data.gt,
                            challenge: data.challenge,
                            new_captcha: data.new_captcha,
                            product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                            offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                            // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
                        }, handlerEmbed);
                    }
                });
            </script>
        <?php } ?>

        <?php if ($type != 5) {; ?>
            <script>
                var num = 60;
                var sending = false;
                $('.getAdminCode').on('click', function() {
                    if (sending) return
                    var phone = $('#phone').val();
                    var url = "<?php echo WEB_PATH; ?>/admin/user/send_login"
                    $.ajax({
                        url: url,
                        type: "post",
                        data: {
                            phone: phone,
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data.status == 0) {
                                layer.msg('发送成功', {
                                    icon: 1,
                                    time: 8000
                                });
                                send_delay();
                            } else {
                                layer.msg('发送失败', {
                                    icon: 2
                                });

                                sending = false;
                            }
                        }
                    })
                })

                function send_delay() {
                    sending = true;
                    set_delay(num);
                    interval = setInterval(function() {
                        num--;
                        if (num <= 0) {
                            stop_delay();
                        } else {
                            set_delay(num);
                        }
                    }, 1000);
                }

                function stop_delay() {
                    clearInterval(interval);
                    $('.getAdminCode').html('重新获取');
                    sending = false;
                    num = 60;
                }

                function set_delay(num) {
                    $('.getAdminCode').html(num + ' 秒');
                }
            </script>
        <?php } ?>

</body>

</html>