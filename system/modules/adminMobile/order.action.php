<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','adminMobile','no');

class order extends base{

    public function __construct()
    {
        parent::__construct();
    }

    public function  recover(){
		$list_where="where `huode` != '0' and  `status` LIKE  '%已回收%'";
		$num=$this->pagenum;
		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` $list_where");
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record` $list_where",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	

		$formot_array = ["money","yunjiage","preferential_price",'moneycount'];
        data_format($recordlist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
        $this->jsonData->success("","",["list"=>$recordlist,"total"=>$total]);
	}

}
?>