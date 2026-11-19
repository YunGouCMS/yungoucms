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
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/messageModal/message.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>

    <style>
        body {
            background-color: #fff;
            /*height: 600px;*/
            overflow: hidden;
            padding-bottom: 20px;
        }

        tr {
            text-align: center
        }

        .posttime {
            margin: 0 5px;
        }
        .table-list table td:not([align="right"]), .table-form table td:not([align="right"]) {
            padding: 15px 0!important;
        }
    </style>
</head>

<body>
    <div class="header lr10">

        <?php echo $this->headerment(); ?>
    </div>
    <div class="bk10"></div>
    <!--<div class="bk10"></div>-->
    <div class="header-data lr10">
        <form action="#" method="post" style="display: flex;align-items: center;">
            <?php $this->lan("Belonging_country"); ?> 
            <select name="language" style="margin-left: 5px;">
                <option value=""><?php $this->lan("All"); ?></option>
                <?php
                foreach ($languages as $vl) {
                    $sel = '';
                    if ($vl['lg_value'] == $language) $sel = 'selected="selected"';
                    echo "<option value='{$vl['lg_value']}' $sel>{$vl['country']}</option>";
                }
                ?>
            </select>
            <?php $this->lan("Add_time"); ?> <input name="posttime1" type="text" placeholder="<?php $this->lan("tips3"); ?>" id="posttime1" class="input-text posttime" readonly="readonly" style="padding-left:30px!important;height:46px!important;    border: 2px solid #e5e7eb !important;border-radius: 8px !important;margin:0 5px!important;"/> -
            <input name="posttime2" placeholder="<?php $this->lan("tips4"); ?>" type="text" id="posttime2" class="input-text posttime" readonly="readonly" style="padding-left:30px!important;height:46px!important;    border: 2px solid #e5e7eb !important;border-radius: 8px !important;margin:0 5px!important;"/>
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
            </script>
            <script type="text/javascript">
                function r_submit() {

                    $.get('/<?PHP echo G_ADMIN_DIR; ?>/content/r_submit', function(res) {

                        window.location.href = "/<?PHP echo G_ADMIN_DIR; ?>/content/goods_list"

                    })

                }
            </script>
            <select name="sotype">
                <option value="title"><?php $this->lan("Product_title"); ?></option>
                <option value="id"><?php $this->lan("Product_ID"); ?></option>
                <option value="cateid"><?php $this->lan("Column_ID"); ?></option>
                <option value="catename"><?php $this->lan("Column_name"); ?></option>
                <option value="brandid"><?php $this->lan("Brand_ID"); ?></option>
                <option value="brandname"><?php $this->lan("Brand_name"); ?></option>
            </select>
            <input placeholder="<?php $this->lan("Search_content"); ?>" type="text" name="sosotext" class="input-text wid100" value="<?php echo $sosotext ?>" style="width:300px!important;">
            <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search"); ?>">
            <input class="button" type="button" value="<?php $this->lan("reset"); ?>" onclick="r_submit()">
        </form>
    </div>
    <div class="bk10"></div>
    <form action="#" method="post" name="myform">
        <div class="table-list lr10">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php $this->lan("sort"); ?></th>
                        <th><?php $this->lan("id"); ?></th>
                        <th width="30%"><?php $this->lan("Product_title"); ?></th>
                        <th><?php $this->lan("Column_name"); ?></th>
                        <th><?php $this->lan("Total_price"); ?></th>
                        <th><?php $this->lan("Discounted_price"); ?></th>
                        <th><?php $this->lan("Give_points"); ?></th>
                        <th><?php $this->lan("popularity"); ?></th>
                        <th><?php $this->lan("status"); ?></th>
                        <th><?php $this->lan("Sales"); ?></th>
                        <th width="90px"><?php $this->lan("country"); ?></th>
                        <th><?php $this->lan("operation"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shoplist as $v) { ?>
                        <tr>
                            <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>3)value=value.slice(0,3)" type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c' style="border: 0!important;text-align:center!important;"></td>
                            <td style="width: 40px;"><?php echo $v['id']; ?></td>
                            <td align="left" style=" padding-left:20px"><span style=""><?php echo $v['title']; ?></span></td>
                            <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $v['cate_name']; ?></a></td>

                            <td><?php echo $v['money']; ?></td>

                            <td><?php echo $v['disc_price']; ?></td>
                            <td><?php echo $v['s_score']; ?></td>
                            <td class="layui-form">
                                <input type="checkbox" style="" class="is_state" name="renqi" <?php if ($v['renqi'] == 1) {
                                                                                                    echo 'checked="checked"';
                                                                                                } ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>">
                            </td>

                            <td class="layui-form">
                                <input type="checkbox" style="" class="is_state" name="status" <?php if ($v['status'] == 1) {
                                                                                                    echo 'checked="checked"';
                                                                                                } ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("Available"); ?>|<?php $this->lan("Removed"); ?>">
                            </td>

                            <td>
                                <input name='sales[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['fictitious']; ?>' class='upsales input-text-c' style="border:0!important;text-align:center!important;">
                            </td>
                            <td><?php echo $v['lan']; ?></td>
                            <td class="action">
                                <?php if ($type) { ?>
                                    <a class="butaoin " href="<?php echo G_ADMIN_PATH; ?>/content/jifen_goods_del_restore/<?php echo $v['id']; ?>"><?php $this->lan("Undo_delete"); ?></a>
                                    <a class="butaoin butaish" href="<?php echo G_ADMIN_PATH; ?>/content/jifen_goods_del_real/<?php echo $v['id']; ?>"><?php $this->lan("Completely_delete"); ?></a>
                                <?php } else { ?>
                                    <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/content/jifen_goods_edit/<?php echo $v['id']; ?>"><?php $this->lan("modify"); ?></a>
                                    <a class="butaoin butaish a_form_noparam" data-confirm="<?php $this->lan("confirm");?>" data-cancel="<?php $this->lan("cancel");?>" href="<?php echo G_ADMIN_PATH; ?>/content/jifen_goods_del/<?php echo $v['id']; ?>"><?php $this->lan("delete"); ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </form>

    <div class="btn_paixu" style="padding:0;">
        <div style="display:flex;align-items: center;justify-content: space-between;height: 50px;padding: 0 9px;">
            <div>
                <input type="button" style="display: inline-block !important;" class="button" value=" <?php $this->lan("sort"); ?>"
                    onclick="myform.action='<?php echo G_MODULE_PATH; ?>/content/jifen_goods_listorders/dosubmit';myform.submit();" />

                <input type="button" style="display: inline-block !important;" class="button" value=" <?php $this->lan("Modify_sales_volume"); ?> "
                    onclick="myform.action='<?php echo G_MODULE_PATH; ?>/content/jifen_goods_listsales/sales';myform.submit();" />
            </div>
            <div id="pages">
                <ul>
                    <li><?php $this->lan("common"); ?> <?php echo $total; ?> <?php $this->lan("strip"); ?></li><?php echo $page->show('one', 'li'); ?>
                </ul>
            </div>
        </div>
    </div>

    </div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
    // 修改过
    layui.use(['form', 'jquery'], function() {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)', function(data) {
            var id = $(this).attr('data-state');
            var name = $(this).attr('name');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            } else if (data.elem.checked == false) {
                zhi = 2;
            }
            $.post('<?php echo G_MODULE_PATH ?>/content/goods_set/' + name + '/' + id, {
                id: id,
                state: zhi,
                model: 2
            }, function(data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    layer.msg(data.msg, {
                        icon: 1
                    });
                    // setTimeout(function () {
                    //     window.location.href = "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_logistics";
                    // }, 2000);
                } else {
                    layer.msg(data.msg, {
                        icon: 2
                    });
                }
            });
        });
    })
</script>

</html>