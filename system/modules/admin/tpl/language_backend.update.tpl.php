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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
.color_window_td a{ float:left; margin:0px 10px;}
.nav_icon{
    font-weight: bold;
}
</style>
</head>
<body>
<div class="header lr10">
    <!--<h3 class="nav_icon">语言包管理</h3>-->
    <!--<span class="span_fenge lr10"></span>-->
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0" style="border:none">
		<!-- <tr> -->
			<!-- <td width="120" align="right">语言名称 </td> -->
			<!-- <td> -->
				<!-- <input type="text" name="lg_name" value="<?php echo $res['lg_name'];?>" placeholder="请输入语言名称" class="input-text wid300" /> -->
			<!-- </td> -->
		<!-- </tr> -->
		<tr>
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;"><?php $this->lan("Pack_code");?> </td>
			<td style="display: flex;">
				<!-- <input type="text" name="lg_value" value="<?php echo $res['lg_value'];?>" placeholder="请输入语言代码" class="input-text wid300"/> -->
				<input type="hidden" name="lg_name" id="lg_name" value="<?php echo $res['lg_name'];?>" placeholder="请输入语言代码" class="input-text wid300"/>
				<select name="lg_value" id="lg_value" style="margin: 0!important;">
				<option value="Chinese" <?php if($res['lg_value'] == "Chinese") {echo "selected";} ?>>简体中文（中国）</option>
                   <option value="English" <?php if($res['lg_value'] == "English") {echo "selected";} ?>>English（北美）</option>
                   <option value="Vietnamese" <?php if($res['lg_value'] == "Vietnamese") {echo "selected";} ?>>Việt Nam（越南）</option>
                   <option value="Hindi" <?php if($res['lg_value'] == "Hindi") {echo "selected";} ?>>Hindi（印地）</option>
                   <!-- <option value="Vietnamese" <?php if($res['lg_value'] == "Vietnamese") {echo "selected";} ?>>Việt Nam（越南）</option>
                   <option value="Laos" <?php if($res['lg_value'] == "Laos") {echo "selected";} ?>>ອັກສອນລາວ（老挝）</option>
                   <option value="Indonesia" <?php if($res['lg_value'] == "Indonesia") {echo "selected";} ?>>Bahasa Indonesia（印尼）</option>
                   <option value="Thailand" <?php if($res['lg_value'] == "Thailand") {echo "selected";} ?>>ภาษาไทย（泰国）</option>
                   <option value="Cambodia" <?php if($res['lg_value'] == "Cambodia") {echo "selected";} ?>>ភាសាខ្មែរ（柬埔寨）</option>
                   <option value="Japanese" <?php if($res['lg_value'] == "Japanese") {echo "selected";} ?>>にほんご（日语）</option>
                   <option value="Korean" <?php if($res['lg_value'] == "Korean") {echo "selected";} ?>>한국어（韩语）</option>
                   <option value="Singapore" <?php if($res['lg_value'] == "Singapore") {echo "selected";} ?>>简体中文（新加坡）</option>
                   <option value="Taiwan" <?php if($res['lg_value'] == "Taiwan") {echo "selected";} ?>>繁體中文（中国台湾）</option>
                   <option value="Brazil" <?php if($res['lg_value'] == "Brazil") {echo "selected";} ?>>Brasil（巴西）</option>
                   <option value="Ecuador" <?php if($res['lg_value'] == "Ecuador") {echo "selected";} ?>>República del Ecuador（厄瓜多尔） </option>
                   <option value="Italy" <?php if($res['lg_value'] == "Italy") {echo "selected";} ?>>Regno d'Italia(意大利)</option>
                   <option value="Philippines" <?php if($res['lg_value'] == "Philippines") {echo "selected";} ?>>Tagalog（菲律宾）</option>
                   <option value="Arab" <?php if($res['lg_value'] == "Arab") {echo "selected";} ?>>اللغة العربية（阿拉伯语）</option>
                   <option value="Russian" <?php if($res['lg_value'] == "Russian") {echo "selected";} ?>>Русский язык（俄语）</option> -->
                   <option value="Portugal" <?php if($res['lg_value'] == "Portugal") {echo "selected";} ?>>Portugal（葡萄牙语）</option>
             </select>
		</tr>
		<tr style="display:none;">
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;">选择国旗 </td>
			<td>
				<div class="layui-row">
					<div class="layui-col-md1 width100">
						<div class="layui-upload-list height66">
							<div class="layui-bottom-img">
								<span>+</span>
								<text>点击上传</text>
							</div>
							<img class="layui-upload-img" width="100px" src="<?php echo $res['img']; ?>"  id="national_flag-img">
							<input type="hidden" id="national_flag-lack" name="national_flag" value="<?php echo $res['national_flag']; ?>">
							<p id="demoText"></p>
							<button type="button" id="addbanner" onclick="uploadimg('上传图片','#national_flag','national_flag','language')"></button>
						</div>
					</div>
					<div class="layui-col-md9">
					    100*100px，支持Png
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;"><?php $this->lan("country");?> </td>
			<td style="display: flex;">
				<input type="text" name="country" id="country" value="<?php echo $res['country'];?>" placeholder="<?php $this->lan("enter_country");?>" class="input-text wid300"/>
			</td>
		</tr>
		<tr style="display:none;">
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;">时区 </td>
			<td>
				<input type="text" name="time_zone" id="time_zone" value="<?php echo $res['time_zone'];?>" placeholder="请输入时区" class="input-text wid300"/>
			</td>
		</tr>
		<tr style="display:none;">
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;">货币单位 </td>
			<td>
				<input type="text" name="currency_unit" id="currency_unit" value="<?php echo $res['currency_unit'];?>" placeholder="请输入货币单位" class="input-text wid300"/>
			</td>
		</tr>
		<tr style="display:none;">
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;">手机区号 </td>
			<td>
				<input type="text" name="area_code" id="area_code" value="<?php echo $res['area_code'];?>" placeholder="请输入手机区号" class="input-text wid300"/>
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align:right!important;padding-right: 10px;"><?php $this->lan("state");?></td>
			<td style="display: flex;">
               <div style="display:flex;align-items: center;margin: 4px 0!important;">
               <div style="display: flex;white-space: nowrap;align-items: center;gap: 5px;"> <input style="min-width:13px;width:13px" type="radio" name="status" value="1" <?php if($res['status'] == 1){echo 'checked';} ?> class="input-text"><?php $this->lan("open");?></div>
                <div style="padding-left: 10px;display: flex;white-space: nowrap;align-items: center;gap: 5px;">
                <input style="min-width:13px;width:13px;" type="radio" name="status" value="0" <?php if($res['status'] == 0){echo 'checked';} ?> class="input-text"><?php $this->lan("close");?>
                </div>
               </div>
            </td>
		</tr>
		
		
		<tr>
        	<td width="120" align="right" style="text-align:right!important;padding-right: 10px;"></td>
            <td style="display: flex;">		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("submit");?>" style="margin: 0!important;">
            </td>
		</tr>
</table>
</form>
</div><!--table-list end-->
<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
<script>
function upImage(){
	return document.getElementById('imgfield').click();
}
</script>
<script type="text/javascript">
//API JS
//window.parent.api_off_on_open('open');
function lgSel(){
	$("#lg_name").val($("#lg_value").find("option:selected").text())
	var lg_value = $("#lg_value").val()
	$.post("<?php echo G_MODULE_PATH?>/setting/languageChange",{lg_value:lg_value},function(res){
		res = JSON.parse(res);
		if(res.code == 200){
			var data = res.data
			$("#national_flag-img").attr("src",data.img)
			$("#national_flag-lack").val(data.flag)
			$("#country").val(data.country)
			$("#time_zone").val(data.time_zone)
			$("#currency_unit").val(data.unit)
			$("#area_code").val(data.area_code)
		}
	})
}
$("#lg_value").change(function(){
	lgSel()
})
<?php
if(!$id){
?>
	lgSel()
<?php
}
?>
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 