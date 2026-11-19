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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>

</head>
<style>
    tbody tr{ line-height:30px; height:30px;}
    .layui-form-onswitch{
        margin-top: 0px!important;
    }
    .ssgjdiv {padding:10px 0 0 10px;}
    .ssgjdiv form {display: flex;align-items: center;}
    .ssgjdiv .button {height:32px;}
    .ssgjdiv select {margin-left:10px;}
    .btn_paixu {padding-left:0;}
</style>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
    <form action="#" method="post" name="myform">
    <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th ><?php $this->lan("sort","排序");?></th>
            <th ><?php $this->lan("id","id");?></th>
            <th ><?php $this->lan("name","名称");?></th>
            <th ><?php $this->lan("Jump_link","跳转链接");?></th>
            <th ><?php $this->lan("navigation_img","图片");?></th>
            <th ><?php $this->lan("country","语言");?></th>
            <th ><?php $this->lan("state","状态");?></th>
		    <th ><?php $this->lan("operation","操作");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($lists as $v){ ?>
		<tr>
			<td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['sort']; ?>' class='input-text-c'></td>
			<td align="center"><?php echo $v['id']; ?></td>
			<td align="center"><?php echo $v['name']; ?></td>
            <td align="center"><?php echo $v['url'];?></td>
			<td align="center"><img height="50px" src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $v['img']; ?>"/></td>
			<td align="center"><?php echo $v['language']?:"通用";?></td>
            <td align="center"><?php echo $v['state'];?></td>
			<td align="center"> 
                <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/setting/community_edit/<?php echo $v['id'];?>"><?php $this->lan("modify","修改");?></a>
                <a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/setting/community_del/<?php echo $v['id'];?>"><?php $this->lan("delete","删除");?></a>
			</td>
		</tr>
		<?php } ?>
  	</tbody>
	</table>
</form>
  <div class="btn_paixu">
      <div style="width:200px;">
        <input type="button" class="button" value=" <?php $this->lan("sort","排序");?> " onclick="myform.action='<?php echo G_MODULE_PATH; ?>/setting/community_sort/dosubmit';myform.submit();"/>
    </div>
  </div>
	<div class="btn_paixu"></div>
</div><!--table-list end-->

<script>
    layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = 0;
            }
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/up_dhstatus',{id:id,status:zhi},function (data) {
                data = JSON.parse(data);
                if (data == 1){
                    layer.msg('修改成功');
                }else{
                    layer.msg('修改失败');
                }
            });
        });
        form.render();

    });

    $("#checkAll").on('change', function () {
        console.log("aaa")

    if ($(this).is(":checked")) { // 全选
        $(".checkOne").prop("checked",true);
    } else { // 反选
        $(".checkOne").prop("checked",false);
    }
	});
	$('.batchDel').click(function(){
         //获取所有选中的CheckBox的id
        var  ids="";
        $(".checkOne").each(function(i) {
            if($(this).prop('checked')){
            var id= $(this).attr("data-id");
            if(ids){
                ids = ids +","
            }
                ids = ids+id	
            }
        });
    // ajax批量删除
        $.post("<?php echo G_MODULE_PATH; ?>/wap/batch_del",{ids:ids},function(res){
            if (res == 0) {
                layer.msg("删除成功!");
                // alert("删除成功!");
                setTimeout(function(){
                    location.reload(false);
                },1000);
            }else {
                layer.msg("删除失败!");
            
            }
        })
        ids="";
  	});
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 