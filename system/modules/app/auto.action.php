<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('user');
class auto extends base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function revealed(){
        $time = time();
        $sql = "SELECT id FROM `@#_shoplist` where q_end_time < $time limit 1000";
        $list = $this->db->GetList($sql);
        foreach ($list as $k => $v) {
            $this->db->Autocommit_start();
            $id = $v['id'];
            //写入中奖商品
            $sql = "INSERT INTO `@#_shoplist_copy` select * from `@#_shoplist` where id = ".$id;
            $q1 = $this->db->Query($sql);
            //是否写入成功
            $sql1 = "SELECT id from `@#_shoplist_copy` where id = $id";
            $q11 = $this->db->Query($sql1);
            if($q1 || $q11){
                //删除原商品
                $sql2= "DELETE FROM `@#_shoplist` WHERE id=$id"; 
                $q2 = $this->db->Query($sql2);
                $this->db->Query("update `@#_shoplist_copy` set `q_showtime` = 'N' where id = ".$id);
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();    
                $this->jsonData->successCode('','error');
            }
        }
    }
}