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
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th width="60px"><?php $this->lan("id", "id"); ?></th>
					<th width="150px" align="center"><?php $this->lan("host","绑定域名"); ?></th>
					<th width="200px" align="center"><?php $this->lan("type","类别"); ?></th>
					<th width="100px" align="center"><?php $this->lan("name","渠道名称"); ?></th>
					<th width="400px" align="center"><?php $this->lan("code","代码/像素id"); ?></th>
					<th width="150px" align="center"><?php $this->lan("remarks","备注"); ?></th>
					<th width="150px" align="center"><?php $this->lan("operation","操作"); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list as $v) { ?>
					<tr>
						<td align="center"><?php echo $v['id']; ?></td>
						<td align="center"><?php echo $v['host']; ?></td>
						<td align="center"><?php echo $v['type_name']; ?></td>
						<td align="center"><?php echo $v['name']; ?></td>
						<td align="center"><textarea cols="30" rows="10" style="height: auto; border: none;" readonly><?php echo $v['value']; ?></textarea></td>
						<td align="center"><?php echo $v['desc'];?></td>
						<td align="center">
							<a class="butaoin" href="<?php echo G_MODULE_PATH.'/codes/promotion_code_edit/'.$v['id']; ?>"><?php $this->lan("modify","修改"); ?></a>
							<a class="butaoin butaish" href="<?php echo G_MODULE_PATH.'/codes/promotion_code_del/'.$v['id']; ?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete","删除"); ?></a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	</div><!--table-list end-->
	<div id="pages">
      <ul>
        <li><?php $this->lan("common"); ?> <?php echo $total; ?> <?php $this->lan("strip"); ?></li><?php echo $page->show('one', 'li'); ?>
      </ul>
    </div>
	<script>
	</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>