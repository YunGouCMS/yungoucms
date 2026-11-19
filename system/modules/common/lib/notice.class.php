<?php

class notice{
    private $db;
    private $state = [
        "1"=>["name"=>"充值","url"=>WEB_PATH."/member/member/down_money_list"],
        "2"=>["name"=>"提现","url"=>WEB_PATH."/member/member/commissions"],
        "3"=>["name"=>"中奖","url"=>G_MODULE_PATH."/dingdan/lists/zj"],
        "4"=>["name"=>"发货","url"=>G_MODULE_PATH."/dingdan/lists/notsend"],//订单地址确认
        "5"=>["name"=>"线下充值","url"=>WEB_PATH."/member/member/recharge"],
    ];
    public function __construct(){
        $this->db = System::load_sys_class('model');
    }
    public function test(){
        echo "hello";
    }

    public function getState(){
        return $this->state;
    }

    public function addNotice($data,$detail){
        $data['time'] = time();
        $data['data'] = serialize($detail);
        $sql = Assemblydata($data,"admin_message");
        $res = $this->db->Query($sql);
    }

    public function getNoticeCount($type,$admin_type=1){
        $res = $this->db->GetOne("select count(*) as count from `@#_admin_message` where `type` = $type and `admin_type` = $admin_type");
        return $res['count']?:0;
    }
}