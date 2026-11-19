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
	tr{ line-height:30px;}
 </style>

</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
 <table width="100%" cellspacing="0" style="border-width: 1px 1px 0px 0px;">
    <thead>
         <tr>
            <th width="100">ID</th>
            <th align='center'>类型</th>
            <th align='center'>内容</th>
            <th align='center'>是否完成</th>
            <th align='center'>时间</th>
			   <th align='center'>操作</th>
         </tr>
    </thead>
    <form action="#" method="post" name="myform">
   <tbody>
   	<?php foreach($list as $vl){ ?>
       <tr style="border-left: 1px solid #E2E2E2;">
         <td align='center'><?php echo $vl['id'];?></td>        
         <td align='center'><?php echo $vl['type'];?></td>        
         <td align='center'><?php echo $vl['content'];?></td>        
         <td align='center'><?php echo $vl['is_check'];?></td>        
         <td align='center'><?php echo $vl['time'];?></td>        
         <td align='center'>
            <a href="<?php echo G_ADMIN_PATH; ?>/brand/edit/<?php echo $brand['id']; ?>">修改</a><span class='span_fenge lr5'>|</span>
            <a href="<?php echo G_ADMIN_PATH; ?>/brand/del/<?php echo $brand['id']; ?>">删除</a>
         </td>
      </tr>
     <?php } ?>
   </table>
   </form>
 <!-- <div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div> -->

</div><!--table-list end-->


<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 
