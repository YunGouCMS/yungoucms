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
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js?v=1.0"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
.color_window_td a{ float:left; margin:0px 10px;}
.poishch{display: inline;}
.uploadarr_box{width: 130px;margin-left:0;position: relative;}
.uploadarr_box i {right: 0;top: 0;background: rgba(0, 0, 0, .3) url(/statics/templates/teyunbao/images/mobile/setIcon.png) no-repeat -64px -126px;position: absolute;}
#slist>div input:first-child {width:103px;}
</style>
</head>
<body>
<div class="header lr10">
    <h3 class="nav_icon" <?php if(!$id){?> style='display:none;' <?}?>><?php $this->lan("Edit_navigation_bar");?></h3>
    <span class="span_fenge lr10"></span>
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0" style="border:none">
  		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Navigation_code");?> </td>
			<td style="display:flex;">
				<!--<input type="text" name="code" value="<?php echo $wapone['code'];?>" style="width: 500px;" placeholder="请输入导航代码" class="input-text wid300" />-->
				<select name="code" style="margin:0!important;">
					<option value=""><?php $this->lan("tips1");?></option>
					<option value="kefu" <?php if($wapone['code'] == 'kefu'){ echo 'selected'; } ?>><?php $this->lan("Service_code");?></option>
					<option value="yaoqing" <?php if($wapone['code'] == 'yaoqing'){ echo 'selected'; } ?>><?php $this->lan("Invitation_code");?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("navigation_name");?> </td>
			<td style="display:flex;">
				<input type="text" name="title" value="<?php echo $wapone['title'];?>" style="width: 113px;" placeholder="<?php $this->lan("tips2");?>" class="input-text wid300" />
			</td>
		</tr>
		<tr>
			<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Navigation_link");?> </td>
			<td style="display:flex;">
				<input type="text" name="link" value="<?php echo $wapone['link'];?>" style="width: 500px;" placeholder="<?php $this->lan("tips3");?>" class="input-text wid300"/>
				
			</td>
		</tr>
		<tr>
					<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("isApp");?> </td>
					<td style="padding-left: 10px;display:flex;gap:10px;">
						<input type="radio" name="type" style="margin-top: -3px;margin:0!important;" value="2" title="" /> <?php $this->lan("yes");?>
						<input type="radio" name="type" style="margin-top: -3px;margin:0!important;" value="1" checked='checked' /> <?php $this->lan("no");?>
					</td>
				</tr>
		   <tr>
        	<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("img");?> </td>
            <td style="display:flex;">
				<div class="layui-row" style="margin:0!important;">
					<div class="layui-col-md1" style="width:50px;height:50px;margin-right:10px!important;">
						<div class="layui-upload-list height66" style="margin-top:5px">
							<div class="layui-bottom-img" <?php if ($wapone && !$wapone['img']): ?>style="opacity: 0;"<?php endif;?>>
								<span>+</span>
								<!--<text><?php $this->lan("Click_upload");?></text>-->
							</div>
							<img class="layui-upload-img" width="46px" style="top:55%" src="<?php  echo $wapone['img']? G_UPLOAD_PATH."/".$wapone['img'] : ''; ?>"  id="imgha-img">
							<input type="hidden" id="imgha-lack" name="thumb" value="<?php echo $wapone['img']; ?>">
							<p id="demoText"></p>
							<button type="button" onclick="uploadimg('上传图片','#imgha','image','banner')"></button>
						</div>
					</div>
					<div data-title="<?php $this->lan("tips4");?>" class="tip">
								<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
							</div>
				</div>

            </td>
        </tr>
		<tr>
        	<td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><?php $this->lan("language");?></td>
            <td width="900" style="display:flex;">
				<select name="language" style="margin:0!important;">
					<option value="" <?php if($wapone['language'] == ''){ echo 'selected'; } ?>><?php $this->lan("currency");?></option>
					<?php foreach($languages as $key => $v){ ?>
						<option value="<?php echo $v['lg_value']; ?>" <?php if($wapone['language'] == $v['lg_value']){ echo 'selected'; } ?> ><?php echo $v['country']; ?></option>
					<?php } ?>
				</select>
            </td>         
        </tr>
		<tr>
			<td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><?php $this->lan("Show_pictures");?></td>
			<td width="900">

			<div id="lunbo" style="display: flex;flex-wrap: wrap;align-items: flex-start;gap: 10px;">
			<?php foreach($wapone['picarr'] as $k=>$pic): ?>
						<div class="uploadarr_box" style="margin:0!important;">
							<i class="poishch" onclick="deletefile(this)"></i>
							<img src="<?php echo G_UPLOAD_PATH."/".$pic; ?>" onclick="uploadimg('修改图片','#uploadedig_arr_file600837','image','banner','10')" id="uploadedig_arr_file600837-img" alt="" style="width:130px;height:130px">
							<input type="hidden" id="demo<?php echo $k?>-lock" name="picarr[]" value="<?php echo $pic; ?>">
                    	</div>
						<?php endforeach; ?>
						<div class="layui-row" style="display: inline-flex;padding: 0;margin:0!important;">
						    <div class="layui-col-md1 width130">
								<div class="layui-upload-list height130">
									<div class="layui-bottom-img">
										<span>+</span>
										<text><?php $this->lan("Add_image");?></text>
									</div>
								    <p id="demoText"></p>
									<!--<button type="button" class="layui-btn" id="addbanner"></button>-->
									<input type="file" id="file-upload" multiple style="opacity: 0;height: 100%;position: absolute;top: 0;width: 100%;"/>
								</div>
						    </div>
						</div>
                    </div>
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><?php $this->lan("Show_word");?></td>
			<td width="900">
				<div style="display:flex;align-items:end">
				    <button class="add_keys layui-btn-sm layui-btn" type="button" onClick="add_keys('slist','dh_key')" style="margin:0!important;"><?php $this->lan("add");?></button>
				    	<div data-title="版本号key:edition;描述key:desc;安卓下载地址key:apk_upload;ios下载地址key:ios_upload;" style="margin:0 0 0 10px!important;" class="tip">
								<img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
							</div>
			    </div>
				<div id="slist" style="margin-top:5px;">
					<div id="dh_key" class="layui-row" style="margin-top:5px;display:none;text-align: left!important;">
						<?php $this->lan("key");?>
						<input type="text" data-name="data[dh_key][key]" value="" class="input-text">
						<?php $this->lan("value");?>
						<input type="text" data-name="data[dh_key][value]" value="" class="input-text ">
						<button type="button" onclick="remove_keys(this)" class="layui-btn-sm layui-btn" style="display: none; background:red;"><?php $this->lan("delete");?></button>
					</div>
					<?php foreach($wapone["data"] as $k=>$v){?>
						<div id="<?php echo $k?>" class="layui-row" style="margin-top:5px;text-align: left!important;">
						<?php $this->lan("key");?>
						<input type="text" name="data[<?php echo $k;?>][key]" value="<?php echo $v['key'];?>" class="input-text">
						<?php $this->lan("value");?>
						<input type="text" name="data[<?php echo $k;?>][value]" value="<?php echo $v['value'];?>" class="input-text ">
						<button type="button" onclick="remove_keys(this)" data-parent="<?php echo $k;?>" class="layui-btn-sm layui-btn" style=" background:red;"><?php $this->lan("delete");?></button>
					</div>
					<?php } ?>

				</div>
			</td>
		</tr>
		<tr>
        	<td width="120" align="right" style="text-align: right!important;padding-right: 10px;"></td>
            <td style="display:flex;">		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" style="margin:0!important;">
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
function set_title_color(color) {
	$('#title2').css('color',color);
	$('#title_style_color').val(color);
}
function set_title_bold(){
	if($('#title_style_bold').val()=='bold'){
		$('#title_style_bold').val('');	
		$('#title2').css('font-weight','');
	}else{
		$('#title2').css('font-weight','bold');
		$('#title_style_bold').val('bold');
	}
}

//API JS
//window.parent.api_off_on_open('open');

$("#addbanner").on('click',function (){
    var id = diu_Randomize(1000,100000)
    console.log(id)
    var html = `
         <div class="uploadarr_box" style="display: inline-block;margin-right: 5px;position: relative;">
                <i class="poishch" onclick="deletefile(this)"></i>
                    <img  onclick="uploadimg('上传商品图片','#imgHeadPhoto${id}','image','banner')" id="imgHeadPhoto${id}-img" width="130px" height="130px" src="/statics/uploads/admanage/mr.jpg">
                     <input type="hidden" name="picarr[]" id="imgHeadPhoto${id}-lack" value="">
          </div>
    `
	var bb = $("#lunbo>div:nth-last-child(1)")
	bb.before(html)
    // $("#lunbo").append(html)

    setTimeout(()=>{
        uploadimg('上传商品图片','#imgHeadPhoto'+id,'image','banner')
    },100)
})
$('#file-upload').on('change', function(e) {
    var files = e.target.files;
    for (var i = 0; i < files.length; i++) {
        var formData = new FormData();
        formData.append('filedata', files[i]);
        $.ajax({
          url: getDomain() + "/app/ajax/uploadImg", // 替换为你的上传处理URL
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(data) {
            if (data.code != 200) return
            var id = diu_Randomize(1000,100000)
            var html = `
              <div class="uploadarr_box" style="display: inline-block;margin-right: 5px;position: relative;" id="parent-${id}">
                <i class="poishch" onclick="deletefile(this)"></i>
                    <img  onclick="uploadimg('上传商品图片','#imgHeadPhoto${id}','image','banner')" id="imgHeadPhoto${id}-img" width="130px" height="130px" src="/statics/uploads/admanage/mr.jpg">
                     <input type="hidden" name="picarr[]" id="imgHeadPhoto${id}-lack" value="">
                </div>
            `
    		var bb = $("#lunbo>div:nth-last-child(1)")
    		bb.before(html)
            console.log('图片上传成功:', data.data);
            $(`#imgHeadPhoto${id}-complete`).val(data.data.url)
            $(`#imgHeadPhoto${id}-lack`).val(data.data.path)
            $(`#imgHeadPhoto${id}-img`).attr('src', data.data.url)
          },
          error: function() {
            console.log('图片上传失败');
          }
        });
    }
});
function  diu_Randomize(b,e){
    if (!b && b!=0 || !e){ return  "?" ;}
    return  Math.floor( ( Math.random() * e ) + b );
}
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 