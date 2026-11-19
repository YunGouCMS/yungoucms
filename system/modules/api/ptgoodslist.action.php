<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

class ptgoodslist extends SystemAction
{
    private $db;

    public function __construct()
    {
        $this->db=System::load_sys_class('model');
    }

    public function success($code=200,$msg="success",$data=[]){
        echo json_encode(["code"=>$code,"msg"=>$msg,"data"=>$data]);
    }
    public function error($code=200,$msg="success",$data=[]){
        echo json_encode(["code"=>$code,"msg"=>$msg,"data"=>$data]);
    }

    /**
     * 积分即将揭晓
     * @param int page 页码
     */
    public function getUndrawnList(){
        $FIdx=!empty($_POST['page']) ?$_POST['page']*10: 0;
        $EIdx=10;
        $shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist` WHERE `q_uid` is null AND ssc!=1 AND `canyurenshu`>0 AND `canyurenshu` < `zongrenshu` ");
        // AND  (`language` = '$this->nowUserLanguage' or `language` = '')
        //即将开奖
        $shoplist['listItems']=$this->db->GetList("SELECT `id`,`money`,`thumb`,`qishu`,`title`,`canyurenshu`,`shenyurenshu`,`zongrenshu`,(`canyurenshu`/`zongrenshu`)as schedule 
                                                    FROM `@#_shoplist` 
                                                    WHERE `q_uid` is null 
                                                    AND ssc!=1 
                                                    AND `canyurenshu` > 0 
                                                    AND `canyurenshu` < `zongrenshu`  
                                                    ORDER BY canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc LIMIT $FIdx,$EIdx");
                                                    // AND (`language` = '$this->nowUserLanguage' or `language` = '') 
        if(empty($shoplist['listItems'])){
            $shoplist['listItems'] = [];
            $this->success('','暂时无参数',$shoplist);
        }else{
            foreach($shoplist['listItems'] as $key=>$val){
                // $shoplist['listItems'][$key]['q_end_time']=date('Y-m-d H:i',$val['q_end_time']);
                $shoplist['listItems'][$key]['thumb']= G_UPLOAD_PATH.'/'. $val['thumb'];
                $shoplist['listItems'][$key]['integral']= $val['money'];
                $shoplist['listItems'][$key]['schedule']= round($val['schedule'],2);
                // 计算误差
                $parts = explode('.', (string)($val['canyurenshu']/$val['zongrenshu'] *100));
                $integerPart = $parts[0];
                if(isset($parts[1])){
                    $decimalPart = substr($parts[1], 0, 2); // 截取小数部分的前两位
                }else{
                    $decimalPart = "00";
                }
                $shoplist['listItems'][$key]['width'] = $integerPart . '.' . $decimalPart; // 组合回数字
            }
            $shoplist['code']=0;
            $shoplist['count']=$shopsum['total'];
                        
            
        }
       $this->success('',"ok", $shoplist);

    }
   
}