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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/sample/css/sample.css" rel="stylesheet" media="screen" />
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/ckeditor.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/translations/zh-cn.js"></script>
<style>
    .bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
    .color_window_td a{ float:left; margin:0px 10px;}
    .rightgray {display: flex;align-items: center;}
    .rightgray div {background-color: #f5f5f5;border: 1px solid #B4B4B4;border-left-width: 0px;height: 27px;padding: 2px 15px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;line-height: 28px;}
    .rightgray input {margin: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;width: 85px;min-width: 80px;}
    .inpwid100 {width: 100%;margin: 0;box-sizing: border-box;padding: 2px 5px;}
    .imgbox{float:left;margin-left:3px;}
    .layui-btn {background-color: #000000;}
</style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
    <form method="post" action="#">
        <table width="100%"  cellspacing="0" cellpadding="0" style="border:none">
            <tr>
                <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("Belonging_column");?></td>
                <td style="display:flex;">
                    <select id="category" name="category_id" style="margin:0!important;">
                        <?php foreach($category as $key => $l){ ?>
                            <option value="<?php echo $l['cateid']; ?>" <?php if($res['category_id']==$l['cateid']) echo "selected";?>><?php echo $l['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>   
            <tr>
                <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("name");?></td>
                <td class="rightgray" style="border:0!important;padding: 0 10px !important;margin:0 0 10px 0!important;"><input type="text" id="name"  name="name" value="<?php echo $res['name']; ?>" class="input-text"></td>
            </tr>
            <tr > 
                <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("keys");?></td>
                <td class="rightgray" style="border:0!important;padding: 0 10px !important;"><input type="text" name="keys" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>3)value=value.slice(0,3)" value="<?php echo $res['keys']; ?>"  class="input-text"></td>
            </tr>
            <tr> 
                <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("sort");?></td>
                <td style="display:flex;"><input type="text" name="sort" onKeyUp="value=value.replace(/\D/g,'')" class="input-text" value="<?php echo $res['sort']?:100; ?>"></td>
            </tr>
            
            <tr height="60px">
                <td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"></td>
                <td style="display:flex;"><input type="submit" name="dosubmit" class="button" style="margin:0!important;" value="<?php $this->lan("modify");?>" /></td>
            </tr>
        </table>
    </form>
</div>
<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
<script type="text/javascript">	

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 