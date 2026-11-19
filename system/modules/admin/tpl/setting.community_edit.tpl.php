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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.layui-btn {background-color: #000000;}
.layui-upload-list button,.layui-upload-list input,#lunbo img {cursor: pointer;}
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
    <table width="100%" cellspacing="0">
        <tr>
            <td width="120" align="right"><?php $this->lan("name","名称");?></td>
            <td><input type="text" name="name" value="<?php echo $record['name'];?>" class="input-text" maxlength="20"></td>
        </tr>
        <tr>
            <td width="120" align="right"><?php $this->lan("img","图片");?></td>
            <td>
                <div class="layui-upload">
                    <div class="layui-row">
                        <div class="layui-col-md1 width130">
                            <div class="layui-upload-list height130">
                                <div class="layui-bottom-img">
                                    <span>+</span>
                                    <text><?php $this->lan("Click_upload","上传");?></text>
                                </div>
                                <img class="layui-upload-img" width="120px" src="<?php echo $record['img']?G_UPLOAD_PATH."/".$record['img']:'';?>"  id="imgha-img">
                                <input type="hidden" id="imgha-lack" name="img" value="<?php echo $record['img']?:'';?>">
                                <p id="demoText"></p>
                                <button type="button" onclick="uploadimg('上传图片','#imgha','image','shopimg')"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="120" align="right"><?php $this->lan("Jump_address","跳转链接");?></td>
            <td><input type="text" name="url" value="<?php echo $record['url'];?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right"><?php $this->lan("state","状态");?></td>
            <td>
               <div style="display:flex;">
                <input style="min-width:13px;width:13px" type="radio" name="status" value="1" <?php if($record['status'] != 2){echo 'checked';} ?> class="input-text"><?php $this->lan("open","开启");?>
                <div style="padding-left: 10px">
                <input style="min-width:13px;width:13px;" type="radio" name="status" value="2" <?php if($record['status'] == 2){echo 'checked';} ?> class="input-text"><?php $this->lan("close","关闭");?>
                </div>
               </div>
            </td>
           
        </tr>
        <tr>
            <td align="right" style="width:120px"><?php $this->lan("language","语言");?></td>
            <td width="900">
                <select name="language">
					<option value="" <?php if($record['language'] == ''){ echo 'selected'; } ?>><?php $this->lan("currency","通用");?></option>
                    <?php foreach($languages as $key => $v){ ?>
                        <option value="<?php echo $v['lg_value']; ?>" <?php if($record['language'] == $v['lg_value']){ echo 'selected'; } ?>><?php echo $v['lg_name']; ?></option>
                    <?php } ?>
                </select>
            </td>  
        </tr>
        <tr>
            <td width="120" align="right"><?php $this->lan("sort","排序");?></td>
            <td><input type="text" name="sort" value="<?php echo $record['sort'];?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right"></td>
            <td>		
                <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit","提交");?>" >
            </td>
        </tr>
    </table>
</form>
</div><!--table-list end-->

<script>
layui.use(['colorpicker'], function(){
    var olorpicker = layui.colorpicker;
    olorpicker.render({
        elem: "#test-banner"
        ,color: "<?php echo $temp['color']; ?>"
        ,format: 'hex'
        ,done: function(color){
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
    function upImage(){
        return document.getElementById('imgfield').click();
    }
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 