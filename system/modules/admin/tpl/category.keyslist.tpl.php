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
 <style>
 	th{ border:0px solid #000;}
 </style>
</head>
<body>
<div class="header lr10">

	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>
<div class="table-list lr10">
 <table width="100%" cellspacing="0" style="border-width: 1px 0px 0px 0px;">
    <thead>
        <tr>
            <th width="70"><?php $this->lan("sort");?></th>
            <th width="60"><?php $this->lan("catid");?></th>
            <th align='center'><?php $this->lan("name");?></th>
            <th align='center'><?php $this->lan("keys");?></th>
			<th align='center'><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($list as $v){ ?>
        <tr style="border-left: 1px solid #e2e2e2;">
            <td align='center'><?php echo $v['sort']?></td>
            <td align='center'><?php echo $v['category_id']?></td>
            <td align='center'><?php echo $v['name']?></td>
            <td align='center'><?php echo $v['keys']?></td>
            <td align='center'>
				<a class="butaoin" href='<?php echo G_MODULE_PATH; ?>/category/editkey/<?php echo $v['id']?>'><?php $this->lan("modify");?></a>
				<a class="butaoin butaish a_form_noparam" data-confirm="<?php $this->lan("confirm");?>" data-cancel="<?php $this->lan("cancel");?>" href="<?php echo G_MODULE_PATH; ?>/category/delkey/<?php echo $v["id"]?>"><?php $this->lan("delete");?></a>
			</td>
        </tr>
    <?php } ?>
    </tbody>
  </table>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 