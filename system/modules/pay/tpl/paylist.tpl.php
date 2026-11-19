<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>支付方式</title>
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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.layui-form-switch {
    margin-top: 0;
}
.btn_paixu {padding: 6px 12px 12px 0px;}
</style>
</head>
<body>
<!-- <div class="header-title lr10">
	<b>支付方式</b> 
	<span class="lr10"> | </span> 
	<a href="<?php echo WEB_PATH; ?>/pay/pay/pay_bank" style="color:#ff0000; font-weight:bold"> 支付银行选择 </a> 
	<span class="lr10"> | </span>
	<!--<a href="<?php echo WEB_PATH; ?>/pay/pay/pay_install" style="color:#0c0; font-weight:bold"> 安装新的支付方式 </a>
</div> -->
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<form name="myform" action="#" method="post" style="height:35px;margin-top:3px;">
  <table width="100%" cellspacing="0" style="border: 0;">	
        <tr>
          <td width="83" align="right" style="padding-right: 10px"> <?php $this->lan("Payment_order_prefix");?></td>
          <td style="display: flex;">
              <input style="width:100px;text-align:center;" type="text" name="pay_prefix" value="<?php echo $web['pay_prefix']; ?>" placeholder="<?php $this->lan("tips");?>" id="payInput" class="input-text" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" maxlength="3" >
              <input type="submit" class="button" name="dosubmit" style="margin: 0 0 0 10px!important;" value="<?php $this->lan("Submit");?>" >
          </td>
        </tr>
    </table>
</form>

<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form action="#" method="post" name="myform">
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width=""><?php $this->lan("sort");?></th>
            <th width=""><?php $this->lan("id");?></th>
            <th width="" align="center"><?php $this->lan("Payment_name");?></th>
            <th width="" align="center"><?php $this->lan("Payment_image");?></th>
            <th  width="" align="center"><?php $this->lan("Payment_introduction");?></th>
<!--            <th width="100px" align="center">排序</th>-->
            <th width="" align="center"><?php $this->lan("state");?></th>
            <th width="" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
      
    <tbody>

		<?php foreach($paylist as $pay): ?>
		<tr>
            <td align="center">
                <input type='text' size='3' style="border: 0 !important;text-align: center !important;" class='input-text-c' name="sort[<?php echo $pay['pay_id']; ?>]" value="<?php echo $pay['sort']; ?>">
            </td>
			<td align="center"><?php echo $pay['pay_id']; ?></td>	
			<td align="center"><?php echo $pay['pay_name']; ?></td>	
			<td align="center"><img height="40px" width="80px" src="<?php echo G_UPLOAD_PATH.'/'.$pay['pay_thumb']; ?>"/></td>
			<td align="center"><?php echo $pay['pay_des']; ?></td>
<!--            <td align="center">--><?php //echo $pay['sort']; ?><!--</td/>-->
            <!--<td align="center">-->
            <!--    <?php if($pay['pay_start'] == 1){ ?>-->
            <!--        <font color='#0c0'>启用</font>-->
            <!--    <?php }else{ ?>-->
            <!--        <font color='#ff0000'>关闭</font>-->
            <!--    <?php } ?>-->
            <!--</td>-->
            <td align="center"  class="layui-form">
		        <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($pay['pay_start'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $pay['pay_id'] ?>" lay-text="<?php $this->lan("Enable");?>|<?php $this->lan("close");?>">
			</td>
			<td align="center">
                <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/pay/pay_set/<?php echo $pay['pay_id']; ?>"><?php $this->lan("set_up");?></a>
                <a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/pay/pay_del/<?php echo $pay['pay_id']; ?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
            </td>
		</tr>
		<?php endforeach; ?>
  	</tbody>
     
</table>
 </form>
    <div class="btn_paixu">
        <div style="width:80px;">
            <input type="button" class="button" value=" <?php $this->lan("sort");?>"
                   onclick="myform.action='/pay/pay/paylistorder';myform.submit();"/>
        </div>
    </div>
</div><!--table-list end-->

<script>
function syncInput() {
  var inputVal = document.getElementById('payInput').value;
  document.getElementById('displayText').innerText = inputVal;
}
layui.use(['form','jquery'],function () {
    var form = layui.form,
        $ = layui.jquery;

    form.render('checkbox');
    form.on('switch(is_state)',function (data) {
        var id = $(this).attr('data-state');
        var zhi = '';
        if (data.elem.checked == true) {
            zhi = 1;
        }else if(data.elem.checked == false){
            zhi = 0;
        }
        $.post('<?php echo WEB_PATH ; ?>/pay/pay/up_paystatus',{id:id,status:zhi},function (data) {
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