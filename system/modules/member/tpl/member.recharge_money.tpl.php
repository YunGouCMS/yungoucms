<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<style>
	.rightgray {display: flex!important;align-items: center;}
	.rightgray div {background-color: #f5f5f5;border: 1px solid #B4B4B4;border-left-width: 0px;height: 28px;padding: 2px 15px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;line-height: 28px;}
	.rightgray input {margin: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;width: 85px;height: 34px;border-color: #B4B4B4;margin-left: 5px;text-align: center;}
	.ks p{
		text-align: left;
		font-size: 28px;
		margin-left: 20px;
	}
	.rule{
		display: flex;
		font-size: 24px;
		padding: 20px 0px;
	}
	.rule div{
        padding-right: 10px;
		display: flex;
	}
	.rule input {
		width: 150px;
	}
</style>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment();?> </div>
<div class="bk10"></div>
<div class="table_form lr10">
  <form action="/member/member/userrecharge" method="post" id="myform">
    <table width="100%">
      <tr>
        <td><?php $this->lan("Recharge_gift");?>&nbsp;
            <input type="radio" name="isOpen"  value="1" <?php if($isOpen){echo 'checked'; } ?> > <?php $this->lan("open");?>
            <input type="radio" name="isOpen" value="0" <?php if(!$isOpen){echo 'checked'; } ?> > <?php $this->lan("close");?>
           </td>
      </tr>
      <tr style="display: flex;">
        <td class="rightgray">
        	<?php $this->lan("Recharge_reached");?>&nbsp;<input type="text" name="r_money" style="height: 28px;" class="input-text" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');this.value=this.value.replace('.','');" value="<?php echo $r_money; ?>" required /><div><?php echo $symbol;?></div>
        </td>
		<td class="rightgray">
			<?php $this->lan("Gift_amount");?>&nbsp;<input type="text" name="money" style="height: 28px;" class="input-text" onKeyUp="this.value=this.value.replace(/[^\.\d]/g,'');this.value=this.value.replace('.','');" value="<?php echo $money; ?>" required /><div><?php echo $symbol;?></div><span style="color:red;margin-left: 15px;"><?php $this->lan("Tips");?></span>
		</td>
      </tr>
      <tr>
        <td width="100%" style="text-align: left;"><?php $this->lan("Tips1");?>&nbsp;</td>
      </tr>
       <tr>
		<td>
			<div style="display: inline-flex;">
				<div id="inpl" onselectstart="return false;" onclick="delElement();"><i></i></div>
				<div id="inputsr" onselectstart="return false;" onclick="addElement();"><i></i></div>
			</div>
		</td>
		</tr>
		<tr id="Receive" style="display: flex;flex-wrap: wrap;">
		<?php foreach ($dayinfo as $key => $value):?>
        <td class="rightgray">
                    <?php $this->lan("The");?><?php echo $key;?><?php $this->lan("day");?> <input type='text' name='d_money["<?php echo $key;?>"]'  class="layui-input" value='<?php echo $value;?>' required/><div><?php echo $symbol;?></div>
        </td>
		<?php endforeach; ?>
      </tr>                               
	  <tr>
        <td>
        	<input type="hidden" id="days" value="<?php echo $allday; ?>" />
    		<input type="submit" value=" <?php $this->lan("Submit");?> "  class="button" style="margin-left: 42px;">
    	</td>
      </tr>
    </table>
  </form>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
  <form action="/member/member/userrecharge" method="post" id="myform">
	  <h5><?php $this->lan("Recharge_discount_rules");?></h5>
	<div class="ks">
		<div class="rule">
			<input type="radio" name="discount[status]" id="status_1" value="1" <?php echo $discount['status']==1?"checked":"";?>><label for="status_1"><?php $this->lan("open");?></label>
			<input type="radio" name="discount[status]" id="status_2" value="2" <?php echo $discount['status']==2?"checked":"";?>><label for="status_2"><?php $this->lan("close");?></label>
		</div>
		<p><?php $this->lan("New_user");?></p>
		<div class="rule">
			<div>
				<p><?php $this->lan("First_recharge_discount");?></p>
				<input type="text" name="discount[first]" value="<?php echo $discount['first']?>">
			</div>
			<div>
				<p><?php $this->lan("Time_limit");?></p>
				<input type="text" name="discount[time]" value="<?php echo $discount['time'];?>">
			</div>
			<div>
				<p><?php $this->lan("Same_day_discount");?></p>
				<input type="text" name="discount[today_1]" value="<?php echo $discount['today_1'];?>"> 
			</div>
		</div>
		<p><?php $this->lan("New_user_overtime");?></p>
		<div class="rule">
			<div>
				<p><?php $this->lan("Overtime_recharge_discount");?></p>
				<input type="text" name="discount[timeout]" value="<?php echo $discount['timeout'];?>">
			</div>
			<div>
				<p><?php $this->lan("Same_day_discount");?></p>
				<input type="text" name="discount[today_2]" value="<?php echo $discount['today_2'];?>">
			</div>
		</div>
		<p><?php $this->lan("Continuous_recharge");?></p>
		<div class="rule">
			<div class="">
				<p><?php $this->lan("Day_one");?></p>
				<input type="text" name="discount[add][]" value="<?php echo $discount['add'][0];?>">
			</div>
			<div class="">
				<p><?php $this->lan("Day_two");?></p>
				<input type="text" name="discount[add][]" value="<?php echo $discount['add'][1];?>">
			</div>
			<div class="">
				<p><?php $this->lan("Day_three");?></p>
				<input type="text" name="discount[add][]" value="<?php echo $discount['add'][2];?>">
			</div>
			<div class="">
				<p><?php $this->lan("Day_four");?></p>
				<input type="text" name="discount[add][]" value="<?php echo $discount['add'][3];?>">
			</div>
			<div class="">
				<p><?php $this->lan("Day_five");?></p>
				<input type="text" name="discount[add][]" value="<?php echo $discount['add'][4]?>">
			</div>
		</div>
		<p><?php $this->lan("Interruption_rules");?></p>
		<div class="rule">
			<div>
				<p><?php $this->lan("Daily_deduction");?></p>
				<input type="text" name="discount[reduce]" value="<?php echo $discount['reduce'];?>">
			</div>
			<div>
				<p><?php $this->lan("Minimum_discount");?></p>
				<input type="text" name="discount[min]" value="<?php echo $discount['min']?:''?>">
			</div>
		</div>
		<input type="submit" value="<?php $this->lan("Submit");?> "  class="button" style="margin-left: 42px;">
	</div>
  </form>
</div>
<!--table-list end-->
<script type="text/javascript">

function delElement(){
	var days=$("#days").val();
	var d=parseInt(days)-1;
	$("#days").val(d);
	$("#Receive td:last").remove(); 
}
function addElement(){
	var days=$("#days").val(); 
	var d=parseInt(days)+1;
	$("#days").val(d);
	$("#Receive").append('<td class="rightgray">第 '+d+' 天 <input type="text" name="d_money['+d+']" class="layui-input" value="0" required/><div>元</div></td>')
	
}

</script>
</body>
</html>
