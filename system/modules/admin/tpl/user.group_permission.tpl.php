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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
</head>
<body>
<div class="header lr10">
   <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<form id="form" method="post" action="<?php echo G_ADMIN_PATH.'/user/up_permission'?>">
<div class="table-list con-tab lr10" id="con-tab">
	<div name='con-tabv' class="con-tabv">
        <table width="100%" class="table_form">
            <tr>
                <th width="150" style="text-align: right!important;padding-right: 25px;">分组名称：</th>
                <th align="left" style="text-align: left!important;padding-left:10px;"><?php echo $info['name'];?>         </th>
            </tr>
            <?php
                foreach ($data as $key => $value) {
                    if($value['pid'] == 0 && $value['is_enable'] == 1){       
            ?>
                <tr>
                    <td align="right" class="p1">
                        <div class="wid90 tal" style="width: 100%;justify-content: end;display: flex;align-items: center;gap: 10px;"> <input type="checkbox" class="mr5" name="auth[]" style="margin: 0!important;" value="<?php echo $value['id']; ?>" 
                            <?php if(in_array($value['id'], $p_arr)){echo 'checked'; } ?> ><?php echo $value['name']; ?></div>
                    </td>
     
                    <td class="p2">
                    <?php
                       foreach ($data as $k => $v) { 
                        if($v['pid'] == $value['id'] && $v['is_enable'] == 1){
                    ?>                      
                            <div>
                                <div class="fwb p3" style="display: flex;align-items: center;gap: 10px;">
                                    <input type="checkbox" class="mr5" name="auth[]"  style="margin: 0!important;" value="<?php echo $v['id']; ?>" <?php if(in_array($v['id'], $p_arr)){echo 'checked'; } ?>><?php echo $v['name']; ?>
                                </div>
                         
                                    <div class="p4">
                                        <?php
                                           foreach ($data as $j => $u) { 
                                            if($u['pid'] == $v['id'] && $u['is_enable'] == 1){
                                        ?> 
<!--wid90-->
                                            <div class="lf " style="display: flex;align-items: center;gap: 10px;width:120px">

                                                <input type="checkbox" class="mr5" name="auth[]"  style="margin: 0!important;" value="<?php echo $u['id']; ?>" <?php if(in_array($u['id'], $p_arr)){echo 'checked'; } ?>><?php echo $u['name']; ?>
                                            </div>      
                                         
                                         <?php
                                                }
                                            }
                                         ?> 
                                    </div>
                                <div class="cl"></div>
                            </div>
                    <?php
                            }
                        }
                    ?>

                    </td>
               
                </tr>
            <?php
                    }
                }
            ?>
        </table>
    </div>
</div>
<div class="table-button lr10" style="padding-left: 242px">
    <input type="hidden" name="group_id" value="<?php echo $info['mid']; ?>">
    <input type="submit" name="submit" value=" 提交 " class="button">
</div>
</form>
<script language="javascript">
    $(".p1 input[type=checkbox]").click(function(){
        if($(this).attr("checked")=="checked"){
            $(this).parent().parent().parent().find(".p2").find("input[type=checkbox]").attr("checked",true);
        }else{
            $(this).parent().parent().parent().find(".p2").find("input[type=checkbox]").attr("checked",false);
        }
    });
    $(".p3 input[type=checkbox]").click(function(){
        if($(this).attr("checked")=="checked"){
            $(this).parent().parent().find(".p4").find("input[type=checkbox]").attr("checked",true);
            $(this).parent().parent().parent().parent().find(".p1").find("input[type=checkbox]").attr("checked",true);
        }else{
            $(this).parent().parent().find(".p4").find("input[type=checkbox]").attr("checked",false);
        }
    });
    $(".p4 input[type=checkbox]").click(function(){
        if($(this).attr("checked")=="checked"){
            $(this).parent().parent().parent().find(".p3").find("input[type=checkbox]").attr("checked",true);
            $(this).parent().parent().parent().parent().parent().find(".p1").find("input[type=checkbox]").attr("checked",true);
        }else{
            //$(this).parent().parent().parent().find(".p2").find("input[type=checkbox]").attr("checked",false);
        }
    });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 