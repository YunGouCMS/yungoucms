<?php

defined('G_IN_SYSTEM') or exit('No permission resources.');
// System::load_app_class('base', 'app', 'no');
require "user.action.php";

class magic extends user
{
    
    const MAGIC_DEBUG = false; // 是否开启调试模式，调试模式下会打印出调试信息
    const MAGIC_SECRET_KEY = "REDACTED_CONFIGURE_LOCALLY"; // Magic Secret Key，请替换为你的Secret Key
    const MAGIC_API_URL = "https://api.magic.link/v1/user"; // Magic API URL
    const MAGIC_APP_ID = "xxxxxxxxxxxx"; // Magic App ID，请替换为你的App ID
    const MAGIC_APP_SECRET = "xxxxxxxxxxxx"; // Magic App Secret，请替换为你的App Secret

    public $magic;

    public function __construct()
    {
        parent::__construct();
        // $this->db = System::load_sys_class('model');
        $web = System::load_sys_config('system');
    }

    /**
     * Magic登录处理
     * 接收前端传来的DID Token，验证后返回你的JWT
     */
    public function magicLogin()
    {
        $didToken = getParams('did_token');

        if (!$didToken) {
            $this->jsonData->error(500, lan('DID Token missing', '缺少DID Token'));
        }

        // 1. 验证Magic Token
        $magicUser = $this->verifyMagicToken($didToken);
        if (!$magicUser) {
            $this->jsonData->error(500, lan('Magic verification failed', 'Magic验证失败'));
        }
        $this->jsonData->success('Magic验证成功', $magicUser);
        // 2. 查找或创建用户（关联你的用户表）
        $user = $this->findOrCreateUser($magicUser);

        // 3. 生成你原有的JWT（复用你现有的JWT生成逻辑）
        $jwt = $this->generateJWT($user);

        // 4. 返回登录成功
        return $this->jsonResponse(200, '登录成功', [
            'jwt' => $jwt,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'wallet_address' => $user['wallet_address'] ?? null
            ]
        ]);
    }

    /**
     * 直接调用 Magic API 验证 DID Token
     */
    private function verifyMagicToken($didToken)
    {
        $url = 'https://api.magic.link/v1/user';
        $headers = [
            'Authorization: Bearer ' . self::MAGIC_SECRET_KEY, // sk_live_xxxx
            'Content-Type: application/json',
            'User-Agent: YourApp/1.0'
        ];
        
        $postData = json_encode(['did_token' => $didToken]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 开发环境临时关闭
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);
        
        curl_close($ch);
        
        // 记录调试信息
        echo("Magic API 响应 - HTTP状态码: $httpCode, 响应: $response");
        
        // 处理 cURL 错误
        if ($curlErrno) {
            echo("Magic API cURL错误: $curlError");
            return false;
        }
        
        // 解析响应
        $data = json_decode($response, true);
        
        // 检查 HTTP 状态码和响应格式
        if ($httpCode === 200 && isset($data['data'])) {
            // 验证成功，返回用户信息
            return $data['data'];
        }
        
        // 处理具体的错误情况
        if ($httpCode === 502) {
            echo ("Magic API 返回 502 - 服务暂时不可用或 Token 无效");
            return false;
        }
        
        if ($httpCode === 401) {
            echo("Magic API 返回 401 - 认证失败，检查 Secret Key");
            return false;
        }
        
        if (isset($data['error'])) {
            echo("Magic API 错误: " . json_encode($data['error']));
        }
        
        return false;
    }

    /**
     * 查找或创建用户
     * 这里连接你现有的用户表
     */
    private function findOrCreateUser($magicUserData)
    {
        $issuer = $magicUserData['issuer'];      // Magic的唯一用户ID
        $email = $magicUserData['email'] ?? null;
        $walletAddress = $magicUserData['public_address'] ?? null;

        // 查找是否已有此用户（可以用email或magic_issuer字段）
        $stmt = $this->db->GetOne("SELECT * FROM `@#_member` WHERE magic_issuer = ? OR email = ?");
        //TODO
       
    }

    /**
     * 生成JWT（复用你现有的JWT逻辑）
     */
    private function generateJWT($user)
    {
        //获取jWT 
    }

    private function jsonResponse($code, $message, $data = [])
    {
        http_response_code($code);
        echo json_encode(array_merge(['message' => $message], $data));
        exit;
    }
}
