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
  <style>
    th {
      border: 0px solid #000;
    }

    tr {
      line-height: 30px;
    }
    .descs{
      display: none;
      position: fixed;
      border: 1px solid;
      background-color:#cccccc;
      /* width: auto;  */
    }
    .desc:hover{
      background-color: aquamarine;
    }
    .desc:hover ~ .descs{
      display: flex;
    }
  </style>

</head>

<body>
  <div class="header lr10">
    <?php echo $this->headerment(); ?>
  </div>
  <div class="bk10"></div>
  <div class="table-list lr10">
    <table width="100%" cellspacing="0" style="border-width: 1px 1px 0px 0px;">
      <thead>
        <tr>
          <th width="60"><?php $this->lan("Coupon_id");?></th>
          <th align='center'><?php $this->lan("Coupon_name");?></th>
          <th align='center'><?php $this->lan("Coupon_model");?></th>
          <th align='center'><?php $this->lan("Coupon_type");?></th>
          <th align='center'><?php $this->lan("Deduction_amount");?> </th>
          <th align='center'><?php $this->lan("Deduction");?></th>
          <th align='center'><?php $this->lan("Validity_period","有效时间");?></th>
          <th align='center'><?php $this->lan("receive_time","领取时间");?></th>
          <th align='center'><?php $this->lan("Coupon_description");?></th>
          <th align='center'><?php $this->lan("operate");?></th>
        </tr>
      </thead>
      <form action="#" method="post" name="myform">
        <tbody>
          <?php foreach ($list as $vl) { ?>
            <tr style="border-left: 1px solid #E2E2E2;">
              <td align='center'><?php echo $vl['id']; ?></td>
              <td align='center'><?php echo $vl['name']; ?></td>
              <td align='center'><?php echo $vl['model_text']; ?></td>
              <td align='center'><?php echo $vl['type_text']; ?></td>
              <td align='center'><?php echo $vl['money']; ?></td>
              <td align='center'><?php echo $vl['discount']; ?></td>
              <td align='center'><?php if($vl['expire_type'] == 1){
                   echo $vl['day'] . "天" . $vl['time'] . "时"; }else{ echo $vl['expire_start'] . "</br>" . $vl['expire_end'];}?>
                </td>
              <td align='center'><?php echo $vl['receive_start']."</br>". $vl['receive_end']; ?></td>
              <td align='center'>
                <span class="desc"><?php echo mb_substr($vl['desc'], 0,20); ?></span>
                <div class="descs"><?php echo $vl['desc']; ?></div>
              </td>
              <td align='center'>
                <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/coupon/edit/<?php echo $vl['id']; ?>"><?php $this->lan("Revise");?></a>
                <a class="butaoin butaish" href="<?php echo G_ADMIN_PATH; ?>/coupon/delete/<?php echo $vl['id']; ?>"><?php $this->lan("delete");?></a>
              </td>
            </tr>
          <?php } ?>
    </table>
    </form>
    <div id="pages">
      <ul>
        <li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one', 'li'); ?>
      </ul>
    </div>

  </div><!--table-list end-->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
  // $(".desc").hover(function() {});
</script>

</html>