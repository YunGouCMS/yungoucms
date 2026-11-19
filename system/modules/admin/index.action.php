<?php 

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
// System::load_app_fun("plug",G_ADMIN_DIR);
// System::load_sys_fun("plug");
System::load_sys_fun('redisData');
class index extends admin {
	private $db;
	private $redis;
	/** 韬龙 API 最后一次请求的 HTTP 状态码，用于 testCryptoApi 返回 401 等提示 */
	private $lastTaoLongHttpCode = 0;
	public function __construct(){		
		parent::__construct();		
		$this->db=System::load_app_model('admin_model');		
        $this->redis = new redisData;
	}
	
	public function init(){
		$info=$this->AdminInfo;
		// 从数据库获取完整的admin信息，包括bell_enabled
		$admin_full = $this->db->GetOne("SELECT * FROM `@#_admin` WHERE `uid` = '{$info['uid']}'");
		if($admin_full){
			$info['bell_enabled'] = isset($admin_full['bell_enabled']) ? $admin_full['bell_enabled'] : 0;
		} else {
			$info['bell_enabled'] = 0;
		}
		$p_data=$this->db->GetOne("SELECT * FROM `@#_admin_usergroup` where mid='$info[mid]'");
        $arr = array(
            // '5'  => "凌晨好",
            // '8'  => "早上好",
            // '11' => "上午好",
            // '13' => "中午好",
            // '17' => "下午好",
            // '19' => "傍晚好",
            // '20' => "晚上好",
            // '24' => "晚上好",
            '5'  => $this->getLgMsg("administrator","home"),
            '8'  => $this->getLgMsg("administrator","home"),
            '11' => $this->getLgMsg("administrator1","home"),
            '13' => $this->getLgMsg("administrator1","home"),
            '17' => $this->getLgMsg("administrator2","home"),
            '19' => $this->getLgMsg("administrator3","home"),
            '20' => $this->getLgMsg("administrator3","home"),
            '24' => $this->getLgMsg("administrator3","home"),
        );

        foreach ($arr as $k => $a) {
            if ($k > date('H')) {
                $str = $a;
                break;
            }
        }
		$p_arr=explode(',',$p_data['permission']);
        $domian =  $_SERVER['HTTP_HOST'];
        /* include_once "certification.action.php";
        $certification = new certification();
        $msg = $certification->certificationinfo();
        $certificationData = $msg['msg'];
        if ($msg['code'] == "2"){
            $certificationData = "<a  href=\"https://copy.taolong.com/?domain={$domian}\" target=\"_blank\">{$certificationData}</a>";
        } */
        $languageBackend = $this->getLanguageList(1,3);
        foreach($languageBackend as &$v){
            $n = strpos($v['lg_name'],"（");
            if($n === false){
                $n = strlen($v['lg_name']);
            }
            $v['lg_name'] = substr($v['lg_name'],0,$n);
            
            // 处理国旗URL
            if(isset($v['national_flag']) && !empty($v['national_flag']) && isset($v['flag_status']) && $v['flag_status'] == 1){
                $flag = explode('/', $v['national_flag']);
                if(count($flag) <= 1){
                    $v['flag_url'] = G_PLUGIN_PATH."/imgs/flag/".$v['national_flag'];
                }elseif(count($flag) > 1){
                    $v['flag_url'] = G_UPLOAD_PATH."/".$v['national_flag'];
                }else{
                    $v['flag_url'] = '';
                }
            }else{
                $v['flag_url'] = '';
            }
            
            // 如果没有国旗URL，根据语言代码生成SVG国旗图标（使用内联SVG）
            if(empty($v['flag_url'])){
                $v['flag_svg'] = $this->getFlagSvgByLanguage($v['lg_value'] ?? 'Chinese');
                $v['flag_url'] = ''; // 保持为空，使用flag_svg
            } else {
                $v['flag_svg'] = '';
            }
        }
        $lan = $info['language'];
        // 获取通知数据
        if($info['mid']==1){
            $notice_data = $this->getManageNotice();
        }else{
            $notice_data = $this->getOperatNotice();
        }
        $notice = ['data' => $notice_data];
        // 获取铃声开关状态
        $bell_enabled = isset($info['bell_enabled']) ? $info['bell_enabled'] : 0;
        $backend_copyright = $this->getConfig('backend_copyright');
        if($backend_copyright==2){
            $logo = G_UPLOAD_PATH.'/'.$this->getConfig("web_logo");
        }else{
            $logo = G_PLUGIN_PATH."/style/global/image/logo.png";
        }
		include $this->tpl(ROUTE_M,'admin.index');
	}
	
	// 切换铃声开关状态
	public function toggleBell(){
		$AdminInfo = $this->AdminInfo;
		$uid = $AdminInfo['uid'];
		$bell_enabled = isset($_POST['bell_enabled']) ? intval($_POST['bell_enabled']) : 0;
		
		// 先检查字段是否存在，如果不存在则创建
		$check_sql = "SHOW COLUMNS FROM `@#_admin` LIKE 'bell_enabled'";
		$check_res = $this->db->GetOne($check_sql);
		if(!$check_res){
			// 字段不存在，添加字段
			$alter_sql = "ALTER TABLE `@#_admin` ADD COLUMN `bell_enabled` TINYINT(1) DEFAULT 0 COMMENT '铃声开关：0关闭，1开启'";
			$this->db->Query($alter_sql);
		}
		
		// 更新数据库
		$sql = "UPDATE `@#_admin` SET `bell_enabled` = '$bell_enabled' WHERE `uid` = '$uid'";
		$res = $this->db->Query($sql);
		
		if($res !== false){
			// 更新session中的信息
			$_SESSION['admin']['bell_enabled'] = $bell_enabled;
			// success方法的参数顺序是 (code, msg, data)，code默认是200
			$this->jsonData->success(200, '操作成功', ['bell_enabled' => $bell_enabled]);
		} else {
			$this->jsonData->error('操作失败');
		}
	}
	
	/**
	 * 根据语言代码获取国旗SVG图标（返回SVG字符串，用于内联显示）
	 */
	private function getFlagSvgByLanguage($lgValue) {
		$flagSvgs = array(
			// 中国国旗 - 红色背景，黄色五角星
			'Chinese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="20" height="15"><rect width="640" height="480" fill="#de2910"/><path d="M213.3 0h213.4v480H213.3z" fill="#ffde00"/><circle cx="320" cy="240" r="80" fill="#de2910"/><circle cx="320" cy="240" r="60" fill="#ffde00"/><path d="M320 180l20 60-50-40h60l-50 40z" fill="#de2910"/></svg>',
			// 美国国旗 - 红白条纹，左上角蓝色区域和白色星星
			'English' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7410 3900" width="20" height="15"><rect width="7410" height="3900" fill="#b22234"/><path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/><rect width="2964" height="2100" fill="#3c3b6e"/></svg>',
			// 越南国旗
			'Vietnamese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="600" fill="#da251d"/><path d="M450 200l50 150-130-95h160l-130 95z" fill="#ff0"/></svg>',
			// 缅甸国旗
			'Myanmar' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="200" fill="#ffcd00"/><rect y="200" width="900" height="200" fill="#34b233"/><rect y="400" width="900" height="200" fill="#ea2839"/><circle cx="450" cy="300" r="100" fill="#fff"/></svg>',
			// 印度国旗（印地语）
			'Hindi' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="900" height="200" fill="#ff9933"/><rect y="200" width="900" height="200" fill="#fff"/><rect y="400" width="900" height="200" fill="#138808"/><circle cx="450" cy="300" r="60" fill="#000080"/><circle cx="450" cy="300" r="50" fill="#fff"/></svg>',
			// 葡萄牙国旗
			'Portugal' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>',
			'Portuguese' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" width="20" height="15"><rect width="450" height="600" fill="#006600"/><rect x="450" width="450" height="600" fill="#ff0000"/><circle cx="300" cy="300" r="120" fill="#ffd700" stroke="#006600" stroke-width="8"/><circle cx="300" cy="300" r="100" fill="#ff0000"/><path d="M300 200 L320 280 L240 240 L360 240 L280 280 Z" fill="#fff"/></svg>',
		);
		
		// 如果找到对应的国旗，返回SVG
		if(isset($flagSvgs[$lgValue])) {
			return $flagSvgs[$lgValue];
		}
		
		// 默认返回一个通用的国旗图标
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="20" height="15"><rect width="640" height="480" fill="#0066cc"/><circle cx="320" cy="240" r="100" fill="#fff"/></svg>';
	}

    //切换语言
    public function changeLan(){
        $lg_value = $_POST['lg_value'];
        $AdminInfo = $this->AdminInfo;
        $sql = "update `@#_admin` set `language` = '$lg_value' where uid = ".$AdminInfo['uid'];
        $res = $this->db->Query($sql);
        if($res){
            $this->jsonData->success("","");
        }else{
            $this->jsonData->error();
        }
    }

    public function setTimeZone(){
        $language = $_POST['time_zone'];
        $key = $_POST['key'];
        $sel = $_POST['sel'];
        $lg = $this->db->GetOne("select time_zone from `@#_language` where lg_value = '$language'");
        $time_zone = $lg['time_zone'];
        $res = true;
        if($sel != 2){
            $res = $this->setConfig("time_zone",$time_zone);
            $this->_setTimeZone();
        }
        $time_zone_index = $this->getConfig("time_zone_index");
        $time_zone_index = mb_unserialize($time_zone_index);
        $time_zone_index[$key] = $language;  
        $time_zone_index = serialize($time_zone_index);
        $res1 = $this->setConfig("time_zone_index",$time_zone_index);
        if($res && $res1){
            echo json_encode(['code'=>200,"msg"=>"ok"]);
        }else{
            echo json_encode(['code'=>500,"msg"=>"err"]);
        }
        exit;
    }

    public function test(){
        $this->lan("Test_taolong","测试韬龙API连接");
        echo microt(1762482559);die;
        // phpinfo();die;
        var_export(_cfg('admindir'));
        var_export(ROUTE_M);
        exit;
        $lists = $this->db->GetList("select uid,yaoqing,level from `@#_member` where auto_user = 0 and level = 0 and yaoqing <> 0");
        $top_uid = 0;
        $lists = [array ( 'uid' => '7415', 'yaoqing' => '9', 'level' => '0', )];
        foreach($lists as $v){
            $top_uid = $this->ts($v['yaoqing']);
            $this->db->Query("update `@#_member` set `top_uid` = $top_uid where uid = ".$v['uid']);
        }
        echo "ok";
          
    }

    private function ts($uid){
        $m = $this->db->GetOne("select uid,yaoqing,level from `@#_member` where uid = ".$uid);
        if($m['level'] == 2 || $m['yaoqing'] == 0){
            return $m['uid'];
        }else{
            return $this->ts($m['yaoqing']);
        }
    }

    private function getOperatNotice(){
        $where1 = "left join `@#_handle` b on a.id = b.to_id and b.type = ";
        $where2 = "where b.id is null and ";
        // 发货订单
        $count1 = $this->db->GetCount("select count(*) from `@#_member_go_record` a 
        left join `@#_member` m on a.uid = m.uid
        left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 1
        $where1 1 $where2 a.`huode` != '0' and a.`status` LIKE '%未发货%' and m.auto_user!=1 and d.id is not null");
        $res = $this->db->GetList("select a.id from `@#_member_go_record` a 
        left join `@#_member` m on a.uid = m.uid 
        left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 1
        $where1 1 $where2 a.`huode` != '0' and a.`status` LIKE '%未发货%' and m.auto_user!=1 and d.id is not null");
        $ids1 = array_column($res?:[],"id");
        //优惠订单
        $count_yh = $this->db->GetCount("select count(*) from `@#_orders` a 
        left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 2
        $where1 2 $where2  a.`status` = 2 and d.id is not null");
        $res = $this->db->GetList("select a.id from `@#_orders` a
        left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 2
        $where1 2 $where2  a.`status` = 2 and d.id is not null");
        $ids_yh = array_column($res?:[],"id");
        //拼团订单
        $count_group = $this->db->GetCount("select count(*) from `@#_orders_group` a 
        left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 3
        $where1 3 $where2 a.`status` = 2 and group_status = 2 and huode !=0  and d.id is not null");
        $res = $this->db->GetList("select a.id from `@#_orders_group` a
        left join `@#_member_orderaddress_brand` d on d.orderid = a.id and d.mode = 3
        $where1 3 $where2 a.`status` = 2 and group_status = 2 and huode !=0  and d.id is not null");
        $ids_group = array_column($res?:[],"id");
        //充值订单
        //银行卡充值
        $count2=$this->db->GetCount("SELECT count(*) FROM `@#_offline_money` a $where1 21 $where2 a.`status`=1 and a.`type` = 2");
        $res=$this->db->GetList("SELECT a.id FROM `@#_offline_money` a $where1 21 $where2 a.`status`=1 and a.`type` = 2");
        $ids2 = array_column($res?:[],"id");
        // usdt充值
        $count3=$this->db->GetCount("SELECT count(*) FROM `@#_offline_money` a $where1 22 $where2 a.`status`=1 and a.`type` = 3");
        $res=$this->db->GetList("SELECT a.id FROM `@#_offline_money` a $where1 22 $where2 a.`status`=1 and a.`type` = 3");
        $ids3 = array_column($res?:[],"id");
        //提现
        $count4=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_cashout` a $where1 31 $where2 a.`auditstatus` = 0");
        $res=$this->db->GetList("SELECT a.id FROM `@#_member_cashout` a $where1 31 $where2 a.`auditstatus` = 0");
        $ids4 = array_column($res?:[],"id");
        $name1 = $this->getLgMsg("Cloud_purchase","home");
        $name2 = $this->getLgMsg("Discounted_Purchase","home");
        $name3 = $this->getLgMsg("Group_buying","home");
        $name4 = $this->getLgMsg("Bank_card_recharge","home");
        $name5 = $this->getLgMsg("USDT_recharge","home");
        $name6 = $this->getLgMsg("Pending_withdrawal_order","home");
        $data = [
            ["name"=>$name1,"count"=>$count1?:0,'url'=>G_MODULE_PATH."/dingdan/lists/notsend",'id'=>$ids1,"key"=>"operat_yg",'type'=>1],
            ["name"=>$name2,"count"=>$count_yh?:0,'url'=>G_MODULE_PATH."/dingdan/yhlists",'id'=>$ids_yh,"key"=>"operat_yhg",'type'=>2],
            ["name"=>$name3,"count"=>$count_group?:0,'url'=>G_MODULE_PATH."/dingdan/groupList",'id'=>$ids_group,"key"=>"operat_ptg",'type'=>3],
            ["name"=>$name4,"count"=>$count2?:0,'url'=>WEB_PATH."/member/member/down_money_list",'id'=>$ids2,"key"=>"operat_yhk",'type'=>41],
            ["name"=>$name5,"count"=>$count3?:0,'url'=>WEB_PATH."/member/member/down_usdt_list",'id'=>$ids3,"key"=>"operat_usdt",'type'=>42],
            ["name"=>$name6,"count"=>$count4?:0,'url'=>WEB_PATH."/member/member/commissions",'id'=>$ids4,"key"=>"operat_ts",'type'=>31],
        ];
        $is_preferential = $this->getConfig("is_preferential")?:2;
        if($is_preferential == 2){
            unset($data[1]);
        }
        $is_group = $this->getConfig("is_group")?:2;
        if($is_group == 2){
            unset($data[2]);
        }
        return array_values($data);
    }

    private function getManageNotice(){
        $sql = "select count(*) from `@#_handle` a  where a.`manage_status` = 1 and a.`type` = ";
        $sql1 = "select a.id from `@#_handle` a  where a.`manage_status` = 1 and a.`type` = ";
        // 发货订单
        $count1 = $this->db->GetCount($sql . "1");
        $res = $this->db->GetList($sql1 . "1");
        $ids1 = array_column($res?:[],"id");

        $count_yh = $this->db->GetCount($sql ."2");
        $res = $this->db->GetList($sql1 ."2");
        $ids_yh = array_column($res?:[],"id");

        $count_group = $this->db->GetCount($sql ."3");
        $res = $this->db->GetList($sql1 ."3");
        $ids_group = array_column($res?:[],"id");
        //充值订单
        //银行卡充值
        $count2=$this->db->GetCount($sql ."21");
        $res=$this->db->GetList($sql ."21");
        $ids2 = array_column($res?:[],"id");
        // usdt充值
        $count3=$this->db->GetCount($sql ."22");
        $res=$this->db->GetList($sql1 ."22");
        $ids3 = array_column($res?:[],"id");
        //提现
        $count4=$this->db->GetCount($sql ."31");
        $res=$this->db->GetList($sql1 ."31");
        $ids4 = array_column($res?:[],"id");

        $count5=$this->db->GetCount($sql ."41 or a.`type` = 42");
        $res=$this->db->GetList($sql1 ."41 or a.`type` = 42");
        $ids5 = array_column($res?:[],"id");
        $name1 = $this->getLgMsg("Cloud_purchase","home");
        $name2 = $this->getLgMsg("Discounted_Purchase","home");
        $name3 = $this->getLgMsg("Group_buying","home");
        $name4 = $this->getLgMsg("Bank_card_recharge","home");
        $name5 = $this->getLgMsg("USDT_recharge","home");
        $name6 = $this->getLgMsg("Pending_withdrawal_order","home");
        $name7 = $this->getLgMsg("Offline_recharge","home");
        $data = [
            ["name"=>$name1,"count"=>$count1?:0,'url'=>G_MODULE_PATH."/handle/lists/1",'id'=>$ids1,"key"=>"manage_yg",'type'=>1],
            ["name"=>$name2,"count"=>$count_yh?:0,'url'=>G_MODULE_PATH."/handle/lists/2",'id'=>$ids_yh,"key"=>"manage_yhg",'type'=>2],
            ["name"=>$name3,"count"=>$count_group?:0,'url'=>G_MODULE_PATH."/handle/lists/3",'id'=>$ids_group,"key"=>"manage_yhg",'type'=>3],
            ["name"=>$name4,"count"=>$count2?:0,'url'=>G_MODULE_PATH."/handle/lists/21",'id'=>$ids2,"key"=>"manage_yhk",'type'=>21],
            ["name"=>$name5,"count"=>$count3?:0,'url'=>G_MODULE_PATH."/handle/lists/22",'id'=>$ids3,"key"=>"manage_usdt",'type'=>22],
            ["name"=>$name6,"count"=>$count4?:0,'url'=>G_MODULE_PATH."/handle/lists/31",'id'=>$ids4,"key"=>"manage_ts",'type'=>31],
            ["name"=>$name7,"count"=>$count5?:0,'url'=>G_MODULE_PATH."/handle/lists/41,42",'id'=>$ids5,"key"=>"manage_cz",'type'=>41],
        ];
        $is_preferential = $this->getConfig("is_preferential")?:2;
        if($is_preferential == 2){
            unset($data[1]);
        }
        $is_group = $this->getConfig("is_group")?:2;
        if($is_group == 2){
            unset($data[2]);
        }
        return array_values($data);
    }
    /**
     * 获取审批，新的充值订单，新的待发货订单
     * @return void
     */
    public function getNotice(){
        $adminInfo = $this->AdminInfo;
        $uid = $adminInfo['uid'];
        if($adminInfo['mid']==1){
            $data = $this->getManageNotice();
            $ids = $this->db->getList("select uid from `@#_admin` where mid = 1");
        }else{
            $data = $this->getOperatNotice();
            $ids = $this->db->getList("select uid from `@#_admin` where mid <> 1");
        }
        $isnew = false;
        $time = time();
        foreach($data as $vl){
            $isnew = $this->isnew($vl['id'],$vl['key']);
            if($isnew){
                foreach($ids as $v){
                    $info = [
                        "uid"=>$v['uid'],
                        "type"=>$vl['type'],
                        "content"=>$vl['name'],
                        "time"=>$time,
                        "data"=>implode(",",$vl['id']),
                        "is_view"=>1,
                    ];
                    $sql = Assemblydata($info,"admin_handle_notice");
                    $this->db->Query($sql);
                }
                break;
            }
        }
        $res = $this->db->GetOne("select * from `@#_admin_handle_notice` where uid = $uid and is_view = 1");
        if($res){
            $this->db->Query("update `@#_admin_handle_notice` set is_view = 2,view_time = $time where uid = $uid and is_view = 1");
            $isnew = true;
        }else{
            $isnew = false;
        }
        echo json_encode(['code'=>200,"data"=>$data,"count"=>99,"is_new"=>$isnew]);
        die;
    }

    private function isnew($arr = [],$key=''){
        $old = $this->redis->redisGet($key);
        if(!$old['data']){
            $this->redis->redisSet($key,json_encode($arr));
            return true;
        }else{
            $this->redis->redisSet($key,json_encode($arr));
            $oldarr = json_decode($old['data']);
            $tem = array_diff($arr,$oldarr);
            if($tem){
                return true;
            }
        }
        return false;
    }

        //通知列表待定
    public function noticeList(){
        $adminInfo = $this->AdminInfo;
        $admin_type = $adminInfo['mid']==1?2:1;
        $wheres['admin_type'] = $admin_type;
        if($_POST['type']){
            $wheres['type'] = $_POST['type'];
        }
        $sql = Assemblydata("","admin_message","sel",$wheres);
        $list = $this->db->GetList($sql);
        // var_dump("sfsdfsdfsdf");
        var_dump($this->tpl(ROUTE_M,'notice.list'));
		include $this->tpl(ROUTE_M,'notice.list');
    }

    //处理通知，待定
    public function handleNotice(){
        $id = $_POST['id'];
        $sql = Assemblydata(['is_check'=>2,"check_time"=>time()],"admin_message","up",['id'=>$id]);
        $this->db->Query($sql);
        echo "ok";
    }

    public function certification(){
        // include_once "certification.action.php";
        // $certification = new certification();
        // $res = $certification->certificationinfo();
        // p($res);
    }

    private function statistics_now(){
        //当前积分,金额剩余
        $res = $this->db->GetOne("select sum(score) as sum_score,sum(money) as sum_money from `@#_member` where auto_user = 0");
        $data['now_score'] = $res['sum_score']?:0;
        $data['now_money'] = $res['sum_money']?:0;
        $res = $this->db->GetOne("select sum(total_money) as sum_total_money from `@#_member_commission`");
        $data['now_commission'] = $res['sum_total_money']?:0;
        $data['income'] = 0;
        return $data;
    }

    public function statistics($times=''){
        $t = false;
        if(!is_array($times)){
            $t = true;
            $times = $_POST['times'];
            if($times == 1){
                $times = get_today_time();
            }else{
                $times = get_before_time($times);
            }
        }
        //今日
        $time_s = $times[0];
        $time_e = $times[1];
        //注册赠送
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_rechange` where `mode` = 1 and (`time` > $time_s and time < $time_e)");
        $data['zs'] = $res['total_money']?:0;
        //今日积分增加
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_account` where pay = '积分' and `type` = 1 and `time` > $time_s and time < $time_e");
        $data['score_add'] = $res['total_money']?:0;
        // 今日推广返佣
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_recodes` where `type` = 1 and (`time` > $time_s and time < $time_e)");
        $data['com_tg'] = $res['total_money']?:0;
        // 今日三级返佣
        $data['com_sj'] = -1;
        // 今日用户取现
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_recodes` where `type` = -3 and (`time` > $time_s and time < $time_e)");
        $data['ts'] = $res['total_money']?:0;
        // 今日股东分红

        $data['fh'] = -1;
        // 今日余额使用
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_account` where `type` = -1 and `pay` = '账户' and (`time` > $time_s and time < $time_e)");
        $data['use_money'] = $res['total_money']?:0;
        // 今日用户充值
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_offline_money` where `create_time` > $time_s and `create_time` < $time_e and status = 2");
        $res1 = $this->db->GetOne("select sum(money) as total_money from `@#_member_addmoney_record` where `time` > $time_s and `time` < $time_e and status = '已付款'");
        $data['rechange'] = $res['total_money']?:0 + $res1['total_money']?:0;
        // 今日积分使用
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_account` where `type` = -1 and `pay` = '积分' and (`time` > $time_s and time < $time_e)");
        $data['use_score'] = $res['total_money']?:0;
        // 今日在线兑换
        $res = $this->db->GetOne("select sum(money) as total_money from `@#_member_go_record` where status = '已回收' and `time` > $time_s and `time` < $time_e");
        $data['exchange'] = $res['total_money']?:0;
        // 今日投资收入
        $data['invest_income'] = -1;
        // 当前商品总数
        $res = $this->db->GetOne("select count(*) as total_money from `@#_shoplist` ");
        $data['goods_total'] = $res['total_money']?:0;
        // 今日订单新增
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e");
        $res1 = $this->db->GetOne("select count(*) as total_money from `@#_orders` where `time` > $time_s and `time` < $time_e");
        $res2 = $this->db->GetOne("select count(*) as total_money from `@#_orders_group` where `time` > $time_s and `time` < $time_e");
        $data['orders_num'] = $res['total_money']?:0 + $res1['total_money']?:0 +$res2['total_money']?:0;
        // 今日发货订单
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status not in ('已付款,未发货,未完成','已回收')");
        $res1 = $this->db->GetOne("select count(*) as total_money from `@#_orders` where `time` > $time_s and `time` < $time_e and status > 2");
        $res2 = $this->db->GetOne("select count(*) as total_money from `@#_orders_group` where `time` > $time_s and `time` < $time_e and status > 2");
        $data['orders_fh_num'] = $res['total_money']?:0 + $res1['total_money']?:0 +$res2['total_money']?:0;
        // 今日待发订单
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status = '已付款,未发货,未完成'");
        $res1 = $this->db->GetOne("select count(*) as total_money from `@#_orders` where `time` > $time_s and `time` < $time_e and status = 2");
        $res2 = $this->db->GetOne("select count(*) as total_money from `@#_orders_group` where `time` > $time_s and `time` < $time_e and status = 2");
        $data['orders_df_num'] = $res['total_money']?:0 + $res1['total_money']?:0 +$res2['total_money']?:0;
        // 今日兑换订单
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status = '已回收'");
        $data['orders_exc_num'] = $res['total_money']?:0;
        // 平台用户总数
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0");
        $data['user_total'] = $res['total_money']?:0;
        // 今日新增人数
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0 and `time` > $time_s and `time` < $time_e");
        $data['user_num'] = $res['total_money']?:0;
        // 昨日新增人数
        $y_times = get_before_time(1);
        $time_s1 = $y_times[0];
        $time_e1 = $y_times[1];
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0 and `time` > $time_s1 and `time` < $time_e1");
        $data['yesterday_user_num'] = $res['total_money']?:0;
        // 本月新增人数
        $y_times = get_current_month_time();
        $time_s1 = $y_times[0];
        $time_e1 = $y_times[1];
        $res = $this->db->GetOne("select count(*) as total_money from `@#_member` where auto_user = 0 and `time` > $time_s1 and `time` < $time_e1");
        $data['month_user_num'] = $res['total_money']?:0;
        // 今日充值用户
        $res = $this->db->GetOne("select count(distinct uid) as total_money from `@#_offline_money` where `create_time` > $time_s and `create_time` < $time_e and status = 2");
        $res1 = $this->db->GetOne("select count(distinct uid) as total_money from `@#_member_addmoney_record` where `time` > $time_s and `time` < $time_e and status = '已付款'");
        $data['user_rechange_num'] = $res['total_money']?:0 + $res1['total_money']?:0;
        // 今日兑换人数
        $res = $this->db->GetOne("select count(distinct uid) as total_money from `@#_member_go_record` where `time` > $time_s and `time` < $time_e and status = '已回收'");
        $data['user_exc_num'] = $res['total_money']?:0;
        // 今日股东增加
        $data['user_invest_add'] = -1;
        // 今日股东退出
        
        $data['user_invest_quit'] = -1;
        if($t){
            $formot_array = ["now_money","now_commission",'income',"zs","com_tg","com_sj","ts","fh","use_money","rechange","exchange","invest_income"];
            data_format($data,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
            $this->jsonData->success('','',$data);
        }
        return $data;
    }

	public function Tdefault(){

        /* 获取磁盘 总容量 */
        $DIR_DATA['Z'] = disk_total_space('.');
        /* 获取磁盘剩余容量 */
        $DIR_DATA['S'] = disk_free_space('.');

        $DIR_DATA['Z_'] = $this->getSymbolByQuantity($DIR_DATA['Z']);
        $DIR_DATA['S_'] = $this->getSymbolByQuantity($DIR_DATA['S']);
		$info=$this->AdminInfo;
		$SysInfo=GetSysInfo();		
		$SysInfo['MysqlVersion']=$this->db->GetVersion();		
		$versions = System::load_sys_config("version");

        // 判断后台公告内容（优先数据库 getConfig，为空时用 _cfg 兜底，与 webcfg/setLogin 一致）
        $notices = [];
        // 谷歌验证码：未开启或未绑定时提示「强烈建议开启」
        $google_status = $this->getConfig('web_verify');
        if ($google_status === null || $google_status === '') $google_status = _cfg('web_verify');
        $google_bound = $this->getConfig('google_authenticator_bound');
        $gs = $google_status !== null && $google_status !== '' ? trim((string)$google_status) : '';
        $gb = $google_bound !== null && $google_bound !== '' ? trim((string)$google_bound) : '';
        if ($gs !== '1' || $gb !== '1') {
            $msg = $this->getLgMsg("Google_verification_code", "home");
            $notices[] = ($msg !== '' && $msg !== 'Google_verification_code') ? $msg : '强烈建议开启后台谷歌验证码！';
        }
        // 图片上传权限：1=开启 2=关闭。开启时提示「未关闭」以提醒注意
        $upload_status = $this->getConfig('is_upload');
        if ($upload_status === null || $upload_status === '') $upload_status = _cfg('is_upload');
        $us = $upload_status !== null && $upload_status !== '' ? trim((string)$upload_status) : '';
        if ($us === '1' || $upload_status === 1) {
            $msg = $this->getLgMsg("Image_permission", "home");
            $notices[] = ($msg !== '' && $msg !== 'Image_permission') ? $msg : '后台图片上传权限未关闭！';
        }
        // 服务器安全是否开启（0=不安全 1=完全，未开启时提示）
        $system_status = _cfg('system_status');
        if($system_status != 1) {
            $notices[] = '服务器安全未开启，请联系官方技术！';
        }
        // 异常商品数量
        $list = $this->db->GetList("SELECT `id` FROM `@#_shoplist` where `q_end_time` is not null and `q_showtime` = 'Y' AND ssc !=1");
        $count_shop = count($list);
        if($count_shop > 0) {
            // $notices[] = '有'.$count_shop.'个异常商品，请尽快处理！';
            $notices[] = $this->lgs['have'].$count_shop.$this->lgs['abnormal_product'];
        }
        //统计数据
        $statistics_now = $this->statistics_now();//当前
		// 总充值金额
        $addmoney_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        // 平台总提现金额
        $cashout_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_cashout` where auditstatus=1 ");

        // 线上总充值
        // $all_pay_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        // 线下总充值
        // $all_recharge_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        $stime = strtotime("today");
        $etime = strtotime("tomorrow") - 1;
        // 今日总消费
        $day_shop_money=$this->db->GetOne("select sum(moneycount) as allmoney from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status like %已付款%  and a.time <= $etime and a.time >= $stime a.huode !=0 and b.auto_user!=1");
        // 今日总提现
        $day_cashout_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_cashout` where auditstatus=1 and time <= $etime and time >= $stime");
        // 今日线上充值
        // $all_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        
        // 今日线下充值
        // $all_money = $this->db->GetOne("SELECT sum(money) as allmoney FROM `@#_member_addmoney_record` where status='已付款'");
        
        // 平台商品数 
        $all_shopnum=$this->db->GetOne("select count(*) as do_shopnum from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status like %已付款% and a.huode !=0 and b.auto_user!=1");
        // 今日订单数
        $day_shopnum=$this->db->GetOne("select count(*) as do_shopnum from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status like %已付款%  and a.time <= $etime and a.time >= $stime and a.huode !=0 and b.auto_user!=1");
        // 已发货订单
        $fa_shopnum=$this->db->GetOne("select count(*) as do_shopnum from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status like %已发货%  and a.huode !=0 and b.auto_user!=1");
        // 待发货订单
        // $dai_shopnum=$this->db->GetOne("select count(*) as do_shopnum from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status='已付款,未发货,未完成' and a.huode !=0 and b.auto_user!=1");
        
        // 平台总用户数 
		$usernum = $this->db->GetOne("select count(*) as usernum from `@#_member` where (`emailcode` = '1' or `mobilecode` = '1') and `auto_user` != 1 and level != 1 ");
        // 昨日新增用户
        $todaystime = strtotime("yesterday");
        $todayetime = strtotime("today") -1;
        $today_usernum = $this->db->GetOne("select count(*) as usernum from `@#_member` where (`emailcode` = '1' or `mobilecode` = '1') and `auto_user` != 1 and level != 1 and time <= $todayetime and time >= $todaystime");
        // 今日新增用户
        $day_usernum = $this->db->GetOne("select count(*) as usernum from `@#_member` where (`emailcode` = '1' or `mobilecode` = '1') and `auto_user` != 1 and level != 1 and time <= $etime and time >= $stime");
        // 今日交易用户
        
        
        // $moneytotal=$this->db->GetOne("select sum(moneycount) as moneytotal from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where  b.auto_user!=1");
		// $vipnum=$this->db->GetOne("select count(*) as vipnum from `@#_member` where (`emailcode` = '1' or `mobilecode` = '1') and `auto_user` != 1 and level = 1");
		$shopnum=$this->db->GetOne("select count(*) as shopnum from `@#_shoplist`");
		// $open_shopnum=$this->db->GetOne("select count(*) as open_shopnum from `@#_shoplist` where q_user_code is not null");
		// $do_shopnum=$this->db->GetOne("select count(*) as do_shopnum from `@#_member_go_record` a left join `@#_member` b on a.uid=b.uid where a.status='已付款,未发货,未完成' and a.huode !=0 and b.auto_user!=1");
		$banben_arr = explode(",",_encrypt(G_BANBEN_TYPE,"DECODE","G_BANBEN_TYPE"));
        $infos = $this->db->Getlist("SELECT `user_id`,`ip`, `city`,`login_time`,`region` FROM `@#_member_login_log` where `user_id` = '1' order by `login_time` desc limit 0,5");

		$data=$this->db->GetOne("SELECT FROM_UNIXTIME(time,'%d') days,SUM(money) datemoney FROM `@#_member_addmoney_record` where status='已付款' GROUP BY days order by datemoney desc LIMIT 1");
		$data1=$this->db->Getlist("SELECT FROM_UNIXTIME(time,'%d') days,SUM(money) datemoney FROM `@#_member_addmoney_record` where status='已付款' GROUP BY days LIMIT 30");
		$maxmoney=$data['datemoney']??0;

		if(!$maxmoney){
			$maxmoney=0;
		}
        $days = '';
        $daysmoney = "";
		foreach ($data1 as $key => $value) {
			$days.="".$value['days'].",";
			$daysmoney.="'".intval($value['datemoney'])."',";
		}
		$days=rtrim($days,',');
		if(!$days){
			$days="'1'";
		}
		$daysmoney=rtrim($daysmoney,',');
		if(!$daysmoney){
			$daysmoney="'00'";
		}
		$lowmoney=intval($maxmoney/5);
		if(!defined('G_BANBEN_NUMBER')){
			define('G_BANBEN_NUMBER',3);
			}
		$banben_num = G_BANBEN_NUMBER;
		
		if(isset($banben_arr[$banben_num])){
			$banben_txt = $banben_arr[$banben_num];
		}else{
			if(G_BANBEN_NUMBER == -1){
				$banben_txt  = base64_decode("5pyq5o6I5p2D");
			}else if(G_BANBEN_NUMBER == -2){
				$banben_txt  = base64_decode("5o6I5p2D5Yiw5pyf");
			}else{
				$banben_txt  = base64_decode("5pyq5o6I5p2D");
			}
			
		}		
		$text = $banben_txt;
        $time_zone = $this->getConfig("time_zone");
        $zone_lists = $this->getLanguageList(1);
        $zones = $this->getZones();
		//$catelen = $this->db->count();	
        $backend_copyright = $this->getConfig('backend_copyright');
        $tj_category = $this->db->GetOne("SELECT count(cateid) as count FROM `@#_category` WHERE `model` = '1'")['count']?:0;
        $tj_brand = $this->db->GetOne("SELECT count(id)  as count FROM `@#_brand`")['count']?:0;
        $tj_article = $this->db->GetOne("SELECT count(id)  as count FROM `@#_article`")['count']?:0;
        $tj_shoplist = $this->db->GetOne("SELECT count(id)  as count FROM `@#_shoplist`")['count']?:0;
        $time = time();
        // $tj_shoplist_xsjx = $this->db->GetOne("SELECT count(id) FROM `@#_shoplist` where `xsjx_time`>'$time'")['count']?:0;
        $tj_member = $this->db->GetOne("SELECT count(uid)  as count FROM `@#_member` ")['count']?:0;
        $tm = get_today_time();
        $tj_member_new = $this->db->GetOne("SELECT count(uid)  as count FROM `@#_member` where `time`>='$tm[0]' and `time`<= '$tm[1]'")['count']?:0;
        $tj_shoplist_new = $this->db->GetOne("SELECT count(id)  as count FROM `@#_shoplist` where `time`>='$tm[0]' and `time`<= '$tm[1]'")['count']?:0;
        $tj_member_account = $this->db->GetOne("SELECT sum(money) as total FROM `@#_member_account` where `pay`='账户' and `type`=1 and `time`>='$tm[0]' and `time`<= '$tm[1]'")['total']?:0;
		include $this->tpl(ROUTE_M,'admin.default');
	}

    public function getZones(){
        $aa = $this->getConfig("time_zone_index");
        $arr = mb_unserialize($aa);
        if(!$arr){
            return [];
        }
        $def = ["0"=>"Chinese","1"=>"English","2"=>"Indonesian","3"=>"Thai"];
        foreach ($def as $key => $value) {
            if($arr[$key]){
                $def[$key] = $arr[$key];
            }
        }
        $d1 = ["星期一","星期二","星期三","星期四","星期五","星期六","星期日"];
        foreach ($def as $key => $value) {
            $lg = $this->db->GetOne("select * from `@#_language` where `lg_value` = '$value'");
            $time =  new DateTime($lg['time_zone']);
            $zones[] = [
                "name"=>$lg['country'],
                "md"=>$time->format("m:d"),
                "hi"=>$time->format("H:i"),
                "s"=>$time->format("s"),
                "n"=>$this->lan($d1[$time->format("N")],'',2),
                "language"=>$value,
                "time_zone"=>$lg['time_zone'],
            ];
        }
        return $zones;
        $datetime_1 = new DateTime("Asia/Shanghai");
        $datetime_2 = new DateTime("America/New_York");
        $datetime_3 = new DateTime("Asia/Pontianak");
        $datetime_4 = new DateTime("Asia/Bangkok");
        $d1 = ["星期一","星期二","星期三","星期四","星期五","星期六","星期日"];
        $d2 = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
        $d3 = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];
        $d4 = ["วันจันทร์","วันอังคาร","วันพุธ","วันพฤหัสบดี","วันศุกร์","วันเสาร์","วันอาทิตย์"];
        $zones = [
            [
                "name"=>"北京时间",
                "md"=>$datetime_1->format("m:d"),
                "hi"=>$datetime_1->format("H:i"),
                "s"=>$datetime_1->format("s"),
                "n"=>$d1[$datetime_1->format("N")],
            ],
            [
                "name"=>"美国时间",
                "md"=>$datetime_2->format("m:d"),
                "hi"=>$datetime_2->format("H:i"),
                "s"=>$datetime_2->format("s"),
                "n"=>$d2[$datetime_2->format("N")],
            ],
            [
                "name"=>"印尼时间",
                "md"=>$datetime_3->format("m:d"),
                "hi"=>$datetime_3->format("H:i"),
                "s"=>$datetime_3->format("s"),
                "n"=>$d3[$datetime_3->format("N")],
            ],
            [
                "name"=>"泰国时间",
                "md"=>$datetime_4->format("m:d"),
                "hi"=>$datetime_4->format("H:i"),
                "s"=>$datetime_4->format("s"),
                "n"=>$d4[$datetime_4->format("N")],
            ],
        ];
        return $zones;
    }

    public function getZoneLists(){
        $lists = $this->getLanguageList(1);
        $this->jsonData->success('','',$lists);
    }

    private function report(){
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $start = mktime(0,0,0,$month,$day,$year);//当天开始时间戳
        $end= mktime(23,59,59,$month,$day,$year);//当天结束时间戳
        //赠送
        $give = $this->db->GetOne("select sum(`money`) as total from `@#_member_rechange` where time > $start and time < $end");
        // 积分
        $score = $this->db->GetOne("select sum(`money`) as total from `@#_member_account` where time > $start and time < $end");
        //佣金
        $commissions = $this->db->GetOne("select sum(`money`) as total from `@#_member_recodes` where `type` = 1 and time > $start and time < $end");
        //提现
        $cashout = $this->db->GetOne("select sum(`money`) as total from `@#_member_cashout` where `examine_status` = 2 and examine_time > $start and examine_time < $end");
        
        //使用余额
        $moeny1 = $this->db->GetOne("select sum(`money`) as total from `@#_member_go_record` where ime > $start and time < $end");
        $moeny2 = $this->db->GetOne("select sum(`money`) as total from `@#_ordres` where ime > $start and time < $end");
        $moeny3 = $this->db->GetOne("select sum(`money`) as total from `@#_orders_group` where ime > $start and time < $end");
        //充值
        $rechange = $this->db->GetOne("select sum(`money`) as total from `@#_member_rechange` where `status`='2' and time > $start and time < $end");
        // 积分使用
        $use_score1 = $this->db->GetOne("select sum(`point`) as total from `@#_member_go_record` where ime > $start and time < $end");
        $use_score2 = $this->db->GetOne("select sum(`point`) as total from `@#_ordres` where ime > $start and time < $end");
        $use_score3 = $this->db->GetOne("select sum(`point`) as total from `@#_orders_group` where ime > $start and time < $end");
        //在线兑换
        $exchange = $this->db->GetOne("select sum(`money`) as total from `@#_member_recodes` where `type` = -4 and time > $start and time < $end");

        //商品总数
        $shop = $this->db->GetOne("select count(*) as total from `@#_shoplist` ");
        // 订单
        
    }

	/*
	 * 首页 数据
	 */
	public function TdefaultData(){
        $date = strtotime(date('Y-m-d',strtotime("-7 day")).' 00:00:00');
        $smoneylist=$this->db->Getlist("SELECT FROM_UNIXTIME(time,'%Y-%m-%d') days,SUM(money) datemoney FROM `@#_member_addmoney_record` where time >= $date GROUP BY days");
        $userlist = $this->db->Getlist("SELECT FROM_UNIXTIME(time,'%Y-%m-%d') days,count(*) as num FROM `go_member` where auto_user = 0 AND mobilecode =1 AND  time >= $date GROUP BY days");
        $get_weekss = $this->get_weekss();
        $mday=array_column($smoneylist,'days');
        $mony=array_column($smoneylist,'datemoney');
        $m =array_combine($mday,$mony);
        $ms =[];
        foreach ($get_weekss as $v){
            if (!isset($m[$v])){
                $ms[]=["day"=>$v,'num'=>0];
            }else{
                $ms[]=["day"=>$v,'num'=>$m[$v]];
            }
        }
        $uday = array_column($userlist,'days');
        $unum = array_column($userlist,'num');
        $u =array_combine($uday,$unum);
        $us =[];
        foreach ($get_weekss as $v){
            if (!isset($u[$v])){
                $us[]=["day"=>$v,'num'=>0];
            }else{
                $us[]=["day"=>$v,'num'=>$u[$v]];
            }
        }
        $zu = [];
        foreach ($get_weekss as $v){
            $t = strtotime($v.' 23:59:59');
            $sql ="SELECT COUNT(*) as num FROM `@#_member` WHERE auto_user =0 AND mobilecode =1 AND time < $t";
            $coutn = $this->db->GetOne($sql);
            $zu[]=["day"=>$v,'num'=>$coutn['num']];
        }

        $data =[
            'code'=>1,
            'm'=>$ms,
            'u'=>$us,
            'zu'=>$zu
        ];
        echo json_encode($data);
        exit();
    }

    /**
     * 加密货币实时价格 - 优先 TaoLong API，失败则由服务端请求 Binance 备用
     * 前端只请求本接口，避免浏览器直连 Binance 被墙导致一直“连接中”
     */
    public function cryptoPrice(){
        header('Content-Type: application/json; charset=utf-8');
        $result = $this->fetchCryptoFromTaoLong();
        if ($result !== null) {
            echo json_encode(['code' => 1, 'source' => 'taolong', 'data' => $result]);
            exit();
        }
        $result = $this->fetchCryptoFromBinance();
        if ($result !== null) {
            echo json_encode(['code' => 1, 'source' => 'binance', 'data' => $result]);
            exit();
        }
        echo json_encode(['code' => 0, 'msg' => '暂无法获取行情']);
        exit();
    }

    /**
     * 测试单个数据源连接（韬龙 / Binance），用于连接失败时诊断
     * GET source=taolong|binance
     */
    public function testCryptoApi(){
        header('Content-Type: application/json; charset=utf-8');
        // $source = isset($_GET['source']) ? strtolower(trim($_GET['source'])) : '';
        // if ($source !== 'taolong' && $source !== 'binance') {
        //     echo json_encode(['code' => 0, 'ok' => false, 'msg' => '参数 source 须为 taolong 或 binance', 'source' => $source]);
        //     exit();
        // }
        $source = "taolong";
        if ($source === 'taolong') {
            $this->lastTaoLongHttpCode = 0;
            $result = $this->fetchCryptoFromTaoLong();
            if ($result !== null) {
                echo json_encode(['code' => 1, 'ok' => true, 'msg' => '韬龙 API 连接正常', 'source' => 'taolong']);
                exit();
            }
            if ($this->lastTaoLongHttpCode === 401) {
                echo json_encode(['code' => 0, 'ok' => false, 'msg' => '韬龙 API 返回 401 未授权，请在后台【系统配置】或 config 表中配置 taolong_crypto_api_key（韬龙 API Key）', 'source' => 'taolong']);
                exit();
            }
            echo json_encode(['code' => 0, 'ok' => false, 'msg' => '韬龙 API 连接失败或超时', 'source' => 'taolong']);
            exit();
        }
        $result = $this->fetchCryptoFromBinance();
        if ($result !== null) {
            echo json_encode(['code' => 1, 'ok' => true, 'msg' => 'Binance API 连接正常', 'source' => 'binance']);
            exit();
        }
        echo json_encode(['code' => 0, 'ok' => false, 'msg' => 'Binance API 连接失败或超时', 'source' => 'binance']);
        exit();
    }

    private function fetchCryptoFromTaoLong(){
        $apiKey = function_exists('_cfg') ? _cfg('taolong_crypto_api_key') : '';
        if(is_array($apiKey)){$apiKey = $apiKey['taolong_crypto_api_key']??"";}
        $url = 'https://api.taolong.com/api/crypto/ticker';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $headers = ['Accept: application/json'];
        if (!empty($apiKey)) $headers[] = 'Authorization: Bearer ' . trim($apiKey);
        if (!empty($apiKey)) $headers[] = 'x-api-key:' . trim($apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $raw = curl_exec($ch);
        $this->lastTaoLongHttpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($raw === false) return null;
        $json = json_decode($raw, true);
        if ($json === null) return null;
        $list = isset($json['data']) ? $json['data'] : (is_array($json) && isset($json[0]) ? $json : []);
        if (!is_array($list)) return null;
        return $this->parseTickerList($list);
    }

    private function fetchCryptoFromBinance(){
        $timeout = 5;
        $urlBtc = 'https://api.binance.com/api/v3/ticker/24hr?symbol=BTCUSDT';
        $urlEth = 'https://api.binance.com/api/v3/ticker/24hr?symbol=ETHUSDT';
        $chBtc = curl_init();
        curl_setopt($chBtc, CURLOPT_URL, $urlBtc);
        curl_setopt($chBtc, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chBtc, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($chBtc, CURLOPT_TIMEOUT, $timeout);
        $chEth = curl_init();
        curl_setopt($chEth, CURLOPT_URL, $urlEth);
        curl_setopt($chEth, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chEth, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($chEth, CURLOPT_TIMEOUT, $timeout);
        $mh = curl_multi_init();
        curl_multi_add_handle($mh, $chBtc);
        curl_multi_add_handle($mh, $chEth);
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            if ($running > 0) curl_multi_select($mh, 0.1);
        } while ($running > 0);
        $btcRaw = curl_multi_getcontent($chBtc);
        $ethRaw = curl_multi_getcontent($chEth);
        curl_multi_remove_handle($mh, $chBtc);
        curl_multi_remove_handle($mh, $chEth);
        curl_multi_close($mh);
        curl_close($chBtc);
        curl_close($chEth);
        if ($btcRaw === false || $ethRaw === false) return null;
        $btc = json_decode($btcRaw, true);
        $eth = json_decode($ethRaw, true);
        if (!is_array($btc) || !is_array($eth) || empty($btc['lastPrice']) || empty($eth['lastPrice'])) return null;
        return [
            'btc' => [
                'price'  => floatval($btc['lastPrice']),
                'change' => floatval(isset($btc['priceChangePercent']) ? $btc['priceChangePercent'] : 0),
                'volume' => floatval(isset($btc['volume']) ? $btc['volume'] : 0)
            ],
            'eth' => [
                'price'  => floatval($eth['lastPrice']),
                'change' => floatval(isset($eth['priceChangePercent']) ? $eth['priceChangePercent'] : 0),
                'volume' => floatval(isset($eth['volume']) ? $eth['volume'] : 0)
            ]
        ];
    }

    private function httpGet($url, $timeout = 5){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $raw = curl_exec($ch);
        curl_close($ch);
        return $raw;
    }

    private function parseTickerList($list){
        $btc = ['price' => 0, 'change' => 0, 'volume' => 0];
        $eth = ['price' => 0, 'change' => 0, 'volume' => 0];
        foreach ($list as $item) {
            if (!is_array($item)) continue;
            $sym = isset($item['symbol']) ? strtoupper($item['symbol']) : '';
            if ($sym === 'BTCUSDT' || $sym === 'BTC') {
                $btc['price'] = floatval(isset($item['last_price'])?$item['last_price']:(isset($item['lastPrice'])?$item['lastPrice']:(isset($item['price']) ? $item['price'] : 0)));
                // $btc['price']  = floatval(isset($item['lastPrice']) ? $item['lastPrice'] : (isset($item['price']) ? $item['price'] : 0));
                $btc['change'] = floatval(isset($item['price_change_percent']) ? $item['price_change_percent'] : (isset($item['priceChangePercent']) ? $item['priceChangePercent'] : (isset($item['changePercent']) ? $item['changePercent'] : 0)));
                $btc['volume'] = floatval(isset($item['volume']) ? $item['volume'] : 0);
            }
            if ($sym === 'ETHUSDT' || $sym === 'ETH') {
                // $eth['price']  = floatval(isset($item['lastPrice']) ? $item['lastPrice'] : (isset($item['price']) ? $item['price'] : 0));
                $eth['price'] = floatval(isset($item['last_price'])?$item['last_price']:(isset($item['lastPrice'])?$item['lastPrice']:(isset($item['price']) ? $item['price'] : 0)));
                // $eth['change'] = floatval(isset($item['priceChangePercent']) ? $item['priceChangePercent'] : (isset($item['changePercent']) ? $item['changePercent'] : 0));
                $eth['change'] = floatval(isset($item['price_change_percent']) ? $item['price_change_percent'] : (isset($item['priceChangePercent']) ? $item['priceChangePercent'] : (isset($item['changePercent']) ? $item['changePercent'] : 0)));
                $eth['volume'] = floatval(isset($item['volume']) ? $item['volume'] : 0);
            }
        }
        if ($btc['price'] <= 0 && $eth['price'] <= 0) return null;
        return ['btc' => $btc, 'eth' => $eth];
    }

    /**
     * 获取本周所有日期
     */
    public function get_weekss($time = '', $format='Y-m-d'){
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i=1; $i<=7; $i++){
            $d = $i-7;
            $date[$i] = date($format ,strtotime( "+{$d} days", $time));
        }
        return $date;
    }

    private function getSymbolByQuantity($bytes) {
        $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $exp = floor(log($bytes)/log(1024));
        return sprintf('%.2f '.$symbol[$exp], ($bytes/pow(1024, floor($exp))));
    }
	public function map(){			
		$info=$this->AdminInfo;
		include $this->tpl(ROUTE_M,'admin.map');
	}
	public function support(){
		include $this->tpl(ROUTE_M,'admin.support');
		
	}

    public function image(){

        $style = $this->segment(4);

        $style = explode("_",$style);

        $width = isset($style[0]) ? intval($style[0]) : '';

        $height = isset($style[1]) ? intval($style[1]) : '';

        $color = isset($style[2]) ? $style[2] : '';

        $bgcolor = isset($style[3]) ? $style[3] : '';

        $lenght = isset($style[4]) ? intval($style[4]) : '';

        $type = isset($style[5]) ? intval($style[5]) : '';

        $checkcode=System::load_app_class("checkcodeimg");

        $checkcode->config($width,$height,$color,$bgcolor,$lenght,$type);

        $checkcode->dian(100,$color);

        _setcookie("checkcode",md5(strtolower($checkcode->code)));

        $checkcode->image();
    }
}

?>