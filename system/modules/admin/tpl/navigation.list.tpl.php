<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
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
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
   <!-- 通知消息系统脚本（必须在layer之后加载） -->
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</head>

<body>
  <div class="header lr10">
    <?php echo $this->headerment(); ?>
  </div>

  <div class="bk10"></div>
  <div class="table-list lr10">
    <style>
      th {
        border: 0px solid #000;
      }
    </style>

  <form action="#" method="post" name="myform">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <th width="90"><?php $this->lan("sort"); ?></th>
          <th align='center'><?php $this->lan("navigation_name"); ?></th>
          <th align='center'><?php $this->lan("Jump_link");?></th>
          <th align='center' width="70"><?php $this->lan("state"); ?></th>
          <th align='center'><?php $this->lan("language"); ?></th>
          <th align='center'><?php $this->lan("type"); ?></th>
          <th align='center'><?php $this->lan("operation"); ?></th>
        </tr>

      </thead>
      <tbody>
        <?php foreach ($lists as $row) {  ?>
          <tr>
            <td align='center'><input name='listorders[1]' type='text' size='3' value='<?php echo $row['order']; ?>' class='input-text-c' style="border:0!important;text-align: center!important;"></td>
            <td align='center'><a target="_blank" href="<?php echo WEB_PATH . $row['url']; ?>"><?php echo $row['name']; ?></a></th>
            <td align="center"><?php echo $row['url'];?></td>
            <td align='center' class="layui-form">
              <input type="checkbox"
                class="is_state"
                name="status"
                <?php if ($row['status'] == 'Y') {
                  echo 'checked=""';
                } ?>
                lay-skin="switch"
                lay-filter="is_state"
                data-state="<?php echo $row['cid'] ?>"
                lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>">
            </td>
            <td align='center'><?php echo $row['language'] ?: "<?php $this->lan('currency');?>"; ?></td>
            <td align='center'><?php if ($row['type'] == 'index') {
                                  echo $this->lan('Header_navigation');
                                } else {
                                  echo $this->lan('Foot_navigation');
                                } ?></td>
            <td align='center'>
              <a class="butaoin" class="" href="<?php echo G_ADMIN_PATH; ?>/ments/editnav/<?php echo $row['cid']; ?>"><?php $this->lan("modify"); ?></a><span class="span_fenge lr5"></span>
              <a class="butaoin butaish a_form_noparam" data-confirm="<?php $this->lan("confirm"); ?>" data-cancel="<?php $this->lan("cancel"); ?>" href="<?php echo G_ADMIN_PATH; ?>/ments/navdel/<?php echo $row['cid']; ?>"><?php $this->lan("delete"); ?></a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    </form>
    <div class="btn_paixu">
      <div style="width:80px; text-align:center;">
        <input type="hidden" class="button" name="dosubmit" value="<?php $this->lan('Submit'); ?>">
      </div>
    </div>
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
           var status = 'Y';
         }else{
           var status = 'N';
         }
         $.post("<?php echo G_ADMIN_PATH; ?>/ments/statusChange", {cid: id, status: status}, function(data) {
          data = JSON.parse(data);
           layer.msg(data.msg, {icon: 1, time: 1000});
         });
      })
   });
  </script>
</html>