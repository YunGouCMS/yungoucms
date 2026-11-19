<?php 
defined('G_IN_SYSTEM')or exit('');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global');
class template extends admin {

	private $templates=array();
	private $thistemp='';
	
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
		$this->ment=array(
		    array("init","短信模板设置",ROUTE_M.'/'.ROUTE_C."/init"),
		);
		$this->templates=System::load_sys_config("templates");
		// $this->thistemp=System::load_sys_config("system","templates_name");
		$this->thistemp = $this->getConfig("templates_name");
	}
	public function init(){
		$templates=array();
		$templates=$this->templates;
		$thistemp=$this->thistemp;
		$is_bg = [
            1 => '登录背景',
            2 =>  '登录框',
            3 => '登录背景',
        ];
        $is_enable = [
            1 => '<span style="color:red;">启用</span>',
            2 =>  '未启用',
        ];
        $recordlist = $this->db->GetList("SELECT `id`,`title`,`bgimg`,`is_bg`,`content`,`is_enable` FROM `@#_login_bg`");
        foreach ($recordlist as $k => $v){
            $recordlist[$k]['color1'] = $v['color2'] = "";
            $recordlist[$k]['is_bg'] = $is_bg[$v['is_bg']];

            $data = json_decode($v['content'],true);
            $recordlist[$k]['color1'] = $data['color1'];
            $recordlist[$k]['color2'] = $data['color2'];

            $recordlist[$k]['enable'] = $is_enable[$v['is_enable']];
        }
		include $this->tpl(ROUTE_M,'template.lists');
	}

	public function setTemplate(){
        $template=$_POST['template'];
		if(empty($template)){
			$this->jsonData->error('',"模版错误");
		}
		$this->setConfig("templates_name",$template);
		$this->jsonData->success();
    }

	public function off(){
		$temp=$this->segment(4);
		if(!isset($this->templates[$temp]))_message("没有这个模板");
		EditConfig("system","templates_name",$temp);
        $sql = $this->db->Query("UPDATE `@#_config` SET `value` = '$temp' WHERE `name` = 'templates_name'");
		echo 1;
	}
	public function edit(){
		$temp=$this->segment(4);
		if(!isset($this->templates[$temp]))_messageerror("没有这个模板");
		$template=$this->templates[$temp];		
		if(!is_writable(G_CONFIG.'templates.inc.php')) _messageerror('Please chmod  templates  to 0777 !');
		if(isset($_POST['dosubmit'])){			
			$new_template['name']=htmlspecialchars($_POST['name']);
			$new_template['dir']=htmlspecialchars($_POST['dir']);
			$new_template['html']=htmlspecialchars($_POST['html']);
			$new_template['author']=htmlspecialchars($_POST['author']);
            $new_template['img']=htmlspecialchars($_POST['img']);
            $new_template['color']=htmlspecialchars($_POST['color']);
            $temps=var_export($new_template,true);
			$this->templates[$temp]=$new_template;
			$html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.');";
			$html.="\n return ".var_export($this->templates,true);
			$html.="\n ?>";			
			$ok=file_put_contents(G_CONFIG.'templates.inc.php',$html);
			if($template['html'] != $new_template['html']){				
				rename(G_TEMPLATES.$template['dir'].DIRECTORY_SEPARATOR.$template['html'],G_TEMPLATES.$template['dir'].DIRECTORY_SEPARATOR.$new_template['html']);
			}
			if($ok){
				echo "<script>
				alert('修改成功');
				window.location.href='".G_MODULE_PATH.'/template/init'."';
				</script>";			
			}
		}
		include $this->tpl(ROUTE_M,'template.edit');
	}
	public function editColor(){
        $temp=$this->segment(4);
        $type = $_POST['type'];
        if(!isset($this->templates[$temp])){
            echo json_encode(['code'=>0]);
            exit();
        }

        $template=$this->templates[$temp];
        if(!is_writable(G_CONFIG.'templates.inc.php')) {
            echo json_encode(['code'=>0,'msg'=>'Please chmod  templates  to 0777 !']);
            exit();
        }
        if(isset($_POST['dosubmit'])){
            $new_template['name']=$template['name'];
            $new_template['dir']=$template['dir'];
            $new_template['html']=$template['html'];
            $new_template['author']=$template['author'];
            $new_template['img']=$template['img'];
            $new_template['color']=$type==1?htmlspecialchars($_POST['color']):$template['color'];
            $new_template['color1']=$type==2?htmlspecialchars($_POST['color']):$template['color1'];
            $temps=var_export($new_template,true);
            $this->templates[$temp]=$new_template;
            $html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.');";
            $html.="\n return ".var_export($this->templates,true);
            $html.="\n ?>";
            $ok=file_put_contents(G_CONFIG.'templates.inc.php',$html);
            if($ok){
                chmod(G_CONFIG.'templates.inc.php', 0777);
                echo json_encode(['code'=>1,'data'=>$ok]);
                exit();
            }
            echo json_encode(['code'=>0,'data'=>$ok]);
            exit();
        }
    }
	//模板列表
	public function see(){	
		$path=G_TEMPLATES.G_STYLE.DIRECTORY_SEPARATOR.G_STYLE_HTML.DIRECTORY_SEPARATOR;		
		$html_arr=scandir($path);
		array_Shift($html_arr);
		array_Shift($html_arr);
		$thistemplate=G_STYLE;
		$temps=$this->db->GetList("SELECT * FROM `@#_template` where `template` = '$thistemplate'",array('key'=>'template_name'));
		if(count($temps) != count($html_arr)){
			foreach($html_arr as $html){
				if(!isset($temps[$html])){
					$temps[$html]['template_name']=$html;
					$temps[$html]['des']='';
					$temps[$html]['template']=$thistemplate;
				}
			}
			}
		unset($thistemplate);		
		include $this->tpl(ROUTE_M,'template.see');
	}
	//更新模板简介
	public function updatades(){
		if(isset($_POST['submit'])){			
			array_pop($_POST);		
			$thistemplate=G_STYLE;
			$temps=$this->db->GetList("SELECT * FROM `@#_template` where `template` = '$thistemplate'",array('key'=>'template_name'));
			foreach($_POST as $key=>$val){
				//替换POST name
				$key=base64_decode($key);
				$val=htmlspecialchars($val);
				if(isset($temps[$key])){
					$this->db->Query("UPDATE `@#_template` SET `des` = '$val' where `template` = '$thistemplate' AND `template_name` = '$key'");
				}else{					
					$this->db->Query("INSERT INTO `@#_template` (`template_name`, `template`, `des`) VALUES ('$key', '$thistemplate', '$val')");
				}
			}
			_message("更新成功");
		}
	}
	//修改模板内容
	public function update(){
		$path=G_TEMPLATES.G_STYLE.DIRECTORY_SEPARATOR.G_STYLE_HTML.DIRECTORY_SEPARATOR;	
		$path.=base64_decode($this->segment(4));
		if(isset($_POST['submit'])){
			$content=stripslashes($_POST['content']);
			$ok=file_put_contents($path,$content);		
			if(gettype(file_put_contents($path,$content))!='integer'){			
				_message("模板修改不成功,请检查模板目录是否具有写入权限!");
			}
			_message("模板修改成功!",WEB_PATH.'/'.ROUTE_M.'/template/see');
		}
		if(file_exists($path)){
			$files=file_get_contents($path);
			$files=htmlspecialchars($files);
		}else{
			_message("模板不存在");
		}
		include $this->tpl(ROUTE_M,'template.update');
	}
	//新建模板 
	public function insert(){
		$path=G_TEMPLATES.G_STYLE.DIRECTORY_SEPARATOR.G_STYLE_HTML.DIRECTORY_SEPARATOR;
		$htmlcontent=<<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
     <body>
        	<h1>模板内容</h1>
     </body>       
</html>
HTML;
		$htmlcontent=htmlspecialchars($htmlcontent);
		include $this->tpl(ROUTE_M,'template.update');
	}
	
	public function email_temp(){	
        $this->ment = [
            array("lists","邮箱设置",ROUTE_M."/setting/email"),
            array("lists","邮箱模版设置",ROUTE_M."/template/email_temp"),
        ];
		$lists = $this->db->GetList("select * from `@#_caches` where mode = 2 order by id desc");
		include $this->tpl(ROUTE_M,'template.email');	
	}

	/**
	 * 添加邮箱模板
	 * @return void
	 */
	public function email_temp_add(){
		$this->ment = [
            array("lists","邮箱设置",ROUTE_M."/setting/email"),
            array("lists","邮箱模版设置",ROUTE_M."/template/email_temp"),
        ];
		if(isset($_POST['dosubmit'])){
			$title = $_POST['title'];
			$value = $_POST['value'];
			$key = trim($_POST['key']);
			$remark = $_POST['remark'];
			$desc = $_POST['desc'];
            $title = safe_replace($_POST['title']);
            $value = safe_replace($_POST['value']);
            $key = safe_replace(trim($_POST['key']));
            $remark = safe_replace($_POST['remark']);
            $desc = safe_replace($_POST['desc']);

			if(!$key){
				_message("模板标识不能为空！",WEB_PATH.'/'.ROUTE_M."/template/email_temp");
			}
			$t = $this->db->GetOne("select * from `@#_caches` where mode = 2 and `key` = '$key'");
			if($t){
			    _message("模板标识已存在！",WEB_PATH.'/'.ROUTE_M."/template/email_temp");
			}
			$sql = Assemblydata([
					"title"=>$title,
					"key"=>$key,
					"value"=>$value,
					"remark"=>$remark,
					"mode"=>2,
					"type"=>1,
					"desc"=>$desc
				],"caches","in");
			$res = $this->db->Query($sql);
			if($res){
				_message("邮件模板添加成功！",WEB_PATH.'/'.ROUTE_M."/template/email_temp");
			}else{
				_message("邮件模板添加失败！");
			}
		}
		include $this->tpl(ROUTE_M,'template.email.edit');
	}
	/**
	 * 修改邮箱模板
	 * @return void
	 */
	public function email_temp_edit(){
		$this->ment = [
            array("lists","邮箱设置",ROUTE_M."/setting/email"),
            array("lists","邮箱模版设置",ROUTE_M."/template/email_temp"),
        ];
		$id = $this->segment(4);
		$record = $this->db->GetOne("select * from `@#_caches` where `id`='$id'");
		if(isset($_POST['dosubmit'])){
            $title = safe_replace($_POST['title'])?:$record['title'];
            $value = safe_replace($_POST['value'])?:$record['value'];
            $remark = safe_replace($_POST['remark'])?:$record['remark'];
            $desc = safe_replace($_POST['desc'])?:$record['desc'];
			$sql = Assemblydata([
					"title"=>$title,
					"value"=>$value,
					"remark"=>$remark,
					"desc"=>$desc
				],"caches","up",[['id',$id]]);
			$res = $this->db->Query($sql);
			if($res){
				_message("邮件模板更新成功！",WEB_PATH.'/'.ROUTE_M."/template/email_temp");
			}else{
				_message("邮件模板更新失败！");
			}
		}
		include $this->tpl(ROUTE_M,'template.email.edit');
	}
	
	public function mobile_temp(){
		$config = System::load_sys_config("mobile");
		//注册验资
        $lists = $this->db->GetList("select * from `@#_caches` where `id` in(4,5,9,10,11,12,16,17,18,19,20,21,22,23,24,25)");
		include $this->tpl(ROUTE_M,'template.mobile');
	}
	//设置模板是否启用
	public function up_mbstatus(){
	    $data = $_POST;
	    $sql = $this->db->Query("update `@#_caches` set `type` ='$data[status]' where `id` = '$data[id]'");
	    if($sql){
	        echo json_encode(['code' => 1]);
        }else{
            echo json_encode(['code' => 2]);
        }
    }
    //修改模板
    public function templateDetails(){
	    $id = $this->segment(4);
	    $data = $this->db->GetOne("SELECT * FROM `@#_caches` where `id` = $id");
		$data['title'] = $this->lgs[$data['title']]?:$data['title'];
        if(isset($_POST['value'])){
            $sql = $this->db->Query("update `@#_caches` set `value` ='$_POST[value]' where `id` = '$_POST[id]'");
            if($sql){
                echo json_encode(['code' => 1]);
            }else{
                echo json_encode(['code' => 2]);
            }
            exit;
        }
        include $this->tpl(ROUTE_M,'template.details');
    }
	
}
?>