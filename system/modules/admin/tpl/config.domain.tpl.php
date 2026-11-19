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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>
<style>
	.content-box {
		margin-top: 10px;
		position: relative;
		display: flex;
		/*div:nth-child(4){*/
	}
	
	.content-box div {
	    display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
	}

	.content-box button {
		width: 60px;
		background: #3C8DBC;
		color: #fff;
		border: none;
		margin-left: -60px;
		line-height: 25px;
		border-radius: 5px;
		cursor: pointer;
	}

	.content-box input:nth-child(2) {
		width: 400px;
		height: 35px;
		line-height: 35px;
		text-indent: 5px;
		margin-left: 10px;
		cursor: pointer;
	}

	.content-box input:nth-child(3) {
		opacity: -1.5;
		position: absolute;
		top: 10px;
		left: 425px;
		width: 70px;
	}

	* {
		font-size: 12px;
		color: #444;
	}

	.radio-inline {
		margin-left: 100px;
	}

	.radio-inline~input {
		margin: -3px 5px;
	}

	.select {
		width: 60px;
		background: #3C8DBC;
		color: #fff;
		border: none;
		margin-left: -60px;
		line-height: 25px;
		border-radius: 5px;
		cursor: pointer;
	}
</style>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div style="padding:20px;font-size: 14px;">
		<form method="post" action="#" enctype="multipart/form-data" name="myform" class="layui-form">
			<?php foreach ($config as $vl) { ?>
				<div class="content-box">
				    <div style="width:200px;text-align:right"><?php $this->lan($vl['title']); ?></div>
					<input type="text" class="doc" name="effects[<?php echo $vl['key'] ?>][url]" placeholder="请上传mp3文件" value="<?php echo $vl['url'] ?>" readonly>
					<button type="button" class="select">&nbsp;<?php $this->lan("select"); ?>&nbsp;</button>
					<input type="file" class="upload" style="opacity: -1.5;position:absolute;top:5px;left:560px; width: 70px;">
					<img src="<?echo G_UPLOAD_PATH;?>/imgs/play.png" width="40" height="40" style="position:absolute;top:5px;left:615px;cursor: pointer;"  class="play" alt="试听" title="试听"
						data-src="<?php echo G_UPLOAD_PATH . $vl['url'] ?>">
					<img src="<?echo G_UPLOAD_PATH;?>/imgs/stop.png" width="40" height="40" style="position:absolute;top:5px;left:615px;display:none;cursor: pointer;" class="stop" alt="">
					
					<!-- <label for="status1<?=$vl['id']?>" class="radio-inline"><?php $this->lan("open","开启")?></label>
					<input id="status1<?=$vl['id']?>" type="radio" name="effects[<?php echo $vl['key'] ?>][status]" value="1" <?php if($vl['status'] != 2) echo 'checked';?>> -->
					
					<!-- <label for="status2<?=$vl['id']?>"><?php $this->lan("close","关闭")?></label>
					<input id="status2<?=$vl['id']?>" type="radio" name="effects[<?php echo $vl['key'] ?>][status]" value="2" <?php if($vl['status'] == 2) echo 'checked';?>> -->
					<div class="" style="position:absolute;top:5px;left:660px;cursor: pointer;">
						<input type="checkbox" 
							class="is_state" 
							<?php if ($vl['status'] == 1) {echo 'checked="checked"';} ?> 
							lay-skin="switch" 
							lay-filter="is_state" 
							data-state="<?php echo $vl['id']; ?>" 
							lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>"
						>
						<input type="hidden" id='status<?=$vl['id']?>' name="effects[<?php echo $vl['key'] ?>][status]" value="<?=$vl['status']?>">
					</div>
				</div>
			<?php } ?>
			<input style="width: 80px; height: 30px;background:#3C8DBC ;color:#fff;border:none;border-radius:5px;margin-top: 15px !important;
    margin-left: 211px !important;" type="submit" name="dosubmit" value="<?php $this->lan("Submit"); ?>" />
		</form>
	</div>

	<audio id="audioPlayer" src="http://v.yungou.test.cn/statics/uploads/muisc/20250417/91571819859456.mp3"></audio>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

<script>
	var audio = document.getElementById('audioPlayer');
	$(".play").click(function() {
		$("#audioPlayer").attr("src", $(this).attr("data-src"));
		var res = audio.play();
		console.log(res);
		$(this).hide();
		$(this).next().show();
	   audio.addEventListener('ended', function() {
	       console.log('音频播放完毕');
	       console.log($(this));
           	$(this).hide();
		    $(".play").show();
       });
	})
	$(".stop").click(function() {
		$(this).hide();
		$(".play").show();
		audio.pause();
	})
	audio.addEventListener('ended', function() {
		console.log('音频播放完毕');
		$(".stop").hide();
		$(".play").show();
	});
	audio.addEventListener('error', function(event) {
		$(".stop").hide();
		$(".play").show();
	});

	$(".upload").change(function() {
		var doc = $(this).parent().children('.doc')[0]
		var file = $(this)[0].files[0];
		if(!file) {
			return false;
		}
		var formData = new FormData();
		formData.append('file', file);
		formData.append('type', "mp3");
		formData.append('path', "music");
		// 使用$.ajax发送POST请求
		$.ajax({
			url: getDomain() + "/api/tjupload/upload", //上传接口。
			type: 'POST',
			data: formData,
			processData: false,  // 告诉jQuery不要处理发送的数据
			contentType: false,  // 告诉jQuery不要设置Content-Type请求头
			success: function(response) {
				response = JSON.parse(response)
				layer.msg('上传成功');
				doc.value = response.data.path;
				
				// 处理成功响应
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log('上传失败', textStatus, errorThrown);
				// 处理错误响应
			}
		});
	})
	layui.use(['form', 'jquery'], function() {
        var form = layui.form,
            $ = layui.jquery;
        form.render('checkbox');
		console.log('加载完成');
		form.on('switch(is_state)',function (data) {
			var id = $(this).attr('data-state');
			var name = $(this).attr('name');
			var zhi = '';
			if (data.elem.checked == true) {
				zhi = 1;
			} else if (data.elem.checked == false) {
				zhi = 2;
			}
			console.log(zhi);
			$("#status"+id).val(zhi);
		})
	})
</script>

</html>