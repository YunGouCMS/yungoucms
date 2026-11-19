<?php

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class codes  extends admin
{
    private $db;
    public function __construct()
    {
        parent::__construct();
        $this->db=System::load_sys_class("model");
    }

    public function index(){

        $this->ment = array(
            array("init","分享图设置",ROUTE_M."/codes/index"),
            array("init","添加分享图",ROUTE_M."/codes/add"),
        );
        $lists = $this->db->GetList("SELECT `id`,`is_selected`,`code_img_url`,`is_selected` FROM `@#_qr_codes`");
        foreach ($lists as $k => $v){
            $lists[$k]['values'] = $this->lgs['select'];
            if ($v['is_selected'] == 1) $lists[$k]['values'] = $this->lgs['selected'];
        }
        include $this->tpl(ROUTE_M,'codes.index');
    }

    private function validationPost($post){
        $msg = '';
        switch ($post){
            case empty($post['backgrounds']):
                $msg = "请上传背景图";
                break;
            case empty($post['icons']):
                $msg = "请上传logo图";
                break;
            case empty($post['qr_code_size']) && !is_numeric($post['qr_code_size']):
                $msg = "请设置二维码大小";
                break;
            case empty($post['qr_code_border']) && !is_numeric($post['qr_code_border']):
                $msg = "请设置二维码边框大小";
                break;
            case empty($post['left_margin']) && !is_numeric($post['left_margin']):
                $msg = "请设置左边距";
                break;
            case empty($post['top_margin']) && !is_numeric($post['top_margin']):
                $msg = "请设置上边距";
                break;
            case empty($post['internal_x']) && !is_numeric($post['internal_x']):
                $msg = "请设置距离左边内部边距";
                break;
            case empty($post['internal_y']) && !is_numeric($post['internal_y']):
                $msg = "请设置距离上边内部边距";
                break;
        }
        $img = pathinfo($post['backgrounds']);
        if ($img['extension'] != 'png') $msg = "目前系统只支持PNG格式图片";
        return $msg;
    }

    //添加模板
    public function add(){

        $this->ment = array(
            array("init","分享图设置",ROUTE_M."/codes/index"),
            array("init","添加分享图",ROUTE_M."/codes/add"),
        );
        $icUrl = G_WEB_PATH."/statics/uploads/"._cfg('web_logo');
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $post =  $_POST;
            if (empty($post['icons'])){
                $post['icons'] =_cfg('web_logo');
                $post['icUrl'] = getServerHttp(2)."/statics/uploads/"._cfg('web_logo');
            }
            $msg = $this->validationPost($post);
            if (!empty($msg)){
                echo json_encode(['code'=>0,'msg'=>$msg]);exit;
            }
            $img = pathinfo($post['backgrounds']);
            $qrcodeUrl = $_SERVER ['DOCUMENT_ROOT'] . '/statics/plugin/qrcode/tmp/' . $img['basename'];
            $webPath = $money=$this->db->Getone("SELECT `value` FROM `go_config` WHERE name = 'web_path'")['value'];
            $url = mycode_new('',$qrcodeUrl,$webPath,$post['bgUrl'],$post['icUrl'],$post['qr_code_size'],$post['qr_code_border'],$post['left_margin'],$post['top_margin'],$post['internal_x'],$post['internal_y']);

            $qrcodeUrls = G_PLUGIN_PATH . '/qrcode/tmp/' . $img['basename'];
            echo json_encode(['code'=>1,'data'=>$qrcodeUrls,'msg'=>"ok"]);exit;
        }else{
            include $this->tpl(ROUTE_M,'codes.add');
        }
    }

    //修改
    public function addinsert(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $post =  $_POST;
            if (empty($post['icons'])){
                $post['icons'] = getServerHttp(2)."/statics/uploads/"._cfg('web_logo');
            }
            $msg = $this->validationPost($post);
            if (!empty($msg)){
                echo json_encode(['code'=>0,'msg'=>$msg]);exit;
            }
            $time = time();
//            file_put_contents("test.txt",var_export($post,1)."\n",FILE_APPEND);
            $q1 = $this->db->Query("INSERT INTO `@#_qr_codes` SET `backgrounds` = '$post[backgrounds]',`icons` = '$post[icons]',`qr_code_size` = '$post[qr_code_size]',`qr_code_border` = '$post[qr_code_border]',`left_margin` = '$post[left_margin]',`top_margin` = '$post[top_margin]',`internal_x` = '$post[internal_x]',`internal_y` = '$post[internal_y]',`is_selected` = 2,`code_img_url` = '$post[syntheticImag]',`update_time` = '$time',`create_time` = '$time'");
            if ($q1){
                echo json_encode(['code'=>1,'msg'=>"保存成功"]);exit;
            }else{
                echo json_encode(['code'=>0,'msg'=>"保存失败"]);exit;
            }
        }
    }

    //设置启动
    public function installStart(){
        $id = $_POST['id'];
        if (empty($id) || !is_numeric($id)){
            echo json_encode(['code'=>0,'msg'=>"网络异常"]);exit;
        }

        $lists = $this->db->GetList("SELECT `id` FROM `@#_qr_codes` WHERE `is_selected` = 1");

        $this->db->Autocommit_start();
        try {
            $ids = '';
            foreach ($lists as $v1) {
                if($v1 == $id){
                    $this->db->Autocommit_rollback();
                    echo json_encode(['code'=>0,'msg'=>"当前分享图以是启用状态哟！"]);exit;
                }
                $ids .= empty($ids) ? $v1['id'] : "," . $v1['id'];
            }
            $this->db->Query("UPDATE `@#_qr_codes` SET `is_selected`=2 WHERE `id` in ($ids)");
            $this->db->Query("UPDATE `@#_qr_codes` SET `is_selected`=1 WHERE `id` = $id");
            $this->db->Autocommit_commit();
            echo json_encode(['code'=>1,'msg'=>'操作成功']);exit;
        }catch(PDOException $e) {
            //回滚事务
            $this->db->Autocommit_rollback();
            echo json_encode(['code'=>0,'msg'=>'操作失败']);exit;
        }
    }

    //删除
    public function del(){
        $id = $_POST['id'];
        if (empty($id) || !is_numeric($id)){
            echo json_encode(['code'=>0,'msg'=>"网络异常"]);exit;
        }
        if ($_POST['is_selected'] == 1){
            echo json_encode(['code'=>0,'msg'=>"不是删除启动的分享图哦！"]);exit;
        }

        $res = $this->db->Query("DELETE FROM `@#_qr_codes` WHERE id='$id'");
        if ($res){
            echo json_encode(['code'=>1,'msg'=>"删除成功"]);exit;
        }else{
            echo json_encode(['code'=>0,'msg'=>"删除失败"]);exit;
        }
    }

    //登录背景图设置
    public function loginlist(){
        $is_bg = [
            1 => '登录背景',
            2 =>  '登录框',
            3 => '登录背景',
        ];
        $is_enable = [
            1 => '<span style="color:red;">启用</span>',
            2 =>  '未启用',
        ];
        $recordlist = $this->db->GetList("SELECT `id`,`title`,`bgimg`,`is_bg`,`content`,`is_enable` FROM `@#_login_bg`");
        foreach ($recordlist as $k => $v){
            $recordlist[$k]['color1'] = $v['color2'] = "";
            $recordlist[$k]['is_bg'] = $is_bg[$v['is_bg']];

            $data = json_decode($v['content'],true);
            $recordlist[$k]['color1'] = $data['color1'];
            $recordlist[$k]['color2'] = $data['color2'];

            $recordlist[$k]['enable'] = $is_enable[$v['is_enable']];
        }
        include $this->tpl(ROUTE_M,'codes.loginlist');
    }

    //数据验证
    private function setDataLoginBg($post){
        $data['is_bg'] = $post['is_bg'];
        $data['title'] = $post['title'];
        $data['bgimg'] = '';
        $data['color1'] = '';
        $data['color2'] = '';
        $data['gradient_direction'] = '';
        $msg = '';
        if ($post['is_bg'] == 1){
            $data['color1'] = $post['color1'];
            $data['color2'] = $post['color2'];
            $data['gradient_direction'] = $post['gradient_direction'];
            !empty($data['color1']) ?: $msg = "颜色必选";
        }else if($post['is_bg'] == 2){
            $data['color1'] = $post['color1'];
            $data['color2'] = $post['color2'];
            $data['gradient_direction'] = $post['gradient_direction'];
            !empty($data['color1']) ?: $msg = "颜色必选";
        }else if($post['is_bg'] == 3){
            $data['bgimg'] = $post['bgimg'];
            !empty($data['bgimg']) ?: $msg = "背景图必选";
        }
        if(!empty($msg)){
            return ['code'=>1,'msg'=>$msg,'data'=>''];
        }else{
            return ['code'=>2,'msg'=>$msg,'data'=>$data];
        }
    }

    /**
     * @param $typeNumber 类型 1 背景色   2 渐变色   3背景图片
     * @param $classType html中的class 目前两种  body : 登录背景   .bg_f63： 登录框
     * @param string $color 颜色
     * @param string $colors 渐变色
     * @param string $direction 渐变方向 left 从右到左  bottom 从上到下  right 从左到右
     * @param string $img 背景图
     * @return string
     */
    private function cssCode($typeNumber,$classType,$color='',$colors='',$direction="left",$img=''){
        $cssCode = '';
        switch ($typeNumber){
            case 1:
                $cssCode = "$classType{background:$color}";
                break;
            case 2:
                $cssCode = "$classType{background-image: linear-gradient(to $direction , $color, $colors);background-repeat:no-repeat;width: 100%;height: 100%;background-size: 100% 100%;background-attachment: fixed;}";
                break;
            case 3:
                $cssCode = "$classType{background-image:url($img);background-repeat:no-repeat;width: 100%;height: 100%;background-size: 100% 100%;background-attachment: fixed;}";
                break;
        }
        return $cssCode;
    }
    
    public function updateurlloginlog(){
        $recordlist = $this->db->GetList("SELECT `id`,`title`,`bgimg`,`is_bg`,`content`,`is_enable`,is_polling FROM `@#_login_bg` where is_bg = 3");
        // p(getServerHttp(2));
        foreach ($recordlist as $v){
            $img = explode("statics/uploads/",$v['bgimg']);
            $res = $this->updateUrlLoginImg($v['id'],$img[1],$v['title'],$v['is_bg'],$v['is_enable'],$v['is_polling']);
            // p($res);
            // $this->loginDel($v['id']);
        }
        
        echo "同步成功";
    }
    
    public function updateUrlLoginImg($id="",$bgimg="",$title="",$is_bg="",$is_enables = "",$is_pollings = ""){
            $post = $_POST;
            $is_enable = 2;
            $is_polling = 2;
            if(!empty($id)){
                $post['id'] = $id;
                $post['is_bg'] = $is_bg;
                $post['title'] = $title;
                $post['bgimg'] = $bgimg;
                $is_enables && $is_enable = $is_enables;
                $is_polling && $is_polling = $is_pollings;
            }
            // p($post);
            
            $classType= ["body",".bg_f63"];
            try{
                if (!empty($post['bgimg'])){
              
                    $post['bgimg'] = getServerHttp(2)."/statics/uploads/".$post['bgimg'];
                }
                $isjudge = $this->setDataLoginBg($post);
                $data = [];
                if ($isjudge['code'] == 1 ) {
                    return json_encode($isjudge);exit;
                }else{
                    $data = $isjudge['data'];
                }

                //获取模板code
                //判断是什么类型
                $codes = '';
                if ($data['is_bg'] == 3){
                    $codes = $this->cssCode(3,'body','','','',$data['bgimg']);
                }

                //设置背景色
                if ($data['is_bg'] == 1){
                    //渐变色设置
                    if (!empty($data['color2'])){
                        $codes = $this->cssCode(2,'body',$data['color1'],$data['color2'],$data['gradient_direction']);
                    }

                    //背景色设置
                    if (empty($data['color2'])){
                        $codes = $this->cssCode(1,'body',$data['color1']);
                    }
                }

                //设置背景色
                if ($data['is_bg'] == 2){
                    //渐变色设置
                    if (!empty($post['color2'])){
                        $codes = $this->cssCode(2,'.bg_f63',$data['color1'],$data['color2'],$data['gradient_direction']);
                    }

                    //背景色设置
                    if (empty($data['color2'])){
                        $codes = $this->cssCode(1,'.bg_f63',$data['color1']);
                    }
                }

                $content = json_encode($data);

                // $sql="insert into `@#_login_bg`(`title`,`bgimg`,`codes`,`content`,`is_enable`,`is_bg`,`is_polling`,`gradient_direction`) values('$data[title]','$data[bgimg]','$codes','$content','$is_enable','$data[is_bg]','$is_polling','$data[gradient_direction]')";
                // $res = $this->db->Query($sql);
                
                $sql="UPDATE `@#_login_bg` SET `title`='$data[title]',`bgimg`='$data[bgimg]',`codes`='$codes',`content`='$content',`gradient_direction`='$data[gradient_direction]' WHERE `id` = '$post[id]'";
                // p($sql);
                $res = $this->db->Query($sql);
                if ($res){
                    return json_encode(['code'=>1,'msg'=>'添加成功']);exit;
                }
                return json_encode(['code'=>2,'msg'=>'添加失败']);exit;
            }catch (Exception $e){
                return json_encode(['code'=>2,'msg'=>'网络繁忙']);exit;
            }
    }
    

    //添加模板
       public function loginadd($id="",$bgimg="",$title="",$is_bg="",$is_enables = "",$is_pollings = ""){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($id)){
            $post = $_POST;
            $is_enable = 2;
            $is_polling = 2;
            if(!empty($id)){
                $post['is_bg'] = $is_bg;
                $post['title'] = $title;
                $post['bgimg'] = $bgimg;
                $is_enables && $is_enable = $is_enables;
                $is_polling && $is_polling = $is_pollings;
            }
            $classType= ["body",".bg_f63"];
            try{
                if (!empty($post['bgimg'])){
                    $addUrl = explode("codes/",$post['bgimg']);
                    if (empty($addUrl[0]))
                    {
                        $post['bgimg'] = getServerHttp(2)."/statics/uploads/".$post['bgimg'];
                    }
                }
                $isjudge = $this->setDataLoginBg($post);
                $data = [];
                if ($isjudge['code'] == 1 ) {
                    echo json_encode($isjudge);exit;
                }else{
                    $data = $isjudge['data'];
                }

                //获取模板code
                //判断是什么类型
                $codes = '';
                if ($data['is_bg'] == 3){
                    $codes = $this->cssCode(3,'body','','','',$data['bgimg']);
                }

                //设置背景色
                if ($data['is_bg'] == 1){
                    //渐变色设置
                    if (!empty($data['color2'])){
                        $codes = $this->cssCode(2,'body',$data['color1'],$data['color2'],$data['gradient_direction']);
                    }

                    //背景色设置
                    if (empty($data['color2'])){
                        $codes = $this->cssCode(1,'body',$data['color1']);
                    }
                }

                //设置背景色
                if ($data['is_bg'] == 2){
                    //渐变色设置
                    if (!empty($post['color2'])){
                        $codes = $this->cssCode(2,'.bg_f63',$data['color1'],$data['color2'],$data['gradient_direction']);
                    }

                    //背景色设置
                    if (empty($data['color2'])){
                        $codes = $this->cssCode(1,'.bg_f63',$data['color1']);
                    }
                }

                $content = json_encode($data);

                $sql="insert into `@#_login_bg`(`title`,`bgimg`,`codes`,`content`,`is_enable`,`is_bg`,`is_polling`,`gradient_direction`) values('$data[title]','$data[bgimg]','$codes','$content','$is_enable','$data[is_bg]','$is_polling','$data[gradient_direction]')";
                $res = $this->db->Query($sql);
                if ($res){
                    echo json_encode(['code'=>1,'msg'=>'添加成功']);exit;
                }
                echo json_encode(['code'=>2,'msg'=>'添加失败']);exit;
            }catch (Exception $e){
                echo json_encode(['code'=>2,'msg'=>'网络繁忙']);exit;
            }
        }
        include $this->tpl(ROUTE_M,'codes.loginadd');
    }

    //修改转态
     public function updateState(){
        $post = $_POST;
        if (!is_numeric($post['id'])){
             echo json_encode(['code'=>2,'msg'=>'检查参数是否合法']);exit;
         }
        $id = $post['id'];

         $record = $this->db->GetOne("SELECT `id`,`is_bg` FROM `@#_login_bg` where `id`='$id'");
         if (empty($record)) {
             echo json_encode(['code'=>2,'msg'=>'未找到数据']);exit;
         }
         $this->db->Autocommit_start();
         $query = '';
         if ($record['is_bg'] != 2)$query = "UPDATE `@#_login_bg` SET `is_enable`=2 WHERE`is_bg` != 2";
         if ($record['is_bg'] == 2)$query = "UPDATE `@#_login_bg` SET `is_enable`=2 WHERE `is_bg` = 2";

         $res1 = $this->db->Query($query);
         $res2 = $this->db->Query("UPDATE `@#_login_bg` SET `is_enable`=1 WHERE `id` = '$id'");

         if ($res1 && $res2){
             $this->db->Autocommit_commit();
             echo json_encode(['code'=>1,'msg'=>'修改成功']);exit;
         }
         $this->db->Autocommit_rollback();
         echo json_encode(['code'=>2,'msg'=>'修改失败']);exit;
     }

     //删除
    public function loginDel($id=""){
        $post = $_POST;
        $id && $post['id'] = $id;
        if (!is_numeric($post['id'])){
            echo json_encode(['code'=>2,'msg'=>'检查参数是否合法']);exit;
        }
        $res = $this->db->Query("DELETE FROM `@#_login_bg` WHERE id='$post[id]'");
        if ($res){
            echo json_encode(['code'=>1,'msg'=>"删除成功"]);exit;
        }else{
            echo json_encode(['code'=>0,'msg'=>"删除失败"]);exit;
        }

    }

    //编辑
    public function loginEditor(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $post = $_POST;
            try{
                if (!empty($post['bgimg'])){
                    $addUrl = explode("codes/",$post['bgimg']);
                    if (empty($addUrl[0]))
                    {
                        $post['bgimg'] = getServerHttp(2)."/statics/uploads/".$post['bgimg'];
                    }
                }
                $isjudge = $this->setDataLoginBg($post);

                $data = [];
                if ($isjudge['code'] == 1 ) {
                    echo json_encode($isjudge);exit;
                }else{
                    $data = $isjudge['data'];
                }

                //获取模板code
                //判断是什么类型
                $codes = '';
                if ($data['is_bg'] == 3){
                    $codes = $this->cssCode(3,'body','','','',$data['bgimg']);
                }

                //设置背景色
                if ($data['is_bg'] == 1){
                    //渐变色设置
                    if (!empty($data['color2'])){
                        $codes = $this->cssCode(2,'body',$data['color1'],$data['color2'],$data['gradient_direction']);
                    }

                    //背景色设置
                    if (empty($data['color2'])){
                        $codes = $this->cssCode(1,'body',$data['color1']);
                    }
                }

                //设置背景色
                if ($data['is_bg'] == 2){
                    //渐变色设置
                    if (!empty($post['color2'])){
                        $codes = $this->cssCode(2,'.bg_f63',$data['color1'],$data['color2'],$data['gradient_direction']);
                    }

                    //背景色设置
                    if (empty($data['color2'])){
                        $codes = $this->cssCode(1,'.bg_f63',$data['color1']);
                    }
                }
                if (!empty($data['bgimg'])){
                    $addUrl = explode("codes/",$data['bgimg']);
                    if (empty($addUrl[0]))
                    {
                        $data['bgimg'] = getServerHttp(2)."/statics/uploads/".$data['bgimg'];
                    }
                }
                $content = json_encode($data);

                $sql="UPDATE `@#_login_bg` SET `title`='$data[title]',`bgimg`='$data[bgimg]',`codes`='$codes',`content`='$content',`gradient_direction`='$data[gradient_direction]' WHERE `id` = '$post[id]'";
                $res = $this->db->Query($sql);
                if ($res){
                    echo json_encode(['code'=>1,'msg'=>'修改成功']);exit;
                }
                echo json_encode(['code'=>2,'msg'=>'修改失败']);exit;
            }catch (Exception $e){
                echo json_encode(['code'=>2,'msg'=>'网络繁忙']);exit;
            }
        }

        $id = intval($this->segment(4));
        if (!is_numeric($id)){
            $id = '';
        }
        $record = $this->db->GetOne("SELECT `id`,`content`,`title`,`bgimg` FROM `@#_login_bg` where `id`='$id'");
        $data = json_decode($record['content'],true);
        include $this->tpl(ROUTE_M,'codes.logineditor');
    }
    /*获取分享页设置*/
    public function uploadconfig(){
        $this->ment = array(
//            array("init","分享图设置",ROUTE_M."/codes/index"),
            array("init","风格设置",ROUTE_M."/codes/uploadconfig"),
        );
        $data = $this->db->GetOne("SELECT * FROM  `@#_upload_config`");
        $photo = json_decode($data['banner'],true);
        include $this->tpl(ROUTE_M,'codes.uploadconfig');
    }
    public function uploadpost(){
        $data = $_POST;
        $arr = [];
        foreach ($data['banner'] as $k=>$v){
            if(empty($v)){
                continue;
            }
            $arr[]= $v;
        }
        $arr = json_encode($arr);
        $time = time();
        $sql  = "UPDATE `@#_upload_config` SET `upload_img` = '$data[upload_img]' , `title` = '$data[title]' , `apk_upload` = '$data[apk_upload]' , `ios_upload` = '$data[ios_upload]' , `desc` = '$data[desc]', `banner` = '$arr' , `edition` = '$data[edition]' , `update_time` = '$time' where 1 ";
//        echo $sql;
//        exit();
        $reconfig = $this->db->Query($sql);
        if($reconfig){
            _message('修改完成');
        }else{
            _messageerror('修改失败');
        }
    }

    public function promotion_code(){
        $this->ment = array(
            array("promotion_code","代码设置",ROUTE_M."/codes/promotion_code"),
            array("promotion_code_add","添加代码",ROUTE_M."/codes/promotion_code_add"),
        );
        $num=20;
        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_promotion_code` WHERE 1");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $list = $this->db->GetPage("SELECT * FROM `@#_promotion_code`",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        foreach ($list as $key => $value) {
            $list[$key]['type_name'] = '';
            foreach($this->get_promotion_code_types() as $vl){
                if($value['type'] == $vl['id']){
                    $list[$key]['type_name'] = $vl['name'];
                }
            }
        }
        include $this->tpl(ROUTE_M,'codes.promotion_code');
    }

    private function get_promotion_code_types(){
        return [
            ["id"=>"google_statistics_url","name"=>"谷歌统计"],
            ["id"=>"google_statistics_content","name"=>"APP个推代码"],
            ["id"=>"google_statistics_android","name"=>"谷歌安卓统计"],
            ["id"=>"google_statistics_ios","name"=>"谷歌苹果统计"],
            ["id"=>"google_statistics_push","name"=>"推送统计"],
            ["id"=>"google_statistics_other","name"=>"其他统计"],
            ["id"=>"facebook_pixel_id","name"=>"Facebook_pixel_id"],
            ["id"=>"facebook_api_id","name"=>"Facebook转换api"],
            ["id"=>"Kwai_pixel_id","name"=>"Kwai 像素id"],
            ["id"=>"Tiktok_pixel_id","name"=>"Tiktok 像素id"],
            ["id"=>"Gtag_pixel_id","name"=>"Gtag 像素id"],
            ["id"=>"Liftoff_pixel_id","name"=>"Liftoff 像素id"],
            ["id"=>"customize_pixel_id","name"=>"自定义渠道标 像素id"],
        ];
    }

    public function promotion_code_add(){
        $this->ment = array(
            array("promotion_code","代码设置",ROUTE_M."/codes/promotion_code"),
            array("promotion_code_add","添加代码",ROUTE_M."/codes/promotion_code_add"),
        );
        if (isset($_POST['submit'])){
            $post = $_POST;
            $data = [
                'host' => $post['host'],
                'name' => $post['name'],
                'value' => $post['value'],
                'type' => $post['type'],
                'desc' => $post['desc'],
            ];
            $sql = Assemblydata($data,"promotion_code");
            $res = $this->db->Query($sql);
            if ($res){
                _message("添加成功",G_MODULE_PATH.'/codes/promotion_code');
            } else{
                _messageerror("添加失败");
            }
        }
        $types = $this->get_promotion_code_types();
        include $this->tpl(ROUTE_M,'codes.promotion_code_add');
    }

    public function promotion_code_edit(){
        $this->ment = array(
            array("promotion_code","代码设置",ROUTE_M."/codes/promotion_code"),
            array("promotion_code_add","添加代码",ROUTE_M."/codes/promotion_code_add"),
        );
        $id = intval($this->segment(4));
        if (!is_numeric($id)){
            _message("参数错误");
        }
        $record = $this->db->GetOne("SELECT * FROM `@#_promotion_code` WHERE `id` = '$id'");
        if (isset($_POST['submit'])){
            $post = $_POST;
            $data = [
                'host' => $post['host'],
                'name' => $post['name'],
                'value' => $post['value'],
                'type' => $post['type'],
                'desc' => $post['desc'],
            ];
            $sql = Assemblydata($data,'promotion_code','up',[['id',$id]]);
            $res = $this->db->Query($sql);
            if ($res){
                _message("修改成功",G_MODULE_PATH.'/codes/promotion_code');
            } else{
                _messageerror("修改失败");
            }
        }
        $types = $this->get_promotion_code_types();
        include $this->tpl(ROUTE_M,'codes.promotion_code_add');
    }

    public function promotion_code_del(){
        $id = intval($this->segment(4));
        if (!is_numeric($id)){
            _message("参数错误");
        }
        $res = $this->db->Query("DELETE FROM `@#_promotion_code` WHERE `id` = '$id'");
        if ($res){
            _message("删除成功");
        } else{
            _messageerror("删除失败");
        }
    }
}