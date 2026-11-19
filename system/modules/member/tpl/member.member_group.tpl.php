<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
    /* 会员等级页面美化样式 */
    body {
        background: #f5f7fa;
    }
    
    .header.lr10 {
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .table-list.lr10 {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow-x: auto;
    }
    
    /* 表格美化 */
    .table-list table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-list thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .table-list thead th {
        color: #fff;
        font-weight: 600;
        padding: 16px 12px;
        text-align: center;
        border: none;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .table-list thead th:first-child {
        border-radius: 8px 0 0 0;
    }
    
    .table-list thead th:last-child {
        border-radius: 0 8px 0 0;
    }
    
    .table-list tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table-list tbody tr:hover {
        background: #f8f9ff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }
    
    .table-list tbody td {
        padding: 16px 12px;
        text-align: center;
        color: #333;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .table-list tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* 按钮美化 */
    .butaoin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        padding: 8px 16px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        display: inline-block !important;
        margin: 0 4px !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .butaoin:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
    }
    
    .butaish {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        box-shadow: 0 2px 4px rgba(245, 87, 108, 0.3) !important;
    }
    
    .butaish:hover {
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4) !important;
    }
    
    /* 经验值范围美化 */
    .experience-range {
        font-family: 'Courier New', monospace;
        color: #667eea;
        font-weight: 500;
    }
    
    /* 返佣百分比美化 */
    .rake-back-percent {
        color: #22c55e;
        font-weight: 600;
    }
    
    /* 分隔符美化 */
    .span_fenge {
        margin: 0 8px;
        color: #e1e8ed;
    }
    
    /* 响应式设计 */
    @media (max-width: 768px) {
        .table-list {
            padding: 16px;
        }
        
        .table-list table {
            font-size: 12px;
        }
        
        .table-list thead th,
        .table-list tbody td {
            padding: 10px 6px;
        }
    }
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0" style="table-layout: fixed;">
    <thead>
		<tr>
            <th width="30px" align="center"><?php $this->lan("ID");?></th>
            <th width="200px" align="center"><?php $this->lan("name");?></th>
            <th width="200px" align="center"><?php $this->lan("Experience_value");?></th>
            <th width="200px" align="center"><?php $this->lan("rake_back","返佣");?></th>
            <th width="200px" align="center"><?php $this->lan("operation");?></th>       
		</tr>
    </thead>
    <tbody>
    	<?php foreach($members as $v){ ?>
		<tr>
			<td align="center"><?php echo $v['groupid']; ?></td>
			<td align="center"><?php echo $v['name']; ?></td>	
			<td align="center"><?php echo $v['jingyan_start']; ?>--<?php echo $v['jingyan_end']; ?></td>
			<td align="center"><?php echo $v['rake_back']*100; ?>%</td>	
            <td align="center">
            	<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/group_modify/<?php echo $v['groupid'];?>"><?php $this->lan("modify");?></a>
                 <span class="span_fenge lr5"></span>
               <a href="<?php echo G_MODULE_PATH; ?>/member/group_del/<?php echo $v['groupid'];?>"  class="butaoin butaish" onclick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
            </td>            	
		</tr>
       <?php } ?>
	
  	</tbody>
	
</table>
</div><!--table-list end-->
<script>
    // 页面加载完成后美化特殊元素
    (function() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                $('.table-list tbody td').each(function() {
                    var text = $(this).text().trim();
                    // 经验值范围美化
                    if (text.indexOf('--') !== -1) {
                        $(this).addClass('experience-range');
                    }
                    // 返佣百分比美化
                    if (text.indexOf('%') !== -1) {
                        $(this).addClass('rake-back-percent');
                    }
                });
            });
        } else {
            // 如果jQuery未加载，使用原生JavaScript
            document.addEventListener('DOMContentLoaded', function() {
                var tds = document.querySelectorAll('.table-list tbody td');
                for (var i = 0; i < tds.length; i++) {
                    var text = tds[i].textContent.trim();
                    // 经验值范围美化
                    if (text.indexOf('--') !== -1) {
                        tds[i].classList.add('experience-range');
                    }
                    // 返佣百分比美化
                    if (text.indexOf('%') !== -1) {
                        tds[i].classList.add('rake-back-percent');
                    }
                }
            });
        }
    })();
</script>
</body>
</html> 