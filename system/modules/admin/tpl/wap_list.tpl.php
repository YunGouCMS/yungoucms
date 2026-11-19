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
 <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
 <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
 <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
.layui-form-switch {
    margin-top: 0;
}
.btn_paixu {padding-left:0;}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
	<div>
		<input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDel button" style="margin-bottom: 10px;">
	</div>
<!--start-->
    <form action="#" method="post" name="myform">
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
		<th width="" align="center"><input id="checkAll" type="checkbox"></th>
        <th width=""><?php $this->lan("sort");?></th>
		<th width=""><?php $this->lan("id");?></th>
        <th width="" align="center"><?php $this->lan("slide_name");?></th>
		<th width="" align="center"><?php $this->lan("slide_img");?></th>
        <th width="" align="center"><?php $this->lan("Jump_link");?></th>
		<th width="" align="center"><?php $this->lan("state");?></th>
		<th align="center"><?php $this->lan("country");?></th>
		<th width="" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($lists as $key => $v){ ?>
		<tr>
			<td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
         	<td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['sort']; ?>' class='input-text-c' style="border: 0!important;text-align: center!important;"></td>
			<td align="center"><?php echo $v['id']; ?></td>
            <td align="center"><?php echo $v['title']; ?></td>
			<td align="center"><img height="50px" src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $v['img']; ?>"/></td>
            <td align="center"><?php echo $v['link'];?></td>
			<td align="center"  class="layui-form">
		    <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($v['status'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
			</td>
			
			<td align="center"><?php echo $v['language']?:"通用";?></td>
			<td align="center">
                <?php
                    if($model==1){
                        echo '<a class="butaoin" style="margin-right:10px!important" href="'.WEB_PATH.'/'.G_ADMIN_DIR.'/wap/update/'.$v['id'].'">'.$this->lgs["modify"].'</a>';
                        echo '<a class="butaoin butaish" href="'.WEB_PATH.'/'.G_ADMIN_DIR.'/wap/delete/'.$v['id'].'">'.$this->lgs["delete"].'</a>';
                    }else{
                        echo '<a class="butaoin" style="margin-right:10px!important" href="'.WEB_PATH.'/'.G_ADMIN_DIR.'/wap/jifen_update/'.$v['id'].'">'.$this->lgs["modify"].'</a>';
                        echo '<a class="butaoin butaish" href="'.WEB_PATH.'/'.G_ADMIN_DIR.'/wap/jifen_delete/'.$v['id'].'">'.$this->lgs["delete"].'</a>';
                    }
                ?>
				<!-- <a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/update/<?php echo $v['id'];?>">修改</a>
				<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/delete/<?php echo $v['id'];?>">删除</a> -->
			</td>
		</tr>
		<?php } ?>
  	</tbody>
</table>
 </form>
  <div class="btn_paixu">
  	<div style="width:80px;">
          <input type="button" class="button" value=" <?php $this->lan("sort");?> "
        onclick="myform.action='<?php echo G_MODULE_PATH; ?>/wap/listorder/dosubmit';myform.submit();"/>
    </div>
  </div>
	<div class="btn_paixu"></div>
</div>

<script>
		$("#checkAll").on('change', function () {
		if ($(this).is(":checked")) { // 全选
			$(".checkOne").prop("checked",true);
		} else { // 反选
			$(".checkOne").prop("checked",false);
		}
	});
	
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
	
	$('.batchDel').click(function(){
         //获取所有选中的CheckBox的id
     	var  ids="";
     	$(".checkOne").each(function(i) {
			if($(this).prop('checked')){
				var id= $(this).attr("data-id");
				if(ids == ""){
					ids=id;
				}else{
					ids += ','+id;
				}
			}
		});
		// ajax批量删除
    // console.log(ids);
      	$.ajax({
            type: 'POST',
            url: '<?php echo G_MODULE_PATH;?>/wap/batch_del',
            data: {ids: ids},
            dataType: 'json',
            success: function (data) {
                     if (data == 0) {
                            // layer.msg("删除成功!");
							alert("删除成功")
                            setTimeout(function(){
                                location.reload(false);
                            },1500);
                      // $(window).attr('location','http://bata.yungoucms.cn/admin/content/goods_list');
                    }else {
                        //  layer.msg("删除失败!");
						 alert("删除失败")
                     
                    }
                }
            });
        ids="";
    });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 