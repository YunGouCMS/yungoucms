<?php
defined("G_IN_SYSTEM")or exit("no");
System::load_app_class("admin",G_ADMIN_DIR,"no");
System::load_app_fun("global",G_ADMIN_DIR);
class clean extends admin {
    private $db;

	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class("model");
	}
	

	/**
	 * 清除clear
	 * @return void
	 */
	public function del(){
		set_time_limit(0);
		$key = $this->segment(4);
		$time = $this->segment(5);
		$model = $this->segment(6);
		if($key == 'time' && $time){
			$arr = [
				"delete FROM `go_appcart` WHERE time < $time",
				"delete FROM `go_cart` WHERE addtime < $time",
				"delete FROM `go_appoint` WHERE time < $time",
				"delete FROM `go_appRechangeUnique` WHERE time < $time",
				"delete FROM `go_member_account` WHERE time < $time",
				"delete FROM `go_member_addmoney_record` WHERE time < $time",
				"delete FROM `go_member_auth` WHERE auth_time < $time",
				"delete FROM `go_member_cashout` WHERE time < $time",
				"delete FROM `go_member_go_record` WHERE time < $time",
				"delete FROM `go_member_login_log` WHERE login_time < $time",
				"delete FROM `go_member_orderaddress_brand` WHERE time < $time",
				"delete FROM `go_member_rechange` WHERE time < $time",
				"delete FROM `go_member_recodes` WHERE time < $time",
				"delete FROM `go_mobile_verify` WHERE time < $time",
				"delete FROM `go_offline_money` WHERE create_time < $time",
				"delete FROM `go_shoplist_copy` WHERE q_end_time < $time", //自己和他人中奖记录
			];

			foreach($arr as $vl){
				if($model==2){
					echo $vl.";<br/>";
				}else{
					$this->db->Query($vl);
				}
			}
			echo "ok";
		}else{
			echo "error,time is not null";
		}
	}
}
