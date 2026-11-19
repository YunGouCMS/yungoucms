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

	<style type="text/css">
		tr {
			height: 40px;
			line-height: 40px
		}

		.dingdan_content {
			width: 100%;
			box-sizing: border-box;
			border: 1px solid #d5dfe8;
			background: #eef3f7;
			float: left;
			padding: 20px;
		}

		.dingdan_content li {
			float: left;
			width: 310px;
		}

		.dingdan_content_user {
			width: 100%;
			box-sizing: border-box;
			border: 1px solid #d5dfe8;
			background: #eef3f7;
			float: left;
			padding: 20px;
		}

		.dingdan_content_user li {
			line-height: 25px;
		}

		.api_b {
			width: 120px;
			display: inline-block;
			font-weight: normal;
			text-align: right;
		}

		.yun_ma {
			word-break: break-all;
			width: 200px;
			background: #fff;
			overflow: auto;
			height: 100px;
			border: 5px solid #09F;
			padding: 5px;
		}

		select {
			height: 30px;
			padding-left: 15px;
			padding-right: 15px;
			border-radius: 5px;
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.input-text {
			border-radius: 3px;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>

</head>

<body>

	<div class="header-title lr10" style="padding-left: 20px;">
		<div class="nav_icon" style="font-size: 15px;font-weight: bold;color: #000000;"><?php $this->lan("description");?></div>

	</div>

	<div class="bk10"></div>

	<div class="table-list lr10">
		<!--start-->
		<div class="bk10"></div>
		<div class="dingdan_content_user">
			<li><b class="api_b"><?php $this->lan("User_id");?></b><?php echo $user['uid']; ?></li>
			<li><b class="api_b"><?php $this->lan("User_nickname");?></b><?php echo $user['username']; ?></li>
			<li><b class="api_b"><?php $this->lan("User_mobile");?></b><?php echo $user['mobile']; ?></li>
			<li><b class="api_b"><?php $this->lan("Purchase_time");?></b><?php echo date("Y-m-d H:i:s", $go_time); ?></li>
			<li><b class="api_b"><?php $this->lan("Receipt_info");?></b><?php
											if ($user_dizhi) {
												echo $user_dizhi['sheng'] . ' - ' . $user_dizhi['shi'] . ' - ' . $user_dizhi['xian'] . ' - ' . $user_dizhi['jiedao'];
												echo "&nbsp;&nbsp;&nbsp;收货人:" . $user_dizhi['shouhuoren'];
												echo "&nbsp;&nbsp;&nbsp;电话:" . $user_dizhi['mobile'];
											} else {

												echo "<?php $this->lan('tips');?>";
											}

											?></li>

		</div>
		<div class="bk10"></div>
			<div class="dingdan_content_user">
				<?php $status = explode(",", $record['status']); ?>
				<form action="#" method="post">
					<input type="hidden" name="code" value="<?php echo $record['id']; ?>" />
					<li><b class="api_b"><?php $this->lan("Purchase_method");?></b>
						<font color="#0c0"><?php echo $record['pay_type']; ?><?php $this->lan("pay");?></font>
					</li>
					<li><b class="api_b"><?php $this->lan("current_state");?></b>
						<font color="#0c0"><?php echo $this->yh_status[$record['status']]; ?></font>
					</li>
					<li><b class="api_b"><?php $this->lan("Order_status");?></b>
						<select name="status" id='status'>
							<option value="<?php echo $record['status']; ?>"><?php echo $this->yh_status[$record['status']]; ?></option>
							<option value="2"><?php $this->lan("Not_shipped");?></option>
							<option value="3"><?php $this->lan("Already_shipped");?></option>
							<option value="4"><?php $this->lan("Completed");?></option>
							<option value="5"><?php $this->lan("Invalid");?></option>
						</select>
					</li>
					<li><b class="api_b"><?php $this->lan("logistics_company");?></b>
						<select name="company">
							<?php foreach ($logistics as $v1) { ?>
								<option value="<?php echo $v1['log_name']; ?>" <?php if ($record['company'] == $v1['log_name']) {
																					echo 'selected';
																				}; ?>><?php echo $v1['log_name']; ?></option>
							<?php } ?>
						</select>
					</li>

					<li><b class="api_b"><?php $this->lan("courier_number");?></b>&nbsp;<input type="text" name="company_code" placeholder="<?php $this->lan("Tips2");?>" value="<?php echo $record['company_code']; ?>" class="input-text wid150"></li>

					<!--<li><b class="api_b">快递运费:</b><input type="text" name="company_money" value="<?php echo $record['company_money']; ?>"  class="input-text wid150" > 元 </li>-->

					<li><b class="api_b"><?php $this->lan("shipping_cost");?></b>&nbsp;<input type="text" name="company_money" value=" <?php $this->lan("Tips3");?>" class="input-text wid150" readonly></li>

					<li class="pwd" style="display:none;"><b class="api_b">安全密码</b><input type="password" name="secure_password" placeholder="请输入安全密码" value="" class="input-text wid150"> 输入安全密码</li>
					<li><input type="submit" class="button" value="<?php $this->lan("update");?>  " name="submit" /></li>
				</form>
			</div>
			<div class="bk10"></div>
	</div><!--table-list end-->
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<script>
		$("#status").change(function(){
			if($("#status").val() == "3"){
				$(".pwd").css("display","block")
			}else{
				$(".pwd").css("display","none")
			}
		})

	</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>