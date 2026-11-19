<?php

class globalpay{
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
        return $this->pay();
	}
    private function pay(){
        $url = "https://globalpayapp.best/alliesPay/bussiness/order";
        $alliesNo = $this->config['id'];
        $tradeNo = $this->config['code'];
        $totalAmount = number_format($this->config['money'],2);
        $tradeType = $this->config['tradeType']?:31; //32
        $productId = $this->config['productId']?:310; //310
        $frontNotifyUrl = $this->config['ReturnUrl'];
        $noticeUrl = $this->config['NotifyUrl'];
        $attach = "a123";
        $data = [
            "alliesNo"=>$alliesNo,//友商编号
            "tradeNo"=>$tradeNo,//订单号
            "totalAmount"=>$totalAmount,//订单金额
            "tradeType"=>$tradeType,//支付方式
            // "clientIp"=>$clientIp,//客户IP
            // "productName"=>$productName,//商品名称
            "attach"=>$attach,//附加参数
            "frontNotifyUrl"=>$frontNotifyUrl,//显示地址
            "noticeUrl"=>$noticeUrl,//回调地址
            // "payerName"=>$payerName,//付款人姓名
            // "remark"=>$remark,//附言
            // "payerPhone"=>$payerPhone,//付款人手机号
            "productId"=>$productId,//产品Id 默认0
        ];
        $sign = $this->sign($data,$this->config['key']);
        $data['sign'] = $sign;
        $res = $this->curlPost($url,$data);
        if($res['code'] == "10000"){
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
        $str .= "&key=".$secret_key;
        $str = strtoupper(md5($str));
        return $str;
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
