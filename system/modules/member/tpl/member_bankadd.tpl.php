<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
</style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
<table width="100%" class="lr10" style="border:none">
    <tr>
        <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Bank_name");?></td>
        <td style="display: flex;"><input type="text" name="name" class="input-text" value="<?php echo $res['name'];?>"></td>
    </tr>
    <tr>
        <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Bank_logo");?></td>
        <td>
            <div class="layui-upload">
                <div class="layui-row">
                    <div class="layui-col-md1" style="width:130px;margin: 0!important;">
                        <div class="layui-upload-list height130">
                            <div class="layui-bottom-img">
                                <span>+</span>
                                <text><?php $this->lan("点击上传");?></text>
                            </div>
                            <img class="layui-upload-img" width="100px" src="<?php echo $res['logo_url'];?>"  id="imgha-img">
                            <input type="hidden" id="imgha-lack" name="logo" value="<?php echo $res['logo']?:'';?>">
                            <p id="demoText"></p>
                            <button type="button" onclick="uploadimg('上传背景图片','#imgha','image','bank')"></button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("background_color");?></td>
        <td><div id="test-banner"></div>
            <input type="hidden" name="bg_color" id="bg_color"  value="<?php echo $res['bg_color'];?>">
        </td>
    </tr>
    <tr>
        <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("state");?></td>
        <td style="width:60px;display: flex;white-space: nowrap;gap: 10px;">
            <input type="radio" style="max-width:20px;margin: 0 5px;" name="status" value="1"  <?php echo $res['status']!=2?"checked":"";?>><?php $this->lan("open");?>
            <input type="radio" style="max-width:20px;margin: 0 5px;" name="status" value="2"  <?php echo $res['status']==2?"checked":"";?>><?php $this->lan("close");?>
        </td>
    </tr>
    <tr>
        <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><?php $this->lan("language");?></td>
        <td width="900" style="display: flex;">
            <select name="language" style="margin: 0!important;">
                <option value="<?php $this->lan("currency");?>"><?php $this->lan("currency");?></option>
                <?php foreach($languages as $key => $v){ ?>
                    <option value="<?php echo $v['lg_value']; ?>" <?php echo $res['lanuage']==$v['lg_value']?"selected":"";?>><?php echo $v['country']; ?></option>
                <?php } ?>
            </select>
        </td>  
    </tr>
    <tr>
        <td width="120" align="right" style="text-align: right!important;padding-right: 10px;"></td>
        <td style="display: flex;"><input type="submit" class="button" name="submit"  value="<?php $this->lan("Submit");?>" style="margin: 0!important;"></td>
    </tr>
</table>
</form>
</div><!--table-list end-->

<script>
layui.use(['colorpicker'], function(){
    var olorpicker = layui.colorpicker;
    olorpicker.render({
        elem: "#test-banner"
        ,color: "<?php echo $res['bg_color']?>"
        ,format: 'hex'
        ,done: function(color){
            $("#bg_color").val(color)
        }
    });
});
function upImage(){
    return document.getElementById('imgfield').click();
}
</script>
</body>
</html> 