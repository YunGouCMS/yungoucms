<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<style>
 td{
	width: auto;
 }
</style>
</head>
<body>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post">
<table width="100%" class="lr10" style="border:none">
    <tr style="display:flex;align-items: center;">
		<td width="120" ><?php $this->lan("Rebate_ratio"); ?></td>
        <td width="120" ><?php $this->lan("one_level");?></td>
        <td style="display: flex;align-items: center;">
            <input type="text" name="fufen_yongjin" id="fufen_yongjin" class="input-text" size="50" maxlength="8" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" value="<?php echo $res['commission']['fufen_yongjin'];?>" style="width: 100px;margin-right: 17px;">%
        </td>
        <td width="120" align="right"><?php $this->lan("two_level");?></td>
        <td style="display: flex;align-items: center;">
            <input type="text" name="fufen_yongjin_1" id="fufen_yongjin_1" class="input-text" size="50" maxlength="8" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" value="<?php echo $res['commission']['fufen_yongjin_1'];?>" style="width: 100px;margin-right: 17px;">%
        </td>
    </tr>
    <tr style="display: none;">
        <td width="120" align="right"><?php $this->lan("Invitation_rewards");?></td>
        <td>
            <input type="text" name="f_visituser" id="f_visituser" class="input-text" size="50" maxlength="8" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" value="<?php echo $res['yaoqing']['f_visituser'];?>" style="width: 250px;margin-right: 17px;">
        </td>
    </tr>
    <tr>
        <td width="120" align="right"></td>
        <td><input type="submit" class="button" name="submit"  value="<?php $this->lan("Submit");?>"></td>
    </tr>
</table>
</form>
</div><!--table-list end-->

<script>

</script>
</body>
</html> 
