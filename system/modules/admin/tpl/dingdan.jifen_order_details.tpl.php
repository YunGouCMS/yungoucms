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
			/*width: 650px;*/
			    width: 95%;
			border: 1px solid #d5dfe8;
			background: #eef3f7;
			float: left;
			padding: 20px;
		}

		.dingdan_content li {
			float: left;
			/*width: 310px;*/
		}

		.dingdan_content_user {
			/*width: 650px;*/
			width: 95%;
			border: 1px solid #d5dfe8;
			background: #eef3f7;
			/*float: left;*/
			padding: 20px;
		}

		.dingdan_content_user li {
			line-height: 25px;
		}

		.api_b {
			width: 105px;
			display: inline-block;
			font-weight: normal;
			text-align: right;
			margin-right: 10px;
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

		.exchangeod-img {
			position: relative;
			width: 80px;
			height: 80px;
			/*width: 50%;*/
			display: block;
			background: #fff;
			margin-left: -10px;
		}

		.exchangeod-img img {
			width: 90%;
		}

		.exchangebox-od {
			width: calc(100% - 20px);
			border-bottom: 1px solid #f9f7f7;
			margin: 0 10px;
		}

		.exchangebox-od {
			display: flex;
		}
	</style>
</head>

<body>
	<div class="header lr10">
		<!--<b><?php $this->lan("Order_delivery"); ?></b>-->
		<?php echo $this->headerment();?>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<!--start-->
		 <div class="dingdan_content_user">
			<ul>
				<li><b class="api_b"><?php $this->lan("Purchaser_ID"); ?></b> <?php echo $user['uid']; ?></li>
				<li><b class="api_b"><?php $this->lan("Purchaser_nick"); ?></b> <?php echo $user['username']; ?></li>
				<li><b class="api_b"><?php $this->lan("Purchaser_phone"); ?></b><?php echo $user['mobile']; ?></li>
				<li><b class="api_b"><?php $this->lan("Purchaser_time"); ?></b><?php echo date("Y-m-d H:i:s", $order['suctime']); ?></li>
				<li><b class="api_b"><?php $this->lan("Order_status"); ?></b><?php switch ($order['status']) {
																				case '2':
																					echo $this->lgs["Waiting_delivery"];
																					break;
																				case '3':
																					echo $this->lgs["Receipt_goods"];
																					break;
																				case '4':
																					echo $this->lgs["Received"];
																					break;
																				case '5':
																					echo $this->lgs["Abnormal_order_amount"];
																					break;
																				case '6':
																					echo $this->lgs["Order_creation_exception"];
																					break;
																			}
																			?>
				<li><b class="api_b"></b></li>
				<li><b class="api_b"><?php $this->lan("Order_information"); ?></b>
					<ul>
						<?php foreach ($re as $key => $val) { ?>
							<div class="exchangebox-od">
								<div class="exchangeod-img">
									<img src="/statics/uploads/<?php echo $val['s_item']['thumb']; ?>" />
								</div>
								<div class="exchangeod-in">
									<p class="con-title fl" style="width:500px"><?php echo $val['s_item']['title']; ?></p>
									<p class="con-money fr">
										<span class="gules">
											<?php echo $this->currency['symbol'];?><?php echo $val['s_item']['disc_price']; ?>
										</span>
										<span style="padding-left: 50%">x<?php echo $val['snum']; ?>
										</span>
									</p>
									<p class="con-title fl" style="width:500px"><?php $this->lan("total"); ?><?php echo $this->currency['symbol'];?><?php echo $order['money'] ?></p>
								</div>
							</div>
						<?php } ?>
						<!--<div style="text-align: right;"><?php $this->lan("total"); ?><?php echo $this->currency['symbol'];?><?php echo $order['money'] ?></div>-->
					</ul>
				</li>
			</ul>
		</div>
		<div class="bk10"></div>
		<div class="dingdan_content_user">
			<li><b class="api_b"><?php $this->lan("Consignee_name"); ?></b><?php echo $address['shouhuoren'] ?></li>
			<li><b class="api_b"><?php $this->lan("Consignee_phone"); ?></b><?php echo $address['shouhuoren'] ?></li>
			<li><b class="api_b"><?php $this->lan("Consignee_address"); ?></b><?php echo $address['sheng'] . $address['shi'] . $address['xian'] . $address['jiedao'] ?></li>
			<form action="<?php echo G_MODULE_PATH; ?>/dingdan/jifen_order_dosend" method="post">
				<li><b class="api_b" style="width:105px"><?php $this->lan("logistics_company"); ?></b>
					<select name="logistics_way">
						<option value="10" <?php if ($order['logistics_way'] == '10') {
												echo 'selected';
											} ?>><?php $this->lan("mall"); ?></option>
						<option value="1" <?php if ($order['logistics_way'] == '1') {
												echo 'selected';
											} ?>><?php $this->lan("zhong_guo"); ?></option>
						<option value="2" <?php if ($order['logistics_way'] == '2') {
												echo 'selected';
											} ?>><?php $this->lan("sheng_tong"); ?></option>
						<option value="3" <?php if ($order['logistics_way'] == '3') {
												echo 'selected';
											} ?>><?php $this->lan("yuan_tong"); ?></option>
						<option value="4" <?php if ($order['logistics_way'] == '4') {
												echo 'selected';
											} ?>><?php $this->lan("shun_feng"); ?></option>
						<option value="5" <?php if ($order['logistics_way'] == '5') {
												echo 'selected';
											} ?>><?php $this->lan("yun_da"); ?></option>
						<option value="6" <?php if ($order['logistics_way'] == '6') {
												echo 'selected';
											} ?>><?php $this->lan("tian_tian"); ?></option>
						<option value="7" <?php if ($order['logistics_way'] == '7') {
												echo 'selected';
											} ?>><?php $this->lan("zhong_tong"); ?></option>
						<option value="8" <?php if ($order['logistics_way'] == '8') {
												echo 'selected';
											} ?>><?php $this->lan("zeng_yi"); ?></option>
						<option value="9" <?php if ($order['logistics_way'] == '9') {
												echo 'selected';
											} ?>><?php $this->lan("jing_dong"); ?></option>
					</select>
				</li>
				<li>
					<b class="api_b" style="width:105px"><?php $this->lan("courier_number"); ?></b><input type="text" name="logistics_code" value="<?php echo $order['logistics_code'] ? $order['logistics_code'] : ''; ?>" placeholder="<?php $this->lan("Tips2"); ?>" class="input-text wid150">
				</li>
				<!-- <li>
					<b class="api_b">快递运费:</b>
					<input type="text" name="company_money" value="<?php echo $record['company_money']; ?>"  class="input-text wid150"> 元
				</li> -->
				<?php if ($order['status'] == '2') { ?>
					<li>
						<input type="submit" class="button" value="  <?php $this->lan("Shipping"); ?>" id="submit" />
						<input type="hidden" name="oid" value="<?php echo $order['oid']; ?>">
					</li>
				<?php } elseif ($order['status'] < 5) { ?>
					<li>
						<input type="submit" class="button" value="  <?php $this->lan("update"); ?> " id="submit" />
						<input type="hidden" name="renew" value="1">
						<input type="hidden" name="oid" value="<?php echo $order['oid']; ?>">
					</li>
				<?php } ?>
			</form>
		</div>

	</div><!--table-list end-->

	<script>
	</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>