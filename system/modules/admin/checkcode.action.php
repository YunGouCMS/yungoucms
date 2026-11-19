<?php
defined('G_IN_SYSTEM')or exit("no");
require_once ("goole/PHPGangsta/GoogleAuthenticator.php");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class checkcode extends admin {

    private $db;
    public function __construct(){

        parent::__construct();

        $this->db=System::load_sys_class("model");

    }
	public function image(){	

		$style = $this->segment(4);		

		$style = explode("_",$style);		

		$width = isset($style[0]) ? intval($style[0]) : '';

		$height = isset($style[1]) ? intval($style[1]) : '';

		$color = isset($style[2]) ? $style[2] : '';

		$bgcolor = isset($style[3]) ? $style[3] : '';

		$lenght = isset($style[4]) ? intval($style[4]) : '';

		$type = isset($style[5]) ? intval($style[5]) : '';

		$checkcode=System::load_app_class("checkcodeimg");

		$checkcode->config($width,$height,$color,$bgcolor,$lenght,$type);

		$checkcode->dian(100,$color);

		if(session_status() !== PHP_SESSION_ACTIVE) session_start();
		$_SESSION['admin_character_code'] = md5(strtolower($checkcode->code));

		$checkcode->image();
	}
    public function code(){
        $ga = new PHPGangsta_GoogleAuthenticator();
        // 创建新的"安全密匙SecretKey"
        // 把本次的"安全密匙SecretKey" 入库,和账户关系绑定,客户端也是绑定这同一个"安全密匙SecretKey"
        // 安全密匙SecretKey 可以和手机端绑定
        $secret = $ga->createSecret();

        //存入数据库
        $data=$this->db->GetList("SELECT * from `@#_google_verify` where 1");

        if(empty($data)){
            $sql="insert into `@#_google_verify`(`keys`) values('$secret')";
        } else{
            $sql = "UPDATE `@#_google_verify` SET `keys`='$secret' WHERE 1";
        }
        $res=$this->db->Query($sql);
        $name = trim((string)$this->getConfig('web_name'));
        if ($name === '') $name = 'YouGouCMS';
        //第一个参数是"标识"(谷歌APP中显示的标题，使用 webcfg 配置的商城名称)，第二个参数为"安全密匙SecretKey" 生成二维码信息
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($name, $secret);
        include $this->tpl(ROUTE_M,'checkcode.code');
    }
    public function suanshuimg(){

        $checkcode=System::load_app_class("checkcodeimg");
        $checkcode->arithmeticCode();
    }

    public function getQr(){
        $data=$this->db->GetOne("SELECT * from `@#_google_verify` where 1");
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();        
        $type = $_POST['type']?:0;
        if(empty($data)){
            // 创建新的"安全密匙SecretKey"
            // 把本次的"安全密匙SecretKey" 入库,和账户关系绑定,客户端也是绑定这同一个"安全密匙SecretKey"
            // 安全密匙SecretKey 可以和手机端绑定
            $sql="insert into `@#_google_verify`(`keys`) values('$secret')";
            $res=$this->db->Query($sql);
        } elseif($type==0){
            $secret = $data['keys'];
        }
        System::load_sys_fun('redisData');
        $redisClass = new redisData();
        $redisClass->redisSet('gg_secret',$secret);
        $name = trim((string)$this->getConfig('web_name'));
        if ($name === '') $name = 'YouGouCMS';
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($name, $secret);
        $this->jsonData->success('','',["img"=>$qrCodeUrl,"secret"=>$secret]);
    }

    public function updateQr(){
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        //存入数据库
        $data=$this->db->GetList("SELECT * from `@#_google_verify` where 1");
        if(empty($data)){
            $sql="insert into `@#_google_verify`(`keys`) values('$secret')";
        } else{
            $sql = "UPDATE `@#_google_verify` SET `keys`='$secret' WHERE 1";
        }
        $res=$this->db->Query($sql);
        return $res;
    }

    /**
     * 重置谷歌验证码：生成新密钥并清除绑定状态，需重新扫码绑定
     */
    public function resetGoogleBind(){
        $this->updateQr();
        $this->setConfig('google_authenticator_bound', '0', '后台谷歌验证码已绑定');
        $this->jsonData->success(200, '已重置，请使用新二维码重新扫码绑定');
    }

    /*谷歌登录验证*/
    public function googleSign(){
        $code = $_POST['code']?:0;
        $ga = new PHPGangsta_GoogleAuthenticator();
        $data = $this->db->GetList("SELECT * FROM `@#_google_verify` LIMIT 1");
        if (empty($data) || empty($data[0]['keys'])){
            $this->jsonData->error("","请先生成二维码");
            return;
        }
        $secret = $data[0]['keys'];
        $checkResult = $ga->verifyCode($secret, $code, 2);
        if(!$checkResult){
            $this->jsonData->error("","验证码输入错误");
        }else{
            // 绑定成功：将状态写入数据库，刷新页面后仍显示“已绑定”
            $this->setConfig('google_authenticator_bound', '1', '后台谷歌验证码已绑定');
            $this->jsonData->success(200, "绑定成功");
        }
    }
}

?>