<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.template{
    display:flex;justify-content: start;align-items: center;
}
.language{
    display:flex;justify-content: start;align-items: center;
}
.show{
    margin-left: 10px;
    margin-right: 10px;
}
input[type="radio"] {
    margin-top: 0px !important;
}
</style>
</head>
<body>
<div class="header lr10">
	<h3 class="nav_icon"><?php $this->lan("title");?></h3>
    <span class="span_fenge lr10"></span>
    <a href="/<?echo _cfg('admindir')?>/template"><?php $this->lan("title");?></a>
    <!-- <span class="span_fenge lr5">|</span> -->
    <!-- <b><a href="/<?echo _cfg('admindir')?>/codes/loginlist" class="adds">登录主题管理</a><b> -->
</div>
<div class="bk10"></div>
<div class="table-list lr10">
    <form name="myform" action="#" method="post" style="font-weight: normal;">
        <table width="100%" cellspacing="0">
            <tr>
                <td width="140" align="center" style="padding-right: 10px"><?php $this->lan("Multi_switching");?></td>
                <td style="display:flex;justify-content: start;align-items: center;margin-bottom: -1px;">
                    <input type="radio" name="template_verify" value="1" <?php if($switch==1){ ?> checked="checked" <?php } ?> />&nbsp;<?php $this->lan("open");?>&nbsp;
                    <input type="radio" name="template_verify" value="0" <?php if($switch==0){ ?> checked="checked" <?php } ?>/>&nbsp;<?php $this->lan("close");?>

                    <div class="template">
                        <p class="show"><?php $this->lan("Display_location");?></p>
                        <div>
                            <input name="" value="1" type="checkbox" checked/>&nbsp;<?php $this->lan("Home_left");?>&nbsp;&nbsp;
			                <input name="" value="1" type="checkbox" checked/>&nbsp;<?php $this->lan("Home_right");?>&nbsp;&nbsp;
                        </div>
                    </div>
                    <input type="submit" style="margin-left: 5px;" value="<?php $this->lan("save");?> " name="dosubmit" class="button">
                </td>   
            </tr>
            <!-- <tr>
                <td width="140" align="right" style="padding-right: 10px">语种切换</td>
                <td style="display:flex;justify-content: start;align-items: center;">
                    <input type="radio" name="language_verify" value="1" <?php if($switch==1){ ?> checked="checked" <?php } ?> />&nbsp;开启&nbsp;
                    <input type="radio" name="language_verify" value="0" <?php if($switch==0){ ?> checked="checked" <?php } ?>/>&nbsp;关闭    
                    <div class="language">
                    <p class="show">展示位置</p>
                    <div>
                         <input name="" value="1" type="checkbox" checked/>&nbsp;首页顶部右侧&nbsp;&nbsp;
			             <input name="" value="1" type="checkbox" checked/>&nbsp;用户中心设置里&nbsp;&nbsp;
                         <input name="" value="1" type="checkbox" checked/>&nbsp;用户中心顶部右侧&nbsp;&nbsp;
                    </div>
                </div>                   
                </td> 
            </tr> -->
        </table>
        
    </form>
</div>

<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
   
    <form action="#" class="layui-form">
        <table width="100%" cellspacing="0" style="font-weight: normal;">
            <thead>
            <tr>
                <th width="150px" align="center"><?php $this->lan("Templates_directory");?></th>
                <th width="320px" align="center"><?php $this->lan("Template_name");?></th>
                <th width="300px" align="center"><?php $this->lan("Template_img");?></th>
                <th width="150px" align="center"><?php $this->lan("HTML_directory");?></th>
                <th width="450px" align="center"><?php $this->lan("Template_color");?></th>
                <th width="100px" align="center"><?php $this->lan("state");?></th>
                <th width="180px" align="center"><?php $this->lan("Template_author");?></th>
                
                
                <!--th width="100px" align="center">操作</th-->
            </tr>
            </thead>
            <tbody>
            <?php foreach($templates as $key=> $temp){?>
                <tr>
                    <td width="100px" align="center"><?php echo $temp['dir']; ?></td>
                    <td width="100px" align="center"><?php echo $temp['name']; ?></td>
                    <td width="100px" align="center">
                        <img class="codeImage" width="20" height="50" src="<?php echo $temp['img'];?>" width="100px">
                    </td>
                    <td width="100px" align="center"><?php echo $temp['html']; ?></td>
                    <td width="100px" align="center">
                         <?php $this->lan("Primary_color");?><div style="margin: 0 15px 0 3px;" id="test-all<?php echo $key; ?>"></div>

                         <?php $this->lan("Gradient");?><div style="margin-left: 3px;" id="test-all2<?php echo $key; ?>"></div>
                    </td>
                    <td width="100px" align="center">
                        <input  type="checkbox"  class="is_state sta" name="is_state" data-template="<?php echo $temp['dir']; ?>" <?php if($temp['dir'] == $thistemp){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $temp['dir'] ?>" lay-text="<?php $this->lan("in_use");?>|<?php $this->lan("not_used");?>">
                    </td>
                    <td width="100px" align="center"><?php echo $temp['author']; ?></td>
                    <!--td width="100px" align="center">
            	 <a  href="<?php echo G_MODULE_PATH; ?>/template/edit/<?php echo $temp['dir']; ?>">修改</a>
            </td-->
                </tr>
                <script>
                    layui.use(['colorpicker'], function(){
                        var olorpicker = layui.colorpicker;
                        olorpicker.render({
                            elem: "#test-all<?php echo $key; ?>"
                            ,color: "<?php echo $temp['color']; ?>"
                            ,format: 'hex'
                            ,done: function(color){
                                $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:1},function (res) {
                                    var result = JSON.parse(res)
                                    if (result.code ==1){
                                        layer.msg('修改成功')
                                        return
                                    }
                                    layer.msg('修改失败')
                                })
                            }
                        });
                        olorpicker.render({
                            elem: "#test-all2<?php echo $key; ?>"
                            ,color: "<?php echo $temp['color1']; ?>"
                            ,format: 'hex'
                            ,done: function(color){
                                $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:2},function (res) {
                                    var result = JSON.parse(res)
                                    if (result.code ==1){
                                        layer.msg('修改成功')
                                        return
                                    }
                                    layer.msg('修改失败')
                                })
                            }
                        });
                    });

                </script>
            <?php }?>
            </tbody>
        </table>
    </form>
</div><!--table-list end-->

<div class="bk10"></div>
<div class="table-list lr10">
    <!--start-->
    <form action="#" class="layui-form">
        <table width="100%" cellspacing="0" style="font-weight: normal;">
            <!--        <input type="button" value=" 批量删除 " class="batchDel button" style="margin-bottom: 10px;">-->
            <thead>
            <tr>
                <th width="150px" align="center"><?php $this->lan("id");?></th>
                <th width="320px" align="center"><?php $this->lan("Theme_name");?></th>
                <th width="300px" align="center"><?php $this->lan("Theme_img");?></th>
                <th width="150px" align="center"><?php $this->lan("Theme_type");?></th>
                <th width="450px" align="center"><?php $this->lan("Theme_color");?></th>
                <!-- <th align="center">渐变色</th> -->
                <th width="100px" align="center"><?php $this->lan("state");?></th>
                <th width="180px" align="center"><?php $this->lan("operation");?></th>
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
                         <?php $this->lan("Primary_color");?><div style="margin: 0 15px 0 3px;display: inline-block;">
                            <div id="test<?php echo $v['id'];?>"></div>
                        </div>
                         <?php $this->lan("Gradient");?><div id="tests<?php echo $v['id'];?>" style="margin-left: 3px;display: inline-block;">
                    </td>
                    <!-- <td align="center"></td> -->

                    <td width="100px" align="center">
                        <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($v['is_enable'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("in_use");?>|<?php $this->lan("not_used");?>">
                    </td>
                    <td align="center">
						<a class="butaoin" href="javascript:;" data-id="<?php echo $v['id']; ?>"  class="details"><?php $this->lan("modify");?></a>
						<a class="butaoin butaish" href="javascript:;" data-id="<?php echo $v['id']; ?>" data-is_enable="<?php echo $v['is_enable'];?>" class="del"><?php $this->lan("delete");?></a>
					</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
    <!-- <div class="btn_paixu"></div> -->
</div><!--table-list end-->
<a href="javascript:;" class="adds"><input type="submit" style="margin-left: 10px;margin-top: 5px;" value="<?php $this->lan("Add_theme");?> " name="dosubmit" class="button"></a>
<a href="/<?echo _cfg('admindir')?>/codes/updateurlloginlog" class="adds"><input type="submit" style="margin-top: 5px;" value="<?php $this->lan("Synchronize_background");?>" name="dosubmit" class="button"></a>

<script>
    layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');

            $.post('<?php echo G_MODULE_PATH; ?>/template/off/'+id,{id:id},function (data) {
                data = JSON.parse(data);
                if (data == 1){
                    layer.msg('修改成功');
                    setTimeout(function(){
                        window.history.go(0);
                    },1500)
                }else{
                    layer.msg('修改失败');
                }
            });
        });

        form.render();

    });
</script>

<!-- 登录主题设置 -->
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
                title: '<?php $this->lan("Add_theme");?>',
                area: ['550px', '521px'],//弹框大小  屏幕宽度的80%，高度的80%；
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
                title: '修改主题模板',
                area: ['450px', '470px'],//弹框大小  屏幕宽度的80%，高度的80%；
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
            var template = $(this).attr("data-template")
            var status = '';
            if (data.elem.checked == true) {
                status = 1;
            }else if(data.elem.checked == false){
                status = 0;
                layer.msg('必须使用一种模版')
                setTimeout(function () {
                    window.history.go(0);
                },1000)
                return;
            }
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/template/setTemplate',{id:id,status:status,template:template},function (data) {
                data = JSON.parse(data)
                if (data.code == 200){
                    layer.msg(data.msg, {icon: 1});
                }else{
                    layer.msg(data.msg,{icon:2});
                }
                setTimeout(function () {
                    window.history.go(0);
                },1000)
            });
        });
        form.render();

    });

    
</script>

<script>
 $('.template').attr('style','display:none');
 $('.language').attr('style','display:none');
 $('input[type=radio][name=template_verify]').change(function () {
        var status = $(this).val();
        switch (status) {
            case '1':
                $('.template').attr('style','');
                break
            case '0':
                $('.template').attr('style','display:none');
                break
            default:
                console.log(status);
            }
        }
 )
 $('input[type=radio][name=language_verify]').change(function () {
        var status = $(this).val();
        switch (status) {
            case '1':
                $('.language').attr('style','');
                break
            case '0':
                $('.language').attr('style','display:none');
                break
            default:
                console.log(status);
            }
        }
 )
</script>
</body>
</html> 