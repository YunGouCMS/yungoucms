<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
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
		tbody tr {
			line-height: 30px;
			height: 30px;
		}
		table {
			border: none !important;
			/* 去除整个表格的边框 */
			border-collapse: collapse !important;
			/* 防止表格单元格之间出现重复的边框 */
		}

		td,
		th {
			border: none !important;
			/* 去除表格单元格和表头的边框 */
		}
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<form name="myform" id="myform" action="#" method="post" class="layui-form">
			<table width="100%" cellspacing="0">
				<tr style="display: flex;height:220px">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("Analytics");?>
						<textarea name="google_statistics_url" placeholder="<?php $this->lan("Analytics");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:220px"><?php echo $system_config['google_statistics_url']; ?></textarea>
					</td>
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("app");?>
						<textarea name="google_statistics_content" placeholder="<?php $this->lan("app");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:220px"><?php echo $system_config['google_statistics_content']; ?></textarea>
					</td>
				</tr>
				<tr style="display: flex;height:220px">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("guge");?>
						<textarea name="google_statistics_android" placeholder="<?php $this->lan("guge");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:220px"><?php echo $system_config['google_statistics_android']; ?></textarea>
					</td>
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("guge_ios");?>
						<textarea name="google_statistics_ios" placeholder="<?php $this->lan("guge_ios");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:220px"><?php echo $system_config['google_statistics_ios']; ?></textarea>
					</td>
				</tr>
				<tr style="display: flex;height:220px">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("Push_statistics");?>
						<textarea name="google_statistics_push" placeholder="<?php $this->lan("Push_statistics");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:220px"><?php echo $system_config['google_statistics_push']; ?></textarea>
					</td>
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("Other_statistics");?>
						<textarea name="google_statistics_push" placeholder="<?php $this->lan("Other_statistics");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:220px"><?php echo $system_config['google_statistics_push']; ?></textarea>
					</td>
					<!-- <td></td> -->
				</tr>
				<tr style="display: flex;height:120px;">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("Facebook_id");?>
						<textarea name="facebook_pixel_id" placeholder="<?php $this->lan("Facebook_id");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;">
						<?php $this->lan("Facebook_api");?>
						<textarea name="facebook_api_id" placeholder="<?php $this->lan("Facebook_api");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:600px; height:50px"><?php echo $system_config['facebook_api_id']; ?></textarea>
					</td>
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						<?php $this->lan("Kwai");?>
						<textarea name="Kwai_pixel_id" placeholder=" <?php $this->lan("Kwai");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['Kwai_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						<?php $this->lan("Tiktok");?>
						<textarea name="Tiktok_pixel_id" placeholder="<?php $this->lan("Tiktok");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['Tiktok_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						<?php $this->lan("Gtag");?>
						<textarea name="Gtag_pixel_id" placeholder=" <?php $this->lan("Gtag");?> " autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['Gtag_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						<?php $this->lan("Liftoff");?>
						<textarea name="Liftoff_pixel_id" placeholder=" <?php $this->lan("Liftoff");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['Liftoff_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						<?php $this->lan("customize");?>
						<textarea name="customize_pixel_id" placeholder="<?php $this->lan("customize");?>" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['customize_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
			</table>
			<div style="margin-top: 10px;margin-left: 237px;">
				<input type="submit" class="button" name="coubmit" value="<?php $this->lan("Submit");?>">
			</div>
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