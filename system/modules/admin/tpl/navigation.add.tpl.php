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
   <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
   <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>

<body>
   <div class="header lr10">
      <?php echo $this->headerment(); ?>
   </div>
   <style>
      table th {
         font-size: 12px;
         font-weight: 100;
         text-align: right;
         width: 100px;
      }

      table td {
         padding-left: 10px;
      }

      input.button {
         display: inline-block
      }
      .layui-unselect.layui-form-switch.layui-form-onswitch {
          margin: 0!important;
      }
   </style>

   <div class="bk10"></div>

   <?php if (ROUTE_A == 'addnav') { ?>
      <form action="#" id="form" method="post">
         <table width="100%" class="table_form">
            <tbody>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("navigation_name"); ?></th>
                  <td style="display:flex;"><input type="text" name="name" id="catname" class="input-text">
                  </td>
               </tr>
               <tr>
                  <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("type"); ?></th>
                  <td style="display:flex;">
                     <select name="type" id="modelid" onChange="" style="margin:0!important;">
                        <option value="index" selected="">≡ <?php $this->lan("Header_navigation"); ?> ≡</option>
                        <option value="foot">≡ <?php $this->lan("Foot_navigation"); ?> ≡</option>
                     </select>
               </tr>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Navigation_link"); ?></th>
                  <td style="display:flex;"><input type="text" name="url" style="width:350px;" placeholder="<?php $this->lan('tips6'); ?>: <?php echo WEB_PATH; ?>" id="catname" class="input-text">
                     <!-- <span><font color="#0c0" size="">※ </font>前面默认会加上: <?php echo WEB_PATH; ?></span> -->
                  </td>
               </tr>
               <tr>
                  <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("state"); ?></th>
                  <td style="display:flex;">
                     <select name="status" id="modelid" onChange="" style="margin:0!important;">
                        <option value="Y" selected="">≡ <?php $this->lan("open"); ?> ≡</option>
                        <option value="N">≡ <?php $this->lan("close"); ?> ≡</option>
                     </select>
               </tr>
               <tr>
                  <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("language"); ?></th>
                  <td style="display:flex;">
                     <select name="language" id="modelid" onChange="" style="margin:0!important;">
                        <?php foreach ($languages as $key => $v) { ?>
                           <option value="<?php echo $v['lg_value']; ?>" <?php if ($info['language'] == $v['lg_value']) {
                                                                              echo 'selected';
                                                                           } ?>><?php echo $v['country']; ?></option>
                        <?php } ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("sort"); ?></th>
                  <td style="display:flex"><input type="text" name="order" id="catname" class="input-text" onKeyUp="value=value.replace(/[^0-9]/g,'')">
                     <div data-title="<?php $this->lan("tips7"); ?>" class="tip" style="margin: 30px 0 0 0!important;">
                        <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                     </div>
                  </td>

               </tr>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"></th>
                  <td style="display:flex"><input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit"); ?>" style="margin:0!important;"></td>
               </tr>
            </tbody>
         </table>
      </form>
   <?php } ?>
   <?php if (ROUTE_A == 'editnav') { ?>
      <form action="#" id="form" method="post">
         <table width="100%" class="table_form">
            <tbody>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("navigation_name"); ?></th>
                  <td style="display:flex;"><input type="text" value="<?php echo $info['name']; ?>" name="name" id="catname" class="input-text">
                  </td>
               </tr>
               <tr>
                  <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("type"); ?></th>
                  <td style="display:flex;">
                     <select name="type" id="modelid" onChange="" style="margin:0!important;">
                        <option value="index" <?php if ($info['type'] == 'index') { ?> selected="" <?php } ?>>≡ <?php $this->lan("Header_navigation"); ?> ≡</option>
                        <option value="foot" <?php if ($info['type'] == 'foot') { ?> selected="" <?php } ?>> ≡ <?php $this->lan("Foot_navigation"); ?> ≡</option>
                     </select>
               </tr>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Navigation_link"); ?></th>
                  <td style="display:flex;"><input type="text" name="url" placeholder="<?php $this->lan('tips6'); ?>: <?php echo WEB_PATH; ?>" value="<?php echo $info['url']; ?>" style="width:350px;" id="catname" class="input-text">
                     <!-- <span><font color="#0c0" size="">※ </font>前面默认会加上: <?php echo WEB_PATH; ?></span> -->
                  </td>
               </tr>
               <tr>
                  <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("state"); ?></th>
                  <td class="layui-form" style="display:flex;">
                     <input type="checkbox"
                        class="is_state"
                        name="sw_status"
                        <?php if ($info['status'] == 'Y') { echo 'checked=""';} ?>
                        lay-skin="switch"
                        lay-filter="is_state"
                        data-state="<?php echo $info['id'] ?>"
                        lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>">
                     <input type="hidden" name="status" value="<?php echo $info['status']; ?>">
                  </td>
               </tr>
               <tr>
                  <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("language"); ?></th>
                  <td style="display:flex;">
                     <select name="language" onChange="" style="margin:0!important;">
                        <?php foreach ($languages as $key => $v) { ?>
                           <option value="<?php echo $v['lg_value']; ?>" <?php if ($info['language'] == $v['lg_value']) {
                                                                              echo 'selected';
                                                                           } ?>><?php echo $v['country']; ?></option>
                        <?php } ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("sort"); ?></th>
                  <td style="display:flex"><input type="text" name="order" value="<?php echo $info['order']; ?>" placeholder="<?php $this->lan("tips7"); ?>" id="catname" class="input-text" onKeyUp="value=value.replace(/[^0-9]/g,'')">
                     <div data-title="<?php $this->lan("tips7"); ?>" class="tip" style="margin:30px 0 0 10px!important;">
                        <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                     </div>
                  </td>
               </tr>
               <tr>
                  <th style="text-align: right !important;padding-right: 10px;"></th>
                  <td style="display: flex;"><input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit"); ?>" style="margin: 0!important;"></td>
               </tr>
            </tbody>
         </table>
      </form>
   <?php } ?>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
   layui.use(['form', 'jquery'], function() {
      var form = layui.form,
         $ = layui.jquery;
      form.render('checkbox');
      form.on('switch(is_state)', function(data) {
         var id = $(this).attr('data-state');
         var name = $(this).attr('name');
         if(data.elem.checked){
            $("input[name='status']").val('Y');
         }else{
            $("input[name='status']").val('N')
         }
      })
   });
</script>

</html>