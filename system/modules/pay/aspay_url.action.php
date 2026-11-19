<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class aspay_url extends basepay
{
    private $pay_name = 'aspay';

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
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['orderId']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", WEB_PATH . "/mobile/home/userbalance");
        }
        if (!in_array($data['status'],["success","2"])) {
            _messageerror("支付失败!", WEB_PATH . "/mobile/home/userbalance");
        }
        sleep(1);
        $out_trade_no = $orderid;    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

        _message("充值成功!", WEB_PATH . "/mobile/home/userbalance");
    }

    /**
     * 
     * 
     * Example Callback Data: 
    channel int 是 商户号 由B9 Pay提供，发送给商户的后台商户ID 
    orderId string 是 商户平台订单号 商户生成的唯一订单号 
    status int 是 订单状态 2代表成功 -1代表失败 
    money string 是 订单金额 例：100.00，需保留小数点后两位数 
    number string 是 实际交易金额 例：100.00，需保留小数点后两位数 
    currency string 是 通道编码 通道编码 
    sign string 是 签名 详情见签名算法 
     * */
    public function houtai()
    {
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        save_log($data,$this->get_logpath($this->pay_name));
        if (!$data) {
            echo 'fail1';
            exit;
        }
        if (!in_array($data['status'],['success','2'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['orderId']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"SUCCESS",$this->get_logpath($this->pay_name));
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
