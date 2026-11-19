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
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
		<a href="<?php echo G_MODULE_PATH; ?>/upload/lists">根目录</a>
		<span class="span_fenge lr5">|</span>
		<a href="javascript:history.go(-1);">返回上一页</a>
		<span class="span_fenge lr5">|</span>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<!--start-->
		<table width="100%" cellspacing="0" style="display: none;">
			<thead>
				<tr>
					<th width="500px" align="center">文件名</th>
					<th width="300px" align="center" colspan="2">类型</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ($arr as $v) { ?>
					<tr>
						<td align="center">

							<?php if ($v['type'] == '文件') { ?>
								<img src="<?php echo $v['url']; ?>" width="50px" alt="">
							<?php } ?>
							<a href="<?php echo $v['url']; ?>"><?php echo $v['name']; ?></a>
						</td>
						<td align="center">
							<?php echo $v['type']; ?>

						</td>
						<td>
							<?php echo $v['tip'] ? "【" . $v['tip'] . "】" : ""; ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<form name="myform" id="myform" action="#" method="post" class="layui-form">
			<table width="100%" cellspacing="0" style="border-style:none;">
				<tr style="display: flex;height:120px">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						谷歌统计
						<textarea name="google_statistics_url" placeholder="请填写谷歌统计地址" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:650px; height:100px"><?php echo $system_config['google_statistics_url']; ?></textarea>
					</td>
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						APP个推代码
						<textarea name="google_statistics_content" placeholder="请填写APP个推的代码" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:650px; height:100px"><?php echo $system_config['google_statistics_content']; ?></textarea>
					</td>
				</tr>
				<tr style="display: flex;height:120px">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						谷歌安卓统计
						<textarea name="google_statistics_android" placeholder="请填写谷歌安卓统计" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:650px; height:100px"><?php echo $system_config['google_statistics_android']; ?></textarea>
					</td>
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						谷歌苹果统计
						<textarea name="google_statistics_ios" placeholder="请填写苹果统计" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:650px; height:100px"><?php echo $system_config['google_statistics_ios']; ?></textarea>
					</td>
				</tr>
				<tr style="display: flex;height:120px">
					<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						推送统计
						<textarea name="google_statistics_push" placeholder="请填写谷歌推送统计" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:650px; height:100px"><?php echo $system_config['google_statistics_push']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
				    	<td width="800" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Facebook_pixel_id
						<textarea name="facebook_pixel_id" placeholder="请填写facebook_pixel_id" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Facebook转换api-id
						<textarea name="facebook_pixel_id" placeholder="请填写转换api-id" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:650px; height:100px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<td></td>
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Facebook_pixel_id
						<textarea name="facebook_pixel_id" placeholder="请填写facebook_pixel_id" autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Kwai 像素id
						<textarea name="facebook_pixel_id" placeholder="请填写Kwai 像素id " autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Tiktok 像素id
						<textarea name="facebook_pixel_id" placeholder="请填写Tiktok 像素id " autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Gtag 像素id
						<textarea name="facebook_pixel_id" placeholder="请填写Gtag 像素id " autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						Liftoff 像素id
						<textarea name="facebook_pixel_id" placeholder="请填写Liftoff 像素id " autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
				<tr style="display: flex;height:120px">
					<td width="1000" align="right" style="padding: 10px 0 0 10px;display: flex;flex-direction: column;align-items: flex-start;border-style:none">
						自定义渠道标 像素id
						<textarea name="facebook_pixel_id" placeholder="请填写自定义渠道标 像素id " autocomplete="off" class="input-text fairy-tag-input tag2" style="width:260px; height:20px"><?php echo $system_config['facebook_pixel_id']; ?></textarea>
					</td>
					<!--<td></td>-->
				</tr>
			</table>
			<div style="margin-top: 10px;margin-left: 237px;">
				<input type="submit" class="button" name="coubmit" value="保存">
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