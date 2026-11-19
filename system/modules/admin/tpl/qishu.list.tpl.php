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
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
<style>
body{ background-color:#fff}
</style>
</head>
<body>
<script>
function qishu(id){
	if(confirm("确定删除该晒单")){
		window.location.href="<?php echo G_MODULE_PATH;?>/qishu/qishu_del/qishu/"+id;
	}
}

</script>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
 <table width="100%" cellspacing="0" style="border-left: 0px;border-right: 0px;">
     	<thead>
        		<tr style="border-left: 1px solid #E2E2E2;border-right: 1px solid #E2E2E2;">
                  	<th width="4%" align="center"><input id="checkAll" type="checkbox"></th>
                    <th width="5%"><?php $this->lan("ID");?></th>        
                    <th width="38%"><?php $this->lan("Product_title");?></th>    
                    <th width="8%"><?php $this->lan("Belonging_column");?></th>             
                    <th width="9%"><?php $this->lan("participated_total");?></th>
                    <th width="6%"><?php $this->lan("Unit_price");?></th>
                    <th width="5%"><?php $this->lan("Number_of_periods");?></th>
                    <th width="6%"><?php $this->lan("popularity");?></th>
                    <th width="8%"><?php $this->lan("Time_limited");?></th>
                    <th width="11%"><?php $this->lan("operation");?></th>
				</tr>
        </thead>
        <tbody style="text-align:center">				
        	<?php foreach($qishu as $v) { ?>
            <tr style="border-left: 1px solid #E2E2E2;border-right: 1px solid #E2E2E2;">
              	<td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
                <td><?php echo $v['id'];?></td>
                <td>第(<?php echo $v['qishu']; ?>)期 <span style="<?php echo $v['title_style']; ?>">
                <a target="_blank" href="<?php echo WEB_PATH; ?>/mobile/mobile/item/<?php echo $v['id'];?>">
                    <?php echo _strcut($v['title'],100);?>
                </a></span></td>
                <td><a href="<?php echo G_ADMIN_PATH; ?>/mobile/mobile/item/<?php echo $v['cateid']; ?>"><?php echo $cate_name; ?></a></td>
                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font>/<?php echo $v['zongrenshu'];?></td>
                <td><?php echo $v['yunjiage'];?></td>
                <td><?php echo $v['qishu'];?></td>
                <td><?php	if($v['renqi']==1){	echo '<font color="#ff0000">人气</font>';}else{echo "未设置";}?></td>
                <td><?php
					if($v['q_end_time']){
						$v['q_user'] = unserialize($v['q_user']);
						echo '<font color="#0c0">'.$this->lgs['userbuylist_Has'].'</font>';
						echo "<br>";
						echo "<a href='".WEB_PATH.'/uname/'.$v['q_uid']."'".get_user_name($v['q_user'])."</a>";
					}else{
						echo $this->lgs['userbuylist_no'];
					}
					?>
				</td>
                <td class="action">
                <!-- <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v['id'];?>">修改</a> -->
				<!-- <span class="span_fenge lr5">|</span> -->
                <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/content/goods_go_one/<?php echo $v['id'];?>"><?php $this->lan("Round_upwards");?></a>
                <!--<span class="span_fenge lr5">|</span>-->
                 <a class="butaoin butaish" href="javascript:;" data-dels="<?php echo $v['id'];?>" class="dels"><?php $this->lan("delete");?></a>
				</td>
            </tr>
            <?php } ?>
			<!-- <tr style="margin-left: -1px;"><td style="border: 0;border: 0;text-align: left;padding-left: 0px;margin-left: 0px;"><input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDel  button" style="margin-bottom: 10px;"></td></tr> -->
        </tbody>
		
     </table>     

<div id="pages" style="display: flex;align-items: center;justify-content: space-between;"><div><input type="button" value="<?php $this->lan("Batch_deletion");?>" class="batchDel  button" style="margin-bottom: 10px;"></div><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>

</div><!--table_list end-->
  <script type="text/javascript">
  
   $("#checkAll").on('change', function () {
    if ($(this).is(":checked")) { // 全选
        $(".checkOne").prop("checked",true);
    } else { // 反选
        $(".checkOne").prop("checked",false);
    }
	});
    $('.dels').on('click',function () {
        var id = $(this).data('dels');
        $.post('<?php echo G_MODULE_PATH; ?>/content/qishugoods_del1',{id:id},function (data) {
            var data = JSON.parse(data);
            if (data.code == 1) {
                layer.msg(data.msg,{icon:1});
                setTimeout(function(){
                    window.location.href = "<?php echo G_MODULE_PATH; ?>/qishu/qishu_list/"+<?php echo $shopid;?>;
                },1500);
            }else {
                layer.msg(data.msg,{icon:2});
            }
        });
    });
	$('.batchDel').click(function(){
         //获取所有选中的CheckBox的id
     var  ids="";
     $(".checkOne").each(function(i) {
       if($(this).prop('checked')){
       var id= $(this).attr("data-id");
        if(ids){
            ids = ids + ','
        }
		ids+=+id;	
       }
	// ajax批量删除
    });
    // console.log(ids);
      $.ajax({
        	type: 'POST',
        	url: '<?php echo G_MODULE_PATH; ?>/qishu/qishu_del',
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

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 
