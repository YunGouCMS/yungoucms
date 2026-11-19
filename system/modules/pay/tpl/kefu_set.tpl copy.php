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
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<style>
tr{height:40px;line-height:40px}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment(); ?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
		<tr>
			<td width="220" align="right">客服名称：</td>
			<td><input type="text" name="name" value="<?php echo $info['name']; ?>" class="input-text">
			</td>
		</tr>
		<tr>
			<td width="220" height="80" align="right">二维码：</td>
			<td>
			<img id="imgHeadPhoto" src="<?php echo G_UPLOAD_PATH.'/'.$info['img']; ?>"  style="width: 70px; height: 50px; border: solid 1px #d2e2e2;" alt="" />
			<input type="file" name="pay_thumb" onchange="PreviewImage(this,'imgHeadPhoto','divPreview');" style="width: 69px;" accept="image/png, image/jpeg, image/gif, image/jpg"/>
			</td>
		</tr>

        <tr>
        	<td width="220" align="right"></td>
        	<input type="hidden" name="id" value="<?php echo $id; ?>">
            <td><input type="submit" class="button" name="dosubmit"  value=" 提交 " ></td>
		</tr>
</table>
</form>

</div><!--table-list end-->

<script>

</script>
</body>
</html>
