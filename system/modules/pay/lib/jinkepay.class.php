<?php 

class jinkepay {
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
        $url = "/api/v1/pay/unifiedOrder";
        $configData = $this->config['data'];
        $url = $configData['url']['val'].$url;
        $money = number_format($this->config['money'],2,".","");
        /*      
        参数	字段名	必填	类型	示例值	描述
        mchId	商户号	是	string	10001	商户号
        productId	通道类型	是	string	901	通道类型，联系客服要
        outTradeNo	商户订单号	是	string	JK00001	商户生成的订单号
        amount	支付金额 (单位: 分)	是	int	10000	支付金额 (单位: 分)，例如: 10000 即为 100.00 元
        reqTime	请求时间	是	long	1735704000000	请求接口时间，13位时间戳
        notifyUrl	异步通知地址	是	string	https://test.com/notify	支付结果异步回调URL，只有传了该值才会发起回调
        returnUrl	跳转通知地址	否	string	https://test.com/return	支付结果同步跳转通知URL
        userId	商户用户id	否	string	USER_001	商户用户id,最大32个字符
        userName	商户用户姓名	否	string	小明	商户用户姓名
        userIp	商户用户ip	否	string	1.1.1.1	商户用户ip，支持IPV6
        sign	签名	是	string	f52ab92457b1a97c1dbbc794de8dea8e	签名值，不参与签名
        */
        $mtime = $this->getMillisecond();
        $order_no = $this->config['u_id'].$this->config['code'];
        $param = [
            "mchId"=>$configData['mchId']['val'],
            "productId"=>$configData['productId']['val'],
            "outTradeNo"=>$order_no,
            "amount"=>intval($money * 100),
            "reqTime"=>$mtime,
            "notifyUrl"=>$this->config['NotifyUrl'],
            "returnUrl"=>$this->config['ReturnUrl'],
            // "userId"=>"",
            // "userName"=>"",
            // "userIp"=>"",
        ];
        $param['sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        // echo json_encode($param);
        // die;
        $header = [
            "Content-Type: application/json",
        ];
        $res = $this->curlPost1($url,$param,2,$header);
        /* 
        参数	字段名	必填	类型	示例值	描述
        code	返回状态码	是	int	0	网关返回码：0=成功，其他失败
        message	返回信息	是	string	签名失败	具体错误原因，例如：签名失败、参数格式校验错误
        data	以下是 data 数据说明，其非空值的参数都参与签名。				
            mchId	商户号	是	string	1	商户号
            tradeNo	支付订单号	是	string	JK0001	返回支付系统订单号
            outTradeNo	商户订单号	是	string	SH0001	返回商户传入的订单号
            amount	订单金额 (单位: 分)	是	string	10000	订单金额 (单位: 分)，例如: 10000 即为 100.00 元
            payUrl	支付地址	是	string	https://test.com/payurl	支付地址，商户需要展示该地址给付款用户。注意：这里一般返回的是 URL 支付链接。
            extData	拓展内容	否	string		
            sign	签名	是	string	f52ab92457b1a97c1dbbc794de8dea8e	对 data 内数据签名
        */
        if($res['code'] == "0"){
            $data = [
                "code" => 0,
                "order_no"=>$order_no,
                "data"=>$res['data']['payUrl'],
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