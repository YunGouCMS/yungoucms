<?php
/**
 * @author tTT
 * @date 2022/9/30
 */
defined('G_IN_SYSTEM')or exit('No permission resources.');
// require G_APP_PATH . "extend/autoload.php";
// require G_APP_PATH .'extend/qiniu/autoload.php';
require G_APP_PATH."/vendor/autoload.php";
System::load_app_class('init','go');
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
class uploadimgs extends init
{
    public function __construct(){
        parent::__construct();
        if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
            if(!$this->userinfo)_message("请登录",WEB_PATH."/member/user/login",3);
        }
        $this->db = System::load_sys_class('model');

    }
    //商品图片上传
    public function upimage(){
        $upload_type = $this->getConfig("upload_type");
        $data = htmlspecialchars($_POST['url']);
        $path=$data?$data:'shopimg';
        if($upload_type == 2){
            $this->qiniuUpload($path);
            exit;
        }
        System::load_sys_class('upload','sys','no');
        // var_dump($data);
        // exit;
        if($_FILES['file']['error'] == '0'){
            upload::upload_config($path);
            $_FILES['file']['topic'] = $path;
            $_FILES['file']['user_id'] = $this->userinfo['uid'];
            $res=upload::go_uploads($_FILES['file']);

            if(!$res['ok']){
                $res['code']=0;
                echo json_encode($res);exit();
            }else{
                $data = array('code'=>1,'msg'=>$res['msg'],'data'=>['url'=>G_UPLOAD_PATH.'/'.$path.'/'.$res['dir'],'url2'=>$path.'/'.$res['dir']]);
                echo json_encode($data);exit();
            }
        }
    }

    //编辑器图片上传
    public function upeditimage(){
        $data = htmlspecialchars($_POST['url']);
        $path=$data?$data:'shopimg';
        System::load_sys_class('upload','sys','no');
        if($_FILES['upload']['error'] == '0'){
            upload::upload_config($path);
            $_FILES['upload']['topic'] = $path;
            $_FILES['upload']['user_id'] = $this->AdminInfo;
            $res=upload::go_uploads($_FILES['upload']);
            if(!$res['ok']){
                $res['code']=0;
                echo json_encode($res);exit();
            }else{
                $data = array('code'=>1,'msg'=>$res['msg'],'data'=>['url'=>G_UPLOAD_PATH.'/'.$path.'/'.$res['dir'],'url2'=>$path.'/'.$res['dir']]);
                echo json_encode(['uploaded'=>true,'url'=>G_UPLOAD_PATH.'/'.$path.'/'.$res['dir']]);exit();
            }
        }
    }

    private function qiniuUpload($prent){
        $uploadMgr = new UploadManager();
        $config = $this->getConfig(['qiniu_AccessKey',"qiniu_SecretKey","qiniu_warehouse","qiniu_domain"]);
        $accessKey = $config['qiniu_AccessKey'];
        $secretKey = $config['qiniu_SecretKey'];
        $bucket = $config['qiniu_warehouse'];
        $domain = $config['qiniu_domain'];
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $flie = $_FILES['file'];
        $filePath = $flie['tmp_name'];
        $key = $prent."/".$this->getfilename($flie['name']);
        list($ret,$error) = $uploadMgr->putFile($token, $key, $filePath);
        if ($error !== null) {
            $this->jsonData->error('',"",$error);
        } else {
            $data = [
                "url"=>$domain."/".$ret['key'],
                "url2"=>$ret['key']
            ];
            $this->jsonData->success(1,'',$data);
        }
    }

    private function getfilename($filename){
        $rand=rand(10,99).substr(microtime(),2,6).substr(time(),4,6);
        $filetype=explode('.',$filename);
        $filetype=strtolower(array_pop($filetype));
        if($filetype=='jpeg'){$filetype='jpg';}
        return getRandomstr()."-".$rand.".".$filetype;
    }

    public function upload(){
        $file = $_FILES['file'];
        $type = $_POST['type']?:"image";
        $path = $_POST['path']?:"files";
        System::load_sys_class('upload');
        upload::upload_config($path,$type);
        $res = upload::go_upload($file);
        if($res){
            $path = upload::$path;
            $data = [
                "url"=>G_UPLOAD_PATH.$path,
                "path"=>$path
            ];
            $this->jsonData->success(200,'',$data);
        }else{
            $this->jsonData->error('',"",$res);
        }
    }
}