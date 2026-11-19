<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

// include "lib/onepay/OnePay.class.php";

class yeepay_url
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
        $data['mchOrderNo'] = str_replace($pay_prefix."_","",$data['mchOrderNo']);
    	$orderid = strstr($data["mchOrderNo"],$pay_prefix);
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
    支付订单号      payOrderId	     是	是	String(30)	P20160427210604000490	支付中心生成的订单号
    商户ID          mchId	        是	是	String(30)	20001222	支付中心分配的商户号
    支付产品ID      productId	    是	是	int	8001	支付产品ID
    商户订单号      mchOrderNo	    是	是	String(30)	20160427210604000490	商户生成的订单号
    支付金额        amount	        是	是	int	100	支付金额,单位分
    实际支付金额	income	        是	是	int	100	实际支付金额,单位分
    状态            status	        是	是	int	1	支付状态,0-订单生成,1-支付中,2-支付成功,3-业务处理完成
    渠道订单号	    channelOrderNo	否	是	String(64)	wx2016081611532915ae15beab0167893571	三方支付渠道订单号
    渠道数据包	    channelAttach	否	是	String	{"bank_type":"CMB_DEBIT","trade_type":"pay.weixin.micropay"}	支付渠道数据包
    扩展参数1	    param1	        否	是	String(64)		支付中心回调时会原样返回
    扩展参数2	    param2	        否	是	String(64)		支付中心回调时会原样返回
    支付成功时间	paySuccTime	    是	是	long		精确到毫秒
    通知类型	    backType	    是	是	int	1	通知类型，1-前台通知，2-后台通知
    签名	        sign	        是	否	String(32)	C380BEC2BFD727A4B6845133519F3AD6	签名值，详见签名算法
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
        $data['mchOrderNo'] = str_replace($pay_prefix."_","",$data['mchOrderNo']);
    	$orderid = strstr($data["mchOrderNo"],$pay_prefix);
        save_log($orderid, "pay_log/yee_pay_$day.log");
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if (!$dingdaninfo) {
            echo 'fail2';
            exit;
        }    //没有该订单,失败
        if (!in_array($data['status'],[2,3])) {
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
