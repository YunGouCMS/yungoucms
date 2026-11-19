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
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment($menu);?>
	<span class="lr10"> </span><span class="lr10"> </span>
    <form action="#" method="post" style="display:inline-block; ">
	<select name="paixu">
    	<option value="time1"> <?php $this->lan("Purchase_time1");?> </option>
        <option value="time2"> <?php $this->lan("Purchase_time2");?> </option>
		<option value="num1"> <?php $this->lan("Purchase_frequency1");?> </option>
        <option value="num2"> <?php $this->lan("Purchase_frequency2");?> </option>
        <option value="money1"> <?php $this->lan("Purchase_price1");?> </option>
        <option value="money2"> <?php $this->lan("Purchase_price2");?> </option>
        
	</select>    
	<input type ="submit" value="<?php $this->lan("sort");?> " name="paixu_submit" class="button"/>
	<input type="text" class="input-text" name="shouji" placeholder="会员手机号" value="<?php echo isset($_POST['shouji']) ? $_POST['shouji'] : '';?>"/>
	<input type="submit" value=" <?php $this->lan("Tips");?> " name="search" class="button">
    </form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center"><?php $this->lan("Order_number");?></th>
            <th align="center"><?php $this->lan("Product_title");?></th>
            <th align="center"><?php $this->lan("Purchase_users");?></th>
            <th align="center"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center"><?php $this->lan("Total_purchase");?></th>
            <th align="center"><?php $this->lan("Purchase_date");?></th>
            <th align="center"><?php $this->lan("Winning");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td align="center">
                <a>
                第(<?php echo $v['shopqishu'];?>)期<?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
                 <td align="center"><?php echo $v['username']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center">￥<?php echo $v['moneycount']; ?>元</td>
                <td align="center"><?php echo date("Y/m/d H:i:s",$v['time']);?></td>
                 <td align="center"><?php  echo $v['huode'] ? "中奖" : '未中奖';?></td>
                <td align="center"><?php echo $v['status'];?></td>
                <td align="center"><a class="butaoin" href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>"><?php $this->lan("details");?></a></td>
            </tr>
            <?php } ?>
            <!-- 修改 -->
            <?php  if (!$recordlist ): ?>
            <tr>
                <td colspan="7" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
  	</tbody>
</table>
<div class="btn_paixu"></div>
<div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?><?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->

<script>
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 