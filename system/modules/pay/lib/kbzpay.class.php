<?php

class kbzpay
{
    private $appKey;
    private $appId;
    private $merch_code = '2025101401';
    private $is_sandbox = true;

    public function config($config){
        $data = $config['data'];
        $this->appKey = $data['appkey']['val'];
        $this->appId = $data['appid']['val'];
        $this->merch_code = $data['merch_code']['val'];
        $this->is_sandbox = $data['is_sandbox']['val'];
    
        $timestamp = time();
        $strs = "abcdefghijklmnopqrstuvwxyzABCD";
        $rand = rand(6000,99999).$strs[rand(0,25)];
        $money = number_format($config['money'],2,'.','');
        $requestData = [
            "timestamp" => $timestamp,
            "notify_url" => $config['NotifyUrl'],
            "nonce_str" => $rand,
            "sign_type" => "SHA256",
            "method" => "kbz.payment.precreate",
            "sign" => '',
            "version" => "1.0",
            "biz_content" => [
                "merch_order_id" => $config['code'],
                "merch_code" => $this->merch_code, 
                "appid" => $this->appId,
                "trade_type" => "APPH5",
                "title" => $config['zxgm'],
                "total_amount" => $money,
                "trans_currency" => "MMK",
                "timeout_express" => "100m",
                "callback_info" => "callbackinfo"
            ]
        ];
        try {
            // $result = $this->createOrder($requestData);
            $result = $this->pwaPay($requestData);
            if($result['result'] == 'SUCCESS'){
                $result['code'] = 200;
            }
            return $result;
        } catch (Exception $e) {
            echo "签名验证失败: " . $e->getMessage() . "\n";
        }
    }

    /**
     * pwa支付
     */
    public function pwaPay($requestData){
        $url = $this->is_sandbox ? 'http://api.kbzpay.com/payment/gateway/uat/precreate' :'https://api.kbzpay.com/payment/gateway/precreate'; // 实际的支付网关地址
        $requestData['biz_content']['trade_type'] = 'PWAAPP';
        $requestData['sign'] = $this->generateSign($requestData);
        $headers['Content-Type'] = 'application/json';
        $body['Request'] = $requestData;
        $response = $this->httpPost($url, $body, $headers);
        $res = $response['Response'];
        /*
        响应数据
         {
            "result": "SUCCESS",
            "msg": "success",
            "nonce_str": "OB4GVHKXY38BQWGMJUDMR7CIDOFG5COI",
            "code": 200,
            "merch_order_id": "v17624094016577783",
            "sign": "6463DA6CC086D4DFD60AF53EC048481DFB35B4EFF3823DDBBFB63C545D9A425C",
            "sign_type": "SHA256",
            "prepay_id": "KBZ0057020e4cffd1b4f9f96923529e0887d6124002158"
        } */

        $time = $requestData['timestamp'];
        if($res['result'] == 'SUCCESS'){
            $data = [
                "prepay_id" => $res['prepay_id'],
                "merch_code" => $this->merch_code,
                "appid" => $this->appId,
                "timestamp" => $time,
                "nonce_str" => $requestData['nonce_str'],
                "sign_type" => "SHA256",
            ];
            $sign = $this->generateSign($data);
            $order_info = "appid=".$this->appId."&merch_code=".$this->merch_code."&nonce_str=".$requestData['nonce_str']."&prepay_id=".$res['prepay_id']."&timestamp=".$time."&sign=".$sign;
            $url = "https://static.kbzpay.com/pgw/uat/pwa/#/?".$order_info;
            $data = [
                "code" => 0,
                "order_no"=>$requestData['biz_content']['merch_order_id'],
                "data"=>$url,
                "url"=>G_WEB_PATH."/index.php/pay/nedpay_url/order_query?code=".$requestData['biz_content']['merch_order_id'],
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data['pay_status_code']=1;
            return $data;
        }else{
            $data = [
                "code" => 500,
                "order_no"=>$requestData['biz_content']['merch_order_id'],
                "data"=>"",
                "url"=>G_WEB_PATH."/index.php/pay/nedpay_url/order_query?code=".$requestData['biz_content']['merch_order_id'],
                "msg"=>"error",
                "result"=>$res,
            ];
            echo json_encode($data);
        }
        return $res;
    }

    /**
     * HTTP POST请求
     * @param string $endpoint 接口端点
     * @param array $data 请求数据
     * @return array 响应数据
     */
    private function httpPost($url, $data,$headers = [])
    {
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
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
            echo $url;
            throw new Exception('HTTP请求失败，状态码: ' . $httpCode . ', 响应: ' . $response);
        }
        $result = json_decode($response, true);
        
        if (!$result) {
            throw new Exception('响应解析失败: ' . $response);
        }
        
        return $result;
    }

    /**
     * 生成签名
     * @param array $data 参与签名的数据
     * @param array $excludeFields 不参与签名的字段
     * @return string SHA256签名
     */
    public function generateSign($data, $excludeFields = ['sign', 'sign_type'])
    {
        // 处理嵌套的biz_content
        $flattenData = $this->flattenData($data);

        // 过滤参数：排除指定字段、空值字段和列表字段
        $filteredData = $this->filterParams($flattenData, $excludeFields);

        // 按参数名ASCII码从小到大排序
        ksort($filteredData);

        // 拼接字符串stringA
        $stringA = $this->buildQueryString($filteredData);

        // 拼接key
        $stringSign = $stringA . "&key=" . $this->appKey;

        // 生成SHA256签名
        return hash('sha256', $stringSign);
    }

    /**
     * 验证签名
     * @param array $data 待验证的数据（包含sign字段）
     * @param array $excludeFields 不参与签名的字段
     * @return bool 验证结果
     */
    public function verifySign($data, $excludeFields = ['sign', 'sign_type'])
    {
        if (!isset($data['sign'])) {
            return false;
        }

        $receivedSign = $data['sign'];
        $generatedSign = $this->generateSign($data, $excludeFields);
        return strtoupper($receivedSign) === strtoupper($generatedSign);
    }

    /**
     * 生成orderInfo字符串
     * @param array $orderData order数据
     * @return string orderInfo字符串
     */
    public function generateOrderInfo($orderData)
    {
        $sign = $this->generateSign($orderData);
        $orderData['sign'] = $sign;

        return $this->buildOrderInfoString($orderData);
    }

    /**
     * 展平数据结构（处理biz_content等嵌套字段）
     * @param array $data 原始数据
     * @return array 展平后的数据
     */
    private function flattenData($data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($key === 'biz_content' && is_array($value)) {
                // 将biz_content内的参数展开到顶层
                foreach ($value as $subKey => $subValue) {
                    $result[$subKey] = $subValue;
                }
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * 过滤参数：排除指定字段、空值字段和列表字段
     * @param array $data 原始数据
     * @param array $excludeFields 要排除的字段
     * @return array 过滤后的数据
     */
    private function filterParams($data, $excludeFields)
    {
        $filtered = [];

        foreach ($data as $key => $value) {
            // 排除指定字段
            if (in_array($key, $excludeFields)) {
                continue;
            }

            // 排除空值
            if ($value === null || $value === '') {
                continue;
            }

            // 排除列表字段（如refund_info）
            if (is_array($value)) {
                continue;
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }

    /**
     * 构建查询字符串
     * @param array $data 数据
     * @return string URL键值对格式的字符串
     */
    private function buildQueryString($data)
    {
        $parts = [];
        foreach ($data as $key => $value) {
            $parts[] = $key . '=' . $value;
        }

        return implode('&', $parts);
    }

    /**
     * 构建orderInfo字符串
     * @param array $orderData 包含签名的order数据
     * @return string orderInfo字符串
     */
    private function buildOrderInfoString($orderData)
    {
        $parts = [];
        foreach ($orderData as $key => $value) {
            $parts[] = $key . '=' . $value;
        }

        return implode('&', $parts);
    }
}
