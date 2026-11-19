<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

// include "lib/onepay/OnePay.class.php";

class onepay_url {
    
    	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	
	
    public function qiantai(){

        $data=$_REQUEST;

        $orderid = strstr($data["tid"],'C');
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
         "id": 31444569, 
         "mcid": "mch5c2f0404102fb", 
         "tid": "VIXAR001",     //订单交易id
         "iid": "123",          //发票id 
         "uuid": "VIXAR123123124", //uuid
         "txtime": "11/04/2022 15:13:16", 
         "amount": 1, 
         "ccy": "LAK", 
         "merchantname": "OnePay Test Merchant", 
         "name": "Vixar Vorlavong", 
         "phone": "55090757", 
         "itemname": null, 
         "description": "Test", 
         "ticket": "VCPQRZHDAHV7", 
         "fccref": "202204112224070" 
        }
     * */
    public function houtai(){
        $data = $_REQUEST;
        if(!$data){
            $data = json_decode(file_get_contents('php://input'),true);
        }
        $this->db->Autocommit_start();
        if(!$data){	echo 'fail';exit;}
            $pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'onepay' and `pay_start` = '1'");
        $info=unserialize($pay_type['pay_key']);//获取token、
        $token = $info['key']['val'];
        $orderid = strstr($data["tid"],'C');
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if(!$dingdaninfo){	echo 'fail';exit;}	//没有该订单,失败
        
        // $dingdaninfo['money'] = $dingdaninfo['money']==1?1000:$dingdaninfo['money'];
        
        $c_money = intval($dingdaninfo['money']);
        $uid = $dingdaninfo['uid'];
        $time = time();
        $config = System::load_app_config("userrecharge",'','member');
        if($config['r_money']){
            $d_money=$config['r_money'];
        }
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
            echo "ok";

            exit;		//充值完成
        }
    }
    
    // public function getCode(){
    //     try{
    //         $a = new OnePay();
    //         $res = $a->getQr();
    //     }catch(Exception $e){
    //         echo ($e->getMessage());
    //         exit;
    //     }
    //     echo $res;
    //     exit;
    // }
    
}