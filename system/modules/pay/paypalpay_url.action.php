<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set("display_errors", "OFF");
System::load_app_class('admin', G_ADMIN_DIR, 'no');
System::load_app_fun('global', G_ADMIN_DIR);

// include "lib/onepay/OnePay.class.php";

class paypalpay_url
{
    private $db;
    private $pay_prefix;
    private $api = "https://api-m.sandbox.paypal.com";
    private $config;

    public function __construct()
    {
        $this->db = System::load_sys_class('model');
        $this->pay_prefix = System::load_sys_config("system", "pay_prefix");
        $pay = $this->db->GetOne("select * from `@#_pay` where `pay_class` = 'paypalpay'");
        $data = unserialize($pay['pay_key']);
        $this->config['clientId'] = $data['clientId']["val"];
        $this->config['clientSecret'] = $data['clientSecret']["val"];
        $mode = $data['mode']['val'];
        if ($mode == 1) {
            $this->api = "https://api-m.sandbox.paypal.com";
        } else {
            $this->api = "https://api-m.paypal.com";
        }
    }

    public function qiantai()
    {
        $data = $_GET ?: $_POST;
        save_log(["data" => $data, "msg" => "准备支付"], "paypalpay" . date("Y-m-d") . ".log", 1);
        $token = $_GET['token'];
        $res = $this->db->GetOne("select * from `@#_member_addmoney_record` where `order_id` = '{$token}'");
        $url = G_WEB_PATH . "/index.php/pay/paypalpay_url/success";
        $celurl = G_WEB_PATH . "/index.php/pay/paypalpay_url/cancel?token=$token";
        $language = System::load_sys_config("system", "web_language");
        $currency = $this->db->GetOne("select * from `@#_language` where `lg_value` = '$language'");
        $formot_array = ["money","other_money"];
        data_format($res,$formot_array,"price",$currency['currency_unit'],"");
        include "tpl/paypal_pay.tpl.php";
    }

    public function cancel()
    {
        $data = $_GET ?: $_POST;
        save_log(["data" => $data, "msg" => "支付取消"], "paypalpay" . date("Y-m-d") . ".log", 1);
        $order_id = $data['token'];
        $sql = "update `@#_member_addmoney_record` set status = '支付取消' where `order_id` = '{$order_id}'";
        $this->db->Query($sql);
        _messageerror("支付取消!", G_WEB_PATH . "/index/pages/cart/recharge/index");
    }
    public function success()
    {
        // _message("充值成功!",G_WEB_PATH."/index/pages/users/index");
        $token = $_POST['token'];
        $url = $this->api . "/v2/checkout/orders/{$token}/capture";
        $result = $this->curl($url, [], 'POST');
        // todo 此处可以根据捕获的订单信息，做订单验证、完结等业务逻辑
        $data = json_decode($result,true);
        save_log(["data" => $data, "msg" => "支付结果"], "paypalpay" . date("Y-m-d") . ".log", 1);
        $res = $this->houtai($data['data']);
        if($res["code"] == 200){
            _message("充值成功!",G_WEB_PATH."/index/pages/users/index");
        }else{
            save_log(["data" => $res, "msg" => "支付结果2"], "paypalpay" . date("Y-m-d") . ".log", 1);
            _messageerror("支付失败!", G_WEB_PATH . "/index/pages/cart/recharge/index");
        }
    }

    /**
     * 
     * 
     * Example Callback Data: 
        {
        "code": 1,
        "msg": "",
        "data": {
            "id": "9T43657641619294T",
            "status": "COMPLETED",
            "payment_source": {
                "paypal": {
                    "email_address": "sb-47oez134945224@personal.example.com",
                    "account_id": "FRXQVTA4AWT24",
                    "account_status": "VERIFIED",
                    "name": {
                        "given_name": "John",
                        "surname": "Doe"
                    },
                    "address": {
                        "country_code": "HK"
                    }
                }
            },
            "purchase_units": [
                {
                    "reference_id": "C17350253946799389",
                    "payments": {
                        "captures": [
                            {
                                "id": "2V892758H4150101K",
                                "status": "COMPLETED",
                                "amount": {
                                    "currency_code": "USD",
                                    "value": "1.00"
                                },
                                "final_capture": true,
                                "seller_protection": {
                                    "status": "ELIGIBLE",
                                    "dispute_categories": [
                                        "ITEM_NOT_RECEIVED",
                                        "UNAUTHORIZED_TRANSACTION"
                                    ]
                                },
                                "seller_receivable_breakdown": {
                                    "gross_amount": {
                                        "currency_code": "USD",
                                        "value": "1.00"
                                    },
                                    "paypal_fee": {
                                        "currency_code": "USD",
                                        "value": "0.33"
                                    },
                                    "net_amount": {
                                        "currency_code": "USD",
                                        "value": "0.67"
                                    },
                                    "receivable_amount": {
                                        "currency_code": "HKD",
                                        "value": "5.08"
                                    },
                                    "exchange_rate": {
                                        "source_currency": "USD",
                                        "target_currency": "HKD",
                                        "value": "7.57624725"
                                    }
                                },
                                "links": [
                                    {
                                        "href": "https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/2V892758H4150101K",
                                        "rel": "self",
                                        "method": "GET"
                                    },
                                    {
                                        "href": "https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/2V892758H4150101K\\/refund",
                                        "rel": "refund",
                                        "method": "POST"
                                    },
                                    {
                                        "href": "https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/9T43657641619294T",
                                        "rel": "up",
                                        "method": "GET"
                                    }
                                ],
                                "create_time": "2024-12-24T07:38:21Z",
                                "update_time": "2024-12-24T07:38:21Z"
                            }
                        ]
                    }
                }
            ],
            "payer": {
                "name": {
                    "given_name": "John",
                    "surname": "Doe"
                },
                "email_address": "sb-47oez134945224@personal.example.com",
                "payer_id": "FRXQVTA4AWT24",
                "address": {
                    "country_code": "HK"
                }
            },
            "links": [
                {
                    "href": "https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/9T43657641619294T",
                    "rel": "self",
                    "method": "GET"
                }
            ]
        }
    }
     * */
    public function houtai($data)
    {
        // $data = $_REQUEST;
        // if(!$data){
        //     $data = json_decode(file_get_contents('php://input'),true);
        // }
        save_log(["data" => $data, "msg" => "处理订单"], "paypalpay" . date("Y-m-d") . ".log", 1);
        if (!$data) {
            return ["msg"=>"fail","code"=>"101"];
        }
        $pay_type = $this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'paypalpay' and `pay_start` = '1'");
        $info = unserialize($pay_type['pay_key']); //获取token、
        $token = $info['key']['val'];
        $reference_id = $data['purchase_units'][0]['reference_id'];
        $orderid = strstr($reference_id, $this->pay_prefix);
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$orderid' and `status` = '未付款' ");
        if (!$dingdaninfo) {
            return ["msg"=>"fail","code"=>"102"];
            exit;
        }    //没有该订单,失败
        if ($data['status'] != "COMPLETED") {
            $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '支付失败' where  `code` = '$orderid'");
            return ["msg"=>"fail","code"=>"103"];
            exit;
        }
        $c_money = intval($dingdaninfo['money']);
        $uid = $dingdaninfo['uid'];
        // $time = time();
        $time = strtotime($data['tradeTime']);
        $config = System::load_app_config("userrecharge", '', 'member');
        if ($config['r_money']) {
            $d_money = $config['r_money'];
        }
        $this->db->Autocommit_start();
        //充值奖励处理
        if ($c_money >= $d_money && $config['isOpen']) {
            if ($config['money']) {
                $m = $config['money']; //充值赠送
                $up_sql1 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $m where (`uid` = '$uid')");
                $up_sql3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值赠送', '$m', '$time')");
            }
            $r_time = $this->db->GetOne("SELECT * FROM `@#_recharge_money` WHERE `uid` = '$uid' AND status=1");
            if (!$r_time) { //判断是否在累计时效内
                $status = 1;
                //写入充值时间做累计领取判断
                $up_sql2 = $this->db->Query("INSERT INTO `@#_recharge_money` (`uid`, `time`, `dindancode`, `status`) VALUES ('$uid', '$time', '$orderid', '$status')");
            }
        }

        $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `status` = '已付款' where  `code` = '$orderid'");
        $up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
        $orderCode = $dingdaninfo['code'];
        $up_q3 = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time`,`code`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time','$orderCode')");

        if ($up_q1 && $up_q2 && $up_q3) {
            $this->db->Autocommit_commit();
        } else {
            $this->db->Autocommit_rollback();
            return ["msg"=>"fail","code"=>"104"];
            exit;
        }
        if (empty($dingdaninfo['scookies'])) {
            return ["msg"=>"success","code"=>"200"];

            exit;        //充值完成
        }
    }

    /**
     * curl 请求 todo ①注释是access-token方式 ②是base64加密凭证
     * @param string $url
     * @param array $data
     * @param string $method
     * @return 
     */
    public function curl($url = null,  $data = [],  $method = 'GET')
    {
        try {
            $payload = empty($data) ? '' : json_encode($data);

            /*
             *  todo ① Access-Token方式，我的是放缓存，需要从缓存获取，缓存没有话会重新去请求拉取
            $tokens = $this->getToken();
            if ($tokens['code'] != 1) {
                return resultArray(0, $tokens['msg']);
            }
            $token = $tokens['data']['access_token'];
            */

            // todo ② 凭证编码
            $token = base64_encode($this->config['clientId'] . ':' . $this->config['clientSecret']);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            // 将凭证放入请求头
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                /* todo ①  Access-Token方式请求头
                'Authorization: Bearer ' . $token
                */
                // todo ② 直接使用base64加密凭证
                'Authorization: Basic ' . $token
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            } elseif ($method === 'GET') {
                curl_setopt($ch, CURLOPT_HTTPGET, true);
            }

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                return resultArray(0, curl_error($ch));
            }

            $data = json_decode($result, true);
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpStatusCode == 422) {
                $message = $data['message'] ?: "";
                return resultArray(0, 'HTTP 422 Unprocessable entity ERROR:' . $message);
            } else if ($httpStatusCode == 504) {
                return resultArray(0, 'HTTP 504 Gateway Timeout');
            }

            curl_close($ch);
            return resultArray(1, '', $data);
        } catch (\Exception $e) {
            return resultArray(0, $e->getMessage());
        }
    }
}

function resultArray($code = 0, $msg = '', $data = [])
{
    return json_encode(['code' => $code, "msg" => $msg, "data" => $data]);
}
