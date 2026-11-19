<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');

class channel extends base {   

    public function __construct() {
        parent::__construct();
    }

    /**
     * 是否下级
     * @param mixed $userid
     * @return bool
     */
    private function is_invitation($userid) {
        $sql = "select yaoqing from `@#_member` where `uid` = '$userid'";
        $yaoqing = $this->db->GetOne($sql);
        if ($yaoqing['yaoqing'] == $this->uId) {
            return true;
        }
        return false;
    }

    /**
     * 获取下级充值提现信息
     * 
     * */
    public function get_invitation_account() {
        // 全部和近7天的充值、提现总金额
        $userid = _htmtocode($_POST['userid']);
        if(!$userid){
            $this->jsonData->error("",'参数错误',[]);
        }
        $user = $this->userInfo(2);
        if($user['level'] != 2){
            $this->jsonData->error("",'您不是渠道商',[]);
        }
        //是否下级
        if(!$this->is_invitation($userid)){
            $this->jsonData->error("",'不是该用户的下级',[]);
        }
        //全部记录
        $recharge_all = $this->get_invitation_recharge($userid);
        $withdraw_all = $this->get_invitation_withdraw($userid);
        //近7天记录
        $recharge_7 = $this->get_invitation_recharge($userid,time()-7*24*3600);
        $withdraw_7 = $this->get_invitation_withdraw($userid,time()-7*24*3600);
        $data = [
            "recharge"=>["total"=>$recharge_all,"days_7"=>$recharge_7],
            "withdraw"=>["total"=>$withdraw_all,"days_7"=>$withdraw_7]
        ];
        $this->jsonData->success("",'获取成功',$data);
    }

    /**
     * 获取下级充值金额
     * @param int $userid 用户id
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     */
    private function get_invitation_recharge($userid,$start_time='',$end_time='') {
        $sql = 'select sum(money) as total from `@#_member_rechange` where `uid` = '.$userid.' and `status` = 1';
        $where = '';
        if($start_time){
            $where .= ' and `time` >= '.$start_time;
        }
        if($end_time){
            $where .= ' and `time` <= '.$end_time;
        }
        $sql .= $where;
        $res = $this->db->getOne($sql);
        return $res['total']?:0;
    }
    
    /**
     * 获取下级提现金额
     * @param int $userid 用户id
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     */
    private function get_invitation_withdraw($userid,$start_time='',$end_time='') {
        $sql = 'select sum(money) as total from `@#_member_cashout` where `uid` = '.$userid.' and `auditstatus` = 2';
        $where = '';
        if($start_time){
            $where .= ' and `time` >= '.$start_time;
        }
        if($end_time){
            $where .= ' and `time` <= '.$end_time;
        }
        $sql .= $where;
        $res = $this->db->getOne($sql);
        return $res['total']?:0;
    }
}