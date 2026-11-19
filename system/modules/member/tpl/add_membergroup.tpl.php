<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
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
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
</style>
</head>
<body>
<div class="header lr10">
    <h3 class="nav_icon"><?php $this->lan("Member_level_add");?></h3>

</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
  	 <tr>
			<td width="120" align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("name");?></td>
			<td style="display:flex;"><input type="text" name="name" value="" class="input-text"></td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Experience_value_start");?></td>
			<td style="display:flex;"><input type="text" name="jingyan_start" value="" class="input-text"></td>
			<div data-title="<?php $this->lan("Experience");?>" style="display: inline-block;width:200px;position: absolute;top: 150px; left: 570px;" class="tip">
                    <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
            </div>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Experience_value_end");?></td>
			<td style="display:flex;"><input type="text" name="jingyan_end" value="" class="input-text"></td>
			<div data-title="<?php $this->lan("Experience");?>" style="display: inline-block;width:200px;position: absolute;top: 150px; left: 570px;" class="tip">
                    <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
            </div>
		</tr>
			<tr>
			<td width="120" align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("rake_back","返佣比例");?>&nbsp;</td>
			<td style="display:flex;"><input type="number" step="0.01" max="1" min="0" name="rake_back" placeholder="<?php $this->lan("rake_back");?>" value="<?php echo $members['rake_back'];?>" class="input-text"></td>
		</tr>
		<tr>
        	<td width="120" align="right" style="text-align: right !important;padding-right: 10px;"></td>
            <td style="display:flex;">		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" style="margin:0!important;">
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
</body>
</html> 