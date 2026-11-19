<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php $this->lan("USDY_recharge");?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
           
            <th width="100px" align="center"><?php $this->lan("USDT_name");?></th>
            <th width="100px" align="center"><?php $this->lan("USDT_address");?></th>
            <th width="100px" align="center"><?php $this->lan("USDT_Exchange");?></th>
            <!-- <th width="200px" align="center"><?php $this->lan("USDT_code");?></th> -->
			<th width="100px" align="center"><?php $this->lan("ishide");?></th>
            <th width="100px" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($list as $val): ?>
		<tr>
			<td align="center"><?php echo $val['name']; ?></td>	
			<td align="center"><?php echo $val['bank_user']; ?></td>
			<td align="center"><?php echo number_format($val['exchange_rate']?:1,2); ?></td>	
			<!-- <td align="center" ><img height="80px" width="80px" src="<?php echo G_UPLOAD_PATH.'/'.$val['img']; ?>"/></td> -->
			<td align="center" class="layui-form">
                <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($val['status'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $val['id']; ?>" lay-text="<?php $this->lan("show");?>|<?php $this->lan("hide");?>">
            </td>	
			<td align="center">
				<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/pay/set_usdt/<?php echo $val['id']; ?>"><?php $this->lan("set_up");?></a>
				<a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/pay/del_usdt/<?php echo $val['id']; ?>"><?php $this->lan("delete");?></a>
			</td>
		</tr>
		<?php endforeach; ?>
  	</tbody>
</table>
</div><!--table-list end-->

<script>
	/*$(".sta").on('click',function(){
        console.log(1111111111111)
		var e=$(this);
		var state=e.attr('state');
		var id=e.attr('kid');
		$.post("/pay/pay/up_state",{status:state,kid:id},function(res){
			console.log(res);
			if(res == '1'){
				e.attr('color','#0c0');
				e.attr('state',res);
				e.html('是');
			}else{
				e.attr('color','#ff0000');
				e.attr('state',res);
				e.html('否');
			}
		})
	})*/
	layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            // var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = 2;
            }

            var id=$(this).attr('data-state');
            $.post('<?php echo WEB_PATH ; ?>/pay/pay/up_state',{status:zhi,kid:id},function (data) {
                data = JSON.parse(data);
                if (data == 1){
                    layer.msg('修改成功');
                }else{
                    layer.msg('修改失败');
                }
            });
        });

        form.render();

    });
</script>
</body>
</html> 