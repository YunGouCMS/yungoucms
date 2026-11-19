<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_sys_fun('redisData');

class quicknav extends admin
{
    public $redis;
    public $redisKey = "kjdh";
    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class('model');
        $this->Pass();
        $this->urlkay = $this->UrlData['kay'];
        $this->redis = new redisData;
    }

    public function index(){
        $this->ment=array(
            array("webcfg","快捷导航",ROUTE_M.'/'.ROUTE_C."/index")
        );
        include  $this->tpl(ROUTE_M,'quicknav.index');
    }

    public function jsNav(){
        if(empty(_getcookie('nav_auth'))){
            $this->success(0);
        }
        $sql ="SELECT * FROM `go_ments` WHERE `is_auth` = '1'";
        $list = $this->db->GetList($sql);
        $result = $this->UrlData['url'];
        foreach ($list as $k=>$v){
            $m =explode('/',trim($v['d'],'/'));
            if(count($m)<3){
                $list[$k]['d'] = G_ADMIN_DIR.$v['d'];
            }
        }
        $list = array_merge($list,$result);
        foreach ($list as $k=>$v){
            $list[$k]['domian'] = get_home_url();
        }
        $this->success(1,'获取成功',$list);
    }

    public function getnav_bank(){
        $key = $_POST['value'];
        $this->keydata = $this->Pass();
        $pass = $key;
        if(md5($pass) != $this->UrlData['key']){
            _setcookie('nav_auth','');
            $this->success(0);
        }
        _setcookie('nav_auth',$pass);
        $this->success(1,'成功');
    }

    public function getnav(){
        $key = $_POST['value'];
        $res = $this->redis->redisGet($this->redisKey);
        $pass = $key;
        if(!$res['data']){
            $res['data'] = md5("123456");
        }
        if(md5($pass) != $res['data']){
            _setcookie('nav_auth','');
            $this->success(0);
        }
        _setcookie('nav_auth',$pass);
        $this->success(200,'成功');
    }

    private function Pass(){
//        $this->UrlData = System::load_sys_config('quicknav');
        $this->UrlData = array (
            'key' => 'REDACTED_CONFIGURE_LOCALLY',
            'url' => array(),
        );
        return 1;
    }

    private function success($code = 1,$msg = '',$data=''){
        $data = array('code'=>$code,'msg'=>$msg,'data' =>$data);
        echo json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        exit();
    }

    public function repassword(){
        include  $this->tpl(ROUTE_M,'quicknav.repassword');
    }
    public function repasswordPost_bank(){
        $data = $_POST['val'];
        $key = System::load_sys_config('quicknav');
        if(md5($data['oldpassword']) != $key['key']){
            echo -1;
        }
        $key['key'] = md5($data['newpassword']);
        $html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.');";
        $html.="\n return ".var_export($key,true);
        $html.="\n ?>";
        $ok=file_put_contents(G_CONFIG.'quicknav.inc.php',$html);

        if($ok){
            chmod(G_CONFIG.'quicknav.inc.php', 0777);
            echo 1;
        }else{
            echo 0;
        }

    }

    public function repasswordPost(){
        $data = $_POST['val'];
        $res = $this->redis->redisGet($this->redisKey);

        if((md5($data['oldpassword']) != $res['data']) && !empty($res['data'])){
            echo -1;
        }

        $res = $this->redis->redisSet($this->redisKey,md5($data['newpassword']));
        if($res){
            chmod(G_CONFIG.'quicknav.inc.php', 0777);
            echo 1;
        }else{
            echo 0;
        }

    }
}