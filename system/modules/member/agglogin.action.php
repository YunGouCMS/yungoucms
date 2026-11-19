<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');

// System::load_app_class('base',null,'no');
// System::load_app_fun('user','go');

// System::load_app_fun('my','go');

// System::load_sys_fun('send');
System::load_app_class('returnData',"app");

require "user.action.php";
class agglogin extends user {

	public $db;
	public $jsonData;

	public function __construct(){	
		parent::__construct();
		$this->db = System::load_sys_class('model');
		$this->jsonData = new returnData();
	}

	public function test(){
		echo "aaaaaaaaaaaa";
	
	}

	/**
     * 聚合登录
     * 
     * 
     */
	public function aggregationLogin(){
        $type = $_POST['type'];
        $web=System::load_sys_config('system');
        $appId = $web['beichen_app_id'];
        $appKey = $web['beichen_app_key'];
        $redirect_url = WEB_PATH."/member/agglogin/aggreagtionNotify/".$this->uId;
        $url = "https://u.beichenwl.cn/connect.php?act=login&appid={$appId}&appkey={$appKey}&type={$type}&redirect_uri={$redirect_url}";
        $res = getCurl($url);
		// if($res['code']==0){
		// 	$header_url = $res['url'];
		// 	if(isset($res['qrcode'])){
		// 		$header_url = $res['qrcode'];
		// 	}
		// 	header('Location: '.$header_url);
		// }
        $this->jsonData->success('', '', json_decode($res,true)); // 
     }
     public function aggreagtionNotify(){
        $type = $_GET["type"];
        $code = $_GET["code"];
        $res = $this->aggreagtionInfo( $type,$code);
        if($res['code']!=0){
            header('Location: '.WEB_PATH.'/login/'.$res['msg']);
        }
        $social_uid = $res['social_uid'];

        $re = key($_GET);
        $re = explode('/', $re);
        $uid = array_pop($re);
        /* if(count($re)==5 && $uid){
            $uid = array_pop($re);
            //登录状态直接绑定
            $this->aggBind($type,$social_uid,$res,$uid);
            header('Location: '.G_WEB_PATH.'/index/pages/users/binding/index');
            exit;
        } */
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$social_uid' and `type` = '$type'");
        if($member_auth){
            $uid = $member_auth["uid"];
            $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
            if(!$mem){

                //没有绑定账号
                //跳转到绑定账号页面
                header('Location: '.G_WEB_PATH.'/login_bind/'.$type.'/'.$social_uid);
                exit();
            }
            //账号存在
            $this->getLoginInfo($mem,$type);
        }else{
            // 第三方账号不存在
            $auth_data['auth_name'] = $res['nickname'];
            $auth_data['auth_img'] = $res['faceimg'];
            $auth_data['auth_email'] = '';
            $auth_data['auth_mobile'] = '';
            // 创建账号
            $res = $this->authCreateUser($social_uid, $type, $auth_data);
            //跳转到绑定账号页面
			header('Location: '.G_WEB_PATH.'/login_bind/'.$type.'/'.$social_uid);
        }
     }

     public function aggBind($type='',$social_uid,$info,$uid){
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$social_uid' and `type` = '$type'");
        if(!$member_auth){
            $auth_data['auth_name'] = $info['nickname'];
            $auth_data['auth_img'] = $info['faceimg'];
            $auth_data['auth_email'] = '';
            $auth_data['auth_mobile'] = '';
            $res = $this->authCreateUser($social_uid, $type, $auth_data);
        }elseif($member_auth['uid']!=0){
            return true;
        }
        $sql = Assemblydata(['uid'=>$uid,'auth_status'=>1],'member_auth','up',[['auth_id',$social_uid],['type',$type]]);
        $this->db->query($sql);
        return true;
     }
     /**
      * 获取用户信息
		参数名	参数类型	参数说明	参数示例
		code	int	返回状态码	0为成功，2为未完成登录，其它值为失败
		msg		string	返回信息	返回错误时的说明
		type	string	登录方式	qq
		social_uid	string	第三方登录UID	AD3F5033279C8187CBCBB29235D5F827
		access_token	string	第三方登录token	89DC9691E274D6B596FFCB8D43368234
		faceimg	string	用户头像	https://thirdqq.qlogo.cn/g?......
		nickname	string	用户昵称	消失的彩虹海
		gender	string	用户性别	男
		location	string	用户所在地	XXXXX市(仅限支付宝/微信返回)
		ip	string	用户登录IP	1.12.3.40
      * @return 
      */
     public function aggreagtionInfo($type,$code='',$social_uid=''){
        if(empty($code) && empty($social_uid)){
            return [];
        }
        $web=System::load_sys_config('system');
        $appId = $web['beichen_app_id'];
        $appKey = $web['beichen_app_key'];
        $url = "https://u.beichenwl.cn/connect.php?act=callback&appid={$appId}&appkey={$appKey}&type={$type}";
        if($code){
            $url .= "&code=".$code;
        }
        if($social_uid){
            $url .= "&social_uid=".$social_uid;
        }
        $res = getCurl($url);
        return json_decode($res,true);
     }

	/**
     * 一为登录
     */
    public function yiwei_login(){
        $type = safe_replace($_POST['type']);
        if(!$type){
            $this->jsonData->error('', $this->lgs['login_type_not'] ?: ''); // 登录方式不能为空
        }
        $web=System::load_sys_config('system');
        $appId = $web['yiwei_app_id'];
        $appKey = $web['yiwei_app_key'];
        $redirect_url = WEB_PATH."/member/agglogin/yiweiNotify/".$this->uId;
        $url = "https://iologin.cc/connect.php?act=login&appid={$appId}&appkey={$appKey}&type={$type}&redirect_uri={$redirect_url}";
        $res = getCurl($url);
        $res = json_decode($res,true);
        $this->jsonData->success('', '', $res); //
    }

    /**
     * 一为登录回调
     */
    public function yiweiNotify(){
        $type = $_GET["type"];
        $code = $_GET["code"];
        $web=System::load_sys_config('system');
        $appId = $web['yiwei_app_id'];
        $appKey = $web['yiwei_app_key'];
        $url = "https://iologin.cc/connect.php?act=callback&appid={$appId}&appkey={$appKey}&type={$type}&code={$code}";
        $res = getCurl($url);
        $res = json_decode($res,true);
        if($res['code']!=0){
			// /go/index/init
            header('Location: '.G_WEB_PATH.'/login&msg='.$res['msg']);
        }
        $social_uid = $res['social_uid'];

        $re = key($_GET);
        $re = explode('/', $re);
        $uid = array_pop($re);
        if(count($re)==5 && $uid){
            $uid = array_pop($re);
            //登录状态直接绑定
            $this->aggBind($type,$social_uid,$res,$uid);
			header('Location: '.G_WEB_PATH.'/login_bind/'.$type.'/'.$social_uid);
            exit;
        }
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$social_uid' and `type` = '$type'");
        if($member_auth){
            $uid = $member_auth["uid"];
            $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
            if(!$mem){
                //没有绑定账号
                //跳转到绑定账号页面
                header('Location: '.G_WEB_PATH.'/index/pages/users/validate/index?auth_id='.$social_uid.'&type='.$type);
                exit();
            }
            //账号存在
            $data = $this->getLoginInfo($mem,$type);
            header('Location: '.G_WEB_PATH.'/index/pages/users/validate/index?token='.$data['token'].'&uid='.$mem['uid']);
        }else{
            // 第三方账号不存在
            $auth_data['auth_name'] = $res['nickname'];
            $auth_data['auth_img'] = $res['faceimg'];
            $auth_data['auth_email'] = '';
            $auth_data['auth_mobile'] = '';
            // 创建账号
            $res = $this->authCreateUser($social_uid, $type, $auth_data);
            //跳转到绑定账号页面
			header('Location: '.G_WEB_PATH.'/login_bind/'.$type.'/'.$social_uid);
        }
    }

	    // 第三方授权 创建授权信息
		public function authCreateUser($auth_id, $auth_type, $auth_data = [])
		{
			$user_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$auth_id' and `type` = '$auth_type'");
			if ($user_auth) {
				$data = array('user_status' => 1, 'auth_id' => $user_auth['auth_id'], 'auth_type' => $auth_type);
				return ["code" => 1, "msg" => "ok", "data" => $data];
			} else {
				$img = 'photo/member.jpg';
				$auth_time = time();
				$auth_status = 0;
				// 昵称 邮箱 电话
				$auth_name = $auth_data['auth_name'] ?: 'y' . $auth_id;
				$auth_email = $auth_data['auth_email'] ?: '';
				$auth_mobile = $auth_data['auth_mobile'] ?: '';
				// 头像下载
				$auth_img = $auth_data['auth_img'];
				if ($auth_img) {
					$dir = 'statics/uploads/touimg/' . date('Ymd');
					if (!is_dir($dir)) {
						mkdir($dir, 0755, true);
					}
					$fileName = $dir . '/' . $auth_id . '_avatar.png';
					$img_url = 'touimg/' . date('Ymd') . '/' . $auth_id . '_avatar.png';
					$ch = curl_init($auth_img);
					$fp = fopen($fileName, "wb");
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_exec($ch);
					curl_close($ch);
					fclose($fp);
	
					$auth_img = $img_url;
				} else {
					$auth_img = $img;
				}
	
				if (!empty($auth_id)) {
					$sql_ma = "INSERT INTO `@#_member_auth` (`type`, `auth_id`, `auth_img`, `auth_name`, `auth_email`, `auth_mobile`, `auth_time`, `auth_status`) value ('$auth_type','$auth_id','$auth_img','$auth_name','$auth_email','$auth_mobile','$auth_time','$auth_status')";
				} else {
					return ["code" => 2, "msg" => $this->lgs['registration_failed'] ?: ''];
				}
	
				$sq_1 = $this->db->Query($sql_ma);
				if ($sq_1) {
					$data = array('user_status' => 1, 'auth_id' => $auth_id, 'auth_type' => $auth_type);
					return ["code" => 1, "msg" => "ok", "data" => $data];
				} else {
					return ["code" => 2, "msg" => $this->lgs['registration_failed'] ?: ''];
				}
			}
		}

}//

?>