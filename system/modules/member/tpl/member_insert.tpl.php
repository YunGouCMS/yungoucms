<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
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
select {
    min-width: 130px;
}
.input-text {
    width: 123px;
}
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
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("nickname");?></td>
			<td style="display: flex;"><input type="text" name="username" value="" placeholder="<?php $this->lan("Tips1");?>" class="input-text"></td>
		</tr>
		<!--tr>
			<td width="120" align="right">邮箱</td>
			<td><input type="text" name="email" value="" class="input-text"></td>
		</tr-->
		<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("mobile_phone_no");?></td>
			<td style="display: flex;"><input type="text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>11)value=value.slice(0,11)" name="mobile" value="" placeholder="<?php $this->lan("Tips2");?>" class="input-text"></td>
		</tr>
		<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("password");?></td>
			<td style="display: flex;"><input type="text" name="password" value="" placeholder="<?php $this->lan("Tips3");?>" class="input-text"></td>
		</tr>
		<!--<tr>
			<td width="120" align="right">余额</td>
			<td><input type="text" name="money" value="" class="input-text">元(修改后的余额就是用户的总余额)</td>
		</tr>
		<tr>
			<td width="120" align="right">经验值</td>
			<td><input type="text" name="jingyan" value="" class="input-text"></td>
		</tr> -->
		<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("integral");?></td>
			<td style="display: flex;"><input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" name="score" value="" placeholder="<?php $this->lan("Tips4");?>" class="input-text"></td>
		</tr>
		<!--tr>
			<td width="120" align="right">邮箱验证</td>
			<td>
				<input type="radio" name="emailcode" value="1"  style="zoom: 120%;">&nbsp;&nbsp;已验证
				<input type="radio" name="emailcode" value="-1" checked style="zoom: 120%;">&nbsp;&nbsp;未验证
			</td>
		</tr-->
		
		<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Mobile_verification");?></td>
			<td style="display: flex;align-items: center;">
				<input type="radio" name="mobilecode" value="1" checked style="zoom: 120%;margin: 0!important;">&nbsp;&nbsp;<?php $this->lan("Verified");?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mobilecode" value="-1"  style="zoom: 120%;margin: 0!important;">&nbsp;&nbsp;<?php $this->lan("Unverified");?>
				<!--<input type="radio" name="mobilecode" value="-1" checked class="input-text">未验证-->
			</td>
		</tr>
		<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("head");?></td>
			<td>
				<div class="layui-row">
					<div class="layui-col-md1 width130" style="margin: 0!important;">
						<div class="layui-upload-list height130">
							<div class="layui-bottom-img" <?php if (!$web_logo): ?>style="opacity: 0;"<?php endif;?>>
								<span>+</span>
								<text><?php $this->lan("Click_upload");?></text>
							</div>
							<img class="layui-upload-img" width="120px" src="<?php echo  G_UPLOAD_PATH.'/'.$web_logo;?>" id="upusertx-img">
                            <input type="hidden" id="upusertx-lack" name="upimage" value="<?php echo  $web_logo?>">
							<p id="demoText"></p>
							<button type="button" onclick="uploadimg('修改用户头像','#upusertx','','touimg')"></button>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<!-- <tr>
			<td width="120" align="right">签名</td>
			<td><textarea style="width:400px;height:100px;padding: 2px 0px 2px 5px;" placeholder="请输入签名" name="qianming"></textarea></td>
		</tr> -->
		<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Member_level");?></td>
			<td style="display: flex;">
				<select name="membergroup" style="margin: 0!important;">
				<?php foreach($member_allgroup as $v){?>
					<option value="<?php echo $v['groupid'];?>"><?php echo $v['name'];?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
			<tr>
			<td width="200" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Member_role");?></td>
			<td style="display: flex;">
				<select name="level" style="margin: 0!important;">
					<option value="0" <?php echo $member['level']==0?"selected":"";?>><?php $this->lan("Ordinary_member");?></option>
					<option value="2" <?php echo $member['level']==2?"selected":"";?>><?php $this->lan("Channel_partners");?></option>
					<option value="3" <?php echo $member['level']==3?"selected":"";?>><?php $this->lan("Merchants");?></option>
					<option value="1" <?php echo $member['level']==1?"selected":"";?>><?php $this->lan("salesperson");?></option>
				</select>
			</td>
		</tr>
		<tr>
        	<td width="200" align="right"></td>
            <td style="display: flex;">		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" style="margin: 0!important;">
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