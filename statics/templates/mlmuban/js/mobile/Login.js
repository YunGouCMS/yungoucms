var loginType=0;
var couter = $('#couter');
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
		var y=$("#phone").val();
		//极验验证
		var bv=$('input[name="geetest_challenge"]').val();
		
		var cv=$('input[name="geetest_validate"]').val();
		
		var fv=$('input[name="geetest_seccode"]').val();

        if(/^1\d{10}$/.test($("#phone").val()) == false){
            $("#phone").val("");
            $("#phone").focus();
            return layer.msg('请输入有效的手机号码');
        }
        ajaxUtil.ajax({
            url:"/mobile/ajax/send_logincode",
            type:'POST',
            dataType:'JSON',
            data:{
                mobilePhone:$("#phone").val(),
                bv:bv,
                cv:cv,
                fv:fv
            }
        },function(result) {
            if(result.status==200){
                layer.msg('短信验证码发送成功，请查收');
                send_delay();
            }else{
                sending = false;
                layer.msg(result.message);
            }
        })
    });
	//切换登录方式
	$(document).on('click','.loginType div',function(){
		$(this).addClass('active').siblings().removeClass('active')
        if($('.accountLogin').hasClass('active')){
            $('.registerCon .pwd').css('display','block')
			$('.registerCon .Lcodes').css('display','block')
            $('.registerCon .code').css('display','none')
            loginType=0;
        }else if($('.mobileCodeLogin').hasClass('active')){
            $('.registerCon .pwd').css('display','none')
			$('.registerCon .Lcodes').css('display','block')
            $('.registerCon .code').css('display','block')
        	loginType=1;
        }
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
	form.on('submit(login)', function(data){
		data.field.loginType=loginType;
		var y=$("#phone").val();
		var pwd=$("#pwd").val();
        var f = function(x) {

            var w = /^1\d{10}$/;

            return w.test(x)

        };

        var d = function(x) {

            var w = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

            return w.test(x)

        };
		//极验验证
		var bv=$('input[name="geetest_challenge"]').val();

        var cv=$('input[name="geetest_validate"]').val();

        var fv=$('input[name="geetest_seccode"]').val(); 
		
		if(loginType==0){
			if (!f(y) && !d(y)){
				$("#phone").val("");
				$("#phone").focus();
				return layer.msg('请输入有效的手机号码');
			}   
			if(data.field.pwd==""){
				$("#pwd").val("");
				$("#pwd").focus();
				return layer.msg('请输入密码');
			}
			// var checkval=$('input:radio[name="option0"]:checked').val();
			// if(checkval!="check"){
			// 	return layer.msg('未勾选《用户协议》和《隐私协议》');
			// }
		    delete data.field['pwd'];
		    delete data.field['validCode'];
			ajaxUtil.ajax({
				url:"/mobile/ajax/userlogin/"+y+'/'+base64encode(utf16to8(pwd))+'/'+bv+'/'+cv+'/'+fv,
				type: 'GET',
				dataType: 'JSON',
				data: {}	 
			},function(result) {
				if(result.state == 0){
					if(window.plus){
						plus.storage.setItem('phone',data.field['phone']);
						plus.storage.setItem('loginToken',data.field['loginToken']);
						plus.storage.setItem('loginType',data.field['loginType']+'');
					}
					var url = "/mobile/home"; 
					setTimeout(window.location.href=url,2000);
				}else{
					if(result.state == 2){
						layer.msg('请完成验证');
					}else if(result.state == 3){
						layer.msg('该账号未认证');
					}else{
						layer.msg('账号或密码错误');
					}
				}
			})
		}else if(loginType==1){
			if (!f(y) && !d(y)){
				$("#phone").val("");
				$("#phone").focus();
				return layer.msg('请输入有效的手机号码');
			}
			var validCode=$("#validCode").val();
			if(validCode==""){
				$("#validCode").val("");
				$("#validCode").focus();
				return layer.msg('请输入验证码');
			}
			if( validCode.length!=6){
				$("#validCode").val("");
				$("#validCode").focus();
				return layer.msg('请输入正确的验证码');
			}
			// var checkval=$('input:radio[name="option0"]:checked').val();
			// if(checkval!="check"){
			// 	return layer.msg('未勾选《用户协议》和《隐私协议》');
			// }
			ajaxUtil.ajax({
				url:"/mobile/ajax/mobilecode_login/"+y+'/'+validCode, 
				type:'GET',
				dataType:'JSON',
				data:{},	 
			},function(result) {
				if(result.state == 0){
					var url ="/mobile/home";
					setTimeout(window.location.href=url,2000);
				}else if(result.state == 4){
				// 	var url='/mobile/user/password';
					var url ="/mobile/home";
					setTimeout(window.location.href=url,2000);
				}else{
					layer.msg(result.message);
				}
			})
		}
		

	});

});

    var base64encodechars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

    var base64decodechars = new Array(

    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,

    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,

    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,

    52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,

    -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,

    15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,

    -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,

    41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

    function base64encode(str) {

        var out, i, len;

        var c1, c2, c3;

        len = str.length;

        i = 0;

        out = "";

        while (i < len) {

            c1 = str.charCodeAt(i++) & 0xff;

            if (i == len) {

                out += base64encodechars.charAt(c1 >> 2);

                out += base64encodechars.charAt((c1 & 0x3) << 4);

                out += "==";

                break;

            }

            c2 = str.charCodeAt(i++);

            if (i == len) {

                out += base64encodechars.charAt(c1 >> 2);

                out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));

                out += base64encodechars.charAt((c2 & 0xf) << 2);

                out += "=";

                break;

            }

            c3 = str.charCodeAt(i++);

            out += base64encodechars.charAt(c1 >> 2);

            out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));

            out += base64encodechars.charAt(((c2 & 0xf) << 2) | ((c3 & 0xc0) >> 6));

            out += base64encodechars.charAt(c3 & 0x3f);

        }

        return out;

    }

    function base64decode(str) {

        var c1, c2, c3, c4;

        var i, len, out;

        len = str.length;

        i = 0;

        out = "";

        while (i < len) {



            do {

                c1 = base64decodechars[str.charCodeAt(i++) & 0xff];

            } while (i < len && c1 == -1);

            if (c1 == -1)

                break;



            do {

                c2 = base64decodechars[str.charCodeAt(i++) & 0xff];

            } while (i < len && c2 == -1);

            if (c2 == -1)

                break;

            out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));



            do {

                c3 = str.charCodeAt(i++) & 0xff;

                if (c3 == 61)

                    return out;

                c3 = base64decodechars[c3];

            } while (i < len && c3 == -1);

            if (c3 == -1)

                break;

            out += String.fromCharCode(((c2 & 0xf) << 4) | ((c3 & 0x3c) >> 2));



            do {

                c4 = str.charCodeAt(i++) & 0xff;

                if (c4 == 61)

                    return out;

                c4 = base64decodechars[c4];

            } while (i < len && c4 == -1);

            if (c4 == -1)

                break;

            out += String.fromCharCode(((c3 & 0x03) << 6) | c4);

        }

        return out;

    }

    function utf16to8(str) {

        var out, i, len, c;

        out = "";

        len = str.length;

        for (i = 0; i < len; i++) {

            c = str.charCodeAt(i);

            if ((c >= 0x0001) && (c <= 0x007f)) {

                out += str.charAt(i);

            } else if (c > 0x07ff) {

                out += String.fromCharCode(0xe0 | ((c >> 12) & 0x0f));

                out += String.fromCharCode(0x80 | ((c >> 6) & 0x3f));

                out += String.fromCharCode(0x80 | ((c >> 0) & 0x3f));

            } else {

                out += String.fromCharCode(0xc0 | ((c >> 6) & 0x1f));



                out += String.fromCharCode(0x80 | ((c >> 0) & 0x3f));

            }

        }

        return out;

    }

    function utf8to16(str) {

        var out, i, len, c;

        var char2, char3;

        out = "";

        len = str.length;

        i = 0;

        while (i < len) {

            c = str.charCodeAt(i++);

            switch (c >> 4) {

                case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:

                    // 0xxxxxxx

                    out += str.charAt(i - 1);

                    break;

                case 12: case 13:

                    // 110x xxxx   10xx xxxx

                    char2 = str.charCodeAt(i++);

                    out += String.fromCharCode(((c & 0x1f) << 6) | (char2 & 0x3f));

                    break;

                case 14:

                    // 1110 xxxx  10xx xxxx  10xx xxxx

                    char2 = str.charCodeAt(i++);

                    char3 = str.charCodeAt(i++);

                    out += String.fromCharCode(((c & 0x0f) << 12) |

                       ((char2 & 0x3f) << 6) |

                       ((char3 & 0x3f) << 0));

                    break;

            }

        }

        return out;

    }

    //base64 加密base64encode(utf16to8(value));

    //base64 解密utf8to16(base64decode(value));