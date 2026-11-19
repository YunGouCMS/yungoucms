<?php
System::load_sys_fun("send");


/*

*   生成购买的云购码

*	user_num 		@生成个数

*	shopinfo		@商品信息

*	ret_data		@返回信息

*/

function pay_get_shop_codes($user_num=1,$shopinfo=null,&$ret_data=null){



	$db = System::load_sys_class("model");

	$ret_data['query'] = true;

	$table = '@#_'.$shopinfo['codes_table'];

	$codes_arr = array();
	$codes_one = $db->GetOne("select id,s_id,s_cid,s_len,s_codes from `$table` where `s_id` = '$shopinfo[id]' order by `s_cid` DESC  LIMIT 1 for update");
//	var_dump("select id,s_id,s_cid,s_len,s_codes from `$table` where `s_id` = '$shopinfo[id]' order by `s_cid` DESC  LIMIT 1 for update");

	$codes_arr[$codes_one['s_cid']] = $codes_one;

	$codes_count_len = $codes_arr[$codes_one['s_cid']]['s_len'];



	if($codes_count_len < $user_num && $codes_one['s_cid'] > 1){

		for($i=$codes_one['s_cid']-1;$i>=1;$i--):

			$codes_arr[$i] = $db->GetOne("select id,s_id,s_cid,s_len,s_codes from `$table` where `s_id` = '$shopinfo[id]' and `s_cid` = '$i'  LIMIT 1 for update");

			$codes_count_len += $codes_arr[$i]['s_len'];

			if($codes_count_len > $user_num)  break;

		endfor;

	}



	if($codes_count_len < $user_num) $user_num = $codes_count_len;



	$ret_data['user_code'] = '';

	$ret_data['user_code_len'] = 0;



	foreach($codes_arr as $icodes){

		$u_num = $user_num;

		$icodes['s_codes'] = unserialize($icodes['s_codes']);

		$code_tmp_arr = array_slice($icodes['s_codes'],0,$u_num);

		$ret_data['user_code'] .= implode(',',$code_tmp_arr);

		$code_tmp_arr_len = count($code_tmp_arr);



		if($code_tmp_arr_len < $u_num){

			$ret_data['user_code'] .= ',';

		}



		$icodes['s_codes'] = array_slice($icodes['s_codes'],$u_num,count($icodes['s_codes']));

		$icode_sub = count($icodes['s_codes']);

		$icodes['s_codes'] = serialize($icodes['s_codes']);



		if(!$icode_sub){

			$query = $db->Query("UPDATE `$table` SET `s_cid` = '0',`s_codes` = '$icodes[s_codes]',`s_len` = '$icode_sub' where `id` = '$icodes[id]'");

			if(!$query)$ret_data['query'] = false;

		}else{

			$query = $db->Query("UPDATE `$table` SET `s_codes` = '$icodes[s_codes]',`s_len` = '$icode_sub' where `id` = '$icodes[id]'");

			if(!$query)$ret_data['query'] = false;

		}

		$ret_data['user_code_len'] += $code_tmp_arr_len;

		$user_num  = $user_num - $code_tmp_arr_len;
	}



}

function pay_insert_shop_ssc($shop='',$type=''){


	//$time=sprintf("%.3f",microtime(true)+(int)System::load_sys_config('system','goods_end_time'));

	$db = System::load_sys_class("model");

	$url = "http://api.yungoucms.cn/getsscdata.php?token=bba23767dae041a3771e4dcde2528ab4&t=ssc";

	$content = json_decode(file_get_contents($url));

	$ssc_data =(array)($content[0]);

	$tocode = System::load_app_class("CloudTocode","pay");

	$tocode->shop = $shop;

	$ssctime=$ssc_data['opentime'];

	$strtotime=strtotime("$ssctime +23 minute");

	$ytdatetime=strtotime(date('Y-m-d 23:53:00'));

	$nowdatetime=strtotime(date('Y-m-d 00:00:00'));

	$nowonetime=strtotime(date('Y-m-d 00:33:00'));

	$nowthreetime=strtotime(date('Y-m-d 03:10:00'));

	$nowseventime=strtotime(date('Y-m-d 07:33:00'));

	$t=time();
	if($t > $ytdatetime){
		$strtotime=strtotime("$ssctime +43 minute");
	}
	if($t > $nowdatetime && $t <$nowonetime){
		$strtotime=strtotime("$ssctime +43 minute");
	}

	if($t > $nowthreetime && $t <$nowseventime){
		$strtotime=strtotime("$ssctime +273 minute");

	}

	$time=sprintf("%.3f",microtime(true)+(int)($strtotime-time()));

	$tocode->run_tocode($time,100,$shop['canyurenshu'],$shop);

	$q_end_counttime=$tocode->end_counttime;

	$content = addslashes($tocode->go_content);





	//更新商品

	$query = true;



	$q_showtime = 'Y';






	$sqlss = "UPDATE `@#_shoplist` SET

							`canyurenshu`=`zongrenshu`,

							`shenyurenshu` = '0',

							`q_end_time` = '$time',
							
							`q_showtime` = '$q_showtime',
							
							`q_end_counttime` = '$q_end_counttime',
							
							`q_content`	= '$content'
							
							 where `id` = '$shop[id]'";

	$q = $db->Query($sqlss);

	if(!$q)$query = false;




	/******************************/

	/*新建*/

	if($query){

		if($shop['qishu'] < $shop['maxqishu']){

			$maxinfo = $db->GetOne("select * from `@#_shoplist` where `sid` = '$shop[sid]' order by `qishu` DESC LIMIT 1");

			if(!$maxinfo){

				$maxinfo=array("qishu"=>$shop['qishu']);

			}

			System::load_app_fun("content",G_ADMIN_DIR);

			$intall = content_add_shop_install($maxinfo,false);

			if(!$intall) return $query;

		}

	}
	return $query;
}



//生成订单号

function pay_get_dingdan_code($dingdanzhui=''){

	return $dingdanzhui.time().substr(microtime(),2,6).rand(0,9);

}





/*

	揭晓与插入商品

	@shop   商品数据

*/



function pay_insert_shop_x($shop='',$type=''){



	$g_c_x = System::load_app_config("get_code_x",'',"pay");

	if(is_array($g_c_x) && isset($g_c_x['class'])){

		$gcx_db = System::load_app_class($g_c_x['class'],"pay");

	}else{

		$g_c_x = array("class"=>"tocode");

		$gcx_db = System::load_app_class($g_c_x['class'],"pay");

	}



	$gcx_db->config($shop,$type);

	$gcx_db->get_run_tocode();

	$ret_data = $gcx_db->returns();

}



/*

	揭晓与插入商品

	@shop   商品数据

*/

function pay_insert_shop($shop='',$type=''){


	$time=sprintf("%.3f",microtime(true)+(int)System::load_sys_config('system','goods_end_time'));

	$db = System::load_sys_class("model");

	if($shop['xsjx_time'] != '0'){

		return $db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,	`shenyurenshu` = '0' where `id` = '$shop[id]'");

	}

	$tocode = System::load_app_class("tocode","pay");

	$tocode->shop = $shop;

	$tocode->run_tocode($time,100,$shop['canyurenshu'],$shop);



	$code =$tocode->go_code;



	$content = addslashes($tocode->go_content);

	$counttime = $tocode->count_time;


	$u_go_info = $db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `goucode` LIKE  '%$code%'");
//	var_dump("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `goucode` LIKE  '%$code%'");exit;

	$u_info = $db->GetOne("select uid,username,email,mobile,img from `@#_member` where `uid` = '$u_go_info[uid]'");

	$num=$db->GetOne("select sum(gonumber) as gnum from `@#_member_go_record` where shopid=".$shop['id']." AND uid=".$u_go_info['uid']);
	$buycount=$num['gnum'];
	$goucount=1;
	$shoplist=$db->GetList("SELECT id,gonumber FROM `@#_member_go_record` WHERE `shopid` = '$shop[id]' AND `shopqishu` = '$u_go_info[shopqishu]' order by time asc");
	foreach ($shoplist as $ky => $v) {

		if($v['id'] == $u_go_info['id']){
			break;
		}else{
			$goucount+=$v['gonumber'];
		}

	}



	//更新商品

	$query = true;
	if($u_info){

		$u_info['username'] = _htmtocode($u_info['username']);

		$q_user = serialize($u_info);

		$gtimes = (int)System::load_sys_config('system','goods_end_time');

		if($gtimes == 0 || $gtimes == 1){
			$q_showtime = 'N';
		}else{
			$q_showtime = 'Y';
		}





		$sqlss = "UPDATE `@#_shoplist` SET

							`canyurenshu`=`zongrenshu`,

							`shenyurenshu` = '0',

							`q_uid` = '$u_info[uid]',

							`q_user` = '$q_user',

							`q_user_code` = '$code',

							`q_content`	= '$content',

							`q_counttime` ='$counttime',

							`q_end_time` = '$time',

							`q_showtime` = '$q_showtime',
							
							`goucount` = '$goucount',
								
							`buycount` = '$buycount'					

							 where `id` = '$shop[id]'";


		$q = $db->Query($sqlss);
		if(!$q)$query = false;

		//如果没有中奖短信就强制在发送一遍--E
		if($q){

			$q = $db->Query("UPDATE `@#_member_go_record` SET `huode` = '$code' where `id` = '$u_go_info[id]' and `code` = '$u_go_info[code]' and `uid` = '$u_go_info[uid]' and `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]'");

			if(!$q) {

				$query = false;

			}else{
				$type = System::load_sys_config("send","type");
				if ($type==2) {
					$uid = $u_info['uid'];
					$mobile = $u_info['mobile'];
					if(!empty($mobile)){

						//send_mobile_shop_code($mobile,$uid,$code);

					}
				}else{
					$post_arr= array("uid"=>$u_info['uid'],"gid"=>$shop['id'],"send"=>1);
					_g_triggerRequest(WEB_PATH.'/api/send/send_shop_code',false,$post_arr);
				}
			}

		}else{

			$query =  false;

		}

	}else{

		$query =  false;

	}

	/******************************/

	/*新建*/

	if($query){

		if($shop['qishu'] < $shop['maxqishu']){

			$maxinfo = $db->GetOne("select * from `@#_shoplist` where `sid` = '$shop[sid]' order by `qishu` DESC LIMIT 1");

			if(!$maxinfo){

				$maxinfo=array("qishu"=>$shop['qishu']);

			}

			System::load_app_fun("content",G_ADMIN_DIR);

			$intall = content_add_shop_install($maxinfo,false);

			if(!$intall) return $query;

		}

	}
	return $query;
}


/*
	云购基金

	go_number @云购人次
*/

function pay_go_fund($go_number=null){

	if(!$go_number)return true;

	$db = System::load_sys_class("model");

	$fund = $db->GetOne("select * from `@#_fund` where 1");

	if($fund && $fund['fund_off']){

		$money = $fund['fund_money'] * $go_number + $fund['fund_count_money'];

		return $db->Query("UPDATE `@#_fund` SET `fund_count_money` = '$money'");

	}else{

		return true;

	}

}

/*
	用户佣金
	uid 		用户id
	dingdancode	@订单号
	type	订单类型1云购，2优惠购，3拼团购
*/
function pay_go_yongjin($uid=null,$dingdancode=null,$type = 1,$level=1){
	if(!$uid || !$dingdancode)return true;
	$db = System::load_sys_class("model");
	$time=time();
	$yesyaoqing=$db->GetOne("SELECT `yaoqing` FROM `@#_member` WHERE `uid`='$uid'");
	//上级id
	$puid=$yesyaoqing['yaoqing'];
	if(!$yesyaoqing['yaoqing']){
		return true;
	}
	$info = get_yongjin_ratio($puid,$level);
	$yongjin = $info[0];
	$level = $info[1];
	if($yongjin > 0){
		$is_yongjin=$db->GetOne("SELECT * FROM `@#_member_commission` where uid = '$puid'");
		if(!$is_yongjin){
			if($puid){
				$db->Query("INSERT INTO `@#_member_commission`(`uid`)VALUES('$puid')");
			}
		}
		$yongjin = floatval(substr(sprintf("%.4f",$yongjin), 0, -1));
		switch($type){
			case 1:
				$table = 'member_go_record';
				break;
			case 2:
				$table = 'orders';
				break;
			case 3:
				$table = 'orders_group';
				break;
			case 4:
				$table = 'member_goods_order';
				break;
		}
		$orderList=$db->GetList("SELECT * FROM `@#_$table` WHERE `code`='$dingdancode'");
		foreach($orderList as $order){		    
			if($order['money'] > 0){
				$y_money=$order['money'] * $yongjin; //仅金额计算
				$content=$order['shopname']?:"直购商品";
				if($type == 1){
					$content = "(第".$order['shopqishu']."期)".$content;
				}
				$db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`shopid`,`money`,`ygmoney`,`time`,`yaoqing`)VALUES('$uid','1','$content','$order[shopid]','$y_money','$order[moneycount]','$time' ,'$puid')");
				$db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money` + '$y_money',`shouru_money`=`shouru_money` + '$y_money' where uid= '$puid'");
			}
		}
	}
	if($info[1] && $puid){
		pay_go_yongjin($puid,$dingdancode,$type,$level);
	}
}

function get_yongjin($uid,$key='',$level=1){
	$db = System::load_sys_class("model");$time=time();
	$config = System::load_app_config("user_fufen",'','member');//积分/经验/佣金
	$yesyaoqing=$db->GetOne("SELECT `yaoqing` FROM `@#_member` WHERE `uid`='$uid'");
	$puid=$yesyaoqing['yaoqing'];
	if(isset($config[$key]) && $config[$key]>0 && $puid ){
		//一级邀请
		$yongjin = $config[$key];
		$content = "邀请好友奖励";
		$db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`shopid`,`money`,`ygmoney`,`time`,`yaoqing`)VALUES('$uid','1','$content','','$yongjin','','$time' ,'$puid')");
		$db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money` + '$yongjin',`shouru_money`=`shouru_money` + '$yongjin' where uid= '$puid'");
	}
	if($level==1 && $puid){
		//二级邀请
		get_yongjin($puid,$key."_1",2);
	}	
}

/**
 * 用户佣金 多级返佣
 *
 * @param integer $uid  用户uid
 * @param string $dingdancode 订单号
 * @param integer $type	订单类型1云购，2优惠购，3拼团购
 * @param integer $level 返佣等级
 * @return array
 */
function get_yongjin_ratio($uid=null,$level=1){
	//佣金 4 3 2 1
	//1邀请 2 邀请 3 邀请4  邀请5
	//5下单 4获得4元，3获得3元，2获得2元，1获得1元
    if(!$uid)return true;
	//数据库
	$db = System::load_sys_class("model");
	//获取配置
	$config = System::load_app_config("user_fufen",'fufen_yongjin','member')?:[];//积分/经验/佣金
	$count = count($config);
	if($count<=0 || $level>$count){return [0,false];};
	//上级id
	//判断邀请人类别 普通会员，渠道商，入驻商，回收商
	$rake = $db->getOne("select g.rake_back,g.rake_back_1,m.level from `@#_member` m join `@#_member_group` g on m.groupid = g.groupid where m.uid = $uid");
	if(!$rake){
		//上级不存在
		return true;
	}
	switch($rake['level']){
		case 2:
			//渠道商佣金
			$channel = $db->getOne("select `value` from `@#_config` where `name` = 'channel_set'");
			$channel = $channel['value']?:'';
			$config = mb_unserialize($channel);
			$yongjin = $config['fufen_yongjin'];
			if($level == 2){
				$yongjin = $config['fufen_yongjin_1'];
			}elseif($level > 2){
				$yongjin = 0;
			}
			break;
		case 1:
			//回收商佣金
			$channel = $db->getOne("select * from `@#_member_commission_config` where uid = $uid");
			if($channel){
				$config = json_decode($channel['commission'],true);
			}else{
				$config = ["fufen_yongjin"=>0,"fufen_yongjin_1"=>0];
			}
			$yongjin = $config['fufen_yongjin'];
			if($level == 2){
				$yongjin = $config['fufen_yongjin_1'];
			}elseif($level > 2){
				$yongjin = 0;
			}
			break;
		default:
			//普通会员
			//一级返佣优先使用会员等级里面的返佣
			if($level == 1 && $rake['rake_back']){
				$yongjin = $rake['rake_back'];
			}else{
				$yongjin = $config[$level-1]?:0;
			}
			break;
	}
	return [$yongjin>1?$yongjin/100:$yongjin,$level+1,$uid,$count];
}


/**
 * 获得拼购云购码
 * @param mixed $num  购买数量
 * @param mixed $shopinfo	商品信息
 * @param mixed $count 		已购买的数量	
 * @return string
 */
function pay_group_codes($num=1,$shopinfo=null,$count){
	$db = System::load_sys_class("model");
	$table = '@#_'.$shopinfo['codes_table']; //云购码表
	$cid = $count?ceil($count/3000):1;
	$start = $count % 3000;
	$get_codes = [];
	while($num>0){
		$res = $db->GetOne("select id,s_id,s_cid,s_len,s_codes,s_codes_tmp from `$table` where `s_id` = '$shopinfo[id]' and `s_cid` = '$cid'  LIMIT 1 for update");
		$codes = @unserialize($res['s_codes_tmp']);
		if(3000 - $start >=$num){
			$get_codes = array_merge($get_codes,array_slice($codes,$start,$num));
			$num = 0;
		}else{
			$get_codes = array_merge($get_codes,array_slice($codes,$start));
			$num = $num - (3000-$start);
		}
		$cid++;
	}
	return implode(",",$get_codes);
}

/**
 * 获得拼购中奖码
 * @return mixed
 */
function get_huode_code($shop){
	$time=sprintf("%.3f",microtime(true)+(int)System::load_sys_config('system','goods_end_time'));
	$db = System::load_sys_class("model");
	$tocode = System::load_app_class("tocode","pay");
	$tocode->shop = $shop;
	$tocode->type = 2;
	$tocode->run_tocode($time,100,$shop['zongrenshu'],$shop);
	$code =$tocode->go_code;
	return $code;
}
?>
