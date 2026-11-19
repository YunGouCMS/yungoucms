<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');

System::load_app_class('base', 'app', 'no');

// System::load_sys_fun('returnData');

class coupon extends base
{

    public function __construct()
    {
        parent::__construct();
    }

    public function coupon_list(){
        $p = htmlspecialchars($_POST['p']) ? $_POST['p'] : 1; //分页
        $num = 10;
        $star = ($p-1) * $num;
        $time = time();
        $sql = "select c.*,if(mc.uid is null,0,1) as is_receive from `@#_coupon` as c 
                left join `@#_member_coupon` as mc on c.id = mc.c_id 
                where mc.uid = '$this->uId' 
                    or mc.uid is null 
                group by c.id 
                order by c.id desc 
                LIMIT $star ,$num";
        $list = $this->db->GetList($sql);
        foreach ($list as &$item){
            $item['is_expire'] = '0';
            if($item['receive_start']){
                $item['is_expire'] = $item['receive_start']<$time?"1":"0";
            }
            if($item['receive_end']){
                $item['is_expire'] = $item['receive_end']>$time?"1":"0";
            }
            $item["start_time"] = $item['expire_start']?date("Y-m-d H:i:s",$item['expire_start']):"";
            $item["end_time"] = $item['expire_end']?date("Y-m-d H:i:s",$item['expire_end']):"";
            $item["receive_start"] = $item['receive_start']?date("Y-m-d H:i:s",$item['receive_start']):"";
            $item["receive_end"] = $item['receive_end']?date("Y-m-d H:i:s",$item['receive_end']):"";
            // unset($item['expire_type']);  
            unset($item['expire_start']);  
            unset($item['expire_end']);  
        }
        $this->jsonData->success(200,'ok',$list?:[]);
    }

    /**
     * 领取优惠券
     */
    public function receive_coupon(){
        $uid = $this->uId;
        $coupon_id = $_POST['coupon_id'];
        $coupon = $this->db->GetOne("SELECT * FROM `@#_coupon` WHERE id = '$coupon_id'");
        if($coupon){
            //检查是否已领取
            $has = $this->db->GetOne("SELECT * FROM `@#_member_coupon` WHERE uid = '$uid' AND c_id = '$coupon_id'");
            if($has){
                $this->jsonData->error('','您已领取');
            }
            $time = time();
            //检测是否可领取
            if($coupon['receive_start'] && $coupon['receive_start']>$time){
                $this->jsonData->error('','优惠券还未开始领取');
            }
            if($coupon['receive_end'] &&  $coupon['receive_end']<$time){
                $this->jsonData->error('','优惠券已过期');
            }
            //领取
            if($coupon['expire_type'] == 1){
                $day = $coupon['day'];
                $h = $coupon['time'];
                $end_time = strtotime("+{$day}days",$time);
                $end_time = strtotime("+{$h}hours",$end_time);
            }else{
                $time = $coupon['expire_start'];
                $end_time = $coupon['expire_end'];
            }
            $info = [
                "uid"=>$uid,
                "c_id"=>$coupon_id,
                "receive_time"=>$time,
                "start_time"=>$time,
                "end_time"=>$end_time,
                "money"=>$coupon['money'],
                "discount"=>$coupon['discount'],
                "type"=>$coupon['type'],
                "model"=>$coupon['model'],
                "desc"=>$coupon['desc'],
                "name"=>$coupon['name'],
            ];
            $sql = Assemblydata($info,"member_coupon");
            $this->db->query($sql);
            $this->jsonData->success(200,'领取成功');
        }else{
            $this->jsonData->error('','优惠券不存在');
        }
    }

    /**
     * 我的优惠券
     */
    public function my_coupon(){
        $uid = $this->uId;

        // 获取优惠卷类型
        $type = safe_replace($_POST['type']);
        $sql = " mc.uid = '$uid' and mc.used = 1";
        if(!empty($type)){
            $sql .= " AND mc.model = '$type' ";
        }else{
            $sql .= " AND mc.model = 1 ";
        }

        $list = $this->db->GetList("SELECT mc.*,c.class_coupon_id 
                FROM `@#_member_coupon` as mc 
                LEFT JOIN `@#_coupon` as c ON mc.c_id = c.id
                WHERE {$sql} ORDER BY mc.receive_time DESC");
        $time = time();

        // 判断购物车中商品分类是否匹配优惠卷
        $goodsId = safe_replace($_POST['goodsId']);
        $goodsList = [];
        if(!empty($goodsId)){
            $goodsList = $this->db->GetList("SELECT `cateid`,`id` FROM `@#_shoplist` WHERE id IN ($goodsId)");
        }

        foreach ($list as $key => &$item){
            //优惠券是否在使用时间内
            if($item['start_time'] < $time && $item['end_time'] > $time){
                $item['status'] = 1;
            }else{
                $item['status'] = 0;
            }
            //优惠券是否过期
            if($item['end_time'] < $time){
                $item['is_expire'] = 1;
            }else{
                $item['is_expire'] = 0;
            }
            $item['receive_time'] = date('Y-m-d H:i:s',$item['receive_time']);
            $item['start_time'] = date('Y-m-d H:i:s',$item['start_time']);
            $item['end_time'] = date('Y-m-d H:i:s',$item['end_time']);
            $item['money'] = number_format($item['money'],2);
            $item['discount'] = round($item['discount'],2);

            // 判断购物车中商品分类是否匹配优惠卷
            if(!empty($goodsList) && $item['class_coupon_id'] > 0){
                // 判断是否领取了该商品分类的优惠卷
                $isCoupon = false;
                foreach ($goodsList as $goods){
                    if($goods['cateid'] == $item['class_coupon_id'] && $isCoupon == false){
                        $isCoupon = true;
                    }
                }
                if (!$isCoupon) {
                    unset($list[$key]);
                }
            }
        }
        $this->jsonData->success(200,'ok',$list);
    }
}