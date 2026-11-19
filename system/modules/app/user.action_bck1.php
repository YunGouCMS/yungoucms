<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');

// System::load_sys_fun('returnData');

class user extends base{

    public $expirationdate = 1;

	public function __construct()
	{
		parent::__construct();
	}

	/*用户登陆*/
	public function login()
	{

        $username = safe_replace($_POST['username']);
        $password = safe_replace($_POST['password']);

        if(empty($username)){
            $this->jsonData->error('','用戶名不能空');
        }
        if(empty($password)){
            $this->jsonData->error('','密码不能为空');
        }
        $logintype='mobile';//手机
        $mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");
        if(!$mem){
            $this->jsonData->error('','账号不存在！');
        }
        $password = md5($password);
        /*$sql = "select * from `@#_member` where `$logintype`='$username' and `password`='$password'";
        $res = $this->db->GetOne($sql);*/
        $res = ($mem['password'] === $password) ? true : false;
        if(!$res){
            $this->jsonData->error('','账号或密码错误');
        }
        if($res['auto_user'] == -1){
            $this->jsonData->error('','您的账号已被冻结');
        }
        $nowtime = time();
        $url = get_home_url();
        $token = [
            'iss' => $url, //签发者
            'aud' => '', //jwt所面向的用户
            'iat' => $nowtime, //签发时间
            'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
            'exp' => $nowtime + 86400*$this->expirationdate, //过期时间-10min
            'data' => [
                'userid' =>$mem['uid'],
                'username' =>$mem['username']
            ]
        ];
        /*设置 uid 获取用户信息*/
        $this->setUid($mem['uid']);
        $this->loginReward();//登陆 充值奖励领取
        $data = array('token'=>$this->jwt->getToken($token),'userinfo'=>$this->userInfo());
        $this->jsonData->success('','登陆成功',$data);
	}

	/*登陆进行重置奖励领取*/
	private function loginReward(){
        //登录成功进行充值累计奖励领取
        $uid=$this->uId;
        $config =include_once(dirname(dirname(__FILE__))."/member/lib/userrecharge.ini.php");
        if($config['isOpen'] && $config['d_money']){
            $d_money=$config['d_money'];
            $info=$this->db->Getone("SELECT * FROM `@#_recharge_money` where uid='$uid' and status =1 ");
            if($info){
                $time=time();
                $old_time=$info['time'];
                $Days = round(($time-$old_time)/3600/24);//相差的天数
                $ee=array_flip($d_money);
                $maxday=end($ee);//获取最大奖励天数
                $ok=0;
                //判断是否过期
                if($Days<=$maxday){
                    $ok=1;
                }else{
                    $this->db->Query("UPDATE `@#_recharge_money` SET `status` =0 where `uid` = '$uid' and status =1 ");
                }
                $receive=$this->db->Getone("SELECT * FROM `@#_recharge_money` where `uid` = '$uid' and status =1 ");
                if($receive['day']<$Days){//判断当天是否领取
                    if($ok){
                        foreach ($d_money as $key => $value) {
                            if($key == $Days){
                                $content ="充值累".$Days."天计奖励";
                                $this->db->Query("UPDATE `@#_recharge_money` SET `day` ='$Days' where `uid` = '$uid' and status =1");
                                $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $value where (`uid` = '$uid')");
                                $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$value', '$time')");
                            }
                        }
                    }
                }
            }
        }
    }

    /*获取用户信息*/
	public function getUserInfo()
    {
        $data = $this->userInfo();
        $this->jsonData->success('','',$data);
    }

    /**
     * 发送注册验证码
    */
    public function sendCod(){
        $phone = safe_replace($_POST['phone']);
        $password = safe_replace($_POST['password']);
        $invitationUid = safe_replace($_POST['invitationUid']) ?: '';

        if(empty($phone)){
            $this->jsonData->error('','手机号码不能为空');
        }
        // if(!preg_match("/^1[3456789]\d{9}$/", $phone)){
        //     $this->jsonData->error('','手机号码格式不正确');
        // }
        $sql = "SELECT `uid` FROM WHERE `mobile` = '$phone'";
        $res = $this->db->GetOne($sql);

        if($res['mobilecode']==1 || $res['emailcode']==1){
            $this->jsonData->error('','手机已被注册');
        }
        $deluserinfo="DELETE from `@#_member` WHERE `mobile` = '$phone'";
        $this->db->Query($deluserinfo);

        $pass= isset($password)? md5($password): md5(123456);
        $time=time();
        $code=mt_rand(100000,999999);
        $codes = $code.'|'.$time;
        //邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证
        $insereuserinfo ="INSERT INTO `@#_member`(`mobile`,password,img,emailcode,mobilecode,yaoqing,time)VALUES('$phone','$pass','photo/member.jpg','-1','$codes','$invitationUid','$time')";
        $insereres = $this->db->query($insereuserinfo);
        if($insereres){
                $sendmobile = System::load_sys_config('mobile');
                $key=$sendmobile['cfg_mobile_on'];
                $fun = 'send_login_code_'.$key;
                $ok =$fun($phone,$code);
                if($ok['0']== '1'){
                    $time=time();
                    $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$phone','$code','$time')");
                    $this->jsonData->success('','验证码发送成功');
                }else{
                    $this->jsonData->error('','验证码发送失败');
                }
        }else{
            $this->jsonData->error(500,'服务器故障稍后再试');
        }
    }

    /*重发验证码*/
    public function anewsendmobile(){
        $name=safe_replace($_POST['phone']);
        $member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");
        if(!$member){
            //_message("参数不正确!");
            $this->jsonData->error('','手机号不正确');
        }
        $checkcode=explode("|",$member['mobilecode']);
        $times=time()-$checkcode[1];
        if($times > 120){
            $sendok = send_mobile_reg_code($name,$member['uid']);
            if($sendok[0]!=1){
                $this->jsonData->error('',$sendok[1]);
            }
            //成功
            $this->jsonData->success('','发送成功');
        }else{
            $this->jsonData->error('','请等会在获取验证码');
        }
    }

    /*注册*/
	public function registered()
	{
        $mobile = safe_replace($_POST['phone']);
        $checkcodes = safe_replace($_POST['code']);
        $password = safe_replace($_POST['password']);

        $invitationUid = safe_replace($_POST['invitationUid']) ?: '';

        if(strlen($checkcodes)!=6){
            $this->jsonData->error('','验证码长度不正确');
        }
        if(empty($password)){
            $this->jsonData->error('','密码不能为空');
        }
        $member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");
        // if($member){
        //     $this->jsonData->error('','该用户已存在');
        // }

        $usercode=explode("|",$member['mobilecode']);
        if($checkcodes!=$usercode[0]){
            $this->jsonData->error('','验证码不正确');
        }
        $pass=  md5($password);
        $res=$this->db->Query("UPDATE `@#_member` SET mobilecode='1', password = '$pass',`yaoqing` = '$invitationUid' where `uid`='$member[uid]'");
        if($res){

            $nowtime = time();
            $url = get_home_url();
            $token = [
                'iss' => $url, //签发者
                'aud' => '', //jwt所面向的用户
                'iat' => $nowtime, //签发时间
                'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
                'exp' => $nowtime + 86400*$this->expirationdate, //过期时间-10min
                'data' => [
                    'userid' =>$member['uid'],
                    'username' =>$member['username']
                ]
            ];
            /*设置 uid 获取用户信息*/
            $this->setUid($member['uid']);
            $userinfo =$this->userInfo();
            $this->Registerfree($userinfo);
            $data = array('token'=>$this->jwt->getToken($token),'userinfo'=>$userinfo);
            $this->jsonData->success('','注册成功',$data);
        }
        $this->jsonData->error('','注册失败');
    }

    /*注册赠送相关*/
    private function Registerfree($userinfo){
        $m=$this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
        $time=time();
        if($m['money'] !=0){
            $money=$m['money'];
            $type=1;
            $pay = '账户';
            $content='注册赠送';
            $uid=$userinfo['uid'];
            $sq_2="insert into `@#_member_rechange`(uid,type,pay,content,money,time)values('$uid','$type','$pay','$content','$money','$time')";
            $this->db->Query($sq_2);
        }
    }

    /*找回密码*/
    public function findpass(){
        $mobile=safe_replace($_POST['phone']);
        $validCode=safe_replace($_POST['code']);
        $pass=safe_replace($_POST['password']);
        $info=array();
        if(preg_match("/^1[34578]\d{9}$/", $mobile)){
            $validCode_info=$this->db->GetOne("SELECT * FROM `@#_mobile_verify` WHERE mobile='$mobile' and verify='$validCode'");
            if($validCode_info){
                $userinfo=$this->db->GetOne("SELECT * FROM `@#_member` where mobile='$mobile'");
                if($userinfo){
                    $password=md5($pass);
                    $ok=$this->db->Query("UPDATE `@#_member` SET password='$password' where mobile='$mobile'");
                    if($ok){
                       $this->jsonData->success();
                    }else{
                      $this->jsonData->error();
                    }
                    $sq_3="DELETE FROM `@#_mobile_verify` where mobile ='$mobile'";
                    $this->db->Query($sq_3);
                }else{
                    $this->jsonData->error('','用户不存在');
                }
            }else{
                $this->jsonData->error('','验证码错误');

            }
        }else{
            $this->jsonData->error('','无效的手机号');
        }
    }

    /*找回密码验证码*/
    public function send_logincode(){
        $phone=safe_replace($_POST['phone']);
        $mobile = System::load_sys_config('mobile');
        $key=$mobile['cfg_mobile_on'];
        $info=array();
        // if(preg_match("/^1[3456789]\d{9}$/", $phone)){
            //file_put_contents('err.txt','mobile---'.var_export($mobile,1)."\n",FILE_APPEND);
            $code=mt_rand('100000','999999');
            $fen = 'send_login_code_'.$key;
            $ok= $fen($mobile,$code);
            if($ok['0']== '1'){
                $time=time();
                $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$mobile','$code','$time')");
                $info['status']='200';
            }else{
                $info['status']='1';
                $info['message']='发送失败';
            }
        // }else{
        //     $info['status']='0';
        //     $info['message']='无效的手机号';
        // }
        echo json_encode($info);
    }

}
?>