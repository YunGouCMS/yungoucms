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
    <span style="color:#f60; padding-left:30px;line-height:40px">谨防模板被盗,建议修改html目录地址</span>
</div>
<div class="bk10"></div>
<div class="table-form lr10">
	<form name="form" action="#" method="post">
    <?php if (ROUTE_A == 'insert'): ?>    	
    	<span style="color:#09c; line-height:25px;"><b>模块名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;文件名</b></span><br />
        <input type="type" name="modulename" onkeyup="value=value.replace(/[\W]/g,'')"  class="input-text wid80" />.
        <input type="type" name="filename" onkeyup="value=value.replace(/[\W]/g,'')" class="input-text wid80" />.
         <input type="type" class="input-text wid40" value="html" readonly />
        <span style="color:#ccc">如: demo.index  不需要添加后缀，系统默认会加上" .html "</span>
        <div class="bk10"></div>
		<span style="color:#09c; line-height:25px;"><b>内容</b></span><br /><textarea style="width:100%;height:400px;" name="content"><?php echo $htmlcontent;?></textarea>
    <?php endif; ?>
    <?php if (ROUTE_A == 'update'): ?>
		<textarea style="width:100%;height:400px;" name="content"><?php echo $files;?></textarea>
    <?php endif; ?>
    <div class="btn_paixu">        		
		<input type ="submit" name="submit" class='button' value="保存模板" style="margin-left:10px"/>
	</div>
</form>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>