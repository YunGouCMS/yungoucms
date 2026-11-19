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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>

</head>
<style>
    tbody tr{ line-height:30px; height:30px;}
    .layui-form-onswitch{
        margin-top: 0px!important;
    }
</style>
<body>
<div class="header lr10" style="display: flex;">
     <!--<?php echo $this->headerment();?>-->
     <h3 class="nav_icon"> <?php $this->lan("title"); ?></h3><span class="span_fenge lr10"></span>
     <!---->
    <a href="#"> <?php $this->lan("title"); ?></a>
    <span class="lr10"></span>
   <a href="<?php echo G_MODULE_PATH; ?>/setting/mobile" > <?php $this->lan("SMS_settings"); ?></a>
    <span class="lr10"></span>
     <a href="#" onClick="mobile_check();"> <?php $this->lan("Send_test_SMS"); ?></a>
    <span class="lr10"></span>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
    <!--start-->
    <form action="#" method="post" name="myform" class="layui-form">

        <table width="100%" cellspacing="0">
            <thead>
            <tr>
<!--                <th width="80px">ID</th>-->
                <th width="" align="center"> <?php $this->lan("SMS_category");?></th>
                <th align="center"> <?php $this->lan("SMS_template");?></th>
                <th align="center"> <?php $this->lan("remarks");?></th>
                <th width="" align="center"> <?php $this->lan("status");?></th>
                <th width="30%" align="center"> <?php $this->lan("operation");?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($lists as $v){ ?>
                <tr>
<!--                    <td align="center">--><?php //echo $v['id']; ?><!--</td>-->
                    <td align="left"><?php echo $v['title']; ?></td>
                    <td align="left"><?php echo $v['value'];?></td>
                    <td align="left" style="color:red"><?php echo $v['remark'];?></td>
                    <td width="10%" align="center">
                        <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($v['type'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
                    </td>
                    <td align="center">
                        <a href="javascript:;" class="butaoin" onClick="userListOpen('<?php echo $v['title']; ?>','<?php echo G_MODULE_PATH; ?>/template/templateDetails/<?php echo $v['id']; ?>/')"> <?php $this->lan("modify");?></a>
<!--                        <span class="span_fenge lr5">|</span>-->
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>

<!--    <div class="btn_paixu">-->
<!--        <div style="width:80px; text-align:center;">-->
<!--            <input type="button" class="button" value=" 排序 "-->
<!--                   onclick="myform.action='--><?php //echo G_MODULE_PATH; ?><!--/wap/listorder/dosubmit';myform.submit();"/>-->
<!--        </div>-->
<!--    </div>-->
    <div class="btn_paixu"></div>
</div><!--table-list end-->

<!--支付弹出框-->
<style>
    #paywindow{position:absolute;z-index:999; width:392px;height:375px;display:none;top: 50%;left: 50%;transform: translate(-50%, -50%);}
    #paywindow_b{width:392px;height:375px;background:#2a8aba; filter:alpha(opacity=60);opacity: 0.6;position:absolute;left:0px;top:0px; display:block}
    #paywindow_c{width:380px;height:360px;background:#fff;display:block;position:absolute;left:6px;top:6px;}
    .p_win_title{ line-height:40px;height:40px;background:#f8f8f8;}
    .p_win_title b{float:left}
    .p_win_title a{float:right;padding:0px 10px;color:#f60}
    .p_win_content h1{font-size:25px;font-weight:bold;}
    .p_win_but,.p_win_mes,.p_win_ctitle,.p_win_text{ margin:20px 20px;}
    .p_win_ctitle{overflow:hidden;}
    .p_win_x_b{float:left; width:73px;height:68px;background-repeat:no-repeat;}
    .p_win_x_t{ font-size:18px; font-weight:bold;font-family: "Helvetica Neue",\5FAE\8F6F\96C5\9ED1,Tohoma;color:#f00; text-align:center}
    .p_win_but{ height:40px; line-height:40px;}
    .p_win_but a{ padding:8px 15px; background:#f60; color:#fff;border:1px solid #f50; margin:0px 15px;font-family: "Helvetica Neue",\5FAE\8F6F\96C5\9ED1,Tohoma; font-size:15px; }
    .p_win_but a:hover{ background:#f50}
    .p_win_text a{ font-size:13px; color:#f60}
    .pay_window_quit:hover{ color:#f00;}
    .layui-layer-content {padding-left: 10px;}
</style>
<div id="paywindow">
    <div id="paywindow_b"></div>
    <div id="paywindow_c">
        <div class="p_win_title p_win_x_t"><span style="text-align: center;margin-left: 60px;"><?php $this->lan("Send_test_SMS");?></span><a href="javascript:void();" class="pay_window_quit">[<?php $this->lan("close");?>]</a><b></b></div>
        <div class="p_win_content">
            <div class="p_win_ctitle">
                <li class="p_win_x_t"></li>
            </div>
            <div class="p_win_mes">
                <input type="text" id="ceshi_haoma" class="input-text" style="width:280px;" placeholder="<?php $this->lan("tips1");?>"/>
            </div>
            <div class="p_win_mes">
                <input id="ceshi_con" style="width:280px; height:30px;text-indent:10px" placeholder="<?php $this->lan("tips2");?>"></input><br/>
                <input type="button" value="<?php $this->lan("Submit");?>" class="button" id="ceshi_form" style="display:block;margin: 10px auto;">
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        // var width = ($(window).width()-372)/2;
        // var height = ($(window).height()-442)/2;
        // $("#paywindow").css("left",width);
        // $("#paywindow").css("top",height);
        //
        $(".pay_window_quit").click(function(){
            $("#paywindow").hide();
        });

        var mobile = '<?php echo $mobiles['cfg_mobile_on']; ?>';

        $("td.mobile_on_off").each(function(i){
            if($(this).attr("key") == mobile){
                $(this).html("<span class=\"on\">开启中</span>");
            }else{
                $(this).html("<span class=\"off\">关闭中</span>");
            }
        });

    });
</script>
<!--支付弹出框-->

<script type="text/javascript">

    function mobile_check(){
        $("#paywindow").show();
        return true;
    }

    $("#ceshi_form").click(function(){
        $.ajaxSetup({
            async : false
        });
        var ceshi_haoma=document.getElementById('ceshi_haoma').value;
        var ceshi_con=document.getElementById('ceshi_con').value;
        if(ceshi_con == ''){
            window.parent.message("内容不能为空!",8,2);
            return;
        }
        $.post("<?php echo WEB_PATH.'/'.ROUTE_M; ?>/setting/mobile",{"ceshi_haoma":ceshi_haoma,"ceshi_con":ceshi_con,"ceshi_submit":true},function(data){

            data = jQuery.parseJSON(data);
            if(data[0]==-1){
                window.parent.message(data[1],8,3);
            }else{
                window.parent.message(data[1],1,2);
            }
        });
    });
</script>
<script>
    layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = 2;
            }
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/template/up_mbstatus',{id:id,status:zhi},function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    layer.msg('修改成功');
                }else{
                    layer.msg('修改失败');
                }
            });
        });

        form.render();

    });
</script>

<script>

    function userListOpen(title,url){
        console.log(title)
        var index = layer.open({
            type: 2,
            title: title,
            shadeClose: false,
            shade: false,
            maxmin: false, //开启最大化最小化按钮
            area: ['450px', '190px'],
            content: url,
            end:function() {
                location.reload();
            }
        });
        // layer.full(index);
    }
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 