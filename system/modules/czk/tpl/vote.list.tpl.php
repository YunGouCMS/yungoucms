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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>

</head>
<body>
<script>
function vote(id){
	 
	if(confirm("确定删除该吗")){
		
		window.location.href="<?php echo G_MODULE_PATH;?>/vote_admin/del?id="+id;
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
		<th width="4%" align="center"><input id="checkAll" type="checkbox"></th>
		<th width="4%" height="30"><?php $this->lan("ID");?></th>
		<th width="14%"><?php $this->lan("name");?></th>
		<th width="14%"><?php $this->lan("password");?></th>
		<th width="14%"><?php $this->lan("num");?></th>
		<th width="14%"><?php $this->lan("type");?></th>
		<th width="14%"><?php $this->lan("status");?></th>
		<th width="14%"><?php $this->lan("operation");?></th>
	</tr>
    </thead>
    <tbody>
	<?php foreach($vote as $v) { ?>
	<tr align="center" class="mgr_tr">
		<td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
		<td ><?php echo $v['id'];?></td>
		<td ><?php echo $v['czknum'];?></td>
		<td ><?php echo $v['password'];?></td>
		<td ><?php echo $v['mianzhi'];?></td>
		<td ><?php if($v['type']==1){echo $this->lan('disposable');}elseif($v['type']==2){echo $this->lan('experience');}?></td>
                <td ><?php if($v['status']==1){echo $this->lan('Not_used') ;}else{echo $this->lan('Used') ;};?></td>
		 
		 
		<td class="action">
		<!--<span>[<a href="<?php echo G_MODULE_PATH;?>/vote_admin/vote_total/<?php echo $v['vote_id'];?>">统计</a>]</span>
		<span>[<a href="<?php echo G_MODULE_PATH;?>/vote_admin/vote_update/<?php echo $v['vote_id'];?>">修改</a>]</span>-->
		<span><a class="butaoin butaish" href="<?php echo G_MODULE_PATH;?>/vote_admin/del/id/<?php echo $v['id'];?>"><?php $this->lan("del");?></a></span></td>
	</tr>
	<?php } ?> 
	<?php  if (!$vote ): ?>
        <tr>
            <td colspan="8" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
        </tr>
    <?php  endif; ?>
    </tbody>
</table>
	<div>
		<input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDel button" style="margin-bottom: 10px;margin-top: 10px;">
	</div>
    <div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('three','li'); ?></ul></div>

</div><!--table_list end-->

</body>
<script>
	$("#checkAll").on('change', function () {
		if ($(this).is(":checked")) { // 全选
			$(".checkOne").prop("checked",true);
		} else { // 反选
			$(".checkOne").prop("checked",false);
		}
	});
	
	$('.batchDel').click(function(){
         //获取所有选中的CheckBox的id
     	var  ids="";
     	$(".checkOne").each(function(i) {
			if($(this).prop('checked')){
				var id= $(this).attr("data-id");
				if(ids == ""){
					ids=id;
				}else{
					ids += ','+id;
				}
			}
		});
		// ajax批量删除
    // console.log(ids);
      	$.ajax({
            type: 'POST',
            url: '<?php echo G_WEB_PATH;?>/czk/vote_admin/batch_del',
            data: {ids: ids},
            dataType: 'json',
            success: function (data) {
                     if (data == 0) {
                            layer.msg("删除成功!");
                            setTimeout(function(){
                                location.reload(false);
                            },1500);
                      // $(window).attr('location','http://bata.yungoucms.cn/admin/content/goods_list');
                    }else {
                         layer.msg("删除失败!");
                     
                    }
                }
            });
        ids="";
    });
</script>
</html> 
