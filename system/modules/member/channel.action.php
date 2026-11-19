<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('admin', G_ADMIN_DIR, 'no');
require_once 'lib/PHPExcel/IOFactory.php';
class channel extends admin
{

    private $db;

    private $level  = 2;  //0:普通会员 1:回收 2:渠道 3：入驻

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
    }

    public function channel_lists()
    {
        $lists = $this->db->GetList("select uid,username from `@#_member` where level = 2 order by uid desc");
        $this->jsonData->success('', '', $lists);
    }

    //更换邀请人
    public function channel_change()
    {
        if (isset($_POST['dosubmit'])) {
            $uid = $_POST['uid'];
            $yaoqing = $_POST['yaoqing'];
            $res = $this->db->GetOne("select `uid`,`level` from `@#_member` where `uid` = '$yaoqing'");
            $member = $this->db->GetOne("select `uid`,`level` from `@#_member` where `uid` = '$uid'");
            if ($member && $res && $res['level'] == 2) {
                $this->db->Query("update `@#_member` set `yaoqing` = '$yaoqing' where `uid` = '$uid'");
                $this->jsonData->success("",'ok');
            } else {
                $this->jsonData->error('参数错误',[$res, $member]);
            }
        }
        $this->jsonData->error();
    }

    
}
