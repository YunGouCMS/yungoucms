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
		<!--<h3 class="nav_icon"><?php $this->lan("title"); ?></h3>-->
		<!--<span class="span_fenge lr10"></span>-->
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table_form lr10">
		<!--start-->
		<form name="myform" action="#" method="post" enctype="multipart/form-data">
			<table width="100%" cellspacing="0" style="border:none">
				<tr>
					<td width="120" align="right"><?php $this->lan("slide_name"); ?> </td>
					<td style="display:flex;">
						<input type="text" name="title" value="<?php echo $wapone['title']; ?>" placeholder="<?php $this->lan("tips1"); ?>" class="input-text wid300" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right"><?php $this->lan("slide_link"); ?> </td>
					<td style="display:flex;">
						<input type="text" name="link" value="<?php echo $wapone['link']; ?>" placeholder="<?php $this->lan("tips2"); ?>" class="input-text wid300" />
					</td>
				</tr>
				<tr>
                    <td width="120" align="right"><?php $this->lan("background_color"); ?></td>
                    <td style="display: flex;">
                        <div id="test-banner" style="margin: 0!important;"></div>
                        <input type="hidden" name="bg_color" id="bg_color" value="<?php echo $wapone['bg_color']; ?>">
                    </td>
                </tr>
				<!--<tr>-->
				<!--	<td align="right" width="120">背景颜色：</td>-->
				<!--	<td>-->
				<!--	    <input  type="text" id="title_style_color" name="title2" value="<?php echo $wapone['color']?>" onKeyUp="return gbcount(this,100,'texttitle2');"  class="input-text wid300">-->
    <!--			    <script src="<?php echo G_GLOBAL_STYLE;?>/global/js/colorpicker.js"></script>-->
    <!--				    <img src="<?php echo G_GLOBAL_STYLE;?>/global/image/colour.png" width="15" height="16" onClick="colorpicker('title_colorpanel','set_title_color');" style="cursor:hand"/>-->
    <!--				    <img src="<?php echo G_GLOBAL_STYLE;?>/global/image/bold.png" onClick="set_title_bold();" style="cursor:hand"/>-->
    <!--				    <span class="lr10">还能输入<b id="texttitle2">100</b>个字符</span>-->
    <!--				</td>-->
		  <!--      </tr>-->
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
									<img class="layui-upload-img" width="190px" src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $wapone['img']; ?>" id="imgha-img">
									<input type="hidden" id="imgha-lack" name="upimage" value="<?php echo $wapone['img']; ?>">
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
					<td width="120" align="right"><?php $this->lan("state"); ?></td>
					<td>
						<div style="display:flex;align-items: center;margin-top: 4px;">
							<div style="margin:0!important;display: flex;white-space: nowrap;align-items: center;gap: 10px;"> <input style="min-width:13px;width:13px" type="radio" name="status" value="1" <?php if ($wapone['status'] == 1) {
																													echo 'checked';
																												} ?> class="input-text"><?php $this->lan("open"); ?></div>
							<div style="margin:0!important;display: flex;white-space: nowrap;align-items: center;gap: 10px;padding-left: 10px">
								<input style="min-width:13px;width:13px;" type="radio" name="status" value="2" <?php if ($wapone['status'] == 2) {
																													echo 'checked';
																												} ?> class="input-text"><?php $this->lan("close"); ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="120" align="right"><?php $this->lan("Jump_method"); ?></td>
					<td>
						<div style="display:flex;align-items: center;margin-top: 4px;">
							<div style="margin:0!important;display: flex;white-space: nowrap;align-items: center;gap: 10px;">
								<input style="min-width:13px;width:13px" type="radio" name="pop_window" value="1" <?php if ($wapone['pop_window'] == 1) {
																														echo 'checked';
																													} ?> class="input-text"><?php $this->lan("New_window"); ?>
							</div>
							<div style="margin:0!important;display: flex;white-space: nowrap;align-items: center;gap: 10px;padding-left: 10px">
								<input style="min-width:13px;width:13px;" type="radio" name="pop_window" value="2" <?php if ($wapone['pop_window'] == 2) {
																														echo 'checked';
																													} ?> class="input-text"><?php $this->lan("No_window"); ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right" style="width:120px"><?php $this->lan("language"); ?></td>
					<td width="900" style="display:flex;">
						<select name="language" style="margin:0!important;">
							<option value="" <?php if ($wapone['language'] == '') {
													echo 'selected';
												} ?>><?php $this->lan("currency"); ?></option>
							<?php foreach ($languages as $key => $v) { ?>
								<option value="<?php echo $v['lg_value']; ?>" <?php if ($wapone['language'] == $v['lg_value']) {
																					echo 'selected';
																				} ?>><?php echo $v['lg_name']; ?></option>
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
	<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
	<script>
		function upImage() {
			return document.getElementById('imgfield').click();
		}
		layui.use(['colorpicker'], function() {
            var olorpicker = layui.colorpicker;
            olorpicker.render({
                elem: "#test-banner",
                color: "<?php echo $wapone['bg_color']; ?>",
                format: 'hex',
                done: function(color) {
                    $("#bg_color").val(color)

                    // $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:1},function (res) {
                    //     var result = JSON.parse(res)
                    //     if (result.code ==1){
                    //         layer.msg('修改成功')
                    //         return
                    //     }
                    //     layer.msg('修改失败')
                    // })
                }
            });
        });
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