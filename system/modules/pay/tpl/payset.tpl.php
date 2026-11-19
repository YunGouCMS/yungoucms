<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php $this->lan("title");?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css" >
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<style>
tr{height:40px;line-height:40px}
.layuputimg {
    border: 1px dashed #B4B4B4;
    width: 100px;
    height: 100px;
    position: relative;
    display: flex;
    align-items: center;
}
.layuputimg img {width: 100%;}
.layuputimg input {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
}
tr td:first-child {padding-right: 10px;}
.textcenter input {text-align: center;}
.layui-btn.zhuijia {background-color: #3c8dbc;position:absolute;bottom: 16px;left: 360px;}
.textcenter {position: relative;}
.textcenter div input {width: 225px;}
.wid300{
    width: 300px;
}
.layui-btn.zhuijia {
    bottom: 10px;
    left: 10px;
}
.table-list tr td {border: 0;}
</style>
</head>
<body>
<div class="header lr10">
	<h3 class="nav_icon"><?php $this->lan("title");?></h3>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
		<tr>
			<td width="220" align="right" style="text-align: right!important;"> <?php $this->lan("Payment_name");?></td>
			<td style="display: flex;"><input type="text" name="pay_name" value="<?php echo $pay['pay_name']; ?>" placeholder="<?php $this->lan("Payment_name");?>" class="input-text">
			</td>
		</tr>

		<tr>
			<td width="220" height="80" align="right" style="text-align: right!important;"> <?php $this->lan("image");?></td>
			<td>
                <div class="layui-upload">
                    <div class="layui-row">
                        <div class="layui-col-md1 width130" style="margin: 0!important;">
                            <div class="layui-upload-list height130">
                                <div class="layui-bottom-img">
                                    <span>+</span>
                                    <text><?php $this->lan("Click_upload");?></text>
                                </div>
                                <img class="layui-upload-img" width="120px" src="<?php echo $pay['pay_thumb']?G_UPLOAD_PATH."/".$pay['pay_thumb']:'';?>"  id="imgha-img">
                                <input type="hidden" id="imgha-lack" name="thumb" value="<?php echo $pay['pay_thumb']?:'';?>">
                                <p id="demoText"></p>
                                <button type="button" onclick="uploadimg('上传背景图片','#imgha','image','pay')"></button>
                            </div>
                        </div>
                    </div>
                </div>
    			<!-- <div class="layuputimg">
    			    <img id="imgHeadPhoto" src="<?php echo G_UPLOAD_PATH.'/'.$pay['pay_thumb']; ?>" alt="" />
        			<input type="file" name="pay_thumb" onchange="PreviewImage(this,'imgHeadPhoto','divPreview');" size="20" accept="image/png, image/jpeg, image/gif, image/jpg"/>
    			</div> -->
			</td>
		</tr>

		<tr>
			<td width="220" align="right" style="text-align: right!important;"><?php $this->lan("introduction");?> </td>
			<td style="display: flex;">
			    <input type="text" name="pay_des" value="<?php echo $pay['pay_des']; ?>" placeholder="<?php $this->lan("introduction");?>" class="input-text">
			</td>
		</tr>
		<tr>
			<td width="220" align="right" style="text-align: right!important;"> <?php $this->lan("state");?></td>
			<td style="display: flex;gap: 10px;align-items: center;">
			<?php if($pay['pay_start']==1): ?>
			<input type="radio" style="margin:0!important;" name="pay_start" value="1" checked="checked" /><?php $this->lan("open");?>
			<input type="radio" style="margin:0!important;" name="pay_start" value="0"/><?php $this->lan("close");?>
			<?php else: ?>
			<input type="radio"  style="margin:0!important;" name="pay_start" value="1" /><?php $this->lan("open");?>
			<input type="radio" style="margin:0!important;" name="pay_start" value="0" checked="checked" /><?php $this->lan("close");?>
			<?php endif; ?>
			</td>
		</tr>

		<?php foreach($pay['pay_key'] as $key=>$val): ?>
		<tr>
			<td width="220" align="right" style="padding-right:5px;text-align: right!important;"><?php $this->lan($val['name'],$val['name']); ?></td>
			<td style="display: flex;"><input class="input-text wid300" placeholder="请输入<?php echo $val['name']; ?>" value="<?php echo $val['val']; ?>" name="pay_key[<?php echo $key; ?>]" /></td>
		</tr>
		<?php endforeach; ?>
      <?php  $fw = $pay['pay_monerange']; ?>
      <tr>
          <td width="220" align="right" style="text-align: right!important;">
              <?php $this->lan("amount");?>
          </td>
          <td style="display: flex;gap: 10px;align-items: center;">
              <?php if($pay['pay_monerange']['isinput']==1): ?>
                  <input type="radio" style="margin:0!important;" name="isinput" value="1" checked="checked" /> <?php $this->lan("open");?>
                  <input type="radio" style="margin:0!important;" name="isinput" value="0"/> <?php $this->lan("close");?>
              <?php else: ?>
                  <input type="radio" style="margin:0!important;" name="isinput" value="1" /> <?php $this->lan("open");?>
                  <input type="radio" style="margin:0!important;" name="isinput" value="0" checked="checked" /> <?php $this->lan("close");?>
              <?php endif; ?>
          </td>
      </tr>
      <tr>
          <td width="220" align="right" style="text-align: right!important;"><?php $this->lan("Amount_range");?> </td>
          <td class="textcenter" style="padding-bottom: 60px !important;">
              <div style="display: flex;align-items: center;gap: 10px;">
                  <input type="number" name="m[min]" value="<?php echo $fw['range']['min']?:50; ?>" class="input-text" style="width: 50px !important;">~
              <input type="number" name="m[max]" value="<?php echo $fw['range']['max']?:5000; ?>" class="input-text" style="width: 50px !important;"> <span class="error_tips" style="margin: 0!important;">
                  <!--<?php $this->lan("tips4");?>-->
                  <div style="display:inline-block;width:200px"  data-title="<?php $this->lan("tips4");?>" class="tip" >
                                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin: 5px 0 0 0!important;">
                            </div>
                  </span>
              </div>
              <div style="margin: 10px 0 0" class="marnext">

                      <?php foreach($fw['ranges'] as $key=>$val): ?>
                          <?php if($key==0): ?>
                              <div style="display: flex;">
                                <input type="number" name="ms[]" value="<?php echo $val;?>" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px 10px!important;display: none;"><i class="layui-icon layui-icon-close"></i></button>
                              </div>
                          <?php else: ?>
                            <div style="display: flex;">
                              <input type="number" name="ms[]" value="<?php echo $val;?>" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px 10px!important;"><i class="layui-icon layui-icon-close"></i></button>
                            </div>
                          <?php endif; ?>
                      <?php endforeach; ?>

                  <?php if(count($fw['ranges'])<1): ?>
                      <div style="display: flex;">
                          <input type="number" name="ms[]" value="50" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px 10px!important;display: none;"><i class="layui-icon layui-icon-close"></i></button>
                      </div>
                  <?php endif; ?>

              </div>
              <button type="button" class="layui-btn layui-btn-sm zhuijia"><?php $this->lan("append");?></button>
          </td>
      </tr>
      <tr <?php if(!$pay['pay_input']){?> style="display: none;" <?php }?>>
       <td width="220" align="right" style="text-align: right!important;">其他</td>
       <td>
           <?php foreach($pay["pay_input"]?:[] as $key=>$vl){?>
               <div >
                   <div style="display: inline-block;">
                       <label for="">名称：</label>
                       <input type="text" name="pay_input[name][<?php echo $key;?>]" value="<?php echo $vl['name'];?>">
                   </div>
                   <div style="display: inline-block;">
                       <label for="">值：</label>
                       <input type="text" name="pay_input[content][<?php echo $key;?>]" value="<?php echo $vl['content'];?>">
                   </div>
                   <div style="display: inline-block;">
                       <label for="">说明：</label>
                       <input type="text" name="pay_input[msg][<?php echo $key;?>]" value="<?php echo $vl['msg'];?>">
                   </div>
                   <div style="display: inline-block;">
                        <input type="radio" style="margin-right:3px" name="pay_input[status][<?php echo $key;?>]" value="1" <?php if($vl['status']!=0){echo "checked";}?> /><?php $this->lan("open");?>
                        <input type="radio" style="margin-left: 3px;margin-right:5px" name="pay_input[status][<?php echo $key;?>]" <?php if($vl['status']==0){echo "checked";}?> value="0"/><?php $this->lan("close");?>
                   </div>
               </div>
               <?php  foreach($vl["children"]?:[] as $k=>$v){?>
                <div style="margin-left: 20px;">
                    <div style="display: inline-block;">
                        <label for="">名称：</label>
                        <input type="text" name="pay_input[c_name][<?php echo $key;?>][<?php echo $k;?>]" value="<?php echo $v['name'];?>">
                    </div>
                    <div style="display: inline-block;">
                        <label for="">值：</label>
                        <input type="text" name="pay_input[c_content][<?php echo $key;?>][<?php echo $k;?>]" value="<?php echo $v['content'];?>">
                    </div>
                    <div style="display: inline-block;">
                        <input type="radio" style="margin-right:3px" name="pay_input[c_status][<?php echo $key;?>][<?php echo $k;?>]" value="1" <?php if($v['status']!=0){echo "checked";}?> /><?php $this->lan("open");?>
                        <input type="radio" style="margin-left: 3px;margin-right:5px" name="pay_input[c_status][<?php echo $key;?>][<?php echo $k;?>]" <?php if($v['status']==0){echo "checked";}?> value="0"/><?php $this->lan("close");?>
                   </div>
                </div>
               <?}?>
           <?}?>
       </td>
      </tr>
      
      <tr>
          <td width="220" align="right" style="text-align: right!important;"><?php $this->lan("sort");?> </td>
          <td style="display: flex;">
              <input type="number" class="input-text wid100" value="<?php echo $pay['sort']; ?>" name="sort" style="text-align:center;" />
          </td>
      </tr>
        <tr>
        	<td width="220" align="right"></td>
            <td style="display: flex;"><input type="submit" class="button" name="dosubmit"  value="<?php $this->lan("Submit");?> " style="margin: 0!important;"></td>
		</tr>
</table>
</form>

</div><!--table-list end-->

<script>
	$('.zhuijia').click(function(){
		var az = '<div style="display: flex;"><input type="text" name="ms[]" value="50" class="input-text" style="margin: 0 0 10px;"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px 10px!important;"><i class="layui-icon layui-icon-close"></i></button></div>'
		$(this).parent().children('div.marnext').append(az)
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
