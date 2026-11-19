<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class pay extends admin {

	protected $ment;	

	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');

		$this->ment=array(

						array("pay_list","网络充值通道",ROUTE_M.'/'.ROUTE_C."/pay_list"),
						// array("pay_add","添加网络充值",ROUTE_M.'/'.ROUTE_C."/pay_add"),
						array("kefu_list","银行卡充值",ROUTE_M.'/'.ROUTE_C."/kefu_list"),
						array("add_kefu","添加银行卡充值",ROUTE_M.'/'.ROUTE_C."/add_kefu"),
						array("usdt_list","USDT充值",ROUTE_M.'/'.ROUTE_C."/usdt_list"),
						array("add_usdt","添加USDT充值",ROUTE_M.'/'.ROUTE_C."/add_usdt"),

		);
	}
	//客服列表
	public function kefu_list(){
		$list=$this->db->GetList("SELECT * FROM `@#_kefu` WHERE `type`=2");
		include $this->tpl(ROUTE_M,'kefulist');
	}
	//添加客服
	public function add_kefu(){
		if(isset($_POST['dosubmit'])){
			if($_FILES){
				$path='photo';	
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
			$name=htmlspecialchars($_POST['name']);
			$bank_user=htmlspecialchars($_POST['bank_user']);
			$bank_no=htmlspecialchars($_POST['bank_no']);
			$bank_name=htmlspecialchars($_POST['bank_name']);
			// $name=htmlspecialchars($_POST['name']);
			// if($name)_message("请填写用户名");
			$status = $_POST['status'];

			$money['range'] = $_POST['m'];
            $money['ranges'] = $_POST['ms'];
            $money['isinput'] =$_POST['isinput'];
            $m = serialize($money);
			$time=time();
			$res=$this->db->Query("INSERT INTO `@#_kefu`(`bank_user`,`bank_no`,`bank_name`,`img`,`time`,`type`,`pay_monerange`,`status`)
			VALUES('$bank_user','$bank_no','$bank_name','$img','$time','2','$m','$status')");
			if($res){
				_message("添加成功！",G_MODULE_PATH."/pay/kefu_list");
			}else{
				_message("添加失败！");
			}
		}
		include $this->tpl(ROUTE_M,'add_kefu');
	}
    //更改客服在线状态
    public function up_state(){
        if(isset($_POST['status'])){
            if($_POST['status'] == '1'){
                $sta='1';
            }else{
                $sta='-1';
            }
            $id=htmlspecialchars($_POST['kid']);
            $res=$this->db->Query("UPDATE `@#_kefu` SET `status` ='$sta' WHERE id='$id'");
            if($res){
                echo 1;
            }else{
                echo -1;
            }
        }
    }
	//客服修改
	public function kefu_set(){
		$id=htmlspecialchars($this->segment(4));
		if($id){
			$info=$this->db->GetOne("SELECT * FROM `@#_kefu` WHERE id='$id'");
			$info['pay_monerange'] = @unserialize($info['pay_monerange']);
			$fw = $info['pay_monerange'];
			if(isset($_POST['dosubmit'])){
				if($_FILES){
				$path='photo';	
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
				$name=$_POST['name'];
				$bank_user=htmlspecialchars($_POST['bank_user']);
				$bank_no=htmlspecialchars($_POST['bank_no']);
				$bank_name=htmlspecialchars($_POST['bank_name']);
				//$img=$_POST['img'];
				$id=$_POST['id'];
				$status=$_POST['status'];
				// if($name)_message("请填写用户名");
				$money['range'] = $_POST['m'];
				$money['ranges'] = $_POST['ms'];
				$money['isinput'] =$_POST['isinput'];
				$m = serialize($money);

				$res=$this->db->Query("UPDATE `@#_kefu` SET `name` ='$name',`img`='$img',`status`='$status',`bank_user` ='$bank_user',`bank_no` ='$bank_no',`bank_name` ='$bank_name',`type`=2,`pay_monerange`='$m' WHERE id='$id'");
				if($res){
					_message('修改成功！',G_MODULE_PATH."/pay/kefu_list");
				}else{
					_message('修改失败！');
				}
			}
		}else{
			_message('参数错误！');
		}
		include $this->tpl(ROUTE_M,'kefu_set');

	}
	//删除客服
	public function del_kefu(){
		$id=htmlspecialchars($this->segment(4));
		if($id){
			$res=$this->db->Query("DELETE FROM `@#_kefu` WHERE id='$id'");
			if($res){
				_message('删除成功！');
			}else{
				_message('删除失败！');
			}
		}else{
			_message('参数错误！');
		}
	}
	//支付列表
	public function pay_list(){

		$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where 1 ORDER BY `sort` DESC");
		$res =$this->db->GetOne("select * from `@#_config` where `name` = 'pay_prefix'");
		$web['pay_prefix'] = $res['value'];
		if(isset($_POST['dosubmit'])){
			$pay_prefix = safe_replace($_POST['pay_prefix']);
            $this->db->Query("UPDATE `@#_config` SET `value`='$pay_prefix' WHERE `name`='pay_prefix'");
			$this->cfgPut();
			_message("ok");
		}
		include $this->tpl(ROUTE_M,'paylist');
	}

	public function up_paystatus(){
		$id = $_POST['id'];
		$status = $_POST['status'];
		$res = $this->db->Query("update `@#_pay` set pay_start = $status where pay_id = $id");
		if($res){
			echo 1;
		}else{
			echo 0;
		}
	}
    /**
     * 支付排序
     */
	public function paylistorder(){
	    $or = $_POST['sort'];
	    if (!is_array($or)){
	        _messageerror('参数错误');
        }
	    foreach ($or as $k=>$v){
	        $sql = "UPDATE `@#_pay` SET `sort` = '$v' WHERE `pay_id` = $k";
	        $this->db->Query($sql);
        }
	    _message('排序成功');
    }
    public function pay_del(){
        $id=htmlspecialchars($this->segment(4));
        $res = $this->db->GetOne("SELECT * FROM `@#_pay` where `pay_id` = '$id'");
        if(empty($res)){
            _message('没有查询到改记录！');
        }
        if($res['pay_start'] ==1){
            _message('请先关闭该支付方式再删除！');
        }
        $re =$this->db->Query("DELETE FROM `@#_pay` WHERE `pay_id` = '$id'");
        if ($re){
            _message('删除成功！');
        }else{
            _message('删除失败！');
        }
    }
	public function pay_bank(){
		if(isset($_POST['dosubmit'])){
			$bank_type = htmlspecialchars($_POST['bank_type']);
			$q_ok = $this->db->Query("UPDATE `@#_caches` SET `value` = '$bank_type' where `key` = 'pay_bank_type'");
			if($q_ok){
				_message("操作成功!");
			}else{
				_message("操作失败!");
			}
		}

		$bank = $this->db->GetOne("select * from `@#_caches` where `key` = 'pay_bank_type'");
		if(!$bank)_message("查询失败");
		include $this->tpl(ROUTE_M,'paybank');
	}

	public function pay_keys(){
		$arr = array(
			"url"=>array("name"=>"接口网关","val"=>"https://pay.joglobalpay.com"),
			"mchNo"=>array("name"=>"商户号","val"=>"MCHIWXPOM3Y"),
			"wayCode"=>array("name"=>"通道编码","val"=>"BRL_PAY_PIN"),
			"currency"=>array("name"=>"币种","val"=>"BRL"),
			"key"=>array("name"=>"密钥","val"=>"XUWBR4QQ8D66OUFCQY37VMSHH8ZAOKIH"),
		);
		$brr = [
			["name"=>"名字","val"=>"name","required"=>true,"msg"=>"马来西亚只支持纯英文"],
			["name"=>"身份ID","val"=>"document","required"=>true,"msg"=>"巴西填付款人CPF"],
			["name"=>"邮箱","val"=>"email","required"=>false,"msg"=>"马来西亚必填"],
			["name"=>"手机号","val"=>"phone","required"=>false,"msg"=>"马来西亚必填，埃及必填"],
			["name"=>"付款账号","val"=>"account","required"=>false,"msg"=>"泰国网银转账必填"],
		];
		echo serialize($arr);
		echo "<br/>";
		echo serialize($brr);
	}

	public function pay_add(){
		// $pay['pay_input'] = [
		// 	["name"=>"QRIS","type"=>"radio","val"=>"payment_method","content"=>"qris","msg"=>"QRIS","required"=>true,"children"=>
		// 		[["name"=>"QRIS","type"=>"radio","val"=>"payment_channel","content"=>"QRIS","msg"=>"QRIS","required"=>true]]
		// 	],
		// 	["name"=>"card","type"=>"radio","val"=>"payment_method","content"=>"card","msg"=>"card","required"=>true,"children"=>
		// 		[["name"=>"CARD","type"=>"radio","val"=>"payment_channel","content"=>"CARD","msg"=>"CARD","required"=>true]]
		// 	],
		// 	["name"=>"VA","type"=>"radio","val"=>"payment_method","content"=>"virtual_account","msg"=>"VA","required"=>true,"children"=>[
		// 			["name"=>"BRI","type"=>"radio","val"=>"payment_channel","content"=>"BRI","msg"=>"BRI","required"=>true],
		// 			["name"=>"BNI","type"=>"radio","val"=>"payment_channel","content"=>"BNI","msg"=>"BNI","required"=>true],
		// 			["name"=>"MANDIRI","type"=>"radio","val"=>"payment_channel","content"=>"MANDIRI","msg"=>"MANDIRI","required"=>true],
		// 			["name"=>"PERMATA","type"=>"radio","val"=>"payment_channel","content"=>"PERMATA","msg"=>"PERMATA","required"=>true],
		// 			["name"=>"BSI","type"=>"radio","val"=>"payment_channel","content"=>"BSI","msg"=>"BSI","required"=>true],
		// 			["name"=>"CIMB","type"=>"radio","val"=>"payment_channel","content"=>"CIMB","msg"=>"CIMB","required"=>true],
		// 			["name"=>"BCA","type"=>"radio","val"=>"payment_channel","content"=>"BCA","msg"=>"BCA","required"=>true],
		// 			["name"=>"BNC","type"=>"radio","val"=>"payment_channel","content"=>"BNC","msg"=>"BNC","required"=>true],
		// 			["name"=>"BTPN","type"=>"radio","val"=>"payment_channel","content"=>"BTPN","msg"=>"BTPN","required"=>true],
		// 			["name"=>"DANAMON","type"=>"radio","val"=>"payment_channel","content"=>"DANAMON","msg"=>"DANAMON","required"=>true],
		// 		],
		// 	],
		// 	["name"=>"ewallet","type"=>"radio","val"=>"payment_method","content"=>"ewallet","msg"=>"ewallet","required"=>true,"children"=>[
		// 			["name"=>"OVO","type"=>"radio","val"=>"payment_channel","content"=>"OVO","msg"=>"OVO","required"=>true],
		// 			["name"=>"DANA","type"=>"radio","val"=>"payment_channel","content"=>"DANA","msg"=>"DANA","required"=>true],
		// 			["name"=>"LINKAJA","type"=>"radio","val"=>"payment_channel","content"=>"LINKAJA","msg"=>"LINKAJA","required"=>true],
		// 			["name"=>"SHOPEEPAY","type"=>"radio","val"=>"payment_channel","content"=>"SHOPEEPAY","msg"=>"SHOPEEPAY","required"=>true],
		// 			["name"=>"DANAQRIS","type"=>"radio","val"=>"payment_channel","content"=>"DANAQRIS","msg"=>"DANAQRIS","required"=>true],
		// 		],
		// 	],
		// ];
		if(isset($_POST['dosubmit'])){
			$pay_thumb = htmlspecialchars($_POST['thumb']);
			$name = htmlspecialchars($_POST['pay_name']);
			$pay_class = htmlspecialchars($_POST['pay_class']);
			$des = htmlspecialchars($_POST['pay_des']);
			$start = intval($_POST['pay_start']);
			$pay_key = $_POST['pay_key'];
			$keys = [];
			foreach($pay_key['key'] as $key=>$val){
				$keys[$val]=array("name"=>$pay_key['name'][$key],"val"=>$pay_key['value'][$key]);
			}
			$pay_key = serialize($keys);
            $money['range'] = $_POST['m'];
            $money['ranges'] = $_POST['ms'];
            $money['isinput'] =$_POST['isinput'];
            $m = serialize($money);
            $sort = htmlspecialchars($_POST['sort']);
			$data = [
				"pay_name" => $name,
				"pay_thumb" => $pay_thumb,
				"pay_class" => $pay_class,
				"pay_des" => $des,
				"pay_start" => $start,
				"pay_key" => $pay_key,
				"pay_monerange" => $m,
				"sort" => $sort,
				"pay_type" => 1,
				"pay_mobile" => 1,
			];
			$sql = Assemblydata($data,"pay");
			$this->db->Query($sql);
			_message("操作成功",WEB_PATH.'/pay/pay/pay_list');
		}
		include $this->tpl(ROUTE_M,'payadd');
	}
	public function pay_set(){
		$payid = intval($this->segment(4));

		$pay = $this->db->GetOne("SELECT * FROM `@#_pay` where `pay_id` = '$payid'");
		if(!$pay)_message("参数错误");
		if($pay['pay_class'] == 'yeepay'){
			if(!file_exists(G_SYSTEM.'modules/'.ROUTE_M.'/lib/yeepay.class.php')){
				_message("开通易宝支付请联系官网");
			}
		}

		$pay['pay_key']  = @unserialize($pay['pay_key']);
		$pay['pay_input']  = mb_unserialize($pay['pay_input']);
        $pay['pay_monerange'] = @unserialize($pay['pay_monerange']);
		if(!is_array($pay['pay_key'])){
			$pay['pay_key'] = array("id"=>array("name"=>"商户号","val"=>""),"key"=>array("name"=>"密匙","val"=>""));
		}
		$pay_thumb = $pay['pay_thumb'];
		if(isset($_POST['dosubmit'])){
			$name = htmlspecialchars($_POST['pay_name']);
			$pay_thumb = htmlspecialchars($_POST['thumb']);
			//$type = intval($_POST['pay_type']);
			$des = htmlspecialchars($_POST['pay_des']);
			$pay_input =$_POST['pay_input']?:[];
			$start = intval($_POST['pay_start']);
			foreach($pay['pay_input'] as $key=>&$vl){
				$vl['name'] = $pay_input['name'][$key];
				$vl['content'] = $pay_input['content'][$key];
				$vl['msg'] = $pay_input['msg'][$key];
				$vl['status'] = $pay_input['status'][$key];
				foreach($pay_input['c_name'][$key] as $k=>$v){
					$vl['children'][$k]['name'] = $v;
				}
				foreach($pay_input['c_content'][$key] as $k=>$v){
					$vl['children'][$k]['content'] = $v;
				}
				foreach($pay_input['c_status'][$key] as $k=>$v){
					$vl['children'][$k]['status'] = $v;
				}
			}
			$pay_input = serialize($pay['pay_input']);
			$pay_key = $_POST['pay_key'];
			foreach($pay_key as $key=>$val){
				$pay_key[$key]=array("name"=>$pay['pay_key'][$key]['name'],"val"=>$pay_key[$key]);
			}
			// $pay_key['url'] = ['name'=>"网关","https://www.xpay.plus"];
			$pay_key = serialize($pay_key);
            $money['range'] = $_POST['m'];
            $money['ranges'] = $_POST['ms'];
            $money['isinput'] =$_POST['isinput'];
            $m = serialize($money);
            $sort = htmlspecialchars($_POST['sort']);
			$this->db->Query("UPDATE `@#_pay` SET `pay_name` = '$name',`pay_thumb` = '$pay_thumb',`pay_des` = '$des',`pay_start` = '$start',`pay_key` = '$pay_key',`pay_monerange`='$m',`sort` = '$sort',`pay_input`='$pay_input' where `pay_id` = '$payid'");
			_message("操作成功",WEB_PATH.'/pay/pay/pay_list');
		}

		include $this->tpl(ROUTE_M,'payset');
	}

	public function usdt_list(){
		$list=$this->db->GetList("SELECT * FROM `@#_kefu` WHERE `type` = 3");
		include $this->tpl(ROUTE_M,'usdt_list');
	}

	public function add_usdt(){
		$language_backend = $this->language_backend;
		$language = $this->db->GetOne("SELECT * FROM `@#_language_backend` where `lg_value` = '$language_backend'");
		$country = $language['country'];
		if(isset($_POST['dosubmit'])){
			if($_FILES){
				$path='photo';	
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
			$bank_name=htmlspecialchars($_POST['bank_name']);
			if($bank_name){
				$data['bank_name'] = $bank_name;
			}
			$url=htmlspecialchars($_POST['url']);
			if($url){
				$data['url'] = $url;
			}
			$bank_no=htmlspecialchars($_POST['bank_no']);
			if($bank_no){
				$data['bank_no'] = $bank_no;
			}
			$bank_user=htmlspecialchars($_POST['bank_user']);
			if($bank_user){
				$data['bank_user'] = $bank_user;
			}
			$pay_class=htmlspecialchars($_POST['pay_class']);
			if($pay_class){
				$data['pay_class'] = $pay_class;
			}
			if($img){
				$data['img'] = $img;
			}
			$data['time'] = time();
			$data['type'] = 3;
			$data['name'] = $_POST['name'];
			$data['status'] = $_POST['status'];
			if(is_numeric($_POST['exchange_rate'])){
				$data['exchange_rate'] = $_POST['exchange_rate'];
			}else{
				_messageerror("汇率必须为数字");
			}

			$money['range'] = $_POST['m'];
            $money['ranges'] = $_POST['ms'];
            $money['isinput'] =$_POST['isinput'];
            $m = serialize($money);
			$data['pay_monerange'] = $m;
			$sql = Assemblydata($data,"kefu","in");
			$res=$this->db->Query($sql);
			if($res){
				_message("添加成功！",G_MODULE_PATH."/pay/usdt_list");
			}else{
				_message("添加失败！");
			}
		}
		include $this->tpl(ROUTE_M,'usdt_add');
	}

	public function set_usdt(){
		$id=htmlspecialchars($this->segment(4));
		if($id){
			$language_backend = $this->language_backend;
			$language = $this->db->GetOne("SELECT * FROM `@#_language_backend` where `lg_value` = '$language_backend'");
			$country = $language['country'];
			$info=$this->db->GetOne("SELECT * FROM `@#_kefu` WHERE id='$id'");
			$info['pay_monerange'] = @unserialize($info['pay_monerange']);
			$fw = $info['pay_monerange'];
			if(isset($_POST['dosubmit'])){
				if($_FILES){
				$path='photo';	
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
				$bank_name=htmlspecialchars($_POST['bank_name']);
				if($bank_name){
					$data['bank_name'] = $bank_name;
				}
				$url=htmlspecialchars($_POST['url']);
				if($url){
					$data['url'] = $url;
				}
				$bank_no=htmlspecialchars($_POST['bank_no']);
				if($bank_no){
					$data['bank_no'] = $bank_no;
				}
				$bank_user=htmlspecialchars($_POST['bank_user']);
				if($bank_user){
					$data['bank_user'] = $bank_user;
				}
				$pay_class=htmlspecialchars($_POST['pay_class']);
				if($pay_class){
					$data['pay_class'] = $pay_class;
				}
				if($img){
					$data['img'] = $img;
				}
				$data['name'] = $_POST['name'];
				$data['status'] = $_POST['status'];
				if(is_numeric($_POST['exchange_rate'])){
					$data['exchange_rate'] = $_POST['exchange_rate'];
				}else{
					_messageerror("汇率必须为数字");
				}
				$money['range'] = $_POST['m'];
				$money['ranges'] = $_POST['ms'];
				$money['isinput'] =$_POST['isinput'];
				$m = serialize($money);
				$data['pay_monerange'] = $m;
				$sql = Assemblydata($data,"kefu","up",[["id",$id]]);
				$res=$this->db->Query($sql);
				if($res){
					_message('修改成功！',G_MODULE_PATH."/pay/usdt_list");
				}else{
					_message('修改失败！');
				}
			}
		}else{
			_message('参数错误！');
		}
		include $this->tpl(ROUTE_M,'usdt_add');
	}

	public function del_usdt(){
		$id=htmlspecialchars($this->segment(4));
		if($id){
			$res=$this->db->Query("DELETE FROM `@#_kefu` WHERE id='$id'");
			if($res){
				_message('删除成功！');
			}else{
				_message('删除失败！');
			}
		}else{
			_message('参数错误！');
		}
	}

}
?>