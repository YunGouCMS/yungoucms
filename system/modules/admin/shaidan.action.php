<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class shaidan extends admin {
	private $db;
	public function __construct(){
		parent::__construct();
		$this->db=$this->DB();
		$this->ment=array(
						array("lists","晒单列表",ROUTE_M.'/'.ROUTE_C."/lists"),
						array("lists","晒单回复列表",ROUTE_M.'/'.ROUTE_C."/shaidan_huifu",1),
						// array("insert","添加商品",ROUTE_M.'/'.ROUTE_C."/insert"),
		);
	}

	public function lists(){
		$where = '';
		if($_POST['shopid']){
			$where['shopid'] = $_POST['shopid'];
		}
		$sql = Assemblydata([],"shaidan","sel",$where);
		$num=20;
        $total=$this->db->GetCount($sql);
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $list=$this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		$state = ["1"=>"待审核",'2'=>"通过审核","3"=>"未通过"];
		foreach($list as $key=>$vl){
			$list[$key]['sd_time'] = date("Y-m-d H:i:s",$vl['sd_time']);
			$list[$key]['examine_state'] = $state[$vl['examine_status']];
			$list[$key]['sd_photolist'] = explode(",",$vl['sd_photolist']);
		}
		include $this->tpl(ROUTE_M,'shaidan.list');	
    }

	public function examine(){
		$id = $_POST['id'];
		$status = $_POST['status'];
		$msg = $_POST['msg'];
		$shaidan = $this->db->GetOne("select * from `@#_shaidan` where sd_id = $id ");
		if(!$shaidan){
			
			$this->jsonData->error(400,"晒单不存在");
            exit;
		}
		if($shaidan['examine_status'] == $status){
            $this->jsonData->error(400,"状态已修改");
            exit;
        }
		$this->db->Autocommit_start();
		$data = array(
			'examine_status'=>$status,
			'examine_text'=>$msg,
			"examine_time"=>time(),
			"examine_id"=>$this->AdminInfo['uid'],
			"examine_name"=>$this->AdminInfo['name'],
		);
		$sql = Assemblydata($data,"shaidan","up",[['sd_id',$id]]);
		$res = $this->db->query($sql);
		if($status != 2){
			if($res){
				$this->db->Autocommit_commit();
				$this->jsonData->success(200,"success");
			}else{
				$this->db->Autocommit_rollback();
                $this->jsonData->error(400,"fail");
			}
		}
		//晒单奖励
		$money = System::load_app_config("user_fufen", "shaidan_money","member")?:0;
		if($money<=0){
			$this->db->Autocommit_commit();
			$this->jsonData->success(200,"success");
		}
		$acc_data = [
			'uid'=>$shaidan['sd_userid'],
            // 'code'=>"",
            'type'=>"1",
            'pay'=>"账户",
            'content'=>"晒单奖励",
            'money'=>$money,
            'time'=>time(),
		];
		$sql = Assemblydata($acc_data,"member_account");
		$res_a = $this->db->Query($sql);
		//用户金额增加
		$sql = "UPDATE `@#_member` SET `money` = `money` + $money WHERE `uid` = ".$shaidan['sd_userid'];
        $res_m =$this->db->query($sql);
		if($res && $res_a && $res_m){
			$this->db->Autocommit_commit();
		}else{
			$this->db->Autocommit_rollback();
            $this->jsonData->error(400,"fail");
		}
		$this->jsonData->success(200,"success");
	}

	public function del(){
		$id = $this->segment(4);
		$sql = "DELETE FROM `@#_shaidan` WHERE `sd_id` = '$id'";
		$this->db->query($sql);
		$sql = "DELETE FROM `@#_shaidan_hueifu` WHERE `sdhf_id` = '$id'";
		$this->db->query($sql);
		$sql = "DELETE FROM `@#_shaidan_like` WHERE `sd_id` = '$id'";
		$this->db->query($sql);
		_message("删除成功");
	}

	public function shaidan_huifu(){
		$id = $this->segment(4);
		$member=$this->db->getlist("select * from `@#_member` where auto_user = 0");
		$num=20;
		$total=$this->db->GetCount("select * from `@#_shaidan_hueifu` where `sdhf_id` = '$id'"); 
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}		
		$page->config($total,$num,$pagenum,"0"); 
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}	
		$sql = "select h.*,m.username from `@#_shaidan_hueifu` h left join `@#_member` m on m.uid = h.sdhf_userid where h.`sdhf_id` = '$id' ";
		$shaidan=$this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));		
		include $this->tpl(ROUTE_M,'shaidan.huifu');
	}

	public function hf_del(){
		$id=intval($this->segment(4));
		$shaidanx=$this->db->getlist("select * from `@#_shaidan_hueifu` where `id`='$id' limit 1 ");
		if($shaidanx){
			$this->db->Query("DELETE FROM `@#_shaidan_hueifu` where `id`='$id' ");
			$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='{$shaidanx['sdhf_id']}'");
			if($shaidan){
				$sd_ping=$shaidan['sd_ping']-1;
				$this->db->Query("UPDATE `@#_shaidan` SET sd_ping='".$sd_ping."' where sd_id='{$shaidanx['sdhf_id']}'");
			}
			_message("删除成功");
		}else{
			_messageerror("参数错误");
		}
	}
}

?>