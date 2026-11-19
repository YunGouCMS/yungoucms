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
			width: 60px;
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
		<div class="nav_icon" style="font-size: 15px;font-weight: bold;color: #000000;"><?php $this->lan("title","详情"); ?></div>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<!--start-->
		<div class="dingdan_content">
			<h3 style="clear:both;display:block; line-height:30px;"><?php echo $shop['title']; ?></h3>
			<div style="display: flex;">
				<div>用户：</div>
				<div><?echo $res['username'];?></div>
			</div>
			<div style="display: flex;">
				<div>金额：</div>
				<div><?echo $res['money'];?></div>
			</div>
			<div style="display: flex;">
				<div>银行：</div>
				<div><?php echo $res['volume']['bank_name']; ?></div>
			</div>
			<div style="display: flex;">
				<div>银行卡号：</div>
				<div><?php echo $res['volume']['bank_no']; ?></div>
			</div>
			<div style="display: flex;">
				<div>转账凭证：</div>
				<div>   <?php
                            foreach($res['picarr'] as $vl){
                                echo "<img src='$vl' height='80px' width='80px'> ";
                            }
                        ?>
						</div>
			</div>
			<div style="display: flex;">
				<div>申请时间：</div>
				<div><?echo $res['create_time'];?></div>
			</div>
		</div>
	</div><!--table-list end-->

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>