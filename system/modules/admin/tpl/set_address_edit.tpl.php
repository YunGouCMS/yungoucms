<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <style>
.layui-form{
    min-height: 500px;
}
.layui-form-select {width: 307px;}
.rightgray {
    display: flex;
    align-items: center;
}
.rightgray div {
    background-color: #f5f5f5;
    border: 1px solid #B4B4B4;
    border-left-width: 0px;
    height: 27px;
    padding: 2px 15px;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    line-height: 28px;
    margin-right: 10px;
}
.rightgray input {
    margin: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    width: 74px;
    min-width: 74px;
    text-align: center;
}
table tbody td *:not(.calendar *):not(.calendar td *), .table-list table tbody td *:not(.calendar *):not(.calendar td *) {
    margin: 0!important;
}
layui-form .layui-form-switch {
    display: inline-block !important;
    margin: 0 !important;
    position: relative !important;
    visibility: visible !important;
    padding-left: 20px !important;
}
.layui-form .layui-form-switch.layui-form-onswitch {
    padding: 0 20px 0 0 !important;
}
.layui-form .layui-form-switch.layui-form-onswitch i {
    transform: translateX(-20px) !important;
}
    </style>
</head>

<body>

<div class="header lr10">

    <?php echo $this->headerment();?>

</div>

<div class="bk10"></div>

<div class="table_form lr10">

    <form action="#" method="post" id="myform" class="layui-form" >

        <table width="100%" cellspacing="0" style="border:none">

            <tr>

                <td width="110" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("logistics_company");?></td>

                <td style="display:flex;"><input type="text" name="log_name" placeholder="<?php $this->lan("logistics_company");?>" class="input-text" id="log_name" value="<?php echo $data['log_name'] ?>"></td>

            </tr>
            <tr>
                <td width="80" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Shipping_fee","是否包邮");?></td>
                <td style="display:flex;">
                    <select name="is_free" id="is_free" lay-filter="is_free">
                        <option value="1" <?php echo $data["is_free"]!=2?'selected':"";?>><?php $this->lan("Free_shipping","包邮");?></option>
                        <option value="2" <?php echo $data["is_free"]==2?'selected':"";?>><?php $this->lan("No_free_shipping","不包邮");?></option>
                    </select>
                </td>         
            </tr>
            <tr class="no_free free_type"  style="<?echo $data["is_free"]!=2 ? 'display:none' : '';?>">
                <td width="80" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Freight_type","运费类型");?></td>
                <td style="display:flex;">
                    <select name="type" id="type" lay-filter="type">
                        <option value="1" <?php echo $data["type"]!=2?'selected':"";?>><?php $this->lan("Fixed_amount","固定金额");?></option>
                        <option value="2" <?php echo $data["type"]==2?'selected':"";?>><?php $this->lan("Fixed_ratio","固定比例");?></option>
                    </select>
                </td>
            </tr>
            <tr class="not_free free" style="<?echo $data["is_free"]!=2 ? 'display:none' : '';?>">
                <td width="80" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Shipping_fee","运费");?></td>
                <td class="rightgray" style="border:0!important;">
                    <span class="money_s" style="<?echo $data["type"]==2 ? 'display:none' : 'display: contents;';?> ">
                        <input type="text" name="money" class="input-text money"  id="money" value="<?php echo $data['money'];?>"></input><div class="money"><?php echo $this->currency['symbol'];?></div>
                    </span>
                    <span class="ratio_s" style="<?echo $data["type"]!=2 ? 'display:none' : 'display: contents;';?>">
                        <input type="text" name="ratio" class="input-text ratio"  id="ratio" value="<?php echo $data['ratio'];?>"></input><div class="ratio"><?php $this->lan("Commodity_value","%*商品价值");?></div>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="80" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("language");?></td>
                <td >
                    <select name="language"  id="language">
                        <?php foreach($languages as $key => $v){ ?>
                            <option value="<?php echo $v['lg_value']; ?>" <?php if($data['language'] == $v['lg_value']){ echo 'selected'; } ?> ><?php echo $v['country']; ?></option>
                        <?php } ?>
                    </select>
                </td>   
            </tr>
            <tr>
                <td width="80" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("state");?></td>
                <td style="display:flex;"><input type="checkbox"  id="is_state" <?php if($data['is_state'] == 1){ echo 'checked=""';} ?>  name="is_state" lay-skin="switch" lay-filter="is_state" value="1" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>"></td>
            </tr>
            <tr>
                <td width="80" align="right" style="text-align: right!important;padding-right: 10px;"></td>
                <td style="display:flex;"><input type="button" value="<?php $this->lan("Submit");?> " id="dosubmit" class="button" style="margin:0!important;"></td>
            </tr>
        </table>

    </form>
</div><!--table-list end-->

<script type="text/javascript">

    layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;
        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            if (data.elem.checked == true) {
                $('#is_state').val('1');
            }else if(data.elem.checked == false){
                $('#is_state').val('2');
            }
        });
        form.on("select(is_free)",function(data){
            if(data.value == 2){
                $(".free_type").css("display","table-row");
                $(".free").css("display","table-row");
            }else{
                $(".free_type").css("display","none");
                $(".free").css("display","none");
            }
        });
        form.on("select(type)",function(data){
            if(data.value == 2){
                $(".ratio_s").css("display","contents");
                $(".money_s").css("display","none");
            }else{
                $(".money_s").css("display","contents");
                $(".ratio_s").css("display","none");
            }
        });
        $('#dosubmit').on('click', function () {
            var log_name = $('#log_name').val();
            var is_state = $('#is_state').val();
            var language = $('#language').val();
            var is_free = $('#is_free').val();
            var money = $('#money').val();
            var ratio = $('#ratio').val();
            var type = $('#type').val();
            var ids = <?php echo $data['id']; ?>;
            var info = {log_name:log_name,is_state:is_state,ids:ids,language:language,is_free:is_free,money:money,ratio:ratio,type:type}
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_address_edit',info,function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(function(){
                        window.location.href = "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_logistics";
                    }, 2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            });
        });
        form.render();
    });

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>