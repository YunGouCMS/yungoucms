<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('memberbase',null,'no');
System::load_app_fun('user','go');
System::load_app_fun('my','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
include G_FACEBOOK_PATH.'/autoload.php';
class facebook extends memberbase {
    public function login()
    {
        //创建实例
        $fb = new \Facebook\Facebook([
            'app_id' => '265516478976271', // 这里就是应用的APPID
            'app_secret' => 'REDACTED_CONFIGURE_LOCALLY', //这里填写应用的密钥
            'default_graph_version' => 'v2.10',//这里sdk的版本号
        ]);
        $helper = $fb->getRedirectLoginHelper();
        //需要获取的登录用户参数，有的参数需要权限
        $permissions = ['491939317@qq.com']; // Optional permissions\
        $aUrl = 'https://demo.yungoucms.com/';
        //获取到的登录连接
        $loginUrl = $helper->getLoginUrl($aUrl, $permissions);
//        p($loginUrl);
        header('location:'.$loginUrl);
//        echo '<a href="' . htmlspecialchars($loginUrl) . '">Login with Facebook!</a>';
//        return redirect($loginUrl);

    }
}
?>