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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>
body{ background-color:#fff}
.tdcenter{
	text-align: center;
}
.action.butaoin.butaish {
    background: rgba(0,0,0,0)!important;
    box-shadow: none!important;
}
</style>
</head>
<body>
<script>
function hueifu(id){
	layer.confirm('确认删除', {
        btn: ['确认','取消'] //按钮
    }, function(){
		$.post("<?php echo G_MODULE_PATH;?>/shaidan/hf_del/"+id,function(data){
			data = JSON.parse(data);
			layer.msg(data.msg, {icon: 1});
			if(data.code==200){
				//延迟1秒刷新
				setTimeout(function(){
					location.reload();
				},1000);
			}else{
				location.reload();
			}
		});
    }, function(){
        layer.close(confirmindex)
    });

}
</script>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mgr_table">
	<thead>
	<tr class="thead" align="center">
		<th width="5%" height="30"><?php $this->lan("id","ID");?></th>
		<th width="10%"><?php $this->lan("Member_id","用户id");?></th>
		<th width="40%"><?php $this->lan("Reply_content","回复内容");?></th>
		<th width="10%"><?php $this->lan("Reply_time","回复时间");?></th>
		<th width="10%"><?php $this->lan("operation","操作");?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($shaidan as $v) { ?>
	<tr align="center" class="mgr_tr">
		<td height="30"><?php echo $v['id'];?></td>		
		<td><?php echo $v['sdhf_userid'].'/'.$v['username']?></td>
		<td><?php echo _strcut($v['sdhf_content'],50);?></td>
		<td><?php echo date("Y-m-d h:i",$v['sdhf_time']);?></td>
		<td class="action butaoin butaish"><span><a class="butaoin myBtn" onClick="hueifu(<?php echo $v['id'];?>)" href="javascript:;"><?php $this->lan("delete","删除");?></a></span></td>		
	</tr>
	<?php }
	if(empty($shaidan)) echo '<tr><td colspan="5" class="tdcenter">'.$this->lan("No_data").'</td></tr>';
	?> 
	</tbody>
</table>

<?php if($total>$num) {?> 

<div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>

<?php } ?> 	
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 
