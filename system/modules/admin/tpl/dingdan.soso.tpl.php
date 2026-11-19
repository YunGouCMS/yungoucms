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
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.header-data li{ line-height:40px;}
.soso_message{ text-align:center; height:80px; line-height:80px;  border-top:5px solid #3C8DBC;border-bottom:5px solid #3C8DBC;}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
	<form action="#" method="post" style="display:inline-block; ">
	      <li style="display:flex;"><input type="type" style="width:150px" name="text" placeholder="<?php $this->lan("Tips1");?>" class="input-text" /> <input type="submit" name="codesubmit" class="button" value="<?php $this->lan("Tips");?> " /></li>
    </form>    
<!--    <br />-->
    <form action="#" method="post" style="display:inline-block; ">
          <li style="display:flex;">
<!--    <select name="user" style="width:100px">-->
<!--    	<option value="uid"> 用户UID </option>-->
<!--	</select>-->
        <input type="hidden" name="user" value="uid">
         <input type="type" name="text" style="width: 150px;" placeholder="<?php $this->lan("Tips2");?>" class="input-text wid200" /> <input type="submit" name="usersubmit" class="button" value="<?php $this->lan("Tips");?>" />
         </li>
    </form>
<!--    <br />-->
    <form action="#" method="post" style="display:inline-block; ">
        <li style="display:flex;">
         <select name="shop" style="width:100px">
    	<option value="sid"> <?php $this->lan("Product_ID");?></option>
        <option value="sname"> <?php $this->lan("Product_title");?></option>     
	     </select>    
        <input type="type" style="width:150px" placeholder="<?php $this->lan("Tips3");?>" name="text" class="input-text wid200" /> <input type="submit" name="shopsubmit" class="button" value="<?php $this->lan("Tips");?>" />
       </li>
    </form>
    <form action="#" method="post" style="display:inline-block; ">
    <li style="display:flex">
    <div class="posttime">
    	  <input name="posttime1" type="text" id="posttime1" class="input-text " placeholder="<?php $this->lan("Tips4");?>" readonly="readonly" />
          &nbsp;-&nbsp; 
 		  <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("Tips5");?>" readonly="readonly" />
	</div>
<script type="text/javascript">
		date = new Date();
		Calendar.setup({
					inputField     :    "posttime1",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});
		Calendar.setup({
					inputField     :    "posttime2",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});
				
</script>
<div style="marign-top:7px">
<input  class="button" type="submit" name="timesubmit" value=" <?php $this->lan("search");?>">
</div>
    </li>
</form>
</div>

<!-- <form action="#" method="post" class="posttimeform">
	
	<div class="posttime">
    	  <input name="posttime1" type="text" id="posttime1" class="input-text " placeholder="请输入开始时间" readonly="readonly" />
          &nbsp;-&nbsp; 
 		  <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="请输入结束时间" readonly="readonly" />
	</div>
<script type="text/javascript">
		date = new Date();
		Calendar.setup({
					inputField     :    "posttime1",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});
		Calendar.setup({
					inputField     :    "posttime2",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});
				
</script>
<input class="button" type="submit" name="timesubmit" value=" 搜 索 ">
</form> -->
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<?php if(($record)): ?>
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center" width="180px"><?php $this->lan("Order_number");?></th>
            <th align="center" width="540px"><?php $this->lan("Product_title");?></th>
            <th align="center" width="140px"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center" width="90px"><?php $this->lan("user_id");?></th>
            <th align="center" width="130px"><?php $this->lan("Total_purchase");?></th>
            <th align="center" width="190px"><?php $this->lan("Purchase_date");?></th>
            <th align="center" width="200px"><?php $this->lan("Order_status");?></th>
            <th align="center" width="80px"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
    	<?php foreach($record as $v): ?>
        	<tr>
                <td align="center">
                <a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">
                    <?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></a></td>
                <td align="center"><?php echo $v['shopname']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center"><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['moneycount']; ?></td>
                <td align="center"><?php echo date("Y/m/d H:i:s",$v['time']);?></td>
                <td align="center"><?php echo $v['status'];?></td>
                <td align="center"><a class="butaoin" href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>"><?php $this->lan("details");?></a></td>
            </tr>
        <?php endforeach; ?>
        <?php  if (!$record ): ?>
            <tr>
                <td colspan="8" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>

  	</tbody>
</table>
 <?php endif; ?>
<?php if(!$record): ?>
    <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center" width="180px"><?php $this->lan("Order_number");?></th>
            <th align="center" width="540px"><?php $this->lan("Product_title");?></th>
            <th align="center" width="140px"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center" width="90px"><?php $this->lan("user_id");?></th>
            <th align="center" width="130px"><?php $this->lan("Total_purchase");?></th>
            <th align="center" width="190px"><?php $this->lan("Purchase_date");?></th>
            <th align="center" width="200px"><?php $this->lan("Order_status");?></th>
            <th align="center" width="80px"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
    	<?php foreach($record?:[] as $v): ?>
        	<tr>
                <td align="center">
                <a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">
                    <?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></a></td>
                <td align="center"><?php echo mb_substr($v['shopname'],0,60);?>...</td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center"><?php echo $v['uid']; ?></td>
                <td align="center">￥<?php echo $v['moneycount']; ?>元</td>
                <td align="center"><?php echo date("Y/m/d H:i:s",$v['time']);?></td>
                <td align="center"><?php echo $v['status'];?></td>
                <td align="center"><a class="butaoin" href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>"><?php $this->lan("details");?></a></td>
            </tr>
        <?php endforeach; ?>
        <?php  if (!$record ): ?>
            <tr>
                <td colspan="8" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>

  	</tbody>
</table>
<?php endif; ?>
<!-- <div class="btn_paixu"></div> -->
<!-- <div id="pages"></div> -->
</div>

<script>
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 