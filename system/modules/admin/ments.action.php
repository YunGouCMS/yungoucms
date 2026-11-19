<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class ments extends admin {
	
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
		$this->ment=array(
						array("navigation","导航栏管理",ROUTE_M."/wap/dh_list"),
						array("navigation","添加导航栏",ROUTE_M."/wap/dh_add"), 
						array("navigation","直购导航栏管理",ROUTE_M."/wap/jifen_dh_list"),
						array("navigation","添加直购导航栏",ROUTE_M."/wap/jifen_dh_add"), 
						array("navigation","PC端导航栏管理",ROUTE_M."/ments/navigation"), 
						array("navigation","添加PC端导航栏",ROUTE_M."/ments/addnav"),
						array("navigation","修改PC端导航栏",ROUTE_M."/ments/editnav",1),
		);
		
	}
	//导航条管理
	public function navigation(){	
		$lists=array();
		$wheres = '';
		if(isset($_POST['souso_submit'])){
			$languae = $_POST['languae'];
			$wheres = " and `language` = '$languae'";
		}
		$lists=$this->db->GetList("SELECT * FROM `@#_navigation` where 1 $wheres order by `type` desc,`order` DESC");		
		$language = $this->getLanguageList(1,2);
		$lgs = array_column($language,"country","lg_value");
        foreach($lists as &$vl){
            $vl['language'] = $lgs[$vl['language']];
        }
		include $this->tpl(ROUTE_M,'navigation.list');
	}
	//添加
	public function addnav(){
		if(isset($_POST['dosubmit'])){		
			
			$name=htmlspecialchars($_POST['name']);
			$url=htmlspecialchars($_POST['url']);
			$status=$_POST['status']=='Y' ? 'Y' : 'N';
			$type = htmlspecialchars($_POST['type']);
			$language = htmlspecialchars($_POST['language']);
			$order=intval($_POST['order']) ? intval($_POST['order']) : 1;		
			$this->db->Query("INSERT INTO `@#_navigation` (`name`, `type`, `url`, `status`,`order`,`language`) VALUES ('$name','$type','$url','$status','$order','$language')");			
			_message("操作成功",WEB_PATH.'/'.ROUTE_M.'/ments/navigation');			
		}
		$languages = $this->getLanguageList(-1,2);
		include $this->tpl(ROUTE_M,'navigation.add');
	}
	
	//修改
	public function editnav(){
		$cid=$this->segment(4);
		if(intval($cid)<=0){
			_message("参数错误");
		}
		$info=$this->db->GetOne("SELECT * FROM `@#_navigation` WHERE `cid`='$cid'");
		if(!$info)_message("参数错误");

		$languages = $this->getLanguageList(-1,2);
		if(isset($_POST['dosubmit'])){
			$name=htmlspecialchars($_POST['name']);
			$url=htmlspecialchars($_POST['url']);
			$type = htmlspecialchars($_POST['type']);
			$language = htmlspecialchars($_POST['language']);
			$status=$_POST['status']=='Y' ? 'Y' : 'N';
			$order=intval($_POST['order']) ? intval($_POST['order']) : 1;
			$this->db->Query("UPDATE `@#_navigation` SET `name`='$name',
													  `url`='$url',
													  `status`='$status',
													  `type` = '$type',
													  `order`='$order',
													  `language`='$language'
			WHERE (`cid`='$cid')");

			_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/ments/navigation');
			
		}
		include $this->tpl(ROUTE_M,'navigation.add');
	}
	//navdel
	public function navdel(){
		$cid=$this->segment(4);
		if(intval($cid)<=0){
			_message("参数错误");
		}
		$this->db->Query("DELETE FROM `@#_navigation` WHERE (`cid`='$cid')");
		if($this->db->affected_rows()){
			_message("操作成功",WEB_PATH.'/'.ROUTE_M.'/ments/navigation');
		}else
			_message("删除失败");
	}

	public function statusChange(){
		$cid=$_POST['cid'];
		$status=$_POST['status'];
		$status = $status == 'Y' ? 'Y' : 'N';
		if(intval($cid)<=0){
			_message("参数错误");
		}
		$res = $this->db->Query("UPDATE `@#_navigation` SET `status`='$status' WHERE (`cid`='$cid')");
		if($res){
			_message("操作成功",WEB_PATH.'/'.ROUTE_M.'/ments/navigation');
		}else{
			_message("操作失败");
		}
	}
		
}

?>