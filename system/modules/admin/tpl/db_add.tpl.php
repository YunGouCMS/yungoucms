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
		.layui-upload-img{
			display: block;
		}
		.height66 {height:64px;padding: 0!important;}
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
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("name","名称");?> </td>
					<td style="display: flex;">
						<input type="text" name="title" value="<?php echo$wap['title'];?>" style="width: 113px;" placeholder="<?php $this->lan("navigation_name");?>" class="input-text wid300" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("Navigation_link");?> </td>
					<td style="display: flex;">
						<input type="text" name="link" value="<?php echo$wap['link'];?>" class="input-text wid300" style="width: 500px;" placeholder="<?php $this->lan("tips3");?>" />
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("is_cart","是否购物车");?></td>
					<td style="padding-left: 10px;display: flex;">
						<input type="radio" name="type" style="margin: 0 5px 0 0!important;" value="2" <?php echo $wap['type']==2?"checked":"";?> title="" /> <?php $this->lan("yes");?> <span style="padding: 0 5px;margin:0!important;"></span>
						<input type="radio" name="type" style="margin: 0 5px 0 0!important;" value="1" <?php echo $wap['type']!=2?"checked":"";?> /> <?php $this->lan("no");?>
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("Before_switching");?> </td>
					<td style="display: flex;">
						<div class="layui-row" style="margin:0!important;">
							<div class="layui-col-md1 width66">
								<div class="layui-upload-list height66">
									<div class="layui-bottom-img" <?if($wap['img']){?> style="opacity: 0;" <?}?>>
										<span>+</span>
										<!--<text><?php $this->lan("Click_upload");?></text>-->
									</div>
									<img class="layui-upload-img" width="46px" src="<?php echo G_UPLOAD_PATH.'/'.$wap['img'];?>" id="imgha-img">
									<input type="hidden" id="imgha-lack" name="image" value="<?php echo $wap['img'];?>">
									<p id="demoText"></p>
									<button type="button" onclick="uploadimg('上传图片','#imgha','image','banner')"></button>
								</div>
							</div>
							<div data-title="<?php $this->lan("tips4");?>" class="tip" style="margin-left:5px!important;">
								<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("After_switching");?> </td>
					<td style="display: flex;">
						<div class="layui-row" style="margin:0!important;">
							<div class="layui-col-md1 width66">
								<div class="layui-upload-list height66">
									<div class="layui-bottom-img" <?if($wap['picarr']){?> style="opacity: 0;" <?}?>>
										<span>+</span>
										<!--<text><?php $this->lan("Click_upload");?></text>-->
									</div>
									<img class="layui-upload-img" width="46px" src="<?php echo G_UPLOAD_PATH.'/'.$wap['picarr'];?>" id="imgpicarr-img">
									<input type="hidden" id="imgpicarr-lack" name="picarr" value="<?php echo $wap['picarr'];?>">
									<p id="demoText"></p>
									<button type="button" onclick="uploadimg('上传图片','#imgpicarr','image','banner')"></button>
								</div>
							</div>
							<div data-title="<?php $this->lan("tips4");?>" class="tip" style="margin-left:5px!important;">
								<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("language");?></td>
					<td width="900" style="display: flex;">
						<select name="language" style="margin:0!important;">
							<option value=""><?php $this->lan("currency");?></option>
							<?php foreach ($languages as $key => $v) { ?>
								<option value="<?php echo $v['lg_value']; ?>" <?php echo $wap['lanuage']==$v['lg_value']?"selected":"";?>><?php echo $v['lg_name']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="120" align="right"></td>
					<td style="display: flex;">
						<input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" style="margin:0!important;">
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