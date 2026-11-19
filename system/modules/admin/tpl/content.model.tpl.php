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
</head>
<body>
<div class="header lr10">
	<h3 class="nav_icon">内容模型管理</h3><span class="span_fenge lr10"></span>
</div>

<div class="bk10"></div>

<div class="table-list lr10">
<form name="myform" action="#" method="post">
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
		<th width="10%">序号</th>
		<th width="15%" align="left">模型名</th>
		<th width="10%" align="left">数据表</th>
		</tr>
    </thead>
  	<tbody>
    	<?php foreach($models as $model){ ?>
        <tr>
        <td width="10%" align="center"><?php echo $model['modelid']; ?></td>
        <td width="15%"><?php echo $model['name']; ?></td>
        <td width="10%"><?php echo $model['table']; ?></td>
        </tr>
        <?php } ?>
	</tbody>
</table>
</form>
</div><!--table-list end-->

<script>
	
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 