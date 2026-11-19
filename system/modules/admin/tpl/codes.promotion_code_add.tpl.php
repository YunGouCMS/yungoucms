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
	<style>
		tbody tr {
			line-height: 30px;
			height: 30px;
		}

		table {
			border: none !important;
			/* 去除整个表格的边框 */
			border-collapse: collapse !important;
			/* 防止表格单元格之间出现重复的边框 */
		}

		td,
		th {
			border: none !important;
			/* 去除表格单元格和表头的边框 */
		}
		body div.table-list.lr10 form table tbody tr td:first-child {text-align: right!important;}
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<form method="post" action="#">
			<table width="100%" >
				<tbody>
					<tr>
						<td align="right" width="200"><?php $this->lan("host", "域名"); ?> </td>
						<td style="display:flex;"><input type="text" name="host"  class="input-text" value="<?php echo $record['host']; ?>"></td>
					</tr>
					<tr>
						<td align="right" width="200"><?php $this->lan("type", "类型"); ?> </td>
						<td style="display:flex;">
							<select name="type" style="margin:0!important;">
								<?php foreach($types as $vl){
									if($vl['id']==$record['type']){
                                        echo '<option value="'.$vl['id'].'" selected>'.$vl['name'].'</option>';
                                    }else{
                                        echo '<option value="'.$vl['id'].'">'.$vl['name'].'</option>';
                                    }
								}?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right" width="200"><?php $this->lan("name", "渠道名称"); ?> </td>
						<td style="display:flex;"><input type="text" name="name"  class="input-text" value="<?php echo $record['name']; ?>"></td>
					</tr>
					<tr>
						<td align="right" width="200"><?php $this->lan("code", "代码/像素id"); ?> </td>
						<td>
							<textarea name="value" id="" cols="50" rows="10" style="height: auto;"><?php echo $record['value']; ?></textarea>
					</tr>
					<tr>
						<td align="right" width="200"><?php $this->lan("remarks", "备注"); ?> </td>
						<td style="display:flex;"><input type="text" name="desc"  class="input-text" style="width:300px" value="<?php echo $record['desc']; ?>"></td>
					</tr>
					<tr>
						<td  width="120" align="right"></td>
						<td style="display:flex;"><input type="submit" class="button" name="submit" value="<?php $this->lan("Submit","提交"); ?>" style="margin:0!important;"></td>
					</tr>
				</tbody>
		</form>
	</div><!--table-list end-->

	<script>
	</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>