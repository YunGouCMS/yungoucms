<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('base','member','no');
class movements extends base {
	public function __construct(){
        $this->db = System::load_sys_class("model");
	}

	public function index(){
        $shopid=intval($this->segment(4));
        $shoplist = $this->db->getlist("select * from `@#_shoplist` where sid=$shopid and q_end_time IS NOT NULL and goucount!=0 and buycount!=0 and q_end_time<".time().' ORDER BY id DESC limit 10');
        $i=0;
        for ($i=0;$i<count($shoplist);$i++)
        {
            if(unserialize($shoplist[$i]['q_user'])['username']=="")
            {
                $shoplist[$i]['username']=unserialize($shoplist[$i]['q_user'])['mobile'];
            }else{
                $shoplist[$i]['username']=unserialize($shoplist[$i]['q_user'])['username'];
            }
        }
        $shop = $this->db->GetOne("select * from `@#_shoplist` where sid=$shopid and q_end_time IS NULL");
        $shop['bl']=($shop['canyurenshu']/$shop["zongrenshu"])*100;
        $shop['shengyu']=$shop['zongrenshu']-$shop["canyurenshu"];
        $count=count($shoplist);
        include templates("mobile/index","movements");
	}

    public function getMapInfo()
    {
        $shopid=intval($this->segment(4));
        $limit=intval($this->segment(5));
        if($limit==0)
        {
            $limit=10;
        }
        $shoplist = $this->db->getlist("select * from `@#_shoplist` where sid=$shopid and q_end_time IS NOT NULL and goucount!=0 and buycount!=0 and q_end_time<".time()." ORDER BY id DESC limit ".$limit);
        echo json_encode($shoplist);
    }

    public function getbuyinfo(){
        $shopid=intval($this->segment(4));
        $shop = $this->db->GetOne("select * from `@#_shoplist` where sid=$shopid and q_end_time IS NULL");
        $shop['bl']=($shop['canyurenshu']/$shop["zongrenshu"])*100;
        $shop['shengyu']=$shop['zongrenshu']-$shop["canyurenshu"];

        echo json_encode($shop);
    }
}
?>