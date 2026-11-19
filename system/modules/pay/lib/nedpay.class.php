<?php 

class nedpay {
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
        $url = "/api/pay/unifiedOrder";
        $configData = $this->config['data'];
        $url = $configData['url']['val'].$url;
        $money = number_format($this->config['money'],2,".","");
        /*      
            参数名	必填	类型	示例值	说明
            mchNo	是	string		商户号
            mchOrderNo	是	string		商户订单号
            productId	是	string		支付产品ID
            amount	是	long		支付金额
            clientIp	是	string		客户端IP
            notifyUrl	是	string		异步通知地址
            reqTime	是	long		请求时间戳13位
            extParam	否	string		额外参数
            sign	是	string		签名
        */
        $mtime = $this->getMillisecond();
        $order_no = $this->config['u_id'].$this->config['code'];
        $param = [
            "mchNo"=>$configData['mchNo']['val'],
            "productId"=>$configData['productId']['val'],
            "mchOrderNo"=>$order_no,
            "amount"=>intval($money * 100),
            "clientIp"=>$this->config['ip'],
            "reqTime"=>$mtime,
            "notifyUrl"=>$this->config['NotifyUrl'],
            // "returnUrl"=>$this->config['ReturnUrl'],
        ];
        $param['sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        // echo json_encode($param);
        // die;
        $header = [
            "Content-Type: application/json",
        ];
        $res = $this->curlPost1($url,$param,2,$header);
        /* 
        payOrderId	是	string		支付订单号
        mchOrderNo	是	string		商户订单号
        orderState	是	string		支付状态
        payDataType	否	long		支付数据类型
        payData	否	string		支付数据
        
        例子{
                "code": 0,
                "data": {
                    "mchOrderNo": "f39fe545-4802-46d4-949b-cd5967e55190",
                    "orderState": 1,
                    "payData": "https://www.baidu.com",
                    "payDataType": "payUrl",
                    "payOrderId": "xxx"
                },
                "msg": "SUCCESS",
                "sign": "33892E13730768D97126E00623DB09D9"
            }
        */
        if($res['code'] == "0" ){
            // header("Location: ".$res['data']['payData']);
            
            $data = [
                "code" => 0,
                "order_no"=>$order_no,
                "data"=>$res['data']['payData'],
                "url"=>G_WEB_PATH."/index.php/pay/nedpay_url/order_query?code=".$this->config['code'],
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
        $str = md5($str);
        // $str = strtoupper(md5($str));
        // $str = strtolower(md5($str));
        return $str;
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

    private function curlPost1($url,$params,$type=1,$header=[]){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if($type==1){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        if($header){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
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