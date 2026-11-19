
var num = 120;
var sending = false;

function send_delay() {
    set_delay(num);
    interval = setInterval(function () {
        num--;
        if (num <= 0) {
            stop_delay();
        } else {
            set_delay(num);
        }
    }, 1000);
}

function send() {
	if (sending == true) {
        return;
    }
    sending = true;
    ajaxUtil.ajax({
        url: domain+"/getRegisterPhoneCode.ph",
        type: 'POST',
        dataType: 'JSON',
        data: {
            mobilePhone: $("#phone").val()
        }
    }, function (result) {
    	if(result.status==0){
    		send_delay();
    	} else if (result.status == 200) {
            layer.msg('短信验证码发送成功，请查收！');
            send_delay();
        } else {
            sending = false;
            layer.msg(result.message);
        }
    })
}

$(".newFa").click(function(){
    if($(this).hasClass('active')){
        $(this).removeClass('active')
        send();
    }else{
        return false
    }
})

function stop_delay() {
    clearInterval(interval);
    $(".newFa").html('重新获取').addClass('active');
    sending = false;
    num = 120;
}

function set_delay(num) {
	$(".newFa").html(num + ' 秒');
}

layui.use(['form', 'layer'], function () {
    var form = layui.form;
    var layer = layui.layer;
    
    send();

    $(document).on('click', '.dy-mask', function (e) {
        $(this).css('display', 'none')
    });

    $(document).on('click', '.dy-mask .dy-rcode', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });

    form.on('submit(register)', function (data) {
		
		 if ($.trim($('#validCode').val()) == '') {
	     	$("#validCode").focus();
             return layer.msg('请输入短信验证码');
         }

         ajaxUtil.ajax({
             url: domain+"/register.ph",
             type: 'POST',
             dataType: 'JSON',
             data:data.field,
         }, function (result) {
             if (result.status == 200) {
                 var url = domain+"/member/toHome.ph";
                 setTimeout(window.location.href = url, 2000);
             } else {
                 layer.msg(result.message);
             }
         })
    });
});
