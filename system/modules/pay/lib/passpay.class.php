<?php 

class passpay {
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
        // $url = "/api/developer/order/create";
        $url = "/api/developer/order/create";
        $configData = $this->config['data'];
        $url = $configData['url']['val'].$url;
        $money = number_format($this->config['money'],2,".","");
        /*      
            mchid	是	string	由我方分配
            pay_id	是	string	由我方分配
            out_trade_no	是	string	商户传入订单号，唯一
            amount	是	string	订单金额：100.00、500.00
            notify_url	是	string	回调地址
            sign	是	string	签名
        */
        $time = time();
        $param = [
            "mchid"=>$configData['mchid']['val'],
            "pay_id"=>$configData['pay_id']['val'],
            "out_trade_no"=>$this->config['u_id'].$this->config['code'],
            "amount"=>$money,
            "notify_url"=>$this->config['NotifyUrl'],
            // "returnUrl"=>$this->config['ReturnUrl'],
        ];
        $param['sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        $res = $this->curlPost($url,$param,2);
        /* {
                "data": {
                    "status": 1,
                    "msg": "CREATE_SUCCESS",
                    "pay_id": "10",
                    "trade_no": "xxx",
                    "out_trade_no": "Test20240714010053367",
                    "amount": "100.00",
                    "pay_link": "http://app.passpay.cc/pay/page?orderNo=XXX&utr=&amount=100",
                    "upiLink": "upi://pay?<params>",
                    "upiParams": "<params>",
                    "PhonePEParams": "<params>"
                },
                "rCode": 200,
                "rMsg": "CREATE_SUCCESS",
                "elapsed_time": 0.396
            }
        */
        if($res['data']['status'] == "1"){
            $data = [
                "code" => 0,
                "data"=>$res['data']['pay_link'],
                "url" => G_WEB_PATH."/index.php/pay/passpay_url/order_query?code=".$this->config['code'],
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data['pay_status_code']=1;
            return $data;
        }
        echo json_encode($res);
        $res['pay_status_code']=0;
        return $res;
    }

    private function sign($array,$secret_key){
        $str = '';
        ksort($array);
        foreach($array as $key=>$vl){
            if($str){$str .= "&";}
            $str .= $key."=".$vl;
        }
        $str .= "&key=".$secret_key;
        // $str = strtoupper(md5($str));
        $str = strtolower(md5($str));
        return $str;
    }

    private function curlPost1($url,$payload){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultJson = curl_exec($ch);
        return json_decode( $resultJson,true);
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
    return json_decode( $resultJson,true);
    }
}