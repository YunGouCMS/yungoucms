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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <style>
        table th {
            border-bottom: 1px solid #eee;
            font-size: 12px;
            font-weight: 100;
            text-align: right;
            width: 200px;
        }

        table td {
            padding-left: 10px;
        }

        input.button {
            display: inline-block
        }
    </style>
</head>

<body>
<div class="header lr10">
    <?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
    <!--start-->
    <form name="myform" action="#" method="post" enctype="multipart/form-data">
        <table width="100%" class="lr10">
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Coupon_name"); ?></td>
                <td style="display:flex;"><input type="text" name="name" value="<?php echo $res['name']; ?>" class="input-text"></td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Usage_type", "优惠券类型"); ?> </td>
                <td style="display:flex;">
                    <select name="model" style="margin:0!important;">
                        <option value="1" <?php echo $res['model'] == "1" ? "selected" : "" ?>><?php $this->lan("Recharge"); ?></option>
                        <option value="2" <?php echo $res['model'] == "2" ? "selected" : "" ?>><?php $this->lan("good"); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Coupon_type", "优惠方式"); ?></td>
                <td style="display:flex;">
                    <select name="type" style="margin:0!important;">
                        <option value="1" <?php echo $res['type'] == "1" ? "selected" : "" ?>> <?php $this->lan("Amount"); ?></option>
                        <option value="2" <?php echo $res['type'] == "2" ? "selected" : "" ?>><?php $this->lan("Discount"); ?></option>
                    </select>
                </td>
            </tr>
            <tr class="money">
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"> <?php $this->lan("Amount"); ?></td>
                <td style="display:flex;"><input type="text" name="money" value="<?php echo $res['money'] ?: 0; ?>" class="input-text"></td>
            </tr>
            <tr class="discount">
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Discount"); ?></td>
                <td><input type="text" name="discount" value="<?php echo $res['discount'] ?: 0; ?>" class="input-text"></td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("use_time","使用时间"); ?></td>
                <td style="display:flex;">
                    <select name="expire_type" id="expire_type" style="margin:0!important;">
                        <option value="1" <?php echo $res['expire_type'] == "1" ? "selected" : "" ?>><?php $this->lan("expire_type1","固定天数"); ?></option>
                        <option value="2" <?php echo $res['expire_type'] == "2" ? "selected" : "" ?>><?php $this->lan("expire_type2","固定时间"); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("class_coupon","商品分类"); ?></td>
                <td style="display:flex;">
                    <select name="class_coupon_id" id="class_coupon_id" style="margin:0!important;">
                        <option value="">选择商品分类</option>
                        <?php foreach($res['classGoodId'] as $v): ?>
                            <option value="<?php  echo  $v['cateid']?>"
                                <?php if($v['cateid'] == $res['class_coupon_id']) {
                                    echo "selected";
                                }else{

                                }?>>
                                <?php echo $v['name']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Validity_period","有效期"); ?></td>
                <td>
                    <div class="expire_type1" style="display:flex;align-items: center;gap: 10px;">
                        <input type="text" style="width:130px" name="day" value="<?php echo $res['day']; ?>" class="input-text"><?php $this->lan("day"); ?>
                        <input type="text" style="width:130px" name="time" value="<?php echo $res['time']; ?>" class="input-text"><?php $this->lan("time"); ?>
                    </div>
                    <div class="expire_type2" style="display: none;display:flex;align-items: center;gap: 10px;">
                        <input type="text" style="width:130px" name="expire_start" id="posttime3" value="<?php echo $res['expire_start']; ?>" class="input-text"><?php $this->lan("start_time","开始时间"); ?>
                        <input type="text" style="width:130px" name="expire_end" id="posttime4" value="<?php echo $res['expire_end']; ?>" class="input-text"><?php $this->lan("end_time","结束时间"); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("receive_time", "领取时间"); ?></td>
                <td style="display:flex;align-items: center;gap: 10px;">
                    <input type="text" style="width:130px" name="receive_start" id="posttime1" value="<?php echo $res['receive_start']; ?>" class="input-text"><?php $this->lan("start_time","开始时间"); ?>
                    <input type="text" style="width:130px" name="receive_end"   id="posttime2" value="<?php echo $res['receive_end']; ?>" class="input-text"><?php $this->lan("end_time","结束时间"); ?>
                </td>
            </tr>

            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("illustrate"); ?></td>
                <td><textarea name="desc" style="text-indent: 8px;height:200px;line-height: 28px;"><?php echo $res['desc']; ?></textarea></td>
            </tr>
            <tr>
                <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"></td>
                <td style="display:flex;"><input type="submit" class="button" name="submit" value=" <?php $this->lan("submit"); ?>" style="margin:0!important;"></td>
            </tr>
        </table>
    </form>
</div><!--table-list end-->

<script type="text/javascript">
    date = new Date();
    Calendar.setup({
        inputField: "posttime1",
        ifFormat: "%Y-%m-%d %H:%M:%S",
        showsTime: true,
        timeFormat: "24"
    });
    Calendar.setup({
        inputField: "posttime2",
        ifFormat: "%Y-%m-%d %H:%M:%S",
        showsTime: true,
        timeFormat: "24"
    });
    Calendar.setup({
        inputField: "posttime3",
        ifFormat: "%Y-%m-%d %H:%M:%S",
        showsTime: true,
        timeFormat: "24"
    });
    Calendar.setup({
        inputField: "posttime4",
        ifFormat: "%Y-%m-%d %H:%M:%S",
        showsTime: true,
        timeFormat: "24"
    });
    function expire_type() {
        var expire_type = $("#expire_type").val();
        if (expire_type == 1) {
            $(".expire_type1").show();
            $(".expire_type2").hide();
        } else {
            $(".expire_type1").hide();
            $(".expire_type2").show();
        }
    }
    $("#expire_type").change(function() {
        expire_type()
    })
    expire_type()

    //
    $("select[name='type']").change(function() {
        ch()
    })

    function ch() {
        var type = $("select[name='type']").val();
        if (type == 1) {
            $(".discount").hide();
            $(".money").show();
        } else {
            $(".discount").show();
            $(".money").hide();
        }
    }
    ch()
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>