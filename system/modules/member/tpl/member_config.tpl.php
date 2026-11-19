<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <style>
        table th {
            border-bottom: 1px solid #eee;
            font-size: 12px;
            font-weight: 100;
            text-align: center;
            width: 200px;
        }

        table td {
            padding-left: 10px;
        }

        input.button {
            display: inline-block
        }
        .layui-unselect.layui-form-switch {
            padding-left: 20px;
        }
        .layui-unselect.layui-form-switch.layui-form-onswitch {
            padding-right: 20px;
            padding-left: 5px;
        }
        .layui-unselect.layui-form-switch.layui-form-onswitch i {
            transform: translateX(-20px);
        }
    </style>
</head>

<body>
    <div class="header lr10">
        <?php echo $this->headerment(); ?>
        <div class="exportBtn" style="margin-top:4px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding: 0 20px;line-height: 32px;font-size: 14px;"
            onclick="window.location.href = '<?php echo G_ADMIN_PATH; ?>/import/index/2'">
            <?php $this->lan("collection","采集"); ?>
        </div>
    </div>
    <div class="bk10"></div>
    <div class="table-list lr10">
        <form action="#" method="post" name="myform" class="layui-form">
            <table cellspacing="0" style="border-width: 1px 0px 0px 0px;">
                <thead>
                    <tr>
                        <th width="70px" align='center' style="max-width: 70px;"><?php $this->lan("sort");?></th>
                        <th align='center'><?php $this->lan("id");?></th>
                        <th align='center' width="70"><?php $this->lan("Bank_name");?></th>
                        <th align='center'><?php $this->lan("Bank_logo");?></th>
                        <th align='center'><?php $this->lan("background_color");?></th>
                        <th align='center'><?php $this->lan("update_time");?></th>
                        <th align='center'><?php $this->lan("Bank_language");?></th>
                        <th align='center'><?php $this->lan("state");?></th>
                        <th align='center'><?php $this->lan("operation");?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($list as $v) { ?>
                        <tr style="border-left: 1px solid #e2e2e2;">
                            <td align='center'><input name='listorders[<?php echo $v['cateid'] ?>]' type='text' size='3' value='<?php echo $v['sort'] ?>' class='input-text-c'></td>
                            <td align='center'><?php echo $v['id'] ?></td>
                            <td align='center'><?php echo $v['name'] ?></td>
                            <td align='center'><img class="img" style="width: 50px;" src="<?php echo $v['logo_url'] ?>"></th>
                            <td align='center'>
                                <div style="margin: 0 15px 0 3px;display: inline-block;">
                                    <div style="width: 30px;height: 30px;background-color: <?php echo $v['bg_color'] ?>;"></div>
                                </div>
                            </td>
                            <td align='center'><?php echo $v['update_time'] ?></td>
                            <td align='center'><?php echo $v['language']?:"通用"; ?></td>
                            <td align='center'>
                            <input type="checkbox" class ="is_state" name="is_state" lay-skin="switch" lay-filter="is_state"  <?php if($v['status'] == 1){ echo 'checked=""';} ?> data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
                            </td>
                            <td align='center'>
                                <a class="butaoin" href='<?php echo G_MODULE_PATH; ?>/member/bankEdit/<?php echo $v['id'] ?>'><?php $this->lan("modify");?></a>
                                <a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/member/bankDel/<?php echo $v["id"] ?>"><?php $this->lan("delete");?></a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (!$list): ?>
                        <tr>
                            <td colspan="9" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td colspan="9" align='right' style="border-width: 0px;text-align:left;margin-left: -5px;padding: 0;">
                            <div style="display: flex;justify-content: space-between;align-items: center;">
                                <input type="button" class="button" value="<?php $this->lan("sort");?>" onclick="myform.action='<?php echo G_MODULE_PATH; ?>/member/bankorder/dosubmit';myform.submit();" style="margin:0!important;"/>
                                <div id="pages" style="margin: 14px 0!important;">
                                    <ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </form>
    </div><!--table-list end-->
    <script>
    layui.use(['layer','colorpicker'], function(){
        var layer = layui.layer
            ,colorpicker = layui.colorpicker;

        <?php foreach($list AS $v) {	?>
        //RGB 、RGBA
        colorpicker.render({
            elem: '#test<?php echo $v["id"];?>'
            ,color: '<?php echo $v['bg_color'];?>'
            ,format: 'rgb' //默认为 hex
        });
        <?php }?>

    });
    layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            console.log(id);
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = -1;
            }
            $.post('<?php echo WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C; ?>/bank_status_up',{id:id,status:zhi},function (data) {
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
</body>
</html>