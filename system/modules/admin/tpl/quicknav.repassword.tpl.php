<!DOCTYPE html>
<html>
<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta charset="utf-8">
    <title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <style>
        .layui-btn{
            background-color: #080808;
        }
        th{ border:0px solid #000;}
        .layui-form-label{
            width: 140px!important;
        }
    </style>
</head>
<body>
<div style="margin: 20px">

    <div class="layui-form layui-form-pane" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label"> <?php $this->lan("Current_password");?></label>
            <div class="layui-input-inline">
                <input type="text"  maxlength="6"  name="password" placeholder="<?php $this->lan("tips14");?>" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("New_password");?></label>
            <div class="layui-input-inline">
                <input type="text"  maxlength="6"  name="newpassword" placeholder="<?php $this->lan("tips12");?>" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Confirm_password");?></label>
            <div class="layui-input-inline">
                <input type="text"  maxlength="6"  name="newtwopassword" placeholder="<?php $this->lan("tips13");?>" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
<!--            <label class="layui-form-label">确认密码</label>-->
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" lay-submit="" onclick="submit()" style="background-color:#3C8DBC" lay-filter="demo1"><?php $this->lan("Submit");?></button>
            </div>
        </div>
    </div>
</div>
<script>
    function submit(){
        var oldpassword = $("input[name='password']").val();
        var newpassword = $("input[name='newpassword']").val();
        var newtwopassword = $("input[name='newtwopassword']").val();
        if(newpassword != newtwopassword){
            return layer.msg('两次输入的密码不一致')
        }
        if(oldpassword == newpassword){
            return layer.msg('旧密码不能用于新密码')
        }
        var val = {
            oldpassword:oldpassword,
            newpassword:newpassword,
            newtwopassword:newtwopassword,
        };
        $.post('<?php echo G_MODULE_PATH; ?>/quicknav/repasswordPost',{val:val},function (res) {
            if(res == 1){
                layer.msg('修改成功')
                setTimeout(function(){
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                },2000)
            }else{
                layer.msg('修改失败');
            }
        })
    }
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>