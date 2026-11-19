<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $this->getPageTitle('会员管理'); ?></title>
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
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/clipboard.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>

<body >
  <div class="header lr10">
    <?php echo $this->headerment(); ?>
  </div>
  <div class="bk10"></div>

  <div>
    <form action="#" method="post" enctype="multipart/form-data">
    <?php foreach($lists as $key=>$val){ ?>     
    <div>
      <label for=""><?php echo $val['username']?></label>
      <a href="/member/Channel/change/<?php echo $uid;?>/<?php echo $val['id']; ?>"><?php $this->lan("change");?></a>
    </div>
    <?php } ?>
    </form>
  </div>

</body>
<script>

</script>

</html>