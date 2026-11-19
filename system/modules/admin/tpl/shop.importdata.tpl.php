<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
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
    <title>云商品同步</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
</head>
<style>
    .mesbot {
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .botselect {
        margin-left: 0px;
    }
    .progress {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .gressbot {
        width: 60%;
        background: #525252;
        border-radius: 20px;
        height: 30px;
        overflow: hidden;
        position: relative;
        box-shadow: rgb(253 253 253) 0px 0px 5px inset;
    }
    .gresstop {
        height: 100%;
        background-image: linear-gradient(#7ebfff, #6699cc);
        border-radius: 20px;
    }
    .gressbot span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 16px;
        color: #ffffff;
    }
    .gressfont {
        font-size: 15px;
        margin-left: 10px;
    }
    .gressfont span {
        color: #009688;
    }
    .datacode {
        margin: 15px;
        flex-wrap: wrap;
        max-height: 110px;
        overflow: overlay;
        background: #f1f1f1;
    }
    .datacode > div :first-child {
        margin-top: 10px;
    }
    .datacode > div {
        width: 100%;
        margin: 0 0 5px 10px;
    }
    .layui-form-label {
        width: 85px;
    }
</style>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="layui-container">
    <form class="layui-form">
        <div class="layui-form-item mesbot">
            <label class="layui-form-label">上传同步数据</label>
            <div class="layui-input-block botselect">
                <button type="button" class="layui-btn" id="test1">
                    <i class="layui-icon">&#xe67c;</i>上传同步数据excel
                </button>
            </div>
            <!-- <a class="layui-btn synchronous" >同步</a> -->
        </div>
        <!-- <div class="layui-form-item">
            <div class="layui-input-block">

            </div>
        </div> -->
    </form>
    <div class="progress">
        <div class="gressbot">
            <div class="gresstop" style="width: 0%;"></div>
            <span>0%</span>
        </div>
        <div class="gressfont">
            已同步<span>0</span>条数据
        </div>
    </div>
    <div class="progress datacode">
    </div>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

<script>
    //socket链接
    var ws = new WebSocket("ws://120.79.215.90:9504");

    ws.onopen = function()
    {
        // Web Socket 已连接上，使用 send() 方法发送数据
        let data = '{"like":"Ok"}';
        ws.send(data);
    };

    ws.onmessage = function (evt)
    {
        // var received_msg = evt.data;
        // alert("数据已接收...");
        // console.log(received_msg);
        var data = JSON.parse(evt.data);
        console.log(data);
        switch (data) {
            case 'number':
            // console.log(data);
            default:
                if (data.code !== undefined){
                    var datapercen = data.data.percentage
                    if(datapercen !== undefined) {
                        var percennum = parseInt(datapercen) + '%';
                        $('.gresstop').css('width',percennum);
                        $('.gressbot span').text(percennum);
                        $('.gressfont span').text(data.data.count);
                    }
                    var dcode = '';
                    if (data.code == 101 ) {
                        dcode += '<div><span style="ff0000">'+data.msg+'</span></div>';
                    } else if (data.code != 201 ) {
                        dcode += '<div><span>'+data.msg+'</span></div>';
                    } else {
                        if (data.data == 1) {
                            dcode += '<div><span>'+data.msg+'</span></div>';
                        } else if (data.data.code == 'goodsCount') {
                            dcode += '<div><span>'+data.msg+'</span><span>共'+data.data.data+'条</span></div>';
                        } else {
                            dcode += '<div><span>'+data.data.data+'</span></div>';
                        }
                    }
                    $('.datacode').append(dcode);
                    $('.datacode').scrollTop($('.datacode')[0].scrollHeight);
                }
        }
    };

    ws.onclose = function()
    {
        // 关闭 websocket
    };

    //Demo
    layui.use(['form','upload','layer'], function(){
        var upload = layui.upload;
        $('.synchronous').on('click',function () {
            let formData = $('.language').val();
            let data = '{"language":"' + formData + '"' +  ',"getShopListData":"getShopListData"}';
            ws.send(data);
        });

        //执行实例
        var uploadInst = upload.render({
            elem: '#test1' //绑定元素
            ,url: '<?php echo "/".ROUTE_M."/".ROUTE_C."/upload_excel";?>' //上传接口
            ,exts: 'xls|excel|xlsx'
            ,accept: 'file' //普通文件
            ,done: function(res){
                console.log(res);
                if(res.code == 1){
                    layer.msg(res.msg,{
                        time:2000
                    },function () {
                        let data = '<?php echo $documentRoot; ?> ';
                        ws.send(data);
                    });
                }else{
                    layer.msg(res.code);
                }
            }
            ,error: function(){
                //请求异常回调
            }
        });

    });

</script>
</html>