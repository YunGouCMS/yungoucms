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
body{ background-color:#fff}
.a_button{color:#fff; font-weight:bold; cursor:pointer; text-decoration:none;border:1px solid #090;padding:5px; background-color:#0c0}
</style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
	<b><?php $this->lan("goods");?>&nbsp;&nbsp;&nbsp;ID&nbsp;</b><b style="color:red;padding-left:10px;"><?php echo $ginfo['id']; ?></b><br/>
    <b><?php $this->lan("Product_num");?>&nbsp;</b><b style="color:red; padding-left:10px;"><?php $this->lan("The");?>(<?php echo $ginfo['qishu']; ?>)<?php $this->lan("phase");?><?php $this->lan("maximum");?><?php echo $ginfo['maxqishu']; ?><?php $this->lan("phase");?></b><br/>
    <b><?php $this->lan("Product_title");?>&nbsp;</b><b style="color:red;padding-left:10px;"><?php echo $ginfo['title']; ?></b><br/>
    <b><?php $this->lan("Product_info");?>&nbsp;</b><b style="color:red;padding-left:8px;"><?php
		echo $this->lgs['Total_price']."&nbsp;".$ginfo['money']."&nbsp;&nbsp;&nbsp;&nbsp;";
		echo $this->lgs['Unit_price']."&nbsp;".$ginfo['yunjiage']."&nbsp;&nbsp;&nbsp;&nbsp;";
		echo $this->lgs['Total_required']."&nbsp;".$ginfo['zongrenshu']."&nbsp;&nbsp;&nbsp;&nbsp;";
		echo $this->lgs['Number_participants']."&nbsp;".$ginfo['canyurenshu']."&nbsp;&nbsp;&nbsp;&nbsp;";
		echo $this->lgs['Remaining_people']."&nbsp;".$ginfo['shenyurenshu']."&nbsp;&nbsp;&nbsp;&nbsp;";

	?></b><br/>
    <b><?php $this->lan("Winning_status");?>&nbsp;</b><b style="color:red;">
		<?php if($ginfo['q_uid'] && $ginfo['shenyurenshu']==0){
					echo $this->lgs['Revealed']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo '<span style="color:#000;">'.$this->lgs['Winner']."</span>&nbsp; ".get_user_name($ginfo['q_uid'],'username','all')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo '<span style="color:#000;">'.$this->lgs['Winner_code']."</span>&nbsp; ".$ginfo['q_user_code']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo '<span style="color:#000;">'.$this->lgs['OrderListjs_time']."</span>&nbsp; ".date("Y-m-d H:i:s",$ginfo['q_end_time']);
		       }else if($ginfo['shenyurenshu']!=0 && $ginfo['xsjx_time'] == 0){
					echo $this->lgs['tips'];
			   }else if($ginfo['shenyurenshu']==0 && $ginfo['xsjx_time'] != 0){
					echo $this->lgs['tips1'];
			   }else if($ginfo['shenyurenshu']==0 && $ginfo['xsjx_time'] == 0){
				    echo "<a href='".G_MODULE_PATH."/content/goods_one_ok/".$ginfo['id']."' class='a_button'>手动揭晓</a>";
			   }
		?>
    
    </b>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
 <table width="100%" cellspacing="0">
     	<thead>
        		<tr>
                	<th width="10%"><?php $this->lan("Order_number");?></th>
                    <th width="20%"><?php $this->lan("Buying_time");?></th>        
                    <th width="5%"><?php $this->lan("Purchase_frequency");?></th>    
                    <th width="25%"><?php $this->lan("Buyer");?></th>             
                    <th width="20%"><?php $this->lan("Come_from");?></th>
				</tr>
        </thead>
        <tbody style="text-align:center">
        	<?php  foreach($go_list as $go){ ?>
        	<tr>
            	<td><?php echo $go['code']; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$go['time']); ?></td>
                <td><?php echo $go['gonumber']; ?></td>
                <td><?php echo $go['username']; ?></td>
                <td><?php echo _get_ip_region($go['ip']); ?></td>
            </tr>
            <?php } ?>
            <?php if(!$go_list){ ?>
            <tr>
            	<td colspan="5"><?php $this->lan("No_data");?></td>
            </tr>
            <?php } ?>
        </tbody>
     </table>     

</div><!--table_list end-->
<div id="pages" style="margin-right: 10px;margin-top:10px"><ul><li style="margin-right: 10px;"><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 
