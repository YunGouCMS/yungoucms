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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <!-- 后台各页面详细UI升级样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <!-- 通知消息系统脚本（必须在layer之后加载） -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.layui-form-onswitch{
    margin-top: 0px!important;
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
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<!-- 修改表格样式 -->
<div class="table-list lr10">
<!--start-->
<form action="#" method="post" id="myform" class="layui-form" >
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
		<th width=""><?php $this->lan("id");?></th>
		<th width="" align="center"><?php $this->lan("title");?></th>
		<th width="" align="center"><?php $this->lan("img");?></th>
		<th width="" align="center"><?php $this->lan("Jump_address");?></th>
		<th width="" align="center"><?php $this->lan("background_color");?></th>
		<th width="" align="center"><?php $this->lan("country");?></th>
		<th width="" align="center"><?php $this->lan("state");?></th>
		<th width="" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($banners as $v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
			<td align="center"><?php echo $v['title'];?></td>
			<td align="center"><img height="50px" src="<?php echo G_UPLOAD_PATH; ?>/bannerimg/<?php echo $v['img'];?>"/></td>
			<td align="center"><?php echo $v['img_url'];?></td>
			<td align="center">
			    <div id="test<?php echo $v['id'];?>"></div>
		    </td>
			<td align="center"><?php echo $v['language']?:"通用";?></td>
			<td width="10%" align="center">
                <input type="checkbox" class ="is_state" name="is_state" lay-skin="switch" lay-filter="is_state"  <?php if($v['status'] == 1){ echo 'checked=""';} ?> data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
            </td>
			<td align="center">
				<a class="butaoin" href="<?php echo WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C; ?>/editimg/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>
				<a class="butaoin butaish" href="<?php echo WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C; ?>/delimg/<?php echo $v['id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
			</td>	
		</tr>
		<?php } ?>
  	</tbody>
</table>
</form>
</div><!--table-list end-->

<script type="text/javascript">
	layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            console.log(111);
            var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = -1;
            }
            $.post('<?php echo WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C; ?>/up_status',{id:id,status:zhi},function (data) {
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
    layui.use(['layer','colorpicker'], function(){
        var layer = layui.layer
            ,colorpicker = layui.colorpicker;
        
        <?php foreach($banners AS $v) {	?>

        //RGB 、RGBA
        colorpicker.render({
            elem: '#test<?php echo $v["id"];?>'
            ,color: '<?php echo $v['bg_color'];?>'
            ,format: 'rgb' //默认为 hex
        });
        <?php }?>
    });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 