<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('user');
class activity extends base {

    public function __construct() {
        parent::__construct();
    }

    // 转盘配置
    public function turntable(){
        $uid = $this->uId?:'';
 
        $hnum = 0;
        $enum = 10;
        $code_path = '';
        $zjlist = [];
        if($uid) { // 用户登陆时 查询用户相关转盘信息（抽奖记录 抽奖次数）
            include_once(dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/QRcode.php');
            
            $qrcode = dirname(dirname(dirname(dirname(__FILE__)))).'/statics/plugin/qrcode/tmp/'.$uid.".png"; // 系统保存图片地址
            if(file_exists($code_path)) {
                $code = WEB_PATH."/mobile/user/register/?code=".$uid; // 分享抽奖页面
                $rs = QRcode::png($code,$qrcode,"M",3.2,2);
            }
            $code_path = G_WEB_PATH."/statics/plugin/qrcode/tmp/".$uid.".png";
            
            // 用户中奖列表
            $zjlist = $this->db->GetOne("select a.*,b.img from `@#_zp_zj` a left join `@#_member_zp` b on a.Grade=b.Grade where a.uid = $uid and a.state='0' and a.type='0' ");
            
            $time=time();
            $st=strtotime(date('Y-m-d', $time));
            $et=strtotime( "+1 day", $time);
            $this->db->Query("UPDATE `@#_zp_zj` SET `type` = 3 where time < '$st' and type = '0' and state = '0' ");// 未领取自动放弃
            $this->db->Query("UPDATE `@#_zp_number` SET `number`= 0,`has_num`= 0 where time <'$st' ");// 更新今日次数
            $u_num=$this->db->GetOne("SELECT * FROM `@#_zp_number`WHERE `uid`=$uid and time >'$st' and time < '$et'");
            
            if($u_num['number']){ 
                $hnum = $u_num['number'];
            }

            if($u_num['has_num']){
                $enum= 10-$u_num['has_num'];
            }
        }

        //中奖商品
        $zjsp = $this->db->GetList("SELECT * FROM `@#_zp_zj` order by time desc limit 0,30");
        if($zjsp) {
            foreach ($zjsp as $key => $value) {
                $zjsp[$key]['name']=get_user_name($zjsp[$key]['uid']);
            }
        } else {
            $zjsp = [];
        }

        // 奖品列表
        $date =$this->db->GetList("SELECT id,name,img,money,Grade,type FROM `@#_member_zp` order by `Grade` ASC");
        if($date) {
            $goods = [];
            foreach ($date as $key => $value) {
                $title.="'".$value['name']."',";
                $prizes['prizeId'] = $value['id']; // 奖品id
                $prizes['prizeName'] = $value['name']; // 奖品名称
                // $prizes['prizeStock'] = $value['id']; // 库存
                $prizes['prizeWeight'] = $value['Grade']; // 权重 转盘宽度
                $prizes['prizeImage'] = G_UPLOAD_PATH .'/'.$value['img']; // 奖品图片
                $goods[] = $prizes;
            }
            $title = rtrim($title,',');
        } else {
            $goods = [];
            $title = '';
        }

        $data['hnum'] = $hnum;
        $data['enum'] = $enum;
        $data['code_path'] = $code_path;
        $data['zjlist'] = $zjlist;
        $data['zjsp'] = $zjsp;
        $data['goods'] = $goods;
        $data['title'] = $title;

        $this->jsonData->success('','',$data);
    }

    // 清空中奖
    public function turntableDelete() {
        $uid = $this->uId;
        if(!$uid){
            $this->jsonData->error(201,'用户错误');
        }
        $id = safe_replace($_GET['id']);

        $query=$this->db->Query("UPDATE `@#_zp_zj` SET type=3 WHERE `id`= $id AND `uid` = $uid");
        if($query){
            $this->jsonData->success(200,'删除成功');
        }
        $this->jsonData->error(201,'删除失败');
    }

    // 转盘中奖
    public function turntablePlay() {
        $uid = $this->uId;
        if(!$uid){
            $this->jsonData->error(201,'用户错误');
        }
        // 用户抽奖次数判断
        $datas=$this->db->GetOne("SELECT * FROM `@#_zp_number` WHERE `uid`= $uid");
        if(!$datas || $datas['number'] == 0 || $datas['has_num'] >= 10){
            $this->jsonData->error(201,'没有次数');
        }
        
        // 中奖抽取
        $date =$this->db->GetList("SELECT id, Grade,probability FROM `@#_member_zp` order by `probability` desc");
        $probabilitys = array_column($date, 'probability','id');
        $prizes_id = $this->get_zp_rand($probabilitys);

        // 中奖记录操作
        $res ='';
        $zparr = $this->db->GetOne("SELECT * FROM `@#_member_zp` WHERE `id`= $prizes_id ");
        if(!$zparr) {
            $this->jsonData->error(201,'奖品错误');
        }
        $name = $zparr['name'];
        $time = time();

        $type = $zparr['type'];
        $ren  = $zparr['ren'];
        $zj = $this->db->Query("INSERT INTO `@#_zp_zj` (`uid`,`Grade`,`content`,`time`,`state`,`ren`) VALUE ('$uid','$prizes_id','$name','$time','$type','$ren')" );

        $id = $this->db->insert_id();

        $res['id'] = $prizes_id;
        $res['zj_id'] = $id;
        $res['type'] = $type;
        $res['money'] = $zparr['money'];
        $res['img'] = G_UPLOAD_PATH .'/'.$zparr['img'];
        $res['content'] = $zparr['name'];
        $res['Grade'] = $zparr['Grade'];
        $zj_num = $this->db->Query("UPDATE `@#_zp_number` SET `number`=`number`-1,`has_num`=`has_num`+1 where `uid`=$uid");
        if($type =='0' ){
            $res['yaoqingnum'] = $zparr['ren'];
        }elseif($type =='1'){
            $score = $zparr['money'];
            $this->db->Query("UPDATE `@#_member` SET `score`=`score`+$score where `uid`= $uid");
            $account_add = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '转盘抽奖', '$score', '$time')");
            $rechange_add = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time` ,`code`) VALUES ('$uid', '1', '账户', '转盘抽奖', '$score', '$time','$id')");
        }elseif($type =='2'){
            $money =  $zparr['money'];
            $this->db->Query("UPDATE `@#_member` SET `money`=`money`+$money where `uid`=$uid");
            $account_add = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '转盘抽奖', '$money', '$time')");
            $rechange_add = $this->db->Query("INSERT INTO `@#_member_rechange` (`uid`, `type`, `pay`, `content`, `money`, `time` ,`code`) VALUES ('$uid', '1', '账户', '转盘抽奖', '$money', '$time','$id')");
        }

        $this->jsonData->success('','', $res);
    }

    // 随机中奖
    private function get_zp_rand($data) {
        $key = array_keys($data);
        shuffle($key); // 打乱中奖顺序
        $goods = [];
        foreach($key as $k) {
            $goods[$k] = $data[$k];
        }

        $probability_sum = array_sum($goods);
        $goods_id = 0;
	    foreach ($goods as $id => $v) {
            $n = rand(1, $probability_sum);
	        if($n <= $v) {
	            $goods_id = $id;
	            break;
	        } else {
                $probability_sum -= $v;
            }
	    }
	    return $goods_id;
    }
}