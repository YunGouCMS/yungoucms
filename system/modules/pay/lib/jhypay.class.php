<?php 

include dirname(__FILE__).DIRECTORY_SEPARATOR."jhypay".DIRECTORY_SEPARATOR."EpayCore.class.php";
class jhypay {
	
	private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
		
		return $this->go_pay();
	
	
		
	}
	
	//
	private function go_pay(){
		$config = $this->config;
        
        //支付方式（可传入alipay,wxpay,qqpay,bank,jdpay）
        $type = $config['tyoe'];
        if(empty($type)){
            $type = 'alipay';
        }
        
        // $epay_config['apiurl'] = 'http://p.pipizf.com/';
        // $epay_config['apiurl'] = 'http://q.txvps.com/';
        $epay_config['apiurl'] = 'http://api.mzf.ltd/';
        
        //商户ID
        $epay_config['pid'] =$config['id'];
        
        //商户密钥
        $epay_config['key'] = $config['key'];
        
        /************************************************************/
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
        	"pid" => $epay_config['pid'],
        	"type" => $type,
        	"notify_url" => $config['NotifyUrl'],
        	"return_url" => $config['ReturnUrl'],
        	"out_trade_no" => $config['u_id'].$config['code'],
        	"name" => $config['zxgm'],
        	"money"	=> $config['money'],
        );

        //建立请求
        $epay = new EpayCore($epay_config);
        $html_text = $epay->pagePay($parameter);
        echo $html_text;
		$data['pay_status_code'] = 1;
		return $data;
	}
	
	
}

?>
