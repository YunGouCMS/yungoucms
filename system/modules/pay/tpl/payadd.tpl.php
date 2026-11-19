<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
    <style>
        tr {
            height: 40px;
            line-height: 40px
        }

        .layuputimg {
            border: 1px dashed #B4B4B4;
            width: 100px;
            height: 100px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .layuputimg img {
            width: 100%;
        }

        .layuputimg input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
        }

        tr td:first-child {
            padding-right: 10px;
        }

        .textcenter input {
            text-align: center;
        }

        .layui-btn.zhuijia {
            background-color: #3c8dbc;
            position: absolute;
            bottom: 16px;
            left: 360px;
        }

        .textcenter {
            position: relative;
        }

        .textcenter div input {
            width: 225px;
        }

        .wid300 {
            width: 300px;
        }
    </style>
</head>

<body>
    <div class="header-title lr10">
        <b><?php $this->lan("title"); ?></b>
    </div>
    <div class="bk10"></div>
    <div class="table-list lr10">
        <!--start-->
        <form name="myform" action="#" method="post" enctype="multipart/form-data">
            <table width="100%" cellspacing="0">
                <tr>
                    <td width="220" align="right"> <?php $this->lan("Payment_name"); ?></td>
                    <td><input type="text" name="pay_name" value="" placeholder="<?php $this->lan("Payment_name"); ?>" class="input-text">
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right"> <?php $this->lan("pay_class", "pay_class"); ?></td>
                    <td><input type="text" name="pay_class" value="" placeholder="<?php $this->lan("pay_class", "pay_class"); ?>" class="input-text">
                    </td>
                </tr>

                <tr>
                    <td width="220" height="80" align="right"> <?php $this->lan("image"); ?></td>
                    <td>
                        <div class="layuputimg">
                            <img id="imgHeadPhoto" src="" alt="" />
                            <input type="file" name="pay_thumb" onchange="PreviewImage(this,'imgHeadPhoto','divPreview');" size="20" accept="image/png, image/jpeg, image/gif, image/jpg" />
                        </div>
                    </td>
                </tr>

                <tr>
                    <td width="220" align="right"><?php $this->lan("introduction"); ?> </td>
                    <td>
                        <input type="text" name="pay_des" value="" placeholder="<?php $this->lan("introduction"); ?>" class="input-text">
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right"> <?php $this->lan("state"); ?></td>
                    <td>
                        <input type="radio" style="margin-right:3px" name="pay_start" value="1" /><?php $this->lan("open"); ?>
                        <input type="radio" style="margin-left: 3px;margin-right:5px" name="pay_start" value="0" checked="checked" /><?php $this->lan("close"); ?>
                    </td>
                </tr>

                <tr>
                    <td>账号信息</td>
                    <td>添加<button type="button" class="pay_key_add">添加</button></td>
                </tr>
                <tr class="pay_key">
                    <td width="220" align="right" style="padding-right:5px;"><input class="input-text wid300" placeholder="请输入key" name="pay_key[key][]" /></td>
                    <td><input class="input-text wid300" placeholder="请输入名称" name="pay_key[name][]" /><input class="input-text wid300" placeholder="请输入值" name="pay_key[value][]" /></td>
                </tr>
                <tr>
                    <td width="220" align="right">
                        <?php $this->lan("amount"); ?>
                    </td>
                    <td>
                        <input type="radio" name="isinput" value="1" /> <?php $this->lan("open"); ?>
                        <input type="radio" name="isinput" value="0" checked="checked" /> <?php $this->lan("close"); ?>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right"><?php $this->lan("Amount_range"); ?> </td>
                    <td class="textcenter">
                        <input type="number" name="m[min]" value="<?php echo $fw['range']['min'] ?: 50; ?>" class="input-text" style="width: 50px !important;">~
                        <input type="number" name="m[max]" value="<?php echo $fw['range']['max'] ?: 5000; ?>" class="input-text" style="width: 50px !important;">
                        <span class="error_tips">
                            <div style="display:inline-block;width:200px" data-title="<?php $this->lan("tips4"); ?>" class="tip">
                                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                            </div>
                        </span>
                        <div style="margin: 10px 0 0">
                            <div>
                                <input type="number" name="ms[]" value="50" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px;display: none;"><i class="layui-icon layui-icon-close"></i></button>
                            </div>
                        </div>
                        <button type="button" class="layui-btn layui-btn-sm zhuijia"><?php $this->lan("append"); ?></button>
                    </td>
                </tr>
                <!--<tr>-->
                <!--  <td width="220" align="right"></td>-->
                <!--  <td>-->
                <!--      <?php foreach ($pay["pay_input"] ?: [] as $key => $vl) { ?>-->
                <!--          <div >-->
                <!--              <div style="display: inline-block;">-->
                <!--                  <label for="">名称：</label>-->
                <!--                  <input type="text" name="pay_input[name][<?php echo $key; ?>]" value="<?php echo $vl['name']; ?>">-->
                <!--              </div>-->
                <!--              <div style="display: inline-block;">-->
                <!--                  <label for="">说明：</label>-->
                <!--                  <input type="text" name="pay_input[msg][<?php echo $key; ?>]" value="<?php echo $vl['msg']; ?>">-->
                <!--              </div>-->
                <!--          </div>-->
                <!--      <?php } ?>-->
                <!--  </td>-->
                <!--</tr>-->

                <tr>
                    <td width="220" align="right"><?php $this->lan("sort"); ?> </td>
                    <td>
                        <input type="number" class="input-text wid100" value="<?php echo $pay['sort']; ?>" name="sort" style="text-align:center;" />
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right"></td>
                    <td><input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit"); ?> "></td>
                </tr>
            </table>
        </form>

    </div><!--table-list end-->

    <script>
        $('.zhuijia').click(function() {
            var az = '<div><input type="text" name="ms[]" value="50" class="input-text" style="margin: 0 0 10px"> <button type="button" class="deletediv layui-btn layui-btn-sm layui-btn-danger" style="margin: 0 0 10px"><i class="layui-icon layui-icon-close"></i></button></div>'
            $(this).parent().children('div').append(az)
            $('.deletediv').click(function() {
                $(this).parent().remove();
            })
        })
        $('.deletediv').click(function() {
            $(this).parent().remove();
        })
        $(".pay_key_add").click(function() {
            var html = $(".pay_key").eq(0).html();
            $(".pay_key").eq(0).after('<tr class="pay_key">'+html+'</tr>');
        })
    </script>
</body>

</html>