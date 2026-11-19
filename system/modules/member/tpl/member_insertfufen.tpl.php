<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
	<!-- 高级骨架屏系统样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
	<!-- 统一后台页面美化样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
	<!-- 后台通用页面增强样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
	<!-- 通知消息系统样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
	<!-- 后台各页面详细UI升级样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
	
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
	<style>
		/* 会员分润页面美化样式 */
		body {
			background: #f5f7fa;
		}
		
		.header.lr10 {
			background: #fff;
			border-radius: 12px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
		}
		
		.table_form.lr10 {
			background: #fff;
			border-radius: 12px;
			margin-bottom: 20px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
		}
		
		/* 表格美化 */
		.table_form table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
		}
		
		.table_form table th {
			border-bottom: 1px solid #e1e8ed;
			font-size: 14px;
			font-weight: 600;
			text-align: right;
			width: 200px;
			padding: 16px 12px;
			color: #333;
			background: #f8f9fa;
		}
		
		.table_form table td {
			padding: 16px 12px;
			border-bottom: 1px solid #f0f0f0;
		}
		
		.table_form table tr:last-child td {
			border-bottom: none;
		}
		
		/* 输入框美化 */
		.input-text {
			border: 1px solid #e1e8ed !important;
			border-radius: 6px !important;
			padding: 10px 12px !important;
			font-size: 14px !important;
			transition: all 0.3s ease !important;
			background: #fff !important;
		}
		
		.input-text:focus {
			border-color: #667eea !important;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
			outline: none !important;
		}
		
		/* 按钮美化 */
		.button {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			color: #fff !important;
			padding: 12px 24px !important;
			border-radius: 6px !important;
			border: none !important;
			cursor: pointer !important;
			font-size: 14px !important;
			font-weight: 500 !important;
			transition: all 0.3s ease !important;
			box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
		}
		
		.button:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
		}
		
		/* 选择框美化 */
		select {
			border: 1px solid #e1e8ed !important;
			border-radius: 6px !important;
			padding: 10px 12px !important;
			font-size: 14px !important;
			background: #fff !important;
			transition: all 0.3s ease !important;
		}
		
		select:focus {
			border-color: #667eea !important;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
			outline: none !important;
		}
		
		/* 单选框和复选框美化 */
		input[type="radio"],
		input[type="checkbox"] {
			width: 18px !important;
			height: 18px !important;
			cursor: pointer !important;
			accent-color: #667eea !important;
			margin-right: 6px !important;
		}
		
		/* rightgray 样式美化 */
		.rightgray {
			display: flex;
			align-items: center;
			width: 180px;
			gap: 8px;
			border: 0!important;
			margin: 10px 0;
		}
		
		.rightgray div {
			/*background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);*/
			color: #fff;
			border: none;
			width: 55px;
			height: 34px;
			padding: 2px 15px;
			border-radius: 6px;
			line-height: 30px;
			text-align: center;
			font-weight: 500;
			font-size: 13px;
			/*box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);*/
		}
		
		.rightgray input {
			margin: 0;
			border-radius: 6px;
			min-width: 10px;
			text-align: center;
		}
		
		/* 添加/删除按钮美化 */
		.yonjin_add,
		.yonjin_remove {
			font-size: 20px !important;
			border: 2px solid #667eea !important;
			width: 32px !important;
			height: 32px !important;
			text-align: center !important;
			line-height: 28px !important;
			border-radius: 6px !important;
			cursor: pointer !important;
			background: #fff !important;
			color: #667eea !important;
			transition: all 0.3s ease !important;
			font-weight: bold !important;
		}
		
		.yonjin_add:hover {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			color: #fff !important;
			transform: scale(1.1);
		}
		
		.yonjin_remove:hover {
			background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
			border-color: #f5576c !important;
			color: #fff !important;
			transform: scale(1.1);
		}
		
		/* 规则区域美化 */
		.rule {
			display: flex;
			font-size: 14px;
			padding: 16px 0;
			line-height: 32px;
			flex-wrap: wrap;
			align-items: center;
		}
		
		.rule div {
			padding-right: 10px;
			display: flex;
			align-items: center;
			gap: 8px;
		}
		
		.rule input {
			margin: 0 3px;
			width: 85px;
			height: 34px;
			border: 1px solid #e1e8ed;
			border-radius: 6px;
			padding: 0 10px;
			transition: all 0.3s ease;
		}
		
		.rule input:focus {
			border-color: #667eea;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
			outline: none;
		}
		
		.rule p {
			margin: 0;
			font-weight: 500;
			color: #333;
			white-space: nowrap;
		}
		
		/* ks 区域美化 */
		.ks {
			padding: 20px;
		}
		
		.ks p {
			text-align: left;
			font-size: 14px;
			color: #666;
			margin: 16px 0 8px 0;
			font-weight: 500;
		}
		
		/* 充值表格美化 */
		.rechageTable {
			margin-top: 20px;
		}
		
		.rechageTable .rightgray {
			display: flex !important;
			align-items: center;
			gap: 8px;
		}
		
		.rechageTable .rightgray div {
			width: 55px;
			/*background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);*/
			color: #fff;
			border: none;
			height: 34px;
			padding: 2px 15px;
			border-radius: 6px;
			line-height: 30px;
			font-weight: 500;
			/*box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);*/
		}
		
		.rechageTable .rightgray input {
			margin: 0;
			border-radius: 6px;
			width: 85px;
			height: 34px;
			border: 1px solid #e1e8ed;
			text-align: center;
			transition: all 0.3s ease;
		}
		
		.rechageTable .rightgray input:focus {
			border-color: #667eea;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
			outline: none;
		}
		
		#Receive .rightgray input {
			height: 32px;
		}
		
		/* 标签美化 */
		label {
			font-weight: 500;
			color: #333;
			margin: 0 4px;
		}
		
		/* 提示图标美化 */
		.tip {
			cursor: pointer;
			transition: transform 0.3s ease;
		}
		
		.tip:hover {
			transform: scale(1.1);
		}
		
		/* 响应式设计 */
		@media (max-width: 768px) {
			.table_form.lr10 {
				padding: 16px;
			}
			
			.table_form table th,
			.table_form table td {
				padding: 12px 8px;
				font-size: 13px;
			}
			
			.rule {
				flex-direction: column;
				gap: 12px;
			}
		}
		
		/* 原有样式保留并优化 */
		input.button {
			display: inline-block
		}
		
		.table_form.lr10 h5 {
			font-size: 18px;
			font-weight: 600;
			color: #333;
			margin-bottom: 20px;
			padding-bottom: 12px;
			border-bottom: 2px solid #e1e8ed;
		}
		
		/* 添加/删除元素按钮区域 */
		#inpl,
		#inputsr {
			width: 40px;
			height: 40px;
			border-radius: 8px;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: #fff;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			margin: 0 8px;
			transition: all 0.3s ease;
			box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
			font-size: 20px;
			font-weight: bold;
		}
		
		#inpl:hover,
		#inputsr:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
		}
		
		#inpl {
			background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
			box-shadow: 0 2px 4px rgba(245, 87, 108, 0.3);
		}
		
		#inpl:hover {
			box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
		}
		
		/* 分隔线美化 */
		.bk10 {
			height: 10px;
		}

		.rightgray {
			display: flex;
			align-items: center;
			width: 180px;
		}

		.rightgray div {
			background-color: #f5f5f5;
			border: 1px solid #B4B4B4;
			border-left-width: 0px;
			width: 55px;
			height: 27px;
			padding: 2px 15px;
			border-top-right-radius: 3px;
			border-bottom-right-radius: 3px;
			line-height: 28px;
			text-align: center;
		}

		.rightgray input {
			margin: 0;
			border-top-right-radius: 0;
			border-bottom-right-radius: 0;
			min-width: 10px;
			text-align: center;
		}

		.ks p {
			text-align: left;
			font-size: 12px;
			color: #444;
			margin-left: 20px;
		}

		.rule {
			display: flex;
			font-size: 12px;
			padding: 20px 0px;
			line-height: 28px;
		}

		.rule div {
			padding-right: 10px;
			display: flex;
			align-items: center;
		}

		.rule input {
			margin: 0 3px;
			/*width: 150px;*/
			width: 85px;
			height: 34px;
			border-color: #B4B4B4;
		}

		.discount {
			width: 13px !important;
		}

		/*rechageTable*/
		.rechageTable .rightgray {
			display: flex !important;
			align-items: center;
		}

		.rechageTable .rightgray div {
			width: 55px;
			background-color: #f5f5f5;
			border: 1px solid #B4B4B4;
			border-left-width: 0px;
			height: 28px;
			padding: 2px 15px;
			border-top-right-radius: 3px;
			border-bottom-right-radius: 3px;
			line-height: 28px;
		}

		.rechageTable .rightgray input {
			margin: 0;
			border-top-right-radius: 0;
			border-bottom-right-radius: 0;
			width: 85px;
			height: 34px;
			border-color: #B4B4B4;
			margin-left: 5px;
			text-align: center;
		}

		#Receive .rightgray input {
			height: 32px;
		}
		.tip:hover:after {
		    bottom: 5px!important;
		}
		.rightgray.fufen_yongjin {
		    margin: 0;
		}
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<form name="myform" action="#" method="post" enctype="multipart/form-data">
		<div class="table_form lr10">
			<!--start-->
			<table width="100%" cellspacing="0" style="border-width: 1px;">
				<tr>
					<td width="200" align="right" style="text-align: right !important;"><?php echo $symbol; ?><?php $this->lan("Deduction"); ?>&nbsp;</td>
					<td width="300" class="rightgray">
						<input type="text" name="fufen_yuan" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips5"); ?><?php echo $symbol; ?>" value="<?php echo $config['fufen_yuan']; ?>" class="input-text">
						<div><?php $this->lan("integral"); ?></div>
					</td>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("nice"); ?>&nbsp;</td>
					<td width="300" class="rightgray">
						<input type="text" name="f_overziliao" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips8"); ?>" value="<?php echo $config['f_overziliao']; ?>" class="input-text">
						<div><?php $this->lan("integral"); ?></div>
					</td>
				</tr>
				<tr>
					<td width="250" align="right" style="text-align: right !important;"><?php $this->lan("Withdrawal_fee"); ?>&nbsp;</td>
					<td width="300" class="rightgray" style="width: 600px;white-space: nowrap;">
						<input type="text" style="width: 75px;" name="fufen_yongjintx" placeholder="<?php $this->lan("tips6"); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" value="<?php echo $config['fufen_yongjintx']; ?>" class="input-text">
						<div><?php echo $symbol; ?></div>
						<!--<label for="yongjintx_pay" style="margin: 0px 5px;"><?php $this->lan("Payment_channels"); ?></label>-->
						<select name="yongjintx_pay" style="width: 250px;display:none;" id="yongjintx_pay">
							<?php foreach ($pay_lists as $vl) {
								echo '<option value="' . $vl['pay_class'] . '" ' . ($config['yongjintx_pay'] == $vl['pay_class'] ? 'selected="selected"' : '') . '>' . $vl['pay_name'] . '</option>';
							} ?>
						</select>
					</td>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("Purchase_rewards"); ?>&nbsp;</td>
					<td class="rightgray">
						<input type="text" name="f_shoppay" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips2"); ?>" value="<?php echo $config['f_shoppay']; ?>" class="input-text">
						<div><?php $this->lan("integral"); ?></div>
					</td>
				</tr>
				<tr style="position: relative;">
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("New_user_registration"); ?></td>
					<td width="300" class="rightgray">
						<input type="text" name="reg_money" placeholder="<?php $this->lan("Gift_amount"); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" class="input-text" style="width: 75px;text-align: center;" id="money" value="<?php echo $m['money']; ?>">
						</input>
						<div><?php echo $symbol; ?></div>

					</td>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("Verify_rewards"); ?>&nbsp;</td>
					<td class="rightgray">
						<input type="text" name="f_phonecode" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips2"); ?>" value="<?php echo $config['f_phonecode']; ?>" class="input-text">
						<div><?php $this->lan("integral"); ?></div>
					</td>
				</tr>
				<tr>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("Rebate_ratio"); ?>&nbsp;</td>
					<td width="300" class="" style="width:600px" >
						<div style="display: flex;width: 607px;display: flex;flex-wrap: wrap;gap: 10px;">
						    <div style="margin:0!important;display: flex;gap: 10px;">
						        <div class="yonjin_add" style="font-size:20px;border:1px solid #ccc;width: 20px;text-align: center;">+</div>
						        <div class="yonjin_remove" style="font-size:20px;border:1px solid #ccc;width: 20px;text-align: center;">-</div>
						    </div>
						    <div style="display: flex;gap: 10px;">
						        <?php if(!is_array($config['fufen_yongjin'])){$config['fufen_yongjin'] = [0];} foreach($config['fufen_yongjin'] as $k=>$v) { ?>
        						<div class="rightgray fufen_yongjin">
        							<input type="text" name="fufen_yongjin[]" style="width: 75px;text-align: center;"
        								   onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" 
        								   maxlength="5" 
        								   placeholder="<?php $this->lan("First_level_commission"); ?>" 
        								   value="<?php echo $v; ?>" 
        								   class="input-text">
        							<div style="margin-right:10px;"><?php echo $k+1;?></div>
        						</div>
        						<?php } ?>
						    </div>
						</div>
						<!-- <div class="rightgray">
							<input type="text" name="fufen_yongjin_1" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" maxlength="5" placeholder="<?php $this->lan("Second_level_commission"); ?>" value="<?php echo $config['fufen_yongjin_1']; ?>" class="input-text">
							<div><?php $this->lan("two_level"); ?></div>
						</div> -->
					</td>
				</tr>
				<tr>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("Invitation_rewards"); ?>&nbsp;</td>
					<td class="rightgray">
						<input type="text" name="f_visituser" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips2"); ?>" value="<?php echo $config['f_visituser']; ?>" class="input-text">
						<div><?php $this->lan("integral"); ?></div>
					</td>
				</tr>

				<tr>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("shaidan", "晒单奖励"); ?>&nbsp;</td>
					<td width="300" class="rightgray">
						<input type="text" name="shaidan_money" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" maxlength="5" placeholder="<?php $this->lan("tips2"); ?>" value="<?php echo $config['shaidan_money']; ?>" class="input-text">
						<!--<div style="margin-right:10px;"><?php echo $symbol; ?></div>-->
						<div><?php $this->lan("integral"); ?></div>
					</td>

					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("sign_in", "每日签到奖励"); ?>&nbsp;</td>
					<td class="rightgray">
						<input type="text" name="f_sign_in" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips2"); ?>" value="<?php echo $config['f_sign_in']; ?>" class="input-text">
						<div><?php $this->lan("integral"); ?></div>
					</td>
				</tr>
				<tr>
					<td width="200" align="right" style="text-align: right !important;"><?php $this->lan("Withdrawal_limit","提现限制"); ?>&nbsp;</td>
					<td width="300" class="rightgray" style="width:600px">
						<input type="text" name="fufen_yongjin_withdrawal_min" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" maxlength="5" placeholder="<?php $this->lan("Min_amount"); ?>" value="<?php echo $config['fufen_yongjin_withdrawal_min']; ?>" class="input-text">
						<div style="margin-right:10px;"><?php echo $symbol; ?></div>
						<input type="text" name="fufen_yongjin_withdrawal_max" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" maxlength="5" placeholder="<?php $this->lan("Max_amount"); ?>" value="<?php echo $config['fufen_yongjin_withdrawal_max']; ?>" class="input-text">
						<div style="margin-right:10px;"><?php echo $symbol; ?></div>
					</td>
					<!--<td width="120" align="right"><?php $this->lan("Invitation_rewards"); ?>&nbsp;</td>-->
					<!--<td class="rightgray">-->
					<!--	<input type="text" name="f_visituser" style="width: 75px;text-align: center;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("Invitation_rewards"); ?>" value="<?php echo $config['f_visituser']; ?>" class="input-text">-->
					<!--	<div style="margin-right:10px;"><?php echo $symbol; ?></div>-->
					<!--</td>-->
				</tr>
			</table>
			<table width="100%" class="rechageTable" cellspacing="0" style="border-width: 1px;">
				<tr>
					<td style="display:flex;align-items: center;gap: 10px;"><?php $this->lan("Recharge_gift"); ?>&nbsp;
						<input type="radio" style="margin:0!important;" name="rech[isOpen]" value="1" <?php if ($isOpen) {
																				echo 'checked';
																			} ?>> <?php $this->lan("open"); ?>
						<input type="radio" style="margin:0!important;" name="rech[isOpen]" value="0" <?php if (!$isOpen) {
																				echo 'checked';
																			} ?>> <?php $this->lan("close"); ?>
					</td>
				</tr>
				<tr style="display: flex;position:relative">
					<td class="rightgray" style="white-space: nowrap;">
						<?php $this->lan("Recharge_reached"); ?>&nbsp;<input type="text" name="rech[r_money]" style="height: 28px;" class="input-text" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');this.value=this.value.replace('.','');" value="<?php echo $r_money; ?>" required />
						<div><?php echo $symbol; ?></div>
					</td>
					<td class="rightgray" style="width:500px;position:relative;white-space: nowrap;">
						<?php $this->lan("Gift_amount"); ?>&nbsp;<input type="text" name="rech[money]" style="height: 28px;" class="input-text" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');this.value=this.value.replace('.','');" value="<?php echo $money; ?>" required />
						<div><?php echo $symbol; ?></div>
						<!--<span style="color:red;margin-left: 15px;"><?php $this->lan("Tips"); ?></span>-->
						<div data-title="<?php $this->lan("Tips"); ?>" style="position: absolute;top: 15px;left: 235px;;z-index: 100;background: white;border: none;
                            width: 0px;" class="tip">
						<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
					</div>
					</td>
					<!--<div data-title="<?php $this->lan("Tips"); ?>" style="position: absolute;top: 505px;left: 560px;z-index:100" class="tip">-->
					<!--	<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
					<!--</div>-->
				</tr>
				<tr>
					<td width="100%" style="text-align: left!important;"><?php $this->lan("Tips1"); ?>&nbsp;</td>
				</tr>
				<tr>
					<td style="display:flex;">
						<div style="display: inline-flex;margin: 0!important;gap: 10px;">
							<div id="inpl" onselectstart="return false;" onclick="delElement();"><i></i></div>
							<div id="inputsr" onselectstart="return false;" onclick="addElement();"><i></i></div>
						</div>
					</td>
				</tr>
				<tr id="Receive" style="display: flex;flex-wrap: wrap;">
					<?php foreach ($dayinfo as $key => $value): ?>
						<td class="rightgray">
							<?php $this->lan("The"); ?><?php echo $key; ?><?php $this->lan("day"); ?> <input type='text' onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" name='rech[d_money][<?php echo $key; ?>]' class="layui-input" value='<?php echo $value; ?>' required />
							<div><?php echo $symbol; ?></div>
						</td>
					<?php endforeach; ?>
					<input type="hidden" id="days" value="<?php echo $allday; ?>" />
				</tr>
			</table>
		</div>
		<div class="table_form lr10" style="border: 1px solid #E2E2E2;padding: 5px;margin-bottom: 10px;">
			<!--<h5>充值优惠规则</h5>-->
			<div class="ks">
				<div class="rule">
					<p style="margin-top: 8px!important;margin-right:20px!important;"><?php $this->lan("Recharge_coupon"); ?></p>
					<input type="radio" name="discount[status]" class="discount" id="status_1" value="1" <?php echo $discount['status'] == 1 ? "checked" : ""; ?>><label for="status_1"><?php $this->lan("open"); ?></label>
					<input type="radio" name="discount[status]" class="discount" id="status_2" value="2" <?php echo $discount['status'] == 2 ? "checked" : ""; ?>><label for="status_2"><?php $this->lan("close"); ?></label>
				</div>
				<p><?php $this->lan("New_user"); ?></p>
				<div class="rule">
					<div>
						<p><?php $this->lan("First_recharge_discount"); ?></p>
						<input type="text" name="discount[first]" value="<?php echo $discount['first'] ?>">
					</div>
					<div>
						<p><?php $this->lan("Time_limit"); ?></p>
						<input type="text" name="discount[time]" value="<?php echo $discount['time']; ?>">
					</div>
					<div>
						<p><?php $this->lan("Same_day_discount"); ?></p>
						<input type="text" name="discount[today_1]" value="<?php echo $discount['today_1']; ?>">
					</div>
				</div>
				<p><?php $this->lan("New_user_overtime"); ?></p>
				<div class="rule">
					<div>
						<p><?php $this->lan("Overtime_recharge_discount"); ?></p>
						<input type="text" name="discount[timeout]" value="<?php echo $discount['timeout']; ?>">
					</div>
					<div>
						<p><?php $this->lan("Same_day_discount"); ?></p>
						<input type="text" name="discount[today_2]" value="<?php echo $discount['today_2']; ?>">
					</div>
				</div>
				<p><?php $this->lan("Continuous_recharge"); ?></p>
				<div class="rule">
					<div class="">
						<p><?php $this->lan("Day_one"); ?></p>
						<input type="text" name="discount[add][]" value="<?php echo $discount['add'][0]; ?>">
					</div>
					<div class="">
						<p><?php $this->lan("Day_two"); ?></p>
						<input type="text" name="discount[add][]" value="<?php echo $discount['add'][1]; ?>">
					</div>
					<div class="">
						<p><?php $this->lan("Day_three"); ?></p>
						<input type="text" name="discount[add][]" value="<?php echo $discount['add'][2]; ?>">
					</div>
					<div class="">
						<p><?php $this->lan("Day_four"); ?></p>
						<input type="text" name="discount[add][]" value="<?php echo $discount['add'][3]; ?>">
					</div>
					<div class="">
						<p><?php $this->lan("Day_five"); ?></p>
						<input type="text" name="discount[add][]" value="<?php echo $discount['add'][4] ?>">
					</div>
				</div>
				<p><?php $this->lan("Interruption_rules"); ?></p>
				<div class="rule">
					<div>
						<p><?php $this->lan("Daily_deduction"); ?></p>
						<input type="text" name="discount[reduce]" value="<?php echo $discount['reduce']; ?>">
					</div>
					<div>
						<p><?php $this->lan("Minimum_discount"); ?></p>
						<input type="text" name="discount[min]" value="<?php echo $discount['min'] ?: '' ?>">
					</div>
				</div>
				<input type="submit" value=" <?php $this->lan("Submit"); ?> " name="submit" class="button" style="margin-left: 42px;">
			</div>
		</div>
	</form>
	<script>
		function delElement() {
			var days = $("#days").val();
			var d = parseInt(days) - 1;
			$("#days").val(d);
			$("#Receive td:last").remove();
		}

		function addElement() {
			var days = $("#days").val();
			if (!days) {
				days = 0;
			}
			var d = parseInt(days) + 1;
			$("#days").val(d);
			$("#Receive").append('<td class="rightgray">第 ' + d + ' 天 <input type="text" name="rech[d_money][' + d + ']" class="layui-input" value="0" required/><div>元</div></td>')

		}
		$(".yonjin_add").click(function() {
			var len = $(".fufen_yongjin").length;
			var sy = '<div style="margin-right:10px;">'+ (len+1) +'</div>';
			var tem = $('.fufen_yongjin input').last().prop('outerHTML');
			html = "<div class='rightgray fufen_yongjin'>" + tem + sy +"</div>";
			$(this).parent().append(html)
		})
		$(".yonjin_remove").click(function() {
			var len = $(".fufen_yongjin").length;
			if (len > 1) {
				$("div.fufen_yongjin:last").remove();
			}

		})
	</script>
</body>
</html>
