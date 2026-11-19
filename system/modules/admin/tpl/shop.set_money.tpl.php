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
	.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
	.color_window_td a{ float:left; margin:0px 10px;}
	.rightgray {display: flex;align-items: center;}
	.rightgray div {background-color: #f5f5f5;border: 1px solid #B4B4B4;border-left-width: 0px;height: 27px;padding: 2px 15px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;line-height: 28px;}
	.rightgray input {margin: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;width: 85px;min-width: 80px;}
	.ChineseDiscount{
        margin-left: 10px;
        margin-top: 15px;
    }
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>    
</div>
<div class="bk10"></div>
<div class="header-data lr10">
	<b><?php $this->lan("Tips");?> </b> <font color="red"> <?php $this->lan("Tips1");?></font>
	<br/>
	<b><?php $this->lan("Tips");?></b> <font color="red"><?php $this->lan("Tips2");?></font>
</div>

<div class="bk10"></div>
<div class="table_form lr10">
<form method="post" action="#" id="form_post">
	<table width="100%"  cellspacing="0" cellpadding="0">
		<tr>
			<td align="right" style="width:200px;text-align:right!important;"><?php $this->lan("Product_title");?>&nbsp;</td>
			<td style="text-align:left!important;">
            	<a target="_blank" href="<?php echo WEB_PATH;?>/goods/<?php echo $shopinfo['id'];?>"><b><?php $this->lan("No");?>(<font color="red"><?php echo $shopinfo['qishu']; ?></font>)<?php $this->lan("phase");?> <?php echo $shopinfo['title']; ?></b></a>
            </td>			
		</tr>
		<tr>
			<td align="right" style="width:200px;text-align:right!important;"><?php $this->lan("Single_selling_price");?>&nbsp;</td>
			<td style="text-align:left!important;"><b style="color:red"><?php echo $shopinfo['yunjiage']; ?></b> <b><?php echo $symbol;?></b></td>			
		</tr>
		<tr>
			<td align="right" style="width:200px;text-align:right!important;"><?php $this->lan("Total_selling_price");?>&nbsp;</td>
			<td style="text-align:left!important;"><b style="color:red"><?php echo $shopinfo['money']; ?></b> <b><?php echo $symbol;?></b></td>			
		</tr>
		<tr>
			<td align="right" style="width:200px;text-align:right!important;"><?php $this->lan("Number_Participants");?>&nbsp;</td>
			<td style="text-align:left!important;"><b style="color:red"><?php echo $shopinfo['canyurenshu']; ?></b> <b>人</b></td>			
		</tr>
		<tr>
			<td align="right" style="width:200px;text-align:right!important;"><?php $this->lan("Total_number_personnel");?>&nbsp;</td>
			<td style="text-align:left!important;"><b style="color:red"><?php echo $shopinfo['zongrenshu']; ?></b> <b>人</b> <?php $this->lan("Round_upwards");?>(<?php echo $shopinfo['money']; ?> / <?php echo $shopinfo['yunjiage']; ?>) = <?php echo $shopinfo['zongrenshu']; ?></td>			
		</tr>
		
		<tr>
			<td align="right" style="width:200px;color:red;text-align:right!important;"><?php $this->lan("New_single_price");?>&nbsp;</td>
			<td class="rightgray" style="border:0!important;padding:0 10px!important;"><input type="text" id="yunjiage"  name="yunjiage" onKeyUp="value=value.replace(/\D/g,'')" style="height: 40px !important;" value="<?php echo $shopinfo['yunjiage']; ?>" class="input-text"><div><?php echo $symbol;?></div></td>			
		</tr>
		<tr>
			<td align="right" style="width:200px;color:red;text-align:right!important;"><?php $this->lan("New_total_price");?>&nbsp;</td>
			<td class="rightgray" style="border:0!important;padding:0 10px!important;"><input type="text" name="money" id="money" onKeyUp="value=value.replace(/\D/g,'')" maxlength="11" value="<?php echo intval($shopinfo['money']); ?>" style="height: 40px !important;" class="input-text"><div><?php echo $symbol;?></div>
			<p class="ChineseDiscount"></p>
      <!-- <p style="margin-left: 10px;margin-top: 15px;">拼购人数：2、3、4、5、6、7、8、9、10人</p> -->
      <p style="margin-left: 10px;margin-top: 15px; color:red; <?echo $is_group==2?'display:none;':'';?>" class="pgrs"></p>
		</td>			
		</tr>
       
      

        <tr height="60px">
			<td align="right" style="width:200px"></td>
			<td style="display:flex;">
                <input type="button" name="confirm" id="confirm" class="button" style="margin:0!important;" value="<?php $this->lan("Confirm_changes");?>" />
              </td>
		</tr>
	</table>
</form>
</div>
<!--JS-->
<script type="text/javascript">
$("#confirm").click(function(){
	var canyurenshu = <?php echo $shopinfo['canyurenshu']; ?>;
	var zongrenshu = <?php echo $shopinfo['zongrenshu']; ?>;
	var y_money = <?php echo $shopinfo['money']; ?>;
	
	var money = parseInt($("#money").val());	
	var yunjiage = parseInt($("#yunjiage").val());

	
	if(!money || !yunjiage){
		window.parent.message("商品价格输入不正确!",8,2);
		return false;
	}
	if(yunjiage > money){
		window.parent.message("单次价格不能大于总价格",8,2);
		return false;
	}
	if(canyurenshu>0){
      var confirmindex = layer.confirm("参与人数不为0,将会清空会员购买记录，并退还用户金额，用户在个人资金记录中可以看到此笔退款流水，是否继续", {
          btn: ['确认','取消'] //按钮
      }, function(){
        layer.close(confirmindex)
        $("#form_post").submit();
      }, function(){
          layer.close(confirmindex)
      });
	}else{
    $("#form_post").submit();
  }
	return false;
});

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
             $(".ChineseDiscount").val("")
             $(".pgrs").text("") 
        }else{
           $(".ChineseDiscount").text(Arabia_To_SimplifiedChinese(moneyVal))
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
</script>
<!--JS-->

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 