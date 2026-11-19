<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class home extends base {
	public function __construct(){
		parent::__construct();
		if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
			if(!$this->userinfo){
			
				if(!isset($_GET['wxid'])){
					header("Location: ".WEB_PATH."/mobile/user/login");exit;
				}else{
					$wxid =$_GET['wxid'];
					if (empty($wxid)){
						header("Location: ".WEB_PATH."/mobile/user/login");exit;
					}
					$mem=$this->db->GetOne("select * from `@#_member_band` where `b_code`='".$wxid."'");
					if (empty($mem)){
						header("Location: ".WEB_PATH."/mobile/user/login");exit;
					}
					$this->userinfo=$member=$this->db->GetOne("select * from `@#_member` where `uid`='".$mem['b_uid']."'");
					_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
					_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
				}
			}
		}
		$this->db = System::load_sys_class('model');
        $this->symbol =$this->db->GetOne("SELECT * FROM `@#_currency` WHERE status='1'");
	}

	public function init(){
	    $webname=$this->_cfg['web_name'];
		$qqkfewm=$this->_cfg['qqkfewm'];
        $symbol=$this->symbol;
		$member=$this->userinfo;
		$title="用户中心";
		$info=System::load_sys_config('system');
		$user_fufen = System::load_app_config("user_fufen",'','member');
      
		if(!empty($member['headimg'])){
			$member['img'] = $member['headimg'];
		}else{
			$member['img'] = G_UPLOAD_PATH.'/'.$member['img'];
		}
      $web_logo=$this->_cfg['web_logo'];
	 //获取用户等级  用户新手  用户小将==
	  $memberdj=$this->db->GetList("select * from `@#_member_group`");
	  $shaidan=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$member[uid]' order by a.sd_time desc" );
	  $shaidantotal=count($shaidan);
	  if(!$shaidantotal){
	  	$shaidantotal=0;
	  }  
	  $jingyan=$member['jingyan'];
		if(!empty($memberdj)){
		     foreach($memberdj as $key=>$val){
			    if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
			    		   $member['yungoudj']=$val['name'];
			    		   $member['groupid']=$val['groupid'];
				}
			}
		}			
     $fbfx =WEB_PATH."/mobile/mobile/init/"._getcookie('uid');

        $uid=_getcookie('uid');
        /*require_once("system/modules/mobile/jssdk.php");

             $wechat= $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

            $jssdk = new JSSDK($wechat['appid'],$wechat['appsecret']);

            $signPackage = $jssdk->GetSignPackage();*/
        //查询佣金
        $uid=$member['uid'];
        $yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid='$uid'");

        if(!$yongjin_info){
            $this->db->Query("INSERT INTO `@#_member_commission` (`uid`) VALUES ('$uid')");
        }
        $shourutotal = $yongjin_info['shouru_money'];
        $zhichutotal = $yongjin_info['zhichu_money'];
        $total=sprintf("%01.2f",$yongjin_info['total_money']);  //计算佣金余额

        $member['mobile'] = substr_replace($member['mobile'], '****', 3, 4);
        
        
           // 今日充值
    $times = get_today_time();
    $toDayMoney=$this->db->GetOne("SELECT sum(money) as daymoney FROM `@#_member_rechange` WHERE `uid` = '$member[uid]' and content = '充值'  and `time` >= '$times[0]' and `time` <= '$times[1]'")['daymoney'] ?: 0;
    $yjDayMoney=$this->db->GetOne("SELECT sum(money) as daymoney FROM `@#_member_rechange` WHERE `uid` = '$member[uid]' and content = '使用佣金充值到账户'  and `time` >= '$times[0]' and `time` <= '$times[1]'")['daymoney'] ?: 0;
    
    $toDayMoney = $toDayMoney + $yjDayMoney;
	 include templates("mobile/user","index");
}

	public function invite(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="用户中心";
        $uid=_getcookie('uid');
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $wechat= $this->db->GetOne("select * from `@#_wechat_config` where id = 1");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        include templates("mobile/user","invite");
    }	

    //我的收藏
    public function mycollect(){
		$webname="收藏 - ".$this->_cfg['web_name'];
    	$shop=0;
    	$member=$this->userinfo;
    	$uid=$member['uid'];
    	$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` AS a LEFT JOIN `@#_shopcollect` as b ON b.shopid=a.id where b.uid='$uid' order by b.time desc");
    	if($shoplist){
    		$shop=1;
    	}
    	include templates("mobile/user","collect"); 
    }

    public function del_collect(){
    	$shopid =htmlspecialchars($this->segment(4));
    	$info=array();
    	if($shopid){
	       	$member=$this->userinfo;
	    	$uid=$member['uid']; 
	    	$rr=$this->db->Query("DELETE FROM `@#_shopcollect` WHERE shopid='$shopid' AND uid='$uid'");
	    	if($rr){
	    		$rs=$this->db->GetOne("select count(*) as num from `@#_shopcollect` where uid = '$uid'");
	    		if($rs['num'] == 0){
	    			$info['noshop']=0;
	    		}
	    		$info['code']=0;
	    		$info['shopid']=$shopid;
	    	}else{
	    		$info['code']=2;
	    	}		
    	}else{
    		$info['code']=1;
    	}
		echo json_encode($info);
    }

    //我的钱包
    public function purse(){
        $memeber =$this->userinfo;
        //p($memeber);exit;
        //$user=$this->db->GetOne("SELECT * from `@#_member`  where `uid`= $member['uid'] ");

        include templates("mobile/user","purse");
    }

    /** --------------------兼容模板-------------------------------- */
    //转入余额明细
    public function incomedetail(){
        $member =$this->userinfo;
        $user=$member['uid'];
        $zr=$this->db->GetList("SELECT * from `@#_member_rechange`  where `uid`= $user and content = '使用佣金充值到账户' order by time desc");
        //var_dump($zr);exit;

        include templates("mobile/user","incomedetail");
    }

	
    //一键添加收藏商品
	public function addAll(){
		$member=$this->userinfo;
    	$uid=$member['uid'];
    	$cart['code']=1;
    	if($uid){
			  $mycollect_shop=$this->db->GetList("SELECT b.* FROM `@#_shopcollect` AS a LEFT JOIN `@#_shoplist` as b ON a.shopid=b.id where a.uid='$uid' order by a.time desc");
			  $ShopNum=1;
			  $Mcartlist=$this->Mcartlist;
			  foreach ($mycollect_shop as $key => $value) {
			  	if($value['zongrenshu'] == $value['canyurenshu']){
			  		continue;
			  	}else{
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
			  }
		  	 _setcookie('Cartlist',json_encode($Mcartlist),'');
			$cart['code']=0;   //表示添加成功
    	}
	$cart['num']=count($Mcartlist);//表示现在购物车有多少条记录
    $cart["mcar"]=$Mcartlist;
	  echo json_encode($cart);
	} 

	public function inviteshare(){
		$member=$this->userinfo;
		include templates("mobile/user","inviteshare");
	}

	public function account_detail(){
      	$symbol=$this->symbol;
		include templates("mobile/user","account_detail");
	}

    //修改密码
    public function safesetting(){
        include templates("mobile/user","safesetting");
    }

    //VIP功能页面
	public function vip(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="用户中心";
        $uid=_getcookie('uid');
		if (!$member['level']) {
			_messagemobile("当前用户不是VIP",WEB_PATH."/mobile/home");
			}
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $wechat= $this->db->GetOne("select * from `@#_wechat_config` where id = 1");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        include templates("mobile/user","vip");
    }

	//参与记录
	public function userbuylist(){
	   $webname=$this->_cfg['web_name'];
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="参与记录";
		$user_dizhi = $mysql_model->GetOne("select * from `@#_member_dizhi` where `uid` = '$uid'");
		include templates("mobile/user","userbuylist");
	}

	//记录详细
	public function userbuydetail(){
	    $webname=$this->_cfg['web_name'];
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="记录详情";
		$crodid=intval($this->segment(4));
		$record=$mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='$member[uid]' LIMIT 1");		
		if($crodid>0){
			include templates("member","userbuydetail");
		}else{
			echo _messagemobile("页面错误",WEB_PATH."/member/home/userbuylist",3);
		}
	}

	/******************************模板兼容*************************************/
    //购买详情
    public function buydetail(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;

        $itemid=intval($this->segment(4));

        $itemlist=$this->db->GetList("select *,a.time as timego,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$member[uid]' and b.id='$itemid' group by a.id order by a.time" );

        if(empty($itemlist)){
            $itemlist=$this->db->GetList("select *,a.time as timego,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$member[uid]' and b.id='$itemid' group by a.id order by a.time" );
        }

        if(!empty($itemlist)){
            if($itemlist[0]['q_end_time']!=''){
                //商品已揭晓
                $itemlist[0]['codeState']='已揭晓...';
                $itemlist[0]['class']='z-ImgbgC02';
            }elseif($itemlist[0]['shenyurenshu']==0){
                //商品购买次数已满
                $itemlist[0]['codeState']='已满员...';
                $itemlist[0]['class']='z-ImgbgC01';
            }else{
                //进行中
                $itemlist[0]['codeState']='进行中...';
                $itemlist[0]['class']='z-ImgbgC01';

            }
            $bl=($itemlist[0]['canyurenshu']/$itemlist[0]['zongrenshu'])*100;

            foreach ($itemlist as $k => $v) {
                $count += $v['gonumber'];
            }
        }

        include templates("/mobile/user","userbuydetail");
    }

	//获得的商品
	public function orderlist(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="获得的商品";
		$symbol=$this->symbol;
		$web_logo = _cfg('web_logo');
// 		var_export(templates("mobile/user","orderlist"));
		include templates("mobile/user","orderlist");
	}
	
	//账号设置
	public function accountset(){
	    $webname=$this->_cfg['web_name'];
      	$web_logo=$this->_cfg['web_logo'];
        $member=$this->userinfo;
        $member["mobile"]=substr_replace($member["mobile"], '****', 3, 4);;

        $title="账号设置";
		include templates("mobile/user","accountset");
	}

	//账户管理
	public function userbalance(){
      	$symbol=$this->symbol;
	    $webname=$this->_cfg['web_name'];
	    $web_xxczts=System::load_sys_config('system','web_xxczts');
		$member=$this->userinfo;
		$title="账户记录";
		$account=$this->db->GetList("select * from `@#_member_rechange` where `uid`='$member[uid]' and `pay` = '账户' ORDER BY time DESC");
         		$czsum=0;
         		$xfsum=0;
		if(!empty($account)){ 
			foreach($account as $key=>$val){
			    $account[$key]['time'] = date('Y-m-d H:i:s',$val['time']);
			    if($val['message']){
			        $account[$key]['content'] = $val['message'];
			    }
			  if($val['type']==1){
			      $account[$key]['type'] = '充值';
				$czsum+=$val['money'];		  
			  }else{
			      $account[$key]['type'] = '扣款';
			      $account[$key]['money'] = '-'.$val['money'];
				$xfsum+=$val['money'];		  
			  }		
			} 		
		}
		include templates("mobile/user","userbalance");
	}

	public function userrecharge(){
        $symbol=$this->symbol;
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
        $uid = $member['uid'];
		$web_xxczts=System::load_sys_config('system','web_xxczts');
 		$config=System::load_sys_config('system');
		$title="账户充值";
		$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1 order by `sort` DESC");
		$arr = [];
		foreach ($paylist as $k=>$v){
            // $paylist[$k]['pay_monerange'] = @unserialize($v['pay_monerange']);
            $v['pay_monerange'] = @unserialize($v['pay_monerange']);
            if(isset($v['pay_monerange']['shield'])){
                  $shield = explode(",",$v['pay_monerange']['shield']);
                if(in_array($uid,$shield)){
                    
                    continue;
                }
            }
            $arr[] = $v;
        }
        $paylist = $arr;
		$kefu_list= $this->db->GetList("SELECT * FROM `@#_kefu` where 1");
		include templates("mobile/user","recharge");
	}

	public function rechargedata(){
        $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1 order by `sort` DESC");
        $arr = [];
        $member=$this->userinfo;
        $uid = $member['uid'];
        foreach ($paylist as $k=>$v){
            $v['pay_monerange'] = @unserialize($v['pay_monerange']);
            if(isset($v['pay_monerange']['shield'])){
                  $shield = explode(",",$v['pay_monerange']['shield']);
                  $res['sdsfsdfsdf'] = "sdfsdf";
                if(in_array($uid,$shield)){
                    continue;
                }
            }
            $arr[] = $v;
        }
        $res = ['code' =>1,'data'=>[]];
        if(count($arr)<1){
            $res['code']=0;
        }
        $res['data']['paylist'] = $arr;
        $res['data']['currency'] = $this->symbol;
        echo my_json_encode($res);
        exit();
    }

    public function useraddress(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $uid = $member['uid'];
        $t = time();
        if($_POST['submit']){
            extract($_POST);
            if(empty($sheng) || empty($shi) || empty($xian)){
                _messagemobile("地市信息不能为空",WEB_PATH."/mobile/home/address");
            }
            
            // $sheng = safe_replace($sheng);
            // $shi = safe_replace($shi);
            // $xian = safe_replace($xian);
            // $jiedao = safe_replace($jiedao);
            
            $jiedao1 = preg_replace( "@<script(.*?)</script>@is", "", $jiedao );
            $jiedao = $jiedao1;
            if(empty($jiedao)){
                _messagemobile("街道地址包含特殊字符",WEB_PATH."/mobile/home/address");
            }
            if(empty($shouhuoren) || empty($mobile)){
                _messagemobile("收货人 电话 不能为空",WEB_PATH."/mobile/home/address");
            }
            $q1 = $this->db->Query("INSERT INTO `@#_member_dizhi` SET `time` = $t, `uid` = $uid, `sheng` = '$sheng', `shi` = '$shi', `xian` = '$xian', `jiedao` = '$jiedao', `shouhuoren`= '$shouhuoren', `mobile`= '$mobile'");
            if($q1){
                _messagemobile("地址添加成功",WEB_PATH."/mobile/home/address");
            }else{
                _messagemobile("地址添加失败",WEB_PATH."/mobile/home/address");
            }
        }else{
            _messagemobile("添加失败",WEB_PATH."/mobile/home/address");
        }
    }

    public function orderuseraddress(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $uid = $member['uid'];
        $t = time();
        $recordid=intval($this->segment(4));
        if($_POST['submit']){
            extract($_POST);
            if(empty($sheng) || empty($shi) || empty($xian)){
                _messagemobile("地市信息不能为空",WEB_PATH."/mobile/home/orderaddress/$recordid");
            }
            
            //   $sheng = safe_replace($sheng);
            // $shi = safe_replace($shi);
            // $xian = safe_replace($xian);
            // $jiedao = safe_replace($jiedao);
            
            $jiedao1 = preg_replace( "@<script(.*?)</script>@is", "", $jiedao );
            $jiedao = $jiedao1;
            if(empty($jiedao)){
                _messagemobile("街道地址包含特殊字符",WEB_PATH."/mobile/home/orderaddress/$recordid");
            }
            if(empty($shouhuoren) || empty($mobile)){
                _messagemobile("收货人 电话 不能为空",WEB_PATH."/mobile/home/orderaddress/$recordid");
            }
            $q1 = $this->db->Query("INSERT INTO `@#_member_dizhi` SET `time` = $t, `uid` = $uid, `sheng` = '$sheng', `shi` = '$shi', `xian` = '$xian', `jiedao` = '$jiedao', `shouhuoren`= '$shouhuoren', `mobile`= '$mobile'");
            if($q1){
                _messagemobile("地址添加成功",WEB_PATH."/mobile/home/orderaddress/$recordid");
            }else{
                _messagemobile("地址添加失败",WEB_PATH."/mobile/home/orderaddress/$recordid");
            }
        }else{
            _messagemobile("添加失败",WEB_PATH."/mobile/home/orderaddress/$recordid");
        }
    }

	public function address(){
		$webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$uid = $member['uid'];
		$orderid=$this->segment(4);
		$ordercode=$this->segment(5);
		$dizhi = $this->db->GetList("SELECT * FROM `@#_member_dizhi` where  `uid` = $uid");
		include templates("mobile/user","address");
	}
    //设置订单的收货地址
    public function orderaddress(){
        $recordid=intval($this->segment(4));
        $shaidan=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$recordid'");
        $isconfirm=$shaidan['confirm_address'];
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $uid = $member['uid'];
        $orderid=json_decode($shaidan["address"],true)['id'];
        $dizhi = $this->db->GetList("SELECT * FROM `@#_member_dizhi` where  `uid` = $uid");
        include templates("mobile/user","orderaddress");
    }

     public function orderlogistics(){
        $orderid=$this->segment(4);
        //$logisticscode=safe_replace($this->segment(5)); 
        $orderinfo=$this->db->GetOne("select code from `@#_member_go_record` where id='$orderid'");
        $ordercode=$orderinfo['code'];
        $record=$this->db->GetOne("select * from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.id='$orderid' order by a.id desc" );
        $addrinfo=$this->db->GetOne("select * from `@#_member_bind_phone` where phone='{$record['address']}'");
        $record['time']=date("Y-m-d H:i:s",$record['q_end_time']);
        $address=$this->db->Getone("SELECT b.* FROM  `@#_member_orderaddress_brand` AS a left join `@#_member_dizhi` AS b on b.id=a.address_id where a.order_code='$ordercode'");
        // $ops=array('http'=>array(
        // 		'method'=>'GET',
        // 		'header'=>'User-Agent:Mozilla/5.0\n',
        // ));
        // $context =stream_context_create($ops);
        // $company= file_get_contents('http://www.kuaidi100.com/autonumber/autoComNum?text='.$logisticscode);
        // $company=json_decode($company,true)['auto'][0]['comCode'];
        // $logistics=file_get_contents('http://www.kuaidi100.com/query?type='.$company.'&postid='.$logisticscode,false,$context);
        // $logistics=json_decode($logistics,true);
        // if($logistics['message']=='ok')
        // {
        //     foreach ($logistics['data'] as $k=>$i) {
        //         $i["t1"]=explode(" ",$i['time'])[0];
        //         $i["t2"]=explode(" ",$i['time'])[1];
        //         $logistics['data'][$k]=$i;
        //     }
        // }
        $temp['context']="您的订单以及配送信息已确认，等待商城发货";
        $temp['location']="";
        $temp['t1']=date('y-m-d',time());
        $temp['t2']=date('h:i:s',time());
        include templates("mobile/user","orderlogistics");
    }

	public function updateddress(){
		$id=intval($this->segment(4));
		$t = time();
		if($id){
			extract($_POST);
			if(empty($sheng) || empty($shi) || empty($xian)){
				_messagemobile("地市信息不能为空",WEB_PATH."/mobile/home/address");
			}
			
			 // $sheng = safe_replace($sheng);
    //         $shi = safe_replace($shi);
    //         $xian = safe_replace($xian);
    //         $jiedao = safe_replace($jiedao);
			
			$jiedao1 = preg_replace( "@<script(.*?)</script>@is", "", $jiedao );
			$jiedao = $jiedao1;
			if(empty($jiedao)){
				_messagemobile("街道地址包含特殊字符",WEB_PATH."/mobile/home/address");
			}
			if(empty($shouhuoren) || empty($mobile)){
				_messagemobile(" 收货人 电话 不能为空",WEB_PATH."/mobile/home/address");
			}
			$q1 = $this->db->Query("UPDATE `@#_member_dizhi` SET `time` = $t, `sheng` = '$sheng', `shi` = '$shi', `xian` = '$xian', `jiedao` = '$jiedao', `shouhuoren`= '$shouhuoren', `mobile`= '$mobile' WHERE `id`= $id");
			if($q1){
				_messagemobile("地址修改成功",WEB_PATH."/mobile/home/address");
			}else{
				_messagemobile("地址修改失败",WEB_PATH."/mobile/home/address");
			}
		}else{
			_messagemobile("修改失败",WEB_PATH."/mobile/home/address");
		}
	}

	public function deladdress(){
		$id=intval($this->segment(4));
		if($id){
			$q1 = $this->db->Query("DELETE FROM `@#_member_dizhi`  WHERE `id`= $id");
			if($q1){
				_messagemobile("删除成功",WEB_PATH."/mobile/home/address");
			}else{
				_messagemobile("删除失败",WEB_PATH."/mobile/home/address");
			}
		}else{
			_messagemobile("删除失败",WEB_PATH."/mobile/home/address");
		}
	}

	//设为默认
	public function setaddress(){
		$id=intval($this->segment(4));
		if($id){
			$q1 = $this->db->Query("UPDATE `@#_member_dizhi` SET `default` = 'Y' WHERE `id`= $id");
			$q2 = $this->db->Query("UPDATE `@#_member_dizhi` SET `default` = 'N' WHERE `id` != $id");
			if($q1 && $q2){
				_messagemobile("设置成功",WEB_PATH."/mobile/home/address");
			}else{
				_messagemobile("设置失败",WEB_PATH."/mobile/home/address");
			}
		}else{
			_messagemobile("设置失败",WEB_PATH."/mobile/home/address");
		}
	}

	//订单地址设置
	public function orderaddress_set(){
		$orderid=intval($this->segment(4));
		$address_id=intval($this->segment(5));
		$code=$this->segment(6);
		$member=$this->userinfo;
		if($orderid && $address_id){
			$time=time();
			$info=$this->db->GetOne("SELECT mobile from `@#_member_dizhi` where id=$address_id");
			$phone=$info['mobile'];
			$q1 = $this->db->Query("INSERT INTO `@#_member_orderaddress_brand` (`orderid`,`order_code`,`address_id`,`uid`,`time`) VALUES ('$orderid','$code','$address_id','$member[uid]','$time')");
			$q2 =$this->db->Query("UPDATE `@#_member_go_record` SET address=$phone WHERE id=$orderid");
			if($q1 && $q2){
				_messagemobile("设置地址成功",WEB_PATH."/mobile/home/orderlogistics/".$orderid);
			}else{
				_messagemobile("设置地址失敗",WEB_PATH."/mobile/home/address");
			}
		}else{
			_messagemobile("设置地址失敗",WEB_PATH."/mobile/home/address");
		}
	}
	
  	public function userqiandao(){

		$webname=$this->_cfg['web_name'];

		$member=$this->userinfo;
        if(!$this->userinfo){
			header("Location: ".WEB_PATH."/mobile/user/login");exit;
        }
		$uid = $member['uid'];

		$qiandao = $this->db->GetOne("SELECT * FROM `@#_qiandao` where  `uid` = $uid");

		include templates("mobile/user","userqiandao");

	}
    
	public function qiandao(){

		$member=$this->userinfo;
        if(!$this->userinfo){
			header("Location: ".WEB_PATH."/mobile/user/login");exit;
        }
		$uid = $member['uid'];

		$t = time();

		$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));

		$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));

		//查询上次签到时间信息

		$qiandao = $this->db->GetOne("SELECT * FROM `@#_qiandao` where  `uid` = $uid");

		if(empty($qiandao)){

			$this->db->Query("INSERT INTO `@#_qiandao` SET `time` = $t, `uid` = $uid,`sum` = 1, `lianxu` = 1");

			//签到送100福分，同时送1元钱

			$this->db->Query("UPDATE `@#_member` SET `score` = `score`+200 WHERE `uid` = $uid");

			_messagemobile("签到成功，系统会赠送您200积分！",WEB_PATH."/mobile/home/userqiandao");

		}

		if($qiandao['time']>0){

			if($qiandao['time']>$start && $qiandao['time']<$end){

				_messagemobile("今天已经签到过了",WEB_PATH."/mobile/home/userqiandao");

			}else{

				$this->db->Query("UPDATE `@#_qiandao` SET `time` = $t, `uid` =$uid, `sum` =`sum`+1  where uid=$uid");

				$this->db->Query("UPDATE `@#_member` SET `score` = `score`+200 WHERE `uid` = $uid");

				//判断是否连续

				if($t - $qiandao['time']>2 && $t - $qiandao['time']<172798 &&  $qiandao['time']>($start-86400)){

					$this->db->Query("UPDATE `@#_qiandao` SET `lianxu`  =`lianxu` +1 where `uid` = $uid");

				}else{

					$this->db->Query("UPDATE `@#_qiandao` SET `lianxu` = 1 where `uid`= $uid");

				}
				_messagemobile("签到成功，坚持签到有积分赠送的哦！同时积分还可以兑换现金哦",WEB_PATH."/mobile/home/userqiandao");
			}
		}else{
			_messagemobile("签到错误",WEB_PATH."/mobile/home/userqiandao");
		}
	}
	
    //设为默认
    public function setorderaddress(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="获得的商品";
        $id=intval($this->segment(4));
        $record=intval($this->segment(5));
        $dizhi=$this->db->GetOne("SELECT * FROM @#_member_dizhi where  `id`= $id");
        $address=json_encode($dizhi);
        $address=str_replace("\\","\\\\",$address);
        $q1 = $this->db->Query("UPDATE `@#_member_go_record` SET `address`='$address',`order_phone`='".$dizhi['mobile']."',`confirm_address`='1' WHERE `id`= $record");
        if($q1){
            _messagemobile("设置成功",WEB_PATH."/mobile/home/orderlist");
        }else{
            _messagemobile("设置成功",WEB_PATH."/mobile/home/orderlist");
        }
    }

    public function orderupdateddress(){
        $id=intval($this->segment(4));
        $recordid=intval($this->segment(5));
        $t = time();
        if($id){
            extract($_POST);
            if(empty($sheng) || empty($shi) || empty($xian)){
                _messagemobile("地市信息不能为空",WEB_PATH."/mobile/home/orderaddress/"+record);
            }
            
            //   $sheng = safe_replace($sheng);
            // $shi = safe_replace($shi);
            // $xian = safe_replace($xian);
            // $jiedao = safe_replace($jiedao);
            
            $jiedao1 = preg_replace( "@<script(.*?)</script>@is", "", $jiedao );
            $jiedao = $jiedao1;
            if(empty($jiedao)){
                _messagemobile("街道地址包含特殊字符",WEB_PATH."/mobile/home/orderaddress/"+record);
            }
            if(empty($shouhuoren) || empty($mobile)){
                _messagemobile("收货人 电话 不能为空",WEB_PATH."/mobile/home/orderaddress/"+record);
            }
            $q1 = $this->db->Query("UPDATE `@#_member_dizhi` SET `time` = $t, `sheng` = '$sheng', `shi` = '$shi', `xian` = '$xian', `jiedao` = '$jiedao', `shouhuoren`= '$shouhuoren', `mobile`= '$mobile' WHERE `id`= $id");
            if($q1){
                _messagemobile("地址修改成功",WEB_PATH."/mobile/home/orderaddress/"+record);
            }else{
                _messagemobile("地址修改失败",WEB_PATH."/mobile/home/orderaddress/"+record);
            }
        }else{
            _messagemobile("修改失败",WEB_PATH."/mobile/home/address");
        }
    }

    public function orderdeladdress(){
        $id=intval($this->segment(4));
        $recordid=intval($this->segment(5));
        if($id){
            $q1 = $this->db->Query("DELETE FROM `@#_member_dizhi`  WHERE `id`= $id");
            if($q1){
                _messagemobile("删除成功",WEB_PATH."/mobile/home/orderaddress/"+record);
            }else{
                _messagemobile("删除失败",WEB_PATH."/mobile/home/orderaddress/"+record);
            }
        }else{
            _messagemobile("删除失败",WEB_PATH."/mobile/home/orderaddress/"+record);
        }
    }
	
	public function orderchecklist(){
		ini_set("display_errors", "0");
		$member=$this->userinfo;
		$uid = $member['uid'];
		$content=$_POST['content'];
		$page=$content['page'];
		$size=$content['size'];
		$phone=$content['phone'];
		$orderid=$content['orderid'];
		$starttime=$content['starttime'];
		$endtime=$content['endtime'];
		$sql="SELECT *,a.time buytime from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where address!='' ";
		if(!empty($phone)){
			if($phone=="全部"){
				$sql2="SELECT phone from `@#_member_bind_phone` WHERE uid='$uid'";
				$phonelist=$this->db->GetList($sql2);
				if(count($phonelist)==0){
					$res=array(
						'status'=>'ok',
						'data'=>array(),
					);
					echo json_encode($res);
					return;
				}
				$sql.=" and (";
				foreach ($phonelist as $key => $value) {
					$sql.=" LOCATE('".$value['phone']."', address)>0 or";
				}
				$sql=substr($sql,0,-2).")";
			}else{
				$sql.=" and LOCATE('".$phone."', address)>0";
			}
		}
		if(!empty($orderid)){
			$sql.=" and LOCATE('".$orderid."', code)>0";
		}
		if(!empty($starttime)&&!empty($endtime)){
			$stime=strtotime($starttime);
			$etime=strtotime($endtime);
			$sql.=" and a.time>=$stime and a.time <= $etime";
		}elseif(!empty($starttime)){
			$stime=strtotime($starttime);
			$sql.=" and a.time>=$stime";
		}elseif(!empty($endtime)){
			$etime=strtotime($endtime);
			$sql.=" and a.time<=$etime";
		}
		$sql.=" order by a.id desc";
		$record=$this->db->GetList($sql);
		foreach ($record as $k => $v) {
			$record[$k]['address']=json_decode($v['address'],true);
			if($record[$k]['company']){
				$record[$k]['order_status']="已发货";
			}else{
				$record[$k]['order_status']="未发货";
			}
			if($v['username']){
				$record[$k]['u_mobile']=$v['username'];
			}else{
				$userinfo=unserialize($v['q_user']);
				$record[$k]['u_mobile']=$userinfo['mobile'];
			}
			$record[$k]['thumb']=G_UPLOAD_PATH.'/'.$v['thumb'];
			$record[$k]['times']=date('Y-m-d H:i:s',$v['buytime']);
		}
		$res=array(
			'status'=>'ok',
			'data'=>$record,
			'num'=>count($record)
		);
		echo json_encode($res);
    }
	
	public function ordercheck(){
		$member=$this->userinfo;
		if(!$member['level']){
			_messagemobile("当前用户不是VIP",WEB_PATH."/mobile/home");
		}
		$uid = $member['uid'];
		if(isset($_POST['dosubmit'])){
			$content=$_POST;
			$page=$content['page'];
			$size=$content['size'];
			$phone=$content['phone'];
			$orderid=$content['orderid'];
			$starttime=$content['starttime']; 
			$endtime=$content['endtime'];			
		}
		if(!$phone)$phone='全部';
		$sql="SELECT *,a.time buytime from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where address!='' ";
		if(!empty($phone)){
			if($phone=="全部"){
				$sql2="SELECT phone from `@#_member_bind_phone` WHERE uid='$uid'";
				$phonelist=$this->db->GetList($sql2);
				if(count($phonelist)==0){

				}else{
					$sql.=" and (";
					foreach ($phonelist as $key => $value) {
						$sql.=" LOCATE('".$value['phone']."', address)>0 or";
					}				
				}
				$sql=substr($sql,0,-2).")";
			}else{
				$sql.=" and LOCATE('".$phone."', address)>0";
			}
		}
		if(!empty($orderid)){
			$sql.=" and LOCATE('".$orderid."', code)>0";
		}
		if(!empty($starttime)&&!empty($endtime)){
			$stime=strtotime($starttime);
			$etime=strtotime($endtime);
			$sql.=" and a.time>=$stime and a.time <= $etime";
		}elseif(!empty($starttime)){
			$stime=strtotime($starttime);
			$sql.=" and a.time>=$stime";
		}elseif(!empty($endtime)){
			$etime=strtotime($endtime);
			$sql.=" and a.time<=$etime";
		}
		$sql.=" order by a.id desc";
		$record=$this->db->GetList($sql);
		foreach ($record as $k => $v) {
			$record[$k]['address']=json_decode($v['address'],true);
			if($record[$k]['company']){
				$record[$k]['order_status']="已发货";
			}else{
				$record[$k]['order_status']="未发货";
			}
			if($v['username']){
				$record[$k]['u_mobile']=$v['username'];
			}else{
				$userinfo=unserialize($v['q_user']);
				$record[$k]['u_mobile']=$userinfo['mobile'];
			}
			$record[$k]['thumb']=G_UPLOAD_PATH.'/'.$v['thumb'];
			$record[$k]['times']=date('Y-m-d H:i:s',$v['buytime']);
		}
		if($record){
			$total=count($record);
		}else{
			$total=0;
		}
		
		include templates("mobile/user","ordercheck");
	}
	
	public function bindphone(){
		$member=$this->userinfo;
		if(!$member['level']){
			_messagemobile("你不是VIP用户",WEB_PATH."/mobile/home");
		}
		include templates("mobile/user","bindphone");
	}
	
	public function sendcode(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$ok=send_mobile_bind_code($_POST['phone_num']);
		if($ok==100){
			$res=array(
				'status'=>'error',
				'data'=>"手机号已被绑定",
			);
			echo json_encode($res);
		}
		if($ok[0]!=1){
			$res=array(
				'status'=>'error',
				'data'=>"发送失败,失败状态:".$ok[1],
			);
		}
		else{
			$res=array(
				'status'=>'ok',
				'data'=>"发送成功",
			);
			_setcookie("mobilecheck",base64_encode($mobile));
		}		
		echo json_encode($res);
	}
	
	public function getbindphonelist(){
		$webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$uid = $member['uid'];
		$list=$this->db->GetList("SELECT * FROM `@#_member_bind_phone` where uid ='".$uid."'");
		$data=array(
			'status'=>'ok',
			'data'=>$list,
		);
		echo json_encode($data);
	}
	
	public function addbindphone(){
		ini_set("display_errors", "0");
		$member=$this->userinfo;
		$uid = $member['uid'];
		$content=$_POST['content'];
		$phone_num=$content['phone_num'];
		$code=$content['code'];
		$template = $this->db->GetList("select * from `@#_member_bind_phone` where `phone` = '".$phone_num."'");
		if(!$member['level']){
			$res=array(
				'status'=>'error',
				'data'=>"你不是VIP用户"
			);
			echo json_encode($res);
			return;
		}
		//号码已被绑定
		if(count($template)>0){
			$res=array(
				'status'=>'error',
				'data'=>"该手机已被绑定"
			);
			echo json_encode($res);
			return;
		}
		$this->db->Autocommit_start();
		$member2=$this->db->GetOne("SELECT code from `@#_member_bind_code_record` where `phone`='$phone_num'");
		$usercode=explode("|",$member2['code']);
		if($code!=$usercode[0]){
			$res=array(
				'status'=>'error',
				'data'=>"验证码输入不正确",
			);
			echo json_encode($res);
			return;
		}
		$res1=$this->db->Query("UPDATE `@#_member_bind_code_record` SET `code`=null WHERE `phone`='$phone_num'");	
		$res2=$this->db->Query("INSERT into `@#_member_bind_phone` (phone,uid)value('$phone_num','$uid');");
		if($res1 && $res2){
			$this->db->Autocommit_commit();
			$res=array(
				'status'=>'ok',
				'data'=>"绑定成功",
			);
		}else{
			$this->db->Autocommit_rollback();
			$res=array(
				'status'=>'error',
				'data'=>"绑定失败",
			);
		}
		echo json_encode($res);
	}
	
	//晒单
	public function singlelist(){
		 $webname=$this->_cfg['web_name'];
		include templates("mobile/user","singlelist");
	}	
	//添加晒单
	public function postsinglebk(){
		$member=$this->userinfo;
		$uid=_getcookie('uid');
		$ushell=_getcookie('ushell');
		$title="添加晒单";	
     	
     	
		if(isset($_POST['submit'])){
			if($_POST['title']==null) _messagemobile("标题不能为空");	
			if($_POST['content']==null) _messagemobile("内容不能为空");	
			if(empty($_POST['file_up'])){
				_messagemobile("图片不能为空");
			}
			$pic=$_POST['file_up'];
			$pics = explode(';',$pic);
			$src=trim($pics[0]);
			$size=getimagesize(G_UPLOAD_PATH."/".$src);
			$width=220;
			$height=$size[1]*($width/$size[0]);
			$thumbs=tubimg($src,$width,$height);				
			$uid=$this->userinfo;
			$sd_userid=$uid['uid'];
			$sd_shopid=$_POST['shopid'];
			$sd_title=$_POST['title'];
			$sd_thumbs="shaidan/".$thumbs;
			$sd_content=$_POST['content'];
			$sd_photolist=$pic;
			$sd_time=time();			
			$sd_ip = _get_ip_dizhi();						
			$qishu= $this->db->GetOne("select `qishu`, `id` from `@#_shoplist` where `id`='$sd_shopid'");
			$qs = $qishu['qishu'];
			$ids = $qishu['id']; 
			$this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_shopid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`)VALUES ('$sd_userid','$ids','$qs','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')");
			_messagemobile("晒单分享成功",WEB_PATH."/mobile/home/singlelist");
		}
		$recordid=intval($this->segment(4));
      
		if($recordid>0){
			$shaidan=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$recordid'");	
			$shopid=$shaidan['id'];
			include templates("mobile/user","postsingle");
		}else{
			_messagemobile("页面错误");
		}
	}

	public function postsingle(){
		$member=$this->userinfo;
		$uid=$member['uid'];
		$title="添加晒单";
		$recordid=intval($this->segment(4));
		$shaidan=$this->db->GetOne("select * from `@#_member_go_record` where `shopid`='$recordid' and `uid` = '$member[uid]'");
		if(!$shaidan){
			_messagemobile("该商品您不可晒单!");
		}
		$ginfo=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$shaidan[shopid]' LIMIT 1");
		if(!$ginfo){
			_messagemobile("该商品已不存在!");
		}
		$shaidanyn=$this->db->GetOne("select sd_id from `@#_shaidan` where `sd_shopid`='{$ginfo['id']}' and `sd_userid` = '$member[uid]'");
		if($shaidanyn){
			_messagemobile("不可重复晒单!");
		}
		if($_POST){
			if($_POST['title']==null)_messagemobile("标题不能为空");
			if($_POST['content']==null)_messagemobile("内容不能为空");
			if(0==$_FILES['files']['size'][0]){
				_messagemobile("图片不能为空");
			}
			$size=System::load_sys_config('upload','upsize');
			$num=count($_FILES['files']['name']);
			if($num > 5)_messagemobile("上传图片不能超过5张");
			$pic="";
			$ip=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'];
			for($i=0;$i<$num;$i++){	
				$type=array('png','jpg','jpeg');
				$filetype=explode('.',$_FILES['files']['name'][$i]);		
				$filetype=strtolower(array_pop($filetype));
				if(!in_array($filetype,$type)){_messagemobile("文件类型不正确");exit;	}
				if($_FILES['files']['size'][$i] > $size){_messagemobile("文件大小超过了允许上传大小");exit;	}

				$toUrl = $ip.'/Img.php';	
				$data = array(
					'path' => 'ShaiDan',
					'file'  => curl_file_create($_FILES['files']['tmp_name'][$i], $_FILES['files']['type'][$i], $_FILES['files']['name'][$i]),
				);
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $toUrl );
				curl_setopt ( $ch, CURLOPT_POST, 1 );
				curl_setopt ( $ch, CURLOPT_HEADER, 0 );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);
				$return = curl_exec ( $ch );
				curl_close ( $ch );
				$info=json_decode($return,1);
				$pic.=trim($info['imgurl']).";";
			}
			$imgarr=explode(';',$pic);
			$src=trim($imgarr[0]);
			$sd_userid=$uid;
			$sd_shopid=intval($ginfo['id']);
			$sd_title=$_POST['title'];
			$sd_shoptitle=$shaidan['shopname'];
			$sd_qishu=$shaidan['shopqishu'];
			$sd_shopsid=$ginfo['sid'];
			$sd_thumbs=$src;
			$sd_content=$_POST['content'];
			$sd_photolist=$pic;
			$sd_time=time();
			$this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_shopid`,`sd_title`,`sd_shoptitle`,`sd_qishu`,`sd_shopsid`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`)VALUES
			('$sd_userid','$sd_shopid','$sd_title','$sd_shoptitle','$sd_qishu','$sd_shopsid','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')");
			header("Location:".WEB_PATH."/mobile/home/singlelist");
		}

		if($recordid>0){
			$shaidan=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$recordid'");
			$shopid=$shaidan['shopid'];
			include templates("mobile/user","postsingle");
		}else{
			_messagemobile("页面错误");
		}
	}
	
	// 晒单上传图片方法
	public function mupload(){
		$uploadDir =$_SERVER['DOCUMENT_ROOT'].'/statics/uploads/shaidan/'.date('Ymd',time()).'/';
		if(!is_dir($uploadDir)){
		 	mkdir($uploadDir,0777);				
		}
		$rand=rand(10,99).substr(microtime(),2,6).substr(time(),4,6);
		$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); 
		if (!empty($_FILES)) {
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			$filetype = strtolower($fileParts['extension']);
			$tempFile   = $_FILES['Filedata']['tmp_name'];
			$targetFilename = $rand.'.'.$filetype;
			if (in_array($filetype, $fileTypes)) {
				move_uploaded_file($tempFile, $uploadDir.$targetFilename);
				echo 'shaidan/'.date('Ymd',time()).'/'.$targetFilename;
			} else {
				echo 'Invalid file type.';
			}
		}
	}
	
	//检查图片存在否（防路径穿越：仅允许当前目录下的安全文件名）
	public function check_exists(){
		$raw = isset($_POST['filename']) ? (string)$_POST['filename'] : '';
		$base = basename($raw);
		if ($base === '' || preg_match('/[^a-zA-Z0-9._-]/', $base)) {
			echo 0;
			return;
		}
		$dir = $_SERVER['DOCUMENT_ROOT'].'/statics/uploads/shaidan/'.date('Ymd',time()).'/';
		$fileurl = realpath($dir.$base);
		if ($fileurl !== false && strpos($fileurl, realpath($dir)) === 0 && is_file($fileurl)) {
			echo 1;
		} else {
			echo 0;
		}
	}
	
	public function file_upload(){
		ini_set('display_errors', 0);
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."UploadHandler.php";
		$upload_handler = new UploadHandler();
	}
	
	public function singoldimg(){
		if($_POST['action']=='del'){
			$sd_id=safe_replace($_POST['sd_id']);
			$oldimg=safe_replace($_POST['oldimg']);
			$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
			$sd_photolist=str_replace($oldimg.";","",$shaidan['sd_photolist']);
			$this->db->Query("UPDATE `@#_shaidan` SET sd_photolist='".$sd_photolist."' where sd_id='".$sd_id."'");
		}
	}
	
	public function singphotoup(){
		$mysql_model=System::load_sys_class('model');
		if(!empty($_FILES)){
			$uid=isset($_POST['uid']) ? $_POST['uid'] : NULL;
			$ushell=isset($_POST['ushell']) ? safe_replace($_POST['ushell']) : NULL;
			$login=$this->checkuser($uid,$ushell);
			if(!$login){_messagemobile("上传出错");}
			System::load_sys_class('upload','sys','no');
			upload::upload_config(array('png','jpg','jpeg','gif'),1000000,'shaidan');
			upload::go_upload($_FILES['Filedata']);
			if(!upload::$ok){
				echo _messagemobile(upload::$error,null,3);
			}else{
				$img=upload::$filedir."/".upload::$filename;
				$size=getimagesize(G_UPLOAD_PATH."/shaidan/".$img);
				$max=700;$w=$size[0];$h=$size[1];
				if($w>700){
					$w2=$max;
					$h2=$h*($max/$w);
					upload::thumbs($w2,$h2,1);
				}
				echo trim("shaidan/".$img);
			}
		}
	}
	
	public function singdel(){
		$action=isset($_GET['action']) ? safe_replace($_GET['action']) : null;
		$raw = isset($_GET['filename']) ? (string)$_GET['filename'] : '';
		$base = basename($raw);
		if($action=='del' && $base !== '' && preg_match('/^[a-zA-Z0-9._-]+$/', $base)){
			$dir = realpath(G_UPLOAD_PATH.'shaidan/');
			if ($dir === false) { return; }
			$filename = $dir.DIRECTORY_SEPARATOR.$base;
			if (strpos(realpath($filename), $dir) !== 0 || !is_file($filename)) { return; }
			$size = @getimagesize($filename);
			if (!$size || (isset($size['mime']) && strpos($size['mime'], 'image/') !== 0)){
				return;
			}
			unlink($filename);
			exit;
		}
	}
	
	//晒单删除
	public function shaidandel(){
		_messagemobile("不可以删除!");
		$member=$this->userinfo;
		$id=$this->segment(4);
		$id=intval($id);
		$shaidan=$this->db->Getone("select * from `@#_shaidan` where `sd_userid`='$member[uid]' and `sd_id`='$id'");
		if($shaidan){
			$this->db->Query("DELETE FROM `@#_shaidan` WHERE `sd_userid`='$member[uid]' and `sd_id`='$id'");
			_messagemobile("删除成功",WEB_PATH."/mobile/home/singlelist");
		}else{
			_messagemobile("删除失败",WEB_PATH."/mobile/home/singlelist");
		}
	}
}