<?php 

defined('G_IN_SYSTEM')or exit('no');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_app_fun('my','go');
System::load_app_fun('user','go');
// System::load_app_class("base","member","no");
System::load_app_class("init","go","no");
System::load_sys_fun('user');
class shaidan extends init {
	public $db;
	public function __construct(){	
		parent::__construct();
		$this->db=System::load_sys_class('model');		
		
	}
	
	public function init(){
		$num = 10;
		$p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 0; //分页
		$star = ($p) * $num;
		$type = $_GET['type']?:0;
		if($type==1){
			$wheres = " and s.sd_userid = '". $this->uId. "'";
		}else{
			$wheres = "and s.status = 1 ";
		}
		$state = ["1"=>"待审核","2"=>"通过审核","3"=>"未通过"];
		$sql = "SELECT s.*,m.username,m.img from `@#_shaidan` s left join `@#_member` m on s.sd_userid = m.uid where 1 and s.examine_status = 2 $wheres order by sd_time desc limit $star,$num";
		$sa_one = $this->db->GetList($sql);
		$total = $this->db->GetCount("SELECT s.*,m.username,m.img from `@#_shaidan` s left join `@#_member` m on s.sd_userid = m.uid where 1 and s.examine_status = 2 $wheres");
		$money = System::load_app_config("user_fufen", "shaidan_money","member")?:0;
		// 列表页评论数用真实条数，与详情页一致（避免 sd_ping 与 go_shaidan_hueifu 实际条数不一致）
		$ping_counts = array();
		if (!empty($sa_one)) {
			$sd_ids = array_map(function($v) { return intval($v['sd_id']); }, $sa_one);
			$ids_str = implode(',', $sd_ids);
			$ping_list = $this->db->GetList("SELECT sdhf_id, COUNT(*) AS cnt FROM `@#_shaidan_hueifu` WHERE sdhf_id IN ($ids_str) GROUP BY sdhf_id");
			foreach ($ping_list as $row) {
				$ping_counts[intval($row['sdhf_id'])] = intval($row['cnt']);
			}
		}
		foreach($sa_one as $key => $vl){
			$vl['sd_photolist'] = explode(';',$vl['sd_photolist']);
			$vl['sd_time'] = getLTF($vl['sd_time']);
			$vl['thumb'] = G_UPLOAD_PATH.'/'. $vl['img'];
			if (isset($ping_counts[$vl['sd_id']])) {
				$vl['sd_ping'] = $ping_counts[$vl['sd_id']];
			}
			if($type==1){
				$vl['state'] = $state[$vl['examine_status']];
				$vl['money'] = $money;
			}
			$sa_one[$key] = $vl;
		}
		include templates( "shaidan","index");
	}

	//详情
	public function detail(){
		$sdhf_id = $this->segment(4);
		$sd = $this->db->GetOne("select * from `@#_shaidan` where sd_id = '$sdhf_id'");
		// PHP 8.2 兼容：检查查询结果是否存在
		if(!$sd || empty($sd)){
			_message("该晒单不存在", WEB_PATH);
			return;
		}
		// 晒单图片：支持分号或逗号分隔，在控制器直接生成 HTML 确保详情页能显示
		$sd['sd_photolist'] = !empty($sd['sd_photolist']) ? explode(';', trim($sd['sd_photolist'])) : array();
		// 兼容逗号分隔或末尾带分号
		if (count($sd['sd_photolist']) === 1 && strpos($sd['sd_photolist'][0], ',') !== false) {
			$sd['sd_photolist'] = array_map('trim', explode(',', $sd['sd_photolist'][0]));
		}
		$sd['sd_photo_urls'] = array();
		$baseUrl = rtrim(G_UPLOAD_PATH, '/');
		$isFullUrl = function($path) {
			$path = trim($path);
			return (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0);
		};
		foreach($sd['sd_photolist'] as $p){
			$p = trim($p);
			if($p === '') continue;
			$sd['sd_photo_urls'][] = $isFullUrl($p) ? $p : $baseUrl.'/'.$p;
		}
		// 无多图时用封面图（sd_thumbs 或 img）兜底
		if (empty($sd['sd_photo_urls']) && !empty($sd['sd_thumbs'])) {
			$p = $sd['sd_thumbs'];
			$sd['sd_photo_urls'][] = $isFullUrl($p) ? $p : $baseUrl.'/'.$p;
		}
		if (empty($sd['sd_photo_urls']) && !empty($sd['img'])) {
			$p = $sd['img'];
			$sd['sd_photo_urls'][] = $isFullUrl($p) ? $p : $baseUrl.'/'.$p;
		}
		$sd_photos_html = '';
		foreach($sd['sd_photo_urls'] as $url){
			$sd_photos_html .= '<img src="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'" alt="" loading="lazy"/>';
		}
		if($sd_photos_html !== '') $sd_photos_html = '<div class="photos">'.$sd_photos_html.'</div>';
		// $sd['sd_time'] = date('Y-m-d h:i:s',$sd['sd_time']);
		$sd['thumb'] = G_UPLOAD_PATH.'/'. (isset($sd['img']) && !empty($sd['img']) ? $sd['img'] : '');

		//评论
		$num = 10;
		// PHP 8.2 兼容：检查 POST 参数是否存在，使用 GET 作为分页参数
		$p = isset($_GET['p']) ? intval($_GET['p']) : (isset($_POST['p']) ? intval($_POST['p']) : 0);
		$star = ($p) * $num;
		$shaidan_hueifu=$this->db->GetList("select s.*,m.username,m.img from `@#_shaidan_hueifu` s left join `@#_member` m on s.sdhf_userid = m.uid where `sdhf_id`='$sdhf_id' order by sdhf_time desc limit $star,$num");
		// 评论数用实际条数，与下方列表一致（避免 sd_ping 与真实条数不一致）
		$real_ping = intval($this->db->GetCount("SELECT COUNT(*) FROM `@#_shaidan_hueifu` WHERE `sdhf_id` = '$sdhf_id'"));
		$sd['sd_ping'] = $real_ping;
		// 当前用户 uid，用于模板判断是否显示“删除”
		$current_uid = isset($this->userinfo['uid']) ? intval($this->userinfo['uid']) : 0;
		// PHP 8.2 兼容：确保评论数据存在且为数组
		if(empty($shaidan_hueifu)){
			$shaidan_hueifu = array();
		}
		foreach($shaidan_hueifu as &$vl){
			// PHP 8.2 兼容：确保所有字段都有默认值
			if(!isset($vl['username'])){
				$vl['username'] = '';
			}
			if(!isset($vl['sdhf_time'])){
				$vl['sdhf_time'] = time();
			}
			if(!isset($vl['sdhf_content'])){
				$vl['sdhf_content'] = '';
			}
			// $vl['sdhf_time'] = date("Y-m-d H:i:s",$vl['sdhf_time']);
			if(!empty($vl['sdhf_img'])){
                $vl['sdhf_img'] = G_UPLOAD_PATH.'/'. $vl['sdhf_img'];
			}else{
				$vl['sdhf_img'] = '';
			}
			$vl['thumb'] = G_UPLOAD_PATH.'/'. (isset($vl['img']) && !empty($vl['img']) ? $vl['img'] : '');
		}
		unset($vl); // 释放引用
		// 强制清除本页模板缓存，确保前端修改生效
		$style = G_STYLE . DIRECTORY_SEPARATOR . G_STYLE_HTML;
		$cacheTpl = G_CACHES . 'caches_template' . DIRECTORY_SEPARATOR . dirname($style) . DIRECTORY_SEPARATOR . md5('shaidan.detail') . '.tpl.php';
		if (file_exists($cacheTpl)) { @unlink($cacheTpl); }
		include templates( "shaidan","detail");
	}

	//商品晒单列表历史期次
	public function itemiframstory(){
	
		$itemid=safe_replace($this->segment(4));

		$item=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid' LIMIT 1");
		$ids =  $item['id'];
		$qs = $item['qishu'];
		$shaidan=$this->db->GetList("select * from `@#_shaidan` where `sd_shopid`= $ids and  `sd_qishu`= $qs");
		if(!empty($shaidan)){
			$error = 0;
				foreach($shaidan as $key=>$val){
					$shaidan[$key]['sd_photolist'] = explode(';',rtrim($shaidan[$key]['sd_photolist'],';'));
					//组合字符串给前台
					$temp = '';
					foreach ($shaidan[$key]['sd_photolist'] as $v) {	
						$temp.= '<li><img src="'.G_UPLOAD_PATH.'/'.$v.'" border=0 width="70px" /></li>';
					}
					$shaidan[$key]['photolist'] = $temp;
					unset($temp);
					$member_info=$this->db->GetOne("select * from `@#_member` where `uid`='$val[sd_userid]'");
					$member_img[$val['sd_id']]=$member_info['img'];
					$member_id[$val['sd_id']]=$member_info['uid'];
					$member_username[$val['sd_id']]=$member_info['username'];
				}	
		}else{
			$error = 1;
		}
		
	
		include templates("index","itemiframstory"); 
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

	public function shaidan_del(){
		$sd_id=$_POST['id'];
        $shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
        if(!$shaidan){
            $this->jsonData->error();
        }
        $res = $this->db->Query("DELETE FROM `@#_shaidan` WHERE `sd_id`='$sd_id'");
        $res = $this->db->Query("DELETE FROM `@#_shaidan_hueifu` WHERE `sdhf_id`='$sd_id'");
        $res = $this->db->Query("DELETE FROM `@#_shaidan_like` WHERE `sd_id`='$sd_id'");
        $this->jsonData->success("200","成功",[]);
	}

	public function shaidan_huifu_add(){
		$sdhf_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$sdhf_content = isset($_POST['content']) ? $_POST['content'] : '';
		$sdhf_content = trim(strip_tags($sdhf_content));
		$sdhf_img = isset($_POST['img']) ? $_POST['img'] : '';
		$sdhf_time = time();
		$username = isset($this->userinfo['username']) ? $this->userinfo['username'] : '';
		if(!$this->userinfo || empty($this->userinfo['uid'])){
			$this->jsonData->error('601',"请先登录");
			return;
		}
		if ($sdhf_id <= 0) {
			$this->jsonData->error('400', '参数错误');
			return;
		}
		if ($sdhf_content === '') {
			$this->jsonData->error('400', '评论内容不能为空');
			return;
		}
		$max_comment_len = 500;
		if (function_exists('mb_strlen') && mb_strlen($sdhf_content) > $max_comment_len) {
			$this->jsonData->error('400', '评论内容不能超过' . $max_comment_len . '字');
			return;
		}
		if (strlen($sdhf_content) > $max_comment_len * 4) {
			$this->jsonData->error('400', '评论内容过长');
			return;
		}
		$shaidan = $this->db->GetOne("select * from `@#_shaidan` where `sd_id` = :sid", array('sid'=>$sdhf_id));
		if(!$shaidan){
			$this->jsonData->error('404', '晒单不存在');
			return;
		}
		$sd_ping=$shaidan['sd_ping']+1;
		$this->db->Query("UPDATE `@#_shaidan` SET sd_ping = :ping where sd_id = :sid", array('ping'=>$sd_ping, 'sid'=>$sdhf_id));

		$data = [
			"sdhf_id" => $sdhf_id,
            "sdhf_userid" => $this->userinfo['uid'],
            "sdhf_content" => $sdhf_content,
            "sdhf_img" => $sdhf_img,
            "sdhf_time" => $sdhf_time,
            "sdhf_username" => $username,
		];
		$sql = Assemblydata($data,"shaidan_hueifu");
        $res = $this->db->Query($sql);
        $this->jsonData->success("200","成功",[]);
	}

	// 删除单条评论：id 为评论记录主键，仅评论作者可删，并同步晒单 sd_ping 与后台
	public function shaidan_hueifu_del(){
		$comment_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if ($comment_id <= 0) {
			$this->jsonData->error('400', '参数错误');
			return;
		}
		$uid = isset($this->userinfo['uid']) ? intval($this->userinfo['uid']) : 0;
		if ($uid <= 0) {
			$this->jsonData->error('601', '请先登录');
			return;
		}
		$row = $this->db->GetOne("SELECT * FROM `@#_shaidan_hueifu` WHERE `id` = :id", array('id' => $comment_id));
		if (!$row) {
			$this->jsonData->error('404', '评论不存在');
			return;
		}
		if (intval($row['sdhf_userid']) !== $uid) {
			$this->jsonData->error('403', '只能删除自己的评论');
			return;
		}
		$sd_id = intval($row['sdhf_id']);
		$shaidan = $this->db->GetOne("SELECT * FROM `@#_shaidan` WHERE `sd_id` = :sid", array('sid' => $sd_id));
		if (!$shaidan) {
			$this->jsonData->error('404', '晒单不存在');
			return;
		}
		$sd_ping = max(0, intval($shaidan['sd_ping']) - 1);
		$this->db->Query("UPDATE `@#_shaidan` SET sd_ping = :ping WHERE sd_id = :sid", array('ping' => $sd_ping, 'sid' => $sd_id));
		$this->db->Query("DELETE FROM `@#_shaidan_hueifu` WHERE `id` = :id", array('id' => $comment_id));
		$this->jsonData->success("200", "已删除", array('zhan' => $shaidan['sd_zhan'], 'ping' => $sd_ping));
	}
	
	//羡慕（点赞）：未登录返回601跳转登录，已登录则已羡慕则减1、未羡慕则加1
	public function shaidan_like(){
		$sd_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if ($sd_id <= 0) {
			$this->jsonData->error();
			return;
		}
		$uid = isset($this->userinfo['uid']) ? intval($this->userinfo['uid']) : 0;
		if ($uid <= 0) {
			$this->jsonData->error('601', '请先登录');
			return;
		}
		$this->db->Autocommit_start();
		$shaidan = $this->db->GetOne("select * from `@#_shaidan` where `sd_id` = :sid", array('sid' => $sd_id));
		if(!$shaidan){
			$this->db->Autocommit_rollback();
			$this->jsonData->error();
			return;
		}
		$res = $this->db->GetOne("SELECT * FROM `@#_shaidan_like` WHERE `sd_id` = :sid AND `uid` = :uid", array('sid' => $sd_id, 'uid' => $uid));
		$num = 1;
		if(!$res){
			$msg = "羡慕成功";
			$num = 1;
			$sql = Assemblydata(["sd_id" => $sd_id,'time'=>time(),'uid'=>$uid,"status"=>1],"shaidan_like");
		}elseif($res['status'] == 0){
			$msg = "羡慕成功";
			$num = 1;
			$sql = Assemblydata(["sd_id" => $sd_id,'time'=>time(),'uid'=>$uid,"status"=>1],"shaidan_like","up",[['id',$res['id']]]);
		}else{
			$msg = "已取消";
			$num = -1;
			$sql = Assemblydata(["sd_id" => $sd_id,'time'=>time(),'uid'=>$uid,"status"=>0],"shaidan_like","up",[['id',$res['id']]]);
		}
		$sd_zhan = $shaidan['sd_zhan'] + $num;
		$res1 = $this->db->Query("UPDATE `@#_shaidan` SET sd_zhan = :zhan where sd_id = :sid", array('zhan' => $sd_zhan, 'sid' => $sd_id));
		$res2 = $this->db->Query($sql);
		if($res1 && $res2){
			$this->db->Autocommit_commit();
		}else{
			$this->db->Autocommit_rollback();
			$this->jsonData->error();
			return;
		}
		$this->jsonData->success("200","成功",["zhan"=>$sd_zhan,"msg"=>$msg]);
	}

}
?>