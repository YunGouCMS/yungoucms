<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class dewipay_url extends basepay
{
    private $pay_name = 'dewipay';

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
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['merchant_trx_id']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", G_WEB_PATH . "/index/pages/users/index?pay_status=fail");
        }
        if (!in_array($data['tradeState'],[1])) {
            _messageerror("支付失败!", G_WEB_PATH . "/index/pages/users/index?pay_status=fail");
        }
        sleep(1);
        $out_trade_no = $orderid;    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

        _message("充值成功!", G_WEB_PATH . "/index/pages/users/index?pay_status=success");
    }

    /**
     * 
     * 
     * Example Callback Data: 
        uuid required string DewiPay unique ID
        merchant_trx_id required string Unique ID sent by merchant to DewiPay
        amount required integer Nominal Amount ex : 10000
        title required string Payment Link Title
        description required string Payment Link Description
        return_url required string URL where the end-customer is redirected if the payment finished
        callback_url	 string The URL to receive payment notification after payment has been made by end user Server side
        created_time required string ISO 8601 Timestamp for payment created. Timezone UTC+7
        expired_time required string Validity period using ISO-8601 format. Timezone UTC+7
        payment_link_url	 string The URL for the merchant's front-end to redirect the end-customer to DewiPay's page. Merchant should not impose any kind of restrictions for this URL, such as whitelisting domain and ip
        status required string Status of payment transaction. Only the detail transaction will be settled
            Key	Value
            ACTIVE	Payment transaction can be paid by end user
            FAILED	Payment transaction has failed
            PAID	Payment transaction has been paid
        status_message	 string Additional message for status, mostly for FAILED status.
        paid_time	 string ISO 8601 Timestamp for payment paid. Timezone UTC+7 Available only if the payment paid
        paid_detail	 object Paid payment's detail
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
        if (!in_array($data['status'],["PAID"])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['merchant_trx_id']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"SUCCESS");
        exit;
    }

/*     {
        "amount": "30.00",
        "country": "BR",
        "currency": "BRL",
        "order_id": "TP1676332148695",
        "settlement_id": "S101100045_1625281028186836992",
        "signature": "0A1BF542882F8C01B4906D504706275E",
        "status": "SUCCESS",
        "status_code": 100,
        "status_detail": "Cashout is successed",
        "timestamp": 1676675261965
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
}
