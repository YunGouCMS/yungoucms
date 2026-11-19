<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_sys_class('PHPExcel');
class wap extends admin {
    private $db;
    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class('model');
        $this->ment=array(
            array("navigation","幻灯片管理",ROUTE_M.'/'.ROUTE_C),
            array("navigation","添加幻灯片",ROUTE_M.'/'.ROUTE_C."/add"),
            array("navigation","修改幻灯片",ROUTE_M.'/'.ROUTE_C."/update",1),
            array("navigation","直购幻灯片管理",ROUTE_M.'/'.ROUTE_C."/jifen_init"),
            array("navigation","添加直购幻灯片",ROUTE_M.'/'.ROUTE_C."/jifen_add"),
            array("navigation","修改直购幻灯片",ROUTE_M.'/'.ROUTE_C."/jifen_update",1),
            array("navigation","PC端幻灯管理",ROUTE_M."/slide/init"),
            array("navigation","PC端添加幻灯片",ROUTE_M."/slide/add"), 
        ); 
    }

    public function jifen_init(){
        $this->init(2);
    }
    public function jifen_add(){
        $this->add(2);
    }
    public function jifen_update(){
        $this->update(2);
    }
    public function jifen_delete(){
        $this->delete(2);
    }

    public function init($model=1){
        $where = ' and model=' . $model;
        $lists=$this->db->GetList("SELECT * FROM `@#_wap` where type='0' and module = 1 $where ORDER BY `language` , `sort`");
        $language = $this->getLanguageList(1);
        $lgs = array_column($language,"country","lg_value");
        foreach($lists as &$vl){
            $vl['language'] = $lgs[$vl['language']];
        }
        include $this->tpl(ROUTE_M,'wap_list');
    }

    //排序
    public function listorder(){
        if($this->segment(4)=='dosubmit'){
            foreach($_POST['listorders'] as $id => $listorder){
                $id = intval($id);
                $listorder = intval($listorder);
                $this->db->Query("UPDATE `@#_wap` SET `sort` = '$listorder' where `id` = '$id'");
            }
            _message("排序更新成功");
        }else{
            _messageerror("请排序");

        }		

    }	

    public function add($model=1){
        if(isset($_POST['submit'])){
        $title=htmlspecialchars(trim($_POST['title']));
        $link=htmlspecialchars(trim($_POST['link']));
        $status=htmlspecialchars(trim($_POST['status']));
        // $title2=htmlspecialchars(trim($_POST['title2']));
        $pop_window=htmlspecialchars(trim($_POST['pop_window']));
        $language=htmlspecialchars(trim($_POST['language']));
        $bg_color=htmlspecialchars(trim($_POST['bg_color']));
        $img=$_POST['img'];
        if(empty($img)){
            _messageerror("请上传图片");
        }
        $data = [
            'title' => $title,
            'link' => $link,
            'status' => $status,
            'pop_window' => $pop_window,
            'language' => $language,
            'bg_color' => $bg_color,
            'img' => $img,
            'model' => $model
        ];
        $sql = Assemblydata($data, 'wap');
        // $this->db->Query("insert into `@#_wap`(`title`,`link`,`img`,`status`, `pop_window`,`language`,`model`) values('$title','$link','$img','$status','$pop_window','$language','$model') ");
        $this->db->Query($sql);
            if($this->db->affected_rows()){
                if($model == 1){
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C);
                }else{
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/jifen_init');
                }
            }else{
                _messageerror("添加失败");
            }
        }
        $languages = $this->getLanguageList(1);
        include $this->tpl(ROUTE_M,'wap_add');
    }

    public function delete($model=1){
        $id=intval($this->segment(4)); 
            $this->db->Query("DELETE FROM `go_wap` WHERE (`id`='$id')");
            if($this->db->affected_rows()){
                if($model == 1){
                    _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C);
                }else{
                    _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/jifen_init');
                }
            }else{
                _message("删除失败");
            }  
    }

    public function batch_del(){
        $ids = $_POST['ids'];
        $this->db->Autocommit_start();
        $res = $this->db->Query("delete from `@#_wap` where id IN($ids)");
        if($res){
        $this->db->Autocommit_commit();
            echo'0';
        }else{
        $this->db->Autocommit_rollback();
            echo '1';
        }
    }
    public function update($model = 1){
        $id=intval($this->segment(4));
        $wapone=$this->db->Getone("SELECT * FROM `@#_wap` where `id`='$id'");	
        $languages = $this->getLanguageList();
        
        if(isset($_POST['submit'])){
            $title=htmlspecialchars(trim($_POST['title']));	
            $link=htmlspecialchars(trim($_POST['link']));	
            // $title2=htmlspecialchars(trim($_POST['title2']));
            $status=htmlspecialchars(trim($_POST['status']));
            $pop_window=htmlspecialchars(trim($_POST['pop_window']));
            $language=htmlspecialchars(trim($_POST['language']));
            $bg_color=htmlspecialchars(trim($_POST['bg_color']));
            $img = $_POST['upimage'];
            $sql = "UPDATE `@#_wap` SET `img`='$img',`title`='$title',`link`='$link',`status`='$status', `pop_window`='$pop_window',`language`='$language',`bg_color`='$bg_color' WHERE `id`=$id";
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                if($model == 1){
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C);
                }else{
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/jifen_init');
                }
            }else{
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'wap_update');
    }
    public function up_dhstatus(){
        $id=$_POST['id'];
        $status=$_POST['status'];
        $info=$this->db->Getone("select * from `@#_wap` where id='$id'");

        if($info){
            $res=$this->db->Query("UPDATE `@#_wap` SET `status`='$status' where id='$id'");
        }
        if($res){
            echo 1;
        }else{
            echo 0;
        }
        
    }
    public function jifen_dh_list(){
        $this->dh_list(2);
    }
    public function jifen_dh_add(){
        $this->dh_add(2);
    }
    public function jifen_dh_update(){
        $this->dh_update(2);
    }

    //导航列表
    public function dh_list($model = 1){ 
        $this->ment=array(
            array("navigation","导航栏管理",ROUTE_M.'/'.ROUTE_C."/dh_list"),
            array("navigation","添加导航栏",ROUTE_M.'/'.ROUTE_C."/dh_add"), 
            array("navigation","直购导航栏管理",ROUTE_M.'/'.ROUTE_C."/jifen_dh_list"),
            array("navigation","添加直购导航栏",ROUTE_M.'/'.ROUTE_C."/jifen_dh_add"), 
            array("navigation","PC端导航栏管理",ROUTE_M."/ments/navigation"), 
            array("navigation","添加PC端导航栏",ROUTE_M."/ments/addnav"), 
        );
        $lgs = $this->getLanguageList(1);
        array_unshift($lgs,["lg_name"=>"通用","lg_value"=>"","country"=>"通用"]);
        array_unshift($lgs,["lg_name"=>"全部","lg_value"=>"-1","country"=>"所有"]);
        $lg = $this->db->GetList("select * from `@#_language` where status = 1");
        $language = array_column($lg?:[],"lg_value");
        $language[] = "";
        $language_str = implode("','",$language);
        $where = 'and `model` = '.$model;
        if(isset($_POST['sososubmit'])){
            if(isset($_POST['title']) && $_POST['title']){
                $where .= " and title like '%{$_POST['title']}%'";
            }
            if(isset($_POST['language'] ) && $_POST['language']!=-1){
                $where .= " and language = '{$_POST['language']}'";
            }
            $lists=$this->db->GetList("SELECT * FROM `@#_wap` where type>0 and module = 1 $where order by language, sort ");
        }else{
            if($language_str){
                $lists=$this->db->GetList("SELECT * FROM `@#_wap` where type>0 and module = 1 $where and (language in ('$language_str'))  order by language, sort ");
            }else{
                $lists=$this->db->GetList("SELECT * FROM `@#_wap` where type>0 and module = 1 $where order by language, sort ");
            }
        }
        $lgs_arr = array_column($lg,"country","lg_value");
        foreach($lists as $key=>$vl){
            $lists[$key]['language'] = $lgs_arr[$vl['language']];
        }
        include $this->tpl(ROUTE_M,'dh_list');
    }

    //导航添加
    public function dh_add($model = 1){
        $this->ment=array(
            array("navigation","导航栏管理",ROUTE_M.'/'.ROUTE_C."/dh_list"),
            array("navigation","添加导航栏",ROUTE_M.'/'.ROUTE_C."/dh_add"), 
            array("navigation","直购导航栏管理",ROUTE_M.'/'.ROUTE_C."/jifen_dh_list"),
            array("navigation","添加直购导航栏",ROUTE_M.'/'.ROUTE_C."/jifen_dh_add"), 
        );
        if(isset($_POST['submit'])){
            $code=htmlspecialchars(trim($_POST['code'])); 
            $title=htmlspecialchars(trim($_POST['title'])); 
            $link=htmlspecialchars(trim($_POST['link']));
            $type=htmlspecialchars(trim($_POST['type']));
            $language=htmlspecialchars(trim($_POST['language']));
            $img=$_POST['thumb'];
            $picarr = serialize($_POST['picarr']);
            $data = serialize($_POST['data']);
            $sql = "insert into `@#_wap`(`code`,`title`,`link`,`img`,`type`,`language`,`picarr`,`data`,`model`) values('$code','$title','$link','$img','$type','$language','$picarr','$data',$model) ";
            $this->db->Query($sql);	
            if($this->db->affected_rows()){
                if($model==1){
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/dh_list");
                }else{
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_dh_list");
                }
            }else{
                    _message("添加失败");
            }
        }
        $languages = $this->getLanguageList();
        // include $this->tpl(ROUTE_M,'dh_add');
        include $this->tpl(ROUTE_M,'dh_update');
    }

    public function dh_update($model = 1){
        $this->ment=array(
            array("navigation","导航栏管理",ROUTE_M.'/'.ROUTE_C."/dh_list"),
            array("navigation","添加导航栏",ROUTE_M.'/'.ROUTE_C."/dh_add"), 
            array("navigation","直购导航栏管理",ROUTE_M.'/'.ROUTE_C."/jifen_dh_list"),
            array("navigation","添加直购导航栏",ROUTE_M.'/'.ROUTE_C."/jifen_dh_add"), 
        );
        $id=intval($this->segment(4));
        $wapone=$this->db->Getone("SELECT * FROM `@#_wap` where `id`='$id'");	
        $wapone['picarr'] = unserialize($wapone['picarr']);
        $wapone['data'] = unserialize($wapone['data']);
        if(isset($_POST['submit'])){
            $code=htmlspecialchars(trim($_POST['code']));	
            $title=htmlspecialchars(trim($_POST['title']));	
            $link=htmlspecialchars(trim($_POST['link']));	
            $language=htmlspecialchars(trim($_POST['language']));	

            $img = $_POST['thumb'];
            $picarr = serialize($_POST['picarr']);
            $data = serialize($_POST['data']);
            $this->db->Query("UPDATE `@#_wap` SET `img`='$img',`code`='$code',`title`='$title',`link`='$link',`language`='$language',`picarr`='$picarr',`data`='$data' WHERE `id`=$id");
            if($this->db->affected_rows()){
                if($model==1){
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/dh_list");
                }else{
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_dh_list");
                }
            }else{
                _messageerror("修改失败");
            }
        }
        $languages = $this->getLanguageList();
        include $this->tpl(ROUTE_M,'dh_update');
    }

    public function dh_delete($model=1){
        $id=intval($this->segment(4)); 
            $this->db->Query("DELETE FROM `go_wap` WHERE (`id`='$id')");
            if($this->db->affected_rows()){
                if($model==1){
                    _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/dh_list");
                }else{
                    _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_dh_list");
                }
            }else{
                _messageerror("删除失败");
            }  
    }
    //底部列表
    public function db_list($model = 1){ 
        $this->ment=array(
            array("navigation","底部栏管理",ROUTE_M.'/'.ROUTE_C."/db_list"),
            array("navigation","添加底部栏",ROUTE_M.'/'.ROUTE_C."/db_add"), 
        );
        $lgs = $this->getLanguageList(1);
        array_unshift($lgs,["lg_name"=>"通用","lg_value"=>""]);
        array_unshift($lgs,["lg_name"=>"全部","lg_value"=>"-1"]);
        $lg = $this->db->GetList("select * from `@#_language` where status = 1");
        $language = array_column($lg,"lg_value");
        $language[] = "";
        $language_str = implode("','",$language);
        $where = 'and `model` = '.$model;
        if(isset($_POST['do_submit'])){
            if(isset($_POST['title']) && $_POST['title']){
                $where .= " and title like '%{$_POST['title']}%'";
            }
            if(isset($_POST['language'] ) && $_POST['language']!=-1){
                $where .= " and language = '{$_POST['language']}'";
            }
            $lists=$this->db->GetList("SELECT * FROM `@#_wap` where type>0 and module = 2 $where order by language, sort ");
        }else{
            $lists=$this->db->GetList("SELECT * FROM `@#_wap` where type>0 and module = 2 $where and (language in ('$language_str') or language is null)  order by language, sort ");
        }
        $lgs_arr = array_column($lg,"country","lg_value");
        foreach($lists as $key=>$vl){
            $lists[$key]['language'] = $lgs_arr[$vl['language']];
        }
        include $this->tpl(ROUTE_M,'db_list');
    }

    //底部添加
    public function db_add($model = 1){
        $this->ment=array(
            array("navigation","底部栏管理",ROUTE_M.'/'.ROUTE_C."/db_list"),
            array("navigation","添加底部栏",ROUTE_M.'/'.ROUTE_C."/db_add"), 
        );
        if(isset($_POST['submit'])){
            $title=htmlspecialchars(trim($_POST['title'])); 
            $link=htmlspecialchars(trim($_POST['link']));
            $type=htmlspecialchars(trim($_POST['type']));
            $language=htmlspecialchars(trim($_POST['language']));
            $img=$_POST['image'];
            $picarr = $_POST['picarr'];
            if(empty($title)){
                _messageerror("名称不能为空");
            }
            $sql = Assemblydata([
                "title"=>$title,
                "link"=>$link,
                "type"=>$type,
                "img"=>$img,
                "picarr"=>$picarr,
                "language"=>$language,
                "model"=>1,
                "module"=>2,
            ],"wap");
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                if($model==1){
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/db_list");
                }else{
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_dh_list");
                }
            }else{
                    _message("添加失败");
            }
        }
        $languages = $this->getLanguageList(1);
        include $this->tpl(ROUTE_M,'db_add');
    }

    public function db_update($model = 1){
        $this->ment=array(
            array("navigation","底部栏管理",ROUTE_M.'/'.ROUTE_C."/db_list"),
            array("navigation","添加底部栏",ROUTE_M.'/'.ROUTE_C."/db_add"), 
            array("navigation","修改底部栏",ROUTE_M.'/'.ROUTE_C."/db_update",1), 
        );
        $id=intval($this->segment(4));
        $wap=$this->db->Getone("SELECT * FROM `@#_wap` where `id`='$id'");	
        if(isset($_POST['submit'])){
            $title=htmlspecialchars(trim($_POST['title'])); 
            $link=htmlspecialchars(trim($_POST['link']));
            $type=htmlspecialchars(trim($_POST['type']));
            $language=htmlspecialchars(trim($_POST['language']));
            $img=$_POST['image'];
            $picarr = $_POST['picarr'];
            if(empty($title)){
                _messageerror("名称不能为空");
            }
            $sql = Assemblydata([
                                    "title"=>$title,
                                    "link"=>$link,
                                    "type"=>$type,
                                    "img"=>$img,
                                    "picarr"=>$picarr,
                                    "language"=>$language,
                                    "model"=>1,
                                    "module"=>2,
                                ],"wap","up",[["id",$id]]);
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                if($model==1){
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/db_list");
                }else{
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_dh_list");
                }
            }else{
                _messageerror("修改失败");
            }
        }
        $languages = $this->getLanguageList(1);
        include $this->tpl(ROUTE_M,'db_add');
    }

    public function db_delete($model=1){
        $id=intval($this->segment(4)); 
            $this->db->Query("DELETE FROM `go_wap` WHERE (`id`='$id')");
            if($this->db->affected_rows()){
                if($model==1){
                    _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/db_list");
                }else{
                    _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_dh_list");
                }
            }else{
                _messageerror("删除失败");
            }  
    }

    public function up_dbstatus(){
        $id=$_POST['id'];
        $info=$this->db->Getone("select * from `@#_wap` where id='$id'");
        $status=isset($_POST['status'])?$_POST['status']:$info['status'];
        $type=isset($_POST['type'])?$_POST['type']:$info['type'];

        if($info){
            $data["status"]=$status;
            $data['type']=$type;
            $sql = Assemblydata($data,"wap","up",[["id",$id]]);
            // $res=$this->db->Query("UPDATE `@#_wap` SET `status`='$status' where id='$id'");
            $res=$this->db->Query($sql);
        }
        if($res){
            echo 1;
        }else{
            echo 0;
        }
        
    }

    //货币设置
    public function currency(){
        $this->ment=array(
            array("navigation","货币列表",ROUTE_M.'/'.ROUTE_C."/currency"),
            array("navigation","添加货币",ROUTE_M.'/'.ROUTE_C."/currencyadd"),
            array("navigation","物流列表",ROUTE_M.'/'.ROUTE_C."/set_logistics"),
            array("navigation","添加物流",ROUTE_M.'/'.ROUTE_C."/set_address_add"),
        );
        $info=$this->db->GetList("SELECT  * FROM `@#_currency` WHERE 1 ORDER BY id DESC ");
        $num = 20;
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $total = $this->db->GetCount("SELECT COUNT(id) FROM `@#_logistics`");
        $page=System::load_sys_class('page');
        $page->config($total,$num,$pagenum,"0");
        $data = $this->db->GetPage("SELECT * FROM `@#_logistics` ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        include $this->tpl(ROUTE_M,'web_currency');
    }

    //货币添加
    public function currencyadd(){
        $this->ment=array(
            array("navigation","货币列表",ROUTE_M.'/'.ROUTE_C."/currency"),	
            array("navigation","添加货币",ROUTE_M.'/'.ROUTE_C."/currencyadd"),
            array("navigation","物流列表",ROUTE_M.'/'.ROUTE_C."/set_logistics"),
            array("navigation","添加物流",ROUTE_M.'/'.ROUTE_C."/set_address_add"),
        );
        $title="添加";
        $languages = $this->getLanguageList(1);
        if($_POST){
            $data=$_POST;
            if($data['status'] == 1){
                $this->db->Query("UPDATE `@#_currency` SET status=0 WHERE 1");
            }
            $res=$this->db->Query("INSERT INTO `@#_currency`(`title`,`code`,`symbol_left`,`symbol_right`,`status`,`unit`,`language`)VALUES('$data[title]','$data[code]','$data[symbol_left]','$data[symbol_right]','$data[status]','$data[unit]','$data[language]')");
            if($res){
                _message("添加成功");
            }else{
                _messageerror("添加失败");
            }
        }

        include $this->tpl(ROUTE_M,'web_currencyadd');
    }

    //货币修改
    public function currencyedit(){
        $title="编辑";
        $id=intval($this->segment(4));
        if($id){
            $languages = $this->getLanguageList(1);
            $info=$this->db->GetOne("SELECT  * FROM `@#_currency` WHERE id='$id'");
            if($_POST){
                $data=$_POST;
                if($data['status'] == 1){
                    $this->db->Query("UPDATE `@#_currency` SET status=0 WHERE 1");
                }
                $htm = '';
                foreach($data as $k=>$v){
                    $htm.="`".$k."`='".$v."',";
                }
                $htm=rtrim($htm,',');
                $res=$this->db->Query("UPDATE `@#_currency` SET $htm WHERE id='$id'");
                if($res){
                    _message("修改成功");
                }else{
                    _messageerror("修改失败");
                }
            }
        }else{     
            _message("参数错误");
        }
        include $this->tpl(ROUTE_M,'web_currencyedit');
    }

    //货币删除
    public function currencyedel(){
        $id=intval($this->segment(4));
        if($id){
            $res=$this->db->Query("DELETE FROM `@#_currency` WHERE id='$id'");
            if($res){
                _message("删除成功");
            }else{
                _messageerror("删除失败");
            }
        }else{
            _messageerror("参数错误");
        }	
    }

    //地址管理
    public function set_addr(){
		$this->ment=array(
			array("navigation","地址管理",ROUTE_M.'/'.ROUTE_C."/set_addr"),
			array("navigation","添加国家",ROUTE_M.'/'.ROUTE_C."/set_addradd"),
		);

		// 所有国家/地区分组列表（作为“国家”模块的数据源），按添加顺序从旧到新显示
		$areas = $this->db->GetList("SELECT * FROM `@#_area` ORDER BY id ASC");

		// 当前选中的国家 ID，默认选中第一个
		$current_id   = 0;
		$current_area = null;
		$lists        = [];

		if ($areas) {
			$current_id = isset($_GET['id']) ? intval($_GET['id']) : $areas[0]['id'];

			foreach ($areas as $item) {
				if ($item['id'] == $current_id) {
					$current_area = $item;
					break;
				}
			}

			// 当前国家下的省 / 市（顶级，市、区默认折叠由前端控制）
			$lists = $this->db->GetList("SELECT * FROM `@#_area_details` WHERE `area_id` = $current_id AND `pid` = 0 ORDER BY `sort`,`code`,`name` DESC");
		}

		include $this->tpl(ROUTE_M,'web_addr');	
    }

    //地址添加
    public function set_addradd(){
        $title="添加";
        $this->ment=array(
            array("navigation","地址管理",ROUTE_M.'/'.ROUTE_C."/set_addr"),	
            array("navigation","添加国家",ROUTE_M.'/'.ROUTE_C."/set_addradd"),
        );
        $lg = $this->getLanguageList(1);
        if(isset($_POST['dosubmit'])){
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $language = $_POST['language'];
            $data = [
                'name' => $name,
                'desc' => $desc,
                'language' => $language,
                'create_time' => time(),
            ];
            $res = $this->db->Insert('area',$data);
            if($res){
                _message("添加成功",WEB_PATH . '/'.ROUTE_M.'/'.ROUTE_C."/set_addr");
            }else{
                _messageerror("添加失败");
            }
        }
        include $this->tpl(ROUTE_M,'web_addradd');	
    }

    //地址编辑
    public function set_addredit(){
        $title="编辑";
        $this->ment=array(
            array("navigation","地址管理",ROUTE_M.'/'.ROUTE_C."/set_addr"),	
            array("navigation","添加地区群组",ROUTE_M.'/'.ROUTE_C."/set_addradd"),
            array("navigation","编辑地区群组",ROUTE_M.'/'.ROUTE_C."/set_addredit"),
        );
        $id=intval($this->segment(4));
        $lg = $this->getLanguageList(1);
        $res = $this->db->GetOne("SELECT * FROM `@#_area` WHERE id = $id");
        if(isset($_POST['dosubmit'])){
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $language = $_POST['language'];
            $data = [
                'name' => $name,
                'desc' => $desc,
                'language' => $language,
            ];
            $sql = Assemblydata($data,"area","up",[['id',$id]]);
            $res = $this->db->Query($sql);
            if($res){
                _message("修改成功",WEB_PATH . '/'.ROUTE_M.'/'.ROUTE_C."/set_addr");
            }else{
                _messageerror("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'web_addradd');	
    }

    //地址删除
    public function set_addrdel(){
        $id=intval($this->segment(4));
        $res = $this->db->Query("DELETE FROM `@#_area` WHERE id = $id");
        $res1 = $this->db->Query("DELETE FROM `@#_area_details` WHERE area_id = $id");
        $this->db->Autocommit_start();
        if($res && $res1){
            $this->db->Autocommit_commit();
            _message("删除成功");
        }else{
            $this->db->Autocommit_rollback();
            _messageerror("删除失败");  
        }
    }

    //地址下属
    public function set_addrchild(){
        $this->ment=array(
            array("navigation","地址管理",ROUTE_M.'/'.ROUTE_C."/set_addr"),	
            array("navigation","添加国家",ROUTE_M.'/'.ROUTE_C."/set_addradd"),
            array("navigation","地址下属",ROUTE_M.'/'.ROUTE_C."/set_addrchild"),
        );
        $id=intval($this->segment(4));
        $res = $this->db->GetOne("SELECT * FROM `@#_area` WHERE id = $id");
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $num = 20;
        $sql = "SELECT * FROM `@#_area_details` WHERE `area_id` = $id and pid = 0 ORDER BY `sort`,`code`,`name` DESC";
        $page = System::load_sys_class('page');
        $total = $this->db->GetCount($sql);
        $page->config($total,$num,$p,0);
        $lists = $this->db->GetPage($sql,['page'=>$p,'num'=>$num]);
        include $this->tpl(ROUTE_M,'web_addrchild');	
    }

    public function get_addrchild(){
        $id=intval($this->segment(4));
        $res = $this->db->GetList("SELECT * FROM `@#_area_details` WHERE pid = $id");
        $this->jsonData->success(200,"",$res);
    }

    //地址下属添加
    public function set_addrchild_add(){
        $this->ment=array(
            array("navigation","地址管理",ROUTE_M.'/'.ROUTE_C."/set_addr"),	
            array("navigation","添加国家",ROUTE_M.'/'.ROUTE_C."/set_addradd"),
            array("navigation","地址下属",ROUTE_M.'/'.ROUTE_C."/set_addrchild"),
            array("navigation","添加地址下属",ROUTE_M.'/'.ROUTE_C."/set_addrchild_add"),
        );
        $id = intval($this->segment(4));
        $res = $this->db->GetOne("SELECT * FROM `@#_area_details` WHERE id = $id");
        if(!$res){
            _messageerror("地址不存在");
        }
        if(isset($_POST['dosubmit'])){
            $data = [
                'name' => $_POST['name'],
                'desc' => $_POST['desc'],
                'code' => $_POST['code'],
                'area_id' => $res['area_id'],
                'pid' => $id,
                "status" => 1,
                'create_time' => time(),
                'update_time' => time(),
            ];
            $sql = Assemblydata($data,"area_details");
            // die($sql);
            $res = $this->db->Query($sql);
            if($res){
                _message("添加成功",WEB_PATH . '/' .ROUTE_M.'/'.ROUTE_C."/set_addrchild/".$res['area_id']);
            }else{
                _messageerror("添加失败");
            }
        }
        include $this->tpl(ROUTE_M,'web_addrchildadd');	
    }

    //地址下属编辑
    public function set_addrchild_edit(){
        $this->ment=array(
            array("navigation","地址管理",ROUTE_M.'/'.ROUTE_C."/set_addr"),	
            array("navigation","添加国家",ROUTE_M.'/'.ROUTE_C."/set_addradd"),
            array("navigation","地址下属",ROUTE_M.'/'.ROUTE_C."/set_addrchild"),
        );
        $id = intval($this->segment(4));
        $recod = $this->db->GetOne("SELECT * FROM `@#_area_details` WHERE id = $id");
        $res = $this->db->GetOne("SELECT * FROM `@#_area_details` WHERE id = $recod[pid]");
        if(isset($_POST['dosubmit'])){
            $data = [
                'name' => $_POST['name'],
                'desc' => $_POST['desc'],
                'code' => $_POST['code'],
                'area_id' => $recod['area_id'],
                "status" => 1,
                'update_time' => time(),
            ];
            $sql = Assemblydata($data,"area_details","up",[['id',$id]]);
            // die($sql);
            $res = $this->db->Query($sql);
            if($res){
                _message("修改成功",WEB_PATH . '/' .ROUTE_M.'/'.ROUTE_C."/set_addrchild/".$recod['area_id'],);
            }else{
                _messageerror("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'web_addrchildadd');	
    }
    //地址下属删除
    public function set_addrchild_del(){
        $id = intval($this->segment(4));
        $recod = $this->db->GetOne("SELECT * FROM `@#_area_details` WHERE id = $id");
        $ids = $id;
        do{
            $pids = $this->db->GetList("SELECT id,pid FROM `@#_area_details` WHERE pid in ($ids)");
            $ids = '';
            foreach($pids?:[] as $k=>$v){
                if($ids) $ids .=",";               
                $ids .= $v['id'];
                $res = $this->db->Query("DELETE FROM `@#_area_details` WHERE id = $v[id] and pid = $v[pid]");
            }
        }while($pids);
        $res = $this->db->Query("DELETE FROM `@#_area_details` WHERE id = $id");
        if($res){
            _message("删除成功",WEB_PATH . '/' .ROUTE_M.'/'.ROUTE_C."/set_addrchild/".$recod['area_id'],);
        }else{
            _messageerror("删除失败");
        }
    }

    //导入模版下载
    public function set_addrchild_import_down(){
        $filename = "statics/plugin/import/省市区街道导入模版.xlsx";
        $filetype = "application/vnd.ms-excel";
        header("Content-type: $filetype");
        header("Content-Disposition: attachment; filename=" . basename($filename));
        header("Content-Transfer-Encoding: binary");
        header("Content-length: " . filesize($filename));
        header("Cache-control: private"); //use this to open files directly
        readfile($filename);
    }

    //地址下属导入
    public function set_addrchild_import(){
        $id=intval($this->segment(4));
        $res = $this->db->GetOne("SELECT * FROM `@#_area` WHERE id = $id");
        if(!$res){
            _messageerror("地址不存在");
        }        
        if($_FILES){
            //设置超时时间
            set_time_limit(0);
            //设置内存限制
            ini_set('memory_limit', '1024M');
            $type='doc,ppt,wps,xls,xlsx';
            $path='excel';
            $upload=System::load_sys_class('upload','sys','no');
            foreach($_FILES as $k=>$v){
                if($v['error'] == '0'){
                    upload::upload_config($path,$type);
                    $res=upload::go_uploads($_FILES[$k],'address');
                    if(!$res['ok']){
                        _messageerror($res['msg']);
                    }else{
                        $filedir=$path.'/'.$res['dir'];
                    }
                }
            }
            $filename="statics/uploads/".$filedir;
            $file_type = PHPExcel_IOFactory::identify($filename);//读取文件类型
            $obj_reader = PHPExcel_IOFactory::createReader($file_type);//获取文件操作对象
            $obj_reader->setLoadSheetsOnly('Sheet1');//只读取sheet的名称 多个可以用数组array(sheet1,sheet2...)
            $obj_PHPExcel = $obj_reader->load($filename);//加载文件
            $done=false;
            $count=0;
            //省/市 | 代码 | 区/县 | 代码 | 街道 | 代码 | 社区 | 代码 
            //哥谭市  01     哥谭区  0101    哥谭街道  010101    哥谭社区  01010101
            //哥谭市  01     哥谭区  0101    哥谭街道1  010102    哥谭社区  01010101
            $arr = [];
            foreach ($obj_PHPExcel->getWorksheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() < 2 ){
                        continue;
                    }
                    $datas=array();
                    foreach ($row->getCellIterator() as $cell) {
                        $data = $cell->getValue();
                        array_push($datas,$data);
                    }
                    $time = time();
                    $pid = 0;
                    
                    for($i=0;$i<count($datas);$i+=2){
                        // 生成缓存键
                        $cacheKey = "area_detail:".$datas[$i].":".$datas[$i+1]."-".$id;
                        
                        // 先从缓存中获取
                        $cachedData = $this->redisClass->redisGet($cacheKey);
                        $res = $cachedData['data'] ? json_decode($cachedData['data'], true) : null;
                        
                        if(!$res){
                            // 缓存中没有则查询数据库
                            if($datas[$i+1]){
                                $sql = "select * from go_area_details where area_id = $id and name='".$datas[$i]."' and `code` ='".$datas[$i+1]."' order by id desc limit 1";
                            }else{
                                $sql = "select * from go_area_details where area_id = $id and pid = $pid and name='".$datas[$i]."' order by id desc limit 1";
                            }
                            $res = $this->db->GetOne($sql);
                            if($res){
                                // 将结果存入缓存，设置过期时间为1小时
                                $this->redisClass->redisSetex($cacheKey, 3600, json_encode($res));
                            }
                        }
                        
                        if($res){
                            $childCacheKey = "area_detail_children:".$res['id'];
                            $cachedChildren = $this->redisClass->redisGet($childCacheKey);
                            $res1 = $cachedChildren['data'] ? json_decode($cachedChildren['data'], true) : null;
                            
                            if(!$res1){
                                $res1 = $this->db->GetOne("select * from go_area_details where pid = ".$res['id']);
                                if($res1){
                                    $this->redisClass->redisSetex($childCacheKey, 3600, json_encode($res1));
                                }
                            }
                        }
                        
                        if(!$res || !$res1){
                            $sql = Assemblydata([
                                "area_id"=>$id,
                                "pid"=>$pid,
                                "name"=>$datas[$i],
                                'create_time'=>$time,
                                'update_time'=>$time,
                                "code"=>$datas[$i+1],
                            ],"area_details");
                            $res = $this->db->query($sql);
                            if($res){
                                $pid = $this->db->insert_id();
                                $count++;
                                
                                // 清除相关缓存
                                $this->redisClass->redisDel($cacheKey);
                                $this->redisClass->redisDel("area_detail_children:".$pid);
                            }else{
                                break;
                            }
                        }else{
                            $pid = $res['id'];
                        }
                    }
                }
            }
            if($count)
            {
                _message("成功导入".$count."条");
            }else{
                _message("导入失败");
            }
        }
    }
    

    //物流管理
    public function set_logistics(){
        $this->ment=array(
            // array("navigation","货币列表",ROUTE_M.'/'.ROUTE_C."/currency"),	
            // array("navigation","添加货币",ROUTE_M.'/'.ROUTE_C."/currencyadd"),
            array("navigation","物流列表",ROUTE_M.'/'.ROUTE_C."/set_logistics"),
            array("navigation","添加物流",ROUTE_M.'/'.ROUTE_C."/set_address_add"),
        );
        $lgs = $this->getLanguageList(1);
        $lgs_str = '';
        foreach($lgs as $vl){
            if($lgs_str) $lgs_str .=",";
            $lgs_str .= "'{$vl['lg_value']}'";
        }
        $num = 20;
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $total = $this->db->GetCount("SELECT COUNT(id) FROM `@#_logistics` where language = '' or language in ($lgs_str)");
        $page=System::load_sys_class('page');
        $page->config($total,$num,$pagenum,"0");
        $data = $this->db->GetPage("SELECT * FROM `@#_logistics` where language = '' or language in ($lgs_str)",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $language = $this->getLanguageList(1);
        $lgs = array_column($language,"country","lg_value");
        foreach($data as &$vl){
            $vl['language'] = $lgs[$vl['language']]?:"通用";
        }
        include $this->tpl(ROUTE_M,'web_logistics');
    }

    //物流管理
    public function set_address_add(){
        if (!empty($_POST)){
            $is_state = $_POST['is_state'];
            $postData = $_POST['log_name'];
            $language = $_POST['language'];
            $is_free = $_POST['is_free'];
            $money = $_POST['money'];
            $ratio = $_POST['ratio'];
            $type = $_POST['type'];
            $isData = $this->db->GetOne("SELECT log_name FROM `@#_logistics` WHERE log_name='$postData' AND `language` = '$language'");
            if (!empty($isData)){
                echo json_encode(['code'=>2,'msg'=>'已存在物流公司']);
                exit;
            }else if(empty($postData)){
                echo json_encode(['code'=>2,'msg'=>'物流公司名不能为空']);
                exit;
            }
            $time = time();
            $sql = Assemblydata([
                "log_name"=>$postData,
                "create_time"=>$time,
                "update_time"=>$time,
                "is_state"=>$is_state,
                "language"=>$language,
                "is_free"=>$is_free,
                "type"=>$type,
                "money"=>$money,
                "ratio"=>$ratio,
            ],"logistics");
            // $res = $this->db->Query("INSERT INTO `@#_logistics` (`log_name`,`create_time`,`update_time`,`is_state`,`language`) VALUE ('$postData','$time','$time','$is_state','$language')");
            $res = $this->db->Query($sql);
            if ($res){
                echo json_encode(['code'=>1,'msg'=>'添加成功']);
                exit;
            }else{
                echo json_encode(['code'=>2,'msg'=>'添加失败']);
                exit;
            }
        }else{
            $this->ment=array(
                array("navigation","物流列表",ROUTE_M.'/'.ROUTE_C."/set_logistics"),
                array("navigation","添加物流",ROUTE_M.'/'.ROUTE_C."/set_address_add"),
            );
            $languages = $this->getLanguageList(1);
            include $this->tpl(ROUTE_M,'set_address_add');
        }
    }

    //物流管理
    public function set_address_edit(){
        if (!empty($_POST)){
            $log_name = $_POST['log_name'];
            $is_state = $_POST['is_state'];
            $language = $_POST['language'];
            $is_free = $_POST['is_free'];
            $type = $_POST['type'];
            $money = $_POST['money'];
            $ratio = $_POST['ratio'];
            $id = $_POST['ids'];
            $isData = $this->db->GetOne("SELECT log_name FROM `@#_logistics` WHERE log_name='$log_name' and id!='$id' and `language` = '$language'");
            if (!empty($isData)){
                echo json_encode(['code'=>2,'msg'=>'已存在物流公司']);
                exit;
            }else if(empty($log_name)){
                echo json_encode(['code'=>2,'msg'=>'物流公司名不能为空']);
                exit;
            }
            $time = time();
            $sql = Assemblydata([
                "log_name"=>$log_name,
                "update_time"=>$time,
                "is_state"=>$is_state,
                "language"=>$language,
                "is_free"=>$is_free,
                "type"=>$type,
                "money"=>$money,
                "ratio"=>$ratio,
            ],"logistics","up",[["id",$id]]);

            // $res = $this->db->Query("UPDATE `@#_logistics` SET log_name='$log_name',is_state='$is_state',`language`='$language' WHERE id='$id'");
            $res = $this->db->Query($sql);
            if ($res){
                echo json_encode(['code'=>1,'msg'=>'修改成功']);
                exit;
            }else{
                echo json_encode(['code'=>2,'msg'=>'修改失败']);
                exit;
            }
        }else{
            $getId = $this->segment(4);
            $data = $this->db->GetOne("SELECT * FROM `@#_logistics` WHERE id='$getId'");
            $this->ment=array(
                array("navigation","物流修改",ROUTE_M.'/'.ROUTE_C."/set_address_edit",2),
                array("navigation","物流列表",ROUTE_M.'/'.ROUTE_C."/set_logistics"),
            );
            $languages = $this->getLanguageList(1);
            // $sml = 
            include $this->tpl(ROUTE_M,'set_address_edit');
        }
    }

    //删除
    public function set_address_delete(){
        $getId = $_POST['id'];
        $res = $this->db->Query("DELETE FROM `@#_logistics` WHERE id='$getId'");
        if ($res){
            echo json_encode(['code'=>1,'msg'=>'删除成功']);
            exit;
        }else{
            echo json_encode(['code'=>2,'msg'=>'删除失败']);
            exit;
        }
    }

    //物流修改状态
    public function states (){
        $id = $_POST['id'];
        $is_state = $_POST['zhi'];
        $update_time = time();
        $res = $this->db->Query("UPDATE `@#_logistics` SET `is_state`='$is_state',`update_time`='$update_time' WHERE id='$id'");
        if ($res){
            echo json_encode(['code'=>1,'msg'=>'修改成功']);
            exit;
        }else{
            echo json_encode(['code'=>2,'msg'=>'修改失败']);
            exit;
        }
    }
}
?>