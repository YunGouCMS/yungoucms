<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
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
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>
<style>
.content-box{
    margin-top:10px;
   position: relative;
   	/*div:nth-child(4){*/
}
.content-box  button{
       width:60px; background:#3C8DBC ;color:#fff;border:none;margin-left: -74px;line-height: 25px;border-radius: 5px;cursor: pointer;
   }
.content-box input:nth-child(1){
       width:300px;height: 35px;line-height: 35px;text-indent: 5px;margin-left: 10px;
   }
.content-box input:nth-child(2){
       opacity: -1.5;position:absolute;top:10px;left:325px; width: 70px;
   }
   .layui-inline.actvicol>div {border-color:#3C8DBC;}
</style>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div style="padding:20px;font-size: 14px;">
		<div class="content-box">
        <?php $this->lan("Button_color");?>
			<div style="margin: 0 15px 0 3px;" id="test-all<?php echo $key; ?>" class="testall actvicol"></div>
			<script>
                layui.use(['colorpicker'], function(){
                    var olorpicker = layui.colorpicker;
                    olorpicker.render({
                        elem: "#test-all<?php echo $key; ?>"
                        ,color: "<?php echo $temp['color']; ?>"
                        ,format: 'hex'
                        ,done: function(color){
                            $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:1},function (res) {
                                var result = JSON.parse(res)
                                if (result.code ==1){
                                    $('.testall').removeClass('actvicol')
                                    $('#test-all<?php echo $key; ?>').addClass('actvicol')
                                    layer.msg('修改成功')
                                    return
                                }
                                layer.msg('修改失败')
                            })
                        }
                    });
                });
            </script>
		</div>
		<div  class="content-box">
			 <?php $this->lan("Text_color");?>
			<div style="margin-left: 3px;" id="test-all2<?php echo $key; ?>" class="testall2 actvicol"></div>
    		<script>
                layui.use(['colorpicker'], function(){
                    var olorpicker1 = layui.colorpicker;
                    olorpicker1.render({
                        elem: "#test-all2<?php echo $key; ?>"
                        ,color: "<?php echo $temp['color1']; ?>"
                        ,format: 'hex'
                        ,done: function(color){
                            $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:2},function (res) {
                                var result = JSON.parse(res)
                                if (result.code ==1){
                                    $('.testall2').removeClass('actvicol')
                                    $('#test-all2<?php echo $key; ?>').addClass('actvicol')
                                    layer.msg('修改成功')
                                    return
                                }
                                layer.msg('修改失败')
                            })
                        }
                    });
                });
            </script>
		</div>
		 <input type="submit" class="button" style="margin-top:10px!important" name="submit" value="<?php $this->lan("Submit","提交");?>" >
	</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>