<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class kbzpay_url extends basepay
{
    private $pay_name = 'kbzpay';

    public function test(){
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        save_log($data,"pay_log/kbzpay_test.log");
        $header = $_SERVER['HTTP_X_REQUESTED_WITH'];
        save_log($header,"pay_log/kbzpay_test.log");
    }

    public function qiantai()
    {
        //需在商户平台设置结果页跳转url:/index.php/pay/kbzpay_url/qiantai 
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
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['merch_order_id']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", G_WEB_PATH . "/index/pages/users/index");
        }
        // if (!in_array($data['state'],[1])) {
        //     _messageerror("支付失败!", WEB_PATH . "/index/pages/users/index");
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
    }

    /**
     * 
     * 
     * Example Callback Data: 
        参数名	示例值	说明
        appid	String(40)	是	Kp12312b84664d44679693855d82a291	KBZPay 分配的商户 appid
        notify_time	String	是	1563267587	通知的发送时间戳
        merch_code	String	是	200001	KBZPay 分配的商户 Code
        merch_order_id	String	是	201907161732001	支付成功的商户侧订单 id
        mm_order_id	String	是	0101234123456745641	KBZPay 侧交易订单 ID
        total_amount	String	是	2000	本次交易支付的订单金额
        trans_currency	String	是	MMK	交易币种
        trade_status	String	是	PAY_SUCCESS	交易状态
        trans_end_time	String	是	1563267587	该笔交易完成的时间戳
        callback_info	String	否		回调信息，如果商户下单时传递了该参数，则返回给商户时会在异步通知时将该参数原样返回。
        nonce_str	String	是	FD546GFD46GFDA768R7DER	随机字符串
        sign	String	是	BC4EE8D710BAC6A7E33DE4511A1CE7723024615EEF491B80DEF7DC743D4DADBE	响应报文签名
        sign_type	String	是	SHA256	响应报文签名类型
        
        {
            "Request":{
                "notify_time":1576842150,
                "merch_code":"200001",
                "merch_order_id":"0101234123456789012",
                "mm_order_id":"01001814070006560257",
                "trans_currency":"MMK",
                "total_amount":"300",
                "trade_status":"PAY_SUCCESS",
                "trans_end_time":1576834704,
                "callback_info":"urlendcode",
                "nonce_str":"513ba55344ad44c8b69465aae66f7703",
                "sign_type":"SHA256",
                "appid":"kp1234567890987654321aabbccddeef",
                "sign":"A570F3625DE55BD1893491677BD85D904044E4742296F66E62D08B3BFB6C3B29"
            }
        }
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
        $data = $data['Request'];
        //
        if (!in_array($data['trade_status'],['PAY_SUCCESS'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['merch_order_id']);
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
