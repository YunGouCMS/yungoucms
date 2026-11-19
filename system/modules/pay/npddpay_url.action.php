<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
System::load_sys_fun('send');
class npddpay_url extends SystemAction {
	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	
	
	public function qiantai(){	
	
		$data=$_REQUEST;
	    $orderid = strstr($data["out_trade_no"],'C');
		sleep(1);
		$out_trade_no = $orderid;	//商户订单号
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo || $dingdaninfo['status'] == '未付款'){
			 header("location:".WEB_PATH."/mobile/home");			
		}else{
			if(empty($dingdaninfo['scookies'])){
				//_message("充值成功!",WEB_PATH."/mobile/home/userbalance");
              header("location:".WEB_PATH."/mobile/home/userbalance");
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
	
			//$data=file_get_contents("php://input");
     		$data=$_REQUEST;
	    	$orderid = strstr($data["out_trade_no"],'C');
			$this->db->Autocommit_start();
			if(!$data){	echo 'fail';exit;}
			unset($data['/pay/nbpay_url/houtai']);
			$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'npddpay' and `pay_start` = '1'");
			$info=unserialize($pay_type['pay_key']);//获取token、
			$token = $info['key']['val'];
			$isSign = $this->getSignVeryfy($data, $data["sign"] ,$token);
			if($isSign){
				if ($data['trade_status'] == 'TRADE_SUCCESS') {
                  	$this->mobile = System::load_sys_config('mobile');
					$key=$this->mobile['cfg_mobile_on'];
					$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' for update");
					if(!$dingdaninfo){	echo 'fail';exit;}	//没有该订单,失败
					$c_money = intval($dingdaninfo['money']);
					$uid = $dingdaninfo['uid'];
					$time = time();
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
								$up_sql2 = $this->db->Query("INSERT INTO `@#_recharge_money` (`uid`, `time`, `dindancode`, `status`) VALUES ('$uid', '$time', '$orderid', '$status')");	
							} 			  		
					}	
			 
					$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '已付款' where  `code` = '$orderid'");
					$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");				
					$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time` ,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$dingdaninfo[code]')");
						
					if($up_q1 && $up_q2 && $up_q3){
						$this->db->Autocommit_commit();	
                        $this->addzp_num($uid,$c_money);
					}else{
						$this->db->Autocommit_rollback();
						echo "fail";exit;
					}			
					if(empty($dingdaninfo['scookies'])){					
						echo "success";
                      //发送短信提醒
                   if($c_money >499){
                        $r_notice= System::load_app_config("rechargenotice");
                        if($r_notice['mradio1'] == '1'){
                         	 send_rmsg($r_notice['mobile1'],$uid,$c_money);
                        }
                     if($r_notice['mradio2'] == '1'){
							 send_rmsg($r_notice['mobile2'],$uid,$c_money);
                        }
                     if($r_notice['mradio3'] == '1'){
							 send_rmsg($r_notice['mobile3'],$uid,$c_money);
                        }
                     if($r_notice['mradio4'] == '1'){
							 send_rmsg($r_notice['mobile4'],$uid,$c_money);
                        }               
                  } 
                      	exit;		//充值完成			
					}
				}
			}else{
				echo 'fail';
			}			

	}
    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
	function getSignVeryfy($para_temp, $sign ,$token) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter =$this-> paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort =$this-> argSort($para_filter);
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr =$this-> createLinkstring($para_sort);
		
		$isSgin = false;
		$isSgin =$this-> md5Verify($prestr, $sign, $token);
		
		return $isSgin;
	}
	/**
	 * 验证签名
	 * @param $prestr 需要签名的字符串
	 * @param $sign 签名结果
	 * @param $key 私钥
	 * return 签名结果
	 */
	function md5Verify($prestr, $sign, $key) {
		$prestr = $prestr . $key;
		$mysgin = md5($prestr);

		if($mysgin == $sign) {
			return true;
		}
		else {
			return false;
		}
	}
	function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
	function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
	public function  addzp_num($uid='',$c_money=''){
		$st=strtotime(date('Y-m-d',time()));
		$et=strtotime( "+1 day",time());
		$u_num=$this->db->GetOne("SELECT * FROM `@#_zp_number`WHERE `uid`=$uid ");
     	$memberinfo=$this->db->GetOne("SELECT * FROM `@#_member`WHERE `uid`=$uid ");
      	$every=$memberinfo['every'];
      	$yaoqing=$memberinfo['yaoqing'];
      	if(!$every){ return false; }
		$time=time();
   		$num=intval($c_money/$every); 
		if($num){  	
         	$zp_numdata=$this->db->GetOne("SELECT * FROM `@#_zp_number` where uid=$yaoqing");
            if($memberinfo['chongzhi'] == 0){//邀请人转盘次数处理
              
               	$this->db->Query("UPDATE `@#_member` SET `chongzhi`='1' WHERE (`uid`='$uid')");	
              
                if( $zp_numdata){

                    if( $zp_numdata['time'] > $st && $zp_numdata['time'] < $et){

                        $this->db->Query("UPDATE `@#_zp_number` SET `number`=`number`+1  where uid=$yaoqing");

                    }else{
                      
                        $this->db->Query("UPDATE `@#_zp_number` SET `number`=1,`time`='$time',`has_num`=0  where uid=$yaoqing");
                    }
                }else{    

                    $this->db->Query("INSERT `@#_zp_number`(`uid`,`number`,`time`)value('$yaoqing','1','$time')");

                }
            }
			if (!$u_num) {//自己增加抽奖次数
              
				$kk=$this->db->Query("INSERT INTO `@#_zp_number` (`uid`,`number`,`time`,`has_num`) value($uid,'1',$time,'0') ");
              
			}else{
              
				if ($u_num['time'] > $st && $u_num['time'] <$et  ) {
                  
                  	$usernum=$u_num['number']+$num;
                  
					$ok=$this->db->Query("UPDATE  `@#_zp_number`  SET `number`='$usernum' where uid=$uid ");
                  
				}else{
                  
					$ok=$this->db->Query("UPDATE  `@#_zp_number`  SET `time`='$time',`number`='$num',`has_num`=0 where uid=$uid ");
                  
				}
			}		
		}			
		
	}	
}

?>