<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
System::load_sys_fun('send');
 include G_SYSTEM."modules/pay/lib/jhypay/EpayCore.class.php";
class jhypay_url extends SystemAction {
	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	
	
	public function qiantai(){	
	sleep(2);
		$data=$_GET;
// 		$file='pay_'.date('y-m-d').'pay.txt';
        // file_put_contents($file,time().'-qiantai_get'.var_export($data,true),FILE_APPEND);//如果文件不存在，则创建一个新文件。
        // $data["out_trade_no"] = "C16732838651454633";
	    $orderid = strstr($data["out_trade_no"],'C');
		if($_GET['trade_status'] == "TRADE_SUCCESS"){
		    
					_message("支付成功!",WEB_PATH."/mobile/cart/paysuccess");
		};
			_message("支付失败");	exit;
		$out_trade_no = $orderid;	//商户订单号
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo || $dingdaninfo['status'] == '未付款'){
			_message("支付失败");			
		}else{
			if(empty($dingdaninfo['scookies'])){
				_message("充值成功!",WEB_PATH."/mobile/home/userbalance");
			}else{
				if($dingdaninfo['scookies'] == '1'){
					_message("支付成功!",WEB_PATH."/mobile/cart/paysuccess");
				}else{
					_message("商品还未购买,请重新购买商品!",WEB_PATH."/mobile/cart/cartlist");
				}
					
			}
		}
	}
	
	public function houtai(){
 		$pay = $this->db->GetOne("select pay_key from `@#_pay` where `pay_class` = 'jhypay' and `pay_start` = '1' ");
		$paykey = unserialize($pay['pay_key']);
        $epay_config['apiurl'] = 'http://q.mzf.ltd/';
        $epay_config['pid'] = $paykey['id']['val'];
        $epay_config['key'] = $paykey['key']['val'];
        
        $epay = new EpayCore($epay_config);
        $verify_result = $epay->verifyNotify();
        $verify_result = true;
        
        if($verify_result) {//验证成功
// 		$file='pay_houtai_get'.date('y-m-d').'pay.txt';
        // file_put_contents($file,time().'-houtai_get'.var_export($_GET,true),FILE_APPEND);
        
            // $_GET['out_trade_no'] = 'lcsc_35376C16494014310291297';
            // var_dump($_GET["out_trade_no"]);exit;
    	    $orderid = strstr($_GET["out_trade_no"],'C');
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
				$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");

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
         } else {
	        echo "fail";exit;
         }
	}
 

}

?>