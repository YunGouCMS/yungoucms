<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');

class ptgoodslist extends base
{
    public function __construct()
    {
        parent::__construct();
    }

    /*获取分类列表*/
    public function listgoodscalss(){
        $cate_id = safe_replace($_POST['cateid'])?$_POST['cateid']:'0';//分类id
        $p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
        $type = htmlspecialchars($_POST['type'])?$_POST['type']:10;//类型

        $num = 10;
        $star = ($p) * $num;
        $endtab = '_jifen';
        $countsql = '';
        if($cate_id !='0'){
            $w= "cateid='$cate_id'";
        }else{
            $w =1;
        }
        switch ($type) {
            case '1':
                $where = "select * from `@#_shoplist$endtab` WHERE $w  order by `money`  asc  LIMIT $star ,$num";
                $countsql = "select  count(*) from `@#_shoplist$endtab` WHERE $w  order by `money`  asc";
                break;
            case '10':
                $where = "select * from `@#_shoplist$endtab` WHERE $w  order by `money`  asc  LIMIT $star ,$num";
                $countsql = "select  count(*) from `@#_shoplist$endtab` WHERE $w  order by `money`  asc";
                break;
            case '20':
                $where = "select * from `@#_shoplist$endtab` WHERE $w  order by `money`  asc  LIMIT $star ,$num";
                $countsql = "select  count(*) from `@#_shoplist$endtab` WHERE $w   order by `money`  asc";
                break;
            case '30':
                $where = "select * from `@#_shoplist$endtab` WHERE  $w order by `money`  asc  LIMIT $star ,$num";
                $countsql = "select  count(*) from `@#_shoplist$endtab` WHERE $w  order by `money`  asc";
                break;
            case '40':
                $where = "select * from `@#_shoplist$endtab` WHERE $w  order by `money`  asc , `updatime` LIMIT $star ,$num";
                $countsql = "select  count(*) from `@#_shoplist$endtab` WHERE $w  order by `money`  asc , `updatime`";
                break;
            case '50':
                $where = "select * from `@#_shoplist$endtab` WHERE $w   order by `money`  DESC  LIMIT $star ,$num";
                $countsql = "select  count(*) from `@#_shoplist$endtab` WHERE $w  order by `money`  DESC";
                break;
            case '60':
                $where = "select * from `@#_shoplist$endtab` WHERE $w  order by `money`  asc  LIMIT $star ,$num";
                $countsql = "select count(*) from `@#_shoplist$endtab` WHERE $w   order by `money`  asc";
                break;
            default:
                break;
        }

        $shoplist = $this->db->GetList($where);
        // $count    = $this->db->GetOne($countsql);
        // $count = $count['count(*)'];
        // $pagex=ceil(count($count)/$num);
        // $redata = [];
        // if($p<=$pagex){
        //     $redata['page']=$p+1;
        //     if($pagex>0){
        //         $redata['sum']=$pagex;
        //     }else if($pagex==0){
        //         $redata['sum']=$pagex;
        //     }
        // }else{
        //     $redata['sum']=0;
        // }
        // $redata['amount'] = $count;
        // $redata['goodslist'] = $shoplist;

        $arr = [];
        foreach($shoplist as $k=>$v){
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['img'] =  G_UPLOAD_PATH.'/'.$v['thumb'];
            $arr[$k]['title'] = $v['title'];
            $arr[$k]['integral'] = $v['s_score'];
            // $arr[$k]['money'] = $v['money'];
            if(strstr($v['money'],'.')){
                $moneyArr=explode('.',$v['money']);
                 if($moneyArr[1] == '00'){
                    $arr[$k]['money']=$moneyArr[0];
                }else{
                    $arr[$k]['money']=$v['money'];
                }
            }else{
                $arr[$k]['money']=$v['money'];
            }
        }
        $this->jsonData->successCode('','',$arr);
    }

    /*获取商品目录*/
    public function catalog(){
        $fenname = $this->db->GetList("select `cateid`,`name` from `@#_category` where `model`='1'and`is_jfclass` = 0 and `parentid` = '0' order by `order` desc");
        $this->jsonData->successCode('','OK',$fenname);
    }

    /**
     * 积分即将揭晓
     * @param int page 页码
     */
    public function getUndrawnList(){
        $FIdx=!empty($_POST['page']) ?$_POST['page']*10: 0;
        $EIdx=10;
        $shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist` WHERE `q_uid` is null AND ssc!=1 AND `canyurenshu`>0 AND `canyurenshu` < `zongrenshu` AND  (`language` = '$this->nowUserLanguage' or `language` = '')");
        //即将开奖
        $shoplist['listItems']=$this->db->GetList("SELECT `id`,`money`,`thumb`,`qishu`,`title`,`canyurenshu`,`shenyurenshu`,`zongrenshu`,(`canyurenshu`/`zongrenshu`)as schedule 
                                                    FROM `@#_shoplist` 
                                                    WHERE `q_uid` is null 
                                                    AND ssc!=1 
                                                    AND `canyurenshu` > 0 
                                                    AND `canyurenshu` < `zongrenshu`  
                                                    AND (`language` = '$this->nowUserLanguage' or `language` = '') 
                                                    ORDER BY canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc LIMIT $FIdx,$EIdx");
        if(empty($shoplist['listItems'])){
            $shoplist['listItems'] = [];
            $this->jsonData->successCode('','暂时无参数',$shoplist);
        }else{
            foreach($shoplist['listItems'] as $key=>$val){
                // $shoplist['listItems'][$key]['q_end_time']=date('Y-m-d H:i',$val['q_end_time']);
                $shoplist['listItems'][$key]['thumb']= G_UPLOAD_PATH.'/'. $val['thumb'];
                $shoplist['listItems'][$key]['integral']= $val['money'];
                $shoplist['listItems'][$key]['schedule']= round($val['schedule'],2);
                // 计算误差
                $parts = explode('.', (string)($val['canyurenshu']/$val['zongrenshu'] *100));
                $integerPart = $parts[0];
                if(isset($parts[1])){
                    $decimalPart = substr($parts[1], 0, 2); // 截取小数部分的前两位
                }else{
                    $decimalPart = "00";
                }
                $shoplist['listItems'][$key]['width'] = $integerPart . '.' . $decimalPart; // 组合回数字
            }
            $shoplist['code']=0;
            $shoplist['count']=$shopsum['total'];
                        
            
        }
       $this->jsonData->successCode('',$this->lgs['get_ok'], $shoplist);

    }
    /**
     * 积分最新揭晓
     * @param int page 页码
     */
    public function getLotteryList(){
        $FIdx=!empty($_POST['page']) ?$_POST['page']*10: 0;
        $EIdx=10;
        // $isCount=$this->segment(6);

        $shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc!=1 AND (`language` = '$this->nowUserLanguage' or `language` = '')");
        //最新获得
        $shoplist['listItems']=$this->db->GetList("SELECT m.v_id,s.`id`,s.`q_uid`,s.`q_end_time`,s.`thumb`,s.`q_user_code`,s.`qishu`,s.`title` FROM `@#_shoplist_copy` s
            LEFT JOIN `@#_member` m on m.uid = s.q_uid
            WHERE s.`q_uid` is not null AND s.`q_showtime` = 'N' and s.ssc!=1 AND (s.`language` = '$this->nowUserLanguage' or s.`language` = '') 
            ORDER BY s.`q_end_time` DESC LIMIT $FIdx,$EIdx");
        if(empty($shoplist['listItems'])){
            $shoplist['listItems'] = [];
            $this->jsonData->successCode('','暂时无参数',$shoplist);
        }else{
            $userInfo = $this->getUserList();
            foreach($shoplist['listItems'] as $key=>$val){
                //查询出购买次数
                $recodeinfo=$this->db->GetList("select `gonumber` from `@#_member_go_record` where `uid` ='$val[q_uid]'  and `shopid`='$val[id]' ");
                $goucount=0;
                foreach ($recodeinfo as $r) {
                    $goucount+=(int)$r['gonumber'];
                }
                // $shoplist['listItems'][$key]['q_user']=get_user_name($val['q_uid']);
                // echo 1;exit;
                // $shoplist['listItems'][$key]['userphoto']=get_user_key($val['q_uid'],'img');
                // $shoplist['listItems'][$key]['userphotow']=get_user_key($val['q_uid'],'headimg');
                foreach($userInfo as $v1){
                    if ($val['q_uid'] == $v1['uid']){
                        // $shoplist['listItems'][$key]['userphoto'] = G_WEB_PATH.'/'.$v1['img'];
                       $shoplist['listItems'][$key]['userphoto'] = G_UPLOAD_PATH.'/'.$v1['img'];
                        if(empty($shoplist['listItems'][$key]['userphoto'])){
                            // $shoplist['listItems'][$key]['userphoto'] =G_UPLOAD_PATH.'/photo/member.jpg?v=1';
                            $shoplist['listItems'][$key]['userphoto'] =G_UPLOAD_PATH.'/photo/member.jpg?v=1';
                        }
                        $shoplist['listItems'][$key]['username'] = $v1['username'];
                        $shoplist['listItems'][$key]['mobile'] = $v1['mobile'];
                    }else{
                        $shoplist['listItems'][$key]['userphoto'] = !empty($shoplist['listItems'][$key]['userphoto'])?$shoplist['listItems'][$key]['userphoto']:G_UPLOAD_PATH.'/photo/member.jpg?v=1';
                        $shoplist['listItems'][$key]['username'] = !empty($shoplist['listItems'][$key]['username'])?$shoplist['listItems'][$key]['username']:'';
                        $shoplist['listItems'][$key]['mobile'] = !empty($shoplist['listItems'][$key]['mobile'])?$shoplist['mobile'][$key]['username']:'';
                    }
                }

                $shoplist['listItems'][$key]['q_end_time']=date('Y-m-d H:i',$val['q_end_time']);

                $shoplist['listItems'][$key]['gonumber']=$goucount;
                $shoplist['listItems'][$key]['thumb']= G_UPLOAD_PATH.'/'. $val['thumb'];
            }

            $shoplist['code']=0;
            $shoplist['count']=$shopsum['total'];
        }

       $this->jsonData->successCode('',$this->lgs['get_ok'], $shoplist);

    }

    /**
     * 获取用户信息
     * @param int $isShowUserInfo 1.隐藏 2显示 是否隐藏用户信息前端展示
     */
    private function getUserList($isShowUserInfo = 1){
        $sql = "SELECT `username`,`uid`,`email`,`mobile`,`img`,`money`,`time`,`headimg` FROM `go_member`";
        $res = $this->db->GetList($sql);
        foreach ($res as &$v){
            $v['mobile'] = substr($v['mobile'],0,3).'****'.substr($v['mobile'],7,4);
            $v['username'] = !empty($v['username']) ? $v['username'] : $v['mobile'];
            // $v['img'] = G_UPLOAD_PATH.'/'. $v['img'];
        }
        return $res;
    }

    //积分商品订单详情
    public function goodsDetails(){
        $recordid = $_POST['aid'];//购买记录表ID
        $logisticscode = $_POST['company_code'];//快递单号

        $record = $this->db->GetOne("select * from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.id='$recordid' order by a.id desc");
        if(empty($record)) $this->jsonData->error('',$this->lgs['select_null']);

        $addrinfo = $this->db->GetOne("select * from `@#_member_bind_phone` where phone='{$record['address']}'");
        $record['time'] = date("Y-m-d H:i:s", $record['q_end_time']);
        // $address = $this->db->Getone("SELECT b.* FROM  `@#_member_orderaddress_brand` AS a left join `@#_member_dizhi` AS b on b.id=a.address_id where a.order_code=$recordid");

        $address = $this->db->Getone("SELECT * FROM  `@#_member_dizhi` WHERE `id` = (SELECT `address_id` FROM `@#_member_orderaddress_brand` WHERE `order_code` =$recordid)");

        // echo "SELECT * FROM  `@#_member_dizhi` WHERE `id` = (SELECT `address_id` FROM `@#_member_orderaddress_brand` WHERE `order_code` =$recordid)";
        $ops = array('http' => array(
            'method' => 'GET',
            'header' => 'User-Agent:Mozilla/5.0\n',
        ));
        $context = stream_context_create($ops);
        $company = file_get_contents('http://www.kuaidi100.com/autonumber/autoComNum?text=' . $logisticscode);
        $company = json_decode($company, true)['auto'][0]['comCode'];
        $logistics = file_get_contents('http://www.kuaidi100.com/query?type=' . $company . '&postid=' . $logisticscode, false, $context);
        $logistics = json_decode($logistics, true);
        if ($logistics['message'] == 'ok') {
            foreach ($logistics['data'] as $k => $i) {
                $i["t1"] = explode(" ", $i['time'])[0];
                $i["t2"] = explode(" ", $i['time'])[1];
                $logistics['data'][$k] = $i;
            }
        }
        $data[0]['title'] = $this->lgs['orderlogistics_context'];
        $data[0]['time'] = $record['time'];
        $data[0]['status'] = $this->lgs['waiting_delivery'];
        
        $arr['img'] = G_UPLOAD_PATH.'/'.$record['thumb'];
        $arr['qishu'] = $record['qishu'];
        $arr['title'] = $record['title'];
        $arr['money'] = $record['money'];
        $arr['code'] = $record['code'];
        $arr['company_code'] = $record['company_code'];
        $arr['company'] = $record['company'];
        $arr['zongrenshu'] = $record['zongrenshu'];
        $arr['huode'] = $record['huode'];
        $arr['time'] = $record['time'];
        $arr['address'] = $record['address'];

        $goucode = explode(",",$record['goucode']);

        $arr['gonumber'] = count($goucode);

        $arr = [$arr];
        $address = $address ?[$address] : [];
        if($logistics['message'] == 'ok'){
            $list = $logistics['data'];
        }
        if($list){
            $data = [];
            foreach($list as $k=>$v){
                $data[$k]['time'] = $v['time'];
                $data[$k]['title'] = $v['context'];
                $data[$k]['status'] = $v['status'];
            }
        }
        $datas = [
            'shop' =>$arr,
            'address' =>$address,
            'contenlist' => $data
        ];
        $this->jsonData->success('','',$datas);
    }
}