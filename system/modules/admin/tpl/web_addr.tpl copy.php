<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> </title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
  .hb-edit{
    display: inline-block;
    width: 36px;
    height: 36px;
  	background:url(<?php echo G_GLOBAL_STYLE; ?>/global/image/edit_bg.png);
  }
  .hb-del{
    display: inline-block;
    width: 36px;
    height: 36px;
  	background:url(<?php echo G_GLOBAL_STYLE; ?>/global/image/delete_bg.png);
  }
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        <!--th width="30px" align="center"><input id="checkAll" type="checkbox"></th-->
		<th align="center"><?php $this->lan("Address_group_name");?></th>
		<th align="center"><?php $this->lan("describe");?></th>
        <th align="center"><?php $this->lan("operation");?></th>    
		</tr>
    </thead>
    <tbody>
		<tr>
          	<!--td align="center"><input class="checkOne" type="checkbox" data-id="72"></td-->
            <td align="center">全国</td>
            <td align="center">全国</td>
			<td align="center"> 
				<a href="<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_addredit/<?php echo $val['id'];?>" class="butaoin"><?php $this->lan("modify");?></a>
				<a href="<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_addrdel/<?php echo $val['id'];?>" class="butaoin butaish"><?php $this->lan("delete");?></a>
			</td>	
		</tr>
  	</tbody>
</table>
	<div class="btn_paixu"></div>
</div><!--table-list end-->

<script>

</script>
</body>
</html> 