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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
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
            <table width="100%" cellspacing="0" style="border:none">
                <tr>
                    <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("img_title"); ?></td>
                    <td style="display:flex;"><input type="text" name="title" value="<?php echo $banner_info['title']; ?>" class="input-text" maxlength="20"></td>
                </tr>
                <tr>
                    <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Popup_img"); ?></td>
                    <td>
                    <div class="layui-upload">
                            <div class="layui-row">
                                <div class="layui-col-md1 width130" style="margin:0!important;">
                                    <div class="layui-upload-list height130">
                                        <div class="layui-bottom-img">
                                            <span>+</span>
                                            <text><?php $this->lan("Click_upload");?></text>
                                        </div>
                                        <img class="layui-upload-img" width="120px" src="<?php echo $banner_info['img']?G_UPLOAD_PATH."/bannerimg/".$banner_info['img']:'';?>" id="imgha-img" style="height: 100%;width: auto;">
                                        <input type="hidden" id="imgha-lack" name="img" value="<?php echo $banner_info['img']?:'';?>">
                                        <p id="demoText"></p>
                                        <button type="button" onclick="uploadimg('上传背景图片','#imgha','image','bannerimg')"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("background_color"); ?></td>
                    <td style="display: flex;">
                        <div id="test-banner" style="margin: 0!important;"></div>
                        <input type="hidden" name="bg_color" id="bg_color" value="<?php echo $banner_info['bg_color']; ?>">
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Jump_address"); ?></td>
                    <td style="display:flex;"><input type="text" name="img_url" value="<?php echo $banner_info['img_url']; ?>" class="input-text"></td>
                </tr>
                <tr>
                    <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("state"); ?></td>
                    <td style="display:flex;gap:10px;">
                        <div style="display:flex;align-items:center;gap:10px;margin:0!important;white-space: nowrap;">
                            <input style="min-width:13px;width:13px" type="radio" name="status" value="1" <?php if ($banner_info['status'] == 1) {
                                                                                                                echo 'checked';
                                                                                                            } ?> class="input-text"><?php $this->lan("open"); ?>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;margin:0!important;white-space: nowrap;">
                                <input style="min-width:13px;width:13px;" type="radio" name="status" value="-1" <?php if ($banner_info['status'] == -1) {
                                                                                                                    echo 'checked';
                                                                                                                } ?> class="input-text"><?php $this->lan("close"); ?>
                            </div>
                    </td>

                </tr>
                <tr>
                    <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><?php $this->lan("language"); ?></td>
                    <td width="900" style="display:flex;">
                        <select name="language" style="margin:0!important;">
                            <option value="" <?php if ($banner_info['language'] == '') {
                                                    echo 'selected';
                                                } ?>><?php $this->lan("currency"); ?></option>
                            <?php foreach ($languages as $key => $v) { ?>
                                <option value="<?php echo $v['lg_value']; ?>" <?php if ($banner_info['language'] == $v['lg_value']) {
                                                                                    echo 'selected';
                                                                                } ?>><?php echo $v['lg_name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"></td>
                    <td style="display:flex;">
                        <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit"); ?>" style="margin:0!important;">
                    </td>
                </tr>
            </table>
        </form>
    </div><!--table-list end-->

    <script>
        layui.use(['colorpicker'], function() {
            var olorpicker = layui.colorpicker;
            olorpicker.render({
                elem: "#test-banner",
                color: "<?php echo $temp['color']; ?>",
                format: 'hex',
                done: function(color) {
                    $("#bg_color").val(color)

                    // $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:1},function (res) {
                    //     var result = JSON.parse(res)
                    //     if (result.code ==1){
                    //         layer.msg('修改成功')
                    //         return
                    //     }
                    //     layer.msg('修改失败')
                    // })
                }
            });
        });
    </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>