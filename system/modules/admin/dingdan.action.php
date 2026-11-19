<?php   
defined('G_IN_SYSTEM')or exit('no');
ignore_user_abort(TRUE);
set_time_limit(0); 
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');
class dingdan extends admin {
	private $db;
	private $status = ["1"=>"待付运费","2"=>"未发货","3"=>"已发货","4"=>"待收货","5"=>"已完成","6"=>"已做废"];
	private $yh_status = ["1"=>"待支付","2"=>"已付款","21"=>"待发货","3"=>"已发货","4"=>"未收货","5"=>"已完成","6"=>"已作废"];
	private $group_status = ["1"=>"进行中","2"=>"已完成","3"=>"超时","4"=>"取消"];
	private $jifen_status = ["0"=>"","1"=>"待付款","2"=>"待发货","3"=>"已发货","4"=>"已收货"];
	public function __construct(){		
		parent::__construct();		
		$this->db=System::load_sys_class('model');		
		$this->ment=array(
						array("select","订单查询",ROUTE_M.'/'.ROUTE_C."/select"),	
						array("lists","订单列表",ROUTE_M.'/'.ROUTE_C."/lists"),
						//array("lists","机订单列表",ROUTE_M.'/'.ROUTE_C."/lists/jlist"),					
						array("lists","中奖订单",ROUTE_M.'/'.ROUTE_C."/lists/zj"),
						//array("lists","机中奖订单",ROUTE_M.'/'.ROUTE_C."/lists/jzj"),					
						array("lists","已发货",ROUTE_M.'/'.ROUTE_C."/lists/sendok"),
						array("lists","未发货",ROUTE_M.'/'.ROUTE_C."/lists/notsend"),						
						array("insert","已完成",ROUTE_M.'/'.ROUTE_C."/lists/ok"),
						array("insert","已作废",ROUTE_M.'/'.ROUTE_C."/lists/del"),
						array("insert","待收货",ROUTE_M.'/'.ROUTE_C."/lists/shouhuo"),
						
						array("dindan_del","订单记录删除",ROUTE_M.'/'.ROUTE_C."/dindan_del"),
				// 		array("genzhong","快递跟踪",ROUTE_M.'/'.ROUTE_C."/genzhong"),
                        // array("send","中奖通知",ROUTE_M."/setting/sendconfig")
		);
	}
	
	public function genzhong(){	
		include $this->tpl(ROUTE_M,'dingdan.genzhong');	
	}
public function lists(){	
		/*
			已付款,未发货,已完成
			未付款,已发货,已作废
			已付款,未发货,待收货
		*/
		$where = $this->segment(4);
		$joins = '';
		if(!$where){
			$list_where = "where `status` LIKE  '%已付款%'";
		}elseif($where == 'jlist'){ 
			//中奖		
			$list_where = "where `status` LIKE  '%已付款%' "; 
		}elseif($where == 'zj'){
			//中奖		
			$list_where = "where `huode` != '0' ";
		}elseif($where == 'jzj'){
			//中奖	
			$list_where = "where `huode` != '0' ";
		}elseif($where == 'sendok'){
			//已发货订单
			$list_where = "where `huode` != '0' and  `status` LIKE  '%已发货%'";
		}elseif($where == 'notsend'){
			//未发货订单
			$list_where = "where `huode` != '0' and `status` LIKE  '%未发货%' and d.id is not null";
			$joins = 'left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 1';
		}elseif($where == 'ok'){
			//已完成
			$list_where = "where `huode` != '0' and  `status` LIKE  '%已完成%'";
		}elseif($where == 'del'){
			//已作废		
			$list_where = "where `status` LIKE  '%已作废%'";
		}elseif($where == 'gaisend'){
			//该发货			
			$list_where = "where `huode` != '0' and `status` LIKE  '%未发货%'";
		}
		$list_where .=" AND b.auto_user!=1";
		$list_where .=" group by a.id";
		if(isset($_POST['paixu_submit'])){
			$paixu = $_POST['paixu'];
			if($paixu == 'time1'){
				$list_where.=" order by a.`time` DESC";
			}
			if($paixu == 'time2'){
				$list_where.=" order by a.`time` ASC";
			}
			if($paixu == 'num1'){
				$list_where.=" order by a.`gonumber` DESC";
			}
			if($paixu == 'num2'){
				$list_where.=" order by a.`gonumber` ASC";
			}
			if($paixu == 'money1'){
				$list_where.=" order by a.`moneycount` DESC";
			}
			if($paixu == 'money2'){
				$list_where.=" order by a.`moneycount` ASC";
			}
		
		}else{
			$list_where.=" order by a.`time` DESC";
			$paixu = 'time1';
		}
			
		$num=30;
		$total_where = str_replace("group by a.id","",$list_where);
		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid $joins $total_where");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$page->config($total,$num,$pagenum,"0");
		$hfiled = " ANY_VALUE(h.operat_name) as operat_name,
		ANY_VALUE(h.operat_status) as operat_status,
		ANY_VALUE(h.operat_desc) as operat_desc,
		ANY_VALUE(h.operat_time) as operat_time,
		ANY_VALUE(h.manage_name) as manage_name,
		ANY_VALUE(h.manage_desc) as manage_desc,
		ANY_VALUE(h.manage_time) as manage_time,
		ANY_VALUE(h.manage_status) as manage_status,
		ANY_VALUE(h.id) as hid";
		$sql = "SELECT  a.id,a.code,a.code_tmp,a.shopqishu,a.shopid as shopids,a.shopname,a.uid,a.username,
					   a.gonumber,a.moneycount,a.time,a.huode,a.status,$hfiled
			FROM `@#_member_go_record` a 
			left join `@#_member` b on a.uid=b.uid 
			left join `@#_shoplist_copy` c on a.shopid=c.id 
			left join `@#_handle` h on a.id = h.to_id and h.type = 1
			$joins
			$list_where";
		$recordlist=$this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
	    foreach ($recordlist as $key=>$v){
            if(strpos($v['status'],"未完成")){
                $status = "未完成";
            }
            if(strpos($v['status'],"已发货")){
                $status = "已发货";
            }
            if(strpos($v['status'],"未发货")){
                $status = "未发货";
            }
            if(strpos($v['status'],"已发货")){
                $status = "已发货";
            }
            if(strpos($v['status'],"已完成")){
                $status = "已完成";
            }
            if(strpos($v['status'],"已作废")){
                $status = "已作废";
            }
			$recordlist[$key]['status'] = $status;
        }
		$formot_array = ["money","yunjiage","preferential_price",'moneycount'];
        data_format($recordlist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'dingdan.list');	 
	}
//订单批量删
  public function dd_del(){
  	$arr=$_POST['ids'];
    $newstr = substr($arr,0,strlen($arr)-1); 
	$this->db->Autocommit_start();
    //file_put_contents('err.txt', '$newstr---'.var_export($newstr,TRUE)."\n", FILE_APPEND);
    $ql= $this->db->Query("delete from `@#_member_go_record` where `id` IN($newstr)");
  		if($ql){
		$this->db->Autocommit_commit();
			echo'0';
		}else{
		$this->db->Autocommit_rollback();
			echo '1';
		}	
  }
  
  
  
			//会员时手机导出
	public function user_outputexcel(){
        $this->notification();
		include('PHPExcel.php');
		$objPHPExcel=new PHPExcel(); 
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','手机号');
		$data=$this->db->Getlist("SELECT mobile FROM `@#_member` where mobilecode='1' and auto_user !=1");
		//var_dump($data);exit;
		foreach($data as $key => $value){
		    $key+= 2;   //从第二行开始填充
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value['mobile']);
		}
	//设置默认字体
	$objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
	//设置列宽
	$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(22);
	//设置居中
	$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet() -> setTitle('会员手机excel');
	$objPHPExcel-> setActiveSheetIndex(0);
	//excel保存在根目录下  如要导出文件，以下改为注释代码
	//$objPHPExcel->getActiveSheet() -> setTitle('SetExcelName');
	//$objPHPExcel-> setActiveSheetIndex(0);
	//$objWriter = $iofactory -> createWriter($objPHPExcel, 'Excel2007');
	//$objWriter -> save('SetExcelName.xlsx');	
	//导出代码
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$filename = '会员手机excel-'.date('YmdHi').'.xlsx';
	ob_end_clean();//清除缓存以免乱码出现
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Cache-Control: max-age=0');
	$objWriter -> save('php://output');		
	}
	//导出订单
	public function output_excel(){
	    // $this->notification();
		include('PHPExcel.php');
		$objPHPExcel=new PHPExcel();
		$where = $this->segment(4);
		if(!$where){
			$list_where = "where a.`status` LIKE  '%已付款%'";
		}elseif($where == 'zj'){
			//中奖		
			$list_where = "where a.`huode` != '0'";
		}elseif($where == 'sendok'){
			//已发货订单
			$list_where = "where a.`huode` != '0' and  a.`status` LIKE  '%已发货%'";
		}elseif($where == 'notsend'){
			//未发货订单
			$list_where = "where a.`huode` != '0' and a.`status` LIKE  '%未发货%'";
		}elseif($where == 'ok'){
			//已完成
			$list_where = "where a.`huode` != '0' and  a.`status` LIKE  '%已完成%'";
		}elseif($where == 'del'){
			//已作废		
			$list_where = "where a.`status` LIKE  '%已作废%'";
		}elseif($where == 'shouhuo'){
			//该发货			
			$list_where = "where a.`status` LIKE  '%待收货%'";
		}
		//设置excel列名
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','订单号');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','商品标题');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','购买用户');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','购买次数');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','购买总价');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','购买日期');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','中奖状态');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','订单状态');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','收货人');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','收货人手机');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1','快递地址');
		$data=$this->db->GetList("SELECT * FROM `@#_member_go_record` as a left join `@#_member_orderaddress_brand` as b on a.id=b.order_code left join `@#_member_dizhi` as c on c.id=b.address_id left join `@#_member` as d on a.uid=d.uid $list_where AND d.auto_user !=1 ORDER BY a.id DESC");
		foreach($data as $key => $value){
		    $key+= 2;   //从第二行开始填充
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value['code']);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['shopname']);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['mobile']);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['gonumber']."次");
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,"￥".$value['moneycount']."元");
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,date('Y-m-d H:i:s',$value['time']));
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,empty($value['huode'])?'未中奖':'中奖');
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,$value['status']);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$key,$value['shouhuoren']);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$key,$value['address']);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$key,$value['sheng']." ".$value['shi']." ".$value['xian']." ".$value['jiedao']);
		}
	//设置默认字体
	$objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
	//设置列宽
	$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(22);
	//设置居中
	$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet() -> setTitle('订单列表');
	$objPHPExcel-> setActiveSheetIndex(0);
	//excel保存在根目录下  如要导出文件，以下改为注释代码
	//$objPHPExcel->getActiveSheet() -> setTitle('SetExcelName');
	//$objPHPExcel-> setActiveSheetIndex(0);
	//$objWriter = $iofactory -> createWriter($objPHPExcel, 'Excel2007');
	//$objWriter -> save('SetExcelName.xlsx');	
	//导出代码
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$filename = '订单列表-'.date('YmdHi').'.xlsx';
	ob_end_clean();//清除缓存以免乱码出现
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header('Cache-Control: max-age=0');
	$objWriter -> save('php://output');		
	}
	//订单详细
	public function get_dingdan(){
		$code=abs(intval($this->segment(4)));
		$record=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where `id`='$code'");
		$codes_table=$record['ordercode_table'];
// 		$codes=$this->db->GetOne("SELECT shop_codes FROM `@#_".$codes_table."` where `orderid`='$code'");
// 		$record['goucode']=$codes['shop_codes'];
		if(!$record)_messageerror("参数不正确!");
		$this->mobile = System::load_sys_config('mobile');
		$key=$this->mobile['cfg_mobile_on'];
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");	
//		$logistics=$this->db->GetList("SELECT * FROM `@#_logistics` WHERE is_state=1");
		$language = $user['language'];
		$logistics=$this->db->GetList("SELECT * FROM `@#_logistics` WHERE is_state=1 and `language`='$language'");
		foreach($logistics as $key=>$vl){
			if($vl['is_free']!=2){
				$logistics[$key]['shipping_fee'] = 0;
			}else{
				if($vl['type'] !=1){
					$fee = bcmul($record['moneycount'] , $vl['ratio']);
					$fee = bcdiv($fee,100);
				}else{
					$fee = $vl['money'];
				}
				$logistics[$key]['shipping_fee'] = $fee;
			}
		}
		if(isset($_POST['submit'])){
			$record_code =explode(",",$record['status']);
			$status = $_POST['status'];
			$state = $_POST['state'];
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
			if($status == '未完成'){
				$status = $record_code[0].','.$record_code[1].','.'未完成';		
			}
			if($status == '已发货'){
				$secure_password = $_POST['secure_password'];
				if(!$this->verifyExamine($secure_password)){
					_messageerror("安全密码错误");
				}
				$res = $this->db->GetList("SELECT * FROM `@#_handle` where `type`='1' and `to_id`='$code'");
				if($res){
					_messageerror("请勿重复提交!");
				}
				$data = [
					"operat_id"=>$this->AdminInfo['uid'],
					"operat_name"=>$this->AdminInfo['username'],
					"operat_status"=>2,
					"operat_time"=>time(),
					"to_id"=>$code,
					"type"=>"1"
				];
				$sql = Assemblydata($data,"handle");
				$this->db->Query($sql);
				// $status = '已付款,已发货,待收货';
				$status = '已付款,未发货,未完成';
			}
			if($status == '未发货'){
				$status = '已付款,未发货,未完成';
			}
			if($status == '已完成'){
				$status = '已付款,已发货,已完成';	
			}
			if($status == '已作废'){
				$status = $record_code[0].','.$record_code[1].','.'已作废';				
			}			
			$ret = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status',`state`=$state,`company` = '$company',`company_code` = '$company_code',`company_money` = '$company_money' where id='$code'");
			if($ret){
				if($status == '已付款,已发货,待收货'){//发货通知
                    _send_SMS($user['mobile'],10,$company_code);
				}
				_message("更新成功");
			}else{
				_messageerror("更新失败");
			}
		}
		$sql = "SELECT c.* FROM  `go_member_orderaddress_brand` as b left join `go_member_dizhi` as c on c.id=b.address_id where b.`order_code` = '{$record['code']}' LIMIT 1";
		$user_dizhi=$this->db->GetOne($sql);
		$go_time = $record['time'];
		$shop = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE id = ".$record['shopid']);
		if(!$shop){
			$shop = $this->db->GetOne("SELECT * FROM `@#_shoplist_copy` WHERE id = ".$record['shopid']);
		}
		$formot_array = ['money'];
		data_format($shop,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		$qishu=array();
		if(!$shop['q_uid']){				
			$qishu['ren']='还未开奖';
			$qishu['ma']='还未开奖';
		}else{
			$qishu['ren']=get_user_name($user);
			$qishu['ma']=$shop['q_user_code'];
		}
		include $this->tpl(ROUTE_M,'dingdan.code');	
	}
	//订单搜索
	public function select(){
		$record = '';
		if(isset($_POST['codesubmit'])){
			$code = htmlspecialchars($_POST['text']);		
			$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `code` = '$code'");	
		}
		if(isset($_POST['usersubmit'])){	
			if($_POST['user'] == 'uid'){
				$uid = intval($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `uid` = '$uid'");	
			}
		}
		if(isset($_POST['shopsubmit'])){
			if($_POST['shop'] == 'sid'){
				$sid = intval($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopid` = '$sid'");
			}
			if($_POST['shop'] == 'sname'){
				$sname= htmlspecialchars($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopname` = '$sname'");
			}
		}
		if(isset($_POST['timesubmit'])){
				$start_time = strtotime($_POST['posttime1']) ? strtotime($_POST['posttime1']) : time();				
				$end_time   = strtotime($_POST['posttime2']) ? strtotime($_POST['posttime2']) : time();
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `time` > '$start_time' and `time` < '$end_time'");
		}
		$formot_array = ["money","yunjiage","preferential_price",'moneycount'];
        data_format($record,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'dingdan.soso');	
	}
	//订单删除
	public function dindan_del(){
		foreach($this->ment as $key=>$vl){
			if($vl[0] == 'dindan_del'){
				$vl[3] = 2;
				$this->ment[$key] = $vl;
			}
		}
		if(isset($_POST['timesubmit'])){
				$time = strtotime($_POST['time']);
				if($time){
					$del=$this->db->Query("DELETE FROM `@#_member_go_record` WHERE time<='$time'");
					if($del){
						_message('删除成功');
					}else{
						_message('删除失败');
					}
				}				
				
		}
		include $this->tpl(ROUTE_M,'dingdan.del');	
	}
	//私有方法保存菜单
	private function https_request($url,$data = null){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return json_decode($output);
	}
	//发送发货通知
	private function send_wx_ship_code($gid=null){
		//查询模板消息id
		$template_id = $this->db->GetOne("SELECT * FROM `@#_wxch_cfg` WHERE `cfg_name` = 'template_fh'");
		if(empty($template_id['cfg_value'])){
			return false;
		}
		$info = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$gid'");
		$member_band = $this->db->GetOne("SELECT * FROM `@#_member_band` WHERE `b_uid` = '{$info['q_uid']}' AND `b_type` = 'weixin'");
		if(empty($member_band)){
			return false;
		}
		$orders = $this->db->GetOne("SELECT * FROM `@#_member_go_record` WHERE `uid` = '{$info['q_uid']}' AND `shopid` = '{$info['id']}'");
		if(!empty($member_band['b_code'])){
			//发送数据组合
			$data = array(
				"touser" => $member_band['b_code'],
				"template_id"=>$template_id['cfg_value'],
				"url"=>WEB_PATH."/mobile/mobile/dataserver/".$info['id'], 
				"data" => array(
					'first' =>array(
						"value"=>"您好，您的中奖商品已经发货，请注意查收！",
						"color"=>"#173177",
						),
					"keyword1"=>array(
						"value"=>$orders['company'],
						"color"=>"#173177",
						),
					"keyword2"=>array(
						"value"=>$orders['company_code'],
						"color"=>"#173177",
						),
					"keyword3"=>array(
						"value"=>$info['title'],
						"color"=>"#173177",
						),
					"keyword4"=>array(
						"value"=>_cfg("web_name"),
						"color"=>"#173177",
						),
					"keyword5"=>array(
						"value"=>_cfg("cell"),
						"color"=>"#173177",
						),
					"remark"=>array(
						"value"=>"本订单由"._cfg("web_name")."提供发货及售后服务,感谢您的支持",
						"color"=>"#173177",
						),
				),
			);
		}
		return json_encode($data);
	}
	//回收列表
	public function  recover(){
        $this->ment=array(
            array("genzhong","收货订单列表",ROUTE_M.'/'.ROUTE_C."/recover"),
        );
		$list_where="where `huode` != 0 and  `status` LIKE  '%已回收%'";
		$num=20;
		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` $list_where");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$page->config($total,$num,$pagenum,"0");
		
		$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record` $list_where",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	

		$formot_array = ["money","yunjiage","preferential_price",'moneycount'];
        data_format($recordlist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'dingdan.recover');	
	}
	/* 回收比例*/
	public function proportion(){
		$this->ment=array(
						array("select","平台回收比例",ROUTE_M.'/'.ROUTE_C."/proportion"),
		);
		$proportion=System::load_sys_config("proportion","proportion");//回收比例
		if($_POST){
		$html="<?php\n ";
		$html.="return array(\n";
		$html.="'proportion' =>'".$_POST['proportion']."' \n ";
		$html.="); \n ?>";
		$ok=file_put_contents(G_CONFIG.'proportion.inc.php',$html);
		if($ok){
			 _message("回收比例设置成功！");
		}else{
			_message("回收比例设置失败！"); 
		}
		}
		
		
		
		
		include $this->tpl(ROUTE_M,'dingdan.proportion');	
	}
	//优惠购订单
	public function yhlists(){
		$this->ment=array(
			// array("select","订单查询",ROUTE_M.'/'.ROUTE_C."/select"),	
			array("yhlists","惠购订单列表",ROUTE_M.'/'.ROUTE_C."/yhlists"),
			array("yhlists","已发货",ROUTE_M.'/'.ROUTE_C."/yhlists/3"),
			array("yhlists","未发货",ROUTE_M.'/'.ROUTE_C."/yhlists/2"),						
			array("yhlists","已完成",ROUTE_M.'/'.ROUTE_C."/yhlists/5"),
			array("yhlists","已作废",ROUTE_M.'/'.ROUTE_C."/yhlists/6"),
			array("yhlists","待收货",ROUTE_M.'/'.ROUTE_C."/yhlists/4"),
			// array("genzhong","快递跟踪",ROUTE_M.'/'.ROUTE_C."/genzhong"),
		);
		$num=30;
		$pagenum=1;
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$status = $this->segment(4)?:'';
		if($status){
			$where = " where o.status = $status";
		}
		$total = $this->db->GetCount("select COUNT(*) from `@#_orders` $where");
		$hfiled = "h.operat_name,h.operat_status,h.operat_desc,h.operat_time,h.manage_name,h.manage_desc,h.manage_time,h.manage_status,h.id as hid";
		$sql = "select o.*,$hfiled,d.id as did from `@#_orders` o 
			left join `@#_member_orderaddress_brand` d on d.orderid = o.id and d.mode = 2
			left join `@#_handle` h on o.id = h.to_id and h.type = 2 
			$where group by o.id order by o.time desc";
		$recordlist = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		foreach($recordlist as $k=> $vl){
			if($vl['status'] ==2 && $vl['did']){
				$recordlist[$k]['status'] = 21;
			}
		}
		$page=System::load_sys_class('page');
		$page->config($total,$num,$pagenum,"0");
		$formot_array = ["money","yunjiage","preferential_price",'moneycount'];
        data_format($recordlist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'dingdan.yh_list');	 
	}
	//订单批量删
	public function yhdd_del(){
		$arr=$_POST['ids'];
		$newstr = substr($arr,0,strlen($arr)-1); 
		$this->db->Autocommit_start();
		$ql= $this->db->Query("delete from `@#_orders` where `id` IN($newstr)");
		if($ql){
			$this->db->Autocommit_commit();
			  echo'0';
		}else{
		  	$this->db->Autocommit_rollback();
			  echo '1';
		}	
	}
	public function yhdd_export(){
		$where = $this->segment(4);
		$sql = "select * from `@#_orders` $where";
		$header = ["订单号","商品标题","用户","用户ID","购买数量","购买总价","购买日期","状态"];
		$body = [
				"code",
				"shopname",
				"username",
				"uid",
				"gonumber",
				"moneycount",
				["time","date","Y-m-d H:i"],
				["status","status",$this->yh_status]
			];
		$this->export($header,$body,"优惠订单",$sql);
	}
	public function get_yhdingdan(){
		
		$id=abs(intval($this->segment(4)));
		$record=$this->db->GetOne("SELECT * FROM `@#_orders` where `id`='$id'");
		if(!$record)_messageerror("参数不正确!");
		$mobile = System::load_sys_config('mobile');
		$key=$mobile['cfg_mobile_on'];
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");	
		$language = $user['language'];
		$logistics=$this->db->GetList("SELECT * FROM `@#_logistics` WHERE is_state=1 and `language`='$language'");
		if(isset($_POST['submit'])){
			$code = abs(intval($_POST['code']));
			$secure_password = $_POST['secure_password'];
			$status = $_POST['status'];
			if($status == 3){
				if(!$this->verifyExamine($secure_password)){
					_messageerror("安全密码错误");
				}
				$res = $this->db->GetList("SELECT * FROM `@#_handle` where `type`='2' and `to_id`='$code'");
				if($res){
					_messageerror("请勿重复提交!");
				}
				$data = [
					"operat_id"=>$this->AdminInfo['uid'],
					"operat_name"=>$this->AdminInfo['username'],
					"operat_status"=>2,
					"operat_time"=>time(),
					"to_id"=>$code,
					"type"=>"2"
				];
				$sql = Assemblydata($data,"handle");
				$this->db->Query($sql);
				$status = $record['status'];
			}
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
			$ret = $this->db->Query("UPDATE `@#_orders` SET `status`='$status',`company` = '$company',`company_code` = '$company_code',`company_money` = '$company_money' where id='$code'");
			if($ret){
				if($status == '已付款,已发货,待收货'){//发货通知
                    _send_SMS($user['mobile'],10,$company_code);
				}
				_message("更新成功",G_MODULE_PATH."/dingdan/yhlists");
			}else{
				_message("更新失败");
			}
		}
		$user_dizhi=$this->db->GetOne("SELECT c.* FROM `@#_orders` as a left join `@#_member_orderaddress_brand` as b on a.code=b.order_code left join `@#_member_dizhi` as c on c.id=b.address_id where b.`order_code` = '$record[code]' LIMIT 1");
		$go_time = $record['time'];
		include $this->tpl(ROUTE_M,'yh_dingdan.code');	
	}
	//拼购订单列表
	public function groupList(){
		$this->ment=array(
			array("groupList","拼购订单列表",ROUTE_M.'/'.ROUTE_C."/groupList"),
			array("groupList","已发货",ROUTE_M.'/'.ROUTE_C."/groupList/3"),
			array("groupList","未发货",ROUTE_M.'/'.ROUTE_C."/groupList/2"),						
			array("groupList","已完成",ROUTE_M.'/'.ROUTE_C."/groupList/5"),
			array("groupList","已作废",ROUTE_M.'/'.ROUTE_C."/groupList/6"),
			array("groupList","待收货",ROUTE_M.'/'.ROUTE_C."/groupList/4"),
		);
		$num=30;
		$pagenum=1;
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$status = $this->segment(4);
		$where = '';
		if($status){
			$where = " where status = $status";
			if($status == 2){
				$where .= " and huode <> '0'";
			}
		}
		$total = $this->db->GetCount("select COUNT(*) from `@#_orders_group` $where");
		$hfiled = "h.operat_name,h.operat_status,h.operat_desc,h.operat_time,h.manage_name,h.manage_desc,h.manage_time,h.manage_status,h.id as hid";
		$sql = "select o.* from `@#_orders_group` o
			left join `@#_handle` h on o.id = h.to_id and h.type = 2 
			$where";
		$recordlist = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		$page=System::load_sys_class('page');
		$page->config($total,$num,$pagenum,"0");
		$formot_array = ["money","yunjiage","preferential_price",'moneycount'];
        data_format($recordlist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'dingdan.group_list');	
	}

	/**
	 * 删除拼购订单
	 */
	public function groupDel(){
		$arr=$_POST['ids'];		
		$newstr = substr($arr,0,strlen($arr)-1); 
		if(empty($newstr)){$newstr = $this->segment(4);}
		$this->db->Autocommit_start();
		$this->db->query("delete from `@#_orders_group` where `id` in ($newstr)");
		$this->db->query("delete from `@#_handle` where `to_id` in ($newstr) and `type`=2");
		$this->db->Autocommit_commit();
		if($arr){
			echo 0;return;
		}
		_message("删除成功!");
	}
	
	public function get_groupDingdan(){
		$id=abs(intval($this->segment(4)));
		$record=$this->db->GetOne("SELECT * FROM `@#_orders_group` where `id`='$id'");
		if(!$record)_messageerror("参数不正确!");
		$mobile = System::load_sys_config('mobile');
		$key=$mobile['cfg_mobile_on'];
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");	
		$language = $user['language'];
		$logistics=$this->db->GetList("SELECT * FROM `@#_logistics` WHERE is_state=1 and `language`='$language'");
		if(isset($_POST['submit'])){
			$code = abs(intval($_POST['code']));
			$status = $_POST['status'];
			$secure_password = $_POST['secure_password'];
			if($status == 3){
				if(!$this->verifyExamine($secure_password)){
					_messageerror("安全密码错误");
				}
				$res = $this->db->GetList("SELECT * FROM `@#_handle` where `type`='3' and `to_id`='$code'");
				if($res){
					_messageerror("请勿重复提交!");
				}
				$data = [
					"operat_id"=>$this->AdminInfo['uid'],
					"operat_name"=>$this->AdminInfo['username'],
					"operat_status"=>2,
					"operat_time"=>time(),
					"to_id"=>$code,
					"type"=>"3"
				];
				$sql = Assemblydata($data,"handle");
				$this->db->Query($sql);
				$status = $record['status'];
			}
			$record_code =explode(",",$record['status']);
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
			$ret = $this->db->Query("UPDATE `@#_orders_group` SET `status`='$status',`company` = '$company',`company_code` = '$company_code',`company_money` = '$company_money' where id='$code'");
			if($ret){
				if($status == '已付款,已发货,待收货'){//发货通知
                    _send_SMS($user['mobile'],10,$company_code);
				}
				_message("更新成功",G_MODULE_PATH."/dingdan/groupList");
			}else{
				_message("更新失败");
			}
		}
		$user_dizhi=$this->db->GetOne("SELECT c.* FROM `@#_orders_group` as a left join `@#_member_orderaddress_brand` as b on a.code=b.order_code left join `@#_member_dizhi` as c on c.id=b.address_id where b.`order_code` = '$record[code]' LIMIT 1");
		$go_time = $record['time'];
		include $this->tpl(ROUTE_M,'yh_dingdan.code');	
	}

	/**
	 * 导出拼团订单
	 *
	 * @param integer $del
	 * @return void
	 */
	public function groupExport(){
	    $wheres = $this->segment(4);
		$sql = "SELECT * FROM `@#_orders_group` WHERE 1=1";
		if($wheres){
			$sql .= " and '$wheres'";
		}
		$header = ["订单号","用户","用户ID","商品ID","商品名称","购买数量","订单金额","订单状态","是否中奖","下单时间",];
		$body = ["code","username","uid","shopid","shopname","gonumber",
		"moneycount",["group_status","status",[1=>"进行中",2=>"完成",3=>"失败"]],"huode",["time","date"]];
		$this->export($header,$body,"拼团订单",$sql);
	}

	public function jifen_order_list($del=0){
		$this->ment=array(
            array("jifen_order_list","直购订单列表",ROUTE_M.'/'.ROUTE_C."/jifen_order_list"),                       
            array("jifen_order_del_list","直购订单删除列表",ROUTE_M.'/'.ROUTE_C."/jifen_order_del_list"),                       
        );
        $num=30;
        $pagenum=1;
        if(isset($_GET['p'])){$pagenum=$_GET['p'];} else{$pagenum=1;}    
        $status = $this->segment(4);
        $where = '';
		if($status){
            $where .= 'and a.status=' . $status;
        }
		if($del){
			$where.='and a.delete_time is not null';
		}else{
			$where.='and a.delete_time is null';
		}
        $sql = "SELECT a.*,m.username FROM `@#_member_goods_order` a left join `@#_member` m on a.uid = m.uid WHERE 1 $where ORDER BY `cretime` DESC ";
		$total = $this->db->GetCount($sql);
		$page=System::load_sys_class('page');
		$page->config($total,$num,$pagenum,"0");
        $recordlist = $this->db->GetPage($sql,array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		$formot_array = ["money","remoney",'moneycount'];
        data_format($recordlist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
		include $this->tpl(ROUTE_M,'dingdan.jifen_list');	
	}

	/**
	 * 删除订单列表
	 *
	 * @return void
	 */
	public function jifen_order_del_list(){
		$this->jifen_order_list(1);
	}

	//订单详细
	public function jifen_details() {
		$this->ment = [
			array("jifen_order_list","直购订单列表",ROUTE_M.'/'.ROUTE_C."/jifen_order_list"),                       
			array("jifen_details","订单详情",ROUTE_M.'/'.ROUTE_C."/jifen_details",2), 
		];
		$orderid = $this->segment(4);
		$sql = "SELECT * FROM `@#_member_goods_order` WHERE `oid`=$orderid";
		$order = $this->db->GetOne($sql);
		//商品信息
		$oid = $order['oid'];
		$re = $this->db->Getlist("SELECT * FROM `@#_member_goods_item` WHERE `oid` = '$oid'");
		foreach ($re as $key => $value) {
			$sid = $value['sid'];
			$item = $this->db->GetOne("SELECT * FROM `@#_shoplist_jifen` WHERE `sid`='$sid'");
			$re[$key]['s_item'] = $item;
		}
		//用户信息
		$uid = $order['uid'];
		$user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`=$uid");
		//地址信息
		$aid = $order['aid'];
		$address = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id`=$aid");
		include $this->tpl(ROUTE_M, 'dingdan.jifen_order_details');
	}

	public function jifen_order_del(){
		$arr=$_POST['ids'];		
		$newstr = substr($arr,0,strlen($arr)-1); 
		if(empty($newstr)){$newstr = $this->segment(4);}
		$this->db->Autocommit_start();
		$ql= $this->db->Query("delete from `@#_member_goods_order` where `id` IN($newstr)");
		$q2= $this->db->Query("delete from `@#_member_goods_item` where `oid` IN($newstr)");
		if($ql && $q2){
			$this->db->Autocommit_commit();
			if($arr){
				echo 0;
				return;
			}
			  _message("删除成功");
		}else{
		  	$this->db->Autocommit_rollback();
			  if($arr){
				echo 1;
				return;
			}
			  _messageerror("删除失败");
		}	
	}

	//订单发货
	public function jifen_order_send() {
		$this->jifen_details();
		exit;
		$orderid = $this->segment(4);
		$order = $this->db->GetOne("SELECT * FROM `@#_member_pay_order` WHERE `oid`=$orderid");
		//商品信息
		$oid = $order['oid'];
		$re = $this->db->Getlist("SELECT * FROM `@#_member_goods_order` WHERE `oid` = '$oid'");
		foreach ($re as $key => $value) {
			$sid = $value['sid'];
			$item = $this->db->GetOne("SELECT * FROM `@#_shoplist_jifen` WHERE `sid`='$sid'");
			$re[$key]['s_item'] = $item;
		}
		//用户信息
		$uid = $order['uid'];
		$user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`=$uid");
		//地址信息
		$aid = $order['aid'];
		$address = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id`=$aid");
		include $this->tpl(ROUTE_M, 'dingdan.jifen_order_details');
	}
	/**
	 * 
	 * 订单发货
	 * @return void
	 */
	public function jifen_order_dosend() {
		$data = $_POST;
		$time = time();
		$message = '发货';
		$logistics_way = $data['logistics_way'];
		$logistics_code = $data['logistics_code'];
		$oid = $data['oid'];
		$sql = "UPDATE `@#_member_goods_order` SET `logistics_way`='$logistics_way',`logistics_code`='$logistics_code',`status`='3',`sendtime`='$time',`uptime`='$time' WHERE `oid`='$oid'";
		$sql1 = "UPDATE `@#_member_pay_order` SET `logistics_way`='$logistics_way',`logistics_code`='$logistics_code',`status`='3',`sendtime`='$time',`uptime`='$time' WHERE `oid`='$oid'";
		if ($data['renew']) {
			$message = '更新';
			$sql = "UPDATE `@#_member_goods_order` SET `logistics_way`='$logistics_way',`logistics_code`='$logistics_code',`status`='3',`sendtime`='$time',`uptime`='$time' WHERE `oid`='$oid'";
			$sql1 = "UPDATE `@#_member_pay_order` SET `logistics_way`='$logistics_way',`logistics_code`='$logistics_code',`uptime`='$time' WHERE `oid`='$oid'";
		}
		// echo $sql;

		$re = $this->db->Query($sql);
		$re1 = $this->db->Query($sql1);
		if ($re) {

			_message($message . '成功', WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . '/jifen_details/'.$oid);
		} else {
			_message($message . '失败');
		}
	}

	//订单确认收货
	public function jifen_order_reget() {
		$oid = $this->segment(4);
		// echo $oid;
		$uptime = time();
		$sql = "UPDATE `@#_member_goods_order` SET `status`='4',`uptime`='$uptime' WHERE `oid`='$oid'";
		$sql1 = "UPDATE `@#_member_pay_order` SET `status`='4',`uptime`='$uptime' WHERE `oid`='$oid'";
		$re = $this->db->Query($sql);
		$re1 = $this->db->Query($sql1);
		$message = '收货';
		if ($re) {
			_message($message . '成功', WEB_PATH . '/' . ROUTE_M . '/' . ROUTE_C . '/dingdan.jifen_list');
		} else {
			_message($message . '失败');
		}
	}

	//充值记录导出
	public function rech_export(){
		// $where = $this->segment(4);
		$sql = "select r.*,m.username from `@#_member_rechange` r left join `@#_member` m on r.uid = m.uid where content like '%充值%'";
		$header = ["订单号","金额","方式","用户","用户ID","日期"];
		$body = [
				"code",
				"money",
				'content',
				"username",
				"uid",
				["time","date","Y-m-d H:i"],
			];
		$this->export($header,$body,"充值记录",$sql);
	}
	//佣金提现记录导出
	public function comm_export(){
		// $where = $this->segment(4);
		$sql = "select r.*,m.username as m_username from `@#_member_cashout` r left join `@#_member` m on r.uid = m.uid where auditstatus = 1";
		$res = $this->db->GetList($sql);
		$header = ["用户","用户ID","开户人","银行名称","支行","金额","日期","银行帐号","联系电话","手续费"];
		$body = [
			"m_username",
			"uid",
            "username",
            "bankname",
            "branch",
            "money",
			["time","date","Y-m-d H:i"],
			"banknumber",
			"linkphone",
			"procefees",
		];
		$this->export($header,$body,"佣金提现记录",$sql);
	}
}