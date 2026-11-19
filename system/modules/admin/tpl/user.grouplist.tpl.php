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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<style>
.table-list {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.butaoin, .butaish {
    padding: 6px 16px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
    margin: 0 4px !important;
}
.butaoin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #fff !important;
}
.butaoin:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}
.butaish {
    background: #fee2e2 !important;
    color: #991b1b !important;
}
.butaish:hover {
    background: #fecaca !important;
    transform: translateY(-2px) !important;
}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>

<div class="table-list lr10">
<form name="myform" action="#" method="post">
  <table width="100%" cellspacing="0" style="table-layout: fixed;">
    <thead>
		<tr>
		<th width="4%"><?php $this->lan("sort");?></th>
		<th width="32%" align="content"><?php $this->lan("group_name");?></th>
		<th width="32%" align="content"><?php $this->lan("name");?></th>
		<th width="32%"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
  	<tbody>
    	<?php foreach($grouplist as $v){ ?>
        <tr>
        <td width="4%" align="center"><?php echo $v['mid']; ?></td>
        <td width="32%" align="center"><?php echo $v['name']; ?></td>
        <td width="32%" align="center"><?php echo date("Y/m/d H:i:s",$v['time']); ?></td>
        <td width="32%" align="center">
		<?php if($v['mid']==1){ ?>
		 <font color="#cccccc"><?php $this->lan("tips");?></font>
		<?php }else{ ?>
		 <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/user/group_permission/<?php echo $v['mid']; ?>"><?php $this->lan("Permissions");?></a>
		<?php } ?>
		
		<?php if($v['mid']==1){ ?>
	   
		<?php }else{ ?>
		<a class="butaoin butaish a_form_noparam" href="<?php echo G_ADMIN_PATH.'/'.ROUTE_C; ?>/del_group/<?php echo $v['mid']; ?>"><?php $this->lan("delete");?></a>
		<?php } ?>
		</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<!-- <div id="pages"><ul><li>共 --><?php //echo $total; ?><!-- 条</li></ul></div>-->
</form>
</div><!--table-list end-->

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 