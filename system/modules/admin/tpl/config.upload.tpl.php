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
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form name="myform" action="#" method="post">
  <table width="100%" cellspacing="0">	
		<tr>
			<td width="200" align="right">允许上传附件大小：</td>
			<td><input type="text" name="upsize" value="<?php echo $web['upsize']; ?>"  class="input-text"> 单位（bit）1024Bit=1KB</td>
			
		</tr>
		<tr>
			<td width="200" align="right">允许上传图片类型：</td>
			<td><input type="text" name="up_image_type" value="<?php echo $up_image_type; ?>" class="input-text wid250">
			<span class="span_meg"> 允许上传的图片类型： 用 , 号分隔</span>
			</td>
		</tr>	
		<tr>
			<td width="200" align="right">允许上传附件类型：</td>
			<td><input type="text" name="up_soft_type" value="<?php echo $up_soft_type; ?>" class="input-text wid250">
			<span class="span_meg"> 允许上传附件类型： 用 , 号分隔</span>
			</td>
		</tr>	
		<tr>
			<td width="200" align="right">允许上传媒体类型： </td>
			<td><input type="text" name="up_media_type" value="<?php echo $up_media_type;  ?>" class="input-text wid250">
			<span class="span_meg"> 允许上传媒体类型： 用 , 号分隔</span>
			</td>
		</tr>	
		<tr>
        	<td width="200" align="right"></td>
            <td><input type="submit" class="button" name="dosubmit"  value=" 提交 " ></td>		
		</tr>
		</table>
		</form>

</div><!--table-list end-->

<script>
	
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 