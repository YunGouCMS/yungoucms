<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
<style>
        tbody tr {line-height: 30px;height: 30px;}
        .con_a_btn {border: 0px solid #0c0}
        .con_a_btn a {display: inline-block;padding: 3px 6px;margin-right: 5px;background: #6CF;color: #fff;font-weight: bold;text-decoration: none;cursor: pointer}
    </style>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment(); ?> </div>
<div class="bk10"></div>
<div class="table-list lr10">
  <table width="100%" cellspacing="0">
    <thead>
      <tr>
        <th width="100px" align="center"><?php $this->lan("name");?></th>
        <th width="100px" align="center"><?php $this->lan("Member_uid");?></th>
        <th width="100px" align="center"><?php $this->lan("balance");?></th>
        <th width="100px" align="center"><?php $this->lan("integral");?></th>
        <th width="100px" align="center"><?php $this->lan("time");?></th>
        <!--th width="100px" align="center">邮箱</th-->
        <th width="100px" align="center"><?php $this->lan("Total_rebate");?></th>
        <th width="100px" align="center"><?php $this->lan("invite_recharge_total");?></th>
        <th width="100px" align="center"><?php $this->lan("invite_cashout_total");?></th>
        <th width="100px" align="center"><?php $this->lan("invite_recharge_days_7");?></th>
        <th width="100px" align="center"><?php $this->lan("invite_cashout_days_7");?></th>

      </tr>
    </thead>
    <tbody>
      <?php foreach ($info as $v) { ?>
      <tr>
        <td align="center"><?php echo $v['name']; ?></td>
        <td align="center"><?php echo $v['uid']; ?></td>
        <td align="center"><?php echo $v['money']; ?></td>
        <td align="center"><?php echo $v['score']; ?></td>
         <td align="center"> <?php echo date("Y/m/d H:i:s", $v['time']); ?> ~<br /> <?php if (!empty($v['login_time'])) echo date("Y/m/d H:i:s", $v['login_time']); ?></td>
        <td align="center"><?php echo $v['total_money']; ?></td>
        <td align="center"><?php echo $v['rechange_total']; ?></td>
        <td align="center"><?php echo $v['cashout_total']; ?></td>
        <td align="center"><?php echo $v['rechange_days_7']; ?></td>
        <td align="center"><?php echo $v['cashout_days_7']; ?></td>
       
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!--table-list end-->

<script>

</script>
</body>
</html>
