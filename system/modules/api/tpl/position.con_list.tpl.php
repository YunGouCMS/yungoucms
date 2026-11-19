<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
tbody tr{ line-height:30px; height:30px; clear:} 
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
            <th width="100px" align="center">ID</th>
            <th width="100px" align="center">标题</th>
            <th width="100px" align="center">所属推荐位</th>
            <th width="100px" align="center">添加时间</th>
            <th width="100px" align="center">操作</th>
		</tr>
    </thead>
    <tbody>
    	<?php foreach($pos_list as $pos): $pos_c = unserialize($pos['pos_data']); ?>
		<tr>
			<td align="center"><?php echo $pos_c['id']; ?></td>	
			<td align="center"><?php echo $pos_c['title']; ?></td>
            <td align="center"><?php echo $pos_c['title']; ?></td>		
			<td align="center"><?php echo date('Y/m/d H:i:s',$pos['pos_time']); ?></td>
			<td align="center">
            <a target="_blank" href="<?php echo $path.$pos_c['id']; ?>">查看</a>
			<span class="span_fenge lr5">|</span>
            <a href="<?php echo $editpath.$pos_c['id']; ?>">原文编辑</a>
			<span class="span_fenge lr5">|</span>
            <a href="<?php echo G_MODULE_PATH.'/position/con_del/'.$pos['id'].'/'.$pos['pos_id']; ?>">移除</a>
            </td>
		</tr>
        <?php endforeach; ?>
  	</tbody>
</table>
<div class="btn_paixu"></div>
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->
<script>
</script>
</body>
</html> 