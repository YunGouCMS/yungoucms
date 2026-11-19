<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

// include "lib/onepay/OnePay.class.php";

class passpay_url
{
    private $db;
    private $pay_prefix;

    public function __construct()
    {
        $this->db = System::load_sys_class('model');
        $this->pay_prefix = System::load_sys_config("system", "pay_prefix");
    }

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
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", WEB_PATH . "/mobile/home/userbalance");
        }
        if (!in_array($data['tradeState'],[1])) {
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
        mchid	是	string	由我方分配
        pay_id	是	string	由我方分配
        trade_no	是	string	我方单号，唯一
        out_trade_no	是	string	商户传入订单号，唯一
        utr	是	string	UTR值，可能为空
        amount	是	string	订单金额：100.00 此为结算金额
        real_amount	是	string	实际支付金额：100.00
        status	是	int	交易状态：3-交易中 5-交易成功 6-交易失败
        msg	是	string	订单状态描述
        sign	是	string	签名
     * */
    public function houtai()
    {
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        $day = date("Y-m-d");
        save_log($data, "pay_log/yee_pay_$day.log");
        if (!$data) {
            echo 'fail1';
            exit;
        }
        /*    $res = $this->verifyData($datas);
        if($res != "success"){
            echo "fail";
        } */
        $web=System::load_sys_config('system');
 		if(isset($web['pay_prefix'])&&$web['pay_prefix']){
            $pay_prefix = $web['pay_prefix'];
        }else{
            $pay_prefix = 'C';
        }
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        save_log($orderid, "pay_log/yee_pay_$day.log");
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if (!$dingdaninfo) {
            echo 'fail2';
            exit;
        }    //没有该订单,失败
        if (!in_array($data['status'],[1])) {
            $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '支付失败' where  `code` = '$orderid'");
            echo "success";
            exit;
        }
        $c_money = intval($dingdaninfo['money']);
        $uid = $dingdaninfo['uid'];
        $time = time();
        $config = System::load_app_config("userrecharge", '', 'member');
        if ($config['r_money']) {
            $d_money = $config['r_money'];
        }
        $this->db->Autocommit_start();
        //充值奖励处理
        if ($c_money >= $d_money && $config['isOpen']) {
            if ($config['money']) {
                $m = $config['money']; //充值赠送
                $up_sql1 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $m where (`uid` = '$uid')");
                $up_sql3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值赠送', '$m', '$time')");
            }
            $r_time = $this->db->GetOne("SELECT * FROM `@#_recharge_money` WHERE `uid` = '$uid' AND status=1");
            if (!$r_time) { //判断是否在累计时效内
                $status = 1;
                //写入充值时间做累计领取判断
                $up_sql2 = $this->db->Query("INSERT INTO `@#_recharge_money` (`uid`, `time`, `dindancode`, `status`) VALUES ('$uid', '$time', '$orderid', '$status')");
            }
        }

        $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '已付款' where  `code` = '$orderid'");
        $up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
        $orderCode = $dingdaninfo['uid'].$dingdaninfo['code'];
        $up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$orderCode')");

        if ($up_q1 && $up_q2 && $up_q3) {
            $this->db->Autocommit_commit();
        } else {
            $this->db->Autocommit_rollback();
            echo "fail3";
            exit;
        }
        if (empty($dingdaninfo['scookies'])) {
            echo "success";

            exit;        //充值完成
        }
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
