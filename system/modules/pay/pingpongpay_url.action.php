<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class pingpongpay_url extends basepay
{
    private $pay_name = 'pingpongpay';

    public function qiantai()
    {

        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        $web=System::load_sys_config('system');
 		if(isset($web['pay_prefix'])&&$web['pay_prefix']){
            $pay_prefix = $web['pay_prefix'];
        }else{
            $pay_prefix = 'C';
        }
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['merchantTransactionId']);//回调订单号
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", G_WEB_PATH . "/index/pages/users/index");
        }
        // if (!in_array($data['status'],['SUCCESS'])) {
        //     _messageerror("支付失败!", G_WEB_PATH . "/index/pages/users/index");
        // }
        sleep(1);
        $out_trade_no = $orderid;    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        if(!$dingdaninfo){
            _messageerror("充值失败!", G_WEB_PATH . "/index/pages/users/index");
        }
        if ($dingdaninfo['status'] == "已付款") {
            _message("充值成功!", G_WEB_PATH . "/index/pages/users/index");
        }else{
            _message("充值中，请留意账号金额!", G_WEB_PATH . "/index/pages/users/index");
        }
        _message("充值成功!", G_WEB_PATH . "/index/pages/users/index");
    }

    /**
     * 
     * 
     * Example Callback Data: 
        参数名	示例值	说明
        amount		金额
        payOrderId		支付订单号
        mchNo		商户号
        mchOrderNo		商户订单号
        ifCode		支付编码
        state		支付状态
        clientIp		
        createdAt		创建时间戳 13位
        successTime		订单支付成功时间
        channelOrderNo		渠道订单号
        errCode		渠道错误码
        errMsg		渠道错误描述
        createdAt		创建时间
        reqTime		通知请求时间
        sign		签名
     * */
    public function houtai()
    {
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        $day = date("Y-m-d");
        save_log($data, "pay_log/{$this->pay_name}_$day.log");
        if (!$data) {
            echo 'fail1';
            exit;
        }
        // $config = $this->db->getOne("select * from `@#_pay` where `pay_class` = '$this->pay_name'");
        // $pay_key = mb_unserialize($config['pay_key']);
        // $salt = $pay_key['salt']['val'];
        //验证签名
        // $ver = new PingPongCheckoutDecrypt();
        // $verfy_sign = $ver->decryptRequest($salt,json_encode($data,true));
        // if(!$verfy_sign){
        //     throw new Exception('响应签名验证失败');
        // }
        if($data['code'] == "000000"){
            $data = $data['bizContent'];
        }

        if (!in_array($data['status'],['SUCCESS'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['merchantTransactionId']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"SUCCESS","pay_log/{$this->pay_name}_$day.log");
        exit;
    }

/*     {

      } */
      /**
       * 代付回调
       *
       * @return void
       */
    public function houtai_withdrawal(){
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        $day = date("Y-m-d");
        save_log($data, "pay_log/{$this->pay_name}_w_$day.log");
        if (!$data) {
            echo 'fail1';
            exit;
        }
        if (!in_array($data['status'],["SUCCESS"])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $order_id = explode("_",$data['order_id']);
        $this->handle_withdrawal($order_id,$status);

    }

    public function order_query(){
        $data = $_GET;
        $out_trade_no = $data["code"];    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        $status = 1;
        if($dingdaninfo['status'] == '已付款'){
           $status = 2;
        }
        if($dingdaninfo['status'] == '取消'){
            $status = 3;
        }
        $dingdaninfo['status'] = $status;
        echo json_encode(["code"=>200, "status"=>$status,"res"=>$dingdaninfo]);
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