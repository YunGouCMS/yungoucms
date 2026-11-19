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
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<link type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/sample/css/sample.css" rel="stylesheet" media="screen" />
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/ckeditor.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/translations/zh-cn.js"></script>
<script type="text/javascript">
window.onload = function(){
	DecoupledEditor
		.create( document.querySelector( '#editor' ), {
			language: 'zh-cn',
			ckfinder: {
				uploadUrl: '/api/tjupload/upeditimage'
			}
		} )
		.then( editor => {
			const toolbarContainer = document.querySelector( '.toolbar-container' );
	
			toolbarContainer.prepend( editor.ui.view.toolbar.element );
	
			window.editor = editor;
		} )
		.catch( err => {
			console.error( err.stack );
		} );
};
</script>
<style>
	.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
<!--	<h3 class="nav_icon">文章修改</h3><span class="span_fenge lr10"></span>-->
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<form method="post" action="#">
	<table width="100%"  cellspacing="0" cellpadding="0">
		<tr>
			<td align="right" width="120" style="text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("Belonging_category");?> </td>
			<td style="display:flex;">            
				<select name="cateid" style="margin:0!important;">               
                <?php echo $categoryshtml; ?>                
                </select>            	
            </td>
		</tr>        
		<tr>
			<td align="right" style="text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("Article_title");?> </td>
			<td style="display:flex;"><input type="text" value="<?php echo $info['title']; ?>" style="<?php echo $info['title_style']; ?>"            
             name="title" id="title" onKeyUp="return gbcount(this,100,'texttitle');" placeholder="<?php $this->lan("Article_title");?>" class="input-text wid400">
           <!-- <input type="hidden"  value="<?php echo $title_color; ?>"   name="title_style_color" id="title_style_color"/>
            <input type="hidden" value="<?php echo $title_bold; ?>"  name="title_style_bold" id="title_style_bold"/>
            <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/colorpicker.js"></script>
            <img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/colour.png" width="15" height="16" onClick="colorpicker('title_colorpanel','set_title_color');" style="cursor:hand"/>
             <img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/bold.png" onClick="set_title_bold();" style="cursor:hand"/>
            <span style="margin-left:10px">还能输入<b id="texttitle">100</b>个字符</span> -->
            </td>
		</tr>
        <!-- <tr>
			<td align="right">作者 </td>
			<td><input type="text" value="<?php echo $info['author']; ?>" name="zuoze" placeholder="请输入作者名称" class="input-text wid100" /></td>
		</tr> -->
        <!--tr>
			<td align="right">关键字：</td>
			<td><input type="text" name="keywords" value="<?php echo $info['keywords']; ?>"  name="title"  class="input-text wid400">
            <span>多个关键字请用   ,  号分割开</span>
            </td>
		</tr>
        <tr>
			<td align="right">摘要：</td>
			<td><textarea name="description" placeholder="<?php echo $info['description']; ?>" value="<?php echo $info['description']; ?>"  class="wid400" onKeyUp="gbcount(this,250,'textdescription');" style="height:60px"></textarea><br> <span>还能输入<b id="textdescription">250</b>个字符</span>
            </td> 
		</tr>
		<tr>
			<td align="right">缩略图：</td>
			<td>
           	<input type="text" id="imagetext" name="thumb" value="<?php echo $info['thumb']; ?>" class="input-text wid300">
			<input type="button" class="button"
             onClick="GetUploadify('<?php echo WEB_PATH; ?>','uploadify','缩略图上传','image','photo',1,500000,'imagetext')" 
             value="上传图片"/>
			</td>
		</tr-->
      <tr>
			<td height="300" align="right" style="text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("Content_details");?> </td>
			<td>
				<main>
					<div class="document-editor">
						<div class="toolbar-container" style="margin:0!important;"></div>
						<div name="editorValue" id="editor" style="margin:0!important;">
							<?php echo $info['content']; ?>
						</div>
					</div>
				</main>
            </td>  
		</tr>
        <input type="hidden" value="" name="editorValue">
        <!--tr>
			<td height="124" align="right">组　图：</td>
			<td><fieldset class="uploadpicarr">
					<legend>列表</legend>
					<div class="picarr-title">最多可以上传<strong>50</strong> 张图片 <a onClick="GetUploadify('<?php echo WEB_PATH; ?>','uploadify','缩略图上传','image','photo',50,500000,'uppicarr')" style="color:#ff0000; padding:10px;">  <input type="button" class="button" value="开始上传" /></a>
                    </div>					
                    	<ul id="uppicarr" class="upload-img-list">
                        <?php foreach($info['picarr'] as $pic): ?>                        
                        <li rel="<?php echo $pic; ?>"><input class="input-text" type="text" name="uppicarr[]" value="<?php echo $pic; ?>"><a href="javascript:void(0);" onClick="ClearPicArr('<?php echo $pic; ?>','<?php echo WEB_PATH; ?>')">删除</a></li>
                        <?php endforeach; ?>                     
                        </ul>
                    
				</fieldset>
             </td>
		</tr-->
       <tr>
			<td align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Release_time");?> </td>
			<td>           
            	<input name="posttime" type="text" id="posttime" value="<?php echo $info['posttime']; ?>" class="input-text posttime"  readonly="readonly" style="margin:0!important;padding: 0 0 0 35px !important;"/>
				<script type="text/javascript">
				date = new Date();
				Calendar.setup({
					inputField     :    "posttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>             
		</tr> 
		<tr>
        	<td align="right" style="width:120px;text-align: right!important;padding-right: 10px;"><?php $this->lan("language");?></td>
            <td width="900" style="display:flex;">
				<select name="language" style="margin:0!important;">
					<option value="" <?php if($info['language'] == ''){ echo 'selected'; } ?>><?php $this->lan("general");?></option>
					<?php foreach($languages as $key => $v){ ?>
						<option value="<?php echo $v['lg_value']; ?>" <?php if($info['language'] == $v['lg_value']){ echo 'selected'; } ?> ><?php echo $v['lg_name']; ?></option>
					<?php } ?>
				</select>
            </td>         
        </tr>
		<tr>
			<td align="right" width="120" style="text-align: right!important;padding-right: 10px;"><font color="red">*</font><?php $this->lan("keys");?> </td>
			<td style="display:flex;">            
				<select name="keys" style="margin:0!important;">       
					<?php foreach($articleKeys as $key => $v){ ?>
						<option value="<?php echo $v['keys']; ?>" <?php if($info['keys'] == $v['keys']){ echo 'selected'; } ?>><?php echo $v['name']; ?></option>
					<?php } ?>
                </select>            	
            </td>
		</tr> 
        <!--tr>
			<td align="right">点击量：</td>
            <td><input type="text" name="hit" class="input-text wid50" value="<?php echo $info['hit']; ?>"/></td>
			<td>
            </td>             
		</tr-->         
        <tr height="60px">
			<td align="right"></td>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
			<td style="display:flex;"><input type="submit" name="dosubmit" class="button dosubmit"  onclick="return tovalid();" style="margin:0!important;" value="<?php $this->lan("Submit");?>" /></td>
		</tr>
	</table>
</form>
</div>
<span id="title_colorpanel" style="position:absolute; left:568px; top:115px" class="colorpanel"></span>
<script type="text/javascript">
	var info=new Array();
    function gbcount(message,maxlen,id){
		
		if(!info[id]){
			info[id]=document.getElementById(id);
		}			
        var lenE = message.value.length;
        var lenC = 0;
        var enter = message.value.match(/\r/g);
        var CJK = message.value.match(/[^\x00-\xff]/g);//计算中文
        if (CJK != null) lenC += CJK.length;
        if (enter != null) lenC -= enter.length;		
		var lenZ=lenE+lenC;		
		if(lenZ > maxlen){
			info[id].innerHTML=''+0+'';
			return false;
		}
		info[id].innerHTML=''+(maxlen-lenZ)+'';
    }
	
function set_title_color(color) {
	$('#title').css('color',color);
	$('#title_style_color').val(color);
}

function set_title_bold(){
	if($('#title_style_bold').val()=='bold'){
		$('#title_style_bold').val('');	
		$('#title').css('font-weight','');
	}else{
		$('#title').css('font-weight','bold');
		$('#title_style_bold').val('bold');
	}
}
function tovalid() {
    var position = $('#editor').html();
    $("input[name='editorValue']").val(position);
}
	//API JS
	//window.parent.api_off_on_open('open');
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 