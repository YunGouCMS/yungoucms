<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>

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

tr{height:40px;line-height:40px}

.dingdan_content{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}

.dingdan_content li{ float:left;width:310px;}

.dingdan_content_user{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}

.dingdan_content_user li{ line-height:25px;}

.api_b{width:60px; display:inline-block;font-weight:normal;text-align: right;}

.yun_ma{ word-break:break-all; width:200px; background:#fff; overflow:auto; height:100px; border:5px solid #09F; padding:5px;}

select{height: 30px; padding-left: 15px; padding-right: 15px; border-radius: 5px;margin-top: 10px; margin-bottom: 10px;}
.input-text{border-radius: 3px; margin-top: 10px; margin-bottom: 10px;}

</style>

</head>

<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
		<div class="dingdan_content_user" style="margin-left: 20px;">

			<li><b class="api_b">购买人ID：</b> <?php echo $user['uid'];?></li>

			<li><b class="api_b">购买人昵称：</b> <?php echo $user['username']; ?></li>

			<li><b class="api_b">购买人手机：</b><?php echo $user['mobile'];?></li>			

			<li><b class="api_b">购买时间：</b><?php echo date("Y-m-d H:i:s",$mobile['time']);?></li>	

            <li><b class="api_b">收货信息：</b><?php 

			if($user_dizhi){

				echo $user_dizhi['sheng'].' - '.$user_dizhi['shi'].' - '.$user_dizhi['xian'].' - '.$user_dizhi['jiedao'];

				echo "&nbsp;&nbsp;&nbsp;收货人:".$user_dizhi['shouhuoren'];
				
				echo "&nbsp;&nbsp;&nbsp;电话:".$user_dizhi['mobile'];	

			}else{

				echo "该用户未填写收货信息,请自行联系买家！";

			}

			?></li>			

		</div>			

		<div class="bk10"></div>

        

		<div class="dingdan_content_user">

			<?php $status=explode(",",$record['status']); ?>

			<form action="#" method="post">
	

			<li><b class="api_b">物流公司:</b>

			<select name="company">

				<option value="中国邮政" <?php if($record['company'] == '中国邮政'){ echo 'selected';}; ?>>中国邮政</option>

				<option value="申通快递" <?php if($record['company'] == '申通快递'){ echo 'selected';}; ?>>申通快递</option>

				<option value="圆通速递" <?php if($record['company'] == '圆通速递'){ echo 'selected';}; ?>>圆通速递</option>

				<option value="顺丰速运" <?php if($record['company'] == '顺丰速运'){ echo 'selected';}; ?>>顺丰速运</option>

				<option value="韵达快递" <?php if($record['company'] == '韵达快递'){ echo 'selected';}; ?>>韵达快递</option>

				<option value="天天快递" <?php if($record['company'] == '天天快递'){ echo 'selected';}; ?>>天天快递</option>

				<option value="中通速递" <?php if($record['company'] == '中通速递'){ echo 'selected';}; ?>>中通速递</option>

				<option value="增益速递" <?php if($record['company'] == '增益速递'){ echo 'selected';}; ?>>增益速递</option>										

                <option value="京东快递" <?php if($record['company'] == '京东快递'){ echo 'selected';}; ?>>京东快递</option>

			 </select>

			</li>

			<li><b class="api_b">快递单号:</b><input type="text" name="company_code" placeholder="请输入快递单号" value="<?php echo $record['company_code']; ?>" placeholder="请输入快递单号" class="input-text wid150"></li>
			<li><input type="submit" class="button" value="  更新  " name="submit" /></li>		

			</form>

		</div>

    

</div>

<script>

	

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 