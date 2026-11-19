<?php 

/**
*	发送用户手机认证码短信
*	mobile @用户手机号  $content =  "你在"._cfg("web_name")."的短信验证码是:".strtolower($checkcodes[0]);
*   uid    @用户的ID
*/

function send_mobile_reg_code($mobile=null,$uid=null){
		if(!$uid)_message("发送用户手机认证码,用户ID不能为空！");
		if(!$mobile)_message("发送用户手机认证码,手机号码不能为空!");	
		$db=System::load_sys_class('model');
		$checkcodes=rand(100000,999999).'|'.time();//验证码
		$db->Query("UPDATE `@#_member` SET mobilecode='$checkcodes' where `uid`='$uid'");				
		$checkcodes = explode("|",$checkcodes);
		$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_reg'");
	
		if(!$template){
            $content = "您的验证码是：[".strtolower($checkcodes[0])."]。请不要把验证码泄露给其他人。";
		}else{
		    if($template['type'] == 2){
		        return false;
            }
        }
		if(empty($template['value'])){
			$content = "您的验证码是：[".strtolower($checkcodes[0])."]。请不要把验证码泄露给其他人。";			
		}else{
			if(strpos($template['value'],"000000") == true){
					$content= str_ireplace("000000",strtolower($checkcodes[0]),$template['value']);			
			}else{
					$content = $template['value'].strtolower($checkcodes[0]);					
			}
		}
		return _sendmobile($mobile,$content);
}

/*
*发送短信到账提醒

*/
function send_rmsg($mobile=null,$uid=null,$money=null){
	if(!$uid){ return false; }
	if(!$mobile){ return false; }	
	$db=System::load_sys_class('model');
	$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_rechange_code'");
	if(!$template || empty($template['value'])){
			$content = "用户".$uid."充值成功，到账金额：".$money."。";	
	}else{

		if($template['type'] == 2){
			return false;
		}
		$content=str_ireplace("000000",$money,$template['value']);
		$content= str_ireplace("0000",$uid,$content);    
	}
	return _sendmobile($mobile,$content);
}

function post_curls($url, $post)
{
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在  
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($post)); // Post提交的数据包
	curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	$res = curl_exec($curl); // 执行操作
	if (curl_errno($curl)) {
		echo 'Errno:'.curl_error($curl);//捕抓异常
	}
	curl_close($curl); // 关闭CURL会话
	return $res; // 返回数据，json格式

}

/**
*	发送用户手机短信验证码
*	mobile @用户手机号
*/
function send_login_code_1($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }else{

        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_1'];	
    $post_data = array();
    $post_data['userid'] =  $mobiles['mid'];
    $post_data['account'] =  $mobiles['mname'];
    $post_data['password'] = $mobiles['mpass'];
    $post_data['content'] =$mobiles['mqianming'].$content; //短信内容
    $post_data['mobile'] = $mobile;
    $post_data['sendtime'] = ''; //时定时发送，输入格式YYYY-MM-DD HH:mm:ss的日期值
    $url='http://sms.qq106.cn/sms.aspx?action=send';
    $o='';
    foreach ($post_data as $k=>$v)
    {
        $o.="$k=".urlencode($v).'&';
    }
    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
    $result = curl_exec($ch);
    $result=simplexml_load_string($result);
    $status=$result->returnstatus;
    if($status == 'Success'){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败'); 
    }   
    return  $arr;
}

function send_login_code_7($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_7'];	
    $post_data = array();
    $post_data['CorpID'] =  $mobiles['mid'];
    $post_data['Pwd'] = $mobiles['mpass'];
    $post_data['Content'] =$content.$mobiles['mqianming']; //短信内容
    $post_data['mobile'] = $mobile;
    $post_data['sendtime'] = ''; //时定时发送，输入格式YYYY-MM-DD HH:mm:ss的日期值
    $url='http://sms.xycredit.com.cn/utf8/BatchSend2.aspx';
    $o='';
    foreach ($post_data as $k=>$v)
    {
        $o.="$k=".urlencode($v).'&';
    }
    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
    $result = curl_exec($ch);
    
    if($result >0){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败'); 
    }   
    return  $arr;    
		//return _sendmobile($mobile,$content);
}

function send_login_code_4($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_4'];	
    $pwd=$mobiles['mpass'];
    $data['username'] = $mobiles['mid'];
    $data['tkey'] 	= date('YmdHis');
    $data['password'] = md5(md5($pwd) . $data['tkey']);
    $data['mobile']=$mobile;
    $data['content']= $content; 
    $data['productid']=$mobiles['productid'];
    $data['xh']='';
    $url='http://www.ztsms.cn/sendNSms.do';

    $huawei_res=post_curls($url,$data);
    $arr=explode(',',$huawei_res);
    if($arr['0']=='1'){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败'); 
    }  
    return  $arr;    
		//return _sendmobile($mobile,$content);
}
function send_mobile_shop_code_5($mobile=null,$uid=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$uid)_message("发送用户手机获奖短信,用户ID不能为空！");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model');					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
    
    if(!$template){
        $template = array();
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if(strpos($template['value'],"00000000") == true){
                $content= str_ireplace("00000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_5'];	
    $postArr = array (
        'account'  =>  $mobiles['mid1'],
        'password' => $mobiles['mpass1'],
        'msg' => $content,
        'mobile' => $mobiles['cid'].$mobile,
    );

    $url='http://intapi.253.com/send/json';

    $postFields = json_encode($postArr);
    $ch = curl_init ();
    curl_setopt( $ch, CURLOPT_URL, $url ); 
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8'
        )
    );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt( $ch, CURLOPT_TIMEOUT,10); 
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec ( $ch );		
    if (false == $ret) {
        $arr = array('-1',$ret); 
    } else {
        $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
        if (200 !== $rsp) {
            $arr = array('-1',"请求状态 ". $rsp . " " . curl_error($ch)); 
        } else {
                $output=json_decode($ret,true);
                if(isset($output['code'])  && $output['code']=='0'){
                    $arr = array('1',"发送成功");
                }else{
                    $arr = array('-1',$output['error']); 
                }
        }
    } 
    return  $arr;    
		//return _sendmobile($mobile,$content);
}
function send_mobile_bind_code($phone=null){
	$db=System::load_sys_class('model');
	$ok=100;
	if(empty($phone)){
		return false;
	}
	$info=$db->GetOne("select * from `@#_member_bind_code_record` where `phone` = '$phone'");
	if($info){
		$db->Query("DELETE FROM  `@#_member_bind_code_record` where `phone` = '$phone'");
	}
	$checkcodes=rand(100000,999999).'|'.time();//验证码
	$time=time();
	$checkcodes = explode("|",$checkcodes);
	$send_incode=$db->Query("INSERT into `@#_member_bind_code_record` (`phone`,`updatetime`,`code`) values ($phone,$time,$checkcodes[0])");
	$template = $db->GetOne("select * from `@#_caches` where `key` = 'send_mobile_bind_code'");
		if(!$template){
      		$content = "你正在绑定手机号，验证码是：[".strtolower($checkcodes[0])."]。请不要把验证码泄露给其他人。";			
		}else{
            if($template['type'] == 2){
                return false;
            }
        }
		if(empty($template['value'])){
			$content = "你正在绑定手机号，验证码是：[".strtolower($checkcodes[0])."]。请不要把验证码泄露给其他人。";			
		}else{
			if(strpos($template['value'],"000000") == true){
					$content= str_ireplace("000000",strtolower($checkcodes[0]),$template['value']);			
			}else{
					$content = $template['value'].strtolower($checkcodes[0]);					
			}
		}	
	//$content="【抢宝在线】你正在绑定手机号，验证码是：".$checkcodes[0];
	$ok=_sendmobile($phone,$content);
	return $ok;
}

/**
*	发送用户手机获奖短信
*	mobile @用户手机号
*   uid    @用户的ID  【潮狸商城】您在@var(irmName)购买的商品已中奖,中奖码是：@var(code)。
*	code   @中奖码
*/

function send_mobile_shop_code_4($mobile=null,$uid=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$uid)_message("发送用户手机获奖短信,用户ID不能为空！");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model');					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
    
    if(!$template){
        $template = array();
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if(strpos($template['value'],"00000000") == true){
                $content= str_ireplace("00000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_4'];	
    $pwd=$mobiles['mpass'];
    $data['username'] = $mobiles['mid'];
    $data['tkey'] 	= date('YmdHis');
    $data['password'] = md5(md5($pwd) . $data['tkey']);
    $data['mobile']=$mobile;
    $data['content']= $content.$mobiles['mqianming'];
    $data['productid']=$mobiles['productid'];
    $data['xh']='';
    $url='http://www.ztsms.cn/sendNSms.do';

    $huawei_res=post_curls($url,$data);
    $arr=explode(',',$huawei_res);
    if($arr['0']=='1'){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败'); 
    }  
    return  $arr;    
		//return _sendmobile($mobile,$content);
}
function send_login_code_5($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    
    if(!$template){ 
        $template = array();
        $content =  "Your verification code is ：".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "Your verification code is ：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_5'];
    $postArr = array (
        'account'  =>  $mobiles['mid'],
        'password' => $mobiles['mpass'],
        'msg' => $content,
        'mobile' => $mobiles['cid'].$mobile,
    );

    $url='http://intapi.253.com/send/json';

    $postFields = json_encode($postArr);
    $ch = curl_init ();
    curl_setopt( $ch, CURLOPT_URL, $url ); 
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8'
        )
    );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt( $ch, CURLOPT_TIMEOUT,10); 
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec ( $ch );
    if (false == $ret) {
        $arr = array('-1',$ret); 
    } else {
        $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
        if (200 !== $rsp) {
            $arr = array('-1',"请求状态 ". $rsp . " " . curl_error($ch)); 
        } else {
                $output=json_decode($ret,true);
                if(isset($output['code'])  && $output['code']=='0'){
                    $arr = array('1',"发送成功");
                }else{
                    $arr = array('-1',$output['error']); 
                }
        }
    }
    curl_close ( $ch );	  
    return  $arr;    
	//return _sendmobile($mobile,$content);
}
//发送微信中奖通知
function send_wx_shop_code_4($mobile,$company_code){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    $db=System::load_sys_class('model');					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'send_wx_shop_code'");
    
    if(!$template){
        $template = array();
        $content =  '您好！您获得的商品已发货，快递单号：'.$company_code."。请注意查收！【YG商城】";
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content = '您好！您获得的商品已发货，快递单号：'.$company_code."。请注意查收！【YG商城】";
    }else{
        if(strpos($template['value'],"0000000000") == true){
                $content= str_ireplace("0000000000",$company_code,$template['value']);
        }else{
                $content = $template['value'].$company_code;
        }
    }
    //$content ='您好！您获得的商品已发货，快递单号：'.$company_code."。请注意查收！【YG商城】";
    $mobiles = $mobiles['cfg_mobile_4'];	
    $pwd=$mobiles['mpass'];
    $data['username'] = $mobiles['mid'];
    $data['tkey'] 	= date('YmdHis');
    $data['password'] = md5(md5($pwd) . $data['tkey']);
    $data['mobile']=$mobile;
    $data['content']= $content.$mobiles['mqianming'];
    $data['productid']=$mobiles['productid'];
    $data['xh']='';
    $url='http://www.ztsms.cn/sendNSms.do';

    $huawei_res=post_curls($url,$data);
    $arr=explode(',',$huawei_res);
    if($arr['0']=='1'){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败'); 
    }  
    return  $arr; 

}
function send_mobile_shop_code_1($mobile=null,$uid=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$uid)_message("发送用户手机获奖短信,用户ID不能为空！");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model');					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
    
    if(!$template){
        $template = array();
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if(strpos($template['value'],"00000000") == true){
                $content= str_ireplace("00000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $mobiledata= $mobiles['cfg_mobile_1'];	
    $mid = urlencode($mobiledata['mid']);
    $mname = $mobiledata['mname'];
    $pwd  = $mobiledata['mpass'];
    $qianming =$mobiledata['mqianming'];
    $haoma = $mobile;

    $post_data = array();
    $post_data['userid'] = $mid;
    $post_data['account'] = $mname;
    $post_data['password'] = $pwd;
    $post_data['content'] =$qianming.$content; //短信内容
    $post_data['mobile'] = $haoma;
    $post_data['sendtime'] = ''; //时定时发送，输入格式YYYY-MM-DD HH:mm:ss的日期值
    $url='http://sms.qq106.cn/sms.aspx?action=send';
    $o='';
    foreach ($post_data as $k=>$v)
    {
        $o.="$k=".urlencode($v).'&';
    }
    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
    $result = curl_exec($ch);
    //$result=simplexml_load_string($result);
    //$status=$result->returnstatus;
    // if($status == 'Success'){
        // $this->v = "发送成功！";
        // $this->error = 1;
    // }else{
        // $this->v = '发送失败！';
        // $this->error = -1;
    // }    
    //return _sendmobile($mobile,$content);
}

//发送微信中奖通知
function send_wx_shop_code_1($mobile,$company_code){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    $db=System::load_sys_class('model');					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'send_wx_shop_code'");
    
    if(!$template){
        $template = array();
        $content =  '您好！您获得的商品已发货，快递单号：'.$company_code."。请注意查收！【YG商城】";
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content = '您好！您获得的商品已发货，快递单号：'.$company_code."。请注意查收！【YG商城】";
    }else{
        if(strpos($template['value'],"0000000000") == true){
                $content= str_ireplace("0000000000",$company_code,$template['value']);
        }else{
                $content = $template['value'].$company_code;
        }
    }
    //$content ='您好！您获得的商品已发货，快递单号：'.$company_code."。请注意查收！【YG商城】";
    $mobiles = $mobiles['cfg_mobile_1'];	
    $post_data = array();
    $post_data['userid'] =  $mobiles['mid'];
    $post_data['account'] =  $mobiles['mname'];
    $post_data['password'] = $mobiles['mpass'];
    $post_data['content'] =$mobiles['mqianming'].$content; //短信内容
    $post_data['mobile'] = $mobile;
    $post_data['sendtime'] = ''; //时定时发送，输入格式YYYY-MM-DD HH:mm:ss的日期值
    $url='http://sms.qq106.cn/sms.aspx?action=send';
    $o='';
    foreach ($post_data as $k=>$v)
    {
        $o.="$k=".urlencode($v).'&';
    }
    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
    $result = curl_exec($ch);
    $result=simplexml_load_string($result);
    $status=$result->returnstatus;
    if($status == 'Success'){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败'); 
    }  
    return  $arr; 

}

/**
*	发送用户验证邮箱
*	email  @用户邮箱地址
*   uid    @用户的ID
*/
function send_email_reg($email=null,$uid=null){
    $db=System::load_sys_class('model');
    $checkcode = _getcode(10);
    $checkcode_sql = $checkcode['code'].'|'.$checkcode['time'];
    $check_code  = serialize(array("email"=>$email,"code"=>$checkcode['code'],"time"=>$checkcode['time']));
    $check_code_url  = _encrypt($check_code,"ENCODE",'',3600*24);

    $clickurl=WEB_PATH.'/member/user/emailok/'.$check_code_url;
    $db->Query("UPDATE `@#_member` SET `emailcode`='$checkcode_sql' where `uid`='$uid'");

    // $web_name = _cfg("web_name");
    // $title = _cfg("web_name")."激活注册邮箱";
    $title = $db->GetOne("select `title` from `@#_caches` where `key` = 'template_email_reg'");
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_email_reg'");
    if($template){
        if($template['type'] == 2){
            return false;
        }
    }
    $url = '<a href="';
    $url.= $clickurl.'">';
    $url.= $clickurl."</a>";
    $template['value'] = str_ireplace("{地址}",$url,$template['value']);
    return _sendemail($email,'',$title['title'],$template['value']);
}

/**
*	发送用户获奖邮箱
*	email  		@用户邮箱地址
*   uid    		@用户的ID
*	usernname	@用户名称
*	code  		@中奖号码
*   shoptitle	@商品名称
*   type	@类型1商品中奖2验证码
*/

function send_email_code($email=null,$username=null,$uid=null,$code=null,$shoptitle=null,$type=1){
	$db=System::load_sys_class('model');
    if($type == 1){
        $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_email_shop'");
        if(!$template){
                $template = array();
                $template['value'] =  "恭喜您：{$username},你在". _cfg("web_name")."云购的商品{$shoptitle}已获得,云购码是:".$code;
        }else{
            if($template['type'] == 2){
                return false;
            }
            $template['value'] = str_ireplace("{用户名}",$username,$template['value']);
            $template['value'] = str_ireplace("{商品名称}",$shoptitle,$template['value']);
            $template['value'] = str_ireplace("{中奖码}",$code,$template['value']);
        }
        $title = "恭喜您!!! 您在". _cfg("web_name")."云购的商品已获得!!!";		
    }elseif($type==2){
        $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_email_code'");
        $title = $template['title'];
        if(!$template){ 
            $template = array();
            $content =  "您好！您的验证码是：".$code;
        }	
        if(empty($template['value'])){
            $content =  "您好！您的验证码是：".$code;
        }else{
            if(strpos($template['value'],"000000") == true){
                    $content= str_ireplace("000000",$code,$template['value']);
            }else{
                    $content = $template['value'].$code;
            }
        }
        $template['value'] = $content;
    }
	return _sendemail($email,'',$title,$template['value']);
}

//发送找回密码的验证码
function send_mobile_fid_code($mobile=null){
    if(!$mobile)_message("发送验证码手机号码不能为空");	
    $db=System::load_sys_class('model');
    $checkcodes=rand(100000,999999).'|'.time();//验证码
    $db->Query("UPDATE `@#_member` SET mobilecode='$checkcodes' where `mobile`='$mobile'");				
    $checkcodes = explode("|",$checkcodes);
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_reg'");
    if(!$template){
        $content =  "你正在"._cfg("web_name")."的找回密码的短信验证码是:".strtolower($checkcodes[0]);
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "你正在"._cfg("web_name")."的找回密码的短信验证码是:".strtolower($checkcodes[0]);
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",strtolower($checkcodes[0]),$template['value']);
                $content= str_ireplace("注册","找回密码",$content);		
        }else{
                $content = "你正在"._cfg("web_name")."的找回密码的短信验证码是:".strtolower($checkcodes[0]);					
        }
    }
            
    return _sendmobile($mobile,$content);
}
/**
 *	发送用户手机短信验证码
 *	mobile @用户手机号
 */
// function send_login_code_8($mobile=null,$code=null){

//     $mobiles = System::load_sys_config("mobile");
//     if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
//     if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
//     $db=System::load_sys_class('model');
//     $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
//     $marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
//     if (in_array($mobile, $marr)){
//         $arr = array('-1','发送失败');
//         return  $arr; exit;
//     }
//     if(!$template){
//         $template = array();
//         $content =  "您好！您的验证码是：".$code;
//     }else{
//         if($template['type'] == 2){
//             return false;
//         }
//     }
//     if(empty($template['value'])){
//         $content =  "您好！您的验证码是：".$code;
//     }else{
//         if(strpos($template['value'],"000000") == true){
//             $content= str_ireplace("000000",$code,$template['value']);
//         }else{
//             $content = $template['value'].$code;
//         }
//     }
//     $mobiles = $mobiles['cfg_mobile_8'];

//     $content=$mobiles['mqianming'].$content; //短信内容
//     $sends=System::load_sys_class('sendmobile');
//     $res = $sends->Mtxcsend($mobile,$content);

//     if($res != ''){
//         $arr = array('1',"发送成功");
//     }else{
//         $arr = array('-1','发送失败');
//     }
//     return  $arr;
//     //return _sendmobile($mobile,$content);
// }

function send_login_code_8($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
	if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
	if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
	$db=System::load_sys_class('model'); 					
	$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
  	$marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
  	if (in_array($mobile, $marr)){ 
   		 $arr = array('-1','发送失败');
        return  $arr; exit;
    }	
	if(!$template){ 
		$template = array();
		$content =  "您好！您的验证码是：".$code;
	}	
	if(empty($template['value'])){
		$content =  "您好！您的验证码是：".$code;
	}else{
		if(strpos($template['value'],"000000") == true){
				$content= str_ireplace("000000",$code,$template['value']);
		}else{
				$content = $template['value'].$code;
		}
	}
	
	$config=array();

	$config['mobile']=$mobile;//发送的号码

	$config['content']= $content;//发送的内容	

	$config['ext']='';

	$config['stime']='';

	$config['rrid']='';
    		
	$sends=System::load_sys_class('sendmobile');
	$cok=$sends->init($config);

	if(!$cok){

		return array('-1','配置不正确!');

	}
	
    $sends->send();
    $sendarr=array($sends->error,$sends->v);
    return $sendarr;
}

function send_mobile_shop_code_8($mobile,$uid='',$code){
    $mobiles = System::load_sys_config("mobile");
    if(!$uid)_message("发送用户手机获奖短信,用户ID不能为空！");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model');
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");

    if(!$template){
        $template = array();
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if($template['type'] == 2){
            return false;
        }
    }
    if(empty($template['value'])){
        $content =  "您在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
    }else{
        if(strpos($template['value'],"00000000") == true){
            $content= str_ireplace("00000000",$code,$template['value']);
        }else{
            $content = $template['value'].$code;
        }
    }
    $mobiles = $mobiles['cfg_mobile_8'];

    $sends=System::load_sys_class('sendmobile');
    $res = $sends->Mtxcsend($mobile,$mobiles['mqianming'].$content);

    if($res != ''){
        $arr = array('1',"发送成功");
    }else{
        $arr = array('-1','发送失败');
    }
    return  $arr;
    //return _sendmobile($mobile,$content);
}
function new_send_template($mobile,$temp_id,$shopid = ''){

    if(empty($mobile) && empty($temp_id)){
        return array('-1','发送失败');
    }
    $db=System::load_sys_class('model');
    $user = $db->GetOne("SELECT * FROM `@#_member` where `mobile` = '$mobile'");
//    var_dump($mobile.$temp_id.$shopid);exit;

    System::load_app_fun('member','member');
    if (get_user_black($mobile,2)){
        return array('-1','手机号已被拉黑');
    }
    $sql = $db->GetOne("SELECT * FROM `@#_caches` where `id` = '$temp_id'");

    if(!$sql){
        return array('-1','模板不存在');
    }else{
        if($sql['type'] == 2){
            return array('-1','模板未启用，请联系管理员');
        }
    }
    switch ($temp_id){
        case $temp_id == 4|| $temp_id == 5 || $temp_id == 9 || $temp_id ==11  || $temp_id == 16 || $temp_id ==17 || $temp_id ==18:
            $content = str_replace('000000',$shopid,$sql['value']);
            break;
        case $temp_id == 12:
            $content = str_replace('000000',$shopid,$sql['value']);
            $content = str_replace('0000',$user['mobile'],$content);
            break;
        case $temp_id == 21 || $temp_id == 22:
            $shopinfo = $db->GetOne("SELECT `title` FROM `@#_shoplist` where `id` = '$shopid'");
            $content = str_replace('XX',$shopinfo['title'],$sql['value']);
            break;
        case $temp_id == 10 :
            $shopinfo = $db->GetOne("SELECT `title` FROM `@#_shoplist` where `id` = '$shopid'");
            $content = str_replace('0000000000',$shopid,$sql['value']);
            break;
        default:
            $content = $sql['value'];
            break;
    }
//    var_dump($mobile);exit;
    return _sendmobile($mobile,$content);
}

//登录时请求的方法
function send_login_code_9($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    $marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
    if (in_array($mobile, $marr)){ 
            $arr = array('-1','发送失败');
        return  $arr; exit;
    }	
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }	
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    
    $config=array();

    $config['mobile']=$mobile;//发送的号码

    $config['content']= $content;//发送的内容	

            
    $sends=System::load_sys_class('sendmobile');
    $cok=$sends->init($config);

    if(!$cok){

        return array('-1','配置不正确!');

    }
    
    $sends->send();
    $sendarr=array($sends->error,$sends->v);
    
    return $sendarr;
}

function send_login_code_21($mobile=null,$code=null){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    $marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
    if (in_array($mobile, $marr)){ 
            $arr = array('-1','发送失败');
        return  $arr; exit;
    }	
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }	
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    
    $config=array();

    $config['mobile']=$mobile;//发送的号码

    $config['content']= $content;//发送的内容	

            
    $sends=System::load_sys_class('sendmobile');
    $cok=$sends->init($config);

    if(!$cok){

        return array('-1','配置不正确!');

    }
    
    $sends->send();
    $sendarr=array($sends->error,$sends->v);
    
    return $sendarr;
}

function send_login_code_22($mobile,$code){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    $marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
    if (in_array($mobile, $marr)){ 
            $arr = array('-1','发送失败');
        return  $arr; exit;
    }	
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }	
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $config=array();
    $config['mobile']=$mobile;//发送的号码
    $config['content']= $content;//发送的内容	
    $sends=System::load_sys_class('sendmobile');
    $cok=$sends->init($config);
    if(!$cok){
        return array('-1','配置不正确!');
    }
    $sends->send();
    $sendarr=array($sends->error,$sends->v);
    return $sendarr;
}
function send_login_code_23($mobile,$code){
    $mobiles = System::load_sys_config("mobile");
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    $marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
    if (in_array($mobile, $marr)){ 
            $arr = array('-1','发送失败');
        return  $arr; exit;
    }	
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }	
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $config=array();
    $config['mobile']=$mobile;//发送的号码
    $config['content']= $content;//发送的内容	
    $sends=System::load_sys_class('sendmobile');
    $cok=$sends->init($config);
    if(!$cok){
        return array('-1','配置不正确!');
    }
    $sends->send();
    $sendarr=array($sends->error,$sends->v);
    return $sendarr;
}

function send_login_code($mobile,$code){
    $mobilec = System::load_sys_config("mobile");
    $key = $mobilec['cfg_mobile_on'];
    if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
    if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
    $db=System::load_sys_class('model'); 					
    // $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_login_code'");
    $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_reg'");
    $marr=array('17127290867','17124267526','13174865001','13973196533','13003317083');
    if (in_array($mobile, $marr)){ 
            $arr = array('-1','发送失败');
        return  $arr; exit;
    }	
    if(!$template){ 
        $template = array();
        $content =  "您好！您的验证码是：".$code;
    }	
    if(empty($template['value'])){
        $content =  "您好！您的验证码是：".$code;
    }else{
        if(strpos($template['value'],"000000") == true){
                $content= str_ireplace("000000",$code,$template['value']);
        }else{
                $content = $template['value'].$code;
        }
    }
    $config=array();
    $config['mobile']=$mobile;//发送的号码
    $config['content']= $content;//发送的内容	
    $sends=System::load_sys_class('sendmobile');
    $cok=$sends->init($config);
    if(!$cok){
        return array('-1','配置不正确!');
    }
    $sends->send();
    $sendarr=array($sends->error,$sends->v);
    return $sendarr;
}

?>