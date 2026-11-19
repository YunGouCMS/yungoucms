<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class yunchat  extends base  {
    public $uid = 0;
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
		$loginUid = _getcookie('uid');
		if (empty($loginUid)){
		    return json_decode(['code'=>3000,'msg'=>'请登录'],JSON_UNESCAPED_UNICODE);
        }
		$this->uid = _encrypt($loginUid,DECODE);;
	}
	//消息
	public function usermas(){
	    $uid = $this->uid;
		include templates('mobile/chatmas','usermas');
	}
	//通知消息
	public function usernotice(){
        $uid = $this->uid;

        include templates('mobile/chatnotice','usernotice');
	}
	// 我的好友
	public function userfriends(){
        $uid = $this->uid;

        include templates('mobile/chatfriends','userfriends');
	}
	// 搜索好友/群聊
	public function usersearch(){
        $uid = $this->uid;

        include templates('mobile/chatsearch','usersearch');
	}
	// 聊天页面
	public function usernews(){
        $uid = $this->uid;

        include templates('mobile/chatnews','usernews');
	}
	// 聊天设置
	public function usersetup(){
        $uid = $this->uid;

        include templates('mobile/chatsetup','usersetup');
	}
	// 转账
	public function usertransfer(){
        $uid = $this->uid;

        include templates('mobile/chattransfer','usertransfer');
	}
	// 积分赠送
	public function usergive(){
        $uid = $this->uid;

        include templates('mobile/chatgive','usergive');
	}
	//新的好友
	public function usernewfr(){
        $uid = $this->uid;

        include templates('mobile/chatnewfre','usernewfr');
	}
	//通知详情
	public function userdetails(){
	    $uid = $this->uid;
	
	    include templates('mobile/chatdetails','userdetails');
	}
}
?>