<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
System::load_sys_fun('send');
// System::load_sys_fun('returnData');

class cashout extends base{

    public function __construct()
    {
        parent::__construct();
    }
    public function cashout(){

        sleep(1);
        $member=$this->userInfo();

        $memberdj=$this->db->GetList("select * from `@#_member_group`");

        $jingyan=$member['jingyan'];

        if(!empty($memberdj)){

            foreach($memberdj as $key=>$val){

                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){

                    $member['yungoudj']=$val['name'];

                }

            }

        }

        $uid = $member['uid'];

        $yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid='$uid'");
        if(!$yongjin_info){

            $this->db->Query("INSERT INTO `@#_member_commission` (`uid`) VALUES ('$uid')");

        }

        /*$shourutotal = $yongjin_info['shouru_money'];

        $zhichutotal = $yongjin_info['zhichu_money'];

        $total=sprintf("%01.2f",$yongjin_info['total_money']+$yongjin_info['dongjie_money']);  //计算佣金总余额*/

        $data['cashouthdtotal']=sprintf("%01.2f",$yongjin_info['total_money']) ?: 0.00;//活动佣金

        //$cashoutdjtotal = $yongjin_info['dongjie_money'];

        $data['pt_msg'] = '(余额满100元才申请退款且每天只能退款3次)';
        $data['pt_Circumscribes'] = '(输入不小于100的正整数)';
        $data['jf_msg'] = '(余额满100元才申请退款且每天只能退款3次)';
        $data['jf_Circumscribes'] = '(该方式充值与普通充值同样计算充值累计，均可参与商城活动，充值比例为1：1)';
        $data['Hint'] = '钱包提现将扣2元手续费';

        $this->jsonData->successCode('','',$data);
    }

    /**
     * 获取用户银行卡信息
     * @return 
     */
    public function getUserBankCard(){
        $uid = $this->uId;
        $data = $this->db->GetList("select * from `@#_member_bank_card` where uid=$uid order by `default` desc,time desc");
        $this->jsonData->successCode('','',$data);
    }

    /**
     * 设置默认
     * @return void
     */
    public function bankSetDefault(){
        $uid = $this->uId;
        $id = safe_replace($_POST['id']);
        $this->db->Query("update `@#_member_bank_card` set `default` = 0 where uid= $uid  ");
        $this->db->Query("update `@#_member_bank_card` set `default` = 1 where uid= $uid  and id = $id");
        $this->jsonData->successCode('','ok');
    }

    public function bankAdd(){
        //保存银行卡信息
        $username   =htmlspecialchars($_POST['txtUserName']);
        $bankname   =htmlspecialchars($_POST['txtBankName']);
        $branch     =htmlspecialchars($_POST['txtSubBank']);
        $banknumber =htmlspecialchars($_POST['txtBankNo']);
        $linkphone  =htmlspecialchars($_POST['txtPhone']);
        $default  =htmlspecialchars($_POST['default']);
        $uid = $this->uId;
        $res = $this->db->GetOne("select uid from `@#_member_bank_card` where uid=$uid and user_name=$username and bank_name = $bankname and bank_no = $banknumber");
        if(!$res){
            if($default){
                $this->db->Query("update `@#_member_bank_card` set `default` = 0 where uid= $uid  ");
            }
            $sql = Assemblydata(["uid"=>$uid,"user_name"=>$username,"bank_name"=>$bankname,"sub_bank"=>$branch,
                                 "bank_no"=>$banknumber,"phone"=>$linkphone,"time"=>date("Y-m-d H:i:s"),
                                 "default"=>$default
                                ],"member_bank_card","in");
            $this->db->Query($sql);
            $this->jsonData->successCode('','ok');
        }else{
            $this->jsonData->error('','请勿重复提交');
        }
    }
    public function bankEdit(){
        //保存银行卡信息
        $username   =htmlspecialchars($_POST['txtUserName']);
        $bankname   =htmlspecialchars($_POST['txtBankName']);
        $branch     =htmlspecialchars($_POST['txtSubBank']);
        $banknumber =htmlspecialchars($_POST['txtBankNo']);
        $linkphone  =htmlspecialchars($_POST['txtPhone']);
        $default  =htmlspecialchars($_POST['default']);

        $id = safe_replace($_POST['id']);
        $uid = $this->uId;
        $res = $this->db->GetOne("select uid from `@#_member_bank_card` where uid=$uid and id = $id");
        if($res){
            if($default){
                $this->db->Query("update `@#_member_bank_card` set `default` = 0 where uid= $uid  ");
            }
            $sql = Assemblydata(["user_name"=>$username,"bank_name"=>$bankname,"sub_bank"=>$branch,
                                 "bank_no"=>$banknumber,"phone"=>$linkphone,"time"=>date("Y-m-d H:i:s"),
                                 "default"=>$default
                                ],"member_bank_card","up",[['id',$id]]);
            $this->db->Query($sql);
            $this->jsonData->successCode('','ok');
        }else{
            $this->jsonData->error('','未找到银行卡');

        }
    }

    public function bankDel(){
        $uid = $this->uId;
        $id = safe_replace($_POST['id']);
        $this->db->Query("delete from `@#_member_bank_card` where uid= $uid and id = $id");
        $this->jsonData->successCode('','ok');
    }

    /**
     * 提现申请
     * @param int type 1.提现 2.积分
     * @param int money 金额
     * @param string txtUserName 开户名称
     * @param string txtBankName 银行名称
     * @param string txtSubBank 开户地点
     * @param string txtBankNo 开户账号
     * @param string txtPhone 联系电话
     * @param int txtCZMoney 充值积分金额 （积分充值必传）
     */
    public function withdrawalApplication()
    {
        $member=$this->userInfo();
        $uid = $this->uId;
        $yongjin_info=$this->db->GetOne("select * from `@#_member_commission` WHERE uid='$uid'");
        /*$shourutotal = $yongjin_info['shouru_money'];
        $zhichutotal = $yongjin_info['zhichu_money'];*/
        $total=sprintf("%01.2f",$yongjin_info['total_money']+$yongjin_info['dongjie_money']);  //计算佣金总余额

        $cashouthdtotal=sprintf("%01.2f",$yongjin_info['total_money']) ?: 0.00;//活动佣金
        // $cashoutdjtotal = $yongjin_info['dongjie_money'];
        if($_POST['type'] == 1){ //提现
            $money      = abs(intval($_POST['money']));
            $username   =htmlspecialchars($_POST['user_name']);
            $bankname   =htmlspecialchars($_POST['bank_name']);
            $branch     =htmlspecialchars($_POST['sub_bank']);
            $banknumber =htmlspecialchars($_POST['bank_no']);
            $linkphone  =htmlspecialchars($_POST['phone']);
            $time       =time();
            $type       = -3;  //收取1/消费-1/充值-2/提现-3
            $checkStatus=$this->db->GetOne("select * from `@#_member_cashout` where uid=$uid and auditstatus=0");
            if($checkStatus){
                $this->jsonData->success('', $this->lgs['repeated_submission']); // 已有提现申请尚未处理完成,请勿重复提交！
            }

            $info=$this->db->GetOne("select uid from `@#_member_cashout` where uid=$uid and time=$time");

            if($info){
               $this->jsonData->error('',$this->lgs['repeated_submission']); // 不可重复提交
            }

            if ($money < 100) {
                // $this->jsonData->error('', '提现金额小于100元'); // 提现金额小于100元
                $msg = $this->getLgMsg('withdrawal_amount_less_than100','invite_cashout');
                $this->jsonData->error('', $msg); // 提现金额小于100元
            }
            
            if($cashouthdtotal<0 || $cashouthdtotal == 0){
                $this->jsonData->error('',$this->lgs['Insufficient_commission_balance']); // 佣金金额小于或等于0
            }
            
            if($cashouthdtotal<100){
                // $this->jsonData->error('','佣金活动金额大于100元才能提现'); // 佣金活动金额大于100元才能提现
                $msg = $this->getLgMsg('invite_cashout_notthan100','controller_invite');// 佣金活动金额大于100元才能提现
                $this->jsonData->error('', $msg);
            }elseif($cashouthdtotal<$money){
                // $this->jsonData->error('','输入额超出活动佣金金额'); // 输入额超出活动佣金金额!
               $msg = $this->getLgMsg('invite_cashout_morelimit','controller_invite');// 输入额超出活动佣金金额!
                $this->jsonData->error('', $msg);
            }elseif($total<$money ){
                // $this->jsonData->error('','输入额超出总佣金金额'); // 输入额超出总佣金金额!
                $msg = $this->getLgMsg('invite_cashout_moretotal','controller_invite');// 输入额超出总佣金金额!
                $this->jsonData->error('', $msg);
            }else{
                //插入提现申请表  这里不用在佣金表中插入记录 等后台审核才插入
                $this->db->Autocommit_start();
                $res= $this->db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money`-'$money',`dongjie_money`=`dongjie_money` + $money WHERE uid='$uid'");
                if($res){
                    // procefees 手续费
                    $procefees = System::load_app_config("user_fufen",'fufen_yongjintx','member');
                    $this->db->Query("INSERT INTO `@#_member_cashout`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`,`procefees`)VALUES('$uid','$money','$username','$bankname','$branch','$banknumber','$linkphone','$time',$procefees)");
                    // $this->jsonData->success('','申请成功！请等待审核'); 
                    $sql = Assemblydata('',"member_cashout","sel",["uid"=>$uid,"time"=>$time,"bankname"=>$bankname]);
                    $req = $this->db->GetOne($sql);
                    if($req){
                        $this->db->Autocommit_commit();
                    }else{
                        $msg = $this->getLgMsg('invite_cashout_appfail','controller_invite');// 申请失败！请留意佣金余额！
                        $this->jsonData->error('', $msg); 
                    }
                    $notice = new notice();
                    $detai = [
                        "type"=>$type,
                        "id"=>$req['id']
                    ];
                    $data = [
                        "type"=>2,
                        "content"=>"提现",
                    ];
                    $notice->addNotice($data,$detai);
                    $msg = $this->getLgMsg('invite_cashout_appsuc','controller_invite');// 申请成功！请等待审核！
                    $this->jsonData->success('', $msg);
                }else{
                    $this->db->Autocommit_rollback();
                    // $this->jsonData->error('','申请失败！请留意佣金余额'); 
                    $msg = $this->getLgMsg('invite_cashout_appfail','controller_invite');// 申请失败！请留意佣金余额！
                    $this->jsonData->error('', $msg);
                }
            }
        }

        if($_POST['type'] == 2){//充值
            $money      = abs(intval($_POST['txtCZMoney']));
            $type       = 1;
            $pay        ="账户";
            $time       =time();
            $content    ="使用佣金充值到账户";

            if($money > $cashouthdtotal || $money <= 0){
                // $this->jsonData->error('','输入额超出活动佣金金额'); // 输入额超出活动佣金金额！
                $this->jsonData->error('',$this->getLgMsg('amount_error','controller_invite')); // 佣金金额输入不正确！
            }
            
            $this->db->Autocommit_start();
            $res= $this->db->Query("UPDATE `@#_member_commission` SET `total_money` = `total_money`-'$money',`zhichu_money`=`zhichu_money` + $money WHERE uid='$uid'");
            if($res){
                //插入记录
                $account=$this->db->Query("INSERT INTO `@#_member_rechange`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$uid','$type','$pay','$content','$money','$time')");
                // 查询是否有该记录
                if($account){
                    //修改剩余金额
                    $leavemoney=$member['money']+$money;
                    $mrecode=$this->db->Query("UPDATE `@#_member` SET `money`='$leavemoney' WHERE `uid`='$uid' ");
                    //在佣金表中插入记录
                    $recode=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`)VALUES('$uid','-2','$content','$money','$time')");
                    if ($recode){
                        $this->db->Autocommit_commit();
                        // $this->jsonData->success('','充值成功！'); // 充值成功！
                        $this->jsonData->success('',$this->getLgMsg('invite_cashout_resuc','controller_invite')); // 充值成功！
                    }
                    $this->db->Autocommit_rollback();
                    // $this->jsonData->error('','充值失败！'); // 充值失败！
                    $this->jsonData->error('',$this->getLgMsg('invite_cashout_refail','controller_invite')); // 充值失败！
                }else{
                    $this->db->Autocommit_rollback();
                    // $this->jsonData->error('','充值失败！'); // 充值失败！
                    $this->jsonData->error('',$this->getLgMsg('invite_cashout_refail','controller_invite')); // 充值失败！
                }
            }else{
                $this->db->Autocommit_rollback();
                // $this->jsonData->error('','充值失败！');// 充值失败！
                $this->jsonData->error('',$this->getLgMsg('invite_cashout_refail','controller_invite')); // 充值失败！
            }
        }
    }


    public function get_bank_code(){
        //获取设置的提现类
        $data = [];
        $pay_class = System::load_app_config("user_fufen", "yongjintx_pay", "member");
        if ($pay_class) {
            $res = $this->db->GetOne("SELECT * FROM `@#_pay` WHERE `pay_class` = '{$pay_class}'");
            if ($res) {
                $data = mb_unserialize($res['data']);
            }
        }
        $this->jsonData->success('','',$data); 
    }

}