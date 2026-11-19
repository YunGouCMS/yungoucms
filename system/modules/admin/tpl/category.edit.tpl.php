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
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<style>
iframe{ font-size:36px;}
.con-tab{ margin:10px; color:#444}
.con-tab #tab-i{ margin-left:20px; overflow:hidden; height:27px; _height:28px; }
.con-tab #tab-i li{
	float:left;background:#eaeef4;
	padding:0 8px;border:1px solid #dce3ed;
	height:25px;_height:26px;line-height:26px;
	margin-right:5px;cursor: pointer; position:relative;z-index:0;
}
.con-tab div.con-tabv{
	clear:both; border:1px solid #dce3ed;
	width:100%;
	margin-top:-1px; 
	/*padding-top:30px;*/
	background-color:#fff; position:relative; z-index:1;}

#tab-i li.on{ background-color:#fff;color:#444; font-weight:bold; border-bottom:1px solid #fff;  position:relative;z-index:2;}

table th{ font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
.con-tabv tr{ text-align:left}
input.button{ display:inline-block}
.table-button{
  padding: 5px 50px;
  padding-left: 220px;
  background:none;
}
.table-list table tr, .table-list tr td {
    border: 0!important; 
}
</style>
<div class="bk10"></div>
<div class="table-list con-tab lr10" id="con-tab">
<?php if($catetype=='def'){ ?>
	<ul id="tab-i">
        <li name="con-tabk"><?php $this->lan("Basic_options");?></li>
<!--        <li name="con-tabk">SEO 设置</li>-->
    </ul>
<div name='con-tabv' class="con-tabv">
 <form action="#" id="form" method="post" enctype="multipart/form-data">
 <table width="100%" class="table_form">
 <tbody>
		<tr>
        <th width="200" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("tips");?> </th>
        <td style="display:flex;">
		 <select name="info[modelid]" disabled="false" style="background:#eee;margin:0!important;" class="wid150"onChange="">
         <option value="<?php echo $cateinfo['model']; ?>"><?php echo $models[$cateinfo['model']]['name']; ?></option>
         <?php foreach($models as $model){ ?>
         <option value="<?php echo $model['modelid']; ?>"><?php echo $model['name']; ?></option>
         <?php } ?>
         </select>
         <!-- <span><font color="#0c0">※ </font>请选择栏目模型</span> -->
      </tr>
      <tr>
        <th width="200" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Previous_column");?> </th>
        <td style="display:flex;">
		<select name="info[parentid]" class="wid150" style="margin:0!important;">
        <option value="0">≡ <?php $this->lan("First_level");?> ≡</option>
        <?php echo $categoryshtml; ?>
        </select></td>
      </tr>     
      <tr>
        <th style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Column_name");?> </th>
        <td style="display:flex;"><input type="text" name="info[name]" class="input-text wid140" placeholder="<?php $this->lan("Column_name");?>" value="<?php echo $cateinfo['name']; ?>"
         onKeyUp="value=value.replace(/[^\a-\z\A-\Z0-9\u4E00-\u9FA5\_]/g,'')">
        	<!-- <span><font color="#0c0">※ </font>请输入栏目名称</span> -->
		</td>
      </tr>
	<tr>
      <th style="text-align: right!important;padding-right: 10px;"><?php $this->lan("English_name");?> </th>
        <td style="display:flex;"><input type="text" name="info[catdir]" placeholder="<?php $this->lan("English_name");?>" value="<?php echo $cateinfo['catdir']; ?>"
        onKeyUp="value=value.replace(/[^\w]/ig,'')" class="input-text wid140">
        <!-- <span><font color="#0c0">※ </font>请输入英文名称</span> -->
      </tr>
	<!-- <tr>
      <th>栏目图片 </th>
        <td>
			<div class="layui-row">
				<div class="layui-col-md1">
					<div class="layui-upload-list">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif;?>>
							<span>+</span>
							<text>点击上传</text>
						</div>
						<img class="layui-upload-img" width="88px" src="<?php echo G_UPLOAD_PATH.'/'.$cateinfo['info']['thumb']; ?>" id="photoimg-img">
						<input type="hidden" id="photoimg-lack" name="thumb" value="<?php echo $web['web_logo']; ?>">
						<p id="demoText"></p>
						<button type="button" onclick="uploadimg('上传图片','#photoimg','image','cateimg')"></button>
					</div>
				</div>
				<div class="layui-col-md9">
				    图片大小：160px*160px
				</div>
			</div>
        </td>
      </tr> -->
	<!--tr>
   	 	<th style="border-bottom:0px;">描述：</th>
   	 	<td style="border-bottom:0px;">
        <textarea name="info[description]"  maxlength="255" class="overflow" style="width:300px;height:60px;"><?php echo $cateinfo['info']['des']; ?></textarea></td>
    </tr-->
</tbody>
</table>
  </div>
    <div name='con-tabv' class="con-tabv">
  	<table width="100%" class="table_form ">
	<tbody><tr>
      <th width="200">META Title（栏目标题）<br>针对搜索引擎设置的标题</th>
      <td><input name="setting[meta_title]" type="text" id="meta_title" value="<?php echo $cateinfo['info']['meta_title']; ?>" size="60" maxlength="60" placeholder="请输入栏目标题,针对搜索引擎设置的标题" class="input-text"></td>
    </tr>
    <tr>
      <th>META Keywords（栏目关键词）<br>关键字中间用半角逗号,隔开</th>
      <td><textarea name="setting[meta_keywords]" id="meta_keywords" placeholder="请输入栏目关键词,关键字中间用半角逗号,隔开" style="width:90%;height:40px"><?php echo $cateinfo['info']['meta_keywords']; ?></textarea></td>
    </tr>
    <tr>
      <th>META Description（栏目描述）<br>针对搜索引擎设置的网页描述</th>
      <td><textarea name="setting[meta_description]" id="meta_description" placeholder="请输入栏目描述,针对搜索引擎设置的网页描述" style="width:90%;height:50px"><?php echo $cateinfo['info']['meta_description']; ?></textarea></td>
    </tr>
</tbody></table>
    </div>
<?php } ?>
<?php if($catetype=='goodsdef'){ ?>
	<!--<ul id="tab-i">-->
 <!--       <li name="con-tabk">基本选项</li>-->
 <!--       <li name="con-tabk">SEO 设置</li>-->
 <!--   </ul>-->
<div name='con-tabv' class="con-tabv">
 <form action="#" id="form" method="post" enctype="multipart/form-data">
 <table width="100%" class="table_form">
 <tbody>
		<!--<tr>-->
  <!--      <th width="200">请选择模型 </th>-->
  <!--      <td>-->
		<!-- <select name="info[modelid]" disabled="false" style="background:#eee;" class="wid150"onChange="">-->
  <!--       <option value="<?php echo $cateinfo['model']; ?>"><?php echo $models[$cateinfo['model']]['name']; ?></option>-->
  <!--       <?php foreach($models as $model){ ?>-->
  <!--       <option value="<?php echo $model['modelid']; ?>"><?php echo $model['name']; ?></option>-->
  <!--       <?php } ?>-->
  <!--       </select>-->
  <!--        <span><font color="#0c0">※ </font>请选择栏目模型</span> -->
  <!--    </tr>-->
      <tr>
        <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Parent_category");?> </th>
        <td style="display:flex;">
		<select name="info[parentid]" class="wid150" style="margin:0!important;">
        <option value="0">≡ <?php $this->lan("tips");?> ≡</option>
        <?php echo $categoryshtml; ?>
        </select></td>
      </tr>     
      <tr>
        <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Category_name");?> </th>
        <td style="display:flex;"><input type="text" name="info[name]" style="margin:0!important;" placeholder="<?php $this->lan("tips1");?>" class="input-text wid140" value="<?php echo $cateinfo['name']; ?>"
        >
        	<!-- <span><font color="#0c0">※ </font>请输入栏目名称</span> -->
		</td>
      </tr>
	<tr>
      <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("eng_name");?> </th>
        <td style="display:flex;"><input type="text" name="info[catdir]" style="margin:0!important;" placeholder="<?php $this->lan("tips2");?>" value="<?php echo $cateinfo['catdir']; ?>"
        onKeyUp="value=value.replace(/[^\w]/ig,'')" class="input-text wid140">
        <!-- <span><font color="#0c0">※ </font>请输入英文名称</span> -->
      </tr>
    <tr>
      <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("exhibit");?></th>
      <td style="display: flex;flex-direction: column;align-items: flex-start;width: max-content;gap: 15px;">
      <?php
        foreach($languages as $k=>$v){?>
          <div style="display:flex;">
              <input  type="text" 
                      name="languages[<?php echo $v['lg_value']; ?>]"
                      value="<?php echo $cateinfo["languages"][$v['lg_value']]; ?>" 
                      style="min-width:407px;width:407px" placeholder="" class="input-text">
                      <span style="width: 320px; display:inline-block;    text-align:left;margin:10px 0 0 10px!important;"><?php echo $v['lg_value']."-".$v['lg_name']; ?></span>
          </div>
      <?php } ?>
      </td>  
    </tr>
	<tr>
      <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Category_pictures");?> </th>
        <td>
			<div class="layui-row">
				<div class="layui-col-md1" style="width:50px;height:50px;margin:0!important;">
					<div class="layui-upload-list">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif;?>>
							<span>+</span>
							<text><?php $this->lan("Click_upload");?></text>
						</div>
						<img class="layui-upload-img" style="top:14px" width="50px" src="<?php echo G_UPLOAD_PATH.'/'.$cateinfo['info']['thumb']; ?>" id="photoimgs-img">
						<input type="hidden" id="photoimgs-lack" name="thumb" value="<?php echo $cateinfo['info']['thumb'];?>">
						<p id="demoText"></p>
						<button type="button" onclick="uploadimg('栏目图片','#photoimgs','image','cateimg')"></button>
					</div>
				</div>
				<div class="layui-col-md9" style="margin:0!important;text-align:left!important;padding-left:10px!important;">
				    <?php $this->lan("tips3");?>
				</div>
			</div>
        </td>
      </tr>
	<!--tr>
   	 	<th style="border-bottom:0px;">描述：</th>
   	 	<td style="border-bottom:0px;">
        <textarea name="info[description]"  maxlength="255" class="overflow" style="width:300px;height:60px;"><?php echo $cateinfo['info']['des']; ?></textarea></td>
    </tr-->
</tbody>
</table>
  </div>
    <div name='con-tabv' class="con-tabv">
  	<table width="100%" class="table_form ">
	<tbody><tr>
      <th width="200">META Title（栏目标题）<br>针对搜索引擎设置的标题</th>
      <td><input name="setting[meta_title]" type="text" id="meta_title" value="<?php echo $cateinfo['info']['meta_title']; ?>" placeholder="请输入栏目标题,针对搜索引擎设置的标题" size="60" maxlength="60" class="input-text"></td>
    </tr>
    <tr>
      <th>META Keywords（栏目关键词）<br>关键字中间用半角逗号,隔开</th>
      <td><textarea name="setting[meta_keywords]" id="meta_keywords" placeholder="请输入栏目关键词,关键字中间用半角逗号,隔开" style="width:90%;height:40px"><?php echo $cateinfo['info']['meta_keywords']; ?></textarea></td>
    </tr>
    <tr>
      <th>META Description（栏目描述）<br>针对搜索引擎设置的网页描述</th>
      <td><textarea name="setting[meta_description]" id="meta_description" placeholder="请输入栏目描述,针对搜索引擎设置的网页描述" style="width:90%;height:50px"><?php echo $cateinfo['info']['meta_description']; ?></textarea></td>
    </tr>
</tbody></table>
    </div>
<?php } ?>
<?php if($catetype=='danweb'){ ?>
	<ul id="tab-i">
    <li name="con-tabk">单网页选项</li>
    <li name="con-tabk">SEO 设置</li>
    <li name="con-tabk">选择模板</li>
    <li name="con-tabk">网页内容</li>
    </ul>
<div name='con-tabv' class="con-tabv"> 
<form id="form" method="post" enctype="multipart/form-data">
 <table width="100%" class="table_form">
 <tbody>
      <tr>
        <th width="200">上级栏目 </th>
        <td>
		<select name="info[parentid]" id="parentid">
        <option value="0">≡ 作为一级栏目 ≡</option>
        <?php echo $categoryshtml; ?>
        </select></td>
      </tr>     
      <tr>
        <th>栏目名称 </th>
        <td><input type="text" name="info[name]" placeholder="请输入栏目名称" class="input-text" value="<?php echo $cateinfo['name']; ?>"
        onKeyUp="value=value.replace(/[^\a-\z\A-\Z0-9\u4E00-\u9FA5\_]/g,'')">
        	<!-- <span><font color="#0c0" size="">※ </font>请输入栏目名称</span> -->
		</td>
      </tr>
	<tr id="catdir_tr">
      <th>英文目录 </th>
        <td><input type="text" name="info[catdir]" placeholder="请输入目录名称" value="<?php echo $cateinfo['catdir']; ?>"
         onKeyUp="value=value.replace(/[^\w]/ig,'')"    class="input-text">
        <!-- <span><font color="#0c0" size="">※ </font>请输入目录名称</span> -->
      </tr>     
	<tr>
      <th>栏目图片 </th>
        <td>
			<img id="imgHeadPhoto" src="<?php echo G_UPLOAD_PATH.'/'.$cateinfo['info']['thumb']; ?>"  style="width: 30px; height: 30px; border: solid 1px #d2e2e2;" alt="" />
			<input type="file" name="thumb" onchange="PreviewImage(this,'imgHeadPhoto','divPreview');" size="20" style="width: 69px;" accept="image/png, image/jpeg, image/gif, image/jpg"/>
        </td>
      </tr>
	<!--tr>
   	 	<th style="border-bottom:0px;">描述：</th>
   	 	<td style="border-bottom:0px;"><textarea name="info[description]"  maxlength="255" style="width:300px;height:60px;"><?php echo $cateinfo['info']['des']; ?></textarea></td>
    </tr-->
</tbody>
</table>
</div>
<div name='con-tabv' class="con-tabv">
  	<table width="100%" class="table_form ">
	<tbody><tr>
      <th width="200">META Title（栏目标题）<br>针对搜索引擎设置的标题</th>
      <td><input name="setting[meta_title]" type="text" id="meta_title" value="<?php echo $cateinfo['info']['meta_title']; ?>" placeholder="请输入栏目标题,针对搜索引擎设置的标题" size="60" maxlength="60" class="input-text"></td>
    </tr>
    <tr>
      <th>META Keywords（栏目关键词）<br>关键字中间用半角逗号,隔开</th>
      <td><textarea name="setting[meta_keywords]" id="meta_keywords" placeholder="请输入栏目关键词,关键字中间用半角逗号,隔开" style="width:90%;height:40px"><?php echo $cateinfo['info']['meta_keywords']; ?></textarea></td>
    </tr>
    <tr>
      <th>META Description（栏目描述）<br>针对搜索引擎设置的网页描述</th>
      <td><textarea name="setting[meta_description]" id="meta_description" placeholder="请输入栏目描述,针对搜索引擎设置的网页描述" style="width:90%;height:50px"><?php echo $cateinfo['info']['meta_description']; ?></textarea></td>
    </tr>
</tbody></table>
    </div>
    <div name='con-tabv' class="con-tabv">
  	<table width="100%" class="table_form ">
	<tbody>
    <tr>
      <th width="200">选择单网页模板 </th>
      <td style="margin:10px;">
      <select name="info[template]">
      	  <option value="<?php echo $cateinfo['info']['template']; ?>" selected><?php echo $cateinfo['info']['template']; ?></option>
           <?php 
		   		if($htmlarr=indexTemplate('/single_web\.(.*)\.html/i')){
					foreach($htmlarr as $html){
			?>
            <option value="<?php echo $html[0]; ?>"><?php echo $html[0]; ?></option>
            <?php }} ?>	

        </select>
      </td>
    </tr>
    </tbody></table>
    </div> 
    <div name='con-tabv' class="con-tabv">
		<div style="display:block; position:relative; margin-top:-31px; margin-bottom:-1px; margin-left:-1px;">		
            <script id="myeditor" type="text/plain"><?php echo base64_decode($cateinfo['info']['content']); ?></script>
            <textarea name="setting[content]" id="settingcontent" style="display:none"></textarea>
        </div>
    </div> 
<?php } ?>

<?php if($catetype=='link'){ ?>
	<ul id="tab-i"><li name="con-tabk">外部链接</li></ul>
	<div name='con-tabv' class="con-tabv">
    <form id="form" method="post">
 	<table width="100%" class="table_form">
	 <tbody>
         <tr>
        <th width="200">上级栏目 </th>
        <td>
		<select name="info[parentid]" id="parentid"> 
        <?php echo $categoryshtml; ?>
        </select></td>
      </tr>     
      <tr>
        <th>栏目名称 </th>
        <td><input type="text" name="info[name]" class="input-text" placeholder="请输入栏目名称" value="<?php echo $cateinfo['name']; ?>"
        onKeyUp="value=value.replace(/[^\a-\z\A-\Z0-9\u4E00-\u9FA5\_]/g,'')">
        	<!-- <span><font color="#0c0" size="">※ </font>请输入栏目名称</span> -->
		</td>
      </tr>
	<tr id="catdir_tr">
      <th>链接地址 </th>
        <td><input type="text" name="info[url]" placeholder="请输入链接地址,如：http://www.qq.com/" value="<?php echo $cateinfo['url']; ?>" class="input-text wid300">
        <!-- <span><font color="#0c0" size="">※ </font>请输入链接地址,如：http://www.qq.com/</span> -->
      </tr>      
	</table>
    </div>
<?php } ?>
</div>
<!--table-list end-->

   <div class="table-button lr10">
    	  <!-- <input type="button" value=" <?php $this->lan("modify");?>" onClick="checkform();" class="button"> -->
    	  <input type="submit" value=" <?php $this->lan("modify");?>"  class="button">
    	</form>
   </div>
<script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/GgTab.js"></script>
<script type="text/javascript">
Gg.Tab({i:"li con-tabk ~on",o:"div con-tabv",events:"click",num:1});

function upImage(){
	return document.getElementById('imgfield').click();
}
<?php if($catetype=='def'){ ?>
function checkform(){
	var form=document.getElementById('form');
	var error=null;	
	if(form.elements[0].value==''){error='请选择栏目模型!';}
	if(form.elements[2].value==''){error='请输入栏目名称!';}
	if(form.elements[3].value==''){error='请输入英文目录名称!';}
	if(error!=null){
    window.parent.message(error,8,2);return false;
  }
	form.submit();	
}
<?php } ?>
<?php if($catetype=='goodsdef'){ ?>
function checkform(){
	var form=document.getElementById('form');
	var error=null;	
	if(form.elements[0].value===''){error='请选择栏目模型!';}
	if(form.elements[2].value==''){error='请输入栏目名称!';}
	if(form.elements[3].value==''){error='请输入英文目录名称!';}
	if(error!=null){
    console.log(form.elements[0].name);
    layer.msg(error);return false;
    // window.parent.message(error,8,2);return false;
  }
  var url = $("#form").attr('action');
  var data = $(this).serialize() + '&submit=1&dosubmit=1&ajax_from=1';
  form_submit(url,data);
  return false;
	// form.submit();	
}
<?php } ?>
<?php if($catetype=='danweb'){ ?>
function checkform(){
	var form=document.getElementById('form');
	var error=null;	
	if(form.elements[0].value==''){error='请选择栏目模型!';}
	if(form.elements[1].value==''){error='请输入栏目名称!';}
	if(form.elements[2].value==''){error='请输入英文目录名称!';}
	if(form.elements[9].value==''){error='请选择栏目模板!';}	
	if(error!=null){window.parent.message(error,8,2);return false;}
	var Content=getContent();
	document.getElementById('settingcontent').value=Content;
	form.submit();	
}
<?php } ?>
<?php if($catetype=='link'){ ?>
function checkform(){
	var form=document.getElementById('form');
	var error=null;	
	if(form.elements[1].value==''){error='请输入栏目名称!';}
	if(form.elements[2].value==''){error='请输入链接地址!';}
	if(error!=null){window.parent.message(error,8,2);return false;}
	form.submit();	
}
<?php } ?>

</script>
<?php if($catetype=='danweb'){ ?>
<script type="text/javascript">
var editurl=Array();
editurl['editurl']='<?php echo G_PLUGIN_PATH; ?>/ueditor/';
editurl['imageupurl']='<?php echo G_ADMIN_PATH; ?>/ueditor/upimage/';
editurl['imageManager']='<?php echo G_ADMIN_PATH; ?>/ueditor/imagemanager';
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.config.js"></script>
<script type="text/javascript">
window.UEDITOR_CONFIG.initialContent="输入栏目内容...";
window.UEDITOR_CONFIG.initialFrameWidth='';
window.UEDITOR_CONFIG.initialFrameHeight=450;
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    //实例化编辑器
    var ue = UE.getEditor('myeditor');
    ue.addListener('ready',function(){
        this.focus()
    });
    function getContent() {
        return ue.getContent();
    }
    function hasContent() {
        var arr = [];
        arr.push( "使用editor.hasContents()方法判断编辑器里是否有内容" );
        arr.push( "判断结果为：" );
        arr.push(  UE.getEditor('myeditor').hasContents() );
        alert( arr.join( "\n" ) );
    }
</script>
<?php } ?>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 