<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
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
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>

	<div class="bk10"></div>
	<form name="" action="#" method="post" enctype="multipart/form-data">
		<table width="100%" cellspacing="0">
			<!-- <tr>
		<td width="220" align="right" style="padding-right: 10px">用户头像</td>
		<td>
            <div class="layui-row">
                <div class="layui-col-md1 width130" style="border:1px solid #fff">
					<div class="layui-upload-list height130">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif; ?>>
							<span>+</span>
							<text>点击上传</text>
						</div>
					    <img class="layui-upload-img" width="120px" src="<?php echo  G_UPLOAD_PATH . '/' . $web['photo']; ?>" id="photoimg-img">
					    <input type="hidden" id="photoimg-lack" name="photo" value="<?php echo $web['photo']; ?>">
					    <p id="demoText"></p>
						<button type="button" onclick="uploadimg('用户头像','#photoimg','image','touimg')"></button>
					</div>
                </div>
                <div class="layui-col-md9">
                    200*200Px，支持jpg、jpeg
                </div>
            </div>
        </td>
        <td></td>
        <td></td>
	</tr> -->
			<tr>
				<td align="right" style="width:120px">预设上传头像</td>
				<td>
					<div id="lunbo" style="display: flex;flex-wrap: wrap;align-items: flex-start;">
						<?php if ($list) foreach ($list as $k => $vl): ?>
							<div class='imgbox' id="parent-<?php echo $k; ?>" style="position:relative">
								<input type="hidden" id="imgHeadPhoto<?php echo $k ?>-lack" name="imgarrList[]" value="<?php echo $vl['path']; ?>" />
								<img id="imgHeadPhoto<?php echo $k ?>-img" src="<?php echo $vl['url']; ?>" onclick="uploadimg('上传预设头像','#imgHeadPhoto<?php echo $k ?>')" style="width:130px;height:130px;" />
								<i style="position: absolute;right:0;color:#3C8DBC;font-size: 20px;cursor: pointer;padding-right: 10px;" onClick="remove_keys(this)" data-parent="parent-<?php echo $k; ?>">x</i>
							</div>
						<?php endforeach; ?>
						<div class="layui-row" style="display: inline-flex;padding: 0;">
							<div class="layui-col-md1 width130">
								<div class="layui-upload-list height130">
									<div class="layui-bottom-img " id="addbanner">
										<span>+</span>
										<text>添加图片</text>
									</div>
									<p id="demoText"></p>
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<div style="margin-top: 10px;margin-left: 237px;">
			<input type="submit" class="button" name="coubmit" value="保存">
		</div>
	</form>
	<script>
		$("#addbanner").on('click', function() {
			var id = diu_Randomize(1000, 100000)
			console.log(id)
			var html = `
			<div style="display:inline-block;margin-right: 5px;position: relative;" id="parent-${id}">
                        <img  onclick="uploadimg('上传预设头像','#imgHeadPhoto${id}','image','PresetsHeadPhoto')" id="imgHeadPhoto${id}-img" width="130px" height="130px">
                         <input type="hidden" name="imgarrList[]" id="imgHeadPhoto${id}-lack" value="">
                <i style="position: absolute;right:0;color:#3C8DBC;font-size: 20px;cursor: pointer;padding-right: 10px;" onClick="remove_keys(this)" data-parent="parent-${id}">x</i>
              </div>
        `
			var bb = $("#lunbo>div:nth-last-child(1)")
			bb.before(html)
			setTimeout(() => {
				uploadimg('上传预设头像', '#imgHeadPhoto' + id, 'image', 'PresetsHeadPhoto')
			}, 100)
		})

		function diu_Randomize(b, e) {
			if (!b && b != 0 || !e) {
				return "?";
			}
			return Math.floor((Math.random() * e) + b);
		}
	</script>
</body>

</html>