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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
		<th width="80px"><?php $this->lan("id"); ?></th>
		<th width="" align="center"><?php $this->lan("slide_img"); ?></th>
		<th width="" align="center"><?php $this->lan("Jump_address","链接");?></th>
		<th width="" align="center"><?php $this->lan("country","国家"); ?></th>
		<th width="70" align='center'><?php $this->lan("state"); ?></th>
		<th width="30%" align="center"><?php $this->lan("operation"); ?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($lists as $v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
			<td align="center"><img height="50px" src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $v['img']; ?>"/></td>
			<td align="center"><?php echo $v['link']; ?></td>
			<td align="center"><?php echo $v['language']?:'通用'; ?></td>
			<td align='center' class="layui-form">
              <input type="checkbox"
                class="is_state"
                name="status"
                <?php if ($v['status'] == '1') {
                  echo 'checked=""';
                } ?>
                lay-skin="switch"
                lay-filter="is_state"
                data-state="<?php echo $v['id'] ?>"
                lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>">
            </td>
			<td align="center">
				<a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/slide/update/<?php echo $v['id'];?>"><?php $this->lan("modify"); ?></a>
				<!--<span class="span_fenge lr5">|</span>-->
				<a class="butaoin butaish a_form_noparam" data-confirm="<?php $this->lan("confirm");?>" data-cancel="<?php $this->lan("cancel");?>" href="<?php echo G_ADMIN_PATH; ?>/slide/delete/<?php echo $v['id'];?>"><?php $this->lan("delete"); ?></a>
			</td>	
		</tr>
		<?php } ?>
  	</tbody>
</table>
	<div class="btn_paixu"></div>
</div><!--table-list end-->

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

<script>
	   layui.use(['form', 'jquery'], function() {
    console.log("layui")
      var form = layui.form,
         $ = layui.jquery;
      form.render('checkbox');
      form.on('switch(is_state)', function(data) {
        console.log(data.elem.checked);
         var id = $(this).attr('data-state');
         var name = $(this).attr('name');
         if(data.elem.checked){
           var status = '1';
         }else{
           var status = '2';
         }
         $.post("<?php echo G_ADMIN_PATH; ?>/slide/statusChange", {id: id, status: status}, function(data) {
          data = JSON.parse(data);
           layer.msg(data.msg, {icon: 1, time: 1000});
         });
      })
   });
</script>
</html> 