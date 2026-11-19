<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('memberbase',null,'no');
System::load_app_fun('user','go');
System::load_app_fun('my','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class user extends memberbase {
	private $conf;
	public function __construct(){
		parent::__construct();
		$this->db = System::load_sys_class("model");
		$this->conf = System::load_app_config("connect",'','api');
	}
	
	public function cook_end(){
		_setcookie("uid","",time()-3600);
		_setcookie("ushell","",time()-3600);
		header("location: ".G_WEB_PATH); 
	}
	
	//返回登录页面
	public function login(){
		$webname=$this->_cfg['web_name'];
		$user = $this->userinfo;
		$banner=$this->db->GetOne("SELECT value FROM `@#_config` WHERE name='web_logo'");
		$banner=$banner['value'];
		if($user){
			header("Location:".WEB_PATH."/mobile/home");exit;
		}
		include templates("mobile/user","login");

	}

    //返回登录页面
    public function logintest(){
        $webname=$this->_cfg['web_name'];
        $user = $this->userinfo;
        $banner=$this->db->GetOne("SELECT value FROM `@#_config` WHERE name='web_logo'");
        $banner=$banner['value'];
        if($user){
            header("Location:".WEB_PATH."/mobile/home");exit;
        }
        include templates("mobile/user","logintest");

    }

    public function loginlog(){
	    $data = file_get_contents("php://input");
        file_put_contents('facebookLogin.txt','input:'.var_export($data,1)."\n",FILE_APPEND);
        $data = $_POST;
        file_put_contents('facebookLogin.txt','POST:'.var_export($data,1)."\n",FILE_APPEND);

        $data = $_REQUEST;
        file_put_contents('facebookLogin.txt','REQUEST:'.var_export($data,1)."\n",FILE_APPEND);

    }

  //中奖几率
	function r($data){
		$date =$this->db->GetList("SELECT Grade,probability FROM `@#_member_zp` order by `probability` desc");
	    $n = rand(1,100);
	    $temp = 0;
	    foreach ($date as $k => $v) {
	        $temp+=$v['probability'];
	        if($n<=$temp)
	        {
	            echo  $v['Grade'];
	            break;
	        }
	    }
	    return false;
	}

	//充值奖励页面
	public function rechargeAward(){
		$user = $this->userinfo;
		if(!$user){
			header("Location:".WEB_PATH."/mobile/user/login");exit; 
		}
		include templates("mobile/user","rechargeAward");
	}
	
	//返回注册页面
	public function register(){
		$webname=$this->_cfg['web_name'];
		$code=intval($this->segment(4));
		if(!empty($code)){ 
			//取出用户id
          $code = substr($code,0,strrpos($code,'&'));
			_setcookie("code",$code,60*60*24*7);
		}
		$banner=$this->db->GetOne("SELECT value FROM `@#_config` WHERE name='web_logo'");
		$banner=$banner['value'];
		include templates("/mobile/user","register");
	}

	//返回发送验证码页面
	public function mobilecode(){
	    $webname=$this->_cfg['web_name'];
	    $mobilename=$this->segment(4);

		include templates("/mobile/user","mobilecheck");
	}

	public function mobilecheck(){
	    $webname=$this->_cfg['web_name'];
		$title="验证手机";
		$time=3000;
		$name=$this->segment(4);
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");
		 //var_dump($member);exit;
		if(!$member)_message("参数不正确");
		if($member['mobilecode']==1){
			_message("该账号验证成功",WEB_PATH."/mobile/mobile");
		}
		if($member['mobilecode']==-1){
		    //是否可发送验证码
            System::load_app_fun('member','member');
            $is_send = get_user_black($name,2);
            if ($is_send){
                _message(您已被禁止发送短信);
                exit();
            }

            $checkcodes=rand(100000,999999).'|'.time();//验证码
            $this->db->Query("UPDATE `@#_member` SET mobilecode='$checkcodes' where `uid`='$member[uid]'");
            $sendok = _send_SMS($name,16,$checkcodes);
			if($sendok[0]!=1){
					_message($sendok[1]);
			}
			header("location:".WEB_PATH."/mobile/user/mobilecheck/".$member['mobile']);
			exit;
		}
		$enname=substr($name,0,3).'****'.substr($name,7,10);
		$time=120;
		include templates("mobile/user","mobilecheck");
	}

  
  		public  function zpzj()
		{

			$data=safe_replace($_POST['itemid']);
			$user = $this->userinfo;
			$uid=$user['uid'];

			if(!$user){
				echo 0;exit;
			}	
            $res='';
            $zparr=$this->db->GetOne("SELECT * FROM `@#_member_zp` WHERE `Grade`=$data ");
            $name=$zparr['name'];
            $time=time();
            $datas=$this->db->GetOne("SELECT * FROM `@#_zp_number` WHERE `uid`=$uid");
             if(!$datas || $datas['number'] == 0 || $datas['has_num'] >=10){
                echo 2;exit;
             }
            $type=$zparr['type'];
            $ren=$zparr['ren'];
            $zj=$this->db->Query("INSERT INTO `@#_zp_zj` (`uid`,`Grade`,`content`,`time`,`state`,`ren`) VALUE ('$uid','$data','$name','$time','$type','$ren')" );
 			
            $id=$this->db->insert_id();
            $res['id']=$id;
           	$res['type']=$type;
          	$res['money']=$zparr['money'];
          	$res['img']=$zparr['img'];
         	$res['content']=$zparr['name'];
         	 $res['Grade']=$zparr['Grade'];
            $zj_num=$this->db->Query("UPDATE  `@#_zp_number` SET  `number`=`number`-1,`has_num`=`has_num`+1 where `uid`=$uid");
            if($type =='0' ){
                $res['yaoqingnum']=$zparr['ren'];
            }elseif($type =='1'){
              	$score=$zparr['money'];
                $this->db->Query("UPDATE  `@#_member` SET  `score`=`score`+$score where `uid`=$uid");
            }elseif($type =='2'){
             	 $money=$zparr['money'];
                $this->db->Query("UPDATE  `@#_member` SET  `money`=`money`+$money where `uid`=$uid");
            }
            echo json_encode($res);

		}

	public function buydetail(){
	 $webname=$this->_cfg['web_name'];
	 $member=$this->userinfo;
	 $itemid=intval($this->segment(4));
	 $itemlist=$this->db->GetList("select a.id as aid,a.time as timego,sum(gonumber) as gonumber,a.username,a.ordercode_table,b.id,b.sid,b.title,b.qishu,b.thumb,b.zongrenshu,b.canyurenshu,b.shenyurenshu,b.q_end_time,b.q_uid from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$member[uid]' and b.id='$itemid' group by a.id order by a.time" );
	 if(empty($itemlist)){
		 $itemlist=$this->db->GetList("select a.id as aid,a.time as timego,sum(gonumber) as gonumber,a.username,a.ordercode_table,b.id,b.sid,b.title,b.qishu,b.thumb,b.zongrenshu,b.canyurenshu,b.shenyurenshu,b.q_end_time,b.q_uid from `@#_member_go_record` a left join `@#_shoplist_copy` b on a.shopid=b.id where a.uid='$member[uid]' and b.id='$itemid' group by a.id order by a.time" ); 
	 }
	 if(!empty($itemlist)){
		 if($itemlist[0]['q_end_time']!=''){
	   //商品已揭晓
			$itemlist[0]['codeState']='已揭晓...';
			$itemlist[0]['class']='z-ImgbgC02';
	    }elseif($itemlist[0]['shenyurenshu']==0){
		//商品购买次数已满
			$itemlist[0]['codeState']='已满员...';
			$itemlist[0]['class']='z-ImgbgC01';
		}else{
		//进行中
			$itemlist[0]['codeState']='进行中...';
			$itemlist[0]['class']='z-ImgbgC01';
		}
		$bl=($itemlist[0]['canyurenshu']/$itemlist[0]['zongrenshu'])*100;
		foreach ($itemlist as $k => $v) {
			$codestable=$v['ordercode_table'];
			$codes=$this->db->GetOne("select shop_codes from `@#_".$codestable."` where orderid='$v[aid]'");
			$itemlist[$k]['goucode']=$codes['shop_codes'];
			$count += $v['gonumber'];
		}
	}
	 include templates("/mobile/user","userbuydetail");
	}
	
	//密码修改
	public function password(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="密码修改";	
		include templates("mobile/user","password");
	}
	
	//原密码错误
	public function oldpassword(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		if($member['password']==md5($_POST['param'])){
			echo '{
					"info":"",
					"status":"y"
				}';
		}else{
			echo "原密码错误";
		}
	}
	
	//密码不能为空
	public function userpassword(){
			$mysql_model=System::load_sys_class('model');
			$member=$this->userinfo;
			$pwd1=isset($_POST['pwd1']) ? safe_replace($_POST['pwd1']) : "";
			$pwd2=isset($_POST['pwd2']) ? safe_replace($_POST['pwd2']) : "";
			$pwd3=isset($_POST['pwd3']) ? safe_replace($_POST['pwd3']) : "";
			if($pwd3==null or $pwd2==null or $pwd1==null){
					echo "密码不能为空;";
					exit;
			}
			if(strlen($_POST['pwd2'])<6 || strlen($_POST['pwd2'])>20){
				echo "密码不能小于6位或者大于20位";
				exit;
			}
			if($_POST['pwd3']!==$_POST['pwd2']){
				echo "二次密码不一致";
				exit;
			}		
			$pwd2=md5($pwd2);
			$pwd1=md5($pwd1);
			if($member['password']!=$pwd1){
				echo "原密码错误";
			}else{
				$mysql_model->Query("UPDATE `@#_member` SET password='".$pwd2."' where uid='".$member['uid']."'");
				echo 'ok';
			}
		}
	public function headimg(){
		$member=$this->userinfo;
		if(!empty($_FILES)){
			$size=System::load_sys_config('upload','upsize');
			$type=array('png','jpg','jpeg');
			$filetype=explode('.',$_FILES['Filedata']['name']);		
			$filetype=strtolower(array_pop($filetype));
			if(!in_array($filetype,$type)){_messagemobile("文件类型不正确");exit;	}
			if($_FILES['Filedata']['size'] > $size){_messagemobile("文件大小超过了允许上传大小");exit;	}
			$ip=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'];
			$toUrl = $ip.'/Img.php';	
			$data = array(
				'path' => 'HeadImg',
				'file'  => curl_file_create($_FILES['Filedata']['tmp_name'], $_FILES['Filedata']['type'], $_FILES['Filedata']['name']),
			);
			$ch = curl_init ();
		    curl_setopt ( $ch, CURLOPT_URL, $toUrl );
		    curl_setopt ( $ch, CURLOPT_POST, 1 );
		    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);
		    $return = curl_exec ( $ch );
		    curl_close ( $ch );
			$info=json_decode($return,1);
			if($info){
				$tname=$info['imgurl'];
				$this->db->Query("UPDATE `@#_member` SET img='$tname' where uid={$member['uid']}");
				_messagemobile("修改成功");				
			}else{
				_messagemobile("修改失败");	
			}

		}					
		include templates("mobile/user","headimg");
	}	
	public function headimg1(){
		$member=$this->userinfo;
		if(!empty($_FILES)){	
			System::load_sys_class('upload','sys','no');
			upload::upload_config('touimg',array('png','jpg','jpeg'),500000);
			upload::go_upload($_FILES['Filedata']);
			$files=safe_replace($_POST['typeCode']);
			if(!upload::$ok){
				echo upload::$error;
			}else{
				$img=upload::$filedir."/".upload::$filename;				
				$size=getimagesize(G_UPLOAD."/touimg/".$img);
				$max=300;$w=$size[0];$h=$size[1];				
				if($w>300 or $h>300){
					if($w>$h){
						$w2=$max;
						$h2 = intval($h*($max/$w));
						upload::thumbs($w2,$h2,true);					
					}else{
						$h2=$max;
						$w2 = intval($w*($max/$h));
						upload::thumbs($w2,$h2,true);
					}
				}
			$tname="touimg/".$img;
			$this->db->Query("UPDATE `@#_member` SET img='$tname' where uid={$member['uid']}");
			_messagemobile("修改成功");
			}					
		}
		include templates("mobile/user","headimg");
	}

	public function profilechange(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		if($_POST){			
			$username=_htmtocode(safe_replace(trim($_POST['username'])));
			$qianming=_htmtocode(safe_replace(trim($_POST['qianming'])));
			$reg_user_str = $this->db->GetOne("select value from `@#_caches` where `key` = 'member_name_key' limit 1");
			$reg_user_str = explode(",",$reg_user_str['value']);
			if(is_array($reg_user_str) && !empty($username)){
				foreach($reg_user_str as $rv){
					if($rv == $username){
						_message("此昵称禁止使用!");
					}
				}
			}			
			//积分、经验添加
			$isset_user=$this->db->GetOne("select `uid` from `@#_member_account` where (`content`='手机认证完善奖励' or `content`='完善昵称奖励') and `type`='1' and `uid`='$member[uid]' and (`pay`='经验' or `pay`='积分')");		
			$mysql_model->Query("UPDATE `@#_member` SET username='".$username."',qianming='".$qianming."' where uid='".$member['uid']."'");
			echo 1;
			die;
		}
	}

	public function profile(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$wxinfo = $this->db->GetOne("SELECT * FROM `@#_member_band` WHERE `b_uid` = '$uid' AND `b_type`='weixin' LIMIT 1");
		$qqinfo = $this->db->GetOne("SELECT * FROM `@#_member_band` WHERE `b_uid` = '$uid' AND `b_type`='qq' LIMIT 1");
		include templates("mobile/user","profile");
	}

	//手机验证界面
	public function mobile(){
		include templates("mobile/user","mobile");
	}
	
	public function mobiles2(){
		$mobile=$this->segment(4);
		include templates("mobile/user","mobiles2");
	}

	public function mobilesuccess(){
		$title="手机验证";
		$member=$this->userinfo;	
		if($_POST){
		$mobile=isset($_POST['mobile']) ? safe_replace($_POST['mobile']) : "";
		if(!_checkmobile($mobile) || $mobile==''){
			echo "手机号错误";die;	
		}
		$member2=$this->db->GetOne("select mobilecode,uid,mobile from `@#_member` where `mobile`='$mobile'");
			if($member2 && $member2['mobilecode'] == 1){
					echo "手机号已被注册或验证！";die;
			}					
			if($member['mobilecode']!=1){
			//验证码
			$ok = send_mobile_reg_code($mobile,$member['uid']);			
			if($ok[0]!=1){
				echo "发送失败,失败状态:".$ok[1];die;
				}else{
				_setcookie("mobilecheck",base64_encode($mobile));
			}					
			}
			$time=120;
			echo  123;die;
		}
	}
	
  	public function delete(){
			$user = $this->userinfo;
			$id=safe_replace($_GET['id']);
			$uid=safe_replace($user['uid']);
			 $query=$this->db->Query("UPDATE `@#_zp_zj` SET type=3 WHERE `id`=$id ");
			 if($query){
			 	echo 1;
			 }
			// print_r($query);exit;

}
	public function mobilecheckband(){	
		$member=$this->userinfo;
		if($_POST){		
			$mobile = isset($_POST['mobile']) ? safe_replace($_POST['mobile']) : '';
			$checkcodes=isset($_POST['code']) ? safe_replace($_POST['code']) : '';
		if(empty($mobile)){
			echo "验证出错，请重新绑定";die;
		}
		if(strlen($checkcodes)!=6){
			echo "验证码输入不正确";die;
		}
		$usercode=explode("|",$member['mobilecode']);
		if($checkcodes!=$usercode[0]){
			echo "验证码输入不正确";die;
		}
		$this->db->Query("UPDATE `@#_member` SET `mobilecode`='1',`mobile` = '$mobile' where `uid`='$member[uid]'");
		$this->db->Query("DELETE FROM `@#_member` WHERE `mobile` = '$mobile' AND `username`=''");
		_setcookie("uid",_encrypt($member['uid']));	
		_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])));				
		echo 123;die;
		}else{
		echo '绑定失败，请重新操作';die;
		}
	}

	//找回登录密码第一步
	public function step1(){
		$webname = $this->_cfg ['web_name'];
		$mobile=$this->segment(4);
		include templates("mobile/user","step1");
	}
	
	//找回密码第二步
	public function step2(){
		$webname = $this->_cfg ['web_name'];
		$mobile=$this->segment(4);
		include templates("mobile/user","step2");
	}
	
	//找回密码第三步
	public function step3(){
		$webname = $this->_cfg ['web_name'];
		$mobile=$this->segment(4);
		include templates("mobile/user","step3");
	}
	
	//验证第一步输入的找回密码是否正确
	public function step1chk(){	
		if($_POST){
		$mobile=isset($_POST['mobile']) ? safe_replace($_POST['mobile']) : "";
		if(!_checkmobile($mobile) || $mobile==''){
			echo "手机号错误";die;	
		}
		$member2=$this->db->GetOne("select mobilecode,uid,mobile from `@#_member` where `mobile`='$mobile'");
			if(!$member2){
					echo "手机号不存在或未验证成功";die;
			}					
			if($member2['mobilecode']=1){
			//验证码
			$ok = send_mobile_fid_code($mobile);			
			if($ok[0]!=1){
				echo "发送失败,失败状态:".$ok[1];die;
				}else{
				_setcookie("mobilecheck",base64_encode($mobile));
			}					
			}
			$time=120;
			echo  123;die;
		}
	}
	
	//验证完手机号码后验证验证码正确性
	public function step2chk(){
		if($_POST){		
			$mobile = isset($_POST['mobile']) ? safe_replace($_POST['mobile']) : '';
			$checkcodes=isset($_POST['code']) ? safe_replace($_POST['code']) : '';
		if(empty($mobile)){
			echo "验证出错，请重新绑定";die;
		}
		if(strlen($checkcodes)!=6){
			echo "验证码输入不正确";die;
		}
		$member2=$this->db->GetOne("SELECT mobilecode,uid,mobile from `@#_member` where `mobile`='$mobile'");
		$usercode=explode("|",$member2['mobilecode']);
		if($checkcodes!=$usercode[0]){
			echo "验证码输入不正确";die;
		}
		$this->db->Query("UPDATE `@#_member` SET `mobilecode`='1' WHERE `mobile`='$mobile'");				
		echo 123;die;
		}else{
		echo '操作失败，请重新操作';die;
		}
	}
  
  
  		//转盘
    	public function zp(){
		$user = $this->userinfo;
		$cookie=safe_replace($_COOKIE['uid']);
        include_once(dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/QRcode.php');
        $code=WEB_PATH."/mobile/user/register/"._getcookie('uid');
        $qrcode=dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/tmp/'._getcookie('uid').".png";
        $rs=QRcode::png($code,$qrcode,"M",3.2,2);
        $path_2 = G_WEB_PATH."/statics/plugin/qrcode/tmp/"._getcookie('uid').".png";
		if(!$user){
			header("Location:".WEB_PATH."/mobile/user/login");exit;
		}	
		$time=time();
		$uid=$user['uid'];
     	 $hnum=0;
      	$enum=10;
   		$st=strtotime(date('Y-m-d',time()));
		$et=strtotime( "+1 day",time());
      	$this->db->Query("UPDATE `@#_zp_zj` SET `type`=3 where time<'$st' and type='0' and state='0' ");//未领取自动放弃
      	$this->db->Query("UPDATE `@#_zp_number` SET `number`=0,`has_num`=0 where time<'$st' ");//更新今日次数
		$u_num=$this->db->GetOne("SELECT * FROM `@#_zp_number`WHERE `uid`=$uid and time>'$st' and time<'$et'");
 		
      	if($u_num['number']){$hnum=$u_num['number'];}
		if($u_num['has_num']){$enum=10-$u_num['has_num'];}
      
		$zjlist=$this->db->GetOne("select a.*,b.img from `@#_zp_zj` a left join `@#_member_zp` b on a.Grade=b.Grade where a.uid =$uid and a.state='0' and a.type='0' ");
  
      	
     	
		//中奖商品
		
		$zjsp=$this->db->GetList("SELECT * FROM `@#_zp_zj` order by time desc limit 0,30");
		foreach ($zjsp as $key => $value) {
			$zjsp[$key]['name']=get_user_name($zjsp[$key]['uid']);
		}
		$date =$this->db->GetList("SELECT * FROM `@#_member_zp` order by `Grade` ASC");
		foreach ($date as $key => $value) {

			$title.="'".$value['name']."',";
          	if($value['Grade']==1){$date[$key]['deg']='0deg';}
			if($value['Grade']==2){$date[$key]['deg']='45deg';}
			if($value['Grade']==3){$date[$key]['deg']='90deg';}
			if($value['Grade']==4){$date[$key]['deg']='135deg';}
			if($value['Grade']==5){$date[$key]['deg']='180deg';}
			if($value['Grade']==6){$date[$key]['deg']='-135deg';}
			if($value['Grade']==7){$date[$key]['deg']='-90deg';}
			if($value['Grade']==8){$date[$key]['deg']='-45deg';}
		}
		$title=rtrim($title,',');
		//echo $title;exit;
		//print_r($date);exit;
		include templates("mobile/user","zp");
	}
	
	//处理提交的新密码
	public function step3chk(){
		$pwd2=isset($_POST['pwd2']) ? safe_replace($_POST['pwd2']) : "";
		$pwd3=isset($_POST['pwd3']) ? safe_replace($_POST['pwd3']) : "";
		$mobile= base64_decode(_getcookie("mobilecheck"));
		if(!_checkmobile($mobile) || $mobile==''){
			echo "手机号码为空，操作失败";exit;
		}
		if($pwd3=='' or $pwd2==''){
				echo "密码不能为空;";exit;
		}
		if(strlen($_POST['pwd2'])<6 || strlen($_POST['pwd2'])>20){
			echo "密码不能小于6位或者大于20位";exit;
		}
		if($_POST['pwd3'] != $_POST['pwd2']){
			echo "二次密码不一致";exit;
		}	
		$this->db->Query("UPDATE `@#_member` SET password='".md5($pwd2)."' WHERE `mobile`='".$mobile."'");
		echo 123;die;
	}
	
	public function getchatmsg(){
	    $uid =safe_replace($_POST['uid']);

	    if (empty($uid)){
            $this->return_json(0,'请输入用户ID');
        }
	    $sql = "SELECT * FROM `@#_system_message` where `to_id` = '$uid' and `del` =1 ORDER BY `send_time` DESC";
	    $data =$this->db->GetList($sql);
        if ($data){
            $arr =[];
            foreach ($data as $v){
                $d =[
                    'id'=>$v['id'],
                    'content'=>$v['content'],
                    'send_time'=>date('Y-m-d H:i:s',$v['send_time']),
                    'status'=>$v['status'],
                    'titles'=>$v['titles'],
                ];
                $arr[]=$d;
            }
            $this->return_json(200,'有新消息',$arr);
        }
        $this->return_json(1,'没有消息');
    }
    public function sysmsgcout(){
        $uid =safe_replace($_POST['uid']);
        if (empty($uid)){
            $this->return_json(0,'请输入用户ID');
        }
        $sql = "SELECT COUNT(*) as num FROM `@#_system_message` where `to_id` = '$uid' and `del` =1 and `status` = 0";
        $data =$this->db->GetOne($sql);
        if ($data){
            $this->return_json(200,'',$data);
        }
        $this->return_json(1,'没有消息');
    }
    public function getsmstitle(){
        $uid =safe_replace($_POST['id']);
        if (empty($uid)){
            $this->return_json(0,'请输入标题ID');
        }
        $sql = "SELECT  *  FROM `@#_system_message` where `id`=$uid";
        $data =$this->db->GetOne($sql);
        if ($data){
            $time=time();
            $upsql ="UPDATE `@#_system_message` SET `view_time` = '$time',`status` =1 WHERE `id` = $uid";
            $this->db->Query($upsql);
            $this->return_json(200,'有新消息',$data);
        }
        $this->return_json(1,'没有消息');
    }

    public function return_json($code='',$msg='',$data=''){
	    $res =['code'=>$code,'msg'=>$msg,'data'=>$data];
	    echo json_encode($res);
	    exit();
    }
	
	
	
  
}
?>