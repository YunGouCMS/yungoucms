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

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>

<style>

body{ background-color:#fff}

tr{ text-align:center}

.button{border-radius: 3px; color: #FFF; background: #161e22; padding-left:15px; padding-right:15px; margin-right: 15px;float: right;}
.button:hover{background: #222d32;}
</style>

</head>

<body>

<div class="header lr10">

	<?php echo $this->headerment();?>
	<a href="<?php echo G_MODULE_PATH; ?>/wechat/hbadd" style="float:right; margin-right:10px; background:#222d32; color:#FFF;height:30px;border-radius:3px;padding-left:10px; padding-right:10px;line-height:30px;margin-top:5px;">添加红包类型</a>
</div>

<div class="bk10"></div>

<form action="#" method="post" name="myform">

<div class="table-list lr10">

        <table width="100%" cellspacing="0">

     	<thead>

        		<tr>

                    <th width="3%">ID</th>                          

                    <th width="20%">红包名称</th>  

                    <th width="6%">红包金额</th>             

                    <th width="10%">发放类型</th>

                    <th width="7%">红包总数</th>

                    <th width="7%">已发放数目</th>

                    <th width="10%">发放开始时间</th>

                    <th width="10%">发放结束时间</th>

                    <th width="15%">操作</th>

				</tr>

        </thead>
        <tbody>				

        	<?php foreach($wechat as $v) { ?>

            <tr>
                <td><?php echo $v['type_id'];?></td>

                <td><?php echo $v['type_name'];?></td>

                <td><?php echo $v['type_money']; ?></a></td>

                <td><?php if($v['send_type'] == 1 ){ echo '微信关注红包';  }?></a></td>

                <td><?php echo $v['total']; ?></a></td>

                <td><?php echo $v['getnum']; ?></a></td>

                <td><?php echo date('Y-m-d',$v['send_start_date']);?></td>

                <td><?php echo date('Y-m-d',$v['send_end_date']);?></td>

                <td class="action">
                <a href="<?php echo G_ADMIN_PATH; ?>/wechat/hbadd/<?php echo $v['type_id'];?>">修改</a>
                <span class='span_fenge lr5'>|</span>
                [<a href="<?php echo G_ADMIN_PATH; ?>/wechat/hbdel/<?php echo $v['type_id'];?>">删除</a>]
	   <span class='span_fenge lr5'>|</span>
                [<a href="<?php echo G_ADMIN_PATH; ?>/wechat/baobiao/<?php echo $v['type_id'];?>">查看发放报表</a>]
                </td>

            </tr>

            <?php } ?>

        </tbody>

     </table>

            </tr>

        </tbody>

     </table>     

    </form>

    	<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>

</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 