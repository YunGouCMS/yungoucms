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
<style type="text/css">
	.bt{height: 30px; width: 200px;line-height: 30px; padding-left: 5px; padding-right: 5px}
</style>
<body>

<div class="header lr10">

	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>

<div class="table_form lr10">

<form method="post" action="#">

	<table width="100%"  cellspacing="0" cellpadding="0">

	<tr>
		<td>规则名称：<input  class="bt" name="name" value="<?php if($id>0){echo $wechat['name'];} ?>"></td>                 
	</tr>
	
	<tr>
		<td>关键词值：<input class="bt" name="keyword" value="<?php if($id>0){echo $wechat['keyword'];} ?>"></td>                 
	</tr>

	<tr>
		<td>文字内容：<textarea style="height:250px; width:600px; resize:none;padding:15px;" name="contents" ><?php if($id>0){echo $wechat['contents'];} ?></textarea> </td>                 
	</tr>        
	<?php if($id>0){?><input type="hidden" name='id' value="<?php echo $id ?>"><?php }  ?>
        <tr height="60px">

			<td><input type="submit" name="dosubmit" class="button" value="提交内容" /></td>

		</tr>

	</table>

</form>

</div>

 <span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 