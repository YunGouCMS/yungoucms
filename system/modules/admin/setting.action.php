<?php 

defined('G_IN_SYSTEM')or exit('no');

System::load_app_class('admin',G_ADMIN_DIR,'no');

System::load_app_fun('global',G_ADMIN_DIR);
require_once ("goole/PHPGangsta/GoogleAuthenticator.php");

class setting extends admin {

    private $db;
    
    protected $ment;	

    public function __construct(){

        parent::__construct();

        $this->db=System::load_sys_class("model");
        $this->ment=array(
                        array("webcfg","全局设置",ROUTE_M.'/'.ROUTE_C."/webcfg"),
                        // array("config","图片设置",ROUTE_M.'/'.ROUTE_C."/config"),

                        //array("upload","上传配置",ROUTE_M.'/'.ROUTE_C."/upload"),

                        //array("watermark","水印配置",ROUTE_M.'/'.ROUTE_C."/watermark"),

                        //array("email","邮箱配置",ROUTE_M.'/'.ROUTE_C."/email"),

//						array("domain","域名绑定",ROUTE_M.'/'.ROUTE_C."/domain"),

        );
    

    }

    /**

    *	中奖通知设置

    */

    public function sendconfig(){

        $this->ment=array(

            array("select","订单查询",ROUTE_M.'/'.ROUTE_C."/select"),

            array("lists","订单列表",ROUTE_M.'/'.ROUTE_C."/lists"),

            //array("lists","机订单列表",ROUTE_M.'/'.ROUTE_C."/lists/jlist"),

            array("lists","中奖订单",ROUTE_M.'/'.ROUTE_C."/lists/zj"),

            //array("lists","机中奖订单",ROUTE_M.'/'.ROUTE_C."/lists/jzj"),

            array("lists","已发货",ROUTE_M.'/'.ROUTE_C."/lists/sendok"),

            array("lists","未发货",ROUTE_M.'/'.ROUTE_C."/lists/notsend"),

            array("insert","已完成",ROUTE_M.'/'.ROUTE_C."/lists/ok"),

            array("insert","已作废",ROUTE_M.'/'.ROUTE_C."/lists/del"),

            array("insert","待收货",ROUTE_M.'/'.ROUTE_C."/lists/shouhuo"),

            array("insert","订单记录删除",ROUTE_M.'/'.ROUTE_C."/dindan_del"),

            array("genzhong","快递跟踪",ROUTE_M.'/'.ROUTE_C."/genzhong"),

            array("send","中奖通知",ROUTE_M."/setting/sendconfig")

        );
    $type = System::load_sys_config("send","type");

    if(isset($_POST['s_type'])){	  

        $s_type = abs($_POST['s_type']);

        if(($s_type == $type) || $s_type > 7){

            _message("更新完成!");

        }

        $html = "<?php return array('type'=>'$s_type'); ?>";

        if(!is_writable(G_CONFIG.'send.inc.php')) exit('send.inc.php 没有写入权限!');

            file_put_contents(G_CONFIG.'send.inc.php',$html);

            _message("更新完成!");

        }

    include $this->tpl(ROUTE_M,'config.send');

    }

    /**
     * 音效设置
     * @return void
     */
    public function domain(){
        $lists = $this->db->GetList("select * from `@#_sound_effects`");
         $this->ment=array(
                        array("webcfg","声效设置",ROUTE_M.'/'.ROUTE_C."/domain"),
        );
        $config = [];
        $title = [
            "login_success"=>"Login_success_ringtone",
            "purchase_successful"=>"Successfully_purchased_ringtone",
            "background"=>"Background_music_ringtone",
            "prompt"=>"Message_prompt_ringtone",
            "recharge_success"=>"Recharge_success_ringtone",
            "withdrawal_success"=>"Withdrawal_account_ringtone",
            "exchange_oline"=>"Exchange_ringtones_online",
            "winning"=>"Winning_reminder_ringtone",
            "rotating"=>"Rotating_dial_bell",
            "turntable_winning"=>"Turntable_winning_ringtone",
        ];
        foreach ($lists as $k=>$v){
            $v['title'] = $title[$v['key']];
            $config[$v['key']] = $v;

        }
        $names = [
            "login_success"=>"登录成功",
            "purchase_successful"=>"购买成功",
            "background"=>"背景音乐",
            "prompt"=>"提示音",
            "recharge_success"=>"充值成功",
            "withdrawal_success"=>"提现成功",
            "exchange_oline"=>"在线兑换",
            "winning"=>"中奖",
            "rotating"=>"转盘转动",
            "turntable_winning"=>"转盘中奖",
        ];
        if(isset($_POST['dosubmit']) && $_POST['dosubmit'] != 'del'){
            $data = $_POST['effects'];
            $this->db->Autocommit_start();
            foreach($data as $k=>$v){
                $info = [
                    "key" => $k,
                    "name" =>$names[$k],
                    "url" => $v['url'],
                    "status" => $v['status'],
                    "desc" => "",
                ];
                $res = $this->db->GetOne("select * from `@#_sound_effects` where `key` = '{$k}'");
                if($res){
                    $sql = Assemblydata($info,"sound_effects","update",[["key",$k]]);
                }else{
                    $sql = Assemblydata($info,"sound_effects","insert");
                }
                $res = $this->db->Query($sql);
            }
            $this->db->Autocommit_commit();
            _message("更新完成!");
        }
        include $this->tpl(ROUTE_M,'config.domain');
    }

    public function sound_effects_set(){
        $id = intval($this->segment(4));
        $status = $_POST['status'];
        if(!$id){
            $this->jsonData->error();
        }        
        $res = $this->db->GetOne("select * from `@#_sound_effects` where `id` = '{$id}'");
        if($res){
           $sql = Assemblydata(['status'=>$status],"sound_effects",'up',[["id",$id]]);
           $res = $this->db->Query($sql);
           $this->jsonData->success();
        }else{
            $this->jsonData->error();
        }
    }

    public function domain1(){	

        $domain_cfg = System::load_sys_config("domain");

        if(empty($domain_cfg)) $domain_cfg = array();
        if(isset($_POST['dosubmit']) && $_POST['dosubmit'] != 'del'){
            //插入或者修改
            $domain  = isset($_POST['domain']) ? trim(htmlspecialchars($_POST['domain'])) : null;
            $module  = 'mobile';
            $action  = 'mobile';
            $func    = 'init';
            
            if(isset($_POST['dosubmit']) && $_POST['dosubmit'] != 'install'){
                $dosubmit = $_POST['dosubmit'];
            }

            if(!$domain || !$module){
                exit("请正确填写绑定参数!");				
            }

            $domain_cfg = array();
            if($_POST['dosubmit'] == 'install'){ 
                if(array_key_exists($domain,$domain_cfg)){
                    exit("绑定的域名已经被使用!");//array_keys
                }			
            }
    
            $domain =  str_ireplace("http://",'',trim($domain,'/'));
            if($dosubmit){
                unset($domain_cfg[$dosubmit]);
            }
            
            $domain_cfg[$domain] = array("m"=>$module,"c"=>$action,"a"=>$func);
            $html  = "<?php \n\n";
            $html .= "return ";
            $html .= var_export($domain_cfg,true).';';	
            $html .= "\n\n?>";

            if(!is_writable(G_CONFIG.'domain.inc.php')) exit('domain.inc.php 没有写入权限!');
            file_put_contents(G_CONFIG.'domain.inc.php',$html);
            exit("ok");
        }

        if(isset($_POST['dosubmit']) && $_POST['dosubmit'] == 'del'){
            $domain  = isset($_POST['domain']) ? trim(htmlspecialchars($_POST['domain'])) : null;
            if(!$domain){
                exit("操作失败!");			
            }

            if(isset($domain_cfg[$domain])){
                unset($domain_cfg[$domain]);
                $html  = "<?php \n\n";
                $html .= "return ";
                $html .= var_export($domain_cfg,true).';';	
                $html .= "\n\n?>";
                if(!is_writable(G_CONFIG.'domain.inc.php')) exit('domain.inc.php 没有写入权限!');
                file_put_contents(G_CONFIG.'domain.inc.php',$html);
                exit("ok");
            }else{
                exit("操作失败2!");	
            }

        }

        $system_config = System::load_sys_config('system');
        $this->ment=array(
            array("domain","声效设置",ROUTE_M.'/'.ROUTE_C."/domain"),
            // array("uploadSet","上传设置",ROUTE_M.'/'.ROUTE_C."/uploadSet"),
            // array("touxiangSet","用户头像设置",ROUTE_M.'/'.ROUTE_C."/touxiangSet")
        );
        include $this->tpl(ROUTE_M,'config.domain');
    }
    
    public function button(){	
         $this->ment=array(
                        array("webcfg","按钮设置",ROUTE_M.'/'.ROUTE_C."/button"),
        );
        include $this->tpl(ROUTE_M,'config.button');
    }

    public function uploadSet(){
        $this->ment=array(
            array("domain","域名绑定",ROUTE_M.'/'.ROUTE_C."/domain"),
            array("uploadSet","上传设置",ROUTE_M.'/'.ROUTE_C."/uploadSet"),
            // array("touxiangSet","用户头像设置",ROUTE_M.'/'.ROUTE_C."/touxiangSet")
        );
        $data = $this->getConfig(["upload_type","qiniu_AccessKey","qiniu_SecretKey","qiniu_warehouse","qiniu_domain",'sdfs']);
        if(isset($_POST['coubmit'])){
            $data = $_POST;
            $this->setConfig("upload_type",$data['upload_type'],"上传类型");
            $this->setConfig("qiniu_AccessKey",$data['qiniu_AccessKey'],"七牛云AccessKey");
            $this->setConfig("qiniu_SecretKey",$data['qiniu_SecretKey'],"七牛云SecretKey");
            $this->setConfig("qiniu_warehouse",$data['qiniu_warehouse'],"仓库名称");
            $this->setConfig("qiniu_domain",$data['qiniu_domain'],"域名");
            $this->cfgPut();
            _message("ok");
        }
        include $this->tpl(ROUTE_M,'config.uploadset');
    }

    public function isuploadauth(){
        $code = $_POST['code'];
        if (empty($code)){
            echo json_encode(['code'=>0,'msg'=>'请输入谷歌验证码']);
            exit();
        }
        $ga = new PHPGangsta_GoogleAuthenticator();
        $data = $this->db->GetList("SELECT * FROM `@#_google_verify` LIMIT 1");
        $secret = $data[0]['keys'];
        $checkResult = $ga->verifyCode($secret, $code, 2);
        if(!$checkResult){
            echo json_encode(['code'=>0,'msg'=>'验证码输入错误']);
            exit;
        }
        echo json_encode(['code'=>1,'msg'=>'成功']);
        exit;
    }

    /*修改绑定域名*/
    public function domainEdit(){

        $domain_cfg = System::load_sys_config("domain");
        $arr = [];
        foreach ($domain_cfg as $k=>$v){
            if($k == 0){
                $arr['k'] = $k;
                $arr['v'] = $v;
                break;
            }
        }

        $this->ment=array(
            array("domain","域名绑定",ROUTE_M.'/'.ROUTE_C."/domain")
            // array("send","域名修改",ROUTE_M.'/'.ROUTE_C."/domainEdit")

        );
        if(isset($_POST['k'])){
            $isarr = array(
                $_POST['k'] => ['m'=>$_POST['m'],'c'=>$_POST['c'],'a'=>$_POST['a']]
            );
            array_splice($domain_cfg,0,1);
            $res =array_merge($isarr,$domain_cfg);
            $html  = "<?php \n\n";

            $html .= "return ";

            $html .= var_export($res,true).';';

            $html .= "\n\n?>";

            if(!is_writable(G_CONFIG.'domain.inc.php')) exit('domain.inc.php 没有写入权限!');

            file_put_contents(G_CONFIG.'domain.inc.php',$html);

            _message("修改成功");
        }
        include $this->tpl(ROUTE_M,'config.domainedit');
    }

    

    //写入文件
    public function cfgPut($file='system.inc.php',$data=[],$table='config'){
        $html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.'); \n";

        $html.="return array( \n";
        if(!$data){
            $cfg=$this->db->GetList("select * from `@#_$table` where 1");
            foreach ($cfg as $v){
                // $html.="'$v[name]' => \"".$v['value']."\", // ".$v['zhushi'];
                $v["value"] = str_replace("'",'"',$v['value']);
                $html .= "'".$v['name']."'=>'".$v['value']."',//".$v['zhushi'];
                $html.="\n";		
            }
        }else{
            foreach ($data as $k=>$v){
                // $v = str_replace("'",'"',$v);
                $html.="'".$k."'=>'".$v."',\n";
            }
        }

        $html.="); \n ?>";
        // var_dump($html);exit;
        if(!is_writable(G_CONFIG.'system.inc.php')) _message('Please chmod  system  to 0777 !');

        return $ok=file_put_contents(G_CONFIG.$file,$html);		

    }
    //写入文件
    public function cfgPut1(){

        $cfg=$this->db->GetList("select * from `@#_config` where 1");

        $html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.'); \n";

        $html.="return array( \n";

        foreach ($cfg as $v){
            // $html.="'$v[name]' => \"".$v['value']."\", // ".$v['zhushi'];
            $v["value"] = str_replace("'",'"',$v['value']);
            $html .= "'".$v['name']."'=>'".$v['value']."',//".$v['zhushi'];
            $html.="\n";		
        }

        $html.="); \n ?>";
        // var_dump($html);exit;
        if(!is_writable(G_CONFIG.'system.inc.php')) _message('Please chmod  system  to 0777 !');

        return $ok=file_put_contents(G_CONFIG.'system.inc.php',$html);		

    }

    //基本设置
    public function config(){
        if(isset($_POST['dosubmit'])){	
            $upload_type = $_POST['upload_type'];
            $this->setConfig("upload_type",$upload_type,"上传类型");

            $qiniu_AccessKey = $_POST['qiniu_AccessKey'];
            $this->setConfig("qiniu_AccessKey",$qiniu_AccessKey,"七牛云AccessKey");

            $qiniu_SecretKey = $_POST['qiniu_SecretKey'];
            $this->setConfig("qiniu_SecretKey",$qiniu_SecretKey,"七牛云SecretKey");
            
            $qiniu_warehouse = $_POST['qiniu_warehouse'];
            $this->setConfig("qiniu_warehouse",$qiniu_warehouse,"仓库名称");
            
            $qiniu_domain = $_POST['qiniu_domain'];
            $this->setConfig("qiniu_domain",$qiniu_domain,"域名");

            $web_logo = $_POST['web_logo'];
            $this->setConfig("web_logo",$web_logo);
            
            if($_FILES){
                $res = $this->c_iconv($_FILES['web_ico']);
                $web_ico = $res['dir'];
                $this->setConfig("web_ico",$web_ico);
            }
            
            $qqkfewm = serialize($_POST['qqkfewm']);
            $this->setConfig("qqkfewm",$qqkfewm);

            $wxkfewm = serialize($_POST['wxkfewm']);
            $this->setConfig("wxkfewm",$wxkfewm);

            $celebrate_img = serialize($_POST['celebrate_img']);
            $this->setConfig("celebrate_img",$celebrate_img);

            //微信公众号
            $web_wxh = $_POST['web_wxh'];
            $this->setConfig("web_wxh",$web_wxh);

            $ok=$this->cfgPut();
//            if($this->db->affected_rows() && $ok){
            if($ok){
                _message("修改成功");
            }else{
                _message("修改失败");
            }
        }
        $web=System::load_sys_config('system');
        $web['qqkfewm'] = safe_unserialize($web['qqkfewm']);
        $web['wxkfewm'] = safe_unserialize($web['wxkfewm']);
        $web['web_wxh'] = safe_unserialize($web['web_wxh']);
        $web['celebrate_img'] = safe_unserialize($web['celebrate_img']);
        $languages = $this->getLanguageList(1);
        $this->ment=array(
            array("config","图片设置",ROUTE_M.'/'.ROUTE_C."/config"),
        );
        include $this->tpl(ROUTE_M,'config.system');

    }

    private function getLgVal($array, $parentId = 0,$language="Chinese"){
        $tree = [];
        foreach($array as $element){
            if($element['pid'] == $parentId){
                $children = $this->getLgVal($array, $element['id'],$language);
                if($children){
                    $tree[$element['key']] = $children;
                }else{
                    // $tree[$element['key']] = unserialize($element['value'])[$language]['value'];
                    $tree[$element['key']] = mb_unserialize($element['value'])[$language]['value'];
                }
            }
        }
        return $tree;
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
        $type=intval($this->segment(5))?:1;
        $files = ["home",'pc'];
        $res=$this->db->Getone("SELECT * FROM `@#_language` where `id`='$id'");	
        $lgs = $this->db->GetList("select * from `@#_language_value` where type = $type");
        $list = $this->getLgVal1($lgs,0,$res['lg_value']);
        $file_name = $res['lg_value'];
        // file_put_contents(G_APP_PATH."/system/funcs/languages/$file_name"."home.php", "<?php \n return ".var_export($list,true).";");
        file_put_contents(G_APP_PATH."/system/funcs/languages/$file_name".$files[$type-1].".php", "<?php \n return [".$list[1]."];");
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$file_name.'.php"');
        echo "<?php \n return [".$list[1]."];";
        // _message("导出成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList");

    }

    public function languageList($type = 1){
        $type = $this->segment(4)?:$type;
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
        ];
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

    public function languageSetStatus(){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $res = $this->db->GetOne("SELECT * FROM `@#_language` where id = $id");
        if($res){
            $this->db->Query("update `@#_language` set status = $status where id = $id");
            $this->jsonData->success();
        } 
        $this->jsonData->error();
    }

    public function languageSort(){
        if($this->segment(4)=='dosubmit'){
            foreach($_POST['listorders'] as $id => $listorder){
                $id = intval($id);
                $listorder = intval($listorder);
                $this->db->Query("UPDATE `@#_language` SET `sort` = '$listorder' where `id` = '$id'");
            }
            _message("排序更新成功");
        }else{
            _messageerror("请排序");

        }
    }

    private function getLgs(){
        $languages = getLanguageList();
        $lg = $this->db->GetLIst("SELECT * FROM `@#_language`  where type = 1");
        $id = 1;
        foreach($lg as $k=>$v){
            $id = $v['id'];
            if(in_array($v['lg_value'],$languages)){
                $i = array_search($v['lg_value'],$languages);
                unset($languages[$i]);
                $lg[$k]['deletion'] = 0;
            }else{
                $lg[$k]['deletion'] = 1;
            }
        }
        foreach($languages as $k=>$v){
        $this->db->Query("insert into `@#_language`(`lg_value`,`lg_name`,`status`) values('$v','',0) ");
            $lg[] = [
                'id'=> ++$id,
                'lg_value'=> $v,
                'lg_name'=> '',
                'status'=>0,
                'deletion'=>0,
            ];
        }
        return $lg;
    }

    public function languageChange(){
        $data = system::load_sys_config("lgs");
        $lg = $_POST['lg_value'];
        if(isset($data[$lg])){
            $res = $data[$lg];
            $res['img'] = G_PLUGIN_PATH."/imgs/flag/".$res['flag'];
            $this->jsonData->success('','',$res);
        }else{
            $this->jsonData->error();
        }
    }

    public function languageAdd(){
        $type = $this->segment(4)?:1;
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
        ];
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
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["#","修改语言包",ROUTE_M.'/'.ROUTE_C."/languageUpdate/".$type,1],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
        ];
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

    public function languageDelete(){
        $id=intval($this->segment(4));
        $type = $this->segment(5)?:1;
        $this->db->Query("DELETE FROM `go_language` WHERE (`id`='$id')");
        if($this->db->affected_rows()){
            _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList/".$type);
        }else{
            _message("删除失败");
        }  
    }

    //导入语言包
    public function languageImport(){
        $type = $this->segment(5)?:1;
        $lg = $this->segment(4);
        if($lg){
            $languages = [["lg_value"=>$lg]];
        }else{
            _messageerror("不能为空",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList/".$type);
            exit;
            $languages = $this->getLanguageList();
        }
        foreach($languages as $val){
            $aa = languages(2, '', $type==2?3:2, $val['lg_value']);
            foreach($aa as $key => $vl){
                $data = [
                    "key"=>$key,
                    'type'=>$type
                ];
                $lgs = $this->db->GetOne("select * from `@#_language_value` where `key` = '$key' and `pid` = 0 and type = $type");
                if(!$lgs){
                    $sql = Assemblydata($data,"language_value","in");
                    $this->db->Query($sql);
                }
                $lgs = $this->db->GetOne("select * from `@#_language_value` where `key` = '$key' and `pid` = 0 and type = $type");
                $pid = $lgs['id'];
                foreach($vl as $k=>$v){
                    $lgs = $this->db->GetOne("select * from `@#_language_value` where `key` = '$k' and pid = $pid and type = $type");
                    if($lgs){
                        // $value = unserialize($lgs['value']);
                        $value = mb_unserialize($lgs['value']);
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
                    $data['type'] = $type;
                    if($lgs){
                        $sql = Assemblydata($data,"language_value","up",[['key',$k],['pid',$pid]]);
                    }else{
                        $sql = Assemblydata($data,"language_value","in");
                    }
                    $this->db->Query($sql);
                }
                
            }
        }
        _message("导入成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/languageList/".$type);
        exit;
    }
    //语言包详细
    public function lgsList(){
        $type = $this->segment(4)?:1;
        $wheres = [];
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
        ];
        $wheres['pid'] = 0;
        $wheres['type'] = $type;
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
                $sql = Assemblydata(["pid","id"],'language_value',"sel",$wheres);
                $res = $this->db->GetList($sql);
                if($res){
                    $id = array_column($res,'id');
                    $pid = array_column($res,'pid');
                    $wheres[] = ["id","in",$pid,"or"];
                    $wheres[] = ["pid","in",$id,"or"];
                }
            }
        }
        $sql = Assemblydata([],'language_value',"sel",$wheres);
        $num = 20;
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page=System::load_sys_class('page');
        $total=$this->db->GetCount($sql);
        $page->config($total,$num,$pagenum,"0");
        $res = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        
        if(isset($wheres['pid'])){
            $child_id = array_column($res,"id");
            $sql = "SELECT * FROM `@#_language_value` WHERE `pid` IN (".implode(",",$child_id).") ORDER BY `id` ASC";
            $res1 = $this->db->GetList($sql);
            $res = array_merge($res,$res1);
        }
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
        $treelist = buildTree($res);
        include $this->tpl(ROUTE_M,'lgs.list');
    }

    public function lgsAdd(){
        $type = $this->segment(5)?:1;
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
        ];
        $pid = $this->segment(4);
        $languages = $this->getLanguageList(-1,$type);
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
            $data['type'] = $type;
            $sql = Assemblydata($data,"language_value","in");
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
        $type = $this->segment(6)?:1;
        $this->ment = [
            ["languageList","语言包设置",ROUTE_M.'/'.ROUTE_C."/languageList/".$type],
            ["languageAdd","添加语言包",ROUTE_M.'/'.ROUTE_C."/languageAdd/".$type],
            ["lgsList","语言包值列表",ROUTE_M.'/'.ROUTE_C."/lgsList/".$type],
            ["lgsAdd","添加语言包值",ROUTE_M.'/'.ROUTE_C."/lgsAdd/0/".$type],
            ["#","修改语言包值",ROUTE_M.'/'.ROUTE_C."/lgsEdit",1],
        ];
        $languages = $this->getLanguageList(-1,$type);
        $id = $this->segment(4);
        $pid = $this->segment(5);
        $lgs = $this->db->GetOne("select * from `@#_language_value` where id=".$id);
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
            $sql = Assemblydata($data,"language_value","up",[["id",$id]]);
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
        $id = $this->segment(4);
        $res = $this->db->Query("delete from `@#_language_value` where id= $id or pid = $id");
        _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/lgsList/'.$type);
    }

    //语言包PC端
    public function languagePc(){
        $this->languageList(2);
    }

    /*后台修改登录页面配置*/
    public function setLogin(){
        $this->ment=array(

            array("webcfg","全局设置",ROUTE_M.'/'.ROUTE_C."/webcfg"),

            array("config","基础设置",ROUTE_M.'/'.ROUTE_C."/config"),

        );
        // 从数据库读取，保证与 go_config 表一致
        $config = $this->getConfig('web_verify_mode');
        $switch = $this->getConfig('web_verify');
        $code_phone = _cfg('code_phone');
        if(isset($_POST['dosubmit'])){
                $web_verify_mode = safe_replace($_POST['web_verify_mode']);
                $web_verify = safe_replace($_POST['web_verify']);
                $gg_is_new = safe_replace($_POST['gg_is_new']);
                // web_verify / web_verify_mode 写入 go_config 表，随后 cfgPut() 同步到 system.inc.php
                $this->db->Query("update `@#_config` set `value` = $web_verify_mode where `name` = 'web_verify_mode'");
                $this->db->Query("update `@#_config` set `value` = $web_verify where `name` = 'web_verify'");
                if($_POST['code_phone'] != $code_phone){
                    $this->db->Query("update `@#_config` set `value` = $_POST[code_phone] where `name` = 'code_phone'");
                }
                if($web_verify_mode==3 && $gg_is_new){
                    include "checkcode.action.php";
                    $c = new checkcode();
                    $c->updateQr();
                    // 更换二维码后视为未绑定，需重新验证
                    $this->setConfig('google_authenticator_bound', '0', '后台谷歌验证码已绑定');
                }

            $ok=$this->cfgPut();
                _message('修改成功');
        }
        // 从数据库读取谷歌验证码是否已绑定，用于页面初始显示“已绑定/未绑定”
        $google_bound = $this->getConfig('google_authenticator_bound');
        $google_bound = (trim((string)$google_bound) === '1') ? 1 : 0;
        $array = [
            ['id' => 1,'title' => '字符验证码'],
            ['id' => 2,'title' => '手机验证码'],
            ['id' => 3,'title' => '谷歌验证码 (推荐)'],
            ['id' => 4,'title' => '算法验证码'],
            ['id' => 5,'title' => '行为验证码']
        ];
        include  $this->tpl(ROUTE_M,'config.login');
    }

    public function setLoginVerify(){
        $web_verify = safe_replace($_POST['status']);
        $this->db->Query("update `@#_config` set `value` = $web_verify where `name` = 'web_verify'");
        $ok=$this->cfgPut();
        $this->jsonData->success();
    }

    public function webcfg(){
        $web=System::load_sys_config('system');
        $web['qqkfewm'] = safe_unserialize($web['qqkfewm']);
        $web['wxkfewm'] = safe_unserialize($web['wxkfewm']);
        $web['celebrate_img'] = safe_unserialize($web['celebrate_img']);
        $web['web_time_format'] = safe_unserialize($web['web_time_format']);
        // var_dump($web);exit;
        if(isset($_POST['dosubmit'])){
            $web_name=htmlspecialchars($_POST['web_name']); //网站名称
            $this->setConfig("web_name",$web_name,"平台名称"); 

            $web_des=htmlspecialchars($_POST['web_des']);   //网站备注
            $this->setConfig("web_des",$web_des,"网站描述");
            
            $web_path=htmlspecialchars($_POST['web_path']); //平台域名
            $this->setConfig("web_path",$web_path,'平台域名');
            
            $winning_method=htmlspecialchars($_POST['winning_method']);//开奖方式
            $this->setConfig("winning_method",$winning_method);
            
            $is_preferential = $_POST['is_preferential'];//优惠购
            $this->setConfig("is_preferential",$is_preferential);
            $sql = "update `@#_ments` set `status` = $is_preferential where `d` = '/dingdan/yhlists'";
            $res = $this->db->Query($sql);

            $is_group = $_POST['is_group'];//拼团购
            $this->setConfig("is_group",$is_group);
            $sql = "update `@#_ments` set `status` = $is_group where `d` = '/dingdan/groupList'";
            $res = $this->db->Query($sql);

            $is_shownotice=htmlspecialchars($_POST['is_shownotice']);
            $this->setConfig("is_shownotice",$is_shownotice);

            $banner=$_POST['banner'];
            $this->setConfig("banner",$banner);

            $web_xxczts=htmlspecialchars($_POST['web_xxczts']);
            $this->setConfig("web_xxczts",$web_xxczts);

            $web_copyright=htmlspecialchars($_POST['web_copyright']);
            $this->setConfig("web_copyright",$web_copyright);

            $margin_bottom = htmlspecialchars($_POST['margin_bottom']);
            $this->setConfig("margin_bottom",$margin_bottom);

            $margin_top = htmlspecialchars($_POST['margin_top']);
            $this->setConfig("margin_top",$margin_top);

            $goods_end_time = safe_replace(intval($_POST['goods_end_time']));
            if($goods_end_time < 30 && $goods_end_time != 0){
                $goods_end_time = 180;
            }
            if($goods_end_time >= 300){
                $goods_end_time = 180;
            }
            if($goods_end_time <= 0){
                $goods_end_time = 10;
            }
            $this->setConfig("goods_end_time",$goods_end_time);

            $admindir = trim((string)($_POST['admindir'] ?? ''));
            if (!preg_match('/\A[a-zA-Z][a-zA-Z0-9_]*\z/D', $admindir)) {
                _messageerror("后台目录名只能包含英文字母、数字和下划线，且必须以字母开头");
                return;
            }
            $admindir_one = __DIR__;
            $admindir_two = dirname($admindir_one).DIRECTORY_SEPARATOR.$admindir;
            if ($admindir_one !== $admindir_two) {
                if (file_exists($admindir_two) || !rename($admindir_one, $admindir_two)) {
                    _messageerror("后台目录重命名失败，请检查其他设置是否已保存");
                    return;
                }
            }
            $this->setConfig("admindir",$admindir);

            $qq=$_POST['qq'];
            $this->setConfig("qq",$qq);
            $wx=$_POST['wx'];
            $this->setConfig("wx",$wx);

            $web_language = htmlspecialchars($_POST['web_language']);
            $this->setConfig("web_language",$web_language);
            
            $order_number_prefix_dp = htmlspecialchars($_POST['order_number_prefix_dp']);
            $this->setConfig("order_number_prefix_dp",$order_number_prefix_dp);
            
            $web_time_format = $_POST['web_time_format'];  
            $web_time_format = serialize($web_time_format);
            $this->setConfig("web_time_format",$web_time_format);

            $web_search=$_POST['web_search'];
            $web_search1 = json_encode($web_search);
            $web_search1 = str_replace('\\',"\\\\",$web_search1);
            $this->setConfig("web_search",$web_search1);

            // is_upload 写入 go_config 表，下方 cfgPut() 会同步到 system.inc.php，保证 _cfg('is_upload') 等功能正常
            $is_upload = intval($_POST['is_upload']);
            $this->setConfig("is_upload",$is_upload);

            $is_offline_recharge = intval($_POST['is_offline_recharge']);
            $this->setConfig("is_offline_recharge",$is_offline_recharge,"线下充值");
            $sql = Assemblydata(["status"=>$is_offline_recharge],"ments","up",[['d',"/member/member/down_reg"]]);
            $this->db->Query($sql);

            $is_charlie_recharge = intval($_POST['is_charlie_recharge']);
            $this->setConfig("is_charlie_recharge",$is_charlie_recharge,"卡密充值");
            $sql = Assemblydata(["status"=>$is_charlie_recharge],"ments","up",[['d',"/czk/vote_admin/init"]]);
            $this->db->Query($sql);

            $is_bank_card_recharge = intval($_POST['is_bank_card_recharge']);
            $this->setConfig("is_bank_card_recharge",$is_bank_card_recharge,"银行卡充值");
            $sql = Assemblydata(["status"=>$is_bank_card_recharge],"ments","up",[['d',"/member/member/down_money_list"]]);
            $this->db->Query($sql);

            $is_usdt_recharge = intval($_POST['is_usdt_recharge']);
            $this->setConfig("is_usdt_recharge",$is_usdt_recharge,"usdt充值");
            $sql = Assemblydata(["status"=>$is_usdt_recharge],"ments","up",[['d',"/member/member/down_usdt_list"]]);
            $this->db->Query($sql);

            $backend_copyright = intval($_POST['backend_copyright']);
            $this->setConfig("backend_copyright",$backend_copyright);

            $web_language_currency = htmlspecialchars($_POST['web_language_currency']);
            $this->setConfig("web_language_currency",$web_language_currency);
            $web_language_backend = htmlspecialchars($_POST['web_language_backend']);
            $this->setConfig("web_language_backend",$web_language_backend,"后台语言");

            $web_off=safe_replace(htmlspecialchars($_POST['web_off']));
            $this->setConfig("web_off",$web_off);

            $web_off_text=safe_replace(htmlspecialchars($_POST['web_off_text']));
            $this->setConfig("web_off_text",$web_off_text);

            $is_popup_banner=safe_replace(htmlspecialchars($_POST['is_popup_banner']));
            $this->setConfig("is_popup_banner",$is_popup_banner);

            $is_turntable=safe_replace(htmlspecialchars($_POST['is_turntable']));
            $this->setConfig("is_turntable",$is_turntable,"转盘弹窗");

            // $web_name_two=htmlspecialchars($_POST['web_name_two']); //短网站名
            // $this->setConfig("web_name_two",$web_name_two);

            // $web_key=htmlspecialchars($_POST['web_key']); //网站关键字
            // $this->setConfig("web_key",$web_key);
            //在线兑换
            $online_exchange = htmlspecialchars($_POST['online_exchange']); //
            $this->setConfig("online_exchange",$online_exchange);
            //晒单
            $is_shaidan = htmlspecialchars($_POST['is_shaidan']); //
            $this->setConfig("is_shaidan",$is_shaidan);

            $ok=$this->cfgPut();
            $html="<?php\n ";
            $html.="return array(\n";

            $html.="'proportion' =>'".$_POST['proportion']."' \n ";
            $html.="); \n ?>";
            $ok1=file_put_contents(G_CONFIG.'proportion.inc.php',$html);

            //绑定域名
            $domain = $_POST['domain_k'];
            $m = $_POST['domain_m'];
            $c = $_POST['domain_c'];
            $a = $_POST['domain_a']?:"init";
            $domain_cfg[$domain] = array("m"=>$m,"c"=>$c,"a"=>$a);
            $html  = "<?php \n\n";
            $html .= "return ";
            $html .= var_export($domain_cfg,true).';';	
            $html .= "\n\n?>";

            if(!is_writable(G_CONFIG.'domain.inc.php')) exit('domain.inc.php 没有写入权限!');
            file_put_contents(G_CONFIG.'domain.inc.php',$html);
            
            //中奖通知
            $s_type = safe_replace(htmlspecialchars($_POST['s_type']));
            $html = "<?php return array('type'=>'$s_type'); ?>";
            if(!is_writable(G_CONFIG.'send.inc.php')) exit('send.inc.php 没有写入权限!');
            file_put_contents(G_CONFIG.'send.inc.php',$html);
            
            if($ok && $ok1){

                _message("修改成功");

            }else{

                _messageerror("修改失败");

            }
        }
        $web['web_search'] = json_decode($web['web_search'], true);
        $proportion=System::load_sys_config("proportion","proportion");//回收比例
        $languages = $this->getLanguageList(1);
        // 处理前端语言的国旗URL
        foreach($languages as $key=>$v){
            if(isset($v['national_flag']) && !empty($v['national_flag']) && isset($v['flag_status']) && $v['flag_status'] == 1){
                $flag = explode('/', $v['national_flag']);
                if(count($flag) <= 1){
                    $languages[$key]['flag_url'] = G_PLUGIN_PATH."/imgs/flag/".$v['national_flag'];
                }elseif(count($flag) > 1){
                    $languages[$key]['flag_url'] = G_UPLOAD_PATH."/".$v['national_flag'];
                }else{
                    $languages[$key]['flag_url'] = '';
                }
            }else{
                $languages[$key]['flag_url'] = '';
            }
            
            // 如果没有国旗URL，根据语言代码生成SVG国旗图标（使用内联SVG）
            if(empty($languages[$key]['flag_url'])){
                $languages[$key]['flag_svg'] = $this->getFlagSvgByLanguage($v['lg_value'] ?? 'Chinese');
                $languages[$key]['flag_url'] = ''; // 保持为空，使用flag_svg
            } else {
                // 即使有flag_url，也生成flag_svg作为备选
                $languages[$key]['flag_svg'] = $this->getFlagSvgByLanguage($v['lg_value'] ?? 'Chinese');
            }
        }
        $language_backend = $this->getLanguageList(1,3);
        // 处理后端语言的国旗URL
        foreach($language_backend as $key=>$v){
            $n = strpos($v['lg_name'],"（");
            if($n === false){
                $n = strlen($v['lg_name']);
            }
            $language_backend[$key]['lg_name'] = substr($v['lg_name'],0,$n);
            
            // 处理国旗URL
            if(isset($v['national_flag']) && !empty($v['national_flag']) && isset($v['flag_status']) && $v['flag_status'] == 1){
                $flag = explode('/', $v['national_flag']);
                if(count($flag) <= 1){
                    $language_backend[$key]['flag_url'] = G_PLUGIN_PATH."/imgs/flag/".$v['national_flag'];
                }elseif(count($flag) > 1){
                    $language_backend[$key]['flag_url'] = G_UPLOAD_PATH."/".$v['national_flag'];
                }else{
                    $language_backend[$key]['flag_url'] = '';
                }
            }else{
                $language_backend[$key]['flag_url'] = '';
            }
            
            // 如果没有国旗URL，根据语言代码生成SVG国旗图标（使用内联SVG）
            if(empty($language_backend[$key]['flag_url'])){
                $language_backend[$key]['flag_svg'] = $this->getFlagSvgByLanguage($v['lg_value'] ?? 'Chinese');
                $language_backend[$key]['flag_url'] = ''; // 保持为空，使用flag_svg
            } else {
                $language_backend[$key]['flag_svg'] = '';
            }
        }
        $domain_cfg = System::load_sys_config("domain");
        $s_type = System::load_sys_config("send","type");
        include $this->tpl(ROUTE_M,'config.webcfg');

    }
    public function c_iconv($file,$type='3'){
        include_once("lib/Iconv.class.php");
        if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
            if ($file['size'] > 2100000) {
                $msg['ok'] = false;
                $msg['msg'] = "你上传的文件体积超过了限制 最大不能超过2M";
            } else {
                $fileext = array("image/pjpeg", "image/gif", "image/x-png", "image/png", "image/jpeg", "image/jpg");
                if (!in_array($file['type'], $fileext)) {
                                $msg['ok'] = false;
                                $msg['msg'] = "你上传的文件格式不正确 仅支持 png";
                }else {
                    if ($im = @imagecreatefrompng($file['tmp_name']) or $im = @imagecreatefromgif($file['tmp_name']) or $im = @imagecreatefromjpeg($file['tmp_name'])) {
                        $imginfo = @getimagesize($file['tmp_name']);
                        if (!is_array($imginfo)) {
                                $msg['ok'] = false;
                                $msg['msg'] = "图形格式错误!";
                        }else {
                            switch ($type) {
                                case 1;
                                    $resize_im = @imagecreatetruecolor(16, 16);
                                    $size = 16;
                                    break;
                                case 2;
                                    $resize_im = @imagecreatetruecolor(32, 32);
                                    $size = 32;
                                    break;
                                case 3;
                                    $resize_im = @imagecreatetruecolor(48, 48);
                                    $size = 48;
                                    break;
                                case 4;
                                    $resize_im = @imagecreatetruecolor(64, 64);
                                    $size = 64;
                                    break;
                                case 5;
                                    $resize_im = @imagecreatetruecolor(128, 128);
                                    $size = 128;
                                    break;
                                default;
                                    $resize_im = @imagecreatetruecolor(64, 64);
                                    $size = 64;
                                    break;
                            }
                            imagecopyresampled($resize_im, $im, 0, 0, 0, 0, $size, $size, $imginfo[0], $imginfo[1]);
                            $icon = new Iconv();
                            $gd_image_array = array($resize_im);
                            $icon_data = $icon->GDtoICOstr($gd_image_array);
                            $filename = dirname(dirname(dirname(dirname(__FILE__))))."/favicon.ico";
                            if (file_put_contents($filename, $icon_data)) {
                                    $msg['ok']=1;
                                $msg['dir']=$filename;
                            }
                        } 
                    }else {
                        $msg['ok'] = false;
                        $msg['msg'] = "生成错误请重试";
                    }
                }
            }
        }else{
            $msg['ok'] = false;
            $msg['msg'] = "请选择图片!";
        }
        return  $msg;
    }

    public function email(){

        $cesi=$this->segment(4);
        $this->ment = [
            array("lists","邮箱设置",ROUTE_M.'/'.ROUTE_C."/email"),
            array("lists","邮箱模版设置",ROUTE_M."/template/email_temp"),
        ];
        if($cesi=='cesi'){

            $youxiang=$this->segment(5);		

            $youxiang=str_replace("|",".",$youxiang);

            $ok=_sendemail($youxiang,'',"后台邮箱配置测试成功","<b>恭喜你邮箱测试成功</b>","1","0");

            if($ok=='1'){

                echo "邮件测试成功";

            }else{

                echo "邮件测试失败";

            }

            exit;

        }

        if(isset($_POST['dosubmit'])){

            $stmp_host=htmlspecialchars($_POST['server']);

            $email=htmlspecialchars($_POST['email']);

            $user=htmlspecialchars($_POST['user']);

            $pass=htmlspecialchars($_POST['pass']);

            $big=htmlspecialchars($_POST['big']);

            $fromName=htmlspecialchars($_POST['name']);

            $html=<<<HTML

            <?php 

            return array (	

                'stmp_host' => '{$stmp_host}',	//stmp服务器

                'user' => '{$user}',//账号

                'pass' => '{$pass}',		//密码

                'big' => '{$big}',				//发送编码

                'from' => "{$email}",//发件人

                'fromName' => "{$fromName}",  		//发件人名

                'nohtml' => "不支持HTML格式"  	//不支持HTML

            );

            ?>

HTML;

            if(!is_writable(G_CONFIG.'email.inc.php')) _message('Please chmod  email  to 0777 !');

            $ok=file_put_contents(G_CONFIG.'email.inc.php',$html);

            if($ok){

                _message("操作成功");

            }

        }

        

        $info=System::load_sys_config("email");			

        include $this->tpl(ROUTE_M,'config.email');

    }

    public function mobile_add(){
        $mobiles = System::load_sys_config("mobile");
        if(isset($_POST['dosubmit'])){
            $name = $_POST['name'];
            $key =  $_POST['key'];
            $value =  $_POST['value'];
            $title =  trim($_POST['title']);
            $info = [];
            $input = [];
            foreach($key as $k=>$v){
                $k1 = trim($v);
                if(empty($k1)) continue;
                $v1 = trim($value[$k]);
                $name1 = trim($name[$k]);
                $info[$k1] = $v1;
                $input[] = ["name"=>$name1,"key"=>$k1,"value"=>$v1];
            }
            if(empty($info)){
                _messageerror("请填写完整信息");
            }
            $info['input'] = $input;
            $info['title'] = $title;
            $arr = [];
            foreach($mobiles as $k=>$v){
                $t = explode("_",$k);
                $t = end($t);
                if(is_numeric($t)){
                    $arr[] = $t;
                }
            }
            $key = 'cfg_mobile_'.(max($arr)+1);
            $info['no'] = max($arr)+1;
            $mobiles[$key] = $info;
            if(!is_writable(G_CONFIG.'mobile.inc.php')) _message('Please chmod  mobile.ini.php  to 0777 !');
                
            $html  = var_export($mobiles,true);
            $html  = "<?php \n return ".$html."; \n?>";
            
            $ok=file_put_contents(G_CONFIG.'mobile.inc.php',$html);
            _message("ok key:".$key);
        }
        include $this->tpl(ROUTE_M,'config.mobile_add');
    }

    /*短信配置与测试*/
    public function mobile(){
        $mobiles = System::load_sys_config("mobile");
        $sendobj = System::load_sys_class("sendmobile");
        $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
        /*发送测试开始*/
        /*
        $sendobj -> init(array("mobile"=>"18306084563","content"=>"..."));
        $ok = $sendobj -> send();
        var_dump($ok);
        */
        /*发送测试完成*/
        $web = System::load_sys_config("system");
        /*修改和启用短信接口*/
        if(isset($_POST['dosubmit'])){

            //通知设置
            $rcnotice = $_POST['rcnotice'];
            $rcmobile1 = $_POST['rcmobile1'];
            $this->setConfig("rcnotice",$rcnotice,"充值是否通知");
            $this->setConfig("rcmobile1",$rcmobile1,"充值通知电话");
            $this->cfgPut();
            //短信配置

            $code= trim($_POST['code']);			
            $cfg_id= trim($_POST['mid']);			
            $cfg_pass = trim($_POST['mpass']);	
            $cfg_qianming = trim(isset($_POST['mqianming']) ? $_POST['mqianming'] : '');
            $cfg_type  = abs(intval($_POST['interface']));
            $productid=trim(isset($_POST['productid']) ? $_POST['productid'] : "");
            $mname=trim(isset($_POST['mname']) ? $_POST['mname'] : "");
            $mobiles['cfg_mobile_on'] = $cfg_type;			
            $key = "cfg_mobile_".$cfg_type;
            if(trim($_POST['mid1'])){
                $mobiles[$key]['mid1'] =  trim($_POST['mid1']);
            }
            if(trim($_POST['mpass1'])){
                $mobiles[$key]['mpass1'] =  trim($_POST['mpass1']);
            }
            if(trim($_POST['userid'])){
                $mobiles[$key]['userid'] =  trim($_POST['userid']);
            }
            if(trim($_POST['cid'])){
                $mobiles[$key]['cid'] =  trim($_POST['cid']);
            }
            $mobiles[$key]['code'] =  $code;
            $mobiles[$key]['mid'] =  $cfg_id;
            $mobiles[$key]['mpass'] =  $cfg_pass;
            $mobiles[$key]['mqianming'] =  $cfg_qianming;
            if($productid){
                $mobiles[$key]['productid'] =  $productid;
            }
            if($mname){
                $mobiles[$key]['mname'] =  $mname;
            }		
            if($cfg_pass=='******'){
                _message("保存需要在输入一次短信密码!!!");
            }
            foreach($_POST as $k=>$v){
                $mobiles[$key][$k] = trim($v);
            }

            if(!is_writable(G_CONFIG.'mobile.inc.php')) _message('Please chmod  mobile.ini.php  to 0777 !');
                
            $html  = var_export($mobiles,true);
            $html  = "<?php \n return ".$html."; \n?>";
            
            $ok=file_put_contents(G_CONFIG.'mobile.inc.php',$html);
            if($ok){
                _message("短信配置更新成功!");
            }
                
        }
        
        
        /*短信测试*/
        if(isset($_POST['ceshi_submit'])){
            $_POST['ceshi_haoma'] = trim($_POST['ceshi_haoma']);
            $_POST['area_code'] = trim($_POST['area_code']);
            $_POST['ceshi_con'] = trim($_POST['ceshi_con']);
            
            if(empty($_POST['ceshi_con']) || empty($_POST['ceshi_haoma'])){
                echo json_encode(array("-1","内容或者手机号不能为空!"));
                return;
            }			

            if(!is_numeric($_POST['ceshi_haoma'])){
                echo json_encode(array("-1","手机号不正确!"));
                return;
            }
            
            $sendok=_sendmobile($_POST['area_code'].$_POST['ceshi_haoma'],$_POST['ceshi_con']);
            // $sendok = _sendmobile1($_POST['ceshi_haoma'],$_POST['ceshi_con'],8);//备用通道测试
            echo json_encode($sendok);
            return;	
        }
        
        /************************************/
        /*短信条数*/
// 		foreach($mobiles as $k=>$v){
// 			//$mobiles[$k]['mobile_text'] = "<b style='color:#0c0'>短信功能正常</b>,短信还剩余 ".$k." 条";
// 			//if($k=="cfg_mobile_on"){
// 					//$mobiles[$k]['mobile_text'] = "<b style='color:#0c0'>短信功能正常11</b>,短信还剩余 ".$v." 条";
// 				//$k_t_fun = "cfg_getdata_".$v;
// 				//$sendobj -> $k_t_fun();
// 				//if($sendobj->v){
// 					//$mobiles[$k]['mobile_text'] = "<b style='color:#0c0'>短信功能正常</b>,短信还剩余 ".$sendobj->v." 条";
// 				//}else{
// 					//$mobiles[$k]['mobile_text'] = "<b style='color:#ff0000'>短信测试失败</b>,失败原因:".$sendobj->error;
// 				//}				
// 			//}
// 			if(is_array($v)){
// 				$k_t = explode("_",$k);
// 				$k_t = array_pop($k_t);
// 				$k_t_fun = "cfg_getdata_".$k_t;
// //				if($mobiles["cfg_mobile_on"]==$k_t){
// //								$sendobj -> $k_t_fun();
// //
// //				if($sendobj->v){
// //					$mobiles[$k]['mobile_text'] = "<b style='color:#0c0'>短信功能正常</b>，短信还剩余 ".$sendobj->v." 条";
// //				}else{
// //					$mobiles[$k]['mobile_text'] = "<b style='color:#ff0000'>短信测试失败</b>,失败原因:".$sendobj->error;
// //				}
// //
// //				}
//                 if($mobiles["cfg_mobile_on"]==$k_t){

//                     if($k_t != 8){
//                         $sendobj -> $k_t_fun();
//                         if($sendobj->v){
//                             $mobiles[$k]['mobile_text'] = "<b style='color:#0c0'>短信功能正常</b>，短信还剩余 ".$sendobj->v." 条";
//                         }else{
//                             $mobiles[$k]['mobile_text'] = "<b style='color:#ff0000'>短信测试失败</b>,失败原因:".$sendobj->error;
//                         }
//                     }
//                 }
            

// 			}
            
// 		}
        $mobiles['cfg_mobile_'.count($mobiles)] = array("mid"=>"","mpass"=>"","mobile_text"=>"");
        $languages = $this->getLanguageList(1);
        include $this->tpl(ROUTE_M,'config.mobile');
    }

    //获取短信余额
    public function get_sms_balance(){
        $key = $this->segment(4);
        $sends=System::load_sys_class('sendmobile');
        $res = $sends->get_sms_total($key);
        if($res['status']){
            $data = ["total"=>$res['balance']];
        }else{
            $data = ["total"=>"请登录商户平台查看","msg"=>"出错"];
        }
        $this->jsonData->success(200,"",$data);
    }
    
    public function rechargeNoticStatus(){
        $rcnotice = safe_replace($_POST['status']);
        $this->db->Query("update `@#_config` set `value` = $rcnotice where `name` = 'rcnotice'");
        $ok=$this->cfgPut();
        $this->jsonData->success();
    }
    
    

    public function mobiles(){
        $mobile=array('mid'=>'','mpass'=>'');
        $mobile=System::load_sys_config("mobile");
                
        $cesi=$this->segment(4);		
        
        if(isset($_POST['dosubmit_ceshi'])){
            $sendobj = System::load_sys_class("sendmobile");
                        
            $_POST['ceshi_haoma'] = safe_replace(trim($_POST['ceshi_haoma']));
            $_POST['ceshi_con'] = safe_replace(trim($_POST['ceshi_con']));
            
            if(empty($_POST['ceshi_con']) || empty($_POST['ceshi_haoma'])){
                echo json_encode(array("-1","内容或者手机号不能为空!"));
                return;
            }			
            $ret = $sendobj->mobile_con_check($_POST['ceshi_con']);
            
            //内容检测不合法返回
            if($ret[0]==-1){
                echo json_encode($ret);
                return;
            }
            if(!is_numeric($_POST['ceshi_haoma'])){
                echo json_encode(array("-1","手机号不正确!"));
                return;
            }
            $sendok=_sendmobile(safe_replace($_POST['ceshi_haoma']),safe_replace($_POST['ceshi_con']));
            echo json_encode($sendok);
            return;			
        }/*if end*/
        
        if(isset($_POST['dosubmit'])){
        
                $cfg_id= safe_replace(trim($_POST['mid']));			
                $cfg_pass = safe_replace(trim($_POST['mpass']));	
                $cfg_qianming = isset($_POST['mqianming']) ? safe_replace(trim($_POST['mqianming'])) : '';
                $cfg_type  = abs(intval($_POST['interface']));
                        
                if($cfg_type == 1){
                    $mobile['cfg_mobile_1']['mid']    		= $cfg_id;
                    $mobile['cfg_mobile_1']['mpass']  		= $cfg_pass;
                    $mobile['cfg_mobile_1']['mqianming']   	= $cfg_qianming;	
                    $mobile['cfg_mobile_2']['mid'] 			= $mobile['cfg_mobile_2']['mid'];
                    $mobile['cfg_mobile_2']['mpass'] 		= $mobile['cfg_mobile_2']['mpass'];
                    $mobile['cfg_mobile_2']['mqianming']	= $mobile['cfg_mobile_2']['mqianming'];
                }
                
                if($cfg_type == 2){
                    $mobile['cfg_mobile_1']['mid'] 			= $mobile['cfg_mobile_1']['mid'];
                    $mobile['cfg_mobile_1']['mpass'] 		= $mobile['cfg_mobile_1']['mpass'];
                    $mobile['cfg_mobile_1']['mqianming']	= $mobile['cfg_mobile_1']['mqianming'];
                    $mobile['cfg_mobile_2']['mid'] 			= $cfg_id;
                    $mobile['cfg_mobile_2']['mpass'] 		= $cfg_pass;
                    $mobile['cfg_mobile_2']['mqianming']	= $cfg_qianming;				
                }
                    if($cfg_type == 7){
                // 	$mobile['cfg_mobile_7']['mid'] 			= $mobile['cfg_mobile_7']['mid'];
                // 	$mobile['cfg_mobile_7']['mpass'] 		= $mobile['cfg_mobile_7']['mpass'];
                // 	$mobile['cfg_mobile_7']['mqianming']	= $mobile['cfg_mobile_7']['mqianming'];
                    $mobile['cfg_mobile_7']['mid'] 			= $cfg_id;
                    $mobile['cfg_mobile_7']['mpass'] 		= $cfg_pass;
                    $mobile['cfg_mobile_7']['mqianming']	= $cfg_qianming;				
                }
                $mobile['cfg_mobile_on'] = $cfg_type;
                        
                if(!is_writable(G_CONFIG.'mobile.inc.php')) _message('Please chmod  mobile.ini.php  to 0777 !');
                
                $html  = var_export($mobile,true);
                $html  = "<?php \n return ".$html."; \n?>";
                $ok=file_put_contents(G_CONFIG.'mobile.inc.php',$html);
                if($ok){
                    _message("短信配置更新成功!");
                }
        }
        
        $sendmobile=System::load_sys_class("sendmobile");
        
        $sendmobile->GetBalance();			
        if($sendmobile->error==1){
            $text2="<b style='color:#0c0'>短信功能正常</b>，短信还剩余 ".$sendmobile->v." 条";
        }else{
            $text2="<b style='color:#ff0000'>短信测试失败</b>，失败原因:".$sendmobile->v;
        }
        
        $new_mbl = $sendmobile->GetBalance_new();
        if($new_mbl['id']){
            $text1= "<b style='color:#0c0'>短信功能正常</b>，短信还剩余 ".$new_mbl['id']." 条";
        }else{
            $text1= "<b style='color:#ff0000'>短信测试失败</b>，失败原因:".$new_mbl['err'];
        }	
        
        if(!isset($mobile['cfg_mobile_2'])){
            $mobile['cfg_mobile_1'] = $mobile['cfg_mobile_2'] = array(); 
            $mobile['cfg_mobile_2']['mid'] 			= $mobile['mid'];
            $mobile['cfg_mobile_2']['mpass'] 		= $mobile['mpass'];;
            $mobile['cfg_mobile_2']['mqianming']	= $mobile['mqianming'];		
            $mobile['cfg_mobile_1'] = array(); 
            $mobile['cfg_mobile_1']['mid'] 			= '';
            $mobile['cfg_mobile_1']['mpass'] 		= '';
            $mobile['cfg_mobile_1']['mqianming']	= '';
            
        }		
        include $this->tpl(ROUTE_M,'config.mobile');
    }

    

    //上传配置

    public function upload(){	

        if(isset($_POST['dosubmit'])){

                $up_image_type = htmlspecialchars($_POST['up_image_type']);	

                $up_soft_type = htmlspecialchars($_POST['up_soft_type']);	

                $up_media_type = htmlspecialchars($_POST['up_media_type']);	

                $upsize = intval($_POST['upsize']);

                $up_image_type = trim($up_image_type,',');

                $up_soft_type = trim($up_soft_type,',');

                $up_media_type = trim($up_media_type,',');

                                

                EditConfig("upload","upsize",$upsize,'xiao');

                EditConfig("upload","up_image_type",$up_image_type,'xiao');

                EditConfig("upload","up_soft_type",$up_soft_type,'xiao');

                EditConfig("upload","up_media_type",$up_media_type,'xiao');				

        

                _message("操作成功!");				

                

        }

        $web=System::load_sys_config('upload');

        /*	

            $up_image_type = implode(',',$web['up_image_type']);

            $up_soft_type = implode(',',$web['up_soft_type']);

            $up_media_type = implode(',',$web['up_media_type']);

        */

        $up_image_type = $web['up_image_type'];

        $up_soft_type = $web['up_soft_type'];

        $up_media_type = $web['up_media_type'];

        include $this->tpl(ROUTE_M,'config.upload');

    }

    //水印配置

    public function watermark(){

        $upload_set = System::load_sys_config("upload");

        if(isset($_POST['dosubmit'])){
            
            $watermark_image=$upload_set['watermark_image'];
            
            if($_FILES){
                $path='banner';	
                $upload=System::load_sys_class('upload','sys','no');		
                foreach($_FILES as $k=>$v){
                    if($v['error'] == '0'){
                        upload::upload_config($path);
                        $res=upload::go_uploads($_FILES[$k],$k);	
                        if(!$res['ok']){	
                            _message($res['msg']);				
                        }else{
                            $watermark_image=$path.'/'.$res['dir'];						
                        }
                    }
                }	
            }

            $watermark_off = $_POST['watermark_off'];

            $watermark_type = $_POST['watermark_type'];

            

            $text = htmlspecialchars($_POST['text']);

            $color = htmlspecialchars($_POST['color']);

            $size = intval($_POST['size']);

            

            $width = intval($_POST['width']);

            $height = intval($_POST['height']);

            //$image = htmlspecialchars($_POST['image']);

            $apache = intval($_POST['apache']);

            $good = intval($_POST['good']);

            $sel = htmlspecialchars($_POST['sel']);

            $html =<<<HTML

            <?php 

            /*

                上传和水印配置

                @up_image_type 		上传图片类型

                @up_soft_type		上传附件类型

                @up_media_type		上传媒体类型

                @upsize				允许单文件最大大小

                @watermark_off		水印开启

                @watermark_type		水印类型

                @watermark_condition

                @watermark_text		文本水印配置

                @watermark_image	图片水印地址

                @watermark_position 水印位置

            */

            return array(

                'up_image_type' => '$upload_set[up_image_type]',

                'up_soft_type' => '$upload_set[up_soft_type]',

                'up_media_type' => '$upload_set[up_media_type]',

                'upsize' => '$upload_set[upsize]',

                'watermark_off' => '$watermark_off',

                'watermark_condition' => array('width'=>'$width','height'=>"$height"),

                'watermark_type' => '$watermark_type',

                'watermark_text' => array('text'=>"$text",'color'=>"$color",'size'=>"$size",'font'=>'C:\WINDOWS\Fonts\simhei.ttf'),

                'watermark_image' => '$watermark_image',

                'watermark_position' => '$sel',

                'watermark_apache' => '$apache',

                'watermark_good' => '$good',

            );

            ?>

HTML;

            $path = G_CONFIG.'/'.'upload.inc.php';

            file_put_contents($path,$html);

            _message("修改成功");

        }

                

        include $this->tpl(ROUTE_M,'config.watermark');

    }

    

    //验证码配置

    public function checkcode(){

            if(isset($_POST['type'])){

            $info= array();

            $info['width']=$_POST['width'];

            $info['height']=$_POST['height'];

            $info['color']=$_POST['color'];

            $info['bgcolor']=$_POST['bgcolor'];

            $info['lenght']=$_POST['lenght'];

            $info['type']=$_POST['type'];

            

            $html_a=var_export($info,true);

            $html="

                <?php 

                    return {$html_a};

                ?>

            ";

            $path = G_CONFIG.'/'.'checkcode.inc.php';

            file_put_contents($path,$html);

            }

            include $this->tpl(ROUTE_M,'config.checkcode');

    }	

    public function codeSet(){

        if(isset($_POST['coubmit'])) {
            $google_statistics_url = htmlspecialchars($_POST['google_statistics_url']);
            $google_statistics_content = htmlspecialchars($_POST['google_statistics_content']);
            $this->db->Query("UPDATE `@#_config` SET `value`='$google_statistics_url' WHERE `name`='google_statistics_url'");
            $this->db->Query("UPDATE `@#_config` SET `value`='$google_statistics_content' WHERE `name`='google_statistics_content'");

            $google_statistics_android = htmlspecialchars($_POST['google_statistics_android']);
            $google_statistics_ios = htmlspecialchars($_POST['google_statistics_ios']);
            $google_statistics_push = htmlspecialchars($_POST['google_statistics_push']);
            $facebook_pixel_id = htmlspecialchars($_POST['facebook_pixel_id']);
            $facebook_api_id = htmlspecialchars($_POST['facebook_api_id']);
            $Kwai_pixel_id = htmlspecialchars($_POST['Kwai_pixel_id']);
            $Tiktok_pixel_id = htmlspecialchars($_POST['Tiktok_pixel_id']);
            $Gtag_pixel_id = htmlspecialchars($_POST['Gtag_pixel_id']);
            $Liftoff_pixel_id = htmlspecialchars($_POST['Liftoff_pixel_id']);
            $customize_pixel_id = htmlspecialchars($_POST['customize_pixel_id']);

            $res = $this->db->getOne("select * from `@#_config` where name = 'google_statistics_android'");
            if($res){
                $this->db->Query("UPDATE `@#_config` SET `value`='$google_statistics_android' WHERE `name`='google_statistics_android'");
            }else{
                $this->db->Query("Insert into `@#_config` (`name`,`value`)value('google_statistics_android','$google_statistics_android')");
            }
            $res = $this->db->getOne("select * from `@#_config` where name = 'google_statistics_ios'");
            if($res){
                $this->db->Query("UPDATE `@#_config` SET `value`='$google_statistics_ios' WHERE `name`='google_statistics_ios'");
            }else{
                $this->db->Query("Insert into `@#_config` (`name`,`value`)value('google_statistics_ios','$google_statistics_ios')");
            }
            $res = $this->db->getOne("select * from `@#_config` where name = 'google_statistics_push'");
            if($res){
                $this->db->Query("UPDATE `@#_config` SET `value`='$google_statistics_push' WHERE `name`='google_statistics_push'");
            }else{
                $this->db->Query("Insert into `@#_config` (`name`,`value`)value('google_statistics_push','$google_statistics_push')");
            }
            $this->setConfig("facebook_pixel_id",$facebook_pixel_id,"facebook_pixel_id");
            $this->setConfig("facebook_api_id",$facebook_api_id,"facebook_api_id");
            $this->setConfig("Kwai_pixel_id",$Kwai_pixel_id,"Kwai_pixel_id");
            $this->setConfig("Tiktok_pixel_id",$Tiktok_pixel_id,"Tiktok_pixel_id");
            $this->setConfig("Gtag_pixel_id",$Gtag_pixel_id,"Gtag_pixel_id");
            $this->setConfig("Liftoff_pixel_id",$Liftoff_pixel_id,"Liftoff_pixel_id");
            $this->setConfig("customize_pixel_id",$customize_pixel_id,"customize_pixel_id");
            $ok=$this->cfgPut();
            if($this->db->affected_rows() && $ok){				
                _message("修改成功");
            }else{
                _message("修改失败");
            }
        }
        $this->ment = [
            array("codeSet","代码设置",ROUTE_M.'/'.ROUTE_C.'/codeSet'),
        ];
        $system_config = System::load_sys_config('system');
		include $this->tpl(ROUTE_M,'setting.code_set');	

    }

    public function community_management(){
        $this->ment = [
            array("community_management","社区管理",ROUTE_M.'/'.ROUTE_C.'/community_management'),
            array("community_add","添加社区",ROUTE_M.'/'.ROUTE_C.'/community_add'),
        ];
        $this->mentLgs();
        $lists = $this->db->GetList("SELECT * FROM `@#_community` ORDER BY sort DESC,id desc");
        foreach($lists as $key=>$vl){
            $lists[$key]["state"] = $vl['status']==1?"开启":"关闭";
        }
        include $this->tpl(ROUTE_M,'setting.community_management');
    }

    public function community_add(){
        $this->ment = [
            array("community_management","社区管理",ROUTE_M.'/'.ROUTE_C.'/community_management'),
            array("community_add","添加社区",ROUTE_M.'/'.ROUTE_C.'/community_add'),
        ];
        $this->mentLgs();
        if(isset($_POST['submit'])){
            $data = array();
            $data['name'] = htmlspecialchars($_POST['name']);
            $data['url'] = htmlspecialchars($_POST['url']);
            $data['img'] = htmlspecialchars($_POST['img']);
            $data['status'] = htmlspecialchars($_POST['status']);
            $data['sort'] = htmlspecialchars($_POST['sort']);
            $sql = Assemblydata($data,"community");
            $this->db->query($sql);
            _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/community_management');
        }
        $languages = $this->getLanguageList(1);
        $lgs = array_column($languages,"country","lg_value");
        include $this->tpl(ROUTE_M,'setting.community_edit');
    }
    public function community_edit(){
        $id = $this->segment(4);
        $this->ment = [
            array("community_management","社区管理",ROUTE_M.'/'.ROUTE_C.'/community_management'),
            array("community_add","添加社区",ROUTE_M.'/'.ROUTE_C.'/community_add'),
        ];
        $this->mentLgs();
        if(isset($_POST['submit'])){
            $data = array();
            $data['name'] = htmlspecialchars($_POST['name']);
            $data['url'] = htmlspecialchars($_POST['url']);
            $data['img'] = htmlspecialchars($_POST['img']);
            $data['status'] = htmlspecialchars($_POST['status']);
            $data['sort'] = htmlspecialchars($_POST['sort']);
            $sql = Assemblydata($data,"community","up",[["id",$id]]);
            $this->db->query($sql);
            _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/community_management');
        }
        $record = $this->db->GetOne("SELECT * FROM `@#_community` WHERE id=$id");
        $language = $this->getLanguageList(1);
        $lgs = array_column($language,"country","lg_value");
        include $this->tpl(ROUTE_M,'setting.community_edit');
    }
    public function community_del($id){
        $this->db->Query("DELETE FROM `@#_community` WHERE id=$id");
        _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/community_management');
    }

    public function community_sort(){
        if($this->segment(4)=='dosubmit'){
            foreach($_POST['listorders'] as $id => $listorder){
                $id = intval($id);
                $listorder = intval($listorder);
                $this->db->Query("UPDATE `@#_community` SET `sort` = '$listorder' where `id` = '$id'");
            }
            _message("排序更新成功");
        }else{
            _messageerror("请排序");
        }
    }
    
    /**
     * 根据语言代码获取国旗SVG图标（返回SVG字符串，用于内联显示）
     */
    private function getFlagSvgByLanguage($lgValue) {
        $flagSvgs = array(
            // 中国国旗 - 红色背景，黄色五角星
            'Chinese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="20" height="15"><rect width="640" height="480" fill="#de2910"/><path d="M213.3 0h213.4v480H213.3z" fill="#ffde00"/><circle cx="320" cy="240" r="80" fill="#de2910"/><circle cx="320" cy="240" r="60" fill="#ffde00"/><path d="M320 180l20 60-50-40h60l-50 40z" fill="#de2910"/></svg>',
            // 美国国旗 - 红白条纹，左上角蓝色区域和白色星星
            'English' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7410 3900" width="20" height="15"><rect width="7410" height="3900" fill="#b22234"/><path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/><rect width="2964" height="2100" fill="#3c3b6e"/></svg>',
            // 越南国旗
            'Vietnamese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="600" fill="#da251d"/><path d="M450 200l50 150-130-95h160l-130 95z" fill="#ff0"/></svg>',
            // 缅甸国旗
            'Myanmar' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="200" fill="#ffcd00"/><rect y="200" width="900" height="200" fill="#34b233"/><rect y="400" width="900" height="200" fill="#ea2839"/><circle cx="450" cy="300" r="100" fill="#fff"/></svg>',
            // 印度国旗（印地语）
            'Hindi' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="200" fill="#ff9933"/><rect y="200" width="900" height="200" fill="#fff"/><rect y="400" width="900" height="200" fill="#138808"/><circle cx="450" cy="300" r="60" fill="#000080"/><circle cx="450" cy="300" r="50" fill="#fff"/></svg>',
            // 葡萄牙国旗
            'Portugal' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>',
            'Portuguese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>',
        );
        
        // 如果找到对应的国旗，返回SVG
        if(isset($flagSvgs[$lgValue])) {
            return $flagSvgs[$lgValue];
        }
        
        // 默认返回一个通用的国旗图标
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="20" height="15"><rect width="640" height="480" fill="#0066cc"/><circle cx="320" cy="240" r="100" fill="#fff"/></svg>';
    }
}

?>