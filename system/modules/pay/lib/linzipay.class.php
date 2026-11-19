<?php 

class linzipay {
    private $config;
	private $url;
	//主入口
	public function config($config=null){

		$this->config = $config;
        return $this->pay();
	}

    public function refund(){

    }

    public function pay(){
        $url = "http://hsbq.linzi.threegf.com/Pay_Index.html";
        $configData = $this->config['data'];
        $money = number_format($this->config['money'],2,".","");
/*      
        pay_memberid 商户号 是 是 平台分配商户号
        pay_orderid 订单号 是 是 上送订单号唯一, 字符长度20
        pay_applydate 提交时间 是 是 时间格式：2016-12-26 18:18:18
        pay_bankcode 银行编码 是 是 参考后续说明
        pay_notifyurl 服务端通知 是 是 服务端返回地址.（POST 返回数据）pay_callbackurl 页面跳转通知 是 是 页面跳转返回地址（POST 返回数据）
        pay_amount 订单金额 是 是 商品金额
        pay_md5sign MD5 签名 是 否 请看 MD5 签名字段格式
        pay_attach 附加字段 否 否 此字段在返回时按原样返回(中文需要url 编码)
        pay_productname 商品名称 是 否
        pay_productnum 商户品数量 否 否
        pay_productdesc 商品描述 否 否
        pay_producturl 商户链接地址 否 否
        pay_payname 付款人姓名 否 否 部分通道如转账转卡类需要传递pay_ip 付款人 IP 是 否 付款玩家的真实IP
        pay_userid 会员 ID 否 否 付款用户的唯一标识，对于原生不抗诉通道，则必须要填写
        pay_username 付款人姓名 否 否
        若为转卡通道，或者银联通道，则此参数必须正确填写，否则无法拉起或到账。其他通道可留空
        */
        $time = time();
        $param = [
            "pay_memberid"=>$configData['pay_memberid']['val'],
            "pay_orderid"=>$this->config['u_id'].$this->config['code'],
            "pay_applydate"=>date("Y-m-d H:i:s", $time),
            "pay_bankcode"=>$configData['code']['val'],
            "pay_notifyurl"=>$this->config['NotifyUrl'],
            "pay_callbackurl"=>$this->config['ReturnUrl'],
            "pay_amount"=>$money,
            // "pay_attach"=>"",
            // "pay_productname"=>"",
            // "pay_productnum"=>"",
            // "pay_productdesc"=>"",
            // "pay_producturl"=>"",
            // "pay_payname"=>"",
            // "pay_userid"=>"",
            // "pay_username"=>"",
        ];
        $param['pay_md5sign'] = $this->sign($param,$configData['key']['val']);  //签名字符串
        // echo json_encode($param);
        // die;
        $res = $this->curlPost($url,$param);
        /* {
            status 状态码 1 为下单成功 其余状态为下单失败
            msg 订单号 下单状态返回的中文显示
            order_id 商户订单号 商户订单号
            mch_order_id 平台订单号 平台订单号
            h5_url 支付链接 付款链接，请求时请取这个参数
            sdk_url SDK 请求参数
            SDK 的请求参数，自身支持 SDK 模式的可以取此
            参数，H5 通道则也返回支付链接
            pay_url 付款链接 付款链接，若为 HTML 格式，则取此参数
        } */
        if($res['status'] == "1"){
            // header('location:'.$res['pay_url']);
            $data = [
                "code" => 0,
                "data"=>$res['pay_url'],
                "msg"=>"ok"
            ];
            echo json_encode($data);
            $data['pay_status_code'] = 1;
            return $data;
        }
        echo json_encode($res);
        $res['pay_status_code'] = 0;
        return $res;
    }

    private function sign($array,$secret_key){
        $str = '';
        ksort($array);
        foreach($array as $key=>$vl){
            if($str){$str .= "&";}
            $str .= $key."=".$vl;
        }
        $str .= "&key=".$secret_key;
        $str = strtoupper(md5($str));
        // $str = strtolower(md5($str));
        return $str;
    }

    private function curlPost1($url,$payload){

        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultJson = curl_exec($ch);
        return json_decode( $resultJson,true);
    }
    private function curlPost($url,$params){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $resultJson = curl_exec($ch);
    return json_decode( $resultJson,true);
    }
}