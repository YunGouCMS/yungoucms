<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

// include "lib/onepay/OnePay.class.php";

class epay_url {
    private $db;
    private $pay_prefix;

    public function __construct(){			
		$this->db=System::load_sys_class('model');		
        $this->pay_prefix = System::load_sys_config("system","pay_prefix");
	} 	
	
    public function qiantai(){

        $data=$_REQUEST;

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
        $data['out_trade_no'] = str_replace($pay_prefix."_","",$data['out_trade_no']);
    	$orderid = strstr($data["out_trade_no"],$pay_prefix);
        if(empty($orderid)){
            _messageerror("充值失败!",WEB_PATH."/mobile/home/userbalance");
        }
        sleep(1);
        $out_trade_no = $orderid;	//商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

        _message("充值成功!",WEB_PATH."/mobile/home/userbalance");

    }
    

/*     private function verifyData($data){
        //计算得出通知验证结果
        $res = $this->db->GetOne("");
        $epay = new EpayCore($epay_config);
        $verify_result = $epay->verify($data);

        if($verify_result) {//验证成功
            //商户订单号
            $out_trade_no = $data['out_trade_no'];
            //彩虹易支付交易号
            $trade_no = $data['trade_no'];
            //交易状态
            $trade_status = $data['trade_status'];
            //支付方式
            $type = $data['type'];
            //支付金额
            $money = $data['money'];
            if ($data['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            }
            //验证成功返回
            echo "success";
        }
        else {
            //验证失败
            echo "fail";
        }
    } */

    /**
     * 
     * 
     * Example Callback Data: 
    {
    "code": 1,
    "data": "{\"amount\":\"50001.0000\",\"bamount\":null,\"orderid\":\"HSBxpKBp4c2x1647505032703\",\"pageurl\":\"http://120.77.76.146:9080/t5/index.html?ticket=25a3c10ded4ddd7d5ab1430ad3a6d326\",\"sign\":\"f17a81b2cff61818fcb848070796e5c3\",\"ticket\":\"25a3c10ded4ddd7d5ab1430ad3a6d326\",\"type\":\"Momo\",\"userid\":\"4d492429445c4d1698647dcdab8d87c0\"}",
    "msg": "success"
    }
     * */
    public function houtai(){
        $data = $_REQUEST;
        // if(!$datas){
        //     $data = json_decode(file_get_contents('php://input'),true);
        // }
        $day = date("Y-m-d");
        save_log($data,"epay_$day.log");
        if(!$data){	echo 'fail1';exit;}
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
        // $orderid = $data["out_trade_no"];
          save_log($orderid,"epay_$day.log");
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if(!$dingdaninfo){	echo 'fail2';exit;}	//没有该订单,失败
        if($data['endtime'] == null || $data['endtime'] == ""){
            $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '支付失败' where  `code` = '$orderid'");
            echo "success";
            exit;
        }
        $c_money = intval($dingdaninfo['money']);
        $uid = $dingdaninfo['uid'];
        $time = time();
        $config = System::load_app_config("userrecharge",'','member');
        if($config['r_money']){
            $d_money=$config['r_money'];
        }
        $this->db->Autocommit_start();
        //充值奖励处理
        if($c_money >= $d_money && $config['isOpen']){
            if($config['money']){
                $m=$config['money'];//充值赠送
                $up_sql1 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $m where (`uid` = '$uid')");
                $up_sql3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值赠送', '$m', '$time')");
            }
            $r_time=$this->db->GetOne("SELECT * FROM `@#_recharge_money` WHERE `uid` = '$uid' AND status=1");
            if(!$r_time){//判断是否在累计时效内
                $status=1;
                //写入充值时间做累计领取判断
                $up_sql2 = $this->db->Query("INSERT INTO `@#_recharge_money` (`uid`, `time`, `dindancode`, `status`) VALUES ('$uid', '$time', '$orderid', '$status')");
            }
        }

        $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '已付款' where  `code` = '$orderid'");
        $up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
        $orderCode = $dingdaninfo['uid'].$dingdaninfo['code'];
        $up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$orderCode')");

        if($up_q1 && $up_q2 && $up_q3){
            $this->db->Autocommit_commit();

        }else{
            $this->db->Autocommit_rollback();
            echo "fail3";exit;
        }
        if(empty($dingdaninfo['scookies'])){
            echo "success";

            exit;		//充值完成
        }
    }
    
}