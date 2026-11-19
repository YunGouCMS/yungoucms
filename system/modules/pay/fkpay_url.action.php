<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class fkpay_url extends basepay
{
    private $pay_name = 'fkpaypay';
    
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
        参数	字段名	必填	类型	示例值	描述
        mchId	商户号	是	string	10001	商户号
        productId	支付通道Id	是	string	888	支付通道Id
        tradeNo	支付订单号	是	string	P00001	返回支付系统订单号
        outTradeNo	商户订单号	是	string	SH00001	返回商户传入的订单号
        amount	订单金额 (单位: 分)	是	int	10000	订单金额 (单位: 分)，例如: 10000 即为 100.00 元
        payAmount	实际支付金额 (单位: 分)	是	int	10000	实际支付金额 (单位: 分)，例如: 10000 即为 100.00 元，请用这个金额上分
        state	订单状态	是	int	1	订单状态：0-待支付，1-成功
        createTime	订单创建时间	是	string	2025/01/01 12:00:00	创建时间；格式：yyyy/MM/dd HH:mm:ss
        payTime	订单支付时间	是	string	2025/01/01 12:00:00	订单支付时间；格式：yyyy/MM/dd HH:mm:ss
        sign	签名	是	string	f52ab92457b1a97c1dbbc794de8dea8e	签名值，不参与签名
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
        save_log($data,$this->get_logpath($this->pay_name));
        if (!$data) {
            echo 'fail1';
            exit;
        }
        if (!in_array($data['trade_status'],["TRADE_SUCCESS"])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"success","pay_log/{$this->pay_name}_$day.log");
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
