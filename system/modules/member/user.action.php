<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');

// System::load_app_class('base',null,'no');
System::load_app_class('init','go','no');

System::load_app_fun('user','go');

System::load_app_fun('my','go');

System::load_sys_fun('send');
System::load_app_class('returnData',"app");

class user extends init {
	public $nowUserLanguage;
	public $where_language;
	public function __construct(){	

		parent::__construct();
		$this->nowUserLanguage = _getcookie('language')?:_cfg('web_language');
		$this->where_language = " language in('{$this->nowUserLanguage}','',null)";
		$this->db = System::load_sys_class("model");
		$this->jsonData = new returnData();
	}

	public function cook_end(){

		_setcookie("uid","",time()-3600);

		_setcookie("ushell","",time()-3600);		

		_message("退出成功",WEB_PATH);

	}

	public function login(){	
		$user = $this->userinfo;
		$param = get_web_param();
		$params = explode('/', $param);
		if($user){
			header("Location:".G_WEB_PATH);exit;
		}else if(!isset($params[3]) || $params[3] == ''){
			// 不再根据 Referer 重定向到 /login/base64(referer)，避免点击顶部「登录」后地址栏变成一长串。
			// 回跳地址改由登录表单的 hidurl 或 URL 中的 segment(4) 提供，登录成功后统一在下方 _message 处处理。
		}

		

		if(isset($_POST['submit'])){		

			$username=$_POST['username'];

			$password=md5($_POST['password']);

			$logintype='';

			if(strpos($username,'@')===false){

				//手机				

				$logintype='mobile';

				/* if(!_checkmobile($username)){

					_message("手机格式不正确!");

				} */				
				$member = $this->db->GetOne("select * from `@#_member` where CONCAT(IFNULL(`area_code`,''),`".$logintype."`) = :un", array('un'=>$username));
				if(!$member){
					$area_code = $this->getPhoneAreaCode($username);
					$phone = str_replace($area_code,'',$username);
					$member = $this->db->GetOne("select * from `@#_member` where `".$logintype."` = :un", array('un'=>$phone));
					if(!$member){
						$member = $this->db->GetOne("select * from `@#_member` where `".$logintype."` = :un", array('un'=>$username));
					}else{
						$username = $phone;
					}
				}
			}else{
				//邮箱
				$logintype='email';
				if(!_checkemail($username)){
					_message("邮箱格式不正确!");
				}
				$member = $this->db->GetOne("select * from `@#_member` where `".$logintype."` = :un", array('un'=>$username));
			}
			if(!$member){
				_message("帐号不存在!");
			}
			if($member['password'] != $password){
				_message("密码错误!");
			}		

			$check=$logintype.'code';

			if($member[$check] != 1){

				$strcode=_encrypt($member['email']);

				_message("帐号未认证",WEB_PATH."/member/user/".$logintype."check/"._encrypt($member[$logintype]));

			}	

					

			if(!is_array($member)){

				_message("帐号或密码错误",NULL,3);

			}else{

				$user_ip = _get_ip_dizhi();

				$this->db->Query("UPDATE `@#_member` SET `user_ip` = :ip where `uid` = :uid", array('ip'=>$user_ip, 'uid'=>$member['uid']));

				_setcookie("uid",_encrypt($member['uid']),60*60*24*7);			

				_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);	

			

			}			

			_message("登录成功",$this->_login_return_url(),2);

				

		}	
		$csrf_token = function_exists('csrf_token_get') ? csrf_token_get() : '';
		include templates("user","login");

		

	}

	public function getLoginInfo($member,$type=''){
		$user_ip = _get_ip_dizhi();
		$this->db->GetOne("UPDATE `@#_member` SET `user_ip` = '$user_ip' where `uid` = '$member[uid]'");
		//记录登录日志

		$this->login_log($member['uid'],$type);
		//领取登录奖励
		$this->loginReward($member['uid']);
		
		_setcookie("uid",_encrypt($member['uid']),60*60*24*7);			
		_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
		_message("登录成功",$this->_login_return_url(),2);
	}

	/**
	 * 登录成功后的回跳地址：优先 URL 中的 base64(return_url)，否则用表单 hidurl，且仅允许站内地址。
	 */
	private function _login_return_url(){
		$raw = $this->segment(4) ? base64_decode($this->segment(4)) : (isset($_POST['hidurl']) ? trim((string)$_POST['hidurl']) : '');
		if ($raw === '') return G_WEB_PATH;
		$host = parse_url(G_WEB_PATH, PHP_URL_HOST);
		$url_host = parse_url($raw, PHP_URL_HOST);
		if ($host !== null && $url_host !== null && strtolower($url_host) === strtolower($host)) {
			return $raw;
		}
		return G_WEB_PATH;
	}

	/**
     * 登陆日志
     * @param $uid
     * @return bool
     */
    private function login_log($uid,$login_type='')
    {
        if (empty($uid)) return false;
        $userinfo  = $this->db->GetOne("SELECT uid from `@#_member` WHERE uid = :uid", array('uid'=>$uid));
        if (!$userinfo) return false;
        $delnum = 10;
        $limit = $delnum + 1;
        $count = $this->db->GetList("SELECT id FROM `@#_member_login_log` WHERE user_id = :uid and type = '1' ORDER BY `id` DESC LIMIT " . intval($limit), array('type'=>1,'key'=>''), array('uid'=>$uid));
        if (is_array($count) && count($count) > $delnum) {
            $key = $delnum - 1;
            $delid = (int)$count[$key]['id'];
            $this->db->Query("DELETE FROM `@#_member_login_log` WHERE `id` < :delid and `user_id` = :uid", array('delid'=>$delid, 'uid'=>$uid));
        }
        $ip = _get_ip();

        $key = _cfg('tx_map_key');
        $data = file_get_contents("https://apis.map.qq.com/ws/location/v1/ip?ip=$ip&key=$key");
        $arr = json_decode($data, true);
        if ($arr && $arr['code'] == 0) {
            $data = $arr['result'];
            $i['ip']        = $data['ip'] ?: '未知IP';
            $i['country']   = $data['ad_info']['nation'] ?: '';
            $i['region']    = $data['ad_info']['province'] ?: '';
            $i['city']      = $data['ad_info']['city'] ?: '';
            $i['district']  = $data['ad_info']['district'] ?: '';
            $i['lat']       = $data['location']['lat'] ?: '';
            $i['lng']       = $data['location']['lng'] ?: '';
            $i['isp']       = $data['ad_info']['isp'] ?: '';
            $i['login_time'] = time();
            $i['user_id']   = $uid;
            $i['login_type']   = $login_type;
        } else {
            $i['ip']        = $ip ?: '未知IP';
            $i['country']   = '';
            $i['region']    = '';
            $i['city']      = '';
            $i['isp']       = '';
            $i['district']  = '';
            $i['lat']       = '';
            $i['lng']       = '';
            $i['login_time'] = time();
            $i['user_id']   = $uid;
            $i['login_type']   = $login_type;
        }
        $res = $this->db->Query("INSERT INTO `@#_member_login_log`(`user_id`, `ip`, `login_time`, `country`, `region`, `city`, `isp`,`district`,`lat`,`lng`,`login_type`) VALUES (:uid,:ip,:lt,:country,:region,:city,:isp,:district,:lat,:lng,:ltype)", array('uid'=>$i['user_id'],'ip'=>$i['ip'],'lt'=>$i['login_time'],'country'=>$i['country'],'region'=>$i['region'],'city'=>$i['city'],'isp'=>$i['isp'],'district'=>$i['district'],'lat'=>$i['lat'],'lng'=>$i['lng'],'ltype'=>$i['login_type']));
        if ($res) {
            return true;
        }
        return false;
    }

	/*登录进行重置奖励领取*/
	public function loginReward($uid)
	{
		if(!$uid) return false;
		//登录成功进行充值累计奖励领取,更新ip信息
		$ip = _get_ip();
		$ip_region = _get_ip_region(3);
		$res =$this->db->getOne("SELECT * FROM `@#_member_ip` WHERE `uid`=$uid");
		if($res){
			$this->db->Query("UPDATE `@#_member_ip` SET `last_ip`=`ip`,`last_ip_region`=`ip_region` , `ip` = '$ip' ,`ip_region` = '$ip_region' where (`uid` = '$uid')");
		}else{
			$this->db->Query("INSERT INTO `@#_member_ip` (`ip`,`ip_region`,`uid`) values ('$ip','$ip_region','$uid')");
		}
		$config = include_once(dirname(dirname(__FILE__)) . "/member/lib/userrecharge.ini.php");
		if ($config['isOpen'] && $config['d_money']) {
			$d_money = $config['d_money'];
			$info = $this->db->Getone("SELECT * FROM `@#_recharge_money` where uid='$uid' and status =1 ");
			if ($info) {
				$time = time();
				$old_time = $info['time'];
				$Days = round(($time - $old_time) / 3600 / 24); //相差的天数
				$ee = array_flip($d_money);
				$maxday = end($ee); //获取最大奖励天数
				$ok = 0;
				//判断是否过期
				if ($Days <= $maxday) {
					$ok = 1;
				} else {
					$this->db->Query("UPDATE `@#_recharge_money` SET `status` =0 where `uid` = '$uid' and status =1 ");
				}
				$receive = $this->db->Getone("SELECT * FROM `@#_recharge_money` where `uid` = '$uid' and status =1 ");
				if ($receive['day'] < $Days) { //判断当天是否领取
					if ($ok) {
						foreach ($d_money as $key => $value) {
							if ($key == $Days) {
								$content = $this->lgs['recharge_day'] . $Days . $this->lgs['day'] . $this->lgs['reward'];
								$this->db->Query("UPDATE `@#_recharge_money` SET `day` ='$Days' where `uid` = '$uid' and status =1");
								$this->db->Query("UPDATE `@#_member` SET `money` = `money` + $value where (`uid` = '$uid')");
								$this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$value', '$time')");
							}
						}
					}
				}
			}
		}
		//更新抽奖信息
		$today = get_today_time();
		// $zp_config = $this->getConfig("zp_set");
		$zp_config = _cfg("zp_set");
		if($zp_config){
			$zp_config = unserialize($zp_config);
			$winning = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = '$uid'");
			$time = time();
			if($winning){
				$num = $zp_config['number']['day_num'];
			}else{
				$num = $zp_config['number']['first_num'];
			}
			if($zp_config['number']['is_num_add'] && $winning){
				$num += $winning['num'];
			}
			if($winning && $winning['add_time'] <$today[0]){
				$sql = "UPDATE `@#_winning_probability` SET `num` = $num,`add_time` = $time  where (`uid` = '$uid')";
			}elseif(!$winning){
				$data = [
					'uid' => $uid,
					'num' => $num,
					'add_time' => $time,
				];
				$sql = Assemblydata($data,"winning_probability");
			}
			$this->db->Query($sql);
		}
	}

	/**
      * 登录方式
      */
	  public function get_login_type(){
        $login_type = $this->getConfig('login_type');
        if($login_type){
            $login_type = unserialize($login_type);
        }
        $arr = [
            "验证码登录",
            "帐号密码登录",
            "邮箱登录",
            "第三方快捷登录"
        ];
        $types = [];
        $agg = false;
        foreach($login_type as $i){
            $types[] = ["key"=>$i,"name"=>$arr[$i-1]];
            if($i==4){
                $agg = true;
            }
        }
        if($agg){
            $index = count($types)-1;
            if($this->getConfig('beichen_app_status')!=2){
                $aggLoginType = $this->getAggLoginType(1);
                $types[$index]['child'][] = ["name"=>'aggLoginType' ,"value"=> $aggLoginType];
            }
            if($this->getConfig('yiwei_app_status')!=2){
                $yiweiLoginType = $this->get_yiwei_login_type(1);
                $types[$index]['child'][] = ["name"=>'yiweiLoginType' ,"value"=> $yiweiLoginType];
            }
        }
        $data = [
            "login_type"=>$types,
            "aggLoginType"=>$aggLoginType,
            "yiweiLoginType"=>$yiweiLoginType?:null
        ];
        $this->jsonData->success('', '', $data); // 登录方式
    }

     public function getAggLoginType($mode=0){
        $res = $this->db->getOne("select * from `@#_config` where name='beichen_login_type'");
        $type = @unserialize($res["value"]);
        $data = [];
        foreach ($type[$this->nowUserLanguage] as $key => $value) {
            if(isset($value['value'])){
                $value['img'] = G_UPLOAD_PATH."/beichen_login/".$value['img'];
                $data[] = $value;
            }
        }
        if($mode){
            return $data;
        }
        $this->jsonData->success('', '', $data); // 
     }

	/**
     * 一为登录方式
     */
    public function get_yiwei_login_type($mode = 0){
        $type = $this->getConfig('yiwei_login_type');
        $type = @unserialize($type);
        $data = [];
        foreach ($type[$this->nowUserLanguage] as $key => $value) {
            if(isset($value['value'])){
                $value['img'] = G_UPLOAD_PATH."/beichen_login/".$value['img'];
                $data[] = $value;
            }
        }
        if($mode){
            return $data;
        }
        $this->jsonData->success('', '', $data); //
    }

	/**
	 * 第三方账号绑定
	 * @return void
	 */
	public function authBindMobile(){
		// $auth_type = $this->segment(4);
		// $auth_id = $this->segment(5);

		if(isset($_POST['submit'])){
			// $auth_id = $_POST['authId'];
			// $auth_type = $_POST['authType'];
			$auth_type = $this->segment(4);
			$auth_id = $this->segment(5);
			$mobile = safe_replace($_POST['mobile']);
			$code = safe_replace($_POST['code']);
			if (empty($auth_id)) {
				_messageerror( '参数错误'); // 参数错误
				$this->jsonData->error('', '参数错误'); // 参数错误
			}
			if (empty($auth_type)) {
				_messageerror( '参数错误'); // 参数错误
				$this->jsonData->error('', '参数错误'); // 参数错误
			}
			if (empty($mobile)) {
				_messageerror( '参数错误'); // 参数错误
				$this->jsonData->error('', '参数错误'); // 参数错误
			}
			if (empty($code)) {
				_messageerror( '参数错误'); // 参数错误
				$this->jsonData->error('', '参数错误'); // 参数错误
			}
			// 查询授权用户是否存在
			$member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$auth_id' AND `type` = '$auth_type'");
			if (empty($member_auth)) {
				_messageerror( '请重新登录'); // 参数错误
				$this->jsonData->error('', '请重新登录'); // 未找到授权信息
			}
			if ($member_auth['auth_status'] == 1 && $member_auth['uid'] != 0) {
				$mem = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$member_auth[uid]'");
				if($mem){
					_messageerror( '已绑定'); // 参数错误
					$this->jsonData->error('', '已绑定'); // 已绑定
				}
			}
	
			// 验证用户验证码
			$codesql = "SELECT * FROM `@#_mobile_verify` WHERE `mobile` ='$mobile' AND `verify` ='$code' ";
			$iscode = $this->db->GetOne($codesql);
	
			if (!$iscode) {
				// $this->jsonData->error('', '验证码错误'); // 验证码错误
			}
			$delcodeid = $iscode['id'];
			$delcode = "DELETE FROM `@#_mobile_verify` WHERE `id` = '$delcodeid'";
			$this->db->query($delcode); // 删除验证码
			// 查询手机号是否注册
			$member = $this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
			if ($member) {
				// 存在 进行绑定
				$uid = $member['uid'];
			} else {
				// 不存在进注册
				$area_code = $this->getPhoneAreaCode($mobile);
				$mobile = str_replace($area_code, '', $mobile);
				$m = $this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
				$password = getRandomstr(6);
				$password = md5($password);
				// 注册赠送金额
				if ($m['money'] > 0) {
					$money = $m['money'];
				} else {
					$money = 0;
				}
				// $top_uid = $this->get_top_uid($invitationUid); // 顶级ID
				$signInfo = [
					"img" => $member_auth['auth_img'],
					"username" => $member_auth['auth_name'],
					"mobile"=>$mobile,
					"password"=>$password,
					"yaoqing"=>0,
					"money"=>$money,
					"top_uid"=>0,
					"time"=>time(),
					'area_code'     =>  $area_code,
				];
				$res = $this->signIn($signInfo);
				if ($res['code'] == 2) {
					$this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 注册失败
				}
				$member = $this->db->GetOne("select * from `@#_member` where `mobile`='$mobile'");
				$uid = $member['uid'];
				$this->Registerfree($member);
			}
			$this->db->Query("UPDATE `@#_member_auth` SET `uid`='$uid', `auth_status`= 1 WHERE auth_id = '$auth_id' AND `type` = '$auth_type'");
			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);			
			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
			_message("登录成功", G_WEB_PATH.'/go',2);
		}
		include templates("user","login_bind");
	}

	public function signIn($data)
    {
        $username = hidtel($data['mobile']);
        $host = G_HTTP_HOST;
        $img = $this->getConfig("photo");
        $data['mobile'] = trim($data['mobile']);
        $info = [
            'username' => $data["username"]?:$username,
            'mobile' => $data['mobile'],
            'img' => $data['img']?:$img,
            'password' => md5($data['password']),
            'emailcode' => '',
            'mobilecode' => $data['code'],
            'yaoqing' => $data['yaoqing'],
            'top_uid' => $data['top_uid'],
            'money' => $data['money']?:0,
            'time' => time(),
            'host' => $host,
            'area_code' => $data['area_code'],
        ];
        $insereuserinfo = Assemblydata($info, 'member');
        $insereres = $this->db->query($insereuserinfo);
        if ($insereres) {
            $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$data[mobile]',''$data[codes]','$data[time]')");
            if($data['yaoqing']){
                //邀请好友奖励积分
                $uid = $this->db->insert_id();
                //奖励佣金
                // System::load_app_fun("pay","pay");
                // get_yongjin($uid,"f_visituser");
            }
            return ['code' => 1, 'msg' => $this->lgs['registration_ok'] ?: '']; // 注册成功
        }
        return ['code' => 2, 'msg' => $this->lgs['registration_failed'] ?: '']; // 注册失败
    }

	/*注册赠送相关*/
	private function Registerfree($userinfo)
	{
		$m = $this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
		$time = time();
		if ($m['money'] != 0) {
			$money = $m['money'];
			$type = 1;
			$pay = '账户';
			$content = $this->lgs['registration_give'] ?: '注册赠送';
			$uid = $userinfo['uid'];
			$sq_2 = "insert into `@#_member_rechange`(uid,type,pay,content,money,time,mode)values('$uid','$type','$pay','$content','$money','$time',1)";
			$this->db->Query($sq_2);
			$this->db->Query("update `@#_member` set money=money+$money where uid='$uid'");
			$sq_3 = "insert into `@#_member_account`(uid,type,pay,content,money,time)values('$uid','$type','$pay','$content','$money','$time')";
			$this->db->Query($sq_3);
		}
	}

	public function register(){

		$config_email = System::load_sys_config("email");

		$config_mobile = System::load_sys_config("mobile");

		

		if(isset($_POST['submit'])){			

			$name=isset($_POST['name']) ? $_POST['name'] : "";

			$userpassword=isset($_POST['userpassword']) ? $_POST['userpassword'] : "";

			$userpassword2=isset($_POST['userpassword2']) ? $_POST['userpassword2'] : "";

			

			if($name==null or $userpassword==null or $userpassword2==null){

				 _message("帐号密码不能为空",null,3);

			}
			// 获取手机区号
			if(strpos($name, '@') === false){
				$area_code = $this->getPhoneAreaCode($name);
				$name = str_replace($area_code, '', $name);
			}
			/* if(!(_checkmobile($name) or _checkemail($name))){

				_message("帐号不是手机或邮箱",null,3);

			} */

			if(strlen($userpassword)<6 || strlen($userpassword)>20){

				 _message("密码小于6位或大于20位",null,3);

			}

			if($userpassword!=$userpassword2){

				_message("两次密码不一致",null,3);

			}			

			$regtype=null;

			// if(_checkmobile($name)){
			if(strpos($name, '@') === false){

				$regtype='mobile';
				if($config_mobile['cfg_mobile_on']==0){
					_message("系统短信配置不正确!");
				}
				/* $cfg_mobile_type  = 'cfg_mobile_'.$config_mobile['cfg_mobile_on'];
				$config_mobile = $config_mobile[$cfg_mobile_type];
				if(empty($config_mobile['mid']) && empty($config_email['mpass'])){
					_message("系统短信配置不正确!");
				}	 */	
			}

			if(_checkemail($name)){

				$regtype='email';

				if(empty($config_email['user']) && empty($config_email['pass'])){

					_message("系统邮箱配置不正确!");

				}				

			}			

			if($regtype==null)_message("注册类型不正确!",null,3);

			// $member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `$regtype` = '$name' LIMIT 1");			
			$member = $this->db->GetOne("select * from `@#_member` where CONCAT(IFNULL(`area_code`,''),`".$regtype."`) = :nm", array('nm'=>$name));

			if(is_array($member)){

				_message("该账号已被注册!",WEB_PATH.'/register');

			}

			

			$time=time();

			$userpassword=md5($userpassword);

			$codetype=$regtype.'code';			

			$decode=_encrypt($this->segment(4),"DECODE");

			$decode = intval($decode);			
			$area_code_safe = isset($area_code) ? $area_code : '';

			//邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证（使用绑定防注入）
			if($this->db->Query("INSERT INTO `@#_member` (password,img,emailcode,mobilecode,reg_key,yaoqing,time,area_code) VALUES (:pw,'photo/member.jpg','-1','-1',:rk,:yq,:tm,:ac)", array('pw'=>$userpassword,'rk'=>$name,'yq'=>$decode,'tm'=>$time,'ac'=>$area_code_safe))){		

				$check_code  = serialize(array("name"=>$name,"time"=>$time));

				$check_code  = _encrypt($check_code,"ENCODE",'',3600*24);

				header("location:".WEB_PATH."/member/user/".$regtype."check"."/".$check_code);

				exit;

			}else{

				_message("注册失败!",WEB_PATH.'/register');

			}

		}

		$title="注册"._cfg("web_name");
		//注册协议
		$article = $this->db->GetOne("SELECT * FROM `@#_article` WHERE `keys` = 2 and {$this->where_language} order by language desc LIMIT 1");
		$article['content'] = replaceArticle($article['content']);
		$article['content'] = replaceArticle($article['content'],'#%webUrl%#');
		$csrf_token = function_exists('csrf_token_get') ? csrf_token_get() : '';
		include templates("user","register");

	}

	

	/* 用户注册邮箱验证 */

	public function emailcheck(){

	

		$title="邮箱验证 -"._cfg("web_name");

		$check_code = _encrypt($this->segment(4),"DECODE");

		$check_code = @unserialize($check_code);

		if(!$check_code || !isset($check_code['name']) || !isset($check_code['time'])){

			_message("参数不正确或者验证已过期!",WEB_PATH.'/register');

		}		

		

		

		$info=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `reg_key` = '$check_code[name]' and `time` = '$check_code[time]' LIMIT 1");

		if(!$info)_message("错误的来源!",WEB_PATH.'/register');

		$emailurl = explode("@",$info['reg_key']);

        $emailurl[1] == 'gmail.com'&& $emailurl[1] = 'google.com';

        $name = $info['reg_key'];

        $enname = $this->segment(4);

	

		$reg_message = '';

		if($info['emailcode']=='1')_message("恭喜您,验证成功!",WEB_PATH."/login");

		if($info['emailcode']=='-1'){			

			$reg_message = send_email_reg($info['reg_key'],$info['uid']);

		}

		include templates("user","emailcheck");

	}

	

	public function sendemail(){

		$check_code = _encrypt($this->segment(4),"DECODE");

		$check_code = @unserialize($check_code);

		if(!$check_code || !isset($check_code['name']) || !isset($check_code['time'])){

			_message("参数不正确或者验证已过期!",WEB_PATH.'/register');

		}		

		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `reg_key` = '$check_code[name]' and `time` = '$check_code[time]' LIMIT 1");

		if(!$member)_message("错误的来源!",WEB_PATH.'/register');

		

		if($member['emailcode']=='1')_message("邮箱已验证",WEB_PATH.'/member/home');

		$this->db->Query("UPDATE `@#_member` SET emailcode='-1' where `uid`='$member[uid]'");

		_message("正在重新发送...",WEB_PATH."/member/user/emailcheck/".$this->segment(4));	

		exit;

	}

	

	public function emailok(){	

	

		$check_code = _encrypt($this->segment(4),"DECODE");

		$check_code = @unserialize($check_code);

		

		if(!isset($check_code['email']) || !isset($check_code['code']) || !isset($check_code['time'])){

			_message("未知的来源!",WEB_PATH,'/register');

		}

				

		

		$sql_code = $check_code['code'].'|'.$check_code['time'];

		

		

		$member=$this->db->GetOne("select * from `@#_member` where `reg_key`='$check_code[email]' AND `emailcode`= '$sql_code' LIMIT 1");

		if(!$member)_message("未知的来源!",WEB_PATH,'/register');

		

		$timec=time() - $check_code['time'];			

		if($timec < (3600*24)){

				

				$title="邮件激活成功";

				$tiebu="完成注册";

				$success="邮件激活成功";					

				$fili_cfg = System::load_app_config("user_fufen");		

				if($member['yaoqing']){

							$time = time();

							$yaoqinguid = $member['yaoqing'];

							//福分			

							if($fili_cfg['f_visituser']){							

								$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$yaoqinguid','1','福分','邀请好友奖励','$fili_cfg[f_visituser]','$time')");

							}						

							$this->db->Query("UPDATE `@#_member` SET `score`=`score`+'$fili_cfg[f_visituser]',`jingyan`=`jingyan`+'$fili_cfg[z_visituser]' where uid='$yaoqinguid'");

				}

				$this->db->Query("UPDATE `@#_member` SET emailcode='1',email = '$member[reg_key]' where `uid`='$member[uid]'");

				include templates("user","emailok");

				

			}else{

					$title="邮箱验证失败";

					$tiebu="验证失败";

					$guoqi="对不起，验证码已过期或不正确！";

					$this->db->Query("UPDATE `@#_member` SET emailcode='-1' where `uid`='$member[uid]'");

					$name=_encrypt($member['reg_key']);

					include templates("user","emailok");

			}			

	}

	

	//重发手机验证码

	public function sendmobile(){

			$check_code = _encrypt($this->segment(4),"DECODE");

			$check_code = @unserialize($check_code);

			if(!$check_code || !isset($check_code['name']) || !isset($check_code['time'])){

				_message("参数不正确或者验证已过期!",WEB_PATH.'/register');

			}	

			$name = $check_code['name'];

		

			$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `reg_key` = '$check_code[name]' and `time` = '$check_code[time]' LIMIT 1");

			if(!$member)_message("参数不正确!");

			if($member['mobilecode']=='1'){_message("该账号验证成功,请直接登录！",WEB_PATH."/login");}	

			$checkcode=explode("|",$member['mobilecode']);

			$times=time()-$checkcode[1];		

			if($times > 120){

				$sendok = send_mobile_reg_code($member['reg_key'],$member['uid']);			

				if($sendok[0]!=1){

					_message("短信发送失败,代码:".$sendok[1]);exit;			

				}

				_message("正在重新发送...",WEB_PATH."/member/user/mobilecheck/".$this->segment(4));				

			}else{

				_message("重发时间间隔不能小于2分钟!",WEB_PATH."/member/user/mobilecheck/".$this->segment(4));

			}

		

	}

	public function mobilecheck(){

		$title="手机认证 - "._cfg("web_name");	

		$check_code = _encrypt($this->segment(4),"DECODE");

		$check_code = @unserialize($check_code);

		if(!$check_code || !isset($check_code['name']) || !isset($check_code['time'])){

			_message("参数不正确或者验证已过期!",WEB_PATH.'/register');

		}	

		$name = $check_code['name'];

		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `reg_key` = '$check_code[name]' and `time` = '$check_code[time]' LIMIT 1");

		if(!$member)_message("未知的来源!",WEB_PATH.'/register');		

		if($member['mobilecode'] == '1'){

			_message("该账号验证成功",WEB_PATH."/login");

		}

		

		if($member['mobilecode'] == '-1'){

			$sendok = send_mobile_reg_code($member['reg_key'],$member['uid']);		

			if($sendok[0]!=1){

					_message($sendok[1]);

			}

			header("location:".WEB_PATH."/member/user/mobilecheck/".$this->segment(4));

			exit;

		}

		

		if(isset($_POST['submit'])){

			$checkcodes=isset($_POST['checkcode']) ? $_POST['checkcode'] : _message("参数不正确!");

			if(strlen($checkcodes)!=6)_message("验证码输入不正确!");

			$usercode=explode("|",$member['mobilecode']);

			if($checkcodes!=$usercode[0])_message("验证码输入不正确!");

			

			$fili_cfg = System::load_app_config("user_fufen");

			if($member['yaoqing']){

				$time = time();

				$yaoqinguid = $member['yaoqing'];

				//福分、经验添加

				if($fili_cfg['f_visituser']){

					$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$yaoqinguid','1','福分','邀请好友奖励','$fili_cfg[f_visituser]','$time')");

				}				

				$this->db->Query("UPDATE `@#_member` SET `score`=`score`+'$fili_cfg[f_visituser]',`jingyan`=`jingyan`+'$fili_cfg[z_visituser]' where uid='$yaoqinguid'");

			}			
            $area_code = $this->getPhoneAreaCode($member["reg_key"]);
            $mobile = str_replace($area_code,'',$area_code);
			$check = $this->db->Query("UPDATE `@#_member` SET mobilecode='1',mobile='$mobile',area_code = $area_code where `uid`='$member[uid]'");	
			if($check){
			_message("验证成功",WEB_PATH."/login");
			}else{
			_message("验证失败",WEB_PATH."/login");
			}
		}

	

		$enname=substr($name,0,3).'****'.substr($name,7,10);

		$time=120;

		$namestr = $this->segment(4);

		include templates("user","mobilecheck");

	}

	public function codeCheck(){

		if(empty($_POST['param'])){

			$message['status']='x';$message['info']="验证码输入错误";

			echo json_encode($message);

			exit;

		}

		if(md5(strtolower($_POST['param']))!=_getcookie('checkcode')){

			$message['status']='x';$message['info']="验证码输入错误";

			echo json_encode($message);

			exit;

		}else{

			$message['status']='y';$message['info']="验证码正确";

			echo json_encode($message);

			exit;

		}

	}

	/**
     * 判断手机号区号
     * @param string $phone 手机号
     * @return string
     */
    public function getPhoneAreaCode($phone){
        //依次获取前1位2位3位4位
        $area[] = substr($phone,0,1);
        $area[] = substr($phone,0,2);
        $area[] = substr($phone,0,3);
        $area[] = substr($phone,0,4);
        //判断区号
        /* $area_codes = $this->redis->redisGet('area_codes');
        if($area_codes['data']){
            $area_codes = json_decode($area_codes['data'],true);
        }else{
            $res = $this->db->GetList("select area_code from `@#_language`");
            $area_codes = array_column($res,"area_code");
            $area_codes = array_unique($area_codes);
            $area_codes = array_values($area_codes);
            $this->redis->redisSet('area_codes',json_encode($area_codes),86400);
        } */
		// $this->redis->redisSet('area_codes',json_encode($area_codes),86400);
		$res = $this->db->GetList("select area_code from `@#_language`");
		$area_codes = array_column($res,"area_code");
		$area_codes = array_unique($area_codes);
		$area_codes = array_values($area_codes);
        foreach($area as $vl){
            if(in_array($vl,$area_codes)){
                return $vl;
            }
        }
        return '';
    }

}//

?>