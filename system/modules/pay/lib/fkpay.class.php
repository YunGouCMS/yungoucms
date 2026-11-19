<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
require_once("fkpay/epay_submit.class.php");
class fkpay {
	private $db;
	public $pay_type_bank = false;
	public $pay_method = 'fkpay';

	public $scookie = null;
	public $fufen = 0;
	public $fufen_to_money = 0;
    public $lg = '';

	public function config($config){
		$configData = $config['data'];
		//商户ID
		$alipay_config['partner']		= $configData['id']['val'];
		//商户KEY
		$alipay_config['key']			= $configData['key']['val'];
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'https';
		//支付API地址
		$alipay_config['apiurl']    = 'https://pay.zjw88888.shop/';
		$content_type = 'text';
		// $url = "/submit.php?_input_charset=UTF-8";
		// $url = $configData['url']['val'].$url;
		$params = array(
			"pid" => $configData['id']['val'],
			"type" => $configData['code']['val'],
			"notify_url"	=> $config['NotifyUrl'],
			"return_url"	=> $config['ReturnUrl'],
			"out_trade_no"	=> $config['code'],
			"name"	=> $config['zxgm'],
			"money"	=> $config['money'],
			"sitename"	=> $config['shouname'],
			"content_type"	=> $content_type,
		);
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html = $alipaySubmit->buildRequestForm($params);
		$html['code'] = 1;
		$html['msg'] = "success";
		$html['url'] = G_WEB_PATH."/index.php/pay/fkpay_url/order_query?code=".$config['code'];
		echo json_encode($html);die;
		// $html = $this->http_post($info['url'],$info['data']);
		echo json_encode(array('code'=>1,'msg'=>'success','html'=>$html));
	}

	public function config1($config){
		$configData = $config['data'];
		$url = "/ty/orderPay";
		$url = "/submit.php?_input_charset=UTF-8";
		$url = $configData['url']['val'].$url;
		// $params = [
		// 	"mer_no" => $configData['id']['val'],
		// 	"mer_order_no" => $config['code'],
		// 	"pname" => $config['shouname'],
		// 	"pemail" => '655827666@qq.com',
		// 	"phone" => '13155555555',
		// 	"order_amount" => $config['money'],
		// 	"countryCode" => "VNM",
		// 	"ccy_no" => "VND",
		// 	"busi_code" => 'MoMoPay',
		// 	"goods" => $config['shouname'],
		// 	"notifyUrl" => $config['NotifyUrl'],
		// ];
		$params = array(
			"pid" => $configData['id']['val'],
			"type" => $configData['code']['val'],
			"notify_url"	=> $config['NotifyUrl'],
			"return_url"	=> $config['ReturnUrl'],
			"out_trade_no"	=> $config['code'],
			"name"	=> $config['zxgm'],
			"money"	=> $config['money'],
			"sitename"	=> $config['shouname'],
			"content_type"	=> "text",
	);
		// if($configData['pay_method']['val']){
		// 	$params['busi_code'] = $configData['pay_method']['val'];
		// }
		$params['sign'] = $this->checkSign($params,$configData['key']['val']);
		// $result = $this->GetData($url,json_encode($params));
		$result = $this->curlPost($url,$params);
		$json = json_decode($result,true);
		header("location:".$json['order_data']);
	}

	public function daifu($id){
		$this->db=System::load_sys_class('model');
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'fkpay' and `pay_start` = '1'");
		$pay_type['pay_key'] = unserialize($pay_type['pay_key']);
		$dingdancode = pay_get_dingdan_code('D');
		$audsta=$this->db->GetOne("SELECT * FROM `@#_member_cashout` WHERE `id`='$id'");
		$this->db->Query("UPDATE `@#_member_cashout` SET `code`='$dingdancode' where `id`=$id"); 
		// print_r($config);		
		$url = "http://sujary.fakgt.com/withdraw/singleOrder ";
		$url = "https://mtawn.ldufs.com/withdraw/singleOrder";
		$params = [
			"mer_no" => $pay_type['pay_key']['id']['val'],
			"mer_order_no" => $dingdancode,
			"acc_no" => $audsta['banknumber'],
			"acc_name" => $audsta['username'],
			"ccy_no" => 'VND',
			"order_amount" => $audsta['money'],
			"bank_code" => $audsta['bankname'],
			"notifyUrl" => G_WEB_PATH.'/index.php/pay/'.$pay_type['pay_class'].'_url/daifu',
			"summary" => $audsta['username']
		];
		$params['sign'] = $this->checkSign($params,$pay_type['pay_key']['key']['val']);
		// print_r($params);
		$result = $this->GetData($url,json_encode($params));
		$json = json_decode($result,true);
		// print_r($json);
		// var_dump($result);
		return $json;
	}
    private function curlPost($url,$params,$type=1){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		if($type!=1){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		}else{
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$resultJson = curl_exec($ch);
		$error = curl_error($ch);
		$error_code = curl_errno($ch);
		var_export([$error,$error_code]);
		curl_close($ch);
		return json_decode( $resultJson,true);
	}
	public function GetData($url,$par='',$requestType='POST'){
		$curl = curl_init();
		$headers = array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($par)
		);
		$postAry = array(
			CURLOPT_POST => 1,
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => $requestType,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_HTTPHEADER => $headers
		);
		$postAry[CURLOPT_POSTFIELDS] = $par;
		// print_r($postAry);
		try {
			curl_setopt_array($curl, $postAry);
			$response = curl_exec($curl);    
			curl_close($curl); 
			return $response;
		} catch (\Throwable $th) {
			return $th->getMessage();
		}
	}  
	
	public function checkSign($params,$appsecret){
		unset($params['sign']);
		ksort($params);
		$md5str = "";
		foreach ($params as $key => $val) {
			if($val){
				$md5str = $md5str . $key . "=" . $val . "&";
			}
		}
		$sign = strtolower(md5($md5str . "key=" . $appsecret));
		return $sign;
	}	
}
?>