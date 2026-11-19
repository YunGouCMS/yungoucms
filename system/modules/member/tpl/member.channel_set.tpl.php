<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
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
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<style>

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

		.rightgray {
			display: flex;
			align-items: center;
			width: 300px;
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
</style>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment();?> </div>
<div class="bk10"></div>
<form name="myform" action="#" method="post" enctype="multipart/form-data">
		<div class="table_form lr10">
			<!--start-->
			<table width="100%" cellspacing="0" style="border-width: 1px;">
                <tr>
                    <td width="120" align="right"><?php $this->lan("Rebate_ratio"); ?>&nbsp;</td>
                    <td width="300" class="rightgray" style="width:600px">
                        <input type="number" name="fufen_yongjin" style="width: 75px;text-align: center;" min="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" maxlength="5" placeholder="<?php $this->lan("First_level_commission"); ?>" value="<?php echo $channel['fufen_yongjin']; ?>" class="input-text">
                        <div style="margin-right:10px;"><?php $this->lan("one_level"); ?></div>
                        <input type="number" name="fufen_yongjin_1" style="width: 75px;text-align: center;" min="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" maxlength="5" placeholder="<?php $this->lan("Second_level_commission"); ?>" value="<?php echo $channel['fufen_yongjin_1']; ?>" class="input-text">
                        <div><?php $this->lan("two_level"); ?></div>
                    </td>
                    <td width="120" align="right"><?php $this->lan("Invitation_rewards"); ?>&nbsp;</td>
                    <td class="rightgray">
                        <input type="number" name="f_visituser" style="width: 75px;text-align: center;" min="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,'').replace(/(\..*)\./g,'$1').replace(/^\./g,'')" placeholder="<?php $this->lan("tips2"); ?>" value="<?php echo $channel['f_visituser']; ?>" class="input-text">
                        <div><?php $this->lan("integral"); ?></div>
                    </td>
                </tr>
            </table>
            <div class="bk15"></div>
            <div class="btn">
                <input type="submit" value="<?php $this->lan("Submit"); ?>" class="button">
            </div>
        </form>
<!--table-list end-->
<script>
</script>
</body>
</html>
