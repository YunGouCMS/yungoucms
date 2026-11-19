<?php 

class ptpay {
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
        // $url = "http://182.16.59.162:12345/trade/pay";
        $url = "/trade/pay";
        $configData = $this->config['data'];
        $url = $configData['url']['val'].$url;
        $money = number_format($this->config['money'],2,".","");
        $param = [
            "version"=>$configData['version']['val'],      //版本号
            "partnerid"=>$configData['partnerid']['val'],   //商户号
            "orderid"=>$this->config['u_id'].$this->config['code'],    //商户订单号
            "payamount"=>intval($money * 100),    //商品金额
            "payip"=>$this->config['ip'],    //用户IP地址
            "notifyurl"=>$this->config['NotifyUrl'],    //异步通知地址
            "returnurl"=>$this->config['ReturnUrl'],    //跳转通知地址
            "paytype"=>$configData['typaytypepe']['val'],    //支付类型
        ];
        $param['sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        $res = $this->curlPost($url,$param);
        /* {
            "code": "0",
            "message": "99999",
            "orderid": "CSY710500273726922465285394",
            "partnerid": "2r82yc7l",
            "partnerorderid": "C17335460342747172",
            "paytype": "518",
            "payurl": "http://pay.deyanhulian.cn/v1/alipay/order/payment?require_id=P252024120712335462742",
            "sign": "c99a2da87d13028cad15aff5260e87ca",
            "version": "1.0"
        } */
        if($res['code'] == "0"){
            $data = [
                "code" => 0,
                "data"=>$res['payurl'],
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
        $str .= "&key=".$secret_key;
        // $str = strtoupper(md5($str));
        $str = strtolower(md5($str));
        return $str;
    }

    private function curlPost($url,$payload){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultJson = curl_exec($ch);
        return json_decode( $resultJson,true);
       }
}