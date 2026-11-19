<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class zp extends admin {
    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class('model');
        $this->ment=array(
            array("navigation","奖品列表",ROUTE_M.'/'.ROUTE_C),
            array("navigation","转盘已发货订单",ROUTE_M.'/'.ROUTE_C."/dingdan"),
            array("navigation","转盘未发货订单",ROUTE_M.'/'.ROUTE_C."/nodingdan"),	
            array("navigation","转盘规则",ROUTE_M.'/'.ROUTE_C."/guize"), 
            array("navigation","添加次数",ROUTE_M.'/'.ROUTE_C."/zpcishu"), 
            array("navigation","添加奖品",ROUTE_M.'/'.ROUTE_C."/add"), 
        ); 
    }

    public function init(){
        $lists=$this->db->GetList("SELECT * FROM `@#_member_zp`");
        data_format($lists,['money'],"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
        include $this->tpl(ROUTE_M,'zp_list');
    }

    //转盘次数修改
    public function zpcishu() {
        if(isset($_POST['submit'])){
            $uid=$_POST['uid'];
            $num=$_POST['num'];
            $time=time();
            $info=$this->db->Getone("SELECT * FROM `@#_zp_number` where uid=$uid");
            if($info){
                if($num < 0){
                    $num=intval($num);
                    $cnum= $info['num']-$num;
                    $insert=$this->db->Query("UPDATE `@#_zp_number` SET `number`= '$cnum' WHERE `uid`=$uid");
                }else{
                    $insert=$this->db->Query("UPDATE `@#_zp_number` SET `number`= `number`+$num,`time`='$time' WHERE `uid`=$uid");
                }
            }else{
                $num=intval($num);
                $insert=$this->db->Query("INSERT INTO `@#_zp_number` (`uid`,`number`,`time`)value('$uid','$num','$time')");
            }
            
            if($insert){
                _message("修改成功");
            }else{
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'zp_cishu');	 
    }

    public function guize(){
        $id=intval($this->segment(4));
        $wapone=$this->db->Getone("SELECT * FROM `@#_member`");
        if(isset($_POST['submit'])){
        $Grade=htmlspecialchars(trim($_POST['every'])); 
        $name=htmlspecialchars(trim($_POST['consumption']));
            $query=$this->db->Query("UPDATE `@#_member` SET `every`='$Grade',`consumption`='$name'");
            if($query){
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/guize");
            }else{
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'zp_guize');
    }

    //添加奖品
    public function add(){
        if(isset($_POST['submit'])){
            $Grade=htmlspecialchars(trim($_POST['Grade']));
            $name=htmlspecialchars(trim($_POST['name']));
            $money=htmlspecialchars(trim($_POST['money']));
            $probability=htmlspecialchars(trim($_POST['probability']));
            $type=htmlspecialchars(trim($_POST['type']));
            $ren=htmlspecialchars(trim($_POST['ren']));
            if($_FILES){
                $path='banner';
                $upload=System::load_sys_class('upload','sys','no');
                foreach($_FILES as $k=>$v){
                    if($v['error'] == '0'){
                        upload::upload_config($path);
                        $res=upload::go_uploads($_FILES[$k]);
                        if(!$res['ok']){
                            _message($res['msg']);
                        }else{
                            $img=$path.'/'.$res['dir'];
                        }
                    }
                }
            }
            $query = $this->db->Query("insert into `@#_member_zp`(`name`,`money`,`img`,`Grade`,`probability`,`type`,`ren`)values('$name','$money','$img','$Grade','$probability','$type','$ren')");	
            if($query){
                _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
            }else{
                _message("添加失败");
            }
        }

        include $this->tpl(ROUTE_M,'zp_add');
    }
    
    public function delete() {
        $id=intval($this->segment(4)); 
        $query=$this->db->Query("DELETE FROM `go_member_zp` WHERE (`id`='$id')");
        if($query){
            _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
        }else{
            _message("删除失败");
        }  
    }
    
    public function update(){
        $id=intval($this->segment(4));
        $wapone=$this->db->Getone("SELECT * FROM `@#_member_zp` where `id`='$id'");	
        
        if(isset($_POST['submit'])){
        $Grade=htmlspecialchars(trim($_POST['Grade'])); 
        $name=htmlspecialchars(trim($_POST['name']));
        $money=htmlspecialchars(trim($_POST['money']));
        $probability=htmlspecialchars(trim($_POST['probability']));
        $type=htmlspecialchars(trim($_POST['type']));
        $ren=htmlspecialchars(trim($_POST['ren']));

            if($_FILES){
                $path='banner';	
                $upload=System::load_sys_class('upload','sys','no');		
                foreach($_FILES as $k=>$v){
                    if($v['error'] == '0'){
                        upload::upload_config($path);
                        $res=upload::go_uploads($_FILES[$k]);	
                        if(!$res['ok']){	
                            _message($res['msg']);				
                        }else{
                            $img=$path.'/'.$res['dir'];						
                        }
                    }
                }	
            }
            if(!$img){
                $img=$wapone['img'];
            }		
            $query=$this->db->Query("UPDATE `@#_member_zp` SET `img`='$img',`Grade`='$Grade',`name`='$name',`money`='$money',`probability`='$probability',`type`='$type',`ren`='$ren' WHERE `id`=$id");
            if($query){
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
            }else{
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'zp_update');
    }

    public function zpupdate(){
        $id=intval($this->segment(4));
        $wapone=$this->db->Getone("SELECT * FROM `@#_member`");	
        
        if(isset($_POST['submit'])){
        $Grade=htmlspecialchars(trim($_POST['every'])); 
        $name=htmlspecialchars(trim($_POST['consumption']));

            if($_FILES){
                $path='banner';	
                $upload=System::load_sys_class('upload','sys','no');		
                foreach($_FILES as $k=>$v){
                    if($v['error'] == '0'){
                        upload::upload_config($path);
                        $res=upload::go_uploads($_FILES[$k]);	
                        if(!$res['ok']){	
                            _message($res['msg']);				
                        }else{
                            $img=$path.'/'.$res['dir'];						
                        }
                    }
                }	
            }
            if(!$img){
                $img=$wapone['img'];
            }		
            $query=$this->db->Query("UPDATE `@#_member_zp` SET `img`='$img',`Grade`='$Grade',`name`='$name',`money`='$money',`probability`='$probability',`type`='$type',`ren`='$ren' WHERE `id`=$id");
            if($query){
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
            }else{
                _message("修改失败");
            }
        }
        include $this->tpl(ROUTE_M,'zp_guizhe');
    }

    //已发货
    public function  dingdan() {
        $dingdan = $this->db->GetList("select * from `@#_zp_zj` where `type`=2");
        include $this->tpl(ROUTE_M,'zp_dingdan');
    }
    
    //未发货	
    public function  nodingdan() {
        $dingdan = $this->db->GetList("select * from `@#_zp_zj` where `type`=1");
        include $this->tpl(ROUTE_M,'zp_nodingdan');
    }
    
    public function zp_dd() {
        $id=intval($_POST['id']);
        $status=intval($_POST['type']);
        $info=$this->db->Getone("select * from `@#_zp_zj` where id='$id'");
        if($status == '1'){
            $sta='1';
            $zt="";
        }else{
            $sta='2';
        }
        if($info){
            $res=$this->db->Query("UPDATE `@#_zp_zj` SET `type`='$sta' where id='$id'");
        }
        echo $sta;
    }

    public function zp_dingdan1(){
        $code=abs(intval($this->segment(4)));
        $mobile=$this->db->Getone("SELECT * FROM `@#_zp_zj` WHERE `id`= $code");
        $uid= $mobile['uid'];
        $user=$this->db->Getone("SELECT * FROM `@#_member` WHERE `uid`= $uid");
        $id=$mobile['addrid'];
        $user_dizhi=$this->db->Getone("SELECT * FROM `@#_member_dizhi` WHERE  `id` = $id");
        $company = $_POST['company'];
        $company_code = $_POST['company_code'];
        if ($company) {
            $insert=$this->db->Query("UPDATE `@#_zp_zj` SET `kd`= '$company' ,`kdd` = '$company_code ' WHERE `id`=$code");
            if ($insert) {
                _message("提交成功");
            }else{
                _message("提交失败");
            }
        }
        
        include $this->tpl(ROUTE_M,'zp_dingdan1');	
    }
}

?>