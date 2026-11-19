<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
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
<style>
body{ background-color:#fff}
</style>
</head>
<body>
<script>
function shaidan(id){
	if(confirm("确定删除该晒单")){
		window.location.href="<?php echo G_MODULE_PATH;?>/shaidan_admin/sd_del/"+id;
	}
}
</script>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
 <table width="100%" cellspacing="0">
 	<thead>
	<tr align="center">
		<th width="5%" height="30"><?php $this->lan("id");?></th>
		<th width="15%"><?php $this->lan("Order_title");?></th>
		<th width="10%"><?php $this->lan("thumbnail");?></th>
		<th width="40%"><?php $this->lan("content");?></th>
		<th width="10%"><?php $this->lan("Envy");?></th>
		<th width="10%"><?php $this->lan("Order_notice");?></th>
		<th width="10%"><?php $this->lan("operation");?></th>
	</tr>
    </thead>
    <tbody>
	<?php foreach($shaidan as $v) { ?>
	<tr align="center" class="mgr_tr">
		<td height="30"><?php echo $v['sd_id'];?></td>
		<td><?php echo _strcut($v['sd_title'],30);?></td>
		<td><img height="30" src="<?php echo $v['sd_thumbs'];?>"></td>
		<td><?php echo _strcut($v['sd_content'],50);?></td>
		<td><?php echo $v['sd_zhan'];?></td>
		<td><?php echo $v['sd_ping'];?></td>
		<td class="action"><span><a onClick="shaidan(<?php echo $v['sd_id'];?>)" href="javascript:;"><?php $this->lan("delete");?></a></span></td>		
	</tr>
	<?php } ?> 
    </tbody>
</table>
<?php if($total>$num) {?> 

<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>

<?php } ?> 	

</div><!--table_list end-->

</body>
</html> 
