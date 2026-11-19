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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>
<style>
    .layui-form-onswitch{
        margin-top: 0px!important;
    }
</style>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>

<div class="bk10"></div>

<div class="table-list lr10">
    <form name="myform" action="#" method="post" class="layui-form">
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th width="10%"><?php $this->lan("sort");?></th>
                <th width="15%" align="content"><?php $this->lan("logistics_company");?></th>
                <th width="20%" align="content"><?php $this->lan("add_time");?></th>
                <th width="10%" align="content"><?php $this->lan("language");?></th>
                <th width="10%" align="content"><?php $this->lan("state");?></th>
                <th width="15%"><?php $this->lan("operation");?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data as $v){ ?>
                <tr>
                    <td width="10%" align="center"><?php echo $v['id']; ?></td>
                    <td width="15%" align="center"><?php echo $v['log_name']; ?></td>
                    <td width="20%" align="center"><?php echo date("Y/m/d H:i:s",$v['create_time']); ?></td>
                    <td width="15%" align="center"><?php echo $v['language']; ?></td>
                    <td width="10%" align="center">
                        <input  type="checkbox" style=""  class="is_state" name="is_state" <?php if($v['is_state'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
                    </td>
                    <td width="15%" align="center">
						<a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/wap/set_address_edit/<?php echo $v['id']; ?>"><?php $this->lan("modify");?></a>
						<a class="butaoin butaish" href="javascript:;" class="del" data-id="<?php echo $v['id']?>"><?php $this->lan("delete");?></a>
					</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div id="pages"><ul><li>共<?php echo $total; ?>条</li><?php echo $page->show('one','li'); ?></ul></div>
    </form>
</div>
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
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
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/states',{id:id,zhi:zhi},function (data) {
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

        $('.del').on('click',function () {
            var id = $(this).data('id');
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_address_delete',{id:id},function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(function(){
                        window.location.href = "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_logistics";
                    }, 1500);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            })
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