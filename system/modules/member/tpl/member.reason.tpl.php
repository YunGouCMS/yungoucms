<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <style>
        .layui-btn{
            background-color: #080808;
        }
        th{ border:0px solid #000;}

    </style>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
</head>
<body>
<div style="margin: 20px">

    <div class="layui-form layui-form-pane" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label" style="margin-right: 10px">审核意见</label>
            <div class="layui-input-inline">
                <textarea name="reason" style="width: 300px;height: 200px;padding:5px"></textarea>
<!--                <input type="text" name="password" placeholder="请输入审核意见" autocomplete="off" class="layui-input">-->
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $id ?>">

        <div class="layui-form-item">
            <!--            <label class="layui-form-label">确认密码</label>-->
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" lay-submit="" onclick="submit()" lay-filter="demo1" style="margin-left: 120px;">提交</button>
            </div>
        </div>
    </div>
</div>
<script>
    function submit(){
        var reason = $("textarea[name='reason']").val();
        var id = $("input[name='id']").val();
        $.post('/member/member/reason',{reason:reason,id:id},function (res) {
            if(res == 1){
                layer.msg('结果已保存')
                var index = parent.layer.getFrameIndex(window.name);
                setTimeout("parent.layer.closeAll(index)",1500);
                window.parent.location.reload();
            }else{
                layer.msg('操作失败');
            }
        })
    }
</script>
</body>
</html>