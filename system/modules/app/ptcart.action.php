<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');

//System::load_app_fun('my','yun4so');

/**
 * 购物车
*/

class ptcart extends base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查看购物车商品
     */
    public function showcart()
    {
        $uid = $this->uId;
        $type = $_POST['type'];//字段区分是查什么数据库 1普通 2积分
        if($type == 1){
            $shoplist = $this->db->GetList("SELECT * FROM `@#_cart` WHERE `uid` = '$uid' AND `status` = '1'");
        }else{
            $shoplist = $this->db->GetList("SELECT * FROM `@#_cart` WHERE `uid` = '$uid' AND `status` = '2' order by `addtime` desc");
        }
        $sql = $type == 1?'_jifen':'';
        if ($shoplist) {
            $arr = [];
            $num = 0;
            foreach ($shoplist as $k => $v) {
                $sid = $v['shopsid'];
                $shopinfo = $this->db->GetOne("SELECT * FROM `@#_shoplist$sql` WHERE `id` = '$sid'");
                if($shopinfo){
                    $arr[$num] = [
                        'cid' => $v['id'],
                        'id' =>$shopinfo['id'],
                        'title'=>$shopinfo['title'],
                        'img' => G_UPLOAD_PATH.'/'.$shopinfo['thumb'],
                        'num' => $v['num'],
                        'price' => $shopinfo['yunjiage'],
                        'yunjiage' => $shopinfo['yunjiage'],
                        'ischeck' => true,
                        'mode'=>$v['mode'],
                    ];
                    if($type == 1){
                        $arr[$num]['money'] = $shopinfo["disc_price"]>0?$shopinfo['disc_price']:$shopinfo['money'];
                        // $arr[$num]['title'] = '('.$shopinfo['s_score'].$this->lgs['point'].')'.$arr[$num]['title'];
                    }else{
                        $arr[$num]['term'] = $shopinfo['qishu'];
                        $arr[$num]['surplus'] = (int)$shopinfo['shenyurenshu'];
                        $arr[$num]['preferential_price'] = $shopinfo['preferential_price'];
                    }
                }else{
                    $sid =  $this->db->GetOne("SELECT `sid` FROM `@#_shoplist_copy` WHERE `id` = '$sid'");
                    $shopinfo = $this->db->GetOne("SELECT `id`,`title`,`yunjiage`,`thumb`,`qishu`,`zongrenshu`,`canyurenshu`,`shenyurenshu` FROM `@#_shoplist` WHERE  `sid` = '$sid[sid]' order by `id` desc");
                    if($shopinfo){
                        $arr[$num] = [
                            'cid' => $v['id'],
                            'id' =>$shopinfo['id'],
                            'title'=>$shopinfo['title'],
                            'img' => G_UPLOAD_PATH.'/'.$shopinfo['thumb'],
                            'num' => $v['num'],
                            'ischeck' => true,
                            'price' => $shopinfo['yunjiage'],
                            'yunjiage' => $shopinfo['yunjiage'],
                            'mode'=>$v['mode'],
                        ];
                        if($type == 1){
                            $arr[$num]['money'] = $shopinfo['money'];
                            // $arr[$num]['title'] = '('.$shopinfo['s_score'].$this->lgs['point'].')'.$arr[$num]['title'];
                        }else{
                            $arr[$num]['term'] = $shopinfo['qishu'];
                            $arr[$num]['surplus'] = (int)$shopinfo['shenyurenshu'];
                            $arr[$num]['preferential_price'] = $shopinfo['preferential_price'];
                        }
                        $sql = $this->db->Query("UPDATE `@#_cart` SET `shopsid` = '$shopinfo[id]' WHERE `id` = '$v[id]'");
                        if(!$sql){
                            $this->jsonData->error('500',$this->lgs['create_error']?:'');
                        }
                    }else{
                        //商品表和揭晓表都没有 删除
                        $this->db->Query("delete FROM `@#_cart` where id = ".$v['id']);
                    }
                }
                $num ++;
            }
            
        }
        //推荐商品
        if($type == 1){
            $list = $this->db->Getlist("SELECT `id`,`thumb`,`title`,`money`,`s_score` as integral FROM `@#_shoplist_jifen` where `online` ='1' AND `quality` = '0' and delete_time is null  ORDER BY `order` DESC LIMIT 4");
        }else{
            $list = $this->db->Getlist("SELECT `id`,`thumb`,`title`,`money`,`qishu`,`shenyurenshu`,`zongrenshu`,`canyurenshu` FROM `@#_shoplist` where`renqi`=1 and `q_uid` is null AND ispk !=1  order by `order` desc LIMIT 4");
        }
        if($list){
            foreach($list as $ks=>&$vs){
                $qishu = empty($vs['qishu']) ? "":"($vs[qishu]". $this->lgs['phase'] .")";
                if($type == 2){
                    $vs['title'] = $qishu.$vs['title'];
                }
                $vs['img'] = G_UPLOAD_PATH.'/'.$vs['thumb'];
                //去掉小数点后的.00,不为00的不去除
                if(strstr($list[$ks]['money'],'.')){
                    $moneyArr=explode('.',$list[$ks]['money']);
                     if($moneyArr[1] == '00'){
                        $list[$ks]['money']=$moneyArr[0];
                    }
                }

                $vs['percentage'] = round($vs['canyurenshu'] / $vs['zongrenshu'],2) * 100 ;
               
            }
        }
        $price = 0;
        if(empty($arr)){
            $arr = [];
        }else{
            foreach($arr as $k=>$v){
                if($type==1){
                    $price +=$v['money']*$v['num'];
                }else{
                    $price += $v['mode'] ==1?$v['num']*$v['price']:$v['preferential_price']*$v['num'];
                    // $price += $type == 1?$v['money']*$v['num']:$v['num'];
                }
            }
        }
        $data = [
            'shopList' =>array_values($arr),
            'price'=>$price,
            'list'=>$list,//推荐商品
        ];
        $this->jsonData->successCode('','OK',$data);
    }

    // 推荐商品
    public function recommended()
    {
        $type = safe_replace($_POST['type']);//字段区分是查什么数据库 1普通 2积分
        //推荐商品
        if($type == 1){
            $list = $this->db->Getlist("SELECT `id`,`thumb`,`title`,`money`,`s_score` as integral FROM `@#_shoplist_jifen`where `online` ='1' AND `quality` = '0' AND (`language` = '$this->nowUserLanguage' or `language` = '')  and delete_time is null  ORDER BY `order` DESC LIMIT 4");
        }else{
            $list = $this->db->Getlist("SELECT `id`,`thumb`,`title`,`money`,`qishu`,`shenyurenshu`,`zongrenshu`,`canyurenshu` FROM `@#_shoplist` where`renqi`=1 and `q_uid` is null AND ispk !=1 AND (`language` = '$this->nowUserLanguage' or `language` = '') order by `order` desc LIMIT 4");
        }
        if($list){
            foreach($list as $ks=>&$vs){
                // $qishu = empty($vs['qishu']) ? "":"($vs[qishu]".$this->lgs['phase'].")";
                // $vs['title'] = $qishu.$vs['title'];
                $vs['img'] = G_UPLOAD_PATH.'/'.$vs['thumb'];
                //去掉小数点后的.00,不为00的不去除
                if(strstr($list[$ks]['money'],'.')){
                    $moneyArr=explode('.',$list[$ks]['money']);
                    if($moneyArr[1] == '00'){
                        $list[$ks]['money']=$moneyArr[0];
                    }
                }

                $vs['percentage'] = round($vs['canyurenshu'] / $vs['zongrenshu'],2) * 100 ;

            }
        }
        $price = 0;
        if(empty($arr)){
            $arr = [];
        }else{
            foreach($arr as $k=>$v){
                $price += $type == 1?$v['money']*$v['num']:$v['num'];
            }
        }

        $this->jsonData->successCode('','OK',$list);
    }

    /**
     * 添加购物车
    */
    public function addcart() 
    {
        $id = safe_replace($_POST['id']);//商品ID
        $type = safe_replace($_POST['type']);//1为普通2为积分
        $num = safe_replace($_POST['number']);//商品数量
        $mode = safe_replace($_POST['mode'])?:1;//1云购，2优惠购
        $user_id = $this->uId;
        if(empty($id) || empty($type) || empty($num)){
            $this->jsonData->error('402',$this->lgs['parameter_null']);
        }

        //1为查询普通商品，2为查询积分商品
        if($type == 2){
            $sql = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE  `id` = '$id'");//积分商品
        }else{
            $sql = $this->db->GetOne("SELECT * FROM `@#_shoplist_jifen` WHERE  `id` = '$id'  and delete_time is null");//普通商品
            $mode = 3;
        }
        if($sql){
            $goods_id = $sql['id'];
            $goods_price = $type == 2?$sql['yunjiage']:($sql["disc_price"]>0?$sql["disc_price"]:$sql['money']);
            if($mode==2 && $type==2){
                $goods_price = $sql['preferential_price'];
                if($goods_price <=0){
                    $this->jsonData->error('400',$this->lgs['select_null']);
                }
            }
            $time = time();
            $sid = $sql['sid'];
            $cart = $this->db->GetOne("SELECT * FROM `@#_cart` WHERE `uid` = '$user_id' AND `shopsid` = '$id' AND `status` = '$type' AND `mode` = '$mode'");//查询是否存在购物车商品
            if($cart){
                if($cart['num'] >= $sql['shenyurenshu'] && $mode==1){
                    $this->jsonData->successCode('',$this->lgs['goods_is_max']);
                }
                //存在 修改
                $goodsCart = $this->db->Query("UPDATE `@#_cart` SET `num` = '$num' + '$cart[num]',`cmoney` = `cmoney`+'$num'*'$goods_price',`addtime` = $time WHERE `uid` = '$user_id' AND `shopsid` = '$id' AND `status` = '$type' AND `mode` = '$mode'");
            }else{
                if($type == 2){
                    $db = $this->db->GetOne("SELECT * FROM `@#_cart` WHERE `uid` = '$user_id' AND `sid` = '$sql[sid]' AND `status` = '$type' AND `mode` = '$mode'");
                    // echo $this->db->getSql();
                    // var_export($db);die;
                    if($db){
                        //因为我存的时候是存的id 所以这里要判断两次 第一次判断商品id存不存在 第二次判断sid存不存在
                        $goodsCart = $this->db->Query("UPDATE `@#_cart` SET `num` = '$num' + '$cart[num]',`cmoney` = `cmoney`+'$num'*'$goods_price',`addtime` = '$time' WHERE `uid` = '$user_id' AND `sid` = '$db[sid]' AND `status` = '$type' AND `mode` = '$mode'");
                    }else{
                        //不存在 新增
                        $goodsCart = $this->db->Query("INSERT INTO `@#_cart`(`uid`,`shopsid`,`num`,`price`,`cmoney`,`addtime`,`status`,`sid`,`mode`)VALUE ('$user_id','$goods_id','$num','$goods_price','$num'*'$goods_price','$time','$type','$sid','$mode')");
  
                    }
                }else{
                    $goodsCart = $this->db->Query("INSERT INTO `@#_cart`(`uid`,`shopsid`,`num`,`price`,`cmoney`,`addtime`,`status`,`sid`,`mode`)VALUE ('$user_id','$goods_id','$num','$goods_price','$num'*'$goods_price','$time','$type','$sid','$mode')");
                }
            }
            if($goodsCart){
                $arr = [];
                $this->jsonData->successCode('',$this->lgs['add_ok'],$arr);
                // if($cart){
                //     $this->jsonData->successCode('',$this->lgs['add_ok'],$arr);
                // }else{
                //     $this->jsonData->successCode('',$this->lgs['add_error'],$arr);
                // }
            }else{
                $this->jsonData->error('500','');
            }
        }else{
            $this->jsonData->error('400',$this->lgs['select_null']);
        }

    }

    /**
     * 修改 购物车商品
     */
    public function editcart()
    {
        $id = $_POST['id'];
        $user_id = $this->uId;
        $num = $_POST['number'];
        $type = $_POST['type'];
        if(empty($id) && empty($num) && empty($type)){
            $this->jsonData->error('', $this->lgs['parameter_null']);
        }
        //修改
        $updata_cart = $this->db->Query("UPDATE `@#_cart` SET `num` = '$num',`cmoney` = '$num'*`price` WHERE `uid` = '$user_id' AND `shopsid` = '$id'  AND `status` = '$type'");
        if($updata_cart){
            $arr = [];
            $this->jsonData->successCode('',$this->lgs['update_ok'],$arr);
        }else{
            $this->jsonData->error('500','');
        }

    }

    /**
     * 删除购物车商品
     */
    public function delcart(){
        $id = $_POST['id'];
        $type = $_POST['type'];//1为普通商品2为积分商品
        $user_id = $this->uId;
        if(empty($id) && empty($type)){
            $this->jsonData->error('402',$this->lgs['parameter_null']); // 参数不能为空
            exit;
        }
        if(strstr($id,',')){
            $arr = explode(',',$id);
        }else{
            $arr = $id;
        }
        //删除
        if(is_array($arr)){
            foreach($arr as $k=>$v){
                $ids = $v;
                if($type == 1){
                    $del = $this->db->Query("DELETE FROM `@#_cart` WHERE `uid` = '$user_id' AND `id` = '$ids'");
                }else{
                    
                    $del = $this->db->Query("DELETE FROM `@#_cart` WHERE `uid` = '$user_id' AND `id` = '$ids' AND `status` = '$type'");
                }
            }
        }else{
            if($type == 1){
                $del = $this->db->Query("DELETE FROM `@#_cart` WHERE `uid` = '$user_id' AND `id` = '$arr' ");
            }else{
                $del = $this->db->Query("DELETE FROM `@#_cart` WHERE `uid` = '$user_id' AND `id` = '$arr' AND `status` = '$type'");
            }
        }
        if($del){
            $arr = [];
            $this->jsonData->successCode('',$this->lgs['delete_ok'],$arr);
        }else{
            $this->jsonData->error('500','');
        }
    }

    /*
     * 购物车商品数量
     * status 判断是普通商品还是积分商品
     * */
    public function goodsnum(){
        $status = $_POST['status'];//1为普通2为积分
        $id = $this->uId;
        if($status == 1){
            $sql = $this->db->Getlist("SELECT * FROM `@#_cart` WHERE `uid` = '$id' AND  `status` = '1'");
        }else{
            $sql = $this->db->Getlist("SELECT * FROM `@#_cart` WHERE `uid` = '$id' AND  `status` = '2'");
        }
        // var_dump($sql);exit;
        $type = $status == 1?'_jifen':'';
        $num = 0;
        foreach($sql as $k=>$v){
            $ids = $v['shopsid'];
            $dom = $this->db->GetOne("SELECT `id` FROM `@#_shoplist$type` WHERE `id` = '$ids'");
            if($dom){
                $num++;
            }
        }
        if($sql){
            $this->jsonData->successCode('','',$num);
        }else{
            $this->jsonData->successCode('','',0);
        }
    }

    /*
     * 确认订单
     * 普通商品
     * */
    public function addorder(){
        $ids = $_POST['id'];//逗号分割的购物车ID
        $aid = $_POST['aid'];//地址ID

        $arra = explode(',',$ids);
        if(is_array($arra)){
            foreach($arra as $k=>$v){
                $id = $v;
                if($v != ''){
                    $list  = $this->db->GetOne("SELECT `id`,`shopsid`,`price`,`num`,`cmoney` FROM `@#_cart` WHERE `id`='$id'");
                    if(!$list){
                        $this->jsonData->error('',$this->lgs['select_null']);// 查询购物车失败
                    }
                    $sql[] = $list;
                }
            }
        }
        $price = 0;
        foreach($sql as $k=>&$v){
            $title = $this->db->GetOne("SELECT `cateid`,`title`,`thumb` FROM `@#_shoplist_jifen`  WHERE `id` = '$v[shopsid]'  and delete_time is null ");
            if($title['cateid'] == 158){
                $type = true;
            }
            $v['title'] = $title['title'];
            $v['thumb'] = G_UPLOAD_PATH .'/'.$title['thumb'];
            $price += $v['cmoney'];
        };
        if($type == null){
            $type = false;
        }
        $uid =$this->uId;
        if($aid == 'x'){
            //自提
        }else if(empty($aid)){
            //查默认
            $dizhi = $this->db->GetOne("SELECT `id`,`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`default` as `df` FROM `@#_member_dizhi`  where  `uid` = $uid and `default` = 'Y'");
            $arr = [$dizhi];
        }else{
            //查指定地址
            $dizhi = $this->db->GetOne("SELECT `id`,`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`default` as `df` FROM `@#_member_dizhi`  where  `uid` = $uid and `id` = '$aid'");
            $arr = [$dizhi];
        }
        $data = [
            'dizhi' => $dizhi?$arr:[],
            'shoplist' =>$sql,
            'price' => $price.'',
            'type' => $type,
        ];
        $this->jsonData->success('','',$data);
    }

    //拼团详细
    public function groupDetail(){
        $member=$this->uId;
        $itemid=$_POST['id'];
        $sql = "select a.*,b.thumb,b.title,a.time as timego,gonumber from `@#_orders_group` a 
        left join `@#_shoplist` b on a.shopid=b.id 
        where a.uid='$member' and a.id='$itemid' 
        group by a.id order by a.time";
        $order = $this->db->GetOne($sql);
        if($order){
            $oid = $order['id'];
            if($order['pid']){
                $oid = $order['pid'];
            }
            $huode = $this->db->GetOne("select o.*,m.v_id from `@#_orders_group` o left join `@#_member` m on o.uid = m.uid where huode <> '0' and (id = $oid or pid = $oid)");
            // $sql = "select * from `@#_orders_group` where huode != 0 and (id = $itemid or pid = $itemid)";
            // $huode = $this->db->GetOne($sql);
            $data['username'] = $huode['username'];
            $member = $this->db->GetOne("select * from `@#_member` where uid = {$huode['uid']}");
            $data['v_id'] = $member['v_id'];
            $data['uid'] = $member['uid'];
        }else{
            $this->jsonData->error('','');
        }
        if($order['q_end_time']!=''){
            //商品已揭晓
            $data['codeState']= $this->lgs['revealed'];
            $data['class']='z-ImgbgC02';
        }elseif($order['group_status']==2){
            //商品购买次数已满
            $data['codeState']= $this->lgs['full_capacity'];
            $data['class']='z-ImgbgC01';
        }elseif($order['group_status']==1){
            //进行中
            $data['codeState']= $this->lgs['afoot'];
            $data['class']='z-ImgbgC01';
        }else{
            //拼团失败
            $data['codeState']= $this->lgs['fail'];
            $data['class']='z-ImgbgC03';
        }
        $data['content'][0] = [
            "code"=>explode(",",$order['goucode']),
            "huode"=>$order['huode'],
            "num"=>$order['gonumber'],
            'time'=>date("Y-m-d H:i:s",$order['time']),
        ];
        $data['huode'] = $order['huode'];
        $data['num'] = $order['gonumber'];
        $data['canyurenshu'] = $order['join_num'];
        $data['end_time'] = $order['q_end_time'];
        $data['img'] = G_UPLOAD_PATH .'/'.$order['thumb'];
        $data['qishu'] = null;
        $data['shengyurenshu'] = $order['group_num'] - $order['join_num'];
        $data['startTimeNumber'] = '';
        $data['title'] = $order['title'];
        $data['type'] =  $data['codeState'];
        $data['end_time'] = $order['q_end_time'];
        $this->jsonData->success('','',$data);
    }

    //积分商品参与记录商品详情
    public function goodscode(){

        $member=$this->uId;
        $itemid=$_POST['id'];
        $itemlist=$this->db->GetList("select *,a.time as timego,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$member' and b.id='$itemid' group by a.id order by a.time" );
        if(empty($itemlist)){
            $itemlist=$this->db->GetList("select *,a.time as timego,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$member' and b.id='$itemid' group by a.id order by a.time" );
        }
        if(!empty($itemlist)){
            if($itemlist[0]['q_end_time']!=''){
                //商品已揭晓
                $itemlist[0]['codeState']= $this->lgs['revealed'];
                $itemlist[0]['class']='z-ImgbgC02';
            }elseif($itemlist[0]['shenyurenshu']==0){
                //商品购买次数已满
                $itemlist[0]['codeState']= $this->lgs['full_capacity'];
                $itemlist[0]['class']='z-ImgbgC01';
            }else{
                //进行中
                $itemlist[0]['codeState']= $this->lgs['afoot'];
                $itemlist[0]['class']='z-ImgbgC01';

            }
            // $bl=($itemlist[0]['canyurenshu']/$itemlist[0]['zongrenshu'])*100;

        }
        $num = 0;
        foreach($itemlist as $k=>$val){
            $list[] = [
                // 'time' => microt($val['timego']),
                'time' => date("d/m/Y H:i:s",$val['timego']),
                'num' => $val['gonumber'],
                'huode' => $val['huode'],
                // 'code' => $val['goucode'],
                'code' => explode(",",$val['goucode'])
            ];
            $num += $val['gonumber'];
            // 查询出购买次数
            // $recodeinfo=$this->db->GetList("select `gonumber` from `@#_member_go_record` where `uid` ='$val[q_uid]'  and `shopid`='$val[id]' ");
            // $goucount=0;
            // foreach ($recodeinfo as $r) {
            //     $goucount+=(int)$r['gonumber'];
            // }
        }

        $startTimeNumber = round($itemlist[0]['q_end_time'] - time());
        
        $userData = unserialize($itemlist[0]['q_user']);
        $userData['username'] = str_replace(' ','',$userData['username']);
        $m = $this->db->GetOne("select * from `@#_member` where uid = {$itemlist[0]['uid']}");
        $data = [
            'startTimeNumber' => $startTimeNumber,
            'img' =>G_UPLOAD_PATH .'/'.$itemlist[0]['thumb'],
            'qishu' =>$itemlist[0]['qishu'],
            'title' =>$itemlist[0]['title'],
            'type'=>$itemlist[0]['codeState'],
            'username'=>$itemlist[0]['q_end_time'] != 0?!empty($userData['username'])?$userData['username']:substr_replace($userData['mobile'], '****', 3, 4):$this->lgs['afoot'],
            'end_time'=>$itemlist[0]['q_end_time'] != 0?date("Y-m-d h:i:s",$itemlist[0]['q_end_time']):$this->lgs['afoot'],
            'canyurenshu'=>$itemlist[0]['canyurenshu'],
            'zongrenshu' =>$itemlist[0]['zongrenshu'],
            'shengyurenshu' =>$itemlist[0]['zongrenshu']-$itemlist[0]['canyurenshu'],
            'num' => $num,
            'content'=>$list,
            'huode'=>$itemlist[0]['huode'],
            'uid'=>$itemlist[0]['uid'],
            'v_id'=>$m['v_id']
        ];
        $this->jsonData->success('','',$data);
    }
    
    //一键添加数量
    public function addAllToCart(){
        $num = $_POST['num'];
        $uid = $this->uId;
        $list = $this->db->GetList("SELECT `id`,`shopsid`,`sid` FROM `@#_cart` where `uid` = '$uid' and `status` = 2");
        $this->db->Autocommit_start();
        foreach ($list as $k=>$v){
            $shop = $this->db->GetOne("SELECT `id`,`zongrenshu`,`canyurenshu`,`shenyurenshu` FROM `@#_shoplist` where `id` = $v[shopsid]");
            if(!$shop){
                $shop = $this->db->GetOne("SELECT `id`,`zongrenshu`,`canyurenshu`,`shenyurenshu` FROM `@#_shoplist` where `sid` = $v[sid] order by id desc");
            }
            if($shop['shenyurenshu'] > $num){
                // $sql[$v['shopsid']] = "UPDATE `@#_cart` SET `num` = '$num',`cmoney` = `price`*'$num' where `id` = '$v[id]'";
                $updateCart = $this->db->Query("UPDATE `@#_cart` SET `num` = '$num',`cmoney` = `price`*'$num' where `id` = '$v[id]'");
            }else{
                // $sql[$v['shopsid']] = "UPDATE `@#_cart` SET `num` = '$shop[shenyurenshu]',`cmoney` = `price`*'$shop[shenyurenshu] ' where `id` = '$v[id]'";
                $updateCart = $this->db->Query("UPDATE `@#_cart` SET `num` = '$shop[shenyurenshu]',`cmoney` = `price`*'$shop[shenyurenshu] ' where `id` = '$v[id]'");
            }
        }
        // var_dump($sql);exit;
        
        if($updateCart){
        $this->db->Autocommit_commit();
            $data = [];
            $this->jsonData->success('','',$data);
        }else{
        $this->db->Autocommit_rollback();
            $this->jsonData->error('500','');
        }
    }
}