<?php 

defined('G_IN_SYSTEM')or exit('no');

System::load_sys_fun('send');
System::load_app_class('admin',G_ADMIN_DIR,'no');
require_once ("goole/PHPGangsta/GoogleAuthenticator.php");

class user extends admin {

	private $db;

	protected $ment;

	public function __construct(){		

		parent::__construct();

		$this->db=System::load_sys_class("model");

		$this->ment=array(

						array("lists","管理员列表",ROUTE_M.'/'.ROUTE_C."/lists"),

						array("reg","添加管理员",ROUTE_M.'/'.ROUTE_C."/reg"),

						array("edit","修改信息",ROUTE_M.'/'.ROUTE_C."/edit/1",'hide'),

						array("edit","添加管理员",ROUTE_M.'/'.ROUTE_C."/reg",'hide'),

						array("group","管理员分组",ROUTE_M.'/'.ROUTE_C."/group"),

						array("add_group","添加管理员分组",ROUTE_M.'/'.ROUTE_C."/add_group"),

				// 		array("openColumn","菜单栏开启",ROUTE_M.'/'.ROUTE_C."/openColumn"),
				// 		array("add_open","添加菜单",ROUTE_M.'/'.ROUTE_C."/add_open"),

		);	

		

	}
	//添加管理员分组
	public function add_group(){
		if(isset($_POST['name'])){
			$time=time();
			$name = safe_replace($_POST['name']);
			$sql="insert into `@#_admin_usergroup`(name,time) values('$name','$time')";
			$rr-$this->db->Query($sql);
			_message("添加成功!");		
		}
		include $this->tpl(ROUTE_M,'user.add_group');
	}

	//管理员分组列表
	public function group(){
		$grouplist=$this->db->GetList("SELECT * FROM `@#_admin_usergroup`");
		$total=count($grouplist);
		include $this->tpl(ROUTE_M,'user.grouplist');
	}
	public function del_group(){
		$mid=safe_replace($this->segment(4));
		if($mid){
			$sql="DELETE FROM `@#_admin_usergroup` where mid='$mid'";
			$r=$this->db->Query($sql);
			_message("删除成功!");
		}
	}
	public function openColumn(){
		$data=$this->db->GetList("SELECT * from `@#_ments` where 1 order by pid ,sort desc");
	$this->ment = [
			array("openColumn","菜单栏开启",ROUTE_M.'/'.ROUTE_C."/openColumn"),
			array("add_open","添加菜单",ROUTE_M.'/'.ROUTE_C."/add_open")
		];
		foreach($data as &$vl){
			$lg = safe_unserialize($vl['language_name']);
			$vl['name'] = $lg[$this->language_backend]?:$vl['name'];
		}
		include $this->tpl(ROUTE_M,'user.openColumn');	
	}

    public function up_status(){
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

	public function add_open(){
	    	$this->ment = [
			array("openColumn","菜单栏开启",ROUTE_M.'/'.ROUTE_C."/openColumn"),
			array("add_open","添加菜单",ROUTE_M.'/'.ROUTE_C."/add_open")
		];
		if(isset($_POST['submit'])) {
			$data = [];
			// if($_POST['name']) {
			// 	$data['name'] = $_POST['name'];
			// };
			if($_POST['language_name']) {
				$data['language_name'] = $_POST['language_name'];
				$data['name'] = reset($data['language_name']);
				$data['language_name'] = serialize($data['language_name']);
			};
			if(isset($_POST['is_enable'])) {
				$data['is_enable'] = $_POST['is_enable'];
			}
			if(isset($_POST['sort'])) {
				$data['sort'] = (int)$_POST['sort'];
			}
			if(isset($_POST['url'])) {
				$data['d'] = $_POST['url'];
				$d = explode("/", $_POST['url']);
				if(isset($d[1]) && in_array($d[1], ['member','go','pay','moblie','yun4so'])) {
					$data['a'] = 1;
				}else{
					$data['a'] = null;
				}
			}
			if(isset($_POST['pid'])) {
				$data['pid'] = (int)$_POST['pid'];
				if($data['pid'] == 0){
					$data['level'] = 0;
				}else{
					$res = $this->db->GetOne("select * from `@#_ments` where pid = ".$data['pid']);
					if($res['pid'] == 0){
						$data['level'] = 1;
					}else{
						$data['level'] = 2;
					}
				}
			}
			if(isset($_POST['act'])){
				$act = $_POST['act'];
				if($act){
					$data['level'] = 3;
				}
			}
			$sql = Assemblydata($data,"ments",'in');
			$this->db->Query($sql);
			if($this->db->affected_rows()) {
				_message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/openColumn');
			} else {
				_messageerror("添加失败");
			}
		}
		$languages = $this->getLanguageList(1,3);
		$plist = $this->db->GetList("select * from `@#_ments` where (pid = 0 or pid = 1) or d = 'head' order by pid");
		include $this->tpl(ROUTE_M,'user.openColumn.update');
	}

	// 修改-菜单栏
	public function edit_group(){
		$id = safe_replace($this->segment(4));
		$this->ment = [
			array("edit_group","编辑菜单栏",ROUTE_M.'/'.ROUTE_C."/edit_group"),
			array("openColumn","菜单栏开启",ROUTE_M.'/'.ROUTE_C."/openColumn")
		];
		// 获取栏目信息
		$ment_info = $this->db->GetOne("SELECT * FROM `@#_ments` where id=$id");
		$ment_info['language_name'] = safe_unserialize($ment_info['language_name'] ?? '');
		$plist = $this->db->GetList("select * from `@#_ments` where (pid = 0 or pid = 1) or d = 'head' order by pid");
		if(isset($_POST['submit'])) {
			$data = [];
			// if($_POST['name']) {
			// 	$data['name'] = $_POST['name'];
			// };
			if($_POST['language_name']) {
				$data['language_name'] = $_POST['language_name'];
				$data['name'] = reset($data['language_name']);
				$data['language_name'] = serialize($data['language_name']);
			};
			if(isset($_POST['is_enable'])) {
				$data['is_enable'] = $_POST['is_enable'];
			}
			if(isset($_POST['sort'])) {
				$data['sort'] = (int)$_POST['sort'];
			}
			if(isset($_POST['url'])) {
				$data['d'] = $_POST['url'];
				$d = explode("/", $_POST['url']);
				if(isset($d[1]) && in_array($d[1], ['member','go','pay','moblie','yun4so','czk'])) {
					$data['a'] = 1;
				}else{
					$data['a'] = null;
				}
			}
			if(isset($_POST['pid'])) {
				$data['pid'] = (int)$_POST['pid'];
				if($data['pid'] == 0){
					$data['level'] = 0;
				}else{
					$res = $this->db->GetOne("select * from `@#_ments` where id = ".$data['pid']);
					if($res['pid'] == 0){
						$data['level'] = 1;
					}else{
						$data['level'] = 2;
					}
				}
			}
			if(isset($_POST['act'])){
				$act = $_POST['act'];
				if($act){
					$data['level'] = 3;
				}
			}
			$sql = Assemblydata($data,"ments",'up',[['id',$id]]);
			$this->db->Query($sql);
			if($this->db->affected_rows()) {
				_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C.'/openColumn');
			} else {
				_messageerror("修改失败");
			}
		}
		$languages = $this->getLanguageList(1,3);
		include $this->tpl(ROUTE_M,'user.openColumn.update');
	}
	public function chang_column(){
		$data=$_POST;
		if($_POST['id']){
		 $id=safe_replace($_POST['id']);
            $stauts=safe_replace($_POST['zhi']);
			if($stauts == '1'){
				$stauts=1;
			}else{
				$stauts=0;
			}
			$res=$this->db->Query("UPDATE `@#_ments` SET is_enable='$stauts' WHERE id='$id'");
			$this->jsonData->success();
		}
        $this->jsonData->error();
	}
	
    // 自改字段
    public function columnUpdate(){
        $id=safe_replace($_POST['id']);
        $fields=safe_replace($_POST['fields']);
        $value=safe_replace($_POST['value']);
        $res=$this->db->Query("UPDATE `@#_ments` SET '$fields'='$value' WHERE id='$id'");

        echo $res;

    }

	public function columnDel(){
        $id=safe_replace($_POST['id']);
		$secure_password = safe_replace($_POST['secure_password']);
		if(!$this->verifyExamine($secure_password)){
			$this->jsonData->error('',"安全密码错误");
        }
		$res = $this->db->GetOne("select * from `@#_ments` where id = ".$id);
		if($res){
			$c = $this->db->GetOne("select * from `@#_ments` where pid = ".$id);
			if($c){
				$this->jsonData->error('',"此菜单下还有子菜单");
			}else{
				$this->db->Query("delete from `@#_ments` where id = ".$id);
				$this->jsonData->success('',"删除成功");
			}
		}else{
			$this->jsonData->error('',"未找到");
		}
	}
	public function reg(){
		$grouplist=$this->db->GetList("SELECT * FROM `@#_admin_usergroup`");
		if(isset($_POST['submit-1'])){

			$username=safe_replace($_POST['username']);
			$name=safe_replace($_POST['name']);

			if($username!=$_POST['username'] || empty($username)){

				_message("用户名格式错误!");			

			}	

			if(_strlen($username)>15){

				_message("用户名长度为2-15个字符,1个汉字等于2个字符!");

			}

			$password1=$_POST['password'];

			$password2=$_POST['pwdconfirm'];
			$secure_password1=safe_replace($_POST['secure_password']);
			$secure_password2=safe_replace($_POST['secure_pwdconfirm']);

			if(empty($password2) || ($password1!=$password2)){

				_message("2次密码不一致!");

			}
			if(empty($secure_password2) || ($secure_password1!=$secure_password2)){

				_message("2次密码不一致!");

			}

			$pmid=isset($_POST['mid']) ? intval($_POST['mid']) : 0;

			$password=md5($password2);		
			$secure_password=md5($secure_password2);		

			$addtime=time();

			$ip=_get_ip();
            $em = safe_replace($_POST['email']);

			$this->db->Query("INSERT INTO `@#_admin` (`mid`, `username`, `name`,`userpass`, `useremail`, `addtime`, `logintime`, `loginip`,`secure_password`) VALUES ('$pmid', '$username','$name', '$password', '$em','$addtime','0','$ip','$secure_password')");

			if($this->db->affected_rows()){			

				$path=WEB_PATH.ROUTE_M.'/user/lists';

				_message("添加管理员成功!",$path);

			}else{

				_message("添加管理员失败!");

			}

		}

		include $this->tpl(ROUTE_M,'user.reg');	

		

		

	}

	public function tt(){
		echo "hello";
	}

	public function group_permission(){
		$id=$this->segment(4);
		if($id){
			$info=$this->db->GetOne("SELECT * FROM `@#_admin_usergroup` where mid=$id");
			$data=$this->db->GetList("select * from `@#_ments` where 1");
			$p_arr=explode(',',$info['permission']);
			//var_dump(in_array('2', $p_arr));exit;

		}
		include $this->tpl(ROUTE_M,'user.group_permission');
	}
	//修改管理员权限
	public function up_permission(){
		if(isset($_POST['submit'])){
			$id=intval($_POST['group_id']);
			if($id){
				$data=join(',',$_POST['auth']);
				$res=$this->db->Query("UPDATE `@#_admin_usergroup` SET permission='$data' WHERE mid='$id'");
				if($res){
					_message("修改成功!");
				}else{
					_message("修改失败!");
				}
			}

		}
	}
	public function lists(){	

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_admin` WHERE 1"); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");		

		$AdminList=$this->db->GetPage("SELECT *,`name` as uname FROM `@#_admin` WHERE 1",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		foreach ($AdminList as $key => $value) {
            $info = $this->db->GetOne("SELECT `user_id`,`ip`, `city`,`login_time`,`region` FROM `@#_member_login_log` where `user_id` = '{$value['uid']}' order by `login_time` desc limit 0,1");
            $name=$this->db->GetOne("SELECT `name` FROM `@#_admin_usergroup` where mid=$value[mid]" );
            $AdminList[$key]['ip'] = $info;
			$AdminList[$key]['name']=$name['name'];
		}
        include $this->tpl(ROUTE_M,'user.list');

	}

	public function edit(){
		$grouplist=$this->db->GetList("SELECT * FROM `@#_admin_usergroup`");
		$path=WEB_PATH.'/'.ROUTE_M.'/user/lists';

		$uid=intval($this->segment(4));

		$info=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `uid`='$uid'");

		if(!is_array($info)){

				_message("没有这个用户!",$path);

		}

		if(isset($_POST['submit-1'])){			
            $username = safe_replace($_POST['username']);
            $isusername=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `username`='$username' and `uid` !='$uid'");
            if($isusername){
                $msg ='【'.$username.'】该昵称不可用';
                _message($msg);
            }

			$password1=safe_replace($_POST['password']);
			$password2=safe_replace($_POST['pwdconfirm']);
			$secure_password1=safe_replace($_POST['secure_password']);
			$secure_password2=safe_replace($_POST['secure_pwdconfirm']);
			$name = safe_replace($_POST['name']);
            $mid=safe_replace($_POST['mid']);
			$data['username'] = $username;
			$data['name'] = $name;
			$data['mid'] = $mid;
			if(!empty($password1)){
                if(empty($password2) || ($password1!=$password2)){
                    _message("2次密码不一致!");
                }
				$data['userpass'] = md5($password2);
			}
			if(!empty($secure_password2)){
                if(empty($secure_password1) || ($secure_password1!=$secure_password2)){
                    _message("2次安全密码不一致!");
                }
				$data['secure_password'] = md5($secure_password2);
			}
			$sql = Assemblydata($data,"admin","up",[['uid',$uid]]);
            // $ok = $this->db->Query("UPDATE `@#_admin` SET  `username`='$username',`mid`='$mid',`name`='$name' WHERE (`uid`='$uid')");
            $ok = $this->db->Query($sql);
            if($ok){
				$path=WEB_PATH.ROUTE_M.'/user/lists';
				_message("修改成功!",$path);
            }else{
                _message("修改失败!");
            }
		}else{		
			include $this->tpl(ROUTE_M,'user.edit');
		}

	}

	

	public function del(){

		

		if(isset($_POST['ajax'])){		

			$uid=intval($this->segment(4));

			if($uid==1){

				echo 0;

				return;

			}

			$path=WEB_PATH.'/'.ROUTE_M.'/user/lists';

			$this->db->Query("DELETE FROM `@#_admin` WHERE (`uid`='$uid')");

			if($this->db->affected_rows())			

				echo $path;

			else

				echo 'no';

			exit;

		}

	}

	public function login(){

		if(isset($_POST['ajax'])){			

			$location=WEB_PATH.'/'.ROUTE_M.'/index';

			$message=array("error"=>false,'text'=>$location);

			$username=safe_replace($_POST['username']);

			$password=safe_replace($_POST['password']);

			$code=strtolower($_POST['code']);			

			if(empty($username)){$message['error']=true;$message['text']="请输入用户名!";echo json_encode($message);exit;}

			if(empty($password)){$message['error']=true;$message['text']="请输入密码!";echo json_encode($message);exit;}			

			

			if(_cfg("web_verify")){
			    $type = $this->db->GetOne("SELECT `value` FROM `@#_config` where `name`  = 'web_verify_mode'")['value'];

                if($type != 5){
                    if(empty($code)){$message['error']=true;$message['text']="请输入验证码!";echo json_encode($message);exit;}
                }else{
                    require_once  dirname(dirname(__FILE__)).'/mobile/lib/class.geetestlib.php';

                    $info=System::load_sys_config('identifying');
                    $GtSdk = new GeetestLib($info['CAPTCHA_ID'], $info['PRIVATE_KEY']);
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $data = array(
                        "captcha_id" => $_SESSION['captcha_id'], # 网站用户id
                        "client_type" => "h5", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
                        "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
                    );
                    $bv=safe_replace($_POST['bv']);
                    $cv=safe_replace($_POST['cv']);
                    $fv=safe_replace($_POST['fv']);
                    $result = $GtSdk->success_validate($bv, $cv, $fv, $data);
                    if (!$result) {
                        $user['error']=true; //未验证
                        $user['text'] = '未验证';
                        echo json_encode($user);die;
                    }
                }
			    switch ($type){
                    case 1:
                        $this->characterSign($code);
                        break;
                    case 2:
                        $this->phoneSign($code);
                        break;
                    case 3:
                        $this->googleSign($code);
                        break;
                    case 4:
                        $this->algorithmSign($code);
                        break;
                }

			}

			

			$info=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `username` = '$username' LIMIT 1");		

			if(!$info){$message['error']=true;$message['text']="登录失败,请检查用户名或密码!";echo json_encode($message);exit;}

			if($info['userpass']!=md5($password)){
			    $message['error']=true;
			    $message['text']="登录失败!";
			    echo json_encode($message);
			    exit;
			}

			

			if(!$message['error']){				

				_setcookie("AID",_encrypt($info['uid'],'ENCODE'));

				_setcookie("ASHELL",_encrypt(md5($info['username'].$info['userpass'])));

				$this->AdminInfo=$info;

				$time=time();$ip=_get_ip();

				$this->db->Query("UPDATE `@#_admin` SET `logintime`='$time' WHERE (`uid`='$info[uid]')");

				$this->db->Query("UPDATE `@#_admin` SET `loginip`='$ip' WHERE (`uid`='$info[uid]')");
				$message['msg'] = "登录成功";
				$message['codes'] = 1;
				// print_r($message);
			}
			$this->login_log($info['uid']);
        
			echo json_encode($message);

			exit;			

		}else{
		    $bgCode = $this->db->GetList("SELECT `codes`,`is_enable`,`is_bg`,`is_polling` FROM `@#_login_bg` WHERE `is_enable`=1");
		    $type = $this->db->GetOne("SELECT `value` FROM `@#_config` where `name` = 'web_verify_mode'")['value'];
		    $phone = $this->db->GetOne("SELECT `value` from `@#_config` where `name` = 'code_phone'")['value'];
//		    var_dump($type);exit;
		    foreach ($bgCode as $bgv){
                if ($bgv['is_bg'] == 1 || $bgv['is_bg'] == 3 )$bg = $bgv['codes'];
		        if ($bgv['is_bg'] == 2) $bg_f63 = $bgv['codes'];
            }
            
            // 获取后台语言列表
            $languageBackend = $this->getLanguageList(1,3);
            // 处理国旗图片路径
            foreach($languageBackend as &$v){
                $hasValidFlag = false;
                // 如果启用了国旗且国旗图片不为空
                if(isset($v['flag_status']) && $v['flag_status'] == 1 && !empty($v['national_flag'])){
                    $flag = explode('/',$v['national_flag']);
                    if(count($flag)<=1){
                        // 相对路径，添加插件路径前缀
                        $v['national_flag'] = G_PLUGIN_PATH."/imgs/flag/".$v['national_flag'];
                        $hasValidFlag = true;
                    }elseif(count($flag)>1){
                        // 绝对路径，添加上传路径前缀
                        $v['national_flag'] = G_UPLOAD_PATH."/".$v['national_flag'];
                        $hasValidFlag = true;
                    }
                }
                // 如果没有有效的国旗图片，根据语言代码生成SVG国旗图标
                if(!$hasValidFlag || empty($v['national_flag'])){
                    $v['national_flag'] = $this->getFlagSvgByLanguage($v['lg_value'] ?? 'Chinese');
                }
            }

			include $this->tpl(ROUTE_M,'user.login');

		}

	}
	/*字符验证方式*/
    public function characterSign($code){
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        $expected = $_SESSION['admin_character_code'] ?? '';
        unset($_SESSION['admin_character_code']);
        if(!$expected || !hash_equals($expected, md5($code))){$message['error']=true;$message['text']="验证码输入错误";echo json_encode($message);exit;}
    }
    /*手机号验证*/
    public function phoneSign($code){
        $phone = $this->db->GetOne("SELECT `value` from `@#_config` where `name` = 'code_phone'")['value'];
        $sign = $this->db->GetOne("select `mobile`,`time`,`verify` from `@#_mobile_verify` where `mobile` = $phone order by id desc LIMIT 0,1")['verify'];
//        var_dump($code);
//        var_dump($sign);exit;
        if($code != $sign){
            $message['error']=true;
            $message['text']="验证码输入错误";
            echo json_encode($message);exit;
        }else{
            $this->db->Query("delete FROM `@#_mobile_verify` where `mobile` = $phone");
        }
    }
    /*谷歌登录验证*/
    public function googleSign($code){
        $ga = new PHPGangsta_GoogleAuthenticator();
        $data = $this->db->GetList("SELECT * FROM `@#_google_verify` LIMIT 1");
        $secret = $data[0]['keys'];
        $checkResult = $ga->verifyCode($secret, $code, 2);
        if(!$checkResult){
            $message['error']=true;
            $message['text']="验证码输入错误";
            echo json_encode($message);exit;
        }
    }
    /*算法登录验证*/
    public function algorithmSign($code){
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        $expected = $_SESSION['admin_arithmetic_code'] ?? null;
        unset($_SESSION['admin_arithmetic_code']);
        if($expected === null || !hash_equals($expected, (string) $code)){
            $message['error']=true;
            $message['text']="验证码输入错误";
            echo json_encode($message);exit;
        }
    }
    public function dig445(){
        $data  = $this->db->GetOne("SELECT `cfg_value` FROM `@#_wxch_cfg` WHERE  `cfg_name` = 'auto'  LIMIT 1");
        $data = unserialize($data['cfg_value']);
        $t1= $data['maxtime'];
        $t2= $data['mintime'];
        include templates("index","shaidan123");
    }
    /*后台登录发送验证码*/
    public function send_login(){
        $mobile = safe_replace($_POST['phone']);
        if(!$mobile){
            $user['status']=2; //未验证
            $user['message'] ='请检查后台配置电话是否为空';
            echo json_encode($user);die;
        }else{
            $phone = $this->db->GetOne("SELECT `value` from `@#_config` where `name` = 'code_phone'")['value'];
//            var_dump($phone);exit;
            if($mobile != $phone){
                $user['status']=2; //未验证
                $user['message'] ='手机号和后台设置不匹配，请检查';
                echo json_encode($user);die;
            }
        }

        $this->mobile = System::load_sys_config('mobile');
        $key=$this->mobile['cfg_mobile_on'];
//        $mobile=safe_replace($_REQUEST['mobilePhone']);
        $info=array();
        // if(preg_match("/^1[3456789]\d{9}$/", $mobile)){
            $code=mt_rand('100000','999999');
            $ok= _send_SMS($mobile,11,$code);
            if($ok['0']== '1'){
                $time=time();
                $this->db->Query("INSERT into `@#_mobile_verify`(`mobile`,`verify`,`time`)VALUES('$mobile','$code','$time')");
                $info['status']=0;
            }else{
                $info['status']='2';
                $info['message']='发送失败';
            }

            echo json_encode($info);die;
        // }else{

        //     $user['status']=2; //未验证
        //     $user['message'] ='系统错误';
        //     echo json_encode($user);die;
        // }
    }

	public function out(){
		// 清除登录 cookie
		_setcookie("AID",'');
		_setcookie("ASHELL",'');
		
		// 如果是 AJAX 请求，返回 JSON
		if(isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")){
			echo json_encode([
				"code" => 200,
				"msg" => "退出成功",
				"string" => "退出成功",
				"defurl" => G_MODULE_PATH.'/user/login',
				"iserror" => "success"
			]);
			exit;
		}
		
		// 非 AJAX 请求，使用原来的弹窗方式
		_message("退出成功",G_MODULE_PATH.'/user/login');
	}

    /**
     * 登陆日志
     * @param $uid
     * @return bool
     */
    private function login_log($uid){
        if(empty($uid)) return false;
         $uid = safe_replace($uid);
        $userinfo  = $this->db->GetOne("SELECT uid from `@#_admin` WHERE uid = '{$uid}'");
        if(!$userinfo) return false;
        $delnum = 15;
        $limit = $delnum+1;
        $count = $this->db->GetList("SELECT id FROM `@#_member_login_log` WHERE user_id = '{$uid}' and type = '2' ORDER BY `id` DESC LIMIT $limit");
        if(count($count) >$delnum){
            $key = $delnum-1;
            $delid = $count[$key]['id'];
            $this->db->Query("DELETE FROM `@#_member_login_log` WHERE `id`< {$delid} and `user_id` = '{$uid}'");
        }
        $ip = _get_ip();
        $url = file_get_contents("http://ip.taobao.com/outGetIpInfo?ip=$ip&accessKey=alibaba-inc");
        $ipdata = json_decode($url,true);
//        file_put_contents('login.txt',var_export($ipdata,TRUE));
        if($ipdata && $ipdata['code'] ==0){
            $data = $ipdata['data'];
            $i['ip']        = $ip?:'未知IP';
            $i['country']   = $data['country']?:'';
            $i['region']    = $data['region']?:'';
            $i['city']      = $data['city']?:'';
            $i['isp']       = $data['isp']?:'';
            $i['login_time']=time();
            $i['user_id']   =$uid;
        }else{
            $i['ip']        = $ip?:'未知IP';
            $i['country']   = '';
            $i['region']    = '';
            $i['city']      = '';
            $i['isp']       = '';
            $i['login_time']=time();
            $i['user_id']   =$uid;
        }
        $sql = "INSERT INTO `@#_member_login_log`(`user_id`, `ip`, `login_time`, `country`, `region`, `city`, `isp`,`type`) VALUES ({$i['user_id']},'{$i['ip']}','{$i['login_time']}','{$i['country']}','{$i['region']}','{$i['city']}','{$i['isp']}',2)";
        $res = $this->db->Query($sql);
        if($res){
            return true;
        }
        return false;
    }

	public function gyuns45()
    { 
        $data = $this->db->GetOne("SELECT `cfg_value` FROM `@#_wxch_cfg` WHERE  `cfg_name` = 'auto'  LIMIT 1");
        $data = unserialize($data['cfg_value']);
        $t1 = $data['maxtime'];
        $t2 = $data['mintime'];
        include templates("index", "shaidan123");
    }

	/**
	 * 根据语言代码获取国旗SVG图标
	 */
	private function getFlagSvgByLanguage($lgValue) {
		$flagSvgs = array(
			// 中国国旗 - 红色背景，黄色五角星
			'Chinese' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480"><rect width="640" height="480" fill="#de2910"/><path d="M213.3 0h213.4v480H213.3z" fill="#ffde00"/><circle cx="320" cy="240" r="80" fill="#de2910"/><circle cx="320" cy="240" r="60" fill="#ffde00"/><path d="M320 180l20 60-50-40h60l-50 40z" fill="#de2910"/></svg>'),
			// 美国国旗 - 红白条纹，左上角蓝色区域和白色星星
			'English' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7410 3900"><rect width="7410" height="3900" fill="#b22234"/><path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/><rect width="2964" height="2100" fill="#3c3b6e"/></svg>'),
			// 越南国旗
			'Vietnamese' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="900" height="600" fill="#da251d"/><path d="M450 200l50 150-130-95h160l-130 95z" fill="#ff0"/></svg>'),
			// 缅甸国旗
			'Myanmar' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="900" height="200" fill="#ffcd00"/><rect y="200" width="900" height="200" fill="#34b233"/><rect y="400" width="900" height="200" fill="#ea2839"/><circle cx="450" cy="300" r="100" fill="#fff"/></svg>'),
			// 印度国旗（印地语）
			'Hindi' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="900" height="200" fill="#ff9933"/><rect y="200" width="900" height="200" fill="#fff"/><rect y="400" width="900" height="200" fill="#138808"/><circle cx="450" cy="300" r="60" fill="#000080"/><circle cx="450" cy="300" r="50" fill="#fff"/></svg>'),
			// 葡萄牙国旗
			'Portugal' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>'),
			'Portuguese' => "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>'),
		);
		
		// 如果找到对应的国旗，返回SVG
		if(isset($flagSvgs[$lgValue])) {
			return $flagSvgs[$lgValue];
		}
		
		// 默认返回一个通用的国旗图标
		return "data:image/svg+xml," . urlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480"><rect width="640" height="480" fill="#0066cc"/><circle cx="320" cy="240" r="100" fill="#fff"/></svg>');
	}

	/**
	 * 切换后台语言
	 */
	public function changeLanguage() {
		$language = $_POST['language'] ?? '';
		if (empty($language)) {
			$this->jsonData->code = 400;
			$this->jsonData->msg = '语言参数不能为空';
			echo json_encode($this->jsonData);
			exit;
		}
		
		// 验证语言是否存在
		$languageList = $this->getLanguageList(1, 3);
		$languageExists = false;
		foreach ($languageList as $lang) {
			if ($lang['lg_value'] == $language) {
				$languageExists = true;
				break;
			}
		}
		
		if (!$languageExists) {
			$this->jsonData->code = 400;
			$this->jsonData->msg = '语言不存在';
			echo json_encode($this->jsonData);
			exit;
		}
		
		// 设置cookie
		_setcookie('admin_language', $language, 31536000, '/', '', false, true);
		$_COOKIE['admin_language'] = $language;
		
		$this->jsonData->code = 200;
		$this->jsonData->msg = '语言切换成功';
		echo json_encode($this->jsonData);
		exit;
	}

}

?>