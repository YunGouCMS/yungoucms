<?php 

include dirname(__FILE__).DIRECTORY_SEPARATOR."alipay".DIRECTORY_SEPARATOR."alipay_submit.class.php";
require_once("epay/EpayCore.class.php");
class epay {
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
        return $this->pay1();
	}

    public function pay(){
    /**************************请求参数**************************/
    $notify_url = $this->config['NotifyUrl'];
    //需http://格式的完整路径，不能加?id=123这类自定义参数
    //页面跳转同步通知页面路径
    $return_url = $this->config['ReturnUrl'];
    //需http://格式的完整路径，不能加?id=123这类自定义参数
    //商户订单号
    $out_trade_no = $this->config['code'];
    //商户网站订单系统中唯一订单号，必填
    //支付方式（可传入alipay,wxpay,qqpay,bank,jdpay）
    $type = $this->config['type'];
    //商品名称
    $name = $this->config['name'];
    //付款金额
    $money = number_format($this->config['money'],2,".","");
    /************************************************************/
    //构造要请求的参数数组，无需改动
    $parameter = array(
        "type" => $type,
        "notify_url" => $notify_url,
        "return_url" => $return_url,
        "out_trade_no" => $this->config['u_id'].$out_trade_no,
        "name" => $name,
        "money"	=> $money,
    );
    $configData = $this->config['data'];
    $epay_config = [
        //支付接口地址
        'apiurl' => $configData['apiurl']['val'],
        //商户ID
        'pid' => $configData['pid']['val'],
        //平台公钥
        'platform_public_key' => $configData['platform_public_key']['val'],
        //商户私钥
        'merchant_private_key' => $configData['merchant_private_key']['val']
    ];
    //建立请求
    $epay = new EpayCore($epay_config);
    $html_text = $epay->pagePay($parameter);
    echo $html_text;
    }

    public function refund(){

    }

    public function pay1(){
        /**
         * 接口类型
         * web	通用网页支付（会根据device判断，自动
         *   返回跳转url/二维码/小程序跳转url等）
        *    jump	跳转支付（仅会返回跳转url）
         *   jsapi	JSAPI支付（小程序内支付使用，仅返回JSAPI参数，
         *   需传入sub_openid和sub_appid参数）
         *   app	APP支付（iOS/安卓APP内支付使用，
          *  仅返回APP支付参数）
          *  scan	付款码支付（需传入auth_code参数，
          *  支付成功后返回订单信息）
         */
        /**
         * 支付方式
         * alipay	支付宝
         *  wxpay	微信支付
         *   qqpay	QQ钱包
         */
        $url = "https://xbyqq.lscipn.cn/api/pay/create";
        $configData = $this->config['data'];
        $param = [
            "pid"=>$configData['pid']['val'],      //商户号
            "method"=>"jump",   //接口类型
            "type"=>$configData['type']['val'],    //支付方式
            "out_trade_no"=>$this->config['u_id'].$this->config['code'],    //商户订单号
            "notify_url"=>$this->config['NotifyUrl'],    //异步通知地址
            "return_url"=>$this->config['ReturnUrl'],    //跳转通知地址
            "name"=>$this->config['zxgm'],    //商品名称
            "money"=>number_format($this->config['money'],2,".",""),    //商品金额
            "clientip"=>$this->config['ip'],    //用户IP地址
            "timestamp"=>time(),    //当前时间戳
        ];
        $param['sign'] = $this->sign($param,$configData['merchant_private_key']['val']);  //签名字符串
        $param['sign_type'] = 'RSA'; //签名类型
        $res = $this->curlPost($url,$param);
        if($res['code'] == "0"){
            $data = [
                "code" => 0,
                "data"=>$res['pay_info'],
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data['pay_status_code'] = 1;
            return $data;
        }
        echo json_encode($res);
        $res['pay_status_code'] = 0;
        return $res;
    }

    private function sign($params,$secret_key){
        ksort($params);
        $signstr = '';
        foreach ($params as $k => $v) {
            if(is_array($v) || $this->isEmpty($v) || $k == 'sign' || $k == 'sign_type') continue;
            $signstr .= '&' . $k . '=' . $v;
        }
        $signstr = substr($signstr, 1);

        $key = "-----BEGIN PRIVATE KEY-----\n" .
            wordwrap($secret_key, 64, "\n", true) .
            "\n-----END PRIVATE KEY-----";
        $privatekey = openssl_get_privatekey($key);
        if(!$privatekey){
            throw new \Exception('签名失败，商户私钥错误');
        }
        openssl_sign($signstr, $sign, $privatekey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }
    private function isEmpty($value)
    {
        return $value === null || trim($value) === '';
    }

    private function curlPost($url,$params){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $resultJson = curl_exec($ch);
        return json_decode( $resultJson,true);
       }
}