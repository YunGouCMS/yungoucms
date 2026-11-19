<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class record extends admin {
    public $ment = array(

        array("lists","会员列表",ROUTE_M."/member/lists"),
        array("lists","查找会员",ROUTE_M."/member/select"),
        array("insert","添加会员",ROUTE_M."/member/insert"),
        array("insert","会员回收站",ROUTE_M."/member/lists/del"),
        // array("insert","昵称限制",ROUTE_M."/member/config"),
        array("member_group","会员等级",ROUTE_M."/member/member_group"),
        array("member_add_group","添加会员等级",ROUTE_M."/member/member_add_group"),
        array("insert","收货商列表",ROUTE_M."/member/lists/recoverlist"),
        array("insert","黑名单会员",ROUTE_M."/member/blacklist"),
        // array("insert","查看下级",ROUTE_M."/member/yaoqing/".$uid),
    );

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class("model");
    }

    /**
     * 购买记录
     * @return void
     */
    public function purchaseRecord(){
        $uid = intval($this->segment(4));
        if($uid <= 0 || !$this->admin_can_view_member_uid($uid)){
            _message("无权限查看该会员数据", WEB_PATH.'/'.ROUTE_M.'/member/lists');
        }
        $sql = "SELECT g.*,if(s.thumb is not null,s.thumb,sy.thumb) as thumb FROM `@#_member_go_record` g left join `@#_shoplist` s on s.id = g.shopid left join `@#_shoplist_copy` sy on sy.id = g.shopid  WHERE `uid` = $uid";
        $num=20;
        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record`  WHERE `uid` = $uid");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $list = $this->db->GetPage($sql,["num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0]);
        data_format($list,['moneycount','money'],"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
        data_format($list,["time"],"times");
        foreach($list as &$vl){
            $vl['thumb'] = G_UPLOAD_PATH."/".$vl['thumb'];
            $vl['huode'] = $vl['huode']?"是":"否";
        }
        include $this->tpl(ROUTE_M,'member.purchase.list');
    }

    /**
     * 充值记录
     * @return void
     */
    public function rechargeRecord(){
        $uid = intval($this->segment(4));
        if($uid <= 0 || !$this->admin_can_view_member_uid($uid)){
            _message("无权限查看该会员数据", WEB_PATH.'/'.ROUTE_M.'/member/lists');
        }
    }
    
    /**
     * 中奖记录
     * @return void
     */
    public function winningRecord(){
        $uid = intval($this->segment(4));

    }
    /**
     * 积分来源记录
     * @return void
     */
    public function scoreRecord(){
        $this->ment[] = array("scoreRecord","积分明细",ROUTE_M.'/'.ROUTE_C."/scoreRecord",2);
        $uid = intval($this->segment(4));
        if($uid <= 0 || !$this->admin_can_view_member_uid($uid)){
            _message("无权限查看该会员数据", WEB_PATH.'/'.ROUTE_M.'/member/lists');
        }
        $sql = "select * from `@#_member_account` where `uid`='$uid' and `type`='1' and pay = '积分'  ORDER BY time DESC";
        $num=20;
        $total = $this->db->GetCount("select count(*) as count from `@#_member_account` where `uid`='$uid' and `type`='1' and pay = '积分'");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $list = $this->db->GetPage($sql,["num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0]);
        foreach($list as $key=>$val){
            $list[$key]['time']=date("Y-m-d H:i:s",$val['time']);
        }
        include $this->tpl(ROUTE_M,'member.score_list');

    }
    /**
     * 佣金来源记录
     * @return void
     */
    public function commissionRecord(){
        $this->ment[] = array("commissionRecord","佣金明细",ROUTE_M.'/'.ROUTE_C."/commissionRecord",2);
        $uid = intval($this->segment(4));
        if($uid <= 0 || !$this->admin_can_view_member_uid($uid)){
            _message("无权限查看该会员数据", WEB_PATH.'/'.ROUTE_M.'/member/lists');
        }
        $junior = $this->db->Getlist("select uid from `@#_member` where yaoqing = :uid", array('type'=>1,'key'=>''), array('uid'=>$uid));
        $jids = $junior ? array_column($junior,"uid") : array();
        $jids = array_map('intval', $jids);
        $jids_str = implode(",", $jids);
        $sql = $jids_str !== '' ? "select * from `@#_member_recodes` where (`type` = 1 and uid in ($jids_str)) or (`type` <> 1 and uid = $uid)" : "select * from `@#_member_recodes` where `type` <> 1 and uid = $uid";
        $num=20;
        $total = $this->db->GetCount($jids_str !== '' ? "select count(*) from `@#_member_recodes` where (`type` = 1 and uid in ($jids_str)) or (`type` <> 1 and uid = $uid)" : "select count(*) from `@#_member_recodes` where `type` <> 1 and uid = $uid");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $list = $this->db->GetPage($sql,["num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0]);
        foreach($list as $key=>$val){
            $list[$key]['time']=date("Y-m-d H:i:s",$val['time']);
            if(in_array($val['type'],[1,-4])){
                $list[$key]['money'] = "+".$list[$key]['money'];
            }else{
                $list[$key]['money'] = "-".$list[$key]['money'];
            }
        }
        include $this->tpl(ROUTE_M,'member.commission.list');
    }

    public function money(){
        $this->ment = [
            ["money","账变记录",ROUTE_M."/".ROUTE_C."/money"],
        ];
        // // 充值记录
        // $sql0 = "SELECT t1.id,t1.uid,t1.type,t1.pay,t1.money,t1.time,t1.content
        //         FROM go_member_rechange t1
        //         JOIN (
        //             SELECT uid, MAX(id) AS latest_id
        //             FROM go_member_rechange
        //             GROUP BY uid
        //         ) t2 ON t1.uid = t2.uid AND t1.id = t2.latest_id";
        // //消费记录
        // $sql1 = "SELECT t1.id,t1.uid,t1.type,t1.pay,t1.money,t1.time,t1.content
        //         FROM go_member_account t1
        //         JOIN (
        //             SELECT uid,  MAX(id) AS latest_id
        //             FROM go_member_account
        //             GROUP BY uid
        //         ) t2 ON t1.uid = t2.uid AND t1.id = t2.latest_id";
        // // 佣金记录
        // $sql2 = "SELECT t1.id,t1.uid,t1.type,'佣金' as pay,t1.money,t1.time,t1.content
        //         FROM go_member_recodes t1
        //         JOIN (
        //             SELECT uid, MAX(id) AS latest_id
        //             FROM go_member_recodes
        //             GROUP BY uid
        //         ) t2 ON t1.uid = t2.uid AND t1.id = t2.latest_id";
        // $sql = "select r.*,m.username from ($sql0 union all $sql1 union all $sql2) r join go_member m on r.uid = m.uid group by r.uid order by r.time desc";
        // $total = $this->db->GetOne("select count(distinct r.uid) as total from ($sql0 union all $sql1 union all $sql2) r");
        // -- 使用COUNT(*) OVER() 一次性获取总数和分页数据
        $sql = "WITH ranked_data AS (
            SELECT 
                id, uid, type, pay, money, time, content,
                ROW_NUMBER() OVER (PARTITION BY uid, source ORDER BY id DESC) as rn
            FROM (
                SELECT 'rechange' as source, id, uid, type, pay, money, time, content
                FROM go_member_rechange
                UNION ALL
                SELECT 'account' as source, id, uid, type, pay, money, time, content
                FROM go_member_account
                UNION ALL
                SELECT 'recodes' as source, id, uid, type, '佣金' as pay, money, time, content
                FROM go_member_recodes
            ) all_records
        ),
        filtered_data AS (
            SELECT r.*, m.username,
                   COUNT(*) OVER() as total_count
            FROM ranked_data r
            JOIN go_member m ON r.uid = m.uid
            WHERE r.rn = 1
        )
        SELECT * FROM filtered_data
        ORDER BY time DESC";
        // $total = $this->db->GetOne("SELECT COUNT(DISTINCT r.uid) AS total FROM ($sql) r");
        // $total = $total['total'];
        $total = 0; // 假设总记录数为0，因为这里不需要分页
        $num = 20;
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $list = $this->db->GetPage($sql,["num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0]);
        if($list){
            $total = $list[0]['total_count'];
            foreach($list as $key => $vl){
                $list[$key]['time'] = date("Y-m-d H:i:s",$vl['time']);
                $list[$key]['money'] = $vl['type']==1?$vl['money']:-$vl['money'];
                if($vl['pay']=="账户"){
                    $list[$key]['money'] = number_format($list[$key]['money'],0);
                    $list[$key]['money'] = $this->currency['symbol'].$list[$key]['money'];
                    $pos = mb_strpos($vl['content'], '开奖失败退');
                    if($pos !== false){
                        $str = mb_substr($vl['content'], 0,$pos+mb_strlen('开奖失败退'));
                        $list[$key]['content'] = $str.$list[$key]['money'];
                    }
                }else{
                    $list[$key]['money'] = intval($list[$key]['money']);
                }
            }
        }
        $page=System::load_sys_class('page');
        $page->config($total,$num,$pagenum,"0");
        include $this->tpl(ROUTE_M,'record.money.list');
    }

    public function get_money_details(){
        $this->ment[] = array("get_money_details","账户明细",ROUTE_M.'/'.ROUTE_C."/get_money_details",2);
        $uid = intval($this->segment(4));
        if($uid <= 0 || !$this->admin_can_view_member_uid($uid)){
            _message("无权限查看该会员数据", WEB_PATH.'/'.ROUTE_M.'/member/lists');
        }
        $p = $this->segment(5)?:0;
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
        $member = $this->db->GetOne("select uid,username from `@#_member` where uid = :uid", array('uid'=>$uid));
        $sql = "select r.* from ($sql union all $sql1 union all $sql2) r order by r.time desc";
        $total = $this->db->GetOne(str_replace("r.*","count(*) as total",$sql));
        $total = $total['total'];
        $num = 20;
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $list = $this->db->GetPage($sql,["num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0]);
        foreach($list as $key => $vl){
            $list[$key]['username'] = $member['username'];
            $list[$key]['time'] = date("Y-m-d H:i:s",$vl['time']);
            $list[$key]['money'] = $vl['type']==1?$vl['money']:-$vl['money'];
            if($vl['pay']=="账户"){
                // TODO 是否显示整数
//                $list[$key]['money'] = number_format($list[$key]['money'],0);
                $list[$key]['money'] = number_format($list[$key]['money'],2);
                $list[$key]['money'] = $this->currency['symbol'].$list[$key]['money'];
                $pos = mb_strpos($vl['content'], '开奖失败退');
                if($pos !== false){
                    $str = mb_substr($vl['content'], 0,$pos+mb_strlen('开奖失败退'));
                    $list[$key]['content'] = $str.$list[$key]['money'];
                }
            }else{
                $list[$key]['money'] = intval($list[$key]['money']);
            }
        }
        include $this->tpl(ROUTE_M,'record.money.details');
    }
}