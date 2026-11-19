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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>

</head>
<style>
    tbody tr {
        line-height: 30px;
        height: 30px;
    }

    .layui-form-onswitch {
        margin-top: 0px !important;
    }
    
    .layui-unselect.layui-form-switch {
        padding-left: 20px;
    }
    .layui-unselect.layui-form-switch.layui-form-onswitch {
        padding: 0 20px 0 0;
    }
    .layui-unselect.layui-form-switch.layui-form-onswitch i {
        transform: translateX(-20px);
    }
</style>
<body>
    <div class="header lr10">
        <?php echo $this->headerment(); ?>
    </div>
    <div class="bk10"></div>
    <div class="table-list lr10">
        <!--start-->
        <form action="#" method="post" name="myform" class="layui-form">

            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="80px">ID</th>
                        <th width="" align="center"> <?php $this->lan("SMS_category", "标题"); ?></th>
                        <th align="center"> <?php $this->lan("SMS_template", "内容"); ?></th>
                        <th align="center"> <?php $this->lan("remarks", "备注"); ?></th>
                        <th width="150px" align="center"> <?php $this->lan("status", "状态"); ?></th>
                        <th width="150px" align="center"> <?php $this->lan("operation", "操作"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lists as $v) { ?>
                        <tr>
                            <td align="center"><?php echo $v['id']; ?></td>
                            <td align="left"><?php echo $v['title']; ?></td>
                            <td align="left"><?php echo $v['value']; ?></td>
                            <td align="left" style="color:red"><?php echo $v['remark']; ?></td>
                            <td width="10%" align="center">
                                    <input 
                                        type="checkbox" 
                                        class="is_state sta" 
                                        name="is_state" <?php if ($v['type'] == 1) { echo 'checked=""';} ?> 
                                        lay-skin="switch" 
                                        lay-filter="is_state" 
                                        data-state="<?php echo $v['id'] ?>" 
                                        lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>"
                                    >
                            </td>
                            <td align="center">
                                <a href="<?php echo G_MODULE_PATH."/template/email_temp_edit/".$v['id']; ?>" class="butaoin" > <?php $this->lan("modify", "修改"); ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="btn_paixu"></div>
    </div><!--table-list end-->
    <script>
        layui.use(['form', 'jquery'], function() {
            var form = layui.form,
                $ = layui.jquery;

            form.render('checkbox');
            form.on('switch(is_state)', function(data) {
                var id = $(this).attr('data-state');
                var zhi = '';
                if (data.elem.checked == true) {
                    zhi = 1;
                } else if (data.elem.checked == false) {
                    zhi = 2;
                }
                $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/template/up_mbstatus', {
                    id: id,
                    status: zhi
                }, function(data) {
                    data = JSON.parse(data);
                    if (data.code == 1) {
                        layer.msg('修改成功');
                    } else {
                        layer.msg('修改失败');
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