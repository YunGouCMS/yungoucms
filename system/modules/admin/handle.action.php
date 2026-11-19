<?php   

defined('G_IN_SYSTEM')or exit('no');
ignore_user_abort(TRUE);
set_time_limit(0); 
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class handle extends admin {
	private $db;
	private $info;
	private $errorMsg='';
	private $yh_status = ["1"=>"待支付","2"=>"已付款","3"=>"已发货","4"=>"未收货","5"=>"已完成","6"=>"已作废"];
	private $group_status = ["1"=>"进行中","2"=>"已付款","3"=>"已发货","4"=>"未收货","5"=>"已完成","6"=>"已作废"];
	private $cashout_status = ["0"=>"待审核","1"=>"通过","2"=>"拒绝","3"=>"打款中","4"=>"待手动打款"];
	private $offline_status =  [
		1 => "<font style='color:red'>提交</font>",
		2 => "<font style='color:green'>已到账</font>",
		3 => "未到账",
	];
	private $state = [
		"1"=>["type"=>1,"name"=>"云购发货","table"=>"member_go_record",'key'=>'Cloud_purchase'],
		"2"=>["type"=>2,"name"=>"优惠购发货","table"=>"orders",'key'=>'Discounted_Purchase'],
		"3"=>["type"=>3,"name"=>"拼团购发货","table"=>"orders_group",'key'=>'Group_buying'],
		"21"=>["type"=>21,"name"=>"银行卡充值","table"=>"offline_money","status"=>[2=>"已到账",3=>"未到账"],'key'=>'Bank_card_recharge'],
		"22"=>["type"=>22,"name"=>"USDT充值","table"=>"offline_money","status"=>[2=>"已到账",3=>"未到账"],'key'=>'USDT_recharge'],
		"31"=>["type"=>31,"name"=>"待提现订单","table"=>"member_cashout","status"=>[1=>"通过",2=>"未通过"],'key'=>'Pending_withdrawal_order'],
		"41"=>["type"=>41,"name"=>"线下充值","table"=>"member_rechange","status"=>[1=>"通过",2=>"未通过"],'key'=>'Offline_recharge'],
	];
	public function __construct(){		
		parent::__construct();		
		$this->db=System::load_sys_class('model');		
// 		$this->ment=array(
// 				array("lists","审核列表",ROUTE_M.'/'.ROUTE_C."/lists"),	
// 		);

	}

	public function lists(){
		//查询重复数据
	    $res = $this->db->GetList("select max(id) as id,type,to_id,count(*) as count from `@#_handle` group by type,to_id having count(*)>1 limit 500");
		foreach($res as $k=>$v){
			//删除
			$sql = "delete from `@#_handle` where id = ".$v['id'];
			$this->db->Query($sql);
		}
		$type = isset($_POST['type'])?$_POST['type']:$this->segment(4);
		$this->ment = [
			array("lists",$this->state[$type]['name']."审核",ROUTE_M.'/'.ROUTE_C."/lists",2),	
		];
		$where = '';
		if(!$type){
			$type = 1;
		}
		$num=30;
		$pagenum=1;
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$table = $this->state[$type]['table'];
		$join = $where = "";
		switch($type){
			case "1":
				$handle = "handle_orders.tpl.php";
				$fields = 't.shopname,t.code,t.shopqishu,t.uid,t.username,t.gonumber,t.moneycount,t.time,t.status as t_status,t.huode';
				break;
			case "2":
				$handle = "handle_orders.tpl.php";
				$fields = "t.shopname,t.code,t.uid,t.username,t.gonumber,t.moneycount,t.time,t.status as t_status";
				break;
			case "3":
				$handle = "handle_orders.tpl.php";
				$fields = "t.shopname,t.code,t.uid,t.username,t.gonumber,t.moneycount,t.time,t.status as t_status,t.huode";
				break;
			case "21":
				$handle = "handle_offline.tpl.php";
				$fields = "t.money,t.volume,t.create_time,t.picarr,t.status as t_status,t.uid,m.username";
				$join = "left join `@#_member` m on m.uid = t.uid";
				break;
			case "22":
				$handle = "handle_offline.tpl.php";
				$fields = "t.money,t.volume,t.create_time,t.picarr,t.status as t_status,t.uid,t.exchange_rate,t.real_money,m.username";
				$join = "left join `@#_member` m on m.uid = t.uid";
				break;
			case "31":
				$handle = "handle_cashout.tpl.php";
				$fields = "t.uid,t.username as k_username,t.bankname,t.branch,t.money,t.time,t.banknumber,t.linkphone,t.procefees,m.username,t.auditstatus as t_status,m.yaoqing,m.top_uid";
				$join = "left join `@#_member` m on m.uid = t.uid";
				$fufen = System::load_app_config("user_fufen",'','member');
				$formot_array = ["money","fufen_yongjintx"];
				data_format($fufen,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
				//渠道商
				$channels = $this->db->GetList("select uid,username from `@#_member` where `level` = 2",array("key"=>"uid"));
				//查询
				$chongzhi = $_POST['chongzhi']?:$_GET['chongzhi'];
				if(explode('_',$chongzhi)[0]=='channel'){
                    $yaoqing = explode('_',$chongzhi)[1];
					$where .= " AND m.`top_uid` = $yaoqing ";
                }
				//查询总提现
				$sql = "select sum(t.money) as total from `@#_member_cashout` t left join `@#_member` m on m.uid = t.uid where t.auditstatus = 1 $where";
				$cashot = $this->db->GetOne($sql);
				break;
			case "41":
			case "41,42":
				$handle = "handle_houtai.tpl.php";
				$fields = "t.uid,t.time,t.money,t.status as t_status,m.username";
				$join = "left join `@#_member` m on m.uid = t.uid";
				$table = "member_rechange";
				break;
		}
		if($sel_status = $_POST['sel_status']?:$_GET['sel_status']){
			$where .= " AND h.manage_status = $sel_status ";
		}
		$posttime1 = $_POST['posttime1']?:$_GET['posttime1'];
		$posttime2 = $_POST['posttime2']?:$_GET['posttime2'];
		if($posttime1 && $posttime2 && $posttime1 > $posttime2){
			_messageerror("开始时间不能大于结束时间");
		}
		if($posttime1){
			$posttime1 = strtotime($posttime1);
			$where .= " AND h.operat_time >= ".$posttime1;
		}
		if($posttime2){
			$posttime2 = strtotime($posttime2);
			$where .= " AND h.operat_time <= ".$posttime2;
		}

		$sql = "SELECT h.*,$fields from `@#_handle` h left join `@#_$table` t on t.id = h.to_id $join WHERE h.`type` in ($type) $where order by h.id desc";
		
		$total = $this->db->GetCount("SELECT COUNT(*) from `@#_handle` h left join `@#_$table` t on t.id = h.to_id $join WHERE h.`type` in ($type) $where");
		$page=System::load_sys_class('page');
		$get_data = [
			"type"=>$type,
			"sel_status"=>$sel_status,
			"posttime1"=>$posttime1,
			"posttime2"=>$posttime2,
			"chongzhi"=>$chongzhi,
		];
		$page->config($total,$num,$pagenum,"0",$get_data);
		if($pagenum > $page->page){
			$pagenum = $page->page;
		}
		$list = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $rechangeStartArray = [1=>'待审核',2=>'已审核',3=>'拒绝'];
        foreach ($list?:[] as $key=>$vl){
            $list[$key]['time'] = date("d/m/Y H:i:s",$vl['time']);
            $list[$key]['manage_time'] = date("d/m/Y H:i:s",$vl['manage_time']);

            if($type == '41,42' || $type == '41'){
                $list[$key]['t_status_cn'] = $rechangeStartArray[$vl['t_status']];
            }
        }
		$languages = $this->getLanguages('home',1);
		$state = [];
		foreach ($this->state as $key=>$vl){
			$state[$vl['type']] = $languages[$this->state[$key]['key']]?:$vl['name'];
		}
		$status_text = [1=>"待审核",2=>"通过",3=>"未通过"];
		include $this->tpl(ROUTE_M,'handle.list');	 
	}

	public function examine(){
		$id = $_POST['id'];
        $this->info = $res = $this->db->GetOne("SELECT * FROM `@#_handle` WHERE id = $id");
        if(!$res){
            echo json_encode(['code'=>0,"msg"=>"未找到记录"]);
            exit;
        }
        $data['manage_status'] = $_POST['status'];
		// $data['manage_status'] = '';
        $data['manage_desc'] = $_POST['desc'];
        $data['manage_time'] = time();
        $data['manage_id'] = $this->AdminInfo['uid'];
        $data['manage_name'] = $this->AdminInfo['username'];
		$this->info['manage_status'] = $data['manage_status'];
        $secure_password = $_POST['secure_password'];
        if(!$this->verifyExamine($secure_password)){
            echo json_encode(['code'=>0,"msg"=>"密码错误"]);
            exit;
        }
		$sql = Assemblydata($data,"handle","up",['id'=>$id]);
		$arr = [21,22,31];
		if($data['manage_status'] ==2 || in_array($res['type'],$arr)){
			$fun = "examine".$res["type"];
			try{
				$exa = $this->$fun($res['to_id']);
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}
		if($exa){
			$req = $this->db->Query($sql);
		}
		echo json_encode(['code'=>0,"msg"=>$this->errorMsg?:"审核完成","ss"=>$res,"sql"=>$sql,"exa"=>$exa]);
		return;
	}

	// 订单发货审核
	public function examine1($id){
		$res = $this->db->GetOne("select * from `@#_member_go_record` where id = $id");
		if($res){
			$status = '已付款,已发货,待收货';
			$ret = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status' where id='$id'");
			if($ret){
				$uid= $res['uid'];
				$company_code = $res['company_code'];
				$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");	
				if($status == '已付款,已发货,待收货'){//发货通知
                    _send_SMS($user['mobile'],10,$company_code);
				}
				return true;
			}else{
				return false;
			}
		}
	}
	// 优惠购订单发货审核
	public function examine2($id){
		$res = $this->db->GetOne("select * from `@#_orders` where id = $id");
		if($res){
			$status = '3';
			$ret = $this->db->Query("UPDATE `@#_orders` SET `status`='$status' where id='$id'");
			if($ret){
				$uid= $res['uid'];
				$company_code = $res['company_code'];
				$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");	
				if($status == '已付款,已发货,待收货'){//发货通知
                    _send_SMS($user['mobile'],10,$company_code);
				}
				return true;
			}else{
				return false;
			}
		}
	}
	// 优惠购订单发货审核
	public function examine3($id){
		$res = $this->db->GetOne("select * from `@#_orders_group` where id = $id");
		if($res){
			$status = '3';
			$ret = $this->db->Query("UPDATE `@#_orders_group` SET `status`='$status' where id='$id'");
			if($ret){
				$uid= $res['uid'];
				$company_code = $res['company_code'];
				$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");	
				if($status == '已付款,已发货,待收货'){//发货通知
                    _send_SMS($user['mobile'],10,$company_code);
				}
				return true;
			}else{
				return false;
			}
		}
	}
	// 银行卡充值审核
	public function examine21($id){
		$res = $this->db->GetOne("select * from `@#_offline_money` where id = $id");
		if($res){
			$status = $this->info['operat_status'];
			$time = time();
			$this->db->Autocommit_start();
			// 同意
			if($status == 2 && $this->info['manage_status'] == 2) {
				$ret=$this->db->Query("UPDATE `@#_offline_money` SET `status`='2',`update_time`='$time' WHERE id='$id'");
				$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $res[money] where (`uid` = '$res[uid]')");
				$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$res[uid]', '1', '账户', '银行卡充值', '$res[money]', '$time')");
				if(!$up_q2 || !$up_q3){
					$this->db->Autocommit_rollback();
					return false;
				}
			}else{
				$ret=$this->db->Query("UPDATE `@#_offline_money` SET `status`='3',`update_time`='$time' WHERE id='$id'");
			}
			if($ret){
				$this->db->Autocommit_commit();
				return true;
			}
			$this->db->Autocommit_rollback();
			return false;
		}
	}
	// usdt充值审核
	public function examine22($id){
		$res = $this->db->GetOne("select * from `@#_offline_money` where id = $id");
		if($res){
			$status = $this->info['operat_status'];
			$time = time();
			$this->db->Autocommit_start();
			// 同意
			if($status == 2 && $this->info['manage_status'] == 2) {
				$real_money = bcmul($res['money'], $res['exchange_rate']?:1, 2);
				$ret=$this->db->Query("UPDATE `@#_offline_money` SET `real_money` = $real_money,`status`='$status',`update_time`='$time' WHERE id='$id'");
				$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $real_money where (`uid` = '$res[uid]')");
				$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$res[uid]', '1', '账户', 'USDT充值', '$real_money', '$time')");
				if(!$up_q2 || !$up_q3){
					$this->db->Autocommit_rollback();
					return false;
				}
			}else{
				$ret=$this->db->Query("UPDATE `@#_offline_money` SET `status`='3',`update_time`='$time' WHERE id='$id'");
			}
			if($ret){
				$this->db->Autocommit_commit();
				return true;
			}
			$this->db->Autocommit_rollback();
			return false;
		}
	}
	// 提现审核
	public function examine31($id){
		$res=$this->db->GetOne("SELECT * FROM `@#_member_cashout` WHERE `id`='$id'  ");
		if($res){
			$status = $this->info['operat_status'];
			$status_massage = $this->info['operat_desc'];
			$time = time();
			$this->db->Autocommit_start();
			$is = true;
			// 同意
			if($status == 1 && $this->info['manage_status'] == 2) {
				$pay = System::load_app_class ( 'pay', 'pay' );
				$res['money'] = $res['money'] - $res['procefees'];//扣除手续费
				$res = $pay->withdrawal($res);
				if($res && $res['pay_status_code']==1){
					$data = ['settlement_id'=>$res['settlement_id']];
					$data = serialize($data);
					$ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='3',`reviewtime`='$time',`status_massage`='$status_massage' WHERE id='$id'");
				}elseif($res && $res['pay_status_code']==2){
					// $this->db->Autocommit_rollback();
					// $this->errorMsg = $res['errorMsg'];
					// return false;
					$type=-3;
					$content="提现";
					$ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='1' WHERE id='$id'");
					$rr=$this->db->Query("UPDATE `@#_member_commission` SET `dongjie_money`=`dongjie_money` - $res[money],`zhichu_money` =`zhichu_money`+$res[money]  where `uid`=$res[uid] ");
					$is=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`,`cashoutid`)VALUES($res[uid],'$type','$content','$res[money]','$res[time]','$res[id]')");
				}elseif($res){
					$this->errorMsg = $res['errorMsg'];
					$ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='4',`reviewtime`='$time',`status_massage`='$status_massage' WHERE id='$id'");
				}else{
					$ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='4',`reviewtime`='$time',`status_massage`='$status_massage' WHERE id='$id'");
				}
				$rr = true;
			}else{
				$zhichu = $res['zhichu_money'] - $res['dongjie_money'];
			    if($zhichu<0)$zhichu = 0;
				$rr=$this->db->Query("UPDATE `@#_member_commission` SET `total_money`=`total_money` + `dongjie_money`,`zhichu_money` = $zhichu,`dongjie_money`=0  where `uid`=$res[uid] ");
				$ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='2',`reviewtime`='$time',`status_massage`='$status_massage' WHERE id='$id'");
			}
			if($ret && $rr && $is){
				$this->db->Autocommit_commit();
				return true;
			}
			$this->db->Autocommit_rollback();
			return false;
		}
		$this->errorMsg = "未找到订单";
		return false;
	}

	public function handle_cashout(){
		$id = $this->segment(4);
		$type=-3;
		$content="提现";
		$res = $this->db->GetOne("SELECT * from `@#_member_cashout` WHERE `id` = '$id'");
		if(!$res){
			_messageerror();
		}
		$ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='1' WHERE id='$id'");
		$rr=$this->db->Query("UPDATE `@#_member_commission` SET `dongjie_money`=`dongjie_money` - $res[money],`zhichu_money` =`zhichu_money`+$res[money]  where `uid`=$res[uid] ");
		// $res['money'] =  $res['money'] - $res['procefees'];
		$is=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`,`cashoutid`)VALUES($res[uid],'$type','$content','$res[money]','$res[time]','$res[id]')");
		_message("成功");
	}

	// 线下充值审核
	public function examine41($id){
		$res=$this->db->GetOne("SELECT * FROM `@#_member_rechange` WHERE `id`='$id'  ");
		if($res){
			$m = $res['money'];
			$uid = $res['uid'];
			$status = $this->info['operat_status'];
			$req = $this->db->Query("update `@#_member_rechange`set `status` = $status where id = $id");
			if($status==2){
				if($res['type'] == 1){
					$sql1=$this->db->Query("UPDATE `@#_member` SET `money`=`money`+'$m' where uid='$uid'");
				}else{
					$sql1=$this->db->Query("UPDATE `@#_member` SET `money`=`money`-'$m' where uid='$uid'");
				}
				return $sql1;
			}else{
				return $req;
			}
		}else{
			$this->errorMsg = "未找到订单";
			return false;
		}
	}

	public function details(){
		$id = $this->segment(4);
		$res  = $this->db->GetOne("select * from `@#_handle` where id = $id");
		// $table = $this->state[$res['type']]['table'];
		// $info = $this->db->GetOne("select * from `@#_$table` where id = ".$res['to_id']);
		$fun = '';
		switch($res['type']){
			case 1:
				
				break;
			case 21:
				$fun = 'bank_details';
				break;
		}
		if(method_exists($this,$fun)){
			$this->$fun($res['to_id']);
		}else{

		}
		// $this->jsonData->success(200,"",$html);
	}

	//银行卡充值详细
	public function bank_details($id=0){
		$id = $id?:$this->segment(4);
		if(!$id){
			_messageerror();
		}
		$res = $this->db->GetOne("select o.*,m.username from `@#_offline_money` o left join `@#_member` m on m.uid = o.uid where o.id = $id");
		if(!$res){
			_messageerror();
		}
		$res['volume'] = mb_unserialize($res['volume']);
		$res['create_time'] = date("Y-m-d H:i:s",$res['create_time']);
		include $this->tpl(ROUTE_M,'handle.details_bank');
	}
}