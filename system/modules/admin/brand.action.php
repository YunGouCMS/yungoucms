<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin','','no');
class brand extends admin {
	private $db;
	public function __construct(){
		parent::__construct();
		$this->db=$this->DB();
		$this->ment=array(
			array("lists","云购品牌分类",ROUTE_M.'/'.ROUTE_C."/lists"),
			array("insert","添加云购品牌",ROUTE_M.'/'.ROUTE_C."/insert"),					
		);
		
	}
	
	//品牌管理
	public function lists($model = 1){
		$page=System::load_sys_class("page");
		$num=20;
		$total=$this->DB()->GetCount("select * from `@#_brand` where `model` = '{$model}'");
		if(isset($_GET['p'])){
			$pagenum=intval($_GET['p']);
		}else{
			$pagenum=1;
		}
		$page->config($total,$num,$pagenum);
		$sql = "select b.*,c.name as cate_name,c.model as cate_model,c.languages
			from `@#_brand` b
			left join `@#_category` c on b.`cateid` = c.cateid
			where b.`model` = '{$model}' order by b.`order` DESC";	
		$brands=$this->db->GetPage($sql,array('key'=>'id','num'=>$num,"page"=>$pagenum));
		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE 1 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		foreach($brands as $key=>$val){
			switch($val['model']){
				case '1':
					$brands[$key]['href']=$this->_cfg['web_path']."/index/pages/glist/index?cateid=".$val['cateid'];;
					break;
				case '3':
					$brands[$key]['href']=$this->_cfg['web_path']."/index/pages/glist/index?cateid=".$val['cateid'];;
					break;
			}
			$val['languages'] = mb_unserialize($val['languages']);
			$brands[$key]['cate_name'] = $val['languages'][$this->language_backend]??$val['cate_name'];
		}
		include $this->tpl(ROUTE_M,'brand.list');
	}

	//品牌管理入库
	public function insert($model = 1){
        $models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = $model order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		$categoryshtml = '';
		$topcat = 0;
		foreach($categorys as $id=>&$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
			if($topcat == $cat['cateid']){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			$categoryshtml .= "<option value='".$cat['cateid']."' $selected>".$cat['name']."</option>";
        }
		// $tree=System::load_sys_class('tree');
		// $tree->icon = array('│ ','├─ ','└─ ');
		// $tree->nbsp = '&nbsp;';
		// $categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";		
		// $tree->init($categorys);
		// $categoryshtml=$tree->get_tree(0,$categoryshtml);

		if(isset($_POST['dosubmit'])){
			$info=array();
			$info['cateid']=intval($_POST['cateid']);
			if(!$info['cateid'])_message("请选择所属栏目");
			$info['name']=htmlspecialchars($_POST['name']);
			$info['order']=intval($_POST['order']) ? intval($_POST['order']) : 1;
			$language = $_POST['languages'];
			$language = serialize($language);
			$info['languages'] = $language;
			$sql="INSERT INTO `@#_brand` (`cateid`, `name`,`order`,`model`,`languages`) VALUES ('$info[cateid]', '$info[name]','$info[order]','$model','$info[languages]')";			
			$this->db->Query($sql);
			if($this->db->affected_rows()){			
				$a = $model==1?"lists":"jifenLists";
				_message("操作成功!",WEB_PATH.'/'.ROUTE_M.'/brand/'.$a);
			}else{
				_message("操作失败!");
			}
		}
		include $this->tpl(ROUTE_M,'brand.edit');
	}
	//修改品牌
	public function edit($model = 1){
		
        $brandid=intval($this->segment(4));
        $this->ment=array(
            array("lists","品牌管理",ROUTE_M.'/'.ROUTE_C."/lists"),
            array("insert","添加品牌",ROUTE_M.'/'.ROUTE_C."/insert"),
            array("insert","修改品牌",ROUTE_M.'/'.ROUTE_C."/edit/".$brandid,2),
        );
        $models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		$brands=$this->db->Getone("select * from `@#_brand` where id='$brandid'");
		if(!$brands)_message("参数错误!");
		$model = $brands['model']?:1;
		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = $model order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		// $tree=System::load_sys_class('tree');
		// $tree->icon = array('│ ','├─ ','└─ ');
		// $tree->nbsp = '&nbsp;';
		// $categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";		
		// $tree->init($categorys);
		// $categoryshtml=$tree->get_tree(0,$categoryshtml);	
		$topcat=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$brands[cateid]'");
		// $categoryshtml.="<option value='$topcat[cateid]' selected>≡ $topcat[name] ≡</option>";		
		$categoryshtml = '';
		foreach($categorys as $id=>&$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
			if($brands['cateid'] == $cat['cateid']){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			$categoryshtml .= "<option value='".$cat['cateid']."' $selected>".$cat['name']."</option>";
        }
		$brands['languages'] = mb_unserialize($brands['languages']);
		$languages = $this->getLanguageList(1);
		if(isset($_POST['dosubmit'])){
			$info=array();
			$info['cateid']=intval($_POST['cateid']);
			if(!$info['cateid'])_message("请选择所属栏目");
			$info['name']=htmlspecialchars($_POST['name']);
			$info['order']=intval($_POST['order']) ? intval($_POST['order']) : 1;
			$language = $_POST['languages'];
			$language = serialize($language);
			$info['languages'] = $language;
			$sql="UPDATE `@#_brand` SET `cateid`='$info[cateid]', `name`='$info[name]', `order`='$info[order]',`languages`='$info[languages]' WHERE (`id`='$brandid') LIMIT 1";			
			$this->db->Query($sql);
			if($this->db->affected_rows()){			
				$a = $model==1?"lists":"jifenLists";
				_message("操作成功!",WEB_PATH.'/'.ROUTE_M.'/brand/'.$a);
			}else{
				_message("操作失败!");
			}
		}		
		include $this->tpl(ROUTE_M,'brand.edit');	
	}

	//删除品牌管理
	public function del($model = 1){
		$brandid=intval($this->segment(4));
		if(!$brandid)_message("参数错误!");		
		$res = $this->db->GetOne("SELECT * FROM `@#_brand` WHERE id = '$brandid'");
		$this->db->Query("delete from `@#_brand` where id='$brandid' LIMIT 1");
		if($this->db->affected_rows()){			
			if($res['model']=="3"){
				$href = WEB_PATH.'/'.ROUTE_M.'/brand/jifenLists';
			}else{
				$href = WEB_PATH.'/'.ROUTE_M.'/brand/lists';
			}
				_message("操作成功!",$href);
		}else{
				_message("操作失败!");
		}
	}
	
	
	/*
	*	品牌排序
	*/	
	public function listorder($model = 1){		
		if($this->segment(4)=='dosubmit'){
			foreach($_POST['listorders'] as $id => $listorder){
			     $id = safe_replace($id);
				$this->db->Query("UPDATE `@#_brand` SET `order` = '$listorder' where `id` = '$id'");		
			}				
			_message("排序更新成功");
		}else{
			_message("请排序");
		}		
	}//
		
	
	//积分品牌
	public function jifenLists(){
		$this->ment=array(
			array("jifenLists","直购品牌分类",ROUTE_M.'/'.ROUTE_C."/jifenLists"),
			array("jifenInsert","添加直购品牌",ROUTE_M.'/'.ROUTE_C."/jifenInsert"),						
		);
		$this->lists(3);
	}

	public function jifenInsert(){
		$this->ment=array(
			array("jifenLists","直购品牌分类",ROUTE_M.'/'.ROUTE_C."/jifenLists"),
			array("jifenInsert","添加直购品牌",ROUTE_M.'/'.ROUTE_C."/jifenInsert"),						
		);
        $this->insert(3);
    }

	public function jifenEdit(){
		$this->ment=array(
			array("jifenLists","直购品牌分类",ROUTE_M.'/'.ROUTE_C."/jifenLists"),
			array("jifenInsert","添加直购品牌",ROUTE_M.'/'.ROUTE_C."/jifenInsert"),					
		);
		$this->edit(3);
	}

	public function jifenDel(){
        $this->del(3);
    }
	
	public function jifenListorder(){
        $this->listorder(3);
    }
}

?>