<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');

System::load_app_class('base', 'app', 'no');

// System::load_sys_fun('returnData');

class zp extends base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取中奖几率
     * @return array
     */
    private function getPrizeRate(){
        $uid = $this->uId;
        // 获取中奖几率
        $winning = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = '$uid'");
        if(!$winning){
            $this->jsonData->error('1001',"未中奖");
        }
        $pro = $winning['probability'];
        // 获取转盘配置
        $config = $this->getConfig("zp_set");
        if(!$config){
            $this->jsonData->error('1002',"未配置");
        }
        $config = unserialize($config);

        $totalMoney = $recharge = $rechargeTwo = $login =0;
        //统计有多少个下级
        $childCount = $this->db->GetCount("SELECT * FROM `@#_member` where `yaoqing` = '$uid'");
        $childArray = $this->db->GetList("SELECT uid FROM `@#_member` where `yaoqing` = '$uid'");
        if($childArray){
            $childArray = array_column($childArray,"uid");
        }else{
            return $winning['probability'];
        }        
        $child = implode(",",$childArray);
        // 统计下级共充值多少金额
        $childMoney = $this->db->GetOne("SELECT SUM(money) as sum FROM `@#_member_addmoney_record` where `status` = '已付款' and `uid` in (SELECT uid FROM `@#_member` where `yaoqing` = '$uid')");
        if($childMoney){
            $totalMoney = $childMoney['sum'];
        }
        // 统计共有多少个下级充值
        $childCountAll = $this->db->GetOne("SELECT COUNT(*) as count FROM `@#_member_addmoney_record` where `status` = '已付款' and `uid` in (SELECT uid FROM `@#_member` where `yaoqing` = '$uid') group by uid");
        if($childCountAll){
            $recharge = $childCountAll['count'];
        }
        // 统计有多少个下级二次充值
        $childCountTwo = $this->db->GetOne("SELECT COUNT(*) as count FROM `@#_member_addmoney_record` where `status` = '已付款' and `uid` in (SELECT uid FROM `@#_member` where `yaoqing` = '$uid') group by uid HAVING COUNT(*) >= 2");
        if($childCountTwo){
            $rechargeTwo = $childCountTwo['count'];
        }
        // 统计邀请人数
        $yqCount = $this->db->GetOne("SELECT COUNT(*) as count FROM `@#_member` where `yaoqing` = '$uid'");
        //统计下级第三方登录
        $loginCount = $this->db->GetOne("SELECT COUNT(*) as count FROM `@#_member_login_log` where `user_id` in ($child) group by user_id ");
        if($loginCount){
            $login = $loginCount['count'];
        }

        $rule = $config['rule'];
        $rule_data = unserialize($winning['rule_data']);
        $de = [
            "rule"=>[
                0=>["status"=>false,"add"=>0],
                1=>["status"=>false,"add"=>0],
            ],
            "child_num"=>0,
            "yaoqing"=>0,
            "yaoqing_add"=>0
        ];
        $rule_data = array_merge($de,$rule_data);
        //规则一： 邀请的人一共充值1200(可调整)以上,且最少1(可调整)位被邀请者有复存(线下充值)行为，同时满足以上2点，增加30(可调整)%的概率领取完成
        if(!$rule_data["rule"][0]['status'] && $totalMoney >= $rule[0]['cy_total'] && $rechargeTwo>= $$rule[0]['cy_num']){
            $pro += $rule[0]['add'];
            $rule_data["rule"][0]["status"] = true;
            $rule_data["rule"][0]["add"] = $rule[0]['add'];

        }
        //规则二： 还有一种情况邀请超过7(可调整)个人后有2(可调整)个以上是通过三方首次登录，且最少有1(可调整)人完成复存时，增加12(可调整)%概率
        if(!$rule_data["rule"][1]['status'] && $yqCount >= $rule[1]['login_num'] && $login >= $rule[1]['yq_num'] && $rechargeTwo >= $rule[1]['cy_num']){
            $pro += $rule[1]['add'];
            $rule_data["rule"][1]["status"] = true;
            $rule_data["rule"][1]["add"] = $rule[0]['add'];
        }

        //规则三： 以上2点对应的任务完成后,每再邀请一名新用户助力(登录成功),再初始的0%(完成条件1、2,则概率基础为42%)基础上增加对应的概率
        if($rule_data["rule"][0]['status'] && $rule_data["rule"][1]['status']){
            if($childCount > $rule_data['child_num'] && $rule_data['child_num'] > 0){
                $num = $childCount - $rule_data['child_num'];
                $rule_data['yaoqing'] += $num;
                $pro += $rule[2]['add'] * $num;
                $rule_data['yaoqing_add'] += $rule[2]['add'] * $num;
            }elseif($childCount > $rule[1]['yq_num'] && $rule_data['child_num'] == 0){
                $num = $childCount - $rule[1]['yq_num'];
                $rule_data['yaoqing'] += $num;
                $pro += $rule[2]['add'] * $num;
                $rule_data['yaoqing_add'] += $rule[2]['add'] * $num;
            }
            $rule_data['child_num'] = $childCount;
        }
        // 计算中奖几率
        $rule_data = serialize($rule_data);
        $this->db->Query("UPDATE `@#_winning_probability` SET `probability` = '$pro',`rule_data` = '$rule_data' WHERE `uid` = '$uid'");
        $data =[
            "childCount"=> $childCount,
            "childMoney"=> $totalMoney,
            "childCountAll"=> $recharge,
            "childCountTwo"=> $rechargeTwo,
            "child"=> $child,
            "loginCount"=> $login,
            "probability"=>$pro,
            "config"=> $config,
        ];
        return $data;
        // $this->jsonData->success('',$winning);
    }

    /**
     * 获取奖金
     */
    public function getPrize(){
        $uid = $this->uId;
        $config = $this->getConfig("zp_set");
        $money = 0;
        if($config){
            $config = unserialize($config);
            $money = $config['money']['money'];
        }
        $data = ['?','?','?','?','?','?','?','?'];
        $i = rand(0,7);
        $data[$i] = $money;
        $this->jsonData->success('','',['money'=>$data]);
    }

    /**
     * 抽奖
     * @return void
     */
    public function prizeDraw(){
        $data = $this->getPrizeRate();
        // $this->jsonData->success('',$data);
        $uid = $this->uId;
        // 获取抽奖信息
        $winning = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = '$uid'");
        if(!$winning){
            $this->jsonData->error('1001',$this->getLgMsg("no_lottery_draws"));
        }
        if($winning['num']<=0){
            $msg = $this->getLgMsg("No number of times");
            $msg = $msg!= "error"?$msg:"抽奖次数不足";
            $this->jsonData->error('1001',$msg);
        }

        // 获取转盘配置
        $config = $this->getConfig("zp_set");
        if(!$config){
            $this->jsonData->error('1002',"未配置");
        }
        $config = unserialize($config);
        // 活动有效时间
        $day = $config['time']['day'];
        // 获取最近抽奖记录
        $lastRecord = $this->db->GetOne("SELECT * from `@#_lottery_records` where `uid` = '$uid' ORDER BY `id` DESC LIMIT 1");
        // 获取本次中奖金额
        $money = 0;
        if(!$lastRecord || ($lastRecord && time() - $lastRecord['time'] > 60*60*24*$day || $lastRecord['huode'])){
            $period = $lastRecord?$lastRecord['period']+1:1;
            $min = $config['money']['first_money_min'];
            $max = $config['money']['first_money_max'];
        }else{
            $period = $lastRecord['period'];
            $min = $config['money']['money_min'];
            $max = $config['money']['money_max'];
        }
        $min *= 100;
        $max *= 100;
        if($min >=100){
            $money = bcdiv(rand($min,$max) ,100,0);
        }else{
            $money = bcdiv(rand($min,$max) ,100,2);
        }
        $huode = 0;
        if($winning['money'] + $money >= $config['money']['money']){
            $money = 0;
        }
        $total = $winning['money'] + $money;
        $time = time();
        // 开始事务
        $this->db->Autocommit_start();
        $w = [
            "num" =>$winning['num'] - 1,
            "money" =>$winning['money'] + $money,
        ];
        if($total >=$config['money']['money'] || $winning['probability'] >= $config['money']['money']){
            // 中奖处理
            $money = $total = $config['money']['money'];
            $huode =  1;
            $w['probability'] = 0;
            $w['money'] = 0;
            //记录中奖信息
            $account = [
                "uid"=>$uid,
                "content"=>'大转盘中奖',
                'money' =>$money,
                'time'=>$time,
                'type'=>1,
                'pay'=>"账户",
                'status'=>1
            ];
            $sql = Assemblydata($account,"member_account");
            $res = $this->db->query("update `@#_member` set `money` = `money` + $money where `uid` = $uid");
            if(!$res){
                $this->db->Autocommit_rollback();
                $this->jsonData->error('1003',"失败");
            }
            $res = $this->db->Query($sql);
            if(!$res){
                $this->db->Autocommit_rollback();
                $this->jsonData->error('1004',"失败");
            }
        }

        // 更新抽奖信息
        $sql1 = Assemblydata($w,"winning_probability","up",[["uid",$uid]]);
        $res1 = $this->db->Query($sql1);
        // 记录抽奖
        $sql2 = Assemblydata(["uid"=>$uid,"time"=>$time,'huode'=>$huode,"money"=>$money,"total_money"=>$total,"probability"=>$winning['probability'],"period"=>$period],"lottery_records");
        $res2 = $this->db->Query($sql2);
        if($res1 && $res2){
            $this->db->Autocommit_commit();
        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('1005',"操作失败",["res"=>[$res1,$res2],"sql"=>[$sql1,$sql2]]);
        }

        $data = [
            "money"=>$money,
            "num"=>$winning['num'] - 1,
            "huode"=>$huode,
            "total"=>$total
        ];
        $this->jsonData->success(200,"success",$data,true);
    }

    /**
     * 返回抽奖次数
     * 
     * @return 
     */
    public function getNumber(){
        $uid = $this->uId;
        $res = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = '$uid'");
        if($res){
            $num = $res['num'];
        }else{
            $num = 0;
        }
        $this->jsonData->success(200,"ok",['num'=>$num]);
    }

    /**
     * 获取抽奖记录
     */
    public function prizeRecord(){

        $uid = $this->uId;
        $p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
        $num = 10;
        $star = ($p) * $num;
        $type = htmlspecialchars($_POST['type']) ? $_POST['type'] : 0; //
        if($type == 1){
            $res = $this->db->GetList("SELECT a.*,m.username,m.mobile from `@#_lottery_records` a left join `@#_member` m on a.uid = m.uid order by a.id desc LIMIT $star ,$num");
        }else{
            $sql = "select a.*,m.username,m.mobile from `@#_lottery_records` a left join `@#_member` m on a.uid = m.uid where a.uid = $uid and a.period = (select period from `@#_lottery_records` where uid = $uid order by id desc limit 1) order by a.id desc LIMIT $star ,$num";
            $res = $this->db->GetList($sql);
            // $res = $this->db->GetList("SELECT * from `@#_lottery_records` where `uid` = '$uid' order by id desc LIMIT $star ,$num");
        }
        foreach($res as &$row){
            $row['time'] = date('Y-m-d H:i:s',$row['time']);
            $row['money'] = number_format($row['money'],2);
            //判断是否全是数字
            if(is_numeric($row['username'])){
                $row['username'] = substr_replace($row['username'],'****',3,4);
            } 
            //隐藏手机中间四位
            if($row['mobile']){
                $row['mobile'] = substr_replace($row['mobile'],'****',3,4);
            } else {
                $row['mobile'] = "";
            }
        }
        $this->jsonData->success(200,"success",$res,true);
    }

    /**
     * 抽奖信息
     * 
     */
    public function prizeInfo(){
        $uid = $this->uId;
        $config = $this->getConfig('zp_set');
        if(!$config){
            $this->jsonData->error('1001',"未配置");
        }
        $config = unserialize($config);
        $res = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = '$uid'");
        $userinfo = $this->db->GetOne("SELECT * from `@#_member` where `uid` = '$uid'");
        if($config['money']['money_min']>=1){
            $scale = 0;
        }else{
            $scale = 2;
        }
        $data = [
            "money_prize"=>number_format($config['money']['money'],$scale),//奖金
            "money_total"=>number_format($res['money']?:0,$scale),
            "money_diff"=>number_format(bcsub($config['money']['money'] , $res['money'],4),$scale),
            "username"=>$userinfo['username'],
        ];
        $this->jsonData->success(200,"success",$data,true);
    }

    /**
     * 抽奖进度
     * @return void
     */
    public function lotteryProgress(){

        $config = $this->getConfig('zp_set');
        if(!$config){
            $this->jsonData->error('1001',"未配置");
        }
        $config = unserialize($config);

        $p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
        $num = 10;
        $star = ($p) * $num;
        $where = " and a.money >0";
        $sql = "select a.uid,a.money,m.username from `@#_winning_probability` a left join `@#_member` m on a.uid = m.uid where 1 $where order by a.add_time desc LIMIT $star ,$num ";
        $res = $this->db->GetList($sql);
        if($res){
            foreach($res as &$row){
                // $row['time'] = date(format: 'Y-m-d H:i:s',$row['time']);
                $row['money_diff'] = $config['money']['money'] - $row['money'];
            }
        }else{
            $res = [];
        }
        $this->jsonData->success(200,"success",$res,true);
    }

    /**
     * 中奖记录
     * @return void
     */
    public function winningRecord(){
        $config = $this->getConfig('zp_set');
        if(!$config){
            $this->jsonData->error('1001',"未配置");
        }
        $config = unserialize($config);

        $p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
        $num = 10;
        $star = ($p) * $num;
        $where = " and a.huode = 1";
        $sql = "select a.uid,a.money,a.time,m.username,m.mobile from `@#_lottery_records` a left join `@#_member` m on a.uid = m.uid where 1 $where order by a.time desc LIMIT $star ,$num ";
        $res = $this->db->GetList($sql);
        if($res){
            foreach($res as &$row){
                $row['time'] = date('Y-m-d H:i:s',$row['time']);
                $row['money'] = number_format($row['money']);   
                //隐藏手机中间四位
                if($row['mobile']){
                    $row['mobile'] = substr_replace($row['mobile'],'****',3,4);
                } else {
                    $row['mobile'] = "";
                }
            }
        }else{
            $res = [];
        }
        $this->jsonData->success(200,"success",$res,true);
    }

    /**
     * 记录邀请
     * 
     */
    public function yaoqing(){
        $uid = $this->uId;
        $yaoqing = htmlspecialchars($_POST['invite_zp_id']);
        $res = $this->db->GetOne("SELECT * from `@#_member` where `uid` = '$yaoqing'");
        if(!$res){
            $this->jsonData->error('1001',$this->getLgMsg("notfound_user"));
        }
        $time = time();
        $in = $this->db->GetOne("SELECT * from `@#_invite_zp` where `invite_id` = $uid");
        if($in){
            $this->jsonData->error('1002',$this->getLgMsg("invited"));
        }
        $sql = Assemblydata(['uid'=>$yaoqing,"invite_id"=>$uid,"time"=>$time],"invite_zp");
        $res = $this->db->Query($sql);
        if($res){
            $this->jsonData->success(200,$this->getLgMsg("success"),[],true);
        }else{
            $this->jsonData->error('1003',"操作失败");
        }
    }
}