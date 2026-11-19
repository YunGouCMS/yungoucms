<?php 

class dewipay {
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
        return $this->createPaymentLink();
	}

    public function refund(){

    }

    public function createPaymentLink() {
        $pay_input = $this->config['pay_input'];
        $url = "";
        $configData = $this->config['data'];
        $url = $configData['url']['val'].$url;
        $bearerToken = $configData['token']['val'];
        //订单号
        $data['merchant_trx_id'] = $order_no = $this->config['u_id'].$this->config['code'];
        //金额
        $data['amount'] = number_format($this->config['money'],0,'.','');
        // 标题
        $data['title'] = $this->config['zxgm'];
        // 过期时间
        $data['expired_time'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
        // 跳转地址
        $data['return_url'] = $this->config['ReturnUrl'];
        // 回调地址
        $data['callback_url'] = $this->config['NotifyUrl'];
        // 验证必需参数
        $requiredParams = ['merchant_trx_id', 'amount', 'title', 'expired_time', 'return_url'];
        foreach ($requiredParams as $param) {
            if (empty($data[$param])) {
                return ['success' => false, 'error' => "缺少必需参数: $param"];
            }
        }
        // $data['payment_channel'] = 'QRIS';
        // $data['payment_method'] = 'qris';
        if(isset($pay_input['payment_channel']) && $pay_input['payment_channel']){
            $data["payment_channel"] = $pay_input['payment_channel'];
        }else{
            $ret = ["code" => 500,'success' => false, 'error' => "缺少必需参数: payment_channel"];
            echo json_encode($ret);
            return $ret;
        }
        if(isset($pay_input['payment_method']) && $pay_input['payment_method']){
            $data["payment_method"] = $pay_input['payment_method'];
        }else{
            $ret = ["code" => 500,'success' => false, 'error' => "缺少必需参数: payment_method"];
            echo json_encode($ret);
            return $ret;
        }
        $res = $this->curlPost($url,$data,$bearerToken);
        /* {
            "status": 201,
            "success": true,
            "data": {
              "uuid": "773cacdc-909a-4dbb-b19a-9f60ec148cc3",
              "merchant_trx_id": "ID_1655298690000",
              "amount": 10000,
              "title": "Test",
              "description": "Test Description",
              "return_url": "https://mock.test.dewipay.id/merchant_callback",
              "callback_url": "https://mock.test.dewipay.id/merchant_callback",
              "status": "ACTIVE",
              "created_time": "2022-06-14T21:11:33+07:00",
              "expired_time": "2022-06-15T21:11:33+07:00",
              "payment_link_url": "https://dewipay.id/share/payment-links/773cacdc-909a-4dbb-b19a-9f60ec148cc3"
            }
          } */
        if($res['success']){
            $ret = $res['data'];
            $data = [
                "code" => 0,
                "order_no"=>$order_no,
                "data"=>$ret['data']['payment_link_url'],
                // "url"=>G_WEB_PATH."", //跳转地址 用于支付方未提供时使用
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data['pay_status_code']=1;
            return $data;
        }else{
 			if(isset($res['response']['error']['fields'])){
                $data = $res['response']['error']['fields']??[];
                foreach ($data as $key => $value) {
                    $res['msg'] = $value[0];
                }
            }
            echo json_encode($res);
            $res['pay_status_code']=0;
            return $res;
        }
    }

    private function curlPost($url,$data,$bearerToken){
        // 准备请求数据
        $postData = http_build_query($data);
        // 初始化 cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $bearerToken,
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        // 执行请求
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 处理响应
        if ($httpCode === 200 || $httpCode == 201) {
            $responseData = json_decode($response, true);
            return ['success' => true, 'data' => $responseData];
        } else if($httpCode==401 || $httpCode == 422){
            return ['success' => false, 'response' => json_decode($response, true)];
        }else{
            return ['success' => false, 'error' => "API 请求失败，HTTP 状态码: $httpCode", 'response' => $response];
        }
    }
}