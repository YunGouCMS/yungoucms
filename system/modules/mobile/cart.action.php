<?php
defined ('G_IN_SYSTEM') or exit ( 'No permission resources.' );
System::load_app_class ( 'base', 'member', 'no' );
System::load_app_fun ( 'user', 'go' );
class cart extends base {
private $Cartlist;
private $Cartlist_jf;
public function __construct() {
	$this->Cartlist = _getcookie('Cartlist');
	$this->Cartlist_jf = _getcookie('Cartlist_jf');
	$this->db = System::load_sys_class("model");
  	$this->symbol =$this->db->GetOne("SELECT * FROM `@#_currency` WHERE status='1'");
	}

	//购物车商品列表
	public function cartlist() {
		$webname = "购物车";
      	$symbol=$this->symbol;
		$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		$shopids = '';
      
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  // echo $shopids;
		  $shoplist = array ();
		  if ($shopids != NULL) {
		  $shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );
		} 
		  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );  
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
				  if ($val ['q_end_time'] == '' || $val ['q_end_time'] == NULL) {
					  $shoplist [$key] = $val;
					  $Mcartlist [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
					  $Mcartlist [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
					  $Mcartlist [$val ['id']] ['money'] = $val ['yunjiage'];
					  $Mcartlist [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
				  }else{
				  		$newid_info=$this->db->GetOne("SELECT * FROM `@#_shoplist` where sid='$val[sid]' ORDER BY id DESC ");

				  		  $shoplist [$newid_info['id']] = $newid_info;
				  		  $shoplist [$newid_info['id']]['old_qishu']=$val['qishu'];
						  $Mcartlist [$newid_info['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
						  $Mcartlist [$newid_info['id']] ['shenyu'] = $newid_info ['shenyurenshu'];
						  $Mcartlist [$newid_info['id']] ['money'] = $newid_info ['yunjiage'];
						  $Mcartlist [$newid_info['id']] ['sun'] = $newid_info ['yunjiage']*$Mcartlist [$newid_info ['id']] ['num'];
						  unset($Mcartlist[$val['id']]);	
				  }
			  } 
			  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );
		  }
		  $MoenyCount = 0;
		  $Cartshopinfo = '{';
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
					  $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist [$key] ['cart_shenyu'] . ",'num':" . $val ['num'] . ",'money':" . $shoplist [$key] ['yunjiage'] . "},";
				  }
			  }
		  }
		  $shop = 0;
		  if (! empty ( $shoplist )) {
			  $shop = 1;
		  }
		  $renqi_shop=$this->db->GetList("SELECT * FROM `@#_shoplist` WHERE  q_end_time is null order by canyurenshu desc limit 10");//查询参与人数最多的数据
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  $Cartshopinfo .= "'MoenyCount':$MoenyCount}";
		  include templates ( "mobile/cart", "cartlist" );
	  }
	  

	  // 支付界面
	  public function pay() {
		  $webname = "结算支付";
          $symbol=$this->symbol;
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }
		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array (
					  "key" => "id" 
			  ) );
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  $Mixturepay = $Money + $fufen_dikou;
		  $isintegralandmoney = $MoenyCount > $Money && $MoenyCount<=$MoenyCount?1:2; 
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  session_start ();
		  $_SESSION ['submitcode'] = $submitcode = uniqid ();
		  include templates ( "mobile/cart", "payment" );
	  }
	  
	  
	// 开始支付
	public function paysubmit() {
		$webname = $this->_cfg ['web_name'];
		header ( "Cache-control: private" );
		parent::__construct ();
		if (! $this->userinfo) {
			header ( "location: " . WEB_PATH . "/mobile/user/login" );
			exit ();
		  }
		  session_start ();
		  $checkpay = $this->segment ( 4 ); // 获取支付方式 fufen money bank
		  $banktype = $this->segment ( 5 ); // 获取选择的银行 CMBCHINA ICBC CCB
		  $money = $this->segment ( 6 ); // 获取需支付金额
		  $fufen = $this->segment ( 7 ); // 获取积分
		  $submitcode1 = $this->segment ( 8 ); // 获取SESSION
		  $uid = $this->userinfo ['uid'];
		  
		  //查询用户余额
		  $user_data = $this->db->GetOne("SELECT * FROM `@#_member` where uid=$uid");
		 
		  //积分余额对比 
		  if($checkpay=='fufen'){
    		   $fufen_cfg = System::load_app_config("user_fufen",'','member');
    		   if($fufen_cfg['fufen_yuan']){
    		       if($user_data['score']<$money*$fufen_cfg['fufen_yuan']){//小于了配置的积分
    		            _messagemobile ( "您的金额或积分不足请充值...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
    				  exit ();
    		       }
    		   }
		  }
		  
		  //现金余额对比
		  if($checkpay=='money'){
    		  if($user_data['money']<$money){
    		       _messagemobile ( "您的金额或积分不足请充值...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
    				  exit ();
    		  }    
		  }
		  
		  
		  if (! empty ( $submitcode1 )) {
			  if (isset ( $_SESSION ['submitcode'] )) {
				  $submitcode2 = $_SESSION ['submitcode'];
			  } else {
				  $submitcode2 = null;
			  }
			  if ($submitcode1 == $submitcode2) {
				  unset ( $_SESSION ["submitcode"] );
			  } else {
				  $WEB_PATH = WEB_PATH;
				  _messagemobile ( "请不要重复提交...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
				  exit ();
			  }
		  } else {
  // 			$WEB_PATH = WEB_PATH;
  // 			_messagemobile ( "正在返回购物车...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  // echo "<pre>";
		  // print_r($Mcartlist);exit;
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  // echo $shopids;
		  $shoplist = array ();
		  if ($shopids != NULL) {
		  $shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );
		}
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
			  		if($val['ispk']){
			  			if($Mcartlist[$key]['num'] >1){
			  				  _messagemobile ( "购物车里单件PK商品数量大于1",WEB_PATH.'/mobile/cart/cartlist');
			  			}else{
			  				$is_pk=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where shopid='$val[id]' and uid=$uid ORDER BY id DESC ");
			  				if($is_pk){
			  					_messagemobile ( "购物车里PK商品已参与过",WEB_PATH.'/mobile/cart/cartlist');exit;
			  				}	
			  			}
			  		}
			  }
		  }	 
		  $pay_checkbox = false;
		  $pay_type_bank = false;
		  $pay_type_id = false;
		  if ($checkpay == 'money') {
			  $pay_checkbox = true;
		  }
		  if ($banktype != 'nobank') {
			  $pay_type_id = $banktype;
		  }
		  if (! empty ( $zhifutype )) {
			  $pay_type_bank = $zhifutype ['pay_class'];
		  }
		  if (! $pay_type_id) {
			  if ($checkpay != 'fufen' && $checkpay != 'money' && $checkpay !='bufufenmoney')
				  _messagemobile ( "选择支付方式" );
		  }
		$pay=System::load_app_class('pay','pay');
		$pay->fufen = $checkpay=='fufen' || $checkpay =='bufufenmoney'?$fufen:0;
		$pay->pay_type_bank = $pay_type_bank;
		
		//var_dump($pay_type_bank);exit;//false
// 		var_dump($uid);
// 		var_dump($pay_type_id);//false
// 		var_dump($pay_checkbox);//false
// 		exit;
		
		$ok = $pay->init($uid,$pay_type_id,'go_record');	//云购商品
		if($ok != 'ok'){
			_setcookie('Cartlist',NULL);
			_messagemobile("购物车没有商品请<a href='".WEB_PATH."/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		  }
		  
		  $check = $pay->go_pay ( $pay_checkbox );
		  if (! $check) {
			  _messagemobile ( "订单添加失败,请<a href='" . WEB_PATH . "/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  if ($check) {
			  // 成功
			  header ( "location: " . WEB_PATH . "/mobile/cart/paysuccess" );
		  } else {
			  // 失败
			  _setcookie ( 'Cartlist', NULL );
			  header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  exit ();
	  }

	//成功页面
	public function paysuccess(){
	    $webname="支付成功";
		_setcookie('Cartlist',NULL);
		$wxkfewm=System::load_sys_config('system','wxkfewm');
		$qq=System::load_sys_config('system','qq');
		include templates("mobile/cart","paysuccess");
	}

	//成功页面
	public function paycancel(){
	    $webname=$this->_cfg['web_name'];
		include templates("mobile/cart","paycancel");
	}

	  
	// 充值
	public function addmoney() {
		parent::__construct ();
		$webname = $this->_cfg ['web_name'];
		$money = $this->segment ( 4 ); // 获取充值金额
		$pay_id = $this->segment ( 5 ); // 获取选择的支付方式
		$userinfo = $this->userinfo;
		if (! $userinfo) {
			header ( "location: " . WEB_PATH . "/mobile/user/login" );
			exit ();
		}
		$payment = $this->db->GetOne ( "select * from `@#_pay` where `pay_id` = ".$pay_id );
		if (! $payment) {
			_messagemobile ( "对不起，没有您所选择的支付方式！" );
		}
		$paymentarr = @unserialize($payment['pay_monerange']);

		if ($money > $paymentarr['range']['max']){
		    $max_money = $paymentarr['range']['min'];
			_messagemobile ( "充值金额必须小于或等于 $max_money 元" );
		}

		if ( $money < $paymentarr['range']['min']){
            $min_money = $paymentarr['range']['min'];
            _messagemobile ( "充值金额必须大于或等于 $min_money 元" );
        }

		if (! empty ( $payment )) {
			$pay_type_bank = $payment ['pay_class'];
		}
		$pay_type_id = $pay_id;
		$uid = $this->userinfo ['uid'];
		$pay = System::load_app_class ( 'pay', 'pay' );
		$pay->pay_type_bank = $pay_type_bank;
		$ok = $pay->init ( $uid, $pay_type_id, 'addmoney_record', $money, $userinfo['level'] );
		var_dump($ok);
		exit();
		if ($ok === 'not_pay') {
			_messagemobile ( "未选择支付平台" );
		}
	}
}
?>