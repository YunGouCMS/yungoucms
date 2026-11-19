<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class language extends admin {
    private $db;
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class("model");
        $type = $this->segment(5);
        if(empty($type)){
            $type = $this->segment(4);
        }
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/{$type}"],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/{$type}"],
            ["#","修改语言包",ROUTE_M.'/'.ROUTE_C."/languageBackendUpdate","0"],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/{$type}"],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/{$type}"],
            ["#","修改语言包值",ROUTE_M.'/'.ROUTE_C."/lgsBackendEdit","0"],
        ];
        $this->mentLgs();
	}

    /**
     * 语言包列表
     * type:1 app，2pc，3后台
     * @return void
     */
    public function languageList(){
        $type = $this->segment(4);
        if($type == 3){
            $languages = $this->db->GetLIst("SELECT * FROM `@#_language_backend`");
            include $this->tpl(ROUTE_M,'language_backend.list');
        }else{
            $languages = $this->db->GetLIst("SELECT * FROM `@#_language` where `type` = $type order by sort desc");
            foreach($languages as &$vl){
                $flag = explode('/',$vl['national_flag']);
                if(count($flag)<=1){
                    $vl['national_flag'] = G_PLUGIN_PATH."/imgs/flag/".$vl['national_flag'];
                }elseif(count($flag)>1){
                    $vl['national_flag'] = G_UPLOAD_PATH."/".$vl['national_flag'];
                }
            }
            include $this->tpl(ROUTE_M,'language.index');
        }
    }

    public function languageAdd(){
        $type = $this->segment(4);
        if($type==3){
            $this->languageBackendAdd();
        }else{
            $this->languageFrontAdd();
        }
    }

    public function languageFrontAdd(){
        $type = $this->segment(4)?:1;
        $time_zones = get_timezone();
        $currency_units = system::load_sys_config("currency_units");
        $area_codes = system::load_sys_config("area_code");
        if(isset($_POST['submit'])){
            $lg_value = htmlspecialchars(trim($_POST['lg_value']));	
            $lg_name = htmlspecialchars(trim($_POST['lg_name']));	
            $status = htmlspecialchars(trim($_POST['status']));	
            $country = htmlspecialchars(trim($_POST['country']));
            $currency_unit = htmlspecialchars(trim($_POST['currency_unit']));
            $area_code = htmlspecialchars(trim($_POST['area_code']));
            $flag_status = htmlspecialchars(trim($_POST['flag_status']));
            $national_flag = $_POST['national_flag'];
            $time_zone = $_POST['time_zone'];
            $data = [
                "lg_name"=>$lg_name,
                "lg_value"=>$lg_value,
                "status"=>$status,
                "country"=>$country,
                "national_flag"=>$national_flag,
                "time_zone"=>$time_zone,
                "currency_unit"=>$currency_unit,
                "area_code"=>$area_code,
                "flag_status"=>$flag_status,
                "type"=>$type,
            ];
            $sql = Assemblydata($data,"language","in");
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/languageList/'.$type);
            }else{
                _messageerror("添加失败");
            }
        }
        $btn_value = "添加";
        include $this->tpl(ROUTE_M,'language.update');

    }
    
    public function languageUpdate(){
        $type = $this->segment(5)?:1;
        $btn_value = "修改";
        $id=intval($this->segment(4));
        $res=$this->db->Getone("SELECT * FROM `@#_language` where `id`='$id'");	
        $arr = explode("/",$res['national_flag']);
        if(count($arr) <= 1){
            $res['img'] = G_PLUGIN_PATH."/imgs/flag/".$res['national_flag'];
        }else{
            $res['img'] = G_UPLOAD_PATH."/".$res['national_flag'];
        }
        $time_zones = get_timezone();
        $currency_units = system::load_sys_config("currency_units");
        $area_codes = system::load_sys_config("area_code");
        if(isset($_POST['submit'])){
            $lg_value = htmlspecialchars(trim($_POST['lg_value']));	
            $lg_name = htmlspecialchars(trim($_POST['lg_name']));	
            $status = htmlspecialchars(trim($_POST['status']));	
            $flag_status = htmlspecialchars(trim($_POST['flag_status']));	
            $national_flag = $_POST['national_flag'];
            $time_zone = $_POST['time_zone'];
            $country = htmlspecialchars(trim($_POST['country']));
            $currency_unit = htmlspecialchars(trim($_POST['currency_unit']));
            $area_code = htmlspecialchars(trim($_POST['area_code']));
            $data = [
                "lg_name"=>$lg_name,
                "lg_value"=>$lg_value,
                "status"=>$status,
                "national_flag"=>$national_flag,
                "country"=>$country,
                "time_zone"=>$time_zone,
                "currency_unit"=>$currency_unit,
                "area_code"=>$area_code,
                "flag_status"=>$flag_status,
            ];
            $sql = Assemblydata($data,"language","up",[['id',$id]]);
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/languageList/'.$type);
            }else{
                _messageerror("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'language.update');

    }


    /**
     * 语言包值列表
     *      * type:1 app，2pc，3后台
     */
    public function lgsList(){
        $type = $this->segment(4);
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $wheres['pid'] = 0;
        if($type == 3){
            $table = "language_value_backend";
        }else{
            $table = "language_value";
            $wheres['type'] = $type;
        }
        if(isset($_POST['sososubmit']) && $_POST['sososubmit']){
            if($keys = getParams('key')){
                $wheres[] = ["key","like","%$keys%"];
            }
            if($val = getParams('val')){
                $wheres[] = ["val","like","%$val%"];
            }
            if($desc = getParams('desc')){
                $wheres[] = ["desc","like","%$desc%"];
            }
            if($keys || $desc || $val){
                $pwheres = $wheres;
                unset($pwheres['pid']);
                $pwheres[] = ["pid",">",0];
                $sql = Assemblydata(["pid"],$table,"sel",$pwheres);
                $res = $this->db->GetList($sql);
                if($res){
                    $pid = array_column($res,'pid');
                    $wheres[] = ["id","in",$pid,"or"];
                }
            }
        }
        $num = 20;
        $sql = Assemblydata([],$table,"sel",$wheres);
        $wheres['pid'] = 0;
        $sql_count = Assemblydata([],$table,"sel",$wheres);

        $page=System::load_sys_class('page');
        $total=$this->db->GetCount($sql_count);
        $page->config($total,$num,$pagenum,"0");
        $res = $this->db->GetPage($sql." order by `key`",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $langs = $this->getLanguageList(1);
        $arr = array_column($langs,"lg_value");
        foreach($res as $key=>$vl){
            $value = mb_unserialize($vl['value']);
            foreach($value as $k=> $vl){
                if(!in_array($k,$arr)){
                    unset($value[$k]);
                }
            }
            $res[$key]['value'] = $value;
        }
        $treelist = $res;
        // $treelist = buildTree($res);
        include $this->tpl(ROUTE_M,'lgs.list');
    }
	

    /**
     * 获取下级语言包
     */
    public function getLanuageChildren(){
        $id = getParams('id');
        $key = getParams('key');
        $val = getParams('val');
        $desc = getParams('desc');
        $type = getParams('type');
        
        $pagenum = abs(getParams('p',1));
        $wheres = [];
        $wheres["pid"] = $id;
        if($key){
            $wheres[] = ["key","like","%$key%"];
        }
        if($val){
            $wheres[] = ["val","like","%$val%"];
        }
        if($desc){
            $wheres[] = ["desc","like","%$desc%"];
        }
        if($type == 3){
            $table = "language_value_backend";
        }else{
            $table = "language_value";
        }
        $sql = Assemblydata([],$table,"sel",$wheres);
        $total = $this->db->GetCount($sql)?:0;
        // if($total==0){
        //     $sql = Assemblydata([],'language_value_backend',"sel",$wheres);
        // }
        $total = $this->db->GetCount($sql)?:0;
        $list = [];
        $num = 10;
        if($total){
            $pagenum = min(ceil($total/$num) , $pagenum); //防止越界
            $list = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
            $langs = $this->getLanguageList(1);
            $arr = array_column($langs,"lg_value");
            foreach($list as $key=>$vl){
                $value = mb_unserialize($vl['value']);
                foreach($value as $k=> $vl){
                    if(!in_array($k,$arr)){
                        unset($value[$k]);
                    }
                }
                $list[$key]['value'] = $value;
            }
        }
        $this->jsonData->success('','',['total'=>$total,"list"=>$list,"p"=>$pagenum,"pagecount"=>ceil($total/$num)]);
    } 

    public function lgsAdd(){
        $type = $this->segment(5)?:$this->segment(4);
        $pid = $this->segment(4);
        $languages = $this->getLanguageList(-1,$type);
        if($type == 3){
            $table = "language_value_backend";
        }else{
            $table = "language_value";

        }
        if(isset($_POST['dosubmit'])){
            $data['key'] = $_POST['key'];
            if($pid){
                $value = $_POST['value'];
                foreach($value as &$vl){
                    $vl = _htmtocode($vl);
                }
                $data['value'] = serialize($value); 
            }else{
                $data['value'] = '';
            }
            $data['pid'] = $pid;
            $data['desc'] = $_POST['desc'];
            if($type !=3){
                $data['type'] = $type;
            }
            $sql = Assemblydata($data,$table,"in");
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lgsList/".$type);
            }else{
                _message("添加失败");
            }
        }
        include $this->tpl(ROUTE_M,'lgs.add');
    }
    public function lgsEdit(){
        $type = $this->segment(5);
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
            ["#","修改语言包值",ROUTE_M.'/'.ROUTE_C."/lgsEdit",1],
        ];
        if($type == 3){
            $table = "language_value_backend";
        }else{
            $table = "language_value";
        }
        $languages = $this->getLanguageList(-1,$type);
        $id = $this->segment(4);
        $lgs = $this->db->GetOne("select * from `@#_{$table}` where id=".$id);
        $pid = $lgs['pid'];
        $lgs['value'] = mb_unserialize($lgs['value']);
        if(isset($_POST['dosubmit'])){
            // $data['key'] = $_POST['key'];
            $value = $_POST['value'];
            foreach($value as &$vl){
                $vl = _htmtocode($vl);
            }
            $data['value'] = serialize($value);
            // $data['pid'] = $_POST['pid'];
            $data['desc'] = $_POST['desc'];
            $sql = Assemblydata($data,$table,"up",[["id",$id]]);
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/lgsList/'.$type);
            }else{
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'lgs.add');
    }

    public function lgsDel(){
        $type = $this->segment(5)?:1;
        if($type == 3){
            $table = "language_value_backend";
        }else{
            $table = "language_value";
        }
        $id = $this->segment(4);
        $res = $this->db->Query("delete from `@#_{$table}` where id= $id or pid = $id");
        _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/lgsList/'.$type);
    }

	/**
	 * 后台语言包
	 * @return void
	 */
    public function languageBackendList(){
        $languages = $this->db->GetLIst("SELECT * FROM `@#_language_backend`");
        include $this->tpl(ROUTE_M,'language_backend.list');
    }

    public function languageBackendAdd(){
        if(isset($_POST['submit'])){
            $lg_value = htmlspecialchars(trim($_POST['lg_value']));	
            $lg_name = htmlspecialchars(trim($_POST['lg_name']));	
            $status = htmlspecialchars(trim($_POST['status']));	
            $country = htmlspecialchars(trim($_POST['country']));
            $currency_unit = htmlspecialchars(trim($_POST['currency_unit']));
            $area_code = htmlspecialchars(trim($_POST['area_code']));
            $national_flag = $_POST['national_flag'];
            $time_zone = $_POST['time_zone'];
            $data = [
                "lg_name"=>$lg_name,
                "lg_value"=>$lg_value,
                "status"=>$status,
                "country"=>$country,
                // "national_flag"=>$national_flag,
                // "time_zone"=>$time_zone,
                // "currency_unit"=>$currency_unit,
                // "area_code"=>$area_code,
            ];
            $sql = Assemblydata($data,"language_backend","in");
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message($this->getLgMsg("add_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList/3");
            }else{
                _messageerror($this->getLgMsg("add_error","app_return",2));
            }
        }
        $btn_value = $this->lgs['Submit'];
        include $this->tpl(ROUTE_M,'language_backend.update');
    }

    public function languageBackendUpdate(){
        $btn_value = $this->lgs['Submit'];
        $id=intval($this->segment(4));
        $res=$this->db->Getone("SELECT * FROM `@#_language_backend` where `id`='$id'");	
        $res['img'] = G_UPLOAD_PATH."/".$res['national_flag'];
        if(isset($_POST['submit'])){
            $lg_value = htmlspecialchars(trim($_POST['lg_value']));	
            $lg_name = htmlspecialchars(trim($_POST['lg_name']));	
            $status = htmlspecialchars(trim($_POST['status']));	
            $national_flag = $_POST['national_flag'];
            $time_zone = $_POST['time_zone'];
            $country = htmlspecialchars(trim($_POST['country']));
            $currency_unit = htmlspecialchars(trim($_POST['currency_unit']));
            $area_code = htmlspecialchars(trim($_POST['area_code']));
            $data = [
                "lg_name"=>$lg_name,
                "lg_value"=>$lg_value,
                "status"=>$status,
                // "national_flag"=>$national_flag,
                "country"=>$country,
                // "time_zone"=>$time_zone,
                // "currency_unit"=>$currency_unit,
                // "area_code"=>$area_code,
            ];
            $sql = Assemblydata($data,"language_backend","up",[['id',$id]]);
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                // _message($this->getLgMsg("update_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/languageBackendList');
                _message($this->getLgMsg("update_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList/3");

            }else{
                _messageerror($this->getLgMsg("update_error","app_return",2));
            }
        }
        include $this->tpl(ROUTE_M,'language_backend.update');

    }

    public function languageBackendDelete(){
        $id=intval($this->segment(4));
        $this->db->Query("DELETE FROM `@#_language_backend` WHERE (`id`='$id')");
        if($this->db->affected_rows()){
            _message($this->getLgMsg("delete_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageBackendList");
        }else{
            _message($this->getLgMsg("delete_error","app_return",2));
        }  
    }

    
    public function languageBackendSetStatus(){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $res = $this->db->GetOne("SELECT * FROM `@#_language_backend` where id = $id");
        if($res){
            $this->db->Query("update `@#_language_backend` set status = $status where id = $id");
            $this->jsonData->success();
        } 
        $this->jsonData->error();
    }

    /**
     * 导入语言包
     * @return never
     */
    public function languageImport(){
        $lg = $this->segment(4)?:$this->segment(5);
        if($lg){
            $languages = [["lg_value"=>$lg]];
        }else{
            _messageerror("不能为空",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageBackendList");
            exit;
            // $languages = $this->getlanguageBackendList(1,2);
        }
        foreach($languages as $val){
            $aa = languages(2, '', 1, $val['lg_value']);
            foreach($aa as $key => $vl){
                $data = [
                    "key"=>$key
                ];
                $lgs = $this->db->GetOne("select * from `@#_language_value_backend` where `key` = '$key' and pid = 0");
                if(!$lgs){
                    $sql = Assemblydata($data,"language_value_backend","in");
                    $this->db->Query($sql);
                }
                $lgs = $this->db->GetOne("select * from `@#_language_value_backend` where `key` = '$key' and pid = 0");
                $pid = $lgs['id'];
                foreach($vl as $k=>$v){
                    if(is_numeric($k)){
                        continue;
                    }
                    $lgsv = $this->db->GetOne("select * from `@#_language_value_backend` where `key` = '$k' and pid = $pid");
                    if($lgsv){
                        $value = mb_unserialize($lgsv['value']);
                    }else{
                        $value = [];
                    }
                    if($value[$val['lg_value']]['value'] == $v){
                        continue;
                    }
                    $value[$val['lg_value']]['name'] = $val['lg_name'];
                    $value[$val['lg_value']]['value'] = $v;
                    $data['key'] = $k;
                    $value = serialize($value);
                    $data['value'] = $value;
                    if($val['lg_value']=="Chinese"){
                        $data['desc'] = $v;
                    }
                    $data['pid'] = $pid;
                    if($lgsv){
                        $sql = Assemblydata($data,"language_value_backend","up",[['key',$k],['pid',$pid]]);
                    }else{
                        $sql = Assemblydata($data,"language_value_backend","in");
                    }
                    $this->db->Query($sql);
                }
                
            }
        }
        _message("导入成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageBackendList");
        exit;
    }

    private function getLgVal1($array, $parentId = 0,$language="Chinese"){
        $tree = [];
        $str = '';
        foreach($array as $element){
            if($element['pid'] == $parentId){
                $children = $this->getLgVal1($array, $element['id'],$language);
                if($children[0]){
                    $str .= "'".$element['key']."'=>[ //".$element['desc']."\n";
                    $tree[$element['key']] = $children[0];
                    $str .= $children[1]."],\n";
                }else{
                    // $tree[$element['key']] = unserialize($element['value'])[$language]['value'];
                    // $str .= "       '".$element['key'] ."'=>'" .unserialize($element['value'])[$language]['value']."', //".$element['desc']."\n";
                    $tree[$element['key']] = mb_unserialize($element['value'])[$language]['value'];
                    $str .= "       '".$element['key'] ."'=>'" .mb_unserialize($element['value'])[$language]['value']."', //".$element['desc']."\n";
                }
            }
        }
        return [$tree,$str];
    }

    //导出语言包
    public function languageExport(){
        $id=intval($this->segment(4));
        $res=$this->db->Getone("SELECT * FROM `@#_language_backend` where `id`='$id'");	
        $lgs = $this->db->GetList("select * from `@#_language_value_backend`");
        $list = $this->getLgVal1($lgs,0,$res['lg_value']);
        $file_name = $res['lg_value'];
        // file_put_contents(G_APP_PATH."/system/funcs/languages/$file_name"."admin.php", "<?php \n return ".var_export($list,true).";");
        file_put_contents(G_APP_PATH."/system/funcs/languages/$file_name"."admin.php", "<?php \n return [".$list[1]."];");
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$file_name.'.php"');
        echo "<?php \n return [".$list[1]."];";
        // _message("导出成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList");

    }

    //语言包详细
    public function lgsBackendList(){
        $wheres['pid'] = 0;
        $num = 20;
        if(isset($_POST['sososubmit']) && $_POST['sososubmit']){
            if($_POST['key']){
                $wheres['key'] = $keys = $_POST['key'];
            }
            if($_POST['val']){
                $val = $_POST['val'];
                $wheres[] = ["value","like","%$val%"];
            }
            if($_POST['desc']){
                $wheres['desc'] = $desc = $_POST['desc'];
            }
            if($keys || $desc || $val){
                unset($wheres['pid']);
                $num = 500;
                $sql = Assemblydata(["pid","id"],'language_value_backend',"sel",$wheres);
                $res = $this->db->GetList($sql);
                if($res){
                    $id = array_column($res,'id');
                    $pid = array_column($res,'pid');
                    $wheres[] = ["id","in",$pid,"or"];
                    $wheres[] = ["pid","in",$id,"or"];
                }
            }
        }

        $sql = Assemblydata([],'language_value_backend',"sel",$wheres);
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page=System::load_sys_class('page');
        $total=$this->db->GetCount($sql);
        $page->config($total,$num,$pagenum,"0");
        $res = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        
        if(isset($wheres['pid'])){
            $child_id = array_column($res,"id");
            $sql = "SELECT * FROM `@#_language_value_backend` WHERE `pid` IN (".implode(",",$child_id).") ORDER BY `id` ASC";
            $res1 = $this->db->GetList($sql);
            $res = array_merge($res,$res1);
        }
        foreach($res as $key=>$vl){
            $res[$key]['value'] = mb_unserialize($vl['value']);
        }
        $treelist = buildTree($res);
        include $this->tpl(ROUTE_M,'lgs_backend.list');
    }

    public function lgsBackendAdd(){
        $pid = $this->segment(4);
        $languages = $this->getlanguageList(1,3);
        if(isset($_POST['dosubmit'])){
            $data['key'] = trim($_POST['key']);
            if($pid){
                $value = $_POST['value'];
                $data['value'] = serialize($value); 
            }else{
                $data['value'] = '';
            }
            $data['pid'] = $pid;
            
            $data['desc'] = $_POST['desc'];
            $sql = Assemblydata($data,"language_value_backend","in");
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message($this->getLgMsg("add_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/lgsBackendList');
            }else{
                _message($this->getLgMsg("add_error","app_return",2));
            }
        }
        include $this->tpl(ROUTE_M,'lgs_backend.add');
    }
    public function lgsBackendEdit(){
        $languages = $this->getlanguageList(1,3);
        $id = $this->segment(4);
        $pid = $this->segment(5);
        $lgs = $this->db->GetOne("select * from `@#_language_value_backend` where id=".$id);
        $lgs['value'] = mb_unserialize($lgs['value']);
        if(isset($_POST['dosubmit'])){
            // $data['key'] = $_POST['key'];
            $data['key'] = trim($lgs['key']);
            $value = $_POST['value'];
            $data['value'] = serialize($value);
            // $data['pid'] = $_POST['pid'];
            $data['desc'] = trim($_POST['desc']);
            $sql = Assemblydata($data,"language_value_backend","up",[["id",$id]]);
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                _message($this->getLgMsg("update_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/lgsBackendList');
            }else{
                _message($this->getLgMsg("update_error","app_return",2));
            }
        }
        include $this->tpl(ROUTE_M,'lgs_backend.add');
    }

    public function lgsBackendDel(){
        $id = $this->segment(4);
        $res = $this->db->Query("delete from `@#_language_value_backend` where id= $id or pid = $id");
        _message($this->getLgMsg("delete_ok","app_return",2),WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/lgsBackendList');
    }



    /**
     * 获取语言对应货币单位
     */
    public function getCurrency(){
        $language = $_POST['language'];
        $currency = $this->db->GetOne("select * from `@#_language` where `lg_value` = '$language'");
        if($currency){
            $this->jsonData->success("","",['currency_unit'=>$currency['currency_unit']]);
        }else{
            $this->jsonData->success("","",'');
        }
    }

    /**
     * 获取时区对应时间
     */
    public function getTimezoneDate(){
        $timezone = $_POST['time_zone'];
        try{
            $tz = new DateTimeZone($timezone);
            // 获取当前时间
            $now = new DateTime('now', $tz);
            $formatted_time = $now->format('Y年m月d日 H:i:s');
            $this->jsonData->success("","",['date'=>$formatted_time]);
        } catch (Exception $e) {
            $this->jsonData->error('',"无效的时区选择");
        }
    }
}
