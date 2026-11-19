<?php

class opaopay {

	private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
		return $this->config_jsdz();

	}
	public function config_jsdz(){
		
		$config = $this->config;
		//参数
		$data = [
			'pid'=> $config['id'],//商户号
			'type' => 'alipay',//支付方式
			'out_trade_no' => $config['code'],//商户唯一订单
			'notify_url' => $config['NotifyUrl'],
			'return_url' => $config['ReturnUrl'],
			'name' => '充值',//商品名称
			'money' => $config["money"],//金额
		];

		//生成签名
		$key = $config['key'];
		$aryLink = $this->aryLink($this->arySort($data));
		$data['sign'] = $this->md5Sign($aryLink, $key);
		$data['sign_type'] = 'MD5';

		//发送请求
		$apiurl='http://nb9999.cn/submit.php';
		  
		$result = $this->createHtml($apiurl,$data);
		echo $result;
		$data['pay_status_code'] = 1;
		return $data;
	}

	public function createHtml($url,$data)
	{
		$sHtml = "<form id='paysubmit' name='paysubmit' action='".$url."' method='post'>";
		foreach ($data as $key => $v) {
			$sHtml .= "<input type='hidden' name='".$key."' value='".$v."'>";
		}
		$sHtml .= "</form>";
		$sHtml .="<script type='text/javascript'>document.getElementById('paysubmit').submit();</script>";
		return $sHtml;
	}

	public function arySort($ary){
	        ksort($ary);
	        reset($ary);
	        return $ary;
	}
	public function aryLink($ary){
	    $arg  = "";
	    while (list ($key, $val) = each ($ary)) {
	        $arg.=$key."=".$val."&";
	    }
	    $arg = substr($arg,0,@count($arg)-2);

	    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

	    return $arg;
	}
	public function md5Sign($aryLink, $key){
	    $aryLink = $aryLink.$key;
	    return md5($aryLink);
	}

}

?>