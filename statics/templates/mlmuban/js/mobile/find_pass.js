var couter = $('#couter')
var num = 60;
var sending = false;
function send_delay(){
    set_delay(num);
    interval = setInterval(function(){
        num--;
        if(num <= 0){
            stop_delay();
        }else{
            set_delay(num);
        }
    }, 1000);
} 
function stop_delay(){
    clearInterval(interval);
     $('#couter').html('重新获取');
    sending = false;
    num = 60;
}
function set_delay(num){
    $('#couter').html(num +' 秒');
}

layui.use(['form','layer'],function(){
	var form = layui.form;
	var layer = layui.layer;
	$(document).on('click','#couter',function(){
		if(sending == true){return;}
	    // 手机号
	    if(/^1\d{10}$/.test($("#phone").val()) == false){
	    	$("#phone").val("");
	    	$("#phone").focus();
	    	return layer.msg('请输入有效的手机号码');
	    }    
	    sending = true; 
	    ajaxUtil.ajax({
			url:"/mobile/ajax/send_logincode",
			type:'POST',
			dataType:'JSON',
			data:{
				mobilePhone:$("#phone").val()
			}
		},function(result) {
			if(result.status==200){
		        layer.msg('短信验证码发送成功，请查收！');
		        send_delay();
		    }else{
		    	sending = false;
		    	layer.msg(result.message);
		    }
		})
    });
	//点击显示密码
	$(document).on('click','#toggle_psw',function(){
		$(this).toggleClass('show_psw')
        if($(this).hasClass('show_psw')){
            $("#pwd").attr('type','text');
        }else{
        	$("#pwd").attr('type','password');
        }  
    });
	form.on('submit(save)', function(data){
		// 手机号
	    if(/^\d{11}$/.test($("#phone").val()) == false){
	    	$("#phone").val("");
	    	$("#phone").focus();
	    	return layer.msg('请输入有效的手机号码');
	    }
		if(data.field.validCode==""){
			$("#validCode").val("");
			$("#validCode").focus();
			return layer.msg('请输入验证码');
		}
		// 数字个数
        var numCnt = data.field.pwd.replace(/\D/g, '').length;  
        // 大小写字母个数
        //var alphaNum = data.field.pwd.replace(/[^a-zA-Z]/g, '').length;
        if (numCnt<6) {
        	$("#pwd").val("");
			$("#pwd").focus();
            return layer.msg('密码至少六位');
        }
		ajaxUtil.ajax({
			url:"/mobile/ajax/findPass",
			type:'POST',
			dataType:'JSON',
			data:{
				phone:$("#phone").val(),
				validCode:$("#validCode").val(),
				pwd:$("#pwd").val()
			}	
		},function(result) {
			if(result.status==200){
				layer.msg('密码修改成功');
		        var url = "/mobile/home";
		        setTimeout(window.location.href=url,2000);
		    }else{
		    	layer.msg(result.message);
		    }
		})
	});
});