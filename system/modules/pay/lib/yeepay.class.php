<?php 

class yeepay {
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
        $url = "https://wg.egzf.xyz/api/pay/create_order";
        $configData = $this->config['data'];
        $money = number_format($this->config['money'],2,".","");
/*      
        商户ID	mchId	是	是	long	20001222	分配的商户号
        支付产品ID	productId	是	是	int	8000	支付产品ID
        商户订单号	mchOrderNo	是	是	String(30)	20160427210604000490	商户生成的订单号
        支付金额	amount	是	是	int	100	支付金额,单位分
        客户端IP	clientIp	否	是	String(32)	210.73.10.148	客户端IP地址
        设备	device	否	是	String(64)	ios10.3.1	客户端设备
        支付结果前端跳转URL	returnUrl	否	是	String(128)	http://shop.yeepay.org/return.htm	支付结果回调URL
        支付结果后台回调URL	notifyUrl	是	是	String(128)	http://shop.yeepay.org/notify.htm	支付结果回调URL
        商品主题	subject	是	是	String(64)	测试商品1	商品主题
        商品描述信息	body	是	是	String(256)	聚合测试商品描述	商品描述信息
        扩展参数1	param1	否	是	String(64)		支付中心回调时会原样返回
        扩展参数2	param2	否	是	String(64)		支付中心回调时会原样返回
        附加参数	extra	是	是	String(512)	{"openId":"o2RvowBf7sOVJf8kJksUEMceaDqo"}	特定渠道发起时额外参数,见下面说明
        签名	sign	是	否	String(32)	C380BEC2BFD727A4B6845133519F3AD6	签名值，详见签名算法 
        */
        $time = time();
        $param = [
            "mchId"=>$configData['mchId']['val'],
            "productId"=>$configData['productId']['val'],
            "mchOrderNo"=>$this->config['u_id'].$this->config['code'],
            "amount"=>intval($money * 100),
            // "clientIp"=>"",
            // "device"=>"",
            "returnUrl"=>$this->config['ReturnUrl'],
            "notifyUrl"=>$this->config['NotifyUrl'],
            "subject"=>$this->config['zxgm'],
            "body"=>$this->config['zxgm'],
            // "param1"=>"",
            // "param2"=>"",
        ];
        if($configData['extra']['val']){
            $param['extra'] = $configData['extra']['val'];  // 附加参数
        }
        $param['sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        // echo json_encode($param);
        // die;
        $res = $this->curlPost($url,$param);
        /* {
            retMsg:''
            retCode:SUCCESS
            sign:C380BEC2BFD727A4B6845133519F3AD6
            payOrderId:20160427210604000490
            payParams:{payMethod: "formJump", payUrl: "支付Url" }
        } */
        if($res['retCode'] == "SUCCESS"){
            $data = [
                "code" => 0,
                "data"=>$res['payParams']['payUrl'],
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