<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_class('returnData','app','no');

class init extends base {
    public $db;
    public $nowUserLanguage;
    public $where_language;
    public $uid = 0;
    public $jsonData;

    public function __construct() {	
      parent::__construct();
      $this->db=System::load_sys_class('model');
      $this->jsonData = new returnData();
      $this->jsonData->type = 3; //3表示初始化
      $raw_lang = _getcookie('language') ?: System::load_sys_config('system','web_language');
      $allowed = $this->db->GetList("select `lg_value` from `@#_language` where 1");
      $allowed = $allowed ? array_column($allowed, 'lg_value') : [];
      $this->nowUserLanguage = in_array($raw_lang, $allowed) ? $raw_lang : (System::load_sys_config('system','web_language') ?: 'Chinese');
      $this->where_language = " language in ('{$this->nowUserLanguage}','',null)";
      $this->uid=safe_replace(_encrypt(_getcookie('uid'),'DECODE'))?:0;

	}	

	public function getConfig($name){
		if(!is_array($name)){
			$res = $this->db->GetOne("select * from `@#_config` where `name` = '$name'");
			return $res['value']?:null;
		}else{
			$names = implode("','",$name);
			$res = $this->db->GetList("select * from `@#_config` where `name` in ('$names')");
			$arr = array_column($res,"value","name");
			$newarr = [];
			foreach($name as $vl){
				$newarr[$vl] = null;
			}
			return array_merge($newarr,$arr);
		}
	}
}