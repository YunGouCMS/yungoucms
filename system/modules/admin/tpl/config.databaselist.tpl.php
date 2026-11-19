<!doctype html>
<html lang="en">
<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="10" >
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/to-update.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/circleChart.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/messageModal/font/iconfont.css" type="text/css">

    <script src="<?php echo G_PLUGIN_PATH; ?>/messageModal/message.js"></script>
    <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>

    <style>

    </style>
</head>
<body>
<div>

    <table class="layui-hide" id="test" lay-filter="test"></table>

    <script type="text/html" id="state">
        {{#  if(d.state == 1 ){ }}
            <buttom class="layui-btn  layui-btn-xs layui-btn-radius layui-btn-disabled">已停止</buttom>
        {{#  } else if(d.state == 2) { }}
            <buttom class="layui-btn  layui-btn-xs layui-btn-radius">
                <i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i> 进行中
            </buttom>
        {{#  } else { }}
            <buttom class="layui-btn  layui-btn-xs layui-bg-green layui-btn-radius">已完成</buttom>
        {{#  } }}
    </script>

    <script type="text/html" id="barDemo">
        {{#  if(d.state == 1 ){ }}
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="execute">执行</a>
        {{#  } else if(d.state == 2) { }}
            <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="stop">停止</a>
        {{#  } }}
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
    layui.use('table', function(){
        var table = layui.table;

        var tableIns = table.render({
            elem: '#test'
            ,url:'<?php echo G_MODULE_PATH; ?>/database/getDelData'/*tpa=https://www.layui.site/test/table/demo1.json*/
            // ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
            ,title: '清除'
            ,cols: [[
                 {field:'deltit', title:'删除表名称',  edit: 'text'}
                ,{field:'isaddtime', title:'任务添加时间',edit: 'text'}
                ,{field:'deltime', title:'需要清除时间',  edit: 'text', sort: true}
                ,{field:'oktime', title:'完成时间', edit: 'text', sort: true}
                ,{field:'state', title:'状态', width:100,toolbar: '#state'}
                ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
            ]]
            ,page: false
        });

        //重载表格
        // tableIns.reload();

        //监听行工具事件
        table.on('tool(test)', function(obj){
            var data = obj.data;
            //console.log(obj)
            console.log(data)
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post('<?php echo G_MODULE_PATH; ?>/database/tabdel',{id:data.id,uuid:data.uuid},function (result) {
                        var result = JSON.parse(result)
                        if(result.code== 1){
                            obj.del();
                            layer.close(index);
                            tableIns.reload();
                        }
                        layer.msg(result.msg)
                    })

                });
            } else if(obj.event === 'execute'){
                $.post('<?php echo G_MODULE_PATH; ?>/database/stop',{state:2,uuid:data.uuid},function (result) {
                    var result = JSON.parse(result)
                    if(result.code== 1){
                        tableIns.reload();
                    }
                    layer.msg(result.msg)
                })
            }else if(obj.event === 'stop'){
                $.post('<?php echo G_MODULE_PATH; ?>/database/stop',{state:1,uuid:data.uuid},function (result) {
                    var result = JSON.parse(result)
                    if(result.code== 1){
                        tableIns.reload();
                    }
                    layer.msg(result.msg)
                })
            }
        });
    });
</script>

</html>