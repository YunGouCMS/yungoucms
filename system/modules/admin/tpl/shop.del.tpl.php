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
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">
<style>
body{ background-color:#fff}
tr{ text-align:center}
.examine{
    border: none;
}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
    <a style="float:right; margin-right:10px;background-color:red" class="exportBtn" href="javascript:;" onclick="confirm('您真的确定要删除吗？\n\n请确认！','<?php echo G_MODULE_PATH; ?>/content/goods_del_all')"><b><?php $this->lan("Empty_lists");?></b></a>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
        <table width="100%" cellspacing="0">
     	<thead>
        		<tr>
                    <th width="4%"><?php $this->lan("id");?></th>        
                    <th width="41%"><?php $this->lan("Product_title");?></th>             
                    <th width="10%"><?php $this->lan("participated_total");?></th>
                    <th width="5%"><?php $this->lan("Unit_price");?></th>
                    <th width="10%"><?php $this->lan("Number_periods");?></th>
                    <th width="10%"><?php $this->lan("popularity");?></th>
                    <!--<th width="10%"><?php $this->lan("Time_limited");?></th>-->
                    <th width="12%"><?php $this->lan("operation");?></th>
				</tr>
        </thead>
        <tbody>				
        	<?php foreach($shoplist?:[] as $v) {  ?>
            <tr>
                <td><?php echo $v['id'];?></td>
                <td><span style="<?php echo $v['title_style']; ?>"><?php echo _strcut($v['title'],0,25);?></span></td>
                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>
                <td><?php echo $v['yunjiage'];?></td>
                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>
                <td><?php
					if($v['renqi']==1){
						echo '<font color="#ff0000">人气</font>';
					}else{
						echo "否";	
					}
					?>
				</td>
                <td class="action">
                <?php if($v['uid'] && $v['status']==1){?>
                    <button class="butaoin examine" data-id="<?php echo $v['examine_id']?>"><?php $this->lan("examine");?></button>
                <?}else{?>
                    <a class="butaoin a_form_noparam"
                        href="<?php echo G_MODULE_PATH; ?>/content/goods_del_key/<?php echo $v['id'];?>/yes/<?php echo $v['qishu'];?>"
                        data-confirm="<?php $this->lan("confirm");?>"
                        data-cancel="<?php $this->lan("cancel");?>"
                        >
                        <?php $this->lan("revoke");?>
                    </a>
                <?}?>
				<a class="butaoin butaish a_form_noparam" style="margin-left:5px"
                    href="<?php echo G_MODULE_PATH; ?>/content/goods_del_key/<?php echo $v['id'];?>/no/<?php echo $v['qishu'];?>"
                    data-confirm="<?php $this->lan("confirm");?>"
                    data-cancel="<?php $this->lan("cancel");?>" >
                    <?php $this->lan("thorough");?>
                </a>
				</td>
            </tr>
            <?php } ?>
        </tbody>
     </table>
    	<div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>
</div>
<script>
    $(".examine").click(function(){
        var id = $(this).data('id');
        var confirmindex = layer.confirm('审核', {
            btn: ['通过','拒绝'] //按钮
            }, function(){
                examine(id,1)
                layer.close(confirmindex)
            }, function(){
                examine(id,2)
                layer.close(confirmindex)
            });
    })
    function examine(id, status=1){
        var url = "<?php echo G_MODULE_PATH; ?>/content/goods_add_examine/"+id;
        $.post(url,{status:status},function(data){
            data = JSON.parse(data);
            if(data.code == 200){
                layer.msg("成功");
                setTimeout(function(){
                    location.reload();
                },1000)
            }else{
                layer.msg("失败");
            }
        })
    }
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 