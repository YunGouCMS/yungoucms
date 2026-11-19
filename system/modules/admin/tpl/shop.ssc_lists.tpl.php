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

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<style>

body{ background-color:#fff}

tr{ text-align:center}

</style>

</head>

<body>

<div class="header lr10">
    <?php echo $this->headerment();?>

</div>

<div class="bk10"></div>

<div class="header-data lr10">

    <b><?php $this->lan("Tips");?></b> 
    <!--<?php $this->lan("Tips1");?>-->
     <div data-title="<?php $this->lan("Tips1");?>" style="display:inline-block;width:200px" class="tip">
                               <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
    </div>

    <br/>

    
  <b><?php $this->lan("Tips");?></b> 
  <!--<?php $this->lan("Tips2");?>-->
  <div data-title="<?php $this->lan("Tips2");?>" style="display:inline-block;width:200px" class="tip">
                               <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
    </div>

</div>

<div class="bk10"></div>

<div class="header-data lr10">

<form action="#" method="post" class="posttimeform">

 <?php $this->lan("Add_time");?>&nbsp;&nbsp;
 <div class="posttime">
	<input name="posttime1" type="text" id="posttime1" class="input-text " placeholder="<?php $this->lan("tips3");?>" readonly="readonly" /> -  
    <input name="posttime2" type="text" id="posttime2" class="input-text " placeholder="<?php $this->lan("tips4");?>" readonly="readonly" />
</div>
<script type="text/javascript">

        date = new Date();

        Calendar.setup({

                    inputField     :    "posttime1",

                    ifFormat       :    "%Y-%m-%d %H:%M:%S",

                    showsTime      :    true,

                    timeFormat     :    "24"

        });

        Calendar.setup({

                    inputField     :    "posttime2",

                    ifFormat       :    "%Y-%m-%d %H:%M:%S",

                    showsTime      :    true,

                    timeFormat     :    "24"

        });

                

</script>

<script type="text/javascript"> 
function r_submit(){
    $.get('<?php echo G_ADMIN_PATH; ?>/content/r_submit',function(res){
        window.location.href="<?php echo G_ADMIN_PATH; ?>/content/sscgoods_list"
    })
}
function open_submit(){
    $.get('<?php echo G_ADMIN_PATH; ?>/content/open_submit',function(res){
       if(res ==1){
       	alert('揭晓成功');
       }else if(res == 2){
       	alert('没有未揭晓商品');
       }else{
       		alert('揭晓失败');
       }
    })	
}

</script>
  
<br><br>
<select name="sotype">

<option value="title"><?php $this->lan("Product_title");?></option>

<option value="id"><?php $this->lan("Product_ID");?></option>

<option value="cateid"><?php $this->lan("Column_ID");?></option>

<option value="catename"><?php $this->lan("Column_name");?></option>

<option value="brandid"><?php $this->lan("Brand_ID");?></option>

<option value="brandname"><?php $this->lan("Brand_name");?></option>

</select>

<input type="text" name="sosotext" placeholder="<?php $this->lan("Search_content");?>" class="input-text wid100"/>

<input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search");?>">

<input class="button" type="button"  value="<?php $this->lan("reset");?>" onclick="r_submit()">
<!--<?php $this->lan("Tips5");?>-->
<div data-title="<?php $this->lan("Tips5");?>" style="display:inline-block;width:200px" class="tip">
                               <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
    </div>
<input class="button" type="button"  value="<?php $this->lan("One_click_prize");?>" onclick="open_submit()" style="float:right;">
</form>

</div>

<div class="bk10"></div>

<form action="#" method="post" name="myform">

<div class="table-list lr10">

    <?php if($this->segment(4)=='money' || $this->segment(4)=='moneyasc'): ?>

        <table width="100%" cellspacing="0">

        <thead>

                <tr>

                    <th width="5%"><?php $this->lan("sort");?></th>

                    <th width="5%"><?php $this->lan("id");?></th>                          

                    <th width="25%"><?php $this->lan("Product_title");?></th>  

                    <th width="8%"><?php $this->lan("Belonging_column");?></th>             

                    <th width="10%"><?php $this->lan("participated_total");?></th>

                    <th width="5%"><?php $this->lan("Unit_price");?></th>

                    <th width="10%"><?php $this->lan("Issue");?>/<?php $this->lan("maximum");?></th>

                    <th width="10%"><?php $this->lan("Commodity_price");?></th>

                    <th width="15%"><?php $this->lan("operation");?></th>

                </tr>

        </thead>

        <tbody>             

            <?php foreach($shoplist as $v) { ?>

            <tr>

              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['title'],30);?></span>

                <?php if($v['xsjx_time'])echo "<font color='red'>限时</font>"; ?>

                </td>

                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>

                <td><?php echo $v['money']; ?></td>

                <td class="action">

                <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>

                <span class='span_fenge lr5'>|</span>

                </td>

            </tr>

            <?php } ?>

        </tbody>

     </table>

    <?php endif; ?>

    

    

    <?php if($this->segment(4)=='maxqishu'): ?>

        <table width="100%" cellspacing="0">

        <thead>

                <tr>

                    <th width="5%"><?php $this->lan("sort");?></th>

                    <th width="5%"><?php $this->lan("id");?></th>                          

                    <th width="25%"><?php $this->lan("Product_title");?></th>  

                    <th width="8%"><?php $this->lan("Belonging_column");?></th>             

                    <th width="10%"><?php $this->lan("participated_total");?></th>

                    <th width="5%"><?php $this->lan("Unit_price");?></th>

                    <th width="10%"><?php $this->lan("Number_periods_del");?></th>         

                    <th width="25%"><?php $this->lan("operation");?></th>

                </tr>

        </thead>

        <tbody>             

            <?php foreach($shoplist as $v) { ?>

            <tr>

              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['title'],30);?></span>

                <?php if($v['xsjx_time'])echo "<font color='red'>限时</font>"; ?>

                </td>

                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>          

                <td class="action">         

                [<a href="javascript:void(0)" gonemoney="<?php echo $v['yunjiage'];?>" gmoney="<?php echo $v['money'];?>" gqishu="<?php echo $v['maxqishu'];?>" gid="<?php echo $v['id']; ?>" gtitle="<?php echo _strcut($v['title'],30);?>" class="pay_window_show"><?php $this->lan("Reset_product");?></a>]

                </td>

            </tr>

            <?php } ?>

        </tbody>

     </table>

    <?php endif; ?>

    

    

    

    

    <?php if($this->segment(4)=='xianshi'): ?>

    <table width="100%" cellspacing="0">

        <thead>

                <tr>

                    <th width="5%"><?php $this->lan("sort");?></th>

                    <th width="5%"><?php $this->lan("id");?></th>        

                    <th width="25%"><?php $this->lan("Product_title");?></th>            

                    <th width="10%"><?php $this->lan("participated_total");?></th>

                    <th width="5%"><?php $this->lan("Unit_price");?></th>

                    <th width="10%"><?php $this->lan("Number_periods_del");?></th>          

                    <th width="20%"><?php $this->lan("Limited_time");?></th>

                    <th width="15%"><?php $this->lan("operation");?></th>

                </tr>

        </thead>

        <tbody>             

            <?php foreach($shoplist as $v) { ?>

            <tr>

              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['title'],30);?></span></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>

                <td><?php echo ($v['xsjx_time']!=0) ? date("Y-m-d H",$v['xsjx_time']) : '否';?></td>

                <td class="action">

                <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>           

                </td>

            </tr>

            <?php } ?>

        </tbody>

     </table>

    <?php endif; ?>

    

    <?php if($this->segment(4)!='xianshi' && $this->segment(4)!='maxqishu' && $this->segment(4)!='moneyasc' && $this->segment(4)!='money'): ?>

        <table width="100%" cellspacing="0">

        <thead>
		 <input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDel button" style="margin-bottom: 10px;">
                <tr>
					<th width="30px" align="center"><input id="checkAll" type="checkbox"></th>
                    <th width="5%"><?php $this->lan("sort");?></th>

                    <th width="5%"><?php $this->lan("id");?></th>        

                    <th width="25%"><?php $this->lan("Product_title");?></th>    

                    <th width="8%"><?php $this->lan("Belonging_column");?></th>             

                    <th width="10%"><?php $this->lan("participated_total");?></th>

                    <th width="5%"><?php $this->lan("Unit_price");?></th>

                    <th width="10%"><?php $this->lan("Number_periods_del");?></th>

                    <th width="10%"><?php $this->lan("popularity_good");?></th>

                    <th width="15%"><?php $this->lan("operation");?></th>

                </tr>

        </thead>

        <tbody>             

            <?php foreach($shoplist as $v) { ?>

            <tr>
				<td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td align="left" style=" padding-left:20px"><span style=""><?php echo _strcut($v['title'],50);?></span></td>

                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>

                <td><?php

                    if($v['renqi']==1){

                        echo '<font color="#ff0000">人气</font>';

                    }else{

                        echo "<a href='".G_MODULE_PATH."/content/goods_set/renqi/".$v['id']."'>设为人气</a>";   

                    }

                    ?>

                </td>

                <td class="action">

				<a href="<?php echo G_ADMIN_PATH; ?>/content/goods_set_money/<?php echo $v['id'];?>"><?php $this->lan("Reset_price");?></a> 
				<span class='span_fenge lr5'>|</span>
				<a href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>
				<span class='span_fenge lr5'>|</span>
				<a href="<?php echo G_ADMIN_PATH; ?>/qishu/qishu_list/<?php echo $v['id'];?>"><?php $this->lan("Previous_period");?></a>
				<span class='span_fenge lr5'>|</span>
				<a href="<?php echo G_ADMIN_PATH; ?>/content/goods_del/<?php echo $v['id'];?>"><?php $this->lan("delete");?></a>
			
                </td>

            </tr>

            <?php } ?>

        </tbody>

     </table>     

    <?php endif; ?> 

    </form>

    

   <div class="btn_paixu">

    <div style="width:80px; text-align:center;">

          <input type="button" class="button" value="  <?php $this->lan("sort");?>"

        onclick="myform.action='<?php echo G_MODULE_PATH; ?>/content/goods_listorder/dosubmit';myform.submit();"/>

    </div>

  </div>

        <div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>

</div>

<?php if($this->segment(4)=='maxqishu'): ?>

<!--期数修改弹出框-->

<style>

#paywindow{position:absolute;z-index:999; display:none}

#paywindow_b{width:372px;height:360px;background:#2a8aba; filter:alpha(opacity=60);opacity: 0.6;position:absolute;left:0px;top:0px; display:block}

#paywindow_c{width:360px;height:338px;background:#fff;display:block;position:absolute;left:6px;top:6px;}

.p_win_title{ line-height:40px;height:40px;background:#f8f8f8;}

.p_win_title b{float:left}

.p_win_title a{float:right;padding:0px 10px;color:#e42d6a}

.p_win_content h1{font-size:25px;font-weight:bold;}

.p_win_but,.p_win_mes,.p_win_ctitle,.p_win_text{ margin:10px 20px;}

.p_win_mes{border-bottom:1px solid #eee;line-height:35px;}

.p_win_mes span{margin-left:10px;}

.p_win_ctitle{overflow:hidden;}

.p_win_x_b{float:left; width:73px;height:68px;background-repeat:no-repeat;}

.p_win_x_t{ font-size:18px; font-weight:bold;font-family: "Helvetica Neue",\5FAE\8F6F\96C5\9ED1,Tohoma;color:#f00; text-align:center}

.p_win_but{ height:40px; line-height:40px;}

.p_win_but a{ padding:8px 15px; background:#e42d6a; color:#fff;border:1px solid #f50; margin:0px 15px;font-family: "Helvetica Neue",\5FAE\8F6F\96C5\9ED1,Tohoma; font-size:15px; }

.p_win_but a:hover{ background:#f50}

.p_win_text a{ font-size:13px; color:#e42d6a}

.pay_window_quit:hover{ color:#f00}

</style>

<div id="paywindow">

    <div id="paywindow_b"></div>

    <div id="paywindow_c">

        <div class="p_win_title"><a href="javascript:void();" class="pay_window_quit">[关闭]</a><b>：：：重设期数</b></div>

        <div class="p_win_content">         

            <div class="p_win_mes">

                 <b>标题:　　　</b><span id="max_title">...</span>           

            </div>

            <div class="p_win_mes">

                 <b>原最大期数:</b><span id="max_qishu">0</span>　期

            </div>

            <div class="p_win_mes">

                 <b>新最大期数:</b><input type="text" id="max_new_qishu" class="input-text" style="width:50px;margin-left:10px;">需要大于原最大期数

            </div>

            <div class="p_win_mes">

                 <b>新商品售价:</b><input type="text" id="max_one_money" class="input-text" style="width:50px;margin-left:10px;">商品每一次购买的价格

            </div>

            <div class="p_win_mes">

                 <b>新商品总价:</b><input type="text" id="max_new_money" class="input-text" style="width:50px;margin-left:10px;">商品的总价格      

            </div>

            <div class="p_win_mes">             

                    <input type="button" value=" 更新并新建一期 " class="button" id="max_button" onclick="set_shop_qishu(this)">              

            </div>  

        </div>

    </div>

</div>

<script>

$(function(){

    var width = ($(window).width()-372)/2;

    var height = ($(window).height()-360)/2;

    $("#paywindow").css("left",width);

    $("#paywindow").css("top",height);

        

    $(".pay_window_quit").click(function(){

        $("#paywindow").hide();                              

    });

    

        

    $(".pay_window_show").click(function(){

        var gid    = $(this).attr("gid");

        var gtitle = $(this).attr("gtitle");

        var gqishu = $(this).attr("gqishu");

        var gmoney = $(this).attr("gmoney");

        var gonemoney = $(this).attr("gonemoney");

        

        $("#max_one_money").val(gonemoney);

        $("#max_new_money").val(gmoney);

        

        $("#max_new_qishu").val(parseInt(gqishu)+100);

        $("#max_qishu").text(gqishu);

        $("#max_title").text(gtitle);

        $("#max_button").attr("onclick","set_shop_qishu("+gid+")");     

        $("#paywindow").show(); 

    

    });

    

});

function set_shop_qishu(T){

    

    var yqishu = parseInt($("#max_qishu").text());

    var tqishu = parseInt($("#max_new_qishu").val());

    var money = parseInt($("#max_new_money").val());

    var onemoney = parseInt($("#max_one_money").val());

    

    if(!money || !onemoney || (money < onemoney)){

        window.parent.message("商品价格填写不正确!",8,2);

        return;

    }

    if(tqishu <= yqishu){   

        window.parent.message("新期数不能小于原来的商品期数",8,2);

        return;

    }

    if(!tqishu){    

        window.parent.message("新期数不能为空",8,2);

        return;

    }

    

    $.post("<?php echo G_MODULE_PATH; ?>/content/goods_max_qishu/dosubmit/",{"gid":T,"qishu":tqishu,"money":money,"onemoney":onemoney},function(datas){

        var data = jQuery.parseJSON(datas);     

        if(data.err == '-1'){       

            window.parent.message(data.meg,8,2);        

            return;

        }else{          

            window.parent.message(data.meg,8,2);

            window.parent.btn_iframef5();

            return;

        }       

        

    });

}

</script>

<!--期数修改弹出框-->

<?php endif; ?>
  <script type="text/javascript">
   //商品批量删除
   $("#checkAll").on('change', function () {
    if ($(this).is(":checked")) { // 全选
        $(".checkOne").prop("checked",true);
    } else { // 反选
        $(".checkOne").prop("checked",false);
    }
	});
    
	$('.batchDel').click(function(){
         //获取所有选中的CheckBox的id
     var  ids="";
     $(".checkOne").each(function(i) {
       if($(this).prop('checked')){
       var id= $(this).attr("data-id");
		ids+=+id+',';	
       }
	// ajax批量删除
    });
    // console.log(ids);
      $.ajax({
        	type: 'POST',
        	url: '/admin/content/sscshop_del',
        	data: {ids: ids},
        	dataType: 'json',
        	success: function (data) {
           			 if (data == 0) {
                         layer.msg("删除成功!");
                         setTimeout(function(){
                             location.reload(false);
                         },1500);
                         // $(window).attr('location','http://bata.yungoucms.cn/admin/content/goods_list');
                     }else {
                         layer.msg("删除失败!");
                     
                    }
        		}
    		});
      	ids="";
  	});
  </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 