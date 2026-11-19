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
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
  <script src="/statics/templates/mlmuban/js/layui/lay/modules/form.js"></script>
  <style>
    .btn{
      padding: 5px 15px;
      background-color: #1E9FFF;
      color: #fff;
      border-radius: 2px;
      cursor: pointer;
      border: none;
    }
    input{
      padding: 5px 15px;
      border-radius: 2px;
      border: 1px solid #ccc;
    }
  </style>
</head>

<body>
  <div class="header lr10">
    <h3 class="nav_icon"> <?php $this->lan("title","添加短信配置"); ?></h3><span class="span_fenge lr10"></span>
  </div>
  <div class="bk10"></div>
  <div class="lr10" id="div_top_block" style="display:block; width:100px; height:0px;background:#d5dfe8;">

  </div>
    <form action="#" method="post" id="myform">
      <div>
        <label>短信名称</label><input type="text" name="title" value="">
        <button type="button" class="btn add">+</button>
      </div>
      <div class="main">
        <div class="content">
          <label>名称</label><input type="text" name="name[]" value="">
          <label>key</label><input type="text" name="key[]" value="">
          <label>value</label><input type="text" name="value[]" value="">
          <button type="button" class="btn del">-</button>
        </div>
      </div>
      <input type="submit" value=" <?php $this->lan("Submit","提交"); ?>" name="dosubmit" class="button">
    </form>

  </div><!--table-list end-->

  <script>
    var html = $(".content").html();
    $(".add").click(function() {
      $(".main").append("<div class='content'>" + html + "</div>");
    })
    $(".main").on("click",".del",function() {
      if($(".content").length > 1){
        $(this).parent().remove();
      }
      console.log("aa")
    })
  </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>