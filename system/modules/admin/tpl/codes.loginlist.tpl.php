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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <style>
        td {font-weight: initial;}
        
        .layui-layer-iframe iframe{
            /*height: 350px !important;*/
        }
        .layui-unselect.layui-form-switch {
            padding-left: 25px;
        }
        .layui-unselect.layui-form-switch.layui-form-onswitch {
            padding: 0 25px 0 5px;
        }
        .layui-unselect.layui-form-switch.layui-form-onswitch i {
            transform: translateX(-20px);
        }
    </style>
</head>
<body>
<div class="header" style="position: relative;">
    <h3 class="nav_icon"><?php $this->lan("Login_theme_management");?></h3><span class="span_fenge"></span>
    <!--<span class="span_fenge lr10"></span>-->
    <!--<a href="/<?echo _cfg('admindir')?>/codes/loginlist"><?php $this->lan("Login_theme_management");?></a>-->
    <span class="span_fenge lr5"></span>
     <!--href="/<?echo _cfg('admindir')?>/template"-->
    <b><a href="javascript:;" class="adds1"><?php $this->lan("title");?></a><b>
        <div style="position: absolute;top:0px;right: 15px;">
             <b><a href="javascript:;" class="adds" style="background:red"><?php $this->lan("Add_theme");?></a><b>
             <!--<b><a href="/<?echo _cfg('admindir')?>/codes/updateurlloginlog" class="adds"><?php $this->lan("Synchronize");?></a><b>-->
        </div>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
    <!-- start -->
    <form action="#" class="layui-form">
       
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th align="center"><?php $this->lan("id");?></th>
                <th align="center"><?php $this->lan("Theme_name");?></th>
                <th align="center"><?php $this->lan("Theme_img");?></th>
                <th align="center"><?php $this->lan("Theme_type");?></th>
                <th align="center"><?php $this->lan("Theme_color");?></th>
                <th align="center"><?php $this->lan("state");?></th>
                <th align="center"><?php $this->lan("operation");?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($recordlist AS $v) {	?>
                <tr>
                    <td align="center"><?php echo $v['id'];?></td>
                    <td align="center"><?php echo $v['title'];?></td>
                    <td align="center"><?php if(!empty($v['bgimg'])){?><img src="<?php echo $v['bgimg'];?>" style="width: 100px;height: 50px;"><?php }?></td>
                    <td align="center"><?php echo $v['is_bg'];?></td>
                    <td align="center">
                        <?php $this->lan("Primary_color");?> <div style="margin: 0 15px 0 3px;display: inline-block;">
                            <div id="test<?php echo $v['id'];?>"></div>
                        </div>
                        <?php $this->lan("Gradient");?> <div id="tests<?php echo $v['id'];?>" style="margin-left: 3px;display: inline-block;">
                    </td>
                    <td width="100px" align="center">
                        <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($v['is_enable'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("in_use");?>|<?php $this->lan("not_used");?>">
                    </td>
                    <td align="center"><a class="butaoin details" href="javascript:;" data-id="<?php echo $v['id']; ?>" ><?php $this->lan("modify");?></a>
                        <a class="butaoin butaish del" href="javascript:;" data-id="<?php echo $v['id']; ?>" data-is_enable="<?php echo $v['is_enable'];?>"><?php $this->lan("delete");?></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
    <div class="btn_paixu"></div>
</div>
<!-- 11table-list end -->

<script>
    layui.use(['layer','colorpicker'], function(){
        var layer = layui.layer
            ,colorpicker = layui.colorpicker;
        $("#checkAll").on('change', function () {
            if ($(this).is(":checked")) { // 全选
                $(".checkOne").prop("checked",true);
            } else { // 反选
                $(".checkOne").prop("checked",false);
            }
        });

        <?php foreach($recordlist AS $v) {	?>
        //RGB 、RGBA
        colorpicker.render({
            elem: '#test<?php echo $v["id"];?>'
            ,color: '<?php echo $v['color1'];?>'
            ,format: 'rgb' //默认为 hex
        });
        colorpicker.render({
            elem: '#tests<?php echo $v["id"];?>'
            ,color: '<?php echo $v['color2'];?>'
            ,format: 'rgb'
            ,alpha: true //开启透明度滑块
        });
        <?php }?>

        $(".adds").on("click",function(){
            layer.open({
                type: 2,
                title: '<?php $this->lan('Add_theme');?>',
                area: ['550px', '480px'],//弹框大小  屏幕宽度的80%，高度的80%；
                content: "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginadd"
            });
        });

        //启用
        $(".is_enable").on("click",function () {
            let id = $(this).data("id");
            let is_enable = $(this).data("is_enable");

            if(is_enable == 1){
                layer.msg("已经是启用状态，无需再次设置",{time:2000});
                return ;
            }

            var index = ''
            layer.msg('修改中请稍等片刻',{time:1000},function () {
                index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            });

            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/updateState',{id:id},function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    layer.msg(data.msg, {icon: 1,time:2000},function(){
                        // parent.location.reload();//刷新父页面，注意一定要在关闭当前iframe层之前执行刷新
                        // parent.layer.close(index); //再执行关闭
                        location.assign("<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginlist")
                    });
                }else{
                    layer.close(index);
                    layer.msg(data.msg,{icon:2});
                }
            });
        });

        //删除
        $('.del').on('click',function () {
            let id = $(this).data("id");
            let is_enable = $(this).data("is_enable");
            if(is_enable == 1){
                layer.msg("已经是启用状态，不能被删除",{time:2000});
                return ;
            }
            // layer.confirm('确认删除吗？', {
            //     btn: ['确认','在想想']
            // }, function(){
            var index = ''
            layer.msg('正在删除，请耐心等待！',{time:1000},function () {
                index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            });
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginDel',{id:id},function (res) {
                res = JSON.parse(res);
                if(res.code == 1){
                    layer.msg(res.msg,{icon:1,time:1500},function () {
                        // location.reload();
                        location.assign("<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginlist")
                    });

                }else{
                    layer.close(index);
                    layer.msg(res.msg,{icon:2,time:1500});
                }
            })
            // },);
        });

        //b编辑
        $('.details').on('click',function () {
            let id = $(this).data("id");
            layer.open({
                type: 2,
                title: '<?php $this->lan('Modify_theme_template');?>', 
                area: ['450px', '440px'],//弹框大小  屏幕宽度的80%，高度的80%；
                content: "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginEditor/" + id
            });
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
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/updateState',{id:id},function (data) {
                layer.msg('修改成功')
                setTimeout(function () {
                    window.history.go(0);
                },1500)
                //location.assign("<?php //echo WEB_PATH . G_ADMIN_DIR; ?>///codes/loginlist")
                // if (data.code == 1){
                //     layer.msg(data.msg, {icon: 1,time:2000},function(){
                //     });
                // }else{
                //     layer.msg(data.msg,{icon:2});
                // }
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