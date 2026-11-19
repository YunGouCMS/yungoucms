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
<style>
tr{height:40px;line-height:40px}
</style>
</head>
<body>
<div class="header-title lr10">
	<b>验证码配置</b>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form name="myform" action="#" method="post">
  <table width="180%" cellspacing="0">	
		<tr>
			<td width="180" align="right">验证码宽:</td>
			<td><input type="text" name="width" value=""  class="input-text wid180">
			</td>
		</tr>
		<tr>
			<td width="180" align="right">验证码高:</td>
			<td><input type="text" name="height" value=""  class="input-text wid180">
			</td>
		</tr>
		<tr>
			<td width="180" align="right">字体颜色:</td>
			<td><input type="text" name="color" value=""  class="input-text wid180">
			</td>
		</tr>
		<tr>
			<td width="180" align="right">背景颜色:</td>
			<td><input type="text" name="bgcolor" value=""  class="input-text wid180">
			</td>
			</td>
		</tr>
		<tr>
			<td width="200" align="right">验证位数:</td>
			<td><input type="text" name="lenght" value=""  class="input-text wid180">
			</td>
		</tr>
		<tr>
			<td width="200" align="right">验证类型:</td>
			<td><input type="text" name="type" value=""  class="input-text wid180">
			</td>
		</tr>
		<tr>
			<td width="180">开启位置</td>
			<td align="left">
				后台开启<input type="checkbox" name="sel">&nbsp;
				会员开启<input type="checkbox" name="sel">&nbsp;
				评论开启<input type="checkbox" name="sel">
			</td>
		</tr>
		<tr>
			<td width="180" align="right"><input type="submit" value="设置"class="button"</td>
			<td><input type="reset" name="type" value="重置"  class="button">
			</td>
		</tr>
</table>
</form>

</div><!--table-list end-->

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 