<?php 
defined('G_IN_SYSTEM')or exit('');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
// System::load_app_class(G_ADMIN_DIR ,'' ,'no');

// 首页弹窗设置
class banner extends admin {
    private $db;
    public function __construct(){
        parent::__construct();
        $this->db=$this->DB();
        $this->ment=array(
            array("lists","首页弹窗列表",ROUTE_M.'/'.ROUTE_C."/lists"),
            array("addcate","添加首页弹窗图",ROUTE_M.'/'.ROUTE_C."/addimg"),
        );
    }

    //首页弹窗列表
    public function lists(){
        $banners=array();
        $banners=$this->db->Getlist("select * from `@#_banner` where type = 1");	
        $language = $this->getLanguageList(1);
        $lgs = array_column($language,"country","lg_value");
        foreach($banners as &$vl){
            $vl['language'] = $lgs[$vl['language']];
            $vl['img'] = str_replace("bannerimg/","",$vl['img']);
        }	
        include $this->tpl(ROUTE_M,'banner.list');
    }

    //添加首页弹窗
    public function addimg(){
        if(isset($_POST['submit'])){
            $title = htmlspecialchars($_POST['title']);
            $img_url = htmlspecialchars($_POST['img_url']);	
            $language=htmlspecialchars(trim($_POST['language']));
            $bg_color=htmlspecialchars(trim($_POST['bg_color']));
            $status = htmlspecialchars($_POST['status']);
            $img= $_POST['img'];
            if(empty($title) || empty($img_url)){
                _message("添加失败",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
            }
            $time = time();
            $sql = "INSERT INTO `@#_banner`(title,img,img_url,status,create_time,language,bg_color) VALUES('$title','$img','$img_url','$status','$time','$language','$bg_color')";
            $this->db->Query($sql);
            if($this->db->affected_rows()){
                    _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
            }else{
                    _message("添加失败");
            }
        }
        $languages = $this->getLanguageList();
        include $this->tpl(ROUTE_M,'banner.addimg');
    }

    //编辑
    public function editimg(){
        $this->ment=array(
            array("lists","首页弹窗列表",ROUTE_M.'/'.ROUTE_C."/lists"),
            array("editimg","修改首页弹窗图",ROUTE_M.'/'.ROUTE_C."/editimg",2),
            array("addcate","添加首页弹窗图",ROUTE_M.'/'.ROUTE_C."/addimg"),
        );
        $banner_id=intval($this->segment(4));
        $banner_info = $this->db->GetOne("SELECT * FROM `@#_banner` where `id`='$banner_id'");
        if(!$banner_info)_message("参数不正确");	
        
        if(isset($_POST['submit'])){		
            $title=htmlspecialchars($_POST['title']);
            $img_url= htmlspecialchars($_POST['img_url']);
            $status = htmlspecialchars($_POST['status']);
            $language=htmlspecialchars(trim($_POST['language']));
            $bg_color=htmlspecialchars(trim($_POST['bg_color']));
            // 判断是否更该图片
            $img= $_POST['img']?:$banner_info['img'];
            $time = time();
            $this->db->Query("UPDATE `@#_banner` SET `title`='$title',`img`='$img',`img_url`='$img_url', `status`='$status',`language`='$language', `update_time`='$time',`bg_color`='$bg_color' WHERE `id`='$banner_id'");
            if($this->db->affected_rows()){
                    _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
            }else{
                    _message("修改失败");
            }
        }
        $languages = $this->getLanguageList();
        include $this->tpl(ROUTE_M,'banner.editimg');	
    }

    //删除
    public function delimg(){
        $banner_id = intval($this->segment(4));
        if($banner_id){
            $this->db->Query("DELETE FROM `@#_banner` WHERE `id`='$banner_id'");
            if($this->db->affected_rows()){
                _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
            }else{
                _message("删除失败",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
            }
        }
    }

    //更改状态
    public function up_status(){
        $banner_id = htmlspecialchars($_POST['id']);
        $status = htmlspecialchars($_POST['status']);
        if($banner_id){
            $res = $this->db->Query("UPDATE `@#_banner` SET `status`='$status' WHERE `id`='$banner_id'");
            if ($res){
                echo json_encode(['code'=>1,'msg'=>'修改成功']);
                exit;
            }else{
                echo json_encode(['code'=>2,'msg'=>'修改失败']);
                exit;
            }
        }
    }
}
?>