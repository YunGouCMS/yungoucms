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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>

<style>
	.qiniu{

	}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>
<form name="" action="#" method="post" enctype="multipart/form-data">
<table width="100%" cellspacing="0">	
	<tr>
		<td width="220" align="right" style="padding-right: 10px"><?php $this->lan("tips");?></td>
		<td>
            <select name="upload_type" id="upload_type">
                <option value="1" <?php if($data['upload_type'] == 1){echo "selected";}?>><?php $this->lan("local");?></option>
                <option value="2" <?php if($data['upload_type'] == 2){echo "selected";}?>><?php $this->lan("Seven_cloud");?></option>
            </select>
        </td>
	</tr>
	<tr>
		<td width="220" align="right" style="padding-right: 10px"><?php $this->lan("configuration");?></td>
		<td></td>
	</tr>
	<tr >
        <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("AccessKey");?></td>
		<td>
		    <input type="text" value="<?php echo $data['qiniu_AccessKey']?>" name="qiniu_AccessKey" placeholder="<?php $this->lan("tips1");?>" class="input-text wid200">
        </td>
	</tr>
	<tr>
		<td width="220" align="right" style="padding-right: 10px"><?php $this->lan("SecretKey");?></td>
		<td>
		    <input type="text" value="<?php echo $data['qiniu_SecretKey']?>" name="qiniu_SecretKey" placeholder="<?php $this->lan("tips2");?>" class="input-text wid200">
        </td>
	</tr>
	<tr>
        <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Warehouse_name");?></td>
		<td>
		    <input type="text" value="<?php echo $data['qiniu_warehouse']?>" name="qiniu_warehouse" placeholder="<?php $this->lan("tips3");?>" class="input-text wid200">
        </td>
	</tr>
	<tr>
		<td width="220" align="right" style="padding-right: 10px"><?php $this->lan("customize");?></td>
		<td>
		    <input type="text" value="<?php echo $data['qiniu_domain']?>" name="qiniu_domain" placeholder="<?php $this->lan("tips4");?>" class="input-text wid200">
        </td>
        <td></td>
        <td></td>
	</tr>
</table>
<div style="margin-top: 10px;margin-left: 237px;">
	<input type="submit" class="button" name="coubmit" value="<?php $this->lan("Submit");?>" >
</div>
</form>
<script>
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 