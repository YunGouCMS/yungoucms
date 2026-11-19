<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class shopajax extends base {
private $Mcartlist;
public function __construct(){
	parent::__construct();
	$this->db = System::load_sys_class('model');
	//查询购物车的信息
	$Mcartlist=_getcookie("Cartlist");
	$this->Mcartlist=json_decode(stripslashes($Mcartlist),true);
	}

	//个人中心首页
	public function init(){
	     $pagetime=safe_replace($this->segment(4));
	    if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
			if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);
		}
		$member=$this->userinfo;
		$title="我的云购中心";
		$user['code']=1;
		$user['username']=get_user_name($member['uid']);
		$user['uid']=$member['uid'];
		if(!empty($member)){
			$user['code']=0;
		}
		echo json_encode($user);
	}
	
	//一键包尾
	public function addAllCartlist() {
		$webname = "购物车 - ".$this->_cfg ['web_name'];
		$Mcartlist=_getcookie("Cartlist");
		$Mcartlist = json_decode ( stripslashes ( $Mcartlist ), true );
		$shopids = '';
		if (is_array ( $Mcartlist )) {
			foreach ( $Mcartlist as $key => $val ) {
				$shopids .= intval ( $key ) . ',';
			}
			$shopids = str_replace ( ',0', '', $shopids );
			$shopids = trim ( $shopids, ',' );
		  }
		  if ($shopids != NULL) {
		  $shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );
		}
		   foreach ($shoparr as $key => $value) {
		   	if($value['shenyurenshu'] == '0'){
		   		unset($Mcartlist[$key]);
		   		unset($shoparr[$key]);
		   	}
		   }
		  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );  
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
					  $Mcartlist [$val ['id']] ['num'] =  $val ['shenyurenshu'];
					  $Mcartlist [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
					  $Mcartlist [$val ['id']] ['money'] = $val ['yunjiage'];
					  $Mcartlist [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
			  } 
			  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );
		  }
		$info['err']='true';
		echo json_encode($info);
	  }
	  
	//我的云购记录
	public function getUserBuyList(){
	   $member=$this->userinfo;
	   $FIdx=safe_replace($this->segment(4));
	   $EIdx=10;//safe_replace($this->segment(5));
	   $isCount=safe_replace($this->segment(6));
	   $state=$this->segment(7);
		if($state==1){
		  //参与云购的商品 进行中...
		  $shoplist=$this->db->GetList("select  a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,a.q_uid,a.q_user,b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber from `@#_shoplist` a left join `@#_member_go_record` b on b.shopid=a.id where b.uid='$member[uid]' and a.q_end_time is null GROUP BY shopid order by b.time desc" );
		  $shoplistall['listItems']=$this->db->GetList("select  a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,a.q_uid,a.q_user,b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber from `@#_shoplist` a left join `@#_member_go_record` b on b.shopid=a.id where b.uid='$member[uid]' and a.q_end_time is null GROUP BY shopid order by b.time desc limit  $FIdx,$EIdx " );
        }elseif($state==-1){
         	$shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$member[uid]' and b.q_end_time is null GROUP BY shopid  " );
            $shoplistall['listItems']=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_shoplist` a left join `@#_member_go_record` b on b.shopid=a.id where b.uid='$member[uid]' and a.q_end_time is null GROUP BY shopid order by b.time desc limit  $FIdx,$EIdx " );
			
            $arr1=$this->db->GetList("select  b.id,b.thumb,b.canyurenshu,b.zongrenshu,b.q_end_time,b.q_showtime,b.q_uid,b.q_user,a.shopname,a.shopqishu,a.shopid,a.uid,sum(a.gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$member[uid]' and b.q_end_time is not null and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );
            $arr2=$this->db->GetList("select  a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,a.q_uid,a.q_user,b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber from `@#_shoplist` a left join `@#_member_go_record` b on b.shopid=a.id where b.uid='$member[uid]'  GROUP BY shopid order by b.time desc limit  $FIdx,$EIdx " );

		
        	$shoplistall['listItems']=array_merge($arr2,$arr1);          
        }else{
		  //参与云购的商品 已揭晓...
		  $shoplist=$this->db->GetList("select  b.id,b.thumb,b.canyurenshu,b.zongrenshu,b.q_end_time,b.q_showtime,b.q_uid,b.q_user,a.shopname,a.shopqishu,a.shopid,a.uid,sum(a.gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$member[uid]' and b.q_end_time is not null and b.q_showtime !='Y' GROUP BY shopid limit 100" ); 
		  $shoplistall['listItems']=$this->db->GetList("select  b.id,b.thumb,b.canyurenshu,b.zongrenshu,b.q_end_time,b.q_showtime,b.q_uid,b.q_user,a.shopname,a.shopqishu,a.shopid,a.uid,sum(a.gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$member[uid]' and b.q_end_time is not null and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );
		} 
		if(!empty($shoplistall['listItems'])){
		   $shoplistall['code']=0;
		   foreach($shoplistall['listItems'] as $key=>$val){
			  $shoplistall['listItems'][$key]['q_user']=get_user_name($val['q_uid']);
			  $shoplistall['listItems'][$key]['q_end_time']=microt($val['q_end_time']);
			  $shoplistall['listItems'][$key]['q_end_times']=$val['q_end_time'];
			 if($val['q_end_time']!=''){
			   //商品已揭晓
			    $shoplistall['listItems'][$key]['codeState']=3;
				continue;
			 }elseif($val['shenyurenshu']==0){
			 //商品购买次数已满
			   $shoplistall['listItems'][$key]['codeState']=2;
			    continue;
			 }else{
			 //进行中
			    $shoplistall['listItems'][$key]['codeState']=1;
			 }
		   }
		}else{
		  $shoplistall['code']=1;
		}
        // $shoplistall['count']=$shoplist['countnum'];
        $shoplistall['count']=count($shoplist);
		echo json_encode($shoplistall);
	}

	//获得的商品
	public function getUserOrderList(){
		$FIdx=safe_replace($this->segment(4));
		$EIdx=10;//safe_replace($this->segment(5));
		$proportion=System::load_sys_config("proportion","proportion");//回收比例
		$member=$this->userinfo;
		$order=$this->db->GetOne("select count(*) as num from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where b.q_uid='$member[uid]' and a.huode!=0 and b.q_showtime='N'");
		$orderlist['listItems']=$this->db->GetList("select a.id as aid,a.code,a.address,a.status,a.shopname,a.shopid,a.company_code,b.id as 'bid',b.q_end_time,b.thumb,b.q_user_code,b.money,b.qishu from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where b.q_uid='$member[uid]' and a.huode!=0 and b.q_showtime='N' order by a.time desc limit $FIdx,$EIdx " );
		if(empty($orderlist['listItems'])){
		  $orderlist['code']=1;
		}else{
			$addreinfo=$this->db->GetList("SELECT * FROM  `@#_member_orderaddress_brand` where uid=$member[uid]");
			foreach($orderlist['listItems'] as $key=>$val){
		        $orderlist['listItems'][$key]['q_end_time']=microt($val['q_end_time']);
		       
		        $addrinfo=$this->db->GetOne("select * from `@#_member_bind_phone` where phone='{$val['address']}'");
		        if($addrinfo){
		        	 $orderlist['listItems'][$key]['adr']=1;
		        }else{
		        	$orderlist['listItems'][$key]['adr']=0;
		        }
				if($proportion){
					 $orderlist['listItems'][$key]['proportion']= $proportion;
				}
				foreach ($addreinfo as $k => $v) {
					if(in_array($val['code'],$v)){
						 $orderlist['listItems'][$key]['addreinfo']= $v;
						 break;
					}
				}
		       
		    }
		   $orderlist['code']=0;
		}
		$orderlist['count']=$order['num'];
	  echo json_encode($orderlist);
	}

    /**----------------------------模板兼容------------------------------------**/
    //获得的商品
    public function getUserOrderLists(){
        $FIdx=safe_replace($this->segment(4));
        $EIdx=10;//safe_replace($this->segment(5));
        $proportion=System::load_sys_config("proportion","proportion");//回收比例
        $member=$this->userinfo;
        $order=$this->db->GetList("select *,b.id as 'bid',a.id as aid from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where b.q_uid='$member[uid]' and a.huode!=0 and b.q_showtime='N' order by a.time desc");

        $orderlist['listItems']=$this->db->GetList("select *,b.id as 'bid',a.id as aid from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where b.q_uid='$member[uid]' and a.huode!=0 and b.q_showtime='N' order by a.time desc limit $FIdx,$EIdx " );
        if(empty($orderlist['listItems'])){
            $orderlist['code']=1;
        }else{
            foreach($orderlist['listItems'] as $key=>$val){
                $orderlist['listItems'][$key]['q_end_time']=microt($val['q_end_time']);
                $addreinfo=$this->db->Getone("SELECT a.id FROM  `@#_member_orderaddress_brand` AS a left join `@#_member_go_record` AS b on b.id=a.orderid where b.id=$val[aid]");
                $addrinfo=$this->db->GetOne("select * from `@#_member_bind_phone` where phone='{$val['address']}'");
                if($addrinfo){
                    $orderlist['listItems'][$key]['adr']=1;
                }else{
                    $orderlist['listItems'][$key]['adr']=0;
                }
                if($proportion){
                    $orderlist['listItems'][$key]['proportion']= $proportion;
                }
                $orderlist['listItems'][$key]['addreinfo']= $addreinfo;
            }
            $orderlist['code']=0;

        }
        $orderlist['count']=count($order);
        // echo "<pre>";
        // print_r($orderlist);
        echo json_encode($orderlist);
    }

	
	/*回收商品*/
	public function recover(){ 
		$id=safe_replace($this->segment(4));
		if($id){
           session_start();
		  if (empty ( $_SESSION['submitcodedes'] )) {
				$_SESSION['submitcodedes']=microtime();
		  } else {  
			  unset ( $_SESSION ["submitcodedes"] );
				_messagemobile("不能重复提交");exit; 
		  }
			$proportion=System::load_sys_config("proportion","proportion");//回收比例
			$info=$this->db->GetOne("SELECT a.*,b.money FROM `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.id='$id' and a.status!='已回收'");
			$uid=$info['uid'];
          	$shopid=$info['shopid'];
			$type="-4";
			$pay='账户';
			$money =floor($info['money']*$proportion*0.01);
			$content='兑换商品获得('.$money.")元";
			$time=time();
            $errinfo =$this->db->GetOne("SELECT * FROM `@#_member_recodes` where shopid='$shopid' and type='-4'");
           if(!$info || $errinfo){
             unset ( $_SESSION ["submitcodedes"] );
              _messagemobile("商品已回收");exit;  
            }else{
				$commission_info=$this->db->GetOne("SELECT id FROM `@#_member_commission` where uid='$uid'");
				$this->db->Autocommit_start();
				$update=$this->db->Query("UPDATE `@#_member_go_record` SET `status`='已回收' WHERE id='$id'");
				$insert=$this->db->Query("INSERT INTO `@#_member_recodes`(uid,type,content,shopid,money,time,unqsid) VALUES ('$uid','$type','$content','$shopid','$money','$time','$shopid')");
				if($commission_info){
					$up_usermoney=$this->db->Query("UPDATE `@#_member_commission` SET `total_money`=`total_money`+$money,shouru_money=shouru_money+'$money' WHERE uid='$uid'");
				}else{
					$up_usermoney=$this->db->Query("INSERT INTO `@#_member_commission`(uid,total_money,shouru_money) VALUES ('$uid','$money','$money')"); 
				// 	var_dump("INSERT INTO `@#_member_commission`(uid,total_money,shouru_money) VALUES ('$uid','$money','$money')");exit;
				}
				if($update && $insert && $up_usermoney){   
                  unset ( $_SESSION ["submitcodedes"] );
					$this->db->Autocommit_commit();
					 _messagemobile("兑换商品成功",WEB_PATH."/mobile/home/orderlist");
				}else{
					$this->db->Autocommit_rollback();
                  unset ( $_SESSION ["submitcodedes"] );
					_messagemobile("兑换商品失败");
				}				
			}
		}else{
			_messagemobile("参数错误");
		}
	}
	
	//获取晒单
	public function getUserPostList(){
	   $FIdx=safe_replace($this->segment(4));
	   $EIdx=10;//safe_replace($this->segment(5));
	   $member=$this->userinfo;
	   $post=$this->db->GetList("select  b.id, b.thumb,b.title,b.q_user_code,b.q_end_time, a.sd_title,a.sd_content,a.sd_time from `@#_shaidan` a left join `@#_shoplist_copy` b on a.sd_shopid=b.id where a.sd_userid='$member[uid]' order by a.sd_time desc" );
	   $postlist['listItems']=$this->db->GetList("select b.id, b.thumb,b.title,b.q_user_code,b.q_end_time, a.sd_title,a.sd_content,a.sd_time from `@#_shaidan` a left join `@#_shoplist_copy` b on a.sd_shopid=b.id where a.sd_userid='$member[uid]' order by a.sd_time desc limit $FIdx,$EIdx" );
     	 $arr= $postlist['listItems'];       
		if(empty($postlist['listItems'])){
		  $postlist['code']=1;
		}else{
		  foreach($postlist['listItems'] as $key=>$val){
	        $postlist['listItems'][$key]['sd_time']=date('Y-m-d H:i',$val['sd_time']);
	      }
		   $postlist['code']=0;
		}
		$postlist['postCount']=count($post);
	  //echo "<pre>";
	  //print_r($postlist);
	  echo json_encode($postlist);
	}

	//获取未晒单
	public function getUserUnPostList(){
	   $FIdx=safe_replace($this->segment(4));
	   $EIdx=10;//safe_replace($this->segment(5));
	   $member=$this->userinfo;
	    //获得的商品
	    $orderlist=$this->db->GetList("select b.id, b.thumb,b.title,b.q_user_code,b.q_end_time, a.shopid from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where b.q_uid='$member[uid]' order by a.time desc" );
		//获取晒单
		$postlist=$this->db->GetList("select b.id, b.thumb,b.title,b.q_user_code,b.q_end_time, a.sd_title,a.sd_content,a.sd_time,a.sd_shopid from `@#_shaidan` a left join `@#_shoplist_copy` b on a.sd_shopid=b.id where a.sd_userid='$member[uid]' order by a.sd_time desc" );
		$huoid='';
		$sd_id = $r_id = array();
		foreach($postlist as $sd){
			$sd_id[]=$sd['sd_shopid'];
		}
	
		foreach($orderlist as $rd){
			if(!in_array($rd['shopid'],$sd_id)){
				$r_id[]=$rd['shopid'];
			}
		}
		if(!empty($r_id)){
			$rd_id=implode(",",$r_id);
			$rd_id = trim($rd_id,',');
		}else{
			$rd_id="0";
		}
		//未晒单
	   $unpost=$this->db->GetOne("select count(id) as num from  `@#_shoplist_copy`  where id in($rd_id) order by id" );
	   $unpostlist['listItems']=$this->db->GetList("select id,sid,title,thumb,qishu,q_user_code,q_end_time from  `@#_shoplist_copy`  where id in($rd_id) order by id limit  $FIdx, $EIdx" );
		if(empty($unpostlist['listItems'])){
		   $unpostlist['code']=1;
		}else{
		  foreach($unpostlist['listItems'] as $key=>$val){
	        $unpostlist['listItems'][$key]['q_end_time']=microt($val['q_end_time']);
	      }
		   $unpostlist['code']=0;
		}
	    $unpostlist['unPostCount']=$unpost['num'];
	  echo json_encode($unpostlist);
	}

	//充值记录
	public function getUserRecharge(){
		$member=$this->userinfo;
		$FIdx=safe_replace($this->segment(4));
		$EIdx=15;//safe_replace($this->segment(5));
		$Rechargelist=$this->db->GetList("select * from `@#_member_rechange` where `uid`='$member[uid]'  ORDER BY time DESC");
	    $Recharge['listItems']=$this->db->GetList("select * from `@#_member_rechange` where `uid`='$member[uid]'  ORDER BY time DESC limit $FIdx,$EIdx ");
        if(empty($Recharge['listItems'])){
		    $Recharge['code']=1;
		}else{
		  foreach($Recharge['listItems'] as $key=>$val){
		      if($val['message']){
		          $Recharge['listItems'][$key]['content'] = $val['message'];
              }
		    $Recharge['listItems'][$key]['time']=date("Y-m-d H:i:s",$val['time']);
		  }
		    $Recharge['code']=0;
		}
 		$Recharge['count']=count($Rechargelist);

		echo json_encode($Recharge);
	}

	//消费记录
	public function getUserConsumption(){
	   $member=$this->userinfo;
	   $FIdx=safe_replace($this->segment(4));
	   $EIdx=15;
	   $Consumptionlist=$this->db->GetList("select * from `@#_member_account` where `uid`='$member[uid]' and `pay` = '账户' and `type`='-1' ");
	   $Consumption['listItems']=$this->db->GetList("select * from `@#_member_account` where `uid`='$member[uid]' and `pay` = '账户' and `type`='-1'  ORDER BY time DESC limit $FIdx,$EIdx ");
        	if(empty($Consumption['listItems'])){
		    $Consumption['code']=1;
		}else{
		foreach($Consumption['listItems'] as $key=>$val){
		    $Consumption['listItems'][$key]['time']=date("Y-m-d H:i:s",$val['time']);
		  }
		    $Consumption['code']=0;
		}
 		$Consumption['count']=count($Consumptionlist);

		echo json_encode($Consumption);
	}
	
	    // 推荐商品
    public function getRenqiShop2()
    {
        $list = $this->db->Getlist("SELECT * FROM `@#_shoplist` where `pos`=1 and `q_uid` is null AND ispk !=1 order by `order` desc LIMIT 10");
        if($list){
            foreach($list as $ks=>&$vs){
                // $qishu = empty($vs['qishu']) ? "":"($vs[qishu]".$this->lgs['phase'].")";
                // $vs['title'] = $qishu.$vs['title'];
                $vs['img'] = G_UPLOAD_PATH.'/'.$vs['thumb'];
                //去掉小数点后的.00,不为00的不去除
                if(strstr($list[$ks]['money'],'.')){
                    $moneyArr=explode('.',$list[$ks]['money']);
                    if($moneyArr[1] == '00'){
                        $list[$ks]['money']=$moneyArr[0];
                    }
                }

                $vs['percentage'] = round($vs['canyurenshu'] / $vs['zongrenshu'],2) * 100 ;

            }
        }
        $price = 0;
        if(empty($arr)){
            $arr = [];
        }else{
            foreach($arr as $k=>$v){
                $price += $type == 1?$v['money']*$v['num']:$v['num'];
            }
        }
    
        echo json_encode(["code"=>0,"count"=>$list]);
        // $this->jsonData->successCode('','OK',$list);
    }
}
?>