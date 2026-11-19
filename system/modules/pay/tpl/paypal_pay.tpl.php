<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <style>
        .title {
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            background-color: #e2211c;
            padding: 0 13px;
            margin: 0 auto;
            height: 115px;
            line-height: 115px;
        }

        h5 {
            font-size: 48px;
            color: #ffffff;
        }
        .text{
            font-size: 68px;
            margin-left: 30%;
            margin-top: 50%;
            margin-bottom: 10px;
            color: #999;
        }
        .money{
            color: #e2211c;
        }
        .btn {
            width: 80%;
            font-size: 48px;
            line-height: 100px;
            background-color: #e2211c;
            color: #fff;
            border-radius: 6px;
            text-align: center;
            border: none;
        }
        .cel{
            background-color: #999;
        }

    </style>
</head>

<body>
    <div>
        <div class="title">
            <h5>支付确认</h5>
        </div>
        <div class="text">充值金额：<span class="money"><?php echo $res['money']; ?></span></div>
        <div style="margin-top: 40%; width: 100%; text-align: center;">
            <form action="<?php echo $url; ?>" method="post">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <button type="submit" class="btn">确认支付</button>
            </form>
            <div style="height:10px"></div>
            <form action="<?php echo $celurl; ?>" method="get">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <button type="submit" class="btn cel">取消支付</button>
            </form>
        </div>
    </div>
</body>
<script>

</script>

</html>