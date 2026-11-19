<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "0");
include dirname(__FILE__) . '/lib/weixin.class.php';

class weixin_url extends SystemAction
{
	private $out_trade_no;

	public function __construct()
	{
		$this->db = System::load_sys_class('model');
	}

	private function qiantai()
	{
		if (_is_mobile()) {
			$message = '_messagemobile';
		} else {
			$message = '_message';
		}
		$out_trade_no = $this->out_trade_no;
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if (!$dingdaninfo || $dingdaninfo['status'] == '未付款') {
			_message("支付失败");
		} else {
			if (empty($dingdaninfo['scookies'])) {
				_message("充值成功!", WEB_PATH . "/member/home/userbalance");
			} else {
				if ($dingdaninfo['scookies'] == '1') {
					_message("支付成功!", WEB_PATH . "/member/cart/paysuccess");
				} else {
					_message("商品还未购买,请重新购买商品!", WEB_PATH . "/member/cart/cartlist");
				}
			}
		}
	}

	public function houtai()
	{
		
		$pay_type = $this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'weixin' and `pay_start` = '1'");
		$pay_type_key = unserialize($pay_type['pay_key']);
		$key = $pay_type_key['key']['val'];
		$id = $pay_type_key['id']['val'];
		$notify = new Notify_pub();
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$notify->saveData($xml); 
		
		if ($notify->checkSign() == FALSE) {
			$notify->setReturnParameter("return_code", "FAIL");
			$notify->setReturnParameter("return_msg", "签名失败");
		} else {
			$notify->setReturnParameter("return_code", "SUCCESS");
		}

		$returnXml = $notify->returnXml(); 
		echo $returnXml;
		if ($notify->checkSign() == TRUE) {
			if ($notify->data["return_code"] == "FAIL") {
				exit();
			} elseif ($notify->data["result_code"] == "FAIL") {
				exit();
			} else {
				$this->out_trade_no = $notify->data["out_trade_no"];
			}
			$ret = $this->weixin_chuli();
			$this->qiantai();
		} else {
			exit();
		}
	}
	public function jsapi_houtai()
	{

		$this->out_trade_no =$this->segment(4);
		if ($this->out_trade_no) {
			$ret = $this->weixin_chuli();
			$this->qiantai();
		} else { 
			exit();
		}
	}
	private function weixin_chuli()
	{
		$pay_type = $this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'weixin' and `pay_start` = '1'");
		$out_trade_no = $this->out_trade_no;
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' FOR UPDATE");
		if (!$dingdaninfo) {
			return false;
		}
		if ($dingdaninfo['status'] == '已付款') {
			return false;
			exit;
		}
		$c_money = intval($dingdaninfo['money']);
		$uid = $dingdaninfo['uid'];
		$time = time();
		$this->db->Autocommit_start();

		$config = System::load_app_config("userrecharge");
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
					$up_sql2 = $this->db->Query("INSERT INTO `@#_recharge_money` (`uid`, `time`, `dindancode`, `status`) VALUES ('$uid', '$time', '$out_trade_no', '$status')");	
				} 			  		
	  	}
	  	
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '微信支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$dingdaninfo[code]')");
		if ($up_q1 && $up_q2 && $up_q3) {
			$this->db->Autocommit_commit();
		} else {
			$this->db->Autocommit_rollback();
			return '充值失败';
		}
		if (empty($dingdaninfo['scookies'])) {
			setcookie('wxxml',''); 
			return "充值完成";
		}
		$scookies = unserialize($dingdaninfo['scookies']);
		$pay = System::load_app_class('pay', 'pay');
		$pay->scookie = $scookies;
		$ok = $pay->init($uid, $pay_type['pay_id'], 'go_record');
		if ($ok != 'ok') {
			$_COOKIE['Cartlist'] = '';
			_setcookie('Cartlist', NULL);
			return '商品购买失败';
		}
		$check = $pay->go_pay(1);
		if ($check) {
			$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
			$_COOKIE['Cartlist'] = '';
			_setcookie('Cartlist', NULL);
			return "商品购买成功";
		} else {
			return '商品购买失败';
		}
	}
}