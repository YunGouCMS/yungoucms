<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class joglobalpay_url extends basepay
{
    private $pay_name = 'joglobalpay';
    
    public function qiantai()
    {

        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        if(!$data){
            $data = $_GET;
        }
        $web=System::load_sys_config('system');
 		if(isset($web['pay_prefix'])&&$web['pay_prefix']){
            $pay_prefix = $web['pay_prefix'];
        }else{
            $pay_prefix = 'C';
        }
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        save_log($data,$this->get_logpath($this->pay_name));
        if (empty($orderid)) {
            _messageerror("充值失败!", WEB_PATH . "/index/pages/users/index");
        }
        if (!in_array($data['trade_status'],["TRADE_SUCCESS"])) {
            _messageerror("支付失败!", WEB_PATH . "/index/pages/users/index");
        }
        sleep(1);
        $out_trade_no = $orderid;    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

        _message("充值成功!", WEB_PATH . "/index/pages/users/index");
    }

    /**
     * 
     * 
     * Example Callback Data: 
        {
        "mchNo": "string",
        "appId": "string",
        "mchOrderNo": "string",
        "amount": 0,
        "wayCode": "string",
        "currency": "string",
        "subject": "string",
        "body": "string",
        "notifyUrl": "string",
        "returnUrl": "string",
        "state": "string",
        "userid": "string",
        "signType": "string",
        "sign": "string"
        }
     * */
    public function houtai()
    {
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        if(!$data){
            $data = $_GET;
        }
        $path = $this->get_logpath($this->pay_name);
        save_log($data,$path);
        if (!$data) {
            echo 'fail1';
            exit;
        }
        if (!in_array($data['state'],[2])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['mchOrderNo']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"success",$path);
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
        if (!in_array($data['trade_status'],["SUCCESS"])) {
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
