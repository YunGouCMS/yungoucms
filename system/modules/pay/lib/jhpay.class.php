<?php 

class jhpay {
 
	private $config;
	/**
	*	支付入口
	**/
	
	public function config($config=null){
			$this->config = $config;
			$this->config_jsdz();
	}
	
	public function config_jsdz(){
			$config = $this->config;
			$alipay		= $config['alipay'];
			//	商户订单号,选填.
			//若不为""，提交的订单号必须在自身账户交易中唯一;为""时，码上付会自动生成随机的商户订单号.
			$OrderID					=	$config['u_id'].$config['code'];

			//	支付金额,必填.
			//单位:元，精确到分.
			$amount						=   $config['money'];

			//	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
			$p8_Url						= 	$config['NotifyUrl'];
			$type=''; // 901微信h5 902微信扫码 903支付宝扫码 904 支付宝手机
			if($config['type'] == 1){
				$type='alipay';
			}elseif($config['type'] == 2){
				$type='wxpay';
			}elseif($config['type'] == 3){
				$type='qqpay';
			}else{
				$type='alipay';
			}
			//error_reporting(0);
			ini_set("display_errors","0");
			error_reporting(E_ALL);
			//header("Content-type: text/html; charset=utf-8");
			$pay_memberid =  $config['id'];   //商户ID
			$pay_orderid = $OrderID;    //订单号
			$pay_amount =  $amount;    //交易金额
     		// $pay_amount = '0.01';
			$pay_bankcode = $type;   //银行编码
			if(empty($pay_memberid)||empty($pay_amount)||empty($pay_bankcode)){
				die("信息不完整！");
			}
			$pay_applydate = date("Y-m-d H:i:s");  //订单时间
			$pay_notifyurl = $config['NotifyUrl'];   //服务端返回地址
			$pay_callbackurl = G_WEB_PATH.'/mobile/home/userbalance/';  //页面跳转返回地址
			$Md5key = $config['key'];  //密钥
// 			$tjurl = "https://m.szlxzx.cn/submit.php";  //提交地址
// 			$tjurl = "http://q.8svip.cn/submit.php"; 
// $tjurl = "http://q.iyunnet.cn/submit.php"; 
// $tjurl = "http://y.8svip.cn/submit.php"; 
$tjurl = "http://y.vruu.cn/submit.php"; 
			//扫码
            $native = array(
                    "pid" => $pay_memberid,
                    "type" =>$type,
                    "notify_url"	=> $pay_notifyurl,
                    "return_url"	=> $pay_callbackurl,
                    "out_trade_no"	=> $pay_orderid,
                    "name"	=> "商品",
                    "money"	=> $pay_amount,
                    "sitename"	=> '用户充值'
            );
      //var_dump( $native);die;
            $para_filter = array();
            while (list ($key, $val) = each ($native)) {
                if($key == "sign" || $key == "sign_type" || $val == "")continue;
                else	$para_filter[$key] = $native[$key];
            }      
            ksort($para_filter);
            reset($para_filter);
	$arg  = "";
	while (list ($key, $val) = each ($para_filter)) {
		$arg.=$key."=".$val."&";
	}
    $arg = substr($arg,0,count($arg)-2);
    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	$prestr = $arg . $Md5key;
	$sign = md5($prestr);

     		 $native["sign"] = $sign;
      		 $native["sign_type"] = 'MD5';
			//$native['pay_attach'] = "recharge";
			//$native['pay_productname'] ='用户充值';
      
			$this->sendHttpPost($tjurl,$native);

		}
	/** post跳转方式发起请求
	 * @param  string $tjurl
	 * @param  array $arraystr
	 */
	function sendHttpPost($tjurl, $arraystr)
	{
		$str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '" >';
		foreach ($arraystr as $key => $val) {
			$str .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
		}
		$str .= '</form>';
		$str .= '<script>';
		$str .= 'document.Form1.submit();';
		$str .= '</script>';
		exit($str);

	}
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);
		
		$mysign = md5Sign($prestr, $this->alipay_config['key']);

		return $mysign;
	}
/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	return $arg;
}
  function md5Sign($prestr, $key) {
	$prestr = $prestr . $key;
	return md5($prestr);
}
}
?>