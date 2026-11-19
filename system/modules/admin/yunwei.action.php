<?php 

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class yunwei extends admin {
	private $db;
	public function __construct(){		
		parent::__construct();		
		$this->db=System::load_sys_class('model');
		$this->ment=array(
						array("webtongji","站长工具",ROUTE_M.'/'.ROUTE_C."/webtongji"),
						array("sitemap","站点优化",ROUTE_M.'/'.ROUTE_C."/websitemap"),
		);
	}
	
	function init(){}	
	
	//站长工具
	public function webtongji(){	
		include $this->tpl(ROUTE_M,"yunwei.tongji");
	}
	
	//站点优化
	public function websitemap(){	
		include $this->tpl(ROUTE_M,"yunwei.sitemap");
	}
	
	
	
}
?>