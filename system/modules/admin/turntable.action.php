<?php

defined('G_IN_SYSTEM') or exit('no');
System::load_app_class('admin', G_ADMIN_DIR, 'no');
class turntable extends admin
{
    private $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        /* $this->ment=array(
            array("navigation","奖品列表",ROUTE_M.'/'.ROUTE_C),
            array("navigation","转盘已发货订单",ROUTE_M.'/'.ROUTE_C."/dingdan"),
            array("navigation","转盘未发货订单",ROUTE_M.'/'.ROUTE_C."/nodingdan"),	
            array("navigation","转盘规则",ROUTE_M.'/'.ROUTE_C."/guize"), 
            array("navigation","添加次数",ROUTE_M.'/'.ROUTE_C."/zpcishu"), 
            array("navigation","添加奖品",ROUTE_M.'/'.ROUTE_C."/add"), 
        );  */
        $this->ment = array(
            array("ruleSet", "转盘规则", ROUTE_M . '/' . ROUTE_C . "/ruleSet"),
            array("zpcishu", "添加次数", ROUTE_M . '/' . ROUTE_C . "/zpcishu"),
            array("prizeRecord", "抽奖记录", ROUTE_M . '/' . ROUTE_C . "/prizeRecord"),
            array("popup_lists", "转盘弹窗", ROUTE_M . '/' . ROUTE_C . "/popup_lists"),
            array("popup_add", "添加转盘弹窗", ROUTE_M . '/' . ROUTE_C . "/popup_add"),
            array("virtualData", "生成虚拟中奖", ROUTE_M . '/' . ROUTE_C . "/virtualData"),
        );
    }

    /**
     * 规则设置
     * @return void
     */
    public function ruleSet()
    {
        $res = $this->getConfig("zp_set");
        $res = unserialize($res);
        $rule = $res['rule'];
        $number = $res['number'];
        $money = $res['money'];
        $time = $res['time'];
        if (isset($_POST['dosubmit']) && $_POST['dosubmit']) {
            $data = [
                'rule' => $_POST['rule'],
                'number' => $_POST['number'],
                'money' => $_POST['money'],
                'time' => $_POST['time'],
            ];
            $data = serialize($data);
            $res = $this->setConfig("zp_set", $data, "大转盘设置");
            if($res){
                _message("设置成功");
            }else{
                _messageerror("设置失败");
            }
        }
        include $this->tpl(ROUTE_M, "turntable.rule_set");
    }

    /**
     * 获取抽奖记录
     */
    public function prizeRecord()
    {
        $num = 30;
        $total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_lottery_records`")?:0;
        $page = System::load_sys_class('page');
        if (isset($_GET['p'])) {
            $pagenum = $_GET['p'];
        } else {
            $pagenum = 1;
        }
        $sql = "SELECT a.*,m.username from `@#_lottery_records` a left join `@#_member` m on m.uid = a.uid where 1 order by a.id desc";
        $recordlist = $this->db->GetPage($sql, array("num" => $num, "page" => $pagenum, "type" => 1, "cache" => 0));
        foreach ($recordlist as &$row) {
            $row['time'] = date('Y-m-d H:i:s', $row['time']);
        }
        include $this->tpl(ROUTE_M, "turntable.prize_list");
    }

    /**
     * 增加抽奖次数
     * @return void
     */
    public function zpcishu()
    {
        if (isset($_POST['submit'])) {
            $uid = $_POST['uid'];
            $num = $_POST['num'];
            $time = time();
            $info = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = '$uid'");
            if ($info) {
                if ($num < 0) {
                    $num = intval($num);
                    $num = $info['num'] - $num;
                }
                $insert = $this->db->Query("UPDATE `@#_winning_probability` SET `num`= '$num' WHERE `uid`=$uid");
                if ($insert) {
                    _message("操作成功");
                } else {
                    _messageerror("操作失败");
                }
            } else {
                _messageerror("用户不存在");
            }
        }
        include $this->tpl(ROUTE_M, 'zp_cishu');
    }

    //首页弹窗列表
    public function popup_lists()
    {
        $banners = array();
        $banners = $this->db->Getlist("select * from `@#_banner` where `type` = 2");
        foreach($banners as &$vl){
            $vl['img'] = str_replace("bannerimg/","",$vl['img']);
        }	
        include $this->tpl(ROUTE_M, 'banner.list');
    }

    //添加转盘弹窗
    public function popup_add()
    {
        if (isset($_POST['submit'])) {
            $title = htmlspecialchars($_POST['title']);
            $img_url = htmlspecialchars($_POST['img_url']);
            $language = htmlspecialchars(trim($_POST['language']));
            $bg_color = htmlspecialchars(trim($_POST['bg_color']));
            $img = '';
            if (empty($title) || empty($img_url)) {
                _message("添加失败", WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . "/popup_lists");
            }
            $img = $_POST['img'];
            $time = time();
            $sql = "INSERT INTO `@#_banner`(title,img,img_url,status,create_time,language,bg_color,type) VALUES('$title','$img','$img_url','1','$time','$language','$bg_color',2)";
            $this->db->Query($sql);
            if ($this->db->affected_rows()) {
                _message("添加成功", WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . "/popup_lists");
            } else {
                _message("添加失败");
            }
        }
        $languages = $this->getLanguageList();
        include $this->tpl(ROUTE_M, 'banner.addimg');
    }

    //编辑
    public function editimg()
    {
        $banner_id = intval($this->segment(4));
        $banner_info = $this->db->GetOne("SELECT * FROM `@#_banner` where `id`='$banner_id'");
        if (!$banner_info) _message("参数不正确");

        if (isset($_POST['submit'])) {
            $title = htmlspecialchars($_POST['title']);
            $img_url = htmlspecialchars($_POST['img_url']);
            $status = htmlspecialchars($_POST['status']);
            $language = htmlspecialchars(trim($_POST['language']));
            $bg_color = htmlspecialchars(trim($_POST['bg_color']));
            $img = $_POST["img"]?:$banner_info['img'];
            // 判断是否更该图片验证图片是否为空
            $time = time();
            $this->db->Query("UPDATE `@#_banner` SET `title`='$title',`img`='$img',`img_url`='$img_url', `status`='$status',`language`='$language', `update_time`='$time',`bg_color`='$bg_color' WHERE `id`='$banner_id'");
            if ($this->db->affected_rows()) {
                _message("修改成功", WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . "/popup_lists");
            } else {
                _message("修改失败");
            }
        }
        $languages = $this->getLanguageList();
        include $this->tpl(ROUTE_M, 'banner.editimg');
    }

    //删除
    public function delimg()
    {
        $banner_id = intval($this->segment(4));
        if ($banner_id) {
            $this->db->Query("DELETE FROM `@#_banner` WHERE `id`='$banner_id'");
            if ($this->db->affected_rows()) {
                _message("删除成功", WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . "/lists");
            } else {
                _message("删除失败", WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . "/lists");
            }
        }
    }

    /**
     * 生成虚拟中奖数据
     */
    public function virtualData(){
        if (isset($_POST['dosubmit'])) {
            $mem = $this->db->getOne("SELECT min(uid) as min_uid,max(uid) as max_uid FROM `@#_member` where auto_user = 1");
            if(!$mem["min_uid"] || $mem['max_uid']){
                $this->jsonData->error('1001',"未配置虚拟用户");
            }
            $num = htmlspecialchars($_POST['number']);
            $date = get_today_time();
            $start_time = $date[0];
            $end_time = time();
            // 获取转盘配置
            $config = $this->getConfig("zp_set");
            if(!$config){
                $this->jsonData->error('1002',"未配置");
            }
            $config = unserialize($config);
            $money = $total = $config['money']['money'];
            for($i = 0; $i < $num; $i++){
                $uid = rand($mem['min_uid'],$mem['max_uid']);
                $time = rand($start_time,$end_time);
                $huode = 1;
                $probability = rand(91,100);
                $sql = Assemblydata(["uid"=>$uid,"time"=>$time,'huode'=>$huode,"money"=>$money,"total_money"=>$total,"probability"=>$probability],"lottery_records");
                $res[] = $this->db->Query($sql);
            }
            _message('success', WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . "/virtualData");
        }
        include $this->tpl(ROUTE_M, 'turntable.virtual_data');
    }
}
