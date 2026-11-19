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
</style>
</head>
<body>
<script>
$(function(){
    $("#category").change(function(){ 
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
    });
}); 

function CheckForm(){
    // $('#content').val(UE.getEditor('editor').getContent());
    // $('#content').val(CKEDITOR.replace( 'editor').getContent());
    var money = parseInt($("#money").val());
    // if(money >= 100000){
    //     window.parent.message("价格大于10万，商品添加会很慢,请耐心等待，不要关闭窗口!",1,5);
    // }	
    return true;
}

function show_jiage(){
    $('#yunrenci').show();
    $('#yunjiage').hide();
}

function hide_jiage(){
    $('#yunjiage').show();
    $('#yunrenci').hide();
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
                div.innerHTML='<img src="'+this.result+'" style="width:130px;height:130px;"/>';
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
<div>
    <form action="#" method="post">
        <table width="100%"  cellspacing="0" cellpadding="0">
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font> <span style="display: inline-block;margin-right: 5px;"><?php $this->lan("product_link");?></span> </td>
                <td>
                    <textarea name="itemid" placeholder=" <?php $this->lan("Goods_list");?>"  rows="10" cols="100" style="width: 980px;height:50px;margin-top: 5px;"><?php echo $itemid; ?></textarea>
                </td>
            </tr> 
            <tr>
                <td align="right" style="width:120px"></td>
                <td><input type="submit" name="dosubmit" class="button" style="margin-bottom: 5px;"  value="<?php $this->lan("ID");?>" /></td>
            </tr> 
        </table>
    </form>
</div>
<div class="table_form lr10">
    <form method="post" action="<?php echo  WEB_PATH;?>/<?echo _cfg('admindir')?>/content/goods_add" onSubmit="return CheckForm()" enctype="multipart/form-data">
        <table width="100%"  cellspacing="0" cellpadding="0">
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("Belonging_language");?></td>
                <td>
                    <select id="language" name="language">
                        <!-- ≡ 请选择语言 ≡ -->
                        <option value="Chinese">简体中文（中国大陆）</option>
                        <?php foreach($lg as $key => $l){ ?>
                            <option value="<?php echo $l['lg_value']; ?>"><?php echo $l['lg_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("Belonging_category");?></td>
                <td>
                <select id="category" name="cateid">           
                    <?php echo $categoryshtml; ?>                
                    </select> 
                </td>
            </tr>
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("Belonging_brand");?></td>
                <td>
                    <select id="brand" name="brand">
                        <option value="0">≡ <?php $this->lan("Choose_brand");?> ≡</option>
                    </select>
                </td>
            </tr>  
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("Product_title");?></td>
                <td>
                <input type="text" id="title" name="title"
                        value="<?php echo $info['title']; ?>"
                        style="width:60%" onKeyUp="return gbcount(this,100,'texttitle');" placeholder="<?php $this->lan("Tips4");?>" class="input-text wid400 inpwid100">
                <span style="margin-left:10px;display: none;">还能输入<b id="texttitle">100</b>个字符</span>
                
                </td>
            </tr>

            <tr style="background-color:#FFC">
                <td style="width:120px"></td>
                <td>
                    <b><?php $this->lan("Tips");?></b> <font color="red"><?php $this->lan("Tips1");?></font><br />
                    <b><?php $this->lan("Tips");?></b> <?php $this->lan("Tips2");?>
                </td>
            </tr>
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("Total_price");?></td>
                <td class="rightgray"><input type="text" id="money"  name="money"
                    value="<?php echo $info['price']; ?>" 
                    onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px; text-align:center" class="input-text"><div><?php $this->lan("unit");?></div></td>
            </tr>
            <tr id="yunjiage"> 
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("Single_price");?></td>
                <td class="rightgray"><input type="text" name="yunjiage" onKeyUp="value=value.replace(/\D/g,'')" style="padding-left:0px;text-align:center" class="input-text" value="100"><div><?php $this->lan("unit");?></div></td>
            </tr>
            <tr id="yunrenci" style='display:none;'> 
                <td align="right" style="width:120px"><font color="red">*</font>参与人数</td>
                <td><input type="text" name="yunrenci" onKeyUp="value=value.replace(/\D/g,'')" style="width:65px;padding-left:0px;text-align:center" class="input-text" value="1">人</td>
            </tr>
            <tr>      
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("maximum");?></td>     
                <td class="rightgray"><input type="text" name="maxqishu" value="9999" class="input-text" style="padding-left:0px; text-align:center" onKeyUp="value=value.replace(/\D/g,'')"><div><?php $this->lan("phase");?></div>&nbsp;&nbsp;&nbsp;<?php $this->lan("Tips3");?></td>
            </tr>	
            
            <tr>
                <td align="right" style="width:120px"><font color="red">*</font><?php $this->lan("thumbnail");?></td>
            <td>
                <div class="layui-upload">
                    <div class="layui-row">
                        <div class="layui-col-md1 width130">
                            <div class="layui-upload-list height130">
                                <!-- <?php if (!$img): ?>style="opacity: 0;"<?php endif;?> -->
                                <div class="layui-bottom-img" >
                                    <span>+</span>
                                    <text><?php $this->lan("Click_upload");?></text>
                                </div>
                                <!-- <?php echo G_UPLOAD_PATH.'/'.$info['images'][0]; ?> -->
                                <img class="layui-upload-img" width="120px" src=""  id="imgha-img">
                                <input type="hidden" id="imgha-lack" name="thumb" value="">
                                <!-- <?php echo $info['images'][0]; ?> -->
                                <p id="demoText"></p>
                                <button type="button" onclick="uploadimg('上传背景图片','#imgha','image','shopimg')"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            </tr>
            <tr>
                <!-- 修改 -->
                <td align="right" style="width:120px"><?php $this->lan("Show_pictures");?></td>            
                <td>
                    <div class="layui-upload" style="width:60%">
                        <!-- <button type="button" class="layui-btn layui-btn-sm" onclick="uploadFiles('商品展示图片上传','imgarrList','#demo2')">
                            上传 <i class="layui-icon">&#xe681;</i>
                        </button> -->
                        <!-- <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                            <div class="layui-upload-list" id="demo2" style="display:flex" >
                                <div type="button" class="layui-btn layui-btn-sm" style="width:130px;height:130px;border:1px dashed #B4B4B4;background:#fff;marign-left:5px" onclick="uploadFiles('商品展示图片上传','imgarrList','#demo2')">
                                    <div class="layui-bottom-img">
										<span>+</span>
										<text style="font-size:10px">添加图片</text>
									</div>
                                </div>
                                <ul id="uppicarr" class="upload-img-list" >
                                    <?php if($info['images']) foreach($info['images'] as $k=>$pic): ?>
                                        <input type="hidden" id="demo<?php echo $k?>-complete" name="imgarrlist[]" value="<?php echo $pic; ?>" />
                                        <div class='imgbox'><img id="demo<?php echo $k?>-img" src="<?php echo G_UPLOAD_PATH."/".$pic; ?>" onclick="uploadimg('商品展示图片上传','#demo2<?php echo $k?>')" style="width:130px;height:130px;"/></div>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div id="imginput"></div>
                        </blockquote> -->
                        <div id="lunbo" style="display: flex;flex-wrap: wrap;align-items: flex-start;">
                        <!-- <?php foreach ($photo as $k=>$v){ ?>
                          <div style="display: inline-block;margin-right: 5px;">
                              <img  onclick="uploadimg('下载页轮播图','#imgHeadPhoto<?php echo $k;?>','image','banner')" id="imgHeadPhoto<?php echo $k;?>-img" width="200px" src="<?php echo G_UPLOAD_PATH.'/'.$v;?>">
                              <input type="hidden" name="banner[]" id="imgHeadPhoto<?php echo $k;?>-lack" value="<?php echo $v;?>">
                          </div>
                        <?php } ?> -->
						<div class="layui-row" style="display: inline-flex;padding: 0;">
						    <div class="layui-col-md1 width130">
								<div class="layui-upload-list height130">
									<div class="layui-bottom-img">
										<span>+</span>
										<text><?php $this->lan("Add_image");?></text>
									</div>
								    <p id="demoText"></p>
									<button type="button" class="layui-btn" id="addbanner"></button>
								</div>
						    </div>
						</div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="300"  align="right"><?php $this->lan("Content_details");?></td>
                <td>
                    <main>
                        <div class="document-editor" style="width:60%">
                            <div class="toolbar-container" style="margin:0!important;"></div>
                            <div name="editorValue" id="editor" style="margin:0!important;">
                                <?php echo $info['content']; ?>
                            </div>
                        </div>
                    </main>
                </td>  
            </tr>
            <input type="hidden" value="" name="editorValue">
            <tr>
                <td align="right" style="width:120px"><?php $this->lan("Product_attributes");?></td>
                <td width="900">
                    <input name="goods_key[pos]" value="1" type="checkbox" checked/>&nbsp;<?php $this->lan("recommend");?>&nbsp;&nbsp;
                    <input name="goods_key[renqi]" value="1" type="checkbox" checked/>&nbsp;<?php $this->lan("popularity");?>&nbsp;&nbsp;
                </td>          
            </tr>
            <!--tr>
                <td align="right" style="width:120px">限时获得</td>
                <td>
                
                    选择日期
                    <input name="xsjx_time" type="text" id="xsjx_time" class="input-text posttime"  readonly="readonly" />
                    <script type="text/javascript">
                    date = new Date();
                    Calendar.setup({
                        inputField     :    "xsjx_time",
                        ifFormat       :    "%Y-%m-%d",
                        showsTime      :    true,
                        timeFormat     :    "24"
                    });
                    </script>
                    <label><input name="xsjx_time_h" type="radio" value="36000" checked> 上午10点 </label>           
                    <label><input name="xsjx_time_h" type="radio" value="54000"> 下午3点 </label>
                    <label><input name="xsjx_time_h" type="radio" value="79200"> 晚上10点 </label>  
                    <input value="清空时间" onClick="" type="button">    
                    <span class="lr10">&nbsp;</span>	<b>不选择时间则不参与限时获得, 本期揭晓后自动添加的下一期不是限时获得商品！</b>         
                </td>        
            </tr-->
            <tr height="60px">
                <td align="right" style="width:120px"></td>
                <td><input type="submit" name="dosubmit" class="button" onclick="return tovalid();" value="<?php $this->lan("Add_product");?>" /></td>
            </tr>
        </table>
    </form>
</div>
<span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
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
                <img  onclick="uploadimg('上传商品图片','#imgHeadPhoto${id}','image','shopimg')" id="imgHeadPhoto${id}-img" width="130px" height="130px" src="/statics/uploads/admanage/mr.jpg">
                         <input type="hidden" name="imgarrList[]" id="imgHeadPhoto${id}-lack" value="">
                <i style="position: absolute;right:0;color:#3C8DBC;font-size: 20px;cursor: pointer;padding-right: 10px;" onClick="remove_keys(this)" data-parent="parent-${id}">x</i>
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
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 