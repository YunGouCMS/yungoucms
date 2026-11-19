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

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <style>
        .layui-btn-normal {
            background-color: #000000;
        }
    </style>
</head>
<body style="padding-right:50px">
<div >
    <form id="black"  class="layui-form" action="#" lay-filter="example">
        <!--<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">-->
        <!--    <legend><h3><?php echo $data['title'];?></h3></legend>-->
        <!--</fieldset>-->
        <div class="layui-form-item">
            <!--            <label class="layui-form-label">选项</label>-->
            <div class="layui-input-block" id="blacklist" style="margin-left:30px;margin-top:10px">
                <input type="hidden" id="userid" name="id" value="<?php echo $data['id'];?>">
                <input placeholder="<?php echo $data['desc'];?>" name="value" value="<?php echo $data['value'];?>" class="input-text" style="width:400px" ></input>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block" style="margin-left:33px">
                <button type="button" class="layui-btn layui-btn-normal" id="LAY-component-form-getval"><?$this->lan("Submit",'提交');?></button>
            </div>
        </div>
    </form>
</div>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script>
    // layui.use(['form', 'layedit', 'laydate'], function(){
    //     var form = layui.form
    //         ,layer = layui.layer
    //         ,layedit = layui.layedit
    //         ,laydate = layui.laydate;

    //     });
        $('#LAY-component-form-getval').on('click', function(){
            console.log(1);
            var data = $('#black').serialize();
            $.post('<?php echo G_MODULE_PATH; ?>/template/templateDetails/<?php echo $data['id'] ?>/',data,function (res) {
                var result = JSON.parse(res)
                if (result.code==1){
                    layer.msg('修改模板成功')
                    setTimeout(()=>{
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭窗口
                        parent.layer.close(index);
                    },1500)

                }else {
                    layer.msg(result.msg?result.msg:'修改失败')
                }
            })
        });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>
