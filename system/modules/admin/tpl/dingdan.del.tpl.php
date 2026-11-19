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
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.header-data li{ line-height:40px;}
.soso_message{ text-align:center; height:80px; line-height:80px;  border-top:5px solid #FFBE7A;border-bottom:5px solid #FFBE7A;}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="header-data lr10">
<form action="#" method="post" style="display:inline-block; " onSubmit="return dindanconfirm();">
<div style="display:flex">
  
   <div style="margin-left: 10px;" class="posttime">
   <input name="time" type="text" id="time" class="input-text" style="padding-left:32px" placeholder="请输入时间"  readonly="readonly" value="" />
   </div> 
<script type="text/javascript">
		date = new Date();
		Calendar.setup({
					inputField     :    "time",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});				
</script>
   	  
<div><input class="button" type="submit" id='submit' name="timesubmit" value=" 删 除 "></div>
</div>
	
</form>
</div>

<div class="header-data lr10">
&nbsp;&nbsp;&nbsp;&nbsp;<i style='color:red;'>* 订单删除无法恢复,请谨慎删除！否则后果自负</i><br>
&nbsp;&nbsp;&nbsp;&nbsp;<i style='color:red;'>* 订单数量巨大，请不要一次性删除大于七天的数据</i><br>
</div>
<script>
function dindanconfirm(){
	if(confirm('确定删除吗？')){
	       return true;		
	}
}

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 