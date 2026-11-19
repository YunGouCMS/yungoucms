<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'adminMobile', 'no');

class record extends base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 购买记录
     * @return void
     */
    public function purchaseRecord()
    {
        $uid = $this->segment(4);
        $sql = "SELECT g.*,if(s.thumb is not null,s.thumb,sy.thumb) as thumb FROM `@#_member_go_record` g left join `@#_shoplist` s on s.id = g.shopid left join `@#_shoplist_copy` sy on sy.id = g.shopid  WHERE `uid` = $uid";
        $num = 20;
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record`  WHERE `uid` = $uid");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $list = $this->db->GetPage($sql, ["num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0]);
        data_format($list, ['moneycount', 'money'], "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        data_format($list, ["time"], "times");
        foreach ($list as &$vl) {
            $vl['thumb'] = G_UPLOAD_PATH . "/" . $vl['thumb'];
            $vl['huode'] = $vl['huode'] ? "是" : "否";
        }
        include $this->tpl(ROUTE_M, 'member.purchase.list');
    }

    /**
     * 充值记录
     * @return void
     */
    public function rechargeRecord()
    {
        $chongzhi = '';
        //搜索记录
        $channel = $this->db->GetList("select uid,username from `@#_member` where `level` = 2");
        $posttime1 = isset($_POST['posttime1']) ? $_POST['posttime1'] : '';
        $posttime2 = isset($_POST['posttime2']) ? $_POST['posttime2'] : '';
        if (!empty($posttime1) && !empty($posttime2)) { //如果2个时间都不为空
            $posttime1 = strtotime($posttime1);
            $posttime2 = strtotime($posttime2);
            if ($posttime1 > $posttime2) {
                _message("前一个时间不能大于后一个时间");
            }
            $times = "`time`>='$posttime1' AND `time`<='$posttime2'";
        }

        //天数
        $day = $_POST['day'];
        if ($day) {
            switch ($day) {
                case '1':
                    $daytime = strtotime(date('Y-m-d') . ' 00:00:00');
                    $times = "`time` >= '$daytime'";
                    // code...
                    break;
                case '2':
                    $start = strtotime(date('Y-m-d', strtotime("-1 day")) . '00:00:00');
                    $end = strtotime(date('Y-m-d', strtotime("-1 day")) . '23:59:59');
                    $times = "`time` >= '$start' AND `time` <= '$end'";
                    break;
                case '3':
                    $start = strtotime(date('Y-m-d', strtotime("-2 day")) . '00:00:00');
                    $end = strtotime(date('Y-m-d', strtotime("-2 day")) . '23:59:59');
                    $times = "`time` >= '$start' AND `time` <= '$end'";
                    break;
                case '7':
                    $start = strtotime(date('Y-m-d', strtotime("-7 day")) . '00:00:00');
                    $end = time();
                    $times = "`time` >= '$start' AND `time` <= '$end'";
                    break;
                default:
            }
        }
        $chongzhi = isset($_POST['chongzhi']) ? $_POST['chongzhi'] : '';
        if (!empty($chongzhi) && ($chongzhi != '请选择充值来源' && $chongzhi != '充值来源')) {
            $content = " AND `content`='$chongzhi'";
        }
        if (empty($chongzhi) || $chongzhi == '请选择充值来源') $content = " AND (`content` like '%充值%' or `content`='使用佣金充值到云购账户' or `content`='使用余额宝充值到云购账户')";

        $sel_channel = $_POST['sel_channel'];
        if ($sel_channel) {
            if (explode('_', $sel_channel)[0] == 'channel') {
                $yaoqing = explode('_', $sel_channel)[1];
                $content .= " AND `uid` in (select uid from `@#_member` where `top_uid` = $yaoqing) ";
            }
        }
        $yonghu = isset($_POST['yonghu']) ? $_POST['yonghu'] : '';
        if (empty($yonghu) || $yonghu == '请选择用户类型') {
            $uid = ' AND 1';
        }
        $yonghuzhi = isset($_POST['yonghuzhi']) ? $_POST['yonghuzhi'] : '';
        if ($yonghu == '用户id') {
            if ($yonghuzhi) {
                $uid = " AND `uid`='$yonghuzhi'";
            } else {
                $uid = ' AND 1';
            }
        }
        if ($yonghu == '用户名称') {
            if ($yonghuzhi) {
                $user_uid = $this->db->GetOne("select uid from `@#_member` where `username`='$yonghuzhi'");
                if ($user_uid) {
                    $uid = " AND `uid`='$user_uid[uid]'";
                } else {
                    _message($yonghuzhi . "用户不存在");
                    $uid = ' AND 1';
                }
            } else {
                $uid = ' AND 1';
            }
        }
        if ($yonghu == '用户邮箱') {
            if ($yonghuzhi) {
                $user_uid = $this->db->GetOne("select uid from `@#_member` where `email`='$yonghuzhi'");
                if ($user_uid) {
                    $uid = " AND `uid`='$user_uid[uid]'";
                } else {
                    _message($yonghuzhi . "用户不存在");
                    $uid = ' AND 1';
                }
            } else {
                $uid = ' AND 1';
            }
        }
        if ($yonghu == '用户手机') {
            if ($yonghuzhi) {
                $user_uid = $this->db->GetOne("select uid from `@#_member` where `mobile`='$yonghuzhi'");
                if ($user_uid) {
                    $uid = " AND `uid`='$user_uid[uid]'";
                } else {
                    _message($yonghuzhi . "用户不存在");
                    $uid = ' AND 1';
                }
            } else {
                $uid = ' AND 1';
            }
        }
        $wheres = $times . $content . $uid;
        $num = 20;
        if (empty($wheres)) {
            $total = $this->db->GetOne("SELECT count(*) as count FROM `@#_member_rechange` WHERE `type` = '1' and `content` <> '注册赠送'");
            $summoeny = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_member_rechange` WHERE `type` = '1' and `content` <> '注册赠送'");
        } else {
            $total = $this->db->GetOne("SELECT count(*) as count FROM `@#_member_rechange` WHERE 1 $wheres AND `type` = '1' and `content` <> '注册赠送' ");
            $summoeny = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_member_rechange` WHERE 1 $wheres AND `type` = '1' and `content` <> '注册赠送' ");
        }
        $total = $total['count'];
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        if (empty($wheres)) {
            $sql = "SELECT * FROM `@#_member_rechange` WHERE `type` = '1' and `content` <> '注册赠送' order by `time` desc";
            $recharge = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        } else {
            $sql = "SELECT * FROM `@#_member_rechange` WHERE 1 $wheres AND `type` = '1' and `content` <> '注册赠送' order by `time` desc";
            $recharge = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        }
        for ($i = 0; $i < count($recharge); $i++) {
            $uid = $recharge[$i]['uid'];
            $sql = "select m.username,m1.uid as ym_uid,m1.username as ym_username,m1.level as ym_level from `@#_member` m 
                    left join `@#_member` m1 on m.top_uid = m1.uid and m1.level = 2 
                    where m.`uid`='$uid'";
            $member = $this->db->GetOne($sql);

            $recharge[$i]['ym_uid'] = $member['ym_uid'] ?: '';
            $recharge[$i]['ym_username'] = $member['ym_username'] ?: '';
            $recharge[$i]['username'] = $member['username'] ?: '';
            //审核状态
            $exa = $this->db->GetOne("select * from `@#_handle` where type = 41 and to_id = " . $recharge[$i]['id']);
            $recharge[$i]['operat_name'] = $exa['operat_name'] ?: '';
            $recharge[$i]['manage_name'] = $exa['manage_name'] ?: '';
            $recharge[$i]['manage_desc'] = $exa['manage_desc'] ?: '';
            $recharge[$i]['manage_time'] = $exa['manage_time'] ?: '';
            $recharge[$i]['manage_status'] = $exa['manage_status'] ?: '';
        }
        $formot_array = ["money", 'sum_money'];
        data_format($recharge, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        data_format($summoeny, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        $data = [
            'recharge' => $recharge,
            'summoeny' => $summoeny,
            "channel" => $channel,
            "total" => $total,
        ];
        $this->jsonData->success("", "", $data);
    }

    /**
     * 中奖记录
     * @return void
     */
    public function winningRecord()
    {
        $uid = $this->segment(4);
    }
    /**
     * 积分来源记录
     * @return void
     */
    public function scoreRecord()
    {
        $this->ment[] = array("scoreRecord", "积分明细", ROUTE_M . '/' . ROUTE_C . "/scoreRecord", 2);
        $uid = $this->segment(4);
        $sql = "select * from `@#_member_account` where `uid`='$uid' and `type`='1' and pay = '积分'  ORDER BY time DESC";
        $num = 20;
        $total = $this->db->GetCount("select count(*) as count from `@#_member_account` where `uid`='$uid' and `type`='1' and pay = '积分'");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $list = $this->db->GetPage($sql, ["num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0]);
        foreach ($list as $key => $val) {
            $list[$key]['time'] = date("Y-m-d H:i:s", $val['time']);
        }
        include $this->tpl(ROUTE_M, 'member.score_list');
    }
    /**
     * 佣金来源记录
     * @return void
     */
    public function commissionRecord()
    {
        $this->ment[] = array("commissionRecord", "佣金明细", ROUTE_M . '/' . ROUTE_C . "/commissionRecord", 2);
        $uid = $this->segment(4);
        $junior = $this->db->Getlist("select uid from `@#_member` where yaoqing = $uid");
        $jids = array_column($junior, "uid");
        $jids = implode(",", $jids);
        $sql = "select * from `@#_member_recodes` where (`type` = 1 and uid in ($jids) or (`type` <> 1 and uid = $uid))";
        $num = 20;
        $total = $this->db->GetCount("select count(*) from `@#_member_recodes` where (`type` = 1 and uid in ($jids) or (`type` <> 1 and uid = $uid))");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $list = $this->db->GetPage($sql, ["num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0]);
        foreach ($list as $key => $val) {
            $list[$key]['time'] = date("Y-m-d H:i:s", $val['time']);
            if (in_array($val['type'], [1, -4])) {
                $list[$key]['money'] = "+" . $list[$key]['money'];
            } else {
                $list[$key]['money'] = "-" . $list[$key]['money'];
            }
        }
        include $this->tpl(ROUTE_M, 'member.commission.list');
    }

    //账变记录
    public function money()
    {
        $wheres = "";
        $uid = $_POST['content'];
        if($uid){
            $wheres = " and r.uid = $uid";
        }
        // 充值记录
        $sql0 = "SELECT t1.id,t1.uid,t1.type,t1.pay,t1.money,t1.time,t1.content
                FROM go_member_rechange t1
                JOIN (
                    SELECT uid, MAX(id) AS latest_id
                    FROM go_member_rechange
                    GROUP BY uid
                ) t2 ON t1.uid = t2.uid AND t1.id = t2.latest_id";
        //消费记录
        $sql1 = "SELECT t1.id,t1.uid,t1.type,t1.pay,t1.money,t1.time,t1.content
                FROM go_member_account t1
                JOIN (
                    SELECT uid,  MAX(id) AS latest_id
                    FROM go_member_account
                    GROUP BY uid
                ) t2 ON t1.uid = t2.uid AND t1.id = t2.latest_id";
        // 佣金记录
        $sql2 = "SELECT t1.id,t1.uid,t1.type,'佣金' as pay,t1.money,t1.time,t1.content
                FROM go_member_recodes t1
                JOIN (
                    SELECT uid, MAX(id) AS latest_id
                    FROM go_member_recodes
                    GROUP BY uid
                ) t2 ON t1.uid = t2.uid AND t1.id = t2.latest_id";
        $sql = "select r.*,m.username from ($sql0 union all $sql1 union all $sql2) r join go_member m on r.uid = m.uid where 1 $wheres group by r.uid order by r.time desc";
        $total = $this->db->GetOne("select count(distinct r.uid) as total from ($sql0 union all $sql1 union all $sql2) r where 1 $wheres");
        $total = $total['total'];
        $num = 20;
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $list = $this->db->GetPage($sql, ["num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0]);
        foreach ($list as $key => $vl) {
            $list[$key]['time'] = date("Y-m-d H:i:s", $vl['time']);
            $list[$key]['money'] = $vl['type'] == 1 ? $vl['money'] : -$vl['money'];
            if ($vl['pay'] == "账户") {
                $list[$key]['money'] = number_format($list[$key]['money'], 0);
                $list[$key]['money'] = $this->currency['symbol'] . $list[$key]['money'];
                $pos = mb_strpos($vl['content'], '开奖失败退');
                if ($pos !== false) {
                    $str = mb_substr($vl['content'], 0, $pos + mb_strlen('开奖失败退'));
                    $list[$key]['content'] = $str . $list[$key]['money'];
                }
            } else {
                $list[$key]['money'] = intval($list[$key]['money']);
            }
        }
        $data = [
            'lists' => $list,
            'total' => $total,
        ];
        $this->jsonData->success('', '', $data);
    }

    public function get_money_details()
    {
        $this->ment[] = array("get_money_details", "账户明细", ROUTE_M . '/' . ROUTE_C . "/get_money_details", 2);
        $uid = $this->segment(4);
        $p = $this->segment(5) ?: 0;
        $num = 20;
        // 充值记录
        $sql = "SELECT t1.id,t1.uid,t1.type,t1.pay,t1.money,t1.time,t1.content
                FROM go_member_rechange t1 where t1.uid = $uid";
        //消费记录
        $sql1 = "SELECT t1.id,t1.uid,t1.type,t1.pay,t1.money,t1.time,t1.content
                FROM go_member_account t1 where t1.uid = $uid";
        // 佣金记录
        $sql2 = "SELECT t1.id,t1.uid,t1.type,'佣金' as pay,t1.money,t1.time,t1.content
                FROM go_member_recodes t1 where t1.uid = $uid";
        // $sql = "select r.*,m.username from ($sql union all $sql1 union all $sql2) r join go_member m on r.uid = m.uid where r.uid = $uid order by r.time desc";
        $member = $this->db->GetOne("select uid,username from `@#_member` where uid = $uid");
        $sql = "select r.* from ($sql union all $sql1 union all $sql2) r order by r.time desc";
        $total = $this->db->GetOne(str_replace("r.*", "count(*) as total", $sql));
        $total = $total['total'];
        $num = 20;
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $list = $this->db->GetPage($sql, ["num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0]);
        foreach ($list as $key => $vl) {
            $list[$key]['username'] = $member['username'];
            $list[$key]['time'] = date("Y-m-d H:i:s", $vl['time']);
            $list[$key]['money'] = $vl['type'] == 1 ? $vl['money'] : -$vl['money'];
            if ($vl['pay'] == "账户") {
                $list[$key]['money'] = number_format($list[$key]['money'], 0);
                $list[$key]['money'] = $this->currency['symbol'] . $list[$key]['money'];
                $pos = mb_strpos($vl['content'], '开奖失败退');
                if ($pos !== false) {
                    $str = mb_substr($vl['content'], 0, $pos + mb_strlen('开奖失败退'));
                    $list[$key]['content'] = $str . $list[$key]['money'];
                }
            } else {
                $list[$key]['money'] = intval($list[$key]['money']);
            }
        }
        include $this->tpl(ROUTE_M, 'record.money.details');
    }
}
