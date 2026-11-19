<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
class jhpay_url extends SystemAction {
	private $title;
	private $Money;
	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	

		private function qiantai(){	

	}
	
	public function houtai(){
	
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'jhpay' and `pay_start` = '1'");
		$pay_type_key = unserialize($pay_type['pay_key']);
		$pkey =  $pay_type_key['key']['val'];		//支付KEY
		$pid =  $pay_type_key['id']['val'];		//支付商号ID
		$ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["pid"], // 商户ID
            "orderid" =>  $_REQUEST["out_trade_no"], // 订单号
            "amount" =>  $_REQUEST["money"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "transaction_id" =>  $_REQUEST["trade_no"], // 支付流水号
            "returncode" => $_REQUEST["trade_status"],
        );

	$para_filter = array();
	while (list ($key, $val) = each ($_GET)) {
		if($key == "sign" || $key == "sign_type" || $val == "")continue;
		else	$para_filter[$key] = $_GET[$key];
	}
		ksort($para_filter);
        reset($para_filter);
 	$arg  = "";
	while (list ($key, $val) = each ($para_filter)) {
		$arg.=$key."=".$val."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);
	
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
 	$prestr = $arg . $pkey;
	$mysgin = md5($prestr);
      
        $Md5key = $pkey;
   
		ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));
		
		
		$Money	= $ReturnArray['amount'];
		$title	= $ReturnArray['orderid'];
		$this->title = strstr($title,'C');
		$this->Money = $Money;
		
		
        if ($mysgin == $_REQUEST["sign"]) {
			//file_put_contents("success.txt",'sign is ok '."\n", FILE_APPEND);
            if ($_REQUEST["trade_status"] == "TRADE_SUCCESS") {
             	 //file_put_contents("success.txt",'trade_status is' .$_REQUEST["trade_status"]."\n", FILE_APPEND);
					$ret = $this->rongyfpayalism_chuli();
				   $str = "交易成功！订单号：".$_REQUEST["out_trade_no"];
                   //file_put_contents("success.txt",$str."\n", FILE_APPEND);
				   exit("ok");
            }
        }
		else
		{
			exit("Fail");
		}

	
		

	}
	
	private function rongyfpayalism_chuli(){
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'jhpay' and `pay_start` = '1'");
		$title = $this->title;
		$Money = $this->Money;
		$this->db->Autocommit_start();
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$title' for update");
      
		if(!$dingdaninfo){ return 'IncorrectOrder';}	//没有该订单,失败
		if($dingdaninfo['status'] == '已付款'){
			return 'ok';
		}
		$c_money = intval($Money);
      	//$c_money = '100';
		$j_num = intval($c_money/100);
		$uid = $dingdaninfo['uid'];
		$time = time();		
		//if($c_money<1){exit("fail");}
		file_put_contents("success.txt",date("Y-m-d-H:i:s").':'.$c_money.'-'.$j_num.'-'.$uid."\n", FILE_APPEND);
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '联系qq', `status` = '已付款',`money`=$c_money where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		// 2019-09-16 新增体验金功能，如果包含体验金则赠送体验金
		$user_tiyanjin =$this->db->GetOne("SELECT experience_money from `@#_member` where `uid` = '$uid'");
		if($user_tiyanjin['experience_money']>0){
			$tiyanjin = $user_tiyanjin['experience_money'];
			$c_moneys = $c_money + $user_tiyanjin['experience_money'];
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_moneys,`experience_money`='0' where (`uid` = '$uid')");
			$up_q4 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '体验金充值到账户', '$tiyanjin', '$time')");
			file_put_contents("success.txt",date("Y-m-d-H:i:s").':体验金:'.$tiyanjin." 充值成功\n", FILE_APPEND);
		}else{
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
		}
					
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
		//file_put_contents("success.txt","UPDATE `@#_member_addmoney_record` SET `pay_type` = '联系qq：9201571', `status` = '已付款',`money`=$c_money where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'"."\n", FILE_APPEND);
		if($up_q1 && $up_q2 && $up_q3){			
			$this->db->Autocommit_commit();
		}else{
			$this->db->Autocommit_rollback();
			return 'fail';
		}			
		if(empty($dingdaninfo['scookies'])){					
			return "ok";	//充值完成	
		}
				

	}
	
}//

?>