<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('send');
// System::load_sys_fun('returnData');

class myhome extends base{

    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();
    }

    /**
     * 我的首页
     * @return void
     */
    public function index(){
        $member['uid']=$_POST['uid'];
        $qqkfewm = System::load_sys_config("system",'qqkfewm');
        if (empty($member['uid'])){
            
            $member['uid'] = '';
            $member['username'] = '';
            $member['img'] = G_UPLOAD_PATH.'/banner/20220602/web_logo.png';
            $member['money'] = '0';
            $member['time'] = '';
            $member['loadpay'] = ['num'=>'0'];
            $member['loadsend'] = ['num'=>'0'];
            $member['loadre'] = ['num'=>'0'];
            $member['mobile'] = '';
            $member['yongjin'] = '';
//            $member['customerService'] = $this->db->GetOne("SELECT `link` FROM `@#_wap` where `title` ='联系客服' AND `status` = '1'")['link'];
            $member['customerService'] = G_UPLOAD_PATH.'/' . $this->db->GetOne("SELECT `img` FROM `@#_wap` where `id` ='6' AND `status` = 1")['img'];
            $this->jsonData->success('','请求成功',$member);
            
        }else{
            $user = $this->setUid($member['uid']);
            $member = $this->userInfo();
            $member['uid'] = $_POST['uid'];
        }
        $substr=substr($member["mobile"],3,4);
        $member["mobile"]=str_replace($substr,"****",$member["mobile"]);
        $member['username'] = !empty($member['username']) ? $member['username'] : $member["mobile"];
//        var_dump($member['img']);exit;
        if(!empty($member['headimg'])){
            $member['img'] = $member['headimg'];
        }else{
//            $member['img'] = G_UPLOAD_PATH.'/'.$member['img'];
            $member['img'] = $member['img'];
        }
        
        if(empty($member['img'])){
            $member['img'] = G_UPLOAD_PATH.'/banner/20220602/web_logo.png';
        }
        //待付款
        $member['loadpay'] = $this->db->GetOne("SELECT count(*) as num FROM `@#_member_goods_order` WHERE `uid`=".$member['uid']." AND `status`='1'");
        //待发货
        $member['loadsend'] = $this->db->GetOne("SELECT count(*) as num FROM `@#_member_pay_order` WHERE `uid`=".$member['uid']." AND `status`='2'");
        //待收货
        $member['loadre'] = $this->db->GetOne("SELECT count(*) as num FROM `@#_member_pay_order` WHERE `uid`=".$member['uid']." AND `status`='3'");
        if(empty($member['loadpay'])){
            $member['loadpay'] = ['num'=>'0'];
        }
        if(empty($member['loadsend'])){
            $member['loadsend'] = ['num'=>'0'];
        }
        if(empty($member['loadre'])){
            $member['loadre'] = ['num'=>'0'];
        }
        //            $member['customerService'] = $this->db->GetOne("SELECT `link` FROM `@#_wap` where `title` ='联系客服' AND `status` = '1'")['link'];
        $member['customerService'] = G_UPLOAD_PATH .'/' . $this->db->GetOne("SELECT `img` FROM `@#_wap` where `id` ='6' AND `status` = 1")['img'];
        if (!empty($member)){
            $yongjin = (int)$member['yongjin'];
            $member['yongjin']= (string)$yongjin;
            $m = (int)$member['money'];
            $member['money']= (string)$m;
            $this->jsonData->success('','请求成功',$member);
        }else{
            $this->jsonData->success('','暂无数据');
        }
    }

    //我的订单
    public function allorders() {
        $member = $this->userInfo();
        $uid = $member['uid'];
        //1.判断是否有过期订单,有则修改
        $otime = time() - 3600;
        $sql = "SELECT `oid`,`cretime` FROM `@#_member_goods_order` WHERE `cretime` < $otime AND `uid`='$uid'";
        $re = $this->db->Getlist($sql);
        if ($re) {
            $oids = '';
            foreach ($re as $key => $value) {
                if ($oids == '') {
                    $oids = $value['oid'];
                } else {
                    $oids .= ',' . $value['oid'];
                }
            }
            $uptime = time();
            $sql = "UPDATE `@#_member_goods_order` SET `status`='0',`uptime`='$uptime' WhERE `oid` IN($oids) AND `uid`='$uid'";
            // echo $sql;
            $res = $this->db->Query($sql);
            if (!$res) {
                $this->jsonData->errCode('','数据异常');
            }
        }
        if ($re){
            $this->jsonData->successCode('','获取成功',$re);
        }
        $this->jsonData->successCode('','暂无数据');
    }

    /**
     *  我的订单
     * @param int type  //0全部订单 1待付款 2待发货 3待收货
     * @param int page  //页数
     * @param string where  //搜索
     */
    public function getOrder(){
        $member = $this->userInfo();

        $type = $_POST['type'] ?: 0;
        $page = $_POST['page']; //页数
        $where = $_POST['where']; //搜索

        $isRequestData = '';
        !empty($page) && $isRequestData = $this->requestFiltering($page);
        !empty($where) && $isRequestData = $this->requestFiltering($where,2);
        if ($isRequestData['code'] != 1 && !empty($isRequestData)) $this->jsonData->errCode(codeReturn::DATA_TYPE_ERR,$isRequestData['msg'],'');

        $where =  urldecode($where);
        $pagenum = 5;
        $uid = $member['uid'];
        $orderlist = [];
        if ($page == 0) {
            $start = 0;
            $end = $pagenum;
        } else {
            $start = $pagenum * $page;
            $end = $pagenum * ($page + 1);
        }

        switch ($type) {
            case 0: //全部
                $sql ="SELECT
                        A.*
                    FROM
                    (SELECT * FROM `@#_member_goods_order` UNION ALL SELECT * FROM 
                        `@#_member_pay_order`)AS A
                         JOIN `@#_member_goods_item` AS B ON A.oid = B.oid
                        LEFT JOIN `@#_shoplist_jifen` AS C ON B.sid = C.sid 
                    WHERE
                        A.uid = '$uid' AND C.title like '%$where%' GROUP BY A.code ORDER BY A.uptime DESC LIMIT $start,$end";
                break;
            case '1': //待付款
                $sql ="SELECT
                            	A.*
                            FROM
                            (SELECT * FROM `@#_member_goods_order`)AS A
                            	 JOIN `@#_member_goods_item` AS B ON A.oid = B.oid
                            	LEFT JOIN `@#_shoplist_jifen` AS C ON B.sid = C.sid 
                            WHERE
                            	A.uid = '$uid' AND A.status='1' AND C.title like '%$where%' GROUP BY A.code ORDER BY A.uptime DESC LIMIT $start,$end";
                // 			$sql = "SELECT * FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `status`='1' ORDER BY uptime DESC LIMIT $start,$end";
                break;
            case '2': //待发货
                $sql ="SELECT
                            	A.*
                            FROM
                            (SELECT * FROM `@#_member_pay_order`)AS A
                            	 JOIN `@#_member_goods_item` AS B ON A.oid = B.oid
                            	LEFT JOIN `@#_shoplist_jifen` AS C ON B.sid = C.sid 
                            WHERE
                             	A.uid = '$uid' AND A.status='2' AND C.title like '%$where%' GROUP BY A.code ORDER BY A.uptime DESC LIMIT $start,$end";
                //                 echo $sql;
                //                 exit;
                // 			$sql = "SELECT * FROM `@#_member_pay_record` WHERE `uid` = '$uid' AND `status`='2' ORDER BY uptime DESC LIMIT $start,$end";
                break;
            case '3': //待收货
                $sql ="SELECT
                            	A.*
                            FROM
                            (SELECT * FROM `@#_member_pay_order`)AS A
                            	 JOIN `@#_member_goods_item` AS B ON A.oid = B.oid
                            	LEFT JOIN `@#_shoplist_jifen` AS C ON B.sid = C.sid 
                            WHERE
                             	A.uid = '$uid' AND  A.status='3' AND C.title like '%$where%' GROUP BY A.code ORDER BY A.uptime DESC LIMIT $start,$end";
                // 			$sql = "SELECT * FROM `@#_member_pay_record` WHERE `uid` = '$uid' AND `status`='3' ORDER BY uptime DESC LIMIT $start,$end";
                break;
        }
        $orderlist = $this->db->Getlist($sql);

        if (empty($orderlist)) $this->jsonData->successCode('','暂无参数',[]);

        //添加商品信息
        $newArray = [];
        foreach ($orderlist as $k => $v) {
            if($type == 1 || $type == 2 || $v['status'] == 1){
                $newArray[$k]['url'] = G_WEB_PATH.'/mobile/new_order/paymentorder/'.$v['id'].'/3/61520'.$uid;
            }else{
                $newArray[$k]['url'] = '';
            }
            $newArray[$k]["cretime"]=date("Y-m-d H:i:s",$v["cretime"]);
            $newArray[$k]["oid"] = $v["oid"];
            $newArray[$k]["typenum"] = (int)$v["typenum"];
            $newArray[$k]["status"] = (int)$v["status"];
            $newArray[$k]["money"] = $v["money"];
            $oid = $v['oid'];
            $re = $this->db->Getlist("SELECT `sid`,`snum` FROM `@#_member_goods_item` WHERE `oid` = '$oid'");
                foreach ($re as $key => $value) {
                    $sid = $value['sid'];
                    /*搜索时*/
                    $item = $this->db->GetOne("SELECT `id`,`thumb`,`money`,`title`,`s_score` FROM `@#_shoplist_jifen` WHERE `sid`='$sid'");
                    if($item){
                        
                    $item['thumb'] = G_UPLOAD_PATH .'/' . $item['thumb'];
                    $re[$key]['s_item'] = $item;
                    }else{
                        $re[$key]['s_item']['id'] = "0";
                        $re[$key]['s_item']['thumb'] = G_UPLOAD_PATH ."/photo/goods.jpg";
                        $re[$key]['s_item']['money'] = "0";
                        $re[$key]['s_item']['title'] = "商品以被下架";
                        $re[$key]['s_item']['s_score'] = "0";
                    }
                }
            $newArray[$k]['o_item'] = $re;
        }
// 		p($orderlist);exit;

        if (!empty($newArray)) $this->jsonData->successCode('','获取成功',$newArray);
        $this->jsonData->successCode('','暂无参数',[]);
    }

    //删除订单
    public function delOrder() {
        $orderid = $_POST['orderid'];
        if (empty($orderid)) $this->jsonData->errCode('','订单id不能为空');

        !empty($orderid) && $isRequestData = $this->requestFiltering($orderid);

        if ($isRequestData['code'] != 1 && !empty($isRequestData)) $this->jsonData->errCode(codeReturn::DATA_TYPE_ERR,$isRequestData['msg'],'');
        //删除
        //开启事务
        $this->db->Query("BEGIN");
        $sql1 = "DELETE FROM `@#_member_goods_order` WHERE `oid`='$orderid'";
        $re1 = $this->db->Query($sql1);
        $sql2 = "DELETE FROM `@#_member_goods_item` WHERE `oid`='$orderid'";
        $re2 = $this->db->Query($sql2);
        if ($re1 && $re2) {
            //操作无误，提交事务
            $this->db->Query("COMMIT");
        } else {
            //回滚事务
            $this->db->Query(ROLLBACK);
            $this->jsonData->errCode('','失败');
        }
        $this->jsonData->successCode('','删除成功');
    }

    //发送发货提醒
    public function send_remin() {
        $oid = $_POST['oid'];
        if (empty($oid)) $this->jsonData->errCode('','oid必传');
        !empty($oid) && $isRequestData = $this->requestFiltering($oid);
        if ($isRequestData['code'] != 1 && !empty($isRequestData)) $this->jsonData->errCode(codeReturn::DATA_TYPE_ERR,$isRequestData['msg'],'');

        $this->mobile = System::load_sys_config('mobile');
        $key = $this->mobile['cfg_mobile_on'];
        $info = array();

        $oinfo = $this->db->GetOne("SELECT `code`,`remin` FROM `@#_member_pay_order` WHERE `oid`='$oid'");
        if (!$oinfo) {
            $this->jsonData->errCode('','无效订单id');
        }
        if ($oinfo['remin'] == '1') {
            $this->jsonData->successCode('','商家已收到发货提醒,请耐心等候');
        }
        /*$minfo = $this->db->GetOne("SELECT `mobile` FROM `@#_logistics_mobile` WHERE `id`='1'");
        $mobile = $minfo['mobile'];
        $code = $oinfo['code'];
        if (preg_match("/^1[3456789]\d{9}$/", $mobile)) {
            if ($key == 1) {
                $ok = template_order_remin($mobile, $code);
            } else {
                $ok = send_login_code_4($mobile, $code);
            }*/
        $ok[0] = 1;
        if ($ok['0'] == '1') {
            $re = $this->db->Query("UPDATE `@#_member_pay_order` SET `remin`='1' WHERE `oid`='$oid'");
            $info['status'] = '200';
        } else {
            $this->jsonData->errCode('','发送失败');
        }
        /*} else {
            $info['status'] = '0';
            $info['message'] = '无效的手机号';
        }*/
        $this->jsonData->successCode('','提醒成功');
    }

    //我的地址
    public function acquiesce() {
        $member = $this->userInfo();
        $uid = $member['uid'];
        $dizhi = $this->db->GetList("SELECT `id`,`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`default` as `df`,`time`,`qq`,`shifoufahuo` FROM `@#_member_dizhi`  where  `uid` = $uid ORDER BY `default` DESC");
        if (!$dizhi) {
            $this->jsonData->successCode('','暂无参数');
        }
        $this->jsonData->successCode('','获取成功',$dizhi);
    }

    /**
     * 添加地址
     * @param string sheng 省
     * @param string shi 市
     * @param string xian 县
     * @param string jiedao 街道
     * @param string shouhuoren 收货人
     * @param int mobile 电话
     * @param string default 详情
     */
    public function doadd() {
        $data = $_POST;
        $member = $this->userInfo();
        $uid = $member['uid'];
        $sheng = $data['sheng'];
        $shi = $data['shi'];
        $xian = $data['xian'];
        $jiedao = $data['jiedao'];
        $shouhuoren = $data['shouhuoren'];
        $mobile = $data['mobile'];
        $default = $data['default'];
        $qq = $data['qq'];
        $t = time();

        // if (empty($sheng)) $this->jsonData->errCode('','省不能为空');
        // if (empty($shi)) $this->jsonData->errCode('','市不能为空');
        // if (empty($xian)) $this->jsonData->errCode('','县不能为空');
        if (empty($jiedao)) $this->jsonData->errCode('','街道不能为空');
        if (empty($shouhuoren)) $this->jsonData->errCode('','收货人不能为空');
        if (empty($mobile)) $this->jsonData->errCode('','电话不能为空');
        // if(!preg_match("/^1[3456789]\d{9}$/",$mobile)){
        //      $this->jsonData->errCode('','电话号码不合法');
        // }
        //地址数量限制
        $num = $this->db->GetOne("SELECT count(*) FROM `@#_member_dizhi` WHERE uid = '$uid'");

        if ($num['count(*)'] > 4) {
            $this->jsonData->errCode('','收货地址数量最多为5个');
        }
        //默认地址查询
        $his = $this->db->GetOne("SELECT id FROM `@#_member_dizhi` WHERE uid = '$uid' AND `default` = 'Y'");
        if ($default == 'Y' && $his) {
            $id = $his['id'];
            $up = $this->db->Query("UPDATE `@#_member_dizhi` SET `default` = 'N' WHERE id = '$id'");
        }
        if ($default == 'N' && $his == '') {
            $default = 'Y';
        }
        //添加
        $sql = "INSERT INTO `@#_member_dizhi`(uid,sheng,shi,xian,jiedao,shouhuoren,mobile,`default`,time,`qq`) VALUES ('$uid','$sheng','$shi','$xian','$jiedao','$shouhuoren','$mobile','$default','$t','$qq')";
        $re = $this->db->Query($sql);

        if ($re) {
            $info['status'] = '200';
        } else {
            $this->jsonData->errCode('','添加失败');

        }
        $this->jsonData->successCode('','添加成功');
    }

    /**
     * 地址详情
     * @param int addressId 地址id
     */
    public function editaddress() {
        $id = $_POST['addressId'];
        if (empty($id)) $this->jsonData->errCode('','id不能为空');

        $member = $this->userInfo();
        $uid = $member['uid'];
        $edit = $this->db->GetOne("SELECT `id`,`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`default` as `df`,`time`,`qq`,`shifoufahuo`  FROM `@#_member_dizhi`  where  `uid` = $uid AND `id` = '$id'");
        if($edit){
          $this->jsonData->successCode('','获取成功',$edit);
        }else{
            $this->jsonData->successCode('','暂无参数');
        }
    }

    /**
     * 修改地址
     * @param int mobile 电话
     * @param int id 地址id
     * @param string sheng 省
     * @param string shi 市
     * @param string xian 县
     * @param string jiedao 街道
     * @param string shouhuoren 收货人
     * @param string default 详情
     */
    public function doedit() {
        $data = $_POST;
        $member = $this->userInfo();
        $uid = $member['uid'];
        $mobile = safe_replace($_POST['mobile']);
        $aid = safe_replace($_POST['id']);
        $sheng = safe_replace($_POST['sheng']);
        $shi = safe_replace($_POST['shi']);
        $xian = safe_replace($_POST['xian']);
        $jiedao = safe_replace($_POST['jiedao']);
        $shouhuoren = safe_replace($_POST['shouhuoren']);
        $default = safe_replace($_POST['df']);
        $qq = safe_replace($_POST['qq']);

        if (empty($mobile) || empty($aid) ||empty($sheng) ||empty($shi) ||empty($xian) ||empty($jiedao) ||empty($shouhuoren) || empty($default)){
            $this->jsonData->errCode('','必填参数不能为空');
        }
        // if(!preg_match("/^1[3456789]\d{9}$/",$mobile)){
        //      $this->jsonData->errCode('','电话号码不合法');
        // }
        $info = array();
        $t = time();
        //默认地址查询
        $his = $this->db->GetOne("SELECT id FROM `@#_member_dizhi` WHERE uid = '$uid' AND `default` = 'Y'");
        if ($default == 'Y' && $his) {
            $id = $his['id'];
            $up = $this->db->Query("UPDATE `@#_member_dizhi` SET `default` = 'N' WHERE id = '$id'");
        }
        //修改
        $sql = "UPDATE `@#_member_dizhi` SET `default`='$default',`jiedao`='$jiedao',`xian`='$xian',`shi`='$shi',`sheng`='$sheng',`mobile`='$mobile',`shouhuoren`='$shouhuoren',`qq`='$qq' WHERE id = '$aid'";
        $re = $this->db->Query($sql);

        //ajax
        if ($re) {
            $this->jsonData->success('','');

        } else {
            $this->jsonData->error('','');
        }
    }

    //删除地址
    public function dodelete() {
        $id = $_POST['id'];
        $member = $this->userInfo();
        $uid = $member['uid'];
        $info = $this->db->Getone("SELECT * FROM `@#_member_dizhi` WHERE `id`='$id'");

        //查询是否有订单使用过这个地址
        $is_order_have = $this->db->Getone("SELECT `confirm_address` FROM `@#_member_yg_record` WHERE `confirm_address`='$id'");
        if(!empty($is_order_have)){
            $this->jsonData->successCode('',''); // 已有订单使用地址
        }

        //开启事务
        $this->db->Query("BEGIN");
        $re1 = $this->db->Query("DELETE FROM `@#_member_dizhi` WHERE `id` = '$id'");

        if ($info['default'] == 'Y') {
            $info2 = $this->db->Getone("SELECT * FROM `@#_member_dizhi` WHERE `uid`='$uid'");
            $newid = $info2['id'];
            $re2 = $this->db->Query("UPDATE `@#_member_dizhi` SET `default`='Y' WHERE `id`='$newid'");
            if (!$re2) {
                $this->db->Query("ROLLBACK");
                $this->jsonData->error('','');
            }
        }
        if ($re1) {
            $this->db->Query("COMMIT");
            $this->jsonData->success('','');

        } else {
            $this->db->Query("ROLLBACK");
            $this->jsonData->error('','');
        }
    }

    /**
     * 修改默认
     * @param int id 地址id
     */
    public function dodefault() {
        $id = $_POST['id'];
        $member = $this->userInfo();
        $uid = $member['uid'];
        $his = $this->db->GetOne("SELECT id FROM `@#_member_dizhi` WHERE uid = '$uid' AND `default` = 'Y'");
        $oldid = $his['id'];
        $re1 = $this->db->Query("UPDATE `@#_member_dizhi` SET `default` = 'N' WHERE id = '$oldid'");
        $re2 = $this->db->Query("UPDATE `@#_member_dizhi` SET `default`='Y'  WHERE `id` = '$id' AND `uid` = '$uid'");
        if ($re1 && $re2) {
            $this->jsonData->success('','');
        } else {
            $this->jsonData->error('','');
        }
    }

    /**
     * 邀请好友
     */
    public function getInvitation(){
        $uid = $this->userInfo()['uid'];
        if($uid){
            $moneyData = 0;
            $id_list=$this->db->Getlist("SELECT uid,username,mobile FROM `@#_member` where yaoqing='$uid' ORDER BY `uid` DESC");
            foreach ($id_list as $key => $value) {
                $info[$key]['id']=$value['uid'];
                if($value['username']){
                    $info[$key]['name']=$value['username'];
                }else{
                    $info[$key]['name']=substr($value['mobile'],0,3).'****'.substr($value['mobile'],7,4);
                }
                $money=$this->db->Getone("SELECT sum(money) as m,`time` FROM `@#_member_recodes` where uid = {$value['uid']} and type='1'");
                $info[$key]['total_money']=sprintf("%.2f",$money['m']);
                $moneyData += sprintf("%.2f",$money['m']);

                $info[$key]['time'] = !empty($money['time']) ? date('Y-m-d H:i:s',$money['time']) : '未获取到时间';
            }

//            echo json_encode(['code'=>1,'msg'=>'获取成功','data'=>$info,'money'=>$moneyData]);exit;
            $data['info'] = $info ?: [];
            $data['money'] = $moneyData;
            $data['involvednum'] = count($id_list);
            $this->jsonData->successCode('','',$data);
        }else{
            $this->jsonData->error('',''); // 数据有误

        }

    }

    // 我的邀请码
    public function mycode(){
        $uid = $this->userInfo()['uid'];
        $qrcodeUrls['code'] = $this->mycode_create(1,$uid);
        $qrcodeUrls['dataMsgOne'] = '成功邀请好友注册购买优惠券、红酒、玉石类商品';
        $qrcodeUrls['dataMsgTwo'] = '您可获得订单 6% 奖励';
        $this->jsonData->successCode('','', $qrcodeUrls); // 我们的邀请码
    }
    
    /**
     * 邀请码
     */
    public function mycode_create($type = 2,$id){
        if (!is_numeric($id)){
            $id = _encrypt($id,'DECODE');
        }
        $qr_codes = $this->db->GetOne("SELECT `id`,`backgrounds`,`icons`,`qr_code_size`,`qr_code_border`,`left_margin`,`top_margin`,`internal_x`,`internal_y` FROM `@#_qr_codes` WHERE `is_selected` = 1");
        $roots = $_SERVER ['DOCUMENT_ROOT'];
        $rootUrl = '/statics/uploads/codes/user/' . "{$qr_codes['id']}-{$id}.png";
        if (!is_dir('statics/uploads/codes/user')) {
            mkdir('statics/uploads/codes/user',0777,true);
        }
        $qrcodeUrl = $roots . $rootUrl;

        $webPath = $this->db->Getone("SELECT `value` FROM `go_config` WHERE name = 'web_path'")['value'];

        if (!file_exists($qrcodeUrl)){
            $qr_codes['bgUrl'] = G_WEB_PATH . "/statics/uploads/" . $qr_codes['backgrounds'];
            $qr_codes['icUrl'] = G_WEB_PATH . "/statics/uploads/" . $qr_codes['icons'];
            mycode_new($id,$qrcodeUrl,$webPath,$qr_codes['bgUrl'],$qr_codes['icUrl'],$qr_codes['qr_code_size'],$qr_codes['qr_code_border'],$qr_codes['left_margin'],$qr_codes['top_margin'],$qr_codes['internal_x'],$qr_codes['internal_y']);
            if (!file_exists($qrcodeUrl)) {
                echo json_encode(['code'=>0,'msg'=>'网络异常，请联系管理员']);exit;
            }
        }
        if ($type == 1){
            return  G_WEB_PATH . $rootUrl;
        }
        echo json_encode(['code'=>1,'msg'=>'ok','data'=>$rootUrl]);exit;
    }
    /**
     * 邀请码获取
     */
    public function mycode_new1($uid,$qrcode){

        //error_reporting(E_ALL);
        //ini_set('display_errors', 1);

        header("Content-type: image/png");
        ob_clean();//在输出钱擦掉（清除）缓存
        include_once($_SERVER ['DOCUMENT_ROOT'].'/statics/plugin/qrcode/QRcode.php');
        // $code=WEB_PATH."/mobile/mobile/init/".$uid;
        
        $url = $money=$this->db->Getone("SELECT `value` FROM `go_config` WHERE name = 'web_path'")['value'];
        $code=$url."/mobile/mobile/init/".$uid;

        /*$qrcode=$_SERVER ['DOCUMENT_ROOT'].'/statics/plugin/qrcode/tmp/'._getcookie('uid').".png";*/
         $bg_img=$_SERVER ['DOCUMENT_ROOT'].'/statics/plugin/qrcode/bg.png';

        $rs=QRcode::png($code,$qrcode,"M",5.9,2);

        $path_2 = $qrcode;

        $logo = $_SERVER ['DOCUMENT_ROOT'].'/statics/plugin/qrcode/logo.png';//需要显示在二维码中的Logo图像
        $QR = $path_2;
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
            $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
            $QR_width = imagesx ( $QR );
            $QR_height = imagesy ( $QR );
            $logo_width = imagesx ( $logo );
            $logo_height = imagesy ( $logo );
            $logo_qr_width = $QR_width / 4;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
        }
        //$pic2=imagerotate($QR, -15, 0);
        imagepng ( $QR, $path_2 );//带Logo二维码的文件名

        // 图片二
         $path_1 = $bg_img;
        // 创建图片对象
         $image_1 = imagecreatefrompng($path_1);
        $image_2 = imagecreatefrompng($path_2);
        // 合成图片
         imagecopymerge($image_1, $image_2, 70, 260,0, 0, imagesx($image_2), imagesy($image_2), 100);
        // 输出合成图片
        $rs=imagepng($image_1,$qrcode);

         imagedestroy($image_1);
        imagedestroy($image_2);

    }

    /**
     * 获取用户信息
     * @param int $isHaveNum 1.单条，2.数组
     * @param int $userId 用户id
     */
    private function getUserList($isHaveNum = 1,$userId = ''){
        if ($isHaveNum == 1){

            if (empty($userId)) false;
            // $sql = "SELECT `username`,`uid`,`email`,`mobile`,`img`,`money`,`time`,`headimg` FROM `go_member` where `uid`=$userId";
            $sql = "SELECT `username`,`go_member`.`uid`,`v_id`,`email`,`mobile`,`img`,`money`,`time`,`headimg`,`ip_region` FROM `go_member`
            left join `go_member_ip` on `go_member`.uid = `go_member_ip`.uid where `go_member`.`uid`=$userId";

            $res = $this->db->GetOne($sql);
//            echo 11;
//            p($res);exit;
            $res['mobile'] = substr($res['mobile'],0,3).'****'.substr($res['mobile'],7,4);
            $res['username'] = !empty($res['username']) ? $res['username'] : $res['mobile'];
           $res['img'] = G_UPLOAD_PATH.'/'. $res['img'];
            // $res['img'] = G_WEB_PATH.'/'. $res['img'];
        }

        if ($isHaveNum == 2){
            // $sql = "SELECT `username`,`uid`,`email`,`mobile`,`img`,`money`,`time`,`headimg` FROM `go_member`";
            $sql = "SELECT `username`,`go_member`.`uid`,`v_id`,`email`,`mobile`,`img`,`money`,`time`,`headimg`,`ip_region` FROM `go_member` left join `go_member_ip` on `go_member`.uid = `go_member_ip`.uid";
            $res = $this->db->GetList($sql);

            foreach ($res as &$v){
                $v['mobile'] = substr($v['mobile'],0,3).'****'.substr($v['mobile'],7,4);
                $v['username'] = !empty($v['username']) ? $v['username'] : $v['mobile'];
               $v['img'] = G_UPLOAD_PATH.'/'. $v['img'];
                // $v['img'] = G_WEB_PATH.'/'. $v['img'];

            }
        }

        return $res;
    }

    /**
     * 获取指定用户信息
     * @param int $userId 用户id
     */
    public function getUserInfo(){
        $userId = $_POST['userId'] ?: $this->uId;
        if (empty($userId)) $this->jsonData->error('','参数有误');
//        echo 2;
        $data = $this->getUserList(1,$userId);
        if (!$data){
            $this->jsonData->success('','暂无用户');
        }
        $this->jsonData->successCode('','获取成功',$data);
    }

    /**
     * 访问他人购买记录
     * @param int type 类型 0 参与的商品 全部... 1.获得奖品 2.晒单记录
     * @param int userId 用户id
     * @param int page 页码
     */
    public function getUserBuyList(){

        $type=intval($_POST['type']);

        $uid=intval($_POST['userId']);

        $FIdx=intval($_POST['page']);

        $EIdx=10;//$this->segment(7);

        $member= $this->userInfo();

        if($uid){
            $user_info=$this->db->GetOne("SELECT `shop_ permission`,`owinshop_permission` FROM `@#_member` where uid='$uid'");
        }

        if($type==0){
            //参与的商品 全部...
            if($uid == $member['uid']){

                $shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid ");

                $shop['listItems']=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );
            }else{

                if($user_info['shop_ permission'] == '0'){

                    $this->jsonData->successCode('','暂无数据');
                }else{

                    $shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid ");
                    $shop['listItems']=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' and b.q_showtime !='Y' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );

                }
            }
        }elseif($type==1){

            //获得奖品
            if($uid == $member['uid']){

                $shoplist=$this->db->GetList("select * from  `@#_shoplist_copy`  where q_uid='$uid' " );

                $shop['listItems']=$this->db->GetList("select * from  `@#_shoplist_copy`  where q_uid='$uid' and q_showtime !='Y' order by q_end_time desc limit $FIdx,$EIdx" );
            }else{
                if($user_info['owinshop_permission'] == '0'){
                    $this->jsonData->successCode('','暂无数据');
                }else{
                    $shoplist=$this->db->GetList("select * from  `@#_shoplist_copy`  where q_uid='$uid' " );

                    $shop['listItems']=$this->db->GetList("select * from  `@#_shoplist_copy`  where q_uid='$uid' order by q_end_time desc limit $FIdx,$EIdx" );

                }
            }

        }elseif($type==2){

            //晒单记录

            $shoplist=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist_copy` b on a.sd_shopid=b.id where a.sd_userid='$uid' and b.q_showtime !='Y'" );

            $shop['listItems']=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist_copy` b on a.sd_shopid=b.id where a.sd_userid='$uid' order by a.sd_time desc limit $FIdx,$EIdx" );

        }

        if(empty($shop['listItems'])){

            //$shop['code']=4;

            $this->jsonData->successCode('','暂无数据');

        }else{
            foreach($shop['listItems'] as $key=>$val){

                if($val['q_end_time']!=''){

                    $shop['listItems'][$key]['codeState']=3;

//                    $shop['listItems'][$key]['q_user']=get_user_name($val['q_uid']);

                    $shop['listItems'][$key]['q_end_time']=$this->microt($val['q_end_time']);

                }

                if(isset($val['sd_time'])){

                    $shop['listItems'][$key]['sd_time']=date('m月d日 H:i',$val['sd_time']);

                }

            }

            $shop['code']=0;

            $shop['count']=count($shoplist);

        }
        if (empty($shop['listItems'])) $this->jsonData->successCode('','暂无数据');

        $newArray = [];
        //第
        $di = $this->getLgMsg("CPhase","home_mycollect");
        // 期
        $qi = $this->getLgMsg("mycollect_Phase","home_mycollect");
        if ($type == 0){
            foreach($shop['listItems'] as $k => $v){
                $newArray[$k]['thumb'] = G_UPLOAD_PATH.'/'. $v['thumb'];
                $newArray[$k]['title'] = '（'.$di . $v['shopqishu'] .$qi.')' . $v['title'];
                $newArray[$k]['zongrenshu'] = $v['zongrenshu'];
                $newArray[$k]['canyurenshu'] = $v['canyurenshu'];
                $newArray[$k]['shenyurenshu'] = $v['shenyurenshu'];
//                $newArray[$k]['shopqishu'] = $v['shopqishu'];
                $newArray[$k]['id'] = $v['id'];
                $newArray[$k]['scale'] = ($v['canyurenshu']/$v['zongrenshu'])*100;
            }

        }

        if ($type == 1){
            foreach($shop['listItems'] as $k => $v){
                $newArray[$k]['thumb'] = G_UPLOAD_PATH.'/'. $v['thumb'];
                // $newArray[$k]['title'] = '（第' . $v['qishu'] .'期)' . $v['title'];
                $newArray[$k]['title'] = '（'.$di . $v['qishu'] .$qi.')' . $v['title'];
                $newArray[$k]['money'] = $v['money'];
                $newArray[$k]['q_user_code'] = $v['q_user_code'];
                $newArray[$k]['id'] = $v['id'];
                $newArray[$k]['q_end_time'] = $v['q_end_time'];
                $newArray[$k]['userInfo'] = unserialize($v['q_user']);
                $user = unserialize($v['q_user']);
                $userinfo = $this->db->GetOne("select uid,v_id from `@#_member` where uid=".$user['uid']);
                $user['v_id'] = $userinfo['v_id'];
                $newArray[$k]['userInfo'] = $user;

//                $newArray[$k]['shopqishu'] = $v['shopqishu'];
            }
//            p($shop );exit;
//
//            p($newArray );exit;
        }
        $this->jsonData->successCode('','获取成功',$newArray?:$shop);
    }

    private function microt($time,$x=null){
        $list=explode(".",$time);
        if($x=="l"){
            return date("His",$list[1]).substr($list[0],2,2);
        }else if($x=="r"){
            return date("Y年m月d日 H:i",$list[1]);
        }else{
            return date("Y-m-d H:i:s",$list[0]);
        }
    }

    /**
     * 服务条款
     * @param int id 1.用户服务条款 2.隐私协议
     */
    public function gonggaoxq(){
        $gid =htmlspecialchars($_POST['id']);
        if (empty($gid)) $this->jsonData->error('','参数有误');
        /* $ggid = 1;
        switch ($gid){
            case 1:
                $ggid = 32;
                break;
            case 2:
                $ggid = 33;
                break;
            case 3:
                $ggid = 13;
                break;
            case 14:
                $ggid = 14;
                break;
        } */
        // $gonggaoxq=$this->db->GetList("SELECT `title`,`content` FROM `@#_article` WHERE `id` = $ggid");
        $gonggaoxq=$this->db->GetList("SELECT `title`,`content` FROM `@#_article` WHERE `keys` = $gid and  (`language` = '$this->nowUserLanguage' or `language` = '')");
        $gonggaoxq[0]['content'] = replaceArticle($gonggaoxq[0]['content'],"#%webName%#");
        $gonggaoxq[0]['content'] = replaceArticle($gonggaoxq[0]['content'],"#%webUrl%#");
        str_replace('<span class="text-huge">','<span class="text-huge" style="font-size:18px">',$gonggaoxq);
        // 
        if (empty($gonggaoxq)){
            $this->jsonData->successCode('','暂无数据',[]);
        }
        $this->jsonData->successCode('','获取成功',$gonggaoxq);
    }
    
    /**
     * 关于拼反返
     */
     public function pffconfig(){
         $data['images'] = "https://demo.yungoucms.com/statics/uploads/member.png";
         
         $config = System::load_sys_config('dataconfi');
         $data['gab1'] = $config['gab1'];
         $data['gab2'] = $config['gab2'];
         $data['gab3'] = $config['gab3'];
         $this->jsonData->successCode('','获取成功',$data);
     }

     // 支付
    public function userrecharge(){
        $is_bank_card_recharge = $this->getConfig('is_bank_card_recharge');
        $is_usdt_recharge = $this->getConfig('is_usdt_recharge');   
        $is_charlie_recharge = $this->getConfig('is_charlie_recharge'); //卡密
        $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1 order by `sort` DESC");
        foreach ($paylist as $k=>$v){
            $paylist[$k]['pay_input'] = @unserialize($v['pay_input']);
            $paylist[$k]['pay_monerange'] = @unserialize($v['pay_monerange']);
            if(isset($paylist[$k]['pay_monerange']['ranges'])){
                $this->number_formt($paylist[$k]['pay_monerange']['ranges']);
            }
            $paylist[$k]['pay_thumb'] = G_UPLOAD_PATH.'/'.$v['pay_thumb'];
            unset($paylist[$k]['pay_key']);
        }
        $kefu_list= $this->db->GetList("SELECT * FROM `@#_kefu` where `type` = 2 and `status`=1");

        foreach ($kefu_list as &$v1){
            $v1['img'] =  G_UPLOAD_PATH . "/" . $v1['img'];
            $v1['pay_monerange'] = @unserialize($v1['pay_monerange']);
            if(isset($v1['pay_monerange']['ranges'])){
                $this->number_formt($v1['pay_monerange']['ranges']);
            }

        }

        $usdt_list= $this->db->GetList("SELECT id,name,img,bank_user as dizhi,pay_monerange,exchange_rate FROM `@#_kefu` where `type` = 3 and `status`=1");
        foreach ($usdt_list as &$v1){
            $v1['img'] =  G_UPLOAD_PATH . "/" . $v1['img'];
            $v1['pay_monerange'] = @unserialize($v1['pay_monerange']);
            // $v1['exchange_rate'] = $v1['exchange_rate'] * 100;
            if(isset($v1['pay_monerange']['ranges'])){
                $this->number_formt($v1['pay_monerange']['ranges']);
            }
        }
        $data = [
            'paylist' => $paylist,
            'is_bank_recharge' => $is_bank_card_recharge,
            'is_usdt_recharge' => $is_usdt_recharge,
            'is_charlie_recharge' => $is_charlie_recharge,
            'kefu' => $is_bank_card_recharge==2?[]:$kefu_list,
            'usdt' => $is_usdt_recharge==2?[]:$usdt_list,
        ];
        $this->jsonData->successCode('','获取成功',$data);
    }
    private function number_formt(&$array){
        foreach($array as &$vl){
            $vl = number_format($vl);
        }
    }

    public function offlinemoney()
    {
        $post = $_POST;
        if(!preg_match('/^\d+$/', $post['id'])){
            $this->jsonData->errCode('','操作失败');
        }

        $uid = $this->uId;
        $time = time();
        $post['id'] = safe_replace($post['id']);
        $pay= $this->db->GetOne("SELECT * FROM `@#_kefu` where id = '$post[id]'");
        if(!$pay){
            $this->jsonData->errCode('','支付方式未找到');
        }
        $is_bank_card_recharge = $this->getConfig("is_bank_card_recharge")==2?false:true;
        if(!$is_bank_card_recharge && $pay['type']==2){
            $this->jsonData->errCode('','银行卡充值未开启');
        }
        $is_usdt_recharge = $this->getConfig("is_usdt_recharge")==2?false:true;
        if(!$is_usdt_recharge && $pay['type']==3){
            $this->jsonData->errCode('','usdt充值未开启');
        }

        $post['money'] = safe_replace($post['money'],true);
        $picarr = $post['picarr'];
        if($picarr){
            $picarr = explode(",",$picarr);
            $picarr = serialize($picarr);
        }

        $type = $pay['type'];
        unset($pay['pay_monerange']);
        $kefu = serialize($pay);
        $exchange_rate = $pay['exchange_rate']?:1;
        $pay_prefix = $this->getConfig('pay_prefix')?:"C";
        System::load_app_fun("pay","pay");
        $code = pay_get_dingdan_code($pay_prefix);		//订单号
        $money = number_format($post['money']);
        $data = [
            "uid"=>$uid,
            "bank_id"=>$post['id'],
            "create_time"=>$time,
            "status"=>1,
            "volume"=>$kefu,
            "money"=>$money,
            "picarr"=>$picarr,
            "type"=>$type,
            "exchange_rate"=>$exchange_rate,
            "real_money"=>bcmul($money,$exchange_rate),
            "code"=>$code
        ];
        $sql = Assemblydata($data,"offline_money");
        //usdt充值处理
        if($pay['type']==3){
            if(!$pay['pay_class']){
                $this->jsonData->errCode('','后台未配置');
            }
            $paydb = System::load_app_class($pay['pay_class'],'pay');
            $config = $data;
            $config['data'] = [
                "url"=> ["val"=>$pay['url']],
                "pid"=> ["val"=>$pay['bank_name']],
                "key"=> ["val"=>$pay['bank_user']],
                "type"=> ["val"=>$pay['bank_no']],
            ];
            $config['ReturnUrl']  = G_WEB_PATH.'/index.php/pay/'.$pay['pay_class'].'_url/qiantai';	//前台回调
            $config['NotifyUrl']  =G_WEB_PATH.'/index.php/pay/'.$pay['pay_class'].'_url/houtai_usdt';		//后台回调
            $config['u_id'] =_cfg('pay_prefix')."_".$uid;
            $config['shouname'] = _cfg('web_name');						//收款方
            $config['ip'] = _get_ip();
            $ok = $paydb->config($config);
            if($ok['pay_status_code']==1){
                $res = $this->db->Query($sql);
            }else{
                $res = false;
                $msg = $ok['msg'];
            }
        }else{
            $res = $this->db->Query($sql);
            if($res){
                $this->jsonData->successCode('','操作成功');
            }else{
                $this->jsonData->errCode('','操作失败');
            }
        }
    }

    public function commissions(){
        $member=$this->userInfo();
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }
        $uid = $member['uid'];
        $recodetotal=0;   // 判断是否为空
        $shourutotal=0;
        $zhichutotal=0;
        $invifriends=$this->db->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        //查询佣金表
        for($i=0;$i<count($invifriends);$i++){
            $sqluid=$invifriends[$i]['uid'];
            //查询邀请好友给我反馈的佣金
            $recodes[$sqluid]=$this->db->GetList("select * from `@#_member_recodes` where `uid`='$sqluid' and `type`=1 ORDER BY `time` DESC");
            $user[$sqluid]['username']= $this->get_user_name($invifriends[$i]);
        }

        //自己提现或充值
        $recodes[$uid]=$this->db->GetList("select * from `@#_member_recodes` where `uid`='$uid' and `type`!=1 ORDER BY `time` DESC");
        $user[$uid]['username']= $this->get_user_name($member);
        $recodearr = [];
        $i=0;
        if(!empty($recodes)){
            foreach($recodes as $key=>$val){
                $username[$key]=$user[$key]['username'];
                foreach($val as $key2=>$val2){
                    $m = $this->db->GetOne("select username,v_id from `@#_member` where uid = ".$val2['uid']);
                    $s = $this->db->GetOne("select title from `@#_shoplist` where id = ".$val2['shopid']);
                    $val2['username']= $m['username'];
                    $val2['v_id']= $m['v_id'];
                    $val2['title']= $s['title'];
                    $val2['time'] = date("Y-m-d H:i:s",$val2['time']);
                    $recodearr[$i]=$val2;
                    $i++;
                }
            }
        }
        $recodetotal=count($recodes);
        //查询   累计收入：元    累计(提现/充值)：元    佣金余额：元
        $data = [
            'recodetotal' => $recodetotal,
            'recodearr' => $recodearr,
            'shourutotal' => $shourutotal,
            'zhichutotal' => $zhichutotal,
        ];
        $this->jsonData->successCode('','获取成功',$data);
    }

    public function get_user_name($uid='',$type='username',$key='sub'){
        if(is_array($uid)){
            if(isset($uid['username']) && !empty($uid['username'])){
                if(is_numeric($uid['username'])){
                    $uid['username']= substr_replace($uid['username'],'****',3,4);
                    return  $uid['username'];
                }else{
                    return $uid['username'];
                }
            }
            if(isset($uid['email']) && !empty($uid['email'])){
                if($key=='sub'){
                    $email = explode('@',$uid['email']);
                    return $uid['email'] = substr($uid['email'],0,2).'*'.$email[1];
                }else{
                    return $uid['email'];
                }
            }
            if(isset($uid['mobile']) && !empty($uid['mobile'])){
                if($key=='sub'){
                    return $uid['mobile'] = substr($uid['mobile'],0,3).'****'.substr($uid['mobile'],7,4);
                }else{
                    return $uid['mobile'];
                }
            }
            return '';
        }else{
            $db = System::load_sys_class("model");
            $uid = intval($uid);
            $info = $db->GetOne("select username,email,mobile from `@#_member` where `uid` = '$uid' limit 1");
            if(isset($info['username']) && !empty($info['username'])){
                return $info['username'];
            }

            if(isset($info['email']) && !empty($info['email'])){
                if($key=='sub'){
                    $email = explode('@',$info['email']);
                    return $info['email'] = substr($info['email'],0,2).'*'.$email[1];
                }else{
                    return $info['email'];
                }
            }
            if(isset($info['mobile']) && !empty($info['mobile'])){
                if($key=='sub'){
                    return $info['mobile'] = substr($info['mobile'],0,3).'****'.substr($info['mobile'],7,4);
                }else{
                    return $info['mobile'];
                }
            }
            if(isset($info[$type]) && !empty($info[$type])){
                return $info[$type];
            }
            return '';
        }
    }
}
?>