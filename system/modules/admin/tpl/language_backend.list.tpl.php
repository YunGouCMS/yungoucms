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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<style>
.layui-form-switch {
    margin-top: 0;
}
.layui-form-onswitch em {margin-left:0px;}
</style>
</head>
<body>
<div class="header lr10">

<?php echo $this->headerment();?>

</div>

<div class="bk10"></div>

<div class="table-list lr10">

<!--start-->

<form name="myform" id="myform" action="#" method="post">

<table width="100%" cellspacing="0">	
<thead>
		<tr>
		<th width="60px">ID</th>
            <th width="" align="center"><?php $this->lan("Pack_code");?></th>
		    <th width="" align="center"><?php $this->lan("country");?></th>
		    <!-- <th width="" align="center">国旗</th> -->
		    <!-- <th width="" align="center">区号</th> -->
			<!-- <th width="" align="center">货币</th> -->
			<!-- <th width="" align="center">时区</th> -->
            <th align="center"><?php $this->lan("state");?></th>
			<!--<th align="center">是否缺失</th>-->
			<th width="350" align="center"><?php $this->lan("operation");?>
				<!-- <a href="">导入</a> -->
			</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($languages as $key => $v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
            <td align="center"><?php echo $v['lg_value']; ?></td>
            <td align="center"><?php echo $v['country'];?></td>
            <!-- <td align="center"><img src="<?php echo G_PLUGIN_PATH."/imgs/flag/".$v['national_flag'];?>" alt=""></td> -->
            <!-- <td align="center"><?php echo $v['area_code'];?></td> -->
            <!-- <td align="center"><?php echo $v['currency_unit'];?></td> -->
			<!-- <td align="center"><?php echo $v['time_zone'];?></td> -->
			<td align="center" class="layui-form">
			    <!--<?php echo $v['status'] == 1?'启用':'关闭';?>-->
			    <input type="checkbox" class="is_state sta" name="is_state" <?php if($v['status'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
			</td>
			<!--<td align="center"><?php echo $v['deletion']==1?'缺失':'正常' ;?></td>-->
			<td align="center">
				<a class="import butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/languageImport/<?php echo $v['lg_value']."/".$v['lg_value'];?>"><?php $this->lan("import","导入");?></a>
				<!-- <a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/languageExport/<?php echo $v['id'];?>">导出</a> -->
				<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/languageBackendUpdate/<?php echo $v['id'];?>/3"><?php $this->lan("modify");?></a>
				<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/languageBackendDelete/<?php echo $v['id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
			</td>	
		</tr>
		<?php } ?>
  	</tbody>
</table>
</form>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script>
	$(".import").click(function(e){
		if(confirm('确定导入?')==false){
			return false;
		}else{
			layer.msg('正在导入中,请耐心等待..')
		}
	})
	layui.use(['form','jquery'],function () {
        var form = layui.form,
        $ = layui.jquery;
        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            var status = '';
            if (data.elem.checked == true) {
                status = 1;
            }else if(data.elem.checked == false){
                status = 0;
            }
            $.post("<?php echo WEB_PATH.'/'.ROUTE_M; ?>/language/languageBackendSetStatus",{id:id,status:status},function (data) {
                data = JSON.parse(data);
                if (data.code == 200){
                    layer.msg(data.msg, {icon: 1});
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            });
        });
        form.render();
      });
</script>
</html> 
