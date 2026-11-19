<?php

/**
 * 返回前端数据
 */
System::load_app_class("codeReturn",'app','no');
class returnData extends codeReturn
{
    public $time_format;
    private $time_zone = '';
    private $language = "Chinese";

    public $type = 1; //1app,2后端，3pc
    public $language_backend = 'Chinese'; //1app,2后端，3pc

    // 需要变更的价格key
    public $moneyKey = ['money','yugoujia','yunjiage','price','preferential_price','yongjin','dongjie_money','shouru_money','zhichu_money','totalMoney','fufen_yongjin','ygmoney','recovery'];

    // 判断是否使用小数点
    public $isDecimal = true;

    public function __set($name, $value){
        $this->$name = $value;
    }

    public function setLanguage($lg,$time_zone = ''){
        $this->language = $lg;
        $time_format = system::load_sys_config("system","web_time_format");
        $time_format = @unserialize($time_format);
        $this->time_zone = $time_zone;
        if($time_zone){// 如果有时区就设置时区
            date_default_timezone_set($time_zone);
        }  
        $this->time_format = $time_format[$this->language]?:"d/m/Y";
    }
    /**
     * 成功  return data返回 []
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @param $type int 返回类型 1 array 2 json
     * @return void
     */
    public function successCode($code = null,$msg = 'ok',$data = [],$type = 2){
        $this->success($code,$msg,$data);
        exit;
        if(empty($code)) $code = codeReturn::SUCCESS;
        if(empty($msg)) $msg = 'ok';
        if ($type == 1){
            return ['code' => $code,'msg'=>$msg,'data'=>$data];

        }
        if ($type == 2){
            echo json_encode(['code' => $code,'msg'=>$msg,'data'=>$data]);
            exit();
        }
    }

    /**
     * 失败 return data返回 []
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @param $type int 返回类型 1 array 2 json
     * @return void
     */
    public function errCode($code = null,$msg = 'error',$data = [],$type = 2){
        if(empty($code)) $code = codeReturn::ERR_SERVER;
        if(empty($msg)) $msg = 'error';
        if(is_string($msg)){
            $msg = _unhtmtocode($msg);
        }
        if ($type == 1){
            return ['code' => $code,'msg'=>$msg,'data'=>$data];

        }
        if ($type == 2){
            echo  json_encode(['code' => $code,'msg'=>$msg,'data'=>$data]);
            exit();
        }
    }

    /**
     * 失败 return data返回 {}
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @return void
     */
    public function error($code = null,$msg = 'error',$data =[]){
        if(empty($code)) $code = codeReturn::ERR_SERVER;
        if(empty($msg)) $msg = 'error';
        $data = (object)$data;
        if(is_string($msg)){
            $msg = _unhtmtocode($msg);
        }
        $msg = $this->getLan($msg);
        echo json_encode(['code' => $code,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 成功 return data返回 {}
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @return void
     */
    public function success($code = null,$msg = 'ok',$data = [],$format=false){
        if(empty($code)) $code = codeReturn::SUCCESS;
        if(empty($msg)) $msg = 'ok';
        $msg = _unhtmtocode($msg);
        $msg = $this->getLan($msg);
        if(!is_array($data)){
            $data = _unhtmtocode($data);
            $data = (object)$data;
        }elseif(!$format){
            $this->price_format($data);
        }
        echo json_encode(['code' => $code,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function price_format(&$data){
        // 判断是否启用小数
        if ($this->isDecimal) return $data;

        foreach($data as $key=>&$vl){
            if(is_array($vl)){
                $this->price_format($vl);
            }else{
                $vl = _unhtmtocode($vl);
                if(in_array($key,$this->moneyKey,true)){
                    $vl = number_format($vl);
                }
                if(strpos($key,"time")!==false && $vl){
                    $arr = explode(" ",$vl);
                    $pattern = '/^\d{4}-\d{2}-\d{2}$/';
                    if(isset($arr[0]) && preg_match($pattern, $arr[0])){
                        // $arr[0] = date("d/m/Y",strtotime($arr[0]));
                        $arr[0] = date($this->time_format,strtotime($arr[0]));
                        $vl = implode(" ",$arr);
                    }
                }
            }
        }
    }

    private function getLan($msg=''){
        if(empty($msg)){
            return $msg;
        }
        $db = System::load_sys_class('model');
        
        switch($this->type){
            case 1:
            case 3:
                $type = $this->type==1?1:2;
                $language = $this->language;
                $lan = $db->GetOne("select `value` from `@#_language_value` where `type` = {$type} and `value` like '%\"$msg\"%'");
                break;
            case 2:
                $language = $this->language_backend;
                $lan = $db->GetOne("select `value` from `@#_language_value_backend` where  `value` like '%\"$msg\"%'");
        }
        if($lan){
            $value = mb_unserialize($lan['value']);
            $msg = $value[$language]['value'];
            return _unhtmtocode($msg);
        }
        return $msg;
    }
}