<?php


/**
 *      [YunGouCMS] (C)2011-2024 Tao - Long Inc.    111
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.php  2024-05-30 05:34:47Z 韬龙网络 $
 */


/*
*---------------------------------------------------------------
* 静态资源直连：避免 /statics/ 被路由到 PHP 导致返回 HTML（jQuery 等报 Invalid token）
*---------------------------------------------------------------
*/
$req_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$uri_path = $req_uri !== '' ? rawurldecode(parse_url($req_uri, PHP_URL_PATH) ?: '') : '';
if ($uri_path !== '' && strpos($uri_path, '/statics/') === 0) {
	$static_file = __DIR__ . DIRECTORY_SEPARATOR . ltrim(str_replace(['/','\\'], DIRECTORY_SEPARATOR, $uri_path), DIRECTORY_SEPARATOR);
	if (is_file($static_file)) {
		$ext = strtolower(pathinfo($static_file, PATHINFO_EXTENSION));
		$mimes = array('js'=>'application/javascript', 'css'=>'text/css', 'png'=>'image/png', 'jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg', 'gif'=>'image/gif', 'ico'=>'image/x-icon', 'woff'=>'font/woff', 'woff2'=>'font/woff2', 'ttf'=>'font/ttf', 'svg'=>'image/svg+xml', 'eot'=>'application/vnd.ms-fontobject');
		if (isset($mimes[$ext])) header('Content-Type: ' . $mimes[$ext]);
		header('Content-Length: ' . filesize($static_file));
		readfile($static_file);
		exit;
	}
}
/*
*---------------------------------------------------------------
* SYSTEM BAN BEN TYPE
*---------------------------------------------------------------
*/
if (strpos($req_uri, '/api/checkcode/image') === false) {
	header('Content-Type: text/html;charset=utf-8');
}
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Access-Control-Allow-Headers:Accept,Referer,User-Agent,X-Requested-With,Content-Type,token");


define('G_BANBEN_TYPE',"9aabCQkBVlQABwcEU1wDD1NWUVcCClBaAwcAC1GK3fvfn5besLlKgbis04z5gr+wSoSF3Nmz09657BTWi+KC263Wu7EV19Pv2Jii0+rt");

/*
*---------------------------------------------------------------
* SYSTEM FOLDER NAME
*---------------------------------------------------------------
*/
$system_path = 'system';

/*
*---------------------------------------------------------------
* STATICS FOLDER NAME
*---------------------------------------------------------------
*/
$statics_path = 'statics';


/*
*---------------------------------------------------------------
* APP START PATH
*---------------------------------------------------------------
*/
define('G_APP_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);


/*
 * --------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------
 */
include  G_APP_PATH.$system_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'global.php';



/*
 * --------------------------------------------------------------
 * APP START
 * --------------------------------------------------------------
 */

System::CreateApp();

?>
