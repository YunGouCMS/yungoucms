<?php
require_once 'vendor/autoload.php';
use Twilio\Rest\Client;

class Twilio
{

    // 使用你的 Account SID 和 Auth Token 初始化 Twilio REST API 客户端
    private $sid = '你的Account SID';
    private $token = '你的Auth Token';
    private $from = '你的Twilio电话号码';// 你的 Twilio 电话号码，包括国家代码

    public function __construct($sid = null, $token = null,$from = null)
    {
        if ($sid && $token && $from) {
            $this->sid = $sid;
            $this->token = $token;
            $this->from = $from;
        } else if (!$this->sid || !$this->token || !$this->from) {
            throw new Exception('请设置Twilio的Account SID和Auth Token 和from');
        }
    }

    /**
     * Summary of send
     * @param mixed $to// 接收者的电话号码，包括国家代码
     * @param mixed $message // 你想发送的消息内容
     * @return array
     */
    public function send($to, $message)
    {
        $client = new Client($this->sid, $this->token);
        try {
            $message = $client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
            return [1,"短信已发送！"];
        } catch (Exception $e) {
            return [-1,"发送失败: " . $e->getMessage()];
        }
    }
}
