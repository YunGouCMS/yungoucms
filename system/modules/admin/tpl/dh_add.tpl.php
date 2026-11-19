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
	<title>后台首页 </title>
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
	<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
	<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js?v=1.0"></script>
	<script type="text/javascript">
		var editurl = Array();
		editurl['editurl'] = '<?php echo G_PLUGIN_PATH; ?>/ueditor/';
		editurl['imageupurl'] = '<?php echo G_ADMIN_PATH; ?>/ueditor/upimage/';
		editurl['imageManager'] = '<?php echo G_ADMIN_PATH; ?>/ueditor/imagemanager';
	</script>
	<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.all.min.js"></script>
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
	<style>
		.bg {
			background: #fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px
		}

		.color_window_td a {
			float: left;
			margin: 0px 10px;
		}

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

		.poishch {
			display: inline;
		}

		.uploadarr_box {
			width: 130px;
			margin-left: 0;
			position: relative;
		}

		.uploadarr_box i {
			right: 0;
			top: 0;
			background: rgba(0, 0, 0, .3) url(/statics/templates/teyunbao/images/mobile/setIcon.png) no-repeat -64px -126px;
			position: absolute;
		}

		#slist>div input:first-child {
			width: 103px;
		}
	</style>
</head>

<body>

	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table_form lr10">
		<form name="myform" action="#" method="post" enctype="multipart/form-data">
			<table width="100%" cellspacing="0" style="border:none">
				<tr>
					<td width="120" align="right"><?php $this->lan("Navigation_code");?> </td>
					<td>
						<!--<input type="text" name="code" value="" style="width: 500px;" placeholder="请输入导航代码" class="input-text wid300" />-->
						<select name="code">
							<option value=""><?php $this->lan("tips");?></option>
							<option value="kefu"><?php $this->lan("Service_code");?></option>
							<option value="yaoqing"><?php $this->lan("Invitation_code");?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="120" align="right"><?php $this->lan("navigation_name");?> </td>
					<td>
						<input type="text" name="title" value="" style="width: 113px;" placeholder="<?php $this->lan("navigation_name");?>" class="input-text wid300" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right"><?php $this->lan("Navigation_link");?> </td>
					<td>
						<input type="text" name="link" value="" class="input-text wid300" style="width: 500px;" placeholder="<?php $this->lan("tips3");?>" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right"><?php $this->lan("isApp");?> </td>
					<td style="padding-left: 10px">
						<input type="radio" name="type" style="margin-top: -3px;" value="2" title="" /> <?php $this->lan("yes");?> <span style="padding: 0 5px;"></span>
						<input type="radio" name="type" style="margin-top: -3px;" value="1" checked='checked' /> <?php $this->lan("no");?>
					</td>
				</tr>
				<tr>
					<td width="120" align="right"><?php $this->lan("img");?></td>
					<td>
						<div class="layui-row">
							<div class="layui-col-md1 width66">
								<div class="layui-upload-list height66">
									<div class="layui-bottom-img">
										<span>+</span>
										<!--<text><?php $this->lan("Click_upload");?></text>-->
									</div>
									<img class="layui-upload-img a123" width="46px" src="" id="imgha-img">
									<input type="hidden" id="imgha-lack" name="image" value="">
									<p id="demoText"></p>
									<button type="button" onclick="uploadimg('上传图片','#imgha','image','banner')"></button>
								</div>
							</div>
							<!--<div class="layui-col-md9">-->
							<!--    100*100px，支持Png-->
							<!--</div>-->
							<div data-title="<?php $this->lan("tips4");?>" class="tip">
								<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right" style="width:120px"><?php $this->lan("language");?></td>
					<td width="900">
						<select name="language">
							<option value=""><?php $this->lan("currency");?></option>
							<?php foreach ($languages as $key => $v) { ?>
								<option value="<?php echo $v['lg_value']; ?>"><?php echo $v['lg_name']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" style="width:120px"><?php $this->lan("Add_image");?></td>
					<td width="900">
						<!-- <div class="layui-upload" style="width:60%">
				<button type="button" class="layui-btn layui-btn-sm" onclick="uploadFiles('展示图片上传','picarr','#demo2','image','banner')">
					上传 <i class="layui-icon">&#xe681;</i>
				</button>
				<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
					<div class="layui-upload-list" id="demo2" style="height: 150px;">

					</div>
					<div id="imginput"></div>
				</blockquote>
			  </div> -->
						<div id="lunbo" style="display: flex;flex-wrap: wrap;align-items: flex-start;">
							<!-- <?php foreach ($photo as $k => $v) { ?>
                          <div style="display: inline-block;margin-right: 5px;">
                              <img  onclick="uploadimg('下载页轮播图','#imgHeadPhoto<?php echo $k; ?>','image','banner')" id="imgHeadPhoto<?php echo $k; ?>-img" width="200px" src="<?php echo G_UPLOAD_PATH . '/' . $v; ?>">
                              <input type="hidden" name="banner[]" id="imgHeadPhoto<?php echo $k; ?>-lack" value="<?php echo $v; ?>">
                          </div>
                        <?php } ?> -->
							<div class="layui-row" style="display: inline-flex;padding: 0;">
								<div class="layui-col-md1 width130">
									<div class="layui-upload-list height130">
										<div class="layui-bottom-img">
											<span>+</span>
											<text><?php $this->lan("Add_image");?></text>
										</div>
										<p id="demoText"></p>
										<!--<button type="button" class="layui-btn" id="addbanner"></button>-->
										<input type="file" id="file-upload" multiple style="opacity: 0;height: 100%;position: absolute;top: 0;width: 100%;" />
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right" style="width:120px"><?php $this->lan("Show_word");?></td>
					<td width="900">
						<div style="display:flex;align-items:end">
							<button class="add_keys layui-btn-sm layui-btn" type="button" onClick="add_keys('slist','dh_key')"><?php $this->lan("add");?></button>

							<div data-title="版本号key:edition;描述key:desc;安卓下载地址key:apk_upload;ios下载地址key:ios_upload;" style="margin-left:5px" class="tip">
								<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
							</div>
						</div>
						<div id="slist" style="margin-top:5px;">
							<div id="dh_key" class="layui-row " style="margin-top:5px;display:none;">
								<?php $this->lan("key");?>
								<input type="text" data-name="data[dh_key][key]" value="" class="input-text">
								<?php $this->lan("value");?>
								<input type="text" data-name="data[dh_key][value]" value="" class="input-text ">
								<button type="button" onclick="remove_keys(this)" class="layui-btn-sm layui-btn" style="display: none; background:red;"><?php $this->lan("delete");?></button>
							</div>

						</div>
					</td>
				</tr>
				<tr>
					<td width="120" align="right"></td>
					<td>
						<input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>">
					</td>
				</tr>
			</table>
		</form>
	</div>
	<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
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
	<script>
		function upImage() {
			return document.getElementById('imgfield').click();
		}

		$("#addbanner").on('click', function() {
			var id = diu_Randomize(1000, 100000)
			console.log(id)
			var html = `
         <div class="uploadarr_box" style="display: inline-block;margin-right: 5px;position: relative;">
                    <i class="poishch" onclick="deletefile(this)"></i>
                    <img  onclick="uploadimg('上传商品图片','#imgHeadPhoto${id}','image','banner')" id="imgHeadPhoto${id}-img" width="130px" height="130px" src="/statics/uploads/admanage/mr.jpg">
                     <input type="hidden" name="picarr[]" id="imgHeadPhoto${id}-lack" value="">
          </div>
    `
			var bb = $("#lunbo>div:nth-last-child(1)")
			bb.before(html)
			// $("#lunbo").append(html)

			setTimeout(() => {
				uploadimg('上传商品图片', '#imgHeadPhoto' + id, 'image', 'banner')
			}, 100)
		})
		$('#file-upload').on('change', function(e) {
			var files = e.target.files;
			for (var i = 0; i < files.length; i++) {
				var formData = new FormData();
				formData.append('filedata', files[i]);
				$.ajax({
					url: getDomain() + "/app/ajax/uploadImg", // 替换为你的上传处理URL
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(data) {
						if (data.code != 200) return
						var id = diu_Randomize(1000, 100000)
						var html = `
                <div class="uploadarr_box" style="display: inline-block;margin-right: 5px;position: relative;" id="parent-${id}">
                    <img id="imgHeadPhoto${id}-img" width="130px" height="130px">
                        <input type="hidden" name="imgarrList[]" id="imgHeadPhoto${id}-lack" value="">
                    <i class="poishch" onclick="deletefile(this)" data-parent="parent-${id}"></i>
                  </div>
            `
						var bb = $("#lunbo>div:nth-last-child(1)")
						bb.before(html)
						console.log('图片上传成功:', data.data);
						$(`#imgHeadPhoto${id}-complete`).val(data.data.url)
						$(`#imgHeadPhoto${id}-lack`).val(data.data.path)
						$(`#imgHeadPhoto${id}-img`).attr('src', data.data.url)
					},
					error: function() {
						console.log('图片上传失败');
					}
				});
			}
		});

		function diu_Randomize(b, e) {
			if (!b && b != 0 || !e) {
				return "?";
			}
			return Math.floor((Math.random() * e) + b);
		}
	</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>