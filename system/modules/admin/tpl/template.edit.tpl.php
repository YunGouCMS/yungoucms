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
<title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
</head>
<body>
<div class="header lr10">
	<h3 class="nav_icon">模板管理</h3><span class="span_fenge lr10"></span>
    <span style="color:#f60; padding-left:30px; line-height:40px"></span>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<form action="#" method="post" id="myform">
<table width="100%" class="lr10">
    <tr>
    	<td width="80">模板名称</td> 
   		<td><input type="text" name="name" class="input-text"  value="<?php echo $template['name']; ?>"></td>
    </tr>
    <tr>
    	<td width="80">模板目录</td> 
   		<td><input type="text" name="dir" class="input-text"  value="<?php echo $template['dir']; ?>"></td>
    </tr>
    <tr>
    	<td width="80">HTML目录</td> 
   		<td><input type="text" name="html" class="input-text"  value="<?php echo $template['html']; ?>"></td>
    </tr>
    <tr>
    	<td width="80">模板作者</td> 
   		<td><input type="text" name="author" class="input-text"  value="<?php echo $template['author']; ?>"></td>
    </tr>
</table>
   	<div class="bk15"></div>
    <input type="submit" value=" 提交 " name="dosubmit" class="button">
</form>
</div><!--table-list end-->

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 