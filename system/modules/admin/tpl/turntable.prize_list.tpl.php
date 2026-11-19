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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<div class="header lr10">
	<?php echo $this->headerment();?>
	<span class="lr10"> </span><span class="lr10"> </span>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center"><?php $this->lan("id");?></th>
        	<th align="center"><?php $this->lan("id_user");?></th>
        	<th align="center"><?php $this->lan("Lottery_time");?></th>
        	<th align="center"><?php $this->lan("Winning_rate");?></th>
        	<th align="center"><?php $this->lan("Is_winner");?></th>
        	<th align="center"><?php $this->lan("Winning_amount");?></th>
        	<th align="center"><?php $this->lan("Current_amount");?></th>
		</tr>
    </thead>
    <tbody>
		<?php
         foreach($recordlist as $key=> $v) {	?>		
            <tr>
              	<td align="center"><?php echo $v['id']; ?></td>
              	<td align="center"><?php echo $v['uid']; ?>/<?php echo $v['username']; ?></td>
              	<td align="center"><?php echo $v['time']; ?></td>
              	<td align="center"><?php echo $v['probability']; ?></td>
              	<td align="center"><?php echo $v['huode']?"是":"否"; ?></td>
              	<td align="center"><?php echo $v['money']; ?></td>
              	<td align="center"><?php echo $v['total_money']; ?></td>
            </tr>
        <?php } ?>
            <!-- 修改 -->
        <?php  if (!$recordlist ): ?>
            <tr>
                <td colspan="13" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
  	</tbody>
</table>
<div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->

<script>
   
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 