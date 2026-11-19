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
<title>后台首页 </title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<script type="text/javascript">
var editurl=Array();
editurl['editurl']='<?php echo G_PLUGIN_PATH; ?>/ueditor/';
editurl['imageupurl']='<?php echo G_ADMIN_PATH; ?>/ueditor/upimage/';
editurl['imageManager']='<?php echo G_ADMIN_PATH; ?>/ueditor/imagemanager';
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.all.min.js"></script>
<style>
	.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
	.color_window_td a{ float:left; margin:0px 10px;}
	table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
	table td{ padding-left:10px;}
	input.button{ display:inline-block}
</style>
</head>
<body>

<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">

		<tr>
			<td width="120" align="right"><?php $this->lan("Recharge");?></td>
			<td>
				<input type="text" name="every" value="<?php echo $wapone['every']; ?>" placeholder="<?php $this->lan("Recharge");?>" class="input-text wid300" />
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("consumption");?></td>
			<td>
				<input type="text" name="consumption" value="<?php echo $wapone['consumption']; ?>" placeholder="<?php $this->lan("consumption");?>" class="input-text wid300" />
			</td>
		</tr>
		<tr>
        	<td width="120" align="right"></td>
            <td>		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" >
            </td>
		</tr>
	</table>
</form>
</div>
 <span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
<script type="text/javascript">
function set_title_color(color) {
	$('#title2').css('color',color);
	$('#title_style_color').val(color);
}
function set_title_bold(){
	if($('#title_style_bold').val()=='bold'){
		$('#title_style_bold').val('');	
		$('#title2').css('font-weight','');
	}else{
		$('#title2').css('font-weight','bold');
		$('#title_style_bold').val('bold');
	}
}

//API JS
//window.parent.api_off_on_open('open');
</script>
<script>
function upImage(){
	return document.getElementById('imgfield').click();
}
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 