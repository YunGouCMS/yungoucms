<?php 

/*
*	取得用户的收货地址
*	@uid  用户ID
*	@key  返回类型, bool 真假值,array 返回地址数组
*/
function member_get_dizhi($uid='',$key='bool'){
	$uid = abs(intval($uid));
	if(!$uid)return false;
	$db = System::load_sys_class("model");
	$info = $db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' and `default` = 'Y'");
	if($info){
		return $info;
	}else{
		return false;
	}
}

/**
 *  加入黑名单
 * @param $uid 用户ID
 * @param $type 类型
 * @param string $remarks 备注
 * @return bool
 */
function member_add_black($uid,$type,$remarks =''){
     if(!$uid)return false;
     if(!$type)return false;
     $db = System::load_sys_class("model");
     $info = $db->GetOne("SELECT * FROM `@#_member_blacklist` WHERE `uid` = '$uid'");
     $user = $db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
     $mobile = $user['mobile'];
     $time = time();
     if ($info){
         $id = $info['id'];
         $sql ="UPDATE `go_member_blacklist` SET `type` = '$type',`update` =$time, `remarks` = '$remarks' WHERE `id` = $id";
         $res = $db->Query($sql);
         if($res){
             return  $info;
         }
         return  false;
     }
     $sql = "INSERT INTO `@#_member_blacklist` (`uid`,`mobile`, `addtime`, `type`, `remarks`) VALUES ('$uid','$mobile', $time, '$type', '$remarks')";
     $res = $db->Query($sql);
     if($res){
         return  true;
     }
    return  false;
}

/**
 * 判断用户是否被拉入黑名单
 * @param $mobile
 * @param null $type
 * @return bool
 */
function get_user_black($mobile,$type=null){
     if(!$mobile)return false;
     if(!$type)return false;
     $db = System::load_sys_class("model");
     //select * from go_member_blacklist where (`type`& 4 ) > 0 and uid = 177
     $sql = "SELECT * FROM `@#_member_blacklist` WHERE (`type`& $type ) > 0 AND  `mobile`='$mobile'";

     $info = $db->GetOne($sql);
     if(!$info){
         return false;
     }
     return true;
     if (empty($type)){
         return $info;
     }
     $bal = System::load_sys_config('userblacktype');
     if(array_search($type,array_keys($bal))){
         $auth = $info['type']&$type ? true : false;
         return $auth;
     }
     return $info;
    
//    return false;
}