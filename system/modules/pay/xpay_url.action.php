<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class xpay_url extends basepay
{
    private $pay_name = 'xpay';

    public function qiantai()
    {

        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        //获取url
        $url = $_SERVER['REQUEST_URI'];
        $t = explode("/",$url);
        $type = array_pop($t);
        if($type == "houtai_usdt"){
            $href = "/member/home/userrecharge_usdt";
        }else{
            $href = "/mobile/home/userbalance";
        }
        $data['out_trade_no'] = str_replace($this->pay_prefix."_","",$data['cust_ord']);
    	$orderid = strstr($data["out_trade_no"],$this->pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", WEB_PATH . $href);
        }
        if (!in_array($data['status'],["success","201"])) {
            _messageerror("支付失败!", WEB_PATH . $href);
        }
        sleep(1);
        $out_trade_no = $orderid;    //商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

        _message("充值成功!", WEB_PATH . $href);
    }

    /**
     * 
     * 
     * Example Callback Data: 
    商户ID	pid	是	Int	1001	
    易支付订单号	trade_no	是	String	20160806151343349021	XPay订单号
    商户订单号	out_trade_no	是	String	20160806151343349	商户系统内部的订单号
    支付方式	type	是	String	alipay	支付方式列表
    商品名称	name	是	String	VIP会员	
    商品金额	money	是	String	1.00	
    支付状态	trade_status	是	String	TRADE_SUCCESS	只有TRADE_SUCCESS是成功
    业务扩展参数	param	否	String		
    签名字符串	sign	是	String	202cb962ac59075b964b07152d234b70	签名算法点此查看
    签名类型	sign_type	是	String	MD5	默认为MD5
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
        if (!in_array($data['trade_status'],['TRADE_SUCCESS'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"success",$this->get_logpath($this->pay_name));
        exit;
    }
    public function houtai_usdt()
    {
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        save_log($data,$this->get_logpath($this->pay_name));
        if (!$data) {
            echo 'fail';
            exit;
        }
        if (!in_array($data['trade_status'],['TRADE_SUCCESS'])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_usdt($orderid,$status,"success",$this->get_logpath($this->pay_name));
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
