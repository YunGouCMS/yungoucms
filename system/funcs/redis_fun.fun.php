<?php

/**
 * 缓存调用
 */
System::load_sys_fun("/constant/redis_key");
class redisFun extends redis_key
{
    public $redis;//redis对象
    private $language = "Chinese";//当前语言

    public function __construct(){
        if(!$this->redis){
            System::load_sys_fun('redisData');
            $this->redis = new redisData();
        }
    }

    /**
     * 获取多语言缓存
     * @param mixed $msg  语言key
     * @param mixed $def 默认值
     * @param mixed $type 1:移动端 2:pc
     */
    public function getLgs($msg,$def='',$type=2){
        $typeArray = [
            1=>'mobile',
            2=>'pc'
        ]; 
//        $ty = "redis";
        $lg = $this->redis->redisHget(redis_key::LANGUAGE .$this->language. $typeArray[$type],$msg);
        $mysqlMsg = $msg;
        if (!empty($lg) && $lg['code']==1 && $lg['data'] == false){
            $msg = md5($msg);
            $lg = $this->redis->redisHget(redis_key::LANGUAGEZN .$this->language. $typeArray[$type],$msg);
        }

        if(!empty($lg) && $lg['code']==1 && $lg['data'] != false){
            $lg['value'] = $lg['data'];
        }
        if (!empty($lg) && $lg['code']==1 && $lg['data'] == false){
            $mysql_model=System::load_sys_class('model');
            $isZn = "key";
            $lg = $mysql_model->GetOne("select `value` from `@#_language_value` where `type` = $type and `key` = '{$mysqlMsg}'");

            if(!$lg){

                $isZn = "zn";
                $lg = $mysql_model->GetOne("select `value` from `@#_language_value` where `type` = $type and `value` like '%\"$mysqlMsg\"%'");
            }

            if($isZn == "key"){
                $this->redis->redisHset(redis_key::LANGUAGE . $typeArray[$type],$mysqlMsg,$lg['value']);
            }
            if($isZn == "zn"){
                $this->redis->redisHset(redis_key::LANGUAGEZN . $typeArray[$type],$msg,$lg['value']);
            }
//            $ty = "mysql";
        }
 /*       save_log("=====获取方式====");
        save_log($ty);*/
        return $lg;
    }

    /**
     * 设置当前语言
     * @param mixed $language  语言key
     */
    public function setLgs($language = "Chinese"){
        $this->language = $language;
    }
}