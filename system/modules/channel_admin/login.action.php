<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');

System::load_app_class('base', 'member', 'no');
System::load_sys_fun('user');

class login extends base
{
    public function __construct()
    {
        $this->db = System::load_sys_class("model");
        parent::__construct();
    }

    /**
     * 登录页面
     */
    public function init()
    {
        // 如果已登录，跳转到首页
        $channel_uid = _getcookie("channel_uid");
        if ($channel_uid) {
            header("Location: " . G_WEB_PATH . "/channel_admin/index");
            exit;
        }

        if (isset($_POST['submit'])) {
            $this->doLogin();
        }

        $tpl_path = dirname(__FILE__) . '/tpl/login.tpl.php';
        include $tpl_path;
    }

    /**
     * 执行登录
     */
    private function doLogin()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            _message("用户名和密码不能为空！");
        }

        // 查询渠道商用户（level = 2）（使用绑定防 SQL 注入）
        $password_md5 = md5($password);
        $member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE (`username` = :un OR `mobile` = :un) AND `password` = :pw AND `level` = 2", array('un' => $username, 'pw' => $password_md5));

        if (!$member) {
            _message("账号不存在或不是渠道商！");
        }

        // 检查账号状态
        if ($member['emailcode'] != '1' && $member['mobilecode'] != '1') {
            _message("账号未激活！");
        }

        // 设置登录cookie - 确保字段值一致
        $mobile = isset($member['mobile']) ? (string)$member['mobile'] : '';
        $email = isset($member['email']) ? (string)$member['email'] : '';
        $uid = (string)$member['uid'];
        $password = (string)$member['password'];
        
        $cookie_value = _encrypt(md5($uid . $password . $mobile . $email));
        
        // 记录登录日志
        $time = time();
        $ip = _get_ip();
        $this->db->Query("INSERT INTO `@#_member_login_log`(`user_id`, `ip`, `login_time`, `type`) VALUES (:uid, :ip, :lt, '3')", array('uid' => $member['uid'], 'ip' => $ip, 'lt' => $time));

        // 设置cookie - 使用明确的路径，不设置域名（让浏览器自动处理）
        $cookie_time = time() + 60 * 60 * 24 * 7;
        $is_https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443');
        
        // 设置 channel_uid - 使用 setcookie 直接设置，不设置域名（空字符串表示当前域名）
        setcookie("channel_uid", $uid, $cookie_time, '/', '', $is_https, true); // httponly=true 更安全
        $_COOKIE['channel_uid'] = $uid; // 立即生效，用于当前请求
        
        // 设置 channel_shell
        setcookie("channel_shell", $cookie_value, $cookie_time, '/', '', $is_https, true); // httponly=true 更安全
        $_COOKIE['channel_shell'] = $cookie_value; // 立即生效，用于当前请求
        
        header("Location: " . G_WEB_PATH . "/channel_admin/index");
        exit;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        _setcookie("channel_uid", "", time() - 3600);
        _setcookie("channel_shell", "", time() - 3600);
        header("Location: " . G_WEB_PATH . "/channel_admin/login");
        exit;
    }
}

