<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class sunpay_url extends basepay
{
    private $pay_name = 'sunpay';

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
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['cust_ord']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", WEB_PATH . "/mobile/home/userbalance");
        }
        if (!in_array($data['status'],["success","201"])) {
            _messageerror("支付失败!", WEB_PATH . "/mobile/home/userbalance");
        }
        sleep(1);
        $out_trade_no = $orderid;    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        if($dingdaninfo['moeny'] != $data['transferAmount']){
            _messageerror("充值失败，请联系客服!", WEB_PATH . "/mobile/home/userbalance");
        }
        _message("充值成功!", WEB_PATH . "/mobile/home/userbalance");
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
        save_log($data,$this->get_logpath($this->pay_name));
        if (!$data) {
            echo 'fail1';
            exit;
        }
        if (!in_array($data['status'],['success','201'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['cust_ord']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"SUCCESS",$this->get_logpath($this->pay_name),$data['transferAmount']);
        exit;
    }

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
        save_log($data, "pay_log/{$this->pay_name}/w_$day.log");
        if (!$data) {
            echo 'fail';
            exit;
        }
        if (!in_array($data['status'],["SUCCESS",'success'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $order_id = explode("_",$data['cust_ord']);
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
