<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<style>
tr{height:40px;line-height:40px}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
		<!--<tr>-->
		<!--	<td width="220" align="right">客服名称：</td>-->
		<!--	<td><input type="text" name="name" value="" class="input-text">-->
		<!--	</td>-->
		<!--</tr>-->
		
		<tr>
			<td width="220" align="right"><?php $this->lan("Payment_name");?>&nbsp;&nbsp;</td>
			<td><input type="text" name="bank_user" value="" class="input-text">
			</td>
		</tr>
		<tr>
			<td width="220" align="right"><?php $this->lan("Bank_account");?>&nbsp;&nbsp;</td>
			<td><input type="text" name="bank_no" value="" class="input-text">
			</td>
		</tr>
		<tr>
			<td width="220" align="right"><?php $this->lan("Receiving_bank");?>&nbsp;&nbsp;</td>
			<td><input type="text" name="bank_name" value="" class="input-text">
			</td>
		</tr>
    
		<tr>
			<td width="220" height="80" align="right"><?php $this->lan("Bank_code");?>&nbsp;&nbsp;</td>
			<td>
            <img id="imgHeadPhoto" src="<?php echo G_UPLOAD_PATH.'/'.$pay['pay_thumb']; ?>"  style="width: 30px; height: 30px; border: solid 1px #d2e2e2;" alt="" />
			<input type="file" name="pay_thumb" onchange="PreviewImage(this,'imgHeadPhoto','divPreview');" style="width: 69px;" accept="image/png, image/jpeg, image/gif, image/jpg"/>
               <?php $this->lan("tips2");?>
			</td>
		</tr>
        <tr>
          <td width="220" align="right">
              <?php $this->lan("status");?>&nbsp;&nbsp;
          </td>
          <td>
              <?php if($info['status']==1): ?>
                  <input type="radio" name="status" value="1" checked="checked" />&nbsp;&nbsp;<?php $this->lan("show");?>
                  <input type="radio" name="status" value="0"/>&nbsp;&nbsp;<?php $this->lan("hide");?>
              <?php else: ?>
                  <input type="radio" name="status" value="1" />&nbsp;&nbsp;<?php $this->lan("show");?>
                  <input type="radio" name="status" value="0" checked="checked" />&nbsp;&nbsp;<?php $this->lan("hide");?>
              <?php endif; ?>
          </td>
      </tr>
		<tr>
          <td width="220" align="right">
              <?php $this->lan("tips");?>&nbsp;&nbsp;
          </td>
          <td>
              <?php if($pay['pay_monerange']['isinput']==1): ?>
                  <input type="radio" name="isinput" value="1" checked="checked" />&nbsp;&nbsp;<?php $this->lan("open");?>
                  <input type="radio" name="isinput" value="0"/>&nbsp;&nbsp;<?php $this->lan("close");?>
              <?php else: ?>
                  <input type="radio" name="isinput" value="1" />&nbsp;&nbsp;<?php $this->lan("open");?>
                  <input type="radio" name="isinput" value="0" checked="checked" />&nbsp;&nbsp;<?php $this->lan("close");?>
              <?php endif; ?>
          </td>
      </tr>
      <tr>
          <td width="220" align="right"><?php $this->lan("Amount_range");?>&nbsp;&nbsp; </td>
          <td>
              <input type="text" name="m[min]" value="<?php echo $fw['range']['min']?:50; ?>" class="input-text" style="width: 50px !important;">~
              <input type="text" name="m[max]" value="<?php echo $fw['range']['max']?:5000; ?>" class="input-text" style="width: 50px !important;"> <span class="error_tips"><?php $this->lan("tips1");?></span>
              <div style="margin: 10px 0 0">

                      <?php foreach($fw['ranges'] as $key=>$val): ?>
                          <?php if($key==0): ?>
                              <div>
                                <input type="text" name="ms[]" value="<?php echo $val;?>" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px;display: none;"><i class="layui-icon layui-icon-close"></i></button>
                              </div>
                          <?php else: ?>
                            <div>
                              <input type="text" name="ms[]" value="<?php echo $val;?>" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px;"><i class="layui-icon layui-icon-close"></i></button>
                            </div>
                          <?php endif; ?>
                      <?php endforeach; ?>

                  <?php if(count($fw['ranges'])<1): ?>
                      <div>
                          <input type="text" name="ms[]" value="50" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px;display: none;"><i class="layui-icon layui-icon-close"></i></button>
                      </div>
                  <?php endif; ?>

              </div>
              <button type="button" class="layui-btn layui-btn-sm zhuijia"><?php $this->lan("append");?></button>
          </td>
      </tr>
        <tr>
        	<td width="220" align="right"></td>
            <td><input type="submit" class="button" name="dosubmit"  value=" <?php $this->lan("Submit");?> " ></td>
		</tr>
</table>
</form>

</div><!--table-list end-->

<script>
	$('.zhuijia').click(function(){
		var az = '<div><input type="text" name="ms[]" value="50" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px"><i class="layui-icon layui-icon-close"></i></button></div>'
		$(this).parent().children('div').append(az)
		$('.deletediv').click(function(){
			$(this).parent().remove();
		})
	})
    $('.deletediv').click(function(){
        $(this).parent().remove();
    })
</script>
</body>
</html>
