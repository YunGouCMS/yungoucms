<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
// System::load_app_class('base', 'member', 'no');
System::load_app_class('init','go');

System::load_app_fun('user', 'go');
class cart extends init
{
	private $Cartlist;
	public function __construct()
	{
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma:no-cache");
		$this->Cartlist = _getcookie('Cartlist');
		$this->db = System::load_sys_class("model");
		$this->nowUserLanguage = _getcookie('language') ?: _cfg("web_language");
	}
	//获取购物车的商品信息到头部
	public function cartheader()
	{
		$Cartlist = json_decode(stripslashes($this->Cartlist), true);
		$shopids = '';
		if (is_array($Cartlist)) {
			foreach ($Cartlist as $key => $val) {
				$shopids .= intval($key) . ',';
			}
			$shopids = str_replace(',0', '', $shopids);
			$shopids = trim($shopids, ',');
		}
		$shoplist = array();
		if ($shopids !== '' && $shopids !== null) {
			$shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) limit 5", array("key" => "id"));
		}
		$MoenyCount = 0;
		$Cartshopinfo = '{';
		if (is_array($shoplist) && count($shoplist) >= 1) {
			foreach ($Cartlist as $key => $val) {
				$key = intval($key);
				if (isset($shoplist[$key])) {
					$shoplist[$key]['cart_gorenci'] = $val['num'] ? $val['num'] : 1;
					$MoenyCount += $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];
					$shoplist[$key]['cart_xiaoji'] = substr(sprintf("%.3f", $shoplist[$key]['yunjiage'] * $val['num']), 0, -1);
					$shoplist[$key]['cart_shenyu'] = $shoplist[$key]['zongrenshu'] - $shoplist[$key]['canyurenshu'];
					$Cartshopinfo .= "'$key':{'shenyu':" . $shoplist[$key]['cart_shenyu'] . ",'num':" . $val['num'] . ",'money':" . $shoplist[$key]['yunjiage'] . "},";
				}
			}
		}
		$MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);
		$Cartshopinfo .= "'MoenyCount':$MoenyCount}";
		if (!is_array($Cartlist)) {
			$shop['num'] = 0;
		} else {
			$shop['num'] = count($Cartlist) - 1;
		}
		$shop['sum'] = $MoenyCount;
		echo json_encode($shop);
	}
	//获取购物车的商品信息
	public function cartshop()
	{
		$Cartlist = json_decode(stripslashes($this->Cartlist), true);
		$shopids = '';
		if (is_array($Cartlist)) {
			foreach ($Cartlist as $key => $val) {
				$shopids .= intval($key) . ',';
			}
			$shopids = str_replace(',0', '', $shopids);
			$shopids = trim($shopids, ',');
		}
		$shoplist = array();
		if ($shopids !== '' && $shopids !== null) {
			$shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) limit 7", array("key" => "id"));
		}
		$MoenyCount = 0;
		$Cartshopinfo = '{';
		if (is_array($shoplist) && count($shoplist) >= 1) {
			foreach ($Cartlist as $key => $val) {
				$key = intval($key);
				if (isset($shoplist[$key])) {
					$shoplist[$key]['cart_gorenci'] = $val['num'] ? $val['num'] : 1;
					$MoenyCount += $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];
					$shoplist[$key]['cart_xiaoji'] = substr(sprintf("%.3f", $shoplist[$key]['yunjiage'] * $val['num']), 0, -1);
					$shoplist[$key]['cart_shenyu'] = $shoplist[$key]['zongrenshu'] - $shoplist[$key]['canyurenshu'];
					$Cartshopinfo .= "'$key':{'shenyu':" . $shoplist[$key]['cart_shenyu'] . ",'num':" . $val['num'] . ",'money':" . $shoplist[$key]['yunjiage'] . "},";
				}
			}
		}
		$MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);
		$Cartshopinfo .= "'MoenyCount':$MoenyCount}";
		$li = "";
		foreach ((array)$shoplist as $st) {
			$li .= '<li id="shopid' . $st['id'] . '">';
			$li .= '<a href="javascript:;" onclick="delshop(' . $st['id'] . ')" title="删除" class="Close"></a>';
			$li .= '<a href="' . WEB_PATH . '/goods/' . $st['id'] . '"><img src="' . G_UPLOAD_PATH . '/' . $st['thumb'] . '" title="' . $st['title'] . '"></a>';
			$li .= '<span class="orange">' . $st['cart_gorenci'] . '</span>人次';
			$li .= '</li>';
		}
		if (count($shoplist) >= 7) {
			$li .= '<li class="Roll_CartMore"><a target="_blank" title="查看更多" href="' . WEB_PATH . '/member/cart/cartlist">更多<i>&gt;</i></a></li>';
		}
		$shop['li'] = $li;
		if (!is_array($Cartlist)) {
			$shop['num'] = 0;
		} else {
			$shop['num'] = count($Cartlist) - 1;
		}
		$shop['sum'] = $MoenyCount;
		echo json_encode($shop);
	}
	//获取购物车的商品数量ajax
	public function getnumber()
	{
		$Cartlist = json_decode(stripslashes($this->Cartlist), true);
		if (!is_array($Cartlist)) {
			echo 0;
		} else {
			echo count($Cartlist) - 1;
		}
	}
	//卡密充值
	public function card_addmoney()
	{
		$is_charlie_recharge = $this->getConfig('is_charlie_recharge') == 2 ? false : true;
		if (!$is_charlie_recharge) {
			_messagemobile("卡密充值未开启");
		}
		if (isset($_POST['submit']) &&  $_SERVER['REQUEST_METHOD'] == "POST") {
			$data = $_POST;
			$card = strip_tags(htmlspecialchars($_POST['czknum']));
			$pass = strip_tags(htmlspecialchars($_POST['password']));
			$cardinfo = $this->db->GetOne("SELECT * FROM `@#_czk` WHERE czknum='$card' AND password='$pass' FOR update");
			if ($cardinfo) {
				if ($cardinfo['status']) {
					$uid = intval(strip_tags(htmlspecialchars($_POST['uid'])));
					if (!$uid) {
						_messagemobile("非法参数！");
						exit;
					}
					$user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE uid = :uid", array('uid' => $uid));
					if (!$user) {
						_messagemobile("用户不存在！");
						exit;
					}
					$money = $cardinfo['mianzhi'];
					$cardid = $cardinfo['id'];
					$time = time();
					$this->db->Autocommit_start();
					$sql1 = $this->db->Query("INSERT INTO `@#_card_recharge` (uid,money,code,codepwd,rechargecount,time) VALUES(:cu,:m,:c,:p,'1',:t)", array('cu'=>$uid,'m'=>$money,'c'=>$card,'p'=>$pass,'t'=>$time));
					$sql2 = $this->db->Query("UPDATE `@#_member` SET `money`=`money`+:m WHERE uid=:cu", array('m'=>$money,'cu'=>$uid));
					$sql3 = $this->db->Query("UPDATE `@#_czk` SET status = '0' where id=:id", array('id'=>$cardid));
					$sql4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) VALUES (:cu,'1','账户','卡密充值',:m,:t)", array('cu'=>$uid,'m'=>$money,'t'=>$time));
					if ($sql1 && $sql2 && $sql3 && $sql4) {
						$this->db->Autocommit_commit();
						_messagemobile("充值成功！");
						exit;
					} else {
						$this->db->Autocommit_rollback();
						_messagemobile("充值失敗！");
						exit;
					}
				} else {
					_messagemobile("卡密已使用");
				}
			} else {
				_messagemobile("卡密卡號不存在");
			}
		} else {
			_messagemobile("非法提交！记录该次行为");
		}
	}
	//购物车商品列表
	public function cartlist()
	{
		$Cartlist = json_decode(stripslashes($this->Cartlist), true);
		$shopids = '';
		if (is_array($Cartlist)) {
			foreach ($Cartlist as $key => $val) {
				$shopids .= intval($key) . ',';
			}
			$shopids = str_replace(',0', '', $shopids);
			$shopids = trim($shopids, ',');
		}
		$shoplist = array();
		if ($shopids !== '' && $shopids !== null) {
			$shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids)", array("key" => "id"));
		}
		$MoenyCount = 0;
		$Cartshopinfo = '{';
		if (is_array($shoplist) && count($shoplist) >= 1) {
			foreach ($Cartlist as $key => $val) {
				$key = intval($key);
				if (isset($shoplist[$key])) {
					$shoplist[$key]['cart_gorenci'] = $val['num'] ? $val['num'] : 1;
					$MoenyCount += $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];
					$shoplist[$key]['cart_xiaoji'] = substr(sprintf("%.3f", $shoplist[$key]['yunjiage'] * $val['num']), 0, -1);
					$shoplist[$key]['cart_shenyu'] = $shoplist[$key]['zongrenshu'] - $shoplist[$key]['canyurenshu'];
					$Cartshopinfo .= "'$key':{'shenyu':" . $shoplist[$key]['cart_shenyu'] . ",'num':" . $val['num'] . ",'money':" . $shoplist[$key]['yunjiage'] . "},";
				}
			}
		}
		$MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);
		$Cartshopinfo .= "'MoenyCount':$MoenyCount}";
		$cry = $this->db->GetOne("select * from `@#_language` where lg_value = '{$this->nowUserLanguage}'");
		$currency_unit = $cry['currency_unit'];
		include templates("cart", "cartlist");
	}
	//支付界面
	public function pay()
	{
		parent::__construct();
		if (!$member = $this->userinfo) $this->HeaderLogin();
		$Cartlist = json_decode(stripslashes($this->Cartlist), true);

		$shopids = '';
		if (is_array($Cartlist)) {
			foreach ($Cartlist as $key => $val) {
				$shopids .= intval($key) . ',';
			}
			$shopids = str_replace(',0', '', $shopids);
			$shopids = trim($shopids, ',');
		}
		$shoplist = array();
		if ($shopids !== '' && $shopids !== null) {
			$shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids)", array("key" => "id"));
		}
		$MoenyCount = 0;
		if (is_array($shoplist) && count($shoplist) >= 1) {
			foreach ($Cartlist as $key => $val) {
				$key = intval($key);
				if (isset($shoplist[$key])) {
					$shoplist[$key]['cart_gorenci'] = $val['num'] ? $val['num'] : 1;
					$MoenyCount += $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];
					$shoplist[$key]['cart_xiaoji'] = substr(sprintf("%.3f", $shoplist[$key]['yunjiage'] * $val['num']), 0, -1);
					$shoplist[$key]['cart_shenyu'] = $shoplist[$key]['zongrenshu'] - $shoplist[$key]['canyurenshu'];
				}
			}
		} else {
			_setcookie('Cartlist', NULL);
			_message("购物车没有商品!", WEB_PATH);
		}
		//总支付价格
		$MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);
		//会员余额
		$Money = $member['money'];
		//商品数量
		$shoplen = count($shoplist);
		$fufen = System::load_app_config("user_fufen");
		if ($fufen['fufen_yuan']) {
			$fufen_dikou = intval($member['score'] / $fufen['fufen_yuan']);
		} else {
			$fufen_dikou = 0;
		}
		$paylist = $this->db->GetList("SELECT * from `@#_pay` where `pay_start` = '1' AND `pay_mobile` != 1 ");
		$cookies = base64_encode($this->Cartlist);
		session_start();
		$_SESSION['submitcode'] = $submitcode = uniqid();
		$csrf_token = function_exists('csrf_token_get') ? csrf_token_get() : '';
		include templates("cart", "pay");
	}
	//开始支付
	public function paysubmit()
	{
		if (!isset($_POST['submit'])) {
			_message("正在返回购物车...", WEB_PATH . '/member/cart/cartlist');
			exit;
		}
		if (!function_exists('csrf_token_validate') || !csrf_token_validate()) {
			_message("请求无效，请从支付页面重新提交", WEB_PATH . '/member/cart/cartlist', 3);
			exit;
		}
		session_start();
		if (isset($_POST['submitcode'])) {
			if (isset($_SESSION['submitcode'])) {
				$submitcode = $_SESSION['submitcode'];
			} else {
				$submitcode = null;
			}
			if ($_POST['submitcode'] == $submitcode) {
				unset($_SESSION["submitcode"]);
			} else {
				_message("请不要重复提交...", WEB_PATH . '/member/cart/cartlist');
			}
		} else {
			_message("正在返回购物车...", WEB_PATH . '/member/cart/cartlist');
		}

		parent::__construct();
		if (!$this->userinfo) $this->HeaderLogin();
		$uid = $this->userinfo['uid'];

		$pay_checkbox = isset($_POST['moneycheckbox']) ? true : false;
		$pay_type_bank = isset($_POST['pay_bank']) ? $_POST['pay_bank'] : false;
		$pay_type_id = isset($_POST['account']) ? $_POST['account'] : false;

		if (isset($_POST['shop_score'])) {
			$fufen_cfg = System::load_app_config("user_fufen", '', 'member');
			$fufen = intval($_POST['shop_score_num']);
			if ($fufen_cfg['fufen_yuan']) {
				$fufen = intval($fufen / $fufen_cfg['fufen_yuan']);
				$fufen = $fufen * $fufen_cfg['fufen_yuan'];
			}
		} else {
			$fufen = 0;
		}
		/************start*************/
		$Cartlist = json_decode(stripslashes($this->Cartlist), true);
		$pay = System::load_app_class('pay', 'pay');
		//$pay->scookie = json_decode(base64_decode($_POST['cookies']));
		$pay->fufen = $fufen;
		$pay->pay_type_bank = $pay_type_bank;
		$ok = $pay->init($uid, $pay_type_id, 'go_record');	//云购商品	
		if ($ok !== 'ok') {
			$_COOKIE['Cartlist'] = NULL;
			_setcookie("Cartlist", null);
			_message($ok, G_WEB_PATH);
		}
		$check = $pay->go_pay($pay_checkbox);
		if ($check === 'not_pay') {
			_message(lan('未选择支付平台!'), WEB_PATH . '/member/cart/cartlist');
		}
		if (!$check) {
			_message(lan("Product_payment_failed","商品支付失败!"), WEB_PATH . '/member/cart/cartlist');
		}
		if ($check) {
			//成功
			header("location: " . WEB_PATH . "/member/cart/paysuccess");
		} else {
			//失败	
			$_COOKIE['Cartlist'] = NULL;
			_setcookie("Cartlist", null);
			header("location: " . WEB_PATH);
		}
		exit;
	}
	//成功页面
	public function paysuccess()
	{
		$_COOKIE['Cartlist'] = NULL;
		_setcookie("Cartlist", null);
		include templates("cart", "paysuccess");
	}

	//usdt充值
	public function addmoney_usdt()
	{
		parent::__construct();
		if (!$this->userinfo) $this->HeaderLogin();
		$uid = $this->userinfo['uid'];
		$post = $_POST;
        if(!preg_match('/^\d+$/', $post['id'])){
            $this->jsonData->errCode('','操作失败');
        }
		$post['id'] = safe_replace($post['id']);
        $pay= $this->db->GetOne("SELECT * FROM `@#_kefu` where id = :id", array('id' => $post['id']));
        if(!$pay){
            $this->jsonData->errCode('','支付方式未找到');
        }
		$is_usdt_recharge = $this->getConfig("is_usdt_recharge")==2?false:true;
        if(!$is_usdt_recharge && $pay['type']==3){
            $this->jsonData->errCode('','usdt充值未开启');
        }
        $post['money'] = safe_replace($post['money'],true);
		$type = $pay['type'];
        unset($pay['pay_monerange']);
        $kefu = serialize($pay);
        $exchange_rate = $pay['exchange_rate']?:1;
        $pay_prefix = $this->getConfig('pay_prefix')?:"C";
        System::load_app_fun("pay","pay");
        $code = pay_get_dingdan_code($pay_prefix);		//订单号
        $money = number_format($post['money']);
		$time = time();
		$data = [
            "uid"=>$uid,
            "bank_id"=>$post['id'],
            "create_time"=>$time,
            "status"=>1,
            "volume"=>$kefu,
            "money"=>$money,
            "type"=>$type,
            "exchange_rate"=>$exchange_rate,
            "real_money"=>bcmul($money,$exchange_rate),
            "code"=>$code
        ];
        $sql = Assemblydata($data,"offline_money");
		if($pay['type']==3){
            if(!$pay['pay_class']){
                $this->jsonData->errCode('','后台未配置');
            }
            $paydb = System::load_app_class($pay['pay_class'],'pay');
            $config = $data;
            $config['data'] = [
                "url"=> ["val"=>$pay['url']],
                "pid"=> ["val"=>$pay['bank_name']],
                "key"=> ["val"=>$pay['bank_user']],
                "type"=> ["val"=>$pay['bank_no']],
            ];
            $config['ReturnUrl']  = G_WEB_PATH.'/index.php/pay/'.$pay['pay_class'].'_url/qiantai/houtai_usdt';	//前台回调
            $config['NotifyUrl']  =G_WEB_PATH.'/index.php/pay/'.$pay['pay_class'].'_url/houtai_usdt';		//后台回调
            $config['u_id'] =_cfg('pay_prefix')."_".$uid;
            $config['shouname'] = _cfg('web_name');						//收款方
            $config['ip'] = _get_ip();
            $ok = $paydb->config($config);
            if($ok['pay_status_code']==1){
                $res = $this->db->Query($sql);
            }else{
                $res = false;
                $msg = $ok['msg'];
            }
        }
	}

	//充值
	public function addmoney()
	{
		parent::__construct();
		if (!isset($_POST['submit'])) {
			_message("正在返回充值页面...", WEB_PATH . '/member/member/home/userrecharge');
			exit;
		}
		if (!$this->userinfo) $this->HeaderLogin();
		$pay_type_bank = isset($_POST['pay_bank']) ? $_POST['pay_bank'] : false;
		$pay_id = isset($_POST['account']) ? $_POST['account'] : false;
		$money = intval($_POST['money']);
		// var_export($_POST);die;
		/* $uid = $this->userinfo['uid'];
		$pay = System::load_app_class('pay', 'pay');
		$pay->pay_type_bank = $pay_type_bank;
		$ok = $pay->init($uid, $pay_id, 'addmoney_record', $money);
		if ($ok === 'not_pay') {
			_message("未选择支付平台");
		}
		echo "aa"; */

		$pay_input = $_POST;
        $userinfo = $this->userinfo;
        $uid = $userinfo['uid'];
        $payment = $this->db->GetOne ( "select * from `@#_pay` where `pay_id` = ".$pay_id );
        if (!$payment) {
            $this->jsonData->errCode('','对不起，没有您所选择的支付方式！');
            // $this->jsonData->errCode('',$this->getLgMsg("cart_addmoney_nopayment","controller_cart"));
        }

        $coupon_id = safe_replace($_GET['coupon_id'])?:safe_replace($_POST['coupon_id']); // 
        if($coupon_id){
            $coupon = $this->db->GetOne("SELECT * FROM `@#_member_coupon` WHERE `id` = $coupon_id and `uid` = $uid");
            if(!$coupon){
                $this->jsonData->errCode('',"对不起，优惠券未找到！");
            }else{
                //已过期
                if($coupon['end_time']<time()){
                    $this->jsonData->errCode('',"对不起，优惠券已过期！");
                }
                // 已使用
                if($coupon['used']!=1){
                    $this->jsonData->errCode('',"对不起，优惠券已使用！");
                }
                //类型不对
                if($coupon['model']!=1){
                    $this->jsonData->errCode('',"对不起，该优惠券不适用于此充值方式！");
                }
            }
        }else{
            $coupon = false;
        }
        $paymentarr = @unserialize($payment['pay_monerange']);

        if ($money > $paymentarr['range']['max']){
            $max_money = $paymentarr['range']['min'];
            $this->jsonData->errCode('',"充值金额必须小于或等于 $max_money 元");
        }

        if ( $money < $paymentarr['range']['min']){
            $min_money = $paymentarr['range']['min'];
			_messageerror("充值金额必须大于或等于 $min_money 元",WEB_PATH . '/member/member/home/userrecharge');
            // $this->jsonData->errCode('',"充值金额必须大于或等于 $min_money 元");
            // $this->jsonData->errCode('',$this->getLgMsg("cart_addmoney_moneygre","controller_cart"));
        }
		$pay_type_bank = $payment ['pay_class'];
        $pay_type_id = $pay_id;
        $pay = System::load_app_class ( 'pay', 'pay' );
        $pay->pay_type_bank = $pay_type_bank;
        //优惠券
        if($coupon){
            if($coupon['type']==1){
                $real_money = bcsub($money,$coupon['money'],2);
            }else{
                $discount = $coupon['discount']>1?$coupon['discount']/100:$coupon['discount'];
                $real_money = bcmul($money,$discount,2);
            }
        }else{
            // 折扣
            $discountInfo = $this->getConfig('recharge_discount');
            if(!$discountInfo){
                $discount = 1;
            }
            $discount = unserialize($discount);
            if($discount['status'] != 1){
                $discount = 1;
            }else{
                $info = $this->db->GetOne("SELECT * FROM `@#_recharge_discount` where uid = $uid");
                if($info){
                    $discount = $info['discount']/100;
                }else{
                    $discount = 1;
                }
            }
            $real_money = bcmul($money,$discount,2);
        }
        $pay->pay_input_set($pay_input);
        $ok = $pay->init ( $uid, $pay_type_id, 'addmoney_record', $money, $userinfo['level'],$real_money,$coupon_id);
        if($ok['pay_status_code']==1){
            $sql = Assemblydata(['used'=>2],"member_coupon","up",[["id",$coupon_id]]);
            $this->db->Query($sql);
        }
        exit;
	}
	//加入购物车异步获取信息
	public function Fastpay()
	{
		if ($_GET['shopid']) {
			$id = $_GET['shopid'];
			$shoplist = $this->db->GetOne("SELECT zongrenshu,canyurenshu,yunjiage as price, title as cg_title FROM `@#_shoplist` where `id` = $id");
			//用户账户金额
			$uid = safe_replace(_encrypt(_getcookie('uid'), 'DECODE'));
			if (empty($uid)) {
				$shoplist['umoney'] = '未登录';
				$shoplist['ustatus'] = '0';
			} else {
				$money = $this->db->GetOne("SELECT `money` FROM `@#_member` where `uid` = $uid");
				$shoplist['umoney'] = $money['money'];
				$shoplist['ustatus'] = '1';
			}
			$shoplist['cg_title'] = _unhtmtocode($shoplist['cg_title']);
			echo json_encode($shoplist);
		}
	}

	//快速异步支付信息
	public function Fastpaysubmit()
	{
		parent::__construct();
		if (!$this->userinfo) $this->HeaderLogin();
		$uid = $this->userinfo['uid'];
		$pay_checkbox = false;
		$pay_type_bank = false;
		$pay_type_id = false;
		$var = $_POST;
		// 组合购物车数组
		$arr['shenyu'] = $var['shenyu'];
		$shopnum = $var['num'];
		$arr['money'] = $var['money'];
		$shopid = $var['shopid'];
		/************start*************/
		$pay = System::load_app_class('pay', 'pay');
		$rs  = $pay->pay_user_go_shop($uid, $shopid, $shopnum);
		if ($rs) {	//成功
			$message = array('error' => '您已经支付成功');
			echo json_encode($message);
			exit;
		}
	}
}

