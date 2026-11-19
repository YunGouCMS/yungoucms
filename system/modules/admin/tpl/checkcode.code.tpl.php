<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>谷歌验证</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="Shortcut Icon" type="image/x-icon" href="/favicon.ico" />
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/login.css" type="text/css">

</head>
<style>
.checkcode{
    width:200px;
    height:200px;
    margin:auto;
    padding:20px;
}
#title{
    margin-left: 20px;

}
</style>
<body>

<div class="checkcode">
    <img src="<?php echo $qrCodeUrl; ?>"/>
</div>
<div id="title">
    <p style=" color: #FFFFFF;word-break:keep-all;white-space:nowrap;">

        1、谷歌APP下载，请在应用市场搜索: 谷歌身份验证器（又名：Authenticator）；<br>
        2、每次生成一次性谷歌码后，请尽快绑定，绑定成功后请务必尽快删除已生成的谷歌码；<br>
        3、谷歌码扫描前，请确保服务器时间（后台首页查看）与北京时间一致，否则该验证码无效。<br>
    </p>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 
