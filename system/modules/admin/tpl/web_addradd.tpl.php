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
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/bootstrap.css" type="text/css">
	<link href="<?php echo G_GLOBAL_STYLE; ?>/global/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/stylesheet.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/common.js"></script>
	<style>
		.bk10 {
			height: 10px;
		}
		.form-group {
		    display: flex;
		}
	</style>
</head>

<body>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div id="content">
		<div class="container-fluid">
			<div class="panel panel-default">
				<div class="panel-heading">
				    <!--<?php echo $title; ?>-->
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php $this->lan("Address_group");?></h3>
				</div>
				<div class="panel-body">
					<form action="#" method="post"  id="form-geo-zone" class="form-horizontal">
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-name"><?php $this->lan("Address_group_name"); ?></label>
							<div class="col-sm-10">
								<input type="text" name="name" value="<?=$res['name']?>" placeholder="<?php $this->lan("Address_group_name"); ?>" id="input-name" class="form-control" />
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="language"><?php $this->lan("Belonging_language"); ?></label>
							<div class="col-sm-10">
								<select id="language" name="language">
									<option value=""><?php $this->lan("currency");?></option>
									<?php foreach($lg as $key => $l){ ?>
										<option value="<?php echo $l['lg_value']; ?>" <?php if($l['lg_value'] == $tempData['language']){echo "selected";} ?>><?php echo $l['lg_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-description"><?php $this->lan("describe"); ?></label>
							<div class="col-sm-10">
								<input type="text" name="desc" value="<?=$res['desc']?>" placeholder="<?php $this->lan("describe"); ?>" id="input-description" class="form-control" />
							</div>
						</div>
						<div class="form-group ">
							<label class="col-sm-2 control-label" for=""></label>
							<div class="col-sm-10">
								<input type="submit" name="dosubmit" value="<?php $this->lan("Submit");?>" class="submit btn btn-primary" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script type="text/javascript">

</script>

</html>