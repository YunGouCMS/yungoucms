<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('admin', G_ADMIN_DIR, 'no');
require_once 'lib/PHPExcel/IOFactory.php';
class setting extends admin {
    private $db;
    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class("model");
    }
public function test(){
    echo "hello world";
}

    //写入文件
    /* public function cfgPut(){
        $cfg=$this->db->GetList("select * from `@#_config` where 1");
        $html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.'); \n";
        $html.="return array( \n";
        foreach ($cfg as $v){
            // $html.="'$v[name]' => \"".$v['value']."\", // ".$v['zhushi'];
            $v["value"] = str_replace("'",'"',$v['value']);
            $html .= "'".$v['name']."'=>'".$v['value']."',//".$v['zhushi'];
            $html.="\n";		
        }
        $html.="); \n ?>";
        // var_dump($html);exit;
        if(!is_writable(G_CONFIG.'system.inc.php')) _message('Please chmod  system  to 0777 !');
        return $ok=file_put_contents(G_CONFIG.'system.inc.php',$html);		
    } */
    public function userSet(){
        $this->ment=array(
            array("userSet","用户设置",ROUTE_M.'/'.ROUTE_C."/userSet"),
            // array("touxiangSet","头像设置",ROUTE_M.'/'.ROUTE_C."/touxiangSet")
        );
        $nickname = $this->db->GetOne("select * from `@#_caches` where `key` = 'member_name_key' limit 1");
        $list = $this->db->GetList("select * from `@#_member_presets_picture`");
        $keys = [];
        foreach($list as &$val){
            $val['url'] = G_UPLOAD_PATH."/".$val['path'];
            $keys[$val['key']] = $val['id'];
        }
        $arr = [
            "google"    =>  ["name"=>$this->lgs['Google']?:"Google",  "img"=>"google.png"],
            "twitter"   =>  ["name"=>$this->lgs['Twitter']?:"Twitter", "img"=>"twitter.png"],
            "line"      =>  ["name"=>$this->lgs['Line']?:"Line",  "img"=>"line.png"],
            "qq"        =>  ["name"=>$this->lgs['qq']?:"QQ",      "img"=>"qq.png"],
            "wx"        =>  ["name"=>$this->lgs['WeChat']?:"WeChat",  "img"=>"wx.png"],
            "alipay"    =>  ["name"=>$this->lgs['Alipay']?:"Alipay",  "img"=>"alipay.png"],
            "sina"      =>  ["name"=>$this->lgs['weibo']?:"weibo",   "img"=>"sina.png"],
            "baidu"     =>  ["name"=>$this->lgs['baidu']?:"baidu",   "img"=>"baidu.png"],
            "douyin"    =>  ["name"=>$this->lgs['douyin']?:"douyin",  "img"=>"douyin.png"],
            "huawei"    =>  ["name"=>$this->lgs['huawei']?:"huawei",  "img"=>"huawei.png"],
            "xiaomi"    =>  ["name"=>$this->lgs['xiaomi']?:"xiaomi",  "img"=>"xiaomi.png"],
            "microsoft" =>  ["name"=>$this->lgs['Microsoft']?:"Microsoft","img"=>"microsoft.png"],
            "facebook"  =>  ["name"=>$this->lgs['FaceBook']?:"FaceBook","img"=>"face.png"],
            "wework"    =>  ["name"=>$this->lgs['WeCom']?:"WeCom",   "img"=>"qiye.png"],
            "dingtalk"  =>  ["name"=>$this->lgs['ding']?:"ding",    "img"=>"dingtalk.png"],
            "gitee"     =>  ["name"=>$this->lgs['Gitee']?:"Gitee",   "img"=>"gitee.png"],
            "github"    =>  ["name"=>$this->lgs['Github']?:"Github",  "img"=>"github.png"],
        ];
        $zh = ["qq","wx","alipay","sina","baidu","douyin","huawei","xiaomi","wework","dingtalk"];
        $sql = "select type,count(*) as num from `@#_member_auth` group by type";
        $auth = $this->db->GetList($sql);
        $sortArray = array_column($auth, 'num', 'type');
        arsort($sortArray);
        $agglist = [];
        foreach($sortArray as $k=>$v){
            if($arr[$k]){
                $agglist[$k] = $arr[$k];
                unset($arr[$k]);
            }
        }
        $agglist = array_merge($agglist,$arr);
        $yiweilist = $agglist;
        unset($agglist['line']);
        unset($yiweilist['facebook']);

        if(isset($_POST['dosubmit'])){
            $nicknames = htmlspecialchars($_POST['nickname']);
            $nicknames = trim($nicknames,",");
            $nicknames = str_ireplace(" ",'',$nicknames);
            if($nickname){
                $this->db->Query("UPDATE `@#_caches` SET `value` = '$nicknames' where `key` = 'member_name_key'");
            }else{
                $this->db->Query("INSERT INTO `@#_caches` (`key`,`value`) value ('member_name_key','$nicknames')");
            }

            $is_photo = $_POST['is_photo'];
            $this->setConfig("is_photo",$is_photo); 

            $photo = $_POST['photo'];
            $this->setConfig("photo",$photo); 

            if($is_photo == 1 && $photo){
                $this->db->Query("UPDATE `@#_member` SET img='$photo' WHERE img='$photo'");
            }

            $login_type = $_POST['login_type'];
            $login_type = serialize($login_type);
            $this->setConfig("login_type",$login_type); 

            $login_facebook_account = htmlspecialchars($_POST['login_facebook_account']);
            $this->setConfig("login_facebook_account",$login_facebook_account); 

            $login_facebook_key = htmlspecialchars($_POST['login_facebook_key']);
            $this->setConfig("login_facebook_key",$login_facebook_key); 

            $login_google_account = htmlspecialchars($_POST['login_google_account']);
            $this->setConfig("login_google_account",$login_google_account); 

            $login_google_key = htmlspecialchars($_POST['login_google_key']);
            $this->setConfig("login_google_key",$login_google_key); 

            $login_WhatsApp_account = htmlspecialchars($_POST['login_WhatsApp_account']);
            $this->setConfig("login_WhatsApp_account",$login_WhatsApp_account);

            $login_WhatsApp_key = htmlspecialchars($_POST['login_WhatsApp_key']);
            $this->setConfig("login_WhatsApp_key",$login_WhatsApp_key);

            $login_Line_account = htmlspecialchars($_POST['login_Line_account']);
            $this->setConfig("login_Line_account",$login_Line_account);

            $login_Line_key = htmlspecialchars($_POST['login_Line_key']);
            $this->setConfig("login_Line_key",$login_Line_key);

            $login_Outlook_account = htmlspecialchars($_POST['login_Outlook_account']);
            $this->setConfig("login_Outlook_account",$login_Outlook_account);

            $login_Outlook_key = htmlspecialchars($_POST['login_Outlook_key']);
            $this->setConfig("login_Outlook_key",$login_Outlook_key);

            $login_TikTok_account = htmlspecialchars($_POST['login_TikTok_account']);
            $this->setConfig("login_TikTok_account",$login_TikTok_account);

            $login_TikTok_key = htmlspecialchars($_POST['login_TikTok_key']);
            $this->setConfig("login_TikTok_key",$login_TikTok_key);

            $login_Discord_account = htmlspecialchars($_POST['login_Discord_account']);
            $this->setConfig("login_Discord_account",$login_Discord_account);

            $login_Discord_key = htmlspecialchars($_POST['login_Discord_key']);
            $this->setConfig("login_Discord_key",$login_Discord_key);

            $login_Telegram_account = htmlspecialchars($_POST['login_Telegram_account']);
            $this->setConfig("login_Telegram_account",$login_Telegram_account);

            $login_Telegram_key = htmlspecialchars($_POST['login_Telegram_key']);
            $this->setConfig("login_Telegram_key",$login_Telegram_key);

            $login_Wechat_account = htmlspecialchars($_POST['login_Wechat_account']);
            $this->setConfig("login_Wechat_account",$login_Wechat_account);

            $login_Wechat_key = htmlspecialchars($_POST['login_Wechat_key']);
            $this->setConfig("login_Wechat_key",$login_Wechat_key);

            $login_Zalo_account = htmlspecialchars($_POST['login_Zalo_account']);
            $this->setConfig("login_Zalo_account",$login_Zalo_account);

            $login_Zalo_key = htmlspecialchars($_POST['login_Zalo_key']);
            $this->setConfig("login_Zalo_key",$login_Zalo_key);

            $beichen_app_id = htmlspecialchars($_POST['beichen_app_id']);
            $this->setConfig("beichen_app_id",$beichen_app_id); 

            $beichen_app_key = htmlspecialchars($_POST['beichen_app_key']);
            $this->setConfig("beichen_app_key",$beichen_app_key); 

            $beichen_app_status = htmlspecialchars($_POST['beichen_app_status']);
            $this->setConfig("beichen_app_status",$beichen_app_status); 
            $beichen_login_type = $_POST['beichen_login_type'];

            foreach($beichen_login_type as $key=>$vl){
                foreach($vl as $k=>$v){
                    $beichen_login_type[$key][$k]['name'] = $agglist[$k]['name'];
                    $beichen_login_type[$key][$k]['img'] = $agglist[$k]['img'];
                }
            }
            $beichen_login_type = serialize($beichen_login_type);
            $this->setConfig("beichen_login_type",$beichen_login_type);
            //一为
            $yiwei_app_id = htmlspecialchars($_POST['yiwei_app_id']);
            $this->setConfig("yiwei_app_id",$yiwei_app_id); 

            $yiwei_app_key = htmlspecialchars($_POST['yiwei_app_key']);
            $this->setConfig("yiwei_app_key",$yiwei_app_key); 

            $yiwei_login_type = $_POST['yiwei_login_type'];
            foreach($yiwei_login_type as $key=>$vl){
                foreach($vl as $k=>$v){
                    $yiwei_login_type[$key][$k]['name'] = $yiweilist[$k]['name'];
                    $yiwei_login_type[$key][$k]['img'] = $yiweilist[$k]['img'];
                }
            }
            $yiwei_login_type = serialize($yiwei_login_type);
            $this->setConfig("yiwei_login_type",$yiwei_login_type);
             
            $cl_sy_app_id = htmlspecialchars($_POST['cl_sy_app_id']);//创蓝闪验一键登录appid
            $this->setConfig("cl_sy_app_id",$cl_sy_app_id);

            $cl_sy_app_key = htmlspecialchars($_POST['cl_sy_app_key']);//创蓝闪验一键登录appkey
            $this->setConfig("cl_sy_app_key",$cl_sy_app_key);

            $ks_app_id = htmlspecialchars($_POST['ks_app_id']);//
            $this->setConfig("ks_app_id",$ks_app_id);
            $ks_app_secret = htmlspecialchars($_POST['ks_app_secret']);//
            $this->setConfig("ks_app_secret",$ks_app_secret);
            foreach($_POST as $key=>$vl){
                if(strpos($key,'_status')!==false){
                    $value = htmlspecialchars($vl)?:1;
                    $this->setConfig($key,$value);
                }
            }
            $ok=$this->cfgPut();
            //预设头像
            $imgs = array_slice($_POST['imgarrList'],0,19);
            foreach($imgs as $vl){
                $str_arr = explode("/",$vl);
                $key = end($str_arr);
                if(!isset($keys[$key])){
                    $s_key = time()."-".$key;
                    $sql = "insert into `@#_member_presets_picture` (`path`,`key`) value ('$vl','$s_key')";
                    $this->db->Query($sql);
                    unset($keys[$key]);
                }else{
                    unset($keys[$key]);
                }
            }
            $ids_arr = array_values($keys);
            $ids = implode(",",$ids_arr);
            $this->db->Query("delete from `@#_member_presets_picture` where id in ($ids)");
            if($ok){				
                _message("修改成功");
            }else{
                _message("修改失败");
            }
        }
        $nickname = $nickname['value'];
        $languages = $this->getLanguageList(1);
        $web=System::load_sys_config('system');
        $web['beichen_login_type'] = @unserialize($web['beichen_login_type']);
        $web['yiwei_login_type'] = @unserialize($web['yiwei_login_type']);
        $web['login_type'] = @unserialize($web['login_type']);

        include $this->tpl(ROUTE_M,'setting.user_set');
    }

    public function touxiangSet(){
        $this->ment=array(
            array("userSet","用户设置",ROUTE_M.'/'.ROUTE_C."/userSet"),
            array("touxiangSet","头像设置",ROUTE_M.'/'.ROUTE_C."/touxiangSet")
        );
        $list = $this->db->GetList("select * from `@#_member_presets_picture`");
        $keys = [];
        foreach($list as &$val){
            $val['url'] = G_UPLOAD_PATH."/".$val['path'];
            $keys[$val['key']] = $val['id'];
        }
        if(isset($_POST['coubmit'])){
            $data = $_POST;
            $imgs = array_slice($data['imgarrList'],0,19);
            foreach($imgs as $vl){
                $str_arr = explode("/",$vl);
                $key = end($str_arr);
                if(!isset($keys[$key])){
                    $s_key = time()."-".$key;
                    $sql = "insert into `@#_member_presets_picture` (`path`,`key`) value ('$vl','$s_key')";
                    $this->db->Query($sql);
                    unset($keys[$key]);
                }else{
                    unset($keys[$key]);
                }
            }
            $ids_arr = array_values($keys);
            $ids = implode(",",$ids_arr);
            $this->db->Query("delete from `@#_member_presets_picture` where id in ($ids)");
            _message("ok");
        }
        include $this->tpl(ROUTE_M,'setting.touxiangset');
    }
}
