<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('send');
// System::load_sys_fun('returnData');

class statement extends base{

    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();
    }

    /**
     * 消费类型
     * @param int page 页码
     * @param int type 1.消费记录 2.充值记录 3.用户体现记录 4.积分消费记录 5.装卖回收
     */
    public function statementType()
    {
        $page = $_POST['page']?:0;
        $type = $_POST['type'] ?: 5;
        switch ($type){
            case 1:
                $res = $this->statement($page);
                break;
            case 2:
                $res = $this->getUserRecharge($page);
                break;
            case 3:
                $res = $this->getUsertiXian($page);
                break;
            case 4:
                $res = $this->getUserJfConsumption($page);
                break;
            case 5:
                $res = $this->getRecycling($page);
                break;
        }
        echo $res;exit;
    }

    /**
     * 消费记录
     * @param int page 页码
     */
    public function statement($page = 0)
    {
        $member=$this->userInfo();
        $FIdx=safe_replace($page);
        $EIdx=10;
        $FIdx=$FIdx*$EIdx;
        // and `type`='-1'
        $Consumptionlist=$this->db->GetList("select `uid` from `@#_member_account` where `uid`='$member[uid]' ");
        $Consumption['listItems']=$this->db->GetList("select * from `@#_member_account`   where `uid`='$member[uid]'  ORDER BY time DESC limit $FIdx,$EIdx ");
        //货币单位
        $currency = $this->currency;
        $Consumption["currency"] = $currency;
        if(empty($Consumption['listItems'])){
            $this->jsonData->successCode('','暂无数据',$Consumption);
        }else{
            foreach($Consumption['listItems'] as $key=>$val){
                $Consumption['listItems'][$key]['time']=date("Y-m-d H:i:s",$val['time']);
                if($val['type'] == -1){
                    $val['money'] = -$val['money'];
                }
                $Consumption['listItems'][$key]['money']=$val['money'];
                $content = $this->getLgMsg($val['content']);
                if($content == "error"){$content = $val['content'];}
                // $Consumption['listItems'][$key]['content']= $content;
                if(!empty($Consumption['listItems'][$key]['code'])){
                    $Consumption['listItems'][$key]['goods'] = $goods = $this->db->GetOne("select `uphoto`,`shopname` from `@#_member_go_record` where `code`='{$Consumption['listItems'][$key]['code']}' and shopid = {$val['message']} ");
                    if($val['type']==-1){
                        if(!$goods){
                            $Consumption['listItems'][$key]['goods'] = $goods = $this->db->GetOne("select `uphoto`,`shopname` from `@#_member_go_record` where `code`='{$Consumption['listItems'][$key]['code']}' ");
                        }
                        $content .= "【".$goods['shopname']."】";
                    }
                }
                $Consumption['listItems'][$key]['content']= $content;
            }
            $Consumption['code']=0;
        }
        $Consumption['count']=count($Consumptionlist);
        $this->jsonData->successCode('','获取成功',$Consumption);
    }

    /**
     * 充值记录
     * @param int page 页码
     */
    public function getUserRecharge($page = 0){
        $member=$this->userInfo();
        $FIdx=safe_replace($page);
        $EIdx=10;//safe_replace($this->segment(5));
        $FIdx=$FIdx*$EIdx;
        $Rechargelist=$this->db->GetList("select * from `@#_member_rechange` where `uid`='$member[uid]'  ORDER BY time DESC");
        $Recharge['listItems']=$this->db->GetList("select * from `@#_member_rechange` where `uid`='$member[uid]'  ORDER BY time DESC limit $FIdx,$EIdx ");
        //货币单位
        $currency = $this->currency;
        $Recharge["currency"] = $currency;
        if(empty($Recharge['listItems'])){
            $this->jsonData->successCode('','暂无数据',$Recharge);
        }else{
            foreach($Recharge['listItems'] as $key=>$val){
                $Recharge['listItems'][$key]['time']=date("Y-m-d H:i:s",$val['time']);
                //临时处理多语言
                if($val['content'] == "充值"){
                    $Recharge['listItems'][$key]['content'] = $this->getLgMsg("Recharge");
                }elseif($val['content'] == "线下充值"){
                    $Recharge['listItems'][$key]['content'] = $this->getLgMsg("offline_recharge");
                }elseif($val['content'] == "USDT充值"){
                    $Recharge['listItems'][$key]['content'] = "USDT".$this->getLgMsg("Recharge");
                }elseif($val['content'] == "银行卡充值"){
                    $Recharge['listItems'][$key]['content'] = $this->getLgMsg('bank_card').$this->getLgMsg("Recharge");
                }
            }
            $Recharge['code']=0;
        }
        $Recharge['count']=count($Rechargelist);

        $this->jsonData->successCode('','获取成功',$Recharge);
    }

    //用户体现记录
    public function getUsertiXian($page = 0){
        $member=$this->userInfo();
        $FIdx=safe_replace($page);
        $EIdx=10;
        $FIdx=$FIdx*$EIdx;
        $uid = $member['uid'];
        $Consumptionlist=$this->db->GetList("SELECT id FROM `@#_member_cashout` WHERE `uid` = '$uid'");
        $Consumption['listItems']=$this->db->GetList("SELECT * FROM `@#_member_cashout` WHERE `uid` = '$uid' ORDER BY time DESC limit $FIdx,$EIdx ");
        //货币单位
        $currency = $this->currency;
        $Consumption["currency"] = $currency;
        if(empty($Consumption['listItems'])){
            $this->jsonData->success('','暂无数据',$Consumption);
        }else{
            foreach($Consumption['listItems'] as $key=>$val){
                $Consumption['listItems'][$key]['time']=date("Y-m-d H:i:s",$val['time']);
            }
            $Consumption['code']=0;
        }
        $Consumption['count']=count($Consumptionlist);
//        p($Consumption);ecit;
        $this->jsonData->successCode('','获取成功',$Consumption);
    }


    //积分消费记录
    public function getUserJfConsumption($page = 0){
        $member=$this->userInfo();
        $FIdx=safe_replace($page);
        $EIdx=10;
        $FIdx=$FIdx*$EIdx;
        $uid = $member['uid'];
        $Consumptionlist=$this->db->GetList("SELECT *  FROM (SELECT
                    	*,
                    	1 AS `code` 
                    FROM
                    	`go_member_account` 
                    WHERE
                    	`pay` = '积分'	UNION 
                    SELECT
                    	`uid`,
                    	1 AS sid,
                    	`type`,
                    	`pay`,
                    	`content`,
                    	`money`,
                    	`time`,
                    	CASE `code` WHEN -1 THEN 1 ELSE 0 END `code`
                    FROM
                    	`go_member_rechange` 
                    WHERE
                    	`pay` = '积分' 	UNION 
                    	
                    SELECT
                    	`uid`,
                    	1 AS `sid`,
                    	CASE `type` WHEN -1 THEN 1 ELSE 0 END `tyep`,
                    	`pay`,
                    	CASE `type` WHEN -1 THEN '购买商品赠送' ELSE 0 END `content`,
                    	CASE `type` WHEN -1 THEN `score` ELSE 0 END `money`,
                    	`time`,
                    	123 AS `code`
                    FROM
                    	`go_member_account_copy`) as temp WHERE  temp.uid = '$uid'  ORDER BY temp.time DESC");

        $Consumption['listItems']=$this->db->GetList("SELECT *  FROM (SELECT
                    	*,
                    	1 AS `code` 
                    FROM
                    	`go_member_account` 
                    WHERE
                    	`pay` = '积分'	UNION 
                    
                    SELECT
                    	`uid`,
                    	1 AS sid,
                    	`type`,
                    	`pay`,
                    	`content`,
                    	`money`,
                    	`time`,
                    	CASE `code` WHEN -1 THEN 1 ELSE 0 END `code`
                    FROM
                    	`go_member_rechange` 
                    WHERE
                    	`pay` = '积分' 	UNION 
                    	
                    SELECT
                    	`uid`,
                    	1 AS `sid`,
                    	CASE `type` WHEN -1 THEN 1 ELSE 0 END `tyep`,
                    	`pay`,
                    	CASE `type` WHEN -1 THEN '购买商品赠送' ELSE 0 END `content`,
                    	CASE `type` WHEN -1 THEN `score` ELSE 0 END `money`,
                    	`time`,
                    	123 AS `code`
                    FROM
                    	`go_member_account_copy`) as temp WHERE  temp.uid = '$uid'  ORDER BY temp.time DESC limit $FIdx,$EIdx ");
        if(empty($Consumption['listItems'])){
            $this->jsonData->success('','暂无数据',$Consumption);
        }else{
            foreach($Consumption['listItems'] as $key=>$val){
                $Consumption['listItems'][$key]['time']=date("Y-m-d H:i:s",$val['time']);
            }
            $Consumption['code']=0;
        }
        $Consumption['count']=count($Consumptionlist);
        $this->jsonData->successCode('','获取成功',$Consumption);

    }

    //获取回收商品列表
    public function getRecycling($page = 0){
        $FIdx=safe_replace($page);
        $EIdx=10;
        $FIdx=$FIdx*$EIdx;
        $userId = $this->userInfo()['uid'];
        if(empty($userId)){
//            echo json_encode(['code'=>0,'msg'=>'账号有误','data'=>'']);exit;
            $this->jsonData->error('','账号有误');
        }

        $data = $this->db->GetList("select `content`,`money`,`time` from `@#_member_recodes` where `uid` = '$userId' and `type`='-4' order by time desc limit $FIdx,$EIdx");
        // rsort($data);
        //货币单位
        $currency = $this->currency;
        foreach ($data as &$v){
            if(!empty($v['time'])) $v['time'] = date('Y-m-d H:i:s',$v['time']);
            $v['currency'] = $currency;
        }

        if(!empty($data)){
            $this->jsonData->successCode('','获取成功',$data);
        }else{
//            echo json_encode(['code'=>2,'msg'=>'暂无数据','data'=>'']);exit;
            $this->jsonData->successCode('','暂无数据',$data);

        }

    }
}
?>