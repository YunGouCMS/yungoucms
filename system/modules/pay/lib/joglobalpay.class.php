<?php 

class joglobalpay {
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
        $url = "/api/payin/brc/order/create";
        $configData = $this->config['data'];
        $url = $configData['url']['val'].$url;
        $money = number_format($this->config['money'],2,".","");
        /*      
            {
            "mchNo": "MCHIPOW8L3I", 商户号
            "appId": "",
            "mchOrderNo": "TESTABTwrnt4Vgf3U9tt",
            "amount": 100000,
            "wayCode": "MMK_PAY_IN",
            "currency": "MMK",
            "clientIp": "220.205.240.168",
            "subject": "测试商品",
            "body": "这是测试商品描述",
            "notifyUrl": "http://www.baidu.com",
            "name": "Sanju  Bansal",
            "phone": "18688984423",
            "email": "w.gssdyohqr@chvro.cy",
            "returnUrl": null,
            "signType": "MD5",
            "sign": "de69ecd558ab347ddc2af4e5db056aec",
            "userid": "test"
            }
        */
        $param = [
            "mchNo"=>$configData['mchNo']['val'], //商户号
            "mchOrderNo"=>$this->config['u_id'].$this->config['code'], //商户订单号
            "amount"=>intval($money*100), //订单金额
            "wayCode"=>$configData['wayCode']['val'], //支付方式
            "currency"=>$configData['currency']['val'], //币种
            "clientIp"=>$this->config['ip'], //用户ip
            "subject"=>$this->config['zxgm'], //商品名称
            "body"=>"用户".$this->config['u_id'].$this->config['zxgm'], //商品描述
            "notifyUrl"=>$this->config['NotifyUrl'], //异步通知地址
            "returnUrl"=>$this->config['ReturnUrl'], //同步通知地址
            // "name"=>$this->config['name'], //用户名
            // "phone"=>$this->config['phone'], //用户手机号
            // "email"=>$this->config['email'], //用户邮箱
            "signType"=>'MD5', //签名方式
        ];
        // var_export($param);die;
        $param['sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        //获取毫秒级时间戳
        $header = [
            "Content-Type: application/json",
        ];
        $res = $this->curlPost($url,$param,2,$header);
        /* {
            "msg": "操作成功",
            "code": 200,
            "data": {
                "mchNo": "MCHIPOW8L3I",
                "appId": "sos3de6jkaaoc7pt8c04wu04q4",
                "sign": "B29E66ED3C9395D11D74B8F95F2AC7C3",
                "mchOrderNo": "TESTABTwrnt4Vgf3U9tt",
                "wayCode": "BRL_PAY_IN",
                "currency": "BRL",
                "notifyUrl": "http://www.baidu.com",
                "payUrl": "http://127.0.0.1:9802/api/order/v3/H190202412062304503fte0",
                "payOrderId": "H190202412062304503fte0"
            }
            }
        */
        if($res['code'] == 200){
            $data = [
                "code" => 0,
                'order_id'=>$this->config['code'],
                "data"=>$res['data']['payUrl'],
                "money"=>$money*100,
                "msg"=>"ok"
            ];
            echo json_encode($data);die;
            $data['pay_status_code']=1;
            return $data;
        }
        echo json_encode($res);die;
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
      } */
    public function withdrawal($config){
        $this->config = $config;
        $url = "https://api.mgm-paysandbox.com/api/settle/settlement"; //沙盒
        // $url = "https://api.mgm-pay.com/api/settle/settlement";
        $configData = $this->config['data'];
        $cashout = $this->config['cashout'];
        $mtime = $this->getMillisecond();
        $header = [
            "Content-Type: application/json",
            "ApiVersion: 1.0",
            "AppId:".$configData['AppId']['val'],
            "Noncestr: ".rand(100000,99999999),
            "Timestamp: ".$mtime
        ];
        //
        $account_type = "CPF";         //账号类型
        if($account_type =="CPF"){
            $cashout['linkphone'] = str_replace(".","",$cashout['linkphone']);
            $cashout['linkphone'] = str_replace("-","",$cashout['linkphone']);
        }
        $cashout['banknumber'] = str_replace(".","",$cashout['banknumber']);
        $cashout['banknumber'] = str_replace("-","",$cashout['banknumber']);
        //
        $payee = [
            // name	String	是	收款方名字
            // lastname	String	否	收款方姓氏
            // account	String	是	收款方账号
            // account_type	String	是	账户类型
            // document	String	否	收款方个人公民身份ID，巴西必填
            // phone	String	否	收款方电话号码，详见：电话号码格式
            // 马来西亚必填
            // 印度必填
            // email	String	否	邮箱
            // 马来西亚必填
            // 印度必填
            // bank_name	String	否	银行名称
            // bank_code	String	否	银行编码
            // 印度、埃及、马来西亚、南非必填
            // bank_branch	String	否	银行支行
            // 印度 account_type=BANK时，传印度银行的IFSC号码
            "name"=>$cashout['username'],
            // "lastname"=>"",
            "account"=>$cashout['banknumber'],
            "account_type"=>$account_type,
            "document"=>$cashout['linkphone'],
            // "phone"=>"",
            // "bank_name"=>"",
            // "bank_code"=>"",
            // "bank_branch"=>"",
            // "bank_account_type"=>"",
        ];
        $money = number_format($cashout['money'],2,".","");
        $data = [
            // country	String	是	是	国家代码。详见：国家和货币代码
            // currency	String	是	是	货币代码。详见：国家和货币代码
            // order_id	String	是	是	商户订单编号
            // amount	Double	是	是	固定2位小数点的浮点数金额，如：10.00
            // notification_url	String	是	是	回调通知地址。支付成功后，向商户发送成功的回调通知
            // timestamp	Long	是	是	毫秒级UTC时间戳（13位）
            // signature	String	是	否	MD5签名，32位大写字母
            // payee	object	是	否	收款方信息体
            "country"=>$configData['country']['val'],
            "currency"=>$configData['currency']['val'],
            "order_id"=>$cashout['id']."_".$cashout['uid'].'_'.$cashout['time'],
            "amount"=>$money,
            "notification_url"=>$this->config['NotifyUrl'],
            "timestamp"=>$mtime,
        ];
        $param = [
            "merchant_no"=>$configData['merchant_no']['val'],
            "data"=>$data
        ];
        $param["data"]['signature'] = $this->sign($param['data'],$configData['key_1']['val']);  //签名字符串
        $param['data']["payee"]=$payee;
        save_log($param,"mgmpay".date("Y-m-d").".log",1);
        $res = $this->curlPost($url,$param,2,$header);
         /* {
            "data": {
          ​    "payee": {
          ​      "name": "Andru00e9 Alves",
          ​      "account": "test",
          ​      "phone": "test",
          ​      "account_type": "PHONE"
          ​    },
          ​    "country": "BR",
          ​    "amount": "20.00",
          ​    "status_code": 1,
          ​    "settlement_id": "S101100045_1625281327681114112",
          ​    "signature": "4B0167490060655A16B8904F9FD391B2",
          ​    "currency": "BRL",
          ​    "order_id": "TP1676332232637",
          ​    "status": "APPLY",
          ​    "status_detail": "Cashout is order",
          ​    "timestamp": 1676332239463
            },
            "state": "ok"
          } */
        if($res['state'] == "ok"){
            $data = [
                "code" => 0,
                "settlement_id"=>$res['data']['settlement_id'],
                "msg"=>"ok"
            ];
            $data['pay_status_code']=1;
            return $data;
        }
        $res['pay_status_code']=0;
        return $res;
    }
}