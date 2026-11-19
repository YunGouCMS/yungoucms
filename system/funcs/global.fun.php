<?php

/*

*   所有公共函数文件

*/

/*

*	序列化

*/

function _serialize($obj){ 

   return base64_encode(gzcompress(serialize($obj))); 

}

/**
 * 日志打印
 * @param mixed $msg 日志消息
 * @param string $file 日志文件路径
 * @param int $type 日志类型 1=admin 2=home
 */
function save_log($msg, $file = '', $type = 0)
{
    if (empty($file)) {
        $file = "system/logs/" . date('Y-m-d') . '.log';
    }

    if ($type == 1) {
        $config = System::load_sys_config('config');
        $file = $_SERVER['DOCUMENT_ROOT'] . $config['logsPath']['admin'] . $file;
    }

    if ($type == 2) {
        $config = System::load_sys_config('config');
        $file = $_SERVER['DOCUMENT_ROOT'] . $config['logsPath']['home'] . $file;
    }

    // 确保目录存在
    $dir = dirname($file);
    if (!is_dir($dir)) {
        // 递归创建目录，权限为 0755
        if (!mkdir($dir, 0755, true)) {
            // 如果创建目录失败，可以记录错误或使用备用路径
            $errortxt = '【' . date('Y-m-d H:i:s') . '】INFO:' . var_export("无法创建日志目录: " . $dir, true) . PHP_EOL;
            file_put_contents('error.log', $errortxt . PHP_EOL, FILE_APPEND);
        }
    }

    $txt = '【' . date('Y-m-d H:i:s') . '】INFO:' . var_export($msg, true) . PHP_EOL;

    // 尝试写入文件，如果失败则记录错误
    if (file_put_contents($file, $txt . PHP_EOL, FILE_APPEND) === false) {
        $errortxt = '【' . date('Y-m-d H:i:s') . '】INFO:' . var_export("无法写入日志文件: " . $file, true) . PHP_EOL;
        file_put_contents('error.log', $errortxt . PHP_EOL, FILE_APPEND);
    }

}

// 打印函数

function p($arr){

  header('Content-type:text/html;charset=utf-8');

  echo "<pre>";

  print_r($arr);

  echo "</pre>";

}

// 打印断点函数

function halt($arr){

    header('Content-type:text/html;charset=utf-8');

    echo "<pre>";

    print_r($arr);

    echo "</pre>";

    exit;

}

function PSql($sql='',$quit = true){
    echo "<pre>";
    echo str_replace("@#","go",$sql);
    echo "</pre>";
    if($quit){
        exit;
    }
}

/**
 * 获取今天开始到结束的时间戳
 *
 * @return array [start_time,$endtime]
 */
function get_today_time()
{
    $start_time = strtotime(date('Y-m-d'));
    $end_time = $start_time + 86399;

    return [$start_time, $end_time];
}

/**
 * 获取当月开始及结束时间戳
 * @return array
 */
function get_current_month_time()
{
    $start_time = mktime(0,0,0,date('m'),1,date('Y'));
    $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));

    return [$start_time, $end_time];
}
/**
 * 获取本季度开始结束时间戳
 * @return array
 */
function get_current_quarter_time()
{
    $season = ceil(date('n') /3); //获取月份的季度
    $start_time = strtotime(date('Y-m-01',mktime(0,0,0,($season - 1) *3 +1,1,date('Y'))));
    $end_time = strtotime(date('Y-m-t',mktime(0,0,0,$season * 3,1,date('Y'))));

    return [$start_time, $end_time];
}

/**
 * 获取上个季度开始结束时间戳
 * @return array
 */
function get_last_quarter_time()
{
    $season = ceil(date('n') /3); //获取月份的季度
    $start_time = strtotime(date('Y-m-01',mktime(0,0,0,($season - 2) *3 +1,1,date('Y'))));
    $end_time = strtotime(date('Y-m-t',mktime(0,0,0,($season-1) * 3,1,date('Y'))));

    return [$start_time, $end_time];
}

/**
 * 获取今年开始结束时间戳
 * @return array
 */
function get_current_year_time()
{
    $start_time = strtotime(date('Y-01-01'));
    $end_time = strtotime(date('Y-12-31 23:59:59'));

    return [$start_time, $end_time];
}

/**
 * 获取去年开始结束时间戳
 * @return array
 */
function get_last_year_time()
{
    $start_time = strtotime(date('Y-01-01',strtotime('-1 year')));
    $end_time = strtotime(date('Y-12-31 23:59:59',strtotime('-1 year')));

    return [$start_time, $end_time];
}

/**
 * 根据指定年月获取开始结束时间
 * @return int $year 年份
 * @return int $month 月份
 */
function get_month_time($year = '', $month = '')
{
    $year = $year ? $year : date('Y');
    $month = $month ? $month : date('m');
    $days = date('t', strtotime($year.'-'.$month));
    $start_time = strtotime($year.'-'.$month);
    $end_time = mktime(23, 59, 59, $month, $days, $year);
    return [$start_time, $end_time];
}

/**
 * 获取上个月开始结束时间戳
 * @return array
 */
function get_last_month_time()
{
    $start_time = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month')));
    $end_time = strtotime(date("Y-m-d 23:59:59", strtotime(-date('d') . 'day')));

    return [$start_time, $end_time];
}

/**
 * 获取昨日开始结束时间戳
 * @return array
 */
function get_tomorrow_time()
{
    $start_time = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
    $end_time = $start_time + 86399;

    return [$start_time, $end_time];
}
/**
 * 获取n天前到今天结束的时间戳
 * @return array
 */
function get_before_time($n = 1,$type = 1)
{
    $start_time = mktime(0,0,0,date('m'),date('d')-$n,date('Y'));
    if($type == 1){
        $end_time = strtotime(date('Y-m-d')." 23:59:59");
    }else{
        $end_time = mktime(23,59,59,date('m'),date('d')-$n,date('Y'));
    }
    return [$start_time, $end_time];
}

/*

*	反序列化

*/

function _unserialize($txt){

   return unserialize(gzuncompress(base64_decode($txt))); 

}

/**

*	PHP 版本判断

*

**/

function is_php($version = '5.0.0'){

		static $_is_php;

		$version = (string)$version;

		if ( ! isset($_is_php[$version]))

		{

			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;

		}

		return $_is_php[$version];

}

	

/**

 * 返回经addslashes处理过的字符串或数组

 * @param $string 需要处理的字符串或数组

 * @return mixed

 */

function new_addslashes($string){

	if(!is_array($string)) return addslashes($string);

	foreach($string as $key => $val) $string[$key] = new_addslashes($val);

	return $string;

}

/*数组转字符串*/

function Array2String($Array){

		if(!$Array)return false;

		$Return='';

		$NullValue="^^^";

		foreach ($Array as $Key => $Value) {

			if(is_array($Value))

				$ReturnValue='^^array^'.Array2String($Value);

			else

				$ReturnValue=(strlen($Value)>0)?$Value:$NullValue;

			$Return.=urlencode(base64_encode($Key)) . '|' . urlencode(base64_encode($ReturnValue)).'||';

		}

		return urlencode(substr($Return,0,-2));

}

/*字符串转数组*/

function String2Array($String){

	if(NULL==$String)return false;

    $Return=array();

    $String=urldecode($String);

    $TempArray=explode('||',$String);

    $NullValue=urlencode(base64_encode("^^^"));

    foreach ($TempArray as $TempValue) {

        list($Key,$Value)=explode('|',$TempValue);

        $DecodedKey=base64_decode(urldecode($Key));

        if($Value!=$NullValue) {

            $ReturnValue=base64_decode(urldecode($Value));

            if(substr($ReturnValue,0,8)=='^^array^')

                $ReturnValue=String2Array(substr($ReturnValue,8));

            $Return[$DecodedKey]=$ReturnValue;

        }

        else

        $Return[$DecodedKey]=NULL;

    }

    return $Return;

}

// 微秒处理
/* function microt($time,$x=null){
    $list=explode(".",$time);
    if($x=="l"){
        return date("His",$list[1]).substr($list[0],2,2);
    }else if($x=="r"){
        return date("Y年m月d日 H:i",$list[1]);
    }else{
        return date("Y-m-d H:i:s",$list[0]).".".substr($list[1],2,2);
    }
} */
function microt($time,$x=null){
	$len=strlen($time);
	if($len<13){
		$time=$time.".0";
	}
	$list=explode(".",$time);	
	if($x=="L"){		
		return date("His",$list[0]).substr($list[1],0,3);
	}else if($x=="Y"){
        // return date("Y-m-d",$list[0]);
        return getLTF($list[0],2);
	}else if($x=="H"){
		return date("H:i:s",$list[0]).".".substr($list[1],0,3);
	}else if($x=="r"){
		// return date("Y年m月d日 H:i",$list[0]);
		return getLTF($list[0],3);
	}else{
        // return date("Y-m-d H:i:s",$list[0]).".".substr($list[1],0,3);
        return getLTF($list[0]).".".substr($list[1],0,3);       
	}
}

/**
 * 字符过滤url
 *
 * @param [type] $str
 * @param boolean $type
 * @return string
 */
function safe_replace($str,$type=false)
{
    $str = htmlspecialchars($str);
//    $str = str_replace( '/', "", $str);
    $str = str_replace( '"', "", $str);
    $str = str_replace( '(', "", $str);
    $str = str_replace( ')', "", $str);
    $str = str_replace( 'CR', "", $str);
    $str = str_replace( 'ASCII', "", $str);
    $str = str_replace( 'ASCII 0x0d', "", $str);
    $str = str_replace( 'LF', "", $str);
    $str = str_replace( 'ASCII 0x0a', "", $str);
    // $str = str_replace( ',', "", $str);
    $str = str_replace( '%', "", $str);
    $str = str_replace( ';', "", $str);
    $str = str_replace( 'eval', "", $str);
    $str = str_replace( 'open', "", $str);
    $str = str_replace( 'sysopen', "", $str);
    $str = str_replace( 'system', "", $str);
    $str = str_replace( '$', "", $str);
//    $str = str_replace( "'", "", $str);
    $str = str_replace( 'ASCII 0x08', "", $str);
    $str = str_replace("&gt", "", $str);
    $str = str_replace("&lt", "", $str);
    $str = str_replace("<SCRIPT>", "", $str);
    $str = str_replace("</SCRIPT>", "", $str);
    $str = str_replace("<script>", "", $str);
    $str = str_replace("</script>", "", $str);
    $str = str_replace("select","",$str);
    $str = str_replace("join","",$str);
    $str = str_replace("union","",$str);
    $str = str_replace("where","",$str);
    $str = str_replace("insert","",$str);
    $str = str_replace("delete","",$str);
    $str = str_replace("update","",$str);
    $str = str_replace("like","",$str);
    $str = str_replace("drop","",$str);
    $str = str_replace("DROP","",$str);
    $str = str_replace("create","",$str);
    $str = str_replace("modify","",$str);
    $str = str_replace("rename","",$str);
    $str = str_replace("alter","",$str);
    $str = str_replace("cas","",$str);
    $str = str_replace("&","",$str);
    $str = str_replace(">","",$str);
    $str = str_replace("<","",$str);
    $str = str_replace(" ",chr(32),$str);
    $str = str_replace(" ",chr(9),$str);
    $str = str_replace("    ",chr(9),$str);
    $str = str_replace("&",chr(34),$str);
    $str = str_replace("'",chr(39),$str);
    $str = str_replace("<br />",chr(13),$str);
    $str = str_replace("''","'",$str);
    $str = str_replace("css","'",$str);
    $str = str_replace("CSS","'",$str);
    $str = str_replace("<!--","",$str);
    $str = str_replace("convert","",$str);
    $str = str_replace("md5","",$str);
    $str = str_replace("passwd","",$str);
    $str = str_replace("password","",$str);
    $str = str_replace("../","",$str);
    $str = str_replace("./","",$str);
    $str = str_replace("Array","",$str);
    $str = str_replace("or 1='1'","",$str);
    $str = str_replace(";set|set&set;","",$str);
    $str = str_replace("`set|set&set`","",$str);
    $str = str_replace("--","",$str);
    $str = str_replace("OR","",$str);
    $str = str_replace('"',"",$str);
    $str = str_replace("*","",$str);
    $str = str_replace("-","",$str);
    $str = str_replace("+","",$str);
//    $str = str_replace("/","",$str);
    $str = str_replace("=","",$str);
    $str = str_replace("'/","",$str);
    $str = str_replace("-- ","",$str);
    $str = str_replace(" -- ","",$str);
    $str = str_replace(" --","",$str);
    $str = str_replace("(","",$str);
    $str = str_replace(")","",$str);
    $str = str_replace("{","",$str);
    $str = str_replace("}","",$str);
    $str = str_replace("-1","",$str);
//    $str = str_replace("1","",$str);
//    $str = str_replace(".","",$str);
    $str = str_replace("response","",$str);
    $str = str_replace("write","",$str);
    $str = str_replace("|","",$str);
    $str = str_replace("`","",$str);
    $str = str_replace(";","",$str);
    $str = str_replace("etc","",$str);
    $str = str_replace("root","",$str);
    $str = str_replace("//","",$str);
    $str = str_replace("!=","",$str);
    $str = str_replace("$","",$str);
    $str = str_replace("&","",$str);
    $str = str_replace("&&","",$str);
    $str = str_replace("==","",$str);
    $str = str_replace("#","",$str);
    $str = str_replace("@","",$str);
    $str = str_replace("mailto:","",$str);
    $str = str_replace("CHAR","",$str);
    $str = str_replace("char","",$str);
    if($type){
        $str = str_replace(",","",$str);
    }
    return $str;
}

/*获取页面完整url*/

function get_web_url() {

	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';

	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];

	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);

	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;

}

/*获取网站当前地址*/

function get_home_url() {

	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';

	$path=explode('/',safe_replace($_SERVER['SCRIPT_NAME']));

	if(count($path)==3){

		return $sys_protocal.$_SERVER['HTTP_HOST'].'/'.$path[1];

	}

	if(count($path)==2){

		return $sys_protocal.$_SERVER['HTTP_HOST'];

	}

}

/*获取页面get参数*/

function get_web_param() {

	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];

	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);

	return $relate_url;

}

/*HTML安全过滤*/

function _htmtocode($content) {

		$content = str_replace('%','%&lrm;',$content);

		$content = str_replace("<", "&lt;", $content);

		$content = str_replace(">", "&gt;", $content);            

		$content = str_replace("\n", "<br/>", $content);

		$content = str_replace(" ", "&nbsp;", $content);

		$content = str_replace('"', "&quot;", $content);

		$content = str_replace("'", "&#039;", $content);

		$content = str_replace("$", "&#36;", $content);

		$content = str_replace('}','&rlm;}',$content);

		return $content;

}

function _unhtmtocode($content) {
    $content = str_replace('%&lrm;', '%',$content);
    $content = str_replace( "&lt;",  "<",$content);
    $content = str_replace( "&gt;",  ">",$content);            
    $content = str_replace( "<br/>", "\n",$content);
    $content = str_replace( "&nbsp;"," ", $content);
    $content = str_replace( "&quot;",'"', $content);
    $content = str_replace( "&#039;","'", $content);
    $content = str_replace( "&#36;", "$", $content);
    $content = str_replace('&rlm;}', '}',$content);
    return $content;
}

/*手机号码验证*/

function _checkmobile($mobilephone=''){

	if(strlen($mobilephone)!=11){	return false;	}

	// if(preg_match("/^13[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$mobilephone)){   
	if(preg_match("/^1[3-9][0-9]{9}$/",$mobilephone)){   

		return true;

	}else{
		return false;

	}

}

/**
 *  手机号码替换为**
 * @param $tel
 * @return mixed
 */
function hidtel($tel)
{
    $str = substr_replace($tel, '****', 3, 4);
    return $str;
}

	

/*邮箱验证*/

function _checkemail($email=''){

		if(mb_strlen($email)<5){

			return false;

		}

		$res="/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";

		if(preg_match($res,$email)){

			return true;

		}else{

			return false;

		}

}	

/*加密解密 ENCODE 加密   DECODE 解密*/

function _encrypt($string, $operation = 'ENCODE', $key = '', $expiry = 0){

	if($operation == 'DECODE') {

		$string =  str_replace('_', '/', $string);

	}

	$key_length = 4;

	if(defined("G_BANBEN_NUMBER")){

			$key = md5($key != '' ? $key : System::load_sys_config("code","code"));

	}else{

			$key = md5($key != '' ? $key : G_WEB_PATH);

	}

	$fixedkey = md5($key);

	$egiskeys = md5(substr($fixedkey, 16, 16));

	$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';

	$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));

	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));

	$i = 0; $result = '';

	$string_length = strlen($string);

	for ($i = 0; $i < $string_length; $i++){

		$result .= chr(ord($string[$i]) ^ ord($keys[$i % 32]));

	}

	if($operation == 'ENCODE') {

		$retstrs =  str_replace('=', '', base64_encode($result));

		$retstrs =  str_replace('/', '_', $retstrs);

		return $runtokey.$retstrs;

	} else {	
		// PHP 8.2 兼容：将字符串转换为整数进行数值比较
		$timestamp = intval(substr($result, 0, 10));
		if(($timestamp == 0 || $timestamp - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {

			return substr($result, 26);

		} else {

			return '';

		}

	}

}

function _getcookie($name){

	if(empty($name)){return false;}

	if(isset($_COOKIE[$name])){
		return $_COOKIE[$name];

	}else{		

		return false;

	}

}

function _setcookie($name,$value,$time=0,$path='/',$domain=''){		

	if(empty($name)){return false;}

	$_COOKIE[$name]=$value;				//及时生效

	$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;

	if(!$time){

		return setcookie($name,$value,0,$path,$domain,$s);

	}else{

		return setcookie($name,$value,time()+$time,$path,$domain,$s);

	}

}

/**
 * CSRF：获取并下发 token（写 Cookie，供表单隐藏域使用）
 * 用法：在输出表单前调用 $token = csrf_token_get(); 模板中 <input type="hidden" name="csrf_token" value="...">
 */
function csrf_token_get(){
	$name = 'yungou_csrf';
	$len = 32;
	if (empty($_COOKIE[$name]) || strlen((string)$_COOKIE[$name]) !== $len) {
		$token = bin2hex(random_bytes(16));
		$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
		setcookie($name, $token, time() + 7200, '/', '', $secure, true);
		return $token;
	}
	return $_COOKIE[$name];
}

/**
 * CSRF：校验 POST 请求是否携带合法 token（与 Cookie 中一致）
 */
function csrf_token_validate(){
	$name = 'yungou_csrf';
	$post = isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : '';
	$cookie = isset($_COOKIE[$name]) ? (string)$_COOKIE[$name] : '';
	return $post !== '' && $cookie !== '' && hash_equals($cookie, $post);
}

/*

*获取字符串长度

*一个汉字2个字节,字符1个字节

*/

function _strlen($str=''){

	if(empty($str)){

		return 0;

	}

	if(!_is_utf8($str)){

		$str=iconv("GBK","UTF-8",$str);

	}

	return ceil((strlen($str)+mb_strlen($str,'utf-8'))/2);

}

	

 	/*

	* 调用模板函数

	* $module   模板目录

	* $template 模板文件名,

	* $StyleTheme    模板方案目录,为空为默认目录

	*/

	

function templates($module = '', $template = '',$StyleTheme=''){	

		if(empty($StyleTheme)){$style=G_STYLE.DIRECTORY_SEPARATOR.G_STYLE_HTML;}

		else{

			$templates=System::load_sys_config('templates',$style);

			$style=$templates['dir'].DIRECTORY_SEPARATOR.$templates['html'];

		}
        $admindir = System::load_sys_config("system",'admindir');
        if($admindir == ROUTE_M){
            $style = "mlmuban/mlmuban";
        }

		$FileTpl = G_CACHES.'caches_template'.DIRECTORY_SEPARATOR.dirname($style).DIRECTORY_SEPARATOR.md5($module.'.'.$template).'.tpl.php';	

		$FileHtml = G_TEMPLATES.$style.DIRECTORY_SEPARATOR.$module.'.'.$template.'.html';	
        
		if(file_exists($FileHtml)){		

			if (file_exists($FileTpl) && @filemtime($FileTpl) >= @filemtime($FileHtml) &&0) {					

				return $FileTpl;			

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

					return $FileTpl;

				else				

					_error('template message','The "'.$module.'.'.$template .'" template file does not exist');

			}

		}

		_error('template message','The "'.$module.'.'.$template .'" template file does not exist');

		

	}

/**

 * 字符截取 支持UTF8/GBK

 * @param $string

 * @param $length

 * @param $dot

 */

function _strcut($string, $length,$dot = '...') {

        $string = trim($string);        

        if($length && strlen($string) > $length) {   

            //截断字符   

            $wordscut = '';   

			if(strtolower(G_CHARSET) == 'utf-8') {           

                //utf8编码   

                $n = 0;

                $tn = 0;   

                $noc = 0;   

                while ($n < strlen($string)) {   

                    $t = ord($string[$n]);   

                    if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {   

                        $tn = 1;   

                        $n++;   

                        $noc++;   

                    } elseif(194 <= $t && $t <= 223) {   

                        $tn = 2;   

                        $n += 2;   

                        $noc += 2;   

                    } elseif(224 <= $t && $t < 239) {   

                        $tn = 3;   

                        $n += 3;   

                        $noc += 2;   

                    } elseif(240 <= $t && $t <= 247) {   

                        $tn = 4;   

                        $n += 4;   

                        $noc += 2;   

                    } elseif(248 <= $t && $t <= 251) {   

                        $tn = 5;   

                        $n += 5;   

                        $noc += 2;   

                    } elseif($t == 252 || $t == 253) {   

                        $tn = 6;   

                        $n += 6;   

                        $noc += 2;   

                    } else {   

                        $n++;   

                    }   

                    if ($noc >= $length) {   

                        break;   

                    }   

                }   

                if ($noc > $length) {   

                    $n -= $tn;   

                }   

                $wordscut = substr($string, 0, $n);   

            } else {   

                for($i = 0; $i < $length - 1; $i++) {   

                    if(ord($string[$i]) > 127) {   

                        $wordscut .= $string[$i].$string[$i + 1];   

                        $i++;   

                    } else {   

                        $wordscut .= $string[$i];   

                    }   

                }   

            }   

            $string = $wordscut.$dot;

        }   

        return trim($string);

}

	

/*获取客户端ip*/

function _get_ip(){

		if (isset($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], "unknown")) 

			$ip = $_SERVER['HTTP_CLIENT_IP']; 

		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], "unknown")) 

			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 

		else if (isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 

			$ip = $_SERVER['REMOTE_ADDR']; 

		else if (isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 

			$ip = $_SERVER['REMOTE_ADDR']; 

		else $ip = ""; 
        $ip = safe_replace($ip);
		return ($ip);

}

/* 获取ip + 地址*/

function _get_ip_dizhi(){

		$opts = array(

			'http'=>array(

			'method'=>"GET",

			'timeout'=>5,)

		);		

		$context = stream_context_create($opts); 

		$ipmac=_get_ip();

		if(strpos($ipmac,"127.0.0.") === true)return '';

		$url_ip='http://www.36ip.cn/?type=json&ip='.$ipmac;

		$str = @file_get_contents($url_ip, false, $context);

		if(!$str) return "";

		$json=json_decode($str,true);

		if($json['data']){

			$ipcity= $json['data'];

			$ip= $ipcity.','.$ipmac;

		}else{

			$ip="";

		}

		return $ip;

}

/**
 * 
 * 返回IP归属地
 * @param mixed $type 
 * @param mixed $ip
 * @return bool|string
 */
function _get_ip_region($type=3,$ip=''){
    if(!$ip){
        $ip = _get_ip();
    }
    $iparr = ["127.0.0.1","0.0.0.0","255.255.255.255"];
    if(in_array($ip,$iparr)){
        return "广东省广州市";
    }
    // $url = "http://ip.yungoucms.com/?ip=".$ip."&type=$type&mode=1";
    // $ip_region = getCurl($url);
    System::load_sys_class('ip2Region/region','sys','no');
    $Ip2Region = new region();
    $ip_region = $Ip2Region->getIp2Region($ip,$type,1);
    return $ip_region;
}

	

/**

 * 判断字符串是否为utf8编码，英文和半角字符返回ture

 * @param $string

 * @return bool

 */

function _is_utf8($string) {

	return preg_match('%^(?:

					[\x09\x0A\x0D\x20-\x7E] # ASCII

					| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte

					| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs

					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte

					| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates

					| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3

					| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15

					| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16

					)*$%xs', $string);

}

/**

*	发送电子邮件

*	@email 也可以是一个二维数组，包含邮件和用户名信息

**/

function _sendemail($email,$username=null,$title='',$content='',$yes='',$no=''){

	System::load_sys_class("email",'sys',"no");

	$config=System::load_sys_config('email');

	if(!$username)$username="";

	if(!$yes)$yes="Send success";

	if(!$no)$no="Send fail";

	if(!_checkemail($email)){return false;}	

	email::config($config);

	if(is_array($email)){

		email::adduser($email);	

	}else{

		email::adduser($email,$username);

	}	

	$if=email::send($title,$content);

	if($if){

			return $yes;

	}else{

			return $no;

	}

}

function _send_SMS($mobile,$temp_id,$code=''){
    System::load_sys_fun('send');
    return new_send_template($mobile,$temp_id,$code);
}
/*

*	发送短信

**/
function _sendmobile($mobiles='',$content='',$key=null){
	$mobiles=str_replace("，",',',$mobiles);

	$mobiles=str_replace(" ",'',$mobiles);

	$mobiles=trim($mobiles," ");

	$mobiles=trim($mobiles,",");
    System::load_app_fun('member','member');
    $arr = explode(',',$mobiles);
    foreach ($arr as $v){
        if (get_user_black($v,2)){
            return array('-1','手机号已被拉黑');
        }
    }

	$sends=System::load_sys_class('sendmobile');

	$config=array();

	$config['mobile']=$mobiles;

	$config['content']= $content;	

	$config['ext']='';

	$config['stime']='';

	$config['rrid']='';

	$cok=$sends->init($config);
	
	if(!$cok){

		return array('-1','配置不正确!');

	}
	$sends->send();
	$sendarr=array($sends->error,$sends->v);
	return $sendarr;

}

 function _xml_to_array($xml){
     $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
     if(preg_match_all($reg, $xml, $matches)){
         $count = count($matches[0]);
         for($i = 0; $i < $count; $i++){
         $subxml= $matches[2][$i];
         $key = $matches[1][$i];
             if(preg_match( $reg, $subxml )){
                 $arr[$key] = _xml_to_array($subxml);
             }else{
                 $arr[$key] = $subxml;
             }
         }
     }
     return $arr;
 }

//测试
function _sendmobile1($mobiles='',$content='',$key=null){
	$mobiles=str_replace("，",',',$mobiles);

	$mobiles=str_replace(" ",'',$mobiles);

	$mobiles=trim($mobiles," ");

	$mobiles=trim($mobiles,",");
    System::load_app_fun('member','member');
    $arr = explode(',',$mobiles);
    foreach ($arr as $v){
        if (get_user_black($v,2)){
            return array('-1','手机号已被拉黑');
        }
    }

	$sends=System::load_sys_class('sendmobile');

	$config=array();

	$config['mobile']=$mobiles;

	$config['content']= $content;	

	$config['ext']='';

	$config['stime']='';

	$config['rrid']='';

	$cok=$sends->init($config,"",$key);
	
	if(!$cok){

		return array('-1','配置不正确!');

	}
	$sends->send();
	$sendarr=array($sends->error,$sends->v);
	return $sendarr;

}

/**

*

*	页面执行时间统计

*

*/

function get_end_time(){

	

	$EndTime=explode(" ",microtime());

	$StartTime=explode(" ",G_START_TIME);

	return intval($EndTime[1]-$StartTime[1])+($EndTime[0]-$StartTime[0]).'/S';

}

/*

*

* 页面内存消耗统计

*/

function get_end_memory(){

	$memory=memory_get_usage();

	$memory=$memory/1024;

	return round($memory,2).'/KB';

}

/**

*	message 输出自定义错误页面

*	$str 错误信息

*	$url 返回页面的地址, 默认返回上一页	

*	$time 返回时间，默认3秒后返回

*   $config 其他参数配置.类型为数组 $config['titlebg']='#549bd9',$config['title']='#fff',

*/

/*
*	系统消息提示 成功
**/

function _message($string=null,$defurl=null,$time=2,$config=null,$iserror = false){
	if(empty($defurl)){
		$defurl=G_HTTP_REFERER;
		if(empty($defurl))$defurl=WEB_PATH;
	}
	if(defined("G_IN_ADMIN")){
		if(empty($config)){
			$config = array("titlebg"=>"#DB3752","title"=>"#fff");
		}
		$str_url_two=array("url"=>WEB_PATH.'/'.G_ADMIN_DIR,"text"=>"返回后台首页");
	}else{
		$str_url_two=array("url"=>G_WEB_PATH,"text"=>"返回首页");
	}
	$time=intval($time);if($time<2){$time=2;}
    $iserror = $iserror?"error":"success";
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest"){
        echo json_encode([
            "code" => 200,
            "string" => $string,
            "msg" => $string,
            "defurl" => $defurl,
            "time" => $time,
            "config" => $config,
            "iserror" => $iserror,
            "str_url_two" => $str_url_two
        ]);
        exit;
    }
	include templates("system","messageTj");
	exit;
}
/*
*	系统消息提示 成功
**/

function _messageerror($string=null,$defurl=null,$time=2,$config=null,$iserror = true){
    if(empty($defurl)){
        $defurl=G_HTTP_REFERER;
        if(empty($defurl))$defurl=WEB_PATH;
    }
    if(defined("G_IN_ADMIN")){
        if(empty($config)){
            $config = array("titlebg"=>"#DB3752","title"=>"#fff");
        }
        $str_url_two=array("url"=>WEB_PATH.'/'.G_ADMIN_DIR,"text"=>"返回后台首页");
    }else{
        $str_url_two=array("url"=>G_WEB_PATH,"text"=>"返回首页");
    }
    $time=intval($time);if($time<2){$time=2;}
    $iserror = $iserror?"error":"success";
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest"){
        echo json_encode([
            "code" => 500,
            "string" => $string,
            "msg" => $string,
            "defurl" => $defurl,
            "time" => $time,
            "config" => $config,
            "iserror" => $iserror,
            "str_url_two" => $str_url_two
        ]);
        exit;
    }
    include templates("system","messageTj");
    exit;
}

/*

*	手机消息提示

**/

function _messagemobile($string=null,$defurl=null,$time=2,$config=null){

	if(empty($defurl)){

		$defurl=G_HTTP_REFERER;

		if(empty($defurl))$defurl=WEB_PATH;

	}

	if(empty($config)){

		if(ROUTE_M==System::load_sys_config("system","admindir")){		

			$config=array();

			$config['titlebg']='#549bd9';$config['title']='#fff';

		}

	}

	$time=intval($time);if($time<2){$time=2;}

	include templates("mobile/system","message");

	exit;

}

/**

*	message 输出自定义错误页面

*	$str 错误信息

*	$url 返回页面的地址, 默认返回上一页	

*	$time 返回时间，默认3秒后返回

*/

function _error($title,$content){

	if(empty($title)){$title='404 Page Not Found';}

	if(empty($content)){$content='The page you requested was not found.';}

	echo '<!DOCTYPE html><html lang="en"><head><title>'.$title.'</title><style type="text/css">::selection{ background-color: #E13300; color: white; }

::moz-selection{ background-color: #E13300; color: white; }::webkit-selection{ background-color: #E13300; color: white; }

body {	background-color: #fff;	margin: 40px;	font: 13px/20px normal Helvetica, Arial, sans-serif;	color: #4F5155;}

a {	color: #003399;	background-color: transparent;	font-weight: normal;}

h1 {	color: #444;	background-color: transparent;	border-bottom: 1px solid #D0D0D0;	font-size: 19px;	font-weight: normal;

	margin: 0 0 14px 0;	padding: 14px 15px 10px 15px;}

code {	font-family: Consolas, Monaco, Courier New, Courier, monospace;	font-size: 12px;

	background-color: #f9f9f9;	border: 1px solid #D0D0D0;	color: #002166;	display: block;	margin: 14px 0 14px 0;	padding: 12px 10px 12px 10px;}

#container {	margin: 10px;	border: 1px solid #D0D0D0;	-webkit-box-shadow: 0 0 8px #D0D0D0;}

p {	margin: 12px 15px 12px 15px;}</style><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>	<div id="container">		<h1>'.$title.'</h1>		<p>'.$content.'</p></div></body></html>';

	exit;

}

/**

 * IE浏览器判断

 */

function _is_ie() {

	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)) return false;

	if(strpos($useragent, 'msie ') !== false) return true;

	return false;

}

function _is_mobile() { 

    $user_agent = $_SERVER['HTTP_USER_AGENT']; 

    $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte"); 

    $is_mobile = false; 

    foreach ($mobile_agents as $device) { 

        if (stristr($user_agent, $device)) { 

            $is_mobile = true; 

            break; 

        } 

    } 

    return $is_mobile; 

}

	

/* PHP解析错误处理 */

function _error_handler(){

	ini_set("display_errors","OFF");					 	//错误报告提示关闭

	ini_set("error_log",G_CACHES."error.logs");	      		//写错误日志的地址	

}

/* $n 字符串长度,返回一个数组*/

function _getcode($n=10){

	$num=intval($n) ? intval($n) : 10;

	if($num>44)

		$codestr=base64_encode(md5(time()).md5(time()));

	else

		$codestr=base64_encode(md5(time()));

	$temp=array();

	$temp['code']=substr($codestr,0,$num);

	$temp['time']=time();

	return $temp;

}

/* 获取系统变量 */

function _cfg($name=''){

	return System::load_sys_config('system',$name);

}

/* 安装系统函数 */

function hook_mysql_install($message=''){

	if(file_exists(G_APP_PATH.'install')){

		echo "<script>";

		echo "window.location.href='".G_WEB_PATH."/install'";

		echo "</script>";

	}else{

		_error("数据库连接不成功,请检查配置！",$message);

	}

	exit;

}

/* 网络操作函数 */

function _g_triggerRequest($url,$io=false,$post_data = array(), $cookie = array()){

		$method = empty($post_data) ? 'GET' : 'POST';
        $url_array = parse_url($url);		

        $port = isset($url_array['port'])? $url_array['port'] : 80; 

		

		if(function_exists('fsockopen')){

			$fp = @fsockopen($url_array['host'], $port, $errno, $errstr, 30);

		}elseif(function_exists('pfsockopen')){

			$fp = @pfsockopen($url_array['host'], $port, $errno, $errstr, 30);

		}elseif(function_exists('stream_socket_client')){

			$fp = @stream_socket_client($url_array['host'].':'.$port,$errno,$errstr,30);

		} else {

			$fp = false;

		}
        if(!$fp){	
             return false;

        }

		

        $getPath = $url_array['path'] ."?". $url_array['query'];
        $header  = $method . " " . $getPath." ";

        $header .= "HTTP/1.1\r\n";

        $header .= "Host: ".$url_array['host']."\r\n"; //HTTP 1.1 Host域不能省略

		$header .= "Pragma: no-cache\r\n";
        /*
			//以下头信息域可以省略

			$header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 \r\n";

			$header .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,q=0.5 \r\n";
			$header .= "Accept-Language: en-us,en;q=0.5 ";
			$header .= "Accept-Encoding: gzip,deflate\r\n";
        */	

        if(!empty($cookie)){
                $_cookie_s = strval(NULL);

                foreach($cookie as $k => $v){

                        $_cookie_s .= $k."=".$v."; ";

                }

				$_cookie_s = rtrim($_cookie_s,"; ");

                $cookie_str =  "Cookie: " . base64_encode($_cookie_s) ." \r\n";	   //传递Cookie

                $header .= $cookie_str;

        }

		$post_str = '';

         if(!empty($post_data)){
                $_post = strval(NULL);

                foreach($post_data as $k => $v){

                        $_post .= $k."=".urlencode($v)."&";

                }

				$_post = rtrim($_post,"&");

                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";//POST数据

                $header .= "Content-Length: ". strlen($_post) ." \r\n";//POST数据的长度	

				

                $post_str = $_post."\r\n"; //传递POST数据

        }

		$header .= "Connection: Close\r\n\r\n";

		$header .= $post_str;
        fwrite($fp,$header);

		if($io){		

			 while (!feof($fp)){ 

                   echo fgets($fp,1024);

			 }

		}   

        fclose($fp);

		//echo $header;

        return true;

}

/**

*	短时间显示, 几分钟前,几秒前...

**/

function _put_time($time = 0){

	$ttime = time() - $time;

	if($ttime <= 0 || $ttime < 60){

		return lan('几秒前');

	}	

	if($ttime > 60 && $ttime <120){

		return '1'.lan('分钟前');

	}

	

	$i = floor($ttime / 60);								//分

	$h = floor($ttime / 60 / 24);						//时

	$d = floor($ttime / 86400);							//天

	$m = floor($ttime / 2592000);						//月

	$y = floor($ttime / 60 / 60 / 24 / 365);				//年

	if($i < 30){

		return $i.lan('minute_ago','分钟前');

	}

	if($i > 30 && $i < 60){

		return "1".lan('within_hours','小时内');

	}

	if($h && $h < 24){

		return $h.lan('hours_ago','小时前');

	}

	if($d && $d < 30){

		return $d.lan('days_ago','天前');

	}	

	if($m && $m < 12){		

		return $m.lan('month_ago','个月前');

	}

	if($y){

		return $y.lan('before_year','年前');

	}	

	return "";

	

}

// 这个函数是判断版本，如果是搞版本的则直接用版本带的函数处理，如果不是则用递归处理
function my_json_encode($p){
    if (PHP_VERSION >= '5.4'){
            $str = json_encode($p, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }else{ 
            $str=JSON($p);
        }
    return $str;
}

  /**************************************************************
   *
   *  将数组转换为JSON字符串（兼容中文）
   *  @param  array   $array      要转换的数组
   *  @return string      转换得到的json字符串
   *  @access public
   *
   *************************************************************/
function JSON($array) {
       arrayRecursive($array, 'urlencode', true);
       $json = json_encode($array);
       $json = json_encode($array,JSON_UNESCAPED_UNICODE);
       return urldecode($json);
   }

   /**************************************************************
    *
    *  使用特定function对数组中所有元素做处理
    *  @param  string  &$array     要处理的字符串
    *  @param  string  $function   要执行的函数
    *  @return   boolean $apply_to_keys_also     是否也应用到key上
    *  @access  public
    *
    *************************************************************/
   function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
      {
          static $recursive_counter = 0;
          if (++$recursive_counter > 1000) {
              die('possible deep recursion attack');
          }
          foreach ($array as $key => $value) {
              if (is_array($value)) {
                  arrayRecursive($array[$key], $function, $apply_to_keys_also);
              } else {
                  $array[$key] = $function($value);
              }
              if ($apply_to_keys_also && is_string($key)) {
                  $new_key = $function($key);
                  if ($new_key != $key) {
                      $array[$new_key] = $array[$key];
                      unset($array[$key]);
                  }
              }
          }
          $recursive_counter--;
      }
      // get方法请求
      function getCurl($url){//get https的内容
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//不输出内容
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       $result= curl_exec($ch);
       curl_close($ch);
       return $result;     
       }

        // post方法请求
       function postCurl($url,$post){//get https的内容
       $ch = curl_init();//新建curl
       curl_setopt($ch, CURLOPT_URL, $url);//url  
       curl_setopt($ch, CURLOPT_POST, 1);  //post
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post);//post内容  
      $res =  curl_exec($ch); //输出
      $arr= json_decode($res,true);
      curl_close($ch);
       return $arr;
       }

       function curlPost($url,$params,$type=1,$header = []){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            if($header){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            $error = curl_error($ch);
            if ($error) {
                return $error;   
            }
            $resultJson = curl_exec($ch);
            
            curl_close($ch);

            if($type==1){
                return json_decode( $resultJson,true);
            }else{
                return $resultJson;
            }
       }

       // 获取token
       function get_token($appid,$appsecret){
       	$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
       	$json=getCurl($url);
       	$arr=json_decode($json,true);
       	return $arr['access_token'];
       }
//非阻塞模式fsockopen发送
function vsock($url) {
    $host = parse_url($url,PHP_URL_HOST);
    $port = parse_url($url,PHP_URL_PORT);
    $port = $port ? $port : 80;
    $scheme = parse_url($url,PHP_URL_SCHEME);
    $path = parse_url($url,PHP_URL_PATH);
    $query = parse_url($url,PHP_URL_QUERY);
    if($query) $path .= '?'.$query;
    if($scheme == 'https') {
        $host = 'ssl://'.$host;
    }
 
    $fp = fsockopen($host,$port,$error_code,$error_msg,1);
    if(!$fp) {
        return false;
    }
    else {
        stream_set_blocking($fp,true);//开启了手册上说的非阻塞模式
        stream_set_timeout($fp,1);//设置超时
        $header = "GET $path HTTP/1.1\r\n";
        $header.="Host: $host\r\n";
        $header.="Connection: close\r\n\r\n";//长连接关闭
        fwrite($fp, $header);
        usleep(1000); // 这一句也是关键，如果没有这延时，可能在nginx服务器上就无法执行成功
        fclose($fp);
        return true;
    }
}
//输入正则
//参数 1  匹配项
//参数 2  要检测的字符串
function _strRegular($index,$str){
    if(empty($index))return;
    $configsearch = System::load_sys_config('search');
    if(!is_array($configsearch[$index]) || !isset($configsearch[$index]))return true;
    $search = $configsearch[$index];
    $strlength = mb_strlen($str,"utf-8");
    if(isset($search['max']) && !empty($search['max'])){
        if($strlength > $search['max']){
            _messagemobile('字符超过限制'.$search['max'].'个字符');
        }
    }
    if(isset($search['min']) && !empty($search['min'])){
        if($strlength > $search['max']){
            _messagemobile('最少输入'.$search['max'].'个字符');
        }
    }
    if(isset($search['regular']) && !empty($search['regular'])){
        if(preg_match($search['regular'],$str)){
            _messagemobile('格式不正确');
        }

    }

}

/**
 * 字符串生成二维码
 * @param $str 二维码内容
 * @param $logo logo图
 * @param $path 二维码保存地址
 */
function getQr($str,$path,$logo){
    ob_flush();
    include_once($_SERVER ['DOCUMENT_ROOT'].'/statics/plugin/qrcode/QRcode.php');

    $rs=QRcode::png($str,$path,"M");

    $logo = G_WEB_PATH.'/statics/uploads/' . $logos;//需要显示在二维码中的Logo图像

    if ($logo !== FALSE) {
        $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
        $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
        $QR_width = imagesx ( $QR );
        $QR_height = imagesy ( $QR );
        $logo_width = imagesx ( $logo );
        $logo_height = imagesy ( $logo );
        $logo_qr_width = $QR_width / 4;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
    }
    imagepng ( $QR, $path_2 );//带Logo二维码的文件名
    return G_WEB_PATH."/".$path;
    // ob_clean();//在输出钱擦掉（清除）缓存

}

/**
 * 邀请码
 * @param $uid 用户id
 * @param $qrcode 二维码保存地址
 * @param $url 网站地址
 * @param $bg_img 二维码背景图
 * @param $logo logo图
 * @param $qr_code_size 二维码大小
 * @param $qr_code_border 二维码边框
 * @param $left_margin 二维码距离左边距
 * @param $top_margin 二维码距离顶部距离
 * @param $internal_x 二维码距离左边内部距离
 * @param $internal_y 二维码距离上边内部距离
 */
function mycode_new($uid,$qrcode,$url,$bg_img,$logo,$qr_code_size,$qr_code_border,$left_margin,$top_margin,$internal_x,$internal_y){
    ob_flush();
    include_once($_SERVER ['DOCUMENT_ROOT'].'/statics/plugin/qrcode/QRcode.php');

    $code=$url."/index/pages/indexs/index?code=$uid"; // vue 首页地址
    $rs=QRcode::png($code,$qrcode,"M",$qr_code_size,$qr_code_border);
    $path_2 = $qrcode;

    //  //先给他写死 
    //  $root_path = $_SERVER['DOCUMENT_ROOT'];
    // $path = explode("/",$root_path);
    // $bg_img=dirname(dirname(dirname(dirname(__FILE__)))).'/'.$path[3].'/statics/plugin/qrcode/bg.png';
    // $logo = dirname(dirname(dirname(dirname(__FILE__)))).'/'.$path[3].'/statics/plugin/qrcode/logo.jpg';//需要显示在二维码中的Logo图像
    
    $rootPath = $_SERVER['DOCUMENT_ROOT'];
    $bg_imgs = explode("uploads/codes",$bg_img)[1];
    $bg_img=$rootPath.'/statics/uploads/codes'.$bg_imgs;
    // uploads/codes
    $logos = explode("uploads",$logo)[1];
    $logo = $rootPath.'/statics/uploads' . $logos;//需要显示在二维码中的Logo图像
    $QR = $path_2;

    if ($logo !== FALSE) {
        $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
        $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
        $QR_width = imagesx ( $QR );
        $QR_height = imagesy ( $QR );
        $logo_width = imagesx ( $logo );
        $logo_height = imagesy ( $logo );
        $logo_qr_width = $QR_width / 4;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
    }
    imagepng ( $QR, $path_2 );//带Logo二维码的文件名
    // 图片二
    $path_1 = $bg_img;
    // 创建图片对象
    
    $image_1 = imagecreatefrompng($path_1);
    $image_2 = imagecreatefrompng($path_2);
    // 合成图片
    imagecopymerge($image_1, $image_2, $left_margin, $top_margin,$internal_x, $internal_y, imagesx($image_2), imagesy($image_2), 100);
    // 输出合成图片
    $rs=imagepng($image_1,$qrcode);

    imagedestroy($image_1);
    imagedestroy($image_2);

    // ob_clean();//在输出钱擦掉（清除）缓存

}

/**
 * 获取http
 * @param int $type 1.获取http 2.获取http和域名
 */
function getServerHttp($type = 1){
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    if ($type == 2) $http_type .= $_SERVER['SERVER_NAME'];

    return $http_type;
}
/**
 * 获取用户真实 ip
 * @return array|false|mixed|string
 */
function getClientIp(){
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    if (getenv('HTTP_X_REAL_IP')) {
        $ip = getenv('HTTP_X_REAL_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ip);
        $ip = $ips[0];
    } elseif (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = '0.0.0.0';
    }

    return $ip;
}

/**
 * 将字符串中的 &nbsp; 转为空格，用于前台商品标题等显示（多语言/富文本中常带 &nbsp; 导致显示为原文）
 */
function _strip_nbsp($str) {
    return str_replace('&nbsp;', ' ', (string)$str);
}

/**
 * @param $str 需要替换的文章
 * @param $search  需要替换的字符
 */
function replaceArticle($str ,$search  = "#%webName%#"){
    $systemData = System::load_sys_config('system');
    $str1 = '';
    switch ($search){
        case "#%webName%#"://网站名称
            $replace = $systemData['web_name'];
            $str1 = str_ireplace($search,$replace,$str);
            break;
        case "#%webUrl%#"://网站url
            $replace = $systemData['web_path'];
            $str1 = str_ireplace($search,$replace,$str);
            break;
    }
    return  $str1;
}

/**
 *  获取语言
 * $type 类型   1.修改文件   2.获取语言包
 * $data 修改是写入修改类容
 * $address 1.admin 后台  2.home 前台
 * $lan 用户选择的语言
 * @return string|array
 */
function languages($type, $data = '', $address = '',$lan= '')
{
    if (empty($type)) {
        return false;
    }
    $language = 'language.txt';
    switch ($type) {
        case 1:
            $dri = G_APP_PATH . 'system/funcs/';
            if (empty($data))
                return false;
            $res = file_put_contents($language, $data);
            if (!file_exists($dri . 'languages/' . $data . 'home.php')) {
                $file = fopen($dri . 'languages/' . $data . 'home.php', 'w');
                fclose($file);
            }
            if (!file_exists($dri . 'languages/' . $data . 'admin.php')) {
                $file = fopen($dri . 'languages/' . $data . 'admin.php', 'w');
                fclose($file);
            }
            break;
        case 2:
            if (empty($address))return false;
            if(empty($lan)) {
                // 获取默认语音
                $web_language = System::load_sys_config('system','web_language');
                $lan = $web_language;
            }

            $address == 1 && $addreUrl = $lan . 'admin';
            $address == 2 && $addreUrl = $lan . 'home';
            $address == 3 && $addreUrl = $lan . 'pc';
            if (empty($addreUrl))
                return false;
            $languageUrl = 'languages/' . $addreUrl . '.php';
            if(file_exists(G_SYSTEM.'funcs/'.$languageUrl)) {
                $res = include "$languageUrl";
            } else {
                return false;
            }
    }
    return $res;
}

/**
 * 获取语言列表--废弃
 * 
 */
function getLanguageList() {
    $directory = G_SYSTEM.'funcs/languages'; // 替换为你的目录路径
    // 获取指定目录下的所有文件名称
    $files = scandir($directory);
    // 过滤掉当前目录(.)、上级目录(..)和目录
    $files = array_diff($files, array('.', '..'));
    // 过滤掉文件夹
    $files = array_filter($files, function ($file) use ($directory) {
        return is_file("$directory/$file");
    });
    $arr = [];
    // 输出文件名
    foreach ($files as $file) {

        $arr[] = str_replace(".php","",str_replace("home.php","",str_replace("admin.php","",$file)));
        
    }
    
    return array_unique($arr);
}

/**
 * 下载图片
 * @param mixed $path  图标保存目录
 * @param mixed $url
 * @return mixed
 */
function downimg($path,$url){
    if ($url) {
        $dir = "statics/uploads/".$path.'/' . date('Ymd');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = md5($url);
        $fileName = $dir . '/' . $name. '.png';
        $img_url = $path.'/' . date('Ymd') . '/' . $name . '.png';
        $ch = curl_init($url);
        $fp = fopen($fileName, "wb");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $img_url;
    } else {
        return $url;
    }
}

/**
 * 返回sql
 * @param mixed $data  更新查询数据
 * @param mixed $table  表名
 * @param mixed $type   类型：in up 
 * @param mixed $where  条件 [[列名,[默认等于],条件],...]
 * @param mixed $orderby  排序 
 * @return string
 */
function Assemblydata($data=[],$table='',$type="in",$where=[],$orderby=[]){
    if(!$table)return false;
    switch ($type) {
        case "in":
        case "insert":
            $sql = "INSERT INTO `@#_$table` ";
            $key = "";
            $value = "";
            foreach($data as $k=>$v){
                if($key){ 
                    $key .=",";
                    $value .=",";
                }
                $key .= "`$k`";
                if($v === null){
                    $value .= 'null';
                }else{
                    $value .= " '$v' ";
                }
            }
            $sql .="($key) VALUES ($value)";
            break;

        case "up":
        case "update":
            $sql = "UPDATE `@#_$table` SET ";
            $d = '';
            foreach($data as $k=>$v){
                if($d){$d .= ",";}
                if($v===null){
                    $d .= "`$k` = null";
                }else{
                    $d .= "`$k` = '$v'";
                }
            }
            $sql .= $d;
            break;

        case "del":
        case "delete":

            break;
        
        case "sel":
        case "select":
            if($data){
                $fields =implode(",", $data);
            }else{
                $fields = "*";
            }
            $sql = "SELECT $fields FROM `@#_$table` ";
            break;
        }
        if($where){
            $w = '';
            foreach($where as $k=>$v){
                if(is_array($v) && count($v)==4){
                    $join = "or";
                }else{
                    $join = "and";
                }
                if($w){
                    $w .= " $join ";
                }
                if(is_array($v)){
                    $wk = $v[0];
                    $wh = "=";
                    if(count($v)>=3){
                        $wh = $v[1];
                        $wv = $v[2];
                    }
                    if(count($v)== 2){
                        $wv= $v[1];
                    }
                    if(is_array($wv)){
                        $wv = implode("','", $wv);
                        $w .=  "`$wk` $wh ('$wv')";
                    }else{
                        $w .=  "`$wk` $wh '$wv'";
                    }                  
                }elseif(is_numeric($k)){
                    $w .= "$v";
                }else{
                    $w .= "`$k` = '$v'";
                }
            }
            $sql .= " WHERE $w";
        }
    return $sql;
}

// 数组转树形结构
function buildTree(&$list,$pid = 0) {
    foreach ($list as $k => $v) {
        if($v['pid'] == $pid) {
            unset($list[$k]);
            $v['children'] = buildTree($list,$v['id'])?: [];
            $tree[] = $v;
        }
    }
    return $tree;
}

function convert($second,$format = ["天","小时","分钟"])
    {
        $newtime = '';
        $d = floor($second / (3600*24));
        $h = floor(($second % (3600*24)) / 3600);
        $m = floor((($second % (3600*24)) % 3600) / 60);
        if ($d>'0') {
            if ($h == '0' && $m == '0') {
                $newtime= $d.$format[0];
            } else {
                $newtime= $d.$format[0].$h.$format[1].$m.$format[2];
            }
        } else {
            if ($h!='0') {
                if ($m == '0') {
                    $newtime= $h.$format[1];
                } else {
                    $newtime= $h.$format[1].$m.$format[2];
                }
            } else {
                $newtime= $m.$format[2];
            }
        }
        return $newtime;
}   

/**
 * 格式化数据
 * @param mixed $data
 * @param mixed $formatArray 待格式化字段['money','price',...]
 * @param mixed $type   格式化类型，默认为price|time|times
 * @param mixed $symbol_left    格式化符号
 * @param mixed $symbol_right    格式化符号
 * @return array
 */
function data_format(&$data,$formatArray = [],$type='price',$symbol_left = '',$symbol_right='',$decimals=0){
    foreach($data?:[] as $key=>$vl){
        if(is_array($vl)){
            $res = data_format($vl,$formatArray,$type,$symbol_left,$symbol_right,$decimals);
            $data[$key] = $res;
        }else{
            switch($type){
                case "price":
                    if(in_array($key,$formatArray,true)){  
                        if(is_array($symbol_left)){
                            if(isset($data['language'])){
                                $vl = $symbol_left[$data['language']]['symbol_left'].number_format($vl,$decimals).$symbol_left[$data['language']]['symbol_right'];
                            }else{
                                $vl = number_format($vl,$decimals);
                            }
                        }else{
                            $vl = $symbol_left.number_format($vl,$decimals).$symbol_right;
                        }
                        $data[$key] = $vl;
                    }
                break;
                case "times":
                    if(in_array($key,$formatArray,true)){
                        $arr = explode(".",$vl);
                        $arr[0] = date("d/m/Y H:i:s",$arr[0]);
                        $vl = implode(".",$arr);
                    }
                break;

                case "time":
                    if(strpos($key,"time")!==false && $vl){
                        $arr = explode(" ",$vl);
                        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
                        if(isset($arr[0]) && preg_match($pattern, $arr[0])){
                            $arr[0] = date("d/m/Y",strtotime($arr[0]));
                            $vl = implode(" ",$arr);
                        }
                    }
                break;
            }
            $data[$key] = $vl;
        }
    }
    return $data;
}

/**
 * 返回随机字符串
 *
 * @param integer $len
 * @param integer $type 1:数字字母混合 2:纯字母 3:纯数字 4:大小写字母混合 5:大小写字母数字混合 6:大写字母数字混合
 * @return string
 */
function getRandomstr($len = 6,$type = 1){
    $str1 = "qwertyuiopasdfghjklzxcvbnm";
    $str2 = "QWERTYUIOPASDFGHJKLZXCVBNM";
    $str3 = "0123456789";
    switch($type){
        case 1:
            $str = $str1 . $str3;
        break;
        case 2:
            $str = $str1;
        break;
        case 3:
            $str = $str3;
        break;
        case 4:
            $str = $str1 . $str2;
        break;
        case 5:
            $str = $str1 . $str2 . $str3;
        break;
        case 6:
            $str = $str2 . $str3;
        break;
    }
    $randomString = '';
    for($i = 0;$i<$len;$i++){
        $index = rand(0, strlen($str) - 1);
        $randomString .= $str[$index];
    }
    return $randomString;
}

 
/**
 * utf8 反序列化
 * @param $serial_str
 * @return mixed
 */
function mb_unserialize($serial_str) {
    if(!is_string($serial_str) || trim($serial_str) == '') {
        return [];
    }
    // $serial_str= preg_replace('!s:(\d+):"(.*?)";!s', "'s:'.strlen('$2').':\"$2\";'", $serial_str);
    $serial_str = preg_replace_callback(
        '!s:(\d+):"(.*?)";!',
        function ($matches) {
            $length = strlen($matches[2]);
            return "s:{$length}:\"{$matches['2']}\";";
        },
        $serial_str
    );
    $serial_str= str_replace("\r", "", $serial_str);
    return @unserialize($serial_str);
}
 
/**
 * ascii 反序列化
 * @param $serial_str
 * @return mixed
 */
function asc_unserialize($serial_str) {
    // $serial_str = preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $serial_str);
    $serial_str = preg_replace_callback(
        '!s:(\d+):"(.*?)";!',
        function ($matches) {
            $length = strlen($matches[2]);
            return "s:{$length}:\"{$matches['2']}\";";
        },
        $serial_str
    );
    $serial_str= str_replace("\r", "", $serial_str);
    return @unserialize($serial_str);
}

function tubimg($src,$width,$height){
	$url=G_UPLOAD_PATH."/".$src;
	$size=getimagesize($url);
	$name=rand(10,99).substr(microtime(),2,6).substr(time(),4,6);
	$filetype=explode("/",$src);
	$img = imagecreatefromjpeg($url);		
	$dst = ImageCreateTrueColor( $width,$height);
	imagecopyresampled($dst,$img,0,0,0,0,$width,$height,$size[0],$size[1]);											
	imagejpeg($dst,"statics/uploads/".$filetype[0]."/".$filetype[1]."/".$name.".jpg");
	return $filetype[1]."/".$name.".jpg";
}

/**
 * 签名
 */
function sign($data,$key){
    ksort($data);
    $str = '';
    foreach($data as $k=>$v){
        $str .= $k.'='.$v.'&';
    }
    $str = rtrim($str,'&');
    $str = $str.$key;
    return md5($str);
}

/**
 * 获取时区
 * @return array
 */
function get_timezone(){
    //时区
    $timezones = DateTimeZone::listIdentifiers();
    $grouped = [];
    foreach ($timezones as $tz) {
        $parts = explode('/', $tz, 2);
        if (count($parts) === 2) {
            $group = $parts[0];
            $city = $parts[1];
            $grouped[$group][] = $city;
        }
    }
    ksort($grouped);
    return $grouped;
}

function getLanuage($field = ''){
    $language = _getcookie('language')?:"Chinese";
    $mysql_model=System::load_sys_class('model');
    $lan = $mysql_model->GetOne("select * from `@#_language` where `lg_value` = '{$language}' and type =2");
    if(!$lan) return [];
    $flag = explode('/',$lan['national_flag']);
    if(count($flag)<=1){
        $lan['national_flag'] = G_PLUGIN_PATH."/imgs/flag/".$lan['national_flag'];
    }elseif(count($flag)>1){
        $lan['national_flag'] = G_UPLOAD_PATH."/".$lan['national_flag'];
    }
    if($field){
        return isset($lan[$field])?$lan[$field]:'';
    }
    return $lan;
}

/**
 * 多语言下拉只显示括号内内容，如 "Simplified (简体)" -> "简体"
 * 支持半角()、全角（）以及末尾多出括号的情况（如 "Khmer (ភាសាខ្មែរ))"）
 * @param string $lg_name 语言名称
 * @return string
 */
function language_short_name($lg_name){
    if (!is_string($lg_name) || $lg_name === '') return $lg_name;
    // 匹配第一对括号（半角或全角）内的内容，不要求括号在末尾
    if (preg_match('/[(（]\s*([^)\）]*)\s*[)\）]/u', $lg_name, $m)) return trim($m[1]);
    return $lg_name;
}

/**
 * 获取语言
 * @param mixed $msg  语言key
 * @param mixed $def 默认值
 * @param mixed $type 1:移动端 2:pc
 */
function lan($msg,$def='',$type=2){

    // $table = ["language_value","language_value","language_value_backend"];
	$mysql_model=System::load_sys_class('model');
	$lan = $mysql_model->GetOne("select `value` from `@#_language_value` where `type` = $type and `key` = '{$msg}'");
    if(!$lan){
        $lan = $mysql_model->GetOne("select `value` from `@#_language_value` where `type` = $type and `value` like '%\"$msg\"%'");
    }
   System::load_sys_fun('redis_fun');
    $redisFun = new redisFun();
    $lan = $redisFun->getLgs($msg,$def='',$type=2);
	if($lan){
		$language = _getcookie('language')?:"Chinese";
		$value = mb_unserialize($lan['value']);
		$msg = $value[$language]['value'];
		return _unhtmtocode($msg);
	}
	return $def?:$msg;
}

function getWebHeader() {
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if ('HTTP_' == substr($key, 0, 5)) {
            $headers[str_replace('_', '-', substr($key, 5))] = $value;
        }
        if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
        } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH'];
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE'];
        }
    }
    return $headers;
}

/**
 * 获取对应语言时间格式
 * @param $time 时间戳
 * @param int $type 1年月日时分秒 2:年月日 3年月日时分
 */
function getLTF($time,$type = 1){
    $language = _getcookie('language')?:"Chinese";
    $web_time_format = _cfg("web_time_format");
    $web_time_format = mb_unserialize($web_time_format);
    // PHP 8.2 兼容：检查数组键是否存在
    $format = isset($web_time_format[$language]) ? $web_time_format[$language] : 'Y-m-d';
    
    // PHP 8.2 兼容：处理时间戳（整数）和日期字符串两种情况
    $timestamp = null;
    if(is_numeric($time)){
        // 如果是数字，直接作为时间戳
        $timestamp = intval($time);
    }elseif(is_string($time) && !empty($time)){
        // 如果是字符串，尝试解析
        $times = explode(" ",$time);
        $time_part = isset($times[0]) ? $times[0] : $time;
        // 尝试将字符串转换为时间戳
        $timestamp = is_numeric($time_part) ? intval($time_part) : strtotime($time_part);
        if($timestamp === false){
            $timestamp = time(); // 如果解析失败，使用当前时间
        }
    }else{
        $timestamp = time(); // 默认使用当前时间
    }
    
    if($type == 1){
        return date($format." H:i:s",$timestamp);
    }elseif($type == 2){
        return date($format,$timestamp);
    }elseif($type == 3){   
        return date($format." H:i",$timestamp);
    }
    return date($format." H:i:s",$timestamp);
}

/**
 * 获取对应语言的服务器时间
 * @param int $type 1:时间戳 2:时间格式
 * @param string $format 时间格式
 * 
 */
function getLanTime($type = 1, $format = ''){
    $language = _getcookie('language')?:"Chinese";
    $date = '';
    $db = System::load_sys_class('model');
    $lg = $db->GetOne("select * from `@#_language` where lg_value = '{$language}'");
    date_default_timezone_set($lg['time_zone']);
    if($type == 2){
        if(!$format){
            $web_time_format = _cfg("web_time_format");
            $web_time_format = mb_unserialize($web_time_format);
            $format = $web_time_format[$language];
            $date = date($format." H:i:s");
        }else{
            $date = date($format);
        }
    }else{
        $date = time();
    }
    return $date;
}

/**
 * PHP 8.2 兼容：安全的 unserialize 函数
 * 替代 @unserialize，使用 try-catch 处理错误
 */
function safe_unserialize($str, array $options = []) {
    if (empty($str) || !is_string($str)) {
        return false;
    }
    
    try {
        $result = unserialize($str, $options);
        if ($result === false && $str !== serialize(false)) {
            return false;
        }
        return $result;
    } catch (Throwable $e) {
        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * 获取请求参数
 * @param string|array $params 参数
 * @param mixed $default 默认
 * @return string|array
 */
function getParams($params, $default = ''){
    if(is_array($params)){
        $data = [];
        foreach($params as $v){
            $a = $_GET[$v]??$_POST[$v]??null;
            $a = $a?:$default;
            if(is_array($a)){
                foreach($a as $k=>&$v){
                    $v = _htmtocode(trim($v));
                }
            }else{
                $a = _htmtocode(trim($a));
            }
            $data[$v] = $a;
        }
    }else{
        $a = $_GET[$params]??$_POST[$params]??null;
        $a = $a?:$default;
        if(is_array($a)){
            foreach($a as $k=>&$v){
                $v = _htmtocode(trim($v));
            }
        }else{
            $a = _htmtocode(trim($a));
        }
        $data = $a;
    }
    return $data;
}