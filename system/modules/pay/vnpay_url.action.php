<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

// include "lib/onepay/OnePay.class.php";

class vnpay_url {
    private $db;
    private $pay_prefix;

    public function __construct(){			
		$this->db=System::load_sys_class('model');		
        $this->pay_prefix = System::load_sys_config("system","pay_prefix");
	} 	
	
    public function qiantai(){

        $data=$_REQUEST;

        if($data['code'] != "1"){
            _messageerror("充值失败!",WEB_PATH."/mobile/home/userbalance");
        }
        $data = json_decode($data['data'],true);
        $orderid = strstr($data["orderid"],$this->pay_prefix);
        sleep(1);
        $out_trade_no = $orderid;	//商户订单号
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

        _message("充值成功!",WEB_PATH."/mobile/home/userbalance");

    }
    
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
        $datas = $_REQUEST;
        if(!$datas){
            $datas = json_decode(file_get_contents('php://input'),true);
        }
        save_log($datas,"pay.log",1);
        if(!$datas){	echo 'fail';exit;}
        $data = json_decode($datas['data'],true);
        $orderid = strstr($data["orderid"],$this->pay_prefix);
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if(!$dingdaninfo){	echo 'fail';exit;}	//没有该订单,失败
        if($datas['code'] != "1"){
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
        $orderCode = $dingdaninfo['code'];
        $up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$orderCode')");

        if($up_q1 && $up_q2 && $up_q3){
            $this->db->Autocommit_commit();

        }else{
            $this->db->Autocommit_rollback();
            echo "fail";exit;
        }
        if(empty($dingdaninfo['scookies'])){
            echo "success";

            exit;		//充值完成
        }
    }
    
}