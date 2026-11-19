<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_fun("pay", "pay");
System::load_sys_fun("user");
System::load_app_class("tocode", "pay", 'no');
class pay
{
    private $db;
    private $members;        //会员信息
    private $MoenyCount;     //商品总金额
    private $shops;         //商品信息
    private $pay_type;        //支付类型
    private $fukuan_type;    //付款类型 买商品 充值
    private $dingdan_query = true;    //订单的	mysql_qurey 结果
    public $pay_type_bank = false;

    public $scookie = null;
    public $fufen = 0;
    public $fufen_to_money = 0;
    public $coupon_id = 0;
    private $pay_input = [];
    private $getScore_num = 0;//赠送的订单积分

    private $member_coupon_id = 7; // 用户使用优惠卷id号
    private $coupon_money = 0; // 优惠金额
    private $coupon_money_record = 0; // 优惠金额记录
    private $payType = ""; // 支付类型
    private $couponList = []; // 优惠卷列表
    private $isCoupon = true; // 判断是否使用优惠卷 true 没有使用 false 使用

    //初始化类数据
    //$addmoney 充值金额
    public function init($uid = null, $pay_type = null, $fukuan_type = '', $addmoney = '', $vip = 0, $real_money = 0)
    {
        // echo 1111;exit;
        $this->db = System::load_sys_class('model');
        $this->db->Autocommit_start();

        // 用户使用优惠卷id号
        $member_coupon_id = _getcookie("conpuid" . $uid );
        if ($member_coupon_id > 0){
            $this->member_coupon_id = $member_coupon_id;
        }

        $this->members = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid' for update");
        if ($this->pay_type_bank) {
            $pay_class = $this->pay_type_bank;
            $this->pay_type = $this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = '$pay_class' and `pay_start` = '1'");
            $this->pay_type['pay_bank'] = $pay_type;
        }
        if (is_numeric($pay_type)) {
            $this->pay_type = $this->db->GetOne("SELECT * from `@#_pay` where `pay_id` = '$pay_type' and `pay_start` = '1'");
            $this->pay_type['pay_bank'] = 'DEFAULT';
        }

        $this->fukuan_type = $fukuan_type;
        if ($fukuan_type == 'go_record') {
            // 购买商品时获取支付类型
            $this->payType = _getcookie("checkpay" . $uid);
            return $this->go_record();
        }
        if ($fukuan_type == 'addmoney_record') {
            return $this->addmoney_record($addmoney, null, $vip, $real_money);
        }
        return false;
    }

    public function pay_input_set($pay_input)
    {
        $this->pay_input = $pay_input;
    }

    //买商品
    private function go_record()
    {
        $fufen_config = System::load_app_config("user_fufen", '', 'member'); //积分配置
        if($fufen_config['fufen_yuan']){
            $fufen_yuan = $fufen_config['fufen_yuan'];
        }else{
            $fufen_yuan = 0;
        }
        if (is_array($this->scookie)) {
            $Cartlist = $this->scookie;
        } else {
            $Cartlist = json_decode(stripslashes(_getcookie('Cartlist')), true);
        }

        $shopids = '';            //商品ID
        if (is_array($Cartlist)) {
            foreach ($Cartlist as $key => $val) {
                $shopids .= intval($key) . ',';
            }
            $shopids = str_replace(',0', '', $shopids);
            $shopids = trim($shopids, ',');
        }

        $shoplist = array();        //商品信息
        if ($shopids != NULL) {
            $shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update", array("key" => "id"));
        } else {
            $this->db->Autocommit_rollback();
            return '购物车内没有商品!';
        }

        /*******************************优惠卷*****************************************/
        // 判读是否是金额支付
        $coupon = [];
        if ($this->payType == 'money'){
            $uid = $this->members['uid'];
            $time = time();
            $this->couponList = $coupon = $this->db->GetOne("SELECT 
                    mc.uid, 
                    mc.type, 
                    mc.money, 
                    mc.discount, 
                    mc.id, 
                    c.class_coupon_id
                FROM `go_member_coupon` AS mc
                LEFT JOIN `go_coupon` AS c ON c.id = mc.c_id 
                WHERE mc.c_id = '$this->member_coupon_id' 
                  AND mc.uid = '$uid' 
                  AND mc.used = 1 
                  AND mc.start_time <= '$time' 
                  AND mc.end_time >= '$time' 
                  AND c.model = 2");

            if ($coupon['type'] == 1){
                $this->coupon_money_record = $this->coupon_money = $coupon['money'];
            }
        }

        $MoenyCount = 0;
        $shopguoqi = 0;
        $getScore_num = 0;//赠送的积分数量
        $f_money = bcdiv($this->fufen,$fufen_yuan,0);
        if (count($shoplist) >= 1) {
            $scookies_arr = array();
            $scookies_arr['MoenyCount'] = 0;
            foreach ($Cartlist as $key => $val) {
                $key = intval($key);
                if (isset($shoplist[$key]) && $shoplist[$key]['shenyurenshu'] != 0) {
                    if (($shoplist[$key]['xsjx_time'] != '0') && $shoplist[$key]['xsjx_time'] < time()) {
                        unset($shoplist[$key]);
                        $shopguoqi = 1;
                        continue;
                    }
                    $shoplist[$key]['cart_gorenci'] = $val['num'] ? $val['num'] : 1;
                    if ($shoplist[$key]['cart_gorenci'] >= $shoplist[$key]['shenyurenshu']) {
                        $shoplist[$key]['cart_gorenci'] = $shoplist[$key]['shenyurenshu'];
                    }

                    $t_meony = bcmul($shoplist[$key]['yunjiage'], $shoplist[$key]['cart_gorenci'], 2);
                    if($f_money <= 0){
                        $getScore_num += $shoplist[$key]['cart_gorenci'];
                    }elseif($f_money < $t_meony){
                        $f_num = round(bcdiv($f_money, $shoplist[$key]['yunjiage'], 2));
                        $getScore_num = $getScore_num + $shoplist[$key]['cart_gorenci'] - $f_num;
                        $f_money = 0;
                    }else{
                        $f_money -= $t_meony;    
                    }

                    /**************************************优惠卷*********************************************/
                    /*$isCoupon = true;
                    // 判断是否使用优惠卷
                    if ($this->payType == 'money' && !empty($coupon)) {
                        // 检查分类是否匹配
                        if (!empty($coupon['class_coupon_id']) && $coupon['class_coupon_id'] != $shoplist[$key]['cateid']) {
                            $isCoupon = false;
                        }

                        if ($isCoupon){
                            //  商品金额
                            $goodsMoney = $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];
                            // 分类匹配，进行价格计算
                            if ($coupon['type'] == 1) {
                                $this->save_log("1金额");

                                // 金额是否大于优惠金额
                                if ($this->coupon_money > 0) {
                                    // 优惠金额
                                    if ($this->coupon_money >= $goodsMoney) {
                                        // 如果大于商品金额就扣除剩余金额
                                        $this->coupon_money -= $goodsMoney;
                                        $isCoupon = false;
                                    }elseif ($this->coupon_money <= $goodsMoney){
                                        // 如果大于商品金额就扣除剩余金额
                                        $MoenyCount += $goodsMoney - $this->coupon_money;
                                        $isCoupon = false;
                                    }
                                }
                            } else {
                                $this->save_log("2百分比");
                                // 百分比优惠
                                $MoenyCount += $goodsMoney * $coupon['discount'] / 100;
                                $isCoupon = false;
                            }
                        }

                    }

                    if ($isCoupon){
                        $MoenyCount += $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];
                    }*/

                    //原始方法
                    $MoenyCount += $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci'];

                    $shoplist[$key]['cart_xiaoji'] = substr(sprintf("%.3f", $shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci']), 0, -1);
                    $shoplist[$key]['cart_shenyu'] = $shoplist[$key]['zongrenshu'] - $shoplist[$key]['canyurenshu'];
                    $scookies_arr[$key]['shenyu'] = $shoplist[$key]['cart_shenyu'];
                    $scookies_arr[$key]['num'] = $shoplist[$key]['cart_gorenci'];
                    $scookies_arr[$key]['money'] = intval($shoplist[$key]['yunjiage']);
                    $scookies_arr['MoenyCount'] += intval($shoplist[$key]['cart_xiaoji']);
                } else {
                    unset($shoplist[$key]);
                }
            }
            if (count($shoplist) < 1) {
                $scookies_arr = '0';
                $this->db->Autocommit_rollback();
                if ($shopguoqi) {
                    return '限时获得过期商品不能购买!';
                } else {
                    return '购物车里没有商品!';
                }
            }
        } else {
            $scookies_arr = '0';
            $this->db->Autocommit_rollback();
            return '本期商品已销售完毕';
        }

        $this->getScore_num = $getScore_num;
        $this->MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);
        $this->save_log($this->MoenyCount);
        /**
         *	最多能抵扣多少钱
         **/
        if ($this->fufen) {
            if ($this->fufen >= $this->members['score']) {
                $this->fufen = $this->members['score'];
            }

            if ($fufen_config['fufen_yuan']) {
                $this->fufen_to_money  = intval($this->fufen / $fufen_config['fufen_yuan']);
                if ($this->fufen_to_money >= $this->MoenyCount) {
                    $this->fufen_to_money = $this->MoenyCount;
                    $this->fufen = $this->fufen_to_money * $fufen_config['fufen_yuan'];
                }
            } else {
                $this->fufen_to_money = 0;
                $this->fufen = 0;
            }
        } else {
            $this->fufen_to_money = 0;
            $this->fufen = 0;
        }

        //总支付价格 总价格会在创建订单时重置
        $this->MoenyCount = $this->MoenyCount - $this->fufen_to_money;
        $this->shoplist = $shoplist;
        $this->scookies_arr = $scookies_arr;
        return 'ok';
    }

    /* 充值 data 其他数据 */
    private function addmoney_record($money = null, $data = null, $vip = 0, $real_money = 0)
    {

        $uid = $this->members['uid'];
        // $web=System::load_sys_config('system');
        $res = $this->db->GetOne("select * from `@#_config` where `name` = 'pay_prefix'");
        $web['pay_prefix'] = $res['value'];
        if (isset($web['pay_prefix']) && $web['pay_prefix']) {
            $pay_prefix = $web['pay_prefix'];
        } else {
            $pay_prefix = 'C';
        }
        $dingdancode = pay_get_dingdan_code($pay_prefix);        //订单号

        if (!is_array($this->pay_type)) {
            return 'not_pay';
        }
        $pay_type = $this->pay_type['pay_class'];
        $time = time();
        if (!empty($data)) {
            $scookies = $data;
        } else {
            $scookies = '0';
        }
        $score = $this->fufen;
        if ($this->coupon_id) {
            $info["coupon_id"] = $this->coupon_id;
            $info = serialize($info);
        } else {
            $info = null;
        }
        $query = $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`,`real_money`,`data`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','$score','$scookies','$real_money','$info')");
        if ($query) {
            $this->db->Autocommit_commit();
        } else {
            $this->db->Autocommit_rollback();
            return false;
        }

        $pay_type = $this->pay_type;

        $paydb = System::load_app_class($pay_type['pay_class'], 'pay');

        $pay_type['pay_key'] = unserialize($pay_type['pay_key']);
        // $money = $money==1000?1:$money;
        $config = array();
        $config['u_id'] = _cfg('pay_prefix') . "_" . $uid;
        $config['id'] = $pay_type['pay_key']['id']['val'];            //支付合作ID
        $config['key'] = $pay_type['pay_key']['key']['val'];        //支付KEY
        $config['shouname'] = _cfg('web_name');                        //收款方
        $config['title'] = _cfg('web_name');                        //付款项目
        // $config['money'] = $vip ? $money : $money;									//付款金额$money
        $config['money'] = $vip ? $real_money : $real_money;                                    //付款金额$real_money
        $config['type']  = $pay_type['pay_type'];                    //支付方式：	即时到帐1   中介担保2
        $config['pay_id'] = $pay_type['pay_id'];                      //支付id
        $config['pay_name'] = $pay_type['pay_name'];                     //支付名称
        $config['uid'] = $this->members['mobile'];                    //用户id
        $config['ReturnUrl']  = G_WEB_PATH . '/index.php/pay/' . $pay_type['pay_class'] . '_url/qiantai';    //前台回调
        $config['NotifyUrl']  = G_WEB_PATH . '/index.php/pay/' . $pay_type['pay_class'] . '_url/houtai';        //后台回调

        $config['pay_bank'] = $this->pay_type['pay_bank'];

        $config['code'] = $dingdancode;
        $config['pay_type_data'] = $pay_type['pay_key'];
        $config['data'] = $pay_type['pay_key'];
        $config['ip'] = _get_ip();

        $config['zxgm'] = '充值';
        $config['pay_input'] = $this->pay_input;
        $res = $paydb->config($config);
        //$paydb->send_pay();

        return $res;
    }
    public function save_log($msg)
    {
        $txt = '【' . date('Y-m-d H:i:s') . '】INFO:' . var_export($msg, true) . PHP_EOL;
        file_put_contents('pay_log/order_info/pay_'.date("Ymd").'.txt', $txt . PHP_EOL, FILE_APPEND);
    }
    //生成订单
    private function set_dingdan($pay_type = '', $dingdanzhui = '')
    {
        $uid = $this->members['uid'];
        $uphoto = $this->members['img'];
        $username = get_user_name($this->members);
        $insert_html = '';
        $this->dingdancode = $dingdancode = pay_get_dingdan_code($dingdanzhui);        //订单号

        if (count($this->shoplist) > 1) {
            $dingdancode_tmp = 1;    //多个商品相同订单
        } else {
            $dingdancode_tmp = 0;    //单独商品订单
        }

        //        $ip = _get_ip_dizhi();
        $ip = _get_ip();
        //订单时间
        $time = sprintf("%.3f", microtime(true));
        $this->MoenyCount = 0;

        /****************************优惠卷*****************************/
       /* foreach ($this->shoplist as $key => $shop) {
            $ret_data = array();
            pay_get_shop_codes($shop['cart_gorenci'], $shop, $ret_data);
            $this->dingdan_query = $ret_data['query'];
            if (!$ret_data['query']) $this->dingdan_query = false;
            $codes = $ret_data['user_code'];                                    //得到的购买码
            $codes_len = intval($ret_data['user_code_len']);                        //得到购买码个数
            $money = $codes_len * $shop['yunjiage'];                                //单条商品的总价格
            $this->MoenyCount += $money;                                        //总价格
            $status = '未付款,未发货,未完成';
            $shop['canyurenshu'] = intval($shop['canyurenshu']) + $codes_len;
            $shop['goods_count_num'] = $codes_len;
            $this->shoplist[$key] = $shop;
            $shop_title = str_replace("'", "\'", $shop['title']);
            $shop_title = str_replace('"', '\"', $shop_title);
            if ($codes_len) {
                $insert_html .= "('$dingdancode','$dingdancode_tmp','$uid','$username','$uphoto','$shop[id]','$shop_title','$shop[qishu]','$codes_len','$money','$codes','$pay_type','$ip','$status','$time','0'),";
            }
        }*/

        foreach ($this->shoplist as $key => $shop) {
            $ret_data = array();
            pay_get_shop_codes($shop['cart_gorenci'], $shop, $ret_data);
            $this->dingdan_query = $ret_data['query'];
            if (!$ret_data['query']) $this->dingdan_query = false;
            $codes = $ret_data['user_code'];                                    //得到的购买码
            $codes_len = intval($ret_data['user_code_len']);                        //得到购买码个数

            /******************************优惠卷*****************************/
            $money = 0;
            // 判断是否使用优惠卷
            if ($this->payType == 'money' && !empty($this->couponList)) {
                $isCouponMoney = true;
                // 检查分类是否匹配
                if (!empty($this->couponList['class_coupon_id']) && $this->couponList['class_coupon_id'] != $this->shoplist[$key]['cateid']) {
                    $isCouponMoney = false;
                }
                if ($isCouponMoney){
                    //  商品金额
                    $goodsMoney = $codes_len * $shop['yunjiage'];
                    // 分类匹配，进行价格计算
                    if ($this->couponList['type'] == 1) {

                        // 金额是否大于优惠金额
                        if ($this->coupon_money > 0) {
                            // 优惠金额
                            if ($this->coupon_money >= $goodsMoney) {
                                // 如果大于商品金额就扣除剩余金额
                                $money += 0;
                                $this->coupon_money -= $goodsMoney;

                                $this->isCoupon = false;
                            }elseif ($this->coupon_money <= $goodsMoney){
                                // 如果大于商品金额就扣除剩余金额
                                $money += $goodsMoney - $this->coupon_money;
                                $this->coupon_money = 0;
                                $this->isCoupon = false;
                            }
                        }
                    } else {
                        // 百分比优惠
                        $money += $goodsMoney * $this->couponList['discount'] / 100;
                        $this->isCoupon = false;
                    }

                }

            }

            if ($this->isCoupon){
                $money += $codes_len * $shop['yunjiage'];
            }

            // 没使用优惠卷金额
            $moneycount = $codes_len * $shop['yunjiage'];

            // 优惠卷修改前方法
//            $money = $codes_len * $shop['yunjiage'];

            //单条商品的总价格
            $this->MoenyCount += $money;
            $status = '未付款,未发货,未完成';
            $shop['canyurenshu'] = intval($shop['canyurenshu']) + $codes_len;
            $shop['goods_count_num'] = $codes_len;
            $this->shoplist[$key] = $shop;
            $shop_title = str_replace("'", "\'", $shop['title']);
            $shop_title = str_replace('"', '\"', $shop_title);
            if ($codes_len) {
                // 优惠卷修改前方法
//                $insert_html .= "('$dingdancode','$dingdancode_tmp','$uid','$username','$uphoto','$shop[id]','$shop_title','$shop[qishu]','$codes_len','$money','$codes','$pay_type','$ip','$status','$time','0'),";
                $insert_html .= "('$dingdancode','$dingdancode_tmp','$uid','$username','$uphoto','$shop[id]','$shop_title','$shop[qishu]','$codes_len','$moneycount','$codes','$pay_type','$ip','$status','$time','0'),";
            }
        }

        //ip
        $ip_region = _get_ip_region(3);
        $insert_html = str_replace("'0')", "'0','$ip_region')", $insert_html);
        $sql = "INSERT INTO `@#_member_go_record` (`code`,`code_tmp`,`uid`,`username`,`uphoto`,`shopid`,`shopname`,`shopqishu`,`gonumber`,`moneycount`,`goucode`,`pay_type`,`ip`,`status`,`time`,`confirm_address`,`ip_region`) VALUES ";
        $sql .= trim($insert_html, ',');
        if (empty($insert_html)) {
            return false;
        }
        //$this->db->Query("set global max_allowed_packet = 2*1024*1024*10");
        //        var_dump($sql);
        $RE = $this->db->Query($sql);
        //        var_dump($RE);exit;
        //file_put_contents('err.txt','$RE---'.var_export($RE,1)."\n",FILE_APPEND);
        return $RE;
    }

    //账户里支付
    private function pay_bag()
    {
        $time = time();
        $uid = $this->members['uid'];
        $fufen_config = System::load_app_config("user_fufen", '', 'member');

        $query_1 = $this->set_dingdan('账户', 'A');

        /*会员购买过账户剩余金额*/
        // $Money = $this->members['money'] - $this->MoenyCount + $this->fufen_to_money;

        $Money = bcsub($this->members['money'], $this->MoenyCount, 2);

        $Money = bcadd($Money, $this->fufen_to_money, 2);

        $actual_money = $this->MoenyCount;

        $point = 0;
        $query_fufen = true;
        $pay_zhifu_name = '账户';
        if ($this->fufen_to_money) {  //积分支付
            $actual_money = $this->MoenyCount - $this->fufen_to_money;
            $point = $this->fufen;
            $myfufen = $this->members['score'] = $this->members['score'] - $this->fufen;
            $query_fufen = $this->db->Query("UPDATE `@#_member` SET `score`='$myfufen' WHERE (`uid`='$uid')");
            $query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '{$this->dingdancode}', '-1', '积分', '购买商品', '{$this->fufen}', '$time')");
        }

        //添加用户经验
        $jingyan = $this->members['jingyan'] + $fufen_config['z_shoppay'];
        $query_jingyan = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");    //经验值

        //更新用户账户金额
        $query_2 = $this->db->Query("UPDATE `@#_member` SET `money`='$Money' WHERE (`uid`='$uid')");            //金额
        $query_3 = $info = $this->db->GetOne("SELECT * FROM  `@#_member` WHERE (`uid`='$uid') LIMIT 1");
        $query_5 = true;
        $query_6 = false;
        $query_insert = true;
        $goods_count_num = 0;
        $dingdancode = $this->dingdancode;
        foreach ($this->shoplist as $shop):
            if ($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']) {
                $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
            } else {
                $sellnum = $this->db->GetOne("select sum(gonumber) as sellnum from `@#_member_go_record` where `shopid` = '$shop[id]'");
                $sellnum = $sellnum['sellnum'];
                $shenyurenshu = $shop['zongrenshu'] - $sellnum;
                $query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$sellnum',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");

                // $shenyurenshu = $shop['zongrenshu'] - $shop['canyurenshu'];
                // $query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$shop[canyurenshu]',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");
                if (!$query) $query_5 = false;
            }
            if ($actual_money > 0) { //余额支付
                $query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `code`, `type`, `pay`, `content`, `money`, `time`,`message`) VALUES ('$uid', '{$this->dingdancode}', '-1', '$pay_zhifu_name', '购买商品', '{$shop['cart_xiaoji']}', '$time','$shop[id]')");
                $query_6 = $this->db->Query("UPDATE `@#_member_go_record` SET `money`='{$shop['cart_xiaoji']}',`point`='$point',`status`='已付款,未发货,未完成' WHERE `code`='$dingdancode' and `uid` = '$uid' and shopid = {$shop['id']}");
            } else {
                $query_4 = true;
            }
            $goods_count_num += $shop['goods_count_num'];
        endforeach;

        //添加积分
        // if (!$this->fufen_to_money) {
        if ($actual_money>0) {
            $g_num = $goods_count_num - $this->getScore_num;
            $mygoscore = $fufen_config['f_shoppay'] * $this->getScore_num;
            $mygoscore_text =  "购买了{$goods_count_num}人次商品";
            if($g_num){
                $mygoscore_text .= ",积分抵扣了{$g_num}人次商品"; 
            }    
            $myscore = $this->members['score'] + $mygoscore;
            $query_add_fufen_1 = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
            $query_add_fufen_2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`,`code`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid','{$this->dingdancode}', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
            $query_fufen = ($query_add_fufen_1 && $query_add_fufen_2);
        }

        if(!$query_6){
            $query_6 = $this->db->Query("UPDATE `@#_member_go_record` SET `money`='{$actual_money}',`point`='$point',`status`='已付款,未发货,未完成' WHERE `code`='$dingdancode' and `uid` = '$uid'");
        }
        $query_7 = $this->dingdan_query;
        $query_8 = $this->db->Query("UPDATE `@#_caches` SET `value`=`value` + $goods_count_num WHERE `key`='goods_count_num'");
        $this->goods_count_num = $goods_count_num;
        if ($query_fufen && $query_jingyan && $query_1 && $query_2 && $query_3 && $query_4 && $query_5 && $query_6 && $query_7 && $query_insert && $query_8) {

            /****************优惠卷***********************/
            //跟新优惠卷状态
            if (!empty($this->couponList) && !$this->isCoupon){
                $couponTime = time();
                if ($this->couponList['type'] == 2){
                    // 判断是否是折扣
                    $this->db->Query("UPDATE `@#_member_coupon` SET `used` = '2' where `id` = {$this->couponList['id']}");

                    $couponSql = "INSERT INTO `@#_member_coupon_log` (`uid`,`code`, `c_id`, `type`, `discount`, `create_time`) VALUES ('{$this->couponList['uid']}','{$this->dingdancode}', {$this->couponList['id']}, {$this->couponList['type']}, {$this->couponList['discount']}, '$couponTime')";
                }elseif ($this->couponList['type'] == 1){
                    // 判断是否是金额
                    if ($this->coupon_money > 0 ){

                        // 如果金额大于0就修改金额
                        $this->db->Query("UPDATE `@#_member_coupon` SET `money` = {$this->coupon_money} where `id` = {$this->couponList['id']}");

                        $couponSql = "INSERT INTO `@#_member_coupon_log` (`uid`,`code`, `c_id`, `type`, `money`, `create_time`) VALUES ('{$this->couponList['uid']}','{$this->dingdancode}',{$this->couponList['id']},  {$this->couponList['type']},{$this->coupon_money}, '$couponTime')";
                    }elseif ($this->coupon_money <= 0 ){
                        // 如果金额小于0就修改状态
                        $this->db->Query("UPDATE `@#_member_coupon` SET `used` = '2',`money` = 0 where `id` = {$this->couponList['id']}");

                        $couponSql = "INSERT INTO `@#_member_coupon_log` (`uid`,`code`, `c_id`, `type`, `money`, `create_time`) VALUES ('{$this->couponList['uid']}','{$this->dingdancode}', {$this->couponList['id']}, {$this->couponList['type']}, 0, '$couponTime')";
                    }
                }
                $this->save_log($couponSql);
                $this->db->Query($couponSql);
            }

            if ($info['money'] == $Money) {
                $this->db->Autocommit_commit();
                foreach ($this->shoplist as $shop):
                    if ($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']) {
                        $this->db->Autocommit_start();
                        if ($shop['ssc']) {
                            $query_insert = pay_insert_shop_ssc($shop, 'add');
                        } else {
                            $query_insert = pay_insert_shop($shop, 'add');
                        }

                        if (!$query_insert) {
                            $this->db->Autocommit_rollback();
                        } else {
                            _send_SMS($info['mobile'], 21, $shop['id']);
                            $this->db->Autocommit_commit();
                        }
                        $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
                    }
                endforeach;
                return true;
            } else {
                $this->db->Autocommit_rollback();
                return false;
            }
        } else {
            $this->db->Autocommit_rollback();
            return false;
        }
    }

    /**
     *	开始支付
     **/
    public function go_pay($pay_checkbox)
    {
        $this->save_log("===开始支付===");
        if ($this->members['money'] >= $this->MoenyCount || $this->fufen_to_money >= $this->MoenyCount) {
            $this->save_log("===进入支付===");
            $uid = $this->members['uid'];
            $pay_1 =  $this->pay_bag();
            if (!$pay_1) {
                return $pay_1;
            }
            $dingdancode = $this->dingdancode;
            $pay_2 = pay_go_fund($this->goods_count_num);
            $pay_3 = pay_go_yongjin($uid, $dingdancode);
            return $pay_1;
        }
        if (!is_array($this->pay_type)) {
            return 'not_pay';
        }
        if (is_array($this->scookies_arr)) {
            $scookie = serialize($this->scookies_arr);
        } else {
            $scookie = '0';
        }
        if ($pay_checkbox) {
            $money = $this->MoenyCount - $this->members['money'];
            return $this->addmoney_record($money, $scookie);
        } else {
            //全额支付
            $this->MoenyCount;
            return $this->addmoney_record($this->MoenyCount, $scookie);
        }
        exit;
    }

    public function pay_user_go_shop($uid = null, $gid = null, &$num = null)
    {
        if (empty($uid) || empty($gid) || empty($num)) {
            return false;
        }
        $uid = intval($uid);
        $gid = intval($gid);
        $num = intval($num);
        $this->db = System::load_sys_class('model');
        $this->db->Autocommit_start();
        $member = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid' for update");
        $goodinfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' and `shenyurenshu` != '0' for update");
        if (!$goodinfo['shenyurenshu']) {
            $this->db->Autocommit_rollback();
            return false;
        }
        if ($goodinfo['shenyurenshu'] < $num) {
            $num = $goodinfo['shenyurenshu'];
        }
        $if_money = $goodinfo['yunjiage'] * $num;
        $this->members = $member;
        $this->MoenyCount = $if_money;
        $goodinfo['goods_count_num'] = $num;
        $goodinfo['cart_gorenci'] = $num;

        $this->shoplist = array();
        $this->shoplist[0] = $goodinfo;

        if ($member && $goodinfo && $member['money'] >= $if_money) {

            $uid = $member['uid'];
            $pay_1 =  $this->pay_bag();
            if (!$pay_1) {
                return $pay_1;
            }
            $dingdancode = $this->dingdancode;
            $pay_2 = pay_go_fund($this->goods_count_num);
            $pay_3 = pay_go_yongjin($uid, $dingdancode);
            return $pay_1;
        } else {
            $this->db->Autocommit_rollback();
            return false;
        }
    }

    /**
     * 提现
     */
    public function withdrawal($cashout)
    {
        if (!$cashout) {
            return false;
        }
        $pay_class = System::load_app_config("user_fufen", "yongjintx_pay", "member");
        if (!$pay_class) {
            return ["pay_status_code" => 2, "errorMsg" => "请设置打款方式"];
        }
        $paydb = System::load_app_class($pay_class, 'pay');
        $this->db = System::load_sys_class('model');
        $pay = $this->db->GetOne("select * from `@#_pay` where pay_class = '$pay_class'");
        $pay['cashout'] = $cashout;
        $pay['NotifyUrl'] = G_WEB_PATH . '/index.php/pay/' . $pay_class . '_url/houtai_withdrawal';        //后台回调
        $pay['data'] = unserialize($pay["pay_key"]);
        if (method_exists($paydb, "withdrawal")) {
            return $paydb->withdrawal($pay);
        } else {
            return false;
        }
    }
}
