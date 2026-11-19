<?php
/**
 * PingPongx V4 支付接口封装类
 * 基于V4 API文档 - 下单接口（Hosted模式）
 */

class pingpongpay
{
    private $accId;
    private $clientId;
    private $salt;
    private $baseUrl;
    private $version = '1.0';
    private $signType = 'SHA256';
    
    
    private $country = 'ID'; // 国家编码
    private $currency = 'IDR'; // 货币

    private $config;
    
    public function config($config){        
        $this->config = $config;
        $data = $config['data'];
        $this->accId = $data['accId']['val'];
        $this->clientId = $data['clientId']['val'];
        $this->salt = $data['salt']['val'];
        $this->country = $data['country']['val']??$this->country;
        $this->currency = $data['currency']['val']??$this->currency;
        $isSandbox = $data['isSandbox']['val']??true;
        $this->baseUrl = $isSandbox ? 
            'https://sandbox-acquirer-payment.pingpongx.com' : 
            'https://acquirer-payment.pingpongx.com';
        $money = number_format($config['money'], 0, '.', '');
        try {
            // 简单业务参数
            $bizContent = [
                'captureDelayHours' => '0',
                'amount' => $money,
                'merchantTransactionId' => $config['code'],
                'payResultUrl' => $config['ReturnUrl']."?merchantTransactionId=".$config['code'],
                'payCancelUrl' => $config['ReturnUrl'],
                'notificationUrl' => $config['NotifyUrl'],
                'shopperIP' => $config['ip'],
                "paymentMethods"=>["VISA","Mastercard"],
                // 'customer' => [
                //     'identificationId' => '211',
                //     'lastName' => 'LeBronbb',
                //     'email' => 'test@pingpongx.com',
                //     'phone' => '9157030054'
                // ],
                'goods' => [
                    'name'  => "Recharge",
                    'imgUrl'  => G_UPLOAD_PATH.'/'. $config['pay_thumb'],
                    'number'    => 1,
                    'unitPrice' => $money,
                ],
                'billingAddress'    =>[
                    'street'    =>'账单人街道',
                    'postcode'    =>'123456',
                    'city'    =>'北京',
                    'state'    =>'北京',
                    'country'   =>  $this->country,
                ],
                'currency'  => $this->currency,
            ];
            
            $res = $this->prePay($bizContent);
           /*
            return example:
             array (
                'success' => true,
                'payment_url' => 'https: //sandbox-safepay.pingpongx.com?token=REDACTED_CONFIGURE_LOCALLY',
                'transaction_id' => '25111000000450240389',
                'token' => 'REDACTED_CONFIGURE_LOCALLY',
            ) */
            
            if($res['code']=="000000"){
                $res = json_decode($res['bizContent'],true);
                $data = [
                    "code" => 0,
                    "order_no"=>$config['code'],
                    "data"=>$res['paymentUrl'],
                    // "url"=>G_WEB_PATH."/index.php/pay/nedpay_url/order_query?code=".$requestData['biz_content']['merch_order_id'],
                    "msg"=>"ok"
                ];
            }else{
                $data = [
                    "code" => 500,
                    "order_no"=>$config['code'],
                    "data"=>"",
                    // "url"=>G_WEB_PATH."/index.php/pay/nedpay_url/order_query?code=".$requestData['biz_content']['merch_order_id'],
                    "msg"=>"error",
                    "result"=>$res,
                ];
            }
            echo json_encode($data);
        } catch (Exception $e) {
            $res =  [
                'success' => false,
                'error' => $e->getMessage()
            ];
            echo json_encode($res);
        }
        return $res;
    }   

    /**
     * 创建订单
     * @param mixed $amount
     * @param mixed $currency
     * @param mixed $orderNo
     * @param mixed $notifyUrl
     * @param mixed $returnUrl
     * @param mixed $subject
     * @param mixed $body
     * @param mixed $extra
     * @return void
     */
    public function createOrder($amount, $currency, $orderNo, $notifyUrl, $returnUrl, $subject, $body, $extra = []){

    }
    
    /**
     * 生成签名
     * @param array $params 请求参数
     * @return string 签名
     */
    private function generateSignature($params)
    {
        // 移除sign参数
        if (isset($params['sign'])) {
            unset($params['sign']);
        }
        
        // 参数按ASCII码从小到大排序
        ksort($params);
        
        // 拼接参数
        $stringToSign = '';
        foreach ($params as $key => $value) {
            if ($value !== '' && $value !== null) {
                $stringToSign .= $key . '=' . $value . '&';
            }
        }
        $stringToSign = rtrim($stringToSign, '&');
        // 使用HMAC-SHA256生成签名
        return strtoupper(hash_hmac('sha256', $stringToSign, $this->salt));
    }
    
    /**
     * 验证响应签名
     * @param array $response 响应数据
     * @return bool 验证结果
     */
    public function verifyResponseSignature($response)
    {
        var_export($response);
        if (!isset($response['sign'])) {
            return false;
        }
        
        $receivedSign = $response['sign'];
        $expectedSign = $this->generateSignature($response);
       var_dump($expectedSign);
        return hash_equals($expectedSign, $receivedSign);
    }
    
    /**
     * HTTP POST请求
     * @param string $endpoint 接口端点
     * @param array $data 请求数据
     * @return array 响应数据
     */
    private function httpPost($endpoint, $data,$header = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'User-Agent: PingPongx-PHP-Client/1.0'
        ];
        if (!empty($header)) {
            $headers = array_merge($headers, $header);
        }
        
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        // var_export(["header"=>$headers,"param"=>$jsonData,"url"=>$url,"bizContent"=>$data['bizContent']]);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('CURL请求失败: ' . $error);
        }
        
        if ($httpCode !== 200) {
            throw new Exception('HTTP请求失败，状态码: ' . $httpCode . ', 响应: ' . $response);
        }
        
        $result = json_decode($response, true);
        
        if (!$result) {
            throw new Exception('响应解析失败: ' . $response);
        }
        
        // 验证响应签名
        // if (!$this->verifyResponseSignature($result)) {
        //     throw new Exception('响应签名验证失败');
        // }
        
        return $result;
    }
    
    /**
     * 下单接口（Hosted模式）
     * @param array $bizContent 业务参数
     * @param array $riskControlParams 风控参数（可选）
     * @return array 响应数据
     */
    public function prePay($bizContent, $riskControlParams = [])
    {
        // 合并业务参数和风控参数
        if (!empty($riskControlParams)) {
            $bizContent = array_merge($bizContent, $riskControlParams);
        }
        
        // 构建请求参数
        $requestData = [
            'accId' => $this->accId,
            'clientId' => $this->clientId,
            'signType' => $this->signType,
            'version' => $this->version,
            'bizContent' =>$bizContent
        ];
        // 生成签名
        $PingPongCheckoutClient = new PingPongCheckoutClient();
        $requestData = $PingPongCheckoutClient->signRequest($this->salt,$requestData);
        //验证签名
        $ver = new PingPongCheckoutDecrypt();
        $verfy_sign = $ver->decryptRequest($this->salt,json_encode($requestData,true));
        if(!$verfy_sign){
            throw new Exception('响应签名验证失败');
        }
        $res = $this->httpPost('/v4/payment/prePay', $requestData,$requestData);
        return $res;
    }
    
    /**
     * 解析业务响应参数
     * @param array $response API响应
     * @return array 业务参数
     */
    public function parseBizContent($response)
    {
        if (isset($response['bizContent']) && is_string($response['bizContent'])) {
            return json_decode($response['bizContent'], true);
        }
        return [];
    }
    
    /**
     * 检查响应是否成功
     * @param array $response API响应
     * @return bool 是否成功
     */
    public function isSuccess($response)
    {
        return isset($response['code']) && $response['code'] === '000000';
    }
    
    /**
     * 获取错误信息
     * @param array $response API响应
     * @return string 错误信息
     */
    public function getErrorMessage($response)
    {
        return $response['description'] ?? '未知错误';
    }
}

/**
 * 业务参数构建器
 */
class BizContentBuilder
{
    private $data = [];
    
    /**
     * 设置立即capture
     */
    public function setCaptureImmediate()
    {
        $this->data['captureDelayHours'] = 0;
        return $this;
    }
    
    /**
     * 设置手动capture
     */
    public function setCaptureManual()
    {
        $this->data['captureDelayHours'] = -1;
        return $this;
    }
    
    /**
     * 设置交易金额和币种
     */
    public function setAmount($amount, $currency = 'USD')
    {
        $this->data['amount'] = (string)$amount;
        $this->data['currency'] = $currency;
        return $this;
    }
    
    /**
     * 设置商户订单号
     */
    public function setMerchantTransactionId($transactionId)
    {
        $this->data['merchantTransactionId'] = $transactionId;
        return $this;
    }
    
    /**
     * 设置支付结果URL
     */
    public function setPayResultUrl($url)
    {
        $this->data['payResultUrl'] = $url;
        return $this;
    }
    
    /**
     * 设置取消支付URL
     */
    public function setPayCancelUrl($url)
    {
        $this->data['payCancelUrl'] = $url;
        return $this;
    }
    
    /**
     * 设置通知URL
     */
    public function setNotificationUrl($url)
    {
        $this->data['notificationUrl'] = $url;
        return $this;
    }
    
    /**
     * 设置关单时间
     */
    public function setTimeExpire($timestamp)
    {
        $this->data['timeExpire'] = (string)$timestamp;
        return $this;
    }
    
    /**
     * 设置备注
     */
    public function setRemark($remark)
    {
        $this->data['remark'] = $remark;
        return $this;
    }
    
    /**
     * 设置语言
     */
    public function setLanguage($language = 'en')
    {
        $this->data['language'] = $language;
        return $this;
    }
    
    /**
     * 设置交易国家
     */
    public function setTradeCountry($country)
    {
        $this->data['tradeCountry'] = $country;
        return $this;
    }
    
    /**
     * 设置商户来源
     */
    public function setMerchantSource($source)
    {
        $this->data['merchantSource'] = $source;
        return $this;
    }
    
    /**
     * 设置用户IP
     */
    public function setShopperIP($ip)
    {
        $this->data['shopperIP'] = $ip;
        return $this;
    }
    
    /**
     * 设置动态账单描述
     */
    public function setMerchantRequestBillDescriptor($descriptor)
    {
        $this->data['merchantRequestBillDescriptor'] = $descriptor;
        return $this;
    }
    
    /**
     * 设置会员ID
     */
    public function setMerchantUserId($userId)
    {
        $this->data['merchantUserId'] = $userId;
        return $this;
    }
    
    /**
     * 设置支付方式
     */
    public function setPaymentMethods($methods)
    {
        $this->data['paymentMethods'] = $methods;
        return $this;
    }
    
    /**
     * 设置3DS验证参数
     */
    public function setThreeDSRequestData($threeDSData)
    {
        $this->data['threeDSRequestData'] = $threeDSData;
        return $this;
    }
    
    /**
     * 获取构建的业务参数
     */
    public function build()
    {
        return $this->data;
    }
}

/**
 * 风控参数构建器
 */
class RiskControlBuilder
{
    private $data = [];
    
    /**
     * 设置客户信息
     */
    public function setCustomer($customerData)
    {
        $this->data['customer'] = $customerData;
        return $this;
    }
    
    /**
     * 设置商品信息
     */
    public function setGoods($goods)
    {
        $this->data['goods'] = $goods;
        return $this;
    }
    
    /**
     * 设置送货地址
     */
    public function setShippingAddress($address)
    {
        $this->data['shippingAddress'] = $address;
        return $this;
    }
    
    /**
     * 设置账单地址
     */
    public function setBillingAddress($address)
    {
        $this->data['billingAddress'] = $address;
        return $this;
    }
    
    /**
     * 设置电商信息
     */
    public function setECommerce($ecommerceData)
    {
        $this->data['eCommerce'] = $ecommerceData;
        return $this;
    }
    
    /**
     * 设置航空行业信息
     */
    public function setAirline($airlineData)
    {
        $this->data['airline'] = $airlineData;
        return $this;
    }
    
    /**
     * 设置充值信息
     */
    public function setReCharge($rechargeData)
    {
        $this->data['reCharge'] = $rechargeData;
        return $this;
    }
    
    /**
     * 设置租车行业信息
     */
    public function setCarRental($carRentalData)
    {
        $this->data['carRental'] = $carRentalData;
        return $this;
    }
    
    /**
     * 设置浏览器信息
     */
    public function setBrowserInfo($browserInfo)
    {
        $this->data['browserInfo'] = $browserInfo;
        return $this;
    }
    
    /**
     * 设置设备信息
     */
    public function setDevice($deviceInfo)
    {
        $this->data['device'] = $deviceInfo;
        return $this;
    }
    
    /**
     * 获取构建的风控参数
     */
    public function build()
    {
        return $this->data;
    }
}

/**
 * 这个类用于对请求内容进行签名，以确保请求的安全性。
 */

 class PingPongCheckoutClient
 {
     const SignAlgorithm = ['MD5', 'SHA256']; // 签名加密方式
 
     /**
      * 对请求内容进行签名，并将签名结果添加到请求参数中
      *
      * @param string $salt
      * @param array $requestBody
      *
      * @return array
      */
     public function signRequest(string $salt, array $requestBody)
     {
         //参数校验
         $this->validate($requestBody);
         //拼接请求参数
         $signContent = $this->signContent($salt, $requestBody);
         $sign = $this->getSign($signContent, $requestBody);
         $requestBody['sign'] = strtoupper($sign);
         if (is_array($requestBody['bizContent'])) {
             $requestBody['bizContent'] = json_encode($requestBody['bizContent'], JSON_UNESCAPED_SLASHES);
         }
         return $requestBody;
     }
 
     /**
      * 拼接请求参数
      *
      * @param string $salt
      * @param array $requestBody
      * @return array|string
      */
     public function signContent(string $salt, array $requestBody)
     {
         unset($requestBody['sign']);
         if (is_array($requestBody['bizContent'])) {
             $requestBody['bizContent'] = json_encode($requestBody['bizContent'],JSON_UNESCAPED_SLASHES);
         }
         //根据键进行排序
         ksort($requestBody);
         $signContent = urldecode(http_build_query($requestBody));
         $signContent = $salt . $signContent;
         return $signContent;
     }
 
 
     /**
      * 获取请求内容的签名结果。
      *
      * @param string $signContent
      * @param array $requestBody
      * @return array|string
      */
     public function getSign(string $signContent, array $requestBody)
     {
         switch ($requestBody['signType']) {
             case "MD5":
                 $sign = $this->md5Sign($signContent);
                 break;
             case "SHA256":
                 $sign = $this->sha256($signContent);
                 break;
         }
         return $sign;
     }
 
     /**
      * md5 加密
      * 
      * @param string $signContent
      * @return string
      */
     public function md5Sign(string $signContent)
     {
         return md5($signContent);
     }
 
     /**
      * sha256 加密
      *
      * @param string $signContent
      * @return string
      */
     public function sha256(string $signContent)
     {
         return hash("sha256", $signContent);
     }
 
 
     /**
      * 对于参与的签名参数进行校验
      *
      * @param array $requestBody
      * @return true
      * @throws \Exception
      */
     public function validate(array $requestBody)
     {
         $keys = ['accId', 'clientId', 'signType', 'version', 'bizContent'];
         foreach ($keys as $key) {
             if (!isset($requestBody[$key]) || empty($requestBody[$key])) {
                 throw new \Exception('invalid -' . $key);
             }
         }
         if (!array($requestBody['signType'], self::SignAlgorithm)) {
             throw new \Exception('invalid - signType' );
         }
         if (!is_array($requestBody['bizContent']) && !is_string($requestBody['bizContent'])) {
             throw new \Exception('invalid - bizContent' );
         }
         return true;
     }
 }

 class PingPongCheckoutDecrypt
{
    /**
     * 对异步通知内容进行验签
     *
     * @param string $salt
     * @param array $requestBody {"clientId":"2023120712300910285","code":"000000","bizContent":"{\"exchangedCurrency\":\"USD\",\"amount\":\"1.080000\",\"authenticationInfo\":{\"avsResult\":\"Unknown\",\"cvvResult\":\"Y\",\"threeDSecure\":\"N\"},\"cardInfo\":{\"firstName\":\"James\",\"isoCountryA2\":\"RU\",\"lastName\":\"LeBron\",\"lastFourDigits\":\"1112\",\"cardLevel\":\"CLASSIC\",\"paymentBrand\":\"VISA\",\"cardType\":\"CREDIT\",\"issuringBank\":\"\",\"ipCountry\":\"CN\",\"firstSixDigits\":\"401200\",\"isoCountry\":\"RUSSIAN FEDERATION\"},\"issuerInfo\":{\"issuerResultMsg\":\"Successful approval/completion or V .I.P .PIN\\n verification is successful\",\"issuerResultCode\":\"00\"},\"threeDSecure\":\"\",\"remark\":\"Remark customer defined txt\",\"transactionTime\":\"1702349526000\",\"transactionId\":\"2023121250013357\",\"notifyType\":\"RECHARGE\",\"requestId\":\"3931b66e-78ba-4107-d75d-098a005f7bc3\",\"merchantTransactionId\":\"PShop2023121210510ES\",\"paymentMethod\":{\"type\":\"VISA\"},\"currency\":\"USD\",\"exchangedAmount\":\"1.080000\",\"captureDelayHours\":0,\"status\":\"SUCCESS\"}","sign":"B9BFE38FFE24B81FCAA41FC46F5560CB","accId":"2023120712300910285520","description":"Transaction succeeded","signType":"MD5"}
     *
     * @return boolean
     */
    public function decryptRequest(string $salt, string $requestBody)
    {
        //拼接请求参数
        $signData = $this->signContent($salt, $requestBody);
        $sign = $this->getSign($signData['signContent'], $signData['data']);
        if ($signData['sign'] == strtoupper($sign)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 拼接请求参数
     *
     * @param string $salt
     * @param array $requestBody
     * @return array|string
     */
    public function signContent(string $salt, string $requestBody)
    {
        //替换特殊字符
        $requestBody = str_replace("\\n", '\\\\n', $requestBody);
        $requestBody = str_replace("\\r", '\\\\r', $requestBody);
        $requestBody = str_replace("\\f", '\\\\f', $requestBody);
        $requestBody = str_replace("\\t", '\\\\t', $requestBody);
        $requestBody = str_replace("\\v", '\\\\v', $requestBody);
        $data = json_decode($requestBody,true);
        if (!$data) {
            if (function_exists('json_last_error') && json_last_error() != 0) {
                $error = json_last_error_msg();
            } else {
                $error = 'data does not meet the requirements';
            }
            throw new \Exception($error);
        }
        $sign = $data['sign'];
        unset($data['sign']);
        //根据键进行排序
        ksort($data);
        $signContent = urldecode(http_build_query($data));
        $signContent = $salt . $signContent;
        return ['sign' => $sign, 'data' => $data, 'signContent' => $signContent];
    }

    /**
     * 获取请求内容的签名结果。
     *
     * @param string $signContent
     * @param array $requestBody
     * @return array|string
     */
    public function getSign(string $signContent, array $requestBody)
    {
        switch ($requestBody['signType']) {
            case "MD5":
                $sign = $this->md5Sign($signContent);
                break;
            case "SHA256":
                $sign = $this->sha256($signContent);
                break;
        }
        return $sign;
    }

    /**
     * md5 加密
     * 
     * @param string $signContent
     * @return string
     */
    public function md5Sign(string $signContent)
    {
        return md5($signContent);
    }

    /**
     * sha256 加密
     *
     * @param string $signContent
     * @return string
     */
    public function sha256(string $signContent)
    {
        return hash("sha256", $signContent);
    }

}

// 使用示例
class PaymentExample
{
    public static function run()
    {
        // 初始化支付对象
        $payment = new pingpongpay(
            '2018092714313010016291', // accId
            '2018092714313010016',    // clientId
            'your_secret_key_here',   // secretKey
            true                      // 沙箱环境
        );
        
        try {
            // 构建业务参数
            $bizBuilder = new BizContentBuilder();
            $bizContent = $bizBuilder
                ->setCaptureImmediate()
                ->setAmount('1000', 'USD')
                ->setMerchantTransactionId('PMT-' . uniqid() . time())
                ->setPayResultUrl('https://your-domain.com/pay/result')
                ->setPayCancelUrl('https://your-domain.com/pay/cancel')
                ->setNotificationUrl('https://your-domain.com/notify/payment')
                ->setShopperIP('222.126.52.23')
                ->setLanguage('en')
                ->build();
            
            // 构建风控参数（可选）
            $riskBuilder = new RiskControlBuilder();
            $riskControlParams = $riskBuilder
                ->setCustomer([
                    'identificationId' => '211',
                    'lastName' => 'LeBronbb',
                    'email' => 'test@pingpongx.com',
                    'phone' => '9157030054'
                ])
                ->setGoods([
                    [
                        'goodsId' => '001',
                        'goodsName' => '测试商品',
                        'quantity' => 1,
                        'price' => '1000'
                    ]
                ])
                ->setBillingAddress([
                    'firstName' => 'LeBron',
                    'lastName' => 'James',
                    'address1' => '123 Test St',
                    'city' => 'Test City',
                    'country' => 'US'
                ])
                ->build();
            
            // 调用下单接口
            $response = $payment->prePay($bizContent, $riskControlParams);
            
            var_export($response);die;
            // 处理响应
            if ($payment->isSuccess($response)) {
                $bizResponse = $payment->parseBizContent($response);
                
                echo "下单成功!\n";
                echo "交易ID: " . ($bizResponse['transactionId'] ?? '') . "\n";
                echo "支付URL: " . ($bizResponse['paymentUrl'] ?? '') . "\n";
                echo "Token: " . ($bizResponse['token'] ?? '') . "\n";
                
                // 这里可以重定向到支付URL或使用JS SDK
                if (isset($bizResponse['paymentUrl'])) {
                    // 重定向到支付页面
                    // header('Location: ' . $bizResponse['paymentUrl']);
                    // exit;
                }
                
                if (isset($bizResponse['innerJsUrl'])) {
                    // 使用JS SDK方式
                    echo "JS SDK URL: " . $bizResponse['innerJsUrl'] . "\n";
                }
                
            } else {
                echo "下单失败: " . $payment->getErrorMessage($response) . "\n";
            }
            
        } catch (Exception $e) {
            echo "支付请求异常: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * 简单下单示例
     */
    public static function simpleExample()
    {
        $payment = new pingpongpay(
            '2018092714313010016291',
            '2018092714313010016',
            'your_secret_key_here',
            true
        );
        
        try {
            // 简单业务参数
            $bizContent = [
                'captureDelayHours' => '0',
                'amount' => '1000',
                'currency' => 'USD',
                'merchantTransactionId' => 'PMT-' . uniqid() . time(),
                'payResultUrl' => 'https://your-domain.com/pay/result',
                'payCancelUrl' => 'https://your-domain.com/pay/cancel',
                'notificationUrl' => 'https://your-domain.com/notify/payment',
                'shopperIP' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'customer' => [
                    'identificationId' => '211',
                    'lastName' => 'LeBronbb',
                    'email' => 'test@pingpongx.com',
                    'phone' => '9157030054'
                ]
            ];
            
            $response = $payment->prePay($bizContent);
            
            if ($payment->isSuccess($response)) {
                $bizResponse = $payment->parseBizContent($response);
                return [
                    'success' => true,
                    'payment_url' => $bizResponse['paymentUrl'] ?? '',
                    'transaction_id' => $bizResponse['transactionId'] ?? '',
                    'token' => $bizResponse['token'] ?? ''
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $payment->getErrorMessage($response)
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

?>