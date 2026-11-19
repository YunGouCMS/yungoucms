<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_app_class('returnData','app','no');
class language extends base
{
	public $jsonData;
	

	public function __construct()
	{
		parent::__construct();
		$this->db = System::load_sys_class('model');
		$this->jsonData = new returnData();
		$this->jsonData->__set('type', 3);
        // $this->nowUserLanguage = isset($this->getHeader()['LANGUAGE'])&& !empty($this->getHeader()['LANGUAGE'])?$this->getHeader()['LANGUAGE']:System::load_sys_config('system','web_language');
        if(isset($this->getHeader()['LANGUAGE'])&& !empty($this->getHeader()['LANGUAGE'])){
            $this->nowUserLanguage = $this->getHeader()['LANGUAGE'];
        }
        if(!$this->nowUserLanguage){
            $this->nowUserLanguage = _getcookie('language')?:System::load_sys_config('system','web_language');
        }
        _setcookie('language', $this->nowUserLanguage?:"Chinese", 3600);
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

    /**
     * 获取语言
     * @param $msg
     * @return 
     */
    public function getLanMsg(){
        $msg = trim($_POST['msg']);
        if(empty($msg)){
            $this->jsonData->success(200,'','');   
        }
        $lan = lan($msg);
        $this->jsonData->success(200,'',$lan);
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
            $rq = $this->db->GetOne("select * from `@#_language_value` where `key`='$rqs' and type = 2");
            $lg = $this->db->GetList("select * from `@#_language_value` where (`key`='$rqs' or `pid`=".$rq['id'].") and type = 2");
        }else{
			$this->jsonData->error(500, '参数错误');
            $lg = $this->db->GetList("select * from `@#_language_value` where type = 2");
        }
        if($lg) {
            $lg = $this->buildArray($lg);
            $lg = empty($rqs) ? $lg :  $lg[$rqs];
        }else{
            $lg = '';
        }
        if($_POST['languageType']){
            // $lg = [
            //     "Quick_Payment"=> "Quick Payment - Information about the product you are about to purchase",
            //     "Purchase_goods"=> "Purchase goods",
            //     "Purchase_Quantity"=> "Purchase Quantity",
            //     "person-time"=> "person-time",
            //     "tips"=> "The more people purchase, the greater the chance of getting it!",
            //     "total"=> "total",
            //     "account_balance"=> "Your account balance",
            //     "top-up"=> "top-up",
            //     "Pay_Now"=> "Pay Now"
            // ];
            $this->jsonData->success(200, '', $lg);
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

	//用户号语言
	public function nowUserLanguage()
	{
        // $this->jsonData->success(200);
		$lg = $this->languages();
		if ($lg) {
			if (is_array($lg)) {
				foreach ($lg as &$vl) {
					$vl = _unhtmtocode($vl);
				}
			} else {
				$lg = _unhtmtocode($lg);
			}
			$this->jsonData->success(200, '', $lg, true);
		} else {
			$this->jsonData->errCode(500, '');
		}
	}

	//语言选择
	public function languagSelection()
	{
		$lge = $this->db->GetLIst("SELECT * FROM `@#_language`WHERE status = 1 and type = 1 order by sort desc");
		$lgls = [];
		foreach ($lge as $k => $v) {
			$lgls[$k]['value'] = $v['lg_value'];
			$lgls[$k]['text'] = $v['lg_name'];
			$lgls[$k]['flag_status'] = $v['flag_status'];
			if ($v['flag_status'] == 1) {
				$flag = explode('/', $v['national_flag']);
				if (count($flag) <= 1) {
					$lgls[$k]['national_flag'] = G_PLUGIN_PATH . "/imgs/flag/" . $v['national_flag'];
				} elseif (count($flag) > 1) {
					$lgls[$k]['national_flag'] = G_UPLOAD_PATH . "/" . $v['national_flag'];
				}
			} else {
				$lgls[$k]['national_flag'] = '';
			}
		}

		$this->jsonData->successCode('', '', $lgls);
	}

    /**
     * 切换语言
     */
    public function languageChange(){
        $language = isset($_POST['language']) ? $_POST['language'] : '';
        if(empty($language)){
            $this->jsonData->error(500, '参数错误');
        }
        _setcookie('language', $language, 3600);
        $this->jsonData->success(200, '切换成功');
    }
}
