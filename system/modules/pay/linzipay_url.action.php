<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

class linzipay_url
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
        $data['orderid'] = str_replace($pay_prefix."_","",$data['orderid']);
    	$orderid = strstr($data["orderid"],$pay_prefix);
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
        memberid 商户编号 是
        orderid 订单号 是
        amount 订单金额 是
        transaction_id 交易流水号 是
        datetime 交易时间 是
        returncode 交易状态 是 “00” 为成功
        attach 扩展返回 否 商户附加数据返回
        sign 签名 否 请看验证签名字段格式
     * */
    public function houtai()
    {
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        $day = date("Y-m-d");
        save_log($data, "linzi_pay_$day.log");
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
        $data['orderid'] = str_replace($pay_prefix."_","",$data['orderid']);
    	$orderid = strstr($data["orderid"],$pay_prefix);
        save_log($orderid, "linzi_pay_$day.log");
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if (!$dingdaninfo) {
            echo 'fail2';
            exit;
        }    //没有该订单,失败
        if (!in_array($data['returncode'],["00"])) {
            $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '支付失败' where  `code` = '$orderid'");
            echo "OK";
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
            echo "OK";

            exit;        //充值完成
        }
    }
}
