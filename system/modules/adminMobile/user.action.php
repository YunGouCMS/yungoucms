<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','adminMobile','no');

class user extends base{

    public function __construct()
    {
        parent::__construct();
    }

    /*后台首页*/
    public function index()
    {   
        $data = [];
        $this->jsonData->success('200','',$data);
    }

    //站内信列表
    public function messageLists()
    {
        $num = $this->pagenum;
        $sql = "SELECT id, titles, send_time FROM `go_system_title` where del = 1 ORDER BY id DESC";
        $counts = "SELECT count(*)  as count FROM `go_system_title` where del = 1  ";
        $total = $this->db->GetOne($counts);
        $total = $total['count'];

        if (isset($_POST['p'])) {
            $pagenum = $_POST['p'];
        } else {
            $pagenum = 1;
        }
        $members = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));

        foreach ($members as $key => $value) {
            $members[$key]['send_time'] = date('m/d/Y H:i:s', $value['send_time']);
            // 查询发送数据用户信息 显示两条
            // 已读 
            $sql = "SELECT a.id,m.uid,a.status, m.username,m.mobile FROM `go_system_message` as a INNER JOIN go_member as m WHERE a.to_id = m.uid and a.title_id = '$value[id]' and del = 1 and status = 1 limit 3";
            $read_data = $this->db->GetList($sql);
            $members[$key]['read_data'] = $read_data;
            // 未读
            $sql = "SELECT a.id,m.uid,a.status, m.username,m.mobile FROM `go_system_message` as a INNER JOIN go_member as m WHERE a.to_id = m.uid and a.title_id = '$value[id]' and del = 1 and status = 0 limit 3";
            $unread_data = $this->db->GetList($sql);

            $members[$key]['unread_data'] = $unread_data;
        }
        $data = [
            'total' => $total,
            'lists' => $members,
        ];
        $this->jsonData->success('200','',$data);
    }

    //站内信详情
    public function SendDetails()
    {
        $id = $_GET['id'];
        $sql = "SELECT * FROM `go_system_title` where id = '$id'";
        $title = $this->db->GetOne($sql);
        $title['send_time'] = date('Y-m-d H:i:s', $title['send_time']);
        $data = [
            'record' => $title,
        ];
        $this->jsonData->success('200','',$data);
    }

    //代理/渠道商列表
    public function channelLists(){
        $table = "@#_member";
        $level = 2;
        $w = ' and `level` = ' . $level;
        $wc = ' and level = ' . $level;
        $num = 20;
        $time = time();
        $sqlcoutn = "SELECT count(*) FROM `$table` WHERE (`emailcode` = '1' or `mobilecode` = '1' ) and `auto_user` != 1 $wc";
        $total = $this->db->GetCount($sqlcoutn);
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'] ?: 0;
        } else {
            $pagenum = 1;
        }
        $start = $num * ($pagenum - 1);
        $sql = "SELECT uid,username from $table where 1 $w LIMIT $start,$num";
        $members = $this->db->GetList($sql);
        $time = time();
        //七日前
        $days_7 = $time - 7 * 24 * 3600;

        //时间查询
        $posttime1 = $_POST['posttime1'];
        $posttime2 = $_POST['posttime2'];
        $where = '';
        if ($posttime1 && $posttime2 && $posttime1 > $posttime2) {
            _messageerror("开始时间不能大于结束时间");
        }
        if ($posttime1) {
            $posttime1 = strtotime($posttime1);
            $where .= " AND r.time >= " . $posttime1;
        }
        if ($posttime2) {
            $posttime2 = strtotime($posttime2);
            $where .= " AND r.time <= " . $posttime2;
        }
        foreach ($members as $key => $value) {
            $uid = $value['uid'];
            $sql = "select COALESCE(SUM(CASE WHEN r.id is not null THEN r.money ELSE 0 END), 0) as rechange_total,
                            COALESCE(SUM(CASE WHEN r.id is not null and r.time >= $days_7 THEN r.money ELSE 0 END), 0) as rechange_days_7,
                            COALESCE(SUM(CASE WHEN w.id is not null THEN w.money ELSE 0 END), 0) as cashout_total,
                            COALESCE(SUM(CASE WHEN w.id is not null and w.time >= $days_7 THEN w.money ELSE 0 END), 0) as cashout_days_7
                    from `@#_member` m 
                    left join `@#_member_rechange` r on r.uid=m.uid 
                    left join `@#_member_cashout` W on W.uid=m.uid
                    where m.yaoqing = $uid";
            $res = $this->db->GetOne($sql);
            $members[$key]['rechange_total'] = $res['rechange_total'];
            $members[$key]['cashout_total'] = $res['cashout_total'];
            $members[$key]['rechange_days_7'] = $res['rechange_days_7'];
            $members[$key]['cashout_days_7'] = $res['cashout_days_7'];
            $sql = "select sum(r.money) as rechange_total from `@#_member_addmoney_record` r
                    left join `@#_member` m on r.uid=m.uid
                    where m.yaoqing = $uid $where";
            $rech = $this->db->GetOne($sql);
            $members[$key]['rechange_total'] = $rech['rechange_total'] ?: 0;

            $res = $this->db->GetOne("select * from `@#_channel_yaoqing` where uid = $uid");
            $members[$key]['channel_yaoqing'] = G_WEB_PATH.'/index/pages/users/register/index?code='.$uid;
            if($res['data']){
                $members[$key]['channel_yaoqing'] .= "&".$res['data'];
            }
        }
        $formot_array = ["money", "other_money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        $data = [
            'total' => $total,
            'lists' => $members,
        ];
        $this->jsonData->success('200','',$data);
    }

    //线下充值
    public function offlineRechargeLists()
    {
        $num = 20;
        //今日时间戳
        $time_s_0 = strtotime(date('Y-m-d') . ' 00:00:00');
        //昨日时间戳
        list($time_s_1, $time_e_1) = get_tomorrow_time();
        //前日时间戳
        list($time_s_2, $time_e_2) = get_before_time(2, 2);
        // 7天前时间
        list($time_s_7, $time_e_7) = get_before_time(7);
        $wheres = '';
        $sum_diy = ',"" as day_money_diy';
        if (isset($_POST['sososubmit'])) {
            $sousuo = htmlspecialchars(trim($_POST['sousuo']));
            $content = htmlspecialchars(trim($_POST['content']));
            $posttime1 = htmlspecialchars(trim($_POST['posttime1']));
            $posttime2 = htmlspecialchars(trim($_POST['posttime2']));
            if ($content) {
                switch ($sousuo) {
                    case 'id':
                        $wheres = " AND u.`uid` = '$content'";
                        break;
                    case 'v_id':
                        $wheres = " AND u.`v_id` = '$content'";
                        break;
                    case 'username':
                        $wheres = " AND u.`username` LIKE '%$content%'";
                        break;
                    case 'email':
                        $wheres = " AND u.`email` LIKE '%$content%'";
                        break;
                    case 'mobile':
                        $wheres = " AND u.`mobile` LIKE '%$content%'";
                        break;
                    default:
                }
            }
            if ($posttime1 || $posttime2) {
                if ($posttime1 && !$posttime2) {
                    $posttime1 = strtotime($posttime1);
                    $sum_diy = ",SUM(CASE WHEN r.`time` >= $posttime1 THEN r.`money` ELSE 0 END) AS day_money_diy";
                }
                if ($posttime2 && !$posttime1) {
                    $posttime2 = strtotime($posttime2);
                    $sum_diy = ",SUM(CASE WHEN r.`time` <= $posttime2 THEN r.`money` ELSE 0 END) AS day_money_diy";
                }
                if ($posttime2 && $posttime1) {
                    $posttime1 = strtotime($posttime1);
                    $posttime2 = strtotime($posttime2);
                    if ($posttime1 >= $posttime2) {
                        _messageerror("后一个时间需要大于前一个");
                    }
                    $sum_diy = ",SUM(CASE WHEN r.`time` >= $posttime1 and r.`time` <= $posttime2 THEN r.`money` ELSE 0 END) AS day_money_diy";
                }
            }
        }
        if (!$wheres) {
            $wheres = " and r.`type` = '1'
            AND r.`STATUS` = '2' 
            AND (r.`content` = '使用佣金充值到账户' or r.`content` = '充值')
            AND u.uid is not NULL";
        }
        $sql = "SELECT  max(r.time) as time,u.mobile,u.money,u.username,u.score,u.uid,u.v_id,
                        SUM(CASE WHEN r.`time` >= $time_s_0 THEN r.`money` ELSE 0 END) AS day_money,
                        SUM(CASE WHEN r.`time` >= $time_s_1 and r.`time` <= $time_e_1 THEN r.`money` ELSE 0 END) AS day_money_1,
                        SUM(CASE WHEN r.`time` >= $time_s_2 and r.`time` <= $time_e_2 THEN r.`money` ELSE 0 END) AS day_money_2,
                        SUM(CASE WHEN r.`time` >= $time_s_7 and r.`time` <= $time_e_7 THEN r.`money` ELSE 0 END) AS day_money_7
                        $sum_diy
                FROM `@#_member` u 
                LEFT JOIN `@#_member_rechange` r ON r.uid = u.uid 
                WHERE  1
                    $wheres
                GROUP BY u.uid order by time desc
                ";
        $total = $this->db->GetOne("SELECT count(*) as total FROM ($sql) as tem ");
        $total = $total['total'];
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $members = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        foreach ($members as $k => $v) {
            $members[$k]['time'] = $v['time'] ? date('Y-m-d H:i:s', $v['time']) : '无';
        }
        $formot_array = ["money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        $data = [
            'lists' => $members,
            'total' => $total,
            'pagenum' => $pagenum,
        ];
        $this->jsonData->success('','',$data);
    }

    //线下充值/扣款
    public function offlineRecharge(){
        $is_offline_recharge = $this->getConfig("is_offline_recharge") == 2 ? false : true;
        if (!$is_offline_recharge) {
            $this->jsonData->error(-1);
        }
        $data = $_POST;
        if ($data) {
            $money = $data['money'];
            $uid = $data['uid'];
            if (!$uid) {
                $this->jsonData->error(6);
            }
            $type = $data['type'];
            $message = $data['message'];
            $secure_password = $data['secure_password'];
            if (!$secure_password) {
                $this->jsonData->error(5,"请输入安全密码");
            }
            if (!$this->verifyExamine($secure_password)) {
                $this->jsonData->error(5,"安全密码错误");
            }
            $time = time();
            $userinfo = $this->db->Getone("SELECT `money` FROM `@#_member` where uid='$uid'");
            $this->db->Autocommit_start();
            $AdminInfo = $this->getAdminInfo();
            if ($money == 0) {
                $this->jsonData->error(1);
            } elseif ($money < 0 || $type == 2) {
                $m = abs($money);
                if ($m > $userinfo['money']) {
                    $this->jsonData->error(2);
                }
                $content = '线下扣款';
                $admin_id = $AdminInfo['uid'];
                $admin_name = $AdminInfo['name'];
                //先记录，用户金额审核后变动
                $sql2 = $this->db->Query("INSERT INTO  `@#_member_rechange` (`uid`,`type`,`pay`,`content`,`money`,`time`,`message`,`status`,`examine_status`,`admin_id`,`admin_name`)
                    VALUES('$uid','-1','账户','$content','$m','$time','$message','1','1','$admin_id','$admin_name')");
                if ($sql2) {
                    $this->db->Autocommit_commit();
                    $req = $this->db->GetOne("select * from `@#_member_rechange` where `uid`=$uid and `type` = '-1' and `time` = '$time'");
                    $res = $this->db->GetList("SELECT * FROM `@#_handle` where `type`='41' and `to_id`='{$req['id']}'");
                    if ($res) {
                        _messageerror("请勿重复提交!");
                    }
                    $desc = $_POST['desc'];
                    $data = [
                        "operat_id" => $AdminInfo['uid'],
                        "operat_name" => $AdminInfo['username'],
                        "operat_status" => 2,
                        "operat_desc" => $desc,
                        "operat_time" => time(),
                        "to_id" => $req['id'],
                        "type" => "41"
                    ];
                    $sql = Assemblydata($data, "handle");
                    $this->db->Query($sql);
                    $newmoney = $this->db->Getone("SELECT `money` FROM `@#_member` where uid='$uid'");
                    $this->jsonData->success(200, 'ok', ["money" => $newmoney['money']]);
                } else {
                    $this->db->Autocommit_rollback();
                    $this->jsonData->error(4);
                }
            } else {
                $m = $money;
                $content = '线下充值';
                $admin_id = $AdminInfo['uid'];
                $admin_name = $AdminInfo['name'];
                $sql2 = $this->db->Query("INSERT INTO  `@#_member_rechange` (`uid`,`type`,`pay`,`content`,`money`,`time`,`message`,`status`,`examine_status`,`admin_id`,`admin_name`)
                    VALUES('$uid','1','账户','$content','$m','$time','$message','1','1','$admin_id','$admin_name')");
                if ($sql2) {
                    $this->db->Autocommit_commit();
                    $req = $this->db->GetOne("select * from `@#_member_rechange` where `uid`=$uid and `type` = '1' and `time` = '$time'");
                    $res = $this->db->GetList("SELECT * FROM `@#_handle` where `type`='41' and `to_id`='{$req['id']}'");
                    if ($res) {
                        _messageerror("请勿重复提交!");
                    }
                    $desc = $_POST['desc'];
                    $data = [
                        "operat_id" => $AdminInfo['uid'],
                        "operat_name" => $AdminInfo['username'],
                        "operat_status" => 2,
                        "operat_desc" => $desc,
                        "operat_time" => time(),
                        "to_id" => $req['id'],
                        "type" => "41"
                    ];
                    $sql = Assemblydata($data, "handle");
                    $this->db->Query($sql);
                    $newmoney = $this->db->Getone("SELECT `money` FROM `@#_member` where uid='$uid'");
                    $this->jsonData->success(200, 'ok', ["money" => $newmoney['money']]);
                } else {
                    $this->db->Autocommit_rollback();
                    $this->jsonData->error(4);
                }
            }
        }
    }
}
?>