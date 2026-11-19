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
    <!-- 后台各页面详细UI升级样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <style>
        th {
            border: 0px solid #000;
        }
    </style>
</head>

<body>
    <div class="header lr10">

        <?php echo $this->headerment(); ?>
    </div>

    <div class="bk10"></div>
    <div class="table-list lr10">
        <form action="#" method="post" name="myform">
            <table width="100%" cellspacing="0" style="border-width: 1px 0px 0px 0px;">
                <thead>
                    <tr>
                        <th width="70" align='center'><?php $this->lan("sort"); ?></th>
                        <th width="60" align='center'><?php $this->lan("catid"); ?></th>
                        <?php if($cate_type == 'goods'){?>
                        <th align='center' width="120"><?php $this->lan("Category_pictures"); ?></th>
                        <?php } ?>
                        <th align='center'><?php $this->lan("Column_name"); ?></th>
                        <th align='center' style="<?php echo $cate_type == 'article' ? 'display:none' : ''; ?>"><?php $this->lan("Access_address"); ?></th>
                        <th align='center'><?php $this->lan("operation"); ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($categorys as $v) { ?>
                        <tr style="border-left: 1px solid #e2e2e2;">
                            <td align='center'><input name='listorders[<?php echo $v['cateid'] ?>]' type='text' size='3' value='<?php echo $v['order'] ?>' class='input-text-c' style="border:0!important;text-align: center!important;"></td>
                            <td align='center'><?php echo $v['cateid'] ?></td>
                        <?php if($cate_type == 'goods'){
                            if($v['img']){?>
                                <td align='center'><img src="<?php echo $v['img'] ?>" width="50" height="50" alt=""/></td>
                            <?php }else{?>
                                <td align='center'></td>
                            <?php }} ?>
                            <td align='center'><?php echo $v['name'] ?></th>
                            <td align='center' style="<?php echo $cate_type == 'article' ? 'display:none' : ''; ?>"><a href='<?php echo $v['href'] ?>' target='_blank'><?php echo $v['href']; ?></a></td>
                            <td align='center'>
                                <a class="butaoin" href='<?php echo G_MODULE_PATH; ?>/category/editcate/<?php echo $v['cateid'] ?>'><?php $this->lan("modify"); ?></a>
                                <a class="butaoin butaish a_form_noparam" href="<?php echo G_MODULE_PATH; ?>/category/delcate/<?php echo $v["cateid"] ?>"><?php $this->lan("delete"); ?></a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>

            </table>
            <input type="button" class="button" value="<?php $this->lan("sort"); ?>" style="margin-top:10px!important;" onclick="myform.action='<?php echo G_MODULE_PATH; ?>/category/listorder/dosubmit';myform.submit();" />
        </form>
    </div><!--table-list end-->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <!-- 高级骨架屏系统脚本 -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
    <!-- 通知消息系统脚本（必须在layer之后加载） -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
    <script>
        //window.parent.message("3443",8,20);
    </script>
</body>

</html>