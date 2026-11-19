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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
body{ background-color:#fff}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>

<div class="table_form lr10">
<?php if(ROUTE_A=='edit' || ROUTE_A=='jifenEdit'){ ?>
<table width="100%"  cellspacing="0" cellpadding="0">
<form name="form" action="#" method="post">

    <!--<tr>-->
    <!--    <td align="right" width="200">请选择模型 </td>-->
    <!--    <td>-->
    <!--        <select name="info[modelid]" class="wid150" onChange="">-->
    <!--                     <option value="">≡ 请选择模型 ≡</option>-->
    <!--            <?php foreach($models as $model){ ?>-->

    <!--                <?php if($model['modelid'] == 1){  ?>-->
    <!--                    <option value="<?php echo $model['modelid']; ?>" <?php if($model['modelid'] == 1){ echo 'selected';} ?>><?php echo $model['name']; ?></option>-->
    <!--                <?php } ?>-->
    <!--            <?php } ?>-->
    <!--            <?php echo $topmodel; ?>-->
    <!--        </select>-->
    <!--         <span><font color="#0c0">※ </font>请选择栏目模型</span> -->
    <!--</tr>-->
	<tr>
    		<td align="right" class="wid100" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Belonging_column");?></td>
			<td style="display:flex;"><select name="cateid" class="wid100" style="margin:0!important;">
				<?php echo $categoryshtml; ?>
    			</select>
    		</td>
    </tr>
    <tr>
			<td align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Brand_name");?></td>
			<td style="display:flex;"><input type="text"  name="name" class="input-text wid100" placeholder="<?php $this->lan("tips1");?>" value="<?php echo $brands['name'] ; ?>"></td>
	</tr>
    <tr>
      <td align="right" class="wid100" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("exhibit");?></td>
      <td style="display: flex;flex-direction: column;width: fit-content;gap: 15px;">
        <?php
            foreach($languages as $k=>$v){?>
            <div>
                <input  type="text" 
                        name="languages[<?php echo $v['lg_value']; ?>]"
                        value="<?php echo $brands["languages"][$v['lg_value']]; ?>" 
                        style="min-width:407px;width:407px" placeholder="" class="input-text">
                        <span style="width: 320px; display:inline-block;    text-align:left;"><?php echo $v['lg_value']."-".$v['lg_name']; ?></span>
            </div>
        <?php } ?>
      </td>  
    </tr>
    <tr>
			<td align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("sort");?></td>
			<td style="display:flex;"><input type="text"  name="order" onKeyUp="value=value.replace(/[^\d]/ig,'')" placeholder="<?php $this->lan("tips2");?>" class="input-text wid100" value="<?php echo $brands['order'] ; ?>">
            <span></span>
            </td>
	</tr>
    <tr height="60px">
			<td align="right" style="text-align: right !important;padding-right: 10px;"></td>
			<td style="display:flex;"><input class="button" type="submit" name="dosubmit" style="margin:0!important;" value="<?php $this->lan("Submit");?> " /></td>
	</tr>
</form>
</table>
<?php } ?>
<?php if(ROUTE_A=='insert' || ROUTE_A=='jifenInsert'){ ?>
<table width="100%"  cellspacing="0" cellpadding="0">
<form name="form" action="#" method="post">

    <!--<tr>-->
    <!--    <td align="right" width="200">请选择模型 </td>-->
    <!--    <td>-->
    <!--        <select name="info[modelid]" class="wid150" onChange="">-->
    <!--                     <option value="">≡ 请选择模型 ≡</option>-->
    <!--            <?php foreach($models as $model){ ?>-->

    <!--                <?php if($model['modelid'] == 1){  ?>-->
    <!--                    <option value="<?php echo $model['modelid']; ?>" <?php if($model['modelid'] == 1){ echo 'selected';} ?>><?php echo $model['name']; ?></option>-->
    <!--                <?php } ?>-->
    <!--            <?php } ?>-->
    <!--            <?php echo $topmodel; ?>-->
    <!--        </select>-->
    <!--         <span><font color="#0c0">※ </font>请选择栏目模型</span> -->
    <!--</tr>-->
	<tr>
    		<td align="right" class="wid100" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Belonging_column");?></td>
			<td style="display:flex;"><select name="cateid" class="morediyi" style="margin:0!important;">
            	<option selected>≡ <?php $this->lan("tips3");?> ≡</option>
				<?php echo $categoryshtml; ?>
    			</select>
    		</td>
    </tr>
    <tr>
			<td align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Brand_name");?></td>
			<td style="display:flex;"><input type="text" placeholder="<?php $this->lan("tips1");?>" name="name" class="input-text wid100"></td>
	</tr>
    <tr>
			<td align="right" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("sort");?></td>
			<td style="display:flex;"><input type="text" name="order" onKeyUp="value=value.replace(/[^\d]/ig,'')" placeholder="<?php $this->lan("tips2");?>" class="input-text wid100">
            <span></span>
            </td>
	</tr>
    <tr height="60px">
			<td align="right" style="text-align: right !important;padding-right: 10px;"></td>
			<td style="display:flex;"><input class="button" type="submit" name="dosubmit" style="margin:0!important;" value="<?php $this->lan("Submit");?> " /></td>
	</tr>
</form>
</table>
<?php } ?>
</div>
<script>
$(document).ready(function(){
    $('.morediyi option:nth-child(2)').attr('selected','selected')
}) 
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 
