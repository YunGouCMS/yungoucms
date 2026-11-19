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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/bootstrap.css" type="text/css">
<link href="<?php echo G_GLOBAL_STYLE; ?>/global/css/font-awesome.min.css" type="text/css" rel="stylesheet"/>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/stylesheet.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/bootstrap.min.js"></script>
<style>
</style>
</head>
<body>
  <div class="header lr10">
	<?php //echo $this->headerment();?>
	<h3 class="nav_icon"><?php $this->lan("edit_currency");?></h3><span class="span_fenge lr10"></span>
</div>
<div class="bk10"></div>
<div id="content">
  <div class="container-fluid">
       
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php $this->lan("edit_currency");?></h3>
      </div>
      <div class="panel-body">
        <form action="#" method="post" enctype="multipart/form-data" id="form-currency" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-title"><?php $this->lan("currency_name");?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $info['title']; ?>" placeholder="<?php $this->lan("currency_name");?>" id="input-title" class="form-control" />
                          </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-code"><?php $this->lan("code");?></label>
            <div class="col-sm-10">
              <input type="text" name="code" value="<?php echo $info['code']; ?>" placeholder="<?php $this->lan("code");?>" id="input-code" class="form-control" />
                            <!--span class="help-block">如果此货币为默认货币时，请不要改变。</span-->
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-symbol-left"><?php $this->lan("Left_symbol");?></label>
            <div class="col-sm-10">
              <input type="text" name="symbol_left" value="<?php echo $info['symbol_left']; ?>" placeholder="<?php $this->lan("Left_symbol");?>" id="input-symbol-left" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-symbol-right"><?php $this->lan("Right_symbol");?></label>
            <div class="col-sm-10">
              <input type="text" name="symbol_right" value="<?php echo $info['symbol_right']; ?>" placeholder="<?php $this->lan("Right_symbol");?>" id="input-symbol-right" class="form-control" />
            </div>
          </div>
          <!--div class="form-group">
            <label class="col-sm-2 control-label" for="input-decimal-place">小数位</label>
            <div class="col-sm-10">
              <input type="text" name="decimal_place" value="<?php echo $info['decimal_place']; ?>" placeholder="请不要填写小于0的整数" id="input-decimal-place" class="form-control" />
            </div>
          </div-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value"><?php $this->lan("unit");?></label>
            <div class="col-sm-10">
              <input type="text" name="unit" value="<?php echo $info['unit']; ?>" placeholder="<?php $this->lan("unit");?>" id="input-value" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value"><?php $this->lan("language");?></label>
            <div class="col-sm-10">
              <select name="language">
                <?php foreach($languages as $key => $v){ ?>
                  <option value="<?php echo $v['lg_value']; ?>" <?php if($info['language'] == $v['lg_value']){ echo 'selected'; } ?> ><?php echo $v['lg_value']."-".$v['lg_name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php $this->lan("state");?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                    <option value="1" <?php if($info['status'] == 1){ echo 'selected="selected"'; }else{ } ?>><?php $this->lan("open");?></option>
                	<option value="0" <?php if($info['status'] == 0){ echo 'selected="selected"'; }else{ } ?>><?php $this->lan("close");?></option>
              </select>
            </div>
          </div>
          <div class="form-group text-center">
           <button typle="submit" class="btn btn-danger btn-lg "><?php $this->lan("Submit");?></button>
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
</html> 