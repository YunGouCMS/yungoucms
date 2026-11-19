<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
class mobile extends base {
	public function __construct() {
		parent::__construct();
		$this->db=System::load_sys_class('model');
      	$this->symbol =$this->db->GetOne("SELECT * FROM `@#_currency` WHERE status='1'");
	}

	//首页
	public function init(){
	    $piracyData = $this->certificationinfo();
	   // p($piracyData);
	   // exit;
        $web_gzwm = $piracyData['web_gzwm'];

		$web_log=$piracyData['web_log'];
        $is_shownotice=$piracyData['is_shownotice'];
        $symbol =$this->symbol;
		$web_log=$web_log['value'];
        $is_shownotice=$is_shownotice['value'];
        $banner =_cfg('banner');
        $is_shownotice = _cfg('is_shownotice');
        if($is_shownotice == 2){
            $banner = '';
        }
		$webname=$this->_cfg['web_name'];
		//获取广告
		$shop_ad=$piracyData['shop_ad'];
		//最新获得
		$shopqishu=$piracyData['shopqishu'];
        $web_app = $piracyData['web_app'];
        unset($piracyData);
		$code=$this->segment(4);
        
		$member = _getcookie('uid');
//		var_dump($member);
		
		if(!empty($code)){
		  //  file_put_contents("code.txt","获取分享用户id:{$code}",FILE_APPEND);
            //取出用户id
            _setcookie("code",_encrypt($code,'ENCODE'),60*60*24*7);
        }
        
// 		$code=abs(intval(_encrypt(_getcookie("code"),'DECODE')));
// 		var_dump($code);
		include templates("mobile/index","index");
	}
	
	public function getcodes(){
	    $code=$this->segment(4);
	    if(!empty($code)){
		  //  file_put_contents("code.txt","获取分享用户id:{$code}",FILE_APPEND);
            //取出用户id
            _setcookie("code",_encrypt($code,'ENCODE'),60*60*24*7);
            $webPath = $money=$this->db->Getone("SELECT `value` FROM `go_config` WHERE name = 'web_path'")['value'];
            Header("Location: $webPath"); 

        }
        
        
        
	}

	//ajax获取首页商品列表信息
	public function initajax(){
		$cate_band =htmlspecialchars($this->segment(4));
		$select =htmlspecialchars($this->segment(5));
		$p =htmlspecialchars($this->segment(6)) ? $this->segment(6) :1;
		$member=$this->userinfo;
		$uid=$member['uid'];
		$end=10;
		$star=($p-1)*$end;
		$select_w = '';
		if($select == 10){
			$select_w = 'order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc';
		}
		if($select == 20){
			$select_w = 'order by `qishu` desc';
		}
		if($select == 30){
			$select_w = 'order by `money` desc';
		}
		if($select == 40){
			$select_w = 'order by `order` desc'; 
		}
      	if($select == 11){
			$select_w = 'order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) asc';
		}
		if($select == 21){
			$select_w = 'order by `qishu` asc';
		}
		if($select == 31){
			$select_w = 'order by `money` asc';
		}
		if($select == 40){
			$select_w = 'order by `order` asc'; 
		}

		if($select=='10'){
		
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}elseif($select=='20'){
		
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}elseif($select=='30') {
			
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}elseif ($select=='40') {
			
			$count=$this->db->GetList("select id from `@#_shoplist` where `renqi`=1 and `q_uid` is null AND ispk !=1 AND `shenyurenshu` !=0 $select_w");
		
		}

		if($select=='10'){
		
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `q_uid` is null AND shenyurenshu !=0 $select_w limit $star,$end");
		}elseif($select=='20'){
			
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `q_uid` is null AND shenyurenshu !=0 $select_w limit $star,$end");
		}elseif($select=='30') {
			
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `q_uid` is null AND shenyurenshu !=0 $select_w limit $star,$end");
		}elseif($select=='40') {
		
			$shoplist=$this->db->GetList("select id,title,zongrenshu,canyurenshu,qishu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `renqi`=1 and `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w limit $star,$end");
		}
	
      	if($select=='11'){
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}elseif($select=='21'){
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}elseif($select=='31') {
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}elseif ($select=='41') {
			$count=$this->db->GetList("select id from `@#_shoplist` where `renqi`=1 and `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}

      	if($select=='11'){
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `q_uid` is null AND shenyurenshu !=0 $select_w limit $star,$end");
		}elseif($select=='21'){
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `q_uid` is null AND shenyurenshu !=0 $select_w limit $star,$end");
		}elseif($select=='31') {
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `q_uid` is null AND shenyurenshu !=0 $select_w limit $star,$end");
		}elseif ($select=='41') {
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,qishu,canyurenshu,shenyurenshu,money,thumb,ssc,ispk from `@#_shoplist` where `renqi`=1 and `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w limit $star,$end");
		}
     	
		$max_renqi_qishu = 1;
		$max_renqi_qishu_id = 1;

		if($uid){
			foreach($shoplist as $k=>$v){
				$current=$this->db->GetOne("SELECT * FROM `@#_shopcollect` WHERE shopid=$v[id] AND uid=$uid");
				if($current){
					$shoplist[$k]['current']='current';
				}else{
					$shoplist[$k]['current']='';
				}
			}		
		}

		if(!empty($shoplistrenqi)){
			foreach ($shoplistrenqi as $renqikey =>$renqiinfo){
				if($renqiinfo['qishu'] >= $max_renqi_qishu){
					$max_renqi_qishu = $renqiinfo['qishu'];
					$max_renqi_qishu_id = $renqikey;
				}
			}
			$shoplistrenqi[$max_renqi_qishu_id]['t_max_qishu'] = 1;
		}

		$this_time = time();
		if(count($shoplist) > 1){
					if($shoplist[0]['time'] > $this_time - 86400*3)
					$shoplist[0]['t_new_goods'] = 1;
		}
		$pagex=ceil(count($count)/$end);
		if($p<=$pagex){
			$shoplist[0]['page']=$p+1;
		}
		if($pagex>0){
			$shoplist[0]['sum']=$pagex;
		}else if($pagex==0){
			$shoplist[0]['sum']=$pagex;
		}

		echo json_encode($shoplist);
	}

	//时时彩商品列表
	public function ssc_glist(){
        $webname="透明购专区";
		$title="透明购专区";
		$key="透明购专区"; 
		$shoplist=$this->db->GetList("SELECT `qishu`,`thumb`,`title`,`id`,`sid`,`zongrenshu`,`canyurenshu`,`shenyurenshu`,`money` FROM `@#_shoplist` WHERE zongrenshu!=canyurenshu AND `ssc`='1' order by `canyurenshu` DESC");
		$member=$this->userinfo;
        $symbol=$this->symbol;
		$uid=$member['uid'];
		if($uid){
			foreach($shoplist as $k=>$v){
				$current=$this->db->GetOne("SELECT * FROM `@#_shopcollect` WHERE shopid=$v[id] AND uid=$uid");
				if($current){
					$shoplist[$k]['current']='current';
				}else{
					$shoplist[$k]['current']='';
				}
				if($v['ssc'] == 1){
					$shoplist[$k]['ul']="<i class='zimgs'></i>";
				}elseif($v['xg']>0){
					$shoplist[$k]['ul']="<i class='xgimgs'></i>";
				}elseif($v['bzqf'] == 1){
					$shoplist[$k]['ul']="<i class='qfimgs'></i>";
				}
			}		
		}
		include templates("mobile/index","ssc_glist"); 
	}
	
	//商品列表
	public function glist(){
        $webname="所有商品";
		$title="商品列表";
		$key="所有商品";
		$cateid=htmlspecialchars($this->segment(4));
        $symbol=$this->symbol;
		if(!$cateid)$cateid=0;
		$fenname=$this->db->GetOne("SELECT name FROM `@#_category` WHERE `cateid`  = '$cateid'");
		if(!$fenname){
			$fenname['name']='全部商品';
		}
		if($cateid == '101'){
			$fenname['name']='PK夺宝';
		}
		include templates("mobile/index","glist");
	}
	
	public function fen(){
		$fenid =htmlspecialchars($this->segment(4));
		$fenname=$this->db->GetOne("SELECT name FROM `@#_category` WHERE `cateid`  = '$fenid'");
        $webname=$this->_cfg['web_name'];
		$title="$fenname[name]"._cfg("web_name");
		$key=$fenname[name];
		$shoplist=$this->db->GetList("SELECT `qishu`,`thumb`,`title`,`id`,`sid`,`zongrenshu`,`canyurenshu`,`shenyurenshu`,`money` FROM `@#_shoplist` WHERE `q_user`  = '' AND `cateid`='$fenid' order by `canyurenshu` DESC");
		$list=count($shoplist);
		include templates("mobile/index","fen");
	}
	
	//网站公告
	public function gonggao(){
        $webname=$this->_cfg['web_name'];
		$title="网站公告";
		$key="网站公告";
		include templates("mobile/index","gonggao");
	}
	
	//公告详情
	public function gonggaoxq(){
        $webname=$this->_cfg['web_name'];
		$ggid =htmlspecialchars($this->segment(4));
		$gonggaoxq=$this->db->GetList("SELECT * FROM `@#_article` WHERE `id` = $ggid");
        $gonggaoxq[0]['content'] = replaceArticle($gonggaoxq[0]['content'],"#%webName%#");
        $gonggaoxq[0]['content'] = replaceArticle($gonggaoxq[0]['content'],"#%webUrl%#");
		include templates("mobile/index","gonggaoxq");
	}
    public function zp(){
    	$user=$this->userinfo;
    	$uid=$user['uid'];
        $st=strtotime(date('Y-m-d',time()));
		$et=strtotime( "+1 day",time());

      	$this->db->Query("UPDATE `@#_zp_zj` SET `type`=3 where time<'$st' and type='0' and state='0' ");//所有今天以前未领取自动放弃     
  
    	$dj=$this->db->GetList("SELECT a.*,b.img FROM `@#_zp_zj` as a left join `@#_member_zp` as b on a.Grade=b.Grade WHERE a.`uid`='$uid' order by a.time desc");

		include templates("mobile/index","zp");
	}
	
	//搜索
	public function search(){
        $webname=$this->_cfg['web_name'];
        $search =urldecode(htmlspecialchars($this->segment(4)));
        _strRegular('index',$search);
		$title=$search.' - '._cfg('web_name');
		$key="商品搜索";
		$shoplist=$this->db->GetList("SELECT `qishu`,`thumb`,`title`,`id`,`sid`,`zongrenshu`,`canyurenshu`,`shenyurenshu`,`money` FROM `@#_shoplist` WHERE `q_user`  = '' AND `title` LIKE '%".$search."%' order by `canyurenshu` DESC");
		$list=count($shoplist);
		include templates("mobile/index","search");
	}
  	//商品列表
	public function auto_glist(){
        $webname="实时购专区";
		$title="实时购专区";
		$key="实时购专区"; 
		$shoplist=$this->db->GetList("SELECT `qishu`,`thumb`,`title`,`id`,`sid`,`zongrenshu`,`canyurenshu`,`shenyurenshu`,`money` FROM `@#_shoplist` WHERE `q_user`  = '' AND `ssc`!='1' order by `canyurenshu` DESC");
		include templates("mobile/index","auto_glist"); 
	}

	//商品列表
	public function jflist(){
        $webname=$this->_cfg['web_name'];
		$title="积分购物";
		$key="积分购物";
		include templates("mobile/index","jflist");
	}
	
	//ajax获取商品列表信息
	public function glistajax(){
		$cate_band =htmlspecialchars($this->segment(4));
		$select =htmlspecialchars($this->segment(5));
		$p =htmlspecialchars($this->segment(6)) ? $this->segment(6) :1;
		if(!$select){
			$select = '10';
		}
		if($cate_band){
			$fen1 = intval($cate_band);
			$cate_band = 'list';
		}else{
			echo $shoplist[0]['sum']=0;exit;
		}
		if(empty($fen1)){
			$brand=$this->db->GetList("select * from `@#_brand` where 1 order by `order` DESC");
			$daohang = '所有分类';
		}else{
			$brand=$this->db->GetList("select * from `@#_brand` where `cateid`='$fen1' order by `order` DESC");
			$daohang=$this->db->GetOne("select * from `@#_category` where `cateid` = '$fen1' order by `order` DESC");
			$daohang = $daohang['name'];
		}
		$category=$this->db->GetList("select * from `@#_category` where `model` = '1'");

		//分页
		$end=10;
		$star=($p-1)*$end;
		$select_w = '';
		if($select == 10){
			$select_w = 'order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc';
		}
		if($select == 20){
			$select_w = "and `renqi` = '1'";
		}
		if($select == 30){
			$select_w = 'order by `canyurenshu` desc';
		}
		if($select == 40){
			$select_w = 'order by `time` DESC';
		}
		if($select == 50){
			$select_w = 'order by `money` DESC';
		}
		if($select == 60){
			$select_w = 'order by `money` ASC';
		}

		if($fen1){ 
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null and `cateid`='$fen1' AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}else{
			$count=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w");
		}
		if($fen1){
			$shoplist=$this->db->GetList("select `thumb`,`title` ,`id`,`money`,`canyurenshu`,`zongrenshu`,`shenyurenshu`,`qishu` from `@#_shoplist` where `q_uid` is null and `cateid`='$fen1' AND ispk !=1 AND shenyurenshu !=0 $select_w limit $star,$end");
		}else{
			$shoplist=$this->db->GetList("select `thumb`,`title` ,`id`,`money`,`canyurenshu`,`zongrenshu`,`shenyurenshu`,`qishu` from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 $select_w limit $star,$end");
		}
		$max_renqi_qishu = 1;
		$max_renqi_qishu_id = 1;

		if(!empty($shoplistrenqi)){
			foreach ($shoplistrenqi as $renqikey =>$renqiinfo){
				if($renqiinfo['qishu'] >= $max_renqi_qishu){
					$max_renqi_qishu = $renqiinfo['qishu'];
					$max_renqi_qishu_id = $renqikey;
				}
			}
			$shoplistrenqi[$max_renqi_qishu_id]['t_max_qishu'] = 1;
		}

		$this_time = time(); 
		if(count($shoplist) > 1){
					if($shoplist[0]['time'] > $this_time - 86400*3)
					$shoplist[0]['t_new_goods'] = 1;
					$shoplist[0]['c_id'] = 'list/'.$select;
		}
		$pagex=ceil(count($count)/$end);
		if($p<=$pagex){
			$shoplist[0]['page']=$p+1;
			if($pagex>0){
				$shoplist[0]['sum']=$pagex;
			}else if($pagex==0){
				$shoplist[0]['sum']=$pagex;
			}
		}else{
			$shoplist[0]['sum']=0;
		}
		echo json_encode($shoplist);
	}
	
	
	/****************************兼容模板****************************************/
	//ajax获取商品列表信息
	public function glistajax1(){
		$cate_band =htmlspecialchars($this->segment(4));
		$select =htmlspecialchars($this->segment(5));
		$p =htmlspecialchars($this->segment(6)) ? $this->segment(6) :1;
		if(!$select){
			$select = '10';
		}
		
		if($cate_band){
			$fen1 = intval($cate_band);
			$cate_band = 'list';
		}else{
			echo $shoplist[0]['sum']=0;exit;
		}
		if(empty($fen1)){
			$brand=$this->db->GetList("select * from `@#_brand` where 1 order by `order` DESC");
			$daohang = '所有分类';
		}else{
			$brand=$this->db->GetList("select * from `@#_brand` where `cateid`='$fen1' order by `order` DESC");
			$daohang=$this->db->GetOne("select * from `@#_category` where `cateid` = '$fen1' order by `order` DESC");
			$daohang = $daohang['name'];
		}

		$category=$this->db->GetList("select * from `@#_category` where `model` = '1'");

		//分页
		$end=10;
		$star=($p-1)*$end;
		$select_w = '';
		if($select == 10){
			$select_w = 'order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc';
		}
		if($select == 20){
			$select_w = "and `renqi` = '1'";
		}
		if($select == 30){
			$select_w = 'order by `canyurenshu` desc';
		}
		if($select == 40){
			$select_w = 'order by `time` DESC';
		}
		if($select == 50){
			$select_w = 'order by `money` DESC';
		}
		if($select == 60){
			$select_w = 'order by `money` ASC';
		}

		if($fen1){ 
			$count=$this->db->GetOne("select count(*) as total from `@#_shoplist` where `q_uid` is null and `cateid`='$fen1' AND ispk !=1 and zongrenshu !=canyurenshu $select_w");
		}else{
			$count=$this->db->GetOne("select count(*) as total from `@#_shoplist` where `q_uid` is null AND ispk !=1 and zongrenshu !=canyurenshu $select_w");
		}
		if($fen1){
			$shoplist=$this->db->GetList("select * from `@#_shoplist` where `q_uid` is null and `cateid`='$fen1' AND ispk !=1 and zongrenshu !=canyurenshu $select_w limit $star,$end");
		}else{
			$shoplist=$this->db->GetList("select * from `@#_shoplist` where `q_uid` is null AND ispk !=1 and zongrenshu !=canyurenshu $select_w limit $star,$end");
		}
		$max_renqi_qishu = 1;
		$max_renqi_qishu_id = 1;

		if(!empty($shoplistrenqi)){
			foreach ($shoplistrenqi as $renqikey =>$renqiinfo){
				if($renqiinfo['qishu'] >= $max_renqi_qishu){
					$max_renqi_qishu = $renqiinfo['qishu'];
					$max_renqi_qishu_id = $renqikey;
				}
			}
			$shoplistrenqi[$max_renqi_qishu_id]['t_max_qishu'] = 1;
		}

		$this_time = time(); 
		if(count($shoplist) > 1){
					if($shoplist[0]['time'] > $this_time - 86400*3)
					$shoplist[0]['t_new_goods'] = 1;
					$shoplist[0]['c_id'] = 'list/'.$select;
		}
		$pagex=ceil($count['total']/$end);
		if($p<=$pagex){
			$shoplist[0]['page']=$p+1;
			if($pagex>0){
				$shoplist[0]['sum']=$pagex;
			}else if($pagex==0){
				$shoplist[0]['sum']=$pagex;
			}
		}else{
			$shoplist[0]['sum']=0;
		}

		echo json_encode($shoplist);
	}
	
	
	//ajax获取商品列表信息
	public function glist_ajax(){
		$cate_id =htmlspecialchars($this->segment(4));
		$p =htmlspecialchars($this->segment(5)) ? $this->segment(5) :1;
		$num=10;
		$star=($p-1)*$num;
		if($cate_id == '0'){
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,canyurenshu,shenyurenshu,money,thumb,ssc,qishu from `@#_shoplist` where `q_uid` is null  AND ispk !=1 AND shenyurenshu !=0 order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc limit $star,$num");
		}elseif($cate_id == '101'){
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,canyurenshu,shenyurenshu,money,thumb,ssc,qishu from `@#_shoplist` where `q_uid` is null and `ispk`='1' AND shenyurenshu !=0 order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc limit $star,$num");	
		}else{
			$shoplist=$this->db->GetList("select id,sid,title,zongrenshu,canyurenshu,shenyurenshu,money,thumb,ssc,qishu from `@#_shoplist` where `q_uid` is null and `cateid`='$cate_id' AND ispk !=1 AND shenyurenshu !=0 order by id desc limit $star,$num");	
		}
		if($cate_id == '0'){
			$total=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null AND ispk !=1 AND shenyurenshu !=0 order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc ");
		}elseif($cate_id == '101'){
			$total=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null and  `ispk`='1' AND shenyurenshu !=0 order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc ");	
		}else{ 
			$total=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is null and `cateid`='$cate_id' AND ispk !=1 AND shenyurenshu !=0 order by id desc ");	
		}
		$this_time = time();
		if(count($shoplist) > 1){
					if($shoplist[0]['time'] > $this_time - 86400*3)
					$shoplist[0]['t_new_goods'] = 1;
				$shoplist[0]['c_id'] = $cate_id;
		}

		$pagex=ceil(count($total)/$num); 
		if($p<=$pagex){
			$shoplist[0]['page']=$p+1;
		}
		if($p>$pagex){
		    $pagex=0;
		}
		if($pagex>0){
			$shoplist[0]['sum']=$pagex;
		}else if($pagex==0){
			$shoplist[0]['sum']=$pagex;
		}

		echo json_encode($shoplist);
	}
	
	
	
	
	//商品详细
	public function item(){
	    $webname=$this->_cfg['web_name'];
	    $sortname=$this->_cfg['web_name_two'];
		$mysql_model=System::load_sys_class('model');
		$itemid=safe_replace($this->segment(4));
     	$symbol=$this->symbol;

		$info=$mysql_model->GetOne("select id,sid,cateid,picarr,qishu,title,title2,brandid,q_uid,q_user,q_end_time,q_user_code,money,zongrenshu,canyurenshu,shenyurenshu,thumb from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");
		if(!$info){
			$info=$mysql_model->GetOne("select id,sid,cateid,picarr,qishu,title,title2,brandid,q_uid,q_user,q_end_time,q_user_code,money,zongrenshu,canyurenshu,shenyurenshu,thumb from `@#_shoplist_copy` where `id`='".$itemid."' LIMIT 1");
		}
		$item=$info;

		if(!$item)_messagemobile("商品不存在");
		$key="第(".$item['qishu'].")期 ".$item['title'];
		if($item['q_end_time']){
			header("location: ".WEB_PATH."/mobile/mobile/dataserver/".$item['id']);
			exit;
		}
		$sid=$item['sid'];
		$sid_code=$mysql_model->GetOne("select sid,q_uid from `@#_shoplist` where `sid`='$sid' order by `id` DESC LIMIT 1,1");
		//$sid_go_record=$mysql_model->GetOne("select * from `@#_member_go_record` where `shopid`='$sid_code[sid]' and `uid`='$sid_code[q_uid]' order by `id` DESC LIMIT 1");
		$category=$mysql_model->GetOne("select * from `@#_category` where `cateid` = '$item[cateid]' LIMIT 1");
		$brand=$mysql_model->GetOne("select * from `@#_brand` where `id`='$item[brandid]' LIMIT 1");
		$title=$item['title'];
		$syrs=$item['zongrenshu']-$item['canyurenshu'];
	   
		$item['picarr'] = unserialize($item['picarr']);

   
		//$us=$mysql_model->GetList("select * from `@#_member_go_record` where `shopid`='".$itemid."' AND `shopqishu`='".$item['qishu']."'ORDER BY id DESC LIMIT 6");
		//$us2=$mysql_model->GetList("select * from `@#_member_go_record` where `shopid`='".$itemid."' AND `shopqishu`='".$item['qishu']."'ORDER BY id DESC");
		$itemlist = $this->db->GetList("select id,sid,qishu from `@#_shoplist_copy` where `sid`='$item[sid]' and `q_end_time` is not null order by `qishu` desc");
		$itemlist1 = $this->db->GetList("select id,sid,qishu from `@#_shoplist` where `sid`='$item[sid]'  and `q_showtime` ='Y' order by `qishu`");
		$itemlist=array_merge($itemlist1,$itemlist);

		//期数显示
		$loopqishu='';
		$loopqishu.='<li class="cur"><a href="javascript:;">'."第".$item['qishu']."期</a><b></b></li>";
		if(empty($itemlist)){
		foreach($itemlist as $qitem){
			$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/item/'.$qitem['id'].'" class="">第'.$qitem['qishu'].'期</a></li>';
		}}
		foreach($itemlist as $qitem){
			if($qitem['id'] == $itemid){
				$loopqishu.='<li class="cur"><a href="javascript:;">'."第".$itemlist[0]['qishu']."期</a><b></b></li>";
			}else{
				$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/dataserver/'.$qitem['id'].'" >第'.$qitem['qishu'].'期</a></li>';
			}
		}
		//foreach($itemlist as $k=>$v){
		//	var_dump($itemlist[$k]['qishu']);
		//}
		$gorecode=array();
		if(!empty($itemlist)){
		//查询上期的获奖者信息
			$gorecode=$this->db->GetOne("select uid,q_user_code,gonumber,q_end_time,q_uid,shopid,b.time from `@#_shoplist_copy` as a left join `@#_member_go_record` as b on a.id=b.shopid  where  a.`sid`='".$itemlist[0]['sid']."' AND a.`qishu`='".$itemlist[0]['qishu']."' and b.huode !=0 ");
			if(!$gorecode){
				$gorecode=$this->db->GetOne("select uid,q_user_code,gonumber,q_end_time,q_uid,shopid,b.time from `@#_shoplist` as a left join `@#_member_go_record` as b on a.id=b.shopid  where  a.`sid`='".$itemlist[0]['sid']."' AND a.`qishu`='".$itemlist[0]['qishu']."' and b.huode !=0 ");
			}
		}
		//echo microt($itemlist[0]['q_end_time']);exit;
		$curtime=time();
        $shopitem='itemfun';
		//晒单数
// 		$shopid=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid'");
// 		$shoplist=$this->db->GetList("select * from `@#_shoplist` where `sid`='$shopid[sid]'");
// 		$shop='';
// 		foreach($shoplist as $list){
// 			$shop.=$list['id'].',';
// 		}
// 		$id=trim($shop,',');
// 		if($id){
// 			$shaidan=$this->db->GetList("select * from `@#_shaidan` where `sd_shopid` IN ($id)");
// 			$sum=0;
// 			foreach($shaidan as $sd){
// 				$shaidan_hueifu=$this->db->GetList("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd[sd_id]'");
// 				$sum=$sum+count($shaidan_hueifu);
// 			}
// 		}else{
// 			$shaidan=0;
// 			$sum=0;
// 		}
		if($item['shenyurenshu']=='0' || $item['xsjx_time']=='0' || empty($item['q_uid'])){
			//$last_item = $us[0];
			$period_info = $mysql_model->GetOne("select * from `@#_period` order by `id` DESC LIMIT 1");
			if($period_info['num'] >= 120){
				$period = date("Ymd",strtotime("+1 day")).'001';
			}else{
				$period = $period_info['period']+1;
			}
		}
	
		include templates("mobile/index","item");
	}
	
	/**********************************兼容模板*******************************************/
		//商品详细
	public function item1(){
	    $webname=$this->_cfg['web_name'];
	    $sortname=$this->_cfg['web_name_two'];
		//$key="商品详情";
		$mysql_model=System::load_sys_class('model');
		$itemid=safe_replace($this->segment(4));
    
        $time = microtime(true) *1000;
        
		$info=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");
		if(!$info){
			$info=$mysql_model->GetOne("select * from `@#_shoplist_copy` where `id`='".$itemid."' LIMIT 1");
		}
	
		
		$item=$info;
		if(!$item)_messagemobile("商品不存在！");
		$key="第(".$item['qishu'].")期 ".$item['title'];
		if($item['q_end_time']){
			header("location: ".WEB_PATH."/mobile/mobile/dataserver/".$item['id']);
			exit;
		}
		$sid=$item['sid'];
		$sid_code=$mysql_model->GetOne("select sid,q_uid from `@#_shoplist` where `sid`='$sid' order by `id` DESC LIMIT 1,1");
		
		
	
		
		
		$sid_go_record=$mysql_model->GetOne("select * from `@#_member_go_record` where `shopid`='$sid_code[sid]' and `uid`='$sid_code[q_uid]' order by `id` DESC LIMIT 1");
		
	
		
		$category=$mysql_model->GetOne("select * from `@#_category` where `cateid` = '$item[cateid]' LIMIT 1");
		
	
		
		$brand=$mysql_model->GetOne("select * from `@#_brand` where `id`='$item[brandid]' LIMIT 1");
		
	

		$title=$item['title'];
		$syrs=$item['zongrenshu']-$item['canyurenshu'];
		$item['picarr'] = unserialize($item['picarr']);

		$us=$mysql_model->GetList("select * from `@#_member_go_record` where `shopid`='".$itemid."' AND `shopqishu`='".$item['qishu']."'ORDER BY id DESC LIMIT 6");
		
	

		//$us2=$mysql_model->GetList("select * from `@#_member_go_record` where `shopid`='".$itemid."' AND `shopqishu`='".$item['qishu']."'ORDER BY id DESC");

		$itemlist = $this->db->GetList("select * from `@#_shoplist_copy` where `sid`='$item[sid]' and `q_end_time` is not null order by `qishu` DESC");
		
	

		//期数显示
		$loopqishu='';
		$loopqishu.='<li class="cur"><a href="javascript:;">'."第".$item['qishu']."期</a><b></b></li>";

		if(empty($itemlist)){
		foreach($itemlist as $qitem){  
			$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/item/'.$qitem['id'].'" class="">第1期</a></li>';

		}}

		foreach($itemlist as $qitem){
			if($qitem['id'] == $itemid){
				$loopqishu.='<li class="cur"><a href="javascript:;">'."第".$itemlist[0]['qishu']."期</a><b></b></li>";
			}else{
				
				$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/dataserver/'.$qitem['id'].'" >第'.$qitem['qishu'].'期</a></li>';
			}
		}
		$gorecode=array();
		if(!empty($itemlist)){
		//查询上期的获奖者信息

			$gorecode=$this->db->GetOne("select * from `@#_shoplist_copy` as a left join `@#_member_go_record` as b on a.id=b.shopid  where  a.`sid`='".$itemlist[0]['sid']."' AND a.`qishu`='".$itemlist[0]['qishu']."' and b.huode !=0 ");
			if(!$gorecode){
				$gorecode=$this->db->GetOne("select * from `@#_shoplist` as a left join `@#_member_go_record` as b on a.id=b.shopid  where  a.`sid`='".$itemlist[0]['sid']."' AND a.`qishu`='".$itemlist[0]['qishu']."' and b.huode !=0 ");
			}
		}

		//echo microt($itemlist[0]['q_end_time']);exit;
		$curtime=time();
        $shopitem='itemfun';

		//晒单数
		$shopid=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid'");
		
		
		
		$shoplist=$this->db->GetList("select * from `@#_shoplist` where `sid`='$shopid[sid]'");
		
		
		
		$shop='';
		foreach($shoplist as $list){
			$shop.=$list['id'].',';
		}
		$id=trim($shop,',');
		if($id){
			$shaidan=$this->db->GetList("select * from `@#_shaidan` where `sd_shopid` IN ($id)");
			$sum=0;
			foreach($shaidan as $sd){
				$shaidan_hueifu=$this->db->GetList("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd[sd_id]'");
				$sum=$sum+count($shaidan_hueifu);
			}
		}else{
			$shaidan=0;
			$sum=0;
		}
        
        
        
		if($item['shenyurenshu']=='0' || $item['xsjx_time']=='0' || empty($item['q_uid'])){

			$last_item = $us[0];

			$period_info = $mysql_model->GetOne("select * from `@#_period` order by `id` DESC LIMIT 1");

			if($period_info['num'] >= 120){

				$period = date("Ymd",strtotime("+1 day")).'001';

			}else{

				$period = $period_info['period']+1;

			}

		}

	
        
	//微信JSSDK分享 消耗时间10秒 关闭
		require_once("system/modules/mobile/jssdk.php");
        $wechat= $this->db->GetOne("select * from `@#_wechat_config` where id = 1");
	
        //$jssdk = new JSSDK($wechat['appid'],$wechat['appsecret']);
        
        // $signPackage = $jssdk->GetSignPackage();
        
		include templates("mobile/index","item");
	}
	
	
	//客服咨询
	public function service(){
	  $webname="客服咨询";
	  $wxkfewm=$this->_cfg['wxkfewm'];
	  $info=System::load_sys_config('system');
	  $data=$this->db->Getlist("SELECT * FROM `@#_article` where cateid = '4'");
        $data['content'] = replaceArticle($data['content'],"#%webName%#");
        $data['content'] = replaceArticle($data['content'],"#%webUrl%#");
	  include templates("mobile/system","service");
	}
	
  	//往期揭晓列表
	public function rulelist(){
		$webname="往期揭晓";
		$fenname['name']="往期揭晓";
      	$id=$this->segment(4);
      if($id){
        $qishulist=$this->db->GetList("select * from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist` where id='$id') order by q_end_time desc limit 0,30");
        if(!$qishulist){
        	$qishulist=$this->db->GetList("select * from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist_copy` where id='$id') order by q_end_time desc limit 0,30");
        }
        foreach($qishulist as $k=>$v){
          $userinfo=unserialize($v['q_user']);
          $name=empty($userinfo['username'])?$userinfo['mobile']:$userinfo['username'];
          $name=is_numeric($name)?substr_replace($name,'****',3,4):$name;
          $qishulist[$k]['username']=$name;
          $userimg=$this->db->GetOne("SELECT img FROM `@#_member` where uid=$v[q_uid]");
           $qishulist[$k]['userimg']=$userimg['img'];
        }
      }else{
      	_messagemobile("参数错误");
      }

		include templates("mobile/index","rulelist");
	}
	public function itemsajax(){
	    $itemid =htmlspecialchars($this->segment(4));
		$cate_band =htmlspecialchars($this->segment(5));
		$select =htmlspecialchars($this->segment(6));
		$p =htmlspecialchars($this->segment(7)) ? $this->segment(7) :1;
		$end=10;
		$star=($p-1)*$end;
		$select_w = '';
		if($select == 10){
			$select_w = 'order by a.`time` desc';
		}
		if($select=='10'){
			// $count=$this->db->GetList("select * from `@#_member_go_record` where `shopid`='$itemid' $select_w");
			$count=$this->db->GetList("select *,from_unixtime(a.time) as time2,b.headimg from `@#_member_go_record` as a,`@#_member` as b where a.uid = b.uid and `shopid`='$itemid' $select_w");
		}
		// $shoplist=$this->db->GetList("select * from `@#_member_go_record` where `shopid`='$itemid' $select_w limit $star,$end");
		$shoplist=$this->db->GetList("select *,from_unixtime(a.time) as time2,b.headimg from `@#_member_go_record` as a,`@#_member` as b where a.uid = b.uid and `shopid`='$itemid' $select_w limit $star,$end");
		$max_renqi_qishu = 1;
		$max_renqi_qishu_id = 1;
		
	foreach($shoplist as &$sl){
			if (empty($sl['username']))
			{
				$sl['username'] = substr_replace($sl['mobile'], '****',3,4);
			}
			if(is_numeric($sl['username'])){
				$sl['username'] = substr_replace($sl['username'], '****',3,4);
			}
		}
		if(!empty($shoplistrenqi)){
			foreach ($shoplistrenqi as $renqikey =>$renqiinfo){
				if($renqiinfo['qishu'] >= $max_renqi_qishu){
					$max_renqi_qishu = $renqiinfo['qishu'];
					$max_renqi_qishu_id = $renqikey;
				}
			}
			$shoplistrenqi[$max_renqi_qishu_id]['t_max_qishu'] = 1;
		}

		$this_time = time();
		if(count($shoplist) > 1){
					if($shoplist[0]['time'] > $this_time - 86400*3)
					$shoplist[0]['t_new_goods'] = 1;
		}
		$pagex=ceil(count($count)/$end);
		if($p<=$pagex){
			$shoplist[0]['page']=$p+1;
		}
		if($pagex>0){
			$shoplist[0]['sum']=$pagex;
		}else if($pagex==0){
			$shoplist[0]['sum']=$pagex;
		}

		echo json_encode($shoplist);
	}
	
	public function userPrivacy(){
		$data=$_REQUEST;
		$member=$this->userinfo;
		$uid=$member['uid'];
		if($data){
			$status=$data['status'];
			if($data['type'] == 1){
				$res=$this->db->Query("UPDATE `@#_member` SET `shop_ permission` = '$status' WHERE uid='$uid' ");
			}else{
				$res=$this->db->Query("UPDATE `@#_member` SET `owinshop_permission` = '$status' WHERE uid='$uid' ");
			}
			if($res){
				echo 'ok';
			}else{
				echo '更新失败';
			}
		}

	}
	
	//商品详细
	public function itemajax(){
		$mysql_model=System::load_sys_class('model');
		$itemid=safe_replace($this->segment(4));
		$item=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");
		$uid=$mysql_model->GetOne("select * from `@#_member` where `uid`='".$item['q_uid']."' LIMIT 1");
		$mysql_model->Query("UPDATE `@#_shoplist` SET `q_showtime`='N' where `id`= $itemid");
		$temp =array();
		$temp = $item;
		$temp['user'] = empty($uid['username']) ? substr($uid['mobile'],0,3).'****'.substr($uid['mobile'],7,4) : $uid['username'];
		$temp['pic'] = G_UPLOAD_PATH.'/'.$item['thumb'];
		echo '<div style="width:90%; margin-left:5%;"><img width="90%" src='.$temp['pic'].'></div><div class="txt"><div class="zj"><span>已开团</span><span style="color: #5bb6ea;">'.$temp['user']."</span></div></div>";exit;
	}

	//往期商品查看
	public function dataserver1(){
	    $webname=$this->_cfg['web_name'];
		$key="揭晓结果";
        $symbol=$this->symbol;
		$itemid=intval($this->segment(4));
		$item=$this->db->GetOne("select id,sid,cateid,picarr,qishu,title,title2,brandid,q_uid,q_user,q_end_time,q_user_code,money,ssc,q_end_counttime,zongrenshu,q_external_code,thumb from `@#_shoplist_copy` where `id`='$itemid'  LIMIT 1");
		if(!$item){
			$item=$this->db->GetOne("select id,sid,cateid,picarr,qishu,title,title2,brandid,q_uid,q_user,q_end_time,q_user_code,money,ssc,q_end_counttime,zongrenshu,q_external_code,thumb from `@#_shoplist` where `id`='$itemid'  LIMIT 1");
		}
		if(!$item){
			_messagemobile("商品不存在");
		}
		if ( !$item['q_end_time'] ) {
			header("location: ".WEB_PATH."/mobile/mobile/item/".$item['id']);
			die;
		}
		if($item['ssc'] != 1){
			if(empty($item['q_user_code'])){
				_messagemobile("该商品正在进行中...");
			}
		}
		$itemlist = $this->db->GetList("select id,qishu from `@#_shoplist_copy` where `sid`='$item[sid]' order by `qishu` DESC");
		$itemlist1 = $this->db->GetList("select id,qishu from `@#_shoplist` where `sid`='$item[sid]' AND `q_showtime` ='Y' order by `qishu` DESC");
		$itemlist=array_merge($itemlist1,$itemlist);
		$category=$this->db->GetOne("select * from `@#_category` where `cateid` = '$item[cateid]' LIMIT 1");
		$brand=$this->db->GetOne("select * from `@#_brand` where `id` = '$item[brandid]' LIMIT 1");
		$zjtx=$this->db->GetOne("select * from `@#_member` where `uid` = '$item[q_uid]' LIMIT 1");
		//中奖码
		$q_user = unserialize($item['q_user']);
		$q_user_code_len = strlen($item['q_user_code']);
		$q_user_code_arr = array();
		for($q_i=0;$q_i < $q_user_code_len;$q_i++){
			$q_user_code_arr[$q_i] = substr($item['q_user_code'],$q_i,1);
		}
		//期数显示
		$loopqishu='';
		if(empty($itemlist[0]['q_end_time'])){
			$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/item/'.$itemlist[0]['id'].'">'."第".$itemlist[0]['qishu']."期</a><b></b></li>";
			array_shift($itemlist);
		}
		foreach($itemlist as $qitem){
			if($qitem['id'] == $itemid){
				$loopqishu.='<li class="cur"><a href="javascript:;">'."第".$qitem['qishu']."期</a><b></b></li>";
			}else{
				$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/dataserver/'.$qitem['id'].'" >第'.$qitem['qishu'].'期</a></li>';
			}
		}
		//总次数
		$user_shop_number = 0;
		//用户时间
		$user_shop_time = 0;
		//得到码
		$user_shop_codes = '';
		$user_shop_list = $this->db->GetList("select gonumber from `@#_member_go_record` where `uid`= '$item[q_uid]' and `shopid` = '$itemid' and `shopqishu` = '$item[qishu]'");
		foreach($user_shop_list as $user_shop_n){
			$user_shop_number += $user_shop_n['gonumber'];
			if($user_shop_n['huode']){
				$user_shop_time = $user_shop_n['time'];
				$user_shop_codes = $user_shop_n['goucode'];
			}
		}
		$h=abs(date("H",$item['q_end_time']));
		$i=date("i",$item['q_end_time']);
		$s=date("s",$item['q_end_time']);
		$w=substr($item['q_end_time'],11,3);
		$user_shop_time_add = $h.$i.$s.$w;
		$user_shop_fmod = fmod($user_shop_time_add*100,$item['canyurenshu']);
		$code_fmod=fmod($item['q_counttime'],$item['canyurenshu']);
		if($item['ssc']==1){
			$count_time=$item['q_end_counttime']+$item['q_external_code'];
			$code_fmod = fmod($count_time,$item['zongrenshu']);		    
		}		
		if($item['q_content']){
			$item['q_content'] = unserialize($item['q_content']);
		}
        $item['picarr'] = unserialize($item['picarr']) ;
		//记录
		//$itemzx=$this->db->GetOne("select * from `@#_shoplist` where `sid`='$item[sid]' and `qishu`>'$item[qishu]' and `q_end_time` is null order by `qishu` DESC LIMIT 1");
	    $shopids=$this->db->GetOne("select `sid`,`qishu` from `@#_shoplist_copy` where `id`='".$itemid."'");
	    $shangqi=$shopids['qishu']-1;
		$gorecode=$this->db->GetOne("select uid,q_user_code,gonumber,q_end_time,q_uid,shopid,b.time from `@#_shoplist_copy`as a left join `@#_member_go_record` as b on a.id=b.shopid  where  a.`sid`='".$shopids['sid']."' AND a.`qishu`='".$shangqi."' and b.huode !=0 ");
	   // $gorecode_count=$gorecode_count ? $gorecode_count['count'] : 0;
		$shopitem='dataserverfun';
		$curtime=time();
		//晒单数
// 		$shopid=$this->db->GetOne("select sid from `@#_shoplist_copy` where `id`='$itemid'");
// 		$shoplist=$this->db->GetList("select id from `@#_shoplist_copy` where `sid`='$shopid[sid]'");
// 		$shop='';
// 		foreach($shoplist as $list){
// 			$shop.=$list['id'].',';
// 		}
// 		$id=trim($shop,',');
// 		if($id){
// 			$shaidan=$this->db->GetList("select * from `@#_shaidan` where `sd_shopid` IN ($id)");
// 			$sum=0;
// 			foreach($shaidan as $sd){
// 				$shaidan_hueifu=$this->db->GetList("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd[sd_id]'");
// 				$sum=$sum+count($shaidan_hueifu);
// 			}
// 		}else{
// 			$shaidan=0;
// 			$sum=0;
// 		}
// 		$itemxq=0;
// 		if(!empty($itemzx)){
// 		  $itemxq=1;
// 		}
		include templates("mobile/index","item");
	}
    /********************************兼容模板*******************************************/
    //往期商品查看
	public function dataserver(){
	    $webname=$this->_cfg['web_name'];
		$key="揭晓结果";
		$itemid=intval($this->segment(4));
		$item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid'  LIMIT 1");
		if(!$item){
			$item=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid'  LIMIT 1");	
		}
		
		if(!$item){
			_messagemobile("商品不存在！");
		}
		if ( !$item['q_end_time'] ) {
			header("location: ".WEB_PATH."/mobile/mobile/item/".$item['id']);
			die;
		}

		if($item['ssc'] !=1){
			
			if(empty($item['q_user_code'])){
				_messagemobile("该商品正在进行中...");
			}
		}
		$itemlist = $this->db->GetList("select * from `@#_shoplist_copy` where `sid`='$item[sid]' order by `qishu` DESC");
		$category=$this->db->GetOne("select * from `@#_category` where `cateid` = '$item[cateid]' LIMIT 1");
		$brand=$this->db->GetOne("select * from `@#_brand` where `id` = '$item[brandid]' LIMIT 1");
		$zjtx=$this->db->GetOne("select * from `@#_member` where `uid` = '$item[q_uid]' LIMIT 1");
		//中奖码
		$q_user = unserialize($item['q_user']);
		$q_user_code_len = strlen($item['q_user_code']);
		$q_user_code_arr = array();
		for($q_i=0;$q_i < $q_user_code_len;$q_i++){
			$q_user_code_arr[$q_i] = substr($item['q_user_code'],$q_i,1);
		}

		//期数显示
		$loopqishu='';
		if(empty($itemlist[0]['q_end_time'])){
			$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/item/'.$itemid.'">'."第1期</a><b></b></li>";
			array_shift($itemlist);
		}

		foreach($itemlist as $qitem){
			if($qitem['id'] == $itemid){

				$loopqishu.='<li class="cur"><a href="javascript:;">'."第".$qitem['qishu']."期</a><b></b></li>";
			}else{
				$loopqishu.='<li><a href="'.WEB_PATH.'/mobile/mobile/dataserver/'.$qitem['id'].'" >第'.$qitem['qishu'].'期</a></li>';
			}
		}

		//总次数
		$user_shop_number = 0;
		//用户时间
		$user_shop_time = 0;
		//得到码
		$user_shop_codes = '';

		$user_shop_list = $this->db->GetList("select * from `@#_member_go_record` where `uid`= '$item[q_uid]' and `shopid` = '$itemid' and `shopqishu` = '$item[qishu]'");
		foreach($user_shop_list as $user_shop_n){
			$user_shop_number += $user_shop_n['gonumber'];
			if($user_shop_n['huode']){
				$user_shop_time = $user_shop_n['time'];
				$user_shop_codes = $user_shop_n['goucode'];
			}
		}
		$h=abs(date("H",$item['q_end_time']));
		$i=date("i",$item['q_end_time']);
		$s=date("s",$item['q_end_time']);
		$w=substr($item['q_end_time'],11,3);
		$user_shop_time_add = $h.$i.$s.$w;
		$user_shop_fmod = fmod($user_shop_time_add*100,$item['canyurenshu']);
		$code_fmod=fmod($item['q_counttime'],$item['canyurenshu']);
		if($item['q_content']){
			$item['q_content'] = unserialize($item['q_content']);
		}
        $item['picarr'] = unserialize($item['picarr']) ;

		//记录
		$itemzx=$this->db->GetOne("select * from `@#_shoplist` where `sid`='$item[sid]' and `qishu`>'$item[qishu]' and `q_end_time` is null order by `qishu` DESC LIMIT 1");

	    $shopids=$this->db->GetOne("select `sid`,`qishu` from `@#_shoplist_copy` where `id`='".$itemid."'");
	    $shangqi=$shopids['qishu']-1;
	   $gorecode=$this->db->GetOne("select * from `@#_shoplist_copy`as a left join `@#_member_go_record` as b on a.id=b.shopid  where  a.`sid`='".$shopids['sid']."' AND a.`qishu`='".$shangqi."' and b.huode !=0 ");
	 
	    $gorecode_count=$gorecode_count ? $gorecode_count['count'] : 0;

		$shopitem='dataserverfun';
		$curtime=time();
		//晒单数
		$shopid=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid'");
          //echo '<pre>';
	     //var_dump($shopid[sid]);exit;
		$shoplist=$this->db->GetList("select * from `@#_shoplist_copy` where `sid`='$shopid[sid]'");
       // var_dump(1);exit;
		$shop='';
		foreach($shoplist as $list){
			$shop.=$list['id'].',';
		}
		$id=trim($shop,',');
		if($id){
			$shaidan=$this->db->GetList("select * from `@#_shaidan` where `sd_shopid` IN ($id)");
			$sum=0;
			foreach($shaidan as $sd){
				$shaidan_hueifu=$this->db->GetList("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd[sd_id]'");
				$sum=$sum+count($shaidan_hueifu);
			}
		}else{
			$shaidan=0;
			$sum=0;
		}
		$itemxq=0;
		if(!empty($itemzx)){
		  $itemxq=1;
		}
  	 
		include templates("mobile/index","item");
	}
	//趋势图
	public function movements(){
   		$webname=$this->_cfg['web_name'];
		$key="助手分析";
		$itemid=intval($this->segment(4));
		$item=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid' LIMIT 1");
		if(!$item){
			$item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
			if(!$item){
				_messagemobile("商品不存在！");
			}
			
		}
		$newid = $this->db->GetOne("select id from `@#_shoplist_copy` where `sid`='$item[sid]' order by `qishu` DESC LIMIT 1");
		$num = $this->db->GetList("select id from `@#_shoplist_copy` where `sid`='$item[sid]' AND `q_user_code` is not null order by `qishu`");
		$count=count($num);
		$star=0;
		if($count > 10){
			$star=$count-10;  
		} 
		
		//$newArray = $this->cloudCode($item['id'],$item['codes_table']);
		
		$itemlist = $this->db->GetList("select * from `@#_shoplist_copy` where `sid`='$item[sid]' AND `q_user_code` is not null order by `qishu` LIMIT $star,10");
		
		//初始化copy表的商品id
		$initializeSid = [];
		foreach ($itemlist as $v)$initializeSid[] = $v['id'];
		$newArray = $this->cloudCode($initializeSid);
		
        $minArray = [];
        
		foreach ($itemlist as $k => $value) { 
			$userinfo=unserialize($value['q_user']);
			if($userinfo['username']){
				if(is_numeric($userinfo['username'])){
					$itemlist[$k]['q_user']=substr_replace($userinfo['username'], '****', 3, 4);
				}else{
					$itemlist[$k]['q_user']=$userinfo['username'];
				}
			}else{
				$itemlist[$k]['q_user']=substr_replace($userinfo['mobile'], '****', 3, 4);
			}
			// $num=$this->db->GetOne("select sum(gonumber) as gnum from `@#_member_go_record` where shopid=".$value['id']." AND uid=".$value['q_uid']);
			// $itemlist[$k]['buycount']=$num['gnum'];
			// $shoplist=$this->db->GetList("SELECT gonumber,huode,shopqishu,shopid FROM `@#_member_go_record` WHERE `shopname`='$item[title]' AND `shopqishu` = '$value[qishu]' ");

			// foreach ($shoplist as $ky => $v) {
			// 	$itemlist[$k]['goucount']+=$v['gonumber'];
			// 	if($v['huode'] == $value['q_user_code'] && $v['qishu'] == $value['shopqishu']){
			// 		break;
			// 	}

			// }
			 $num = 0;
			 $isBreak = false;//初始化退出条件
            foreach ($newArray as $k1 => $v1){
                if($k1 == $value['id']){
                    foreach ($v1 as $v2){
                        $num += 1; 
                        if($v2 == $value['q_user_code']){
                            $isBreak = true;
                            break;
                        }
                        if($isBreak) break;
                   }
                }
                
                
            }
        
			$cloudCode = $itemlist[$k]['q_user_codes'] = $num;
            /*$datas = $this->cloudCode($value['id'],$value['codes_table'],$value['q_user_code']);
            p($datas);*/
			$minArray[] = $cloudCode;
			
		}
// 		p($itemlist);
        $itemlists = $itemlist;
        $last_names = array_column($itemlists,'id');
        array_multisort($last_names,SORT_DESC,$itemlists);
		$minNumber = min($minArray);
		
	    include templates("mobile/index","movements");
	}
	
	
	/**
     * 获取拼接云购码
     * @param $goodId 商品id array
     */
    private function cloudCode($goodId){
        if(is_array($goodId)) $goodId = implode(',',$goodId);
        // echo "select `goucode`,`id`,`shopid` from `@#_member_go_record` where id (select`id` from `@#_member_go_record` where`shopid`in ($goodId))";exit;
        // $itemlist = $this->db->GetList("select `goucode`,`id`,`shopid` from `@#_member_go_record` where id in (select`id` from `@#_member_go_record` where`shopid`in ($goodId))");
        $itemlist = $this->db->GetList("select `goucode`,`id`,`shopid` from `@#_member_go_record` where `shopid`in ($goodId)");
        if(empty($itemlist)) return false;
        //存放云购码
        $codesTmp = [];
        // $num = 0;
        foreach ($itemlist as $key => $v){
           $codesTmp[$v['shopid']][$v['id']] = $v['goucode'];
        }
        //数据清除
        unset($itemlist);
        
        //最新存放云购位置
        $newCodes = [];
        foreach($codesTmp as $k1 => $v1){
            //初始化一个新的容器接收云购码
            $newCodeArray = [];
            foreach ($v1 as $v2){
                $newCodeArray = array_merge($newCodeArray,explode(',',$v2));
                // array_merge($newCodeArray,$v2);
            }
            $newCodes[$k1] = $newCodeArray;
        }
        unset($codesTmp);
// 		p($newCodes);exit;
        return $newCodes;
    } 
	
// 	public function movements(){
//   		$webname=$this->_cfg['web_name'];
// 		$key="走势图";
// 		$itemid=intval($this->segment(4));
// 		$item=$this->db->GetOne("select sid from `@#_shoplist` where `id`='$itemid' LIMIT 1");
// 		if(!$item){
// 			$item=$this->db->GetOne("select sid from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
// 			if(!$item){
// 				_messagemobile("商品不存在");
// 			}else{
// 				$sid=$item['sid'];
// 				$item=$this->db->GetOne("select id,sid,qishu,zongrenshu,canyurenshu,shenyurenshu,title,thumb from `@#_shoplist` where `sid`='$sid' order by id desc");
// 			}
// 		}else{
// 		    $sid=$item['sid'];
// 	        $item=$this->db->GetOne("select id,sid,qishu,zongrenshu,canyurenshu,shenyurenshu,title,thumb from `@#_shoplist` where `sid`='$sid' order by id desc");		    
// 		}
// 		$itemlist = $this->db->GetList("select buycount,goucount,qishu,q_user,q_user_code from `@#_shoplist_copy` where `sid`='$sid' AND `q_user_code` is not null order by `qishu` desc LIMIT 0,10");	
// 		foreach ($itemlist as $k => $value) { 
//                 $buyids.=$value['buycount'].",";
//               $userinfo=unserialize($value['q_user']);
//     			if($userinfo['username']){
//     				if(is_numeric($userinfo['username'])){
//     					$itemlist[$k]['q_user']=substr_replace($userinfo['username'], '****', 3, 4);
//     				}else{
//     					$itemlist[$k]['q_user']=$userinfo['username'];
//     				}
//     			}else{
//     				$itemlist[$k]['q_user']=substr_replace($userinfo['mobile'], '****', 3, 4);
    				
//     			}
// 		}
// 	$newid['id'] = $item['id'];
// 		$buyids=trim($buyids,',');
// 		$cybfb=($item['canyurenshu']/$item['zongrenshu'])*100;
// 		$cybfb= sprintf("%.1f",$cybfb).'%';
// 	include templates("mobile/index","movements");
// 	}

	//获取以往期数
	public function get_oldshopinfo(){
		$itemid=intval($_REQUEST['productId']);
		$item=$this->db->GetOne("select sid from `@#_shoplist` where `id`='$itemid' LIMIT 1");
		if(!$item){
			$item=$this->db->GetOne("select sid from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
			if(!$item){
				echo 'fali';exit;
			}
		}
		$itemlist = $this->db->GetList("select id,sid,qishu,zongrenshu,canyurenshu,shenyurenshu,title,q_user,buycount,q_user_code,goucount from `@#_shoplist_copy` where `sid`='$item[sid]' AND `q_user_code` is not null order by `qishu` desc LIMIT 0,10");	
		foreach ($itemlist as $k => $value) { 
			$userinfo=unserialize($value['q_user']);
			if($userinfo['username']){
				if(is_numeric($userinfo['username'])){
					$itemlist[$k]['q_user']=substr_replace($userinfo['username'], '****', 3, 4);
				}else{
					$itemlist[$k]['q_user']=$userinfo['username'];
				}
			}else{
				$itemlist[$k]['q_user']=substr_replace($userinfo['mobile'], '****', 3, 4);
				
			}
		}
		
	echo json_encode($itemlist);exit;
	}
	
	//获取当前期数
	public function get_newshopinfo(){
		$itemid=intval($_REQUEST['id']);
		$item=$this->db->GetOne("select sid from `@#_shoplist` where `id`='$itemid' LIMIT 1");
		if(!$item){
			$item=$this->db->GetOne("select sid from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
			if(!$item){
				echo 'fali';exit;
			}
		}
		$itemlist = $this->db->GetOne("select * from `@#_shoplist` where `sid`='$item[sid]' order by `qishu` desc LIMIT 1");
		echo json_encode($itemlist);
	}

	//最新获得
	public function lottery(){
	    $webname="最新揭晓";
		$key="最新揭晓";
		$lottype=$this->segment(4);
		include templates("mobile/index","lottery");
	}
	//获得码详情
	public function recodedetails(){
		$fenname['name']="参与记录";
      	$id=$this->segment(4);
      if($id){
        $info=$this->db->GetOne("SELECT username,gonumber,time,ordercode_table,uphoto FROM `@#_member_go_record` where id=$id");
		$codestable=$info['ordercode_table'];
        $codeinfo=$this->db->GetOne("SELECT shop_codes FROM `@#_".$codestable."` where orderid=$id");
		$info['goucode']=$codeinfo['shop_codes'];
      }else{
      	_messagemobile("参数错误");
      }
		include templates("mobile/index","recodedetails");
	}

	//商品购买记录
	public function buyrecords(){
	    $webname=$this->_cfg['web_name'];
		$key="参与记录";
		$itemid=intval($this->segment(4));
		$cords=$this->db->GetList("select * from `@#_member_go_record` where `shopid`='$itemid'order by `time` desc");
		$co = count($cords);
		foreach ($cords as $k => $v) {
			if(is_numeric($v['username'])){
				$cords[$k]['username']=substr_replace($v['username'],'****',3,4);
			}
		}
		include templates("mobile/index","buyrecords");
	}

    /************************兼容模板***********************************/
    
    
	//商品购买记录 参与记录
	public function buyrecords1(){
	    $webname=$this->_cfg['web_name'];
		$itemid=intval($this->segment(4));
// 		var_dump($itemid);
		if($_GET["state"]==1){
		$cords=$this->db->GetList("select * from `@#_member_go_record` where `shopid`='$itemid'order by `time` desc");
// 		var_dump("select * from `@#_member_go_record` where `shopid`='$itemid'order by `time` desc");exit;
		$co = count($cords);
		foreach ($cords as $k => $v) {
			if(is_numeric($v['username'])){
				$cords[$k]['username']=substr_replace($v['username'],'****',3,4);	
			}
			$cords[$k]['time']=date("Y-m-d H:i:s",$v["time"]);
		}
		echo json_encode($cords);
		}else{
			$id=$this->segment(4);
			if($id){
			    $qishulist=$this->db->GetList("select * from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist` where id='$id') order by q_end_time desc limit 0,30");
			    if(!$qishulist){
				    $qishulist=$this->db->GetList("select * from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist_copy` where id='$id') order by q_end_time desc limit 0,30");
			    }
    			foreach($qishulist as $k=>$v){
    			  $userinfo=unserialize($v['q_user']);
    			  $name=empty($userinfo['username'])?$userinfo['mobile']:$userinfo['username'];
    			  $name=is_numeric($name)?substr_replace($name,'****',3,4):$name;
    			  $qishulist[$k]['username']=$name;
    			   $qishulist[$k]['q_end_time']=date("Y-m-d H:i:s",$v["q_end_time"]);
    			    $qishulist[$k]['time']=date("Y-m-d H:i:s",$v["time"]);
    			  $userimg=$this->db->GetOne("SELECT img FROM `@#_member` where uid=$v[q_uid]");
    			   $qishulist[$k]['userimg']=$userimg['img'];
    			}
			    echo json_encode($qishulist);
    		}else{
    			echo "参数有误";
    		}
	}
// 		include templates("mobile/index","buyrecords");
}
    //商品购买记录 参与记录
    public function buyrecordss(){
        $webname=$this->_cfg['web_name'];
        $itemid=intval($this->segment(4));

        if($_GET["state"]==1){
            $cords=$this->db->GetList("select * from `@#_member_go_record` where `shopid`='$itemid'order by `time` desc");
            $co = count($cords);
            foreach ($cords as $k => $v) {
                if(is_numeric($v['username'])){
                    $cords[$k]['username']=substr_replace($v['username'],'****',3,4);
                }
                $cords[$k]['time']=date("Y-m-d H:i:s",$v["time"]);
            }
            echo json_encode($cords);
        }else{
            $id=$this->segment(4);
            if($id){
                $qishulist=$this->db->GetList("select * from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist` where id='$id') order by q_end_time desc limit 0,30");
                if(!$qishulist){
                    $qishulist=$this->db->GetList("select * from `@#_shoplist_copy` where sid =(select sid from `@#_shoplist_copy` where id='$id') order by q_end_time desc limit 0,30");
                }
                foreach($qishulist as $k=>$v){
                    $userinfo=unserialize($v['q_user']);
                    $name=empty($userinfo['username'])?$userinfo['mobile']:$userinfo['username'];
                    $name=is_numeric($name)?substr_replace($name,'****',3,4):$name;
                    $qishulist[$k]['username']=$name;
                    $qishulist[$k]['q_end_time']=date("Y-m-d H:i:s",$v["q_end_time"]);
                    $qishulist[$k]['time']=date("Y-m-d H:i:s",$v["time"]);
                    $userimg=$this->db->GetOne("SELECT img FROM `@#_member` where uid=$v[q_uid]");
                    $qishulist[$k]['userimg']=$userimg['img'];
                }
                echo json_encode($qishulist);
            }else{
                echo "参数有误";
            }
        }
        //include templates("mobile/index","buyrecords");
    }
	
	//图文详细
	public function goodsdesc(){
	    $webname=$this->_cfg['web_name'];
		$key="图文详情";
		$itemid=intval($this->segment(4));
		$desc1=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid'");
		$desc2=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid'");
		empty($desc1)?$desc=$desc2:$desc=$desc1;
		if(!$desc){
			_messagemobile('页面错误');
		}
		include templates("mobile/index","goodsdesc");
	}
	
	//晒单评论
	public function goodspost(){
	    $webname=$this->_cfg['web_name'];
		$key="晒单评论";
		$itemid=intval($this->segment(4));
		$shoplist=$this->db->GetList("select * from `@#_shoplist` where `sid`='$itemid'");
		if(!$shoplist){
			_messagemobile('页面错误');
		}
		$shop='';
		foreach($shoplist as $list){
			$shop.=$list['id'].',';
		}
		$id=trim($shop,',');
		if($id){
			$shaidan=$this->db->GetList("select * from `@#_shaidan` where `sd_shopid` IN ($id) order by `sd_id` DESC");
			$sum=0;
			foreach($shaidan as $sd){
				$shaidan_hueifu=$this->db->GetList("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd[sd_id]'");
				$sum=$sum+count($shaidan_hueifu);
			}
		}else{
			$shaidan=0;
			$sum=0;
		}
		include templates("mobile/index","goodspost");
	}	

	public function calResult(){

      	$title="计算结果";
		$itemid=intval($this->segment(4));
	  	$item=$this->db->GetOne("select * from `@#_shoplist_copy` where `id`='$itemid' LIMIT 1");
	    $h=abs(date("H",$item['q_end_time']));
		$i=date("i",$item['q_end_time']);
		$s=date("s",$item['q_end_time']);
		$w=substr($item['q_end_time'],11,3);
		$user_shop_time_add = $h.$i.$s.$w;
		$user_shop_fmod = fmod($user_shop_time_add*100,$item['canyurenshu']);
		$q_external_code=$item['q_external_code'];
		if($item['q_content']){
			$item['q_content'] = unserialize($item['q_content']);
			$user_shop_time_add = $item['q_counttime'];
			$user_shop_fmod = fmod($item['q_counttime'],$item['canyurenshu']);
		}
		$q_counttime=$item['q_counttime'];
		$external_time=unserialize($item['q_external_content']);
		$external_time=$external_time['ssc_phase'];
        $item['picarr'] = unserialize($item['picarr']);
        // p($item);exit;
     
	  include templates("mobile/index","calResult");
	}
	
	//新手指南
	public function about(){
		$webname=$this->_cfg['web_name'];
		$category=$this->db->GetOne("select * from `@#_category` where `parentid`='1' and `name`='新手指南'");
		$article=$this->db->GetList("select * from `@#_article` where `cateid`='$category[cateid]'");
        $article['content'] = replaceArticle($article['content'],"#%webName%#");
        $article['content'] = replaceArticle($article['content'],"#%webUrl%#");
		include templates("mobile/index","about");
	}
	
	//用户服务协议
	public function terms(){
		$webname=$this->_cfg['web_name'];
		$category=$this->db->GetOne("select * from `@#_category` where `parentid`='0' and `name`='新手指南'");
		$article=$this->db->GetOne("select * from `@#_article` where `cateid`='$category[cateid]' and `title`='服务协议' ");

        $article['content'] = replaceArticle($article['content'],"#%webName%#");
        $article['content'] = replaceArticle($article['content'],"#%webUrl%#");
		include templates("mobile/system","terms");
	}

	//访问个人主页
	public function userindex(){
	  $webname="[TA]人主页";
	  $uid=safe_replace($this->segment(4));
	  $symbol=$this->symbol;
	  $member=$this->db->GetOne("select * from `@#_member` where `uid`='$uid'");
	  if(!empty($member['headimg'])){
	  	$member['img'] = $member['headimg'];
	  }else{
	  	$member['img'] = G_UPLOAD_PATH.'/'.$member['img'];
	  }
	  //获取等级  新手  小将==
	  $memberdj=$this->db->GetList("select * from `@#_member_group`");
	  $jingyan=$member['jingyan'];
	  if(!empty($memberdj)){
	     foreach($memberdj as $key=>$val){
		    if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
			   $member['yungoudj']=$val['name'];
			}
		 }
	  }
	  include templates("mobile/index","userindex");
	}
    public function test()
    {
        $time = '1682438399';
        $account = $this->db->GetList("SELECT time FROM `@#_member_account` WHERE `time`<$time LIMIT 100");
        $record = $this->db->GetList("SELECT time FROM `@#_member_go_record` WHERE `time`<$time LIMIT 100");
        $shoplist = $this->db->GetList("SELECT q_end_time FROM `@#_shoplist_copy` WHERE `q_end_time`<$time LIMIT 100");//消费记录

        $addmoney = $this->db->GetList("SELECT time FROM `@#_member_addmoney_record` WHERE `time`<$time LIMIT 100");//
        $tixian = $this->db->GetList("SELECT time FROM `@#_member_cashout` WHERE `time`<$time LIMIT 100");//提现记录
        $xiaofei = $this->db->GetList("SELECT time FROM `@#_member_rechange` WHERE `time`<$time and `content` like '%充值%'   LIMIT 100");//充值记录
        
        if(!$account && !$record && !$shoplist && !$addmoney && !$xiaofei && !$tixian){
            echo '暂无数据清理';exit();
        }
        
        
        
        $data1 = false;
        if (!empty($account)) {
            echo "数据会员会员sql：DELETE FROM `go_member_account` WHERE `time`<$time \n" ;
            $data1 =$this->db->Query("DELETE FROM `@#_member_account` WHERE `time`<$time LIMIT 5000");
        }

        $data2 = false;
        if (!empty($record)) {
            echo "购买记录数据sql：DELETE FROM `go_member_go_record` WHERE `time`<$time  " ;
            $data2 =$this->db->Query("DELETE FROM `@#_member_go_record` WHERE `time`<$time  LIMIT 5000");
        }

        $data3 = false;
        if (!empty($shoplist)) {
            echo "以揭晓记录数据sql：DELETE FROM `go_shoplist_copy` WHERE `q_end_time`<$time \n" ;
            $data3 =$this->db->Query("DELETE FROM `@#_shoplist_copy` WHERE `q_end_time`<$time LIMIT 5000");
        }

        $data4 = false;
        if (!empty($addmoney)) {
            echo "清理消费订单记录数据sql：DELETE FROM `go_member_addmoney_record` WHERE `time`<$time \n" ;
            $data4 =$this->db->Query("DELETE FROM `@#_member_addmoney_record` WHERE `time`<$time  LIMIT 5000");
        }

        $data5 = false;
        if (!empty($xiaofei)) {
            echo "充值记录订单记录数据sql：DELETE FROM `go_member_rechange` WHERE `time`<$time and `content` like '%充值%'  \n" ;
            $data5 =$this->db->Query("DELETE FROM `@#_member_rechange` WHERE `time`<$time  and `content` like '%充值%' LIMIT 5000");
        }

        $data6 = false;
        if(!empty($tixian)) {
            echo "提现记录记录数据sql：DELETE FROM `go_member_cashout` WHERE `time`<$time \n" ;
            $data6 =$this->db->Query("DELETE FROM `@#_member_cashout` WHERE `time`<$time  LIMIT 10");
        }

        if ($data1) {
            echo date("Y-m-d h:i:s",$account[0]['time']).'日，以前数据会员成功-----' . "\n";

        }
        if ($data2) {
            echo date("Y-m-d h:i:s",$record[0]['time']).'日，以前购买记录数据清理成功-----' . "\n";

        }
        if ($data3) {
            echo date("Y-m-d h:i:s",$shoplist[0]['q_end_time']).'日，以前清理历史期数成功-----' . "\n";

        }

        if ($data4) {
            echo date("Y-m-d h:i:s",$addmoney[0]['time']).'日，以前清理消费订单记录表成功-----' . "\n";

        }
        if ($data5) {
            echo date("Y-m-d h:i:s",$xiaofei[0]['time']).'日，以前充值记录清除成功-----' . "\n";
        }

        if ($data6) {
            echo date("Y-m-d h:i:s",$tixian[0]['time']).'日，以前提现记录清除成功-----' . "\n";
        }
        exit();
    }

	
}
?>