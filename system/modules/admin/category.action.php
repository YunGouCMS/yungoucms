<?php 

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class category extends admin {
	private $db;
	public function __construct(){		
		parent::__construct();
		System::load_app_fun('global',G_ADMIN_DIR);
		$this->db=$this->DB('category_model',ROUTE_M);
		$this->ment=array(
						 array("goods","云购商品分类",ROUTE_M.'/'.ROUTE_C."/lists/goods"),
						// array("lists","栏目管理",ROUTE_M.'/'.ROUTE_C."/lists"),
//						 array("addcate","添加文章分类",ROUTE_M.'/'.ROUTE_C."/addcate/def"),
						 array("addcate","添加云购商品分类",ROUTE_M.'/'.ROUTE_C."/addcate/goodsdef"),
						// array("addcate","添加单网页",ROUTE_M.'/'.ROUTE_C."/addcate/danweb"),
//						array("article","文章分类",ROUTE_M.'/'.ROUTE_C."/lists/article"),
						// array("single","单页列表",ROUTE_M.'/'.ROUTE_C."/lists/single"),
						// array("addcate","添加外部链接",ROUTE_M.'/'.ROUTE_C."/addcate/link"),
		);
	}

	//栏目列表
	public function lists(){
		$cate_type=$this->segment(4);
		if(!$cate_type){
			$cate_where='1';
		}
		$href = '';
		if($cate_type=='article'){
			$cate_where="`model` = '2'";
            $this->ment=array(
                       array("article","文章分类",ROUTE_M.'/'.ROUTE_C."/lists/article"),
                       array("article","文章列表",ROUTE_M."/content/article_list"),

                       array("addcate","添加文章分类",ROUTE_M.'/'.ROUTE_C."/addcate/def"), 
            );
		}
		if($cate_type=='goods'){
			$cate_where="`model` = '1'";
            $this->ment=array(
                      array("goods","云购商品分类",ROUTE_M.'/'.ROUTE_C."/lists/goods"),
                      array("addcate","添加云购商品分类",ROUTE_M.'/'.ROUTE_C."/addcate/goodsdef"), 
                             
            );
		}
		if($cate_type=='single'){
			$cate_where="`model` = '-1'";
		}			
		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE $cate_where order by `parentid` ASC,`order` DESC",array('key'=>'cateid'));
		$models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		$tree=System::load_sys_class('tree');
		$tree->icon = array('│ ','├─ ','└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      	$mobe="";
		foreach($categorys as &$vl){
			$vl['typename']=cattype($vl['model']);		
			$vl['info'] = safe_unserialize($vl['info']);
			if($vl['model']==-1){
				$vl['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/danweb/';
            		$mobe="系统";
			}
			if($vl['model']==-2){
				$vl['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/link/';
			}
			if($vl['model'] == 1){
				$href = $this->_cfg['web_path']."/index/pages/glist/index?cateid=".$vl['cateid'];
				$vl['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/goodsdef/';
				$vl['model_name']=$models[$vl['model']]['name'];
				$vl['href'] = $href;
              	$mobe="商品";

			}
			if($vl['model'] == 2){
				$href = $this->_cfg['web_path']."/index/pages/indexs/gonggaoxq/index";
				$vl['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/def/';
				$vl['model_name']=$models[$vl['model']]['name'];
				$vl['href'] = $href;
              	$mobe="文章";
			}else{
				
			}		
			$vl['editcate']=G_ADMIN_PATH.'/'.ROUTE_C.'/editcate/';
			$vl['delcate']=G_ADMIN_PATH.'/'.ROUTE_C.'/delcate/';			
			$vl['img'] = $vl['pic_url']?G_UPLOAD_PATH.'/'.$vl['pic_url']:'';
			$categorys[$vl['cateid']]=$vl;
		}	

      		// $web_path=$this->_cfg['web_path'].$href;
		include $this->tpl(ROUTE_M,'category.list');		
	} 
	

	//添加栏目
	public function addcate(){
		$catetype=$this->segment(4);			//类型
		if($catetype == 'def'){
			$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = 2 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		}else{
			$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = 1 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		}
		$topmodel='';
		$models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		if($topcat=intval($this->segment(5))){
			$topcat=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$topcat' LIMIT 1");
			if(!$topcat)_message("参数错误");		
			if($topcat['model']>0){
				$modelname=$models[$topcat['model']]['name'];
				$topmodel="<option value=\"$topcat[model]\" selected>≡ $modelname ≡</option>";
			}
		}
		$categoryshtml = '';
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
		$info=array();
		$mesage=NULL;
		if($catetype=="def"){
			$this->ment=array(
				array("article","文章分类",ROUTE_M.'/'.ROUTE_C."/lists/article"),
				array("article","文章列表",ROUTE_M."/content/article_list"),

				array("addcate","添加文章分类",ROUTE_M.'/'.ROUTE_C."/addcate/def"),
			);
		}
		$languages = $this->getLanguageList(1);
		if(isset($_POST['info'])){	
			$thumb = $_POST['thumb'];
			switch($catetype){
				case 'def':
					$info['modelid']=($topcat!=0) ? $topcat['model'] : intval($_POST['info']['modelid']);
					$info['parentid']=intval($_POST['info']['parentid']);
					$info['name']=htmlspecialchars($_POST['info']['name']);
					$info['catdir']=htmlspecialchars($_POST['info']['catdir']);

					if($info['modelid']==0){$mesage='请选择数据模型';}			
					if(empty($info['name'])){$mesage='栏目名不能为空';}
					if(empty($info['catdir'])){$mesage='英文名不能为空';}				
					if(!empty($mesage))_message($mesage,null,3);					
					
					$setting=array (				
					'thumb' =>$thumb,
					'des' =>'',
					'template' =>'',
					'content' =>'',
					'meta_title' => htmlspecialchars($_POST['setting']['meta_title']),
					'meta_keywords' =>htmlspecialchars($_POST['setting']['meta_keywords']),
					'meta_description' => htmlspecialchars($_POST['setting']['meta_description']),
					);				
							
					$setting['des']=htmlspecialchars($_POST['info']['description']);
					$setting=serialize($setting);	
				
					$sql="INSERT INTO `@#_category` 
					(`parentid`, `channel`,`model`,`name`, `catdir`, `url`, `info`, `order`) 
					VALUES 
					('$info[parentid]','0', '$info[modelid]','$info[name]', '$info[catdir]', '','$setting','1')";
					
					$this->db->Query($sql);			
					if($this->db->affected_rows()){				
						_message("栏目添加成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/article');
					}else{
						_message("栏目添加失败!");
					}
					break;
				case 'goodsdef':
					$info['modelid']=($topcat!=0) ? $topcat['model'] : intval($_POST['info']['modelid']);
					$info['parentid']=intval($_POST['info']['parentid']);
					$info['name']=htmlspecialchars($_POST['info']['name']);
					$info['catdir']=htmlspecialchars($_POST['info']['catdir']);

					// if($info['modelid']==0){$mesage='请选择数据模型';}		
					$info['modelid'] = 1;
					if(empty($info['name'])){$mesage='栏目名不能为空';}
					if(empty($info['catdir'])){$mesage='英文名不能为空';}				
					if(!empty($mesage))_message($mesage,null,3);					
					
					$setting=array (				
					'thumb' =>$thumb,
					'des' =>'',
					'template' =>'',
					'content' =>'',
					'meta_title' => htmlspecialchars($_POST['setting']['meta_title']),
					'meta_keywords' =>htmlspecialchars($_POST['setting']['meta_keywords']),
					'meta_description' => htmlspecialchars($_POST['setting']['meta_description']),
					);				
							
					$setting['des']=htmlspecialchars($_POST['info']['description']);
					$setting=serialize($setting);	
					$language = $_POST['languages'];
					$language = serialize($language);
					$sql="INSERT INTO `@#_category` 
					(`parentid`, `channel`,`model`,`name`, `catdir`, `url`, `info`, `order`,`languages`) 
					VALUES 
					('$info[parentid]','0', '$info[modelid]','$info[name]', '$info[catdir]', '','$setting','1','$language')";
					$this->db->Query($sql);			
					if($this->db->affected_rows()){				
						_message("栏目添加成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/goods');
					}else{
						_message("栏目添加失败!");
					}
				break;
				case 'danweb':					
					
					$info['modelid']=-1;
					$info['parentid']=intval($_POST['info']['parentid']);
					$info['name']=htmlspecialchars($_POST['info']['name']);
					$info['catdir']=htmlspecialchars($_POST['info']['catdir']);					
					
					if(empty($info['name'])){$mesage='栏目名不能为空';}
					if(empty($info['catdir'])){$mesage='英文名不能为空';}				
					if(!empty($mesage))_message($mesage,null,3);					
					//$thumb = htmlspecialchars($_POST['thumb']);
					$setting=array (
					'thumb' =>$thumb,
					'des' =>'',
					'template' =>'',
					'content' =>'',
					'meta_title' => $_POST['setting']['meta_title'],
					'meta_keywords' => $_POST['setting']['meta_keywords'],
					'meta_description' => $_POST['setting']['meta_description'],
					);				
						
					$setting['des']=htmlspecialchars($_POST['info']['description']);
					$setting['template']=$_POST['info']['template'];
					$setting['content']=base64_encode(editor_safe_replace(stripslashes($_POST['setting']['content'])));
					$setting=serialize($setting);	
					$sql="INSERT INTO `@#_category` 
							(`parentid`,`channel`,`model`,`name`, `catdir`, `url`, `pic_url`, `info`, `order`) 
							VALUES 
							('$info[parentid]','0', '$info[modelid]','$info[name]', '$info[catdir]', '', '$thumb' ,'$setting','1')
						";		
					$this->db->Query($sql);			
					if($this->db->affected_rows()){
						_message("栏目添加成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/danweb');
					}else{
						_message("栏目添加失败!");
					}
				break;
				case 'link':	
					$info['modelid']=-2;
					$info['parentid']=intval($_POST['info']['parentid']);
					$info['name']=htmlspecialchars($_POST['info']['name']);
					$info['url']=htmlspecialchars($_POST['info']['url']);
					if(empty($info['name'])){_message('栏目名不能为空');}
					if(empty($info['url'])){_message("地址不能为空");}				
					$sql="INSERT INTO `@#_category` 
					(`parentid`,`channel`, `model`,`name`, `url`, `order`) 
					VALUES 
					('$info[parentid]', '0','$info[modelid]','$info[name]', '$info[url]','1')";				
					$this->db->Query($sql);			
					if($this->db->affected_rows()){
						_message("栏目添加成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/link');
					}else{
						_message("栏目添加失败!");
					}
					
				break;
			}//switch END			
			
		}//$_POST END
		
		include $this->tpl(ROUTE_M,'category.add');
	}//FUN END
	
	
	//编辑
	public function editcate(){
			$cateid=$this->segment(4);		
			if(!intval($cateid)){_message("参数错误");exit;}			
			$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");
			if(!$cateinfo)_message("没有这个栏目");	
			$cateinfo['info']=unserialize($cateinfo['info']);
			$cateinfo['languages']=unserialize($cateinfo['languages']);
			if($cateinfo['model'] == 1){
				$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = 1 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
			}else{
				$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = 2 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
			}
			$models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
			$catetype='def';						//类型
			if($cateinfo['model']>0)$catetype='def';
			if($cateinfo['model']==1)$catetype='goodsdef';
			if($cateinfo['model']==-1)$catetype='danweb';
			if($cateinfo['model']==-2)$catetype='link';	
			$topinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateinfo[parentid]' LIMIT 1");
			$categoryshtml = '';
			foreach($categorys as $id=>&$cat){
				$cat['languages'] = mb_unserialize($cat['languages']);
				$cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
				if($cateinfo['parentid'] == $cat['cateid']){
					$selected = 'selected';
				}else{
					$selected = '';
				}
				$categoryshtml .= "<option value='".$cat['cateid']."' $selected>".$cat['name']."</option>";
			}
			$languages = $this->getLanguageList(1);
			$info=array();	
			if(isset($_POST['info'])){
				$thumb = $_POST['thumb']?:$cateinfo['info']['thumb'];
				switch($catetype){
					case 'def':						
						$info['parentid']=intval($_POST['info']['parentid']);
						$info['name']=htmlspecialchars($_POST['info']['name']);
						$info['catdir']=htmlspecialchars($_POST['info']['catdir']);	
						if(empty($info['name'])){_message('栏目名不能为空');}
						if(empty($info['catdir'])){_message("地址不能为空");}
						//$thumb = htmlspecialchars($_POST['thumb']);
						$setting=array (
						'thumb' =>$thumb,
						'des' =>htmlspecialchars($_POST['info']['description']),
						'template' =>$cateinfo['info']['template'],
						'content' =>'',				
						'meta_title' => htmlspecialchars($_POST['setting']['meta_title']),
						'meta_keywords' => htmlspecialchars($_POST['setting']['meta_keywords']),
						'meta_description' => htmlspecialchars($_POST['setting']['meta_description']),
						);
						
						$setting=serialize($setting);	
						$sql="UPDATE `@#_category` SET `parentid`='$info[parentid]',
													   `name`='$info[name]', 
													   `catdir`='$info[catdir]',
													   `info`='$setting' ,
													   `pic_url` = '$thumb'
													   WHERE (`cateid`='$cateid')
						 ";		
						$this->db->Query($sql);			
						if($this->db->affected_rows()){
							_message("操作成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/article');
						}else{
							_message("操作失败!");
						}
						
					break;
					case 'goodsdef':					
						$info['parentid']=intval($_POST['info']['parentid']);
						$info['name']=htmlspecialchars($_POST['info']['name']);
						$info['catdir']=htmlspecialchars($_POST['info']['catdir']);	
						if(empty($info['name'])){_message('栏目名不能为空');}
						if(empty($info['catdir'])){_message("地址不能为空");}
						//$thumb = htmlspecialchars($_POST['thumb']);
						$setting=array (
						'thumb' =>$thumb,
						'des' =>htmlspecialchars($_POST['info']['description']),
						'template' =>$cateinfo['info']['template'],
						'content' =>'',				
						'meta_title' => htmlspecialchars($_POST['setting']['meta_title']),
						'meta_keywords' => htmlspecialchars($_POST['setting']['meta_keywords']),
						'meta_description' => htmlspecialchars($_POST['setting']['meta_description']),
						);
						$language = $_POST['languages'];
						$language = serialize($language);
						$setting=serialize($setting);	
						$sql="UPDATE `@#_category` SET `parentid`='$info[parentid]',
													   `name`='$info[name]', 
													   `catdir`='$info[catdir]',
													   `info`='$setting' ,
													   `pic_url` = '$thumb',
													   `languages` = '$language'
													   WHERE (`cateid`='$cateid')
						 ";		
						$this->db->Query($sql);			
						if($this->db->affected_rows()){
							_message("操作成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/goods');
						}else{
							_message("操作失败!");
						}
						
					break;
					case 'danweb':
						$info['parentid']=intval($_POST['info']['parentid']);
						$info['name']=$_POST['info']['name'];
						$info['catdir']=$_POST['info']['catdir'];	
						if(empty($info['name'])){_message('栏目名不能为空');}
						if(empty($info['catdir'])){_message("地址不能为空");}							
						$setting=array (
						'thumb' =>$thumb,
						'des' =>htmlspecialchars($_POST['info']['description']),
						'template' =>$_POST['info']['template'],
						'content' =>base64_encode(editor_safe_replace(stripslashes($_POST['setting']['content']))),
						'meta_title' => htmlspecialchars($_POST['setting']['meta_title']),
						'meta_keywords' => htmlspecialchars($_POST['setting']['meta_keywords']),
						'meta_description' => htmlspecialchars($_POST['setting']['meta_description']),
						);
						
						$setting=serialize($setting);	
						$sql="UPDATE `@#_category` SET `parentid`='$info[parentid]',
													   `name`='$info[name]', 
													   `catdir`='$info[catdir]',
													   `info`='$setting' 
													   WHERE (`cateid`='$cateid')
						 ";		
						$this->db->Query($sql);			
						if($this->db->affected_rows()){
							_message("操作成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/danweb');
						}else{
							_message("操作失败!");
						}					
					break;
				
					case 'link':
						$info['parentid']=intval($_POST['info']['parentid']);
						$info['name']=htmlspecialchars($_POST['info']['name']);
						$info['url']=htmlspecialchars($_POST['info']['url']);
						if(empty($info['name'])){_message('栏目名不能为空');}
						if(empty($info['url'])){_message("地址不能为空");}	 
						$sql="UPDATE `@#_category` SET `parentid`='$info[parentid]',`name`='$info[name]', `url`='$info[url]' WHERE (`cateid`='$cateid')";				
						$this->db->Query($sql);			
						if($this->db->affected_rows()){
							_message("操作成功!",WEB_PATH.'/'.ROUTE_M.'/category/lists/link');
						}else{
							_message("操作失败!");
						}
					
					break;
				}//SWITCH END				
			}//IF POST END
		if($cateinfo['model'] == 2){

			$this->ment=array(
				array("article","文章分类",ROUTE_M.'/'.ROUTE_C."/lists/article"),
				array("article","文章列表",ROUTE_M."/content/article_list"),

				array("addcate","添加文章分类",ROUTE_M.'/'.ROUTE_C."/addcate/def"),
				array("addcate","修改文章分类",ROUTE_M.'/'.ROUTE_C."/editcate/".$cateid),
			);
		}else{

			$this->ment=array(
				array("article","云购商品分类",ROUTE_M.'/'.ROUTE_C."/lists/goods"),
				array("addcate","添加云购商品分类",ROUTE_M.'/'.ROUTE_C."/addcate/goodsdef"),
				array("addcate","修改云购商品分类",ROUTE_M.'/'.ROUTE_C."/editcate/",2),
			);
		}
			include $this->tpl(ROUTE_M,'category.edit');
			
	}
	/*
	*	栏目排序
	*/
	public function listorder(){		
		if($this->segment(4)=='dosubmit'){
			foreach($_POST['listorders'] as $id => $listorder){
			       $id = safe_replace($id);
				$this->db->Query("UPDATE `@#_category` SET `order` = '$listorder' where `cateid` = '$id'");		
			}				
			_message("排序更新成功");
		}else{
			_message("请排序");
		}		
	}//
		
	//ajax删除栏目
	public function delcate(){			
		$cateid=$this->segment(4);		
		if(!intval($cateid)){echo "no";exit;}
		  $info=$this->db->GetOne("SELECT * FROM `@#_category` WHERE (`cateid`='$cateid')");
		  if(!$info){echo "no";exit;}
		$this->db->Query("DELETE FROM `@#_category` WHERE (`cateid`='$cateid') LIMIT 1");
		if($this->db->affected_rows()){	
		  if($info['model'] == 2){
			$url = WEB_PATH.'/'.ROUTE_M.'/category/lists/article';
		  }elseif($info['model'] ==1){
			$url = WEB_PATH.'/'.ROUTE_M.'/category/lists/goods';
		  }elseif($info['model'] ==3){
			$url = WEB_PATH.'/'.ROUTE_M.'/category/jifen_lists';
		  }
			_message("删除成功",$url);
		}else{
			_messageerror("删除失败");
		}	
		
}

	public function keysList(){
		$this->ment=array(
			array("article","文章keys",ROUTE_M.'/'.ROUTE_C."/keysList"),
			array("addcate","添加keys",ROUTE_M.'/'.ROUTE_C."/addkey"), 
 		);
		// $this->headerment();
		$where = [];
		if(isset($_POST['category_id']) && $_POST['category_id']){
			$where['category_id'] = $_POST['category_id'];
		}
		$sql = Assemblydata([],"category_keys","sel",$where);
		$list =$this->db->GetList($sql);

		include $this->tpl(ROUTE_M,'category.keyslist');
	}

	public function addkey(){
		$this->ment=array(
			array("article","文章keys",ROUTE_M.'/'.ROUTE_C."/keysList"),
			array("addcate","添加keys",ROUTE_M.'/'.ROUTE_C."/addkey"), 
 		);
		if(isset($_POST['dosubmit'])){
			$data['category_id'] = $_POST['category_id'];
			$data['name'] = $_POST['name'];
			$data['keys'] = $_POST['keys'];
			$data['sort'] = $_POST['sort'];
			$ssql = $this->db->GetOne("select * from `@#_category_keys` where `keys` = '".$data['keys']."'");
			if($ssql){
				_messageerror("keys已存在");
			}
			$sql = Assemblydata($data,'category_keys','in');
			$this->db->Query($sql);
			_message('添加成功',WEB_PATH.'/'.ROUTE_M.'/category/keyslist');
		}
		$sql = Assemblydata(["cateid","name"],'category',"sel",[['model',2]]);
		$category = $this->db->GetList($sql);
		include $this->tpl(ROUTE_M,'category.addkey');

	}

	public function editkey(){
		$this->ment=array(
			array("article","文章keys",ROUTE_M.'/'.ROUTE_C."/keysList"),
			array("editkey","修改文章keys",ROUTE_M.'/'.ROUTE_C."/editkey",2), 
 		);
		$id = $this->segment(4);
		if(isset($_POST['dosubmit'])){
			$data['category_id'] = $_POST['category_id'];
			$data['name'] = $_POST['name'];
			$data['keys'] = $_POST['keys'];
			$data['sort'] = $_POST['sort'];
			$ssql = $this->db->GetOne("select * from `@#_category_keys` where `keys` = '".$data['keys']."' and id <> $id");
			if($ssql){
				_messageerror("keys已存在");
			}
			$sql = Assemblydata($data,'category_keys','up',[["id",$id]]);
			$this->db->Query($sql);
			_message('修改成功',WEB_PATH.'/'.ROUTE_M.'/category/keyslist');
		}
		$sql = Assemblydata(["cateid","name"],'category',"sel",[['model',2]]);
		$category = $this->db->GetList($sql);
		$sql1 = Assemblydata(['*'],'category_keys',"sel",[['id',$id]]);
		$res = $this->db->GetOne($sql1);
		include $this->tpl(ROUTE_M,'category.editkey');
	}

	public function delkey(){
		$id = $this->segment(4);
		$this->db->Query('delete from `@#_category_keys` where id='.$id);
		_message('删除成功',WEB_PATH.'/'.ROUTE_M.'/category/keyslist');
	}

	//栏目列表
	public function jifen_lists(){			
		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = 3 order by `parentid` ASC,`order` DESC",array('key'=>'cateid'));
		$models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		foreach($categorys as &$v){
			$href = $this->_cfg['web_path']."/index/pages/glist/index?cateid=".$v['cateid'];
			$v['href'] = $href;
			$v['info'] = safe_unserialize($v['info']);
			$v['typename']=cattype($v['model']);		
			if($v['model']==-1){ 
				$v['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/danweb/';
			}
			if($v['model']==-2){
				$v['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/link/';
			}
			if($v['model']>0){
				$v['addsun']=G_ADMIN_PATH.'/'.ROUTE_C.'/addcate/def/';
				$v['model']=$models[$v['model']]['name'];
			}else{
				$v['model']='';
			}		
			$v['editcate']=G_ADMIN_PATH.'/'.ROUTE_C.'/jifen_cate_edit/';
			$v['delcate']=G_ADMIN_PATH.'/'.ROUTE_C.'/delcate/';			
			// $categorys[$v['cateid']]=$v;
			$v['is_jf']=$v['is_jfclass'] == 0 ? "普通商品" : "积分商品";
		}	
		$this->ment=array(
			array("jifen_lists","直购商品分类",ROUTE_M.'/'.ROUTE_C."/jifen_lists"),
			array("jifen_cate_add","添加直购商品分类",ROUTE_M.'/'.ROUTE_C."/jifen_cate_add"),
		);
		include $this->tpl(ROUTE_M,'category.jifen_list');		
	}

	public function jifen_cate_add(){
		$this->ment=array(
			array("jifen_lists","直购商品分类",ROUTE_M.'/'.ROUTE_C."/jifen_lists"),
			array("jifen_cate_add","添加直购商品分类",ROUTE_M.'/'.ROUTE_C."/jifen_cate_add"),
		);
		$catetype=$this->segment(4);			//类型	
		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE 1 and model = 3 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		$models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		// $tree=System::load_sys_class('tree');
		// $tree->icon = array('│ ','├─ ','└─ ');
		// $tree->nbsp = '&nbsp;';
		// $categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";
		// $tree->init($categorys);
		// $categoryshtml=$tree->get_tree(0,$categoryshtml);
		$topmodel='';
		if($topcat=intval($this->segment(5))){
			$topcat=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$topcat' LIMIT 1");
			if(!$topcat)_message("参数错误");		
			// $categoryshtml.="<option value='$topcat[cateid]' selected>≡ $topcat[name] ≡</option>";
			if($topcat['model']>0){
				$modelname=$models[$topcat['model']]['name'];
				$topmodel="<option value=\"$topcat[model]\" selected>≡ $modelname ≡</option>";
			}
		}
		$categoryshtml = '';
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
		$info=array();
		$mesage=NULL;
		$languages = $this->getLanguageList(1);
		if(isset($_POST['dosubmit'])){	
			$info = $_POST['info'];
			if(!empty($mesage))_message($mesage,null,3);
			$setting['thumb']=$_POST['pic_url'];
			$setting['des']=$info['description'];
			unset($info['description']);
			$setting=serialize($setting);
			$info['info'] = $setting;
			$info['model'] = 3;
			$language = $_POST['languages'];
			$language = serialize($language);
			$info['languages'] = $language;
			// var_export($info);
			// die;		
			if($info['modelid']==0){$mesage='请选择数据模型';}				
			if(empty($info['name'])){$mesage='栏目名不能为空';}
			if(empty($info['catdir'])){$mesage='英文名不能为空';}				
			
			
			$sql = Assemblydata($info,'category');
			// Psql($sql);
		    $res = $this->db->Query($sql);
			if($res){
                _message('添加成功',WEB_PATH.'/'.ROUTE_M.'/category/jifen_lists');
            }else{
                _messageerror('添加失败');
            }

			
		}
		include $this->tpl(ROUTE_M,'category.jifen_add');
	}
	public function jifen_cate_edit(){
		$this->ment=array(
			array("jifen_lists","直购商品分类",ROUTE_M.'/'.ROUTE_C."/jifen_lists"),
			array("jifen_cate_edit","修改直购商品分类",ROUTE_M.'/'.ROUTE_C."/jifen_cate_edit",2),
		);
		$id=$this->segment(4);			//类型	
		$res = $this->db->GetOne("SELECT * FROM `@#_category` WHERE cateid = $id");

		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE model = 3 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
		$models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));
		$categoryshtml = '';
		foreach($categorys as &$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
			if($res['parentid'] == $cat['cateid']){
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
		// $categoryshtml=$tree->get_tree($res["parentid"],$categoryshtml);
		$info=array();
		$mesage=NULL;
		$res['info'] = mb_unserialize($res['info']);
		$res['languages'] = mb_unserialize($res['languages']);
		$languages = $this->getLanguageList(1);
		if(isset($_POST['dosubmit'])){	
			$info = $_POST['info'];
			if(!empty($mesage))_message($mesage,null,3);
			$setting['thumb']=$_POST['pic_url'];
			$setting['des']=$info['description'];
			unset($info['description']);
			$setting=serialize($setting);
			$info['info'] = $setting;
			$info['model'] = 3;
			$info['parentid'] = $info['parentid']?:0;
			$language = $_POST['languages'];
			$language = serialize($language);
			$info['languages'] = $language;
			if($info['modelid']==0){$mesage='请选择数据模型';}				
			if(empty($info['name'])){$mesage='栏目名不能为空';}
			if(empty($info['catdir'])){$mesage='英文名不能为空';}				
			
			$sql = Assemblydata($info,'category',"up",[['cateid',$id]]);
			// Psql($sql);
		    $res = $this->db->Query($sql);
			if($res){
                _message('修改成功',WEB_PATH.'/'.ROUTE_M.'/category/jifen_lists');
            }else{
                _messageerror('修改失败');
            }
		}
		include $this->tpl(ROUTE_M,'category.jifen_add');
	}
}
?>
