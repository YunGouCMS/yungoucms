<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

class auto extends SystemAction
{
    private $db;

    public function __construct()
    {
        $this->db=System::load_sys_class('model');
    }

    public function revealed(){
        $time = time()-60;
        // $sql = "SELECT id FROM `@#_shoplist` where q_end_time < $time and q_showtime = 'Y' limit 1000";
        $sql = "SELECT id FROM `@#_shoplist` where q_end_time < $time limit 100";
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
                echo json_encode(['code'=>500,"msg"=>"error","id"=>$id]);die;
            }
        }
    }
}