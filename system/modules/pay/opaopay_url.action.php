<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
System::load_sys_fun('send');
class opaopay_url extends SystemAction {
	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	
	
	public function qiantai(){	
	
		$data=$_GET;

	    $orderid = strstr($data["out_trade_no"],'C');
		sleep(1);
		$out_trade_no = $orderid;	//商户订单号
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

		_message("充值成功!",WEB_PATH."/mobile/home/userbalance");

	}
	
	public function houtai(){ 
  
 		$data=$_GET;
		$this->db->Autocommit_start();
		if(!$data){	echo 'fail';exit;}
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'opaopay' and `pay_start` = '1'");
		$info=unserialize($pay_type['pay_key']);//获取token、
		$token = $info['key']['val'];

        //生成签名
		//$aryLink = $this->aryLink($this->arySort($data));
		//$sign = $this->md5Sign($aryLink, $token);

        //if ($sign == $data["sign"]) {
		    	$orderid = strstr($data["out_trade_no"],'C');
				$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
				if(!$dingdaninfo){	echo 'fail';exit;}	//没有该订单,失败
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
				$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$dingdaninfo[code]')");

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
			//}else{
          
			//	echo 'fail';
			//}			

	}

	public function arySort($ary){
	        ksort($ary);
	        reset($ary);
	        return $ary;
	}
	public function aryLink($ary){
	    $arg  = "";
	    while (list ($key, $val) = each ($ary)) {
	        $arg.=$key."=".$val."&";
	    }
	    $arg = substr($arg,0,@count($arg)-2);

	    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

	    return $arg;
	}
	public function md5Sign($aryLink, $key){
	    $aryLink = $aryLink.$key;
	    return md5($aryLink);
	}
 

}

?>