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
    <title>Document</title>

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>

</head>
<body>

<!--    -->
    <button type="button" id="imgHeadPhoto2" class="layui-btn layui-btn-normal" onclick="uploadimg('上传商品图片','#imgHeadPhoto2')">上传图片</button>
<!--    -->
    <input id="imgHeadPhoto2-lack">
<!--    -->
    <input id="imgHeadPhoto2-complete">
<!--    -->
    <img src="" id="imgHeadPhoto2-img">

    <hr>
<blockquote class="layui-elem-quote layui-text">
    <h3>多文件上传</h3>
</blockquote>

    <button type="button" class="layui-btn layui-btn-normal" onclick="uploadFiles('上传商品图片','banner','#imgarr')">多图上传</button>
    <div id="imgarr">

    </div>

    <hr>
    <div class="layui-upload-drag" id="test10" onclick="uploadimg('上传商品图片','#imgsss')">
        <i class="layui-icon"></i>
        <p>点击上传，或将文件拖拽到此处</p>
        <div class="layui-hide" id="uploadDemoView">
            <hr>
            <img src="" id="imgsss-img" alt="上传成功后渲染" style="max-width: 196px">
        </div>
    </div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
        console.log(Win)
</script>

</html>