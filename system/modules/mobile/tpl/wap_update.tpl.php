<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
.color_window_td a{ float:left; margin:0px 10px;}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
		<tr>
			<td width="120" align="right">幻灯名称:</td>
			<td>
				<input type="text" name="title" value="<?php echo $wapone['title'];?>" class="input-text wid300" />
			</td>
		</tr>
		<tr>
			<td width="120" align="right">幻灯链接:</td>
			<td>
				<input type="text" name="link" value="<?php echo $wapone['link'];?>" class="input-text wid300"/>
				 请填写完整的链接地址..  
			</td>
		</tr>
    	<tr>
			<td width="120" align="right">排序:</td>
			<td>
				<input type="text" name="sort" value="<?php echo $wapone['sort'];?>" class="input-text wid300" />
			</td>
		</tr>
		<tr>
			<td align="right" width="120">背景颜色：</td>
			<td><input  type="text" id="title_style_color" name="title2" value="<?php echo $wapone['color']?>" onKeyUp="return gbcount(this,100,'texttitle2');"  class="input-text wid300">
            <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/colorpicker.js"></script>
            <img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/colour.png" width="15" height="16" onClick="colorpicker('title_colorpanel','set_title_color');" style="cursor:hand"/>
             <img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/bold.png" onClick="set_title_bold();" style="cursor:hand"/>
            <span class="lr10">还能输入<b id="texttitle2">100</b>个字符</span>
            </td>
		</tr>
		   <tr>
        	<td width="120" align="right">图3片:</td>
            <td>	
			<img id="imgHeadPhoto2" src="<?php echo G_UPLOAD_PATH."/".$wapone['img']; ?>"  style="width: 30px; height: 30px; border: solid 1px #d2e2e2;" alt="" />
			<input type="file" name="image" onchange="PreviewImage(this,'imgHeadPhoto2','divPreview');" style="width: 69px;" size="20" accept="image/png, image/jpeg, image/gif, image/jpg"/><laber style="margin-left:10px;">图片大小：640*300px<laber>
            </td>
        </tr>

		<tr>
        	<td width="120" align="right"></td>
            <td>		
            <input type="submit" class="button" name="submit" value="提交" >
            </td>
		</tr>
</table>
</form>
</div><!--table-list end-->
<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
<script>
function upImage(){
	return document.getElementById('imgfield').click();
}
</script>
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
</body>
</html> 