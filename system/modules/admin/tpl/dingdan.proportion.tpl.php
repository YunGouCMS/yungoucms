<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>

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

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

    <style>

        tbody tr {
            line-height: 30px;
            height: 30px;
        }

        .con_a_btn {
            border: 0px solid #0c0
        }

        .con_a_btn a {
            display: inline-block;
            padding: 3px 6px;
            margin-right: 5px;
            background: #6CF;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer
        }
.rightgray {display: flex;align-items: center;}
	.rightgray div {background-color: #f5f5f5;border: 1px solid #B4B4B4;border-left-width: 0px;height: 27px;padding: 2px 15px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;line-height: 28px;}
	.rightgray input {margin: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;}
    </style>
</head>

<body>

<div class="header lr10">

    <?php echo $this->headerment(); ?>

</div>

<div class="bk10"></div>

<div class="table-list lr10">
<form action='<?php echo G_MODULE_PATH; ?>/dingdan/proportion' method="post">

<div class="rightgray"> 
&nbsp;&nbsp;&nbsp;会员回收比例设置<input type='text' name='proportion' placeholder="请输入整数" maxlength="5" value="<?php echo $proportion; ?>" class="input-text" style="width: 65px;text-align: center;margin-left: 5px;"><div>%</div>
</div>
<div>
    <input type="submit" value="提交" class="button" style="margin-left: 119px;margin-top: 15px">
</div>

</form>

</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 