<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
class reward extends base
{

    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();
    }
    
    public function is_sign_in(){
        $uid = $this->uId;
        $date = date("Y-m-d");
        $res = $this->db->GetOne("select * from `@#_member_sign_in` where `date` like '%$date%' and uid = $uid");
        if($res){
            $data['is_sign'] = true;
        }else{
            $data['is_sign'] = false;
        }
        $this->jsonData->success('','ok',$data);
        
    }
    
    /**
     *签到领积分 
     **/
    public function sign_in(){
        $uid = $this->uId;
        $date = date("Y-m-d");
        $res = $this->db->GetOne("select * from `@#_member_sign_in` where `date` like '%$date%' and uid = $uid");
        if($res){
                $this->jsonData->error('','今日已签到');
        }
        $user = $this->db->GetOne("select * from `@#_member` where uid = $uid");
        if(!$user){
            $this->jsonData->error('','');
        }
        $user_fufen = System::load_app_config("user_fufen", '', 'member');
        if(!isset($user_fufen['f_sign_in'])){
                $this->jsonData->error('','未配置');
        }
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $yres = $this->db->GetOne("select * from `@#_member_sign_in` where `date` like '%$yesterday%' and uid = $uid");
        if($yres){
            // if($yres['day_num']>=7){
            //     $this->jsonData->error('','签到次数已达上限');
            // }
            $day_num = $yres['day_num'] + 1;
        }else{
            $day_num = 1;
        }
        $score = intval($user_fufen['f_sign_in']) + intval($user['score']);
        $time = date("H:i:s");
        $data = [
                "uid"=>$uid,
                "date"=>$date,
                "time"=>$time,
                "score"=>$user_fufen['f_sign_in'],
                "start"=>0,
                "day_num"=>$day_num
            ];
        $sql = Assemblydata($data,"member_sign_in");
        $r =  $this->db->Query($sql);
        if($r && $user_fufen['f_sign_in']>0){
            $this->db->Query("update `@#_member` set score = $score where uid = $uid");
            $mygoscore = $user_fufen['f_sign_in'];
            $mygoscore_text =  "签到";
            $time = time();
            $query_add_fufen_2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
        }
        $user = $this->db->GetOne("select * from `@#_member` where uid = $uid");
        $data = [
            "user_score"=>$user['score'],
            "add_score"=>$user_fufen['f_sign_in']
        ];
        $this->jsonData->success('','ok',$data);
    }   
    
    public function sign_in_num(){
        $uid = $this->uId;
        $date = date("Y-m-d");
        $res = $this->db->GetOne("select day_num from `@#_member_sign_in` where `date` like '%$date%' and uid = $uid");
        if(!$res){
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $res = $this->db->GetOne("select day_num from `@#_member_sign_in` where `date` like '%$yesterday%' and uid = $uid");
        }
        if(!$res){
            $res['day_num'] = 0;
        }else{
            if($res['day_num'] && $res['day_num']<=7){
              $days = '';
              for($i=0;$i<$res['day_num'];$i++){
                if($days){$days .= ",";}
                $days .= "'".date('Y-m-d', strtotime("-$i day"))."'";
              }  
            }
            $sql = "select id,CONCAT(date,' ',time) as time,score from `@#_member_sign_in` where uid = $uid and `date` in ($days)";
            $list = $this->db->GetList($sql);
            $res['list'] = $list?:[];
            $res['score_list'] = array_column($list?:[],"score");
        }

        $this->jsonData->success('','ok',$res);
    }

    public function getInterval(){
        $time = rand(30,60);
        // $time = 10000;
        $type = $_POST['type']?:1;
        System::load_sys_fun('redisData');
        $redis = new redisData;
        $interval = $redis->redisGet($type."interval".$this->uId);
        if($interval['data']){
            $time = $redis->redisGet($type."interval_time".$this->uId);
            $num = $time["data"]-(time()-$interval['data']);
        }else{
            // $redis->redisSetex($type."interval".$this->uId,$time,time());
            // $redis->redisSetex($type."interval_time".$this->uId,$time,$time);
            $num = 0;
        }
        if($num){
            $this->jsonData->error("","倒计时未结束",['time'=>$num]);
        }
        $this->jsonData->success("","ok",['time'=>0]);
    }
    
    /**
    *看视频领积分
    **/
    public function ad($type = 1){
        $type_text = ["1"=>"看广告","2"=>"玩游戏"];
        $type_f = ['1'=>"ad",'2'=>"game"];
        
        $uid = $this->uId;
        $date = date("Y-m-d");
        $user_fufen = System::load_app_config("user_fufen", '', 'member');
        if(!isset($user_fufen['f_'.$type_f[$type]]) || !isset($user_fufen['f_'.$type_f[$type].'_num'])){
                $this->jsonData->error('','未配置');
        }
        $add = $user_fufen['f_'.$type_f[$type]];
        $num = $user_fufen['f_'.$type_f[$type].'_num'];
        $res = $this->db->GetOne("select count(*) as count from `@#_member_ad` where `date` = '$date' and uid = $uid and type = $type");

        if($res['count']>=$num){
                $this->jsonData->error('','今日奖励已达上限');
        }
        //重置倒计时
        $interval = rand(30,60);
        // $interval = 10000;
        System::load_sys_fun('redisData');
        $redis = new redisData;
        $redis->redisSetex($type."interval".$this->uId,$interval,time());
        $redis->redisSetex($type."interval_time".$this->uId,$interval,$interval);
        //重置倒计时end

        $user = $this->db->GetOne("select * from `@#_member` where uid = $uid");
        $score = intval($add) + intval($user['score']);
        $this->db->Autocommit_start();
        $time = date("Y-m-d H:i:s");
        $data = [
                "uid"=>$uid,
                "date"=>$date,
                "time"=>$time,
                "score"=>$add,
                "type"=>$type,
            ];
        $sql = Assemblydata($data,"member_ad");
        $r2 = $this->db->Query($sql);
        $mygoscore = $add;
        $mygoscore_text =  $type_text[$type];
        $time = time();
        $r1 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
        if($r1 && $r2){
            $this->db->Query("update `@#_member` set score = $score where uid = $uid");        
            $this->db->Autocommit_commit();
            $user = $this->db->GetOne("select * from `@#_member` where uid = $uid");
            $data = [
                "user_score"=>$user['score'],
                "add_score"=>$add,
                "time"=>$interval
            ];
            // $this->jsonData->success('','ok',$user['score']);
            $this->jsonData->success('','ok',$data);
        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('','请重试');
        }
         
    }
    
    
    public function ad_num($type = 1){
        $uid = $this->uId;
        $date = date("Y-m-d");
        $res = $this->db->GetOne("select count(*) as num from `@#_member_ad` where `type` = $type and `date` = '$date' and uid = $uid");
        if(!$res){
            $res['num'] = 0;
        }
        $type_text = ["1"=>"看广告","2"=>"玩游戏"];
        $type_f = ['1'=>"ad",'2'=>"game"];
        $user_fufen = System::load_app_config("user_fufen", '', 'member');
        if(!isset($user_fufen['f_'.$type_f[$type]]) || !isset($user_fufen['f_'.$type_f[$type].'_num'])){
                $this->jsonData->error('','未配置');
        }
        $res["total"] = $user_fufen['f_'.$type_f[$type].'_num'];
        $this->jsonData->success('','ok',$res);
    }

    

    public function game(){
        $this->ad(2);
    }

    public function game_num(){
        $this->ad_num(2);
    }

    public function transfer(){
        
        $uid = safe_replace($_POST['uid']);
        $phone = safe_replace($_POST['phone']);
        $code = safe_replace($_POST['code']);
        $score = safe_replace($_POST['score']);
        if($uid == $this->uId){
            $this->jsonData->error('','转赠人不能是自己');
        }
        $codesql = "SELECT * FROM `@#_mobile_verify` WHERE `mobile` = '$phone' and `verify` = '$code'";
        $iscode = $this->db->GetOne($codesql);
        if (!$iscode){
            $this->jsonData->error('','验证码不正确');
        }
        $jsmember = $this->db->GetOne("select * from `@#_member` where uid = $uid or mobile = $uid");
        if(!$jsmember){
            $this->jsonData->error("","没有找到");
        }
        $member = $this->db->GetOne("select * from `@#_member` where uid = ".$this->uId);
        if ($score>$member['score']){
            $this->jsonData->error('','积分不足');
        }
        $jscore = $jsmember['score'] + $score;
        $myscore = $member['score'] - $score;
        $this->db->Query("update `@#_member` set score = $jscore where uid = ".$jsmember['uid']);
        $this->db->Query("update `@#_member` set score = $myscore where uid = ".$this->uId);
        $mygoscore_text = "转赠积分";
        $time = time();
        $r1 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`,`message`) 
                                                        VALUES ('$uid', '1', '积分', '$mygoscore_text', '$score', '$time','{$this->uId}')");
        $r2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`,`message`) 
                                                        VALUES ('{$this->uId}', '-1', '积分', '$mygoscore_text', '$score', '$time','$uid')");
        $sendid = $iscode['id'];
        $this->db->query("DELETE FROM  `@#_mobile_verify` WHERE `id` = '$sendid'");
        $this->jsonData->success(200,'ok');
    }

    /**
     * 转赠中奖商品
     *
     * @return void
     */
    public function transfer_goods(){
        $uid = safe_replace($_POST['uid']);
        $phone = safe_replace($_POST['phone']);
        $code = safe_replace($_POST['code']);
        $order_id = safe_replace($_POST['order_id']);
        $mode = safe_replace($_POST['mode']);
        if($uid == $this->uId){
            $this->jsonData->error('','转赠人不能是自己');
        }
        $info = $this->db->GetOne("SELECT `uid`,`level` FROM `@#_member` WHERE `uid` = '$this->uId'");
        if($info['level'] != 1){
            // $this->jsonData->error('','只有回收商才能转赠');
        }
        $codesql = "SELECT * FROM `@#_mobile_verify` WHERE `mobile` = '$phone' and `verify` = '$code'";
        $iscode = $this->db->GetOne($codesql);
        if (!$iscode){
            // $this->jsonData->error('','验证码不正确');
        }
        $jsmember = $this->db->GetOne("select * from `@#_member` where uid = $uid or mobile = $uid");
        if(!$jsmember){
            $this->jsonData->error("","接受人没有找到");
        }
        $table = ["1"=>"member_go_record","2"=>"orders","3"=>"orders_group"];
        $table = $table[$mode]??'';
        if(!$table){
            $this->jsonData->error("","订单参数错误");
        }
        $sql = "select * from `@#_$table` where uid = $this->uId and id = $order_id";
        $order = $this->db->GetOne($sql);
        if(!$order){
            $this->jsonData->error("","订单没有找到");
        }
        $transfer = $this->db->GetOne("select * from `@#_member_transfer` where uid = $this->uId and order_id = $order_id and mode = $mode");
        if($transfer){
            $this->jsonData->error("","不能重复转赠");
        }
        $data = [
            'uid'=>$this->uId,
            'to_uid'=>$uid,
            'order_id'=>$order_id,
            'code'=>$order['code'],
            'create_time'=>time(),
            'mode'=>$mode,
        ];
        $sql = Assemblydata($data,"member_transfer");
        $this->db->Autocommit_start();
        $id = $this->db->Query($sql);
        if(!$id){
            $this->db->Autocommit_rollback();   
            $this->jsonData->error("","转赠失败");
        }else{

            $this->db->query("DELETE FROM  `@#_mobile_verify` WHERE `id` = ".$iscode['id']);
            $this->db->Autocommit_commit();
            $this->jsonData->success(200,'ok');
        }
    }
}
