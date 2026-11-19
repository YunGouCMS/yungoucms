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
.nav_icon{font-weight: bold;}
.tecen {text-align:center;}
.wid200 {width:229px;}
.layui-row button {cursor: pointer;}
table tr td select,table tr td input {width:220px;max-width:220px;box-sizing: border-box;min-height: 32px;}
table tbody td *:not(.calendar *):not(.calendar td *), .table-list table tbody td *:not(.calendar *):not(.calendar td *) {margin:0!important;}
tr td:last-child {display:flex;}
body div.table_form.lr10 form table tr td:first-child {text-align:right!important;padding-right: 10px!important;}
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
			<td width="150" align="right"><?php $this->lan("Select_language");?> </td>
			<td >
				<!-- <input type="text" name="lg_value" value="<?php echo $res['lg_value'];?>" placeholder="请输入语言代码" class="input-text wid300"/> -->
				<input type="hidden" name="lg_name" id="lg_name" value="<?php echo $res['lg_name'];?>" placeholder="<?php $this->lan("Pack_code");?> " class="input-text wid300"/>
				<select name="lg_value" id="lg_value">
                   <option value="Chinese" <?php if($res['lg_value'] == "Chinese") {echo "selected";} ?>>Simplified (简体)</option>
                   <option value="English" <?php if($res['lg_value'] == "English") {echo "selected";} ?>>English (English)</option>
                   <option value="Vietnamese" <?php if($res['lg_value'] == "Vietnamese") {echo "selected";} ?>>Vietnamese (Tiếng Việt)</option>
                   <option value="Laos" <?php if($res['lg_value'] == "Laos") {echo "selected";} ?>>Lao (ລາວ)</option>
                   <option value="Indonesia" <?php if($res['lg_value'] == "Indonesia") {echo "selected";} ?>>Indonesian (Indonesia)</option>
                   <option value="Thailand" <?php if($res['lg_value'] == "Thailand") {echo "selected";} ?>>Thai (ไทย)</option>
                   <option value="Cambodia" <?php if($res['lg_value'] == "Cambodia") {echo "selected";} ?>>Khmer (ភាសាខ្មែរ)）</option>
                   <option value="Japanese" <?php if($res['lg_value'] == "Japanese") {echo "selected";} ?>>Japanese (日本語)</option>
                   <option value="Korean" <?php if($res['lg_value'] == "Korean") {echo "selected";} ?>>Korean (한국어)</option>
                   <option value="Singapore" <?php if($res['lg_value'] == "Singapore") {echo "selected";} ?>>Singapore（简体）</option>
                   <option value="Taiwan" <?php if($res['lg_value'] == "Taiwan") {echo "selected";} ?>>Traditional (繁体)</option>
                   <option value="Brazil" <?php if($res['lg_value'] == "Brazil") {echo "selected";} ?>>Portuguese (Português)</option>
                   <option value="Ecuador" <?php if($res['lg_value'] == "Ecuador") {echo "selected";} ?>>Spanish (Español)</option>
                   <option value="Italy" <?php if($res['lg_value'] == "Italy") {echo "selected";} ?>>Italian (Italiano)</option>
                   <option value="Philippines" <?php if($res['lg_value'] == "Philippines") {echo "selected";} ?>>Filipino (Filipino)</option>
                   <option value="Arab" <?php if($res['lg_value'] == "Arab") {echo "selected";} ?>>Arabic (عربى)</option>
                   <option value="Russia" <?php if($res['lg_value'] == "Russia") {echo "selected";} ?>>Russian (Pусский)</option>
                   <option value="Hindi" <?php if($res['lg_value'] == "Hindi") {echo "selected";} ?>>Hindi (हिन्दी)</option>
				   <option value="Myanmar" <?php if($res['lg_value'] == "Myanmar") {echo "selected";} ?>>Myanmar (မြန်မာ)</option>
             </select>
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("Upload_flag");?> </td>
			<td>
				<div class="layui-row">
					<div class="layui-col-md1 width100">
						<div class="layui-upload-list height66">
							<div class="layui-bottom-img">
								<span>+</span>
								<text><?php $this->lan("Click_upload");?></text>
							</div>
							<img class="layui-upload-img" width="100px" src="<?php echo $res['img']; ?>"  id="national_flag-img">
							<input type="hidden" id="national_flag-lack" name="national_flag" value="<?php echo $res['national_flag']; ?>">
							<p id="demoText"></p>
							<button type="button" id="addbanner" onclick="uploadimg('上传图片','#national_flag','national_flag','language')"></button>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="120" align="right"> <?php $this->lan("Flag_status");?></td>
			<td>
               <div style="display:flex;align-items: center;margin-top: 4px;">
               <div style="display:flex;white-space: nowrap;align-items: center;gap: 5px;"> <input style="min-width:13px;width:13px" type="radio" name="flag_status" value="1" <?php if(!$res || $res['flag_status'] == 1){echo 'checked';} ?> class="input-text" ><?php $this->lan("open");?></div>
                <div style="padding-left: 10px; display:flex;white-space: nowrap;align-items: center;gap: 5px;">
                <input style="min-width:13px;width:13px;" type="radio" name="flag_status" value="2" <?php if($res['flag_status'] == 2){echo 'checked';} ?> class="input-text"><?php $this->lan("close");?>
                </div>
               </div>
            </td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("country");?> </td>
			<td>
				<input type="text" name="country" id="country" value="<?php echo $res['country'];?>" placeholder="<?php $this->lan("country");?>" class="input-text wid200 tecen"/>
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("Select_time_zone");?> </td>
			<td>
				<select name='time_zone' id="time_zone" class="wid200 tecen">
					<?php
						foreach($time_zones as $key=>$val){
							$selected = '';
							foreach($val as $k=>$v){
								if($res['time_zone'] == "$key/$v"){$selected = "selected";}else{$selected = '';}
								echo "<option  value='$key/$v' $selected>$key/$v</option>";
							}
						}
						if($selected == "" && $res){
							echo "<option class='opt' value='{$res['time_zone']}' selected>{$res['time_zone']}</option>";
						}
					?>
				</select>
				<div id="time_zone_date"></div>
				<!-- <input type="text" name="time_zone" id="time_zone" value="<?php echo $res['time_zone'];?>" placeholder="请输入时区" class="input-text wid200 tecen"/> -->
			</td>
		</tr>
		<tr id="cry">
			<td width="120" align="right"><?php $this->lan("currency");?> </td>
			<td>
				<!-- <input type="text" name="currency_unit" id="currency_unit" value="<?php echo $res['currency_unit'];?>" placeholder="<?php $this->lan("currency");?>" class="input-text wid200 tecen"/> -->
				<input list="browsers" name="currency_unit" id="currency_unit" value="<?php echo $res['currency_unit'];?>" placeholder="<?php $this->lan("currency");?>" class="input-text wid200 tecen">
				<datalist id="browsers">
					<?php foreach($currency_units as $vl){
						echo "<option value='{$vl[2]}'>{$vl[0]}-{$vl[1]}({$vl[3]})</option>";	
					}?>
				</datalist>
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("area_code");?> </td>
			<td>
				<input list="areas" type="text" name="area_code" id="area_code" value="<?php echo $res['area_code'];?>" placeholder="<?php $this->lan("area_code");?>" class="input-text wid200 tecen"/>
				<datalist id="areas">
					<?php foreach($area_codes as $vl){
						echo "<option value='{$vl[0]}'>{$vl[1]}({$vl[0]})</option>";	
					}?>
			</td>
		</tr>
		<tr>
			<td width="120" align="right"><?php $this->lan("state");?></td>
			<td>
               <div style="display:flex;align-items: center;margin-top: 4px;">
               <div style="display:flex;white-space: nowrap;align-items: center;gap: 5px;"> <input style="min-width:13px;width:13px" type="radio" name="status" value="1" <?php if($res['status'] == 1){echo 'checked';} ?> class="input-text"><?php $this->lan("Enable");?></div>
                <div style="padding-left: 10px;display:flex;white-space: nowrap;align-items: center;gap: 5px;">
                <input style="min-width:13px;width:13px;" type="radio" name="status" value="0" <?php if($res['status'] == 0){echo 'checked';} ?> class="input-text"><?php $this->lan("closure");?>
                </div>
               </div>
            </td>
		</tr>
		
		
		<tr>
        	<td width="120" align="right"></td>
            <td>		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("submit");?>" >
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
			$(".opt").remove()
			$("#time_zone").append('<option class="opt" value="'+data.time_zone+'">'+data.time_zone+'</option>')
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
$("#time_zone").change(function(){
	var time_zone = $(this).val()
	$.post("<?php echo G_MODULE_PATH;?>/language/getTimezoneDate",{time_zone:time_zone},function(res){
		res = JSON.parse(res);
		if(res.code == 200){
			var data = res.data
			$("#time_zone_date").html(data.date)
		}
	}) 
})
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 