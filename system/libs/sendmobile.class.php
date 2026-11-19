<?php 

/**

*	V3.1.6	 time:2014-06-12

**/

class sendmobile {

	

	public $error = '';

	public $v = '';

	

	private $mobile;

	private $config;

	private $op;

	private $sms = [
		"1"=>"","21"=>"fan",
	];

	

	/**

	*	短信配置总入口

	*	config  @设置要发送的短信数组

	*	mobiles @短信总配置文件

	*	key 	@手动指定开启的短信接口,不指定调用配置文件

	**/

	public function init($config=null,$mobiles=null,$key=null){
		if(!$config){
			return false;
		}
		if($config['mobile']==NULL)return false;
		if($config['content']==NULL)return false;
		$this->config = $config;
		if(!$mobiles){
			$this->mobile = System::load_sys_config('mobile');
		}
		if(intval($key) && isset($this->mobile['cfg_mobile_'.$key]) && method_exists($this,"cfg_seting_".$key)){
			$op = $key;
			$func = "cfg_seting_".$key;		
		}else{
			$op = $this->mobile['cfg_mobile_on'];
			$func = "cfg_seting_".$this->mobile['cfg_mobile_on'];		
		}
		if(!method_exists($this,$func)){
			return ;
		}
		$this->op = $op; 
		return $this->$func();	
	}

	

	

	

	/**

	*	总发送入口	
	**/	

	public function send(){
		$func = "cfg_send_".$this->op;
		return $this->$func();
	}

	/*郑州商讯短信配置设置*/

	private function cfg_seting_1(){

		return true;

	}

	

	/*郑州商讯短信发送*/

	private function cfg_send_1(){
		$mobile = $this->mobile['cfg_mobile_1'];	
		$mid = urlencode($mobile['mid']);
		$mname = $mobile['mname'];
		$pwd  = $mobile['mpass'];
		$qianming =$mobile['mqianming'];
		$haoma = $this->config['mobile'];

		$post_data = array();
		$post_data['userid'] = $mid;
		$post_data['account'] = $mname;
		$post_data['password'] = $pwd;
		$post_data['content'] =$qianming.$this->config['content']; //短信内容
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
     	//file_put_contents('err.txt', '$result---'.var_export($result,TRUE)."\n", FILE_APPEND);
		$result=simplexml_load_string($result);
		$status=$result->returnstatus;
    
		if($status == 'Success'){
			$this->v = "发送成功！";
			$this->error = 1;
		}else{
			$this->v = '发送失败！';
			$this->error = -1;
		}

	}

	

	/*郑州商讯短信其他操作*/

	public function cfg_getdata_1(){
		
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_1'];	
		$mid = urlencode($mobile['mid']);
		$mname = $mobile['mname'];
		$pwd  = $mobile['mpass'];
		$date=date("YmdHis",time());
		$post_data = array();
		$post_data['userid'] = $mid;
		$post_data['timestamp'] = $date;
		$post_data['sign'] =strtolower(md5($mname.$pwd.$date));
		$url='http://47.96.152.191:8088/v2sms.aspx?action=overage';
		$o='';
		foreach ($post_data as $k=>$v)
		{
			$o.="$k=".$v.'&';
		}
	
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$result = curl_exec($ch);
      	//file_put_contents('err.txt', '$post_data---'.var_export($post_data,TRUE)."\n", FILE_APPEND);
      	//file_put_contents('err.txt', '$result---'.var_export($result,TRUE)."\n", FILE_APPEND);
		$result=simplexml_load_string($result);
		$status=$result->returnstatus;
        

		if($status == 'Sucess'){
			$this->v = $arr['transactional_balance'] + $result->overage;
		}else{
			$this->error = $result->message;
		}
		return $arr;	

	}
	

	/**********************************************************/

	/**********************************************************/

	/**********************************************************/

	
	/**
	 * 企信通短信配置设置 appid=23434&signature=2d51fafb8930f0e79a5324a2c7d6a7cc
	 */
	private function cfg_seting_4(){
		return true;
	}
	public function cfg_getdata_4(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_4'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];

		$post_data = array();
		$post_data['username'] = $name;
		//$post_data['signature'] = $pwd;
		$post_data['tkey'] 	= date('YmdHis');
		$post_data['password'] = md5(md5($pwd) . $post_data['tkey']);
		$url='http://www.ztsms.cn/balanceN.do';
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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		//$arr = json_decode($result,true);
		if($result == '-1'){
				$this->error = '用户名或者密码不正确';
		}else if($result == '-2'){
				$this->error = 'tkey不正确';
		}else if($result == '-3'){
				$this->error = '用户不存在或用户停用';
		}else{
			$this->v = $arr['transactional_balance'] + $result;

		}
	

		return $arr;
	}

	public function cfg_send_4(){
		$mobile = $this->mobile['cfg_mobile_4'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];
		$haoma = $this->config['mobile'];
		$data['username'] = $name;
		$data['tkey'] 	= date('YmdHis');
		$data['password'] = md5(md5($pwd) . $data['tkey']);
		$data['mobile']=$haoma;
		$data['content']= $this->config['content'].$mobile['mqianming'];
		$data['productid']=$mobile['productid'];
		$data['xh']='';
		$url='http://www.ztsms.cn/sendNSms.do';

		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在  
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); // Post提交的数据包
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$res = curl_exec($curl); // 执行操作
		if (curl_errno($curl)) {
			echo 'Errno:'.curl_error($curl);//捕抓异常
		}
		curl_close($curl); // 关闭CURL会话
		//$arr=json_decode($res,true);
		$arr=explode(',',$res);
		if($arr['0']=='1'){
			$this->v = "发送成功！";
			$this->error = 1;
		}else{
			$this->v = '发送失败！';
			$this->error = -1;
		}
		return $arr;
	}

	/**
	 * 创蓝短信配置设置 
	 */
	private function cfg_seting_5(){
		return true;
	}
	public function cfg_getdata_5(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_5'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];
		$postArr = array (
			'account'  =>  $name,
			'password' => $pwd
        );
		$url='http://intapi.253.com/balance/json?';
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
			$this->v = $ret;
			$this->error = -1;
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 !== $rsp) {
				$this->v = "请求状态 ". $rsp . " " . curl_error($ch);
				$this->error = -1;
            } else {
					$output=json_decode($ret,true);
					if(isset($output['code'])  && $output['code']=='0'){
						$this->v = $output['balance'];
						$this->error = 1;
					}else{
						$this->v = $output['error'];
						$this->error = -1;
					}
            }
        }
	}
	

	public function cfg_send_5(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_5'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];
		$haoma = $this->config['mobile'];
      	$cid=$mobile['cid'];
		$postArr = array (
			'account'  =>  $name,
			'password' => $pwd,
			'msg' => $this->config['content'],
			'mobile' =>$cid.$haoma
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
			$this->v = $ret;
			$this->error = -1;
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 !== $rsp) {
				$this->v = "请求状态 ". $rsp . " " . curl_error($ch);
				$this->error = -1;
            } else {
					$output=json_decode($ret,true);
					if(isset($output['code'])  && $output['code']=='0'){
						$this->v = "发送成功！";
						$this->error = 1;
					}else{
						$this->v = $output['error'];
						$this->error = -1;
					}
            }
        }
		curl_close ( $ch );
	}

	// 享宇短信配置设置
	private function cfg_seting_7(){
		return true;
	}
	public function cfg_getdata_7(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_7'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];
		$postArr = array (
			'CorpID'  =>  $name,
			'Pwd' => $pwd,
        );
		$url='https://sms.xycredit.com.cn/ws/SelSum.aspx';
// 		$postFields = http_build_query($postArr);
		
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_HEADER, 0);//不抓取头部信息。只返回数据

        curl_setopt($curl, CURLOPT_TIMEOUT,1000);//超时设置

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//1表示不返回bool值

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));//重点

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));

        $ret = curl_exec($curl);
        if (false == $ret) {
			$this->v = $result;
			$this->error = -1;
        } else {
            $rsp = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
            if (200 !== $rsp) {
				$this->v = "请求状态 ". $rsp . " " . curl_error($curl);
				$this->error = -1;
            } else {
					$output=json_decode($ret,true);
				
					if($output >= 0){
						$this->v = $output;
						$this->error = 1;
					}else{
					    $arrError = array('-1'=>'账号未注册','-2'=>'其他错误','-3'=>'帐号或密码错误','-100'=>'IP黑名单','-101'=>'调用接口速度太快（大于30s）','-102'=>'账号黑名单','-103'=>'IP未导白');
						$this->v = $output['error'] = $output;
						$this->error = $arrError[$output];
					}
            }
        }
	}
	

	public function cfg_send_7(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_7'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];
		$haoma = $this->config['mobile'];
		$sign = $mobile['mqianming'];
      	$cid=$mobile['cid'];
		$postArr = array (
			'CorpID'  =>  $name,
			'Pwd' => $pwd,
			'Content' =>$this->config['content'].$sign,
			'Mobile' =>$cid.$haoma,
			'Cell'=>'',
			'SendTime'=>''
        );
        
		$url='http://sms.xycredit.com.cn/utf8/BatchSend2.aspx';
      
// 		$postFields = json_encode($postArr);
		$curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_HEADER, 0);//不抓取头部信息。只返回数据

        curl_setopt($curl, CURLOPT_TIMEOUT,1000);//超时设置

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//1表示不返回bool值

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));//重点

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));

        $ret = curl_exec($curl);
        
        if (false == $ret) {
			$this->v = $result;
			$this->error = -1;
        } else {
            $rsp = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
            
            if (200 !== $rsp) {
				$this->v = "请求状态 ". $rsp . " " . curl_error($curl);
				$this->error = -1;
            } else {
					$output=json_decode($ret,true);
					if($output>0){
						$this->v = "发送成功！";
						$this->error = 1;
					}else{
					    $arrError = array('-1'=>'账号未注册','-2'=>'其他错误','-3'=>'帐号或密码错误','-5'=>'余额不足，请充值','-6'=>'定时发送时间不是有效的时间格式','-7'=>'提交信息末尾未加签名，请添加中文的企业签名【 】');
						$this->v = $arrError[$ret];
						$this->error =-1;
					}
            }
        }
		curl_close ( $ch );
	}

	

		

	/**********************************************************/

	/**********************************************************/

	/**********************************************************/

	

	

	/*短信宝短信配置设置*/

	private function cfg_seting_2(){

		$mobile = $this->mobile['cfg_mobile_2'];

		$this->config['content'] = $this->config['content'].$mobile['mqianming'];

		return true;

	}

	

	/*短信宝短信发送*/

	private function cfg_send_2(){
		$mobile = $this->mobile['cfg_mobile_2'];
		$config = $this->config;
		$url = "http://www.smsbao.com/sms?u=".$mobile["mid"]."&p=".md5($mobile["mpass"])."&m=".$config["mobile"]."&c=".$config['content'];
		$ch2 = curl_init($url);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		$html = curl_exec($ch2);
		$errno = curl_errno($ch2);
		curl_close($ch2);
		if($html>0){
			$errorArray = array(30=>"密码错误",40=>"账号不存在",41=>"余额不足",42=>"帐号过期",43=>"IP地址限制",50=>"内容含有敏感词",51=>"手机号码不正确");
			$error = $errorArray[$html];
			if(empty($error)){
				$error = "发送失败";
			}
			$this->v=$error;
			$this->error=-1;
		}else{
			$this->v="发送成功";
			$this->error=1;
		}
	}

	

	/*短信宝短信其他操作*/

	public function cfg_getdata_2(){
		$this->mobile = System::load_sys_config("mobile");
		$flag = 0; 
		$mobile = $this->mobile['cfg_mobile_2'];	
		if($mobile['mid']==null || $mobile['mpass']==null){
			$this->error=-2;
			$this->v="短信账户或者密码不能为空!";
			return;
		}
		$url = "http://www.smsbao.com/query?u=".$mobile["mid"]."&p=".md5($mobile["mpass"]);
		$ch2 = curl_init($url);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		$html = curl_exec($ch2);
		curl_close($ch2);
		$htmls = split(",",$html);	
		$this->v=$htmls[1];
		$this->error=1;
	 	return array($this->error,$this->v);
}

	

		

	/**********************************************************/

	/**********************************************************/

	/**********************************************************/

	

	

	/*互亿无线短信配置设置*/

	private function cfg_seting_3(){

		return true;

	}

	

	/*互亿无线短信发送*/

	private function cfg_send_3($post_data=null,$target=null,$get_key=null){	

		//cf_tlwl

		//BPPKNes	

		$config = $this->config;

		

		$account = $this->mobile['cfg_mobile_3']['mid'];

		$password = $this->mobile['cfg_mobile_3']['mpass'];

		

		//"您的验证码是：9707。请不要把验证码泄露给其他人。"

		$config['content'] = rawurlencode($config['content']);		

		/*发送短信*/

		if(!$get_key){

			$post_data = "account={$account}&password={$password}&mobile=".$config['mobile']."&content=".$config['content'];

			$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";		

		}

			

		

		

		/*curl*/

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $target);

		curl_setopt($curl, CURLOPT_HEADER, false);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_NOBODY, true);

		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

		$return_str = curl_exec($curl);

		curl_close($curl);
	 
		/*curl*/

		

	

		/*xml*/

				$arr = _xml_to_array($return_str);

		/*xml*/

	

		if($get_key){	

			$this->error = $arr['GetNumResult']['code'];

			$this->v = $arr['GetNumResult']['num'];

			return $arr;		

		}

	

	

		/*成功*/

		if($arr['SubmitResult']['code']==2){

			$this->v = $arr['SubmitResult']['msg'];

			$this->error = 1;

		}else{

			$this->v = $arr['SubmitResult']['msg'];

			$this->error = -1;

		}		

		return $arr;

	}

	

	/*互亿无线短信其他操作*/

	public function cfg_getdata_3(){

		

		/*获取条数*/

		$this->mobile = System::load_sys_config("mobile");

		

		$account = $this->mobile['cfg_mobile_3']['mid'];

		$password = $this->mobile['cfg_mobile_3']['mpass'];

		

		$post_data = "account={$account}&password={$password}";

		$target = "http://106.ihuyi.cn/webservice/sms.php?method=GetNum";		

		return	$this->cfg_send_3($post_data,$target,true);		

	}

	

	

	

	/* 郑州商讯短信内容检测 */

	private function mobile_con_check($content=null){

		$this->mobile = $mobile = System::load_sys_config('mobile');

		$mobile = $this->mobile['cfg_mobile_1'];	

		$name = urlencode($mobile['mid']);

		$pwd  = $mobile['mpass'];

		$content = iconv( "UTF-8", "gb2312//IGNORE" ,$content);

		$content = urlencode($content);	

		

		$con_check=fopen("http://www.139000.com/send/checkcontent.asp?name=$name&pwd=$pwd&content=$content","r");

		if(!$con_check){

			fclose($con_check);				

		}

		

		$rets = '';

		while (!feof($con_check)) {				

			$rets .= fgets($con_check,1024);				

		}

					

		if($rets){

				$rets = $this->exp_url($rets);

				if($rets['errid']=='0'){

					return array("1","新短信接口内容合法");

				}else{

					return array("-1","内容检测失败,不能包含:【".$rets['err'].'】');

				}

		}else{

			return array("-1","检测失败");

		}

	

	}

	

	/*URL转数组*/

	private function exp_url($url=''){

		if(!empty($url)){

			$ret = iconv("GB2312","UTF-8",$url);

			$ret = explode("&",$ret);

				foreach($ret as $k=>$v){

					$v = explode("=",$v);

					$ret[$v[0]] = $v[1];

				}

			return $ret;

		}else{

			return false;

		}

		

	}

	//新添加sms
    private function cfg_seting_8(){
        return true;
    }
    public function cfg_getdata_8(){

    }

    // public function cfg_send_8(){
    //     $mobile = $this->mobile['cfg_mobile_8'];
    //     $haoma = $this->config['mobile'];

    //     $content = $mobile['mqianming'].$this->config['content']; //短信内容需要用urlencode编码下
    //     $result = $this->Mtxcsend($haoma,$content);

    //     if($result==null)
    //     {
    //         $this->v = $arr['returnsms']['message'];
    //         $this->error = -1;
    //     }
    //     else
    //     {
    //         //print_r($result);
    //         // echo "返回信息提示：",$result->Description,"<br>";
    //         // echo "返回状态为：",$result->StatusCode,"<br>";
    //         // echo "返回余额：",$result->Amount,"<br>";
    //         // echo "返回本次任务ID：",$result->MsgId,"<br>";
    //         // echo "返回成功短信数：",$result->SuccessCounts,"<br>";
    //         $this->v = "发送成功！";
    //         $this->error = 1;
    //     }

    //     return $arr;
    // }
    
    public function cfg_send_8(){
        $this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_8'];	
		$name = urlencode($mobile['mid']);
		$pwd  = $mobile['mpass'];
		$haoma = $this->config['mobile'];
		$sign = $mobile['mqianming'];
		$mname = $mobile['mname'];
      	// $cid=$mobile['cid'];
      	$time=date('YmdHis');
      	$signmd5 =md5($mname.$pwd.$time);
      	
        //新配置的
        $postArr = array(
            'userid'=>$name,
            'account'=>$mname,
            'password'=>$pwd,
            'mobile'=>$haoma,
            'content'=>$sign.$this->config['content'],
            'sendTime'=>'',
            'action'=>'send',
            'extno'=>'',
            'rt'=>'json'
            );
        $response = $this->limitTexting($haoma);
        if($response['code'] == 2){
            $this->v = $response['msg'];
			$this->error = -1;
			return ;
        }
		
		//新配置的请求路径
		$url = "http://121.89.199.127:8888/sms.aspx?action=send";

// 		$postFields = json_encode($postArr);
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);//不抓取头部信息。只返回数据
        curl_setopt($curl, CURLOPT_TIMEOUT,1000);//超时设置
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//1表示不返回bool值
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));//重点
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));

        $ret = curl_exec($curl);
        if (false == $ret) {
			$this->v = $result;
			$this->error = -1;
        } else {
            $rsp = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
            
            if (200 !== $rsp) {
				$this->v = "请求状态 ". $rsp . " " . curl_error($curl);
				$this->error = -1;
            } else {
				$output=json_decode($ret,true);
		
				if($output['ReturnStatus']=="Success"){
					$this->v = "发送成功！";
					$this->error = 1;
				}else{
				    
					$this->v = $output['Message'];
					$this->error =-1;
				}
            }
        }
		curl_close ( $curl );
    }

    public function Mtxcsend($mobiles,$content,$extnumber='',$plansendtime=null)
    {
        try{
            $mobile = System::load_sys_config("mobile");
//            var_dump($mobile);exit;
            $username = $mobile['cfg_mobile_8']['mid'];
            $password = $mobile['cfg_mobile_8']['mpass'];

            $wsdl="http://47.92.214.24/webservice.asmx?wsdl";
            $client=new SoapClient($wsdl);
            $sms=array(
                'Msisdns'=>$mobiles,
                'SMSContent'=>$content,
                'ExtNumber'=>$extnumber,
            );
            if($plansendtime!=null && $plansendtime!=''){
                $sms['PlanSendTime']=$plansendtime;
            }
            $body=array(
                'userName'=>$username,
                'password'=>$password,
                'sms'=>$sms
            );
            $result=$client->__call("SendSms", array($body));
        }catch(Exception $e){
            print_r($e);
        }
        //$client->__soapCall("SendSms", array($body));
        if(is_soap_fault($result))
        {
            echo "faultcode:",$result->faultcode,"faultstring:",$result->faultstring;
            return null;
        }
        else
        {
            $data=$result->SendSmsResult;
//            var_dump($data);exit;
            return $data;
        }
    }
    
    	/**
         * 企信通
         * 
         **/
        //
	private function cfg_seting_9(){
		return true;
	}
	
	
	
	

// 	//企信通短信发送
// 	public function cfg_send_9(){
// 		$this->mobile = System::load_sys_config("mobile");
// 		$mobile = $this->mobile['cfg_mobile_9'];	
// 		$name = urlencode($mobile['mid']);
// 		$pwd  = $mobile['mpass'];
// 		$haoma = $this->config['mobile'];
// 		$sign = $mobile['mqianming'];
// 		$mname = $mobile['mname'];
//       	$cid=$mobile['cid'];
//       	$time=date('YmdHis');
//       	$signmd5 =md5($mname.$pwd.$time);

//         // //测试用的
//         // $postArr = array(
//         //     'userid'=>20348,
//         //     'account'=>'GS001',
//         //     'password'=>'REDACTED_CONFIGURE_LOCALLY',
//         //     'mobile'=>$haoma,
//         //     'content'=>'【汇易购】'.$this->config['content'],
//         //     'sendTime'=>'',
//         //     'action'=>'send',
//         //     'extno'=>'',
//         //     'rt'=>'json'
//         //     );

//         //新配置的
//         $postArr = array(
//             'userid'=>$name,
//             'account'=>$mname,
//             'password'=>$pwd,
//             'mobile'=>$haoma,
//             'content'=>$sign.$this->config['content'],
//             'sendTime'=>'',
//             'action'=>'send',
//             'extno'=>'',
//             'rt'=>'json'
//             );
            
//         $response = $this->limitTexting($haoma);
//         if($response['code'] == 2){
//             $this->v = $response['msg'];
// 			$this->error = -1;
// 			return ;
//         }
// 		//$url='http://121.89.199.127:8888/v2sms.aspx?action=send';
		
// 		//新配置的请求路径
// 		$url = "http://120.77.14.55:8888/sms.aspx?action=send";
      
// // 		$postFields = json_encode($postArr);
// 		$curl = curl_init();
//         curl_setopt($curl, CURLOPT_URL, $url);
//         curl_setopt($curl, CURLOPT_HEADER, 0);//不抓取头部信息。只返回数据
//         curl_setopt($curl, CURLOPT_TIMEOUT,1000);//超时设置
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//1表示不返回bool值
//         curl_setopt($curl, CURLOPT_POST, 1);
//         curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));//重点
//         curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));

//         $ret = curl_exec($curl);

//         if (false == $ret) {
// 			$this->v = $result;
// 			$this->error = -1;
//         } else {
//             $rsp = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
            
//             if (200 !== $rsp) {
// 				$this->v = "请求状态 ". $rsp . " " . curl_error($curl);
// 				$this->error = -1;
//             } else {
// 				$output=json_decode($ret,true);
		
// 				if($output['ReturnStatus']=="Success"){
// 					$this->v = "发送成功！";
// 					$this->error = 1;
// 				}else{
				    
// 					$this->v = $output['Message'];
// 					$this->error =-1;
// 				}
//             }
//         }
// 		curl_close ( $ch );
// 	}

    	//企信通短信发送
	public function cfg_send_9(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_9'];
		
// 		$name = urlencode($mobile['mid']);//短信id
		
		$pwd  = $mobile['mpass'];//密码
		
		$haoma = $this->config['mobile'];//号码
		
		$sign = $mobile['mqianming'];//商城签名
		
		$mname = $mobile['mname'];//用户名
		
      	$microtime = microtime(true);
        $milliseconds = (int)($microtime * 1000);//毫秒级时间戳
    
      	$content = $sign.$this->config['content'];//发送内容
      	
      	$signmd5 =md5($mname.$milliseconds.md5($pwd));//短信签名

        //新配置的
        $postArr = array(
            'userName'=>$mname,//短信用户名
            "content"=>$content,//发送内容
            "phoneList"=>["$haoma"],
            "timestamp"=>$milliseconds,//毫秒级时间戳
            "sign"=>$signmd5,//短信签名
            );
            
        $response = $this->limitTexting($haoma);
        if($response['code'] == 2){
            $this->v = $response['msg'];
			$this->error = -1;
			return ;
        }
		
		//新配置的请求路径
		$url = "http://47.106.170.137:8001/sms/api/sendMessageMass";
    
		$postFields = json_encode($postArr);
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);//不抓取头部信息。只返回数据
        curl_setopt($curl, CURLOPT_TIMEOUT,1000);//超时设置
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//1表示不返回bool值
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array(//设置请求头
            'Accept: application/json',
            'Content-Type: application/json;charset=utf-8'
        ));//重点
        
        curl_setopt($curl, CURLOPT_POSTFIELDS,$postFields);

        $ret = curl_exec($curl);

        if (false == $ret) {
			$this->v = $ret;
			$this->error = -1;
        } else {
            $rsp = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
            
            if (200 !== $rsp) {
				$this->v = "请求状态 ". $rsp . " " . curl_error($curl);
				$this->error = -1;
            } else {
				$output=json_decode($ret,true);
		
				if($output['code']==0){
					$this->v = "发送成功！";
					$this->error = 1;
				}else{
				    
					$this->v = $output['message'];
					$this->error =-1;
				}
            }
        }
		curl_close ( $ch );
	}

	private function cfg_seting_21(){
		return true;
	}
	//短信帆
	public function cfg_send_21(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_21'];	
		$clientId  = $mobile['cid'];
		$senderId  = $mobile['mid'];
		$clientSecret  = $mobile['mpass'];
		$mqianming = $mobile['mqianming'];
		if(strpos($mqianming,"【") === false || strpos($mqianming,"】") === false){
			$this->v = "签名格式不正确";
            $this->error = -1;
            return ;
		}
		// $code=$mobile['code'];
		$code = '';
		$haoma = $this->config['mobile'];
		$postArr = array (
			'clientId'  =>  $clientId,
			'senderId' => $senderId,
			'clientSecret' => $clientSecret,
			'numbers' =>$code.$haoma,
			'contents' => $mqianming.$this->config['content'],
        );
		$url='https://www.sms.fan/send-message';
		$str = "";
		foreach($postArr as $key=>$vl){
			if($str){$str .="&";}
			$str .= $key . "=" . $vl;
		}
		$url = $url ."?". $str;
		$url = str_replace(" ","%20",$url);
		$ret = getCurl($url);
		if (!$ret) {
			$this->v = "false";
			$this->error = -1;
		} else {
			$output=json_decode($ret,true);
			if(isset($output['status'])  && $output['status']=='200'){
				$this->v = "发送成功！";
				$this->error = 1;
			}else{
				$this->v = $output['content'];
				$this->error = -1;
			}
		}
	}
	private function cfg_seting_22(){
		return true;
	}
	//two
	public function cfg_send_22(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_22'];	
		$clientId  = $mobile['cid'];
		$from  = $mobile['mid'];
		$clientSecret  = $mobile['mpass'];
		$mqianming = $mobile['mqianming'];
		if(strpos($mqianming,"【") === false || strpos($mqianming,"】") === false){
			$this->v = "签名格式不正确";
            $this->error = -1;
            return ;
		}
		$code=$mobile['code'];
		$haoma = $this->config['mobile'];
		include "sms/twilio.php";
		$twilio = new Twilio($clientId,$clientSecret,$from);
		$ret = $twilio->send($code.$haoma,$mqianming.$this->config['content']);
		var_export($ret);
		$this->v = "发送成功！";
		$this->error = 1;
		if ($ret[0]!=1) {
			$this->v = $ret[1];
			$this->error = -1;
		} else {
			$this->v = "发送成功！";
			$this->error = 1;
		}
	}

	private function cfg_seting_23(){
		return true;
	}
	//颂量
	public function cfg_send_23(){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_23'];
		include "sms/itniotech.php";
		$appid  = $mobile['appid'];
		$appkey  = $mobile['appkey'];
		$appsecret  = $mobile['appsecret'];
		$mqianming = $mobile['mqianming'];
		$code=$mobile['code'];
		$haoma = $this->config['mobile'];
		$sms = new itniotech($appkey,$appsecret,$appid);
		$ret = $sms->send($code.$haoma,$mqianming.$this->config['content']);
		if($ret){
			if($ret['status'] == 0){
				$this->v = "发送成功！";
				$this->error = 1;
			}else{
				$this->v = $ret['reason'];
				$this->error = -1;
			}
		}else{
			$this->v = "发送失败！";
			$this->error = -1;
		}
	}
    /*******************限制短信发送*************************/
    
    public function limitTexting($mobile=''){
        return ['code'=>1,'msg'=>'ok'];
        //次数限制
        $num = 20;
        
        System::load_sys_fun('redisData');
        $redis = $this->redis = new redisData;
        $key = $mobile . "limitTexting";
        $keyLock = $mobile . "limitTextingLock";
        
        $redisDataLock = $redis->redisGet($keyLock)['data'];
        
        $redisData = $redis->redisGet($key)['data'];
        if(empty($redisDataLock)){
            $start_time = strtotime(date('Y-m-d'));
            $end_time = $start_time + 86399;
            $redisTime = $end_time - time();
            
            $redis->redisDel($key);
            $redis->redisSetex($keyLock,$redisTime,1);
            $redis->redisSet($key,1);
            
            return ['code'=>1,'msg'=>'ok'];
        }else{
            if($redisData >= $num){
                return ['code'=>2,'msg'=>'今日短信发送超过20条'];
            }else{
                $redis->redisSet($key,$redisData+1);
                return ['code'=>1,'msg'=>'ok'];
            }
        }
        
    }
    
    
    
    
    
    
    public function get_sms_total($key){
		$this->mobile = System::load_sys_config("mobile");
		$mobile = $this->mobile['cfg_mobile_'.$key];
		if(!isset($this->sms[$key])){
			return ["status"=>false];
		}
		$file = $this->sms[$key];
		include "sms/".$file.".php";
		$res = balance($mobile);
		$res['status'] = true;
		return $res;
	}
    
    
    
    
    
    
    

}