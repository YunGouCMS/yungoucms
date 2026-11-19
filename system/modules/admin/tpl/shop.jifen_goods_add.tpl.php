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

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/sample/css/sample.css" rel="stylesheet" media="screen" />
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/ckeditor.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/translations/zh-cn.js"></script>
<style>
    .bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
    .color_window_td a{ float:left; margin:0px 10px;}
    .rightgray {display: flex;align-items: center;}
    .rightgray div {background-color: #f5f5f5;border: 1px solid #B4B4B4;border-left-width: 0px;height: 27px;padding: 2px 15px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;line-height: 28px;}
    .rightgray input {margin: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;width: 85px;min-width: 80px;}
    .inpwid100 {width: 100%;margin: 0;box-sizing: border-box;padding: 2px 5px;}
    .imgbox{float:left;margin-left:3px;}
    .layui-btn {background-color: #000000;}
    .ChineseText{
        margin-left: 10px;
        margin-top: 15px;
    }
    .ChineseDiscount{
        margin-left: 10px;
        margin-top: 15px;
    }
    .layui-upload-list button,.layui-upload-list input,#lunbo img {cursor: pointer;}
</style>
</head>
<body>
<script>
$(function(){
    <?php if($tempData['cateid']){
    ?>
    brand();
    <?php
    } ?>
    function brand(){
        var parentId=$("#category").val(); 
        if(null!= parentId && ""!=parentId){ 
            $.post("<?php echo WEB_PATH; ?>/api/brand/json_brand/",{cid:parentId},function(myJSON){
                var options="";
                myJSON = JSON.parse(myJSON)
                if(myJSON.length>0){
                    $.each(myJSON, function(i, item) {
                        console.log(item);
                        options+="<option value="+item.id+">"+item.name+"</option>";
                    })
                    $("#brand").html(options);
                }
            });
        } 
    }
    $("#category").change(function(){ 
        brand()
    });
    brand()
}); 

function CheckForm(){
    var money = parseInt($("#money").val());
    return true;
}

window.onload=function(){
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
        
    var input=document.getElementById("uploadfile");
    var div;
    // 当用户上传时触发事件
    input.onchange=function(){
        readFile(this);
    }
    //处理图片并添加都dom中的函数
    var readFile=function(obj){
        // 获取input里面的文件组
        var fileList=obj.files;
        $('#uppicarr').html('');
        //对文件组进行遍历，可以到控制台打印出fileList去看看
        for(var i=0;i<fileList.length;i++){
            var reader= new FileReader();
            reader.readAsDataURL(fileList[i]);
                // 当文件读取成功时执行的函数
            reader.onload=function(e){
                div=document.createElement('div');
                div.setAttribute('class','imgbox');
                div.innerHTML='<img src="'+this.result+'" style="width:60px;height:60px;"/>';
                document.getElementById("uppicarr").appendChild(div);
            }
        }
    }
}
</script>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
    <form method="post" action="#" onSubmit="return CheckForm()" enctype="multipart/form-data">
        <table width="100%"  cellspacing="0" cellpadding="0">
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><font color="red">*</font><?php $this->lan("Belonging_language");?></td>
                <td style="display:flex;">
                    <select id="language" name="language" style="margin:0!important;">
                        <option value=""><?php $this->lan("currency");?></option>
                        <?php foreach($lg as $key => $l){ ?>
                            <option value="<?php echo $l['lg_value']; ?>" <?php if($l['lg_value'] == $tempData['language']){echo "selected";} ?>><?php echo $l['lg_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><font color="red">*</font><?php $this->lan("Belonging_category");?></td>
                <td style="display:flex;">
                    <select id="category" name="cateid" style="width:184px;margin:0!important;">           
                    <option value='0'><?php $this->lan("Select_category");?></option>
                        <?php 
                            foreach($category as $k => $v){ 
                                $selected = '';
                                if($v['cateid'] == $tempData['cateid']){$selected = "selected";}
                                echo "<option value='{$v['cateid']}' $selected>{$v['name']}</option>";
                            }
                        ?>
                    </select> 
                </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px;"><?php $this->lan("Belonging_brand");?></td>
                <td style="display:flex;">
                    <select id="brand" name="brandid" style="width:184px;margin:0!important;">
                        <option value="0">≡ <?php $this->lan("Choose_brand");?> ≡</option>
                    </select>
                </td>
            </tr>  
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><font color="red">*</font><?php $this->lan("Product_title");?></td>
                <td style="display:flex;">
                <input type="text" id="title" name="title" style="width:60%;height: 35px;" onKeyUp="return gbcount(this,100,'texttitle');" placeholder="<?php $this->lan("Tips6");?>" class="input-text wid400 inpwid100" value="<?php echo $tempData['title'];?>">

                <span style="margin-left:10px;display: none;"><?php $this->lan("tips10");?><b id="texttitle">500</b><?php $this->lan("tips11");?></span>
                
                </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><font color="red">*</font><?php $this->lan("Total_price");?></td>
                <td class="rightgray" style="border:0!important;padding:5px 10px 0!important;"><input type="text" id="money"  name="money" maxlength="11" onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px; text-align:center;width: 150px;" class="input-text" value="<?php echo $tempData['money'];?>"><div class="symbol"><?php echo $symbol;?></div>
                <p class="ChineseText"></p>
                <p style="margin-left: 10px;margin-top: 15px; color:red;" class="pgrs"></p>
                </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><?php $this->lan("Discounted_prices");?></td>
                <td class="rightgray" style="border:0!important;padding:5px 10px 0!important;"><input type="text" id="preferential_price"  name="disc_price" maxlength="11" onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px; text-align:center;width: 150px" class="input-text" value="<?php echo $tempData['disc_price']?:1;?>"><div class="symbol"><?php echo $symbol;?></div>
                <p class="ChineseDiscount">
                    
                </p>
                </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><font color="red">*</font><?php $this->lan("Give_points");?></td>
                <td class="rightgray" style="border:0!important;padding:5px 10px 0!important;"><input type="text" id="s_score"  name="s_score" maxlength="11" onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px; text-align:center;width: 150px" class="input-text" value="<?php echo $tempData['s_score']?:0;?>">
                </td>
            </tr>
            <tr style="display:none">
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px;"><font color="red">*</font><?php $this->lan("Virtual_sales");?></td>
                <td class="rightgray" style="border:0!important;padding:5px 10px 0!important;"><input type="text" id="sales"  name="sales" maxlength="11" onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px; text-align:center;width: 150px" class="input-text" value="<?php echo $tempData['sales']?:0;?>">
                </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><?php $this->lan("Virtual_sales");?></td>
                <td class="rightgray" style="border:0!important;padding:5px 10px 0!important;"><input type="text" id="fictitious"  name="fictitious" maxlength="11" onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px; text-align:center;width: 150px" class="input-text" value="<?php echo $tempData['fictitious']?:0;?>">
                </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><font color="red">*</font><?php $this->lan("thumbnail");?></td>
            <td>
                <div class="layui-upload">
                    <div class="layui-row">
                        <div class="layui-col-md1 width130" style="margin:0!important;">
                            <div class="layui-upload-list height130">
                                <div class="layui-bottom-img">
                                    <span>+</span>
                                    <text><?php $this->lan("Click_upload");?></text>
                                </div>
                                <img class="layui-upload-img" width="120px" src="<?php echo $tempData['thumb']?G_UPLOAD_PATH."/".$tempData['thumb']:'';?>"  id="imgha-img">
                                <input type="hidden" id="imgha-lack" name="thumb" value="<?php echo $tempData['thumb']?:'';?>">
                                <p id="demoText"></p>
                                <button type="button" onclick="uploadimg('上传背景图片','#imgha','image','shopimg')"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><?php $this->lan("Show_pictures");?></td>            
                <td>
                    <div id="lunbo" style="display: flex;flex-wrap: wrap;align-items: flex-start;">
                        <?php if($tempData['imgarrList']) foreach($tempData['imgarrList'] as $k=>$pic): ?>
							<div class='imgbox' id="parent-<?php echo $k; ?>" style="position:relative;margin:0 5px 0 0!important;"
                                <input type="hidden" id="demo<?php echo $k?>-complete" name="imgarrList[]" value="<?php echo $pic; ?>" />
                                <img id="demo<?php echo $k?>-img" src="<?php echo G_UPLOAD_PATH."/".$pic; ?>" onclick="uploadimg('商品展示图片上传','#demo2<?php echo $k?>')" style="width:130px;height:130px;"/>
                                <i class="poishch" onClick="remove_keys(this)" data-parent="parent-<?php echo $k; ?>"></i>
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
                <td height="300" style="text-align: right !important;padding-right: 10px;" align="right"><?php $this->lan("Content_details");?></td>
                <td>
                    <main>
                        <div class="document-editor" style="width:60%;margin:0!important;">
                            <div class="toolbar-container" style="margin:0!important;"></div>
                            <div name="editorValue" id="editor" style="margin:0!important;">
                            <?php echo $tempData['content']?:'';?>
                            </div>
                        </div>
                    </main>
                </td>  
            </tr>
            <input type="hidden"  name="editorValue" value=''>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"><?php $this->lan("Product_attributes");?></td>
                <td width="900" style="display:flex;">
                    <input name="pos" value="1" type="checkbox" style="margin:0!important;" <?php echo $tempData['pos']?"checked":""; ?>/>&nbsp;<?php $this->lan("recommend");?>&nbsp;&nbsp;
                    <input name="renqi" value="1" type="checkbox" style="margin:0!important;" <?php echo $tempData['renqi']?"checked":""; ?>/>&nbsp;<?php $this->lan("popularity");?>&nbsp;&nbsp;
                    
                    <input name="newhot" value="1" type="checkbox" style="margin:0!important;" <?php echo $tempData['newhot']?"checked":""; ?>/>&nbsp;<?php $this->lan("New_products_hot_sale");?>&nbsp;&nbsp;
                </td>          
            </tr>
            <tr>
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px">
                    <?php $this->lan("Product_status");?>
                </td>
                
                    <!--<input name="online" value="1" type="checkbox" <?php echo $tempData['online']?"checked":""; ?>/>&nbsp;上架&nbsp;&nbsp;-->
                    <td id="backend_copyright_auth_pau" style="width:460px;display:flex;">
							<input type="radio" name="online" value="1" style="margin:0!important;" <?php if ( !isset($tempData['online']) ||$tempData['online'] == 1) { ?> checked="checked" <?php } ?> />&nbsp;<?php $this->lan("Available");?>&nbsp;
							<input type="radio" name="online" value="0" style="margin:0!important;" <?php if (isset($tempData['online'])&& $tempData['online'] != 1) { ?> checked="checked" <?php } ?> />&nbsp;<?php $this->lan("Removed");?>
						</td>
                
            </tr>
            <tr height="60px">
                <td align="right" style="text-align: right !important;padding-right: 10px;width:120px"></td>
                <td style="display:flex;"><input type="submit" name="dosubmit" class="button" style="margin:0!important;" onclick="return tovalid();" value="<?php echo $id?$this->lgs['Modify_product']:$this->lgs['Add_product'];?>" /></td>
            </tr>
        </table>
    </form>
</div>
<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
<script type="text/javascript">	
//对应货币
$("#language").change(function(){
        var language = $(this).val();
        if(language==''){
            return false;
        }
        var url = "<?php echo G_MODULE_PATH; ?>/language/getCurrency";
        $.post(url,{language:language},function(res){
            var data = JSON.parse(res);
            $(".symbol").html(data.data.currency_unit);
        })
    });
    $("#language").trigger("change");

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

    function tovalid() {
        var position = $('#editor').html();
        $("input[name='editorValue']").val(position);
    }

    $(document).ready(function(){  
    $(function(){  
    $('input:button').click(function(){  
        $('#xsjx_time').val("");  
    });  
    });  
    });  
    //API JS
    //window.parent.api_off_on_open('open');
    $("#addbanner").on('click',function (){
        var id = diu_Randomize(1000,100000)
        console.log(id)
        //
        var html = `
             <div style="display: inline-block;margin-right: 5px;position: relative;" id="parent-${id}">
                <img  onclick="uploadimg('上传商品图片','#imgHeadPhoto${id}','image','shopimg')" id="imgHeadPhoto${id}-img" width="130px" height="130px">
                         <input type="hidden" name="imgarrList[]" id="imgHeadPhoto${id}-lack" value="">
                <i class="poishch" onClick="remove_keys(this)" data-parent="parent-${id}"></i>
              </div>
        `
		var bb = $("#lunbo>div:nth-last-child(1)")
		bb.before(html)
        // $("#lunbo").append(html)

        setTimeout(()=>{
            uploadimg('上传商品图片','#imgHeadPhoto'+id,'image','shopimg')
        },100)

    })
    function  diu_Randomize(b,e){
        if (!b && b!=0 || !e){ return  "?" ;}
        return  Math.floor( ( Math.random() * e ) + b );
    }
    function getDomain(){
        var url = document.location.toString();
        var arrUrl = url.split("//");
        return arrUrl[0]+"//"+document.domain
    }
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
                 <div style="display: inline-block;margin-right: 5px;position: relative;" id="parent-${id}">
                    <img id="imgHeadPhoto${id}-img" width="130px" height="130px">
                        <input type="hidden" name="imgarrList[]" id="imgHeadPhoto${id}-lack" value="">
                    <i class="poishch" onClick="remove_keys(this)" data-parent="parent-${id}"></i>
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
    
    // 中文大写
    function numberToChinese(num) {
      const chineseNums = [
        "零",
        "一",
        "二",
        "三",
        "四",
        "五",
        "六",
        "七",
        "八",
        "九",
      ];
      const units = ["", "十", "百", "千", "万","十","百","千", "亿","十","百",];
      let result = "";

      const numStr = String(num);
      const len = numStr.length;

      for (let i = 0; i < len; i++) {
        const digit = Number(numStr[i]);
        const unit = len - i - 1;

        result += chineseNums[digit] + units[unit];
      }

      return result;
    }

    // 百亿中文
  function Arabia_To_SimplifiedChinese(Num) {
    for (var i = Num.length - 1; i >= 0; i--) {
      Num = Num.replace(",", ""); //替换Num中的“,”
      Num = Num.replace(" ", ""); //替换Num中的空格
    }
    if (isNaN(Num)) {
      //验证输入的字符是否为数字
      //alert("请检查小写金额是否正确");
      return;
    }
    //字符处理完毕后开始转换，采用前后两部分分别转换
    var part = String(Num).split(".");
    var newchar = "";
    //小数点前进行转化
    for (var i = part[0].length - 1; i >= 0; i--) {
      if (part[0].length > 11) {
        //alert("位数过大，无法计算");
        return "";
      } //若数量超过拾亿单位，提示
      var tmpnewchar = "";
      var perchar = part[0].charAt(i);

      switch (perchar) {
        case "0":
          tmpnewchar = "零" + tmpnewchar;
          break;
        case "1":
          tmpnewchar = "一" + tmpnewchar;
          break;
        case "2":
          tmpnewchar = "二" + tmpnewchar;
          break;
        case "3":
          tmpnewchar = "三" + tmpnewchar;
          break;
        case "4":
          tmpnewchar = "四" + tmpnewchar;
          break;
        case "5":
          tmpnewchar = "五" + tmpnewchar;
          break;
        case "6":
          tmpnewchar = "六" + tmpnewchar;
          break;
        case "7":
          tmpnewchar = "七" + tmpnewchar;
          break;
        case "8":
          tmpnewchar = "八" + tmpnewchar;
          break;
        case "9":
          tmpnewchar = "九" + tmpnewchar;
          break;
      }

      switch (part[0].length - i - 1) {
        case 0:
          tmpnewchar = tmpnewchar;
          break;
        case 1:
          if (perchar != 0) tmpnewchar = tmpnewchar + "十";
          break;
        case 2:
          if (perchar != 0) tmpnewchar = tmpnewchar + "百";
          break;
        case 3:
          if (perchar != 0) tmpnewchar = tmpnewchar + "千";
          break;
        case 4:
          tmpnewchar = tmpnewchar + "万";
          break;
        case 5:
          if (perchar != 0) tmpnewchar = tmpnewchar + "十";
          break;
        case 6:
          if (perchar != 0) tmpnewchar = tmpnewchar + "百";
          break;
        case 7:
          if (perchar != 0) tmpnewchar = tmpnewchar + "千";
          break;
        case 8:
          tmpnewchar = tmpnewchar + "亿";
          break;
        case 9:
        if (perchar != 0) tmpnewchar = tmpnewchar + "十";
          break;
        case 10:
          tmpnewchar = tmpnewchar + "百";
          break;
      }
      newchar = tmpnewchar + newchar;
    }
    //替换所有无用汉字，直到没有此类无用的数字为止
    while (
      newchar.search("零零") != -1 ||
      newchar.search("零亿") != -1 ||
      newchar.search("亿万") != -1 ||
      newchar.search("零万") != -1
    ) {
      newchar = newchar.replace("零亿", "亿");
      newchar = newchar.replace("亿万", "亿");
      newchar = newchar.replace("零万", "万");
      newchar = newchar.replace("零零", "零");
    }
    //替换以“一十”开头的，为“十”
    if (newchar.indexOf("一十") == 0) {
      newchar = newchar.substr(1);
    }
    //替换以“零”结尾的，为“”
    if (newchar.lastIndexOf("零") == newchar.length - 1) {
      newchar = newchar.substr(0, newchar.length - 1);
    }
    return newchar;
  }

    $("#money").on("change",function(){
        var moneyVal = $("#money").val();
        // Arabia_To_SimplifiedChinese(moneyVal)
        if(moneyVal===""){
             $(".ChineseText").val("")
        }else{
           $(".ChineseText").text(Arabia_To_SimplifiedChinese(moneyVal)) 
           groupnum()
        }
    })
    $("#yunjiage").on("change",function(){
        groupnum()
    })

    function groupnum(){
        //计算拼购人数
        var moneyVal = $("#money").val();
        var yunjiage = $("input[name=yunjiage]").val();
        console.log(moneyVal,yunjiage)
        if(moneyVal && yunjiage){
            var num =Math.ceil(moneyVal/yunjiage);
            console.log(num)
            var str = "";
            for(var i = 2;i<=10;i++){
                if(num%i==0){
                    if(str){
                        str += "、"
                    }
                    str += i+"人团 "
                }
            }
            if(!str){
                str = "当前价格无法进行拼购，请修改！！！";
            }
            $(".pgrs").text("可拼购人数:"+str)
        }else{
            $(".pgrs").text("")
        }
    }
    $("#preferential_price").on("change",function(){
        var moneyVal = $("#preferential_price").val();
        // Arabia_To_SimplifiedChinese(moneyVal)
        if(moneyVal===""){
             $(".ChineseDiscount").val("")
        }else{
           $(".ChineseDiscount").text(Arabia_To_SimplifiedChinese(moneyVal)) 
        }
    })

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 