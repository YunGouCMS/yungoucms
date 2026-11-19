<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php $this->lan("Member_edit");?></title>
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
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
.rigBank{
    position: absolute;left: 500px;top:0px;display:flex;
}
.rigBank input{
    margin-bottom: 8px;
}
.rigAddress{
  position: absolute;left: 500px;top:380px;display:flex;border-top: 1px solid #444;
}
.rigAddress input{
    margin-bottom: 8px;
}
.rigBank span,.rigAddress span {
    width: 150px;
    display: inline-block;
    padding-top: 8px;
    padding-bottom: 8px;
    color: #444;
    padding-left: 10px;
    text-align: right;
    margin-right: 10px;
}
.rigBank em,.rigAddress em {
    width: 300px;
    display: inline-block;
}
.rigBank td .xvxian,.rigAddress td .xvxian {
    border-top: 1px dotted #444;
}
.rigBank td .xvxian:first-child,.rigAddress td .xvxian:first-child {
    border-top: 0;
}
select {
    min-width: 130px;
}
.input-text {
    width: 123px;
}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10" style="min-height: calc(100vh - 65px);">
<!--start-->
<form name="myform" action="#" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0" style="position: relative;">
     <div>
            <tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("nickname");?></td>
			<td style="display: flex;"><input type="text" name="username" placeholder="<?php $this->lan("Tips1");?>" value="<?php echo $member['username'];?>" class="input-text" style="max-width: 250px !important;"></td>
		</tr>
		<!--tr>
			<td width="120" align="right">邮箱&nbsp;</td>
			<td><input type="text" name="email" value="<?php echo $member['email'];?>" class="input-text"></td>
		</tr-->
		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("mobile_phone_no");?></td>
			<td style="display: flex;"><input type="text" name="mobile" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>11)value=value.slice(0,11)" value="<?php echo $member['mobile'];?>" placeholder="<?php $this->lan("Tips2");?>" class="input-text" style="max-width: 250px !important;"></td>
		</tr>
		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("password");?></td>
			<td style="display: flex;"><input type="text" name="password" value="" placeholder="<?php $this->lan("Tips3");?>" class="input-text" style="max-width: 250px !important;"></td>
		</tr>
		<!--<tr>
			<td width="120" align="right">账户金额</td>
			<td><input type="text" name="money" value="<?php echo $member['money'];?>" class="input-text">元</td>
		</tr>
		<tr>
			<td width="120" align="right">经验值</td>
			<td><input type="text" name="jingyan" value="<?php echo $member['jingyan'];?>" class="input-text"></td>
		</tr> -->
		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("integral");?></td>
			<td style="display: flex;"><input type="text" name="score" onkeyup="this.value=this.value.replace(/\D/g,'')" value="<?php echo $member['score'];?>" placeholder="<?php $this->lan("Tips4");?>" class="input-text" style="max-width: 250px !important;"></td>
		</tr>
        </div>
        <div >
           <tr class="rigBank">
			<td width="200" align="right" style="margin-top: 8px;"><?php $this->lan("Withdraw_bank_card");?></td>
			<td style="display:flex;flex-direction: column;width: calc(100vw - 665px);">
				<?php foreach($bank as $k=>$vl){
					echo "<div class='xvxian' style='display:flex;flex-direction: column;'>";
					echo "<div><span>{$this->lgs['Account_name']}</span><em>{$vl['user_name']}</em>";
					echo "<span>{$this->lgs['Bank_name']}</span><em>{$vl['bank_name']}</em></div>";
					echo "<div><span>{$this->lgs['Open_name']}</span><em>{$vl['sub_bank']}</em>";
					echo "<span>{$this->lgs['Bank_account_number']}</span><em>{$vl['bank_no']}</em></div>";
					echo "<div><span>{$this->lgs['Contact_number']}</span><em>{$vl['phone']}</em>";
					echo "<span>{$this->lgs['Isdefault']}</span><em>{$vl['default']}</em></div>";
					echo "</div>";
					?>
				<?php } ?>
				<?php foreach($bank as $k=>$vl){
				// 	echo "<div style='display:flex;flex-direction: column;'>";
				// 	echo "<div><span>开户姓名：</span><input type='text' class='input-text' name='bank[{$k}][user_name]' value='{$vl['user_name']}' placeholder='请输入开户姓名'>";
				// 	echo "<span>银行名称：</span><input type='text' class='input-text' name='bank[{$k}][bank_name]' value='{$vl['bank_name']}' placeholder='请输入银行名称'></div>";
				// 	echo "<div><span>开户省/市/支行名：</span><input type='text' class='input-text' name='bank[{$k}][sub_bank]' value='{$vl['sub_bank']}' placeholder='请输入开户省/市/支行名'> ";
				// 	echo "<span>银行账号：</span><input type='text' class='input-text' name='bank[{$k}][bank_no]' value='{$vl['bank_no']}' placeholder='请输入银行账号'></div>";
				// 	echo "<div><span>联系电话：</span><input type='text' class='input-text' name='bank[{$k}][phone]' value='{$vl['phone']}' placeholder='请输入联系电话'>";
				// 	echo "<span>是否默认：</span><input type='text' class='input-text' name='bank[{$k}][default]' value='{$vl['default']}' placeholder='是否默认'></div>";
				// 	echo "</div>";
					?>
				<?php } ?>
			</td>
		</tr>
		
			<tr class="rigAddress">
			<td width="200" align="right" style="margin-top: 8px;"><?php $this->lan("Delivery_address");?></td>
			<td style="display:flex;flex-direction: column;width: calc(100vw - 665px);">
			    <?php foreach($address as $k=>$vl){
					echo "<div class='xvxian' style='display:flex;flex-direction: column;'>";
					echo "<div><span>{$this->lgs['province']}</span><em>{$vl['sheng']}</em>";
					echo "<span>{$this->lgs['city']}</span><em>{$vl['shi']}</em></div>";
					echo "<div><span>{$this->lgs['Districts_counties']}</span><em>{$vl['xian']}</em>";
					echo "<span>{$this->lgs['street']}</span><em>{$vl['jiedao']}</em></div>";
					echo "<div><span>{$this->lgs['consignee']}</span><em>{$vl['shouhuoren']}</em>";
					echo "<span>{$this->lgs['Contact_number']}</span><em>{$vl['mobile']}</em></div>";
					echo "<div><span>{$this->lgs['QQ']}</span><em>{$vl['qq']}</em>";
					echo "<span>{$this->lgs['isAddress']}</span><em>{$vl['default']}</em></div>";
					echo "</div>";
					?>
				<?php } ?>
				<?php foreach($address as $k=>$vl){
				// 	echo "<div style='display:flex;flex-direction: column;'>";
				// 	echo "<div><span>省份：</span><input type='text' class='input-text' name='address[{$k}][sheng]' value='{$vl['sheng']}' placeholder='请输入省份'>";
				// 	echo "<span>城市：</span><input type='text' class='input-text' name='address[{$k}][shi]' value='{$vl['shi']}' placeholder='请输入城市'></div>";
				// 	echo "<div><span>区县：</span><input type='text' class='input-text' name='address[{$k}][xian]' value='{$vl['xian']}' placeholder='请输入区县'> ";
				// 	echo "<span>街道：</span><input type='text' class='input-text' name='address[{$k}][jiedao]' value='{$vl['jiedao']}' placeholder='请输入街道'></div>";
				// 	echo "<div><span>收货人：</span><input type='text' class='input-text' name='address[{$k}][shouhuoren]' value='{$vl['shouhuoren']}' placeholder='请输入收货人'>";
				// 	echo "<span>联系电话：</span><input type='text' class='input-text' name='address[{$k}][mobile]' value='{$vl['mobile']}' placeholder='请输入联系电话'></div>";
				// 	echo "<div><span>QQ：</span><input type='text' class='input-text' name='address[{$k}][qq]' value='{$vl['qq']}' placeholder='请输入QQ'>";
				// 	echo "<span>是否默认地址：</span><input type='text' class='input-text' name='address[{$k}][default]' value='{$vl['default']}' placeholder='是否默认'></div>";
				// 	echo "</div>";
					?>
				<?php } ?>
			</td>
		</tr> 
       </div>
           
      
  	    
		
		<!-- <tr>
			<td width="120" align="right">设为收货商</td>
			<td>
				<input type="radio" name="level" value="1" <?php if($member['level']=='1'){echo 'checked';} ?> style="zoom: 120%;">&nbsp;&nbsp;开启&nbsp;&nbsp;&nbsp;
				<input type="radio" name="level" value="0" <?php if($member['level']=='0'){echo 'checked';} ?>  style="zoom: 120%;margin-left: 10px;">&nbsp;&nbsp;关闭
			</td>
		</tr> -->
		<!--tr>
			<td width="120" align="right">邮箱验证&nbsp;</td>
			<td>
				<input type="radio" name="emailcode" value="1" <?php if($member['emailcode']=='1'){echo 'checked';}?> style="zoom: 120%;">已验证
				<input type="radio" name="emailcode" value="-1" <?php  if($member['emailcode']=='-1'){echo 'checked';}?>  style="zoom: 120%;">未验证
			</td>
		</tr-->
		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("Mobile_verification");?></td>
			<td style="display: flex;align-items: center;">
				<input type="radio" name="mobilecode" value="1" <?php echo $member['mobilecode']=='1'?'checked':'';?> style="zoom: 120%;margin:0!important;">&nbsp;&nbsp;<?php $this->lan("Verified");?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="mobilecode" value="-1" <?php echo $member['mobilecode']=='-1'?'checked':'';?>  style="zoom: 120%;margin:0!important;">&nbsp;&nbsp;<?php $this->lan("Unverified");?>
			</td>
		</tr>

		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("head");?></td>				
			<td>
				<div class="layui-row">
					<div class="layui-col-md1 width130" style="margin: 0!important;">
						<div class="layui-upload-list height130">
							<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif;?>>
								<span>+</span>
								<text><?php $this->lan("Click_upload");?></text>
							</div>
							<img class="layui-upload-img" width="120px" src="<?php echo $member['img'];?>"  id="upusertx-img">
							<input type="hidden" id="upusertx-lack" name="upimage" value="<?php echo $member['img'];?>">
							<p id="demoText"></p>
							<button type="button" onclick="uploadimg('修改用户头像','#upusertx','','touimg')"></button>
						</div>
					</div>
				</div>
			</td>
			</td>
		</tr>
		<!-- <tr>
			<td width="120" align="right">签名&nbsp;</td>
			<td><textarea style="width:400px;height:100px;padding:5px;" placeholder=" 请输入签名" name="qianming"><?php echo $member['qianming'];?></textarea></td>
		</tr> -->
		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("jurisdiction");?></td>
			<td style="display: flex;">
				<select name="membergroup" style="margin: 0!important;">
				<?php foreach($member_allgroup as $v){?>
					<option value="<?php echo $v['groupid'];?>" <?php echo $member_group['name']==$v['name']?'selected':''; ?>><?php echo $v['name'];?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" style="text-align: right !important;padding-right: 10px;box-sizing: border-box;"><?php $this->lan("Member_role");?></td>
			<td style="display: flex;">
				<select name="level" style="margin: 0!important;">
					<option value="0" <?php echo $member['level']==0?"selected":"";?>><?php $this->lan("Ordinary_member");?></option>
					<option value="2" <?php echo $member['level']==2?"selected":"";?>><?php $this->lan("Channel_partners");?></option>
					<option value="3" <?php echo $member['level']==3?"selected":"";?>><?php $this->lan("Merchants");?></option>
					<option value="1" <?php echo $member['level']==1?"selected":"";?>><?php $this->lan("salesperson");?></option>
				</select>
			</td>
		</tr>
		<!--tr>
			<td width="120" align="right">圈子组&nbsp;</td>
			<td>
				<?php 
					if(!empty($quanziname)){ 
						echo implode(',',$quanziname);
					}
				?>
			</td>
		</tr-->
		<!--<tr>-->
  <!--      	<td width="120" align="right"></td>-->
  <!--          <td>		-->
  <!--          <input type="button" class="button" name="" value="购买记录" >-->
  <!--          <input type="button" class="button" name="" value="充值记录" >-->
  <!--          <input type="button" class="button" name="" value="中奖记录" >-->
  <!--          <input type="button" class="button" name="" value="上下级关系" >-->
  <!--          <input type="button" class="button" name="" value="个人银行卡" >-->
  <!--          <input type="button" class="button" name="" value="充值记录" >-->
  <!--          </td>-->
		<!--</tr>-->
		<tr>
        	<td width="200" align="right"></td>
            <td style="display: flex;">		
            <input type="submit" class="button" name="submit" value="<?php $this->lan("Submit");?>" style="margin: 0!important;">
            </td>
		</tr>
</table>
</form>
</div><!--table-list end-->

<script>
function upImage(){
	return document.getElementById('imgfield').click();
}
</script>

<script>
var rigBankhei = $('.rigBank').height();
$('.rigAddress').css('top', rigBankhei);
    layui.use(['upload', 'element', 'layer'], function(){
        var $ = layui.jquery
            ,upload = layui.upload
            ,element = layui.element
            ,layer = layui.layer;
        //多图片上传
        var uploadInst = upload.render({
            elem: '#updateimgbtn'
            ,data:{url:'touimg'}
            ,url: '<?php echo G_WEB_PATH;?>/<?php echo G_ADMIN_DIR;?>/content/upimage' //此处用的是第三方的 http 请求演示，实际使用时改成您自己的上传接口即可。
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                // obj.preview(function(index, file, result){
                //
                // });

                element.progress('demo', '0%'); //进度条复位
                layer.msg('上传中', {icon: 16, time: 0});
            }
            ,done: function(res){
                console.log(res)
                //如果上传失败
                if(res.code == 1){
                    $('#imgHeadPhoto2').val(res.data.url)
                    $('#imgha').attr('src',res.data.url); //图片链接（base64）
                    $('#demoText').html(''); //置空上传失败的状态
                    return layer.msg('上传成功');
                }
                //上传成功的一些操作
                //……
                element.progress('demo', '0%'); //进度条复位
                return layer.alert(res.msg);
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
            //进度条
            ,progress: function(n, elem, e){
                element.progress('demo', n + '%'); //可配合 layui 进度条元素使用
                if(n == 100){
                    layer.msg('上传完毕', {icon: 1});
                }
            }
        });
        upload.render({
            elem: '#listshopimg'
            ,number:10
            ,url: '<?php echo G_WEB_PATH;?>/<?php echo G_ADMIN_DIR;?>/content/upimage' //此处配置你自己的上传接口即可
            ,multiple: true
            ,data:{url:'shopimg'}
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                // obj.preview(function(index, file, result){
                //     $('#demo2').append('<img src="'+ result +'" width="100px" alt="'+ file.name +'" class="layui-upload-img">')
                // });
            }
            ,done: function(res){
                if (res.code==1){
                    $('#demo2').append('<img src="'+ res.data.url +'" width="100px" alt="用户头像" class="layui-upload-img">')
                    $('#imginput').append('<input type="hidden" name="imgarrlist[]" value="'+res.data.url2+'" />')
                }

                return layer.alert(res.msg);
                //上传完毕
            }
        });
    });
</script>
</body>
</html> 