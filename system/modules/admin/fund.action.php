<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class fund extends admin {
    private $db;
    public $_cfg;
    public $ment;
    public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class("model");
		$this->ment = array(
            array("bank", "银行列表", ROUTE_M . '/' . ROUTE_C . "/bank"),
            array("bind", "添加银行", ROUTE_M . '/' . ROUTE_C . "/bank_add"),
        );
	}
	
	public function fundset(){
		$config = $this->db->GetOne("select * from `@#_fund` LIMIT 1");
		if(isset($_POST['dosubmit'])){
			$off = intval($_POST['fund_off']);
			$money = floatval(substr(sprintf("%.3f",$_POST['fund_money']), 0, -1));
			if(isset($_POST['fund_count_money'])){
				$count_money = floatval(substr(sprintf("%.3f",$_POST['fund_count_money']), 0, -1));
			}else{
				$count_money = $config['fund_count_money'];
			}
			if($money<=0){
				_message("基金出资金额不正确");
			}
			$this->db->Query("UPDATE `@#_fund` SET `fund_off` = '$off',`fund_money` = '$money',`fund_count_money` = '$count_money'");
			_message("修改成功");
		}
		$config = $this->db->GetOne("select * from `@#_fund` LIMIT 1");
		include $this->tpl(ROUTE_M,'fundset');
	}

}