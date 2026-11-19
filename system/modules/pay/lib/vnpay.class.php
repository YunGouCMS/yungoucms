<?php

class vnpay{
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
        return $this->pay();
	}
    private function pay(){
        $url = "https://fdhj12fdsaj.yespay.bet/api/create";
        $userid = $this->config['id'];
        $orderid = $this->config['code'];
        $amount = number_format($this->config['money'],4,".","");
        $returnurl = $this->config['ReturnUrl'];
        $notifyurl = $this->config['NotifyUrl'];
        $note = "";
        $payload = '';
        $types = ["zalo","momo","vt"];
        if($amount<10000000){
            // $type = $types[0];
            $type = $types[1];
        }elseif($amount<30000000){
            $type = $types[1];
        }elseif($amount<50000000){
            $type = $types[2];
        }else{
            echo json_encode(['code'=>"500","msg"=>"amount error"]);
            exit;
        }
        $data = [
            "userid"=>$userid,//友商编号
            "orderid"=>$orderid,//订单号
            "type"=>$type,//订单类型
            "amount"=>$amount,//订单金额
            "returnurl"=>$returnurl,//显示地址
            "notifyurl"=>$notifyurl,//回调地址
            // "note"=>$note,//附加参数
            // "payload"=>$payload,//其他参数
        ];
        $sign = $this->sign($this->config['key'],$orderid,$amount);
        $data['sign'] = $sign;
        $res = $this->curlPost($url,$data);
        if($res['code'] == "1"){
            $info = json_decode($res['data'],true);
            $data = [
                "code" => 0,
                "data"=>$info['pageurl'],
                // "details"=>$res,
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data["pay_status_code"] = 1;
            return $data;
        }
        echo json_encode($res);
        $res['pay_status_code']=0;
        return $res;
    }

    private function sign($apikey,$orderid,$amount){
        $signstr = $apikey.$orderid.$amount;
        return md5(strtolower($signstr));
    }

    /**
     * Summary of curlPost
     * @param string $url
     * @param array $payload 
     * @return array
     */
    private function curlPost($url,$payload){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultJson = curl_exec($ch);
        return json_decode( $resultJson,true);
       }
}
