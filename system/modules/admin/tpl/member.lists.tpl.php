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

<title><?php echo $this->getPageTitle('会员列表'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<style>
tbody tr{ line-height:30px; height:30px;} 
.table-list {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.butaoin, .butaish {
    padding: 6px 16px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
    margin: 0 4px !important;
}
.butaoin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #fff !important;
}
.butaoin:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}
.butaish {
    background: #fee2e2 !important;
    color: #991b1b !important;
}
.butaish:hover {
    background: #fecaca !important;
    transform: translateY(-2px) !important;
}
.span_fenge {
    color: #d1d5db;
    margin: 0 8px;
}
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

            <th width="40px" align="center">ID</th>

            <th width="100px" align="center">用户名</th>

            <th width="100px" align="center">邮箱</th>

            <th width="100px" align="center">手机</th>

            <th width="100px" align="center">账户金额</th>

            <th width="100px" align="center">邮箱认证</th>

            <th width="100px" align="center">手机认证</th>

            <th width="100px" align="center">注册来源</th>

            <th width="100px" align="center">操作</th>       

		</tr>

    </thead>

    <tbody>

    	<?php foreach($members as $v){ ?>

		<tr>

			<td align="center"><?php echo $v['uid'] ?? ''; ?></td>

			<td align="center"><?php echo $v['username']; ?></td>	

			<td align="center"><?php echo $v['email']; ?></td>	

			<td align="center"><?php echo $v['mobile']; ?></td>	

			<td align="center"><?php echo $v['money']; ?></td>	

			<td align="center"><?php if($v['eamilcode']==1){?>已认证<?php }else{ ?>未认证<?php } ?></td>	

			<td align="center"><?php if($v['mobilecode']==1){?>已认证<?php }else{ ?>未认证<?php } ?></td>

            <td align="center">电脑</td>

            <td align="center">
            	<a class="butaoin" href="#">修改</a>
                <a class="butaoin butaish" href="#">删除</a>
            </td>            	

		</tr>

       <?php } ?>

	

  	</tbody>

</table>

</div><!--table-list end-->

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 