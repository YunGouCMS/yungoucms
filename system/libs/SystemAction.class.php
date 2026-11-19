<?php 

/**
 *		action.class.php  基础操作动作类
 *
 */
 class SystemAction {
	static  private $route_url;
     static $requestConfigArray;
     static $requestConfigString;
	final protected function DB($model='model',$module='sys'){
		static $classes = array();
		if(isset($classes[$model.$module]))return $classes[$model.$module];
		if($module=='sys'){
			return $classes[$model.$module]=&System::load_sys_class($model);
		}else{		
			return $classes[$model.$module]=&System::load_app_model($model,$module);
		}
		
	}
	
	/*
	* 调用模板函数
	* $module   模板目录
	* $template 模板文件名,
	* $StyleTheme    模板方案目录,为空为默认目录
	*/	
	final protected function view($module = '', $template = '',$StyleTheme=''){	
		if(empty($StyleTheme)){$style=G_STYLE.DIRECTORY_SEPARATOR.G_STYLE_HTML;}
		else{
			$templates=System::load_sys_config('templates',$style);
			$style=$templates['dir'].DIRECTORY_SEPARATOR.$templates['html'];
		}
		$FileTpl = G_CACHES.'caches_template'.DIRECTORY_SEPARATOR.dirname($style).DIRECTORY_SEPARATOR.md5($module.'.'.$template).'.tpl.php';	
		$FileHtml = G_TEMPLATES.$style.DIRECTORY_SEPARATOR.$module.'.'.$template.'.html';	
		if(file_exists($FileHtml)){		
			if (file_exists($FileTpl) && @filemtime($FileTpl) >= @filemtime($FileHtml)) {					
				return include  $FileTpl;			
			} else {			
				$template_cache=&System::load_sys_class('template_cache');	
				if(!is_dir(dirname(dirname($FileTpl)))){
					mkdir(dirname(dirname($FileTpl)),0777, true)or die("Not Dir");		
				    chmod(dirname(dirname($FileTpl)),0777);
				}
				if(!is_dir(dirname($FileTpl))){		
					mkdir(dirname($FileTpl), 0777, true)or die("Not Dir");
					chmod(dirname($FileTpl),0777);
				}	
				$PutFileTpl=$template_cache->template_init($FileTpl,$FileHtml,$module,$template);
				if($PutFileTpl)
					return include  $FileTpl;					
				else				
					_error('template message','The "'.$module.'.'.$template .'" template file does not exist');
			}
		}
		_error('template message','The "'.$module.'.'.$template .'" template file does not exist');
		
	}
	
	protected function tpl($module = 'admin', $template = 'index'){	
		$file =  G_SYSTEM.'modules/'.$module.'/tpl/'.$template.'.tpl.php';
		if(file_exists($file))return $file;
		elseif(defined("G_IN_ADMIN")){
			_message("没有找到<font color='red'>".$module."</font>模块下的<font color='red'>".$template.".tpl.php</font>文件!");
		}else{
			_error('template message','The "'.$module.'.'.$template .'" template file does not exist');
		}
	}

 final protected function segment($n=1){
     //这里可以优化
     if(empty(self::$requestConfigArray)){
         // file_put_contents("requset.txt","请求一次引入文件" ."\n",FILE_APPEND);
         self::$requestConfigString = include_once "requertConfig.php";
     }
     if(!isset(self::$route_url[$n])){
         return false;
     }else{
         /* file_put_contents("requset.txt","我输入的内容：" . var_export(self::$route_url[$n],1)."是否拦截" . in_array(self::$route_url[$n],self::$requestConfigArray) ."\n",FILE_APPEND);*/
        /* $strtolowerArray = strtolower(self::$route_url[$n]);
         //  file_put_contents("requset.txt",preg_match(self::$requestConfigString,$strtolowerArray) ."\n",FILE_APPEND);
         if(strpos(self::$route_url[$n],"&p=") !== false || preg_match(self::$requestConfigString,$strtolowerArray) ){
             file_put_contents("requset.txt","屏蔽的内容：" . var_export(self::$route_url[$n],1)."，是否拦截" . in_array(self::$route_url[$n],self::$requestConfigArray) . '，IP地址:'. $this->getip().",请求方法:".ROUTE_C .'/'.ROUTE_A."，操作时间：" . date("Y-m-d H:i:s",time()) ."\n-----------------------------------------------------------------------------------------------------------\n",FILE_APPEND);
             return '';
         }else{
             return self::$route_url[$n];
         }*/
         return self::$route_url[$n];
     }
 }

     private function getip() {

         static $ip = '';
         $ip = $_SERVER['REMOTE_ADDR'];

         if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {

             $ip = $_SERVER['HTTP_CDN_SRC_IP'];

         } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {

             $ip = $_SERVER['HTTP_CLIENT_IP'];

         } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {

             foreach ($matches[0] AS $xip) {

                 if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {

                     $ip = $xip;

                     break;

                 }

             }

         }

         return $ip;

     }
	final protected function segment_array(){
		return self::$route_url;
	}
	final static public function set_route_url($route_urls){	
		self::$route_url=$route_urls;
		
	}
	
 }

?>