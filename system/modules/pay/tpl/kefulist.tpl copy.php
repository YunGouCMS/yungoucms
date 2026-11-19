<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width="100px" align="center">客服名</th>
            <th width="200px" align="center">图片</th>
			<th width="100px" align="center">是否在线</th>
            <th width="100px" align="center">操作</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($list as $val): ?>
		<tr>
			<td align="center"><?php echo $val['name']; ?></td>	
			<td align="center"><img height="80px" width="80px" src="<?php echo G_UPLOAD_PATH.'/'.$val['img']; ?>"/></td>
			<td align="center">
				<?php if($val['type'] == 1){ ?>
				<font color='#0c0'  class="sta" state="<?php echo $val['status']; ?>" kid="<?php echo $val['id']; ?>" style="cursor:pointer;">是</font>
				<?php }else{ ?>
				<font color='#ff0000'  class="sta" state="<?php echo $val['status']; ?>" kid="<?php echo $val['id']; ?>" style="cursor:pointer;">否</font>
				<?php } ?>
			</td>		
			<td align="center">
				<a href="<?php echo G_MODULE_PATH; ?>/pay/kefu_set/<?php echo $val['id']; ?>">设置</a>
				<span class="span_fenge lr5">|</span>
				<a href="<?php echo G_MODULE_PATH; ?>/pay/del_kefu/<?php echo $val['id']; ?>">删除</a>
			</td>
		</tr>
		<?php endforeach; ?>
  	</tbody>
</table>
</div><!--table-list end-->

<script>
	$(".sta").on('click',function(){
		var e=$(this);
		var state=e.attr('state');
		var id=e.attr('kid');
		$.post("?/pay/pay/up_state",{status:state,kid:id},function(res){
			console.log(res);
			if(res == '1'){
				e.attr('color','#0c0');
				e.attr('state',res);
				e.html('是');
			}else{
				e.attr('color','#ff0000');
				e.attr('state',res);
				e.html('否');
			}
		})		
	})
</script>
</body>
</html> 