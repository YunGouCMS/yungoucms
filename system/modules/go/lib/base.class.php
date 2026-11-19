<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
define("MEMBER",true);

class base extends SystemAction {
	private $db;
	protected $userinfo=NULL;
    public $nowUserLanguage = '';

	public function __construct(){		
        
		if(ROUTE_M=='member' && ROUTE_C=='user' && ROUTE_A=='login'){
			return;
		}
		if(ROUTE_M=='member' && ROUTE_C=='user' && ROUTE_A=='register'){
			return;
		}
        $this->nowUserLanguage = _getcookie('language');
		$this->db = System::load_sys_class('model');
		$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));		
		$utype=_encrypt(_getcookie("utype"),'DECODE');
		$ushell=_encrypt(_getcookie("ushell"),'DECODE');
	
		if($utype===NULL)$this->HeaderLogin();
		if(!$uid)$this->HeaderLogin();
		$this->userinfo=$this->DB()->GetOne("SELECT * from `@#_member` where `uid` = :uid", array('uid'=>$uid));
		if(!$this->userinfo)$this->HeaderLogin();		
	
		$shell=md5($this->userinfo['uid'].$this->userinfo['password'].$this->userinfo[$utype]);		
		if($ushell!=$shell)	$this->HeaderLogin();
	}
	
	private function HeaderLogin(){
		_message("�㻹δ��¼����Ȩ�޷��ʸ�ҳ��",WEB_PATH."/member/user/login",3);
	}
	

	 /**
     * 用户号语言
     * @param $rqs  语言数组选择
     * @return string
     */
    public function languages($rqs = ''){
        !empty($rqs) ?: $rqs = $_POST['languageType'];
        // $lg = languages(2, '', 2, $this->nowUserLanguage);
        if($rqs){
            $rq = $this->db->GetOne("select * from `@#_language_value` where `key`='$rqs' and type = 2");
            $lg = $this->db->GetList("select * from `@#_language_value` where (`key`='$rqs' or `pid`=".$rq['id'].") and type = 2");
        }else{
            $lg = $this->db->GetList("select * from `@#_language_value` where type = 2");
        }
        if($lg) {
            $lg = $this->buildArray($lg);
            $lg = empty($rqs) ? $lg :  $lg[$rqs];
        }
        return $lg;
    }

    private function buildArray($array, $parentId = 0){
        $tree = [];
        foreach($array as $element){
            if($element['pid'] == $parentId){
                $children = $this->buildArray($array, $element['id']);
                if($children){
                    $tree[$element['key']] = $children;
                }else{
                    // $tree[$element['key']] = @unserialize($element['value'])[$this->nowUserLanguage]['value'];
                    $tree[$element['key']] = mb_unserialize($element['value'])[$this->nowUserLanguage]['value'];
                }
            }
        }
        return $tree;
    }
}
?>