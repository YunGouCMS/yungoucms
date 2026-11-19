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
<title><?php echo $this->getPageTitle('管理员列表'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<style>
        tr{height:40px;line-height:40px}
        .input-text{
            width: 150px !important;
        }

        .layui-btn{
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }
        .layui-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
        }
        th{ border:0px solid #000;}
        .table-list {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .butaoin, .butaish {
            padding: 6px 16px !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
            display: inline-block !important;
            margin: 0 4px !important;
        }
        .butaoin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: #fff !important;
        }
        .butaoin:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
        }
        .butaish {
            background: #fee2e2 !important;
            color: #991b1b !important;
        }
        .butaish:hover {
            background: #fecaca !important;
            transform: translateY(-2px) !important;
        }
        .bnoaish {
            background: #f3f4f6 !important;
            color: #9ca3af !important;
            cursor: not-allowed !important;
        }
    </style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>
<form class="layui-form layui-form-pane" action="#">
        <div class="layui-form-item" style="margin-left: 10px;">
            <label class="layui-form-label" style="width: 80px;height: 48px !important;line-height: 30px;"><?php $this->lan("KEY");?></label>
            <div class="layui-input-inline" style="width:230px" >
                <input type="password" name="password" id="keyPassword" maxlength="6" placeholder="<?php $this->lan("tips");?>" autocomplete="off" class="layui-input">
            </div>
             <button id="demo1" type="button" class="layui-btn" lay-submit="" lay-filter="demo1"><?php $this->lan("see");?></button>
            <!-- <button type="button" id="password" style="opacity: 0.01;" class="layui-btn repassword" >修改密码</button> -->
        </div>
</form>
<div class="bk10"></div>

<div class="table-list lr10">
<form name="myform" action="#" method="post">
  <table width="100%" cellspacing="0" style="table-layout: fixed;">
    <thead>
		<tr>
		<th width="70px"><?php $this->lan("sort");?></th>
		<th align="center"><?php $this->lan("name");?></th>
            <th align="center"><?php $this->lan("nickname");?></th>
		<th align="content"><?php $this->lan("Belonging_role");?></th>
		<th align="content" class="is_look" ><?php $this->lan("Most_login");?></th>
        <th align="content" class="is_look" ><?php $this->lan("Last_logged_area");?></th>
		<th align="content"><?php $this->lan("login_time");?></th>
		<th align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
  	<tbody>
    	<?php foreach($AdminList as $k=>$v){ ?>
        <tr>
        <td width="10%" align="center"><?php echo $v['uid']; ?></td>
        <td width="15%" align="center"><?php echo $v['username']; ?></td>
            <td align="center"><?php echo $v['uname'];?></td>
        <td width="10%" align="center"><?php echo $v['name'] ?></td>
        <td width="10%" align="center" class="is_look" >
            <?php echo $v['ip']['ip'];?>
        </td>
            <td align="center" class="is_look" >
                <?php if($v['ip']){?>
                <?php echo $v['ip']['region'];?>-
                <?php echo $v['ip']['city'];?>
                <?php }?>
            </td>
        <td width="20%" align="center"><?php echo date("Y/m/d H:i:s",$v['logintime']); ?></td>
        <td width="15%" align="center">
        <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/user/edit/<?php echo $v['uid']; ?>"><?php $this->lan("modify");?></a>
        <?php if($v['uid']==1){ ?>
        <!--<font class="butaoin bnoaish" color="#cccccc"><?php $this->lan("delete");?></font>-->
        <?php }else{ ?>
        <a class="butaoin butaish a_form_noparam" href="<?php echo G_ADMIN_PATH.'/'.ROUTE_C; ?>/del/<?php echo $v['uid']; ?>"><?php $this->lan("delete");?></a>
        <?php } ?>
        </td>
        </tr>
        <?php } ?>
	</tbody>
</table>
<!-- <div id="pages"><ul><li>共 --><?php //echo $total; ?><!-- 条</li>--><?php //echo $page->show('one','li'); ?><!--</ul></div>-->
</form>
</div><!--table-list end-->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
<script>

$('.is_look').attr('style','display:none');
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;
        //监听提交
        // form.on('submit(demo1)', function(data){
        $("#demo1").click(function(data){
            var value = $("#keyPassword").val();
            $.post('<?php echo G_MODULE_PATH; ?>/quicknav/getnav',{value:value},function (res) {
                const result = JSON.parse(res)
                if(result.code == 200){
                    $('.is_look').attr('style','');
                }else {
                    layer.msg('密码错误')
                    $('.is_look').attr('style','display:none');
                }
            })
            return false;
        });
    });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>   