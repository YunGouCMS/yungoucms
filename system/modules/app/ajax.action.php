<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('send');
// System::load_sys_fun('returnData');

class ajax extends base{
    public function send_lottery_info(){
//        echo 0000111;exit;
        $id=safe_replace($_POST['id']);
//        $this->mobile = System::load_sys_config('mobile');
//        $key=$this->mobile['cfg_mobile_on'];
        $userinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist_copy` where id='$id' and send='0' ");
//        $state=$this->db->Query("UPDATE `@#_shoplist_copy` SET send='1' where id=$id ");
        if($userinfo){
            $sql = "SELECT `uid` FROM `@#_member_go_record` where `shopid` = $id group by uid";
            $sql = $this->db->GetList($sql);
            $sql = array_column($sql,'uid');
            $sql = implode(',',$sql);
            $msql = "SELECT `mobile` FROM `@#_member` where uid in ($sql) and auto_user = 0";
            $memberList = $this->db->GetList($msql);
            foreach ($memberList as $k=>$v){
                $res = _send_SMS($v['mobile'],22);
            }
            $user=unserialize($userinfo['q_user']);
            $uid=$user['uid'];
            $code=$userinfo['q_user_code'];
            $infos=$this->db->GetOne("SELECT auto_user FROM `@#_member` where uid=$uid");
            if(!$infos['auto_user']){//发送短信
                _send_SMS($user['mobile'],5,$code);

            }
        }
        $info['err']='1';
        echo json_encode($info);
    }

    /**
     * 上传图片
     * @param  object $filedata
     * @return string
     */
    public function uploadImg(){
        System::load_sys_class('upload','sys','no');
        upload::upload_config('bank',array('png','jpg','jpeg'),5120000);
        upload::go_upload($_FILES['filedata']);
        if(!upload::$ok){
            $this->jsonData->error('',upload::$error);
        }else{
            $img=upload::$filedir."/".upload::$filename;
            $data = [
                "url"=>G_UPLOAD_PATH."/bank/".$img,
                "path"=>"/bank/".$img
            ];
            $this->jsonData->successCode('','ok',$data);
        }
    }
}