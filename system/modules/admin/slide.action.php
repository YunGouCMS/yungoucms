<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class slide extends admin {
	
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
        $this->ment=array(
            array("navigation","幻灯片管理",ROUTE_M."/wap/init"),
            array("navigation","添加幻灯片",ROUTE_M."/wap/add"),
            array("navigation","添加幻灯片",ROUTE_M."/wap/add"),
            array("navigation","直购幻灯片管理",ROUTE_M."/wap/jifen_init"),
            array("navigation","添加直购幻灯片",ROUTE_M."/wap/jifen_add"),
            array("navigation","PC端幻灯管理",ROUTE_M."/slide/init"),
            array("navigation","PC端添加幻灯片",ROUTE_M."/slide/add"), 
			array("navigation","PC端修改幻灯片",ROUTE_M."/slide/update",1), 
        ); 
		
	}
	public function init(){
		$lists=$this->db->GetList("SELECT * FROM `@#_slide` where 1");		
		$language = $this->getLanguageList(1,2);
		$lgs = array_column($language,"country","lg_value");
        foreach($lists as &$vl){
            $vl['language'] = $lgs[$vl['language']];
        }
		include $this->tpl(ROUTE_M,'slide_list');
	}
	
	public function add(){
		if(isset($_POST['submit'])){
		$title=htmlspecialchars(trim($_POST['title']));
		
		$language=htmlspecialchars(trim($_POST['language']));	
		$link=htmlspecialchars(trim($_POST['link']));
		if(isset($_POST['img'])){
				$img=$_POST['img'];
			}else{
				$img=$slideone['img'];
			}
			 
		$this->db->Query("insert into `@#_slide`(`title`,`link`,`img`,`language`) values('$title','$link','$img','$language') ");	
			if($this->db->affected_rows()){
					_message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
			}else{
					_message("添加失败");
			}
		}
		$languages = $this->getLanguageList(-1,2);
		include $this->tpl(ROUTE_M,'slide_add');
	}
	
	public function delete(){
		$id=intval($this->segment(4)); 
			$this->db->Query("DELETE FROM `go_slide` WHERE (`id`='$id')");
			if($this->db->affected_rows()){
			
					_message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
			}else{
					_message("删除失败");
			}  
	}
	
	public function update(){
		$id=intval($this->segment(4));
		$slideone=$this->db->Getone("SELECT * FROM `@#_slide` where `id`='$id'");	
		$languages = $this->getLanguageList(-1,2);
		if(isset($_POST['submit'])){
			$title=htmlspecialchars(trim($_POST['title']));	
			$link=htmlspecialchars(trim($_POST['link']));	
			$language=htmlspecialchars(trim($_POST['language']));	
			if(isset($_POST['img'])){
				$img=$_POST['img'];
			}else{
				$img=$slideone['img'];
			}		
			$this->db->Query("UPDATE `@#_slide` SET `img`='$img',`title`='$title',`link`='$link',`language`='$language' WHERE `id`=$id");
			if($this->db->affected_rows()){
					_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
			}else{
					_message("修改失败");
			}
		}
		include $this->tpl(ROUTE_M,'slide_update');
	}
	
	public function statusChange(){
	    $id = $_POST['id'];
	    $status = $_POST['status']==1?1:2;
	    $res = $this->db->Query("UPDATE `@#_slide` SET `status`=$status WHERE `id`=$id");
	    if($res){
	        _message("修改成功");
	    }else{
	        _message("修改失败");
	    }
	}
}

?>