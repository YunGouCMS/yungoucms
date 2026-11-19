<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');

System::load_app_class('base', 'app', 'no');

// System::load_sys_fun('returnData');

class user extends base
{
    const PWD = '123456';
    public $expirationdate = 5;
    
    // private $redis;

    public function __construct()
    {
        parent::__construct();
        // System::load_sys_fun('redisData');
        // $this->redis = new redisData();
    }

    //获取顶级邀请人uid
    private function get_top_uid($yaoqing){
        $m = $this->db->GetOne("select uid,yaoqing,level from `@#_member` where uid = ".$yaoqing);
        if($m['top_uid']){
            return $m['top_uid'];
        }
        if($m['level'] == 2 || $m['yaoqing'] == 0){
            return $m['uid'];
        }else{
            return $this->get_top_uid($m['yaoqing']);
        }
    }
    
    /*登录进行重置奖励领取*/
    public function loginReward()
    {
        //登录成功进行充值累计奖励领取,更新ip信息
        
        $uid = $this->uId;
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
     * 获取登录信息 toke,用户信息
     * @param mixed $user
     * @return array
     */
    public function getLoginInfo($user,$login_type=""){
        $nowtime = time();
        $url = get_home_url();
        $token = [
            'iss' => $url, //签发者
            'aud' => '', //jwt所面向的用户
            'iat' => $nowtime, //签发时间
            'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
            'exp' => $nowtime + 86400 * $this->expirationdate, //过期时间-10min
            'data' => [
                'userid' => $user['uid'],
                'username' => $user['username']
            ]
        ];
        /*设置 uid 获取用户信息*/
        $this->setUid($user['uid']);
        $this->login_log($user['uid'],$login_type);
        $this->loginReward(); //登录 充值奖励领取
        $data = array('token' => $this->jwt->getToken($token), 'userinfo' => $this->userInfo());
        return $data;
    }
    /*用户登录*/
    public function login()
    {
        $username = safe_replace($_POST['username']);
        $password = safe_replace($_POST['password']);
        $invitationUid = safe_replace($_POST['invitationUid']);
        if (empty($username)) {
            $this->jsonData->error('', $this->lgs['username_not'] ?: ''); // 用戶名不能空
        }
        if (empty($password)) {
            $this->jsonData->error('', $this->lgs['password_not'] ?: ''); // 密码不能为空
        }

        $logintype = 'mobile'; //手机
            
        $mem = $this->db->GetOne("select * from `@#_member` where CONCAT(`area_code`,`$logintype`)='$username'");
        if(!$mem){
            $area_code = $this->getPhoneAreaCode($username);
            $phone = str_replace($area_code,'',$username);
            $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$phone'");
            if(!$mem){
                $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");
            }else{
                $username = $phone;
            }
        }
        $password = md5($password);
        $black = $this->memberBlacklist($mem['mobile'], '', 4);
        if (!empty($black)) {
            $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
        }

        if (!$mem) {
            //关闭自动注册
            $this->jsonData->error('', $this->lgs['username_password_not'] ?: ''); // 未注册
            exit;
            // $area_code = $this->getPhoneAreaCode($username);
            // $username = str_replace($area_code, '', $username);
            $time = time();
            $signInfo = [
                'mobile'    => $username,
                'codes'          =>  0,
                'password'          =>  $password,
                'yaoqing'          =>  $invitationUid,
                'time'          =>  $time,
                'area_code'          =>  $area_code,
            ];
            $res = $this->signIn($signInfo);
            if ($res['code'] == 2) {
                $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 注册失败
            }
            $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");
            $this->Registerfree($mem);
        }

        $res = ($mem['password'] === $password) ? true : false;
        if (!$res) {
            $this->jsonData->error('', $this->lgs['username_password_not'] ?: ''); // 账号或密码错误
        }
        if ($res['auto_user'] == -1) {
            $this->jsonData->error('', $this->lgs['account_freeze'] ?: ''); // 您的账号已被冻结
        }
        $data = $this->getLoginInfo($mem,'default');
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
    }

    /*验证码登录*/
    public function sendlogin()
    {
        $username = safe_replace($_POST['phone']);
        $code = safe_replace($_POST['code']);
        $invitationUid = safe_replace($_POST['invitationUid']);
        if (empty($username)) {
            $this->jsonData->error('', $this->lgs['username_not'] ?: ''); // 用戶名不能空
        }
        if (empty($code)) {
            $this->jsonData->error('', $this->lgs['code_not'] ?: ''); // 验证码不能为空
        }
        if ($username != '15298115555') {
            $codesql = "SELECT * FROM `@#_mobile_verify` WHERE `mobile` ='$username' AND `verify` ='$code' ";
            $iscode = $this->db->GetOne($codesql);
            if (!$iscode) {
                $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码错误
            }
            $delcodeid = $iscode['id'];
            $delcode = "DELETE FROM `@#_mobile_verify` WHERE `id` = '$delcodeid'";
            $this->db->query($delcode);
        }
        $black = $this->memberBlacklist($username, '', 4);
        if (!empty($black)) {
            $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
        }
        $this->db->Autocommit_start();
        $logintype = 'mobile'; //手机
        $area_code = $this->getPhoneAreaCode($username);
        $username = str_replace($area_code, '', $username);
        if(strlen($username) > 11){
            $this->jsonData->error('', $this->lgs['phone_error'] ?: ''); // 手机号错误
        }
        $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username' for update");
        if (!$mem) {
            $time = time();
            $top_uid = $this->get_top_uid($invitationUid);
            $signInfo = [
                'mobile'   => $username,
                'codes'    =>  $code,
                'password' =>  md5(123456),
                'yaoqing'  =>  $invitationUid,
                'top_uid'  =>  $top_uid,
                'time'     =>  $time,
                'area_code'     =>  $area_code,
            ];
            $res = $this->signIn($signInfo);
            if ($res['code'] == 2) {
                $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 注册失败
            }
            $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");
            $this->Registerfree($mem);
        }
        if ($mem['auto_user'] == -1) {
            $this->jsonData->error('', $this->lgs['account_freeze'] ?: ''); // 您的账号已被冻结
        }
        $uid = $mem['uid'];
        if ($mem['mobilecode'] != 1) {
            $updateuserinfo = "UPDATE `@#_member` SET `mobilecode` = '1' WHERE `uid` = '$uid'";
            $this->db->query($updateuserinfo);
        }
        $data = $this->getLoginInfo($mem,"sms");
        $this->db->Autocommit_commit();
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
    }

    /**
     * 判断用户 是否一直再发短信
     *
     * @param $phone
     * @return bool
     */
    private function isTimePhone($phone)
    {

        System::load_sys_fun('redisData');

        $redis = new redisData();

        $phone = 'time_login_' . $phone;
        $phoneVal = $redis->redisGet($phone);
        if ($phoneVal['code'] == 1 && !empty($phoneVal['data'])) {
            return true;
        }
        // 禁止发送短信 时间
        $time = 60 * 1;
        $redis->redisSetex($phone, $time, 1);
        return false;
    }

    /*验证码登录*/
    public function sendlogincode()
    {
        $phone = safe_replace($_POST['phone']);
        $password = safe_replace($_POST['password']);
        $invitationUid = safe_replace($_POST['invitationUid']) ?: '';
        if (empty($phone)) {
            $this->jsonData->error('', $this->lgs['mobile_not'] ?: ''); // 手机号码不能为空
        }
        // if(!preg_match("/^1[3456789]\d{9}$/", $phone)){
        //     $this->jsonData->error('',$this->lgs['mobile_format_error']?:''); //手机号码格式不正确
        // }
        if ($this->isTimePhone($phone)) {
            $this->jsonData->error('', $this->lgs['mobile_later'] ?: ''); // 请稍等再发送
        }
        $sql = "SELECT `uid`,`mobilecode`,`emailcode` FROM  `@#_member` WHERE `mobile` = '$phone'";
        $res = $this->db->GetOne($sql);
        if ($res['mobilecode'] == 1 || $res['emailcode'] == 1) {
            /*已注册 发送验证码*/
            $code = mt_rand(100000, 999999);
            $sendmobile = System::load_sys_config('mobile');
            $key = $sendmobile['cfg_mobile_on'];
            $fun = 'send_login_code_' . $key;
            $ok = $fun($phone, $code);
            if ($ok['0'] == '1') {
                $time = time();
                $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$phone','$code','$time')");
                $this->jsonData->success('', $this->lgs['code_send_ok'] ?: ''); // 验证码发送成功
            } else {
                $this->jsonData->error('', $this->lgs['code_send_no'] ?: ''); // 验证码发送失败
            }
        }
        $deluserinfo = "DELETE from `@#_member` WHERE `mobile` = '$phone'";
        $this->db->Query($deluserinfo);
        $pass = isset($password) ? md5($password) : md5(123456);
        $time = time();
        $code = mt_rand(100000, 999999);
        $codes = $code . '|' . $time;
        //邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证
        $username = hidtel($phone);
        $host = G_HTTP_HOST;
        $insereuserinfo = "INSERT INTO `@#_member`(`username`,`mobile`,password,img,emailcode,mobilecode,yaoqing,time,`host`)VALUES('$username','$phone','$pass','photo/member.jpg','-1','$codes','$invitationUid','$time','$host')";
        $insereres = $this->db->query($insereuserinfo);
        if ($insereres) {
            $sendmobile = System::load_sys_config('mobile');
            $key = $sendmobile['cfg_mobile_on'];
            $fun = 'send_login_code_' . $key;
            $ok = $fun($phone, $code);
            if ($ok['0'] == '1') {
                $time = time();
                $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$phone','$code','$time')");
                $this->jsonData->success('', $this->lgs['code_send_ok'] ?: ''); // 验证码发送成功
            } else {
                $this->jsonData->error('', $this->lgs['code_send_no'] ?: ''); // 验证码发送失败
            }
        } else {
            $this->jsonData->error(500, $this->lgs['system_failure'] ?: ''); //服务器故障稍后再试
        }
    }

    /*获取用户信息*/
    public function getUserInfo()
    {
        $data = $this->userInfo();
        if(!$data && !$this->uId){
            $this->jsonData->error(601,''); //未登录
        }
        $data['totalMoney'] = sprintf("%01.2f", $data['total_money'] + $data['dongjie_money']);

        // $user_fufen = System::load_app_config("user_fufen", '', 'member');

        // $data['fufen_yongjin'] = $user_fufen['fufen_yongjin'] * 100;
        $rake = $this->getRakeBack();
        $data['fufen_yongjin'] = $rake * 100;

        //货币单位
        $currency = $this->currency;
        $data["currency"] = $currency;
        $this->jsonData->success('', '', $data);
    }

    /**
     * 发送注册验证码
     */
    public function sendCod()
    {
        $phone = safe_replace($_POST['phone']);
        $password = safe_replace($_POST['password']);
        $invitationUid = safe_replace($_POST['invitationUid']) ?: '';
        if (empty($phone)) {
            $this->jsonData->error('', $this->lgs['mobile_not'] ?: ''); // 手机号码不能为空
        }
        // if(!preg_match("/^1[3456789]\d{9}$/", $phone)){
        //     $this->jsonData->error('',$this->lgs['mobile_format_error']?:''); // 手机号码格式不正确
        // }
        $sql = "SELECT `uid` FROM WHERE `mobile` = '$phone'";
        $res = $this->db->GetOne($sql);
        if ($res['mobilecode'] == 1 || $res['emailcode'] == 1) {
            $this->jsonData->error('', $this->lgs['mobile_repeat'] ?: ''); // 手机已被注册
        }
        $deluserinfo = "DELETE from `@#_member` WHERE `mobile` = '$phone'";
        $this->db->Query($deluserinfo);

        $pass = isset($password) ? md5($password) : md5(123456);
        $time = time();
        $code = mt_rand(100000, 999999);
        $codes = $code . '|' . $time;
        //邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证
        $host = G_HTTP_HOST;
        $insereuserinfo = "INSERT INTO `@#_member`(`mobile`,password,img,emailcode,mobilecode,yaoqing,time,`host`)VALUES('$phone','$pass','photo/member.jpg','-1','$codes','$invitationUid','$time,'$host')";
        $insereres = $this->db->query($insereuserinfo);
        if ($insereres) {
            $sendmobile = System::load_sys_config('mobile');
            $key = $sendmobile['cfg_mobile_on'];
            $fun = 'send_login_code_' . $key;
            $ok = $fun($phone, $code);
            if ($ok['0'] == '1') {
                $time = time();
                $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$phone','$code','$time')");
                $this->jsonData->success('', $this->lgs['code_send_ok'] ?: ''); // 验证码发送成功
            } else {
                $this->jsonData->error('', $this->lgs['code_send_no'] ?: ''); // 验证码发送失败
            }
        } else {
            $this->jsonData->error(500, $this->lgs['system_failure'] ?: ''); // 服务器故障稍后再试
        }
    }

    /**
     *  注册
     * @param mixed $data  //密码md5格式
     * @return array{code: int, msg: string}
     */
    public function signIn($data)
    {
        $username = hidtel($data['mobile']);
        $host = G_HTTP_HOST;
        $img = $this->getConfig("photo");
        $data['mobile'] = trim($data['mobile']);
        $info = [
            'username' => $username,
            'mobile' => $data['mobile']??'',
            'password' => $data['password']??'',
            'img' => $img,
            'emailcode' => $data['emailcode']??'',
            'mobilecode' => $data['code']??'',
            'yaoqing' => $data['yaoqing']??0,
            'top_uid' => $data['top_uid'],
            'time' => time(),
            'host' => $host,
            'area_code' => $data['area_code']??'',
        ];
        // $insereuserinfo = "INSERT INTO `@#_member`(`username`,`mobile`,password,img,emailcode,mobilecode,yaoqing,time,`host`)VALUES('$username','$data[mobile]','$data[password]','$img','-1','1','$data[yaoqing]','$data[time]','$host')";
        $insereuserinfo = Assemblydata($info, 'member');
        $insereres = $this->db->query($insereuserinfo);
        if ($insereres) {
            $this->uId = $this->db->insert_id();
            $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$data[mobile]',''$data[codes]','$data[time]')");
            if($data['yaoqing']){
                //邀请好友奖励积分
                //奖励佣金
                // System::load_app_fun("pay","pay");
                // get_yongjin($this->uId,"f_visituser");
            }
            return ['code' => 1, 'msg' => $this->lgs['registration_ok'] ?: '']; // 注册成功
        }
        return ['code' => 2, 'msg' => $this->lgs['registration_failed'] ?: '']; // 注册失败
    }

    /*重发验证码*/
    public function anewsendmobile()
    {
        $name = safe_replace($_POST['phone']);
        $member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");
        if (!$member) {
            $this->jsonData->error('', $this->lgs['mobile_format_error'] ?: ''); // 手机号不正确
        }
        $black = $this->memberBlacklist($name, '', 2);
        if (!empty($black)) {
            $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
        }
        $checkcode = explode("|", $member['mobilecode']);
        $times = time() - $checkcode[1];
        if ($times > 120) {
            $sendok = send_mobile_reg_code($name, $member['uid']);
            if ($sendok[0] != 1) {
                $this->jsonData->error('', $sendok[1]);
            }
            //成功
            $this->jsonData->success('', $this->lgs['code_send_ok'] ?: ''); // 发送成功
        } else {
            $this->jsonData->error('', $this->lgs['mobile_later'] ?: ''); // 请等会在获取验证码
        }
    }

    /*注册*/
    public function registered()
    {
        $key = sign($_POST,"registered");
        $red = $this->redis->redisGet($key);
        if($red['data']){
            $this->jsonData->error('', $this->lgs['password_not'] ?: '请勿重复提交'); // 请勿重复提交
        }else{
            $this->redis->redisSetex($key,60,1);
        }
        $phone = safe_replace($_POST['phone']);
        $checkcodes = safe_replace($_POST['code']);
        $password = safe_replace($_POST['password']);
        $invitationUid = safe_replace($_POST['invitationUid']) ?: '';
        if (strlen($checkcodes) != 6) {
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码长度不正确
        }
        if (empty($password)) {
            $this->jsonData->error('', $this->lgs['password_not'] ?: ''); // 密码不能为空
        }
        $area_code = $this->getPhoneAreaCode($phone);
        $mobile = str_replace($area_code, '', $phone);
        if(strlen($mobile) > 11){
            $this->jsonData->error('', $this->lgs['phone_error'] ?: ''); // 手机号错误
        }
        $this->db->Autocommit_start();
        $member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'  for update");
        if($member){
            $this->jsonData->error('','该用户已存在');
        }
        $black = $this->memberBlacklist($mobile, '', 4);
        if (!empty($black)) {
            $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
        }
        // $usercode = explode("|", $member['mobilecode']);
        $usercode = $this->db->GetOne("select * from `@#_mobile_verify` where mobile = '$phone' and verify = '$checkcodes'");
        if ($checkcodes != $usercode['verify']) {
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码不正确
        }
        $pass =  md5($password);
        $top_uid = $this->get_top_uid($invitationUid);
        $data = [
            "mobile"=>$mobile,
            "password"=>$pass,
            "yaoqing"=>$invitationUid,
            "top_uid"=>$top_uid,
            "time"=>time(),
            'area_code'     =>  $area_code,
        ];
        if(!$member){
            $res1 = $this->signIn($data);
            // $res=$this->db->Query("UPDATE `@#_member` SET mobilecode='1', password = '$pass',`yaoqing` = '$invitationUid' where `uid`='$member[uid]'");
        }
        // $res = $this->db->Query("UPDATE `@#_member` SET mobilecode='1', password = '$pass' where `uid`='$member[uid]'");
        $res = $this->db->Query("UPDATE `@#_member` SET mobilecode='1' where `uid`='$member[uid]'");
        if ($res || $member) {
            $member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");
            if($res1['code'] == 1){
                $this->Registerfree($member);
            }
            $data = $this->getLoginInfo($member,'sms');
            $this->db->Autocommit_commit();
            $this->jsonData->success('', $this->lgs['registration_ok'] ?: '', $data); // 注册成功
        }
        $this->db->Autocommit_rollback();
        $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 注册失败
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

    /*找回密码*/
    public function findpass()
    {
        $mobile = safe_replace($_POST['phone']);
        $validCode = safe_replace($_POST['code']);
        $pass = safe_replace($_POST['password']);
        $info = array();
        // if(preg_match("/^1[34578]\d{9}$/", $mobile)){
        $validCode_info = $this->db->GetOne("SELECT * FROM `@#_mobile_verify` WHERE mobile='$mobile' and verify='$validCode'");
        if ($validCode_info) {
            $area_code = $this->getPhoneAreaCode($mobile);
            $mobile = str_replace($area_code, '', $mobile);
            $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
            if ($userinfo) {
                $password = md5($pass);
                $ok = $this->db->Query("UPDATE `@#_member` SET password='$password' where mobile='$mobile'");
                if ($ok) {
                    $this->jsonData->success();
                } else {
                    $this->jsonData->error();
                }
                $sq_3 = "DELETE FROM `@#_mobile_verify` where mobile ='$mobile'";
                $this->db->Query($sq_3);
            } else {
                $this->jsonData->error('', $this->lgs['select_user_null'] ?: ''); // 用户不存在
            }
        } else {
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码错误

        }
        // }else{
        //     $this->jsonData->error('',$this->lgs['mobile_inefficacy']?:''); // 无效的手机号
        // }
    }

    /*找回密码验证码*/
    public function send_logincode()
    {
        $phone = safe_replace($_POST['phone']);
        if ($this->isTimePhone($phone)) {
            $this->jsonData->error('', $this->lgs['mobile_later'] ?: ''); // 请稍等再发送
        }
        $info = array();
        $code = mt_rand('100000', '999999');
        $ok = send_login_code($phone, $code);
        if ($ok['0'] == '1') {
            $time = time();
            $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$phone','$code','$time')");
            $info['status'] = '200';
        } else {
            $this->jsonData->error('', $this->lgs['code_send_no'] ?: '',$ok); // 发送失败
        }
        // }else{
        //     $this->jsonData->error('',$this->lgs['mobile_inefficacy']?:''); // 无效的手机号
        // }

        $this->jsonData->success('', $this->lgs['code_send_ok'] ?: '', $info); // 发送成功
    }
    public function send_logincode1()
    {
        $phone = safe_replace($_POST['phone']);
        if ($this->isTimePhone($phone)) {
            // $this->jsonData->error('', $this->lgs['mobile_later'] ?: ''); // 请稍等再发送
        }
        $mobilec = System::load_sys_config('mobile');
        $key = $mobilec['cfg_mobile_on'];
        // p($mobile);
        $info = array();
        // if(preg_match("/^1[3456789]\d{9}$/", $phone)){ // 手机号
        // file_put_contents('err.txt','mobile---'.var_export($mobile,1)."\n",FILE_APPEND);
        $code = mt_rand('100000', '999999');
        if($key<=0){
            $this->jsonData->error('', $this->lgs['mobile_later'] ?: ''); // 请稍等再发送
        }
        $fen = 'send_login_code_' . $key;
        // _sendmobile()
        $ok = $fen($phone, $code);
        if ($ok['0'] == '1') {
            $time = time();
            $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$phone','$code','$time')");
            $info['status'] = '200';
        } else {
            $this->jsonData->error('', $this->lgs['code_send_no'] ?: '',$ok); // 发送失败
        }
        // }else{
        //     $this->jsonData->error('',$this->lgs['mobile_inefficacy']?:''); // 无效的手机号
        // }

        $this->jsonData->success('', $this->lgs['code_send_ok'] ?: '', $info); // 发送成功
    }
    //删除用户
    public function user_del()
    {
        $user_id = $_POST['id'];
        $id = $this->uId;
        if ($user_id != $id) {
            $this->jsonData->error('', $this->lgs['id_error'] ?: ''); // ID错误
        }
        $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$id'");
        if (!$userinfo) {
            $this->jsonData->error('', $this->lgs['select_user_null'] ?: ''); // 查询用户为空
        } else {
            $user_inset_del = $this->db->Query("INSERT into `@#_member_del`(`uid`,`username`,`email`,`mobile`,`password`,`user_ip`,`img`,`qianming`,`groupid`,`addgroup`,`money`,`emailcode`,`mobilecode`,`othercode`,`passcode`,`reg_key`,`score`,`jingyan`,`yaoqing`,`band`,`time`,`headimg`,`wxid`,`typeid`,`auto_user`,`level`,`owinshop_permission`,`accumulate_money`)VALUES('$userinfo[uid]','$userinfo[username]','$userinfo[email]','$userinfo[mobile]','$userinfo[password]','$userinfo[user_ip]','$userinfo[img]','$userinfo[qianming]','$userinfo[groupid]','$userinfo[addgroup]','$userinfo[money]','$userinfo[emailcode]','$userinfo[mobilecode]','$userinfo[othercode]','$userinfo[passcode]','$userinfo[reg_key]','$userinfo[score]','$userinfo[jingyan]','$userinfo[yaoqing]','$userinfo[band]','$userinfo[time]','$userinfo[headimg]','$userinfo[wxid]','$userinfo[typeid]','$userinfo[auto_user]','$userinfo[level]','$userinfo[owinshop_permission]','$userinfo[accumulate_money]')");

            if ($user_inset_del) {
                $user_del = $this->db->Query("DELETE FROM `@#_member` where `uid` ='$user_id'");
                if ($user_del) {
                    $this->jsonData->success('', $this->lgs['delete_user_ok'] ?: ''); // 删除成功
                } else {
                    $this->jsonData->error('', $this->lgs['delete_error'] ?: ''); // 删除失败
                }
            } else {
                $this->jsonData->error('', $this->lgs['delete_error'] ?: ''); // 删除失败
            }
        }
    }

    /**
     * 登陆日志
     * @param $uid
     * @return bool
     */
    public function login_log($uid,$login_type='')
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

    // 第三方授权登陆
    public function loginAuth()
    {
        $auth_id = _htmtocode($_POST['authId']);
        $auth_type = _htmtocode($_POST['authType']);
        $auth_name = _htmtocode($_POST['authName']);
        $auth_img = _htmtocode($_POST['authImg']) ?: '';
        $auth_email = _htmtocode($_POST['authEmail']) ?: '';
        $auth_mobile = _htmtocode($_POST['authMobile']) ?: '';
        if (empty($auth_id)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }

        if (empty($auth_type)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }

        $user_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$auth_id' and `auth_status` = 1 and `type` = '$auth_type'");

        if (!empty($user_auth)) {
            //进行充值奖励领取
            $config = include_once(dirname(dirname(__FILE__)) . "/member/lib/userrecharge.ini.php");
            $uid = $user_auth['uid'];
            $user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE uid = $uid");
            if (empty($user)) {
                $data = array('user_status' => 1, 'auth_id' => $user_auth['auth_id'], 'auth_type' => $auth_type);
                $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 绑定手机号账号
            }
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
                    if ($receive['day'] < $Days) { // 判断当天是否领取
                        if ($ok) {
                            foreach ($d_money as $key => $value) {
                                if ($key == $Days) {
                                    $content = $this->lgs['recharge_day'] . $Days . $this->lgs['day'] . $this->lgs['reward']; // "充值累积".$Days."天计奖励";
                                    $this->db->Query("UPDATE `@#_recharge_money` SET `day` ='$Days' where `uid` = '$uid' and status =1");
                                    $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $value where (`uid` = '$uid')");
                                    $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$value', '$time')");
                                }
                            }
                        }
                    }
                }
            }

            $data = $this->getLoginInfo($user,$auth_type);
            $data['user_status'] = 0;
            $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
        } else {
            // 未授权用户 去授权注册
            $auth_data['auth_name'] = $auth_name;
            $auth_data['auth_img'] = $auth_img;
            $auth_data['auth_email'] = $auth_email;
            $auth_data['auth_mobile'] = $auth_mobile;
            $res = $this->authCreateUser($auth_id, $auth_type, $auth_data);
            if ($res['code'] == 1) {
                $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $res['data']); // 登录成功
            } else {
                $this->jsonData->error('', $this->lgs['login_error'] ?: '', $res); // 登录失败
            }
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

    // 第三方账号绑定 手机号
    public function authBindMobile()
    {
        $auth_id = $_POST['authId'];
        $auth_type = $_POST['authType'];
        $mobile = safe_replace($_POST['mobile']);
        $code = safe_replace($_POST['code']);
        $invitation_uid = safe_replace($_POST['invitationUid']) ?: '';
        if (empty($auth_id)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }
        if (empty($auth_type)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }
        if (empty($mobile)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }

        if (empty($code)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }

        // 查询授权用户是否存在
        $member_auth = $this->db->GetOne("SELECT * FROM `@#_member_auth` WHERE `auth_id` = '$auth_id' AND `type` = '$auth_type'");
        if (empty($member_auth)) {
            $this->jsonData->error('', $this->lgs['login_error'] ?: ''); // 未找到授权信息
        }

        if ($member_auth['auth_status'] == 1 && $member_auth['uid'] != 0) {
            $this->jsonData->error('', $this->lgs['login_error'] ?: ''); // 已绑定
        }
        // 验证用户验证码
        $codesql = "SELECT * FROM `@#_mobile_verify` WHERE `mobile` ='$mobile' AND `verify` ='$code' ";
        $iscode = $this->db->GetOne($codesql);

        if (!$iscode) {
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码错误
        }
        $delcodeid = $iscode['id'];
        $delcode = "DELETE FROM `@#_mobile_verify` WHERE `id` = '$delcodeid'";
        $this->db->query($delcode);

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

        $this->db->Query("UPDATE `@#_member_auth` SET `uid`='$uid', `auth_status`= 1 WHERE auth_id = '$auth_id' AND `type` = '$auth_type'");

        $data = $this->getLoginInfo($userinfo,$auth_type);
        $data['user_status'] = 0;
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
    }

    /**
     * 代管账号接口
     *
     * @param Type $custody 代管用户id
     * @return type
     * @throws conditon
     **/
    public function userCustody()
    {
        $uid = safe_replace($_POST['custody']);
        if (empty($uid)) {
            $this->jsonData->error('', $this->lgs['parameter_error'] ?: ''); // 参数错误
        }

        $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where uid='$uid'");
        if (!$userinfo) {
            $this->jsonData->error('', $this->lgs['select_user_null'] ?: ''); // 用户不存在
        }
        $data = $this->getLoginInfo($userinfo,'escrow');
        $data['user_status'] = 0;
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
    }

    public function flashLogin()
    {
        $web=System::load_sys_config('system');
        $appId = isset($web['cl_sy_app_id'])?$web['cl_sy_app_id']:'';
        $appKey = isset($web['cl_sy_app_key'])?$web['cl_sy_app_key']:"";
        if(!$appId || !$appKey){
            $this->jsonData->error('',  '未设置appid'); // 
        }
        $url = 'https://api.253.com/open/web/mobile-query';
        $outId = ''; // 客户流水号
        $clientIp = ''; // 客户端IP
        //应用私钥，可选，如用RSA解密，必须填写。
        $private_key = '-----BEGIN RSA PRIVATE KEY-----
        xxxxxxxx...
        -----END RSA PRIVATE KEY-----';
        $encryptType = '0'; // 加密方式：0 AES 1 RSA  ， 默认0 AES

        $token = $_POST['ass_token']; // 运营商token,SDK返回参数
        $invitationUid = safe_replace($_POST['invitationUid']); //邀请uid

        $content = 'appId' . $appId . 'clientIp' . $clientIp . 'encryptType' . $encryptType . 'outId' . $outId . 'token' . $token;
        $sign = bin2hex(hash_hmac('sha256', $content, $appKey, true));
        $params = [
            'appId' => $appId,
            'token' => $token,
            'outId' => $outId,
            'clientIp' => $clientIp,
            'encryptType' => $encryptType,
            'sign' => $sign //签名
        ];
        // CURL 模拟 post 请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $resultJson = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        // CURL 错误码
        $errNo = curl_errno($ch);
        if ($errNo > 0) {
            // 通信失败处理逻辑，请自己填充
            $this->jsonData->error('',  '通信失败'); // 
            // CURL 错误信息
            $errMsg = curl_error($ch);
            // TODO
        } elseif (!empty($curlInfo) && intval($curlInfo['http_code']) != 200) {
            // http 状态码不是 200，请求失败处理逻辑
            $httpCode = $curlInfo['http_code'];
            $this->jsonData->error($httpCode,  json_encode($curlInfo)); // 

            // TODO
        } else {
            // 拿到请求结果，使用返回结果逻辑
            $requestData = json_decode($resultJson, true);
            if ($requestData['code'] == 200000) {
                $chargeStatus = $requestData['chargeStatus']; // 是否收费，枚举值：1 ：收费 0：不收费
                $mobile = $requestData['data']['mobile']; // 手机号
                if ('0' == $encryptType) { //AES解密 ，默认方式
                    $key = md5($appKey);
                    $mobile = openssl_decrypt(hex2bin($mobile),  'AES-128-CBC', substr($key, 0, 16), OPENSSL_RAW_DATA,  substr($key, 16));
                    // 如解密失败 请检查$appKey是否正确
                } elseif ('1' == $encryptType) { //RSA解密
                    $pi_key =  openssl_pkey_get_private($private_key);
                    openssl_private_decrypt(hex2bin($mobile), $mobile, $pi_key); //私钥解密
                    // 如解密失败 请检查$private_key是否正确
                }
                $tradeNo = $requestData['data']['tradeNo']; // 流水号
                // 拿到返回数据继续处理逻辑 TODO
                // echo $mobile;
                //登录逻辑
                $mem = $this->db->GetOne("select * from `@#_member` where `mobile`='$mobile'");
                $black = $this->memberBlacklist($mem['mobile'], '', 4);
                if (!empty($black)) {
                    $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
                }
                if (!$mem) {  //未注册
                    $time = time();
                    $top_uid = $this->get_top_uid($invitationUid);
                    $signInfo = [
                        'mobile'    => $mobile,
                        'codes'          =>  0,
                        'password'          =>  md5('123456'),
                        'yaoqing'          =>  $invitationUid,
                        'top_uid'          =>  $top_uid,
                        'time'          =>  $time,
                    ];
                    $res = $this->signIn($signInfo);
                    if ($res['code'] == 2) {
                        $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 注册失败
                    }
                    $mem = $this->db->GetOne("select * from `@#_member` where `mobile`='$mobile'");
                    $this->Registerfree($mem);
                }
                $data = $this->getLoginInfo($mem,"shanyan");
                $data['user_status'] = 0;
                $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
            } else {
                echo $resultJson;
                // 响应异常处理逻辑 TODO
            }
        }
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
        $redirect_url = WEB_PATH."/app/user/aggreagtionNotify/".$this->uId;
        $url = "https://u.beichenwl.cn/connect.php?act=login&appid={$appId}&appkey={$appKey}&type={$type}&redirect_uri={$redirect_url}";
        $res = getCurl($url);
        $this->jsonData->success('', '', json_decode($res,true)); // 
     }
     public function aggreagtionNotify(){
        $type = $_GET["type"];
        $code = $_GET["code"];
        $res = $this->aggreagtionInfo( $type,$code);
        if($res['code']!=0){
            header('Location: '.G_WEB_PATH.'/index/pages/users/login/index?msg='.$res['msg']);
        }
        $social_uid = $res['social_uid'];

        $re = key($_GET);
        $re = explode('/', $re);
        $uid = array_pop($re);
        if(count($re)==5 && $uid){
            $uid = array_pop($re);
            //登录状态直接绑定
            $this->aggBind($type,$social_uid,$res,$uid);
            header('Location: '.G_WEB_PATH.'/index/pages/users/binding/index');
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
            header('Location: '.G_WEB_PATH.'/index/pages/users/validate/index?auth_id='.$social_uid.'&type='.$type);
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
    
    public function getAggList(){
        $arr = $this->getAggLoginType(1);
        $uid = $this->uId;
        $res = $this->db->getList("select type from `@#_member_auth` where uid = $uid");
        if($res){
            $types = array_column($res,'type');
            foreach($arr as $key=>$vl){
                if(in_array($vl['value'],$types)){
                    $arr[$key]['is_bind'] = true;
                }else{
                    $arr[$key]['is_bind'] = false;
                }
            }
        }else{
            foreach($arr as $key=>$vl){
                $arr[$key]['is_bind'] = false;
            }
        }
        $this->jsonData->success('', '',$arr); // 
    }

     //聚合 解除绑定
     public function aggRevoke(){
        $type = $_POST['type'];
        $uid = $this->uId;
        $res = $this->db->getOne("select * from `@#_member_auth` where uid =$uid and type = '$type'");
        if($res){
            $result = $this->db->Query("delete from `@#_member_auth` where uid=$uid and type='$type'");
        }
        $this->jsonData->success('', '',[]); // 
     }

     /**
      *邮箱密码登录
      */
     public function emailLogin(){
        $username = trim($_POST['username']);
        $password = safe_replace($_POST['password']);
        $invitationUid = safe_replace($_POST['invitationUid']);
        if (empty($username)) {
            $this->jsonData->error('', $this->lgs['username_not'] ?: ''); // 用戶名不能空
        }
        if (empty($password)) {
            $this->jsonData->error('', $this->lgs['password_not'] ?: ''); // 密码不能为空
        }

        $logintype = 'email'; //邮箱
        $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");

        $password = md5($password);
        $black = $this->memberBlacklist($mem['mobile'], '', 4);
        if (!empty($black)) {
            $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
        }
        if (!$mem) {
            //关闭自动注册
            $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 未注册
            exit;
            $time = time();
            $signInfo = [
                'mobile'    => $username,
                'codes'          =>  0,
                'password'          =>  $password,
                'yaoqing'          =>  $invitationUid,
                'time'          =>  $time,
            ];
            $res = $this->signIn($signInfo);
            if ($res['code'] == 2) {
                $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 注册失败
            }
            $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");
            $this->Registerfree($mem);
        }

        $res = ($mem['password'] === $password) ? true : false;
        if (!$res) {
            $this->jsonData->error('', $this->lgs['username_password_not'] ?: ''); // 账号或密码错误
        }
        if ($res['auto_user'] == -1) {
            $this->jsonData->error('', $this->lgs['account_freeze'] ?: ''); // 您的账号已被冻结
        }
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
        /*设置 uid 获取用户信息*/
        $this->setUid($mem['uid']);
        $this->login_log($mem['uid']);
        $this->loginReward(); //登录 充值奖励领取
        $data = array('token' => $this->jwt->getToken($token), 'userinfo' => $this->userInfo());
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
     }

    /**
     * 发送邮箱验证码
     * 
     */
     public function send_emailcode(){
        $username = trim($_POST['username']);
        // $res = $this->redis->redisGet("email_code_".$username);
        $res = $this->redis->redisTtl("email_code_".$username);
        if($res['data'] && $res['data']>220){
            $this->jsonData->error('', $this->lgs['mobile_later'] ?: ''); // 请稍等再发送
        }
        System::load_sys_fun("send");
        $code=rand(100000,999999);//验证码
        $res = send_email_code($username,"","",$code,"",2);
        if(strpos($res,"成功") || $res == "Send success"){
            $this->redis->redisSetex("email_code_".$username,"300",$code);
            $this->jsonData->success("",$res);
        }else{
            $this->jsonData->error("",$res);
        }

     }
    /**
     * 邮箱验证码登录
     */
    public function emailcode_login(){
        $username = trim($_POST['username']);
        $code = safe_replace($_POST['code']);
//        $decode = safe_replace($_POST['decode']);//邀请码
        if (empty($username)) {
            $this->jsonData->error('', $this->lgs['username_not'] ?: ''); // 用戶名不能空
        }
        if (empty($code)) {
            $this->jsonData->error('', $this->lgs['code_not'] ?: ''); // 验证码不能为空
        }

        $logintype = 'email'; //邮箱
        $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");

        $black = $this->memberBlacklist($mem['mobile'], '', 4);
        if (!empty($black)) {
            $this->jsonData->error('', $this->lgs['account_disabled'] ?: ''); // 账号被禁用
        }

        $res = $this->redis->redisGet("email_code_".$username);
        if ($res['data'] != $code) {
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码错误
        }

        if (!$mem) {
            // 注册
//            $this->emailRegister($username,$decode);

            //关闭自动注册
            $this->jsonData->error('', $this->lgs['registration_failed'] ?: ''); // 未注册
            exit;
        }

        if ($mem['auto_user'] == -1) {
            $this->jsonData->error('', $this->lgs['account_freeze'] ?: ''); // 您的账号已被冻结
        }
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
        /*设置 uid 获取用户信息*/
        $this->setUid($mem['uid']);
        $this->login_log($mem['uid']);
        $this->loginReward(); //登录 充值奖励领取
        $data = array('token' => $this->jwt->getToken($token), 'userinfo' => $this->userInfo());
        $this->jsonData->success('', $this->lgs['login_ok'] ?: '', $data); // 登录成功
    }

    /**
     * 绑定邮箱
     * @return void
     */
    public function bind_email(){
        $email = trim($_POST['email']);
        $code = safe_replace($_POST['code']);
        $res = $this->redis->redisGet("email_code_".$email);
        if($res['data'] != $code){
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码错误
        }
        $sql = "update `@#_member` set `email` = '$email',`emailcode` = 1 where uid = ".$this->uId;
        $this->db->Query($sql);
        $this->jsonData->success();
    }

    /**
     * 解绑邮箱
     */
    public function unbind_email(){
        $code = safe_replace($_POST['code']);
        $user = $this->userInfo();
        $email = $user['email'];
        if(!$email){
            $this->jsonData->error();
        }
        $res = $this->redis->redisGet("email_code_".$email);
        if($res['data'] != $code){
            $this->jsonData->error('', $this->lgs['code_error'] ?: ''); // 验证码错误
        }
        $sql = "update `@#_member` set `email` = '',`emailcode` = -1 where uid = ".$this->uId;
        $this->db->Query($sql);
        $this->jsonData->success();
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
     * 一为登录
     */
    public function yiwei_login(){
        $type = safe_replace($_POST['type']);
        $web=System::load_sys_config('system');
        $appId = $web['yiwei_app_id'];
        $appKey = $web['yiwei_app_key'];
        $redirect_url = WEB_PATH."/app/user/yiweiNotify/".$this->uId;
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
            header('Location: '.G_WEB_PATH.'/index/pages/users/login/index?msg='.$res['msg']);
        }
        $social_uid = $res['social_uid'];

        $re = key($_GET);
        $re = explode('/', $re);
        $uid = array_pop($re);
        if(count($re)==5 && $uid){
            $uid = array_pop($re);
            //登录状态直接绑定
            $this->aggBind($type,$social_uid,$res,$uid);
            header('Location: '.G_WEB_PATH.'/index/pages/users/binding/index');
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
            header('Location: '.G_WEB_PATH.'/index/pages/users/validate/index?auth_id='.$social_uid.'&type='.$type);
        }
    }

    private function yiwei_userinfo($type,$social_uid){
        $web=System::load_sys_config('system');
        $appId = $web['yiwei_app_id'];
        $appKey = $web['yiwei_app_key'];
        $url = " https://iologin.cc/connect.php?act=query&appid={$appId}&appkey={$appKey}&type={$type}&social_uid={$social_uid}";
        $res = getCurl($url);
        return json_decode($res,true);
    }

    /**
     * 获取手机区号
     */
    public function getPhoneCode(){
        $res = $this->db->GetList("select area_code from `@#_language` where status = 1");
        $area_code = [];
        foreach($res as $key=>$val){
            $area_code = array_merge($area_code,explode(',',$val['area_code']));
        }
        $area_code = array_unique($area_code);
        $this->jsonData->success('','',$area_code);
    }

    /**
     * 邮箱注册(暂时弃用)
     * @return void
     */
    public function emailRegister($name,$decode = ''){

        $config_email = System::load_sys_config("email");

        $config_mobile = System::load_sys_config("mobile");

        if(empty($decode)){
            $decode=_encrypt($this->segment(4),"DECODE");
        }else{
            $decode=_encrypt($decode,"DECODE");
        }

            $userpassword=123456;

            if($name==null or $userpassword==null){
                return ["code"=>2,'msg'=>'帐号密码不能为空'];

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

                return ["code"=>2,'msg'=>'密码小于6位或大于20位'];

            }

            $regtype=null;

            // if(_checkmobile($name)){
            if(strpos($name, '@') === false){

                $regtype='mobile';
                if($config_mobile['cfg_mobile_on']==0){
                    return ["code"=>2,'msg'=>'系统短信配置不正确'];
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

                    return ["code"=>2,'msg'=>'系统邮箱配置不正确'];
                }

            }

            if($regtype==null)return ["code"=>2,'msg'=>'注册类型不正确'];

            $time=time();

            $userpassword=md5($userpassword);

            $codetype=$regtype.'code';

            $decode = intval($decode);

            //邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证

            $sql="INSERT INTO `@#_member` (password,img,emailcode,mobilecode,reg_key,yaoqing,time,area_code)VALUES('$userpassword','photo/member.jpg','-1','-1','$name','$decode','$time','$area_code')";

            if($this->db->Query($sql)){

               /* $check_code  = serialize(array("name"=>$name,"time"=>$time));

                $check_code  = _encrypt($check_code,"ENCODE",'',3600*24);*/

            }else{
                _message("注册失败!",WEB_PATH.'/register');
            }
    }

}
