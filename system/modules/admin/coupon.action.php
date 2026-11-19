<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class coupon extends admin {
    private $db;
    private $type_text = ['1'=>"金额","2"=>"折扣"];
    private $model_text = ['1'=>"充值","2"=>"商品"];
    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class('model');
        $this->ment=array(
            array("navigation","优惠券列表",ROUTE_M.'/'.ROUTE_C."/coupon_list"),
            array("navigation","添加优惠券",ROUTE_M.'/'.ROUTE_C."/add"), 
            array("navigation","修改优惠券",ROUTE_M.'/'.ROUTE_C."/edit",1), 
        ); 
    }

    /**
     * 优惠券列表
     */
    public function coupon_list(){
        $where = '';
        if ($_GET['keyword']) {
            $where.= " and `name` LIKE '%{$_GET['keyword']}%'";
        }
        $page = isset($_GET['page'])? $_GET['page'] : 1;
        $pagesize = 20;
        $offset = ($page - 1) * $pagesize;
        $total = $this->db->GetCount("select * from `@#_coupon` where 1 $where");
        $page = System::load_sys_class('page');
        $page->config($total,$pagesize,$page);
        $list = $this->db->GetPage("select * from `@#_coupon` where 1 $where order by id desc",array("num"=>$pagesize,"page"=>$page,"type"=>1,"cache"=>0));
        foreach ($list as &$val) {
            $val['type_text'] = $this->type_text[$val['type']];
            $val['model_text'] = $this->model_text[$val['model']];
            $val['expire_type_text'] = $val['expire_type'] == 1 ? '固定时间' : '领取后';
            $val['expire_start'] = date('Y-m-d H:i:s',$val['expire_start']);
            $val['expire_end'] = date('Y-m-d H:i:s',$val['expire_end']);
            if($val['receive_start']){
                $val['receive_start'] = date('Y-m-d H:i:s',$val['receive_start']);
            }else{
                $val['receive_start'] = '';
            }
            if($val['receive_end']){
                $val['receive_end'] = date('Y-m-d H:i:s',$val['receive_end']);
            }else{
                $val['receive_end'] = '';
            }
        }
        include $this->tpl(ROUTE_M,'coupon.list');
    }

    //添加奖品
    public function add(){
        if(isset($_POST['submit'])){
            $name=htmlspecialchars(trim($_POST['name']));
            $money=htmlspecialchars(trim($_POST['money']));
            $discount=htmlspecialchars(trim($_POST['discount']));
            $type=htmlspecialchars(trim($_POST['type']));
            $day=htmlspecialchars(trim($_POST['day']));
            $time=htmlspecialchars(trim($_POST['time']));
            $model=htmlspecialchars(trim($_POST['model']));
            $desc=htmlspecialchars(trim($_POST['desc']));
            $receive_start = strtotime($_POST['receive_start']);
            $receive_end = strtotime($_POST['receive_end']);
            $expire_type = isset($_POST['expire_type']) ? $_POST['expire_type'] : 1;
            $expire_start = strtotime($_POST['expire_start']);
            $expire_end = strtotime($_POST['expire_end']);
            if(empty($name)){
                _messageerror("名称不能为空");
            }
            if($type==1 && $money<=0){
                _messageerror("金额不能为空或小于等于0");
            }
            if($type==2 && $discount<=0){
                _messageerror("折扣不能为空或小于等于0");
            }
            if($day <=0 && $time <= 0 && $expire_type == 1){
                _messageerror("有效时间不能为空");
            }
            if(empty($model)){
                _messageerror("使用类型不能为空");
            }
            if($expire_start && $expire_end && $expire_start > $expire_end){
                _messageerror("有效期开始时间不能大于有效期结束时间");
            }
            if($receive_start && $receive_end && $receive_start > $receive_end){
                _messageerror("领取开始时间不能大于领取结束时间");
            }

            $data = [
                "name"  => $name,
                "money"  => $money,
                "discount"  => $discount,
                "type"  => $type,
                "day"  => $day,
                "time"  => $time,
                "model"  => $model,
                "receive_start"  => $receive_start,
                "receive_end"  => $receive_end,
                "expire_type"  => $expire_type,
                "expire_start"  => $expire_start,
                "expire_end"  => $expire_end,
                "desc"  => $desc,
            ];
            $sql = Assemblydata($data,"coupon");
            $res = $this->db->query($sql);
            if($res){
                _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/coupon_list");
            }else{
               echo ($sql);die;
                _messageerror("添加失败");
            }
        }else{
            $categoryList = $this->db->GetList("select `cateid`,`name` from `@#_category` where `model`=1 and `type`=2 and `parentid`<=0");
            if(!empty($categoryList))$res['classGoodId'] = $categoryList;
        }

        include $this->tpl(ROUTE_M,'coupon.add');
    }
    
    public function edit(){
        $id = $this->segment(4);
        $res = $this->db->GetOne("SELECT * FROM `@#_coupon` where `id` = '$id'");
        $res['expire_start'] = date('Y-m-d H:i:s',$res['expire_start']);
        $res['expire_end'] = date('Y-m-d H:i:s',$res['expire_end']);
        $res['receive_start'] = date('Y-m-d H:i:s',$res['receive_start']);
        $res['receive_end'] = date('Y-m-d H:i:s',$res['receive_end']);
        
        if(isset($_POST['submit'])){
            $name=htmlspecialchars(trim($_POST['name']));
            $money=htmlspecialchars(trim($_POST['money']));
            $discount=htmlspecialchars(trim($_POST['discount']));
            $type=htmlspecialchars(trim($_POST['type']));
            $day=htmlspecialchars(trim($_POST['day']));
            $time=htmlspecialchars(trim($_POST['time']));
            $model=htmlspecialchars(trim($_POST['model']));
            $desc=htmlspecialchars(trim($_POST['desc']));
            $receive_start = strtotime($_POST['receive_start']);
            $receive_end = strtotime($_POST['receive_end']);
            $expire_type = isset($_POST['expire_type']) ? $_POST['expire_type'] : 1;
            $expire_start = strtotime($_POST['expire_start']);
            $expire_end = strtotime($_POST['expire_end']);
            if(empty($name)){
                _messageerror("名称不能为空");
            }
            if($type==1 && $money<=0){
                _messageerror("金额不能为空或小于等于0");
            }
            if($type==2 && $discount<=0){
                _messageerror("折扣不能为空或小于等于0");
            }
            if($day <=0 && $time <= 0 && $expire_type == 1){
                _messageerror("有效时间不能为空");
            }
            if(empty($model)){
                _messageerror("使用类型不能为空");
            }
            if($expire_start && $expire_end && $expire_start > $expire_end){
                _messageerror("有效期开始时间不能大于有效期结束时间");
            }
            if($receive_start && $receive_end && $receive_start > $receive_end){
                _messageerror("领取开始时间不能大于领取结束时间");
            }

            $data = [
                "name"  => $name,
                "money"  => $money,
                "discount"  => $discount,
                "type"  => $type,
                "day"  => $day,
                "time"  => $time,
                "model"  => $model,
                "receive_start"  => $receive_start,
                "receive_end"  => $receive_end,
                "expire_type"  => $expire_type,
                "expire_start"  => $expire_start,
                "expire_end"  => $expire_end,
                "desc"  => $desc,
            ];
            $sql = Assemblydata($data,"coupon","up",[["id",$id]]);
            $res = $this->db->query($sql);
            if($res){
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/coupon_list");
            }else{
                _message("修改失败");
            }
        }else{
            $categoryList = $this->db->GetList("select `cateid`,`name` from `@#_category` where `model`=1 and `type`=2 and `parentid`<=0");
            if(!empty($categoryList))$res['classGoodId'] = $categoryList;
        }
        include $this->tpl(ROUTE_M,'coupon.add');
    }

    public function delete() {
        $id=intval($this->segment(4));

        //判断用户时候有优惠卷未使用
        $mcId = $this->db->GetOne("select `id` from `@#_member_coupon` where `used`=1 and `c_id`='$id'");
        if ($mcId) {
            _message("该优惠卷有用户未使用，不能删除");
        }

        $query=$this->db->Query("DELETE FROM `@#_coupon` WHERE (`id`='$id')");

        if($query){
            _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/coupon_list");
        }else{
            _message("删除失败");
        }
    }
}

?>