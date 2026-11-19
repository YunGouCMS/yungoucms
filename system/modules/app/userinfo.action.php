<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');

class userinfo extends base {   

    public function __construct() {
        parent::__construct();
    }

    /*修改昵称*/
    public function editusername()
    {
        $sucmsg = $this->getLgMsg("user_headimg_updatesuc",'controller_user');
        $errmsg = $this->getLgMsg("ajax_findPass_upfail",'controller_ajax');
        $errmsg1 = $this->getLgMsg("user_profilechange_nick",'controller_user');
        $errmsg2 = $this->getLgMsg("Nickname already in use",'controller_user');
        if($_POST){
            $username = str_replace(" ", "", $_POST['username']);
            $username=_htmtocode(trim($username));
            $reg_user_str = $this->db->GetOne("select value from `@#_caches` where `key` = 'member_name_key' limit 1");
            $reg_user_str = explode(",",$reg_user_str['value']);
            if(is_array($reg_user_str) && !empty($username)){
                foreach($reg_user_str as $rv){
                    if($rv == $username){
                        $this->jsonData->error('',$errmsg1?:'此昵称禁止使用');
                    }
                }
            }
            //是否重复
            $un = $this->db->GetOne("select * from `@#_member` where username='$username'");
            if($un){
                $this->jsonData->error('',$errmsg2!="error"?$errmsg2:'此昵称已被使用');
            }
            //积分、经验添加
            $userinfo = $this->userInfo();
            $uid = $userinfo['uid'];
            // $isset_user=$this->db->GetOne("select `uid` from `@#_member_account` where (`content`='手机认证完善奖励' or `content`='完善昵称奖励') and `type`='1' and `uid`='$uid' and (`pay`='经验' or `pay`='积分')");
            $res =  $this->db->Query("UPDATE `@#_member` SET username='$username' where uid ='$uid'");
            if($res){
                $this->jsonData->success('',$sucmsg?:'修改成功','');
            }
            $this->jsonData->error('',$errmsg?:'修改失败');
        }
    }

    public function headimgSet(){
        $member=$this->userInfo();
        $img = $_POST['img'];
        $this->db->Query("UPDATE `@#_member` SET img='$img' where uid={$member['uid']}");
        $this->jsonData->success('','修改成功',$this->userInfo());
    }

    /*修改头像*/
    public function headimg(){
        $member=$this->userInfo();
        if(!empty($_FILES)){
            System::load_sys_class('upload','sys','no');
            upload::upload_config('touimg',array('png','jpg','jpeg'),500000);
            upload::go_upload($_FILES['Filedata']);
            $files=$_POST['typeCode'];
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
               $this->jsonData->success('','修改成功',$this->userInfo());
            }
        }
    }

    /*修改密码*/
    public  function updatepassword(){
        $pwd1=isset($_POST['lodpwd']) ? $_POST['lodpwd'] : "";
        $pwd2=isset($_POST['newpwd1']) ? $_POST['newpwd1'] : "";
        $pwd3=isset($_POST['newpwd2']) ? $_POST['newpwd2'] : "";
        if($pwd3==null or $pwd2==null or $pwd1==null){
           $this->jsonData->error('','密码不能为空！');
        }
        if(strlen($pwd2)<6 || strlen($pwd2)>20){
            $this->jsonData->error('','密码不能小于6位或不能大于20位！');
        }
        if($_POST['pwd3']!==$_POST['pwd2']){
            $this->jsonData->error('','两次密码不一致');
        }
        $pwd2=md5($pwd2);
        $pwd1=md5($pwd1);
        $member=$this->userInfo(2);
        if($member['password']!=$pwd1){
            $this->jsonData->error('','旧密码错误');
        }else{
            $this->db->Query("UPDATE `@#_member` SET password='".$pwd2."' where uid='".$member['uid']."'");
            $this->jsonData->success('','修改成功');
        }
    }

    /*找回密码发送验证码*/
    public function backpasswordsend(){
        $phone = safe_replace($_POST['phone']);
        // || !preg_match("/^1[3456789]\d{9}$/", $phone)
        if(empty($phone)){
            $this->jsonData->error('','请输入正确的手机号码');
        }
        $uinfo  = $this->db->GetOne("SELECT uid,mobile FROM `@#_member` WHERE mobile = '$phone'");
        if(!$uinfo){
            $this->jsonData->error('','您的手机号还未注册！');
        }
        $sendmobile = System::load_sys_config('mobile');
        $key=$sendmobile['cfg_mobile_on'];
        $fun = 'send_login_code_'.$key;
        $code=mt_rand(100000,999999);
        $ok =$fun($phone,$code);
        $type = 'backpwd';
        if($ok['0']== '1'){
            $time=time();
            $this->db->Query("INSERT INTO `@#_mobile_verify`(`mobile`,`verify`,`time`,`type`)VALUES('$phone','$code','$time','$type')");
            $this->jsonData->success('','验证码发送成功');
        }else{
            $this->jsonData->error('','验证码发送失败');
        }
    }

    /*找回密码*/
    public function backpassword(){
        $phone = safe_replace($_POST['phone']);
        $code = safe_replace($_POST['code']);
        $newpassword = safe_replace($_POST['newpassword']);
        // || !preg_match("/^1[3456789]\d{9}$/", $phone)
        if(empty($phone)){
            $this->jsonData->error('','请输入正确的手机号码');
        }
        if(empty($code)){
            $this->jsonData->error('','验证码不能为空');
        }
        if(empty($newpassword) || strlen($newpassword)< 6 || strlen($newpassword) >20){
            $this->jsonData->error('','请输入正确的密码');
        }
        $codesql = "SELECT * FROM `@#_mobile_verify` WHERE `mobile` = '$phone' and `verify` = '$code' and `type` = 'backpwd'";
        $iscode = $this->db->GetOne($codesql);
        if (!$iscode){

            $this->jsonData->error('','验证码不正确');
        }
        $pwd = md5($newpassword);
        $setsql = "UPDATE `@#_member` SET `password` = '$pwd' WHERE `mobile` = '$phone'";
        $res = $this->db->query($setsql);
        if ($res){
            $sendid = $iscode['id'];
            $this->db->query("DELETE FROM  `@#_mobile_verify` WHERE `id` = '$sendid'");
            $this->jsonData->success('','修改成功');
        }
        $this->jsonData->error('','修改失败');
    }
    //修改手机号
    public function upphone(){
        $pas = safe_replace($_POST['password']);//密码
        $phone = safe_replace($_POST['phone']);//手机号
        $userinfo = $this->userInfo(2);
        // || !preg_match("/^1[3456789]\d{9}$/", $phone)
        if(empty($phone)){
            $this->jsonData->error('','请输入正确的手机号码');
        }
        // if(empty($pas) || $pas !=$userinfo['password']){
        //     $this->jsonData->error('','请输入正确的密码');
        // }
        $sql = $this->db->Query("UPDATE `@#_member` SET `mobile` = '$phone' WHERE `uid` = $userinfo[uid]");
        if($sql){
            $this->jsonData->success('','成功');
        }else{
            $this->jsonData->error('','失败');
        }
    }

    // 获取站内信信息
    public function getsmsList(){
        $p=htmlspecialchars($_POST['p']) ? $_POST['p'] : 1;
        $status=htmlspecialchars($_POST['status']);
        $pagesize = 50;
        $start = ($p - 1) * $pagesize;

	    $uid = $this->uId;

	    $sql = "SELECT * FROM `@#_system_message` where `to_id` = '$uid' and `del` =1 ORDER BY `send_time` DESC LIMIT $start,$pagesize";
	    $data =$this->db->GetList($sql);
        $count = $this->db->GetNum("SELECT * FROM `@#_system_message` where `to_id` = '$uid' and `del` =1");
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
            $this->jsonData->success('','',["list"=>$arr,"count"=>$count]);
        }
        $this->jsonData->success('','',[]);
    }

    // 统计用户未读取信息
    public function getsmsCount(){
        $uid = $this->uId;

        $sql = "SELECT COUNT(*) as num FROM `@#_system_message` where `to_id` = '$uid' and `del` =1 and `status` = 0";
        $data =$this->db->GetOne($sql);
        if ($data){
            $this->jsonData->success('','',$data);
        }
        $this->jsonData->success('','', 0);
    }
	
    // 获取站内信详情
    public function getsmsDetail(){
        $message_id =safe_replace($_POST['id']);

        if (empty($message_id)){
            $this->jsonData->error('',$this->lgs['parameter_error']?:'');
        }

        $sql = "SELECT  *  FROM `@#_system_message` where `id` = $message_id";
        $data =$this->db->GetOne($sql);
        if ($data){
            $time=time();
            $upsql ="UPDATE `@#_system_message` SET `view_time` = '$time',`status` =1 WHERE `id` = $message_id";
            $this->db->Query($upsql);
            $this->jsonData->success('','',$data);
        }
        $this->jsonData->error('','');
    }

    /**
     * 删除站内信
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function delSms()
    {
        $message_id =safe_replace($_POST['id']);

        if (empty($message_id)){
            $this->jsonData->error('',$this->lgs['parameter_error']?:'');
        }

        $sql = "SELECT  *  FROM `@#_system_message` where `id` = $message_id";
        $data =$this->db->GetOne($sql);
        if ($data){
            $upsql ="UPDATE `@#_system_message` SET `del` = 2 WHERE `id` = $message_id";
            $this->db->Query($upsql);
            $this->jsonData->success('','');
        }
        $this->jsonData->error('','');
    }

    /**
     * 
     * 获取预设头像
     * @return void
     */
    public function getPresetsImg(){
        $is_photo = $this->getConfig("is_photo");
        if($is_photo==2){
            $list = $this->db->GetList("select * from `@#_member_presets_picture`");
            foreach($list?:[] as &$vl){
                $vl['img'] = G_UPLOAD_PATH."/".$vl['path'];
            }
        }else{
            $list = [];
        }
        $this->jsonData->success('','',$list);
    }

    /**
     * 获取头像状态1默认，2预设
     * @return 
     */
    public function avatar_status(){
        $is_photo = $this->getConfig("is_photo")?:1;
        $this->jsonData->success('','',["status"=>$is_photo]);
    }

    /**
     * 获取是否首次充值
     */
    public function is_first_recharge(){
        $count = $this->db->GetCount("SELECT count(*) FROM `@#_member_addmoney_record` WHERE `uid` = $this->uId and status = '已付款' and money > 0 ");
        if($count>1){
            $is_first_recharge = 0;
        }else{
            $sql = "SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = $this->uId and status = '已付款' and money > 0 limit 1";
            $res = $this->db->GetOne($sql);
            if($res){
                $data = mb_unserialize($res['data']);
                if(isset($data['is_first_recharge']) && $data['is_first_recharge'] == 1){
                    $is_first_recharge = 0;
                }else{
                    $is_first_recharge = 1;
                    $data['is_first_recharge'] = 1;
                    $data = serialize($data);
                    $sql = "UPDATE `@#_member_addmoney_record` SET `data` = '$data' WHERE id = ".$res['id'];
                    $this->db->Query($sql);
                }
            }else{
                $is_first_recharge = 0;
            }
        }
        $this->jsonData->success('',"",["is_first_recharge"=>$is_first_recharge]);
    }

    public function searchUser(){
        $search = $_POST['search'];
        if(empty($search)){
            $this->jsonData->error("","不能为空");
        }
        $sql = "select `username`,`go_member`.`uid`,`email`,`mobile`,`img`,`money`,`time`,`headimg`,`auto_user` from `@#_member` where uid = $search or mobile = '$search'";
        $res = $this->db->GetOne($sql);
        if(!$res){
            $this->jsonData->error("","没有找到");
        }
        $res['mobile'] = substr($res['mobile'],0,3).'****'.substr($res['mobile'],7,4);
            $res['username'] = !empty($res['username']) ? $res['username'] : $res['mobile'];
           $res['img'] = G_UPLOAD_PATH.'/'. $res['img'];
            if($res['auto_user']){
                $res['ip_region'] = "广东省广州市";
            }
        $this->jsonData->success('200','ok',$res);
    }
    
    public function socreOrder(){
        $FIdx=safe_replace($_POST['page'])?:0;
        $EIdx=20;
        if($FIdx){
            $FIdx *= $EIdx;
        }
        $sql = "select `username`,`go_member`.`uid`,`email`,`mobile`,`img`,`score`,`time`,`headimg`,`auto_user` from `@#_member` where auto_user = 0 and level = 1 order by score desc limit $FIdx,$EIdx";
        $list = $this->db->GetList($sql);
        foreach($list as $k=> $vl){
            $list[$k]['mobile'] = substr($list[$k]['mobile'],0,3).'****'.substr($list[$k]['mobile'],7,4);
            $list[$k]['username'] = !empty($list[$k]['username']) ? $list[$k]['username'] : $list[$k]['mobile'];
            $list[$k]['img'] = G_UPLOAD_PATH.'/'. $list[$k]['img'];
            if($list[$k]['auto_user']){
                $list[$k]['ip_region'] = "广东省广州市";
            }
        }
        $this->jsonData->success('200','ok',$list);
    }
}