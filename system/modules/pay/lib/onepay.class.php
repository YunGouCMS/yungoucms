<?php
include "onepay/OnePayCore.class.php";

class onepay{
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
		return $this->getCode();

	}
    private function getCode(){
        try{
            $a = new OnePayCore();
            $res = $a->getQr($this->config['money'],"",$this->config['code'],"","");
        }catch(Exception $e){
            echo ($e->getMessage());
            exit;
        }
        echo $res;
        $data['pay_status_code'] = 1;
        return $data;
    }
}