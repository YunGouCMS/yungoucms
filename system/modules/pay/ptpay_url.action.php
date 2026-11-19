<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

// include "lib/onepay/OnePay.class.php";

class ptpay_url
{
    private $db;
    private $pay_prefix;
    private $pay_name = 'ptpay';

    public function __construct()
    {
        $this->db = System::load_sys_class('model');
        $this->pay_prefix = System::load_sys_config("system", "pay_prefix");
    }

    public function qiantai()
    {

        $data = $_REQUEST;

        // if($data['code'] != "0"){
        //     _messageerror("充值失败!",WEB_PATH."/mobile/home/userbalance");
        // }
        // $data = json_decode($data['data'],true);
        // $orderid = strstr($data["orderid"],$this->pay_prefix);
        $web=System::load_sys_config('system');
 		if(isset($web['pay_prefix'])&&$web['pay_prefix']){
            $pay_prefix = $web['pay_prefix'];
        }else{
            $pay_prefix = 'C';
        }
        $data['partnerorderid'] = str_replace($pay_prefix."_","",$data['partnerorderid']);
    	$orderid = strstr($data["partnerorderid"],$pay_prefix);
        if (empty($orderid)) {
            _messageerror("充值失败!", WEB_PATH . "/mobile/home/userbalance");
        }
        if (!in_array($data['orderstatus'],[1,4])) {
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
    {"message":"0",
        "okordertime":"2020/8/10 12:17:55",
        "orderno":"47745045973762457667okp1ep",
        "orderstatus":"1",
        "partnerid":"67okp1ep",
        "partnerorderid":"7772020081010958328",
        "payamount":"5000",
        "paytype":"wcHfWap",
        "remark":"remark",
        "sign":"f70259f77ccbc6aeb924773860885aa5",
        "version":"1.0"}
     * */
    public function houtai()
    {
        $data = $_REQUEST;
        // if(!$datas){
        //     $data = json_decode(file_get_contents('php://input'),true);
        // }
        $day = date("Y-m-d");
        save_log($data, "pay_log/{$this->pay_name}_$day.log");
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
        $data['partnerorderid'] = str_replace($pay_prefix."_","",$data['partnerorderid']);
    	$orderid = strstr($data["partnerorderid"],$pay_prefix);
        save_log($orderid, "pay_log/{$this->pay_name}_$day.log");
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if (!$dingdaninfo) {
            echo 'fail2';
            exit;
        }    //没有该订单,失败
        if (!in_array($data['orderstatus'],[1,4])) {
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
}
