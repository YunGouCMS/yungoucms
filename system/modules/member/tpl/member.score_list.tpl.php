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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<style>
	tbody tr {line-height: 30px;height: 30px;}
	.con_a_btn {border: 0px solid #0c0}
	.con_a_btn a {display: inline-block;padding: 3px 6px;margin-right: 5px;background: #6CF;color: #fff;font-weight: bold;text-decoration: none;cursor: pointer}
	.remarks{
		margin: 10px 0;
	}
	.remarks p{
		line-height: 32px;
	}
	.remarks p a{
		background: #161E22;
		font-size: 12px;
		padding: 4px 6px;
		color: #fff;
		border-radius: 5px;
		margin-left: 20px;
	}
    </style>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment(); ?> </div>
<div class="bk10"></div>
<div class="table-list lr10">
  <table width="100%" cellspacing="0">
    <thead>
      <tr>
        <th width="100px" align="center"><?php $this->lan("ID");?></th>
        <th width="100px" align="center"><?php $this->lan("time");?></th>
        <th width="100px" align="center"><?php $this->lan("integral");?></th>
        <th width="200px" align="center"><?php $this->lan("source");?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $v) { ?>
        <tr>
            <td align="center"><?php echo $v['id'];?></td>
            <td align="center"><?php echo $v['time']; ?></td>
            <td align="center"><?php echo $v['money'];?></td>
            <td align="center"><?php echo $v['content'];?></td>
        </tr>
      <?php } ?>
      <?php if (!$list ): ?>
        <tr>
            <td colspan="7" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
        </tr>
        <?php  endif; ?>
    </tbody>
  </table>

</div>
<script>
   
</script>

</body>
</html>
