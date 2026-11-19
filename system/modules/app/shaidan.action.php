<?php

defined('G_IN_SYSTEM')or exit('no');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_app_class('base','app','no');
System::load_sys_fun('user');
class shaidan extends base {
	public $db;
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
	}

	public function get_shaidan_money(){
		$money = System::load_app_config("user_fufen", "shaidan_money","member")?:0;
		$this->jsonData->success(200,"success",['money' =>$money]);
	}

	//晒单分享
	public function shaidan_list($type=0){
		$num = 10;
		$p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
		$star = ($p) * $num;
		if($type==1){
			$wheres = " and s.sd_userid = '". $this->uId. "'";
		}else{
			$wheres = "and s.status = 1 ";
		}
		$state = ["1"=>"待审核","2"=>"通过审核","3"=>"未通过"];
		$sql = "SELECT s.*,m.username,m.img from `@#_shaidan` s left join `@#_member` m on s.sd_userid = m.uid where 1 and s.examine_status = 2 $wheres order by sd_time desc limit $star,$num";
		$list = $this->db->GetList($sql);
		$money = System::load_app_config("user_fufen", "shaidan_money","member")?:0;
		foreach($list as &$vl){
			$vl['sd_photolist'] = explode(';',$vl['sd_photolist']);
			$vl['sd_time'] = date('Y-m-d h:i:s',$vl['sd_time']);
			$vl['thumb'] = G_UPLOAD_PATH.'/'. $vl['img'];
			if($type==1){
				$vl['state'] = $state[$vl['examine_status']];
				$vl['money'] = $money;
			}
		}
		$this->jsonData->success(200,"",$list?:[]);
	}

	public function shaidan_list_my(){
		$this->shaidan_list(1);
	}

	//是否开启晒单1开启，2关闭
	public function shaidan_status(){
		$is_shaidan = $this->getConfig("is_shaidan")?:1;
		$this->jsonData->successCode('','',["status"=>$is_shaidan]);
	}

	//添加晒单
	public function shaidan_add(){
		$is_shaidan = $this->getConfig("is_shaidan")?:1;
		if($is_shaidan !=1){
			$this->jsonData->error(200,"晒单功能未开启");
		}
		$msg = '';
		if($_POST['title']==null)$msg = "标题不能为空";	
		if($_POST['content']==null) $msg = "内容不能为空";	
		if(empty($_POST['file_up'])){
			$msg = "图片不能为空";
		}
		if($msg){
			$this->jsonData->error(200,$msg);
            return;
		}
		$pic=$_POST['file_up'];
		$pics = explode(';',$pic);
		$src=trim($pics[0]);
		$size=getimagesize(G_UPLOAD_PATH."/".$src);
		$width=220;
		$height=$size[1]*($width/$size[0]);
		$thumbs=tubimg($src,$width,$height);				
		$sd_userid=$this->uId;
		$id=$_POST['shopid'];  //订单id
		$sd_title=$_POST['title'];
		$sd_thumbs="shaidan/".$thumbs;
		$sd_content=$_POST['content'];
		$sd_photolist=$pic;
		$sd_time=time();			
		$sd_ip = _get_ip_dizhi();			

		//是否下单
		$order = $this->db->GetOne("select * from `@#_member_go_record` where `uid`='$this->uId' and `id`='$id'");
        if(!$order){
            $this->jsonData->error(200,"您未下单该商品");
            return;
        }elseif($order['status']!="已付款,已发货,已完成"){
			$this->jsonData->error(200,"请在收货后进行晒单");
            return;
		}
		$sd_shopid = $order['shopid'];
		//是否已晒单
		$shaidan = $this->db->GetOne("select * from `@#_shaidan` where sd_userid='$this->uId' and sd_shopid='$sd_shopid'");
        if($shaidan){
            $this->jsonData->error(200,"您已晒单过该商品");
            return;
        }
		$qishu= $this->db->GetOne("select `qishu`, `id` from `@#_shoplist` where `id`='$sd_shopid'");
		if(!$qishu){
			$qishu= $this->db->GetOne("select `qishu`, `id` from `@#_shoplist_copy` where `id`='$sd_shopid'");
			if(!$qishu){
                $this->jsonData->error(200,"该商品不存在");
                return;
            }
        }
		$qs = $qishu['qishu'];
		$ids = $qishu['id']; 
		$res = $this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_shopid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`)VALUES ('$sd_userid','$ids','$qs','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')");
		$this->jsonData->success("200","成功",[]);
	}

	public function shaidan_detail(){
		$sd_id=$_POST['id'];
		$shaidan=$this->db->GetOne("SELECT s.*,m.username,m.img from `@#_shaidan` s left join `@#_member` m on s.sd_userid = m.uid where `sd_id`='$sd_id'");
		if(!$shaidan){
			$this->jsonData->error(200,"该晒单不存在");
            return;
		}
		$goods = $this->db->GetOne("select * from `@#_shoplist_copy` where `id` = '$shaidan[sd_shopid]' order by `qishu` DESC");
		if(!$goods){
			$goods = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$shaidan[sd_shopid]' order by `qishu` DESC");
		}
		$shaidan_hueifu=$this->db->GetList("select s.*,m.username,m.img from `@#_shaidan_hueifu` s left join `@#_member` m on s.sdhf_userid = m.uid where `sdhf_id`='$sd_id' order by sdhf_time desc limit 10");
		foreach($shaidan_hueifu as &$vl){
			$vl['sdhf_time'] = date("Y-m-d H:i:s",$vl['sdhf_time']);
			if($vl['sdhf_img']){
                $vl['sdhf_img'] = G_UPLOAD_PATH.'/'. $vl['sdhf_img'];
			}
			$vl['thumb'] = G_UPLOAD_PATH.'/'. $vl['img'];
		}
		$shaidan["sd_photolist"]=explode(";",$shaidan['sd_photolist']);
		$shaidan['sd_time'] = date("Y-m-d H:i:s",$shaidan['sd_time']);
		$shaidan['thumb'] = G_UPLOAD_PATH.'/'. $shaidan['img'];
		$goods['q_user'] = unserialize($goods['q_user']);
		$goods['q_content'] = unserialize($goods['q_content']);
		$recodeinfo=$this->db->GetOne("select sum(`gonumber`) as sum from `@#_member_go_record` where `uid` ='{$shaidan["sd_userid"]}'  and `shopid`='$sd_id' ");
		$goods['gonumber'] = $recodeinfo['sum'];
		$data = [
			"shaidan" => $shaidan,
			'goods' => $goods,
			"shaidan_hueifu" => $shaidan_hueifu,
		];
		$this->jsonData->success(200,"成功",$data);
	}

	public function shaidan_del(){
		$sd_id=$_POST['id'];
        $shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
        if(!$shaidan){
            $this->jsonData->error();
        }
        $res = $this->db->Query("DELETE FROM `@#_shaidan` WHERE `sd_id`='$sd_id'");
        $res = $this->db->Query("DELETE FROM `@#_shaidan_huifu` WHERE `sdhf_id`='$sd_id'");
        $res = $this->db->Query("DELETE FROM `@#_shaidan_like` WHERE `sd_id`='$sd_id'");
        $this->jsonData->success("200","成功",[]);
	}
	
	public function shaidan_huifu(){
		$sdhf_id=$_POST['id'];
		$num = 10;
		$p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
		$star = ($p) * $num;
		$shaidan_hueifu=$this->db->GetList("select s.*,m.username,m.img from `@#_shaidan_hueifu` s left join `@#_member` m on s.sdhf_userid = m.uid where `sdhf_id`='$sdhf_id' order by sdhf_time desc limit $star,$num");
		foreach($shaidan_hueifu as &$vl){
			$vl['sdhf_time'] = date("Y-m-d H:i:s",$vl['sdhf_time']);
			if($vl['sdhf_img']){
                $vl['sdhf_img'] = G_UPLOAD_PATH.'/'. $vl['sdhf_img'];
			}
			$vl['thumb'] = G_UPLOAD_PATH.'/'. $vl['img'];
		}
        $this->jsonData->success(200,"成功",$shaidan_hueifu);
	}
	
	public function shaidan_huifu_add(){
		$sdhf_id=$_POST['id'];
        $sdhf_content=$_POST['content'];
        $sdhf_img=$_POST['img'];
        $sdhf_time=time();           
        $sdhf_userid=$this->uId;
		$username = $this->userInfo()['username'];
		if(!$sdhf_userid){
			$this->jsonData->error('601',"请先登录");
		}
		$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sdhf_id'");
		if(!$shaidan){
			$this->jsonData->error();
		}
		$sd_ping=$shaidan['sd_ping']+1;
		$this->db->Query("UPDATE `@#_shaidan` SET sd_ping='".$sd_ping."' where sd_id='".$sdhf_id."'");

		$data = [
			"sdhf_id" => $sdhf_id,
            "sdhf_userid" => $sdhf_userid,
            "sdhf_content" => $sdhf_content,
            "sdhf_img" => $sdhf_img,
            "sdhf_time" => $sdhf_time,
            "sdhf_username" => $username,
		];
		$sql = Assemblydata($data,"shaidan_hueifu");
        $res = $this->db->Query($sql);
        $this->jsonData->success("200","成功",[]);
	}

	public function shaidan_hueifu_del(){
		$sdhf_id=$_POST['id'];
        $shaidan_hueifu=$this->db->GetOne("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sdhf_id'");
        if(!$shaidan_hueifu){
            $this->jsonData->error();
        }
		$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sdhf_id'");
		if(!$shaidan){
			$this->jsonData->error();
		}
		$sd_ping=$shaidan['sd_ping']-1;
		$this->db->Query("UPDATE `@#_shaidan` SET sd_ping='".$sd_ping."' where sd_id='".$sdhf_id."'");

        $res = $this->db->Query("DELETE FROM `@#_shaidan_hueifu` WHERE `sdhf_id`='$sdhf_id'");
        $this->jsonData->success("200","成功",[]);
	}
	
	//点赞
	public function shaidan_like(){
		$sd_id=$_POST['id'];
        $this->db->Autocommit_start();
		$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
		if(!$shaidan){
            $this->jsonData->error();
		}
		$res = $this->db->GetOne("SELECT * FROM `@#_shaidan_like` WHERE `sd_id` = '".$sd_id."'");
		$num = 1;
		if(!$res){
			$msg = "点赞成功";
			$num = 1;
			$sql = Assemblydata(["sd_id" => $sd_id,'time'=>time(),'uid'=>$this->uId,"status"=>1],"shaidan_like");
		}elseif($res['status'] ==0){
			$msg = "点赞成功";
			$num = 1;
			$sql = Assemblydata(["sd_id" => $sd_id,'time'=>time(),'uid'=>$this->uId,"status"=>1],"shaidan_like","up",[['id',$res['id']]]);
		}else{
			$msg = "取消成功";
			$num = -1;
			$sql = Assemblydata(["sd_id" => $sd_id,'time'=>time(),'uid'=>$this->uId,"status"=>0],"shaidan_like","up",[['id',$res['id']]]);
		}
		$sd_zhan=$shaidan['sd_zhan']+$num;
		$res1 = $this->db->Query("UPDATE `@#_shaidan` SET sd_zhan='".$sd_zhan."' where sd_id='".$sd_id."'");
		$res2 = $this->db->Query($sql);
		if($res1 && $res2){
			$this->db->Autocommit_commit();
		}else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error();
		}
		$this->jsonData->success("200","成功",["zhan"=>$sd_zhan,"msg"=>$msg]);
	}
}
?>