<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");

class basepay{
    public $db;
    public $pay_prefix;

    public $success_url = G_WEB_PATH . "/index/pages/users/index?pay_status=success";
    public $error_url = G_WEB_PATH . "/index/pages/users/index?pay_status=fail";

    public function __construct()
    {
        $this->db = System::load_sys_class('model');
        $config = $this->db->GetOne("SELECT `value` FROM `@#_config` WHERE `name` = 'pay_prefix'");
        $this->pay_prefix = $config['value']?:"C";
    }

    /**
     * 获取日志位置
     * @param mixed $pay_name 支付名称
     * @return string
     */
    public function get_logpath($pay_name){
        if(!is_dir("pay_log/".$pay_name)){
            mkdir("pay_log/".$pay_name,0777,true);
        }
        return "pay_log/".$pay_name.'/'.$pay_name.date("Y-m-d").".log";
    }

    /**
     * 回调处理订单
     *
     * @param [type] $orderid
     * @param [type] $status 1成功，2失败
     * @param [type] $callback success or ok
     * @return 
     */
    public function handle_order($orderid,$status,$callback='SUCCESS',$paylog='pay_log/pay.log',$transferAmount=0){
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if (!$dingdaninfo) {
            save_log(["orderid"=>$orderid,"msg"=>"订单未找到"],$paylog);
            echo $callback;exit;
        }    //没有该订单,失败
        $data = null;
        if($transferAmount > 0 && $transferAmount != $dingdaninfo['money']){
            //处理 回调金额不等于订单金额
            $status = 2;
            $data = mb_unserialize($dingdaninfo['data']);
            $data['transferAmount'] = $transferAmount;
            $data = serialize($data);
        }else{
            $c_money = intval($dingdaninfo['money']) ;
        }
        if ($status==2) {
            $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '支付失败',`data` = '$data' where  `code` = '$orderid'");
            echo $callback;exit;
        }
        $uid = $dingdaninfo['uid'];
        $time = time();
        $config = System::load_app_config("userrecharge", '', 'member');
        if ($config['r_money']) {
            $d_money = $config['r_money'];
        }
        $this->db->Autocommit_start();
        //充值奖励处理
        if ($c_money >= $d_money && $config['isOpen']) {
            if ($config['money']) {
                $m = $config['money']; //充值赠送
                $up_sql1 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $m where (`uid` = '$uid')");
                $up_sql3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值赠送', '$m', '$time')");
            }
            $r_time = $this->db->GetOne("SELECT * FROM `@#_recharge_money` WHERE `uid` = '$uid' AND status=1");
            if (!$r_time) { //判断是否在累计时效内
                $status = 1;
                //写入充值时间做累计领取判断
                $up_sql2 = $this->db->Query("INSERT INTO `@#_recharge_money` (`uid`, `time`, `dindancode`, `status`) VALUES ('$uid', '$time', '$orderid', '$status')");
            }
        }

        $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '已付款',real_money = $c_money where  `code` = '$orderid'");
        $up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
        $orderCode = $dingdaninfo['uid'].$dingdaninfo['code'];
        $up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$orderCode')");

        if ($up_q1 && $up_q2 && $up_q3) {
            $this->db->Autocommit_commit();
        } else {
            $this->db->Autocommit_rollback();
            echo "fail3";
            exit;
        }
        if (empty($dingdaninfo['scookies'])) {
            echo $callback;
            exit;        //充值完成
        }
    }

    public function handle_withdrawal($orderid,$status,$callback='SUCCESS'){
        $id = $orderid[0];
        $cashout = $this->db->GetOne("SELECT * FROM `@#_member_cashout` WHERE `id`='$id' ");
        $time = time();
        if($status == 1){
            $type=-3;
            $content="提现";
            $ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='1' WHERE id='$id'");
            $rr=$this->db->Query("UPDATE `@#_member_commission` SET `dongjie_money`=`dongjie_money` - $cashout[money],`zhichu_money` =`zhichu_money`+$cashout[money]  where `uid`=$cashout[uid] ");
            $is=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`,`cashoutid`)VALUES($cashout[uid],'$type','$content','$cashout[money]','$cashout[time]','$cashout[id]')");
        }else{
            $ret=$this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus`='4' WHERE id='$id'");
        }
        echo $callback;
        exit;        //充值完成
    }

    public function handle_usdt($orderid,$status,$callback='SUCCESS',$paylog='pay_log/pay.log'){
        $this->db->Autocommit_start();
        $res = $this->db->GetOne("SELECT * FROM `@#_offline_money` WHERE `code`='$orderid'");
        if($res){
            if($res['status'] != 1){
                echo $callback;
                exit;
            }
            $id = $res['id'];
            $time = time();
            $real_money = bcmul($res['money'], $res['exchange_rate']?:1, 2);
            if($status == 1){
                //更新订单状态
				$ret=$this->db->Query("UPDATE `@#_offline_money` SET `real_money` = $real_money,`status`='2',`update_time`='$time' WHERE id='$id'");
                //更新用户余额
				$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $real_money where (`uid` = '$res[uid]')");
                // 记录充值记录
				$up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$res[uid]', '1', '账户', 'USDT充值', '$real_money', '$time')");
				if(!$up_q2 || !$up_q3 || !$ret){
					$this->db->Autocommit_rollback();
					return false;
				}
            }else{
				$ret=$this->db->Query("UPDATE `@#_offline_money` SET `real_money` = $real_money,`status`='3',`update_time`='$time' WHERE id='$id'");
            }
            $this->db->Autocommit_commit();
        }else{
            save_log(["orderid"=>$orderid,"msg"=>"订单未找到"],$paylog);
            echo $callback;exit;
        }
        echo $callback;
        exit;
    }
}