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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
thead th {min-width:60px;}
</style>
</head>
<body>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<div class="header lr10">
	<?php echo $this->headerment();?>
	<span class="lr10"> </span><span class="lr10"> </span>
    <span>
    <input type ="button" value="<?php $this->lan("export");?> " name="excel_submit" style="float: right;margin-top: 5px!important;height:30px!important;" onclick="javascript:window.location.href='<?php echo G_MODULE_PATH;?>/dingdan/groupExport/<?php echo $where; ?>'" />
    </span>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th style="min-width:20px;" align="center"><input id="checkAll" type="checkbox"></th>
        	<th width="40"><?php $this->lan("Order_number");?></th>
            <th width="80"><?php $this->lan("Is_main_group");?></th>
            <th width="110" align="center"><?php $this->lan("Group_people");?></th>
            <th align="center"><?php $this->lan("Product_title");?></th>
            <th width="90"><?php $this->lan("Purchase_users");?></th>
            <th width="20" align="center"><?php $this->lan("user_id");?></th>
            <th  width="130" align="center"><?php $this->lan("Purchase_frequency");?></th>
            <th  width="120" align="center"><?php $this->lan("Total_purchase");?></th>
            <th width="90" align="center"><?php $this->lan("Purchase_date");?></th>
            <th width="80" align="center"><?php $this->lan("Order_status");?></th>
            <th width="80" align="center"><?php $this->lan("Group_status");?></th>
            <th width="30" align="center"><?php $this->lan("Is_winner");?></th>
            <th width="80" align="center"><?php $this->lan("state");?></th>
            <th width="80" align="center"><?php $this->lan("remarks");?></th>
            <th width="30" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php
                    $st = ["1"=>"<?php $this->lan('Pending_review');?>","2"=>"<?php $this->lan('adopt');?>","3"=>"<?php $this->lan('Not_passed');?>"];
        foreach($recordlist AS $v) {	?>		
            <tr>
              	<td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
                <td align="center">
                <a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">
                    <?php echo $v['code'];?><?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></a></td>
                <td align="center"><?php echo $v['pid']?"否":"是"; ?></td>
                <td align="center"><?php echo $v['group_num']; ?></td>
                <td align="left" style=" padding-left:20px">
                <a href="/mobile/mobile/item/<?php echo $v['shopids'];?>" target="_bank">
                <?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></td>
                <td align="center"><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?></td>
                <td align="center"><?php echo $v['moneycount']; ?></td>
                <td align="center"><?php echo date("Y/m/d H:i:s",$v['time']);?></td>
                <td align="center"><?php echo $this->yh_status[$v['status']];?></td>
                <td align="center"><?php echo $this->group_status[$v['group_status']];?></td>
                <td align="center"><?php echo $v['huode']?"中奖":"未中奖";?></td>
                <td align="center"><?php echo $st[$v['manage_status']];?></td>
                <td align="center"><?php echo $v['manage_desc'];?></td>
                <td align="center">
                    <?php if ($v['huode']){?><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_groupDingdan/<?php echo $v['id']; ?>"><?php $this->lan("details");?></a><?php }?>
                    <a class="butaoin butaish" href="<?php echo G_MODULE_PATH;?>/dingdan/groupDel/<?php echo $v['id']; ?>"><?php $this->lan("Delete_order","删除");?></a>
                </td>
            </tr>
            <?php } ?>
            <!-- 修改 -->
            <?php  if (!$recordlist ): ?>
            <tr>
                <td colspan="16" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
            <?php  endif; ?>
  	</tbody>
</table>
<div class="btn_paixu" style="padding-left: 0;display: flex;align-items: center;justify-content: space-between;height: 45px;">
	<input type="button" value="<?php $this->lan("Batch_deletion");?> " class="batchDel button" style="margin-bottom: 10px;">
	<div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>
</div>
</div><!--table-list end-->

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
                ids+=+id+',';	
            }
        });
    // ajax批量删除
        $.post("<?php echo G_MODULE_PATH; ?>/dingdan/groupDel",{ids:ids},function(res){
            if (res == 0) {
                // layer.msg("删除成功!");
                alert("删除成功!");
                setTimeout(function(){
                    location.reload(false);
                },1000);
                // $(window).attr('location','http://bata.yungoucms.cn/admin/content/goods_list');
            }else {
                layer.msg("删除失败!");
            
            }
        })
        ids="";
  	});
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 