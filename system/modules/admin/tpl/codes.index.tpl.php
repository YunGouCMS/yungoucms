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
    <!-- 后台各页面详细UI升级样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <!-- 通知消息系统脚本（必须在layer之后加载） -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
    <style>
        .inits{
            width,height: 100%;
            display: flex;
			flex-wrap: wrap;
        }
        .msgs{
            width: 214px;
			border: 4px solid #ffffff;
            background: #ecf1f5;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
        }
        .bgClass{
            border: 4px solid #d5dfe8;
            /*border-image: linear-gradient(to bottom, #f3064a, rgba(87, 138, 239, 0)) 1;*/
        }
		.divImage {
			height: 87%;
			display: flex;
			align-items: center;
			justify-content: center;
		}
        .codeImage{
            max-width: 97%;
            max-height: 97%;
        }
		.buttoncon {
			display: flex;
			justify-content: space-between;
			padding: 5px;
			gap: 5px;
		}
        .buttonDiv{
            width: 50%;
        }
        .starts .del{
            width: 50%;
        }
		.nav_icon{
		    font-weight: bold;
		}
    </style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>

</div>
<div class="inits">
    <?php foreach ($lists as $v){?>
    <div class="table_form lr10 msgs <?php if($v['is_selected'] == 1){?> bgClass <?php }?> ">
        <div class="divImage"><img class="codeImage" src="<?php echo $v['code_img_url'];?>"></div>
        <div class="buttoncon">
			<div class="buttonDiv">
			    <input type="button" style="background:#3C8DBC;margin:0!important;min-width: auto;padding:0!important;" value="<?php echo $v['values'];?>" data-id="<?php echo $v['id'];?>" class="layui-btn layui-btn-fluid starts">
			</div>
			<div class="buttonDiv">
			    <input type="button" value="<?php $this->lan("delete");?>" data-is_selected="<?php echo $v['is_selected'];?>"  data-id="<?php echo $v['id'];?>" class="layui-btn layui-btn-fluid layui-btn-danger del" style="margin:0!important;min-width: auto;padding:0!important;">
			</div>
		</div>
    </div>
    <?php }?>
</div>
<script>
    $(".adds").on("click",function(){
        layui.use('layer', function(){
            var layer = layui.layer;
            layer.open({
                type: 2, 
                title: '分享图片(可能会存在图片背景图保存不了请跟换图片格式)',
                area: ['40%', '700px'],//弹框大小  屏幕宽度的80%，高度的80%；
                content: "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/add"
            });
        });  
    });

    //启动
    $(".starts").on("click",function () {
        var index = ''
        layer.msg('正在设置，请耐心等待！',{time:1000},function () {
            index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        });
        let id = $(this).data('id');
        $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/installStart',{id:id},function (res) {
            res = JSON.parse(res);
            if(res.code == 1){
                layer.msg(res.msg,{icon:1,time:1500},function () {
                    location.reload();
                });

            }else{
                layer.close(index);
                layer.msg(res.msg,{icon:2,time:1500});
            }
        })
    })

    //删除
    $(".del").on("click",function () {
        let id = $(this).data('id');
        let is_selected = $(this).data('is_selected');
        layer.confirm('确认删除分享图？', {
            btn: ['确认','在想想'] //按钮
        }, function(){
            var index = ''
            layer.msg('正在删除，请耐心等待！',{time:1000},function () {
                index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            });
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/del',{id:id,is_selected:is_selected},function (res) {
                res = JSON.parse(res);
                if(res.code == 1){
                    layer.msg(res.msg,{icon:1,time:1500},function () {
                        location.reload();
                    });

                }else{
                    layer.close(index);
                    layer.msg(res.msg,{icon:2,time:1500},function(){
                        location.reload();
                    });
                }
            })
        },);

    })
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>