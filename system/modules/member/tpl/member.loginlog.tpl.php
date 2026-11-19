<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>用户登录日志 ( <?php echo count($id_list);?>) 条</legend>
</fieldset>
<ul class="layui-timeline">
    <?php foreach ($id_list as $v) {?>
        <li class="layui-timeline-item">
            <i class="layui-icon layui-timeline-axis"></i>
            <div class="layui-timeline-content layui-text">
                <h3 class="layui-timeline-title">登录时间 <?php echo date('Y-m-d H:i:s',$v['login_time'])?></h3>
                <p>
                <table class="layui-table" lay-size="sm">
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <td>IP</td>
                        <td><?php echo $v['ip']?></td>
                    </tr>
                    <tr>
                        <td>国家</td>
                        <td><?php echo $v['country']?></td>
                    </tr>
                    <tr>
                        <td>省份</td>
                        <td><?php echo $v['region']?></td>
                    </tr>
                    <tr>
                        <td>城市</td>
                        <td><?php echo $v['city']?></td>
                    </tr>
                    <tr>
                        <td>运营商</td>
                        <td><?php echo $v['isp']?></td>
                    </tr>
                    </tbody>
                </table>
                </p>
            </div>
        </li>
    <?php }?>
</ul>

</body>
</html>