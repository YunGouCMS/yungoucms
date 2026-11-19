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
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>

<div class="table_form lr10">
    <form action="#" method="post" id="myform">
        <table width="100%" class="lr10">

            <tr>
                <td width="80">绑定域名<?php $this->lan("description");?></td>
                <td>
                    <input type="text" name="k" placeholder="请输入用户名<?php $this->lan("description");?>" class="input-text" id="username" value="<?php echo $arr[k];?>"></input>
                </td>
            </tr>
            <tr>
                <td>绑定模块<?php $this->lan("description");?></td>
                <td><input type="text" name="m" placeholder="请输入昵称<?php $this->lan("description");?>" class="input-text" id="name" value="<?php echo $arr['v']['m'];?>"></input></td>
            </tr>
            <tr>
                <td>绑定控制器<?php $this->lan("description");?></td>
                <td><input type="text" name="c" placeholder="请输入密码<?php $this->lan("description");?>" class="input-text" id="password" value="<?php echo $arr['v']['c'];?>"></input></td>
            </tr>
            <tr>
                <td>绑定函数<?php $this->lan("description");?></td>
                <td><input type="text" name="a" placeholder="请输入确认密码<?php $this->lan("description");?>" class="input-text" id="pwdconfirm" value="<?php echo $arr['v']['a'];?>"></input></td>
            </tr>
        </table>
        <div class="bk15"></div>
        <input type="hidden" name="submit-1" />
        <input type="submit" value=" 提交<?php $this->lan("description");?> " id="dosubmit" class="button">
    </form>
</div><!--table-list end-->
<script type="text/javascript">
    var error='';
    var bool=false;
    var id='';

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>