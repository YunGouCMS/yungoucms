<?php 

class sunpay {
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
        $url = "/vmc/distr/ts1";
        $configData = $this->config['data'];
        // $pay_input = $this->config['pay_input'];
        $url = $configData['url']['val'].$url;
        
        $money = number_format($this->config['money'],2,".","");
        /*      
            cust_ord	String	是	36	商户单据号
            amount	String	是	12	代收金额
            c_id	String	是	6	商户代码
            content	String	是	12	收款人(显示在条码上)
            client_ip	String	是	70	最终回调信息接收接口
        */
        $time = time();
        $order_no = $this->config['u_id'].$this->config['code'];
        $param = [
            "cust_ord"=>$order_no,
            "amount"=>$money,
            "c_id"=>$configData['cus_id']['val'], //商户代码
            "content"=>$this->config['shouname'], //收款人
            "client_ip"=>$this->config['NotifyUrl'], //最终回调信息接收接口
        ];
        $day = date('Y-m-d',$time);
        save_log($param,"pay_log/sunpay/sunpay_param_$day.log");
        $signature = $this->sign($param,$configData['passcode']['val']);  //签名字符串
        $header = [
            'Accept:application/json',
            'Content-Type:application/json',
            'x-request-signature:'. $signature,
            'x-request-timestamp:'.$time,  //(YYYY-MM-DD hh:mm:ss)
            'x-sign-version:v2'
        ];
        $res = $this->curlPost($url,$param,2,$header);
        /* {
            "status": "success",
            "code": "9",
            "qr_payment": "https://img.vig.....",
            }
        */
        if($res['status'] == "success" || $res['status'] == "201"){
            $data = [
                "code" => 0,
                "data"=>$res['qr_payment'],
                "order_no"=>$order_no,//订单号
                "url"=>G_WEB_PATH."/index.php/pay/nedpay_url/order_query?code=".$this->config['code'],//查询订单接口
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
    //获取毫秒级时间戳
    private function getMillisecond(){
            $sysTimeZone= date_default_timezone_get();//先取出系统所在服务器的时区
            date_default_timezone_set('UTC');//设置时区为UTC时区
            list($s1,$s2)=explode(' ',microtime());
            $timestamp = (float)sprintf('%.0f',(floatval($s1)+floatval($s2))*1000);
            date_default_timezone_set( $sysTimeZone ); //设置时区为服务器时区
            return $timestamp;
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

    private function curlPost($url,$params,$type=1,$header=[]){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    if($type!=1){
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    }else{
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    if($header){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $resultJson = curl_exec($ch);
    return json_decode( $resultJson,true);
    }

    /* {
        "merchant_no": "100116095100034344",
        "data": {
      ​    "amount": 100.00,
      ​    "currency": "BRL",
      ​    "country": "BR",
      ​    "order_id": "STjhg4v34v535",
      ​    "notification_url": "http://xxx.xxx.xxx/notification/new",
      ​    "timestamp":1672584283995,
      ​    "signature":"6A53557FEB25DEC6E52F296DE2CCFD66",
      ​    "payee":{
      ​      "name":"Miscro Duma",
      ​      "account":"123456123",
      ​      "account_type":"Pix_CPF",
      ​      "document":456131345
      ​    }
        }
      } 
      *未完成
      */
    public function withdrawal($config){
        $this->config = $config;
        // $url = "";
        $configData = $this->config['data'];
        $cashout = $this->config['cashout'];
        $url = "/vmc/distr/ts2"; //
        $url = $configData['url']['val'].$url;
        /* c_id	String	是	6	商户代码
        cust_ord	String	是	36	商户单据号
        amount	String	是	12	代付金额
        bank_code	String	是	50	银行名称
        bank_card	String	是	30	收款卡号
        client_ip	String	是	70	最终回调信息接收接口 */
        $money = number_format($cashout['money'],2,".","");
        $param = [
            "c_id" => $configData['cus_id']['val'],
            "cust_ord" => $cashout['id']."_".$cashout['uid'].'_'.$cashout['time'],
            "amount" => $money,
            "bank_code" => $cashout['bankname'],
            "bank_card" => $cashout['banknumber'],
            "client_ip" => $this->config['NotifyUrl'],
        ];
        $signature = $this->sign($param, $configData['passcode']['val']);
        $datetime = date('Y-m-d H:i:s', time());
        $header = [
            "Accept: application/json",
            "Content-Type: application/json",
            "x-request-signature: $signature",
            "x-request-timestamp: $datetime",  //(YYYY-MM-DD hh:mm:ss)
            "x-sign-version:v2"
        ];
        save_log($param,"pay_log/sunpay/w_".date("Y-m-d").".log");
        $res = $this->curlPost($url,$param,2,$header);
         /* {
            参数名	说明	
            SunPay	SunPay订单号	
            code	业务状态	
            cust_ord	商户单据号	
            mch_id	商户账号	
            message	订单提示信息	
          } */
        if($res['code'] == "002"){
            $data = [
                "code" => 0,
                "settlement_id"=>$res['SunPay'],
                "msg"=>"ok"
            ];
            $data['pay_status_code']=1;
            return $data;
        }
        save_log($res,"pay_log/sunpay/w_".date("Y-m-d").".log");
        $res['pay_status_code']=0;
        return $res;
    }
}