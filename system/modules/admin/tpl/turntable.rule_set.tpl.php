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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<style>
	.rule{
		margin-left: 200px;
    	font-size: 16px;
	}
	.rule-titte{
		font-size: 20px;
		font-weight: bold;
	}
	.rule-title{
		font-size: 16px;
		font-weight: bold;
		display: flex;
        align-items: center;
	}
	.rule-titp{
		font-size: 14px;
		font-weight: inherit;
	}
	.rule_input {
	    width: 100px;
	    text-align: center;
	}
	span{
		color: red;
	}
	.layui-unselect.layui-form-checkbox.layui-form-checked {
	    margin: 0!important;
	}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>
<div class="table_form lr10">
    <form name="myform" id="myform" action="#" method="post" class="layui-form">
        <table width="100%" cellspacing="0">
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td class="rule-titte" style="text-align:left!important;"><?php $this->lan("Probability_rule_setting");?></td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td>
    				<div class="rule-title"><?php $this->lan("rule_one");?>
    				    <p class="rule-titp" style="margin:0!important;"><?php $this->lan("invited_recharged");?><span id="cy_total1">1200</span><?php $this->lan("Above_least");?><span id="cy_num1">1</span><?php $this->lan("tips");?><span id="add1">30</span><?php $this->lan("The_probability");?></p>
    				</div>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Total_recharge_amount");?></td>
    			<td style="display:flex;">
        			<input type="number" data-id="cy_total1" name="rule[0][cy_total]" value="<?php echo $rule[0]['cy_total'];?>" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Number_reinstated");?></td>
    			<td style="display:flex;">
        			<input type="number" data-id="cy_num1" name="rule[0][cy_num]" value="<?php echo $rule[0]['cy_num'];?>" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Increase_probability");?></td>
    			<td style="display:flex;">
        			<input type="number" data-id="add1" name="rule[0][add]" value="<?php echo $rule[0]['add'];?>" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td>
    			    <div class="rule-title"><?php $this->lan("rule_two");?>
    				    <p class="rule-titp" style="margin:0!important;"><?php $this->lan("Invitations_exceeding");?><span id="yq_num2">7</span><?php $this->lan("After_individual");?><span id="login_num2">3</span><?php $this->lan("tips1");?><span id="cy_num2">2</span><?php $this->lan("tips2");?><span id="add2">30</span><?php $this->lan("The_probability");?></p>
    				</div>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Number_invitations");?></td>
    			<td style="display:flex;">
        			<input type="number" class="input-text rule_input" data-id="yq_num2" name="rule[1][yq_num]" value="<?php echo $rule[1]['yq_num'];?>">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("login");?></td>
    			<td style="display:flex;">
        			<input type="number" class="input-text rule_input" data-id="login_num2" name="rule[1][login_num]" value="<?php echo $rule[1]['login_num'];?>">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Number_reinstated");?></td>
    			<td style="display:flex;">
        			<input type="number" class="input-text rule_input" data-id="cy_num2" name="rule[1][cy_num]" value="<?php echo $rule[1]['cy_num'];?>">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Increase_probability");?></td>
    			<td style="display:flex;">
        			<input type="number" class="input-text rule_input" data-id="add2" name="rule[1][add]" value="<?php echo $rule[1]['add'];?>">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td>
        			<div class="rule-title"><?php $this->lan("rule_one");?>
    				    <p class="rule-titp" style="margin:0!important;"><?php $this->lan("tips3");?><span id="yq_num3">1</span><?php $this->lan("tips4");?><span id="add3">1</span><?php $this->lan("The_probability");?></p>
    				</div>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Number_invitations");?></td>
    			<td style="display:flex;">
        			<input type="number" class="input-text rule_input" data-id="yq_num3" name="rule[2][yq_num]" value="<?php echo $rule[2]['yq_num'];?>">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Increase_probability");?></td>
    			<td style="display:flex;">
        			<input type="number" class="input-text rule_input" data-id="add3" name="rule[2][add]" value="<?php echo $rule[2]['add'];?>">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td class="rule-titte" style="text-align:left!important;"><?php $this->lan("Lottery_frequency_setting");?></td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Number_new_users");?></td>
    			<td style="display:flex;">
    				<input type="number"  name="number[first_num]" value="<?php echo $number['first_num'];?>" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Number_daily_lucky");?></td>
    			<td style="display:flex;align-items: center;gap:10px;">
            		<input type="number"  name="number[day_num]" value="<?php echo $number['day_num'];?>" class="input-text rule_input"><?php $this->lan("Is_cumulative");?><input type="checkbox"  name="number[is_num_add]" value="1" <?php echo $number['is_num_add']?"checked":"";?>>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td class="rule-titte" style="text-align:left!important;"><?php $this->lan("Lottery_amount_setting");?></td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Winning_amount");?></td>
    			<td style="display:flex;">
    				<input type="number" name="money[money]" value="<?php echo $money['money'];?>" step="0.001" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td>
    			    <p class="rule-title"><?php $this->lan("First_amount");?></p>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Min_amount");?></td>
    			<td style="display:flex;">
            		<input type="number"  name="money[first_money_min]" value="<?php echo $money['first_money_min'];?>" step="0.001" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Max_amount");?></td>
    			<td style="display:flex;">
        			<input type="number"  name="money[first_money_max]" value="<?php echo $money['first_money_max'];?>" step="0.001" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td>
    			    <p class="rule-title"><?php $this->lan("amount");?></p>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Min_amount");?></td>
    			<td style="display:flex;">
            		<input type="number"  name="money[money_min]" value="<?php echo $money['money_min'];?>" step="0.001" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("Max_amount");?></td>
    			<td style="display:flex;">
        			<input type="number"  name="money[money_max]" value="<?php echo $money['money_max'];?>" step="0.001" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
    			<td>
    			    <p class="rule-titte" style="text-align:left!important;"><?php $this->lan("time_setting");?></p>
    				<p class="rule-titp" style="text-align:left!important;"><?php $this->lan("tips5");?></p>
    			</td>
    		</tr>
    		<tr>
    			<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"><?php $this->lan("period_validity");?></td>
    			<td style="display:flex;">
    				<input type="number" name="time[day]" value="<?php echo $time['day'];?>" class="input-text rule_input">
    			</td>
    		</tr>
    		<tr>
            	<td width="250" align="right" style="text-align: right!important;padding-right: 10px;"></td>
                <td style="display:flex;">		
                <input type="submit" class="button" name="dosubmit"  value="<?php $this->lan("Submit");?>" style="margin:0!important;">
                </td>
    		</tr>
    	</table>
    </form>
</div>
<script>
	$(".rule_input").each(function(){
		rule_input(this)
	})
	$(".rule_input").change(function(){
		rule_input(this)
	})
	function rule_input(element){
		var id = $(element).data("id");
        $("#"+id).text($(element).val());
	}
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 