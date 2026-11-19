<?php defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','app','no');

// 支付接口 
class pays extends base {
    public function __construct() {
        parent::__construct();
    }

    //开始购买
    public function go_pay() {
        $cart_id = safe_replace($_POST['cart_id']);
        $cart_id = trim($cart_id,',');
        $uid = $this->uId;
        //获取购买前的账户信息
        $userInfo_before = $this->userInfo();
        if(!$userInfo_before){
            $this->jsonData->error('','获取用户信息失败');
        }
        $cartList = $this->db->GetList("select id,shopsid as shopid,num,`status`,`price`,`cmoney`,`mode` from `@#_cart` where id in($cart_id) and uid = $uid");
        if(!$cartList){
            $this->jsonData->error('','查询购物车失败');
        }

        $shopids = '';
        $y_cartids = '';
        foreach ($cartList as $k=>$v){
            $cartList[$k]['num'] = (int)$v['num'];
            if($v['mode']==1){
                if (empty($shopids)){
                    $shopids = $v['shopid'];
                }else{
                    $shopids .= "," . $v['shopid'];
                }
            }else{
                if($y_cartids){ $y_cartids .= ","; }
                $y_cartids .= $v['id']; 
            }
        }
        //处理云购商品
        $shopList = $this->db->GetList("select `id`,`shenyurenshu` from `@#_shoplist` where id in($shopids) ");
        $newCartList = [];
        $money = 0;
        foreach ($cartList as $v1){
            if($v1['mode']==1){
                $money +=$v1['cmoney'];
                $newCartList[$v1['shopid']]['num'] = $v1['num'];
                $newCartList[$v1['shopid']]['money'] = $v1['price'];
                $newCartList[$v1['shopid']]['sun'] = $v1['cmoney'];
                foreach ($shopList as $v2){
                    if($v2['id'] == $v1['shopid']){
                        $newCartList[$v1['shopid']]['shenyu'] = $v2['shenyurenshu'];
                    }
                }
            }
        }
        $userInfo = $this->userInfo();
        $checkpay = safe_replace($_POST['checkpay']);
        $banktype = safe_replace($_POST['banktype']);
        // $money = safe_replace($_POST['money']);
        $fufen = safe_replace($_POST['fufen'],true);
        $res['code'] = 1;
        $res['msg'] = "";
        $res1['code'] = 200;
        $res1['msg'] = "";
        if($newCartList){

            // 获取优惠卷id
            $conpuid = safe_replace($_POST['conpuid']);
            if (!empty($conpuid)){
                _setcookie("conpuid" . $this->uId, $conpuid, time() + 3600);
            }

            $res = $this->paysubmitapi($checkpay,$banktype,$money,$fufen,[
                'score' => $userInfo['score'],
                'money' =>  $userInfo['money'],
                'uid'   =>  $userInfo['uid'],
            ],$newCartList);
        }
        if($y_cartids){
            //处理优惠购
            $res1 = $this->prePay($y_cartids,$checkpay);
        }
        //清除购物车
        if($res['code'] == 1 && $res1['code']==200){
            $this->db->Query("delete from `@#_cart` where id in($cart_id)");
            // 获取购买后的账户信息
            $userInfo_after = $this->userInfo();
            $info = [
                'before' => ["money"=>$userInfo_before['money'],"score"=>$userInfo_before['score']],
                'after'  => ["money"=>$userInfo_after['money'],"score"=>$userInfo_after['score']],
            ];
            $this->jsonData->success('',$res['msg'],["userInfo"=>$info]);
        }else{
            $this->jsonData->error('',$res['msg']);
        }
    }

    /**
     * 开始支付
     * @param $checkpay int 获取支付方式  1 fufen 2 money 3 bank 4 pointmoney积分金额合并支付
     * @param $banktype int 获取选择的银行 1 nobank
     * @param $money int 金额
     * @param $fufen int 获取积分
     * @param $userInfo array 用户详情
     * @param $Mcartlist array 商品详情
            Array(
            [5180928] => Array
            (
            [num] => 2
            [shenyu] => 119
            [money] => 1.00
            [sun] => 2
            )

            [5180933] => Array
            (
            [num] => 1
            [shenyu] => 33698
            [money] => 1.00
            [sun] => 1
            )
            )
     * @return 
     */
    public function paysubmitapi($checkpay = 1,$banktype = 1,$money = 0,$fufen = 0,$userInfo = [],$Mcartlist = []) {
        $uid = $this->uId;
        $res = $this->payifno($checkpay,$money);
        if($res['code'] == 0) {
            return $res;
        }
        $fufen = $res['funfen'];$money = $res['money'];$checkpay = $res['checkpay'];
        $shopids = '';
        if (is_array($Mcartlist)) {
            foreach($Mcartlist as $key => $val) {
                $shopids .= intval ( $key ) . ',';
            }
            $shopids = str_replace(',0', '', $shopids);
            $shopids = trim($shopids, ',');
        }
        $shoplist = array ();
        if ($shopids != NULL) {
            $shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );
        }
        if (!empty ($shoparr)) {
            foreach ($shoparr as $key => $val) {
                if($val['ispk']){
                    if($Mcartlist[$key]['num'] > 1) {
                        return ["code"=>0,'msg'=>"购物车里单件PK商品数量大于1.."];
                    } else {
                        $is_pk=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where shopid='$val[id]' and uid=$uid ORDER BY id DESC ");
                        if($is_pk) {
                            return ["code"=>0,'msg'=>"购物车里PK商品已参与过.."];
                        }
                    }
                }
            }
        }
        $pay_checkbox = false;
        $pay_type_bank = false;
        $pay_type_id = false;

        // 支付模类型
        if(!empty($checkpay)) _setcookie("checkpay" . $this->uId, $checkpay, time() + 3600);

        if ($checkpay == 'money') {
            $pay_checkbox = true;
        }
        if ($banktype != 'nobank') {
            $pay_type_id = $banktype;
        }
        if (!empty ( $zhifutype )) {
            $pay_type_bank = $zhifutype ['pay_class'];
        }
        if (! $pay_type_id) {
            if($checkpay != 'fufen' && $checkpay != 'money' && $checkpay != 'pointmoney')
            return ["code"=>0,'msg'=>"选择支付方式.."];
        }
        $pay = System::load_app_class('pay','pay');
        if($checkpay == 'fufen' || $checkpay == 'pointmoney') {
            $pay->fufen = $fufen;
        } else {
            $pay->fufen = 0;
        }
        $pay->pay_type_bank = $pay_type_bank;
        $pay->scookie = $Mcartlist;
        $ok = $pay->init($uid,$pay_type_id, 'go_record');	//云购商品
        if($ok != 'ok'){
            return ["code"=>0,'msg'=>"购物车没有商品.."];
        }
        $check = $pay->go_pay($pay_checkbox);
        if (!$check) {
            return ["code"=>0,'msg'=>"订单添加失败.."];
        }
        if ($check) {
            return ["code"=>1,'msg'=>"购买成功.."];
        } else {
            return ["code"=>1,'msg'=>"购买失败.."];
        }
    }

    // 查询余额，返回支付所需积分，余额
    private function payifno($checkpay,$money){
        $checkType = [
            1 => "fufen",
            2 => "money",
            3 => "bank",
            4 => "pointmoney",
            5 => "online_payment",
        ];
        $checkpay = $checkType[$checkpay];
        // 查询用户余额
        $user_data = $this->userInfo();
        $fufen_cfg = System::load_app_config("user_fufen",'','member');
        $fufen = 0;
        $amount = 0;
        // 积分余额对比
        if($checkpay == 'fufen'){
            if($fufen_cfg['fufen_yuan'] <= 0){
                return ["code"=>0,'msg'=>$this->getLgMsg("Points_payment_not_enabled")];
                // return ["code"=>0,'msg'=>"未设置积分抵扣金额..."];
            }
            // 小于了配置的积分
            if($user_data['score'] < $money * $fufen_cfg['fufen_yuan']){
                return ["code"=>0,'msg'=>$this->getLgMsg("Insufficient_Balance")];
                // return ["code"=>0,'msg'=>"您的金额或积分不足请充值..."];
            }
            // $fufen = $money * $fufen_cfg['fufen_yuan'];
            $fufen = bcmul($money, $fufen_cfg['fufen_yuan'], 0);
            $amount = 0;
        }
        // 现金余额对比
        if($checkpay=='money' || $checkpay=='nobank'){
            if($user_data['money'] < $money){
                return ["code"=>0,'msg'=>$this->getLgMsg("Insufficient_Balance")];
                // return ["code"=>0,'msg'=>"您的金额或积分不足请充值.."];
            }
            $amount = $money;
        }
        // 合并支付 对比余额积分
        if($checkpay == 'pointmoney') {
            if($fufen_cfg['fufen_yuan'] <= 0){
                return ["code"=>0,'msg'=>$this->getLgMsg("Points_payment_not_enabled")];
                // return ["code"=>0,'msg'=>"未设置积分抵扣金额..."];
            }
            if($user_data['score'] <= 0) { //积分为0，使用余额
                if($user_data['money'] < $money){
                    return ["code"=>0,'msg'=>$this->getLgMsg("Insufficient_Balance")];
                    // return ["code"=>0,'msg'=>"您的金额或积分不足请充值.."];
                }
                $fufen = 0;
                $amount = $money;
            }
            $score_money = bcdiv($user_data['score'], $fufen_cfg['fufen_yuan'], 0);
            if(bcadd($user_data['money'] , $score_money) < $money) {
                return ["code"=>0,'msg'=>$this->getLgMsg("Insufficient_Balance")];
                // return ["code"=>0,'msg'=>"您的金额或积分不足请充值.."];
            }
            if($score_money > $money) {
                $fufen = bcmul($money, $fufen_cfg['fufen_yuan'], 0);
                $amount = 0;
            } else {
                $fufen = bcmul($score_money, $fufen_cfg['fufen_yuan'],0);
                $amount = $money - $score_money;
            }
        }

        //在线支付
        if($checkpay == 'online_payment'){

        }
        return ["code"=>200,'msg'=>"",'funfen'=>$fufen,"money"=>$amount,"checkpay"=>$checkpay];
    }

    //优惠购
    private function prePay($cart_ids,$checkpay){
        $sql = "select c.id,s.id as shopid,s.preferential_price,s.title,s.thumb,c.num,c.uid from `@#_cart` as c
            join `@#_shoplist` as s on c.shopsid = s.id 
            where c.id in ($cart_ids)";
        $list = $this->db->GetList($sql);
        if($list){
            $userInfo = $this->userInfo();
            $amount = 0;
            $point = 0;
            System::load_app_fun("pay","pay");
            $fufen_config = System::load_app_config("user_fufen",'','member');
            foreach($list as $vl){
                $moneycount = $vl['num'] * $vl['preferential_price'];
                $payinfo = $this->payifno($checkpay,$moneycount);
                if($payinfo['code']!=200){
                    return $payinfo;
                }
                $amount += $payinfo['money'];
                $point += $payinfo['funfen'];
                $ip = _get_ip();
                $ip_region = _get_ip_region(3);
                $uid = $userInfo['uid'];
                $dingdancode = pay_get_dingdan_code('Y');
                $time = time();
                $data = [
                    "code"=>$dingdancode,      //订单号
                    "username"=>$userInfo['username'],  //用户名
                    "uphoto"=>$userInfo['img'],//用户头像
                    "uid"=>$userInfo['uid'],//用户id
                    "shopid"=>$vl['shopid'],//商品id
                    "shopname"=>$vl['title'],//商品名
                    "gonumber"=>$vl['num'],//购买数量
                    "moneycount"=>$moneycount,//订单总价
                    "pay_type"=>"账户",//支付方式
                    "ip"=>$ip,//
                    "ip_region"=>$ip_region,//
                    "status"=>"2",//订单状态 1待支付，2已付款
                    "time"=>$time,//购买时间
                    "money"=>$payinfo['money'],
                    "point"=>$payinfo['funfen'],
                ];
                $sql = Assemblydata($data,"orders","in");
                $res = $this->db->Query($sql);
                //添加用户经验
                $jingyan = $userInfo['jingyan'] + $fufen_config['z_shoppay'];
                $query_jingyan = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");	//经验值
                if($payinfo['funfen']){
                    $query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '积分', '购买商品', '{$payinfo['funfen']}', '$time')");
                }else{
                    //添加积分
                    $mygoscore = $fufen_config['f_shoppay']*$vl['num'];
                    $mygoscore_text =  "购买了{$vl['num']}人次商品";
                    $myscore = $userInfo['score'] + $mygoscore;
                    $query_add_fufen_1 = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
                    $query_add_fufen_2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
                }
                if($payinfo['money']){
                    $query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '账户', '购买商品', '{$payinfo['money']}', '$time')");
                }
                
            }

            $sql = "update `@#_member` set `money` = `money` - $amount ,`score` = `score` - $point where uid = ".$userInfo['uid'];
            $res = $this->db->Query($sql);
        }
        return ["code"=>200,'msg'=>"ok"];
    }

    public function go_pays() {
        $cart_id = $_POST['cart_id'];
        $cart_id = trim($cart_id,',');
        $uid = $this->uId;
        $cartList = $this->db->GetList("select shopsid as shopid,num,`status` from `@#_cart` where id in($cart_id) and uid = $uid");
        if(!$cartList){
            $this->jsonData->error('','查询购物车失败');
        }
        foreach ($cartList as $k=>$v){
            $cartList[$k]['num'] = (int)$v['num'];
        }

        $result = $this->pay_comment($uid,$cartList,1,$_POST['aid'],$_POST['message']);
        if($result['code'] == 1){
            $sql = $this->db->Query("delete from `@#_cart` where id in($cart_id)");
            $this->jsonData->success('',$result['msg'],$result['data']);
        }else{
            $this->jsonData->error('',$result?$result['msg']:'服务异常请稍后再试',$result);
        }
    }

    //普通商品购买并支付
    public function pt_pay() {
        $cart_id = $_POST['cart_id'];
        $pay_id = $_POST['pay_id'];
        $cart_id = trim($cart_id,',');
        $uid = $this->uId;
        $cartList = $this->db->GetList("select shopsid as shopid,num,`status`,price from `@#_cart` where id in($cart_id) and uid = $uid");
        if(!$cartList){
            $this->jsonData->error('','查询购物车失败');
        }
        foreach ($cartList as $k=>$v){
            $cartList[$k]['num'] = (int)$v['num'];
        }
        $result = $this->pay_comment($uid,$cartList,2,0,'');
        if($result['code'] == 1){
            $payment = $this->db->GetOne ( "select * from `@#_pay` where `pay_id` = ".$pay_id );
            $pay_type_bank = $payment['pay_class'];
            $userinfo = $this->userInfo(2);
            $pay = System::load_app_class('pay','pay');

            $pay->pay_type_bank = $pay_type_bank;
            $ok = $pay->init($uid, $pay_id, 'addmoney_record', $cartList[0]['price'], $userinfo['level'],$result['data'], 'app');
            $sql = $this->db->Query("delete from `@#_cart` where id in($cart_id)");
            $this->jsonData->successCode('','成功',$ok);
        }else{
            $this->jsonData->error('',$result?$result['msg']:'服务异常请稍后再试',$result);
        }
    }

    //发送购买请求
    public function pay_comment($uid,$cartList,$type,$aid,$message) {
        $signkey = 'REDACTED_CONFIGURE_LOCALLY';
        $data = [
            'uid' =>$uid,
            'cart' => $cartList,
            'time' => time(),
            'key' => md5(time()),
        ];
        $signKey = http_build_query($data).$signkey;
        $sign = hash("sha256", $signKey);

        $data['salt'] = $sign;
        $data['type'] = $cartList[0]['status'];
        $data['aid'] = $aid;
        $data['message'] = $message;
        $data['paytype'] = $type;

        $rs = $this->db->GetOne("select `value` from `@#_config` where `name` = 'post_url'");
        $url = $rs['value']."/pay_action";
        return $this->posturl($url,$data);
    }


    function posturl($url,$data) {
        $data  = json_encode($data);
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output,true);
    }

    public function recharge_discount(){
        $uid = $this->uId;
        $discount = $this->getConfig('recharge_discount');
        if(!$discount){
            $this->jsonData->success(200,'ok',['discount' => 1]);
        }
        $discount = unserialize($discount);
        if($discount['status'] != 1){
            $this->jsonData->success(200,'ok',['discount' => 1]);
        }
        $member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = $uid");
        //查询是否为注册当天
        $timestamp = time();
        $h = $discount['time'];
        $time = strtotime("+$h hours", $member['time']);
        $days = get_before_time(0);
        //充值记录
        $rech = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = $uid and status = '已付款'");
        $day = $day_diff = 0;
        $info = $this->db->GetOne("SELECT * FROM `@#_recharge_discount` where uid = $uid");
        if($rech){
            if($member['time']>$days[0] && $member['time']<$days[1]){  //注册当天
                if($time>$timestamp){
                    $dis = $discount['today_1'];
                    $m = "today_1";
                }else{
                    $dis = $discount['today_2'];
                    $m = "today_1";
                }
            }else{
                $day = $this->get_recharge_day();
                if($day>0){
                    if($day > count($discount['add'])){
                        $dis = end($discount["add"]);
                    }else{
                        $dis = $discount['add'][$day];
                    }
                    $m = "add";
                }else{
                    $rech = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = $uid and status = '已付款' order by id desc limit 1");
                    $day_diff = intval(($timestamp - $rech['time'])/86400);

                    if($info){
                        $dis = $info['discount'] + $day_diff * $discount['reduce'];
                        if($dis>$discount["min"]){
                            $dis = $discount["min"];
                        }
                        $m = "reduce";
                    }else{
                        $dis = $discount['min'];
                        $m = "min";
                    }
                }
            }
        }else{
            if($time>$timestamp){
                $dis = $discount['first']; //首次
                $m = "first";
            }else{
                $dis = $discount['timeout']; //超时
                $m = "timeout";
            }
        }
        if($info){
            if($info['last_time']< strtotime(date('Y-m-d')." 00:00:00")){
                $data = [
                    "discount" => $dis,
                    "last_time" => $timestamp,
                    "day" => $day?:-$day_diff,
                ];
                $sql = Assemblydata($data,"recharge_discount","up",[["uid",$uid]]);
                $res = $this->db->Query($sql);
            }
        }else{
            $data = [
                "uid" => $uid,
                "discount" => $dis,
                "last_time" => $timestamp,
                "day" => $day?:-$day_diff,
            ];
            $sql = Assemblydata($data,"recharge_discount");
            $res = $this->db->Query($sql);
        }
        $arr = [
            "first"=>$this->lgs['recharge_discount_first'],  //首次
            "today_1"=>$this->lgs['recharge_discount_today'],  //当天
            "timeout"=>$this->lgs['recharge_discount_first'],  //当天2
            "today_2"=>$this->lgs['recharge_discount_today'],  //首次2
            "add"=>$this->lgs['recharge_discount_add'],  //累充折扣
            "reduce"=>$this->lgs['recharge_discount_reduce'], //中断折扣
            "min"=>$this->lgs['recharge_discount_min'],  //最低折扣
        ];

        $this->jsonData->success(200,'ok',['discount' => $dis/100,"name"=>$arr[$m]]);
    }

    /**
     * 获取连续充值天数
     * @return 
     */
    public function get_recharge_day(){
        $uid = $this->uId;
        $discount = $this->getConfig('recharge_discount');
        $discount = unserialize($discount);
        $day = 0;
        for($i = 1; $i < count($discount['add']); $i++){
            $days = get_before_time($i,2);
            $start = $days[0];
            $end = $days[1];
            $rech = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = $uid and status = '已付款' and `time` > '$start' and `time` < '$end'");
            if(!$rech){
                break;
            }
            $day++;
        }
        return $day;
    }

    // 充值
    public function addmoney() {
        $money = safe_replace($_GET['money'])?:safe_replace($_POST['money'],true); // 获取充值金额
        $pay_id = safe_replace($_GET['pay_id'])?:safe_replace($_POST['pay_id']); // 获取选择的支付方式
        $coupon_id = safe_replace($_GET['coupon_id'])?:safe_replace($_POST['coupon_id']); // 获取选择的支付方式
        $pay_input = $_POST;
        $userinfo = $this->userInfo();
        if(!$userinfo){
            $this->jsonData->error(600, $this->lgs['please_log_operate']);
        }
        $uid = $userinfo['uid'];

        $payment = $this->db->GetOne ( "select * from `@#_pay` where `pay_id` = ".$pay_id );
        if (!$payment) {
            // $this->jsonData->errCode('','对不起，没有您所选择的支付方式！');
            $this->jsonData->errCode('',$this->getLgMsg("cart_addmoney_nopayment","controller_cart"));
        }
        if($coupon_id){
            $coupon = $this->db->GetOne("SELECT * FROM `@#_member_coupon` WHERE `id` = $coupon_id and `uid` = $uid");
            if(!$coupon){
                $this->jsonData->errCode('',"对不起，优惠券未找到！");
            }else{
                //已过期
                if($coupon['end_time']<time()){
                    $this->jsonData->errCode('',"对不起，优惠券已过期！");
                }
                // 已使用
                if($coupon['used']!=1){
                    $this->jsonData->errCode('',"对不起，优惠券已使用！");
                }
                //类型不对
                if($coupon['model']!=1){
                    $this->jsonData->errCode('',"对不起，该优惠券不适用于此充值方式！");
                }
            }
        }else{
            $coupon = false;
        }
        $paymentarr = @unserialize($payment['pay_monerange']);

        if ($money > $paymentarr['range']['max']){
            $max_money = $paymentarr['range']['min'];
            $this->jsonData->errCode('',"充值金额必须小于或等于 $max_money 元");
        }

        if ( $money < $paymentarr['range']['min']){
            $min_money = $paymentarr['range']['min'];
            // $this->jsonData->errCode('',"充值金额必须大于或等于 $min_money 元");
            $this->jsonData->errCode('',$this->getLgMsg("cart_addmoney_moneygre","controller_cart"));
        }

        if (! empty ( $payment )) {
            $pay_type_bank = $payment ['pay_class'];
        }
        $pay_type_id = $pay_id;
        $pay = System::load_app_class ( 'pay', 'pay' );
        $pay->pay_type_bank = $pay_type_bank;
        //优惠券
        if($coupon){
            if($coupon['type']==1){
                $real_money = bcsub($money,$coupon['money'],2);
            }else{
                $discount = $coupon['discount']>1?$coupon['discount']/100:$coupon['discount'];
                $real_money = bcmul($money,$discount,2);
            }
        }else{
            // 折扣
            $discountInfo = $this->getConfig('recharge_discount');
            if(!$discountInfo){
                $discount = 1;
            }
            $discount = unserialize($discount);
            if($discount['status'] != 1){
                $discount = 1;
            }else{
                $info = $this->db->GetOne("SELECT * FROM `@#_recharge_discount` where uid = $uid");
                if($info){
                    $discount = $info['discount']/100;
                }else{
                    $discount = 1;
                }
            }
            $real_money = bcmul($money,$discount,2);
        }
        $pay->pay_input_set($pay_input);
        $ok = $pay->init ( $uid, $pay_type_id, 'addmoney_record', $money, $userinfo['level'],$real_money,$coupon_id);
        if($ok['pay_status_code']==1){
            $sql = Assemblydata(['used'=>2],"member_coupon","up",[["id",$coupon_id]]);
            $this->db->Query($sql);
        }
        exit;
        p($ok);exit;
        if ($ok === 'not_pay') {
            // $this->jsonData->errCode('',"未选择支付平台");
            $this->jsonData->errCode('',$this->getLgMsg("cart_addmoney_noplat","controller_cart"));
        }
    }

    /**
     * 充值订单查询
     *
     * @return void
     */
    public function pay_query(){
        $pay_input = $_POST['code'];
        
    }
    /**
     * 优惠购
     */
    public function go_pay_preferential(){
        $is_preferential = $this->getConfig('is_preferential')?:1;
        if($is_preferential != 1){
            $this->jsonData->errCode('',$this->getLgMsg("preferential_not_open","cart_pay")?:"优惠购未开启");
        }
    	System::load_app_fun("pay","pay");
        $checkpay = safe_replace($_POST['checkpay']);//支付方式
        $money = safe_replace($_POST['money'],true);
        $shopid = safe_replace($_POST['shopid']);//商品id
        $gonumber = intval($_POST['gonumber'])?:1; //商品数量
        $time = time();
        $userInfo = $this->userInfo();
        $shop = $this->db->GetOne("select  * from `@#_shoplist` where id = $shopid");
        if(!$shop){
            $this->jsonData->errCode('',$this->getLgMsg("mobile_item_noshop","controller_mobile")?:"商品不存在");
        }
        $moneycount = bcmul($shop['preferential_price'],$gonumber);
        if($money != $moneycount){
            $this->jsonData->errCode('',$this->getLgMsg("mobile_rulelist_noparam","controller_mobile")?:"商品信息有误");
        }
        //获取购买前的账户信息
        $userInfo_before = $this->userInfo();
        if(!$userInfo_before){
            $this->jsonData->error('','获取用户信息失败');
        }
        //支付信息
        $payinfo = $this->payifno($checkpay,$moneycount);
        if($payinfo['code']!=200){
            $this->jsonData->error('',$payinfo['msg']);
        }
        $amount = $payinfo['money'];
        $point = $payinfo['funfen'];

        $fufen_config = System::load_app_config("user_fufen",'','member');
        //订单信息
        $ip = _get_ip();
        $ip_region = _get_ip_region(3);
        $uid = $userInfo['uid'];
        $dingdancode = pay_get_dingdan_code('Y');
        $time = time();
        $data = [
                    "code"=>$dingdancode,      //订单号
                    "username"=>$userInfo['username'],  //用户名
                    "uphoto"=>$userInfo['img'],//用户头像
                    "uid"=>$userInfo['uid'],//用户id
                    "shopid"=>$shopid,//商品id
                    "shopname"=>$shop['title'],//商品名
                    "gonumber"=>$gonumber,//购买数量
                    "moneycount"=>$moneycount,//订单总价
                    "price"=>$shop['preferential_price'],//订单单价
                    // "thumb"=>$shop['thumb'],//商品图片
                    "pay_type"=>"账户",//支付方式
                    "ip"=>$ip,//
                    "ip_region"=>$ip_region,//
                    "status"=>"2",//订单状态 1待支付，2已付款
                    "time"=>$time,//购买时间
                    "money"=>$payinfo['money'],
                    "point"=>$payinfo['funfen'],
        ];
        $this->db->Autocommit_start();
        $sql = Assemblydata($data,"orders","in");
        $res[] = $this->db->Query($sql);
        //添加用户经验
        $jingyan = $userInfo['jingyan'] + $fufen_config['z_shoppay'];
        $res[] = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");	//经验值
        if($payinfo['funfen']){
            $res[] = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '积分', '购买商品', '{$payinfo['funfen']}', '$time')");
        }else{
            //添加积分
            $mygoscore = $fufen_config['f_shoppay']* $shop['preferential_price'] *$gonumber;
            $mygoscore_text =  "购买了{$gonumber}优惠商品";
            $myscore = $userInfo['score'] + $mygoscore;
            $res[] = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
            $res[] = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
        }
        if($payinfo['money']){
            $res[] = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '账户', '购买商品', '{$payinfo['money']}', '$time')");
        }
                
            $sql = "update `@#_member` set `money` = `money` - $amount ,`score` = `score` - $point where uid = ".$userInfo['uid'];
            $res[] = $this->db->Query($sql);
        foreach($res as $vl){
            if(!$vl){
                $this->db->Autocommit_rollback();
                $this->jsonData->error('',$this->getLgMsg("pay_fail","cart_pay")?:"购买失败");
                exit;
            }
        }
        //佣金
        if($payinfo['money']>0){
            pay_go_yongjin($uid,$dingdancode,2);
        }
        $this->db->Autocommit_commit();
        // 获取购买后的账户信息
        $userInfo_after = $this->userInfo();
        $info = [
            'before' => ["money"=>$userInfo_before['money'],"score"=>$userInfo_before['score']],
            'after'  => ["money"=>$userInfo_after['money'],"score"=>$userInfo_after['score']],
        ];
        $this->jsonData->success('',$this->getLgMsg("pay_success","cart_pay")?:"购买成功",["userInfo"=>$info]);
    }


    /**
     * 
     * 拼团支付
     * @return void
     */
    public function go_pay_group(){
        $is_group = $this->getConfig('is_group')?:1;
        if($is_group != 1){
            $this->jsonData->errCode('',"拼团未开启");
        }
    	System::load_app_fun("pay","pay");
        //余额支付c:2,b:1,积分支付：c:1,b:1,混合支付：c:4,b:1
        $checkpay = safe_replace($_POST['checkpay']);//支付方式
        // $banktype = safe_replace($_POST['banktype']);
        $money = safe_replace($_POST['money'],true);
        $shopid = safe_replace($_POST['shopid']);//商品id
        $pid = safe_replace($_POST['pid'])?:-1; //主订单id
        $group_num = intval($_POST['group_num']); //人数
        $day_num = safe_replace($_POST['day_num'])?:3; //天数
        //时间
        $time = time();
        $nextDay = strtotime("+$day_num day", $time); // 将当前时间戳加n天
        $end_time = date('Y-m-d H:i:s', $nextDay);
        // 获取购买数量
        $userInfo = $this->userInfo();

        if($pid==-1){
            $shop = $this->db->GetOne("select * from `@#_shoplist` where id = '$shopid'");
            if(!$shop){
                $this->jsonData->error('',"商品未找到");
            }
            if($shop['groups']==''){
                $this->jsonData->error('',"此商品未开启拼团");
            }
            //购买数量 = 总人数/拼团人数 主订单向上取整
            $gonumber = ceil($shop['zongrenshu'] / $group_num);
            $order = $this->db->GetOne("select id from `@#_orders_group` where uid='{$userInfo['uid']}' and shopid = '$shopid' and group_status = 1 and pid = 0");
            if($order){
                $this->jsonData->error('',"已有拼团");
            }
        }else{
            $porder = $this->db->GetOne("select * from `@#_orders_group` where id = $pid and group_status = 1 and pid = 0");
            if(!$porder){
                $this->jsonData->error('',"未找到拼团订单");
            }
            $fids = $this->friendIds(2);
            if($porder['uid'] == $this->uId){
                $this->jsonData->error('',"不能加入自己的团");
            }
            if(!in_array($porder['uid'],$fids)){
                $this->jsonData->error('',"他不是你的好友");
            }
            if(strtotime($porder['end_time']) < time()){
                $this->jsonData->error('',"拼团时间已过期");
            }
            $shopid = $porder['shopid'];
            $shop = $this->db->GetOne("select * from `@#_shoplist` where id = '{$shopid}'");
            $end_time = $porder['end_time'];
            $group_num = $porder['group_num'];
            $gonumber = intval($shop['zongrenshu'] / $group_num);
            $p = $this->db->GetOne("select * from `@#_orders_group` where uid='{$userInfo['uid']}' and pid = $pid and group_status = 1");
            if($p){
                $this->jsonData->error('',"已加入拼团");
            }
        }
        $price = $shop['money']/$group_num;
        if($price != $money){
            $this->jsonData->error('',"商品信息有误，请刷新重试");
        }
        
        $time1 = date('Y-m-d H:i:s');

        //获取购买前的账户信息
        $userInfo_before = $this->userInfo();
        if(!$userInfo_before){
            $this->jsonData->error('','获取用户信息失败');
        }

        //支付信息
        $payinfo = $this->payifno($checkpay,$price);
        if($payinfo['code']!=200){
            $this->jsonData->error('',$payinfo['msg']);
        }
        $amount = $payinfo['money'];
        $point = $payinfo['funfen'];

        $u_req = $o_req = $e_req = $f_req = $f1_req = $c_req = $c1_req = true;
        $this->db->Autocommit_start();

        $code = pay_get_dingdan_code('P');
        $ip = _get_ip();
        $ip_region = _get_ip_region(3);

        //购买的云购码
        $count = $this->db->GetOne("select sum(gonumber) as count from `@#_orders_group` where id = $pid or pid = $pid");
        if($count['count'] >= $shop['zongrenshu']){
            $this->jsonData->error('',"拼团人数已满");
        }
        $goucode = pay_group_codes($gonumber,$shop,$count['count']);
        $data = [
            "code"=>$code,
            "username"=>$userInfo['username'],  //用户名
            "uphoto"=>$userInfo['img'],//用户头像
            "uid"=>$userInfo['uid'],//用户id
            "shopid"=>$shop['id'],//商品id
            "shopname"=>$shop['title'],//商品名
            "gonumber"=>$gonumber,//购买数量
            "goucode"=>$goucode,
            "ordercode_table"=>$shop['codes_table'],
            "moneycount"=>$price,
            "pay_type"=>"账户",
            "ip"=>$ip,//
            "ip_region"=>$ip_region,//
            "status"=>"2",//订单状态 1待支付，2已付款
            "time"=>$time,//购买时间
            "money"=>$money,
            "point"=>$point,
            "group_num"=>$group_num,
            "end_time"=>$end_time,
            "pid"=>$pid==-1?0:$pid,
            "group_status"=>1,
        ];
        $sql = Assemblydata($data,"orders_group","in");
        $o_req = $this->db->Query($sql);
        // 进行支付
        $sql = "update `@#_member` set `money` = `money` - $amount ,`score` = `score` - $point where uid = ".$userInfo['uid'];
        $u_req = $this->db->Query($sql);
        //添加用户经验
        $uid = $this->uId;
        $fufen_config = System::load_app_config("user_fufen",'','member');
        $jingyan = $userInfo['jingyan'] + $fufen_config['z_shoppay'];
        $e_req = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");	//经验值
        if($payinfo['funfen']){
            $f_req = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$code}', '-1', '积分', '购买商品', '{$payinfo['funfen']}', '$time')");
        }else{
            //添加积分
            $mygoscore = $fufen_config['f_shoppay'] * $gonumber;
            $mygoscore_text =  "购买了{$gonumber}人次商品";
            $myscore = $userInfo['score'] + $mygoscore;
            $f1_req = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
            $c_req = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
        }
        if($payinfo['money']){
            $c1_req = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$code}', '-1', '账户', '购买商品', '{$payinfo['money']}', '$time')");
        }
        if($u_req && $o_req && $e_req && $f_req && $f1_req && $c_req && $c1_req){
            $this->db->Autocommit_commit();
            if(!$pid){
                $ss = $this->db->GetOne("select * from `@#_orders_group` where `code` = '$code'");
                $pid = $ss['id'];
            }
            $count = $this->db->GetOne("select sum(gonumber) as count,count(*) as num from `@#_orders_group` where id = $pid or pid = $pid");
            $this->db->Query("update `@#_orders_group` set `join_num` = {$count['num']} where id = $pid or pid = $pid");
            if($count['count']>= $shop['zongrenshu']){
                $code = get_huode_code($shop);
                $time=sprintf("%.3f",microtime(true)+(int)System::load_sys_config('system','goods_end_time'));
                $time = date("Y-m-d H:i:s",$time);
                $res = $this->db->GetOne("select * from `@#_orders_group` where (id = $pid or pid = $pid) and goucode like '%$code%'");
                $r1 = $this->db->Query("update `@#_orders_group` set `huode` = '$code',`q_end_time` = '$time' where id = '{$res['id']}'");
                $r2 = $this->db->Query("update `@#_orders_group` set `group_status` = '2',`q_end_time` = '$time' where id = $pid or pid = $pid");
                if($r1 && $r2){
                    //佣金
                    $list = $this->db->GetList("select * from `@#_orders_group` where id = $pid or pid = $pid");
                    foreach($list as $vl){
                        if($vl['money']>0){
                            pay_go_yongjin($vl['uid'],$vl['code'],3);
                        }
                    }
                    $this->db->Autocommit_commit();
                }else{
                    $this->db->Autocommit_rollback();
                    $r2 = $this->db->Query("update `@#_orders_group` set `group_status` = '3' where id = $pid or pid = $pid");
                }
            }
             // 获取购买后的账户信息
             $userInfo_after = $this->userInfo();
             $info = [
                 'before' => ["money"=>$userInfo_before['money'],"score"=>$userInfo_before['score']],
                 'after'  => ["money"=>$userInfo_after['money'],"score"=>$userInfo_after['score']],
             ];
            $this->jsonData->success('',"购买成功",["userInfo"=>$info]);
        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('',"购买失败");
        }

    }

    //购买积分商品
    public function go_pay_jifen(){
        $checkpay = safe_replace($_POST['checkpay']);//支付方式
        $orderid = htmlspecialchars($_POST['orderid']);
        $userInfo = $this->userinfo;
        $uid = $this->uId;
        $order = $this->db->GetOne("select * from `@#_member_goods_order` where `id` = '$orderid' and uid = $uid");
        if(!$order){
            $this->jsonData->error('',"订单未找到");
        }
        $dingdancode = $order['code'];
        $money = $order['money'];
        $score = $order['score']?:0; //获取赠送积分
        $payinfo = $this->payifno($checkpay,$money);
        if($payinfo['code']!=200){
            $this->jsonData->error('',$payinfo['msg']);
        }
        $time = time();
        $fufen_config = System::load_app_config("user_fufen",'','member');
        $jingyan = $userInfo['jingyan'] + $fufen_config['z_shoppay'];
        $this->db->Autocommit_start();
        if($payinfo['funfen']){
            $sql = "INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '积分', '购买商品', '{$payinfo['funfen']}', '$time')";
            $res['sql'][] = $sql;
            $res["res"][] = $this->db->Query($sql);
            $this->db->Query("update `@#_member` set `score` = `score` - '{$payinfo['funfen']}'  where uid = ".$uid);
        }
        if($payinfo['money']){
            //记录支付金额
            $sql = "INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '-1', '账户', '购买商品', '{$money}', '$time')";
            $res['sql'][] = $sql;
            $res["res"][] = $this->db->Query($sql);
            $this->db->Query("update `@#_member` set `money` = `money` - '{$payinfo['money']}'  where uid = ".$uid);
        }
        if($score>0){
            //记录获得的积分
            $sql = "INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$dingdancode}', '1', '积分', '购买商品', '{$score}', '$time')";
            $res['sql'][] = $sql;
            $res["res"][] = $this->db->Query($sql);
            $this->db->Query("update `@#_member` set `score` = `score` + $score  where uid = ".$uid);
        }
        //更新用户金额，积分，经验
        $sql = "update `@#_member` set `jingyan`='$jingyan' where uid = ".$uid;
        $res['sql'][] = $sql;
        $res["res"][] = $this->db->Query($sql);
        $sql = "update `@#_member_goods_order` set remoney = $money,status = 2,suctime = $time where id = $orderid";
        $res['sql'][] = $sql;
        $res["res"][] = $this->db->Query($sql);
		//1.存入已支付订单
        $typenum = $order['typenum'];
        $paytype = $order['paytype'];
        $aid = $order['aid'];
        $message = $order['message'];
        $cretime = $order['cretime'];
        $suctime = $uptime = $time;
		$sql = "INSERT INTO `@#_member_pay_order`(`oid`,`code`,`uid`,`typenum`,`money`,`remoney`,`score`,`paytype`,`aid`,`message`,`cretime`,`suctime`,`uptime`) VALUES ('$orderid','$dingdancode','$uid','$typenum','$money','$money','$score','$paytype','$aid','$message','$cretime','$suctime','$uptime')";
        $res['sql'][] = $sql;
		$res["res"][] = $this->db->Query($sql);

    	System::load_app_fun("pay","pay");
        pay_go_yongjin($uid,$dingdancode,4);
        foreach($res['res'] as $key=>$vl){
            if(!$vl){
                $this->db->Autocommit_rollback();
                $this->jsonData->error('',"失败",["i"=>$key,"sql"=>$res['sql'][$key]]);
            }
        }
        $this->db->Autocommit_commit();
        $this->jsonData->success('',"购买成功");
    }

    public function getBank(){
        $sql = Assemblydata([],"bank","sel");
        $num=20;
        if(isset($_POST['p'])){$pagenum=$_POST['p'];}else{$pagenum=1;}
        $list = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        foreach($list as &$vl){
            $vl['logo_url'] = G_UPLOAD_PATH."/".$vl['logo'];
        }
        data_format($list,['update_time'],"times");
        $this->jsonData->success('',"",$list);
    }
}