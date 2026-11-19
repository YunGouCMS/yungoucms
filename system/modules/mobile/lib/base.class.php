<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
define("MEMBER",true);

class base extends SystemAction {
	protected $userinfo=NULL;
	public $nowUserLanguage;
	public $where_language;
	public function __construct(){		

		if(ROUTE_M=='member' && ROUTE_C=='user' && ROUTE_A=='login'){
			return;
		}
		if(ROUTE_M=='member' && ROUTE_C=='user' && ROUTE_A=='register'){
			return;
		}
		$this->nowUserLanguage = _getcookie('language')?:_cfg('web_language');
		$this->where_language = " language in('{$this->nowUserLanguage}','',null)";
		$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));		
		$utype=_encrypt(_getcookie("utype"),'DECODE');
		$ushell=_encrypt(_getcookie("ushell"),'DECODE');

		
		if($utype===NULL)$this->HeaderLogin();
		if(!$uid)$this->HeaderLogin();
		$this->userinfo=$this->DB()->GetOne("SELECT * from `@#_member` where `uid` = '$uid'");
		if(!$this->userinfo)$this->HeaderLogin();		
	
		$shell=md5($this->userinfo['uid'].$this->userinfo['password'].$this->userinfo[$utype]);		
		if($ushell!=$shell)	$this->HeaderLogin();
        System::load_app_fun('member','member');
        $isauthlogin = get_user_black($this->userinfo['mobile'],4);
        if ($isauthlogin){
            _setcookie("uid",'');
            _setcookie("ushell",'');
            _messagemobile("您已被禁止登录！",'/');
        }
	}
	
	public function HeaderLogin(){
		_message("你还未登录，无权限访问该页！",WEB_PATH."/member/user/login",3);
	}
	
}
?>