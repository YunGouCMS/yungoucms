<?php 

class rspay {
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
        return $this->pay();
	}

    public function refund(){

    }

    public function pay(){
        $url = "https://rsapi.yuezhenjie.cn/receipt/submit";
        $configData = $this->config['data'];
        $money = number_format($this->config['money'],2,".","");
/*         merchantId	Y	apiKey（平台分配的商户Id）
        merchantTradeNo	Y	商户订单号
        payCode	Y	支付代码（见支付代码列表）
        amount	Y	订单金额（分）
        userIp	Y	用户真实IP地址
        userId	N	用户ID
        goodsName	Y	商品名称
        goodsDesc	N	商品描述
        notifyUrl	Y	异步回调URL
        returnUrl	N	同步跳转URL（支付成功后，前台页面跳转地址）
        attach	N	透传参数（如果有值，回调时会传递）
        timestamp	Y	Unix时间戳
        sign	Y	签名（见签名规则） */
        $time = time();
        $param = [
            "merchantId"=>$configData['apiKey']['val'],   //apiKey（平台分配的商户Id）
            "merchantTradeNo"=>$this->config['u_id'].$this->config['code'],    //商户订单号
            "payCode"=>$configData['payCode']['val'],    //支付代码
            "amount"=>intval($money * 100),    //商品金额
            "userIp"=>$this->config['ip'],    //用户IP地址
            "goodsName"=>$this->config['zxgm'],    //商品名称
            "notifyUrl"=>$this->config['NotifyUrl'],    //异步通知地址
            "returnUrl"=>$this->config['ReturnUrl'],    //跳转通知地址
            "timestamp"=>$time,    //支付类型
        ];
        $param['sign'] = $this->sign($param,$configData['secretKey']['val']);  //签名字符串
        // echo json_encode($param);
        // die;
        $res = $this->curlPost($url,$param);
        /* {
            "code" : "1000",
            "message" : "创建订单成功",
            "tradeNo" : "123456",
            "merchantTradeNo" : "123456789", 
            "amount" : "1000",
            "payUrl" : "https://www.xxx.com/",
            "sign" : "**************"
        } */
        if($res['code'] == "1000"){
            $data = [
                "code" => 0,
                "data"=>$res['payUrl'],
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

    private function sign($array,$secret_key){
        $str = '';
        ksort($array);
        foreach($array as $key=>$vl){
            if($str){$str .= "&";}
            $str .= $key."=".$vl;
        }
        $str .= "&secretKey=".$secret_key;
        $str = strtoupper(md5($str));
        // $str = strtolower(md5($str));
        return $str;
    }

    private function curlPost1($url,$payload){

        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultJson = curl_exec($ch);
        return json_decode( $resultJson,true);
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