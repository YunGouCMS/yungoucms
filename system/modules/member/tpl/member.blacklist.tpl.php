<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<style>
    /* 黑名单页面美化样式 */
    body {
        background: #f5f7fa;
    }
    
    .header.lr10 {
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
    }
    
    .header.lr10 a {
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        display: inline-block;
        margin-top: 0!important;
    }
    
    .header.lr10 a:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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
    
    /* 空数据提示美化 */
    .table-list tbody td[colspan] {
        padding: 40px 20px;
        color: #999;
        font-size: 14px;
        background: #fafafa;
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
    
    /* 备注信息美化 */
    .table-list tbody td {
        max-width: 300px;
        word-wrap: break-word;
        line-height: 1.6;
    }
    
    /* 手动拉黑标签美化 - 通过JavaScript动态添加样式 */
    .manual-blacklist {
        color: #e53e3e !important;
        font-weight: 500 !important;
        background: #fff5f5 !important;
        padding: 4px 8px !important;
        border-radius: 4px !important;
        display: inline-block !important;
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
        
        .header.lr10 {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    
    /* 原有样式保留并优化 */
	tbody tr {
		line-height: 30px;
		height: auto;
	}
	
	.con_a_btn {
		border: 0px solid #0c0
	}
	
	.con_a_btn a {
		display: inline-block;
		padding: 3px 6px;
		margin-right: 5px;
		background: #6CF;
		color: #fff;
		font-weight: bold;
		text-decoration: none;
		cursor: pointer;
		border-radius: 4px;
		transition: all 0.3s ease;
	}
	
	.con_a_btn a:hover {
		transform: translateY(-1px);
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	}
	
	.remarks {
		margin: 10px 0;
	}
	
	.remarks p {
		line-height: 32px;
	}
	
	.remarks p a {
		background: #161E22;
		font-size: 12px;
		padding: 4px 6px;
		color: #fff;
		border-radius: 5px;
		margin-left: 20px;
		transition: all 0.3s ease;
	}
	
	.remarks p a:hover {
		background: #2a3a4a;
		transform: translateY(-1px);
	}
    </style>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>
<body>
<div class="header lr10"> 
 <?php echo $this->headerment(); ?>
    <a href="javascript:;" onclick="addbalck()"><?php $this->lan("Blacklist_members_add");?></a>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
  <table width="100%" cellspacing="0">
    <thead>
      <tr>
        <th width="100px" align="center"><?php $this->lan("ID");?></th>
        <th width="100px" align="center"><?php $this->lan("name");?></th>
        <th width="100px" align="center"><?php $this->lan("tel");?></th>
        <th width="200px" align="center"><?php $this->lan("Blackout_time");?></th>
          <th><?php $this->lan("remarks");?></th>
        <th width="200px" align="center"><?php $this->lan("operation");?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($userlist as $v) { ?>
          <tr>
              <td align="center"><?php echo $v['uid']?:'手动拉黑'; ?></td>
              <td align="center"><?php echo $v['username']; ?></td>
              <td align="center"><?php echo $v['mobile']; ?></td>
              <td align="center"><?php echo date("Y/m/d H:i:s", $v['addtime']); ?></td>
              <td align="center"><?php echo $v['remarks'];?></td>
              <td align="center">
                  <a href="javascript:;" class="butaoin blackuserid" data-name="<?php echo $v['username']; ?>" data-id="<?php echo $v['mobile']; ?>" data-uid="<?php echo $v['uid']; ?>"><?php $this->lan("modify");?></a>
                  <a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/member/blackdel/<?php echo $v['id']; ?>"
                     onClick="return confirm('是否真的移除！');"><?php $this->lan("remove");?></a> </td>
          </tr>
      <?php } ?>
      <?php  if (!$userlist ): ?>
            <tr>
                <td colspan="7" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
    </tbody>
  </table>

</div>
<!--table-list end-->
<!--<div class="btn_paixu"><input type="button" value=" 批量删除 " class="batchDel button" style="margin-bottom: 10px;"></div>-->
<!--<div id="pages"><ul><li>共 --><?php //echo $total; ?><!-- 条</li>--><?php //echo $page->show('one','li'); ?><!--</ul></div>-->

<script>
    // 页面加载完成后美化手动拉黑标签
    $(document).ready(function() {
        $('.table-list tbody td').each(function() {
            if ($(this).text().trim() === '手动拉黑') {
                $(this).addClass('manual-blacklist');
            }
        });
    });
    
    $('.blackuserid').click(function () {
        var mobile = $(this).attr('data-id')
        var uid = $(this).attr('data-uid')
        var name = $(this).attr('data-name')
        var title =  name?name:uid
        var index=  layer.open({
            type: 2,
            title: '【'+title+'】+ <?php $this->lan('Blacklist_management');?>',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['750px', '260px'],
            content: '/?member/member/blacktype/'+uid,
            end:function() {
                location.reload();
            }
        });
    })
    function addbalck() {
        var index2=  layer.open({
            type: 2,
            title: "添加黑名单",
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['750px', '360px'],
            content: '/?member/member/addblackinput/',
            end:function() {
                location.reload();
            }
        });
    }
</script>

</body>
</html>
