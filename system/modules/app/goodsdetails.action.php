<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/5/19
 * Time: 10:51
 */
System::load_app_class('base','app','no');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/5/23
 * Time: 14:29
 */
class goodsdetails extends base
{

    public function __construct()
    {
        parent::__construct();
    }
    /*
     * 普通商品详情
     * */
    public function details(){
        $mysql_model = System::load_sys_class('model');
        $itemid = $_POST['id'];//商品ID
//        $itemid = 9;
        
        if(empty($itemid)){
            $itemid = 9;
        }
        $info = $mysql_model->GetOne("select * from `@#_shoplist_jifen` where `id`='" . $itemid . "' and delete_time is null LIMIT 1");
        $uid = $this->uId;
        if ($uid) {
            $current = $mysql_model->GetOne("SELECT * FROM `@#_shopcollect` WHERE shopid=$info[id] AND uid=$uid");
            if ($current) {
                $info['current'] = 'active';
            } else {
                $info['current'] = '';
            }
        }
        $item = $info;
        if (!$item) {
            $this->jsonData->error('','商品不存在');
        }
        $key = $item['title'];
        $sid = $item['sid'];
        $sid_code = $mysql_model->GetOne("select * from `@#_shoplist_jifen` where `sid`='$sid' and delete_time is null  order by `id` DESC LIMIT 1");
        if(!$sid_code){            
            $this->jsonData->error('','商品不存在');
        }
        $sid_go_record = $mysql_model->GetOne("select * from `@#_member_go_record` where `shopid`='$sid_code[sid]' and `uid`='$sid_code[q_uid]' order by `id` DESC LIMIT 1");
        $category = $mysql_model->GetOne("select * from `@#_category` where `cateid` = '$item[cateid]' LIMIT 1");
        $brand = $mysql_model->GetOne("select * from `@#_brand` where `id`='$item[brandid]' LIMIT 1");
        $title = $item['title'];
        $item['picarr'] = unserialize($item['picarr']);

        $curtime = time();
        $shopitem = 'itemfun';
        //晒单数

        $shopid = $this->db->GetOne("select * from `@#_shoplist_jifen` where `id`='$itemid'");

        $shoplist = $this->db->GetList("select * from `@#_shoplist_jifen` where `sid`='$shopid[sid]'");
        $shop = '';
        foreach ($shoplist as $list) {
            $shop .= $list['id'] . ',';
        }
        $id = trim($shop, ',');
        if ($id) {
            $shaidan = $this->db->GetList("select * from `@#_shaidan` where `sd_shopid` IN ($id)");
            $sum = 0;
            foreach ($shaidan as $sd) {
                $shaidan_hueifu = $this->db->GetList("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd[sd_id]'");
                $sum = $sum + count($shaidan_hueifu);
            }
        } else {
            $shaidan = 0;
            $sum = 0;
        }
        if ($item['shenyurenshu'] == '0' || $item['xsjx_time'] == '0' || empty($item['q_uid'])) {
            $period_info = $mysql_model->GetOne("select * from `@#_period` order by `id` DESC LIMIT 1");
            if ($period_info['num'] >= 120) {
                $period = date("Ymd", strtotime("+1 day")) . '001';
            } else {
                $period = $period_info['period'] + 1;
            }
        }
        $item['percentage'] = round($item['canyurenshu'] / $item['zongrenshu'],2) * 100 . '%';
        if($item){
            $arr = [];
            $arr['id'] = $item['id'];
            $arr['title'] = $item['title'];
            $arr['integral'] = $item['s_score'];//积分
            $arr['money'] = $item['money'];
            $arr['img'] = G_UPLOAD_PATH.'/'.$item['thumb'];
            foreach($item['picarr'] as $k=>&$v){
                $v = G_UPLOAD_PATH.'/'.$v;
            }
            $arr['picarr'] = $item['picarr'];//banner
            $arr['content'] = '<style>img{margin:0px;vertical-align:bottom;}</style>'.$item['content'];//中间的图片介绍
            $this->jsonData->success('','成功',$arr);
        }
    }
    //积分商品详情
    public function jfgoodsdetails(){
        $sid = safe_replace($_POST['sid']);
        $id = safe_replace($_POST['id']);
        if (empty($sid)){
            $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_user`,`q_uid`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist` where `id` = '$id'");
        }else{
            $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_user`,`q_uid`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist` where `sid` = '$sid'");
        }
        $copy = true;
        $time = time();
        if (empty($goods)){
            if(empty($sid)) {
                $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_uid`,`q_user`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist_copy` where `id` = '$id'");
            } else {
                $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_uid`,`q_user`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist_copy` where `sid` = '$sid'");
            }
            $copy = false;
            $q_user = unserialize($goods['q_user']);
            $goods['q_user'] = $q_user;
            if (stripos($q_user['img'], "statics/uploads") !== false) {
                $goods['q_user']['img'] = G_WEB_PATH.'/'.$q_user['img'];
            } else {
                $goods['q_user']['img'] = G_UPLOAD_PATH.'/'.$q_user['img'];
            }
            $goods['q_end_times'] =$goods['q_end_time'];
            $goods['q_end_time'] = date("Y-m-d H:i:s",$goods['q_end_time']);
            $user_shop_list = $this->db->GetList("select m.*,u.v_id from `@#_member_go_record` m left join `@#_member` u on m.uid = u.uid where m.`uid`= '$goods[q_uid]' and m.`shopid` = '$goods[id]' and m.`shopqishu` = '$goods[num]'");
            // p("select * from `@#_member_go_record` where `uid`= '$goods[q_uid]' and `shopid` = '$id' and `shopqishu` = '$goods[num]'");
            // p($goods);
            foreach($user_shop_list as $user_shop_n){
                $goods['q_user']['total'] += $user_shop_n['gonumber'];
                if($user_shop_n['huode']){
                    $goods['q_user']['user_shop_time'] = $user_shop_n['time'];
                    $goods['user_end_time'] = date("Y-m-d H:i:s",$user_shop_n['time']);
                    $goods['user_buy_time'] = date("Y-m-d H:i:s",$user_shop_n['time']);
                    $goods['q_user']['user_shop_codes'] = $user_shop_n['goucode'];
                    $goods['q_user']['v_id'] = $user_shop_n['v_id'];
                    $goods['q_v_id'] = $user_shop_n['v_id'];
                }
            }
        }

        $qishu = $this->db->GetList("select `id`,`qishu` from `@#_shoplist_copy` where `sid`='$goods[sid]' order by id desc");
        if ($copy){
            $q_user = unserialize($goods['q_user']);
            if (!empty($q_user)){
                $goods['q_user'] = $q_user;
                if (stripos($q_user['img'], "statics/uploads") !== false) {
                    $goods['q_user']['img'] = G_WEB_PATH.'/'.$q_user['img'];
                } else {
                    $goods['q_user']['img'] = G_UPLOAD_PATH.'/'.$q_user['img'];
                }
            }
            if (empty($goods['q_end_times'])){
                $goods['q_end_times'] = $goods['q_end_time'];
            }
            $goods['q_end_time_s'] = $goods['q_end_times'] - $time;

            $gqishu = [
                'id' => $goods['id'],
                'qishu' => $goods['num'],
            ];
            array_unshift($qishu,$gqishu);
        } else {
            // 查询最新的商品
            $new_sid = $goods['sid'];
            $new_goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_user`,`q_uid`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist` where `sid` = '$new_sid'");
            if($new_goods) {
                $new_qishu = [
                    'id' => $new_goods['id'],
                    'qishu' => $new_goods['num'],
                ];
                array_unshift($qishu,$new_qishu);
            }
        }

        
        $goods['img'] = G_UPLOAD_PATH.'/'.$goods['img'];//积分商品详情头部
        $goods['proportion'] = strval(round($goods['currentNumber']/$goods['total'],2)) *100;//计算比率
        $picarr = unserialize($goods['picarr']);

        if (!empty($picarr)){
            foreach ($picarr as $kp => $vp){
                $goods['picarrArray'][$kp] = G_UPLOAD_PATH.'/'.$vp;//积分商品详情头部
            }
        }
        // 1为参与记录
        $id = $goods['id'];
        $cords=$this->db->GetList("select m.*,u.v_id from `@#_member_go_record` as m left join `@#_member` as u on m.uid = u.uid where `shopid`='$id'order by `time` desc");
        $co = count($cords);
        $arr = [];
        //重组参与记录的数据结构·
        foreach ($cords as $k => $v) {
            $arr[$k]['id'] = $v['uid'];
            $arr[$k]['v_id'] = $v['v_id'];
            $arr[$k]['name']=$v['username'];
            $arr[$k]['time']=date("Y-m-d H:i:s",$v["time"]);
            $arr[$k]['num'] = $v['gonumber'];
            $ids = $v['uid'];
            $user = $this->db->GetOne("select `img` from `@#_member` where `uid`='$ids'");
            $arr[$k]['img'] = G_UPLOAD_PATH.'/'.$user['img'];
            // $arr[$k]['img'] = $v['uphoto'];
            // $arr[$k]['ip'] = $v['ip'];
            if(!$v['ip_region']){
                $region = _get_ip_region(3,$v['ip']);
                $this->db->Query("UPDATE `@#_member_go_record` set `ip_region`='$region' WHERE `id` =".$v['id']);
            }
            $arr[$k]['ip_region'] = $v['ip_region'];
            $goucode = explode(',',$v['goucode']);
            sort($goucode);
            $arr[$k]['goucode'] = $goucode;

        }
        //2为历史获得者
        if($id){
            // $qishulist=$this->db->GetList("select `q_user`,`q_uid`,`title`,`buycount`,`q_user_code`,`time`,`q_end_time`,`thumb` from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist` where id='$id') order by q_end_time desc limit 0,30");
            $qishulist=$this->db->GetList("select u.`v_id`,`q_user`,`q_uid`,`title`,`buycount`,`q_user_code`,s.`time`,`q_end_time`,`thumb`,m.`ip`,m.`ip_region`
             from `@#_shoplist_copy` as s left join `go_member_go_record` as m on s.q_user_code = m.huode and s.id = m.shopid and s.q_uid = m.uid and s.qishu = m.shopqishu 
             left join `@#_member` as u on m.uid = u.uid 
             where sid =(select sid from `@#_shoplist` where id='$id') 
             order by q_end_time desc limit 0,30");
            //  left join `@#_member_ip` as i on u.uid = i.uid 
             if(!$qishulist){
                // $qishulist=$this->db->GetList("select `q_user`,`q_uid`,`title`,`buycount`,`q_user_code`,`time`,`q_end_time`,`thumb` from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist_copy` where id='$id') order by q_end_time desc limit 0,30");
                $qishulist=$this->db->GetList("select s.id,u.`v_id`,`q_user`,`q_uid`,`title`,`buycount`,`q_user_code`,s.`time`,`q_end_time`,`thumb`,m.`ip`,m.`ip_region`
                    from `@#_shoplist_copy` as s left join `go_member_go_record` as m on s.q_user_code = m.huode and s.id = m.shopid and s.q_uid = m.uid and s.qishu = m.shopqishu
                    left join `@#_member` as u on m.uid = u.uid 
                    where sid =(select sid from `@#_shoplist_copy` where id='$id') 
                    order by q_end_time desc limit 0,30");
            }
                                // left join `@#_member_ip` as i on u.uid = i.uid 
            $arra = [];
            $previous = [];$p_k = 0;
            //重构数据结构
            foreach($qishulist as $k=>$v){
                $userinfo=unserialize($v['q_user']);
                $name=empty($userinfo['username'])?$userinfo['mobile']:$userinfo['username'];
                $name=is_numeric($name)?substr_replace($name,'****',3,4):$name;
                $arra[$k]['uid'] = $v['q_uid'];//用户的ID
                $arra[$k]['id'] = $v['id'];//用户的ID
                $arra[$k]['v_id'] = $v['v_id'];//用户的ID
                $arra[$k]['name'] = $name;//用户姓名
                $arra[$k]['title'] = $v['title'];//商品标题
                $arra[$k]['term'] = $v['buycount'];//参与人数
                $arra[$k]['code'] = $v['q_user_code'];//中奖码
                $arra[$k]['start_time'] = date("Y-m-d H:i:s",$v["time"]);//开始时间
                $arra[$k]['end_time']=date("Y-m-d H:i:s",$v["q_end_time"]);//结束时间
                // $userimg=$this->db->GetOne("SELECT img FROM `@#_member` where uid=$v[q_uid]");
                $arra[$k]['img']=G_UPLOAD_PATH.'/'.$v['thumb'];//商品图片
                if(!$v['ip_region'] && $v['ip']){
                    $region = _get_ip_region(3,$v['ip']);
                    $this->db->Query("UPDATE `@#_member_go_record` set `ip_region`='$region' WHERE `id` =".$v['id']);
                }
                $arra[$k]['ip_region'] = $v['ip_region'];//用户的ID
                if($id == $v['id']){
                    $p_k = $k;
                }
            }
            if(!$p_k){
                $previous = $arra[0];
            }else{
                $previous = $arra[$p_k+1]??[];
            }
        }
        
        if(strstr($goods['money'],'.')){
            $moneyArr=explode('.',$goods['money']);
                if($moneyArr[1] == '00'){
                $goods['money']=$moneyArr[0];
            }
        }

        $goods['collect'] = false;
        $uid = safe_replace($_POST['uid']);
        if(!empty($uid)){
            $isHave = $this->db->GetOne("select `shopid` from `@#_shopcollect` where `uid`='$uid' and `shopid`='$id'");
            if (!empty($isHave)){
                $goods['collect'] = true;
            }
        }
        $currency = $this->currency;
        //拼团价格
        $groups = explode(",",$goods['groups']);
        $group_price = [];
        foreach($groups as $vl){
            $group_price[] = [
                "num"=>$vl,
                "price"=>$goods['money']/$vl
            ];
        }
        $goods['group_price'] = $group_price;
        //拼团记录
        $uid = $this->uId;
        $sql = "select o.id,o.uid,o.time,o.group_num,o.pid,o.goucode,m.v_id,m.img,i.ip_region from `@#_orders_group` o
                left join `@#_member` m on m.uid = o.uid
                left join `@#_member_ip` i on m.uid = i.uid
                where o.uid = $uid and o.shopid = $id";
        $groups = $this->db->GetList($sql);
        foreach($groups?:[] as &$vl){
            $vl['img'] = G_UPLOAD_PATH.'/'.$vl['img'];
            $vl['time'] = date("Y-m-d H:i:s");
            $goucode = explode(',',$vl['goucode']);
            sort($goucode);
            $vl['goucode'] = $goucode;
        }
        $data = [
            'goods'=> $goods,
            'content' =>$arr,
            'main'=>$arra,
            'previous'=>$previous,
            'group'=>$groups,
            'charts'=>G_WEB_PATH . "/mobile/ /movements/$id",
//            'img'=>$desc,
            'img'=>$goods['content'],
            'qishu'=>$qishu,
            'currency' => $currency
        ];
        $this->jsonData->successCode('','成功',$data);
    }
    /*
     * 积分商品中奖计算详情信息
     * */
    public function goodscode(){
        $itemid = safe_replace($_POST['id']);

        $item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
        $h=abs(date("H",$item['q_end_time']));
        $i=date("i",$item['q_end_time']);
        $s=date("s",$item['q_end_time']);
        $w=substr($item['q_end_time'],11,3);
        $user_shop_time_add = $h.$i.$s.$w;
        $user_shop_fmod = fmod($user_shop_time_add*100,$item['canyurenshu']);
        $q_external_code=$item['q_external_code'];
        if($item['q_content']){
            $now_shop_content  = unserialize($item['q_content']);
            $shop_content_arr = [];
            foreach ($now_shop_content as $key => $value) {
                $time = date("Y-m-d H:i:s",$value['time']);
                $value['time'] = $time;

                array_push($shop_content_arr, $value);
            }
            $item['q_content'] = $shop_content_arr;
            $user_shop_time_add = $item['q_counttime'];
            $user_shop_fmod = fmod($item['q_counttime'],$item['canyurenshu']);
        }
        $external_time=unserialize($item['q_external_content']);
        $external_time=$external_time['ssc_phase'];
        $item['picarr'] = unserialize($item['picarr']);
        $end_shop_time = date("Y-m-d H:i:s",$item['q_end_time']);
        foreach($item['q_content'] as $k=>$v){
            $arr[$k]['time'] = $v['time'];
            $arr[$k]['code'] = $v['time_add'];
            $arr[$k]['username'] = $v['username'];
        }
        if(empty($arr)){
            $arr = [];
        }
        $data = [
            'sum' => $user_shop_time_add,
            'number' => $item['canyurenshu'],
            'remainder' => strval($user_shop_fmod),
            'code' =>$item['q_user_code'],
            'time' =>$end_shop_time,
            'contentlist'=>$arr,
        ];
        $this->jsonData->success('','成功',$data);
    }

        /**
     * 商品参与者
     */
    public function goods_participant(){
        // 1为参与记录
        $sid = safe_replace($_POST['sid']);
        $id = safe_replace($_POST['id']);
        $p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
        $num = 10;
        $star = ($p) * $num;
        if (empty($sid)){
            $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_user`,`q_uid`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist` where `id` = '$id'");
        }else{
            $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_user`,`q_uid`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist` where `sid` = '$sid'");
        }
        if (empty($goods)){
            if(empty($sid)) {
                $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_uid`,`q_user`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist_copy` where `id` = '$id'");
            } else {
                $goods = $this->db->GetOne("SELECT `id`,`title`,`sid`,`money`,`yunjiage`,`q_user_code`,`canyurenshu` as currentNumber ,`qishu` as num,`shenyurenshu` as surplus,`zongrenshu` as total,`thumb` as img,`picarr`,`q_uid`,`q_user`,`content`,`q_end_time`,`preferential_price`,`groups` FROM `@#_shoplist_copy` where `sid` = '$sid'");
            }
        }
        if(!$goods){
            $this->jsonData->error('','商品不存在');
        }
        $id = $goods['id'];
        $cords=$this->db->GetList("select m.*,u.v_id from `@#_member_go_record` as m left join `@#_member` as u on m.uid = u.uid where `shopid`='$id'order by `time` desc LIMIT $star ,$num");
        $total = $this->db->getCount("select m.*,u.v_id from `@#_member_go_record` as m left join `@#_member` as u on m.uid = u.uid where `shopid`='$id'");
        $iplist = $this->db->GetList("select id,ip from `@#_member_go_record` where ip_region is null limit 500");
        foreach ($iplist as $v){
            $region = _get_ip_region(3,$v['ip']);
            $this->db->Query("UPDATE `@#_member_go_record` set `ip_region`='$region' WHERE `id` =".$v['id']);
        }
        $co = count($cords);
        $arr = [];
        //重组参与记录的数据结构·
        foreach ($cords as $k => $v) {
            $arr[$k]['id'] = $v['uid'];
            $arr[$k]['v_id'] = $v['v_id'];
            $arr[$k]['name']=$v['username'];
            $arr[$k]['time']=date("Y-m-d",$v["time"]);
            $arr[$k]['num'] = $v['gonumber'];
            $ids = $v['uid'];
            $user = $this->db->GetOne("select `img` from `@#_member` where `uid`='$ids'");
            $arr[$k]['img'] = G_UPLOAD_PATH.'/'.$user['img'];
            // $arr[$k]['img'] = $v['uphoto'];
            // $arr[$k]['ip'] = $v['ip'];
            $arr[$k]['ip_region'] = $v['ip_region'];
            $arr[$k]['money_total'] = $v['moneycount'];
            $arr[$k]['money'] = $v['money'];
            $arr[$k]['point'] = $v['point'];
            $goucode = explode(',',$v['goucode']);
            sort($goucode);
            $arr[$k]['goucode'] = $goucode;
        }
        $this->jsonData->success(200,"ok",['list'=>$arr,"total"=>$total]);
    }

    //趋势图
    public function trendChart()
    {
        $itemid= safe_replace($_POST['id']);
        $item=$this->db->GetOne("select sid from `@#_shoplist` where `id`='$itemid' LIMIT 1");
        if(!$item){
            $item=$this->db->GetOne("select sid from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
            if(!$item){
                _messagemobile("商品不存在");
            }else{
                $sid=$item['sid'];
                $item=$this->db->GetOne("select id,sid,qishu,zongrenshu,canyurenshu,shenyurenshu,title,thumb from `@#_shoplist` where `sid`='$sid' order by id desc");
            }
        }else{
            $sid=$item['sid'];
            $item=$this->db->GetOne("select id,sid,qishu,zongrenshu,canyurenshu,shenyurenshu,title,thumb from `@#_shoplist` where `sid`='$sid' order by id desc");
        }
        $item['thumb'] = G_UPLOAD_PATH.'/'.$item['thumb'];

        $itemlist = $this->db->GetList("select buycount,goucount,qishu,q_user,q_user_code from `@#_shoplist_copy` where `sid`='$sid' AND `q_user_code` is not null order by `qishu` desc LIMIT 0,10");
        foreach ($itemlist as $k => $value) {
            $buyids.=$value['buycount'].",";
            $userinfo=unserialize($value['q_user']);
            if($userinfo['username']){
                if(is_numeric($userinfo['username'])){
                    $itemlist[$k]['q_user']=substr_replace($userinfo['username'], '****', 3, 4);
                }else{
                    $itemlist[$k]['q_user']=$userinfo['username'];
                }
            }else{
                $itemlist[$k]['q_user']=substr_replace($userinfo['mobile'], '****', 3, 4);

            }
        }

        $buyids=trim($buyids,',');
        $cybfb=($item['canyurenshu']/$item['zongrenshu'])*100;
        $cybfb= sprintf("%.1f",$cybfb);
        $data = [
            "item" => $item,
            "itemlist" => $itemlist,
            "buyids" => $buyids,
            "cybfb" =>  $cybfb,
        ];
        $this->jsonData->success('','成功',$data);

    }

    //积分商品图片详情
    public function goodsdesc()
    {
        $itemid= safe_replace($_POST['id']);
        $item=$this->db->GetOne("select content from `@#_shoplist` where `id`='$itemid' LIMIT 1");
        if(!$item){
            $item=$this->db->GetOne("select content from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
        }
        $data = [
            "item" => $item['content'],
        ];
        $this->jsonData->success('','成功',$data);

    }

    // 查看计算结果
    public function calResult(){
        $itemid=safe_replace($_POST['id']);

        $this->jsonData->success('','成功',$data);
    }

    //查看计算结果
    public function getCalResult(){
        $itemid=safe_replace($_POST['id']);
        $item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid' and `q_end_time` is not null LIMIT 1");
        if($item['q_content']){
            $item['contcode']=0;
            $item['itemlist'] = unserialize($item['q_content']);
            foreach($item['itemlist'] as $key=>$val){
                $item['itemlist'][$key]['time']	=microt($val['time']);
                
                $userinfo = $this->db->GetOne("select uid,v_id from `@#_member` where uid=".$val['uid']);
                $item['itemlist'][$key]['v_id'] = $userinfo['v_id'];

                $h=date("H",$val['time']);
                $i=date("i",$val['time']);
                $s=date("s",$val['time']);
                list($timesss,$msss) = explode(".",$val['time']);
                $item['itemlist'][$key]['timecode']=$h.$i.$s.$msss;
            }
        }else{
            $item['contcode']=1;
        }
        if(!empty($item)){
            $item['code']=0;
        }else{
            $item['code']=1;
        }

//        $item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
        $h=abs(date("H",$item['q_end_time']));
        $i=date("i",$item['q_end_time']);
        $s=date("s",$item['q_end_time']);
        $w=substr($item['q_end_time'],11,3);
        $user_shop_time_add = $h.$i.$s.$w;
        $item['q_end_time'] = microt($item['q_end_time']);
        // $user_shop_fmod = fmod($user_shop_time_add*100,$item['canyurenshu']);
        $user_shop_fmod = fmod($user_shop_time_add*100,$item['zongrenshu']);
        $q_external_code=$item['q_external_code'];
        if($item['q_content']){
            $item['q_content'] = unserialize($item['q_content']);
            $user_shop_time_add = $item['q_counttime'];
            // $user_shop_fmod = fmod($item['q_counttime'],$item['canyurenshu']);
            unset($item['q_content']);
        }
        if(!$item['q_external_code']){
            $user_shop_fmod = fmod($item['q_counttime'],$item['canyurenshu']);
        }
        // $q_counttime=date("Y-m-d H:i:s",$item['q_counttime']);
        $q_counttime=$item['q_counttime'];
        $external_time=unserialize($item['q_external_content']);
        $external_time=$external_time['ssc_phase'];
        $item['picarr'] = unserialize($item['picarr']);

        // $item['q_end_time'] = date("Y-m-d H:i:s",$item['q_end_time']);
        // p($item);exit;
        $item['top'] = [
            "user_shop_fmod"    => $user_shop_fmod,
            "q_external_code"   =>  $q_external_code,
            "user_shop_time_add"   =>  $user_shop_time_add,
            "q_counttime"   =>  $q_counttime,
            "external_time" =>  $external_time
        ];
        $this->jsonData->success('','成功',$item);
    }
}