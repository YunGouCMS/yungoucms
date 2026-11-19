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

</head>

<body>

<div class="header lr10">

   	<?php echo $this->headerment();?>

</div>

<div class="bk10"></div>

<div class="table_form lr10">

<form action="#" method="post" id="myform">

<table width="100%" cellspacing="0" style="border-width: 1px;">

    <tr>

    	<td width="200" align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("name");?></td> 

   		<td style="display:flex;"><input type="text" name="username" placeholder="<?php $this->lan("name");?>" class="input-text" id="username"></td>

    </tr>

    <tr>
        <td align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("nickname");?></td>
        <td style="display:flex;"><input type="text" name="name" placeholder="<?php $this->lan("nickname");?>" class="input-text" id="name" value=""></td>
    </tr>
    <tr>

    	<td align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("password","密码");?></td>

    	<td style="display:flex;"><input type="password" name="password" placeholder="<?php $this->lan("passwword");?>" class="input-text" id="password" value=""></td>

    </tr>

    <tr>

    	<td align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("password_confirm");?></td> 

    	<td style="display:flex;"><input type="password" name="pwdconfirm" placeholder="<?php $this->lan("tips4");?>" class="input-text" id="pwdconfirm" value=""></td>

    </tr>
    <tr>
        <td  align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("Security_password");?></td>
        <td style="display:flex;"><input type="password" name="secure_password" placeholder="<?php $this->lan("tips5");?>" class="input-text" id="secure_password" value=""></input></td>
    </tr>
    <tr>
    	<td align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("Security_password_confirm");?></td>
    	<td style="display:flex;"><input type="password" name="secure_pwdconfirm" placeholder="<?php $this->lan("tips6");?>" class="input-text" id="secure_pwdconfirm" value=""></input></td>
    </tr>

    <tr>

    <td align="right" style="text-align: right!important;padding-right: 10px!important;"><?php $this->lan("Belonging_role");?></td>

        <td style="display:flex;">

        <select name="mid" style="margin:0!important;">

		<?php foreach($grouplist as $v){ ?>
        <option value="<?php echo $v['mid']; ?>" ><?php echo $v['name']; ?></option>

 		<?php } ?>
        </select>

        </td>

    </tr>
	
	<tr>
	
	
		<td align="right"></td> 
	
	
		<td style="display:flex;"><input type="button" value="<?php $this->lan("Submit");?> " id="dosubmit" class="button" style="margin:0!important;"></td>
	
	
	</tr>
	
	
	
	<tr>

</table>
    <input type="hidden" name="submit-1" />
</form>

</div><!--table-list end-->

<script type="text/javascript">

var error='';

var bool=false;

var id='';

$(document).ready(function(){		

		

	   document.getElementById('dosubmit').onclick=function(){

		   		bool=false;

				var myform=document.getElementById('myform');

				if(!myform.username.value){

					error='用户名不能为空';

					id='username';

					bool=true;

				}

				if(!myform.password.value){	

					error='密码不能为空';

					id='password';

					bool=true;

				}

           var names =  $('#name').val();
           if(names == ''){
               error='昵称不能为空';
               id='name';
               bool=true;
           }

				if(!myform.pwdconfirm.value){

					error='请在次输入密码';

					id='pwdconfirm';

					bool=true;

				}

				

				

				if(bool){					

					alert(error);

					return false;

				}else{

					if(myform.password.value!=myform.pwdconfirm.value){

						alert('两次密码不同');

						return false;

					}
					$.post('<?php echo WEB_PATH; ?>/api/checkform/musername',{username:myform.username.value,ajax:true},function(data){
						if(data.trim()=='no'){							
							alert("用户名长度在15个字符内,1个汉字等于2个字符");
						}else if(data.trim()=='yes'){

							document.getElementById('myform').submit();

						}

					});					

				}

	   }

				

	

		

});

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 