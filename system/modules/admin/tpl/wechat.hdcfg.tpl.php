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

<style>

tr{height:40px;line-height:40px}
select{
	height: 34px;
	margin: 0px 10px 0px 0px;
	padding: 2px 15px 2px 15px;
	border: 1px solid #B4B4B4;
	font-size: 12px;
	line-height: 34px;
	border-radius: 3px;
	height: 30px;
}
</style>

</head>

<body>

<div class="header lr10">

	<?php echo $this->headerment();?>

</div>

<div class="bk10"></div>

<div class="table-list lr10">

<!--start-->

<form name="myform" action="#" method="post">

  <table width="100%" cellspacing="0">

  	<tr>

		<td width="220" align="right">新品查看：&nbsp;</td>
		<td>
			<select name="new[]"> 是否开启：
				<option value="yes" <?php if($wechat[0]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
				<option value="no" <?php if($wechat[0]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
			</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[0]['point_value']; ?>"  name="new[]"  class="input-text wid200"></td>

	</tr>

    	<tr>

		<td width="220" align="right">热门查看：&nbsp;</td>
		<td>
			<select name="best[]"> 是否开启：
				<option value="yes" <?php if($wechat[1]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
				<option value="no" <?php if($wechat[1]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
			</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[1]['point_value']; ?>" name="best[]" class="input-text wid200"></td>
	</tr>

  	 <tr>

		<td width="220" align="right">推荐查看：&nbsp;</td>
		<td>
			<select name="hot[]"> 是否开启：
				<option value="yes" <?php if($wechat[2]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
				<option value="no" <?php if($wechat[2]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
			</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[2]['point_value']; ?>" name="hot[]" class="input-text wid200"></td>

	</tr>
  	 <tr>
		<td width="220" align="right">重新绑定：&nbsp;</td>
		<td>
			<select name="cxbd[]"> 是否开启：
				<option value="yes" <?php if($wechat[3]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
				<option value="no" <?php if($wechat[3]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
			</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[3]['point_value']; ?>" name="cxbd[]" class="input-text wid200"></td>

	</tr>
	<tr>

		<td width="220" align="right">订单查询：&nbsp;</td>
		<td>
			<select name="ddcx[]"> 是否开启：
				<option value="yes" <?php if($wechat[4]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
				<option value="no" <?php if($wechat[4]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
			</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[4]['point_value']; ?>" name="ddcx[]" class="input-text wid200"></td>

	</tr>
	 <tr>
		<td width="220" align="right">快递查询：&nbsp;</td>
		<td>
		<select name="kdcx[]"> 
			<option value="yes" <?php if($wechat[5]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
			<option value="no" <?php if($wechat[5]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
		</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[5]['point_value']; ?>" name="kdcx[]" class="input-text wid200"></td>

	</tr>
	  <tr>
		<td width="220" align="right">签到送积分：&nbsp;</td>
		<td>
			<select name="qiandao[]"> 
				<option value="yes" <?php if($wechat[6]['autoload']=='yes'){ ?> selected="selected" <?php } ?>>是</option>
				<option value="no" <?php if($wechat[6]['autoload']=='no'){ ?> selected="selected" <?php } ?>>否</option>
			</select>
		</td>
		<td><input type="text" value="<?php echo $wechat[6]['point_value']; ?>" name="qiandao[]" class="input-text wid200"></td>
	</tr>
    <tr>

            <td><input style="margin-left:90px;" type="submit" class="button" name="dosubmit"  value=" 提交 " ></td>

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