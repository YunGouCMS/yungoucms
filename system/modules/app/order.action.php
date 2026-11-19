<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');

System::load_app_class('base', 'app', 'no');
//购买流程
class order extends base
{
	private $jifen_status = ["0"=>"取消","1"=>"待付款","2"=>"待发货","3"=>"已发货","4"=>"已收货"];
    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();

    }
    /*
     * 订单生成和付款
     * */
    public function jifen_order_add()
    {
        $uid = $this->uId;
        $cids = $_POST['cart_id']; //购物车ID
        empty($cids) && $cids = safe_replace($_POST['cartIds']);
        $message = $_POST['message']; //备注
        $aid = $_POST['addressid']; //地址ID
        if (empty($aid)) {
            $aid = '0';
        }
        $typenum = 0;
        $arr = explode(',', $cids);
        $money = 0;
        $score = 0;
        $time = time();
        $prefix = $this->getConfig('order_number_prefix_dp');
        $code = $prefix . $time . getRandomstr(); //订单号
        $error = [
            "title" => '请求失败',
            'url' => ''
        ];
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if ($v != '') {
                    $list  = $this->db->GetOne("SELECT * FROM `@#_cart` WHERE `uid`=$uid AND `id` = '$v'");
                    if (!$list) {
                        $this->jsonData->error('', '查询购物车信息失败', $error);
                    }
                    $oinfo[] = $list;
                }
            }
        }
        if (empty($oinfo)) {
            $this->jsonData->error('', '请先将商品添加到购物车再提价结算', $error);
        }
        foreach ($oinfo as $k => $v) {
            $sid = $v['shopsid'];
            $sql2 = "SELECT * FROM `@#_shoplist_jifen`WHERE `id`=$sid  and delete_time is null ";
            $sinfo = $this->db->GetOne($sql2);
            if(!$sinfo) {
                continue;
            }
            $oinfo[$k]['item'] = $sinfo;
            $typenum++;
            // $money += $v['cmoney'];
            $m = $sinfo['disc_price']>0?$sinfo['disc_price']:$sinfo['money'];
            $money += bcmul($m,$v['num']);
            $score += bcmul($sinfo['s_score'],$v['num']);
        }
        $result = [$code, $uid, $typenum, $money, $score, $aid, $message, $time];
        //开启事务
        $this->db->Query("BEGIN");
        //建立订单主信息
        $sql3 = "INSERT INTO `@#_member_goods_order`(`code`,uid,typenum,`money`,score,aid,message,`cretime`,`uptime`,`status_type`) VALUES('$code', $uid, $typenum, $money, $score, $aid, '$message', $time,$time,'1')";
        $re3 = $this->db->Query($sql3);
        //修改oid
        $sql4 = "SELECT * FROM `@#_member_goods_order` WHERE `code`='$code'";

        $re4 = $this->db->GetOne($sql4);
        $oid = $re4['id'];

        $sql7 = "UPDATE `@#_member_goods_order` SET `oid`='$oid' WHERE `code`='$code'";

        $re7 = $this->db->Query($sql7);
        //建立订单分项信息
        $time = $re4['cretime'];
        // echo $oid . '<br>';
        foreach ($oinfo as $k => $v) {
            $sid = $v['shopsid'];
            $price = $v['price'];
            $score = $v['item']['s_score']?:0;
            $snum = $v['num'];
            $total = $price * $snum;
            $sql5 = "INSERT INTO `@#_member_goods_item`(`oid`,sid,price,score,snum,`total`,`cretime`) VALUES($oid,$sid,$price,$score,$snum,$total,$time)";
            $re5 = $this->db->Query($sql5);
        }
        //删除购物车信息
        if (is_array($arr)) {
            //            echo '已经转为数组';
            foreach ($arr as $k => $v) {
                if ($v != '') {
                    $sql6 = "DELETE FROM `@#_cart` WHERE `uid`=$uid AND `id` = '$v'";
                    $re6[$k] = $this->db->Query($sql6);
                    if (!$re6[$k]) {
                        $this->jsonData->error('', '删除失败', $error);
                    }
                }
            }
        }
        if ($re3 && $re4 && $re5 && $re6 && $re7) {
            //操作无误，提交事务
            $this->db->Query("COMMIT");
            // echo '事务处理完成';
        } else {
            //回滚事务
            $this->db->Query("ROLLBACK");
            $error["result"] =[!!$re3,!!$re4,!!$re5,!!$re6,!!$re7];
            $error['sql'] = $sql5;
            $this->jsonData->error('', '订单提交失败请重试', $error);
            exit;
        }
        //跳转支付页面
        $title = '支付订单';
        $money = $re4['money'];
        $code = $re4['code'];
        $orderid = $re4['id'];
        $data = [
            'code' => $code,
            'orderid' => $orderid,
            'title' => $title,
            'money' => $money,
        ];
        $this->jsonData->success('', '成功', $data);
    }

    /**
     * 订单列表
     *
     * @return void
     */
    public function jifen_order_list(){
        $userid = $this->uId;
        $p=htmlspecialchars($_POST['p']) ? $_POST['p'] : 1;
        $status=htmlspecialchars($_POST['status']);
        $pagesize = 10;
        $start = ($p - 1) * $pagesize;
        $where = '';
        if($status){
            $where .= 'and status=' . $status;
        }
        $sql = "SELECT * FROM `@#_member_goods_order` WHERE `uid` = '$userid' $where and delete_time is null ORDER BY `cretime` DESC LIMIT $start,$pagesize";
        $orderList = $this->db->GetList($sql);
        foreach ($orderList as $k => $v) {
            $sql2 = "SELECT a.*,s.title,s.thumb FROM `@#_member_goods_item` a left join `@#_shoplist_jifen` s on a.sid = s.id WHERE `oid` = '$v[id]'";
            $itemList = $this->db->GetList($sql2);
            foreach ($itemList as &$vl){
                $vl['thumb'] = G_UPLOAD_PATH.'/'.$vl['thumb'];
            }
            $orderList[$k]['cretime'] = date("Y-m-d H:i:s", $v['cretime']);
            $orderList[$k]['sendtime'] = date("Y-m-d H:i:s", $v['sendtime']);
            $orderList[$k]['suctime'] = date("Y-m-d H:i:s", $v['suctime']);
            $orderList[$k]['itemList'] = $itemList;
            $orderList[$k]['status_text'] = $this->jifen_status[$v['status']];
        }
        $this->jsonData->success('', '成功', $orderList);
    }

    	//订单详情
	public function jifen_order_details() {
        $oid = htmlspecialchars($_POST['orderid']);
        $status = htmlspecialchars($_POST['status']);
		$uid = $this->uId;
        $sql = "SELECT * FROM `@#_member_goods_order` WHERE `oid`=$oid AND `uid`=$uid and delete_time is null";
		$order = $this->db->Getone($sql);
		$sql1 = "SELECT * FROM `@#_member_goods_item` WHERE `oid`=$oid and delete_time is null";
		$oitem = $this->db->Getlist($sql1);
		foreach ($oitem as $key => $value) {
			$sid = $value['sid'];
			$sitem = $this->db->Getone("SELECT * FROM `@#_shoplist_jifen` WHERE `sid` = $sid");
            $sitem['thumb'] = G_UPLOAD_PATH.'/'.$sitem['thumb'];
			$oitem[$key]['s_item'] = $sitem;
		}
		$order['o_item'] = $oitem;
		$order['cretime'] = date('Y-m-d H:i:s', $order['cretime']);
		$order['suctime'] = date('Y-m-d H:i:s', $order['suctime']);
		$order['sendtime'] = date('Y-m-d H:i:s', $order['sendtime']);
		$order['endtime'] = date('Y-m-d H:i:s', $order['endtime']);
		$aid = $order['aid'];
		$address = $this->db->Getone("SELECT * FROM `@#_member_dizhi` WhERE `uid`=$uid AND `id`=$aid");
        $order['address'] = $address;
        $this->jsonData->success('','',$order);
	}

    /**
     * 取消订单
     */
    public function jifen_order_cancel(){
        $id = $_POST['orderid'];
        $userid = $this->uId;
        $order = $this->db->GetOne("SELECT `status` FROM `@#_member_goods_order` WHERE `id` = '$id' AND `uid` = '$userid'");
        if ($order['status']!= '1') {
            $this->jsonData->error('', '该订单状态不可以取消');
        }
        $this->db->Autocommit_start();
        $sql = "UPDATE `@#_member_goods_order` SET `status` = '0' WHERE `id` = '$id' AND `uid` = '$userid'";
        $re = $this->db->Query($sql);
        if(!$re){
            $this->db->Autocommit_rollback();
        }
        $this->db->Autocommit_commit();
        $this->jsonData->success('', '');
    }

    //删除订单
	public function jifen_order_del() {
		$orderid = $_POST['orderid'];
		$res = ['code' => '200', 'message' => '删除成功', 'id' => $orderid];
		if ($orderid == ' ') {
			$res = ['code' => '500', 'message' => '订单id为空,删除失败'];
		}
		//删除
		//开启事务
		$this->db->Query("BEGIN");
		// $sql1 = "DELETE FROM `@#_member_goods_order` WHERE `oid`='$orderid'";
        $time = time();
        $sql1 = "Update `@#_member_goods_order` set delete_time = $time where `oid`='$orderid'";
		$re1 = $this->db->Query($sql1);
		// $sql2 = "DELETE FROM `@#_member_goods_item` WHERE `oid`='$orderid'";
        $sql2 = "Update `@#_member_goods_item` set delete_time = $time where `oid`='$orderid'";
        // 执行sql
		$re2 = $this->db->Query($sql2);
		if ($re1 && $re2) {
			//操作无误，提交事务
			$this->db->Query("COMMIT");
		} else {
			//回滚事务
			$this->db->Query("ROLLBACK");
			$res = ['code' => '600', 'message' => '删除失败,请重试'];
		}
		echo json_encode($res);
	}

    	//查看物流
	public function jifen_order_logistics() {
		$title = "查看物流";
		$fenname['name'] = "查看物流";
		$oid = $this->segment(4);
		$info = $this->db->GetOne("SELECT `logistics_way`,`logistics_code` FROM `@#_member_pay_order` WHERE `oid`='$oid'");
		$lid = $info['logistics_way'];
		$linfo = $this->db->GetOne("SELECT `lname` FROM `@#_logistics` WHERE `lid`='$lid'");
		$info['logistics_way'] = $linfo['lname'];
		
		$oitem = $this->db->Getone("SELECT sid FROM `@#_member_goods_item` WHERE `oid`=$oid");
		$sitem = $this->db->Getone("SELECT thumb FROM `@#_shoplist_jifen` WHERE `sid` = $oitem[sid]");
		
		include templates("mobile/user", "logistics");
	}

	//确认收货
	public function jifen_order_receipt() {
		$orderid = $_POST['orderid'];
		if ($orderid == ' ') {
            $this->jsonData->error(500,"订单id为空,确认收货失败");
		}
        $order = $this->db->GetOne("SELECT `status` FROM `@#_member_goods_order` WHERE `oid`='$orderid' and delete_time is null");
        if(!$order) {
            $this->jsonData->error(500,"该订单不存在");
        }
        if ($order['status']!= '3') {
            $this->jsonData->error(500,"该订单状态不可以确认收货");
        }
        $this->db->Autocommit_start();
		$endtime = time();
		$sql = "UPDATE `@#_member_goods_order` SET `status`='4',`endtime`='$endtime',`uptime`='$endtime' WHERE `oid`='$orderid'";
		$re = $this->db->Query($sql);
		$sql = "UPDATE `@#_member_pay_order` SET `status`='4',`endtime`='$endtime',`uptime`='$endtime' WHERE `oid`='$orderid'";
		$re = $this->db->Query($sql);
		if (!$re) {
            $this->db->Autocommit_rollback();
            $this->jsonData->error(600,"确认收货失败,请重试");
		}
        $this->db->Autocommit_commit();
        $this->jsonData->success(200,"确认收货成功");
	}

    //积分商品支付前准备
    public function jfgoodspay()
    {
        $id = $_POST['id']; //
        //        $id = [170];
        $userid = $this->uId;
        $userinfo =  $this->db->GetOne("SELECT `uid`,`money` FROM `@#_member` WHERE `uid` = '$userid'");
        if (!$id) {
            $this->jsonData->error('', '参数错误');
        }
        $arrs = explode(',', $id); //
        $arr = [];
        $zr = 0;
        //        ,`shenyurenshu` > '$zr'
        foreach ($arrs as $k => $v) {
            $cartid = $v;
            $sql = $this->db->GetOne("SELECT `num`,`shopsid`,`sid` FROM `@#_cart` WHERE `id` = '$cartid'"); //查询到这个购物车
            if ($sql) {
                $dom = $this->db->GetOne("SELECT `qishu`,`title`,`shenyurenshu` FROM `@#_shoplist` WHERE `id` = '$sql[shopsid]' AND `shenyurenshu` > '$zr'"); //查这个商品
                if (!$dom) {
                    $dom = $this->db->GetOne("SELECT `id`,`qishu`,`title` FROM `@#_shoplist` WHERE `sid` = '$sql[sid]' AND `shenyurenshu` > '$zr'"); //查不到的话就查sid
                    $cart = $this->db->Query("UPDATE `@#_cart` SET `shopsid` = '$dom[id]' WHERE `id` = '$cartid'"); //覆盖购物车的商品id
                    if (!$cart) {
                        $this->jsonData->errCode('', '商品id查找失败');
                    }
                }
                $arr['main'][$k]['qishu'] = $dom['qishu'];
                $arr['main'][$k]['title'] = $dom['title'];
                $arr['main'][$k]['money'] = $sql['num'] * 1; //当前商品总价格
                $arr['price'] += $sql['num'] * 1; //总价格
            }
        }
        $arr['userIntegral'] = $userinfo['money']; //用户当前积分
        $arr['title'] = '积分充值';
        $arr['chongzhi'] = G_WEB_PATH . '/mobile/home/userrecharge/2/' . $userid . '/2';
        $this->jsonData->successCode('', '成功', $arr);
    }
    //充值
    public function actionpay()
    {
        $id = $this->uId;
        $data = [
            'title' => '积分充值',
            'url' => G_WEB_PATH . '/mobile/home/userrecharge/2/' . $id . '/2'
        ];
        $this->jsonData->successCode('', '成功', $data);
    }
}
