<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class("Jwt","adminMobile","no");
System::load_app_class('returnData','adminMobile','no');
System::load_app_class('validation','adminMobile','no');
System::load_sys_fun('send');
class base extends SystemAction{
	
    public $jsonData;
	public $jwt;
	public $redis;
	public $db;
    public $uId;
    public $request;
    public $config;
    public $pagenum = 20;

	public function __construct(){
        $this->db = System::load_sys_class('model');
        $this->config = System::load_sys_config('config');
		$this->jsonData = new returnData();
		$this->jwt = new Jwt;
        if(!$this->redis){
			System::load_sys_fun('redisData');
			$this->redis = new redisData();
		}
        //是否需要登录权限
        $isType = $this->isLogin();
        if ($isType){
//        	$code = $this->jsonData->successCode('0','获取');
            $this->go_handle();
            return true;
        }
        //获取token
        $token = $this->authentication();
        $this->go_handle();
	}
    private function go_handle(){
        // PHP 转发
        $url = explode("?",$_SERVER['REQUEST_URI']);
        if(!isset($url[1])){
            //跳转到404
            $this->jsonData->error(404,'请求地址不存在');
        }
        $con = [
            "/login/login","/index/tdefaultData","/index/statistics",

            "/user/messageLists","/user/channelLists",'/user/offlineRechargeLists','/user/offlineRecharge',

            "/order/recover",

            "/record/money","/record/rechargeRecord",

            "/robot/runSet",
        ];
        $uri = str_replace("/appadmin","",$url[1]);
        if(in_array(str_replace("/adminMobile","",$uri),$con)){
            return true;
        }
        // 确保目标路径正确（调试用）
        $targetUrl = "http://localhost:19002" . $url[1];
        // echo "DEBUG: Forwarding to: $targetUrl\n"; // 调试输出
        $data = $_POST;
        $data['admin_uid'] = $this->uId;
        $data['WEB_PATH'] = WEB_PATH;
        $header =[
            "Language:".$this->getHeader()['LANGUAGE'],
        ];
        $res = curlPost($targetUrl, $data,2,$header);
        $tem = json_decode($res,true);
        if(isset($tem["data"]['WriteFile'])){
            $this->redis->redisSetex("WriteFile",60,$tem["data"]['WriteFile']);
            unset($tem["data"]['WriteFile']);
            $res = json_encode($tem);
            echo $res;
        }else{
            echo $res;
            exit;
        }
        // if($res){
        //     exit;
        // }
    }

    //登录判断
    private function isLogin(){
        $routing = System::load_app_fun("routing",'adminMobile');
        //权限判断
        $route_c = $routing[ROUTE_C];
        if (is_array($route_c)){
            if(count($route_c) == 0){
                return true;
            }
            if(count($route_c) > 0 && in_array(ROUTE_A,$route_c)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*判断是否登录*/
    private function authentication()
    {
    	$get = $this->getHeader();
        $token =$get['TOKEN'];
    	if(empty($token)){
    	    $this->jsonData->error(600,'请登录后操作');
        }
        $payload =  $this->jwt->verifyToken($token);
        if($payload){
            return $this->uId = $payload['data']['userid'];
        }else{
            $this->jsonData->error(601,'登录已过期请重新登录');
        }
    }

    /**
     * 获取请求头
     * @return array
     */
    public function getHeader() {
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

    /*赋值Uid 获取用户信息*/
    public function setUid($uid){
       return  $this->uId = $uid;
    }

    /**
     * 接口参数验证
     * @param string $data //验证数据
     * @param int $type //验证类型 1 数字 2 字符串
     * @param $length // 字符串验证长度
     * @param $isChinese //  字符串 验证 1 中文 2 纯英文
     * @return array|int[]
     */
    public function requestFiltering($data = '',$type = 1,$length = 0,$isChinese = 1)
    {
        //判断验证字段是否存在
        if (empty($data)) return ['code'=>3];
        $validation = new validation();

        //判读是否是数字验证
        switch ($type){
            case 1:
               $res = $validation->intValidation($data);
               break;
            case 2:
                $res = $validation->stringValibation($data,$length,$isChinese);
                break;
        }
        return $res;
    }

    public function verifyExamine($password){
        $AdminInfo = $this->db->GetOne("select * from `@#_admin` where `uid` = '$this->uId'");
        if(md5($password) == $AdminInfo['secure_password']){
			return true;
		}else{
			return false;
		}
    }

    public function getAdminInfo(){
        return $this->db->GetOne("select * from `@#_admin` where `uid` = '$this->uId'");
    }

    public function getConfig($name){
		if(!is_array($name)){
			$res = $this->db->GetOne("select * from `@#_config` where `name` = '$name'");
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
}
?>