<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('admin', G_ADMIN_DIR, 'no');
require_once 'lib/PHPExcel/IOFactory.php';
class member extends admin
{
    private $dg;
    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $this->ment = array(
            // array("lists","会员列表",ROUTE_M.'/'.ROUTE_C."/lists"),
            // array("lists","查找会员",ROUTE_M.'/'.ROUTE_C."/select"),
            // array("insert","添加会员",ROUTE_M.'/'.ROUTE_C."/insert"),
            // array("insert","会员回收站",ROUTE_M.'/'.ROUTE_C."/lists/del"),
            array("insert", "佣金提现", ROUTE_M . '/' . ROUTE_C . "/commissions"),
            array("insert", "昵称限制", ROUTE_M . '/' . ROUTE_C . "/config"),
            array("insert", "积分奖励", ROUTE_M . '/' . ROUTE_C . "/member_fufen"),
            array("insert", "充值记录", ROUTE_M . '/' . ROUTE_C . "/recharge"),
            array("insert", "未成功会员", ROUTE_M . '/' . ROUTE_C . "/noregmember"),
            array("insert", "消费记录", ROUTE_M . '/' . ROUTE_C . "/pay_list"),
            array("insert", "佣金明细", ROUTE_M . '/' . ROUTE_C . "/commissions", 2),
            array("insert", "注册赠送", ROUTE_M . '/' . ROUTE_C . "/reg_money"),
            array("insert", "充值赠送", ROUTE_M . '/' . ROUTE_C . "/userrecharge"),
            // array("insert","黑名单会员",ROUTE_M.'/'.ROUTE_C."/blacklist"),
            // array("insert","会员UID/用户名",ROUTE_M.'/'.ROUTE_C."/lists/recoverlist"),
            // array("insert","线下充值",ROUTE_M.'/'.ROUTE_C."/down_reg"),
        );
        $this->symbol = $this->db->GetOne("SELECT * FROM `@#_currency` WHERE status='1'");
        $this->dg = $this->checkPermission("/member/member/daiguan");
    }

    //注册赠送
    public function reg_money()
    {
        $this->ment = array(
            array("insert", "积分奖励", ROUTE_M . '/' . ROUTE_C . "/member_fufen"),
            array("insert", "注册赠送", ROUTE_M . '/' . ROUTE_C . "/reg_money"),
            array("insert", "充值赠送", ROUTE_M . '/' . ROUTE_C . "/userrecharge"),
        );
        $data = $_POST;
        if ($data) {
            $money = htmlspecialchars($data['money']);
            $info = $this->db->Getone("SELECT COUNT(*) FROM `@#_reg_money`");
            if ($info) {
                $rx = $this->db->Query("UPDATE `@#_reg_money` SET `money`='$money'");
            } else {
                $rx = $this->db->Query("INSERT INTO `@#_reg_money`(`money`) VALUES('$money')");
            }
            if ($rx) {
                echo 1;
                exit;
            } else {
                echo 2;
                exit;
            }
        }
        $m = $this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
        $symbol = $this->currency['symbol_left'] ?: $this->currency['symbol_right'];
        include $this->tpl(ROUTE_M, 'member.regmoney');
    }

    //充值赠送
    public function userrecharge()
    {
        $this->ment = array(
            array("insert", "积分奖励", ROUTE_M . '/' . ROUTE_C . "/member_fufen"),
            array("insert", "注册赠送", ROUTE_M . '/' . ROUTE_C . "/reg_money"),
            array("insert", "充值赠送", ROUTE_M . '/' . ROUTE_C . "/userrecharge"),
        );
        $data = $_POST;
        if ($data) {
            if (isset($data['discount'])) {
                $discount = serialize($data['discount']);
                $this->setConfig("recharge_discount", $discount, "用户充值折扣规则");
                _message("保存成功");
            }
            $path =  dirname(__FILE__) . '/lib/userrecharge.ini.php';
            if (!is_writable($path)) _message('Please chmod  userrecharge.ini.php  to 0777 !');
            $html = "<?php \n";
            $html .= "return " . var_export($data, 1) . "\n";
            $html .= "?>";
            file_put_contents($path, $html);
            _message("保存成功");
        }
        $config = System::load_app_config("userrecharge");
        $dayinfo = array(); //累计领取奖励
        $allday = 0;

        if ($config['d_money']) {
            $dayinfo = $config['d_money'];
            foreach ($dayinfo as $key => $value) {
                $allday = $key;
            }
        }
        isset($config['r_money']) ? $r_money = $config['r_money'] : $r_money = 0;
        isset($config['money']) ? $money = $config['money'] : $money = 0;
        isset($config['isOpen']) ? $isOpen = $config['isOpen'] : $isOpen = 0;
        $symbol = $this->currency['symbol_left'] ?: $this->currency['symbol_right'];

        //充值折扣规则
        $discount = $this->getConfig('recharge_discount');
        $discount = unserialize($discount);
        include $this->tpl(ROUTE_M, 'member.recharge_money');
    }

    /**
     * 封禁、解封用户
     */
    public function ban()
    {
        $data['uid'] = $_POST['uid'];
        $data['status'] = $_POST['status'];
        $data['desc'] = $_POST['desc'];
        $data['time'] = time();
        $start = $_POST['start_day'] . ' ' . $_POST['start_time'];
        $end = $_POST['end_day'] . ' ' . $_POST['end_time'];
        $data['start_time'] = strtotime($start);
        $data['end_time'] = strtotime($end);
        if (empty($data['start_time'])) {
            $this->jsonData->error('封禁时间不能为空');
        }
        if ($data['time'] && $data['end_time'] < $data['start_time']) {
            $this->jsonData->error('封禁结束时间不能小于开始时间');
        }
        if ($data['status'] == 2) {
            unset($data['status']);
            $sql = Assemblydata($data, 'member_ban');
            $list = $this->db->Query($sql);
            $this->jsonData->success(200, '封禁成功');
        } else {
            $sql = "SELECT * FROM `@#_member_ban` WHERE `uid` = " . $data['uid'];
            $list = $this->db->GetOne($sql);
            if ($list) {
                $this->db->Query("DELETE FROM `@#_member_ban` WHERE `uid` = " . $data['uid']);
            }
            $this->jsonData->success(200, '解封成功');
        }
    }

    public function getblck()
    {
        $uid = $_POST['uid'];
        $sql = "SELECT * FROM `@#_member_blacklist` WHERE `uid` = '$uid'";
        $list = $this->db->GetOne($sql);
        $bal = System::load_sys_config('userblacktype');
        $arr = [];
        foreach ($bal as $k => $v) {
            $a = ['name' => $v, 'key' => $k];
            if ($list['type']) {
                $a['isqx'] = $list['type'] & $k;
            } else {
                $a['isqx'] = 0;
            }
            $arr[] = $a;
        }
        $data['black'] = $list;
        $data['blacklist'] = $bal;
        $data['arr'] = $arr;
        echo json_encode($data);
    }
    public function blackSendSms()
    {
        try {
            $str = 'select * from go_member_blacklist where (type&2) > 0';
            $sql = $this->db->GetList($str);
            if (count($sql) < 0) {
                return true;
            }
            $first_names = implode(',', array_column($sql, 'uid'));
            $userList = $this->db->GetList("SELECT `mobile` FROM `@#_member` where id in($first_names)");
            $phones = implode(',', array_column($userList, 'mobile'));
            $url = 'http://api.taolong.com/index/add_blacklist';
            $data = [
                'userCode' => '06c55c',
                'phone' => $phones,
            ];
            postCurl($url, $data);
        } catch (Exception $e) {

            return true;
        }
    }
    public function setblack()
    {
        $data = $_POST;
        if (!$data['lst'] || !$data['uid']) {
            echo json_encode(['code' => 0, 'msg' => '请选择拉黑内容']);
            exit();
        }
        System::load_app_fun('member');

        $type = 0;
        foreach ($data['lst'] as $v) {
            $type += $v;
        }
        $res = member_add_black($data['uid'], $type);
        if ($res) {
            echo json_encode(['code' => 1, 'data' => $res]);
            exit;
        }
        echo json_encode(['code' => 0, 'data' => $res]);
        exit;
    }
    //黑名单会员
    public function blacklist()
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        //新增拉黑号码

        //        $this->blackSendSms();
        $sql = "SELECT a.*,b.username,b.uid  FROM `go_member_blacklist` as a LEFT JOIN `go_member` as b on a.mobile = b.mobile order by a.addtime desc";
        $userlist = $this->db->GetList($sql);

        $bal = System::load_sys_config('userblacktype');
        foreach ($userlist as $key => $value) {
            $str = '';
            foreach ($bal as $k => $v) {
                $auth = $value['type'] & $k;
                if ($auth > 0) {
                    // $str.= "、".$v;
                    $str .= "、" . $this->lgs[$v];
                }
            }
            $str = trim($str, '、');
            $userlist[$key]['remarks'] = $userlist[$key]['remarks'] . "【" . $str . "】";
        }
        include $this->tpl(ROUTE_M, 'member.blacklist');
    }
    //黑名单会员删除
    public function blackdel()
    {
        $id = intval($this->segment(4));
        $this->db->Autocommit_start();
        $q2 = $this->db->Query("delete from `@#_member_blacklist` where id='$id'");

        //file_put_contents('err.txt', '$q1---'.var_export($q1,TRUE)."\n", FILE_APPEND);
        if ($q2) {
            $this->db->Autocommit_commit();
            _message("删除成功");
        } else {
            $this->db->Autocommit_rollback();
            _message("删除失败");
        }
    }
    public function blacktype()
    {
        $uid = intval($this->segment(4));
        if (!$uid || !$this->admin_can_view_member_uid($uid)) {
            _messageerror('用户ID 不存在或无权限查看');
        }
        $sql = "SELECT * FROM `@#_member_blacklist` WHERE `uid` = '$uid'";
        $list = $this->db->GetOne($sql);
        $bal = System::load_sys_config('userblacktype');
        $arr = [];
        foreach ($bal as $k => $v) {
            $v = $this->lgs[$v];
            $a = ['name' => $v, 'key' => $k];
            if ($list['type']) {
                $a['isqx'] = $list['type'] & $k;
            } else {
                $a['isqx'] = 0;
            }
            $arr[] = $a;
        }
        include $this->tpl(ROUTE_M, 'member.blacktype');
    }
    public function banunblock()
    {
        $uid = $this->segment(4);
        if (!$uid) {
            _messageerror('用户ID 不存在');
        }
        $member = $this->db->GetOne("SELECT uid FROM `@#_member` WHERE `mobile` = :m", array('m' => $uid));
        if ($member && !$this->admin_can_view_member_uid($member['uid'])) {
            _messageerror('无权限查看该会员');
        }
        $sql = "SELECT * FROM `@#_member_blacklist` WHERE `mobile` = '$uid'";
        $list = $this->db->GetOne($sql);
        $bal = System::load_sys_config('userblacktype');
        $arr = [];
        foreach ($bal as $k => $v) {
            $a = ['name' => $v, 'key' => $k];
            if ($list['type']) {
                $a['isqx'] = $list['type'] & $k;
            } else {
                $a['isqx'] = 0;
            }
            $arr[] = $a;
        }
        include $this->tpl(ROUTE_M, 'member.banunblock');
    }

    /**
     * 手机号模式添加黑名单页面
     */
    public function addblackinput()
    {
        $bal = System::load_sys_config('userblacktype');
        include $this->tpl(ROUTE_M, 'member.blackinput');
    }

    /**
     * 手机号模式添加黑名单
     */
    public function addbalckinputpost()
    {
        $mobile = $_POST['mobile'];
        if (empty($mobile)) {
            echo json_encode(['code' => 1, 'msg' => '拉黑选项不能为空']);
            exit();
        }

        $sql = "SELECT * FROM `go_member_blacklist` WHERE `mobile` ='$mobile'";
        $res = $this->db->GetOne($sql);
        if ($res) {
            echo json_encode(['code' => 0, 'msg' => '手机号已存在']);
            exit();
        }
        $arr = $_POST['arr'];
        $remarks = $_POST['remarks'];
        $time = time();
        $num = 0;
        foreach ($arr as $v) {
            if (empty($v)) {
                continue;
            }
            $num += $v;
        }
        if ($num < 1) {
            echo json_encode(['code' => 0, 'msg' => '拉黑选项不能为空']);
            exit();
        }
        System::load_app_fun('member', 'member');
        $res = member_add_black($mobile, $num, $remarks);
        if ($res) {
            echo json_encode(['code' => 1, 'msg' => '添加成功']);
            exit();
        }
        echo json_encode(['code' => 0, 'msg' => '添加失败']);
        exit();
    }
    //未注册成功会员
    public function noregmember()
    {
        $table = "@#_member";
        $num = 20;
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member` WHERE `emailcode` <> '1' and `mobilecode` <> '1' and `auto_user` != 1 and (`email` !='' OR `mobile` !='')");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $members = $this->db->GetPage("SELECT * FROM `@#_member` WHERE `emailcode` <> '1' and `mobilecode` <> '1' and `auto_user` != 1 and (`email` !='' OR `mobile` !='')", array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        include $this->tpl(ROUTE_M, 'member.lists');
    }

    public function otherregmember()
    {
        $table = "@#_member";
        $num = 20;
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member`  WHERE `emailcode` <> '1' and `mobilecode` <> '1' and `auto_user` != 1 and (`email` ='' AND `mobile` ='')");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $members = $this->db->GetPage("SELECT * FROM `@#_member` WHERE `emailcode` <> '1' and `mobilecode` <> '1' and `auto_user` != 1 and (`email` ='' AND `mobile` ='')", array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        include $this->tpl(ROUTE_M, 'member.lists');
    }

    public function recoverLists()
    {
        $ment = [
            array("recoverLists", "回收商列表", ROUTE_M . '/' . ROUTE_C . "/recoverLists"),
        ];
        $this->lists(1, $ment);
    }
    public function channelLists()
    {
        $ment = [
            array("channelLists", "渠道商列表", ROUTE_M . '/' . ROUTE_C . "/channelLists"),
            //array("channel_set", "渠道商设置", ROUTE_M . '/' . ROUTE_C . "/channel_set"),
        ];
        $this->lists(2, $ment);
    }
    public function settledLists()
    {
        $ment = [
            array("settledLists", "入驻商列表", ROUTE_M . '/' . ROUTE_C . "/settledLists"),
        ];
        $this->lists(3, $ment);
    }
    //显示会员
    public function lists($level = 0, $ment = [])
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("recoverlist", "销售员列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("blacklist", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
            array("insert", "未成功会员", ROUTE_M . '/' . ROUTE_C . "/noregmember"),
        );
        if ($ment) {
            $this->ment = $ment;
        }
        $where = $this->segment(4);
        $w = $wc = '';
        if ($where == 'del') {
            $table = "@#_member_del";
        } else {
            $table = "@#_member";
        }
        if ($where == 'recoverlist') {
            $level = 1;
            $level_id = $_POST['level'];
            if($level_id){
                $w .= ' and groupid = '.$level_id;
                $wc .= ' and groupid = '.$level_id;
            }
        }
        $w .= ' and `level` = ' . $level;
        $wc .= ' and level = ' . $level;
        $num = 20;
        $time = time();
        $sqlcoutn = "SELECT count(*) FROM `$table` WHERE (`emailcode` = '1' or `mobilecode` = '1' ) and `auto_user` != 1 $wc";
        $total = $this->db->GetCount($sqlcoutn);
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'] ?: 0;
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $start = $num * ($pagenum - 1);
        $sql = "SELECT 
                a.*,
                latest_login.ip,
                latest_login.login_time,
                latest_online.start_time,
                latest_online.end_time,
                IF($time - latest_online.end_time < 300, 1, 0) AS `online`,
                latest_online.end_time - latest_online.start_time AS diff_time
            FROM (
                SELECT uid
                FROM $table
                WHERE (emailcode = '1' OR mobilecode = '1')
                AND auto_user != 1
                $w
            ) AS base
            JOIN $table a ON base.uid = a.uid
            LEFT JOIN (
                SELECT 
                mll1.user_id,
                mll1.ip,
                mll1.login_time
                FROM go_member_login_log mll1
                JOIN (
                SELECT user_id, MAX(id) AS max_id
                FROM go_member_login_log
                WHERE type = '1'
                GROUP BY user_id
                ) mll2 ON mll1.id = mll2.max_id
            ) latest_login ON a.uid = latest_login.user_id
            LEFT JOIN (
                SELECT 
                mo1.uid,
                mo1.start_time,
                mo1.end_time
                FROM go_member_online mo1
                JOIN (
                SELECT uid, MAX(id) AS max_id
                FROM go_member_online
                GROUP BY uid
                ) mo2 ON mo1.id = mo2.max_id
            ) latest_online ON a.uid = latest_online.uid
            ORDER BY 
                `online` DESC,  
                diff_time DESC
            LIMIT $start,$num";
        $members = $this->db->GetList($sql);
        $thumb = _cfg('photo');
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
            $members[$key]['mobilenoss'] = $value['mobile'];
            $members[$key]['mobile'] = substr_replace($value['mobile'], '****', 3, 4);
            $other_money = $this->db->GetOne("SELECT `total_money` FROM `@#_member_commission` where uid={$value['uid']}");
            $members[$key]['other_money'] = isset($other_money['total_money']) ? $other_money['total_money'] : 0;
            if ($value['yaoqing']) {
                $yaoqing = $this->db->GetOne("SELECT `username`,`mobile` FROM `@#_member` where uid={$value['yaoqing']}");
                if ($yaoqing['username']) {
                    $members[$key]['y_name'] = $yaoqing['username'];
                } else {
                    $members[$key]['y_name'] = $yaoqing['mobile'];
                }
                $chan = $this->db->GetOne("select m.username,m.mobile,m.uid,m.username,m.level from `@#_member` m where m.`uid`='{$value['top_uid']}'");
                if ($chan['username']) {
                    $members[$key]['ym_uid'] = $chan['uid'];
                    $members[$key]['ym_username'] = $chan['username'];
                }
            }
            //             if($value['img'] == $thumb){
            //                 $members[$key]['img'] = '/statics/uploads/'.$thumb;
            //             }

            if (count(explode("/statics/uploads/", $value['img'])) < 2) {
                $members[$key]['img'] = '/statics/uploads/' . $value['img'];
            }
            $members[$key]['online'] = ($time - $value['end_time']) <= 300 ? "是" : "否";
            $members[$key]['online_time'] = convert($value['end_time'] - $value['start_time'], ["d", "h", "m"]);

            $members[$key]['userStatus'] = $this->db->GetOne("select `id` from `go_member_blacklist` WHERE `mobile` = '$value[mobile]'")['id'];
            //渠道商
            if ($level == 2) {
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
                $members[$key]['channel_yaoqing'] = $res['data'];
            }
        }
        $formot_array = ["money", "other_money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        //线下充值
        $is_offline_recharge = $this->getConfig('is_offline_recharge') == 2 ? false : true;
        $member_levels = $this->db->GetList("select * from `@#_member_group`");
        $level_text = array_column($member_levels, 'name', 'groupid');
        include $this->tpl(ROUTE_M, 'member.lists');
    }
    public function userloginlog()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $id_list = $this->db->Getlist("SELECT * FROM `@#_member_login_log` where user_id='$uid' ORDER BY id desc LIMIT 5");
        include $this->tpl(ROUTE_M, 'member.loginlog');
    }
    //邀请用户
    public function yaoqing()
    {
        $uid = intval($this->segment(4));
        if ($uid > 0 && !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $this->ment = array(

            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
            array("insert", "查看下级", ROUTE_M . '/' . ROUTE_C . "/yaoqing/" . $uid),
        );
        if ($uid) {
            $days_7 = time() - 7 * 24 * 3600;
            $field = "COALESCE(SUM(CASE WHEN r.id is not null and r.content <> '使用佣金充值到账户' THEN r.money ELSE 0 END), 0) as rechange_total,
                    COALESCE(SUM(CASE WHEN r.id is not null and r.time >= $days_7 THEN r.money ELSE 0 END), 0) as rechange_days_7,
                    COALESCE(SUM(CASE WHEN w.id is not null THEN w.money ELSE 0 END), 0) as cashout_total,
                    COALESCE(SUM(CASE WHEN w.id is not null and w.time >= $days_7 THEN w.money ELSE 0 END), 0) as cashout_days_7";
            $sql = "SELECT m.uid,m.username,m.mobile,m.money,m.time,latest_login.login_time,$field FROM `@#_member` m 
            left join `@#_member_rechange` r on r.uid=m.uid 
            left join `@#_member_cashout` W on W.uid=m.uid 
            LEFT JOIN (
                SELECT 
                mll1.user_id,
                mll1.ip,
                mll1.login_time
                FROM go_member_login_log mll1
                JOIN (
                SELECT user_id, MAX(id) AS max_id
                FROM go_member_login_log
                WHERE type = '1'
                GROUP BY user_id
                ) mll2 ON mll1.id = mll2.max_id
            ) latest_login ON m.uid = latest_login.user_id
            where m.yaoqing='$uid' group by m.uid";
            $id_list = $this->db->Getlist($sql);
            foreach ($id_list as $key => $value) {
                $info[$key]['id'] = $value['uid'];
                $info[$key]['uid'] = $value['uid'];
                $info[$key]['money'] = $value['money'];
                $info[$key]['score'] = $value['score'];
                $info[$key]['time'] = $value['time'];
                $info[$key]['login_time'] = $value['login_time'];
                if ($value['username']) {
                    $info[$key]['name'] = $value['username'];
                } else {
                    $info[$key]['name'] = $value['mobile'];
                }
                $money = $this->db->Getone("SELECT sum(money) as m FROM `@#_member_recodes` where uid = {$value['uid']} and type='1'");
                $info[$key]['total_money'] = sprintf("%.2f", $money['m']);
                $info[$key]['rechange_total'] = $value['rechange_total'];
                $info[$key]['cashout_total'] = $value['cashout_total'];
                $info[$key]['rechange_days_7'] = $value['rechange_days_7'];
                $info[$key]['cashout_days_7'] = $value['cashout_days_7'];
            }
        } else {
            _message('参数错误');
        }
        include $this->tpl(ROUTE_M, 'member.yaoqing');
    }

    //账户明细
    public function zhmx()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $this->ment = array(

            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
            array("insert", "账户明细", ROUTE_M . '/' . ROUTE_C . "/zhmx/" . $uid),
        );
        $data =  $_POST;
        $mintime = strtotime($data['posttime1']);
        $maxtime = strtotime($data['posttime2']);
        if ($mintime) {
            $select = "time >= $mintime";
        }
        if ($maxtime) {
            $select = "time <= $maxtime";
        }
        if ($mintime && $maxtime) {
            $select = "time <= $maxtime and time >= $mintime";
        }
        if (!$select) {
            $select = '1';
        }
        if ($uid) {
            $info1 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE  uid = '$uid'  and pay != '积分' and $select order by time desc ");
            $info2 = $this->db->GetList("SELECT * FROM `@#_member_rechange` WHERE  uid = '$uid' and pay != '积分'and content = '充值'  and $select order by time desc ");
            //		    var_dump($info2);exit;and status = '2'and status = '2'
        }
        $account_cz = $this->db->GetOne("SELECT sum(`money`) as cz_money FROM `@#_member_rechange` where uid = '$uid' and content = '充值'  and pay != '积分' and type = '1' and $select")['cz_money'];
        $account_cz = $account_cz ? $account_cz : 0;
        $chashout = $this->db->GetOne("SELECT sum(`money`) as money FROM `@#_member_cashout` where uid = '$uid' and auditstatus = 1 and $select")['money'];
        $chashout = $chashout ? $chashout : 0;
        $info = array_merge($info1, $info2);
        foreach ($info as $key => $value) {
            $info[$key]['time'] = date('Y-m-d H:i:s', $value['time']);
            if ($value['code']) {
                $code = _cfg('pay_prefix') . '_' . $value['uid'] . $value['code'];
                $info[$key]['code'] = $code;
            }
        }
        $zhmx['account_cz'] = $account_cz;
        $zhmx['chashout'] = $chashout;
        $formot_array = ["account_cz", "chashout"];
        data_format($zhmx, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.zhmx');
    }
    //账户明细添加备注
    public function massage()
    {
        $id = $this->segment(4);
        $type = $this->segment(5);
        $data = $_POST;
        if ($data) {
            if ($type == 1) {
                $sql = "update `@#_member_account` set `message` = '$data[remark]' where `id` = $data[id]";
            } else if ($type == 2) {
                $sql = "update `@#_member_rechange` set `message` = '$data[remark]' where `id` = $data[id]";
            }
            $result = $this->db->Query($sql);
            if ($result) {
                echo json_encode(['code' => 1]);
            } else {
                //                var_dump($sql);exit;
                echo json_encode(['code' => 2]);
            }
            exit;
        }
        include $this->tpl(ROUTE_M, 'member.massage');
    }
    //用户充值金额统计
    public function  statistics()
    {
        $id = $this->segment(4);
        $mintime = strtotime(date('Y-m-d') . ' 00:00:00');
        $maxtime = strtotime(date('Y-m-d') . ' 23:59:59');
        $sql = 0;
        $sql = $this->db->GetOne("select sum(`money`) as money from `@#_member_recodes` where `uid` = '$id' and type = '-2' and time <=$maxtime and time >= $mintime")['money'];

        $sql += $this->db->GetOne("select sum(`money`) as money from `@#_member_account` where `uid` = '$id'and pay != '积分' and type = '1' and time <=$maxtime and time >= $mintime")['money'];

        $sql -= $this->db->GetOne("select sum(`money`) as money from `@#_member_recodes` where `uid` = '$id' and type <> '-2' and time <=$maxtime and time >= $mintime")['money'];
        $sql -= $this->db->GetOne("select sum(`money`) as money from `@#_member_account` where `uid` = '$id'and pay != '积分' and type = '-1' and time <=$maxtime and time >= $mintime")['money'];
        $recharge['sum_money'] = $sql ?: 0;
        $formot_array = ['sum_money'];
        data_format($recharge, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.personal');
    }
    //账户代管
    public function daiguan()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $userinfo = $this->db->GetOne("SELECT * FROM `@#_member` where uid='$uid'");
        _setcookie("uid", _encrypt($uid), 60 * 60 * 24 * 7);
        _setcookie("ushell", _encrypt(md5($userinfo['uid'] . $userinfo['password'] . $userinfo['mobile'] . $userinfo['email'])), 60 * 60 * 24 * 7);
        header("location:" . G_WEB_PATH . "/index/pages/users/logging/index?custody=$uid");
    }

    //线下充值
    public function down_reg()
    {
        $this->ment = array(
            array("insert", "线下充值", ROUTE_M . '/' . ROUTE_C . "/down_reg")
        );
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
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $members = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        foreach ($members as $k => $v) {
            $members[$k]['time'] = $v['time'] ? date('Y-m-d H:i:s', $v['time']) : '无';
        }
        $formot_array = ["money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.downreg');
    }

    //修改会员金额
    public function upmoney()
    {
        $is_offline_recharge = $this->getConfig("is_offline_recharge") == 2 ? false : true;
        if (!$is_offline_recharge) {
            $this->jsonData->error(-1);
        }
        $data = $_REQUEST;
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
                $this->jsonData->error(5);
            }
            if (!$this->verifyExamine($secure_password)) {
                $this->jsonData->error(5);
            }
            $time = time();
            $userinfo = $this->db->Getone("SELECT `money` FROM `@#_member` where uid='$uid'");
            $this->db->Autocommit_start();
            if ($money == 0) {
                $this->jsonData->error(1);
            } elseif ($money < 0 || $type == 2) {
                $m = abs($money);
                if ($m > $userinfo['money']) {
                    $this->jsonData->error(2);
                }
                $content = '线下扣款';
                $admin_id = $this->AdminInfo['uid'];
                $admin_name = $this->AdminInfo['name'];
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
                        "operat_id" => $this->AdminInfo['uid'],
                        "operat_name" => $this->AdminInfo['username'],
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
                $admin_id = $this->AdminInfo['uid'];
                $admin_name = $this->AdminInfo['name'];
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
                        "operat_id" => $this->AdminInfo['uid'],
                        "operat_name" => $this->AdminInfo['username'],
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

    //添加会员
    public function insert()
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        $mysql_model = System::load_sys_class('model');
        $member_allgroup = $mysql_model->Getlist("select groupid,name from `@#_member_group`");
        $web1 = $this->db->GetOne("SELECT `value` FROM `@#_config` WHERE `name`='photo'");
        $web_logo = $web1['value'];
        if (isset($_POST['submit'])) {
            $username = htmlspecialchars(trim($_POST['username']));
            if (empty($username)) {
                _message("用户名不能为空");
                exit;
            }
            $password = htmlspecialchars(trim($_POST['password']));
            if (empty($password)) {
                _message("密码不能为空");
                exit;
            } else {
                $password = md5($password);
            }

            $img = $_POST['upimage'];
            $email = htmlspecialchars(trim($_POST['email']));
            $mobile = htmlspecialchars(trim($_POST['mobile']));
            $money = htmlspecialchars(trim($_POST['money'])) ?: 0;
            $jingyan = htmlspecialchars(trim($_POST['jingyan'])) ?: 0;
            $score = htmlspecialchars(trim($_POST['score'])) ?: 0;
            $emailcode = htmlspecialchars(trim($_POST['emailcode']));
            $mobilecode = htmlspecialchars(trim($_POST['mobilecode']));
            $qianming = htmlspecialchars(trim($_POST['qianming']));
            $membergroup = htmlspecialchars(trim($_POST['membergroup']));
            $level = htmlspecialchars(trim($_POST['level'])) ?: 0;
            $time = time();
            do {
                $v_id = rand(100000, 1000000000);
                $s = $this->db->GetOne("select * from `go_member` where v_id = $v_id");
                if (!$s) {
                    break;
                }
            } while ($s);
            $sql = "INSERT INTO `@#_member` (username,img,email,mobile,password,money,jingyan,score,emailcode,mobilecode,qianming,groupid,time,level,v_id) value ('$username','$img','$email','$mobile','$password','$money','$jingyan','$score','$emailcode','$mobilecode','$qianming','$membergroup','$time','$level','$v_id')";

            $this->db->Query($sql);
            if ($this->db->affected_rows()) {
                _message("增加成功");
            } else {
                _message("增加失败");
            }
        }
        include $this->tpl(ROUTE_M, 'member_insert');
    }

    //修改会员
    public function modify()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看或修改该会员", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("modify", "会员修改", ROUTE_M . '/' . ROUTE_C . "/modify/" . $uid),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        $member = $this->db->Getone("select * from `@#_member` where `uid`='$uid'");
        $thumb = _cfg('photo');
        if ($member['img'] == $thumb) {
            $member['img'] = '/statics/uploads/' . $thumb;
        }

        if (count(explode("/statics/uploads/", $member['img'])) < 2) {
            $member['img'] = '/statics/uploads/' . $member['img'];
        }
        $member_group = $this->db->Getone("select name from `@#_member_group` where `groupid`=" . $member['groupid'] . "");
        $member_allgroup = $this->db->Getlist("select groupid,name from `@#_member_group`");
        if (!empty($member['addgroup'])) {
            $member['addgroup'] = trim($member['addgroup'], ',');
            $addgroup = explode(',', $member['addgroup']);
            for ($i = 0; $i < count($addgroup); $i++) {
                $quanzi = $this->db->Getone("select title from `@#_quanzi` where `id`=" . $addgroup[$i] . "");
                $quanziname[] = $quanzi['title'];
            }
        }
        //用户地址
        $address = $this->db->GetList("select * from `@#_member_dizhi` where uid = $uid");
        //用户银行卡
        $bank = $this->db->GetList("select * from `@#_member_bank_card` where uid=$uid order by time desc");
        if (isset($_POST['submit'])) {

            $img = str_replace('/statics/uploads/', '', $_POST['upimage']);
            $username = htmlspecialchars(trim($_POST['username']));
            $email = htmlspecialchars(trim($_POST['email']));
            $mobile = htmlspecialchars(trim($_POST['mobile']));
            $password = htmlspecialchars(trim($_POST['password']));
            //用户名和手机号是否重复
            $res = $this->db->GetOne("select uid,username,mobile from `@#_member` where (username='$username' or mobile='$mobile') and uid!=$uid");
            if (!empty($res)) {
                if ($res['username'] == $username) {
                    _messageerror('用户名存在');
                } else {
                    _messageerror("手机号已注册");
                }
            }
            if (empty($password)) {
                $password = $member['password'];
            } else {
                $password = md5($password);
            }
            $jingyan = htmlspecialchars(trim($_POST['jingyan']));
            $score = htmlspecialchars(trim($_POST['score']));
            $emailcode = htmlspecialchars(trim($_POST['emailcode']));
            $mobilecode = htmlspecialchars(trim($_POST['mobilecode']));
            $level = htmlspecialchars(trim($_POST['level'])); //vip标识
            $qianming = htmlspecialchars(trim($_POST['qianming']));
            $membergroup = htmlspecialchars(trim($_POST['membergroup']));
            $sql = "UPDATE `@#_member` SET `username`='$username',`email`='$email',`mobile`='$mobile',`password`='$password',`jingyan`='$jingyan',`score`='$score',`emailcode`='$emailcode',`mobilecode`='$mobilecode',`level`='$level',`img`='$img',`groupid`='$membergroup',`qianming`='$qianming' WHERE `uid`='$uid'";
            $address = $_POST['address'];
            if ($address) {
                foreach ($address as $vl) {
                    $address_sql = Assemblydata($vl, "member_dizhi", "up", [['uid', $uid]]);
                    $res = $this->db->Query($address_sql);
                }
            }
            $bank = $_POST['bank'];
            if ($bank) {
                foreach ($bank as $vl) {
                    $address_sql = Assemblydata($vl, "member_bank_card", "up", [['uid', $uid]]);
                    $res = $this->db->Query($address_sql);
                }
            }
            $res = $this->db->Query($sql);
            if ($res) {
                _message("修改成功");
            } else {
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M, 'member_modify');
    }

    //恢复会员
    public function huifu()
    {
        $uid = intval($this->segment(4));
        $this->db->Autocommit_start();
        $q1 = $this->db->Query("insert into `@#_member` select * from `@#_member_del` where uid='$uid'");
        $q2 = $this->db->Query("delete from `@#_member_del` where uid='$uid'");
        if ($q1 && $q2) {
            $this->db->Autocommit_commit();
            _message("恢复成功");
        } else {
            $this->db->Autocommit_rollback();
            _message("恢复失败");
        }
    }

    //删除会员
    public function del()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限操作该会员", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $this->db->Autocommit_start();
        $q1 = $this->db->Query("insert into `@#_member_del` select * from `@#_member` where uid='$uid'");

        $q2 = $this->db->Query("delete from `@#_member` where uid='$uid'");
        $q3 = $this->db->Query("delete from `@#_member_band` where `b_uid`='$uid'");
        $q4 = $this->db->Query("delete from `@#_member_blacklist` where `uid`='$uid'");
        $q4 = $this->db->Query("delete from `@#_member_auth` where `uid`='$uid'");

        //file_put_contents('err.txt', '$q1---'.var_export($q1,TRUE)."\n", FILE_APPEND);
        if ($q1 && $q2) {
            $this->db->Autocommit_commit();
            _message("删除成功");
        } else {
            $this->db->Autocommit_rollback();
            _message("删除失败");
        }
    }
    public function del_pl()
    {
        $arr = $_POST['ids'];
        //file_put_contents('err.txt', '$arr---'.var_export($arr,TRUE)."\n", FILE_APPEND);
        $newstr = substr($arr, 0, strlen($arr) - 1);
        $type = $this->db->GetOne("SELECT count(`username`) from `@#_member` where uid IN($newstr)");
        //file_put_contents('err.txt', '$type---'.var_export($type,TRUE)."\n", FILE_APPEND);
        $this->db->Autocommit_start();
        if ($type['count(`username`)'] > 0) {
            $q1 = $this->db->Query("insert into `@#_member_del` select * from `@#_member` where uid IN($newstr)");
            $q2 = $this->db->Query("delete from `@#_member` where uid IN($newstr)");
            //file_put_contents('err.txt', '$type1---'.var_export(1,TRUE)."\n", FILE_APPEND);
            if ($q1 && $q2) {
                $this->db->Autocommit_commit();
                echo '0';
            } else {
                $this->db->Autocommit_rollback();
                echo '1';
            }
        } else {
            //file_put_contents('err.txt', '$type2---'.var_export(2,TRUE)."\n", FILE_APPEND);
            $q2 = $this->db->Query("delete from `@#_member_del` where uid IN($newstr)");
            if ($q2) {
                $this->db->Autocommit_commit();
                echo '0';
            } else {
                $this->db->Autocommit_rollback();
                echo '1';
            }
        }
    }
    //消费记录批量删除
    public function xf_del()
    {
        $arr = $_POST[ids];
        $newstr = substr($arr, 0, strlen($arr) - 1);
        // file_put_contents('err.txt', '$newstr---'.var_export($newstr,TRUE)."\n", FILE_APPEND);
        $q2 = $this->db->Query("delete from `@#_member_go_record` where id IN($newstr)");
        if ($q2) {
            $this->db->Autocommit_commit();
            echo '0';
        } else {
            $this->db->Autocommit_rollback();
            echo '1';
        }
    }

    //充值记录批量删除
    public function cz_del()
    {
        $arr = $_POST[ids];
        $newstr = substr($arr, 0, strlen($arr) - 1);
        //file_put_contents('err.txt', '$newstr---'.var_export($newstr,TRUE)."\n", FILE_APPEND);
        $ql = $this->db->Query("delete from `@#_member_rechange` where uid IN($newstr)");
        if ($ql) {
            $this->db->Autocommit_commit();
            echo '0';
        } else {
            $this->db->Autocommit_rollback();
            echo '1';
        }
    }

    public function del_true()
    {
        $uid = intval($this->segment(4));
        $q1 = $this->db->Query("delete from `@#_member_del` where uid='$uid'");
        if ($q1) {
            _message("删除成功");
        } else {
            _message("删除失败");
        }
    }

    //查找会员
    public function select()
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        if (isset($_POST['sososubmit'])) {
            $sousuo = htmlspecialchars(trim($_POST['sousuo']));
            $content = htmlspecialchars(trim($_POST['content']));
            if (empty($sousuo) || empty($content)) {
                _message("参数错误");
            }

            $members = array();
            $time = time();
            $join = "LEFT JOIN `@#_member_online` l on a.uid = l.uid and l.id = (select max(id) from `@#_member_online` where `uid` = a.uid)";
            $join .= " LEFT JOIN `@#_member` ym on a.top_uid = ym.uid";
            $fields = "l.start_time,l.end_time,if($time - l.end_time <300,1,0) as `online`, l.end_time - l.start_time as diff_time";
            $fields .= ",ym.username as ym_username,ym.uid as ym_uid";
            if ($sousuo == 'id') {
                $members = $this->db->GetList("SELECT a.*,$fields FROM `@#_member` a $join WHERE a.`uid` = '$content'");
            }
            if ($sousuo == 'v_id') {
                $members = $this->db->GetList("SELECT a.*,$fields FROM `@#_member` a $join WHERE a.`v_id` = '$content'");
            }
            if ($sousuo == 'nickname') {
                $members = $this->db->GetList("SELECT a.*,$fields FROM `@#_member` a $join WHERE a.`username`  LIKE '%$content%'");
            }
            if ($sousuo == 'email') {
                $members = $this->db->GetList("SELECT a.*,$fields FROM `@#_member` a $join WHERE a.`email` LIKE '%$content%'");
            }
            if ($sousuo == 'mobile') {
                $members = $this->db->GetList("SELECT a.*,$fields FROM `@#_member` a $join WHERE a.`mobile` LIKE '%$content%'");
            }

            foreach ($members as $key => $value) {
                $other_money = $this->db->GetOne("SELECT `total_money` FROM `@#_member_commission` where uid={$value['uid']}");
                $members[$key]['other_money'] = isset($other_money['total_money']) ? $other_money['total_money'] : 0;
                $log = $this->db->GetOne("select `login_time` from `@#_member_login_log` where user_id = {$value['uid']} order by id desc");
                $members[$key]['login_time'] = $log['login_time'];
                $members[$key]['online'] = $value["online"] ? "是" : "否";
                $members[$key]['diff_time'] = convert($value["diff_time"]);
            }
        }
        $formot_array = ["money", "other_money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member_select');
    }

    //会员组
    public function member_group()
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        $members = $this->db->Getlist("select * from `@#_member_group` where 1");
        include $this->tpl(ROUTE_M, 'member.member_group');
    }

    //修改会员组
    public function group_modify()
    {
        $id = intval($this->segment(4));
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_group", "会员等级修改", ROUTE_M . '/' . ROUTE_C . "/group_modify/" . $id),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        $members = $this->db->Getone("select * from `@#_member_group` where `groupid`='$id'");
        if (isset($_POST['submit'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $jingyan_start = htmlspecialchars(trim($_POST['jingyan_start']));
            $jingyan_end = htmlspecialchars(trim($_POST['jingyan_end']));
            $rake_back = htmlspecialchars(trim($_POST['rake_back'])) ?: 0;
            if (empty($name) || $jingyan_start < 0  || empty($jingyan_end)) {
                _message('会员组或者经验值不能为空');
            } elseif ($jingyan_start >= $jingyan_end) {
                _message('开始经验不能大于结束经验');
            } elseif ($jingyan_end <= $jingyan_start) {
                _message('结束经验不能小于开始经验');
            }
            $sql = "UPDATE `@#_member_group` SET `name`='$name',`jingyan_start`='$jingyan_start', `jingyan_end`='$jingyan_end',`rake_back` = '$rake_back' WHERE `groupid`='$id'";
            $this->db->Query($sql);
            if ($this->db->affected_rows()) {
                _message("修改成功");
            } else {
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M, 'member.group_modify');
    }

    //删除会员组
    public function group_del()
    {
        $id = intval($this->segment(4));
        $sql = "DELETE FROM `@#_member_group` WHERE `groupid`='$id'";
        $this->db->Query($sql);
        if ($this->db->affected_rows()) {
            _message("删除成功");
        } else {
            _message("删除失败");
        }
    }

    //增加会员组
    public function member_add_group()
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),
        );
        if (isset($_POST['submit'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $jingyan_start = htmlspecialchars(trim($_POST['jingyan_start']));
            $jingyan_end = htmlspecialchars(trim($_POST['jingyan_end']));
            if (empty($name) || empty($jingyan_start) || empty($jingyan_end)) {
                _message('会员组或者经验值不能为空');
            }
            $sql = "INSERT INTO `@#_member_group` (`name`,`jingyan_start`,`jingyan_end`) value ('$name','$jingyan_start','$jingyan_end')";
            $this->db->Query($sql);
            if ($this->db->affected_rows()) {
                _message("增加成功");
            } else {
                _message("增加失败");
            }
        }
        include $this->tpl(ROUTE_M, 'add_membergroup');
    }

    //银行卡列表
    public function bank()
    {
        $this->ment = array(
            array("bank", "银行列表", ROUTE_M . '/' . ROUTE_C . "/bank"),
            array("bankAdd", "添加银行", ROUTE_M . '/' . ROUTE_C . "/bankAdd"),

        );
        $sql = Assemblydata([], "bank", "sel");
        $num = 20;
        $total = $this->db->GetCount($sql);
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $list = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        foreach ($list as &$vl) {
            $vl['logo_url'] = G_UPLOAD_PATH . "/" . $vl['logo'];
        }
        $language = $this->getLanguageList(1);
        $lgs = array_column($language, "country", "lg_value");
        foreach ($list as &$vl) {
            $vl['language'] = $lgs[$vl['language']];
        }
        data_format($list, ['update_time'], "times");
        include $this->tpl(ROUTE_M, 'member_config');
    }

    public function bank_status_up()
    {
        $id = htmlspecialchars($_POST['id']);
        $status = htmlspecialchars($_POST['status']);
        if ($id) {
            $res = $this->db->Query("UPDATE `@#_bank` SET `status`='$status' WHERE `id`='$id'");
            if ($res) {
                echo json_encode(['code' => 1, 'msg' => '修改成功']);
                exit;
            } else {
                echo json_encode(['code' => 2, 'msg' => '修改失败']);
                exit;
            }
        }
    }

    public function bankorder()
    {
        if ($this->segment(4) == 'dosubmit') {
            foreach ($_POST['listorders'] as $id => $listorder) {
                $id = safe_replace($id);
                $time = time();
                $this->db->Query("UPDATE `@#_bank` SET `sort` = '$listorder',`update_time` = '$time' where `id` = '$id'");
            }
            _message("排序更新成功");
        } else {
            _messageerror("请排序");
        }
    } //

    public function bankAdd()
    {
        $this->ment = array(
            // array("lists","会员列表",ROUTE_M.'/'.ROUTE_C."/lists"),
            // array("lists","查找会员",ROUTE_M.'/'.ROUTE_C."/select"),
            // array("insert","添加会员",ROUTE_M.'/'.ROUTE_C."/insert"),
            // array("insert","会员回收站",ROUTE_M.'/'.ROUTE_C."/lists/del"),
            // // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            // array("member_group","会员等级",ROUTE_M.'/'.ROUTE_C."/member_group"),
            // array("member_add_group","添加会员等级",ROUTE_M.'/'.ROUTE_C."/member_add_group"),
            // array("insert","收货商列表",ROUTE_M.'/'.ROUTE_C."/lists/recoverlist"),
            // array("insert","黑名单会员",ROUTE_M.'/'.ROUTE_C."/blacklist"),
            array("bank", "银行列表", ROUTE_M . '/' . ROUTE_C . "/bank"),
            array("bankAdd", "添加银行", ROUTE_M . '/' . ROUTE_C . "/bankAdd"),

        );
        $languages = $this->getLanguageList(1);
        if (isset($_POST['submit'])) {
            $data = [
                "name" => htmlspecialchars($_POST['name']),
                "logo" => htmlspecialchars($_POST['logo']),
                "bg_color" => htmlspecialchars($_POST['bg_color']),
                "status" => htmlspecialchars($_POST['status']),
                "language" => htmlspecialchars($_POST['language']),
                "create_time" => time(),
                "update_time" => time(),
            ];

            $sql = Assemblydata($data, "bank");
            $res = $this->db->Query($sql);
            if ($res) {
                _message("ok", WEB_PATH . ROUTE_M . '/' . ROUTE_C . "/bank");
            } else {
                _messageerror("失败");
            }
        }
        include $this->tpl(ROUTE_M, 'member_bankadd');
    }
    public function bankEdit()
    {
        $this->ment = array(
            // array("lists","会员列表",ROUTE_M.'/'.ROUTE_C."/lists"),
            // array("lists","查找会员",ROUTE_M.'/'.ROUTE_C."/select"),
            // array("insert","添加会员",ROUTE_M.'/'.ROUTE_C."/insert"),
            // array("insert","会员回收站",ROUTE_M.'/'.ROUTE_C."/lists/del"),
            // // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            // array("member_group","会员等级",ROUTE_M.'/'.ROUTE_C."/member_group"),
            // array("member_add_group","添加会员等级",ROUTE_M.'/'.ROUTE_C."/member_add_group"),
            // array("insert","收货商列表",ROUTE_M.'/'.ROUTE_C."/lists/recoverlist"),
            // array("insert","黑名单会员",ROUTE_M.'/'.ROUTE_C."/blacklist"),
            array("bank", "银行列表", ROUTE_M . '/' . ROUTE_C . "/bank"),
            array("bankAdd", "添加银行", ROUTE_M . '/' . ROUTE_C . "/bankAdd"),
            array("bankEdit", "编辑", ROUTE_M . '/' . ROUTE_C . "/bankEdit", 2),
        );

        $id = $this->segment(4);
        $res = $this->db->GetOne("select * from `@#_bank` where `id` = $id");
        $res['logo_url'] = G_UPLOAD_PATH . "/" . $res['logo'];
        $languages = $this->getLanguageList(1);
        if (isset($_POST['submit'])) {
            $data = [
                "name" => htmlspecialchars($_POST['name']),
                "logo" => htmlspecialchars($_POST['logo']),
                "bg_color" => htmlspecialchars($_POST['bg_color']),
                "status" => htmlspecialchars($_POST['status']),
                "language" => htmlspecialchars($_POST['language']),
                // "create_time"=>time(),
                "update_time" => time(),
            ];
            $sql = Assemblydata($data, "bank", "up", [["id", $id]]);
            $res = $this->db->Query($sql);
            if ($res) {
                _message("ok", WEB_PATH . ROUTE_M . '/' . ROUTE_C . "/bank");
            } else {
                _messageerror("失败");
            }
        }
        include $this->tpl(ROUTE_M, 'member_bankadd');
    }

    public function bankDel()
    {
        $id = $this->segment(4);
        if (!$id) {
            _messageerror();
        }
        $res = $this->db->Query("delete from `@#_bank` where `id` = $id");
        _message("ok");
    }

    public function config()
    {
        $this->ment = array(
            array("lists", "会员列表", ROUTE_M . '/' . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . '/' . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . '/' . ROUTE_C . "/insert"),
            array("insert", "会员回收站", ROUTE_M . '/' . ROUTE_C . "/lists/del"),
            // array("insert","昵称限制",ROUTE_M.'/'.ROUTE_C."/config"),
            array("member_group", "会员等级", ROUTE_M . '/' . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员等级", ROUTE_M . '/' . ROUTE_C . "/member_add_group"),
            array("insert", "收货商列表", ROUTE_M . '/' . ROUTE_C . "/lists/recoverlist"),
            array("insert", "黑名单会员", ROUTE_M . '/' . ROUTE_C . "/blacklist"),

        );
        $nickname = $this->db->GetOne("select * from `@#_caches` where `key` = 'member_name_key' limit 1");
        if (isset($_POST['submit'])) {
            $nicknames = htmlspecialchars($_POST['nickname']);
            $nicknames = trim($nicknames, ",");
            $nicknames = str_ireplace(" ", '', $nicknames);
            if ($nickname) {
                $this->db->Query("UPDATE `@#_caches` SET `value` = '$nicknames' where `key` = 'member_name_key'");
            } else {
                $this->db->Query("INSERT INTO `@#_caches` (`key`,`value`) value ('member_name_key','$nicknames')");
            }
            _message("操作成功");
        }
        $nickname = $nickname['value'];
        include $this->tpl(ROUTE_M, 'member_config');
    }

    /**
     * 渠道商设置
     * 
     */
    public function channel_set()
    {
        $this->ment = array(
            array("channel_set", "渠道商设置", ROUTE_M . '/' . ROUTE_C . "/channel_set"),
            array("insert", "会员福利", ROUTE_M . '/' . ROUTE_C . "/member_fufen"),
        );
        $channel = $this->getConfig('channel_set');
        $channel = mb_unserialize($channel);
        if (isset($_POST['submit'])) {
            $data  = [
                "fufen_yongjin" => $_POST['fufen_yongjin'] ?: 0,
                "fufen_yongjin_1" => $_POST['fufen_yongjin_1'] ?: 0,
                "f_visituser" => $_POST['f_visituser'] ?: 0,
            ];
            $channel = serialize($data);
            $this->setConfig('channel_set', $channel);
            _message("操作成功");
        }
        include $this->tpl(ROUTE_M, 'member.channel_set');
    }

    public function recover_commission_set(){
        $this->ment = array();
        $uid = $this->segment(4);
        $res = $this->db->getOne("select * from `@#_member_commission_config` where uid = $uid");
        if($res){
            $res['commission'] = json_decode($res['commission'],true);
            $res['commission']['fufen_yongjin'] = $res['commission']['fufen_yongjin']*100;
            $res['commission']['fufen_yongjin_1'] = $res['commission']['fufen_yongjin_1']*100;
            $res['yaoqing'] = json_decode($res['yaoqing'],true);
        }
        if (isset($_POST['submit'])) {
            $fufen_yongjin = _htmtocode($_POST['fufen_yongjin'])?:0;
            $fufen_yongjin_1 = _htmtocode($_POST['fufen_yongjin_1'])?:0;
            $f_visituser = _htmtocode($_POST['f_visituser'])?:0;
            $commission = [
                "fufen_yongjin" => $fufen_yongjin/100,
                "fufen_yongjin_1" => $fufen_yongjin_1/100,
            ];
            $yaoqing = [
                "f_visituser" => $f_visituser/100,
            ];
            $data  = [
                "uid"=> $uid,
                "commission" => json_encode($commission),
                "yaoqing" => json_encode($yaoqing),
            ];
            if($res){
                $sql = Assemblydata($data, "member_commission_config", 'up', [["uid", $uid]]);
            }else{
                $sql = Assemblydata( $data, 'member_commission_config');
            }
            $this->db->Query($sql);
            _message("操作成功");
        }
        include $this->tpl(ROUTE_M, 'member.recover_set');
    }

    public function channel_yaoqing_set()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $res = $this->db->getOne("select * from `@#_channel_yaoqing` where uid = $uid");
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            if ($res) {
                $sql = Assemblydata(["data" => $data], "channel_yaoqing", 'up', [["uid", $uid]]);
            } else {
                $sql = Assemblydata(["uid" => $uid, "data" => $data], 'channel_yaoqing');
            }
            $this->db->Query($sql);
            _message("操作成功");
        }
        include $this->tpl(ROUTE_M, 'member.channel_yaoqing_set');
    }

    /**
     * 会员福利
     *  
     * */
    public function member_fufen()
    {
        $this->ment = array(
            array("insert", "会员福利", ROUTE_M . '/' . ROUTE_C . "/member_fufen"),
            array("channel_set", "渠道商设置", ROUTE_M . '/' . ROUTE_C . "/channel_set"),
            // array("insert","注册赠送",ROUTE_M.'/'.ROUTE_C."/reg_money"),
            // array("insert","充值赠送",ROUTE_M.'/'.ROUTE_C."/userrecharge"),
        );
        if (isset($_POST['submit'])) {
            $path =  dirname(__FILE__) . '/lib/user_fufen.ini.php';
            if (!is_writable($path)) _message('Please chmod  user_fufen.ini.php  to 0777 !');
            $f_overziliao = intval(trim($_POST['f_overziliao'])); //昵称完善奖励的积分
            $f_shoppay = intval(trim($_POST['f_shoppay']));       //商品购买奖励的积分
            $f_phonecode = intval(trim($_POST['f_phonecode']));       //手机验证奖励的积分
            $f_visituser = intval(trim($_POST['f_visituser']));       //邀请好友奖励的积分
            $shaidan_money = intval(trim($_POST['shaidan_money']));       //晒单奖励的积分
            //以上是积分，一下是经验值
            $z_overziliao = intval(trim($_POST['z_overziliao']));     //
            $z_shoppay = intval(trim($_POST['z_shoppay']));           //
            $z_phonecode = intval(trim($_POST['z_phonecode']));       //
            $z_visituser = intval(trim($_POST['z_visituser']));       //

            $fufen_yuan = intval(trim($_POST['fufen_yuan']));         //1元等于多少积分
            $fufen_yongjins = $_POST['fufen_yongjin'];     //邀请好友返佣比例一级
            $fufen_yongjin_1 = floatval(trim($_POST['fufen_yongjin_1']));     //邀请好友返佣比例二级
            $fufen_yongjintx = floatval(trim($_POST['fufen_yongjintx']));     //提现一百元佣金所产生的手续费
            $fufen_yongjin_withdrawal_min = floatval(trim($_POST['fufen_yongjin_withdrawal_min']));     //提现最小金额
            $fufen_yongjin_withdrawal_max = floatval(trim($_POST['fufen_yongjin_withdrawal_max']));     //提现最大金额
            $yongjintx_type = floatval(trim($_POST['yongjintx_type']));     //提现渠道
            $yongjintx_pay = trim($_POST['yongjintx_pay']);     //支付通道
            $f_sign_in = floatval(trim($_POST['f_sign_in'])); //签到奖励
            foreach ($fufen_yongjins as $key => $val) {
                $fufen_yongjin[$key] = number_format(trim($val),2);
            }
            $fufen_yongjin = var_export($fufen_yongjin,true);
            if ($fufen_yuan <= 0) {
                _message('积分输入有错误');
            }
            $jieguo = $fufen_yuan % 10;
            if ($jieguo != 0 && $fufen_yuan != 1) {
                _message('积分输入有错误');
            }
            $html = <<<HTML
<?php 
return array (	
	'f_overziliao' => '$f_overziliao',
	'f_shoppay' => '$f_shoppay',
	'f_phonecode' => '$f_phonecode',
	'f_visituser' => '$f_visituser'	,
	'z_overziliao' => '$z_overziliao',
	'z_shoppay' => '$z_shoppay',
	'z_phonecode' => '$z_phonecode',
	'z_visituser' => '$z_visituser',
	'fufen_yuan' => '$fufen_yuan',
	'fufen_yongjin' => $fufen_yongjin,
	'fufen_yongjin_1' => '$fufen_yongjin_1',
	'fufen_yongjintx' => '$fufen_yongjintx',
	'fufen_yongjin_withdrawal_min' => '$fufen_yongjin_withdrawal_min',
	'fufen_yongjin_withdrawal_max' => '$fufen_yongjin_withdrawal_max',
	'yongjintx_type' => '$yongjintx_type',
	'yongjintx_pay' => '$yongjintx_pay',
	'shaidan_money' => '$shaidan_money',
    'f_sign_in' => '$f_sign_in',
);
?>
HTML;
            file_put_contents($path, $html);
            //注册赠送
            $reg_money = htmlspecialchars($_POST['reg_money']);
            $info = $this->db->Getone("SELECT COUNT(*) FROM `@#_reg_money`");
            if ($info) {
                $rx = $this->db->Query("UPDATE `@#_reg_money` SET `money`='$reg_money'");
            } else {
                $rx = $this->db->Query("INSERT INTO `@#_reg_money`(`money`) VALUES('$reg_money')");
            }
            //充值赠送
            $data = $_POST['rech'];
            $path =  dirname(__FILE__) . '/lib/userrecharge.ini.php';
            if (!is_writable($path)) _message('Please chmod  userrecharge.ini.php  to 0777 !');
            $html = "<?php \n";
            $html .= "return " . var_export($data, 1) . "\n";
            $html .= "?>";
            file_put_contents($path, $html);
            // 充值折扣
            if (isset($_POST['discount'])) {
                $discount = serialize($_POST['discount']);
                $this->setConfig("recharge_discount", $discount, "用户充值折扣规则");
            }
            _message("修改成功!");
        }
        $config = System::load_app_config("user_fufen");
        //注册赠送
        $m = $this->db->Getone("SELECT money FROM `@#_reg_money` WHERE 1");
        // 充值赠送
        $rech = System::load_app_config("userrecharge");
        $dayinfo = array(); //累计领取奖励
        $allday = 0;
        if ($rech['d_money']) {
            $dayinfo = $rech['d_money'];
            foreach ($dayinfo as $key => $value) {
                $allday = $key;
            }
        }
        isset($rech['r_money']) ? $r_money = $rech['r_money'] : $r_money = 0;
        isset($rech['money']) ? $money = $rech['money'] : $money = 0;
        isset($rech['isOpen']) ? $isOpen = $rech['isOpen'] : $isOpen = 0;

        //充值折扣规则
        $discount = $this->getConfig('recharge_discount');
        $discount = unserialize($discount);

        $symbol = $this->currency['symbol_left'] ?: $this->currency['symbol_right'];
        $res = $this->db->GetList("select * from `@#_pay` where pay_start = 1");
        $pay_lists = [];
        foreach ($res as $key => $value) {
            $paydb = System::load_app_class($value['pay_class'], 'pay');
            if (method_exists($paydb, 'withdrawal')) {
                $pay_lists[] = $value;
            }
        }
        include $this->tpl(ROUTE_M, 'member_insertfufen');
    }
    public function commissionsDetail()
    {
        $uid = intval($this->segment(4));
        if ($uid <= 0 || !$this->admin_can_view_member_uid($uid)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $sql = "select r.*,m.username from `@#_member_recodes` r
            left join `@#_member` m on r.uid = m.uid
         where (r.uid = $uid and r.type <> 1) or (m.yaoqing = $uid and r.type = 1)";
        $list = $this->db->Getlist($sql);
        foreach ($list as &$vl) {
            $vl['time'] = date('Y-m-d H:i:s', $vl['time']);
        }
        $num = 20;
        $total = $this->db->GetCount($sql);
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        include $this->tpl(ROUTE_M, 'member.commissionsDetail');
    }
    //佣金提现申请管理
    public function commissions()
    {
        $this->ment = array(
            array("insert", "充值记录", ROUTE_M . '/' . ROUTE_C . "/recharge"),
            array("insert", "消费记录", ROUTE_M . '/' . ROUTE_C . "/pay_list"),
            array("insert", "佣金提现", ROUTE_M . '/' . ROUTE_C . "/commissions"),
        );
        $num = 20;
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member_cashout` WHERE 1");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        // $commissions=$this->db->GetPage("SELECT * FROM `@#_member_cashout`  WHERE 1 order by `time` desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $hfiled = "h.operat_name,h.operat_status,h.operat_desc,h.operat_time,h.manage_name,h.manage_desc,h.manage_time,h.manage_status,h.id as hid";
        $sql = "SELECT o.*,$hfiled FROM `@#_member_cashout` o 
            left join `@#_handle` h on o.id = h.to_id and h.type = 31
            WHERE 1 order by o.auditstatus, o.`time` desc";
        $commissions = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        //查询用户名
        if (!empty($commissions)) {
            foreach ($commissions as $key => $val) {
                $uid = $val['uid'];
                $user[$key] = $this->db->GetOne("SELECT username FROM `@#_member` WHERE `uid`='$uid'  ");
            }
        }
        // $fufen = System::load_app_config("user_fufen",'','member');
        $mid = $this->AdminInfo['mid'];
        $formot_array = ["money", "fufen_yongjintx", "procefees"];
        data_format($commissions, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        // data_format($fufen,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.commissions');
    }
    //审核意见填写 已废弃
    public function reason()
    {
        return;
        if (isset($_POST['reason'])) {
            $id = $_POST['id'];
            //	        var_dump($id);exit;
            $audsta = $this->db->GetOne("SELECT * FROM `@#_member_cashout` WHERE `id`='$id'  ");
            $sql =  $this->db->Query("UPDATE `@#_member_cashout` SET `auditstatus` = '2',`status_massage` = '$_POST[reason]' where `id` = '$id'");
            if ($sql) {
                $uid = $this->db->GetOne("SELECT `uid` FROM  `@#_member_cashout` where `id` = '$id'");

                $member = $this->db->GetOne("SELECT `mobile` FROM `@#_member` where uid = '$uid[uid]'");
                _send_SMS($member['mobile'], 25);

                $rr = $this->db->Query("UPDATE `@#_member_commission` SET `dongjie_money`=`dongjie_money` - $audsta[money],`total_money` =`total_money`+$audsta[money]  where `uid`=$audsta[uid] ");
                echo 1;
            } else {
                echo 2;
            }
        } else {
            $id = intval($this->segment(4));
            include $this->tpl(ROUTE_M, 'member.reason');
        }
    }

    //佣金提现申请审核
    public function commreview()
    {
        $id = intval($this->segment(4));
        $audsta = $this->db->GetOne("SELECT * FROM `@#_member_cashout` WHERE `id`='$id'  ");
        if (!$audsta) {
            echo json_encode(['code' => 0, "msg" => "未找到记录"]);
            exit;
        }
        if ($audsta['auditstatus'] != 0) {
            echo json_encode(['code' => 0, "msg" => "已审核"]);
            exit;
        }
        $status = $_POST['status'];
        $desc = $_POST['desc'];
        $secure_password = $_POST['secure_password'];
        if (!$this->verifyExamine($secure_password)) {
            echo json_encode(['code' => 0, "msg" => "安全密码错误"]);
            exit;
        }
        $res = $this->db->GetList("SELECT * FROM `@#_handle` where `type`='31' and `to_id`='{$id}'");
        if ($res) {
            _messageerror("请勿重复提交!");
        }
        $data = [
            "operat_id" => $this->AdminInfo['uid'],
            "operat_name" => $this->AdminInfo['username'],
            "operat_status" => $status,
            "operat_desc" => $desc,
            "operat_time" => time(),
            "to_id" => $id,
            "type" => "31"
        ];
        $sql = Assemblydata($data, "handle");
        $this->db->Query($sql);
        echo json_encode(['code' => 1, 'msg' => '操作成功']);
        exit;

        $data = [
            "auditstatus" => $status,
            "status_massage" => $desc,
            "reviewtime" => time(),
            "admin_id" => $this->AdminInfo['uid'],
            "admin_name" => $this->AdminInfo['name'],
        ];
        if ($status == 1) {
            $data['examine_status'] = 1;
        }
        $sql = Assemblydata($data, "member_cashout", "up", [['id', $id]]);
        $is = $this->db->Query($sql);
        if ($is == 1) {
            echo json_encode(['code' => 200, "msg" => "审核成功,待审批"]);
            exit;
        } else {
            echo json_encode(['code' => 0, "msg" => "审核失败"]);
            exit;
        }
    }

    public function examineCommre()
    {
        $id = $this->segment(4);
        $audsta = $this->db->GetOne("SELECT * FROM `@#_member_cashout` WHERE `id`='$id'  ");
        if (!$audsta) {
            echo json_encode(['code' => 0, "msg" => "未找到记录"]);
            exit;
        }
        $status = $_POST['status'] == 1 ? 2 : 3;
        $desc = $_POST['desc'];
        $secure_password = $_POST['secure_password'];
        if (!$this->verifyExamine($secure_password)) {
            echo json_encode(['code' => 0, "msg" => "安全密码错误"]);
            exit;
        }
        if ($audsta['examine_status'] != 1) {
            echo json_encode(['code' => 0, "msg" => "已审核"]);
            exit;
        }
        $data = [
            "examine_status" => $status,
            "examine_desc" => $desc,
            "examine_time" => date("Y-m-d H:i:s"),
            "examine_id" => $this->AdminInfo['uid'],
            "examine_name" => $this->AdminInfo['name'],
        ];

        $sql = Assemblydata($data, "member_cashout", "up", [['id', $id]]);
        $this->db->Query($sql);
        if ($status == 2) {
            $type = -3;
            $content = "提现";
            $rr = $this->db->Query("UPDATE `@#_member_commission` SET `dongjie_money`=`dongjie_money` - $audsta[money],`zhichu_money` =`zhichu_money`+$audsta[money]  where `uid`=$audsta[uid] ");
            $is = $this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`,`cashoutid`)VALUES($audsta[uid],'$type','$content','$audsta[money]','$audsta[time]','$audsta[id]')");
        }
        echo json_encode(['code' => 200, "msg" => "审批成功"]);
        exit;
    }

    //充值记录
    public function recharge()
    {
        $this->ment = array(
            array("insert", "充值记录", ROUTE_M . '/' . ROUTE_C . "/recharge"),
            array("insert", "消费记录", ROUTE_M . '/' . ROUTE_C . "/pay_list"),
            array("insert", "佣金提现", ROUTE_M . '/' . ROUTE_C . "/commissions"),
        );
        $symbol = $this->symbol;
        $chongzhi = '';
        //搜索记录
        $channel = $this->db->GetList("select uid,username from `@#_member` where `level` = 2");
        if (isset($_POST['sososubmit'])) {
            $posttime1 = isset($_POST['posttime1']) ? $_POST['posttime1'] : '';
            $posttime2 = isset($_POST['posttime2']) ? $_POST['posttime2'] : '';
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
            } else {
                $times = '1';
            }
            // if(empty($posttime1) || empty($posttime2)) _message("2个时间都不能为空");
            if (!empty($posttime1) && !empty($posttime2)) { //如果2个时间都不为空
                $posttime1 = strtotime($posttime1);
                $posttime2 = strtotime($posttime2);
                if ($posttime1 > $posttime2) {
                    _message("前一个时间不能大于后一个时间");
                }
                $times = "`time`>='$posttime1' AND `time`<='$posttime2'";
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
            setcookie('r_where', $wheres);
        }
        // var_dump($wheres);
        $num = 20;
        if (empty($wheres)) {
            $w = $_COOKIE['r_where'];
            if ($w) {
                $wheres = stripslashes($w);
            }
        }
        if (empty($wheres)) {
            $total = $this->db->GetOne("SELECT count(*) as count FROM `@#_member_rechange` WHERE `type` = '1' and `content` <> '注册赠送'");
            $summoeny = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_member_rechange` WHERE `type` = '1' and `content` <> '注册赠送'");
        } else {
            $total = $this->db->GetOne("SELECT count(*) as count FROM `@#_member_rechange` WHERE $wheres AND `type` = '1' and `content` <> '注册赠送' ");
            $summoeny = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_member_rechange` WHERE $wheres AND `type` = '1' and `content` <> '注册赠送' ");
        }
        $total = $total['count'];
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        if (empty($wheres)) {
            $sql = "SELECT * FROM `@#_member_rechange` WHERE `type` = '1' and `content` <> '注册赠送' order by `time` desc";
            $recharge = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        } else {
            $sql = "SELECT * FROM `@#_member_rechange` WHERE $wheres AND `type` = '1' and `content` <> '注册赠送' order by `time` desc";
            $recharge = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        }
        // var_dump($sql);
        // var_dump("SELECT * FROM `@#_member_rechange` WHERE  $wheres AND `content` = '充值' order by `time` desc");exit;
        $rechangeStartArray = [1=>'待审核',2=>'已审核',3=>'拒绝'];
        $members = array();
        for ($i = 0; $i < count($recharge); $i++) {
            $uid = $recharge[$i]['uid'];
            $member = $this->db->GetOne("select m.*,m1.uid as ym_uid,m1.username as ym_username,m1.level as ym_level from `@#_member` m left join `@#_member` m1 on m.top_uid = m1.uid and m1.level = 2 where m.`uid`='$uid'");
            $recharge[$i]['ym_uid'] = $member['ym_uid'] ?: '';
            $recharge[$i]['ym_username'] = $member['ym_username'] ?: '';
            $members[$i] = $member['username'];
            if (empty($member['username'])) {
                if (!empty($member['email'])) {
                    $members[$i] = $member['email'];
                }
                if (!empty($member['mobile'])) {
                    $members[$i] = $member['mobile'];
                }
            }
            //审核状态
            $exa = $this->db->GetOne("select * from `@#_handle` where type = 41 and to_id = " . $recharge[$i]['id']);
            $recharge[$i]['operat_name'] = $exa['operat_name'] ?: '';
            $recharge[$i]['manage_name'] = $exa['manage_name'] ?: '';
            $recharge[$i]['manage_desc'] = $exa['manage_desc'] ?: '';
            $recharge[$i]['manage_time'] = $exa['manage_time'] ?: '';
            $recharge[$i]['manage_status'] = $exa['manage_status'] ?: '';

            $recharge[$i]['rechange_status'] = $rechangeStartArray[$recharge[$i]['status']];
        }
        $mid = $this->AdminInfo['mid'];
        $formot_array = ["money", 'sum_money'];
        data_format($recharge, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        data_format($summoeny, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);

        // $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
        include $this->tpl(ROUTE_M, 'member.recharge');
    }

    //充值审核
    public function rechargeExamine()
    {
        $id = $_POST['id'];
        $res = $this->db->GetOne("SELECT * FROM `@#_member_rechange` WHERE id = $id");
        if (!$res) {
            echo json_encode(['code' => 0, "msg" => "未找到记录"]);
            exit;
        }
        $data['examine_status'] = $_POST['status'];
        $data['examine_desc'] = $_POST['desc'];
        $secure_password = $_POST['secure_password'];
        if (!$this->verifyExamine($secure_password)) {
            echo json_encode(['code' => 0, "msg" => "密码错误"]);
            exit;
        }
        if ($data['examine_status'] == 2) {
            $data['status'] = 2;
        }
        $data['examine_id'] = $this->AdminInfo['uid'];
        $data['examine_name'] = $this->AdminInfo['name'];
        $data['examine_time'] = date("Y-m-d H:i:s");
        $sql = Assemblydata($data, "member_rechange", "up", [['id', $id]]);
        $this->db->Query($sql);
        $m = $res['money'];
        $uid = $res['uid'];
        if ($data['examine_status'] == 2) {
            if ($res['type'] == 1) {
                $sql1 = $this->db->Query("UPDATE `@#_member` SET `money`=`money`+'$m' where uid='$uid'");
            } else {
                $sql1 = $this->db->Query("UPDATE `@#_member` SET `money`=`money`-'$m' where uid='$uid'");
            }
            $this->db->Query($sql1);
        }
        echo json_encode(['code' => 200, "msg" => "操作成功"]);
        exit;
    }

    //消费记录
    public function pay_list()
    {
        $this->ment = array(
            array("insert", "充值记录", ROUTE_M . '/' . ROUTE_C . "/recharge"),
            array("insert", "消费记录", ROUTE_M . '/' . ROUTE_C . "/pay_list"),
            array("insert", "佣金提现", ROUTE_M . '/' . ROUTE_C . "/commissions"),
        );
        if (isset($_POST['sososubmit'])) {
            $wheres = "";
            $posttime1 = isset($_POST['posttime1']) ? $_POST['posttime1'] : '';
            $posttime2 = isset($_POST['posttime2']) ? $_POST['posttime2'] : '';
            if (!empty($posttime1)) { //如果2个时间都不为空
                $posttime1 = strtotime($posttime1);
                $times = " and a.`time`>='$posttime1'";
            }
            if (!empty($posttime2)) { //如果2个时间都不为空
                $posttime2 = strtotime($posttime2);
                $times = " AND a.`time`<='$posttime2'";
            }
            if ($posttime1&& $posttime2 && ($posttime1 > $posttime2)) {
                _message("前一个时间不能大于后一个时间");
            }

            $yonghu = isset($_POST['yonghu']) ? $_POST['yonghu'] : '';
            if (empty($yonghu) || $yonghu == '请选择用户类型') {
                $uid = '';
            }
            $yonghuzhi = isset($_POST['yonghuzhi']) ? $_POST['yonghuzhi'] : '';
            if ($yonghu == '用户id') {
                if ($yonghuzhi) {
                    $uid = " AND a.`uid`='$yonghuzhi'";
                }
            }
            if ($yonghu == '用户名称') {
                if ($yonghuzhi) {
                    $user_uid = $this->db->GetOne("select uid from `@#_member` where `username`='$yonghuzhi'");
                    if ($user_uid) {
                        $uid = " AND a.`uid`='$user_uid[uid]'";
                    } else {
                        _message($yonghuzhi . "用户不存在");
                    }
                }
            }
            if ($yonghu == '用户邮箱') {
                if ($yonghuzhi) {
                    $user_uid = $this->db->GetOne("select uid from `@#_member` where `email`='$yonghuzhi'");
                    if ($user_uid) {
                        $uid = " AND a.`uid`='$user_uid[uid]'";
                    } else {
                        _message($yonghuzhi . "用户不存在");
                    }
                }
            }
            if ($yonghu == '用户手机') {
                if ($yonghuzhi) {
                    $user_uid = $this->db->GetOne("select uid from `@#_member` where `mobile`='$yonghuzhi' and auto_user !=1");
                    if ($user_uid) {
                        $uid = " AND a.`uid`='$user_uid[uid]'";
                    } else {
                        _message($yonghuzhi . "用户不存在");
                        $uid = ' AND 1';
                    }
                } else {
                    $uid = ' AND 1';
                }
            }
            $wheres .= $times . $uid;

            $auto_user = isset($_POST['auto_user']) ? $_POST['auto_user'] : '';
            $wheres .= " AND b.`auto_user`=$auto_user";

            setcookie('where', $wheres);
        }
        $num = 20;
        if (empty($wheres)) {
            $w = $_COOKIE['where'];
            if ($w) {
                $wheres = stripslashes($w);
            }else{
                $wheres = " and b.auto_user = 0";
            }
            
        }
        
        $total = $this->db->GetCount("SELECT count(*) FROM `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid WHERE 1 $wheres ");
        $summoeny = $this->db->GetOne("SELECT sum(moneycount) sum_money FROM `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid  WHERE 1 and $wheres");
        
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }

        $page->config($total, $num, $pagenum, "0");
        // echo 	"SELECT a*,b.uid,b.auto_user FROM `@#_member_go_record` as a left join `@#_member` as b on a.uid=b.uid WHERE 1 and b.auto_user !=1 order by a.`time` desc";exit;	
        if (empty($wheres)) {

            $pay_list = $this->db->GetPage("SELECT a.*,b.uid,b.auto_user,b.username FROM `@#_member_go_record` as a left join `@#_member` as b on a.uid=b.uid WHERE   b.auto_user = 0 order by a.`id` desc", array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        } else {
            $pay_list = $this->db->GetPage("SELECT a.*,b.uid,b.auto_user,b.username FROM `@#_member_go_record` as a left join `@#_member` b on a.uid=b.uid  WHERE 1 $wheres order by a.`id`desc", array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        }
        $members = array();
        for ($i = 0; $i < count($pay_list); $i++) {
            $uid = $pay_list[$i]['uid'];
            $member = $this->db->GetOne("select m.`username`,m.`email`,m.`mobile`,m1.uid as ym_uid,m1.username as ym_username from `@#_member` m left join `@#_member` m1 on m.top_uid = m1.uid where m.`uid`='$uid'");
            $pay_list[$i]['ym_username'] = $member['ym_username'];
            $pay_list[$i]['ym_uid'] = $member['ym_uid'];
            $members[$i] = $member['username'];
            if (empty($member['username'])) {
                if (!empty($member['email'])) {
                    $members[$i] = $member['email'];
                }
                if (!empty($member['mobile'])) {
                    $members[$i] = $member['mobile'];
                }
            }
            $sid = $pay_list[$i]['shopid'];
            $shop = $this->db->GetOne("select `maxqishu` from `go_shoplist_copy` where id = $sid");
            if (!$shop) {
                $shop = $this->db->GetOne("select `maxqishu` from `go_shoplist` where id = $sid");
            }
            $pay_list[$i]['maxqishu'] = $shop['maxqishu'];
        }
        $formot_array = ["money", 'moneycount', 'sum_money'];
        data_format($pay_list, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        data_format($summoeny, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.pay_list');
    }

    //清除充值记录
    public function clear_r()
    {
        $data = $_POST;
        if ($data['searchsubmit']) {
            if (empty($data['search_time'])) {
                _message('请选择清除时间');
                exit;
            }
            $time = strtotime($data['search_time']);
            $sql = "DELETE FROM `@#_member_rechange`  WHERE (`content` like '%充值%' or `content`='使用佣金充值到账户' or `content`='使用余额宝充值到账户' OR `content`='通过微信公众号充值') AND `type`='1' AND time<'$time'";
            $res = $this->db->Query($sql);
            if ($res) {
                _message('清除成功');
                exit;
            } else {
                _message('清除失败');
                exit;
            }
        }
    }

    public function replay()
    {
        setcookie('where', null);
        echo 1;
        exit;
    }

    public function replay1()
    {
        setcookie('r_where', null);
        echo 1;
        exit;
    }

    //查看二维码图片
    public function mycode()
    {
        $id = $_POST['id'];
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['code' => 0, 'msg' => "网络异常"]);
            exit;
        }

        $qr_codes = $this->db->GetOne("SELECT `id`,`backgrounds`,`icons`,`qr_code_size`,`qr_code_border`,`left_margin`,`top_margin`,`internal_x`,`internal_y` FROM `@#_qr_codes` WHERE `is_selected` = 1");
        $roots = $_SERVER['DOCUMENT_ROOT'];
        $rootUrl = '/statics/uploads/codes/user/' . "{$qr_codes['id']}-{$id}.png";
        $qrcodeUrl = $roots . $rootUrl;

        $webPath = $money = $this->db->Getone("SELECT `value` FROM `go_config` WHERE name = 'web_path'")['value'];
        if (!file_exists($qrcodeUrl)) {
            $qr_codes['bgUrl'] = G_WEB_PATH . "/statics/uploads/" . $qr_codes['backgrounds'];
            $qr_codes['icUrl'] = G_WEB_PATH . "/statics/uploads/" . $qr_codes['icons'];
            mycode_new($id, $qrcodeUrl, $webPath, $qr_codes['bgUrl'], $qr_codes['icUrl'], $qr_codes['qr_code_size'], $qr_codes['qr_code_border'], $qr_codes['left_margin'], $qr_codes['top_margin'], $qr_codes['internal_x'], $qr_codes['internal_y']);
            if (!file_exists($qrcodeUrl)) {
                echo json_encode(['code' => 0, 'msg' => '网络异常，请联系管理员']);
                exit;
            }
        }
        $webPath = $webPath;
        echo json_encode(['code' => 1, 'msg' => 'ok', 'data' => $webPath . $rootUrl]);
        exit;
    }

    /**
     * 用户地图
     */
    public function usermap()
    {
        include $this->tpl(ROUTE_M, 'member.map');
    }
    /*
     * 用户定位
     */
    public function userTxmap()
    {
        $sql = "	SELECT
		*
	FROM
		@#_member_login_log AS c 
	WHERE
		`type` = '1' 
	AND c.id = ( SELECT MAX( id ) AS id FROM go_member_login_log WHERE user_id = c.user_id AND lat !='' ORDER BY id DESC )";
        //        $sql ="SELECT `lat`,`lng` FROM `go_member_login_log` WHERE `type` = 1 AND `lat` !='' AND `lng` !='' GROUP BY `user_id` UNION ALL SELECT `lat`, `lng` FROM `go_map_member`";
        $addreslist = $this->db->GetList($sql);

        include $this->tpl(ROUTE_M, 'member.txmap');
    }
    /**
     * 获取用户位置数据
     */
    public function usermapdata()
    {
        $sql = "SELECT `region` as `name` ,COUNT(*) AS 'value' FROM (SELECT * FROM  `@#_member_login_log` WHERE type = 1 AND `region` !='' GROUP BY `user_id`) AS B GROUP BY `region`";
        $addreslist = $this->db->GetList($sql);
        echo json_encode(['code' => 1, 'msg' => 'ok', 'data' => $addreslist, 'def' => ['region' => '', 'logincount' => 0]]);
        exit;
    }

    /**
     * 聊天
     */
    public function cartindex()
    {
        //        System::load_sys_fun('redisData');
        //        $Redis=  new redisData();

        $user_id = intval($this->segment(4));
        if ($user_id <= 0 || !$this->admin_can_view_member_uid($user_id)) {
            _message("无权限查看该会员数据", WEB_PATH . '/' . ROUTE_M . '/member/lists');
        }
        $sql = "SELECT * FROM `@#_member` WHERE `uid` = '$user_id'";
        $userinfo = $this->db->GetOne($sql);
        include $this->tpl(ROUTE_M, 'cart.index');
    }

    /**
     * 获取 当前用户聊天朋友
     */
    public function getcartFrind()
    {
        $uid = $_POST['user_id'];
        if (empty($uid)) {
            echo json_encode(['code' => 0, 'data' => ['1', 2]]);
            exit();
        }
        $sql = "SELECT B.username,B.uid,B.img,B.mobile FROM (SELECT `b_uid` AS fid FROM `@#_oucld_friends` WHERE `a_uid` = $uid AND `status` =1  UNION ALL SELECT  `a_uid` AS fid FROM `@#_oucld_friends` WHERE `b_uid` = $uid AND `status` =1) AS A LEFT JOIN `@#_member` AS B ON A.fid = B.uid";
        $data = $this->db->GetList($sql);

        foreach ($data as $k => $v) {
            $data[$k]['mobile'] = substr_replace($v['mobile'], '****', 3, 4);
        }
        echo json_encode(['code' => 1, 'data' => $data]);
    }

    /**
     * 系统群发
     */
    public function SendMsg()
    {
        $this->ment = array(
            array("lists", "发送站内信", ROUTE_M . '/' . ROUTE_C . "/SendMsg"),
            array("lists", "站内信列表", ROUTE_M . '/' . ROUTE_C . "/SendList")
        );
        include $this->tpl(ROUTE_M, 'cart.sendmsg');
        exit();
    }

    /**
     * 系统群发列表
     */
    public function SendList()
    {
        $this->ment = array(
            array("lists", "发送站内信", ROUTE_M . '/' . ROUTE_C . "/SendMsg"),
            array("lists", "站内信列表", ROUTE_M . '/' . ROUTE_C . "/SendList")
        );

        if (isset($_POST['dosubmit'])) {
            // 修改站内信状态
            $template_verify = $_POST['template_verify'] ?: 0;
            $update = $this->db->Query("UPDATE `@#_config` SET `value`='$template_verify' WHERE (`name`='sys_message')");
            if ($update) {
                _message('修改成功');
            } else {
                _message('修改失败');
            }
        }
        $num = 20;
        $sql = "SELECT id, titles, send_time FROM `go_system_title` where del = 1 ORDER BY id DESC";
        $counts = "SELECT count(*)  FROM `go_system_title` where del = 1  ";
        $total = count($this->db->GetList($counts));
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $members = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));

        foreach ($members as $key => $value) {
            $members[$key]['send_time'] = date('Y-m-d H:i:s', $value['send_time']);
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
        $switch = $this->getConfig('sys_message');
        include $this->tpl(ROUTE_M, 'cart.SendList');
        exit();
    }

    public function chartShow()
    {
        $id = $this->segment(4);
        $sql = "SELECT `id`,`titles`,`content` FROM `go_system_title`  WHERE id = $id";
        $data =  $this->db->GetOne($sql);

        $sql = "SELECT a.id,m.uid,a.status , m.username,m.mobile FROM `go_system_message` as a INNER JOIN go_member as m WHERE a.to_id = m.uid and a.title_id = '$data[id]' and del = 1 ";
        $userData = $this->db->GetList($sql);
        $status = [
            0   => '未读',
            1   => '已读',
            2   =>  '消息已撤销',
        ];
        foreach ($userData as $k1 => $v1) {
            $userData[$k1]['username'] = $v1['username'] ?:  hidtel($v1['mobile']);
            $userData[$k1]['statusName'] = $status[$v1['status']];
        }
        include $this->tpl(ROUTE_M, 'cart.chartShow');
        exit();
    }

    public function chartDel()
    {
        $id = $_POST['id'];
        if (!$id) {
            echo json_encode(['code' => 2, 'msg' => '参数有误']);
            exit;
        }
        $res = $this->db->Query("UPDATE `go_system_title` SET del = 2 where id = $id");
        // $res = $this->db->Query("UPDATE `go_system_message` SET del = 2 where title_id = $id");
        if ($res) {
            echo json_encode(['code' => 1, 'msg' => '删除成功']);
            exit;
        }
        echo json_encode(['code' => 2, 'msg' => '删除失败']);
        exit;
    }
    public function chartDels()
    {
        $id = $_POST['ids'];
        if (!$id) {
            echo json_encode(['code' => 2, 'msg' => '参数有误']);
            exit;
        }
        $res = $this->db->Query("UPDATE `go_system_title` SET del = 2 where id in ($id)");
        if ($res) {
            echo json_encode(['code' => 1, 'msg' => '删除成功']);
            exit;
        }
        echo json_encode(['code' => 2, 'msg' => '删除失败']);
        exit;
    }

    /**
     * 获取聊天记录
     */
    public function getchatmsg()
    {
        $from_id = $_POST['from_id'];
        $to_uid = $_POST['to_uid'];

        $sql = "SELECT * FROM (SELECT
                        * 
                    FROM
                        `go_cloud_chat` 
                    WHERE
                        `to_id` = $from_id 
                        AND `from_id` = $to_uid UNION ALL
                    SELECT
                        * 
                    FROM
                        `go_cloud_chat` 
                    WHERE
                        `to_id` = $to_uid 
                        AND `from_id` = $from_id) AS D GROUP BY id";

        $data = $this->db->GetList($sql);

        echo json_encode(['code' => 1, 'data' => $data]);
    }

    public function addarr()
    {
        return false;
        $arr = System::load_sys_config('cs');
        $sqlarr = [];
        foreach ($arr as $vs) {
            foreach ($vs['children'] as $v) {
                $l = $v['lat'];
                $g = $v['log'];
                $sqlarr[] = ["$l", "$g"];
            }
        }
        $sql    = "INSERT INTO `@#_map_member`(`lat`, `lng`) VALUES ";

        foreach ($sqlarr as $v) {
            $sql    .= " ('" . implode("','", $v) . "'),";
        }
        $sql    = substr($sql, 0, -1);
        $res = $this->db->Query($sql);
        echo $res . '<br>';
        echo  "成功";
    }

    /*
   * 发送短信
   */
    public function SendSmS()
    {
        include $this->tpl(ROUTE_M, 'cart.sendsms');
        exit();
    }

    /**
     * 正式发送短信
     */
    public function sendMessage()
    {
        $post = $_POST;

        /* if (empty($post['titles']) || empty($post['content'])){
             echo json_encode(['code'=>2,'msg'=>'参数有误']);exit;
         }*/

        $userType = 0;
        if (!empty($post['userIds'])) {
            $userType = 24;
            $userData = $this->db->GetList("SELECT `mobile` FROM `go_member` WHERE uid in ($post[userIds])");
            //            p("SELECT `mobile` FROM `go_member` WHERE uid in ($post[userIds])");
        } else {
            $userType = 23;
            $userData = $this->db->GetList("SELECT `mobile` FROM `go_member` WHERE auto_user = 0");
        }
        if ($userType == 0 || empty($userData)) {
            echo json_encode(['code' => 2, 'msg' => '发送失败']);
            exit;
        }
        $num = 0;
        foreach ($userData as $v) {
            $res = _send_SMS($v['mobile'], $userType);
            if ($res[0] == 1) {
                $num++;
            }
        }
        echo json_encode(['code' => 1, 'msg' => "成功发送 $num  人"]);
        exit;
    }

    //推送服务器消息
    public function tuisong()
    {
        $data = $_POST;
        $msg = '';
        if (empty($data['titles'])) $msg = '标题不能为空';
        if (empty($data['content'])) $msg = '消息类型不能为空';
        if (!empty($msg)) {
            echo json_encode(['code' => 2, 'msg' => $msg]);
            exit;
        }
        $data['msg_type'] = 1;
        $data['send_status'] = 2;
        $data['status'] = 0;
        //获取用户id
        if ($data['userIds']) {
            /*$userIdArray = explode(',',$data['userIds']);
            if (!is_array($userData)){
                echo json_encode(['code'=>2,'msg'=>'请检查ID是否使用英文逗号隔开']);
                exit;
            }*/

            $userData = $this->db->GetList("SELECT `uid` FROM go_member WHERE `uid` in ($data[userIds])");
        } else {
            $userData = $this->db->GetList("SELECT `uid` FROM go_member WHERE `mobilecode`='1' and `auto_user` != '1' and 'level'!=1");
        }
        $data['send_time'] = time();
        $r = $this->db->Query("INSERT INTO go_system_title (`titles`,`send_time`,`content`) VALUES ('$data[titles]','$data[send_time]','$data[content]')");

        $getId = $this->db->insert_id();

        if (empty($getId)) {
            echo json_encode(['code' => 2, 'msg' => '网络异常']);
            exit;
        }
        // System::load_sys_fun('redisData');
        // $redis = new redisData();

        foreach ($userData as $v) {
            $this->db->Query("INSERT INTO go_system_message (`titles`,`send_time`,`content`,`to_id`,`msg_type`,`send_status`,`status`,`title_id`) VALUES ('$data[titles]','$data[send_time]','$data[content]','$v[uid]','1','1','0','$getId')");
            // redis 添加数据
            // $message_data[$getId]['titles'] = $data['titles'];
            // $message_data[$getId]['content'] = $data['content'];
            // $message_data[$getId]['send_time'] = $data['send_time'];
            // $message_data[$getId]['send_time'] = $data['send_time'];
            // $read_data[$v['uid']][] = $message_data;
        }

        echo json_encode(['code' => 2, 'msg' => '推送成功']);
        exit;
    }

    //网络充值 充值未支付记录
    public function network_recharge()
    {
        $num = 20;
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member_addmoney_record` where status = '未付款'  order by id desc");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $addmoney_record = $this->db->GetPage("SELECT * FROM `@#_member_addmoney_record`  where status = '未付款' order by id desc", array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));

        foreach ($addmoney_record as &$v) {
            $v['time'] = date("Y-m-d H:i:s", $v['time']);

            $member = $this->db->GetOne("select username , mobile from `go_member` where uid = $v[uid]");
            $m = $member['username'] ?: $member['mobile'];

            $v['uid'] = $v['uid'] . "[" .  $m . "]";
        }
        $formot_array = ["money"];
        data_format($addmoney_record, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.network_recharge');
    }

    public function patch_del_recharge()
    {
        $ids = $_POST['ids'];
        if(!$ids){
            $ids = $this->segment(4);
        }
        if ($ids) {
            $this->db->Query("delete from `@#_member_addmoney_record` where id IN ($ids)");
            _message("删除成功");
            exit;
        }
        _messageerror("删除失败");

        echo 1;
        exit;
    }

    //线下usdt充值列表
    public function down_usdt_list()
    {
        $is_bank_card_recharge = $this->getConfig("is_bank_card_recharge") == 2 ? false : true;
        $is_usdt_recharge = $this->getConfig("is_usdt_recharge") == 2 ? false : true;
        $this->ment = [];
        if ($is_bank_card_recharge) {
            $this->ment[] = array("down_money_list", "银行卡充值", ROUTE_M . '/' . ROUTE_C . "/down_money_list");
        }
        if ($is_usdt_recharge) {
            $this->ment[] = array("down_usdt_list", "USDT充值", ROUTE_M . '/' . ROUTE_C . "/down_usdt_list");
        }
        if (isset($_POST['sososubmit'])) {
            // p($_POST);
            $posttime1 = isset($_POST['posttime1']) ? $_POST['posttime1'] : '';
            $posttime2 = isset($_POST['posttime2']) ? $_POST['posttime2'] : '';
            if (empty($posttime1) || empty($posttime2)) _message("2个时间都不为能空");
            if (!empty($posttime1) && !empty($posttime2)) { //如果2个时间都不为空
                $posttime1 = strtotime($posttime1);
                $posttime2 = strtotime($posttime2);
                if ($posttime1 > $posttime2) {
                    _message("前一个时间不能大于后一个时间");
                }
                $times = "`create_time`>='$posttime1' AND `create_time`<='$posttime2'";
            }
            $chongzhi = isset($_POST['chongzhi']) ? $_POST['chongzhi'] : '';
            if (!empty($chongzhi) && $chongzhi != '请选择账户状态') {
                $times .= " and status = $_POST[chongzhi]";
            }

            $wheres = $times;
            setcookie('where', $wheres);
        } else {
            $wheres = 1;
        }

        $num = 20;
        // 		p("SELECT COUNT(*) FROM `@#_offline_money` where $wheres order by id desc");
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_offline_money` where $wheres and `type` = 3 order by id desc");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        // $members=$this->db->GetPage("SELECT * FROM `@#_offline_money` where $wheres and `type` = 3 order by id desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $hfiled = "h.operat_name,h.operat_status,h.operat_desc,h.operat_time,h.manage_name,h.manage_desc,h.manage_time,h.manage_status,h.id as hid";
        $sql = "SELECT o.*,$hfiled FROM `@#_offline_money` o 
                left join `@#_handle` h on o.id = h.to_id and h.type = 22 
                where $wheres and o.`type` = 3 order by o.id desc";
        $members = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        $start = [
            1 => "<font style='color:red'>提交</font>",
            2 => "<font style='color:green'>已到账</font>",
            3 => "未到账",
        ];
        foreach ($members as &$v) {
            $v['volume'] = @unserialize($v['volume']);
            $v['create_time'] = date("Y-m-d H:i:s", $v['create_time']);
            $v['state'] = $v['status'];
            $v['status'] = $start[$v['status']];
            $v['real_money'] = $v['real_money'] ?: $v['money'];
            $v['picarr'] = @unserialize($v['picarr']);
            $member = $this->db->GetOne("select username , mobile from `go_member` where uid = $v[uid]");
            $m = $member['username'] ?: $member['mobile'];
            $v['money'] =  number_format($v['money']) . "USDT";
            $v['uid'] = $v['uid'] . "/" .  $m;
        }
        $formot_array = ["real_money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.downusdtlist');
    }

    //线下充值列表
    public function down_money_list()
    {
        $is_bank_card_recharge = $this->getConfig("is_bank_card_recharge") == 2 ? false : true;
        $is_usdt_recharge = $this->getConfig("is_usdt_recharge") == 2 ? false : true;
        $this->ment = [];
        if ($is_bank_card_recharge) {
            $this->ment[] = array("down_money_list", "银行卡充值", ROUTE_M . '/' . ROUTE_C . "/down_money_list");
        }
        if ($is_usdt_recharge) {
            $this->ment[] = array("down_usdt_list", "USDT充值", ROUTE_M . '/' . ROUTE_C . "/down_usdt_list");
        }
        if (isset($_POST['sososubmit'])) {
            // p($_POST);
            $posttime1 = isset($_POST['posttime1']) ? $_POST['posttime1'] : '';
            $posttime2 = isset($_POST['posttime2']) ? $_POST['posttime2'] : '';
            if (empty($posttime1) || empty($posttime2)) _message("2个时间都不为能空");
            if (!empty($posttime1) && !empty($posttime2)) { //如果2个时间都不为空
                $posttime1 = strtotime($posttime1);
                $posttime2 = strtotime($posttime2);
                if ($posttime1 > $posttime2) {
                    _message("前一个时间不能大于后一个时间");
                }
                $times = "`create_time`>='$posttime1' AND `create_time`<='$posttime2'";
            }
            $chongzhi = isset($_POST['chongzhi']) ? $_POST['chongzhi'] : '';
            if (!empty($chongzhi) && $chongzhi != '请选择账户状态') {
                $times .= " and status = $_POST[chongzhi]";
            }

            $wheres = $times;
            setcookie('where', $wheres);
        } else {
            $wheres = 1;
        }

        $num = 20;
        // 		p("SELECT COUNT(*) FROM `@#_offline_money` where $wheres order by id desc");
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_offline_money` where $wheres and `type` = 2 order by id desc");
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $page->config($total, $num, $pagenum, "0");
        $hfiled = "h.operat_name,h.operat_status,h.operat_desc,h.operat_time,h.manage_name,h.manage_desc,h.manage_time,h.manage_status,h.id as hid";
        $sql = "SELECT o.*,$hfiled FROM `@#_offline_money` o 
            left join `@#_handle` h on o.id = h.to_id and h.type = 21 
            where $wheres and o.`type` = 2 group by o.id order by o.id desc";
        $members = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        $start = [
            1 => "<font style='color:red'>提交</font>",
            2 => "<font style='color:green'>已到账</font>",
            3 => "未到账",
        ];
        foreach ($members as &$v) {
            $v['volume'] = @unserialize($v['volume']);
            $v['create_time'] = date("Y-m-d H:i:s", $v['create_time']);
            $v['status'] = $start[$v['status']];
            $v['picarr'] = @unserialize($v['picarr']);

            $member = $this->db->GetOne("select username , mobile from `go_member` where uid = $v[uid]");
            $m = $member['username'] ?: $member['mobile'];

            $v['uid'] = $v['uid'] . "/" .  $m;
        }
        $formot_array = ["money"];
        data_format($members, $formot_array, "price", $this->currency['symbol_left'], $this->currency['symbol_right']);
        include $this->tpl(ROUTE_M, 'member.downmoneylist');
    }

    public function down_money_post()
    {
        $id = htmlspecialchars(trim($_POST['id']));
        $secure_password = $_POST['secure_password'];
        if (!$this->verifyExamine($secure_password)) {
            echo json_encode(['code' => 0, "msg" => "密码错误"]);
            exit;
        }
        //待审核记录是否存在
        $res = $this->db->GetOne("select id from `@#_handle` where to_id = $id and `type` = 21");
        if ($res) {
            echo json_encode(['code' => 0, "msg" => "待审核中"]);
            exit;
        }
        $data = [
            "operat_id" => $this->AdminInfo['uid'],
            "operat_name" => $this->AdminInfo['username'],
            "operat_status" => $_POST['status'],
            "operat_desc" => $_POST['desc'],
            "operat_time" => time(),
            "to_id" => $id,
            "type" => "21"
        ];
        $sql = Assemblydata($data, "handle");
        $this->db->Query($sql);
        echo json_encode(['code' => 1, 'msg' => '操作成功']);
        exit;
    }
    public function down_usdt_post()
    {
        $id = htmlspecialchars(trim($_POST['id']));
        $secure_password = $_POST['secure_password'];
        if (!$this->verifyExamine($secure_password)) {
            echo json_encode(['code' => 0, "msg" => "密码错误"]);
            exit;
        }
        //待审核记录是否存在
        $res = $this->db->GetOne("select id from `@#_handle` where to_id = $id and `type` = 22");
        if ($res) {
            echo json_encode(['code' => 0, "msg" => "待审核中"]);
            exit;
        }
        $data = [
            "operat_id" => $this->AdminInfo['uid'],
            "operat_name" => $this->AdminInfo['username'],
            "operat_status" => $_POST['status'],
            "operat_desc" => $_POST['desc'],
            "operat_time" => time(),
            "to_id" => $id,
            "type" => "22"
        ];
        $sql = Assemblydata($data, "handle");
        $this->db->Query($sql);
        echo json_encode(['code' => 1, 'msg' => '操作成功']);
        exit;
    }
}
