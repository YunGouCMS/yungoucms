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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="60" >
    <title>异常商品列表<?php $this->lan("description");?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
    <span></span>
    <span> <?php $this->lan("Return_lists");?><?php echo $datehh;?> （<span id="countdown">60s</span>）</span>
</div>
<div class="table-list lr10" style="margin-top: 10px">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="3%"><?php $this->lan("ID");?></th>
                <th width="27%"><?php $this->lan("Product_title");?></th>
                <th width="10%"><?php $this->lan("participated_total");?></th>
                <th width="5%"><?php $this->lan("Unit_price");?></th>
                <th width="10%"><?php $this->lan("Time_limited");?></th>
                <th width="10%"><?php $this->lan("revoke");?></th>
            </tr>
        </thead>
        <tbody>
        <?php  foreach ($shoplist as $k=>$v){ ?>
            <tr style="text-align: center">
                <td width="5%"><?php echo $v['id'];?></td>
                <td width="25%" align="left"><?php echo $v['title'];?></td>
                <td width="10%"><?php echo $v['canyurenshu'];?>/<?php echo $v['zongrenshu'];?></td>
                <td width="5%"><?php echo $v['yunjiage'];?></td>
                <td width="5%"><?php echo $v['money'];?></td>
                <td width="10%"><?php echo $v['error'];?></td>
            </tr>
        <?php } ?>
        <?php  if (!$shoplist ): ?>
            <tr>
                <td colspan="6" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
        </tbody>

    </table>
</div>
<div id="date">

</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
    (function () {
        var countdown = document.getElementById("countdown"), second = 60, timer = null;
        timer = setInterval(function () {
            if (second > 0) {
                second--;
                second = second<10 ? '0'+second :second
                countdown.innerHTML = second;
            } else {
                clearInterval(timer);
                timer = null;
            }
        }, 1000);
    })()
</script>
</html>