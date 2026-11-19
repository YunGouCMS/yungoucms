<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
// System::load_app_class('base', 'app', 'no');

require "user.action.php";
class ks extends user
{

    private $AppId = '';
    private $AppSecret = '';

    public $expirationdate = 1;

    public $token = '';
    public $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        $web = System::load_sys_config('system');
        if (isset($web['ks_app_id'])) {
            $this->AppId = $web['ks_app_id'];
        }
        if (isset($web['ks_app_secret'])) {
            $this->AppSecret = $web['ks_app_secret'];
        }
    }

    //获取openid、session_key
    public function getOpenid($type = 0)
    {
        $js_code = $_POST['code'];
        if(!$js_code){$js_code = $_POST['js_code'];}
        $str = 'app_id=' . $this->AppId . '&app_secret=' . $this->AppSecret . '&js_code=' . $js_code;
        $url = 'https://open.kuaishou.com/oauth2/mp/code2session?' . $str;
        $res = $this->postCurl($url);
        if ($type) {
            return $res;
        }
        $this->jsonData->success('', '', $res);
    }

    public function getToken()
    {
        $appSecret = $this->AppSecret;
        $appId = $this->AppId;
        $url = "https://open.kuaishou.com/oauth2/access_token?app_secret=$appSecret&grant_type=client_credentials&app_id=$appId";
        $res = $this->postCurl($url);
        return $res;
    }

    public function login()
    {
        $res = $this->getOpenid(1);
        // $res = [
        //     "result"=> 1,
        //     "open_id" =>"ks_test5"
        // ];
        if ($res['result'] == 1) {
            $openid = $res['open_id'];
            // $session_key = $res['session_key'];
            /* if(strlen($openid) != strlen("f1b44f6c41794dfe14bf5725d60566d4")){
                $this->jsonData->error('', "open_id 非法", $res);
                exit;
            } */
            //是否存在
            $login_type = "ks";
            $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$openid' and `type` = '$login_type'");
            if ($member_auth) {
                $uid = $member_auth["uid"];
                $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
                if ($mem) {
                    $data = $this->getLoginInfo($mem, $login_type);
                    $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
                }
            }
            $this->jsonData->success('201', "未注册", $res); // 
        }
        $this->jsonData->error('', $this->lgs['login_error'], $res);
    }

    public function register()
    {
        $encrypted_phone = htmlspecialchars($_POST['encrypted_phone']);
        $iv = htmlspecialchars($_POST['iv']);
        $sessionKey = htmlspecialchars($_POST['session_key']);
        $appid = $this->AppId;
        $res = $this->decryptData($encrypted_phone,$iv,$sessionKey,$appid);
        if($res['code'] == 200){
            $mobile = $res['mobile'];
        }
        $openid = $_POST['open_id'];
        $auth_data['auth_name'] = $_POST['username']?: "";
        $auth_data['auth_img'] = $_POST['head_img']?: "";
        $auth_data['auth_email'] = '';
        $auth_data['auth_mobile'] = $mobile?:'';
        $auth_data['auth_email'] = '';
        if(strlen($openid) != 32){
            $this->jsonData->error('', "open_id 非法");
            exit;
        }
        //是否存在
        $login_type = "ks";
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$openid' and `type` = '$login_type'");
        $mem = false;
        if ($member_auth) {
            $uid = $member_auth["uid"];
            $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
            if ($mem) {
                $data = $this->getLoginInfo($mem, $login_type);
                $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
            }
        } else {
            // 创建账号
            $res1 = $this->authCreateUser($openid, $login_type, $auth_data);
            if ($res1['code'] != 1) {
                $this->jsonData->error('', $this->lgs['login_error'] ?: '', $res1); // 登录失败
            }
        }
        if(1){
            if (!$auth_data['auth_mobile']) {
                $this->jsonData->success('', '请绑定手机号', ['authId' => $openid]);
            } else {
                $mobile = $auth_data['auth_mobile'];
                $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$openid' and `type` = '$login_type'");
                $invitation_uid = safe_replace($_POST['invitationUid']) ?: '';
                if ($member_auth['auth_status'] == 1 && $member_auth['uid'] != 0 && $mem) {
                    $this->jsonData->error('', $this->lgs['login_error'] ?: '',"手机号已绑定"); // 已绑定
                }
                // 查询手机号是否注册
                $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
                if ($userinfo) {
                    // 存在 进行绑定
                    $uid = $userinfo['uid'];
                } else {
                    // 不存在进注册
                    $m = $this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
                    $password = '123456';
                    $password = md5($password);
                    $mobilecode = 1;
                    $membergroup = 1;
                    // 注册赠送金额
                    if ($m['money'] > 0) {
                        $money = $m['money'];
                    } else {
                        $money = 0;
                    }
                    $user_img = $member_auth['auth_img'];
                    $auth_name = $member_auth['auth_name'];
                    $yaoqing = $invitation_uid;
                    $auth_time = time();
                    $host = G_HTTP_HOST;
                    $sql_m = "INSERT INTO `@#_member` (img, mobile, password, username, money, mobilecode, groupid, time, yaoqing,`host`) value ('$user_img','$mobile','$password','$auth_name','$money','$mobilecode','$membergroup','$auth_time','$yaoqing','$host')";
                    $sq_1 = $this->db->Query($sql_m);
                    if (!$sq_1) {
                        $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 登录失败
                    }

                    $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
                    $uid = $userinfo['uid'];
                    //记录
                    $type = 1;
                    $pay = '账户';
                    $content = $this->lgs['registration_give'] ?: '注册赠送';
                    if ($money > 0) {
                        $sq_2 = "insert into `@#_member_rechange`(uid,type,pay,content,money,time)values('$uid','$type','$pay','$content','$money','$auth_time')";
                        $this->db->Query($sq_2);
                    }

                    // 又邀请用户是 查看邀请积分
                    if ($invitation_uid > 0) {
                        $homeData = $this->db->GetOne("SELECT `score`,`yaoqing`,`username` FROM `@#_member` WHERE `uid`='$invitation_uid'");
                        if (!empty($homeData)) {
                            $yaoqing = $invitation_uid;
                            $configjf = System::load_sys_config("user_fufen", "f_visituser");
                            if ($configjf > 0) {
                                $jifen = $configjf;
                                $type = 1;
                                $time = time();
                                $pay = '积分';
                                $content = $this->lgs['invite_friends'] ?: '邀请好友奖励积分';
                                $userScore = $homeData['score'] + $jifen;

                                $sq_3 = "insert into `@#_member_account`(uid,type,pay,content,money,time)values('$invitation_uid','$type','$pay','$content','$jifen','$time')";
                                $sql = "UPDATE `@#_member` SET score = $userScore, is_realname = 2 where uid='" . $invitation_uid . "'";
                                $this->db->Query($sq_3);
                                $this->db->Query($sql);
                            }
                        }
                    }
                }

                $this->db->Query("UPDATE `@#_member_auth` SET `uid`='$uid', `auth_status`= 1 WHERE auth_id = '$openid' AND `type` = '$login_type'");

                $data = $this->getLoginInfo($userinfo, $login_type);
                $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
            }
        }
    }

    public function webLogin()
    {
        /* app_id	string	yes	应用唯一标识
        app_secret	string	yes	应用对应的密钥
        code	string	yes	临时授权票据
        grant_type	string	yes	写死 "authorization_code" 即可 */
        $param = [
            'app_id' => $this->AppId,
            'app_secret' => $this->AppSecret,
            'code' => $_POST['code'],
            'grant_type' => 'authorization_code'
        ];
        $str = 'app_id=' . $param['app_id'] . '&app_secret=' . $param['app_secret'] . '&code=' . $param['code'] . '&grant_type=' . $param['grant_type'];
        $url = 'https://open.kuaishou.com/oauth2/access_token?' . $str;
        $res = $this->getCurl($url);
        // $str = 'app_id='. $param['app_id']. '&app_secret='. $param['app_secret']. '&js_code='. $param['js_code'];
        // $url = 'https://open.kuaishou.com/oauth2/mp/code2session?'.$str;
        // $res = $this->postCurl($url);
        /* $res = [
            "result"=> 1,  
            "access_token"=> "ks_test1",  
            "expires_in"=> 172800,  
            "refresh_token"=> "ks_test2",  
            "refresh_token_expires_in"=>15552000,	  
            "open_id"=> "ks_test1",	    
            "scopes"=>["user_info"]
        ]; */
        /* {  
            "result": 1,  
            "access_token": "xxxxxxx",  
            "expires_in": 172800,  
            "refresh_token": "xxxxxx",  
            "refresh_token_expires_in":15552000,	  
            "open_id": "xxxxx",	    
            "scopes": ["user_info"]    
         }   */
        if ($res['result'] == 1) {
            $this->token = $res['access_token'];
            $openid = $res['open_id'];
            //是否存在
            $login_type = "ks";
            $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$openid' and `type` = '$login_type'");
            if ($member_auth) {
                $uid = $member_auth["uid"];
                $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
                if ($mem) {
                    $data = $this->getLoginInfo($mem, $login_type);
                    $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
                }
            } else {
                $userinfo = $this->getUserInfo();
                $mobile = $this->getUserPhone();
                /* $userinfo = [
                    "name"=>"ks_test1",
                    "head"=>"https://www.kuaishou.com/images/user_center/default_avatar.png",
                ];
                $mobile = [
                    "result"=>1,
                    "phone"=>"13900000000",
                ]; */
                // 第三方账号不存在
                $auth_data['auth_name'] = $userinfo['name'] ?: "";
                $auth_data['auth_img'] = $userinfo['head'] ?: "";
                $auth_data['auth_email'] = '';
                $auth_data['auth_mobile'] = '';
                // 创建账号
                $res1 = $this->authCreateUser($openid, $login_type, $auth_data);
                if ($res1['code'] != 1) {
                    $this->jsonData->error('', $this->lgs['login_error'] ?: '', $res); // 登录失败
                }
                if ($mobile['result'] != 1) {
                    $this->jsonData->success('', '请绑定手机号', ['authId' => $openid]);
                } else {
                    $mobile = $mobile['phone'];
                    $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$openid' and `type` = '$login_type'");
                    $invitation_uid = safe_replace($_POST['invitationUid']) ?: '';
                    if ($member_auth['auth_status'] == 1 && $member_auth['uid'] != 0) {
                        $this->jsonData->error('', $this->lgs['login_error'] ?: ''); // 已绑定
                    }
                    // 查询手机号是否注册
                    $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
                    if ($userinfo) {
                        // 存在 进行绑定
                        $uid = $userinfo['uid'];
                    } else {
                        // 不存在进注册
                        $m = $this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
                        $password = '123456';
                        $password = md5($password);
                        $mobilecode = 1;
                        $membergroup = 1;
                        // 注册赠送金额
                        if ($m['money'] > 0) {
                            $money = $m['money'];
                        } else {
                            $money = 0;
                        }
                        $user_img = $member_auth['auth_img'];
                        $auth_name = $member_auth['auth_name'];
                        $yaoqing = $invitation_uid;
                        $auth_time = time();
                        $sql_m = "INSERT INTO `@#_member` (img, mobile, password, username, money, mobilecode, groupid, time, yaoqing) value ('$user_img','$mobile','$password','$auth_name','$money','$mobilecode','$membergroup','$auth_time','$yaoqing')";
                        $sq_1 = $this->db->Query($sql_m);
                        if (!$sq_1) {
                            $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 登录失败
                        }

                        $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
                        $uid = $userinfo['uid'];
                        //记录
                        $type = 1;
                        $pay = '账户';
                        $content = $this->lgs['registration_give'] ?: '注册赠送';
                        if ($money > 0) {
                            $sq_2 = "insert into `@#_member_rechange`(uid,type,pay,content,money,time)values('$uid','$type','$pay','$content','$money','$auth_time')";
                            $this->db->Query($sq_2);
                        }

                        // 又邀请用户是 查看邀请积分
                        if ($invitation_uid > 0) {
                            $homeData = $this->db->GetOne("SELECT `score`,`yaoqing`,`username` FROM `@#_member` WHERE `uid`='$invitation_uid'");
                            if (!empty($homeData)) {
                                $yaoqing = $invitation_uid;
                                $configjf = System::load_sys_config("user_fufen", "f_visituser");
                                if ($configjf > 0) {
                                    $jifen = $configjf;
                                    $type = 1;
                                    $time = time();
                                    $pay = '积分';
                                    $content = $this->lgs['invite_friends'] ?: '邀请好友奖励积分';
                                    $userScore = $homeData['score'] + $jifen;

                                    $sq_3 = "insert into `@#_member_account`(uid,type,pay,content,money,time)values('$invitation_uid','$type','$pay','$content','$jifen','$time')";
                                    $sql = "UPDATE `@#_member` SET score = $userScore, is_realname = 2 where uid='" . $invitation_uid . "'";
                                    $this->db->Query($sq_3);
                                    $this->db->Query($sql);
                                }
                            }
                        }
                    }

                    $this->db->Query("UPDATE `@#_member_auth` SET `uid`='$uid', `auth_status`= 1 WHERE auth_id = '$openid' AND `type` = '$login_type'");

                    $data = $this->getLoginInfo($userinfo, $login_type);
                    $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
                }
            }
        } else {
            $this->jsonData->error('', '获取access_token失败', $res);
        }
    }

    private function refreshToken()
    {
        $param = [
            'app_id' => $this->AppId,
            'app_secret' => $this->AppSecret,
            'refresh_token' => $_POST['refresh_token'],
            'grant_type' => 'refresh_token'
        ];
        $str = 'app_id=' . $param['app_id'] . '&app_secret=' . $param['app_secret'] . '&refresh_token=' . $param['refresh_token'] . '&grant_type=' . $param['grant_type'];
        $url = 'https://open.kuaishou.com/oauth2/access_token?' . $str;
        $res = $this->getCurl($url);
        if ($res['result'] == 1) {
            $this->token = $res['access_token'];
            $this->jsonData->success('', '', $res);
        } else {
            $this->jsonData->error('', '获取access_token失败', $res);
        }
    }

    public function getUserInfo()
    {
        $url = 'https://open.kuaishou.com/user/info?access_token=' . $this->token;
        $res = $this->getCurl($url);
        /* {  
            "result": 1,  
            "user_info": {  
             "name":"xxxx",  
             "sex":"M",  
             "fan":12,  
             "follow":17,  
             "head":"http://xxxx.png",  
             "bigHead":"http://yyyyy.png",  
             "city":"河北省石家庄市"  
             }	    
         }   */
        if ($res['result'] == 1) {
            return $res['user_info'];
            // $this->jsonData->success('','',$res);
        } else {
            return $res;
            // $this->jsonData->error('','获取用户信息失败',$res);
        }
    }

    public function getUserPhone()
    {
        $url = 'https://open.kuaishou.com/user/phone?access_token=' . $this->token;
        $res = $this->getCurl($url);
        if ($res['result'] == 1) {
            $phone = $this->analysisPhone($res['encrypted_phone']);
            $res["phone"] = $phone;
            return $res;
        } else {
            return $res;
        }
    }

    

    //解密数据
    public function decryptData( $encryptedData, $iv, $sessionKey,$appid)
	{
        return ["code"=>200,"mobile"=>"15320569375"];
		if (strlen($sessionKey) != 24) {
			return ["code"=>41001,"mobile"=>""];
		}
		$aesKey=base64_decode($sessionKey);
          

		if (strlen($iv) != 24) {
            return ["code"=>41002,"mobile"=>""];
		}
		$aesIV=base64_decode($iv);
 
		$aesCipher=base64_decode($encryptedData);
		$result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
		$dataObj=json_decode( $result );
        // save_log($dataObj,"ks.log",1);
		if(isset($dataObj['phoneNumber'])){
            return ["code"=>200,"mobile"=>$dataObj['phoneNumber']];
        }else{
            return ["code"=>41003,"mobile"=>""];
        }
    }

    private function analysisPhone($encryptedPhone,$iv='')
    {
        if($iv){
            $cipherPhone = $encryptedPhone;
        }else{
            // 使用冒号分割IV和加密后的电话号码
            $array = explode(":", $encryptedPhone);
            $iv = base64_decode($array[0]);
            $cipherPhone = $array[1];
        }
        // 解密函数
        function decryptAESCBC($ciphertext, $key, $iv)
        {
            return openssl_decrypt($ciphertext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
        }
        // 解密电话号码
        $decryptedBytes = decryptAESCBC($cipherPhone, base64_decode($this->AppSecret), $iv);
        $plainPhone = mb_convert_encoding($decryptedBytes, 'UTF-8', 'auto');
        // 输出解密后的电话号码
        return $plainPhone;
    }

    private function postCurl($url, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 忽略SSL证书验证
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    function postCurl1($url, $post)
    { //get https的内容
        $ch = curl_init(); //新建curl
        curl_setopt($ch, CURLOPT_URL, $url); //url  
        curl_setopt($ch, CURLOPT_POST, 1);  //post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); //post内容  
        $res =  curl_exec($ch); //输出
        $arr = json_decode($res, true);
        curl_close($ch);
        return $arr;
    }

    private function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 忽略SSL证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 忽略SSL证书CN域验证
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }
}
