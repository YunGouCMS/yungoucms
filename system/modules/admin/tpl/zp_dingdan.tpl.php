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
<title>未发货</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
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
		<th width="70px"><?php $this->lan("id");?></th>
		<th width="" align="center"><?php $this->lan("Winning_members");?></th>
		<th width="" align="center"><?php $this->lan("Prize_level");?></th>
		<th width="" align="center"><?php $this->lan("Prize_name");?></th>
		<th width="" align="center"><?php $this->lan("Number_invitations");?></th>
		<th width="" align="center"><?php $this->lan("description");?></th>
		<th width="" align="center"><?php $this->lan("Prize_status");?></th>

		</tr>
    </thead>
    <tbody>
		<?php foreach($dingdan as $v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
			<td align="center"><?php echo $v['id']?></td>
			<td align="center"><?php echo $v['Grade']; ?><?php $this->lan("Prize");?></td>
			<td align="center"><?php echo $v['content']; ?></td>
			<td align="center"><?php echo $v['ren']; ?></td>
			<td align="center"><font color='#0c0' class="sta" state="<?php echo $v['type']; ?>" kid="<?php echo $v['id']; ?>" style="cursor:pointer;"><?php $this->lan("Shipped");?></font></td>		
			<td align="center"><a href="<?php echo G_MODULE_PATH;?>/zp/zp_dingdan1/<?php echo $v['id']; ?>"><?php $this->lan("Details");?></a></td>
		</tr>
		<?php } ?>
		<?php  if (!$dingdan ): ?>
            <tr>
                <td colspan="7" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
  	</tbody>
</table>
	<div class="btn_paixu"></div>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 