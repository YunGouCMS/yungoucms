<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('user');
//System::load_app_fun('my','yun4so');
class integral extends base
{

    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();

    }

    public function jifen_glist(){
        $p=htmlspecialchars($_POST['p'])?$_POST['p']:1;
        $cateid=safe_replace($_POST['cateid'])?:0;
        $recommend=safe_replace($_POST['recommend']);
        $sort = htmlspecialchars($_POST['sort'])?$_POST['sort']:10;//类型排序
        $where = '';
        $order = '';
        if($cateid){
            $where .= " and `cateid`='$cateid'";
        }
        if($recommend){
            $where .= ' and pos = 1';
        }
        if($sort == 10){
            $order .= 'order by `order` desc';
        }
        if($sort == 20){
            $order .= "order by `order` desc";
        }
        if($sort == 30){
            // fictitious
            $order .= 'order by `sales1` desc';
        }
        if($sort == 40){
            $order .= 'order by newhot desc, `time` DESC';
        }
        if($sort == 50){
            $order .= 'order by `money` DESC';
        }
        if($sort == 60){
            $order .= 'order by `money` ASC';
        }
        $end = 20;
        $star = ($p-1)*$end;
        $where .= ' and online = 1';
        $sql = "SELECT *,`sales`+ `fictitious` as sales1 FROM `@#_shoplist_jifen` WHERE 1 $where {$this->where_language} and delete_time is null and status = 1 $order limit $star,$end";
        $list = $this->db->GetList($sql);
        foreach($list as &$vl){
            // $vl['sales'] = $vl['sales'] + $vl['fictitious'];
            $vl['sales'] = $vl['sales1'];
            $vl['picarr'] = @unserialize($vl['picarr']);
            foreach($vl['picarr'] as &$v){
                $v = G_UPLOAD_PATH.'/'.$v;
            }
            $vl['thumb'] = G_UPLOAD_PATH.'/'.$vl['thumb'];
        }
        $this->jsonData->success('','',$list);
    }

    /**
     * 积分商品列表
     */
    public function integral_glist(){
        $p=htmlspecialchars($_POST['p']) ? $_POST['p'] : 1;
        $cate_band=safe_replace($_POST['cateid']);
        $recommend=safe_replace($_POST['recommend']);
        $sort = htmlspecialchars($_POST['sort'])?$_POST['sort']:10;//类型排序
        if(!$sort){
            $sort = '10';
        }
        if($cate_band){
            $fen1 = intval($cate_band);
//            $cate_band = 'list';
        }else{
//            echo $shoplist[0]['sum']=0;exit;
            $fen1=0;
        }
//    echo $fen1;
        //分页
        $end=10;
        $star=($p-1)*$end;
        $select_w = '';

        if($sort == 10){
            // $select_w = 'order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc';
            $select_w = 'order by `order` desc, canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc,s.id desc';
        }
        if($sort == 20){
            // $select_w = "and 1`renqi` = '0'";
            $select_w = "order by `order` desc, canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc,s.id desc";
        }
        if($sort == 30){
            $select_w = 'order by `canyurenshu` desc,s.id desc';
        }
        if($sort == 40){
            $select_w = 'order by `time` DESC,s.id desc';
        }
        if($sort == 50){
            $select_w = 'order by `money` DESC,s.id desc';
        }
        if($sort == 60){
            $select_w = 'order by `money` ASC,s.id desc';
        }
        if($recommend){
            $recommend_where = " and pos = 1 ";
        }else{
            $recommend_where = "";
        }
        $uid = $this->uId?:'null';
        $sql = "select `s`.*,if(`c`.uid,1,0) as is_collect 
                from `@#_shoplist` as s
                left join `@#_shopcollect` as c on `s`.id = `c`.shopid and `c`.uid = $uid 
                where `q_uid` is null 
                and `ispk` != 1 
                and zongrenshu !=canyurenshu 
                and s.language in ('$this->nowUserLanguage', '')
                $recommend_where
                ";
        if($fen1){
            $sql .= " and `cateid`='$fen1'";
        }
        $sql .= "$select_w limit $star,$end";
        $shoplist = $this->db->GetList($sql);
        $a=[];
        //货币单位
        $currency = $this->currency;
        foreach ($shoplist as $key=>$val){
            $a[$key]['currency'] = $currency;
            $a[$key]['id'] = $val['id'];
            $a[$key]['qishu'] = $val['qishu'];
            // 计算误差
            $parts = explode('.', (string)($val['canyurenshu']/$val['zongrenshu'] *100));
            $integerPart = $parts[0];
            if(isset($parts[1])){
                $decimalPart = substr($parts[1], 0, 2); // 截取小数部分的前两位
            }else{
                $decimalPart = "00";
            }
            $a[$key]['width'] = $integerPart . '.' . $decimalPart; // 组合回数字
//            $a[$key]['width'] = number_format($val['canyurenshu']/$val['zongrenshu'],2);
            $a[$key]['title'] = $val['title'];
            $a[$key]['canyurenshu'] = $val['canyurenshu'];
            $a[$key]['shengyu'] = $val['shenyurenshu'];
            $a[$key]['zongrenshu'] = $val['zongrenshu'];
            $a[$key]['is_collect'] = $val['is_collect'];  //是否收藏
            // $a[$key]['money'] = $val['money'];
            if(strstr($val['money'],'.')){
                $moneyArr=explode('.',$val['money']);
                 if($moneyArr[1] == '00'){
                    $a[$key]['money']=$moneyArr[0];
                }else{
                    $a[$key]['money']=$val['money'];
                }
            }else{
                $a[$key]['money']=$val['money'];
            }
            $a[$key]['img']=G_UPLOAD_PATH.'/'.$val['thumb'];
        }

        $this->jsonData->successCode('','成功',$a);
    }

    /**
     * 积分商品类目
     */
    public function integral_glist_catalog(){
        $model = htmlspecialchars($_POST['model'])?:1;
        $category=$this->db->GetList("select `cateid`,`name`,`languages`,`pic_url` from `@#_category` where `model` = '$model' and  (`parentid` = '0'|| `parentid` is null) order by `order` desc");
        foreach($category as $key => $val){ 
            $languages = unserialize($val["languages"]);
            $name = $languages[$this->nowUserLanguage];
            if($name){
                $category[$key]["name"] = $name;
            }
            unset($category[$key]["languages"]);
            $category[$key]['thumb'] = G_UPLOAD_PATH."/".$val['pic_url'];
            unset($category[$key]["pic_url"]);
        }
        
        $this->jsonData->successCode('','成功',$category);
    }

    public function integral_collection(){
        $id=safe_replace($_POST['id']);
        $typeid=safe_replace($_POST['typeid']);
        $member=$this->uId;
        $uid=$member;
        if(!$member){
            $this->jsonData->error('1','未登录');
        }else{
            if(!$id){
                $this->jsonData->error('2','未知商品');
            }else{
                if($typeid == 1){//添加收藏
                    $time=time();
                    $rr=$this->db->Query("INSERT INTO `@#_shopcollect` (`shopid`,`uid`,`time`) VALUES('$id','$uid','$time')");
                    if($rr){
                        $this->jsonData->success('3','收藏成功');
                        echo 3;exit;
                    }else{
                        $this->jsonData->error('4','收藏失败');
//                        echo 4;exit;
                    }
                }else{//取消收藏
                    $rs=$this->db->Query("DELETE FROM `@#_shopcollect` WHERE shopid='$id' AND uid='$uid'");
                    if($rs){
                        $this->jsonData->success('5','取消收藏成功');
//                        echo 5;exit;
                    }else{
                        $this->jsonData->success('6','取消收藏成功');
//                        echo 6;exit;
                    }
                }

            }
        }
    }

    /**
     * 最新揭晓商品详情
     */
    public function announce_details(){
        $itemid=intval($_POST['id']);
        $field='`id`,`qishu`,`title`,`money`,`thumb`,`q_user_code`,`q_uid`,`q_end_time`,`content`,`sid`';
//        $field='*';
//        echo $itemid;exit;
        $info=$this->db->GetOne("select $field from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");
        if(!$info){
            $info=$this->db->GetOne("select $field from `@#_shoplist_copy` where `id`='".$itemid."' LIMIT 1");
        }
//        $item=$info;
        if(!$info) $this->jsonData->error('','商品不存在');
        $new_shop=$this->db->GetOne("select id from `@#_shoplist` where `sid`='".$info['sid']."' order by id desc LIMIT 1");
        if(empty($new_shop)) $this->jsonData->error('','参数错误');
//        p($new_shop);exit;
        $infosum=$this->db->GetOne("SELECT sum(gonumber) as gonumber,username FROM `@#_member_go_record` where uid=$info[q_uid] and shopid=$info[id]");
        $dingdan=$this->db->GetOne("select * from `@#_member_go_record` where `uid`='".$info['q_uid']."' and `shopid`='$itemid' LIMIT 1");
        $info['new_id']=$new_shop['id'];
        $info['gonumber']=empty($infosum['gonumber'])?'':$infosum['gonumber'];
        $info['go_time']=microt($dingdan['time']);
        $info['q_end_time']=microt($info['q_end_time']);
        $info['username']=empty($dingdan['username'])?'':$dingdan['username'];
        $info['thumb']=G_UPLOAD_PATH.'/'.$info['thumb'];
        $info['uphoto']=G_UPLOAD_PATH.'/'.$dingdan['uphoto'];
//        $info['uphoto']=$dingdan['uphoto'];
//        p($info);
        $this->jsonData->successCode('','ok',$info);
    }

    /**
     * 商品购买记录 参与记录
     */
    public function buyrecords(){
//        $webname=$this->_cfg['web_name'];
        $itemid=intval($_POST['itemid']);
        $state=intval($_POST['state']);
        if(empty($itemid)) $this->jsonData->error('','参数错误');

        if($state==1){
            $field='uphoto,gonumber,time,shopid,username,uid';
            $cords=$this->db->GetList("select $field from `@#_member_go_record` where `shopid`='$itemid'order by `time` desc");
//            p($cords);exit;
            $co = count($cords);
            foreach ($cords as $k => $v) {
                if(is_numeric($v['username'])){
                    $cords[$k]['username']=substr_replace($v['username'],'****',3,4);
                }
                $cords[$k]['time']=date("Y-m-d H:i:s",$v["time"]);

                $userimg=$this->db->GetOne("SELECT * FROM `@#_member` where uid=$v[uid]");
                $cords[$k]['uphoto']=G_UPLOAD_PATH.'/'.$userimg['img'];
            }

            $this->jsonData->successCode('','ok',$cords);
//            echo json_encode($cords);
        }else{
                $field='`id`,`title`,`thumb`,`q_user_code`,`q_uid`,`q_end_time`';
                $qishulist=$this->db->GetList("select $field from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist` where id='$itemid') order by q_end_time desc limit 0,30");
                if(!$qishulist){
                    $qishulist=$this->db->GetList("select $field from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist_copy` where id='$itemid') order by q_end_time desc limit 0,30");
                }
//                p($qishulist);exit;
                foreach($qishulist as $k=>$v){
                    $infosum=$this->db->GetOne("SELECT sum(gonumber) as gonumber,username FROM `@#_member_go_record` where uid=$v[q_uid] and shopid=$v[id]");
//                    p($infosum);exit;
                    $userinfo=unserialize($v['q_user']);
                    $qishulist[$k]['q_end_time']=date("Y-m-d H:i:s",$v["q_end_time"]);
                    $qishulist[$k]['gonumber']=$infosum['gonumber'];
                    $qishulist[$k]['username']=$infosum['username'];
                    $qishulist[$k]['thumb']=G_UPLOAD_PATH.'/'.$qishulist[$k]['thumb'];
//                    $qishulist[$k]['time']=date("Y-m-d H:i:s",$v["time"]);
                    $userimg=$this->db->GetOne("SELECT * FROM `@#_member` where uid=$v[uid]");
//                    $name=empty($userimg['username'])?$userimg['mobile']:$userimg['username'];
//                    $name=is_numeric($name)?substr_replace($name,'****',3,4):$name;
                    $qishulist[$k]['userimg']=G_UPLOAD_PATH.'/'.$userimg['img'];
                    $qishulist[$k]['go_time']=date("Y-m-d H:i:s",$userimg["time"]);
                }
                $this->jsonData->successCode('','ok',$qishulist);
//                echo json_encode($qishulist);

        }
    }

    /**
     * 我的云购记录
     */
    public function getUserBuyList(){
        $member=$this->uId;

        $FIdx=intval($_POST['page'])?intval($_POST['page']):0;

        $EIdx=50;
        if ($FIdx > 0) {
            $FIdx = $FIdx * $EIdx;
        }
        // -1:全部；1：进行中；3：已揭晓
        $state=intval($_POST['state']);
        if($state==1){
            //参与云购的商品 进行中...
            /* $shoplistall['listItems']=$this->db->GetList("select m.v_id,a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,a.q_uid,a.q_user,b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber,b.time  from `@#_shoplist` a 
            left join `@#_member_go_record` b on b.shopid=a.id 
            left join `@#_member` m on b.uid=m.uid 
            where b.uid='$member' and a.q_end_time is null GROUP BY shopid order by b.time desc limit  $FIdx,$EIdx " ); */
            $sql = "select * from (
                        select a.yunjiage,b.money,m.v_id,a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,
                        a.q_uid,a.q_user,b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber,
                        max(b.time) as time,1 as mode,null as oid,null as group_num,null as join_num
                        from `@#_shoplist` a 
                        left join `@#_member_go_record` b on b.shopid=a.id 
                        left join `@#_member` m on b.uid=m.uid 
                        where b.uid='$member' and a.q_end_time is null
                        GROUP BY shopid
                    union all
                        select m.v_id,a.id,a.thumb,b.join_num as canyurenshu,b.group_num as zongrenshu,b.q_end_time,a.q_showtime,
                        a.q_uid,a.q_user,b.shopname,null as shopqishu,b.shopid,b.uid,b.gonumber,b.time,
                        3 as mode,4 as money,5 as yunjiage,b.id as oid,b.group_num,b.join_num
                        from `@#_orders_group` b 
                        left join `@#_shoplist` a on b.shopid = a.id 
                        left join `@#_member` m on b.uid=m.uid 
                        where b.uid = '$member' and b.group_status = 1
                        GROUP BY oid
                    ) as g  order by time desc limit  $FIdx,$EIdx ";
            $shoplistall['listItems']=$this->db->GetList($sql);
            // echo str_replace("@#","go",$sql);
        }elseif($state==-1){

            /* $arr1=$this->db->GetList("select m.v_id,b.id,b.thumb,b.canyurenshu,b.zongrenshu,b.q_end_time,b.q_showtime,b.q_uid,b.q_user,a.shopname,a.shopqishu,a.shopid,a.uid,sum(a.gonumber) as gonumber,b.time from `@#_member_go_record` a 
            left join `@#_shoplist_copy` b on a.shopid=b.id 
            left join `@#_member` m on a.uid=m.uid 
            where a.uid='$member' and b.q_end_time is not null and b.q_showtime !='Y' GROUP BY shopid order by a.id desc limit $FIdx,$EIdx " );

            $arr2=$this->db->GetList("select  m.v_id,a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,a.q_uid,a.q_user,b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber,b.time from `@#_shoplist` a 
            left join `@#_member_go_record` b on b.shopid=a.id 
            left join `@#_member` m on b.uid=m.uid 
            where b.uid='$member'  GROUP BY shopid order by b.id desc limit  $FIdx,$EIdx " );

            $shoplistall['listItems']=array_merge($arr2,$arr1); */
            $sql = "select * from (
                        select s.yunjiage,a.money,m.v_id,a.shopname,a.shopqishu,a.shopid,a.uid,sum(a.gonumber) as gonumber,s.id,max(a.time) as time,
                            if(s.thumb is not null,s.thumb,sy.thumb) as thumb,
                            if(s.canyurenshu,s.canyurenshu,sy.canyurenshu) as canyurenshu,
                            if(s.zongrenshu,s.zongrenshu,sy.zongrenshu) as zongrenshu,
                            if(s.q_end_time,s.q_end_time,sy.q_end_time) as q_end_time,
                            if(s.q_showtime,s.q_showtime,sy.q_showtime) as q_showtime,
                            if(s.q_uid,s.q_uid,sy.q_uid) as q_uid,
                            if(s.q_user,s.q_user,sy.q_user) as q_user,
                            1 as mode,null as oid,0 as group_status,null as group_num,null as join_num
                        from `@#_member_go_record` a 
                        left join `@#_shoplist` s on a.shopid=s.id 
                        left join `@#_shoplist_copy` sy on a.shopid=sy.id and sy.q_end_time is not null and sy.q_showtime !='Y' 
                        left join `@#_member` m on a.uid=m.uid 
                        where a.uid='$member' 
                        GROUP BY shopid 
                    union all 
                        select m.v_id,b.shopname,null as shopqishu,b.shopid,b.uid,b.gonumber,
                        a.id,b.time,a.thumb,b.join_num as canyurenshu,b.group_num as zongrenshu,b.q_end_time,a.q_showtime,
                        a.q_uid,a.q_user,3 as mode,4 as money,5 as yunjiage,b.id as oid,b.group_status,b.group_num,b.join_num
                        from `@#_orders_group` b 
                        left join `@#_shoplist` a on b.shopid = a.id 
                        left join `@#_member` m on b.uid=m.uid 
                        where b.uid = '$member'
                        GROUP BY oid
                    ) as ss
                    order by time desc limit  $FIdx,$EIdx";
            $shoplistall['listItems'] = $this->db->GetList($sql);
            // echo str_replace("@#","go",$sql);
        }else{
            //参与云购的商品 已揭晓...
            /* $shoplistall['listItems']=$this->db->GetList("select m.v_id,b.id,b.thumb,b.canyurenshu,b.zongrenshu,b.q_end_time,b.q_showtime,b.q_uid,b.q_user,a.shopname,a.shopqishu,a.shopid,a.uid,sum(a.gonumber) as gonumber,b.time from `@#_member_go_record` a 
            left join `@#_shoplist_copy` b on a.shopid=b.id 
            left join `@#_member` m on a.uid=m.uid 
            where a.uid='$member' and b.q_end_time is not null and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " ); */
            $g_where = " and group_status = 2 and is_end = 1";
            $sql = "select * from (
                select a.yunjiage,b.money,m.v_id,a.id,a.thumb,a.canyurenshu,a.zongrenshu,a.q_end_time,a.q_showtime,a.q_uid,a.q_user,
                b.shopname,b.shopqishu,b.shopid,b.uid,sum(b.gonumber) as gonumber,max(b.time) as time,1 as mode,
                null as oid,0 as group_status,null as group_num,null as join_num
                from `@#_shoplist_copy` a 
                left join `@#_member_go_record` b on b.shopid=a.id 
                left join `@#_member` m on b.uid=m.uid 
                where b.uid='$member' and a.q_end_time is not null and a.q_showtime !='Y'
                GROUP BY shopid
            union all
                select m.v_id,a.id,a.thumb,b.join_num as canyurenshu,b.group_num as zongrenshu,b.q_end_time,'N' as q_showtime,
                a.q_uid,a.q_user,b.shopname,null as shopqishu,b.shopid,b.uid,b.gonumber,b.time,3 as mode,4 as money,5 as yunjiage,
                b.id as oid,b.group_status,b.group_num,b.join_num
                from `@#_orders_group` b 
                left join `@#_shoplist` a on b.shopid = a.id 
                left join `@#_member` m on b.uid=m.uid 
                where b.uid = '$member' and b.group_status = 2
                GROUP BY oid
            ) as g  order by time desc limit  $FIdx,$EIdx ";
            $shoplistall['listItems']=$this->db->GetList($sql);
        }
        if(!empty($shoplistall['listItems'])){
            foreach($shoplistall['listItems'] as $key=>$val){
                switch($val['mode']){
                    case 1:
                        $shoplistall['listItems'][$key]['q_user']=get_user_name($val['q_uid']);
                        if(empty($val['q_end_time'])){
                            $shoplistall['listItems'][$key]['q_end_time']='';
                            $shoplistall['listItems'][$key]['q_end_times'] = 0;
                            $shoplistall['listItems'][$key]['q_end_time_s'] = 0;
                        }else{
                            $endtimes = (int)substr($val['q_end_time'],0,10);
                            $shoplistall['listItems'][$key]['q_end_time'] = microt($val['q_end_time']);
                            $shoplistall['listItems'][$key]['q_end_times'] = $endtimes;
                            $shoplistall['listItems'][$key]['q_end_time_s'] = $endtimes - time();
                        }
                        if($val['q_end_time']!=''){
                            //商品揭晓中
                            if($val['q_end_time']<=time()){
                                //已揭晓
                                $shoplistall['listItems'][$key]['codeState']=2;
                            }else{
                                //揭晓中
                                $shoplistall['listItems'][$key]['codeState']=3;
                            }
                        } else{
                            //进行中
                            $shoplistall['listItems'][$key]['codeState']=1;
                        }
                        $shoplistall['listItems'][$key]['scale'] = ($val['canyurenshu']/$val['zongrenshu'])*100;
                        break;

                    case 3:
                        $oid = $val['oid'];
                        $res = $this->db->GetOne("select o.*,m.v_id from `@#_orders_group` o left join `@#_member` m on o.uid = m.uid where id = $oid");
                        if(!$res['huode']){
                            if($res['pid']){
                                $oid = $res['pid'];
                            }
                            $res = $this->db->GetOne("select o.*,m.v_id from `@#_orders_group` o left join `@#_member` m on o.uid = m.uid where huode <> '0' and (id = $oid or pid = $oid)");
                        }
                        if($res){
                            $shoplistall['listItems'][$key]['q_end_time'] = $res['q_end_time'];
                            $shoplistall['listItems'][$key]['q_uid'] = $res['uid'];
                            $shoplistall['listItems'][$key]['q_user'] = $res['username'];
                            $shoplistall['listItems'][$key]['v_id'] = $res['v_id'];
                            $endtimes = strtotime($res['q_end_time']);
                        }else{
                            $shoplistall['listItems'][$key]['v_id'] = '';
                            $shoplistall['listItems'][$key]['q_uid'] = '';
                        }
                        if(empty($val['q_end_time'])){
                            $shoplistall['listItems'][$key]['q_end_time']='';
                            $shoplistall['listItems'][$key]['q_end_times'] = 0;
                            $shoplistall['listItems'][$key]['q_end_time_s'] = 0;
                        }else{
                            $endtimes = strtotime($val['q_end_times']);
                            $shoplistall['listItems'][$key]['q_end_times'] = $endtimes;
                            $shoplistall['listItems'][$key]['q_end_time_s'] = $endtimes - time();
                        }
                        if(isset($val['group_status'])){
                            if($val['group_status']==1){
                                $shoplistall['listItems'][$key]['codeState']=1;
                            }
                            if($val['group_status']==2){
                                $shoplistall['listItems'][$key]['codeState']=2;
                            }
                            if($val['group_status']==3){
                                $shoplistall['listItems'][$key]['codeState']=4;
                            }
                        }else{
                            $shoplistall['listItems'][$key]['codeState']=1;
                        }
                        $shoplistall['listItems'][$key]['join_num'] =$val['join_num']?:1;
                        $shoplistall['listItems'][$key]['scale'] = ($val['join_num']?:1/$val['group_num'])*100;
                        break;
                }
                if(empty($shoplistall['listItems'][$key]['q_uid'])){
                    $shoplistall['listItems'][$key]['q_uid']='';
                }
                $shoplistall['listItems'][$key]['thumb']=G_UPLOAD_PATH.'/'.$val['thumb'];

                $shoplistall['listItems'][$key]['time'] = explode(' ',getLTF($val['time']))[0];

            }
        }
        else{
            $shoplistall['code']=1;
        }
        $this->jsonData->successCode('','ok',$shoplistall);
    }

    /**
     * 获得的商品
     */
    public function getUserOrderList(){
        $FIdx=intval($_POST['page']) ?: 0;
        $EIdx=10;

        if ($FIdx > 0) {
            $FIdx = $FIdx * $EIdx;
        }
        $proportion=System::load_sys_config("proportion","proportion");//回收比例
//        echo $proportion;exit;
        $field='b.qishu,b.title,b.thumb,a.time,b.money,a.`status`,a.id as oid,a.`code`,a.shopid,a.company_code,a.company_money,b.preferential_price';
        $member=$this->uId;
        // $orderlist=$this->db->GetList("select $field,b.id as 'bid',a.id as aid,a.address  from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where b.q_uid=$member and a.huode!=0 and b.q_showtime='N' order by b.time desc limit $FIdx,$EIdx " );

        $sql = "select * from (
                select $field,b.id as 'bid',a.id as aid,a.address,1 as mode,b.q_end_time,b.q_user_code,0 as pprice ,state
                from `@#_member_go_record` a 
                left join `@#_shoplist_copy` b on a.shopid=b.id 
                where b.q_uid=$member and a.huode!=0 and b.q_showtime='N' 
                union all
                select $field,b.id as 'bid',a.id as aid,a.address,2 as mode,a.time as q_end_time,null as q_user_code,a.price as pprice,1 as state
                from `@#_orders` a
                left join `@#_shoplist` b on a.shopid=b.id 
                where a.uid = $member and a.status >=2
                union all
                select $field,b.id as 'bid',a.id as aid,a.address,3 as mode,a.q_end_time,a.huode as q_user_code,0 as pprice,1 as state
                from `@#_orders_group` a 
                left join `@#_shoplist` b on a.shopid=b.id 
                where a.uid = $member and a.status >=2 and a.huode !=0 and a.is_end = 1
                ) as g
                order by time desc limit $FIdx,$EIdx ";
            // echo str_replace("@#","go",$sql);
        $orderlist=$this->db->GetList($sql);

        if(empty($orderlist)){
            $this->jsonData->successCode('','没有获得商品记录',[]);
        }else{
            $userinfo = $this->db->GetList("select uid,level from `@#_member` where uid=$member");
            $state = ["1"=>"待支付","2"=>"已付款","3"=>"已发货","4"=>"未收货","5"=>"已完成","6"=>"已作废"];
            $state_text = ["1"=>"待支付","2"=>"确认地址","3"=>"已发货","4"=>"已发货","5"=>"订单已完成","6"=>"订单已取消"];
            $online_exchange = System::load_sys_config("system","online_exchange")?:1;
            foreach($orderlist as $key=>$val){
                switch($val['mode']){
                    case 1:
                        $table = "member_go_record";
                        //是否晒单
                        $shaidan = $this->db->GetOne("select * from `@#_shaidan` where sd_userid='{$this->uId}' and sd_shopid='{$val['shopid']}'");
                        if($shaidan){
                            $orderlist[$key]['shaidan_status'] = 1;
                        }else{
                            $orderlist[$key]['shaidan_status'] = 0;
                        }
                        break;
                    case 2:
                        $table = "orders";
                        break;
                    case 3:
                        $table = "orders_group";
                        break;
                }
                // $addreinfo=$this->db->Getone("SELECT a.id FROM  `@#_member_orderaddress_brand` AS a left join `@#_$table` AS b on b.code=a.order_code where b.id=$val[aid]");
                $addreinfo=$this->db->Getone("SELECT a.id FROM  `@#_member_orderaddress_brand` a where a.order_code = '{$val['code']}' ");
                // $addrinfo=$this->db->GetOne("select * from `@#_member_bind_phone` where phone='{$val['address']}'");
                //转赠
                $addrinfo = $this->db->GetOne("select * from `@#_member_transfer` where uid='{$this->uId}' and order_id = {$val['id']} and mode = {$val['mode']}'");
                if($addrinfo){
                    $orderlist[$key]['adr']=1;
                }else{
                    $orderlist[$key]['adr']=0;
                }
                if($proportion){
                    $orderlist[$key]['proportion']= $proportion;
                    $orderlist[$key]['recovery']= floor($orderlist[$key]['money'] * $proportion * 0.01);
                }else{
                    $orderlist[$key]['proportion']= 0;
                    $orderlist[$key]['recovery']= 0;
                }

                unset($orderlist[$key]['bid']);
                $orderlist[$key]['thumb']=G_UPLOAD_PATH.'/'.$orderlist[$key]['thumb'];

                $status=$orderlist[$key]['status'];
                $orderlist[$key]['duihuan']=0;
                if($orderlist[$key]['adr']){
                    $orderlist[$key]['statusss']='已转赠他人';
                }else{
                    if(stristr($status,'已回收')){
                        //已完成  1
                        //  $orderlist[$key]['statusss']=1;
                        $orderlist[$key]['statusss']='已完成';
                        $orderlist[$key]['q_end_time']=microt($val['q_end_time']);
                    }else{
                        switch($val['mode']){
                            case 1:
                                $orderlist[$key]['online_exchange'] = $online_exchange;
                                if(stristr($status,'未发货') && stristr($status,'已付款') && !stristr($status,'已作废')){
                                    if(!$addreinfo){
                                        //请确认地址  2
                                    //    $orderlist[$key]['status']="";
                                        $orderlist[$key]['statusss']='确认地址';
                                        if($orderlist[$key]['proportion']){
                                            //在线兑换
                                            $orderlist[$key]['duihuan']=1;
                                        }
                                    }else{
                                        //正在备货  3
                                        $orderlist[$key]['statusss']='正在备货';
                                    }
                                }else{
                                    if(stristr($status,'已发货') && stristr($status,'待收货') && stristr($status,'已付款')){
                                        //已发货  4
                                        $orderlist[$key]['statusss']='已发货';
                                        if(!$addreinfo){
                                            //请确认地址
                                            $orderlist[$key]['statusss']='确认地址';
                                        }
                                    }else{
                                        if(stristr($status,'已发货') && stristr($status,'已完成') && stristr($status,'已付款')){
                                            //订单已完成  5
                                            $orderlist[$key]['statusss']='订单已完成';
                                        }else{
                                            if(stristr($status,'已作废')){
                                                //订单已取消  6
                                                $orderlist[$key]['statusss']='订单已取消';
                                            }
                                        }
                                    }
                                }
                                $orderlist[$key]['title'] = "({$this->getLgMsg('CPhase')}$val[qishu]{$this->getLgMsg('phase')})" . $val['title'];
                                $orderlist[$key]['q_end_time']=microt($val['q_end_time']);
                                break;
                            case 2:
                                if(!$val['title']){
                                    $shop = $this->db->GetOne("select a.* from `@#_shoplist_copy` a where a.id = ".$val['shopid']);
                                    $orderlist[$key]['title'] = $shop['title'];
                                    $orderlist[$key]['preferential_price'] = $shop['preferential_price'];
                                    $orderlist[$key]['thumb'] = G_UPLOAD_PATH.'/'.$shop['thumb'];
                                    // $orderlist[$key]['title'] = $shop['title'];
                                }
                                $orderlist[$key]['preferential_price'] = $orderlist[$key]['pprice']?:$orderlist[$key]['preferential_price'];
                                // $orderlist[$key]['q_end_time'] = microt($val['time']);
                                $orderlist[$key]['q_end_time'] = date("Y-m-d H:i:s",$val['time']);
                                $orderlist[$key]['statusss'] = $state_text[$val['status']];
                                if($addreinfo && $val['status']==2){
                                    $orderlist[$key]['statusss']='正在备货';
                                }
                                break;
                            case 3:
                                $orderlist[$key]['statusss'] = $state_text[$val['status']];
                                if($addreinfo && $val['status']==2){
                                    $orderlist[$key]['statusss']='正在备货';
                                }
                                break;

                        }
                    }
                }
//                unset($orderlist[$key]['status']);
                // unset($orderlist[$key]['adr']);
                if($userinfo['level']!=1){
                    $orderlist[$key]['adr'] = -1;
                }
            }
            $this->jsonData->successCode('','ok',$orderlist);
        }

    }
    //我的收藏
    public function mycollect(){
         $shop=0;
        $field='`id`,`qishu`,`title`,`thumb`,`money`,`zongrenshu`,`shenyurenshu`,`canyurenshu`';
        $uid=$this->uId;

        $shoplist=$this->db->GetList("SELECT $field FROM `@#_shoplist` AS a LEFT JOIN `@#_shopcollect` as b ON b.shopid=a.id where b.uid='$uid' order by b.time desc");
        if($shoplist){
            foreach ($shoplist as $k=>$v){
                $shoplist[$k]['thumb']=G_UPLOAD_PATH.'/'.$v['thumb'];
                $cybfb=($v['canyurenshu']/$v['zongrenshu'])*100;
                $cybfb= sprintf("%.1f",$cybfb);
                $shoplist[$k]['percent']= $cybfb;
            }
            $shop=1;
        }
        $this->jsonData->successCode('','ok',$shoplist);
    }

    //首页添加收藏
    public function show_collect(){
        $id=safe_replace($_POST['shopid']);
        $typeid=safe_replace($_POST['type']);
//        $member=$this->uId;
        $uid=$this->uId;
        $data=$this->db->GetOne("SELECT * FROM `@#_shopcollect` WHERE shopid='$id' AND uid='$uid'");
//        var_dump($data);exit;
        if(empty($typeid)){
            if(empty($data)){
//                echo 11;
                //添加收藏
                $time=time();
                $rr=$this->db->Query("INSERT INTO `@#_shopcollect` (`shopid`,`uid`,`time`) VALUES('$id','$uid','$time')");
                if($rr){
                    // $this->jsonData->success('','添加收藏成功');
                    $this->jsonData->success('',$this->getLgMsg("collect_goods"));
                }else{
                    // $this->jsonData->error('添加收藏失败');
                    $this->jsonData->success('',$this->getLgMsg("collect_goods_fail"));
                }
            }else{
                $rs=$this->db->Query("DELETE FROM `@#_shopcollect` WHERE shopid='$id' AND uid='$uid'");
                if($rs){
                    // $this->jsonData->success('','取消收藏成功');
                    $this->jsonData->success('',$this->getLgMsg("cancel_collect_ok"));
                    //                        echo 5;exit;
                }else{
                    // $this->jsonData->error('','取消收藏失败');
                    $this->jsonData->success('',$this->getLgMsg("cancel_collect_fail"));
//                        echo 6;exit;
                }
            }
        }else{
            if(empty($data)){
                $time=time();
                $rr=$this->db->Query("INSERT INTO `@#_shopcollect` (`shopid`,`uid`,`time`) VALUES('$id','$uid','$time')");
                if($rr){
                    $this->jsonData->success('','添加收藏成功');
                }else{
                    $this->jsonData->error('','添加收藏失败');
                }
            }else{
                $this->jsonData->error('','不能重复收藏');
            }
        }

    }

    public function cart_price_list(){
     $uid=$this->uId;
//     $uid=51399;
     $field='b.qishu,b.title,b.money,a.num';
//     echo $uid;
     $data=$this->db->GetList("SELECT $field FROM `@#_cart` as a left join `@#_shoplist` as b on a.shopsid = b.sid where a.uid=$uid order by a.addtime desc");
     $sum=0;
     foreach ($data as $k=>$v){
         $data['sum']+=$v['num'];
     }
//     $data['sum']=$sum;
     $this->jsonData->successCode('','ok',$data);
//     var_dump($data);
    }

    /**
     * 积分结算支付
     */
    public function integral_pay(){
        $uid=$this->uId;
        $ids=safe_replace($_POST['ids']);
        $ids = str_replace ( ',0', '', $ids );
        $ids = trim ( $ids, ',' );

//        $uid=51440;
        $field='b.id,b.qishu,b.title,b.money,a.num,a.shopsid,b.ispk';
//        $sql="SELECT $field FROM `@#_cart` as a left join `@#_shoplist` as b on a.shopsid = b.id where a.uid=$uid and a.id in($ids) order by a.addtime desc";
//        echo $sql;exit;
        $data=$this->db->GetList("SELECT $field FROM `@#_cart` as a left join `@#_shoplist` as b on a.shopsid = b.id where a.uid=$uid and a.id in($ids) order by a.addtime desc");
//        var_dump($data);exit;
        $sum=0;
        $shopids='';
        $Mcartlist=array_column($data,NULL,'id');
        foreach ($data as $k=>$v){
            $sum+=$v['num'];
            $shopids.=intval ($v['id']) . ',';
        }
        $shopids = str_replace ( ',0', '', $shopids );
        $shopids = trim ( $shopids, ',' );

        $userinfo=$this->db->GetOne("select money from @#_member where uid=$uid ");
//        echo $sum;exit;
        if($userinfo ['money'] <=0 || $sum>$userinfo['money']){
            $this->jsonData->error('','积分不足');
        }
        if ($shopids != NULL) {
            $shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );
        }
//        var_dump($shoparr);exit;
        if (! empty ( $shoparr )) {
            foreach ( $shoparr as $key => $val ) {
                if($val['ispk']){
                    if($Mcartlist[$key]['num'] >1){
                        $this->jsonData->error( "",'购物车里单件PK商品数量大于1');
                    }else{
                        $is_pk=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where shopid='$val[id]' and uid=$uid ORDER BY id DESC ");
                        if($is_pk){
                            $this->jsonData->error( "",'购物车里PK商品已参与过');
                        }
                    }
                }
            }
        }
        $checkpay='money';
        $banktype='nobank';
//        $pay_checkbox = false;
        $pay_type_bank = false;
        $pay_type_id = false;
        if ($checkpay == 'money') {
            $pay_checkbox = true;
        }

        if ($banktype != 'nobank') {
            $pay_type_id = $banktype;
        }

        if (! empty ( $zhifutype )) {
            $pay_type_bank = $zhifutype ['pay_class'];
        }

        if (! $pay_type_id) {
            if ($checkpay != 'fufen' && $checkpay != 'money')
                $this->jsonData->error('','选择支付方式');
        }
        $pay=System::load_app_class('pay','pay');
        //修改支付每次都要使用积分问题 lq 2014-12-01
//        $pay->fufen = $fufen;
//        $pay->fufen = $checkpay=='fufen'?$sum:0;
        $pay->pay_type_bank = $pay_type_bank;
        // var_dump();exit;
        $ok = $pay->init($uid,$pay_type_id,'go_record','',0,0,$ids);	//云购商品
//        echo $ok;exit;
        if($ok != 'ok'){
            $this->jsonData->error('','购物车没有商品');
        }
       
        $check = $pay->go_pay ( $pay_checkbox,$ids );
        if (! $check) {
            $this->jsonData->error('','订单添加失败');
        }
        if ($check) {
            // 成功
            $this->jsonData->success('','支付成功');
        } else {
            // 失败
            $this->jsonData->error('','支付失败');
        }
//        exit ();
//        var_dump($data );
    }
    /*回收商品*/
    public function recover(){
        // $online_exchange = system::load_sys_config("system","online_exchange")?:1;
        $online_exchange = $this->getConfig("online_exchange")?:1;
        if($online_exchange!=1){
            $this->jsonData->error('','在线兑换未开启');
        }
        $id=intval(safe_replace($_POST['aid']));
        if(empty($id)) $this->jsonData->error('','参数错误');
        $proportion=System::load_sys_config("proportion","proportion");
        $this->db->Autocommit_start();
        $data=$this->db->GetOne("select * from `@#_member_go_record` where id=$id and status <> '已回收' and huode!=0 for update");
        if(empty($data)){
            $this->jsonData->error('','商品已回收');
        }
        $info=$this->db->GetOne("SELECT a.*,b.money FROM `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.id='$id' and a.status!='已回收' and a.huode!=0");
        $uid=$info['uid'];
        $shopid=$info['shopid'];
        $type="-4";
        $pay='账户';
        $money =floor($info['money']*$proportion*0.01);
        $content='兑换商品获得('.$money.")元";
        $time=time();
        sleep(1);
        $errinfo =$this->db->GetOne("SELECT * FROM `@#_member_recodes` where shopid='$shopid' and type='-4'");
        if(!$info || $errinfo){
            $this->jsonData->error('','商品已回收');
        }else {
            $commission_info = $this->db->GetOne("SELECT id FROM `@#_member_commission` where uid='$uid'");
            $insert = $this->db->Query("INSERT INTO `@#_member_recodes`(uid,type,content,shopid,money,time) VALUES ('$uid','$type','$content','$shopid','$money','$time')");
            if ($commission_info) {
                $up_usermoney = $this->db->Query("UPDATE `@#_member_commission` SET `total_money`=`total_money`+$money,shouru_money=shouru_money+'$money' WHERE uid='$uid'");
            } else {
                $up_usermoney = $this->db->Query("INSERT INTO `@#_member_commission`(uid,total_money,shouru_money) VALUES ('$uid','$money','$money')");
            }
            $update=$this->db->Query("UPDATE `@#_member_go_record` SET `status`='已回收' WHERE id='$id'");
            if ($update && $insert && $up_usermoney) {
                $this->db->Autocommit_commit();
                $this->jsonData->success('',$this->getLgMsg("Reward_success",'home_orderlist'));
            } else {
                $this->db->Autocommit_rollback();
                $this->jsonData->error('','兑换商品失败');
            }
        }
            echo $id;
    }

    //订单地址设置
    public function orderaddress_set(){

        $orderid=intval($_POST['aid']);
        $address_id=intval($_POST['add_id']);
        $type = intval($_POST['type']);
//        echo '$orderid:'.$orderid.'   $addid:'.$address_id;exit;
        if($orderid && $address_id && $type){
            switch($type){
                case 1:
                    //云购商品
                    $table = "member_go_record";
                    break;
                case 2:
                    // 优惠购商品
                    $table = "orders";
                    break;
                case 3:
                    // 拼团商品
                    $table = "orders_group";
                    break;
            }
            $sql = "select * from `@#_$table` where id = $orderid";
            $res = $this->db->GetOne($sql);
            if(!$res){
                $this->jsonData->error('',$this->getLgMsg('mobile_rulelist_noparam','controller_mobile'));
            }
            $time=time();
            $this->db->Autocommit_start();
            $info=$this->db->GetOne("SELECT mobile from `@#_member_dizhi` where id=$address_id");
            $phone=$info['mobile'];
            $tem = $this->db->GetOne("select id from `@#_member_orderaddress_brand` where `order_code` = '{$res['code']}'");
            if($tem){
                $q1 = $this->db->Query("update `@#_member_orderaddress_brand` set `address_id` = $address_id,`time` = $time where `order_code` ='{$res['code']}' ");
            }else{
                $q1 = $this->db->Query("INSERT INTO `@#_member_orderaddress_brand` (`orderid`,`order_code`,`address_id`,`time`,`mode`) VALUES ($orderid,'{$res['code']}',$address_id,$time,$type)");
            }
            $q2 =$this->db->Query("UPDATE `@#_$table` SET address=$phone WHERE id=$orderid");
            if($q1 && $q2){
                $this->db->Autocommit_commit();
                $notice = new notice();
                $detai = [
                    "type"=>$type,
                    "id"=>$orderid,
                    'code'=>$res['code']
                ];
                $data = [
                    "type"=>4,
                    "content"=>"订单地址确认",
                ];
                $notice->addNotice($data,$detai);
                $this->jsonData->success('',$this->getLgMsg('home_orderaddress_set_suc','controller_home'));

            }else{
                $this->db->Autocommit_rollback();
                $this->jsonData->error('',$this->getLgMsg('home_orderaddress_set_fail','controller_home'));

            }

        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('',$this->getLgMsg('home_orderaddress_set_fail','controller_home'));
        }
    }

    /**
     * 订单详情
     */
    public function orderdetails() {
        $oid = intval($_POST['oid']);
//        $status = intval($_POST['status']);
        $uid = $this->uId;
        // echo $oid . ',' . $uid . ',' . $status;
        $feild='aid,code,cretime,suctime,paycode,status,money,oid';
//        $sql = ;
        $order = $this->db->Getone("SELECT $feild FROM `@#_member_goods_order` WHERE `oid`=$oid AND `uid`=$uid");
        if(empty($order)) $order = $this->db->Getone("SELECT $feild FROM `@#_member_pay_order` WHERE `oid`=$oid AND `uid`=$uid");

        if(empty($order)) $this->jsonData->error('','参数错误');
        $feild2='id,title,thumb,money,s_score';
        $sql1 = "SELECT * FROM `@#_member_goods_item` WHERE `oid`=$oid";
        $oitem = $this->db->Getlist($sql1);
        if(empty($oitem)) $this->jsonData->error('','参数错误');
        foreach ($oitem as $key => $value) {
            $sid = $value['sid'];
            $sitem = $this->db->Getone("SELECT $feild2 FROM `@#_shoplist_jifen` WHERE `sid` = $sid");
            $sitem['thumb']=G_UPLOAD_PATH.'/'.$sitem['thumb'];
            $oitem[$key]['s_item'] = $sitem;
        }
        $order['o_item'] = $oitem;
        $text=['交易关闭','待付款','待发货','待收货','交易完成','已付款,金额异常','已付款,订单异常'];
        $order['status_code']=$text[$order['status']];
        $order['cretime'] =empty($order['cretime']) ? '' : date('Y-m-d H:i:s', $order['cretime']);
        $order['suctime'] =empty($order['suctime']) ? '' : date('Y-m-d H:i:s', $order['suctime']);
        $order['sendtime'] =empty($order['sendtime']) ? '' : date('Y-m-d H:i:s', $order['sendtime']);
        $order['endtime'] =empty($order['endtime']) ? '' : date('Y-m-d H:i:s', $order['endtime']);
        $aid = $order['aid'];
        $address = $this->db->Getone("SELECT * FROM `@#_member_dizhi` WhERE `uid`=$uid AND `id`=$aid");
        if($address){
            $address['df']=$address['default'];
            unset($address['default']);
            $order['address'] = [$address];
        }else{
            $order['address']=[];
        }
        if(empty($order['paycode'])) $order['paycode']='';
        $this->jsonData->successCode('','获取成功',$order);

    }

    public function receipt_yh($code,$status,$company,$company_code,$company_money,$mode){
        if($mode == 2){
            $table = "orders";
        }else{
            $table = "orders_group";
        }
        $record=$this->db->GetOne("SELECT `status` FROM `@#_$table` where `id`='$code'");
        if(!$record)$this->jsonData->error('','参数不正确');
        if($record['status'] != '3'){
            if(!$record)$this->jsonData->error('','订单状态不正确');
        }
       /*  if(!$company_money){
            $company_money = '0.01';
        }else{
            $company_money = sprintf("%.2f",$company_money);
        } */
        $status = 5;
        // $re = $this->db->Query("UPDATE `@#_$table` SET `status`='$status',`company` = '$company',`company_code` = '$company_code' where id='$code'");
        $re = $this->db->Query("UPDATE `@#_$table` SET `status`='$status' where id='$code'");

        if (!$re) $this->jsonData->error('','确认收货失败,请重试');
        $this->jsonData->success('','确认收货成功');
    }

    /**
     * 确认收货
     */
    public function receipt() {
        $code=abs(intval($_POST['aid']));
        $status = $_POST['status'];
        $company = $_POST['company']; // 快递单位
        $company_code = $_POST['company_code']; // 快递单号
        $company_money = floatval($_POST['company_money']); // 快递金额
        $mode = $_POST['mode']?:1; // 订单类型
        if($mode>1){
            $this->receipt_yh($code,$status,$company,$company_code,$company_money,$mode);
        }
        $record=$this->db->GetOne("SELECT `status` FROM `@#_member_go_record` where `id`='$code'");
        if(!$record || $record['status'] != "已付款,已发货,待收货"){
            $record=$this->db->GetOne("SELECT `status` FROM `@#_orders` where `id`='$code'");
            $mode = 2;
            if(!$record){
                $record=$this->db->GetOne("SELECT `status` FROM `@#_orders_group` where `id`='$code'");
                $mode = 3;
            }
            $this->receipt_yh($code,$status,$company,$company_code,$company_money,$mode);
        };
        if(!$record)$this->jsonData->error('','参数不正确');
        $record_code =explode(",",$record['status']);

        /* if(!$company_money){
            $company_money = '0.01';
        }else{
            $company_money = sprintf("%.2f",$company_money);
        } */
        if($status == '未完成'){
            $status = $record_code[0].','.$record_code[1].','.'未完成';
        }
        if($status == '已发货'){
            $status = '已付款,已发货,待收货';
        }
        if($status == '未发货'){
            $status = '已付款,未发货,未完成';
        }
        if($status == '已完成'){
            $status = '已付款,已发货,已完成';
        }
        if($status == '已作废'){
            $status = $record_code[0].','.$record_code[1].','.'已作废';
        }
        // $re = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status',`company` = '$company',`company_code` = '$company_code' where id='$code'");
        $re = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status' where id='$code'");

        if (!$re) $this->jsonData->error('','确认收货失败,请重试');
        $this->jsonData->success('',$this->getLgMsg('success'));
    }

    public function receipt_back() {
        $orderid = $_POST['orderid'];
        if (empty($orderid)) {
            $this->jsonData->error('','订单参数错误');
        }
        $res=$this->db->Getone("SELECT * FROM `@#_member_pay_order` where `oid`=$orderid AND `status`='4'");
        if($res) $this->jsonData->error('','不能重复确认收货');
        $endtime = time();
        $sql = "UPDATE `@#_member_pay_order` SET `status`='4',`endtime`='$endtime',`uptime`='$endtime' WHERE `oid`='$orderid'";
        $re = $this->db->Query($sql);
        if (!$re) $this->jsonData->error('','确认收货失败,请重试');
        $this->jsonData->success('','确认收货成功');
    }

    // 马上获知的商品

    public function GetStartRaffleAllList(){

        //更新拼团订单
        $time = date("Y-m-d H:i:s");
        if($this->db->GetOne("select id from `@#_orders_group` where q_end_time < '$time'")){
            $this->db->Query("update `@#_orders_group` set is_end = 1 where q_end_time < '$time'");
        }
        if($this->db->GetOne("select id from `@#_orders_group` where `end_time` < '$time' and `join_num` < `group_num`")){
            $glist = $this->db->GetList("select * from `@#_orders_group` where `end_time` < '$time' and `join_num` < `group_num` and group_status = 1");
            //金额退回
            foreach($glist as $vl){
                $uid = $vl['uid'];
                $time1 = time();
                if($vl['money']){
                    $money = $vl['money'];
                    // $pay_type = '商品'.$vl['shopid'].'拼团失败退回'.$money.'元';
                    $pay_type = "拼团失败，余额Rp".$money."已返还";
                    //金额退回用户
                    $this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
                    //记录原因
                    $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time1");
                }
                if($vl['point']){
                    $score = $vl['point'];
                    // $pay_type = '商品'.$vl['shopid'].'拼团失败退回'.$score.'积分';
                    $pay_type = "拼团失败，积分".$money."已返还";
                    //金额退回用户
                    $this->db->Query("UPDATE `@#_member` SET `score` = `score`+ $score WHERE `uid` = $uid");
                    //记录原因
                    $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=-1, `pay`='积分', `content`='$pay_type', `money`='$score',`time`=$time1");
                }

            }
            $this->db->Query("update `@#_orders_group` set group_status = 3 where `end_time` < '$time' and `join_num` < `group_num`");
        }

        $maxSeconds = 0;

        $result = array();

        $result['errorCode'] = 0;

        $result['maxSeconds'] = $maxSeconds;

        $result['listItems'] = array();

        $times = (int)System::load_sys_config('system','goods_end_time');

        $time = time();
        //$sql="select qishu,xsjx_time,id,thumb,title,q_uid,q_user,q_end_time,money from `@#_shoplist` where `q_showtime` = 'Y' and ssc !=1 AND id > '$maxSeconds' order by `q_end_time` DESC";
        $list = $this->db->getlist("select qishu,xsjx_time,id,thumb,title,q_uid,q_user,q_user_code,q_end_time,money from `@#_shoplist` where `q_showtime` = 'Y' and ssc !=1 AND id > '$maxSeconds' AND (`language` = '$this->nowUserLanguage' or `language` = '') order by `q_end_time` DESC");
        //中奖通知
        $s_type = System::load_sys_config("send","type");
        if($s_type == 2){
            $celebrate = System::load_sys_config('system','celebrate_img');
            $celebrate_img = @unserialize($celebrate);
            $result['celebrate_img'] = G_UPLOAD_PATH.'/'.$celebrate_img[$this->nowUserLanguage];
        }else{
            $result['celebrate_img'] = '';
        }
        $result['celebrate_pop'] = $s_type;
        // 中奖通知end

        $result['myList'] = [];
        if(empty($list)){
            $result['errorCode'] = 0;
            $result['maxSeconds'] = $maxSeconds;
            $result['listItems'] = $list;
            $this->jsonData->successCode('','请求成功',$result);
        }
        foreach($list as $item) {

            $recodeinfo=$this->db->GetList("select `gonumber` from `@#_member_go_record` where `uid` ='$item[q_uid]'  and `shopid`='$item[id]' ");
            $userinfo=$this->db->GetOne("select `username`,`v_id`,`uid`,`img` from `@#_member` where `uid` ='$item[q_uid]'");

            $goucount=0;

            foreach ($recodeinfo as $r) {
                $goucount+=(int)$r['gonumber'];
            }

            if ( $result['maxSeconds'] == $maxSeconds ) {

                $result['maxSeconds'] = $item['id'];

            }

            if($item['xsjx_time']){

                $item['q_end_time'] += $times;

            }

            $data = array();

            $data['id'] = $item['id'];

            $data['qishu'] = $item['qishu'];

            $data['title'] = $item['title'];

            $data['money'] = $item['money'];
            $data['v_id'] = $userinfo['v_id'];

            $data['thumb'] =G_UPLOAD_PATH.'/'.$item['thumb'];
            $data['userphoto'] =G_UPLOAD_PATH.'/'.$userinfo['img'];

            $data['seconds'] = intval($item['q_end_time'] - $time);
            $data['gonumber']=$goucount;

            $data['test'] = $userinfo;
            $data['this_uid'] = $this->uId;
            $data['item'] = $item;
            $result['listItems'][] = $data;
            if($userinfo['uid'] == $this->uId){
                $data['q_end_time'] = microt($item['q_end_time']);
                $data['huode'] = $item['q_user_code'];
                $result['myList'][] = $data;
            }
        }

        $this->jsonData->successCode('','请求成功',$result);

    }

    public function BarcodernoInfo(){
//        $itemid = intval($this->segment(4));
        $itemid = $_POST['id'];
        $list = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id`= $itemid");
        $this->db->Autocommit_start();
        if($list['ssc'] == 1){
            $url = "http://api.yungoucms.cn/getsscdata.php?token=bba23767dae041a3771e4dcde2528ab4&t=ssc";//时时彩接口地址

            $content = json_decode(file_get_contents($url));
            $ssc_data =(array)($content[0]);
            $openCode = $ssc_data['opencode'];
            $code=str_replace(',','',$openCode);	//时时彩中奖号码

            $ssc_contents=array();
            $ssc_contents['ssc_time']=$ssc_data['opentime'];
            $ssc_contents['ssc_phase']=$ssc_data['expect'];
            $q_external_content=serialize($ssc_contents);
            $q_external_code=$code;
            $q_end_time=$list['q_end_counttime'];
            $count_time=$q_end_time+$code;
            $go_code = 10000001+fmod($count_time,$list['zongrenshu']);

            $u_go_info = $this->db->GetOne("select * from `@#_member_go_record` where `shopid` = '$list[id]' and `shopqishu` = '$list[qishu]' and `goucode` LIKE  '%$go_code%'");
            $u_info = $this->db->GetOne("select uid,username,email,mobile,img from `@#_member` where `uid` = '$u_go_info[uid]'");
            $u_info['username'] = _htmtocode($u_info['username']);

            $q_user = serialize($u_info);
            $num=$this->db->GetOne("select sum(gonumber) as gnum from `@#_member_go_record` where shopid=".$itemid." AND uid=".$u_info['uid']);
            $buycount=$num['gnum'];
            $shoplist=$this->db->GetList("SELECT id,gonumber FROM `@#_member_go_record` WHERE `shopid`='$itemid' AND `shopqishu` = '$list[qishu]' order by time asc");
            $goucount=1;
            foreach ($shoplist as $ky => $v) {

                if($v['id'] == $u_go_info['id']){
                    break;
                }else{
                    $goucount+=$v['gonumber'];
                }

            }
            $sqlss = "UPDATE `@#_shoplist` SET

								`q_uid` = '$u_info[uid]',

								`q_user` = '$q_user',

								`q_user_code` = '$go_code',

								`q_counttime` ='$count_time',
								
								`q_showtime` ='N',
								
								`q_external_content` ='$q_external_content',
								
								`q_external_code` ='$code',
								
								`goucount` = '$goucount',
								
								`buycount` = '$buycount'
								
								 where `id` = '$itemid'";
            $sql2= "UPDATE `@#_member_go_record` SET `huode` = '$go_code' where `id` = '$u_go_info[id]' and `uid`= '$u_info[uid]' and `shopid` = '$itemid' and `shopqishu` = '$list[qishu]'";
            //file_put_contents('ssc.txt','sql---'.$sqlss."\n",FILE_APPEND);
            $q = $this->db->Query($sqlss);
            $res = $this->db->Query($sql2);
            $list=$this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id`= $itemid");
            //if(!$u_info['auto_user']){//发送短信
            //send_mobile_shop_code($u_info['mobile'],$u_info['uid'],$go_code);
            // }
        }
        if($list){
            /* $list['q_showtime']='N';
            $keys  = $vals = '(';
            $content = '';
            foreach($list as $key=>$val){
                if($key == "content"){
                    // $val = str_replace("'","\'", $val);
                    // $val = str_replace('"','\"', $val);
                    $content = $val;
                }
                $keys.="`$key`,";
                $vals.="'$val',";
            }
            $keys = rtrim($keys,',');
            $vals = rtrim($vals,',');
            $keys.= ')';
            $vals.= ')';
            $sql = "INSERT INTO `@#_shoplist_copy` ".$keys." VALUES ".$vals;
            $sql2= "DELETE FROM `@#_shoplist` WHERE id=$itemid"; */
            //写入中奖商品
            $sql = "INSERT INTO `@#_shoplist_copy` select * from `@#_shoplist` where id = ".$list['id'];
            $q1 = $this->db->Query($sql);
            //是否写入成功
            $sql1 = "SELECT id from `@#_shoplist_copy` where id = $itemid";
            $q11 = $this->db->Query($sql1);
            if($q1 || $q11){
                //删除原商品
                $sql2= "DELETE FROM `@#_shoplist` WHERE id=$itemid"; 
                $q2 = $this->db->Query($sql2);
                $this->db->Query("update `@#_shoplist_copy` set `q_showtime` = 'N' where id = ".$list['id']);
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();    
                $this->jsonData->successCode('','error');
            }
        }else{
            $list = $this->db->GetOne("SELECT * FROM `@#_shoplist_copy` WHERE `id`= $itemid");
        }
        $num=$this->db->GetOne("SELECT sum(gonumber) as gonumber FROM `@#_member_go_record` WHERE `uid` ='$list[q_uid]'  AND `shopid`='$list[id]'");
        $lists = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$list[q_uid]'");
        $result = array();
//        $result['code'] = 0;
//        $result['codeType']=0;
        $result['id'] = $list['id'];
        $result['title'] = $list['title'];
        $result['q_uid'] = $list['q_uid'];
        $result['gonumber']=(int)$list['buycount'];
        $result['thumb']=G_UPLOAD_PATH.'/'.$list['thumb'];
        $result['q_user_code'] = $list['q_user_code'];
        $result['q_end_time'] = date("Y-m-d H:i", $list['q_end_time']);
       $result['userphoto'] =G_UPLOAD_PATH.'/'.$lists['img'];
        // $result['userphoto'] =G_WEB_PATH.'/'.$lists['img'];
//        $result['headimg'] =$lists['headimg'];
        $result['qishu'] =$list['qishu'];
//        $result['ssc'] =$list['ssc'];
        if(!empty($lists['username'])){
            $quser=$lists['username'];
        }else{
            $quser=$lists['mobile'];
        }

        if(is_numeric($quser)){
            $quser=substr_replace($quser,'****',3,4);
        }
        $member = $this->db->GetOne("select * from `@#_member` where uid = ".$list['q_uid']);
        $result['v_id'] =$member['v_id'];
        $result['username'] =$quser;
        $result['mobile'] ='****';
        $data['listItems'][]=$result;
        $this->jsonData->successCode('','ok',$data);

    }

    //优惠订单
    public function getYhOrders(){
        $FIdx=intval($_POST['page'])?intval($_POST['page']):0;
        $EIdx=10;
        $uid = $this->uId;
        if ($FIdx > 0) {
            $FIdx = $FIdx * $EIdx;
        }
        $list  = $this->db->GetList("select * from `@#_orders` where uid=$uid limit $FIdx,$EIdx");
        $this->jsonData->successCode('','ok',$list);
    }

    //获取好友拼团订单  上级一级，下级无限
    public function getGroupOrders(){
        $FIdx=intval($_POST['page'])?intval($_POST['page']):0;
        $EIdx=10;
        if ($FIdx > 0) {
            $FIdx = $FIdx * $EIdx;
        }
        $shopid = intval($_POST['id']);
        if(!$shopid){
            $this->jsonData->error('','id不能为空');
        }
        $uid = $this->uId;
        /*$id = $uid;
         $ids = $this->userInfo(2)['yaoqing'];
        //查询下级
        do{
            $ch = $this->db->GetOne("select uid,yaoqing from `@#_member` where `yaoqing` = $id");
            if($ch){
                $id = $ch['uid'];
                $ids .= ",$id";
            }
        }while($ch); 
        $sql = "select o.id,o.username,o.uphoto,o.shopid,o.shopname,o.moneycount as money,o.time,o.ip_region,o.end_time,o.group_num,count(o1.id)+1 as join_num,s.thumb from `@#_orders_group` o 
            left join `@#_orders_group` o1 on o.id = o1.pid
            left join `@#_shoplist` s on o.shopid = s.id
            where o.uid in ($ids) and o.status = 2 and o.group_status = 1 and o.pid=0 group by o.id limit $FIdx,$EIdx";*/

        $sql = "select o.id,o.uid,o.username,o.uphoto,o.shopid,o.shopname,o.moneycount as money,o.time,o.ip_region,o.end_time,o.group_num,count(o1.id)+1 as join_num,s.thumb from `@#_orders_group` o 
            left join `@#_orders_group` o1 on o.id = o1.pid
            left join `@#_shoplist` s on o.shopid = s.id
            where o.shopid = $shopid and o.status = 2 and o.group_status = 1 and o.pid=0 group by o.id limit $FIdx,$EIdx";
        $list = $this->db->GetList($sql);
        $f_ids = $this->friendIds(2);
        foreach($list as $key=>$vl){
            $list[$key]['img']=G_UPLOAD_PATH.'/'.$vl['thumb'];
            $list[$key]['time']=date("Y-m-d H:i:s",$vl['time']);
            // $list[$key]['time']=date("Y-m-d H:i:s",$vl['time']);
            $endtime = strtotime($vl['end_time']);
            $list[$key]['second'] = $endtime - time();
            $list[$key]['countdown'] = convert($endtime - time());
            $is_friend = in_array($vl['uid'],$f_ids)?1:2;
            if($vl['uid'] == $this->uId){
                $is_friend = 3;
            }
            $list[$key]['is_friend'] = $is_friend;
        }
        $this->jsonData->successCode('','ok',$list);
    }

    /**
     * 
     * 获取拼团即将揭晓商品
     * @return void
     */
    public function getGroupAnnounce(){
        //拼团商品
        $time = date("Y:m-d H:i:s");
        $sql = "select o.id,s.title,s.thumb,s.id as shopid
            from `@#_orders_group` o
            left join `@#_shoplist` s on o.shopid = s.id 
            where o.q_end_time > '$time' and o.huode !=0";
        $list = $this->db->GetList($sql);
        if($this->db->GetOne("select id from `@#_orders_group` where q_end_time < '$time'")){
            $this->db->Query("update `@#_orders_group` set is_end = 1 where q_end_time < '$time'");
        }
        if($this->db->GetOne("select id from `@#_orders_group` where end_time < '$time'")){
            $this->db->Query("update `@#_orders_group` set group_status = 3 where end_time < '$time'");
        }
        $t = time();
        foreach($list as $key=> $vl){
            $list[$key]['seconds']= intval(strtotime($vl['q_end_time']) - $t);
            $list[$key]['img']=G_UPLOAD_PATH.'/'.$vl['thumb'];
        }
        $this->jsonData->successCode('','ok',$list?:[]);
    }

    /**
     * 获取物流
     */
    public function get_logistics(){
        $language = $this->nowUserLanguage;
        $sql = "select * from `@#_logistics` where is_state = 1 and (language = '$language' or language = '')";
        $list = $this->db->GetList($sql);
        $this->jsonData->success('',"ok",['list'=>$list]);
    }

    /**
     * 支付运费
     */
    public function pay_shiping_fee(){
        $id = safe_replace($_POST['id']);
        $res = $this->db->GetOne("select * from `@#_member_go_record` where id = $id");
        if(!$res){
            $this->jsonData->error();
        }
        if($res['state']!=1){
            $this->jsonData->error("","已支付");
        }
        $money = $res['company_money'];
        $uid = $this->uId;
        $user = $this->userInfo();
        if($user['money']< $money){
            $this->jsonData->error("","余额不足");
        }
        $this->db->Autocommit_start();
        $sql = "update `@#_member` set money = money - $money where uid = $uid";
        $res1 = $this->db->Query($sql);
        //记录支付金额
        $dingdancode = $res['code'];
        $time = time();
        $sql = "INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '账户', '支付运费', '{$money}', '$time')";
        $res11 = $this->db->Query($sql);
        $sql = Assemblydata(["state"=>2,"status"=>"已付款,未发货,未完成"],"member_go_record","up",[["id",$id]]);
        $res2 = $this->db->Query($sql);
        if($res1 && $res11 && $res2){
            $this->db->Autocommit_commit();
            $this->jsonData->success();
        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('',"");
        }
    }
}