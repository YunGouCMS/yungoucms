<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

include "basepay.action.php";

class mgmpay_url extends basepay
{
    private $pay_name = 'mgmpay';

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
        currency	String	是	货币代码。如巴西为BRL
        order_id	String	是	商户订单编号
        payment_id	String	是	平台代收订单唯一号
        amount	String	是	固定2位小数点的浮点数金额
        timestamp	Long	是	毫秒级UTC时间戳（13位）
        extend	String	是	附加数据
        status	String	是	支付结果状态
        status_code	Int	是	支付结果状态码
        status_detail	String	是	支付结果状态描述
        signature	String	否	MD5签名，32位大写字母
        {
        "amount": "101.82",
        "currency": "BRL",
        "order_id": "TEST1676152468957",
        "payment_id": "P_101100045_1624527347380260864",
        "signature": "9F2FAF1AD2A11F392037343C6F6E8BE3",
        "status": "SUCCESS",
        "status_code": 100,
        "status_detail": "Payment is success",
        "timestamp": 1676675149139
        }
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
        if (!in_array($data['status'],["SUCCESS"])) {
            $status = 2;
        }else{
            $status = 1;
        }
        $data['order_id'] = str_replace($this->pay_prefix."_","",$data['order_id']);
    	$orderid = strstr($data["order_id"],$this->pay_prefix);
        $this->handle_order($orderid,$status,"SUCCESS");
        exit;
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
        save_log($data, "pay_log/{$this->pay_name}_$day.log");
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
