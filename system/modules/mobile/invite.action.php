<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class invite extends base {
	public function __construct(){ 
		parent::__construct();
		if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
			if(!$this->userinfo)header('location:'.WEB_PATH."/mobile/user/login");
		}		
		$this->db = System::load_sys_class('model');
        $this->symbol =$this->db->GetOne("SELECT * FROM `@#_currency` WHERE status='1'");
	}

	public function friends(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $user_fufen = System::load_app_config("user_fufen",'','member');
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid=$member['uid'];
		$yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid='$uid'");
		if(!$yongjin_info){
			$this->db->Query("INSERT INTO `@#_member_commission` (`uid`) VALUES ('$uid')");
		}
        $involvedtotal=0;   //邀请人数
        //查询邀请好友信息  
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        $involvedtotal=count($invifriends);
		$shourutotal = $yongjin_info['shouru_money'];
		$zhichutotal = $yongjin_info['zhichu_money'];
        $total=sprintf("%01.2f",$yongjin_info['total_money']);  //计算佣金余额

        /**-----------------------------------模板兼容-----------------------------------------**/
        //邀请人数
        $involvedtotal=0;
        //查询邀请好友信息
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        $involvedtotal=count($invifriends);

        //累计佣金
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        if(count($invifriends)==0){
            $totalCommission="0.00";
        }else{
            //查询佣金表
            for($i=0;$i<count($invifriends);$i++){
                $sqluid=$invifriends[$i]['uid'];

                //查询邀请好友给我反馈的佣金
                $recodes[$sqluid]=$mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$sqluid' and `type`=1 ORDER BY `time` DESC");

                $user[$sqluid]['username']= get_user_name($invifriends[$i]);
                $totalCommission+=$user[$sqluid]['money'];
            }
        }

        $image = $this->mycode_new(1);
        include templates("mobile/invite","friends");
    }
	
	// 邀请好友
    public function friends1(){
        $webname="邀请管理";
        $member=$this->userinfo;
        $title="我的中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid=_getcookie('uid');
        $notinvolvednum=0;  //未参加云购的人数
        $involvednum=0;     //参加预购的人数
        $involvedtotal=0;   //邀请人数
        //查询邀请好友信息
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        $involvedtotal=count($invifriends);
        for($i=0;$i<count($invifriends);$i++){
            $sqluid=$invifriends[$i]['uid'];
            $sqname=get_user_name($invifriends[$i]);
            $invifriends[$i]['sqlname']=	 $sqname;
            //查询邀请好友的消费明细
            $accounts[$sqluid]=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$sqluid'  ORDER BY `time` DESC");
            //判断哪个好友有消费
            if(empty($accounts[$sqluid])){
                $notinvolvednum +=1;
                $records[$sqluid]='未参与';
            }else{
                $involvednum +=1;
                $records[$sqluid]='已参与';
            }
        }
        include templates("mobile/invite","friends1");
    }

	 public function address(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="收货地址";
		$member_dizhi=$mysql_model->Getlist("select * from `@#_member_dizhi` where uid='".$member['uid']."' limit 5");
		foreach($member_dizhi as $k=>$v){		
			$member_dizhi[$k] = _htmtocode($v);
		}
		$count=count($member_dizhi);
		include templates("mobile/invite","address");
     }

	public function usertransfer(){
		$member=$this->userinfo;
		$title="转帐";
		if(isset($_POST['submit1'])){
			$tmoney=$_POST[money];
			$tuser=$_POST[txtBankName];
			if($member[score]<1000)
				_message("帐户云积分不得小与1000",null,3);
			if($_POST[money]<1000)
				_message("转帐云积分不得小于1000",null,3);
			if(empty($tmoney)||empty($tuser))
				_message("转入用户和金额不得为空",null,3);
			if($tmoney>$member[score])
				_message("转入云积分不得大于帐户云积分",null,3);
			$user= $this->db->GetOne("SELECT * FROM `@#_member` where `email` = '$tuser' limit 1");	
			if(empty($user))
				$user= $this->db->GetOne("SELECT * FROM `@#_member` where `mobile` = '$tuser' limit 1");	
			if(empty($user))
					_message("转入用户不存在",null,3);
			$uid=$member[uid];
			$tuid=$user[uid];
			if($uid==$tuid)_message("不能给自己转帐",null,3);
			$time=time();
			$cmoney=$member[score]-$tmoney;
			$ctmoney=$user[score]+$tmoney;
			$name=get_user_name($uid,'username','all');
			$tname=get_user_name($tuid,'username','all');
            $this->db->Query("update `@#_member` SET `score`='$cmoney' WHERE `uid`='$uid'");
            $this->db->Query("update `@#_member` SET `score`='$ctmoney' WHERE `uid`='$tuid'");
            $this->db->Query("insert into `@#_member_op_record` (`uid`,`username`,`deltamoney`,`premoney`,`money`,`time`) values ('$uid','$name','-$tmoney','$member[money]','$cmoney','$time')");
            $this->db->Query("insert into `@#_member_op_record` (`uid`,`username`,`deltamoney`,`premoney`,`money`,`time`) values ('$tuid','$tname','$tmoney','$user[money]','$ctmoney','$time')");
            $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '转出到<$tname>', '$tmoney', '$time')");
            $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$tuid', '1', '账户', '由<$name>转入', '$tmoney', '$time')");		
		     _message("给".$tname."的".$tmoney."云积分冲值成功!",null,3);		
		}
		include templates("mobile/invite","usertransfer");
    }

    public function commissions(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid = $member['uid'];
        $recodetotal=0;   // 判断是否为空
        $shourutotal=0;
        $zhichutotal=0;
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        //查询佣金表
        for($i=0;$i<count($invifriends);$i++){
            $sqluid=$invifriends[$i]['uid'];
            //查询邀请好友给我反馈的佣金
            $recodes[$sqluid]=$mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$sqluid' and `type`=1 ORDER BY `time` DESC");
            $user[$sqluid]['username']= get_user_name($invifriends[$i]);
        }

        //自己提现或充值
        $recodes[$uid]=$mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$uid' and `type`!=1 ORDER BY `time` DESC");
        $user[$uid]['username']= get_user_name($member);
        $recodearr='';
        $i=0;
        if(!empty($recodes)){
            foreach($recodes as $key=>$val){
                $username[$key]=$user[$key]['username'];
                foreach($val as $key2=>$val2){
					$recodearr[$i]=$val2;
                    $i++;
                }
            }
        }     	
        $recodetotal=count($recodes);
        //查询   累计收入：元    累计(提现/充值)：元    佣金余额：元
        include templates("mobile/invite","commissions");
    }
	
	//佣金提现/充值
	function cashout(){
        $webname=$this->_cfg['web_name'];
        $symbol=$this->symbol;
        $member=$this->userinfo;
        $title="我的中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid = $member['uid'];
		$yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid='$uid'");
		if(!$yongjin_info){
			$this->db->Query("INSERT INTO `@#_member_commission` (`uid`) VALUES ('$uid')");
		}
		$shourutotal = $yongjin_info['shouru_money'];
		$zhichutotal = $yongjin_info['zhichu_money'];
        $total=sprintf("%01.2f",$yongjin_info['total_money']+$yongjin_info['dongjie_money']);  //计算佣金总余额  
		$cashouthdtotal=sprintf("%01.2f",$yongjin_info['total_money']);//活动佣金
		$cashoutdjtotal = $yongjin_info['dongjie_money'];
        if(isset($_POST['submit1'])){ //提现
   		 session_start();
		  if (empty ( $_SESSION[$uid.'submitcode1'] )) {
				$_SESSION[$uid.'submitcode1']=microtime();
		  } else {  
			  unset ( $_SESSION [$uid."submitcode1"] );
				_message("不能重复提交!");exit; 
		  }
            $money      = abs(intval($_POST['money']));
            $username   =htmlspecialchars($_POST['txtUserName']);
            $bankname   =htmlspecialchars($_POST['txtBankName']);
            $branch     =htmlspecialchars($_POST['txtSubBank']);
            $banknumber =htmlspecialchars($_POST['txtBankNo']);
            $linkphone  =htmlspecialchars($_POST['txtPhone']);
            $qq  =htmlspecialchars($_POST['qq']);
            
            $time       =time();
            $type       = -3;  //收取1/消费-1/充值-2/提现-3
			$info=$this->db->GetOne("select uid from `@#_member_cashout` where uid=$uid and time=$time");
			if($info){ 
				  _message("不能重复提交!");exit; 
			}
            if($cashouthdtotal<0 || $cashouthdtotal == 0){
                _message("佣金金额小于或等于0");exit;
            }
            if($cashouthdtotal<100){
                _message("佣金活动金额大于100元才能提现！");exit;
            }elseif($cashouthdtotal<$money){
                _message("输入额超出活动佣金金额！");exit;
            }elseif($total<$money ){
                _message("输入额超出总佣金金额！");exit;
            }else{
			unset ( $_SESSION [$uid."submitcode1"] );
            //插入提现申请表  这里不用在佣金表中插入记录 等后台审核才插入
			$this->db->Autocommit_start();
			$res= $this->db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money`-'$money',`dongjie_money`=`dongjie_money` + $money WHERE uid='$uid'");
			if($res){
				$this->db->Autocommit_commit();
				$this->db->Query("INSERT INTO `@#_member_cashout`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`,`qq`)VALUES
					('$uid','$money','$username','$bankname','$branch','$banknumber','$linkphone','$time','$qq')");
				$res = _send_SMS($member['mobile'],19);
//				var_dump($res);exit;
					_message("申请成功！请等待审核！",WEB_PATH.'/mobile/invite/cashout');					
			}else{
					$this->db->Autocommit_rollback();
					_message("申请失败！请留意佣金余额！",WEB_PATH.'/mobile/invite/cashout');	
			}
            }
        }
        if(isset($_POST['submit2'])){//充值
            $money      = abs(intval($_POST['txtCZMoney']));
            $type       = 1;
            $pay        ="账户";
            $time       =time();
            $content    ="使用佣金充值到账户";
			session_start();
		  if (empty ( $_SESSION[$uid.'submitcode'] )) {
				$_SESSION[$uid.'submitcode']=microtime();
		  } else {  
			  unset ( $_SESSION [$uid."submitcode"] );
				_message("不能重复提交!");exit; 
		  }
            if($money <= 0 || $money > $cashouthdtotal){
                _message("佣金金额输入不正确！");exit;
            }
            if($cashouthdtotal<$money){
                _message("输入额超出活动佣金金额！");exit;
            }
			$this->db->Autocommit_start();
			$res= $this->db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money`-'$money',`zhichu_money`=`zhichu_money` + $money WHERE uid='$uid'");
			if($res){
	            //插入记录
				$account=$this->db->Query("INSERT INTO `@#_member_rechange`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$uid','$type','$pay','$content','$money','$time')");
				// 查询是否有该记录
				if($account){
					//修改剩余金额
					unset ( $_SESSION [$uid."submitcode"] );
					$leavemoney=$member['money']+$money;
					$mrecode=$this->db->Query("UPDATE `@#_member` SET `money`='$leavemoney' WHERE `uid`='$uid' "); 
					//在佣金表中插入记录
					$recode=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`)VALUES('$uid','-2','$content','$money','$time')");
					$this->db->Autocommit_commit();
                    _send_SMS($member['mobile'],19);
					_message("充值成功！",WEB_PATH.'/mobile/invite/cashout');
				}else{
					_message("充值失败！");
				}			
			}else{
				$this->db->Autocommit_rollback();
					_message("充值失败！");
			}
        }
        include templates("mobile/invite","cashout");
    }
	
	
	//佣金提现/充值
	function cashout1(){
        $webname=$this->_cfg['web_name'];
        $symbol=$this->symbol;
        $member=$this->userinfo;
        $title="我的中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid = $member['uid'];
		$yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid='$uid'");
		if(!$yongjin_info){
			$this->db->Query("INSERT INTO `@#_member_commission` (`uid`) VALUES ('$uid')");
		}
		$shourutotal = $yongjin_info['shouru_money'];
		$zhichutotal = $yongjin_info['zhichu_money'];
        $total=sprintf("%01.2f",$yongjin_info['total_money']+$yongjin_info['dongjie_money']);  //计算佣金总余额  
		$cashouthdtotal=sprintf("%01.2f",$yongjin_info['total_money']);//活动佣金
		$cashoutdjtotal = $yongjin_info['dongjie_money'];
        if(isset($_POST['submit1'])){ //提现
   		 session_start();
		  if (empty ( $_SESSION[$uid.'submitcode1'] )) {
				$_SESSION[$uid.'submitcode1']=microtime();
		  } else {  
			  unset ( $_SESSION [$uid."submitcode1"] );
				_message("不能重复提交!");exit; 
		  }
            $money      = abs(intval($_POST['money']));
            $username   =htmlspecialchars($_POST['txtUserName']);
            $bankname   =htmlspecialchars($_POST['txtBankName']);
            $branch     =htmlspecialchars($_POST['txtSubBank']);
            $banknumber =htmlspecialchars($_POST['txtBankNo']);
            $linkphone  =htmlspecialchars($_POST['txtPhone']);
            $time       =time();
            $type       = -3;  //收取1/消费-1/充值-2/提现-3
			$info=$this->db->GetOne("select uid from `@#_member_cashout` where uid=$uid and time=$time");
			if($info){ 
			  echo 3;exit; 
			}
            if($cashouthdtotal<0 || $cashouthdtotal == 0){
			  echo 3;exit; 
            }
            if($cashouthdtotal<100){
			  echo 1;exit; 
            }elseif($cashouthdtotal<$money){
                
			  echo 2;exit; 
            }elseif($total<$money ){
                
			  echo 2;exit; 
            }else{
			unset ( $_SESSION [$uid."submitcode1"] );
            //插入提现申请表  这里不用在佣金表中插入记录 等后台审核才插入
			$this->db->Autocommit_start();
			$res= $this->db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money`-'$money',`dongjie_money`=`dongjie_money` + $money WHERE uid='$uid'");
			if($res){
				$this->db->Autocommit_commit();
				$this->db->Query("INSERT INTO `@#_member_cashout`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`)VALUES
					('$uid','$money','$username','$bankname','$branch','$banknumber','$linkphone','$time')");
				$res = _send_SMS($member['mobile'],19);
//				var_dump($res);exit;
			  echo 0;exit; 				
			}else{
					$this->db->Autocommit_rollback();
			  echo 3;exit; 
			}
            }
        }
        if(isset($_POST['submit2'])){//充值
            $money      = abs(intval($_POST['txtCZMoney']));
            $type       = 1;
            $pay        ="账户";
            $time       =time();
            $content    ="使用佣金充值到账户";
			session_start();
		  if (empty ( $_SESSION[$uid.'submitcode'] )) {
				$_SESSION[$uid.'submitcode']=microtime();
		  } else {  
			  unset ( $_SESSION [$uid."submitcode"] );
			  echo 3;exit; 
		  }
            if($money <= 0 || $money > $cashouthdtotal){
                echo 1;exit;
            }
            if($cashouthdtotal<$money){
                echo 2;exit;
            }
			$this->db->Autocommit_start();
			$res= $this->db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money`-'$money',`zhichu_money`=`zhichu_money` + $money WHERE uid='$uid'");
			if($res){
	            //插入记录
				$account=$this->db->Query("INSERT INTO `@#_member_rechange`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$uid','$type','$pay','$content','$money','$time')");
				// 查询是否有该记录
				if($account){
					//修改剩余金额
					unset ( $_SESSION [$uid."submitcode"] );
					$leavemoney=$member['money']+$money;
					$mrecode=$this->db->Query("UPDATE `@#_member` SET `money`='$leavemoney' WHERE `uid`='$uid' "); 
					//在佣金表中插入记录
					$recode=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`)VALUES('$uid','-2','$content','$money','$time')");
					$this->db->Autocommit_commit();
                    _send_SMS($member['mobile'],19);
                    echo json_encode(['code' => 1],true);
                    exit;
				}else{
				    echo 3;
                    exit;
				}			
			}else{
				$this->db->Autocommit_rollback();
				    echo 3;
                    exit;
			}
        }
        include templates("mobile/invite","cashout");
    }
    function record(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid = $member['uid'];
        $recount=0;
        $fufen = System::load_app_config("user_fufen",'','member');
        //查询提现记录
        $recordarr=$mysql_model->GetList("select * from  `@#_member_cashout`  where `uid`='$uid' ORDER BY `time` DESC limit 0,30");
        if(!empty($recordarr)){
            $recount=1;
        }
        include templates("mobile/invite","record");
    }

	//云购记录
	public function userbuylist(){
	   $webname=$this->_cfg['web_name'];
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="记录";					
		//$record=$mysql_model->GetList("select * from `@#_member_go_record` where `uid`='$uid' ORDER BY `time` DESC");				
		include templates("mobile/user","userbuylist");
	}
	
	//云购记录详细
	public function userbuydetail(){
	    $webname=$this->_cfg['web_name'];
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="详情";
		$crodid=intval($this->segment(4));
		$record=$mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='$member[uid]' LIMIT 1");		
		if($crodid>0){
			include templates("member","userbuydetail");
		}else{
			echo _message("页面错误",WEB_PATH."/member/home/userbuylist",3);
		}
	}
	
	//获得的商品
	public function orderlist(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="获得的商品";
		//$record=$this->db->GetList("select * from `@#_member_go_record` where `uid`='".$member['uid']."' and `huode`>'10000000' ORDER BY id DESC");				
		include templates("mobile/user","orderlist");
	}
	
	//账户管理
	public function userbalance(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="账户记录";
		$account=$this->db->GetList("select * from `@#_member_account` where `uid`='$member[uid]' and `pay` = '账户' ORDER BY time DESC");
         $czsum=0;
         $xfsum=0;
		if(!empty($account)){ 
			foreach($account as $key=>$val){
			  if($val['type']==1){
				$czsum+=$val['money'];		  
			  }else{
				$xfsum+=$val['money'];		  
			  }		
			} 		
		}
		include templates("mobile/user","userbalance");
	}

	public function userrecharge(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="账户充值";
		//$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1'");
		include templates("mobile/user","recharge");
	}	

	//晒单
	public function singlelist(){
		 $webname=$this->_cfg['web_name'];
		include templates("mobile/user","singlelist");
	}

	//邀请二维码
    public function mycode(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的邀请二维码";          
        $uid=_getcookie('uid');
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                       $member['yungoudj']=$val['name'];
                    }
            }
		}
    include templates("mobile/invite","mycode");
   }

	//合成二维码
	public function mycode_new1(){
		ob_flush();
        header("Content-type: image/png");
        include_once(dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/QRcode.php');
        $code=WEB_PATH."/mobile/mobile/init/"._getcookie('uid');
        $qrcode=dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/tmp/'._getcookie('uid').".png";
        $bg_img=dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/bg.png';
        $rs=QRcode::png($code,$qrcode,"M",4.1,2);
        $path_2 = $qrcode;
        $logo = dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/logo.jpg';//需要显示在二维码中的Logo图像
        $QR = $path_2; 
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
            $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
            $QR_width = imagesx ( $QR );
            $QR_height = imagesy ( $QR );
            $logo_width = imagesx ( $logo );
            $logo_height = imagesy ( $logo );
            $logo_qr_width = $QR_width / 4;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
        }
        imagepng ( $QR, $path_2 );//带Logo二维码的文件名
            // 图片二
            $path_1 = $bg_img;
            // 创建图片对象
            $image_1 = imagecreatefrompng($path_1);
            $image_2 = imagecreatefrompng($path_2);
            // 合成图片
            imagecopymerge($image_1, $image_2, 100, 230, 0, 0, imagesx($image_2), imagesy($image_2), 100);
            // 输出合成图片
            $rs=imagepng($image_1);
			imagedestroy($image_1);
            imagedestroy($image_2);  
    }

    /**
     * 邀请码
     */
     public function mycode_new($type = 2){
         $id = _getcookie("uid");
         if (!is_numeric($id)){
             $id = _encrypt($id,'DECODE');
         }
         $qr_codes = $this->db->GetOne("SELECT `id`,`backgrounds`,`icons`,`qr_code_size`,`qr_code_border`,`left_margin`,`top_margin`,`internal_x`,`internal_y` FROM `@#_qr_codes` WHERE `is_selected` = 1");
         $roots = $_SERVER ['DOCUMENT_ROOT'];
         
         //$rootUrl = '/statics/uploads/codes/user/' . "{$qr_codes['id']}-{$id}.png";
         $rootUrl = '/codes/' . "{$qr_codes['id']}-{$id}.png";
         
         $qrcodeUrl = $roots . $rootUrl;

         $webPath = $money=$this->db->Getone("SELECT `value` FROM `go_config` WHERE name = 'web_path'")['value'];
         $webPath = $webPath;

         if (!file_exists($qrcodeUrl)){
             $qr_codes['bgUrl'] = G_WEB_PATH . "/statics/uploads/" . $qr_codes['backgrounds'];
             $qr_codes['icUrl'] = G_WEB_PATH . "/statics/uploads/" . $qr_codes['icons'];
             mycode_new($id,$qrcodeUrl,$webPath,$qr_codes['bgUrl'],$qr_codes['icUrl'],$qr_codes['qr_code_size'],$qr_codes['qr_code_border'],$qr_codes['left_margin'],$qr_codes['top_margin'],$qr_codes['internal_x'],$qr_codes['internal_y']);
             if (!file_exists($qrcodeUrl)) {
                 echo json_encode(['code'=>0,'msg'=>'网络异常，请联系管理员']);exit;
             }
         }
         if ($type == 1){
              return $rootUrl;
         }
         echo json_encode(['code'=>1,'msg'=>'ok','data'=>$rootUrl]);exit;
    }
}
?>