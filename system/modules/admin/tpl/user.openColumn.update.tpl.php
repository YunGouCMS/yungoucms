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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
.color_window_td a{ float:left; margin:0px 10px;}

input[type="radio"] {
    margin-top: 1px !important;
}
</style>
</head>
<body>
<div class="header">
    <!--<h3 class="nav_icon"><?php $this->lan("edit");?></h3>-->
    <span class="span_fenge"></span>
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0" style="border:none">
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("menu_name");?> </td>
			<td>
				<!-- <input type="hidden" name="name" value="<?php echo $ment_info['name'];?>" style="width: 500px;" placeholder="请输入栏目名" class="input-text wid300" /> -->
				<?php
					  foreach($languages?:[] as $k=>$v){?>
				  <div style="display: flex;align-items: center;margin-bottom:5px;">
					  <span style="width: 110px; display:inline-block;text-align: right;margin:0!important;"><?php echo $v['lg_name']; ?></span>
					  <input  type="text" 
							  name="language_name[<?php echo $v['lg_value']; ?>]"
							  value="<?php if(isset($ment_info['language_name'][$v['lg_value']])){echo $ment_info['language_name'][$v['lg_value']]?:$ment_info["name"];}else{echo $ment_info['name'];} ?>" 
							  style="min-width:407px;width:407px" placeholder="<?php $this->lan("menu_name");?>" class="input-text">
				  </div>
				  <?php }  ?>
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("Superior_column");?></td>
			<td style="display:flex;">
				<select name="pid" style="margin:0!important;">
					<option value='0'><?php $this->lan("top");?></option>
					<?php 
						foreach($plist as $vl){
							$lg_name = safe_unserialize($vl['language_name'] ?? '');
							$vl["name"] = $lg_name[$this->language_backend]?:$vl['name'];
							$lm = "顶级目录";
							if($vl['level']==1){
								$lm = "一级目录";
							}
							if($vl['level']==2){
								$lm = "二级目录";
							}
							$sel = "";
							if($ment_info['pid'] == $vl['id']){
								$sel = "selected";
							}
							echo "<option value='{$vl['id']}' $sel >{$lm}---{$vl['name']}</option>";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("type");?></td>
			<td style="display:flex;">
				<select name="act" style="margin:0!important;">
					<option value='0'><?php $this->lan("contents");?></option>
					<option value='1' <?php if($ment_info['level']==3){echo "selected";} ?> ><?php $this->lan("method");?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("url");?></td>
			<td style="display:flex;">
				<input type="text" name="url" value="<?php echo $ment_info['d'];?>" style="width: 500px;" placeholder="<?php $this->lan("url");?>" class="input-text wid300" />
			</td>
		</tr>
		   <tr>
        	<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("status");?> </td>
            <td style="display:flex;">
            <div style="align-items: center;margin:0!important;display:flex;white-space: nowrap;gap: 5px;">
                <input style="min-width:13px;width:13px" type="radio" name="is_enable" value="1" <?php if($ment_info['is_enable'] == 1){echo 'checked';} ?> class="input-text"><?php $this->lan("open");?></div>
                <div style="padding-left: 10px;display:flex;align-items: center;margin:0!important;white-space: nowrap;gap: 5px;">
                <input style="min-width:13px;width:13px;" type="radio" name="is_enable" value="0" <?php if($ment_info['is_enable'] == 0){echo 'checked';} ?> class="input-text"><?php $this->lan("close");?>
                
               </div>
            </td>
        </tr>
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("sort");?></td>
			<td style="display:flex;">
				<input type="number" name="sort" value="<?php echo isset($ment_info['sort'])?$ment_info['sort']:0;?>" style="width: 500px;"  class="input-text wid300" />
			</td>
		</tr>
		<tr>
        	<td width="120" align="right"></td>
            <td style="display:flex;">
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" style="margin:0!important;" >
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

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 