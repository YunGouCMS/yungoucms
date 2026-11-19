<?php 

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',NULL,'no');
System::load_app_fun('global');
System::load_sys_fun('user');
class qishu extends admin {
	private $db;
	public function __construct(){		
		parent::__construct();		
		$this->db=System::load_app_model('admin_model');
		$this->ment=array(
						array("lists","往期商品列表",ROUTE_M."/content/goods_list"),						
		);	
	} 
  	//往期批量删除
  	  public function qishu_del(){
      	$newstr=$_POST['ids'];
      // file_put_contents('err.txt', '$newstr---'.var_export($newstr,TRUE)."\n", FILE_APPEND);
		$q1 = $this->db->Query("delete from `@#_shoplist_copy` where id IN($newstr)");	
        $q2 = $this->db->Query("delete from `@#_shoplist` where id IN($newstr)");
		if($q2 && $q1){
		$this->db->Autocommit_commit();
			echo'0';
		}else{
		$this->db->Autocommit_rollback();
			echo '1';
		}	
    }
  
  
	
	//期数列表
	public function qishu_list(){
        $shopid=intval($this->segment(4));
        $this->ment = array(

            // array("lists","往期商品列表",ROUTE_M."/content/goods_list"),
            array("lists","查看往期",ROUTE_M."/qishu/qishu_list/".$shopid),
        );
		$info = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$shopid' LIMIT 1");
		$num=20;
		$total1=$this->db->GetCount("select * from `@#_shoplist` where `sid` = '$info[sid]'");
		$total2=$this->db->GetCount("select * from `@#_shoplist_copy` where `sid` = '$info[sid]'");
		$total=$total1+$total2;
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}		
		$page->config($total,$num,$pagenum,"0"); 
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}	
		$start=($pagenum-1)*($num-1);
		// $qishu1=$this->db->GetList("select * from `@#_shoplist` where `sid` = '$info[sid]' order by `qishu` DESC");
		// $qishu2=$this->db->GetList("select * from `@#_shoplist_copy` where `sid` = '$info[sid]' order by `qishu` DESC");
		// $qishu=array_merge($qishu1,$qishu2);
		// $qishu=array_slice($qishu,$start,$num);
		
		$sql1 = "select * from `@#_shoplist` where `sid` = '$info[sid]' ";
		$sql2 = "select * from `@#_shoplist_copy` where `sid` = '$info[sid]' ";
		$sql = "select a.* from (".$sql1 ." union all ".$sql2.") as a order by `qishu` DESC limit $start,$num";
		$qishu = $this->db->GetList($sql);

		$cateid = $qishu[0]['cateid'];
		$cate_name = $this->db->GetOne("select * from `@#_category` where `cateid` = '$cateid' LIMIT 1");
		$cate_name = $cate_name['name'];
		$formot_array = ["money","yunjiage","preferential_price"];
        data_format($qishu,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'qishu.list');
	}
}
?>