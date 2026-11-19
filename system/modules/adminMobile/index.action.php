<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','adminMobile','no');

// System::load_sys_fun('returnData');

class index extends base{

    public $expirationdate = 1;

    public function __construct()
    {
        parent::__construct();
    }

    /*后台首页*/
    public function index()
    {   
        // 数据查询类型
        $day_str = safe_replace($_POST['day_str']);
        
        // $day_str = 
        // 
        // 总充值金额
        $addmoney_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        // 平台总提现金额
        $cashout_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_cashout` where auditstatus=1 ");

        // 线上总充值
        // $all_pay_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        // 线下总充值
        // $all_recharge_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        
        $stime = strtotime("today");
        $etime = strtotime("tomorrow") - 1;
        // 今日总消费
        $day_shop_money=$this->db->GetOne("select sum(moneycount) as allmoney from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status like %已付款%  and a.time <= $etime and a.time >= $stime a.huode !=0 and b.auto_user!=1");
        // 今日总提现
        $day_cashout_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_cashout` where auditstatus=1 and time <= $etime and time >= $stime");
        $data['addmoney_money'] = $addmoney_money;
        $this->jsonData->success('200','',$data);
    }

    /**
     * 获取本周所有日期
     */
    public function get_weekss($time = '', $format='Y-m-d'){
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i=1; $i<=7; $i++){
            $d = $i-7;
            $date[$i] = date($format ,strtotime( "+{$d} days", $time));
        }
        return $date;
    }

    /*
	 * 首页 数据
	 */
	public function tdefaultData(){
        $date = strtotime(date('Y-m-d',strtotime("-7 day")).' 00:00:00');
        $smoneylist=$this->db->Getlist("SELECT FROM_UNIXTIME(time,'%Y-%m-%d') days,SUM(money) datemoney FROM `@#_member_addmoney_record` where time >= $date GROUP BY days");
        $userlist = $this->db->Getlist("SELECT FROM_UNIXTIME(time,'%Y-%m-%d') days,count(*) as num FROM `go_member` where auto_user = 0 AND mobilecode =1 AND  time >= $date GROUP BY days");
        $get_weekss = $this->get_weekss();
        $mday=array_column($smoneylist,'days');
        $mony=array_column($smoneylist,'datemoney');
        $m =array_combine($mday,$mony);
        $ms =[];
        foreach ($get_weekss as $v){
            if (!isset($m[$v])){
                $ms[]=["day"=>$v,'num'=>0];
            }else{
                $ms[]=["day"=>$v,'num'=>$m[$v]];
            }
        }
        $uday = array_column($userlist,'days');
        $unum = array_column($userlist,'num');
        $u =array_combine($uday,$unum);
        $us =[];
        foreach ($get_weekss as $v){
            if (!isset($u[$v])){
                $us[]=["day"=>$v,'num'=>0];
            }else{
                $us[]=["day"=>$v,'num'=>$u[$v]];
            }
        }
        $zu = [];
        foreach ($get_weekss as $v){
            $t = strtotime($v.' 23:59:59');
            $sql ="SELECT COUNT(*) as num FROM `@#_member` WHERE auto_user =0 AND mobilecode =1 AND time < $t";
            $coutn = $this->db->GetOne($sql);
            $zu[]=["day"=>$v,'num'=>$coutn['num']];
        }

        $data =[
            'code'=>1,
            'm'=>$ms,
            'u'=>$us,
            'zu'=>$zu
        ];
        $this->jsonData->success('200','',$data);
    }

    public function statistics($times=''){
        $t = false;
        if(!is_array($times)){
            $t = true;
            $times = $_POST['times'];
            if($times == 1){
                $times = get_today_time();
            }else{
                $times = get_before_time($times);
            }
        }
        //今日
        $time_s = $times[0];
        $time_e = $times[1];
        //注册赠送
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_rechange` where `mode` = 1 and (`time` > $time_s and time < $time_e)");
        $data['zs'] = $res['total_money']?:0;
        //今日积分增加
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_account` where pay = '积分' and `type` = 1 and `time` > $time_s and time < $time_e");
        $data['score_add'] = $res['total_money']?:0;
        // 今日推广返佣
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_recodes` where `type` = 1 and (`time` > $time_s and time < $time_e)");
        $data['com_tg'] = $res['total_money']?:0;
        // 今日三级返佣
        $data['com_sj'] = -1;
        // 今日用户取现
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_recodes` where `type` = -3 and (`time` > $time_s and time < $time_e)");
        $data['ts'] = $res['total_money']?:0;
        // 今日股东分红

        $data['fh'] = -1;
        // 今日余额使用
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_account` where `type` = -1 and `pay` = '账户' and (`time` > $time_s and time < $time_e)");
        $data['use_money'] = $res['total_money']?:0;
        // 今日用户充值
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_offline_money` where `create_time` > $time_s and `create_time` < $time_e and status = 2");
        $res1 = $this->db->GetOne("select sum(money) as total_money from `@#_member_addmoney_record` where `time` > $time_s and `time` < $time_e and status = '已付款'");
        $data['rechange'] = $res['total_money']?:0 + $res1['total_money']?:0;
        // 今日积分使用
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_account` where `type` = -1 and `pay` = '积分' and (`time` > $time_s and time < $time_e)");
        $data['use_score'] = $res['total_money']?:0;
        // 今日在线兑换
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_go_record` where status = '已回收' and `time` > $time_s and `time` < $time_e");
        $data['exchange'] = $res['total_money']?:0;
        // 今日投资收入
        $data['invest_income'] = -1;
        // 当前商品总数
        $res = $this->db->GetOne("select count(*) as total_money from `@#_shoplist` ");
        $data['goods_total'] = $res['total_money']?:0;
        // 今日订单新增
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e");
        $res1 = $this->db->GetOne("select count(*) as total_money from `@#_orders` where `time` > $time_s and `time` < $time_e");
        $res2 = $this->db->GetOne("select count(*) as total_money from `@#_orders_group` where `time` > $time_s and `time` < $time_e");
        $data['orders_num'] = $res['total_money']?:0 + $res1['total_money']?:0 +$res2['total_money']?:0;
        // 今日发货订单
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status not in ('已付款,未发货,未完成','已回收')");
        $res1 = $this->db->GetOne("select count(*) as total_money from `@#_orders` where `time` > $time_s and `time` < $time_e and status > 2");
        $res2 = $this->db->GetOne("select count(*) as total_money from `@#_orders_group` where `time` > $time_s and `time` < $time_e and status > 2");
        $data['orders_fh_num'] = $res['total_money']?:0 + $res1['total_money']?:0 +$res2['total_money']?:0;
        // 今日待发订单
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status = '已付款,未发货,未完成'");
        $res1 = $this->db->GetOne("select count(*) as total_money from `@#_orders` where `time` > $time_s and `time` < $time_e and status = 2");
        $res2 = $this->db->GetOne("select count(*) as total_money from `@#_orders_group` where `time` > $time_s and `time` < $time_e and status = 2");
        $data['orders_df_num'] = $res['total_money']?:0 + $res1['total_money']?:0 +$res2['total_money']?:0;
        // 今日兑换订单
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status = '已回收'");
        $data['orders_exc_num'] = $res['total_money']?:0;
        // 平台用户总数
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0");
        $data['user_total'] = $res['total_money']?:0;
        // 今日新增人数
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0 and `time` > $time_s and `time` < $time_e");
        $data['user_num'] = $res['total_money']?:0;
        // 昨日新增人数
        $y_times = get_before_time(1);
        $time_s1 = $y_times[0];
        $time_e1 = $y_times[1];
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0 and `time` > $time_s1 and `time` < $time_e1");
        $data['yesterday_user_num'] = $res['total_money']?:0;
        // 本月新增人数
        $y_times = get_current_month_time();
        $time_s1 = $y_times[0];
        $time_e1 = $y_times[1];
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0 and `time` > $time_s1 and `time` < $time_e1");
        $data['month_user_num'] = $res['total_money']?:0;
        // 今日充值用户
        $res = $this->db->GetOne("select count(distinct uid) as total_money from `@#_offline_money` where `create_time` > $time_s and `create_time` < $time_e and status = 2");
        $res1 = $this->db->GetOne("select count(distinct uid) as total_money from `@#_member_addmoney_record` where `time` > $time_s and `time` < $time_e and status = '已付款'");
        $data['user_rechange_num'] = $res['total_money']?:0 + $res1['total_money']?:0;
        // 今日兑换人数
        $res = $this->db->GetOne("select count(distinct uid) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status = '已回收'");
        $data['user_exc_num'] = $res['total_money']?:0;
        // 今日股东增加
        $data['user_invest_add'] = -1;
        // 今日股东退出
        
        $data['user_invest_quit'] = -1;
        if($t){
            $formot_array = ["now_money","now_commission",'income',"zs","com_tg","com_sj","ts","fh","use_money","rechange","exchange","invest_income"];
            data_format($data,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
            $this->jsonData->success('','',$data);
        }
        return $data;
    }
}
?>