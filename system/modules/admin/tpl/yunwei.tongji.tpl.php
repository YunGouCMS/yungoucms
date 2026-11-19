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
.topk3{}
.topk3 li{ line-height:35px; border-bottom:1px solid #eee}
.topk3 li a{ margin-left:30px; color:#9CC}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
	<b>站长工具</b> 
</div>
<div class="bk10"></div>

<div class="table-list lr10">
  <table width="100%" cellspacing="0">
  	<tbody>
		<tr>
        	<td width="200px" style="text-align: left;">数据统计（友盟）</td>
             <td><a target="_blank" href="https://www.umeng.com/">https://www.umeng.com</a></td>
        </tr>
    	<tr>
        	<td width="200px" style="text-align: left;">APP打包（APICloud）</td>
            <td><a target="_blank" href="https://www.apicloud.com/">https://www.apicloud.com</a></td>
        </tr>
      
         
        <tr>
        	<td width="200px" style="text-align: left;">APP分发（Hir）</td>
             <td><a target="_blank" href="http://www.hir.im/">http://www.hir.im</a></td>
        </tr>
		<tr>
        	<td width="200px" style="text-align: left;">微信公众平台</td>
             <td><a target="_blank" href="https://mp.weixin.qq.com/">https://mp.weixin.qq.com</a></td>
        </tr>
		<tr>
        	<td width="200px" style="text-align: left;">短网址转换</td>
             <td><a target="_blank" href="http://sina.lt/">http://sina.lt</a></td>
        </tr>
		<tr>
        	<td width="200px" style="text-align: left;">短信群发</td>
             <td><a target="_blank" href="http://www.smsbao.com/">http://www.smsbao.com</a></td>
        </tr>
		<tr>
        	<td width="200px" style="text-align: left;">二维码生成</td>
             <td><a target="_blank" href="https://cli.im/url">https://cli.im</a></td>
        </tr>
	</tbody>
</table>	
</div>
</div><!--table-list end-->
<div class="btn_paixu"></div>
<div class="bk30"></div>
<script>
	
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 