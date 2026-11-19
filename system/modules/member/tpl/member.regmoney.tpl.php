<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
	.rightgray {display: flex;align-items: center;}
	.rightgray div {background-color: #f5f5f5;border: 1px solid #B4B4B4;border-left-width: 0px;height: 27px;padding: 2px 15px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;line-height: 28px;}
	.rightgray input {margin: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;}
</style>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment();?> </div>
<div class="bk10"></div>
<div class="table_form lr10">
  <form action="#" method="post" id="myform">
    <table width="100%" style="border-width: 1px;">
      <tr>
        <td width="80" style="text-align: right;"><?php $this->lan("Gift_amount");?>&nbsp;</td>
        <td class="rightgray">
			<input type="text" name="money" placeholder="<?php $this->lan("Gift_amount");?>" class="input-text" style="width: 85px;text-align: center;" id="money" value="<?php echo $m['money']; ?>" >
          </input><div><?php echo $symbol;?></div>
		</td>
      </tr>
	  <tr>
        <td width="80"></td>
        <td><input type="hidden" name="submit-1" />
    <input type="button" value="<?php $this->lan("Submit");?> " id="dosubmit" class="button"></td>
      </tr>
    </table>
  </form>
</div>
<!--table-list end-->
<script type="text/javascript">

var error='';

var bool=false;

var id='';

$(document).ready(function(){		

		

	   document.getElementById('dosubmit').onclick=function(){

		   		bool=false;

				var myform=document.getElementById('myform');

				if(!myform.money.value){

					error='金额不能为空！';

					id='money';

					bool=true;

				}

				if(bool){					

					alert(error);

					return false;

				}else{
					$.post('<?php echo WEB_PATH; ?>/member/member/reg_money',{money:myform.money.value,ajax:true},function(data){
						if(data=='1'){							
							alert("设置赠送金额成功！")
						}else if(data=='2'){

							alert("设置赠送金额失败！")

						}

					});					

				}

	   }

				

	

		

});

</script>
</body>
</html>
