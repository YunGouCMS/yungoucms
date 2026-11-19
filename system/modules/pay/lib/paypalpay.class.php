<?php

function resultArray($code = 0, $msg = '', $data = [])
{
    return json_encode(['code' => $code, "msg" => $msg, "data" => $data]);
}

class paypalpay
{
    private $config = [];
    // api地址
    // 正式：https://api-m.paypal.com
    // 测试：https://api-m.sandbox.paypal.com
    private $api = "https://api-m.sandbox.paypal.com";
    const PAYPAL_API_TOKEN = 'paypal:token';

    public function config($config = null)
    {

        $data = $config['data'];
        unset($config['data']);
        unset($config['pay_type_data']);
        $this->config = $config;
        if ($data['mode']['val'] == '1') {
            $this->api = "https://api-m.sandbox.paypal.com";
        } else {
            $this->api = "https://api-m.paypal.com";
        }
        $this->config['currency_code'] = $data['currency_code']["val"]?:"USD";
        $this->config['clientId'] = $data['clientId']["val"];
        $this->config['clientSecret'] = $data['clientSecret']["val"];
        // $res = $this->getToken();
        return $this->create();
    }

    /**
     * 获取token，一般返回来的access token有效时是9个小时
     **/
    public function getToken()
    {
        try {
            // 获取缓存中是否有存在
            System::load_sys_fun('redisData');
            $redis  = new redisData;
            $tokens = $redis->redisGet(self::PAYPAL_API_TOKEN);
            if ($tokens['code'] == 11) {
                // 有存在直接返回 access_token
                return resultArray(1, 'SUCCESS', [
                    'access_token' => $tokens['data']
                ]);
            }
            // 使用测试pay
            $clientId = $this->config['clientId'];
            $clientSecret = $this->config['clientSecret'];
            // 对凭证base64编码
            $credentials = base64_encode($clientId . ':' . $clientSecret);
            $url =  $this->api . '/v1/oauth2/token';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $credentials
            ));

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                return resultArray(0, curl_error($ch));
            }
            curl_close($ch);
            // 获取成功写入缓存
            $array = json_decode($result, true);

            $flag = $redis->redisSetex(self::PAYPAL_API_TOKEN, $array['expires_in'],$array['access_token']);
            if ($flag) {
                return resultArray(1, 'SUCCESS', [
                    'access_token' => $array['access_token']
                ]);
            }
        } catch (\Exception $e) {
            return resultArray(0, curl_error($ch));
        }
        return resultArray(0, '获取token失败');
    }

    /**
     * curl 请求 todo ①注释是access-token方式 ②是base64加密凭证
     * @param string $url
     * @param array $data
     * @param string $method
     * @return 
     */
    public function curl( $url = null,  $data = [],  $method = 'GET')
    {
        try {
            $payload = empty($data) ? '' : json_encode($data);

            /*
             *  todo ① Access-Token方式，我的是放缓存，需要从缓存获取，缓存没有话会重新去请求拉取
            $tokens = $this->getToken();
            if ($tokens['code'] != 1) {
                return resultArray(0, $tokens['msg']);
            }
            $token = $tokens['data']['access_token'];
            */

            // todo ② 凭证编码
            $token = base64_encode($this->config['clientId'] . ':' . $this->config['clientSecret']);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            // 将凭证放入请求头
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                /* todo ①  Access-Token方式请求头
                'Authorization: Bearer ' . $token
                */
                // todo ② 直接使用base64加密凭证
                'Authorization: Basic ' . $token
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  //              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 不验证证书的合法性
    //            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 不验证证书中是否设置域名
            } elseif ($method === 'GET') {
                curl_setopt($ch, CURLOPT_HTTPGET, true);
            }

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                return resultArray(0, curl_error($ch));
            }

            $data = json_decode($result, true);
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpStatusCode == 422) {
                $message = $data['message'] ?: "";
                return resultArray(0, 'HTTP 422 Unprocessable entity ERROR:' . $message);
            } else if ($httpStatusCode == 504) {
                return resultArray(0, 'HTTP 504 Gateway Timeout');
            }

            curl_close($ch);
            return resultArray(1, '', $data);
        } catch (\Exception $e) {
            return resultArray(0, $e->getMessage());
        }
    }

    public function create()
    {
        // OrderId是我的自定义订单号
        // $successUrl = 'http://{支付成功跳转地址}?OrderId=' . $inserData['order_id'];
        // $cancelUrl = 'http://{支付失败跳转地址}?OrderId=' . $inserData['order_id'];
        $successUrl = $this->config['ReturnUrl'];
        $cancelUrl = $this->config['CancelUrl'];

        // 创建订单
        $money = number_format($this->config['money'],2,".",'');
        $url = $this->api . "/v2/checkout/orders";
        $data = array(
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $this->config['code'],
                    'description' => "充值",
                    'amount' => [
                        // 货币缩写美元
                        'currency_code' => $this->config["currency_code"]?:'USD',
                        // 金额支持小数后两位
                        'value' => $money
                    ]
                ]
            ],
            'application_context' => [
                'shipping_preference' => 'NO_SHIPPING',
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]
        );
        // 调用上面封装的curl
        $result = $this->curl($url, $data, 'POST');
        save_log(["data" => $result, "msg" => "下单"], "paypalpay" . date("Y-m-d") . ".log", 1);
        $result = json_decode($result,true);
        // {"code":1,"msg":"","data":{"id":"72U66110HM466172T","status":"CREATED","links":[{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/72U66110HM466172T","rel":"self","method":"GET"},{"href":"https:\\/\\/www.sandbox.paypal.com\\/checkoutnow?token=72U66110HM466172T","rel":"approve","method":"GET"},{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/72U66110HM466172T","rel":"update","method":"PATCH"},{"href":"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/72U66110HM466172T\\/capture","rel":"capture","method":"POST"}]}}
        if ($result['code'] == 1) {
            // 下单成功有返回一个id需要记录，后续跳转到成功支付页面，用户确认订单信息的时候需要通过这个id做捕获订单付款，不然不会扣除用户账户信息
            // 下单成功之后直接跳转到支付链接（links数组中的第二个是支付页面的链接）
            $links = $result['data']['links'][1]['href'];
            $data = [
                "code" => 0,
                "data"=>$links,
                "msg"=>"ok"
            ];
            if(!$links){
                $data['code'] = 1;
                $data["result"] = $result;
            }
            $id = $result['data']['id'];
            $sql = "update `@#_member_addmoney_record` set order_id = '$id' where `code` = '{$this->config['code']}'";
            $db = System::load_sys_class('model');
            $db->Query($sql);
            echo json_encode($data);
            $data['pay_status_code'] = 1;
            return $data;

            header("Location: {$links}");
            exit(0);
        }
        echo json_encode($result);
        $result['pay_status_code'] = 0;
        return $result;
    }

}
