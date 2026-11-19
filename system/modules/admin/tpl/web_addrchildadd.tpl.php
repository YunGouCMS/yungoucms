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
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
	<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
	<style>
		table th {
			border-bottom: 1px solid #eee;
			font-size: 12px;
			font-weight: 100;
			text-align: right;
			width: 200px;
		}

		table td {
			padding-left: 10px;
		}

		input.button {
			display: inline-block
		}

		.bg {
			background: #fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px
		}

		.color_window_td a {
			float: left;
			margin: 0px 10px;
		}

		.nav_icon {
			font-weight: bold;
		}
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table_form lr10">
		<!--start-->
		<form name="myform" action="#" method="post" enctype="multipart/form-data">
			<table width="100%" cellspacing="0" style="border:none">
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Superior","上级");?></td>
					<td style="display:flex;">
						<?php echo $res['name']?>
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("name","名称");?></td>
					<td style="display:flex;">
						<input type="text" name="name" value="<?php echo isset($recod['name'])?$recod['name']:""; ?>" placeholder="<?php $this->lan("name","名称");?>" class="input-text wid300" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("code","代码");?></td>
					<td style="display:flex;">
						<input type="text" name="code" value="<?php echo isset($recod['code'])?$recod['code']:""; ?>" placeholder="<?php $this->lan("code","代码");?>" class="input-text wid300" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("remarks","备注");?></td>
					<td style="display:flex;">
						<input type="text" name="desc" value="<?php echo isset($recod['desc'])?$recod['desc']:""; ?>" placeholder="<?php $this->lan("desc","备注");?>" class="input-text wid300" />
					</td>
				</tr>
				<?php if($pid){ foreach ($languages as $vl) { ?>
					<tr>
						<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php echo $vl['lg_name'] ?>值</td>
						<td style="display:flex;">
							<input type="text" name="value[<?php echo $vl['lg_value']; ?>][value]" value="<?php echo isset($recod['value'])?$recod['value'][$vl['lg_value']]['value']:""; ?>" placeholder="请输入值" class="input-text wid300" />
							<input type="hidden" name="value[<?php echo $vl['lg_value']; ?>][name]" value="<?php echo isset($recod['value'])?$recod['value'][$vl['lg_value']]['name']:$vl['lg_name']; ?>" />
						</td>
					</tr>
				<?php }} ?>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"></td>
					<td style="display:flex;">
						<input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit");?>"style="margin:0!important;">
					</td>
				</tr>
			</table>
	</div>
	</form>
	</div><!--table-list end-->
	<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
	<script>
		function upImage() {
			return document.getElementById('imgfield').click();
		}
	</script>
	<script type="text/javascript">
		function set_title_color(color) {
			$('#title2').css('color', color);
			$('#title_style_color').val(color);
		}

		function set_title_bold() {
			if ($('#title_style_bold').val() == 'bold') {
				$('#title_style_bold').val('');
				$('#title2').css('font-weight', '');
			} else {
				$('#title2').css('font-weight', 'bold');
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