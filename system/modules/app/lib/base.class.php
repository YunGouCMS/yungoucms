<?php 
header('Content-Type: application/json;charset=utf-8');
header("Access-Control-Allow-Origin:*");
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class("Jwt","app","no");
System::load_app_class('returnData','app','no');
System::load_app_class('validation','app','no');
System::load_sys_fun('redisData');
System::load_sys_fun('send');
System::load_app_class('notice',"common","no");
class base extends SystemAction{
	public $jsonData;
	public $jwt;
	public $db;
    public $uId;
    public $request;
    public $nowUserLanguage;
    public $lgs;
    public $time_zone;

    public $redis;

    public $currency;

    public $where_language = "";
	public function __construct(){
        //获取当前默认语言
        $this->db = System::load_sys_class('model');
        $this->redis = new redisData();
        $this->nowUserLanguage = isset($this->getHeader()['LANGUAGE'])&& !empty($this->getHeader()['LANGUAGE'])?$this->getHeader()['LANGUAGE']:System::load_sys_config('system','web_language');
        if($this->userInfo() && $this->userInfo()['language']){
            $this->nowUserLanguage = $this->userInfo()['language'];
        }
        $this->where_language = " and (language = '{$this->nowUserLanguage}' or language = ''  or language = null) ";
        $this->lgs = $this->languages('app_return');
        /* if((!isset($this->getHeader()['LANGUAGE']) || empty($this->getHeader()['LANGUAGE'])) && (!$this->userInfo() || !$this->userInfo()['language'])){
            
            $this->setlg();
        } */
        $this->getLanguageCurrency();
		$this->jsonData = new returnData();
        $lg = $this->db->GetOne("select * from `@#_language` where lg_value = '{$this->nowUserLanguage}'");
        $this->jsonData->setLanguage($this->nowUserLanguage,$lg['time_zone']);
        $this->time_zone = $lg['time_zone'];
		$this->jwt = new Jwt;
        $this->lanExist();
        $isType = $this->isLogin();
        $get = $this->getHeader();
        $token = isset($get['TOKEN'])?$get['TOKEN']:"";
        if($token) {
            $payload =  $this->jwt->verifyToken($token);
            if($payload){
                $this->uId = $payload['data']['userid'];
                $this->isBan();
                //用户在线时间
                $time = time();
                $res = $this->db->GetOne("select * from `@#_member_online` where uid = {$this->uId} and  $time - end_time <=300");
                if($res){
                    if($time - $res['end_time'] >60){
                        
                        $sql = "update `@#_member_online` set end_time = $time  where id = ".$res['id'];
                        $this->db->Query($sql);
                    }
                }else{
                    $sql = Assemblydata(['uid'=>$this->uId,"start_time"=>$time,"end_time"=>$time],"member_online");
                    $this->db->Query($sql);
                }
                $user = $this->db->getOne("select uid,language from `@#_member` where language is null and uid = ".$this->uId);
                if($user){
                    $this->db->getOne("update `@#_member` set language = '$this->nowUserLanguage' where uid = ".$this->uId);
                }
            }elseif($isType){
                $this->authentication();
            }
        }elseif($isType){
            $this->authentication();
        }
	}

    /**
	 * 判断语言是否存在，没有返回默认语言
	 * 
	 **/
	private function lanExist(){
        $lans = $this->redis->redisGet("web_language");
        if(!$lans['data']){
            $lans = $this->db->GetList("select lg_value from `@#_language`");
            $lans = array_column($lans,'lg_value');
            $this->redis->redisSet("web_language",json_encode($lans));
        }else{
            $lans = json_decode($lans['data'],true);
        }
        if(in_array($this->nowUserLanguage,$lans)){
            return $this->nowUserLanguage;
        }else{
            $this->nowUserLanguage = System::load_sys_config('system','web_language');
        }
     }

    /**
     * 是否封禁
     *
     * @return bool
     */
    public function isBan(){
        if(!$this->uId){
            return true;
        }
        $user = $this->db->getOne("select * from `@#_member_ban` where uid = ".$this->uId);
        if($user && $user['end_time'] > time()){
            $data = [
                "desc"=>$user['desc'],
                "start_time"=>$user['start_time'],
                "end_time"=>$user['end_time']
            ];
            data_format($data,['start_time','end_time'],"time");
            $this->jsonData->error(601, $this->lgs['is_ban']?:"您的账号已被封禁",$data);
        } elseif($user && $user['end_time'] < time()) {
            //解封
            $this->db->Query("delete from `@#_member_ban` where uid = ".$this->uId);
        }
        return false;
    }
    /**
     * 自动选择语言
     * @return void
     */
    private function setlg(){
        $region = _get_ip_dizhi();
        $lg = $this->db->getOne("SELECT * FROM `@#_language` WHERE `notes` LIKE '%$region%'");
        if($lg){
            $this->nowUserLanguage = $lg["lg_value"];
        }else{
            $region = _get_ip_region(0);
            $lg1 = $this->db->getOne("SELECT * FROM `@#_language` WHERE `notes` LIKE '%$region%'");
            if($lg1){
                $this->nowUserLanguage = $lg1["lg_value"];
            }
        }
    }

    /**
     * 返回对应语言
     * @param mixed $val
     * @param mixed $key
     * @return string
     */
    public function getLgMsg($val="",$key="app_return",$rep=''){
        if(!$val){
            return "error";
        }
        $lgs = $this->languages($key);
        if(isset($lgs[$val])){
            return $lgs[$val];
        }
        return "error";
    }
    //登录判断
    private function isLogin(){
        $routing = System::load_app_fun("routing",'app');
        //权限判断
        $route_c = $routing[ROUTE_C]?:[];
        $m = explode("&",ROUTE_A);
        $route_a = $m[0];
        if (is_array($route_c)){
            if(count($route_c) == 0){
                return true;
            }
            // if(count($route_c) > 0 && in_array(ROUTE_A,$route_c)){
            if(count($route_c) > 0 && in_array($route_a,$route_c)){
                return false;
            }else{
                return true;
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
            // 请登录后操作
    	    $this->jsonData->error(600, $this->lgs['please_log_operate']);
        }
        $payload =  $this->jwt->verifyToken($token);
        if($payload){
            return $this->uId = $payload['data']['userid'];
        }else{
            // 登录已过期请重新登录
            $this->jsonData->error(601, $this->lgs['log_time_out']);
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

    /*获取用户信息*/
    /**
     * @return array UserInfo
     * $type 1 默认获取少数信息
     */
    public function userInfo($type = 1)
    {
        if(!$this->uId){
            return [];
        }
        $uid = $this->uId;

        if($type == 1){
            $sql = "SELECT `username`,`v_id`,`uid`,`auto_user`,`email`,`mobile`,`img`,`money`,`time`,`headimg`,`score`,`language`,`level` FROM `go_member` WHERE (`uid` = '$uid' or `v_id` = '$uid')";
        }else{
            $sql = "SELECT * FROM `go_member` WHERE (`uid` = '$uid' or `v_id` = '$uid')";

        }
        $res = $this->db->GetOne($sql);
        if(!$res){
            return [];
        }
        if(!isset($res['v_id'])){
            do{
                $v_id = rand(1000000,1000000000);
                $s = $this->db->GetOne("select * from `go_member` where v_id = $v_id");
                if(!$s){
                    break;
                }
            }while($s);
            $this->db->Query("update `@#_member` set v_id = '$v_id' where `uid` = '$uid'");
            $res['v_id'] = "$v_id";
        }
        /*获取佣金 可退余额*/
        $yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid=".$uid."");
        if(!$yongjin_info){
            $this->db->Query("INSERT INTO `@#_member_commission` (`uid`) VALUES ('$uid')");
            $yongjin = 0;
        }else{
            $yongjin = $yongjin_info['total_money'];
        }
        $res['yongjin'] = sprintf("%01.2f",$yongjin);
        $res['dongjie_money'] = sprintf("%01.2f",$yongjin_info['dongjie_money']);

        $res['shouru_money'] = $yongjin_info['shouru_money'];
        $res['zhichu_money'] = $yongjin_info['zhichu_money'];
        $res['zhichu_money'] = $yongjin_info['zhichu_money'];
        unset($res['auto_user']);
        $res['img'] = G_UPLOAD_PATH.'/'.$res['img'];
        // $res['img'] = G_WEB_PATH.'/'.$res['img'];
        $res['mobile'] = substr_replace($res['mobile'], '****', 3, 4);
        //ip信息
        $ipinfo = $this->db->getOne("SELECT * FROM `@#_member_ip` WHERE `uid`=$uid");
        // $res['ip'] = $ipinfo['ip'];
        $res['ip_region'] = $ipinfo['ip_region'];
        // $res['last_ip'] = $ipinfo['last_ip'];
        $res['last_ip_region'] = $ipinfo['last_ip_region'];
        return  $res;
    }

    /**
     * 获取用户返佣比例
     */
    public function getRakeBack(){
        //获取对应等级的返佣比例
        $user_fufen = System::load_app_config("user_fufen", '', 'member');
        $reke = $user_fufen['fufen_yongjin'];
        $uid = $this->uId;
        $sql = "SELECT uid,groupid FROM `go_member` WHERE `uid` = '$uid'";
        $res = $this->db->GetOne($sql);
        if(!$res){
            return $reke[0]??0;
        }
        $groupid = $res['groupid'];
        $group = $this->db->GetOne("select * from `go_member_group` where `groupid` = $groupid");
        if(!$group || $group['rake_back']<=0){
            return $reke[0]??0;
        }
        return $group['rake_back'];
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

    /**
     * 获取黑名单
     * @param $mobile
     * @param $uid
     * @param $type int 类型 ''1''''禁止聊天'',''2''''禁止发送短信'',''4''''禁止登录'',''8'' ''禁止购买'',''16'' ''禁止充值'',
     * @return void
     */
    public function memberBlacklist($mobile,$uid = '',$type) {
        $where = " `type`= $type and ";
        if (empty($uid)){
            $where .= "`mobile` = $mobile";
        }else{
            $where .= "`uid` = $uid";
        }
        return $this->db->GetOne("select * from `go_member_blacklist` WHERE $where");
    }

    /**
     * 用户号语言
     * @param $rqs  语言数组选择
     * @return string
     */
    public function languages($rqs = ''){
        !empty($rqs) ?: $rqs = $_POST['languageType'];
        // $lg = languages(2, '', 2, $this->nowUserLanguage);
        if($rqs){
            $rq = $this->db->GetOne("select * from `@#_language_value` where `key`='$rqs' and type = 1");
            $lg = $this->db->GetList("select * from `@#_language_value` where (`key`='$rqs' or `pid`=".$rq['id'].") and type = 1");
        }else{
            $lg = $this->db->GetList("select * from `@#_language_value` where type = 1");
        }
        if($lg) {
            $lg = $this->buildArray($lg);
            $lg = empty($rqs) ? $lg :  $lg[$rqs];
        }
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
                    // $tree[$element['key']] = @unserialize($element['value'])[$this->nowUserLanguage]['value'];
                    $tree[$element['key']] = mb_unserialize($element['value'])[$this->nowUserLanguage]['value'];
                }
            }
        }
        return $tree;
    }

    /**
     * 返回用户的上下级id：上一级，下级无线
     * @return mixed
     */
    public function friendIds($type = 1){
        $id = $this->uId;
        //上级
        $ids = $this->userInfo(2)['yaoqing'];
        //查询下级
        do{
            $ch = $this->db->GetOne("select uid,yaoqing from `@#_member` where `yaoqing` = $id");
            if($ch){
                $id = $ch['uid'];
                $ids .= ",$id";
            }
        }while($ch);
        if($type == 2){
            return explode(",",$ids);
        }
        return $ids;
    }

    //货币单位
    final private function getLanguageCurrency(){
		$language = $this->nowUserLanguage;
		/* $currency = $this->db->GetOne("select * from `@#_currency` where `language` = '$language'");
		if($currency){
			$currency['symbol'] = $currency['symbol_left']?:$currency['symbol_right'];
		} */
		$currency = $this->db->GetOne("select * from `@#_language` where `lg_value` = '$language'");
		if($currency){
			if($language == "Vietnamese"){
				$currency['symbol_left'] = '';
				$currency['symbol_right'] = $currency['currency_unit'];
			}else{
				$currency['symbol_left'] = $currency['currency_unit'];
				$currency['symbol_right'] = '';
			}
			$currency['symbol'] = $currency['currency_unit'];
		}
		$this->currency = $currency?:["title"=>'','unit'=>'',"symbol_left"=>'',"symbol_right"=>'',"symbol"=>''];
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

    /**
     * 判断手机号区号
     * @param string $phone 手机号
     * @return string
     */
    public function getPhoneAreaCode($phone){
        //依次获取前1位2位3位4位
        $area[] = substr($phone,0,1);
        $area[] = substr($phone,0,2);
        $area[] = substr($phone,0,3);
        $area[] = substr($phone,0,4);
        //判断区号
        $area_codes = $this->redis->redisGet('area_codes');
        if($area_codes['data']){
            $area_codes = json_decode($area_codes['data'],true);
        }else{
            $res = $this->db->GetList("select area_code from `@#_language`");
            $area_codes = array_column($res,"area_code");
            $area_codes = array_unique($area_codes);
            $area_codes = array_values($area_codes);
            $this->redis->redisSet('area_codes',json_encode($area_codes),86400);
        }
        foreach($area as $vl){
            if(in_array($vl,$area_codes)){
                return $vl;
            }
        }
        return '';
    }
}
?>