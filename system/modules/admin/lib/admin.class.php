<?php
define('G_IN_ADMIN',true);
define('G_ADMIN_PATH',WEB_PATH.'/'.G_ADMIN_DIR);
System::load_app_class('notice',"common","no");
System::load_app_class('returnData','app','no');
// PHP 8.2 兼容：允许动态属性
#[AllowDynamicProperties]
class admin extends SystemAction {	
	protected $AdminInfo;
	protected $ment;
	private $db;
	public $currency;
	public $jsonData;
	public $language_backend = "Chinese";
	public $lgs = [];
	public $rqs;
	public $redisClass;
    public $where_language = "";
	public function __construct(){	
		$this->_check();
		$this->db=System::load_app_model('admin_model',G_ADMIN_DIR);
		$this->CheckAdmin();
		$lg = $this->AdminInfo['language'];
		$this->language_backend = $lg?:System::load_sys_config("system","web_language_backend");
		$this->where_language = " and (language = '{$this->language_backend}' or language = ''  or language = null) ";
		$rqs= system::load_sys_config("lgs_backend",ROUTE_C."/".ROUTE_A);
		$this->rqs = $rqs;
		if(!$this->redisClass){
			System::load_sys_fun('redisData');
			$this->redisClass = new redisData();
		}
// 		$arr = ["quicknav/getnav","user/login","quicknav/jsNav"];
// 	    if(!in_array(ROUTE_C."/".ROUTE_A,$arr) && empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
// 		    if(!in_array(ROUTE_C."/".ROUTE_A,$arr)){
//         		echo ROUTE_C."/".ROUTE_A."<br>";
//         		echo $rqs;
// 		    }
// 		} 
		/* $lgs = $this->redisClass->redisget("lgs_backend_".$rqs);
		if($lgs['data']){
			$this->lgs = json_decode($lgs['data'],true);
		}else{
			$this->redisClass->redisSetex("lgs_backend_".$rqs, 600,$this->lgs);
			$this->lgs = json_encode($this->getLanguages($rqs));
		} */
		$this->lgs = $this->getLanguages($rqs);
		$lan = $this->redisClass->redisget("language_backend");
		if(!$lan['data'] || ($lan['data'] && $lan['data'] != $this->language_backend)){
			$this->redisClass->redisSet("language_backend", $this->language_backend);
		}

		$this->checkPermission();
		$this->getLanguageCurrency();
		$this->_setTimeZone();
		$this->jsonData = new returnData();
		$this->jsonData->type = 2;
		$this->jsonData->language_backend = $this->language_backend;
	}

	private function _check(){
		/**
		 * 检测运行环境
		 * 1. PHP版本
		 * 2. PHP扩展
		 * 3. redis连接
		 * 
		 */
		$phpVersion = phpversion();
		if(version_compare($phpVersion, '8.0', '<') || version_compare($phpVersion, '8.4', '>')){
			exit('PHP版本不正确,请使用8.0-8.4之间的版本，当前版本：' . $phpVersion);
		}
		$ext = ["PDO","mbstring","redis"];
		$str = "";
		foreach($ext as $v){
			if(!extension_loaded($v)){
				$str .= "PHP扩展".$v."未安装\n";
			}
		}
		if($str){
			exit($str);
		}

		System::load_sys_fun('redisData');
		$redis = new redisData;
		if($redis->error){
			echo "redis连接失败，失败原因：<br>1.redis未开启<br>2.配置文件中redis配置信息错误";
			unset($redis);
			exit();
		}
	}

	public function _setTimeZone(){
		$time_zone = $this->getConfig("time_zone");
		if($time_zone){
			// $zones = ["America/New_York","Asia/Shanghai","Asia/Pontianak","Asia/Bangkok"];
			date_default_timezone_set($time_zone);
		}
	}

	//判断用户是否登陆
	final protected function CheckAdmin(){
		$array = ["checkcode/image","checkcode/suanshuimg"];
		$uri = ROUTE_C."/".ROUTE_A;
		$uri = explode("&",$uri)[0];
		if(in_array($uri,$array)){
			return true;
		}

		if(!(ROUTE_C === 'user' && in_array(ROUTE_A, array('login', 'send_login'), true))){
			$check = $this->CheckAdminInfo();
			if(!$check)_messageerror("请先登录",WEB_PATH.'/'.G_ADMIN_DIR.'/user/login');
			
		}
	}

	//判断用户权限
	 protected function checkPermission($aurl = ''){
		$mid = $this->AdminInfo['mid'];
		$info=$this->db->GetOne("SELECT * FROM `@#_admin_usergroup` where mid=$mid");
		$per = $info['permission'];
		if($per == "ALL"){
			return true;
		}
		$ments = $this->db->GetList("select id,d from `@#_ments` where id in ($per)");
		$url = "/".ROUTE_M."/".ROUTE_C."/".ROUTE_A;
		$url1 = $aurl?:str_replace("/".G_ADMIN_DIR,"",$url);
		$check = false;
		foreach($ments?:[] as $vl){
			if($url1 == $vl['d']){
				$check =true;
			}	
		}
		if(!$check){
			$sql = Assemblydata('','ments','sel',[['d',$url1]]);
			$res = $this->db->GetList($sql);
			if($res){
				if($aurl){
					return false;
				}
				echo '<script>';
				echo 'alert("没有权限");';
				echo 'window.history.go(-1);';
				echo '</script>';
				exit;
			}
		}
		return true;
	}

	/**
	 * 后台是否允许按 uid 查看会员数据（仅超级管理员 permission=ALL 可查看任意会员）
	 * @param int $uid 会员 uid
	 * @return bool
	 */
	protected function admin_can_view_member_uid($uid){
		$uid = intval($uid);
		if($uid <= 0) return false;
		$mid = isset($this->AdminInfo['mid']) ? $this->AdminInfo['mid'] : 0;
		if(!$mid) return false;
		$adm_db = System::load_app_model('admin_model', G_ADMIN_DIR);
		$info = $adm_db->GetOne("SELECT permission FROM `@#_admin_usergroup` WHERE mid = :mid", array('mid' => $mid));
		if(!$info) return false;
		return ($info['permission'] === 'ALL');
	}
	
	final protected function tpl($module = 'admin', $template = 'index'){	
		$this->mentLgs();
		$file =  G_SYSTEM.'modules/'.$module.'/tpl/'.$template.'.tpl.php';
		if(file_exists($file)){
            return $file;
		}
		elseif(defined("G_IN_ADMIN")){
			_error('template message',"没有找到<font color='red'>".$module."</font>模块下的<font color='red'>".$template.".tpl.php</font>文件!");
		}else{
			_error('template message','The "'.$module.'.'.$template .'" template file does not exist');
		}
	}
	//判断用户
	final protected function CheckAdminInfo($uid=null,$ashell=null){
		$this->db=System::load_app_model('admin_model',G_ADMIN_DIR);
		if($uid && $ashell){
			$CheckId = _encrypt($uid,'DECODE');
			$CheckAshell =  _encrypt($ashell,'DECODE');	
		}else{			
			$CheckId=_encrypt(_getcookie("AID"),'DECODE');
			$CheckAshell=_encrypt(_getcookie("ASHELL"),'DECODE');
		}
		if(!$CheckId || !$CheckAshell){			
			return false;
		}		
		$CheckId = safe_replace($CheckId);
		$info=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `uid` = '$CheckId'");		
		if(!$info)return false;
		$infoshell=md5($info['username'].$info['userpass']);
		if($infoshell!=$CheckAshell)return false;
		$this->AdminInfo=$info;
		return true;
	}
		
	final protected function headerment($ments=null){
		$html='';
		$html_l='';
		$URL=trim(get_web_url(),'/');
		$uid=$this->AdminInfo['mid'];
		$this->db=System::load_app_model('admin_model',G_ADMIN_DIR);
		$p_data=$this->db->GetOne("SELECT * FROM `@#_admin_usergroup` where mid='$uid'");
		$permission=$p_data['permission'];

		
		//var_dump($urlarr);exit;
		if(is_array($ments)){
			$ment=$ments;
		}else{
			if(!isset($this->ment))return false;
			$ment=$this->ment;
		}	

		if($permission != 'ALL'){
			$urlarr=$this->db->GetList("SELECT d FROM `@#_ments` where id in($permission) order by `sort` desc");
			foreach($urlarr as $k=>$v){
				$newurr[]=$v['d'];
			}

			foreach ($ment as $key => $value) {
				//file_put_contents('admin.txt','value--'.var_export($urlarr,1)."\n",FILE_APPEND);
				if(!in_array(ltrim($value[2],'admin'),$newurr)){
					unset($ment[$key]);
				}
			}
		}

		foreach($ment as $k=>$v){	
			if(WEB_PATH.'/'.$v[2]==$URL || strpos($URL,$v[2])!==false){
				$html_l='<h3 class="nav_icon">'.$v[1].'</h3><span class="span_fenge lr10"></span>';
			}
			if(!isset($v[3])){	
				$html.='<a href="'.WEB_PATH.'/'.$v[2].'">'.$v[1].'</a>';	
				$html.='<span class="span_fenge lr5"></span>';
			}			
		}
		
		return $html_l.$html;
	}
	
	/**
	 * 获取当前页面的标题（用于<title>标签）
	 * @param string $defaultTitle 默认标题
	 * @return string
	 */
	final protected function getPageTitle($defaultTitle = ''){
		$pageTitle = $defaultTitle;
		if(isset($this->ment) && is_array($this->ment)){
			$URL = trim(get_web_url(),'/');
			foreach($this->ment as $k=>$v){
				if(WEB_PATH.'/'.$v[2]==$URL || strpos($URL,$v[2])!==false){
					$pageTitle = $v[1];
					break;
				}
			}
			// 如果没找到匹配的，使用第一个菜单项
			if(empty($pageTitle) && !empty($this->ment[0])){
				$pageTitle = $this->ment[0][1];
			}
		}
		return $pageTitle;
	}
	
	public function __destruct(){
		$this->admin_log();
	}
	
	private function admin_log(){
		$db = System::load_sys_class("model");
		if(empty($db->sql_log)){
			return;
		}
		$date = date("Y-m-d");		
		$path = G_CACHES.'caches_log'.'/admin.log.'.$date.'.php';
		if(!file_exists($path)){
			$directory = dirname($path);
			mkdir($directory,0755, true);
			$html = "<?php exit; ?> \n\n\n\n";
			file_put_contents($path,$html);
		}		
// 		if(!is_writable($path)) exit("admin.log.{$date}.php 没有写入权限");	
		$date = date("Y-m-d H:i:s");		
		$html = "$date"."------>".var_export($db->sql_log,true);
		$html .=" \n\n\n\n";
		file_put_contents($path,$html,FILE_APPEND);
	}
	/*全局变量弹窗*/
	public function notification(){

		$isurl = _getcookie('download_url');
		$url =WEB_PATH.'/'.G_ADMIN_DIR.'/'.ROUTE_C.'/'.ROUTE_A;
		if (empty($isurl)){
			_setcookie('download_url',G_HTTP_REFERER);
		}
		$back =_getcookie('download_url');
		$code = $_POST['password'];
		if(empty($code)){
			include $this->tpl(ROUTE_M,'admin.message_lhw');
			exit;
		}
		require_once dirname(dirname(__FILE__))."/goole/PHPGangsta/GoogleAuthenticator.php";
		$ga = new PHPGangsta_GoogleAuthenticator();
		$data = $this->db->GetList("SELECT * FROM `@#_google_verify` LIMIT 1");
		$secret = $data[0]['keys'];
		$checkResult = $ga->verifyCode($secret, $code, 2);
		if(!$checkResult){
			include $this->tpl(ROUTE_M,'admin.message_lhw');
			_messageerror('验证码错误');
		}
	}

	/**
	 * 获取语言
	 * @param mixed $status 状态
	 * @param mixed $type 1app端 2pc端 3后台 默认app端
	 * 
	 */
	public function getLanguageList($status = -1,$type = 1){
		$where = '';
		if($status != -1){
			$where = " and status = $status";
		}
		if($type==3){
			$languageList = $this->db->GetList('SELECT * FROM `@#_language_backend` where 1 '.$where);
		}else{
			$languageList = $this->db->GetList("SELECT * FROM `@#_language` where 1 and `type` = $type ".$where);

		}
		return $languageList;
	}
	/**
     * 返回对应语言
     * @param mixed $val
     * @param mixed $key
     * @param mixed $type
     * @return string
     */
    public function getLgMsg($val="",$key="home",$type=1){
        if(!$val){
            return $val;
        }
        $lgs = $this->getLanguages($key,$type);
        if(isset($lgs[$val])){
			// $this->lgs = $lgs[$val];
            return $lgs[$val];
        }
		return $val;
    }

	 /**
     * 用户号语言
     * @param $rqs  语言数组选择
     * @return 
     */
    public function getLanguages($rqs = 'home',$type=1){
		if($rqs ===null){
			return [];
		}
		if($type ==1){
			$table = "language_value_backend";
		}else{
			$table = "language_value";
		}
        if($rqs){
            $rq = $this->db->GetOne("select * from `@#_$table` where `key`='$rqs' and pid = 0");
            if(!$rq){return [];}
            $lg = $this->db->GetList("select * from `@#_$table` where (`key`='$rqs' and pid = 0) or `pid`=".$rq['id']);
        }else{
            $lg = $this->db->GetList("select * from `@#_$table` where 1");
        }
        if($lg) {
            $lg = $this->buildArray($lg);
            $lg = empty($rqs) ? $lg :  $lg[$rqs];
        }
		// $this->lgs = $lg;
        return $lg;
    }

    private function buildArray($array, $parentId = 0){
        $tree = [];
        foreach($array as $element){
            if($element['pid'] == $parentId){
                $children = $this->buildArray($array, $element['id']);
                if($children){
                    $tree[$element['key']] = $children;
                }else{
					$v = mb_unserialize($element['value']);
					$tree[$element['key']] = $v[$this->language_backend]['value']??"";
                    // $tree[$element['key']] = mb_unserialize($element['value'])[$this->language_backend]['value'];
                }
            }
        }
        return $tree;
    }

	public function lan($key='',$default='',$mode=1){
		if(isset($this->lgs[$key]) && is_string($this->lgs[$key]) && $this->lgs[$key] != "error"){
			if($mode!=1){return $this->lgs[$key];}
			echo $this->lgs[$key]??$default;
		}else{
			$lan = $this->db->GetOne("select `value` from `@#_language_value_backend` where `key` = '{$key}'");
			if(!$lan){
				$lan = $this->db->GetOne("select `value` from `@#_language_value` where `value` like '%\"$key\"%'");
			}
			if($lan){
				$value = mb_unserialize($lan['value']);
				$msg = $value[$this->language_backend]['value'];
				if($mode!=1){return _unhtmtocode($msg);}
				echo _unhtmtocode($msg)??$default;
			}else{
				if($mode!=1){return $default?$default:$key;}
				echo $default?$default:$key;
			}
		}
	}

	public function mentLgs(){
		foreach($this->ment?:[] as $key=>$vl){
            $res = $this->db->GetOne("SELECT `value` FROM `@#_language_value_backend` WHERE `value` like '%\"{$vl[1]}\"%'");
			if($res){
                $value = mb_unserialize($res['value']);
                $this->ment[$key][1] = $value[$this->language_backend]['value']?:$vl[1];
            }
        }
	}

	private function getLanguageCurrency(){
		$currency = [];
		$res = $this->db->GetOne("select * from `@#_config` where `name` = 'web_language_currency'");
		if($res){		    
			$language = $res['value'];
			/* $currency = $this->db->GetOne("select * from `@#_currency` where `language` = '$language'");
			if($currency){
				$currency['symbol'] = $currency['symbol_left']?:$currency['symbol_right'];
			} */
			$currency = $this->db->GetOne("select * from `@#_language` where `lg_value` = '$language'");
			if($currency){
				if($language == "Vietnamese" or $language == "Myanmar"){
					$currency['symbol_left'] = '';
					$currency['symbol_right'] = $currency['currency_unit'];
				}else{
					$currency['symbol_left'] = $currency['currency_unit'];
					$currency['symbol_right'] = '';
				}
				$currency['symbol'] = $currency['currency_unit'];
			}
		}
		$this->currency = $currency?:["title"=>'','unit'=>'',"symbol_left"=>'',"symbol_right"=>'',"symbol"=>''];
	}

	/**
	 * 获取货币列表
	 * @return 
	 */
	public function getCurrencyList(){
	    $list = $this->getLanguageList();
		$currencyList = [];
		foreach($list as $key=>$vl){
			if($vl['lg_value'] == "Vietnamese"){
				$cry['symbol_left'] = '';
				$cry['symbol_right'] = $vl['currency_unit'];
			}else{
				$cry['symbol_left'] = $vl['currency_unit'];
				$cry['symbol_right'] = '';
			}
		    $currencyList[$vl['lg_value']] = $cry;
		}
		// $currencyList = array_column($list,'currency_unit',"lg_value");

		return $currencyList;
	}
	
    //验证审核密码
    public function verifyExamine($password){
        if(md5($password) == $this->AdminInfo['secure_password']){
			return true;
		}else{
			return false;
		}
    }

	/**
	 * 
	 * 导出excel
	 * @param array $header  [表头1,表头2,表头3,...]
	 * @param array $body 	[["字段名","类型","内容"],...]
	 * @param string $title	[文件名]
	 * @param string $sql	[查询sql]
	 * @return void
	 */
	public function export($header,$body,$title="",$sql=''){
			$path =  dirname(__FILE__);
			$path = str_replace("lib","",$path);
			include($path.'PHPExcel.php');
			$objPHPExcel=new PHPExcel();
			$H = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
			//设置excel列名
			foreach($header as $k=>$vl){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($H[$k].'1',$vl);
			}
			$data=$this->db->GetList("$sql");
			foreach($data as $key => $vl){
				$key+= 2;   //从第二行开始填充
				foreach($body as $k=>$v){
					if(is_array($v)){
						switch($v[1]){
							case "date":
								$format = "Y-m-d H:i:s";
								if(isset($v[2])){
									$format = $v[2];
								}
								if($vl[$v[0]]){
									$value = date($format,$vl[$v[0]]);
								}
								break;
							case "status":
								$value = $v[2][$vl[$v[0]]];
								break;
							default:
							$value = $vl[$v[0]];
						}
					}else{
						$value = $vl[$v];
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($H[$k].$key,$value);
				}
			}
		//设置默认字体
		$objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
	
		//设置列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(22);
	
		//设置居中
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet() -> setTitle($title);
		$objPHPExcel-> setActiveSheetIndex(0);
		//excel保存在根目录下  如要导出文件，以下改为注释代码
		//$objPHPExcel->getActiveSheet() -> setTitle('SetExcelName');
		//$objPHPExcel-> setActiveSheetIndex(0);
		//$objWriter = $iofactory -> createWriter($objPHPExcel, 'Excel2007');
		//$objWriter -> save('SetExcelName.xlsx');	
		//导出代码
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$filename = $title.'-'.date('YmdHi').'.xlsx';
		ob_end_clean();//清除缓存以免乱码出现
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$objWriter -> save('php://output');		
		}

	public function setCaches($key,$value,$desc=null){
		$res = $this->db->GetOne("select * from `@#_caches` where `key` = '$key'");
		if($res){
			$res = $this->db->Query("UPDATE `@#_caches` SET `value`='$value',`desc`='$desc' WHERE `key`='$key'");
		}else{
			$res = $this->db->Query("Insert into `@#_caches` (`key`,`value`,`desc`)value('$key','$value','$desc')");
		}
		return $res;
	}
	public function setConfig($name,$value,$desc=null){
		$res = $this->db->GetOne("select * from `@#_config` where `name` = '$name'");
        $time = time();
		if($res){
			$res = $this->db->Query("UPDATE `@#_config` SET `value`='$value',`zhushi`='$desc',`update_time`= $time WHERE `name`='$name'");
		}else{
			$res = $this->db->Query("Insert into `@#_config` (`name`,`value`,`zhushi`)value('$name','$value','$desc')");
		}
		return $res;
	}
	public function getConfig($name){
		if(!is_array($name)){
			$res = $this->db->GetOne("select * from `@#_config` where `name` = '$name'");
			if(!$res) return null;
			return $res['value']?:null;
		}else{
			$names = implode("','",$name);
			$res = $this->db->GetList("select * from `@#_config` where `name` in ('$names')");
			$arr = array_column($res,"value","name");
			$newarr = [];
			foreach($name as $vl){
				$newarr[$vl] = null;
			}
			return array_merge($newarr,$arr);
		}
	}
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

}
?>