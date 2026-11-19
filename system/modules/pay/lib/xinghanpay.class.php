<?php 

class xinghanpay {
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
        $url = "https://pay.xinghanpay.com/index/createOrder";
        $configData = $this->config['data'];
        $money = number_format($this->config['money'],2,".","");
/*      
        pay_memberid	商户号	是	是	平台分配商户号
        pay_orderid	订单号	是	是	上送订单号唯一, 字符长度20
        pay_applydate	提交时间	是	是	时间格式：2016-12-26 18:18:18
        pay_bankcode	通道编码	是	是	在商户中心查询
        pay_notifyurl	服务端通知	是	是	服务端返回地址.（POST返回数据）
        pay_callbackurl	页面跳转通知	是	是	页面跳转返回地址（仅返回指定的URL页面）
        pay_amount	订单金额	是	是	单位：元
        pay_md5sign	MD5签名	是	是	请查看签名算法
        pay_productname	商品名称	是	否	
        pay_attach	附加参数	否	否
        */
        $time = time();
        $param = [
            "pay_memberid" => $configData['pay_memberid']['val'],
            "pay_orderid" => $this->config['u_id'].$this->config['code'],
            "pay_amount" => $money,
            "pay_applydate" =>  date("Y-m-d H:i:s", $time),
            "pay_bankcode" => $configData['pay_bankcode']['val'],
            "pay_notifyurl" => $this->config['NotifyUrl'],
            "pay_callbackurl" => $this->config['ReturnUrl'],
        ];
        $param['pay_md5sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        $param['pay_productname'] = $this->config['zxgm'];
        // echo json_encode($param);
        // die;
        $header = array('Content-Type: application/x-www-form-urlencoded'); //请求头记得变化-不同的上传方式
        $res = $this->curlPost($url,$param);
        /* {
            code	状态码	1
            msg	文字说明	SUCCESS
            data	参数信息	object
            data.pay_url	支付链接	http://www.baidu.com
            data.qrcode	二维码信息	“”
            data.order_sn	订单号	F01202404090000145611
        } */
        if($res['code'] == "1"){
            // header('location:'.$res['data']['pay_url']);
            $data = [
                "code" => 0,
                "data"=>$res['data']['pay_url'],
                "order_no"=>$this->config['u_id'].$this->config['code'],
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data['pay_status_code']=1;
            return $data;
        }else{
            echo json_encode($res);
            $res['pay_status_code']=0;
            return $res;
        }
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
    private function curlPost($url,$params,$headers=[]){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    if($headers){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $resultJson = curl_exec($ch);
    return json_decode( $resultJson,true);
    }
}