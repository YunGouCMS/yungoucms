<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'app', 'no');

require G_APP_PATH . "extend/autoload.php";
use JiaweiXS\WeApp\WeApp;
class wechat extends base
{

    private $AppId = '';
    private $AppSecret = '';

    public $expirationdate = 1;

    public function __construct()
    {
        parent::__construct();
        $web=System::load_sys_config('system');
        if(isset($web['wx_mini_app_id'])){
            $this->AppId = $web['wx_mini_app_id'];
        }
        if(isset($web['wx_mini_app_secret'])){
            $this->AppSecret = $web['wx_mini_app_secret'];
        }
    }

    public function getPhoneNumber(){
        $config = [
            "appid" => $this->AppId,
            "secret" => $this->AppSecret,
        ];
        $code = $_POST['code'];
        $nickname = $_POST['nickname'];
        $avatarUrl = $_POST['avatarUrl'];
        $weapp = new WeApp($config['appid'], $config['secret'], G_APP_PATH . "/system/caches");
        $Info = $weapp->getInfo();
        $res = $Info->getPhoneNumber($code);
        $time = date("y-m-d");
        save_log($res,"wx$time.log",1);
        if(isset($res['errcode']) && $res['errcode'] !=0){
            $this->jsonData->error('', $res['errmsg']); // 参数错误
        }
        $auth_id = $_POST['authId'];
        $auth_type = "wx_mini";
        $mobile = $res['phone_info']['purePhoneNumber'];
        $invitation_uid = safe_replace($_POST['invitationUid']) ?: '';

        if (empty($auth_id)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }

        // 查询授权用户是否存在
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$auth_id' AND `type` = '$auth_type'");
        if (empty($member_auth)) {
            $this->jsonData->error('', $this->lgs['login_error'] ?: ''); // 未找到授权信息
        }
        $this->db->Query("update `@#_member_auth` set auth_name = '$nickname',auth_img = '$avatarUrl' where `auth_id` = '$auth_id' AND `type` = '$auth_type'");
        
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
            $user_img = $avatarUrl;
            $auth_name = $nickname;
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

        $this->db->Query("UPDATE `@#_member_auth` SET `uid`='$uid', `auth_status`= 1 WHERE auth_id = '$auth_id' AND `type` = '$auth_type'");

        $nowtime = time();
        $url = get_home_url();
        $token = [
            'iss' => $url, //签发者
            'aud' => '', //jwt所面向的用户
            'iat' => $nowtime, //签发时间
            'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
            'exp' => $nowtime + 86400 * $this->expirationdate, //过期时间-10min
            'data' => [
                'userid' => $userinfo['uid'],
                'username' => $userinfo['username']
            ]
        ];
        /*设置 uid 获取用户信息*/
        $this->setUid($userinfo['uid']);
        $this->login_log($userinfo['uid'],$auth_type);
        $this->loginReward(); //登录 充值奖励领取
        $data = array('token' => $this->jwt->getToken($token), 'userinfo' => $this->userInfo(), 'user_status' => 0);
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
    }

    public function login()
    {
        $config = [
            "appid" => $this->AppId,
            "secret" => $this->AppSecret,
        ];
        $weapp = new WeApp($config['appid'], $config['secret'], G_APP_PATH . "/system/caches");
        $code = $_POST['code'];
        $userinfo = $_POST;
        $res = $weapp->getSessionKey($code); //返回的是json
        $res = json_decode($res, true);
        if (isset($res['openid'])) {
            $openid = $res['openid'];
            //是否存在
            $type = "wx_mini";
            $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$openid' and `type` = '$type'");
            if ($member_auth) {
                $uid = $member_auth["uid"];
                $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
                if($mem){
                    //登录
                    $nowtime = time();
                    $url = get_home_url();
                    $token = [
                        'iss' => $url, //签发者
                        'aud' => '', //jwt所面向的用户
                        'iat' => $nowtime, //签发时间
                        'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
                        'exp' => $nowtime + 86400 * $this->expirationdate, //过期时间-10min
                        'data' => [
                            'userid' => $mem['uid'],
                            'username' => $mem['username']
                        ]
                    ];
                    $this->setUid($mem['uid']);
                    $this->login_log($mem['uid'], $type);
                    $this->loginReward(); //登录 充值奖励领取
                    $data = array('token' => $this->jwt->getToken($token), 'userinfo' => $this->userInfo());
                    $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
                }
            } else {
                // 第三方账号不存在
                $auth_data['auth_name'] = $userinfo['nickname']?:"";
                $auth_data['auth_img'] = $userinfo['avatarUrl']?:"";
                $auth_data['auth_email'] = '';
                $auth_data['auth_mobile'] = '';
                // 创建账号
                $res1 = $this->authCreateUser($openid, $type, $auth_data);
                if ($res1['code'] != 1) {
                    $this->jsonData->error('', $this->lgs['login_error'] ?: '', $res); // 登录失败
                }
            }
            $this->jsonData->success('', '请绑定手机号', ['authId'=>$res['openid']]);
        } else {
            $this->jsonData->error('', '', $res);
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

    /**
     * 登陆日志
     * @param $uid
     * @return bool
     */
    private function login_log($uid,$login_type='')
    {
        if (empty($uid)) return false;
        $userinfo  = $this->db->GetOne("SELECT uid from `@#_member` WHERE uid = '{$uid}'");
        if (!$userinfo) return false;
        $delnum = 10;
        $limit = $delnum + 1;
        $count = $this->db->GetList("SELECT id FROM `@#_member_login_log` WHERE user_id = '{$uid}' and type = '1' ORDER BY `id` DESC LIMIT $limit");
        if (count($count) > $delnum) {
            $key = $delnum - 1;
            $delid = $count[$key]['id'];
            $this->db->Query("DELETE FROM `@#_member_login_log` WHERE `id`< {$delid} and `user_id` = '{$uid}'");
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
        $sql = "INSERT INTO `@#_member_login_log`(`user_id`, `ip`, `login_time`, `country`, `region`, `city`, `isp`,`district`,`lat`,`lng`,`login_type`) VALUES ({$i['user_id']},'{$i['ip']}','{$i['login_time']}','{$i['country']}','{$i['region']}','{$i['city']}','{$i['isp']}','{$i['district']}','{$i['lat']}','{$i['lng']}','{$i['login_type']}')";
        $res = $this->db->Query($sql);
        if ($res) {
            return true;
        }
        return false;
    }

    /*登录进行重置奖励领取*/
    private function loginReward()
    {
        //登录成功进行充值累计奖励领取,更新ip信息

        $uid = $this->uId;
        $ip = _get_ip();
        $ip_region = _get_ip_region(3);
        $res = $this->db->getOne("SELECT * FROM `@#_member_ip` WHERE `uid`=$uid");
        if ($res) {
            $this->db->Query("UPDATE `@#_member_ip` SET `last_ip`=`ip`,`last_ip_region`=`ip_region` , `ip` = '$ip' ,`ip_region` = '$ip_region' where (`uid` = '$uid')");
        } else {
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
        $zp_config = $this->getConfig("zp_set");
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
     * 
     * 开放平台登录
     * @return void
     */
    public function openH5Login(){
        $web=System::load_sys_config('system');
        if(isset($web['wx_open_app_id'])){
            $this->AppId = $web['wx_open_app_id'];
        }
        if(isset($web['wx_open_app_secret'])){
            $this->AppSecret = $web['wx_open_app_secret'];
        }
        $weapp = new WeApp($this->AppId, $this->AppSecret, G_APP_PATH . "/system/caches");
        $open = $weapp->getOpen();
        $code = $_GET['code']?:$_POST['code'];
        if(!$code){
            $this->jsonData->error('', 'code error'); // 
        }
        $res = $open->getOpenid($code);
        if(!isset($res['openid'])){
            $this->jsonData->error('', '', $res); // 
        }
        $social_uid = $res['openid'];
        $type = "wx_open";
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$social_uid' and `type` = '$type'");
        if($member_auth){
            $uid = $member_auth["uid"];
            $mem = $this->db->getOne("SELECT * FROM `@#_member` where `uid`='$uid'");
            if(!$mem){
                //没有绑定账号
                //跳转到绑定账号页面
                header('Location: '.G_WEB_PATH.'/pages/users/validate/index?auth_id='.$social_uid.'&type='.$type);
                exit();
            }
            //账号存在
            $nowtime = time();
            $url = get_home_url();
            $token = [
                'iss' => $url, //签发者
                'aud' => '', //jwt所面向的用户
                'iat' => $nowtime, //签发时间
                'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
                'exp' => $nowtime + 86400 * $this->expirationdate, //过期时间-10min
                'data' => [
                    'userid' => $mem['uid'],
                    'username' => $mem['username']
                ]
            ];
            $this->setUid($mem['uid']);
            $this->login_log($mem['uid'],$type);
            $this->loginReward(); //登录 充值奖励领取
            // $data = array('token' => $this->jwt->getToken($token), 'userinfo' => $this->userInfo());
            header('Location: '.G_WEB_PATH.'/pages/users/validate/index?token='.$this->jwt->getToken($token).'&uid='.$mem['uid']);
        }else{
            // 第三方账号不存在
            $auth_data['auth_name'] = $res['nickname'];
            $auth_data['auth_img'] = $res['faceimg'];
            $auth_data['auth_email'] = '';
            $auth_data['auth_mobile'] = '';
            // 创建账号
            $res = $this->authCreateUser($social_uid, $type, $auth_data);
            //跳转到绑定账号页面
            header('Location: '.G_WEB_PATH.'/pages/users/validate/index?auth_id='.$social_uid.'&type='.$type);
        }
        // $this->jsonData->success('', '', $res); // 登录成功
    }

    /**
     * 获取登录url
     * @return 
     */
    public function openWeb(){
        $web=System::load_sys_config('system');
        if(isset($web['wx_open_app_id'])){
            $this->AppId = $web['wx_open_app_id'];
        }
        if(isset($web['wx_open_app_secret'])){
            $this->AppSecret = $web['wx_open_app_secret'];
        }
        $weapp = new WeApp($this->AppId, $this->AppSecret, G_APP_PATH . "/system/caches");
        $open = $weapp->getOpen();
        $redirect_url = WEB_PATH."/app/wechat/openH5Login";
        $type = $_POST['type']?:0;
        $res = $open->getUrl($redirect_url,$type);
        if($type){
            $this->jsonData->success('', '', $res); // 
        }
    }
}
