<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<style>
	.layui-btn-normal {
	    background-color: #000000;
	}
    .btnborder{
        border:1px solid #E2E2E2;
    }
    .layui-form-checkbox[lay-skin=primary]:hover i{
        border-color: #3c8dbc !important;
    }
    .layui-form-checked[lay-skin=primary] i {
    border-color: #3c8dbc !important;
    background-color: #3c8dbc;
    color: #fff;
    }
	.layui-input-block {
		display: flex;
		align-items: center;
		margin-left: 0;
		padding: 0 15px;
	}
	.layu_flex {
		display: flex;
	}
	. {
		flex-direction: column;
	}
	.layui-form-label {
		width: auto;
		text-align: left;
		padding: 0 15px;
		display: flex;
		align-items: center;
		font-size: 13px;
	}
	. .layui-form-label {
		padding-top: 15px;
		padding-top: 10px;
	}
	.layui-input-block textarea {
		min-height: 35px;
		width: 100%;
		padding: 5px;
	}
	.input-text {
		width: 100%;
		margin: 0;
	}
</style>
</head>
<body>
<div >
    <form id="ban" name="myform"  class="layui-form" action="#" lay-filter="example">
        <div class="layu_flex" style="margin-top: 10px;">
			<label class="layui-form-label"><?php $this->lan("Renew_ID");?></label>
            <div class="layui-input-block">
				<input type="hidden" class="input-text" name="uid" value="<?php echo $uid;?>" id="uid">
                <?php echo $uid;?>
            </div>
        </div>
		<div class="layu_flex">
			<label class="layui-form-label"><?php $this->lan("Renew_status");?></label>
		    <div class="layui-input-block">
				<select name="status">
					<option value="1" content="正常"><?php $this->lan("Renew_normal");?></option>
					<option value="2" content="封禁"><?php $this->lan("Renew_Ban");?></option>
				</select>
		    </div>
		</div>
		<div class="layu_flex ">
			<label class="layui-form-label"><?php $this->lan("Renew_Ban_reason");?></label>
		    <div class="layui-input-block">
				<textarea name="desc" placeholder="违规词" value=""></textarea>
		    </div>
		</div>
		<div class="layu_flex ">
			<label class="layui-form-label"><?php $this->lan("Renew_Ban_time_day");?></label>
		    <div class="layui-input-block">
				<input type="text" name="days" id="days" value="" placeholder="1" class="input-text ban_time" required>
		    </div>
		</div>
		<div class="layu_flex ">
			<label class="layui-form-label"><?php $this->lan("Renew_Ban_time");?></label>
		    <div class="layui-input-block">
				<input type="date" name="start_day" id="start_day" value="" placeholder="年-月-日" class="input-text ban_time" required>
				<input type="time" name="start_time" value="" placeholder="" class="input-text">
		    </div>
		</div>
		<div class="layu_flex ">
			<label class="layui-form-label"><?php $this->lan("Renew_Unblocking_time");?></label>
		    <div class="layui-input-block">
				<input type="date" name="end_day" id="end_day" value="" placeholder="年-月-日" class="input-text ban_time" required>
				<input type="time" name="end_time" value="" placeholder="" class="input-text">
		    </div>
		</div>
        <div class="layui-form-item">
            <div class="layui-input-block" style="margin-top: 10px;">
                <input type="button" value="<?php $this->lan("Renew_Submit");?>" class="button btnborder"  id="LAY-component-form-getval" >
            </div>
        </div>
    </form>
</div>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<script>
	$('#LAY-component-form-getval').on('click', function(){
		var data = $('#ban').serialize();
		$.post('<?php echo G_MODULE_PATH; ?>/member/ban',data,function (res) {
			var result = JSON.parse(res)
			if (result.code==200){
				setTimeout(()=>{
					var index = parent.layer.getFrameIndex(window.name);
					//关闭窗口
					parent.layer.close(index);
				},1500)
			}else {
				layer.msg(result.msg)
			}
		})
	});
	$(".ban_time").change(function(){
		if($(this).attr("name") == "days"){
			var days = $(this).val();
			$("#end_day").val(formatDate($.now()/1000+days*24*60*60));
		}else{
			var start_day = $("#start_day").val();
			var end_day = $("#end_day").val();
			//结束时间不能小于开始时间
			if(end_day < start_day){
				layer.msg('结束时间不能小于开始时间');
				$(this).val('');
				return false;
			}
			var days = getDateDiff(start_day,end_day);
			$("#days").val(days);
		}
	});
	var time = $.now();
	var date = formatDate(time/1000);
	//设置当前日期
	$('#start_day').val(date);
</script>
</body>
</html>
