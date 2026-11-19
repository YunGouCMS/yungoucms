<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','adminMobile','no');

// System::load_sys_fun('returnData');

class login extends base{

    public $expirationdate = 1000;

    public function __construct()
    {
        parent::__construct();
    }

    /*用户登录*/
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
        $mem = $this->db->GetOne("select * from `@#_admin` where `username`='$username'");
        $password = md5($password);
        $res = ($mem['userpass'] === $password) ? true : false;
        if(!$res){
            $this->jsonData->error('','账号或密码错误');
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
        $ip = _get_ip();
        $this->db->Query("UPDATE `@#_admin` SET `loginip` = '$ip'  where (`uid` = '$mem[uid]')");
        /*设置 uid 获取用户信息*/
        $this->setUid($mem['uid']);
        $path = date("Ymd",time()) . ".log";
        save_log("【登录】$mem[username] : 登录 IP：$ip",$path,1);
        $data = array('token'=>$this->jwt->getToken($token),'userinfo'=>$mem);
        $this->jsonData->success('','登录成功',$data);
    }

}
?>