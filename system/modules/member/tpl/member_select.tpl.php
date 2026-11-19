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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.table-list th {text-align: center;}
  .remarks{
  margin: 10px 0;
  }
  .remarks p{
  line-height: 32px;
  }
  .remarks p a{
  background: #161E22;
    font-size: 12px;
    padding: 4px 6px;
    color: #fff;
    border-radius: 5px;
    margin-left: 20px;
  }  
.hoverdiva {
	  display: inline-block;
	  cursor: pointer;
  }
  .hoverdiva:hover div {
  	  display: flex;
  }
  .hoverdiva div {
	display: none;
	position: absolute;
	top: 30px;
	right: 5px;
	width: 150px;
	flex-wrap: wrap;
	justify-content: space-between;
	background: #e2e9eb;
	border-radius: 7px;
	box-shadow: 1px 1px 5px #aaa;
	padding: 5px;
	z-index: 100;
    flex-direction: column;
  }
  .hoverdiva div a {line-height:30px;}
  .ontrueline {background:#41c42d;display: inline-block;width: 10px;height: 10px;border-radius: 50%;}
</style>
</head>
<!--member_lookup-->
<body style="height:100vh">
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
<div class="table_form ">
	<form name="myform" action="#" method="post">
	<select name="sousuo" class="soucsuo"> 
		<option value="id" content="<?php $this->lan("tips1");?> "><?php $this->lan("Member_uid");?></option>
		<option value="v_id" content="<?php $this->lan("tips2");?>"><?php $this->lan("Member_id");?></option>
		<option value="nickname" content="<?php $this->lan("tips3");?>"><?php $this->lan("nickname");?></option>
		<!--option value="email" content="请输入会员邮箱">会员邮箱</option-->
		<option value="mobile" content="<?php $this->lan("tips4");?>"><?php $this->lan("tel");?></option>
	</select>
	<input type="text" style="width: 200px;" name="content" value="<?=$content;?>" placeholder=" <?php $this->lan("tips1");?>" class="input-text soutsuo">
	<input type="submit" class="button" name="sososubmit" value="<?php $this->lan("search");?>" >
	</form>
</div>
</div>
<div class="bk10"></div>
<div class="lr10 table-list">

<table width="100%" cellspacing="0">
		<?php 
			if(!empty($members)){
		?>	
		<thead>
			<tr>
				<th align="center"><?php $this->lan("Member_uid");?></th>
				<th align="center"><?php $this->lan("name");?></th>
				<th align="center"><?php $this->lan("Member_id");?></th>
        <th align="center"><?php $this->lan("Channel");?></th>
				<!--th width="100px" align="center">邮箱</th-->
				<!--<th align="center">手机认证</th>-->
				<th align="center"><?php $this->lan("Member_tel");?></th>
				<th align="center"><?php $this->lan("balance");?></th>
				<th align="center"><?php $this->lan("integral");?></th>
				<th align="center"><?php $this->lan("commission");?></th>
				<!--th width="100px" align="center">邮箱认证</th-->
				<th align="center"><?php $this->lan("time");?></th>
        		<th align="center"><?php $this->lan("Recent_duration");?></th>
				<th align="center"><?php $this->lan("operation");?></th>       
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($members as $v){
		?>
			<tr style="height:50px;line-height: 4px;">
				<td align="center"><?php echo $v['uid']; ?></td>
				<td align="center">
				    <?php
                    if($v['online'] == '是'){
                        echo '<span class="ontrueline"></span>';
                    }
                    ?>
				    <?php echo $v['username']; ?>
				</td>	
				<td align="center"><?php echo $v['v_id']; ?></td>
        <td align="center">
            <?php if($v['ym_uid']){echo $v['ym_uid']."/".$v['ym_username'];} ?>
        </td>
				<td align="center"><?php echo $v['mobile']; ?></td>	
				<td align="center"><?php echo $v['money']; ?></td>
				<td align="center"><?php echo $v['score']; ?></td>
				<td align="center"><?php echo $v['other_money']; ?></td>

				<!--td align="center"><?php if($v['emailcode']==1){?><span style="color:red">已认证</span><?php }else{ ?>未认证<?php } ?></td-->	
				
				<td align="left" style="padding-left: 20px;line-height: 20px;text-align: center;" onclick="userLoginLog(<?php echo $v['uid']; ?>,'<?php echo $v['username']; ?>','<?php echo $v['ip']; ?>')"><?php echo date("Y/m/d H:i:s", $v['time']); ?> ~</br> <?php if(!empty($v['login_time']))echo date("Y/m/d H:i:s", $v['login_time']); ?></td>
          		<td align="center" ><?php echo $v['diff_time']; ?></td>

				<td align="center" style="line-height: 26px;">
					<?php if ($this->dg){ ?>
					<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/daiguan/<?php echo $v['uid']; ?>"target="_blank"><?php $this->lan("Custody");?></a>
					<?php } ?>
					<!--<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/zhmx/<?php echo $v['uid']; ?>"><?php $this->lan("Recharge");?></a>-->
					<a class="butaoin edit_user_monery buttons<?php echo $v['uid']; ?>" uid="<?php echo $v['uid']; ?>" mobile="<?php echo $v['mobile']; ?>"  usermoney="<?php echo $v['money']; ?>"><?php $this->lan("Recharge");?></a>
					<a class="butaoin butaish a_form_noparam" href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v['uid']; ?>" ><?php $this->lan("delete");?></a>
					<div class="hoverdiva butaoin" style="display: math;"><?php $this->lan("more");?>
			          	<div>
        					<a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>"><?php $this->lan("Modify_data");?></a>
        					<a href="javascript:;" class="mycode" data-id="<?php echo $v['uid']; ?>"><?php $this->lan("Invitation_code");?></a>
        					<a href="<?php echo G_MODULE_PATH; ?>/member/yaoqing/<?php echo $v['uid']; ?>"><?php $this->lan("View_subordinates");?></a>
                            <a href="javascript:;" onclick="channel_change(<?php echo$v['uid']?>,<?php echo $v['yaoqing']?>)"><?php $this->lan("Change_yaoqing");?></a>
        					<!--<a href="javascript:;" class="mycode" data-id="<?php echo $v['uid']; ?>">购买记录</a>-->
        					<!--<a href="javascript:;" class="mycode" data-id="<?php echo $v['uid']; ?>">充值记录</a>-->
        					<!--<a href="javascript:;" class="mycode" data-id="<?php echo $v['uid']; ?>">中奖记录</a>-->
        					<!--<a href="javascript:;" class="mycode" data-id="<?php echo $v['uid']; ?>">上下级记录</a>-->
        					<!--<a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>">个人银行卡</a>-->
        					<!--<a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>">个人手机号</a>-->
                            <!--<a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>">收货地址</a>-->
                            <a href="<?php echo G_MODULE_PATH; ?>/record/get_money_details/<?php echo $v['uid']; ?>" data-id="<?php echo $v['uid']; ?>"><?php $this->lan("title_account_detail");?></a>
        					<a href="<?php echo G_MODULE_PATH; ?>/record/scoreRecord/<?php echo $v['uid']; ?>"><?php $this->lan("Source_points");?></a>
					        <a href="<?php echo G_MODULE_PATH; ?>/record/commissionRecord/<?php echo $v['uid']; ?>"><?php $this->lan("Commission_source");?></a>
        					<a href="javascript:;" data-id="<?php echo $v['uid']; ?>" class="banunblock"><?php $this->lan("Ban_unban");?></a>
        					<a href="javascript:;" data-name="<?php echo $v['username']; ?>" data-id="<?php echo $v['uid']; ?>" class="blackuserid"><?php $this->lan("block");?></a>
        				</div>
        			</div>
					<!--<span class="span_fenge lr5">|</span>-->
                    <!--<a href="javascript:;" data-name="<?php echo $v['username']; ?>" data-id="<?php echo $v['uid']; ?>" class="blackuserid">拉黑</a><span class="span_fenge lr5">|</span>-->
					<!--<a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid'];?>">修改</a><span class="span_fenge lr5">|</span>-->
					<!--<a href="<?php echo G_MODULE_PATH; ?>/member/zhmx/<?php echo $v['uid']; ?>">账户明细</a><span class="span_fenge lr5">|</span>-->
					<!--<a href="<?php echo G_MODULE_PATH; ?>/member/yaoqing/<?php echo $v['uid']; ?>">查看下级</a><span class="span_fenge lr5">|</span> -->
					<!--<a href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v['uid'];?>" onclick="return confirm('是否真的删除！');">删除</a>-->
				</td>            	
			</tr>
		<?php 
			} }
		?>
		</tbody>
</table>
<table width="100%" cellspacing="0" style="overflow: auto;">
		<?php 
			if(empty($members)){
		?>	
		<thead>
			<tr>
				<th style="max-width: 100px;" align="center"><?php $this->lan("Member_uid");?></th>
				<th align="center"><?php $this->lan("name");?></th>
				<th width="30" align="center"><?php $this->lan("Member_id");?></th>
				<!--th width="100px" align="center">邮箱</th-->
				<th width="30" align="center"><?php $this->lan("Member_tel");?></th>
				<th align="center"><?php $this->lan("Account_amount");?></th>
				<th align="center"><?php $this->lan("Account_points");?></th>
				<th align="center"><?php $this->lan("Commission_balance");?></th>
				<!--th width="100px" align="center">邮箱认证</th-->
				<th  align="center"><?php $this->lan("time");?></th>
				<th align="center"><?php $this->lan("online");?></th>
        		<th align="center"><?php $this->lan("Online_duration");?></th>
				<th  align="center"><?php $this->lan("authentication");?></th>
				<th align="center"><?php $this->lan("operation");?></th>       
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($members?:[] as $v){
		?>
			<tr style="height:50px;line-height: 4px;">
				<td align="center"><?php echo $v['uid']; ?></td>
				<td align="center"><?php echo $v['username']; ?></td>	
				<td align="center"><?php echo $v['v_id']; ?></td>
				<!--td align="center"><?php echo $v['email']; ?></td-->	
				<td align="center"><?php echo $v['mobile']; ?></td>	
				<td align="center"><?php echo $v['money']; ?></td>
				<td align="center"><?php echo $v['score']; ?></td>
				<td align="center"><?php echo $v['other_money']; ?></td>

				<!--td align="center"><?php if($v['emailcode']==1){?><span style="color:red">已认证</span><?php }else{ ?>未认证<?php } ?></td-->	
				
				<td align="left" style="padding-left: 20px;" onclick="userLoginLog(<?php echo $v['uid']; ?>,'<?php echo $v['username']; ?>','<?php echo $v['ip']; ?>')"><?php echo date("Y/m/d H:i:s", $v['time']); ?> ~ <?php if(!empty($v['login_time']))echo date("Y/m/d H:i:s", $v['login_time']); ?></td>
				<td align="center" ><?php echo $v['online']; ?></td>
          		<td align="center" ><?php echo $v['online_time']; ?></td>
				<td align="center"><?php if($v['mobilecode']==1){?><span style="color:red">已认证</span><?php }else{ ?>未认证<?php } ?></td>

				<td align="center">
					<?php if ($this->dg){ ?>
					<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/daiguan/<?php echo $v['uid']; ?>"target="_blank"><?php $this->lan("Custody");?></a>
					<?php } ?>
                    <a class="butaoin" href="javascript:;" data-name="<?php echo $v['username']; ?>" data-id="<?php echo $v['uid']; ?>" class="blackuserid"><?php $this->lan("block");?></a>
					<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/zhmx/<?php echo $v['uid']; ?>"><?php $this->lan("title_account_detail");?></a>
					<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid'];?>"><?php $this->lan("modify");?></a>
					<a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/yaoqing/<?php echo $v['uid']; ?>"><?php $this->lan("View_subordinates");?></a>
					<a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v['uid'];?>" onclick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
				</td>            	
			</tr>
		<?php 
			} }
		?>
        <?php  if (!$members ): ?>
            <tr>
                <td colspan="12" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
		</tbody>
</table>
</div>
<div class="" id="window" style="margin:30px;display:none;">
    <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Cell");?></label>
            <div class="layui-input-block">
                <input type="tel" name="phone" lay-verify="phone" autocomplete="off" disabled="disabled" class="layui-input" value="" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Account_amount");?></label>
            <div class="layui-input-block">
                <input type="text" name="money" lay-verify="money" autocomplete="off" disabled="disabled" class="layui-input" value="" readonly>
            </div>
        </div>
        <!--<div class="layui-form-item">-->
        <!--    <label class="layui-form-label"><?php $this->lan("Select_action");?></label>-->
        <!--    <div class="layui-input-block">-->
        <!--        <select name="interest" lay-filter="aihao" id='interest'>-->
        <!--            <option value="1"><?php $this->lan("Offline_recharge");?></option>-->
        <!--            <option value="2"><?php $this->lan("Offline_deduction");?></option>-->
        <!--        </select>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="layui-form-item">
        <label class="layui-form-label">
          <spna style="color:#3C8DBC"><?php $this->lan("Recharge"); ?></spna>/<spna style="color:#ef2916"><?php $this->lan("Impairment","扣款"); ?></spna>
        </label>
        <div class="layui-input-block">
          <input type="checkbox" id="type" name="type" value="1" class="is_state sta" lay-value="1|2" checked lay-skin="switch" lay-filter="type" lay-text="<?php $this->lan("Recharge"); ?>|<?php $this->lan("Impairment","扣款"); ?>">
          <input type="hidden" name="interest" id="interest" value="1">
        </div>
      </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="desc"></span><?php $this->lan("notes","备注");?></label>
            <div class="layui-input-block">
                <select name="message" lay-filter="aihao" id='message'>
                    <option value="充值返利"><?php $this->lan("Recharge_rebate");?></option>
                    <option value="无货兑换"><?php $this->lan("goods_exchange");?></option>
                    <option value="活动补偿"><?php $this->lan("Activity_compensation");?></option>
                    <option value="系统扣款"><?php $this->lan("System_deduction");?></option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="desc"></span><?php $this->lan("Amount");?></label>
            <div class="layui-input-block">
                <input type="number" name="addmoney" id="money" lay-verify="title" autocomplete="off" maxlength="11" placeholder="<?php $this->lan("Amount");?>" class="layui-input" required onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" >
            </div>
            <p class="ChineseText" style="margin-left: 120px;"></p>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Security_password");?></label>
            <div class="layui-input-block">
                <input type="password" name="secure_password"  placeholder="<?php $this->lan("Security_password");?>" class="layui-input" required  >
            </div>
        </div>
    </form>
</div>
<div class="channel_change" id="channel_change" style="display:none;">
    <select id="channel_sel" style="width: 80%;margin-left: 40px;
    margin-top: 10px;">
    
    </select>
</div>
<script>
    
function channel_init(uid){
  $.post("/member/channel/channel_lists",{},function(data){
    var data = JSON.parse(data);
    data = data.data
    var html = '<option value="">暂无渠道商</option>';
    for(var i in data){
      html += '<option value="'+data[i].uid+'">'+data[i].uid+'/'+data[i].username+'</option>';
    }
    $('.channel_change select').html(html);
  })
}
channel_init();
function channel_change(uid,yaoqing){
  $("#channel_sel option").each(function(){
    console.log($(this))
    if($(this).val() == yaoqing){
      $(this).prop("selected",true)
    }else{
      $(this).prop("selected",false)
    }
  })
  var desc = '切换渠道商';
  var op = layer.open({
            type: 1, 
            title:desc,
            area:['400px', '250px'],
            btn: ['<?php $this->lan("Confirm","确认");?>','<?php $this->lan("Cancel","取消")?>'],
            btnAlign: 'c',
            content: $("#channel_change"),
            yes:function(index,layero){
              var yaoqing = $('#channel_sel').val();
              console.log(yaoqing)
              $.post("/member/channel/channel_change",{"uid":uid,"yaoqing":yaoqing,"dosubmit":"1"},function(data){
                var data = JSON.parse(data);
                if(data.code == 200){
                  layer.close(op)
                  layer.msg(data.msg)
                  setTimeout(function(){
                    window.location.reload();
                  },1000)
                }else{
                  layer.msg(data.msg)
                }
              })
            }
  })
}

function upImage(){
	return document.getElementById('imgfield').click();
}
$(function(){
    $(".edit_user_monery").click(function(){
        var uid=$(this).attr('uid'),mobile=$(this).attr('mobile'),usermoney=$(this).attr('usermoney');
        $("input[name=phone]").val(mobile);
        $("input[name=money]").val(usermoney);
        var desc = '';
        layui.use(['form','jquery'],function () {
                var form = layui.form,
                    $ = layui.jquery;
                form.render('checkbox');
                desc = $("input[name=type]").next().find("em").html()
                $(".layui-layer-title").html(desc);
                $(".desc").html(desc);

                form.on('switch(type)',function (data) {
                  desc = $(data.elem).next().find("em").html()
                  $(".layui-layer-title").html(desc);
                  $(".layui-layer-btn0").html(desc+'<?php $this->lan("Confirm","确认");?>');
                  $(".desc").html(desc);
                    if (data.elem.checked == true) {
                        $('#interest').val('1');
                    }else if(data.elem.checked == false){
                        $('#interest').val('2');
                    }
                });
        })
	    layer.open({
            type: 1, 
            title:desc,
            area:['400px', '450px'],
            btn: [desc+'<?php $this->lan("Confirm","确认");?>'],
            btnAlign: 'c',
            content: $("#window"),
            yes:function(index,layero){
                var money=$("input[name=addmoney]").val();
                // var type= $("#interest").find("option:selected").attr("value");
                var type=$("input[name=interest]").val();
                var message = $("select[name='message']").val();
                var secure_password=$("input[name=secure_password]").val();
                $.getJSON("<?php echo WEB_PATH."/member/member/upmoney"; ?>",{money:money,type:type,uid:uid,message:message,secure_password:secure_password},function(res){
                //根据后台返回的参数，来进行判断
                var data = res.code
                if(data == -1){
                    layer.msg('线下充值未开启');
                }else if(data == 1){
                  layer.msg('金额不能为0');
                }else if(data == 2){
                  layer.msg('输入金额大于用户余额');
                }else if(data == 4){
                  layer.msg('提交失败');
                }else if(data == 5){
                  layer.msg('安全密码错误');
                }else{
                  layer.msg('提交成功，等待审批')
                  layer.close(index);//关闭弹出层
                  $(".usermoneys"+uid).html(data+'.00');
                  $(".buttons"+uid).attr('usermoney',data+'.00');
                  $("#operation")[0].reset()//重置fo
                }
                });
            } ,
            end: function(){
                $("#operation")[0].reset();
            }
		}); 
    })
});
</script>

<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        $('.blackuserid').on('click',function () {
            var uid = $(this).attr('data-id')
            var name = $(this).attr('data-name')
            var title = name?name:uid
            var index=  layer.open({
                type: 2,
                title: '【'+title+'】 拉黑管理',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['750px', '260px'],
                content: '/?member/member/blacktype/'+uid
            });
        })
    });
	
	$('.soucsuo').change(function(){
		var ptext = $(this).find("option:selected").attr("content")
		$('.soutsuo').attr('placeholder',ptext)
	})
	 var ishovcli = 1
    $('.hoverdiva').hover(function(){
        if (ishovcli == 1) {
            $(this).css({
              position: 'relative'
            });
        	$(this).children().css({
              top: '23px',
              right: '-15px',
              left: 'auto',
              display: 'flex'
            });
        }
    },function(){
        
        if (ishovcli == 1) {
        $('.hoverdiva').css({
            position: 'static'
        });
    	$('.hoverdiva>div').hide();
        }
        
    })
    $('.mententlist').on('contextmenu', function(event) {
        ishovcli = 2
        $('.hoverdiva div').hide()
        event.preventDefault(); // 阻止默认的右键菜单
        
        $(this).find('.hoverdiva div').css({
          left: event.pageX,
          top: event.pageY,
          right: 'auto',
          display: 'flex'
        });
    });
     
    $(document).click(function() {
        ishovcli = 1
        $('.hoverdiva div').hide()
      });

	//查看二维码
	$(".mycode").on("click",function () {
      let id = $(this).data('id');
     var index = ''
      layer.msg('正在加载中，请耐心等待！',{time:1000},function () {
          index = layer.load(1, {
              shade: [0.1,'#fff'] //0.1透明度的白色背景
          });
      });
      $.post('<?php echo WEB_PATH ."/member/member/mycode"; ?>',{id:id},function (res) {
          res = JSON.parse(res);
          if(res.code == 1){
              layer.msg(res.msg,{icon:1,time:1500},function () {
                  layer.close(index);
                  layer.open({
                      type: 1,
                      title:'<?php $this->lan("Invitation_code");?>',
                      skin: 'layui-layer-rim', //加上边框
                      area: ['300px', '500px'], //宽高
                      content: '<img style="width:100%;height:100%" src="'+ res.data+'">'
                  });
              });

          }else{
              layer.close(index);
              layer.msg(res.msg,{icon:2,time:1500});
          }
      })

  });
</script>
</body>
</html> 