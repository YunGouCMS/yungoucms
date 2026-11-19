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
tbody tr{ line-height:30px; height:30px;}
thead tr th{ min-width:70px;}
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
        	<th align="center"><?php $this->lan("Order_number");?></th>
            <th align="center"><?php $this->lan("Product_title");?></th>
            <th align="center" style="min-width:90px;"><?php $this->lan("Purchase_users");?></th>
            <th align="center"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center"><?php $this->lan("Total_purchase");?></th>
            <th align="center"><?php $this->lan("Purchase_date");?></th>
            <th align="center"><?php $this->lan("Winning");?></th>
            <th align="center"><?php $this->lan("status");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">
                  <?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></a></td>
                <td align="left" style=" padding-left:20px">
                <a>
                <?php $this->lan("The");?>(<?php echo $v['shopqishu'];?>)<?php $this->lan("phase");?><?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
                <td align="center"><?php echo $v['username']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?><?php $this->lan("person");?></td>
                <td align="center"><?php echo $v['moneycount']; ?></td>
                <td align="center"><?php echo date("Y/m/d H:i:s",$v['time']);?></td>
                <td align="center"><?php  echo $v['huode'] ? $this->lgs['Winning'] : $this->lgs['no_winning'];?></td>
                <td align="center"><?php echo $v['status'];?></td>
            </tr>
            <?php } ?>
            <!-- 修改recover -->
            <?php  if (!$recordlist ): ?>
            <tr>
                <td colspan="7" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
  	</tbody>
</table>
<!--<div class="btn_paixu"></div>-->
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