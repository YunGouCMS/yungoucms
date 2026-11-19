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
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<style>
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
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
		<tr>
			<td width="120" align="right"><?php $this->lan("slide_name"); ?>:</td>
			<td style="display:flex;">
				<input type="text" name="title" placeholder="<?php $this->lan("slide_name"); ?>" value="<?php echo $slideone['title'];?>" class="input-text wid300" />
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("slide_link"); ?>:</td>
			<td style="display:flex;">
				<input type="text" name="link" value="<?php echo $slideone['link'];?>" placeholder="地址前,会默认添加 <?php echo WEB_PATH; ?>/" class="input-text wid300"/>
				    
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("img"); ?> </td>
			<td style="display:flex;">
				<div class="layui-row" style="margin:0!important;">
					<div class="layui-col-md1 width200" style="margin-right:10px!important;">
						<div class="layui-upload-list height98">
							<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
								<span>+</span>
								<text><?php $this->lan("Click_upload"); ?></text>
							</div>
							<img class="layui-upload-img" width="196px" height="96px" src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $slideone['img']; ?>" id="imgha-img">
							<input type="hidden" id="imgha-lack" name="img" value="<?php echo $slideone['img']; ?>">
							<p id="demoText"></p>
							<button type="button" onclick="uploadimg('上传图片','#imgha','image','banner')"></button>
						</div>
					</div>
					<!--<div class="layui-col-md9">-->
					<!--    图片大小：640*300px，支持jpg、png-->
					<!--</div>-->
					<div data-title="<?php $this->lan("tips4"); ?>" class="tip">
						<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("country");?>:</td>
			<td style="display:flex;">
				<select name="language" id="modelid" onChange="" style="margin:0!important;">
					<?php foreach ($languages as $key => $v) { $sel = "";if($v['lg_value'] == $slideone['language']){$sel = "selected";} ?>
					<option value="<?php echo $v['lg_value']; ?>" <?php echo $sel;?>><?php echo $v['country']; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
        	<td width="120" align="right"></td>
            <td style="display:flex;">		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit"); ?>" style="margin:0!important;">
            </td>
		</tr>
</table>
</form>
</div><!--table-list end-->

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