<?php

class Itniotech
{

    // 使用你的 Account SID 和 Auth Token 初始化 Twilio REST API 客户端
    private $apiKey = '你的apiKey';
    private $apiSecret = '你的apiSecret';
    private $appId = '你的appId';// 
    
    private $url = "https://api.itniotech.com/sms/sendSms";// 短信发送接口地址

    private $state = [
        "0"	=>"成功",
        "-1"=>	"认证错误",
        "-2"=>	"Ip访问受限",
        "-3"=>	"短信内容含有敏感字符",
        "-4"=>	"短信内容为空",
        "-5"=>	"短信内容过长",
        "-6"=>	"不是模板的短信",
        "-7"=>	"号码个数过多",
        "-8"=>	"号码为空",
        "-9"=>	"号码异常",
        "-10"=>	"客户余额不足，不能满足本次发送",
        "-13"=>	"用户被锁定",
        "-16"=>	"超出时间范围限制",
        "-18"=>	"端口程序异常",
        "-19"=>	"联系商务发送短信报价",
    ];
    public function __construct($apiKey = null, $apiSecret = null,$appId = null)
    {
        if ($apiKey && $apiSecret && $appId) {
            $this->apiKey = $apiKey;
            $this->apiSecret = $apiSecret;
            $this->appId = $appId;
        } else if (!$this->apiKey || !$this->apiSecret || !$this->appId) {
            throw new Exception('请设置apiKey,apiSecret,appId');
        }
    }

    /**
     * Summary of send
     * @param mixed $numbers// 接收者的电话号码，包括国家代码
     * @param mixed $content // 你想发送的消息内容
     * @return 
     */
    public function send($numbers, $content)
    {
        header('content-type:text/html;charset=utf8');
        $apiKey = $this->apiKey;
        $apiSecret = $this->apiSecret;
        $appId = $this->appId;
        $url = $this->url;
        $timeStamp = time();
        $sign = md5($apiKey.$apiSecret.$timeStamp);
        $dataArr['appId'] = $appId;
        $dataArr['numbers'] = $numbers;
        $dataArr['content'] = $content;
        $dataArr['senderId'] = ''; // 发送者ID，可以为空
        $dataArr['orderId'] = ''; // 自定义消息，对应发送号码，可以为空
        $data = json_encode($dataArr);
        $headers = array('Content-Type:application/json;charset=UTF-8',"Sign:$sign","Timestamp:$timeStamp","Api-Key:$apiKey");
        $res = $this->curl($url,$data,$headers);
        if($res){
            $res = json_decode($res,true);
            $res['state'] = $this->state[$res['status']];
            return $res;
        }else{
            return false;
        }
        /* status	状态码，0成功，其他失败参见接口响应状态码	String
        reason	失败原因说明	String
        success	提交成功的号码个数	String
        fail	提交失败的号码个数	String
        array	提交成功的json集合	JSONArray
        msgId	提交号码对应平台msgId	String
        number	提交号码	String
        orderId	自定义消息id	String */
    }

    private function curl($url,$data,$headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
        // var_dump($output);
    }
}
