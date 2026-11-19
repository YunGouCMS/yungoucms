<?php
require_once("OnePayQr.class.php");
class OnePayCore{
    private $mcid = "mch65c1cab49c500";
    private $uuid = "test001";
    private $privateKeyFile = "./apiclient_key.pem";
    /**
     * 获取Qr
     *
     * @return 
     */
    public function getQr($amount = 0, $invoiceid = "", $transactionid = "", $terminalid = null, $description = 'Orange Juice'){
        $oneqr = new OnePayQr();
        $qr = $oneqr->getQr($amount,$invoiceid,$transactionid,$terminalid,$description);
        return $qr;
    }
    /**
     * 发起支付请求
     *
     * @return void
     */
    public  function pay(){
      return null;
    }

    /**
     * 作废请求
     *
     * @return void
     */
    public function toVoid(){
        $httpheader = [
            "Content-Type: applicatton/x-www-form-urlencoded"
        ];
        $url = "https://bcel.la:8083/onepay/void.php";
        $signature = $this->signDataWithPrivateKey($this->mcid.$this->uuid,$this->privateKeyFile);
        $data = [
            "mcid"=> "mch5c2f0404102ff",
            "uuid"=> "test001", 
            "signature"=>$signature
        ];
        $res = $this->httpPost($url,$data,$httpheader);
        return $res;
    }   
    
    /**
     * 退款
     *
     * @return void
     */
    public function refund($refundid,$refundamount=0){
        if($refundamount<=0){
            return '';
        }
        $httpheader = [
            "Content-Type: applicatton/x-www-form-urlencoded"
        ];
        $url = "https://bcel.la:8083/onepay/refund.php";
        // $refundid = 230806385767441;
        $signature = $this->signDataWithPrivateKey($this->mcid.$this->uuid.$refundid,$this->privateKeyFile);
        $data = [
            "mcid"=> $this->mcid,
            "uuid"=> $this->uuid, 
            "refundid"=> $refundid, 
            "refundamount"=> $refundamount,
            "signature"=>$signature
        ];
        $res = $this->httpPost($url,$data,$httpheader);
        return $res;
    }

    public function test(){
            // 使用示例
        // $data = 'mcid=' . urlencode('your_mcid_value') . '&uuid=' . urlencode('your_uuid_value');
        $data = $this->mcid.$this->uuid;
        $privateKeyFile = './apiclient_key.pem'; // 私钥文件路径
        
        try {
            $signature = $this->signDataWithPrivateKey($data, $privateKeyFile);
            echo $signature; // 输出Base64编码的签名
        } catch (Exception $e) {
            echo '错误：' . $e->getMessage();
        }

    }

    private function httpGet($url,$data=[],$timeout=10) {
        $url = $url . '?' . http_build_query($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error GET '.curl_error($curl);
        }
        curl_close($curl);
        return $res;
    }

    private function httpPost($url,$data=[],$httpheader,$timeout=30){ 
        // 初始化 cURL 会话
        $ch = curl_init();
        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 发送请求并获取响应
        $response = curl_exec($ch);

        if(curl_errno($ch)){
            $error_msg = curl_error($ch);
            echo "cURL Error: " . $error_msg;
        }
        curl_close($ch);
        if ($response) {
            return json_decode($response);
        } else {
            return "No Response";
        }
    }

    private function signDataWithPrivateKey($data, $privateKeyFile) {
        // 读取私钥文件
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyFile));
        
        // 确保私钥是有效的
        if ($privateKey === false) {
            throw new Exception("无法读取或解析私钥文件");
        }
    
        // 创建一个用于保存签名的变量
        $signature = '';
    
        // 使用SHA256算法和私钥对数据进行签名
        // 注意：OpenSSL中的'sha256'实际上对应SHA256withRSA
        if (!openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new Exception("签名失败");
        }
    
        // 将签名编码为Base64格式
        $base64Signature = base64_encode($signature);
    
        // 清理私钥资源
        openssl_free_key($privateKey);
    
        // 返回Base64编码的签名
        return $base64Signature;
    }
    

}
