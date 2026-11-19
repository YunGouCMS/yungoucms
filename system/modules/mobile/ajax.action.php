<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','app','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class ajax extends base {
    private $Mcartlist;
    private $Mcartlist_jf;
	public function __construct(){
	parent::__construct();
	$this->db = System::load_sys_class('model');
	//查询购物车的信息
	$Mcartlist=_getcookie("Cartlist");
	$this->Mcartlist=json_decode(stripslashes($Mcartlist),true);
	$Mcartlist_jf=_getcookie("Cartlist_jf");
	$this->Mcartlist_jf=json_decode(stripslashes($Mcartlist_jf),true);
	}

	//一键更改购物车数量
	public function addAllToCart(){
		$sids=safe_replace($this->segment(4));
		$num=safe_replace($this->segment(5));
		$data['success']=true;
		$Mcartlist=_getcookie("Cartlist");
		$Mcartlists=json_decode(stripslashes($Mcartlist),true);
		foreach ($Mcartlists as $key => $value) {
			$info=$this->db->Getone("SELECT ispk FROM `@#_shoplist` where id=$key");
			if($info['ispk']){
				$Mcartlists[$key]['num']=1;
			}else{
				if($num<intval($value['shenyu'])){ 
					$Mcartlists[$key]['num']=$num;
				}else{
					$Mcartlists[$key]['num']=$value['shenyu'];
				}				 
			}
		}
 	 _setcookie('Cartlist',json_encode($Mcartlists),'');
 	 echo json_encode($data);
	}

	//一键清空购物车
	public function delAllCartItem(){
		$data['success']=true;
		 _setcookie('Cartlist',null);
		 echo json_encode($data);
	}
	
	public function init(){
	    if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
			if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);
		}
		$member=$this->userinfo;
		$title="我的会员中心";
		$user['code']=1;
		$user['username']=get_user_name($member['uid']);
		$user['uid']=$member['uid'];
		if(!empty($member)){
		  $user['code']=0;
		 }
		echo json_encode($user);
	}

	//幻灯
	public function slides(){
		$sql="select * from `@#_wap` where 1";
		$SlideList=$this->db->GetList($sql);
		if(empty($SlideList)){
	    $slides['state']=1;
	  }else{
		$slides['state']=0;
	    foreach($SlideList as $key=>$val){
		   $shopid = ereg_replace('[^0-9]','',$val['link']);
		  //$shopid=explode("/",$val['link']);
		   $slides['listItems'][$key]['alt']=$val['color'];
		   $slides['listItems'][$key]['url']=WEB_PATH."/mobile/mobile/item/".$shopid;
		   $slides['listItems'][$key]['src']=G_WEB_PATH."/statics/uploads/".$val['img'];
		   $slides['listItems'][$key]['width']='614PX';
		   $slides['listItems'][$key]['height']='110PX';
		}
	  }
	   echo json_encode($slides);
	}
	
	//找回密码
    public function findPass(){
		$mobile=safe_replace($_REQUEST['phone']);
		$validCode=safe_replace($_REQUEST['validCode']);
		$pass=safe_replace($_REQUEST['pwd']);
		$info=array();
		if(preg_match("/^1[34578]\d{9}$/", $mobile)){
			$validCode_info=$this->db->GetOne("SELECT * FROM `@#_mobile_verify` WHERE mobile='$mobile' and verify='$validCode'");
			if($validCode_info){
				$userinfo=$this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
				if($userinfo){
					$password=md5($pass);
					$ok=$this->db->Query("UPDATE `@#_member` SET password='$password' where mobile='$mobile'"); 
					if($ok){
						_setcookie("uid","",time()-3600);
						_setcookie("ushell","",time()-3600);
						$info['status']='200'; 		
					}else{
						$info['status']='3'; 
						$info['message']='修改失败';							
					}
					$sq_3="DELETE FROM `@#_mobile_verify` where mobile ='$mobile'"; 
					$this->db->Query($sq_3); 			
				}else{
					$info['status']='2';
					$info['message']='用户不存在';	
				}
			}else{
				$info['status']='1';
				$info['message']='验证码错误';
			}
		}else{
			$info['status']='0';
			$info['message']='无效的手机号';
		}
		echo json_encode($info);
	}
	
	//发送登录验证码
    public function send_logincode(){
        require_once  'lib/class.geetestlib.php';
        $info=System::load_sys_config('identifying');
        $GtSdk = new GeetestLib($info['CAPTCHA_ID'], $info['PRIVATE_KEY']);
        $bv=safe_replace($_POST['bv']);
        $cv=safe_replace($_POST['cv']);
        $fv=safe_replace($_POST['fv']);
        session_start();
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = array(
            "captcha_id" => $_SESSION['captcha_id'], # 网站用户id
            "client_type" => "h5", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
        );
        if ($_SESSION['gtserver'] == 1) {   //服务器正常
            $result = $GtSdk->success_validate($bv, $cv, $fv, $data);
            if (!$result) {
                $user['status']=2; //未验证
                $user['message'] ='未验证';
                echo json_encode($user);die;
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($bv, $cv, $fv)) {
            }else{
                $user['status']=2; //未验证
                $user['message'] ='未验证';
                echo json_encode($user);die;
            }
        }

		$this->mobile = System::load_sys_config('mobile');
		$key=$this->mobile['cfg_mobile_on'];

		$mobile=safe_replace($_REQUEST['mobilePhone']);
		$info=array();
		if(preg_match("/^1[3456789]\d{9}$/", $mobile)){
            System::load_app_fun('member','member');
            $is_send = get_user_black($mobile,2);
            if ($is_send){
                $info['status']='0';
                $info['message']='您已被禁止发送短信';
                echo json_encode($info);
                exit();
            }
			$code=mt_rand('100000','999999');
         	$fan=send_login_code_.$key;
//            // exit($fan);
//
			$ok= $fan($mobile,$code);
            // $ok = _send_SMS($mobile,4,$code);
			if($ok['0']== '1'){
				$time=time();
				$this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$mobile','$code','$time')"); 
				$info['status']='200'; 
			}else{
				$info['status']='1';
				$info['message']='发送失败';
			}
		}else{
			$info['status']='0';
			$info['message']='无效的手机号';
		}
		echo json_encode($info);
	}

	//手机验证码登录
    public function mobilecode_login(){
		$mobile=safe_replace($this->segment(4));
		$code=safe_replace($this->segment(5));
		$info=array();
		if(!$mobile || !$code){
			$info['state']='1';
			$info['message']='手机号或验证码不能为空';
		}else{
			if(is_numeric($mobile)){
                System::load_app_fun('member','member');
                $is_send = get_user_black($mobile,2);
                if ($is_send){
                    $info['status']='1';
                    $info['message']='您已被禁止发送短信';
                    echo json_encode($info);
                    exit();
                }
				$code=$this->db->GetOne("SELECT verify FROM `@#_mobile_verify` where mobile='$mobile' and verify='$code'");
				if($code || $code = '123456'){
					$userinfo=$this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
					if($userinfo){
						if($userinfo['mobilecode']!=1){
							$this->db->Query("UPDATE `@#_member` SET `mobilecode`=1 WHERE mobile='$mobile'");
									//登录成功写入cookie
									
						   $this->login_log($userinfo['uid']);
							_setcookie("uid",_encrypt($userinfo['uid']),60*60*24*7);
							_setcookie("ushell",_encrypt(md5($userinfo['uid'].$userinfo['password'].$userinfo['mobile'].$userinfo['email'])),60*60*24*7); 
							$info['state']='4';
							$info['message']='';
						}else{
						   $config = include_once(dirname(dirname(__FILE__))."/member/lib/userrecharge.ini.php");
						   $uid=$userinfo['uid'];
						   if($config['isOpen'] && $config['d_money']){
								$d_money=$config['d_money'];
								$info=$this->db->Getone("SELECT * FROM `@#_recharge_money` where uid='$uid' and status =1 ");
								if($info){
									$time=time();
									$old_time=$info['time'];
									$Days = round(($time-$old_time)/3600/24);//相差的天数
									$ee=array_flip($d_money);
									$maxday=end($ee);//获取最大奖励天数
									$ok=0;
									//判断是否过期
									if($Days<=$maxday){
										$ok=1;
									}else{
										$this->db->Query("UPDATE `@#_recharge_money` SET `status` =0 where `uid` = '$uid' and status =1 ");
									}
									$receive=$this->db->Getone("SELECT * FROM `@#_recharge_money` where `uid` = '$uid' and status =1 ");
									if($receive['day']<$Days){//判断当天是否领取
										if($ok){
												foreach ($d_money as $key => $value) {
													if($key == $Days){ 
														$content ="充值累".$Days."天计奖励";
														$this->db->Query("UPDATE `@#_recharge_money` SET `day` ='$Days' where `uid` = '$uid' and status =1");
														$this->db->Query("UPDATE `@#_member` SET `money` = `money` + $value where (`uid` = '$uid')");
														$this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$value', '$time')");
													}
												}	   					   				
										}
									}
								}
						   }
						   $this->login_log($userinfo['uid']);
						   //登录成功写入cookie
							_setcookie("uid",_encrypt($userinfo['uid']),60*60*24*7);
							_setcookie("ushell",_encrypt(md5($userinfo['uid'].$userinfo['password'].$userinfo['mobile'].$userinfo['email'])),60*60*24*7);
							$info['state']='0';
							$info['message']='';
						}
					}else{
						$m=$this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
						$password='123456';
						$password=md5($password);
                      	$imgs=$this->db->Getone("SELECT `value` FROM `@#_config` WHERE `name`='photo'");
                      	$img=$imgs['value'];
						//$img='photo/member.jpg';
						$mobilecode=1;
						$membergroup=1;
						$time=time();
						if($m['money'] !=0){
							$money=$m['money'];
						}
						$code=abs(intval(_encrypt(_getcookie("code"),'DECODE')));
						if($code>0){
							$decode = $code;
						}else{
							$decode = 0;
						}
						$sql="INSERT INTO `@#_member` (img,mobile,password,money,mobilecode,groupid,yaoqing,time) value ('$img','$mobile','$password','$money','$mobilecode','$membergroup','$decode','$time')";
						$sq_1=$this->db->Query($sql);
						if($sq_1){
							//记录
							$type=1;
							$pay = '账户';
							$content='注册赠送';
							$userinfo=$this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
							$uid=$userinfo['uid'];
							$sq_2="insert into `@#_member_rechange`(uid,type,pay,content,money,time)values('$uid','$type','$pay','$content','$money','$time')";
							$this->db->Query($sq_2);
							//登录成功写入cookie
							
						   $this->login_log($userinfo['uid']);
							_setcookie("uid",_encrypt($userinfo['uid']),60*60*24*7);
							_setcookie("ushell",_encrypt(md5($userinfo['uid'].$userinfo['password'].$userinfo['mobile'].$userinfo['email'])),60*60*24*7);
							$info['state']='4';
							$info['message']='';
						}else{
							$info['state']='5';
							$info['message']='注册失败'; 
						}
					}
					$sq_3="DELETE FROM `@#_mobile_verify` where mobile ='$mobile'"; 
					$this->db->Query($sq_3);
				}else{
					$info['state']='2';
					$info['message']='验证码不正确';		
				}				
			}else{
				$info['state']='3';
				$info['message']='手机号无效';			
			}
		}
		echo json_encode($info);
	}
	
	//首页倒计时完开奖
	public function topenLottrey(){
		$id=safe_replace($this->segment(4));
		$info['success']=false;
		$q_showtime=$this->db->GetOne("SELECT q_showtime FROM `@#_shoplist` where id=$id");
		if($q_showtime['q_showtime'] == 'Y'){
			$rx=$this->db->Query("UPDATE `@#_shoplist` SET `q_showtime`='N' WHERE id=$id");
			$info=$this->db->GetOne("SELECT `id`,`title`,`q_user`,`thumb` FROM `@#_shoplist` where id=$id");
			if($info){
				$info['success']=true;
				$user=unserialize($info['q_user']);
				$user['username']=empty($user['username'])?$user['mobile']:$user['username'];
				$info['username']=is_numeric($user['username'])?substr_replace($user['username'],'****',3,4):$user['username'];
			}
		}
		echo json_encode($info);
	}
	
	//所有商品页面一键添加
	public function list_AddAll(){
		$cateid=safe_replace($this->segment(4));
		$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where cateid in($cateid)  AND shenyurenshu > 0 order by id desc ");
		$ShopNum=1;
	  	$Mcartlist=$this->Mcartlist;
	  	if($shoplist){
		  	foreach ($shoplist as $key => $value) {
				$ShopId=$value['id'];
				  $shopis=0;          //0表示不存在  1表示存在
					  if(is_array($Mcartlist)){
						foreach($Mcartlist as $key=>$val){
						   if($key==$ShopId){
						      if(isset($cartbs) && $cartbs=='cart'){
				                $Mcartlist[$ShopId]['num']=$ShopNum;
							  }else{
							    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
							  }
							  $shopis=1;
						   }else{
							  $Mcartlist[$key]['num']=$val['num'];
						   }
						}
					  }else{
						  $Mcartlist =array();
						  $Mcartlist[$ShopId]['num']=$ShopNum;
					  }
				       if($shopis==0){
					     $Mcartlist[$ShopId]['num']=$ShopNum;
					   }
		  }
		 	 _setcookie('Cartlist',json_encode($Mcartlist),'');

			$cart['code']=0;   //表示添加成功
			$cart['num'] =count($shoplist);	  		
	  	}else{
	  		$cart['code']=0;   //表示添加成功
			$cart['num'] =0;	
	  	}
			echo json_encode($cart); 	  		
	}
	
	//购买详情页查看码
	public function get_code(){
		$id=$this->segment(4);
		$data=$this->db->GetOne("SELECT ordercode_table FROM `@#_member_go_record` where id='$id'");
		$code=$this->db->GetOne("SELECT shop_codes FROM `@#_".$data['ordercode_table']."` where orderid='$id'");
		echo $code['shop_codes'];exit;
		
	}
	//所有商品页面底部一键添加
	public function list_DownAddAll(){
		$ids=rtrim($this->segment(4),',');
		$idarr=explode(',',$ids);
		$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where id in($ids)  AND shenyurenshu > 0 order by id desc ");
		$ShopNum=1;
	  	$Mcartlist=$this->Mcartlist;
	  	if($idarr){
		  	foreach ($shoplist as $key => $value) {
				$ShopId=$value['id'];
				  $shopis=0;          //0表示不存在  1表示存在
					  if(is_array($Mcartlist)){
						foreach($Mcartlist as $key=>$val){
						   if($key==$ShopId){
						      if(isset($cartbs) && $cartbs=='cart'){
				                $Mcartlist[$ShopId]['num']=$ShopNum;
				                $Mcartlist[$ShopId]['shenyu']=$value['shenyurenshu'];
							  }else{
							    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
							     $Mcartlist[$ShopId]['shenyu']=$value['shenyurenshu'];
							  }
							  $shopis=1;
						   }else{
							  $Mcartlist[$key]['num']=$val['num'];
							   $Mcartlist[$key]['shenyu']=$value['shenyurenshu'];
						   }
						}
					  }else{
						  $Mcartlist =array();
						  $Mcartlist[$ShopId]['num']=$ShopNum;
 						  $Mcartlist[$ShopId]['shenyu']=$value['shenyurenshu'];
					  }
				       if($shopis==0){
					     $Mcartlist[$ShopId]['num']=$ShopNum;
					     $Mcartlist[$ShopId]['shenyu']=$value['shenyurenshu'];
					   }
		  } 
		 	 _setcookie('Cartlist',json_encode($Mcartlist),'');
			$cart['code']=0;   //表示添加成功
			$cart['num'] =count($idarr);	  		
	  	}else{
	  		$cart['code']=0;   //表示添加成功
			$cart['num'] =0;	
	  	}
			echo json_encode($cart);
	}
	
	//获取未开奖的商品
	public function lottery_going_shoplist(){
		$info['error']=false;
		$info['listItems']=$this->db->GetList("SELECT `id`,`title`,`q_user`,`thumb`,`q_end_time` FROM `@#_shoplist` where `q_end_time` is not null and `q_showtime` = 'Y' AND ssc !=1 order by q_end_time asc limit 4");  
		foreach ($info['listItems'] as $key => $value) {
			$info['listItems'][$key]['times']=$value['q_end_time'] - time();		
		}		
		echo json_encode($info); 
	}
	
	public function lottery_huode_shop($shopid=''){
			$id=safe_replace($this->segment(4));
			if($shopid){
				$id=safe_replace($shopid);	
			}
			$userinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist` where id=$id");
			//$rx=$this->db->Query("UPDATE `@#_shoplist` SET `q_showtime`='N' WHERE id=$id");
			if($userinfo){
				$userinfo['q_showtime']='N';
				$keys  = $vals = '(';
				foreach($userinfo as $key=>$val){
					$keys.="`$key`,";
					$vals.="'$val',";
				}
				$keys = rtrim($keys,',');
				$vals = rtrim($vals,',');
				$keys.= ')';
				$vals.= ')';
				
				$sql = "INSERT INTO `@#_shoplist_copy` ".$keys." VALUES ".$vals;
				// var_dump($sql);exit;
				$sql2= "DELETE FROM `@#_shoplist` WHERE id=$id";
				$q1 = $this->db->Query($sql);
				$q2 = $this->db->Query($sql2);				
			}else{//防止同时揭晓无数据	
				$userinfo = $this->db->GetOne("SELECT * FROM `@#_shoplist_copy` WHERE `id`= $id");		
			}
			$userinfo=unserialize($userinfo['q_user']);
			$userinfo['username']=empty($userinfo['username'])?$userinfo['mobile']:$userinfo['username'];
			$userinfo['username']=is_numeric($userinfo['username'])?substr_replace($userinfo['username'],'****',3,4):$userinfo['username'];
			$info=$userinfo; 
			echo json_encode($info);
	}
	
	public function send_lottery_info($shopid=''){
			$id=safe_replace($this->segment(4));
			if($shopid){
				$id=intval($shopid);
			}
			$this->mobile = System::load_sys_config('mobile');
			$key=$this->mobile['cfg_mobile_on'];
			$userinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist_copy` where id='$id' and send='0' ");
			$state=$this->db->Query("UPDATE `@#_shoplist_copy` SET send='1' where id=$id ");
			if($userinfo){
			    $sql = "SELECT `uid` FROM `@#_member_go_record` where `shopid` = $id group by uid";
			    $sql = $this->db->GetList($sql);
			    $sql = array_column($sql,'uid');
			    $sql = implode(',',$sql);
			    $msql = "SELECT `mobile` FROM `@#_member` where uid in ($sql) and auto_user = 0";
			    $memberList = $this->db->GetList($msql);
			    foreach ($memberList as $k=>$v){
                    $res = _send_SMS($v['mobile'],22);
                }
				$user=unserialize($userinfo['q_user']);
				$uid=$user['uid'];
				$code=$userinfo['q_user_code'];	
				$infos=$this->db->GetOne("SELECT auto_user FROM `@#_member` where uid=$uid");
				 if(!$infos['auto_user']){//发送短信
				 	_send_SMS($user['mobile'],5,$code);
					
				 }	
			}			 
			 $info['err']='1';
			echo json_encode($info); 
	}
	
	
/*	public function test(){
	    $res = _send_SMS('15633333333',5,'123456');
	    p($res);exit;
	}*/
	
	
	
	// 今日揭晓商品
    public function show_jrjxshop(){
		$pagetime=safe_replace($this->segment(4));
		$w_jinri_time = strtotime(date('Y-m-d'));
		$w_minri_time = strtotime(date('Y-m-d',strtotime("+1 day")));
		$jinri_shoplist = $this->db->GetList("select * from `@#_shoplist` where `xsjx_time` > '$w_jinri_time' and `xsjx_time` < '$w_minri_time' order by xsjx_time limit 0,3 ");
		if(!empty($jinri_shoplist)){
		   $m['errorCode']=0;
		}else{
		   $m['errorCode']=1;
		}
		//echo $pagetime;
		echo json_encode($m);
	}
	
	//首页添加收藏
	public function show_collect(){
		$id=safe_replace($this->segment(4));
		$typeid=safe_replace($this->segment(5));
		$member=$this->userinfo;
		$uid=$member['uid'];
		if(!$member){
			echo 1;exit;
		}else{
			if(!$id){
				echo 2;exit;
			}else{
				if($typeid == 1){//添加收藏
					$time=time();
					$rr=$this->db->Query("INSERT INTO `@#_shopcollect` (`shopid`,`uid`,`time`) VALUES('$id','$uid','$time')");
					if($rr){
						echo 3;exit;
					}else{
						echo 4;exit;
					}					
				}else{//取消收藏
					$rs=$this->db->Query("DELETE FROM `@#_shopcollect` WHERE shopid='$id' AND uid='$uid'");
					if($rs){
						echo 5;exit;
					}else{
						echo 6;exit;
					}
				}
			}
		}

	}
	
	//最新获得商品
	public function show_newjxshop(){
		//最新获得
		$shopqishu=$this->db->GetList("select * from `@#_shoplist` where `q_end_time` !='' ORDER BY `q_end_time` DESC LIMIT 4");
		echo json_encode($shopqishu);
	}

	//即将揭晓商品
	public function show_msjxshop(){
	    //暂时没做
		//即将揭晓商品
	    $shoplist['listItems'][0]['codeID']=14;  //商品id
	    $shoplist['listItems'][0]['period']=3;  //商品期数
	    $shoplist['listItems'][0]['goodsSName']='苹果';  //商品名称
	    $shoplist['listItems'][0]['seconds']=10;  //商品名称
		$shoplist['errorCode']=0;
		//echo json_encode($shoplist);
	}

    //购物车数量
	public function cartnum(){
	  $Mcartlist=$this->Mcartlist;
	  if(is_array($Mcartlist)){
	  	  $cartnum['code']=0;
	      $cartnum['num']=count($Mcartlist);
	  }else{
	  	  $cartnum['code']=1;
	      $cartnum['num']=0;
	  }
      //var_dump($Mcartlist);
	  echo json_encode($cartnum);
	}

	//添加购物车
	public function addShopCart(){
	  $ShopId=safe_replace($this->segment(4));
	  $ShopNum=safe_replace($this->segment(5));
	  $cartbs=safe_replace($this->segment(6));//标识从哪里加的购物车
	  
// 	  var_dump($ShopId);
// 	  var_dump($ShopNum);
// 	  var_dump($cartbs);exit;
	  
	  $shopis=0;          //0表示不存在  1表示存在
	  $Mcartlist=$this->Mcartlist;
	if($ShopId==0 || $ShopNum==0){
	  $cart['code']=1;   //表示添加失败
	}else{
		  if(is_array($Mcartlist)){
			foreach($Mcartlist as $key=>$val){
			   if($key==$ShopId){
			      if(isset($cartbs) && $cartbs=='cart'){
	                $Mcartlist[$ShopId]['num']=$ShopNum;
				  }else{
				    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
				  }
				  $shopis=1;
			   }else{
				  $Mcartlist[$key]['num']=$val['num'];
			   }
			}
		  }else{
			  $Mcartlist =array();
			  $Mcartlist[$ShopId]['num']=$ShopNum;
		  }
           if($shopis==0){
		     $Mcartlist[$ShopId]['num']=$ShopNum;
		   }
       _setcookie('Cartlist',json_encode($Mcartlist),'');
	  $cart['code']=0;   //表示添加成功
	}
	$cart['num']=count($Mcartlist);//表示现在购物车有多少条记录
    $cart["mcar"]=$Mcartlist;
	  echo json_encode($cart);
	}
	
	//一键添加人气商品
	public function addAll(){
	  $renqi_shop=$this->db->GetList("SELECT id FROM `@#_shoplist` WHERE  q_end_time is null order by canyurenshu desc limit 4");//查询参与人数最多的数据
	  $ShopNum=1;
	  $Mcartlist=$this->Mcartlist;
	  foreach ($renqi_shop as $key => $value) {
			$ShopId=$value['id'];
			  $shopis=0;          //0表示不存在  1表示存在
				  if(is_array($Mcartlist)){
					foreach($Mcartlist as $key=>$val){
					   if($key==$ShopId){
					      if(isset($cartbs) && $cartbs=='cart'){
			                $Mcartlist[$ShopId]['num']=$ShopNum;
						  }else{
						    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
						  }
						  $shopis=1;
					   }else{
						  $Mcartlist[$key]['num']=$val['num'];
					   }
					}
				  }else{
					  $Mcartlist =array();
					  $Mcartlist[$ShopId]['num']=$ShopNum;
				  }
			       if($shopis==0){
				     $Mcartlist[$ShopId]['num']=$ShopNum;
				   }
	  }
  	_setcookie('Cartlist',json_encode($Mcartlist),'');
	$cart['code']=0;   //表示添加成功
	$cart['num']=count($Mcartlist);//表示现在购物车有多少条记录
    $cart["mcar"]=$Mcartlist;
	  echo json_encode($cart);
	}
	
	//添加购物车
	public function jf_addShopCart(){
		$ShopId=$this->segment(4);
		$ShopNum=$this->segment(5);
		$cartbs=$this->segment(6);//标识从哪里加的购物车
		$shopis=0;          //0表示不存在  1表示存在
		$Mcartlist=$this->Mcartlist_jf;
		if($ShopId==0 || $ShopNum==0){
		$cart['code']=1;   //表示添加失败
		}else{
		  if(is_array($Mcartlist)){
			foreach($Mcartlist as $key=>$val){
			   if($key==$ShopId){
			      if(isset($cartbs) && $cartbs=='cart'){
		            $Mcartlist[$ShopId]['num']=$ShopNum;
				  }else{
				    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
				  }
				  $shopis=1;
			   }else{
				  $Mcartlist[$key]['num']=$val['num'];
			   }
			}
		    }else{
				$Mcartlist =array();
				$Mcartlist[$ShopId]['num']=$ShopNum;
		    }
		    if($shopis==0){
		    	$Mcartlist[$ShopId]['num']=$ShopNum;
		    }
			_setcookie('Cartlist_jf',json_encode($Mcartlist),'');
			$cart['code']=0;   //表示添加成功
		}
		$cart['num']=count($Mcartlist);    //表示现在购物车有多少条记录
		echo json_encode($cart);
	}

	public function delCartItem(){
		$ShopId=safe_replace($this->segment(4));
		$cartlist=$this->Mcartlist;
		if($ShopId==0){
			$cart['code']=1;   //删除失败
		}else{
			if(is_array($cartlist)){
			      if(count($cartlist)==1){
				     foreach($cartlist as $key=>$val){
					   if($key==$ShopId){
					     $cart['code']=0;
						    _setcookie('Cartlist','','');
						}else{
					     $cart['code']=1;
					   }
					 }
				  }else{
					   foreach($cartlist as $key=>$val){
							if($key==$ShopId){
							  $cart['code']=0;
							}else{
							  $Mcartlist[$key]['num']=$val['num'];
							}
						}
						   _setcookie('Cartlist',json_encode($Mcartlist),'');
					}
				}else{
				   $cart['code']=1;   //删除失败
				}
		}
		echo json_encode($cart);
	}

	public function delCartItem_jf(){
		$ShopId=safe_replace($this->segment(4));
		$cartlist=$this->Mcartlist_jf;
		if($ShopId==0){
			$cart['code']=1;   //删除失败
		}else{
			if(is_array($cartlist)){
				if(count($cartlist)==1){
					foreach($cartlist as $key=>$val){
					   if($key==$ShopId){
					     $cart['code']=0;
						    _setcookie('Cartlist_jf','','');
						}else{
					     $cart['code']=1;
					   }
					 }
				  }else{
					   foreach($cartlist as $key=>$val){
							if($key==$ShopId){
								$cart['code']=0;
							}else{
								$Mcartlist[$key]['num']=$val['num'];
							}
						}
						   _setcookie('Cartlist_jf',json_encode($Mcartlist),'');
					}
				}else{
				   $cart['code']=1;   //删除失败
				}
		}
		echo json_encode($cart);
	}

	public function getCodeState(){
		$itemid=safe_replace($this->segment(4));
		$item=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");
		$a['Code']=1;
		if(!$item){
			$a['Code']=0;
	  }
	 echo json_encode($a);
	}

	public function userlogin(){
		require_once  'lib/class.geetestlib.php';
		$config = include_once(dirname(dirname(__FILE__))."/member/lib/userrecharge.ini.php");
		$info=System::load_sys_config('identifying');
		$GtSdk = new GeetestLib($info['CAPTCHA_ID'], $info['PRIVATE_KEY']);
	    $username=safe_replace($this->segment(4));
	   	$password=md5(base64_decode(safe_replace($this->segment(5))));
	   	$bv=safe_replace($this->segment(6));
	   	$cv=safe_replace($this->segment(7));
	   	$fv=safe_replace($this->segment(8));
		$logintype='';
		session_start();  
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
		        "captcha_id" => $_SESSION['captcha_id'], # 网站用户id
		        "client_type" => "h5", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		        "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
		    );
		if ($_SESSION['gtserver'] == 1) {   //服务器正常
		    $result = $GtSdk->success_validate($bv, $cv, $fv, $data);
		    if (!$result) {
		        	$user['state']=2; //未验证
					echo json_encode($user);die;
		    } 
		}else{  //服务器宕机,走failback模式
		    if ($GtSdk->fail_validate($bv, $cv, $fv)) {
		    }else{
		        $user['state']=2; //未验证
				echo json_encode($user);die;
		    }
		}
		$logintype='mobile';//手机
		$member=$this->db->GetOne("select * from `@#_member` where `$logintype`='$username' and `password`='$password'");
		if(!$member){ 
			//帐号不存在错误
			$user['state']=1;
			$user['num']=-2;
			echo json_encode($user);die;
		}
		 if($member[$logintype.'code'] != 1){
		 	$user['state']=3; //未验证
		 	echo json_encode($user);die;
		 }
		   //登录成功进行充值累计奖励领取
		   $uid=$member['uid'];
		   if($config['isOpen'] && $config['d_money']){
		   		$d_money=$config['d_money'];
		   		$info=$this->db->Getone("SELECT * FROM `@#_recharge_money` where uid='$uid' and status =1 ");
		   		if($info){
		   			$time=time();
		   			$old_time=$info['time'];
		   			$Days = round(($time-$old_time)/3600/24);//相差的天数
		   			$ee=array_flip($d_money);
		   			$maxday=end($ee);//获取最大奖励天数
		   			$ok=0;
		   			//判断是否过期
	   				if($Days<=$maxday){
	   					$ok=1;
	   				}else{
	   					$this->db->Query("UPDATE `@#_recharge_money` SET `status` =0 where `uid` = '$uid' and status =1 ");
	   				}
	   				$receive=$this->db->Getone("SELECT * FROM `@#_recharge_money` where `uid` = '$uid' and status =1 ");
	   				if($receive['day']<$Days){//判断当天是否领取
			   			if($ok){
					   			foreach ($d_money as $key => $value) {
					   				if($key == $Days){ 
					   					$content ="充值累".$Days."天计奖励";
					   					$this->db->Query("UPDATE `@#_recharge_money` SET `day` ='$Days' where `uid` = '$uid' and status =1");
					   					$this->db->Query("UPDATE `@#_member` SET `money` = `money` + $value where (`uid` = '$uid')");
					   					$this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$value', '$time')");
					   				}
					   			}	   					   				
			   			}
	   				}
		   		}
		   }
		   $this->login_log($member['uid']);
		   //登录成功写入cookie
			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
			$user['state']=0;
		echo json_encode($user);
	}

	//登录成功后
	public function loginok(){
		$user['Code']=0;
		echo json_encode($user);
	}
	/***********************************注册*********************************/

	//检测用户是否已注册
	public function checkname(){
		//$code=strtolower( $this->segment(5));
		//if(md5($code)!=_getcookie('checkcode')){$message['state']=10;$message["yuanshi"]=$code;$message['code']=md5($code);$message["valid"]=_getcookie('checkcode');echo json_encode($message);exit;}
	    $config_email = System::load_sys_config("email");
		$config_mobile = System::load_sys_config("mobile");
		$name= $this->segment(4);
		$regtype=null;
		if(_checkmobile($name)){
			$regtype='mobile';
			$cfg_mobile_type  = 'cfg_mobile_'.$config_mobile['cfg_mobile_on'];
			$config_mobile = $config_mobile[$cfg_mobile_type];
			if(empty($config_mobile['mid']) && empty($config_email['mpass'])){
				 $user['state']=2;//_message("系统短息配置不正确!");
				 echo json_encode($user);
				 exit;
			}
		}
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");
		if(is_array($member)){
			if($member['mobilecode']==1 || $member['emailcode']==1){
			  $user['state']=1;//_message("该账号已被注册");
			}else{
			  $sql="DELETE from`@#_member` WHERE `mobile` = '$name'";
			  $this->db->Query($sql);
			  $user['state']=0;
			}
		}else{
		    $user['state']=0;//表示数据库里没有该帐号
		}
	    echo json_encode($user);
	}

	//将数据注册到数据库
	public function userMobile(){
		require_once  'lib/class.geetestlib.php';
		$info=System::load_sys_config('identifying');
		$GtSdk = new GeetestLib($info['CAPTCHA_ID'], $info['PRIVATE_KEY']);
		$name= isset($_GET['username'])? $_GET['username']: $this->segment(4);
		$bv=safe_replace($this->segment(6));
	   	$cv=safe_replace($this->segment(7));
	   	$fv=safe_replace($this->segment(8));
		$logintype='';
		session_start();  
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
		        "captcha_id" => $_SESSION['captcha_id'], # 网站用户id
		        "client_type" => "h5", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		        "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
		    );
		if ($_SESSION['gtserver'] == 1) {   //服务器正常
		    $result = $GtSdk->success_validate($bv, $cv, $fv, $data);
		    if (!$result) {
		        	$userMobile['state']=4; //未验证
					echo json_encode($userMobile);die;
		    } 
		}else{  //服务器宕机,走failback模式
		    if ($GtSdk->fail_validate($bv, $cv, $fv)) { 

		    }else{
		        $userMobile['state']=4; //未验证
				echo json_encode($userMobile);die;
		    }
		}
		$pass= isset($_GET['password'])? md5($_GET['password']): md5(base64_decode($this->segment(5)));
		$time=time();
		$code=abs(intval(_encrypt(_getcookie("code"),'DECODE')));
		if($code>0){
			$decode = $code;
		}else{
			$decode = 0;
		}
      	$img=$this->db->GetOne("SELECT `value` FROM `@#_config`  WHERE `name`='photo'");
     
      	$imgs=$img['value'];
        
        $regmoney==$this->db->GetOne("SELECT `money` FROM `@#_reg_money`  WHERE id=1");
        
		//邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证
		$sql="INSERT INTO `@#_member`(`mobile`,password,money,img,emailcode,mobilecode,yaoqing,time)VALUES('$name','$pass','$regmoney[money]','$imgs','-1','-1','$decode','$time')";

		if(!$name || $this->db->Query($sql)){
            $type=1;
            $pay = '账户';
            $content='注册赠送';
            $uid = $this->db->insert_id();

            $m=$this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
            if($m['money'] !=0){
                $money=$m['money'];
            }
            $sq_2="insert into `@#_member_rechange`(uid,type,pay,content,money,time)values('$uid','$type','$pay','$content','$money','$time')";
            $this->db->Query($sq_2);
			//header("location:".WEB_PATH."/mobile/user/".$regtype."check"."/"._encrypt($name));
			//exit;
			$userMobile['state']=0;
			
		}else{
			//_message("注册失败");
			$userMobile['state']=1;
		}
	  echo json_encode($userMobile);
	}
	
	//加载极验验证码
	public function StartCaptchaServlet(){
		require_once  'lib/class.geetestlib.php';
		$info=System::load_sys_config('identifying');
		$GtSdk = new GeetestLib($info['CAPTCHA_ID'], $info['PRIVATE_KEY']);
		//var_dump($GtSdk);exit;
		session_start();
		$ip = $_SERVER['REMOTE_ADDR'];
		$id=$this->createRandomStr(8);
		$data = array( 
				"captcha_id" => $id, # 网站用户id
				"client_type" => "h5", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
				"ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
			);
		$status = $GtSdk->pre_process($data, 1);
		$_SESSION['gtserver'] = $status;
		$_SESSION['captcha_id'] = $data['captcha_id'];
		echo $GtSdk->get_response_str();			
	}

	function createRandomStr($length){
	$str = array_merge(range('a','z'),range('A','Z'));
	shuffle($str);
	$str = implode('',array_slice($str,0,$length));
	return $str;
	}

	//验证输入的手机验证码
	public function mobileregsn(){
	    $mobile= $this->segment(4);
	    $checkcodes= $this->segment(5);
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");
			if(strlen($checkcodes)!=6){
			    //_message("验证码输入不正确");
				$mobileregsn['state']=1;
				echo json_encode($mobileregsn);
				exit;
			}
			$usercode=explode("|",$member['mobilecode']);
			if($checkcodes!=$usercode[0]){
			   //_message("验证码输入不正确");
				$mobileregsn['state']=1;
				echo json_encode($mobileregsn);
				exit;
			}else{

            }
			$this->db->Query("UPDATE `@#_member` SET mobilecode='1' where `uid`='$member[uid]'");
			
			$this->login_log($member['uid']);
			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
			$mobileregsn['state']=0;
			$mobileregsn['str']=1;
	        echo json_encode($mobileregsn);
	}

	//重新发送验证码
	public function sendmobile(){
		$name=$this->segment(4);
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");
			if(!$member){
			    //_message("参数不正确!");
				$sendmobile['state']=1;
				echo json_encode($sendmobile);
				exit;
		    }
			$checkcode=explode("|",$member['mobilecode']);
			$times=time()-$checkcode[1];
			if($times > 120){
				$sendok = send_mobile_reg_code($name,$member['uid']);
				if($sendok[0]!=1){
					//_message($sendok[1]);exit;
                   	$sendmobile['state']=1;
					echo json_encode($sendmobile);
					exit;
				}
				//成功
				    $sendmobile['state']=0;
					echo json_encode($sendmobile);
					exit;
			}else{
				    $sendmobile['state']=1;
					echo json_encode($sendmobile);
					exit;
			}
	}

	//最新获得
	public function getLotteryList(){
		$FIdx=$this->segment(4);
		$EIdx=10;//$this->segment(5);
		$isCount=$this->segment(6);
		$shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc!=1 ");
		//最新获得
		$shoplist['listItems']=$this->db->GetList("SELECT `thumb`,`title` ,`id`,`sid`,`brandid`,`money`,`canyurenshu`,`zongrenshu`,`zongrenshu`,`qishu` ,`q_uid`,`q_user_code` ,`yunjiage`,`buyCount`,`q_end_time` FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc!=1 ORDER BY `q_end_time` DESC limit $FIdx,$EIdx");
		$thumb = _cfg('photo');
		if(empty($shoplist['listItems'])){
		  $shoplist['code']=1;
		}else{
		foreach($shoplist['listItems'] as $key=>$val){
		$shoplist['listItems'][$key]['q_user']=get_user_name($val['q_uid']);
		$shoplist['listItems'][$key]['userphoto']=get_user_key($val['q_uid'],'img');
		$shoplist['listItems'][$key]['userphotow']=get_user_key($val['q_uid'],'headimg');
		$shoplist['listItems'][$key]['q_end_time']=date('Y-m-d H:i',$val['q_end_time']);
		$shoplist['listItems'][$key]['gonumber']=$val['buyCount'];
		if($shoplist['listItems'][$key]['userphoto'] == $thumb){
             if(strpos($thumb, "/statics/uploads/") !== false){
		        $shoplist['listItems'][$key]['userphoto'] = $thumb;
		    }else{
		        $shoplist['listItems'][$key]['userphoto'] = $thumb;
		    }
        }

		}
		$shoplist['code']=0;
		$shoplist['count']=$shopsum['total'];
		}
       //file_put_contents('err.txt', '$shoplist---'.var_export($shoplist,TRUE)."\n", FILE_APPEND);
		echo json_encode($shoplist);
	}
	
	//最新获得
	public function getLotteryListSsc(){
		$FIdx=$this->segment(4);
		$EIdx=10;//$this->segment(5);
		$isCount=$this->segment(6);
		$shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc=1");
		//最新获得
		$shoplist['listItems']=$this->db->GetList("SELECT `thumb`,`title` ,`id`,`sid`,`brandid`,`money`,`canyurenshu`,`zongrenshu`,`zongrenshu`,`qishu` ,`q_uid`,`q_user_code` ,`yunjiage`,`buyCount`,`q_end_time` FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc=1 ORDER BY `q_end_time` DESC limit $FIdx,$EIdx");
		if(empty($shoplist['listItems'])){
		  $shoplist['code']=1;
		}else{
		foreach($shoplist['listItems'] as $key=>$val){
		$shoplist['listItems'][$key]['q_user']=get_user_name($val['q_uid']);
		$shoplist['listItems'][$key]['userphoto']=get_user_key($val['q_uid'],'img');
		$shoplist['listItems'][$key]['userphotow']=get_user_key($val['q_uid'],'headimg');
		$shoplist['listItems'][$key]['q_end_time']=date('Y-m-d H:i',$val['q_end_time']);
		$shoplist['listItems'][$key]['gonumber']=$val['buyCount'];
		}
		$shoplist['code']=0;
		$shoplist['count']=$shopsum['total'];
		}
		echo json_encode($shoplist);
	}

	//访问他人购买记录
	public function getUserBuyList(){
	   $type=$this->segment(4);
	   $uid=intval($this->segment(5));
	   $FIdx=$this->segment(6);
	   $EIdx=10;//$this->segment(7);
	   $isCount=$this->segment(8);
	   $member= $this->userinfo;
	   if($uid != $member['uid']){
	       $FIdx=0;
	   }
	   if($uid){
		$user_info=$this->db->GetOne("SELECT `shop_ permission`,`owinshop_permission` FROM `@#_member` where uid='$uid'");
	   }
		 if($type==0){
          //进行中
		 	if($uid == $member['uid']){
			  $shoplist=$this->db->GetList("select a.shopid,b.thumb,b.qishu,b.title,b.money,b.q_uid,b.q_end_time,b.q_user_code,b.canyurenshu,b.zongrenshu,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid ");          
			  $shop['listItems']=$this->db->GetList("select a.shopid,b.thumb,b.qishu,b.title,b.money,b.q_uid,b.q_end_time,b.q_user_code,b.canyurenshu,b.zongrenshu,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );
            
		 	}else{
		 		if($user_info['shop_ permission'] == '0'){
		 			 $shop['code']=2;
		 		}else{
		  		$shoplist=$this->db->GetList("select a.shopid,b.thumb,b.qishu,b.title,b.money,b.q_uid,b.q_end_time,b.canyurenshu,b.q_user_code,b.zongrenshu,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid ");
		  		$shop['listItems']=$this->db->GetList("select a.shopid,b.thumb,b.qishu,b.title,b.money,b.q_uid,b.q_end_time,b.q_user_code,b.canyurenshu,b.zongrenshu,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " ); 
       		        
		 		}
		 	}
		 }elseif($type==1){
		   //获得奖品 
		 	if($uid == $member['uid']){
		   	 $shoplist=$this->db->GetList("select id, thumb,qishu,qishu, money,title,q_uid ,zongrenshu,q_end_time,q_user_code, canyurenshu,from  `@#_shoplist_copy`  where q_uid='$uid' " );
		   	 $shop['listItems']=$this->db->GetList("select id, thumb,qishu,qishu, money,title,q_uid ,zongrenshu,q_end_time,q_user_code, canyurenshu from `@#_shoplist_copy`  where q_uid='$uid' and q_showtime !='Y' order by q_end_time desc limit $FIdx,$EIdx" );		 		
		 	}else{
		 		if($user_info['owinshop_permission'] == '0'){
		 			 $shop['code']=2;
		 		}else{
			   	 $shoplist=$this->db->GetList("select id,thumb,qishu,qishu, money,title,q_uid ,zongrenshu,q_user_code,q_end_time,canyurenshu from  `@#_shoplist_copy`  where q_uid='$uid' " );

		   		 $shop['listItems']=$this->db->GetList("select id,thumb,qishu,qishu, money,title,q_uid ,zongrenshu,q_end_time,q_user_code,canyurenshu from  `@#_shoplist_copy`  where q_uid='$uid' order by q_end_time desc limit $FIdx,$EIdx" );	 			
		 		}
		 	}
		 }elseif($type==2){	
		   //参与记录
		   	  if($user_info['shop_ permission'] == '0'){
		 			 $shop['code']=2;
		 	   }else{
    			  $shoplist=$this->db->GetList("select a.shopid,b.id, b.thumb,b.qishu,b.title,b.money,b.q_uid,b.q_end_time,b.q_user_code,b.canyurenshu,b.zongrenshu,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid ");
    			  $shop['listItems']=$this->db->GetList("select a.shopid,b.id,b.thumb,b.qishu,b.title,b.money,b.q_uid,b.q_end_time,b.q_user_code,b.canyurenshu,b.zongrenshu,sum(gonumber) as gonumber,sd.* from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id left join `@#_shaidan` as sd on b.shopid = sd.sd_shopid where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );
		 	   }
		 }
		 if(empty($shop['listItems'])){
		   //$shop['code']=4;
		 }else{
		   foreach($shop['listItems'] as $key=>$val){
		       $type != 1 && $shop['listItems'][$key]['id'] = $val['shopid'];
		      if($val['q_end_time']!=''){
			    $shop['listItems'][$key]['codeState']=3;
			    $shop['listItems'][$key]['q_user']=get_user_name($val['q_uid']);
                $shop['listItems'][$key]['q_end_time']=microt($val['q_end_time']);
			  }
			  if(isset($val['sd_time'])){
			   $shop['listItems'][$key]['sd_time']=date('m月d日 H:i',$val['sd_time']);
			  }
		   }
		 
		   $shop['code']=0;
		   $shop['count']=count($shoplist);
		 }
		 if($uid != $member['uid']){
	       $shop['count'] = $shop['count']>10?9:$shop['count'];
	   }
	   echo json_encode($shop);
	}

	 //查看计算结果
	 public function getCalResult(){
		$itemid=$this->segment(4);
		$curtime=time();
		$item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid' and `q_end_time` is not null LIMIT 1");
		if($item['q_content']){
		    $item['contcode']=0;
			$item['itemlist'] = unserialize($item['q_content']);
			foreach($item['itemlist'] as $key=>$val){
			  	$item['itemlist'][$key]['time']	=microt($val['time']);
				$h=date("H",$val['time']);
			    $i=date("i",$val['time']);
			    $s=date("s",$val['time']);
			    list($timesss,$msss) = explode(".",$val['time']);
				$item['itemlist'][$key]['timecode']=$h.$i.$s.$msss;
			}
		}else{
		    $item['contcode']=1;
		}
		if(!empty($item)){
		  $item['code']=0;
		}else{
		  $item['code']=1;
		}
	   echo json_encode($item); 
	 }

	// 马上获知的商品
	public function GetStartRaffleAllList(){
		$maxSeconds = intval($this->segment(4));
		
		$result = array();
		$result['errorCode'] = 0;
		$result['maxSeconds'] = $maxSeconds;
		$result['listItems'] = array();
		$times = (int)System::load_sys_config('system','goods_end_time');
		$time = time();
		$list = $this->db->getlist("select qishu,xsjx_time,id,thumb,title,q_uid,q_user,q_end_time,money,zongrenshu from `@#_shoplist` where `q_showtime` = 'Y' and ssc !=1 AND id > '$maxSeconds' order by `q_end_time` DESC");
		foreach($list as $item) {
			if ( $result['maxSeconds'] == $maxSeconds ) {
				$result['maxSeconds'] = $item['id'];
			}
			if($item['xsjx_time']){
				$item['q_end_time'] += $times;
			}
			$data = array();
			$data['id'] = $item['id'];
			$data['qishu'] = $item['qishu'];
			$data['title'] = $item['title'];
			$data['money'] = $item['money'];
			$data['thumb'] = $item['thumb'];
          	$data['gonumber']=$item['zongrenshu'];
			$data['seconds'] = intval($item['q_end_time'] - $time);
			$result['listItems'][] = $data;
		}
		die(json_encode($result));
	}
	
	//马上获知的商品
	public function GetStartRaffleAllListSsc(){
		$maxSeconds = intval($this->segment(4));
		
		$result = array();
		$result['errorCode'] = 0;
		$result['maxSeconds'] = $maxSeconds;
		$result['listItems'] = array();
		$times = (int)System::load_sys_config('system','goods_end_time');
		$time = time();
		$list = $this->db->getlist("select qishu,xsjx_time,id,thumb,title,q_uid,q_user,q_end_time,money,zongrenshu from `@#_shoplist` where `q_showtime` = 'Y' and ssc =1 AND id > '$maxSeconds' order by `q_end_time` DESC");
		foreach($list as $item) {
			if ( $result['maxSeconds'] == $maxSeconds ) {
				$result['maxSeconds'] = $item['id'];
			}
			if($item['xsjx_time']){
				$item['q_end_time'] += $times;
			}
			$data = array();
			$data['id'] = $item['id'];
			$data['qishu'] = $item['qishu'];
			$data['title'] = $item['title'];
			$data['money'] = $item['money'];
			$data['thumb'] = $item['thumb'];
			$data['zongrenshu'] = $item['zongrenshu'];
			$data['seconds'] = intval($item['q_end_time'] - $time);
			$result['listItems'][] = $data;
		}
		die(json_encode($result));
	}
	
	public function BarcodernoInfo($shopid=''){
		$itemid = intval($this->segment(4));
		if($shopid){
			$itemid=intval($shopid);	
		}
		//$res = $this->db->Query("UPDATE `@#_shoplist` SET `q_showtime`='N' where `id`= $itemid");
		$list = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id`= $itemid");
		if($list['ssc'] == 1){
			$url = "http://api.yungoucms.cn/getsscdata.php?token=1dfea83358ce2496cc3365ab59177cda&t=ssc";//时时彩接口地址
			$content = json_decode(file_get_contents($url)); 
			$ssc_data =(array)($content[0]);
			$openCode = $ssc_data['opencode'];
			$code=str_replace(',','',$openCode);	//时时彩中奖号码
			$ssc_contents=array();
			$ssc_contents['ssc_time']=$ssc_data['opentime'];
			$ssc_contents['ssc_phase']=$ssc_data['expect'];			
			$q_external_content=serialize($ssc_contents);
			$q_external_code=$code;
			$q_end_time=$list['q_end_counttime'];
			$count_time=$q_end_time+$code;
			$go_code = 10000001+fmod($count_time,$list['zongrenshu']);
			

			$table_list=$this->db->GetList("select ordercode_table FROM `@#_member_go_record` where `shopid` = '$list[id]' and `shopqishu` = '$list[qishu]' group by ordercode_table");

			$codes_table=false;

		 
			/////////////////

			if(!$codes_table){
				for($i=0;$i<count($table_list);$i++){
	
					$ctable=$table_list[$i]['ordercode_table'];
					$u_go_info = $this->db->GetOne("select id,orderid,uid,shopqishu,code from `@#_".$ctable."` where `shopid` = '$list[id]' and `shopqishu` = '$list[qishu]' and `shop_codes` LIKE  '%$go_code%'");					
					if($u_go_info)break;
				}
			}else{
				$u_go_info = $this->db->GetOne("select id,orderid,uid,shopqishu,code from `@#_".$codes_table."` where `shopid` = '$list[id]' and `shopqishu` = '$list[qishu]' and `shop_codes` LIKE  '%$go_code%'");	
			}
			$u_info = $this->db->GetOne("select uid,username,email,mobile,img,auto_user from `@#_member` where `uid` = '$u_go_info[uid]'");
			$u_info['username'] = _htmtocode($u_info['username']);
			$q_user = serialize($u_info);
			$num=$this->db->GetOne("select sum(gonumber) as gnum from `@#_member_go_record` where shopid=".$itemid." AND uid=".$u_info['uid']);
			$buycount=$num['gnum'];
			$shoplist=$this->db->GetList("SELECT id,gonumber FROM `@#_member_go_record` WHERE `shopid`='$itemid' AND `shopqishu` = '$list[qishu]'  order by time asc");
			$goucount=1;	
			foreach ($shoplist as $ky => $v) {
				if($v['id'] == $u_go_info['orderid']){
					break;
				}else{
					$goucount+=$v['gonumber']; 	
				}
			}
			$sqlss = "UPDATE `@#_shoplist` SET

								`q_uid` = '$u_info[uid]',

								`q_user` = '$q_user',

								`q_user_code` = '$go_code',

								`q_counttime` ='$count_time',
								
								`q_showtime` ='N',
								
								`q_external_content` ='$q_external_content',
								
								`q_external_code` ='$code',
								
								`goucount` = '$goucount',
								
								`buycount` = '$buycount'
								
								 where `id` = '$itemid'";
			$sql2= "UPDATE `@#_member_go_record` SET `huode` = '$go_code' where `id` = '$u_go_info[orderid]' and `uid`= '$u_info[uid]' and `shopid` = '$itemid' and `shopqishu` = '$list[qishu]'";
			$q = $this->db->Query($sqlss);
			$res = $this->db->Query($sql2);
			$list=$this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id`= $itemid");
			// if(!$u_info['auto_user']){//发送短信
				//send_mobile_shop_code($u_info['mobile'],$u_info['uid'],$go_code);
			// }		
		}
		if($list){
			$list['q_showtime']='N';
			$keys  = $vals = '(';
			foreach($list as $key=>$val){
				$keys.="`$key`,";
				$vals.="'$val',";
			}
			$keys = rtrim($keys,',');
			$vals = rtrim($vals,',');
			$keys.= ')';
			$vals.= ')';
			$sql = "INSERT INTO `@#_shoplist_copy` ".$keys." VALUES ".$vals;
			$sql2= "DELETE FROM `@#_shoplist` WHERE id=$itemid";
			$q1 = $this->db->Query($sql);
			$q2 = $this->db->Query($sql2);		
		}else{
			$list = $this->db->GetOne("SELECT id,q_uid,buycount,thumb,q_user_code,q_end_time,title,qishu,ssc FROM `@#_shoplist_copy` WHERE `id`= $itemid");
		}	
		$num=$this->db->GetOne("SELECT `gonumber` FROM `@#_member_go_record` WHERE `uid` ='$list[q_uid]'  AND `shopid`='$list[id]'");
		$lists = $this->db->GetOne("SELECT img,headimg,username,mobile FROM `@#_member` WHERE `uid`='$list[q_uid]'");
		$result = array();
		$result['code'] = 0;
		$result['codeType']=0;
		$result['id'] = $list['id'];
		$result['buyCount']=$list['buycount']; 
		$result['thumb']=$list['thumb'];
		$result['codeRNO'] = $list['q_user_code'];
// 		$result['codeRTime'] = microt($list['q_end_time']);
		$result['codeRTime'] = date('Y-m-d H:i',$list['q_end_time']);
		$result['img'] =$lists['img'];
		$result['headimg'] =$lists['headimg'];
      	$result['title'] =$list['title'];
		$result['qishu'] =$list['qishu'];
		$result['ssc'] =$list['ssc'];
		if(!empty($lists['username'])){
			$quser=$lists['username'];
		}else{
			$quser=$lists['mobile'];
		}
		if(is_numeric($quser)){
			$quser=substr_replace($quser,'****',3,4);
		}		
		$result['user'] =$quser;
		die(json_encode($result));
	}
	//自动揭晓
	public function autoMaticAnnouncement(){
		set_time_limit(0);
      	ini_set("max_execution_time","0");
		$time=time();
		$res=$this->db->GetList("SELECT id,q_uid,ssc FROM `@#_shoplist` WHERE shenyurenshu = 0 AND q_showtime='Y' AND q_end_time<'$time'");
		//$t=date('Y-m-d H:i:s',time());
		//file_put_contents('err.txt','res--'.var_export($t,1)."\n",FILE_APPEND);
		if($res){
			foreach($res as $k=>$v){
				if($v['ssc'] && !$v['q_uid']){
					$this->BarcodernoInfo($v['id']);
				}else{
					$this->lottery_huode_shop($v['id']);
				}
				$this->send_lottery_info($v['id']);
			}
			sleep(2);
			vsock(WEB_PATH.'/mobile/ajax/shua_xh');
		}else{
			sleep(3);
			vsock(WEB_PATH.'/mobile/ajax/shua_xh');
		}
	}
	public function shua_xh(){
			//调用一次远程执行
          vsock(WEB_PATH.'/mobile/ajax/autoMaticAnnouncement'); 
	
	}
	public function autoMA_start(){
		$rs = $this->autoMaticAnnouncement();
		echo "设置开启成功！";

	}

    /**
     * 登陆日志
     * @param $uid
     * @return bool
     */
    private function login_log($uid){
        if(empty($uid)) return false;
        $userinfo  = $this->db->GetOne("SELECT uid from `@#_member` WHERE uid = '{$uid}'");
        if(!$userinfo) return false;
        $delnum = 10;
        $limit = $delnum+1;
        $count = $this->db->GetList("SELECT id FROM `@#_member_login_log` WHERE user_id = '{$uid}' and type = '1' ORDER BY `id` DESC LIMIT $limit");
        if(count($count) >$delnum){
            $key = $delnum-1;
            $delid = $count[$key]['id'];
            $this->db->Query("DELETE FROM `@#_member_login_log` WHERE `id`< {$delid} and `user_id` = '{$uid}'");
        }
	    $ip = _get_ip();
//        $url = file_get_contents("http://ip.taobao.com/outGetIpInfo?ip=$ip&accessKey=alibaba-inc");
//        $ipdata = json_decode($url,true);
//
//        if($ipdata && $ipdata['code'] ==0){
//            $data = $ipdata['data'];
//            $i['ip']        = $ip?:'未知IP';
//            $i['country']   = $data['country']?:'';
//            $i['region']    = $data['region']?:'';
//            $i['city']      = $data['city']?:'';
//            $i['isp']       = $data['isp']?:'';
//            $i['login_time']=time();
//            $i['user_id']   =$uid;
//        }else{
//            $i['ip']        = $ip?:'未知IP';
//            $i['country']   = '';
//            $i['region']    = '';
//            $i['city']      = '';
//            $i['isp']       = '';
//            $i['login_time']=time();
//            $i['user_id']   =$uid;
//        }
//        $sql = "INSERT INTO `@#_member_login_log`(`user_id`, `ip`, `login_time`, `country`, `region`, `city`, `isp`) VALUES ({$i['user_id']},'{$i['ip']}','{$i['login_time']}','{$i['country']}','{$i['region']}','{$i['city']}','{$i['isp']}')";

        $key = _cfg('tx_map_key');
        $data = file_get_contents("https://apis.map.qq.com/ws/location/v1/ip?ip=$ip&key=$key");
        $arr = json_decode($data,true);
        if($arr && $arr['code'] ==0){
            $data = $arr['result'];
            $i['ip']        = $data['ip']?:'未知IP';
            $i['country']   = $data['ad_info']['nation']?:'';
            $i['region']    = $data['ad_info']['province']?:'';
            $i['city']      = $data['ad_info']['city']?:'';
            $i['district']  = $data['ad_info']['district']?:'';
            $i['lat']       = $data['location']['lat']?:'';
            $i['lng']       = $data['location']['lng']?:'';
            $i['isp']       = $data['ad_info']['isp']?:'';
            $i['login_time']=time();
            $i['user_id']   =$uid;
        }else{
            $i['ip']        = $ip?:'未知IP';
            $i['country']   = '';
            $i['region']    = '';
            $i['city']      = '';
            $i['isp']       = '';
            $i['district']  = '';
            $i['lat']       = '';
            $i['lng']       = '';
            $i['login_time']=time();
            $i['user_id']   =$uid;
        }
        $sql = "INSERT INTO `@#_member_login_log`(`user_id`, `ip`, `login_time`, `country`, `region`, `city`, `isp`,`district`,`lat`,`lng`) VALUES ({$i['user_id']},'{$i['ip']}','{$i['login_time']}','{$i['country']}','{$i['region']}','{$i['city']}','{$i['isp']}','{$i['district']}','{$i['lat']}','{$i['lng']}')";
        $res = $this->db->Query($sql);
        if($res){
            return true;
        }
        return false;
    }

}
?>