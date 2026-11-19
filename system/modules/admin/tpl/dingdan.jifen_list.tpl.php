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
</style>
</head>
<body>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<div class="header lr10">
	<?php echo $this->headerment();?>
	<span class="lr10"> </span><span class="lr10"> </span>

    <span>
    <input type ="button" value="<?php $this->lan("export");?> " name="excel_submit" class="exportBtn" style="float: right;margin-top: 5px!important;height:30px!important;" onclick="javascript:window.location.href='<?php echo G_MODULE_PATH;?>/dingdan/yhdd_export/<?php echo $where; ?>'" />
    </span>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width="60px" align="center"><input id="checkAll" type="checkbox"></th>
        	<th width="200px" align="center"><?php $this->lan("Order_number");?></th>
            <th align="center"><?php $this->lan("Purchase_users");?></th>
            <th align="center"><?php $this->lan("user_id");?></th>
            <th width="250px" align="center"><?php $this->lan("Order_date");?></th>
            <th align="center"><?php $this->lan("Total_order_price");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("Payment_amount");?></th>
            <th width="250px" align="center"><?php $this->lan("Payment_date");?></th>
            <!-- <th align="center">审核状态</th>
            <th align="center">审核备注</th> -->
            <th align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php 
        foreach($recordlist AS $v) {	?>		
            <tr>
              	<td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
                <td align="center"><?php echo $v['code']; ?></td>              
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></td>
                <td align="center"><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo date("Y/m/d H:i:s",$v['cretime']);?></td>
                <td align="center"><?php echo $v['money']; ?></td>
                <td align="center"><?php echo $this->jifen_status[$v['status']];?></td>
                <td align="center"><?php echo $v['remoney']; ?></td>
                <td align="center"><?php echo $v["suctime"]?date("Y/m/d H:i:s",$v['suctime']):'';?></td>
                <!-- <td align="center"><?php echo $st[$v['manage_status']];?></td>
                <td align="center"><?php echo $v['manage_desc'];?></td> -->
                <td align="center">
                    <a class="butaoin" href="<?php echo G_MODULE_PATH;?>/dingdan/jifen_details/<?php echo $v['id']; ?>"><?php $this->lan("details");?></a>
                    <?php if(!$del){ switch ($v['status']) {
                        case '2':
                            // echo '<a class="butaoin" href="' . G_MODULE_PATH . '/dingdan/jifen_order_send/' . $v['oid'] . '">发货</a>';
                            break;
                        case '3':
                            echo '<a class="butaoin" url="' . G_MODULE_PATH . '/dingdan/jifen_order_reget/' . $v['oid'] . '" id="sh" onclick="sh()">确认收货</a>';
                            break;
                        case '6':
                            echo '<a class="butaoin" url="' . G_MODULE_PATH . '/dingdan/jifen_order_add/' . $v['oid'] . '" id="bc" onclick="bc()">补充订单</a>';
                            break;
                        }}
                    echo '<a class="butaoin butaish" style="background:red" url="' . G_MODULE_PATH . '/dingdan/jifen_order_del/' . $v['oid'] . '" id="sc" onclick="sc()">'.$this->lan("Delete_order","delete order",2).'</a>';
                    ?>
                </td>
            </tr>
            <?php } ?>
            <!-- 修改 -->
            <?php  if (!$recordlist ): ?>
            <tr>
                <td colspan="10" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
            <?php  endif; ?>
  	</tbody>
</table>
<div class="btn_paixu" style="padding-left: 0;display: flex;align-items: center;justify-content: space-between;height: 45px;">
	<input type="button" value=" <?php $this->lan("Batch_deletion");?>" class="batchDel button" style="margin-bottom: 10px;">
	<div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>
</div>
</div><!--table-list end-->

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
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
                ids+=+id+',';	
            }
        });
    // ajax批量删除
        $.post("<?php echo G_MODULE_PATH; ?>/dingdan/jifen_order_del",{ids:ids},function(res){
            if (res == 0) {
                // layer.msg("删除成功!");
                alert("删除成功!");
                setTimeout(function(){
                    location.reload(false);
                },1000);
            }else {
                layer.msg("删除失败!");
            
            }
        })
        ids="";
  	});

function sh() {
    var r = confirm('是否确认收货');
    var url = $('#sh').attr('url');
    if(r){
        // console.log('确定')
        window.location.href=url;
    }
}
function sc() {
    var r = confirm('是否确认删除');
    var url = $('#sc').attr('url');
    if(r){
        // console.log('确定')
        window.location.href=url;
    }
}
function bc() {
    var r = confirm('是否确认收货');
    var url = $('#bc').attr('url');
    if(r){
        // console.log('确定')
        window.location.href=url;
    }
}
</script>
</html> 