<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_sys_fun('redisData');
 
class database extends admin{

    public $con = null;
    public $configs = null;
    public $db =null;
    public $selsq = null;
    public $Redis = null;
    //私有属性，用于保存实例
    private static $instance;
    //构造方法私有化，防止外部创建实例
    public function __construct(){
        $this->Redis=  new redisData();
        $config = System::load_sys_config('database');
       
        $this->configs =$config;
        $config = $config['default'];
        $servername = $config['hostname'];
        $username = $config['username'];
        $password = $config['password'];
        $dbname = "information_schema";
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        try {
            $this->con = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password,$options);
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            echo "<br><h1 style='text-align: center;line-height: 50px'>数据库连接失败请检查</h1>";
            exit();
        }
        $this->db = System::load_sys_class('model');
        parent::__construct();
        parent::__destruct();
    }

 

    /**
     * 数据优化 详情页
     * @return bool|mixed|null
     */
    public function Datalist()
    {
        include $this->tpl(ROUTE_M,'config.databaselist');
    }

    //数据优化主页
    public function index(){
        $sqldata = $this->db->GetList("select * from `@#_sqldel` where type = 'sql' GROUP BY uuid");
        $delnum = $this->Redis->redisGet('mysqldelnum_key')['data'];
        $this->ment =array(
            array("index","数据清理",ROUTE_M.'/'.ROUTE_C."/index")
        );
        include $this->tpl(ROUTE_M,'config.database');

    }

    /**
     * @获取删除数据列表
     */
    public function getDelData()
    {
        $item = $this->db->GetList("select * from `@#_sqldel` where type = 'sql' GROUP BY uuid");
        foreach ($item as $k=>$v){
            $item[$k]['isaddtime'] = empty($v['isaddtime'])?'':date('Y-m-d H:i:s',$v['isaddtime']);
            $item[$k]['deltime'] = empty($v['deltime'])?'':date('Y-m-d H:i:s',$v['deltime']);
            $item[$k]['oktime'] = empty($v['oktime'])?'':date('Y-m-d H:i:s',$v['oktime']);
        }

        $sqldata =[];
        $sqldata['code'] =0;
        $sqldata['data']=$item;
        $sqldata['count'] = count($item);
        $sqldata['msg']='';
        $data = json_encode($sqldata);
        echo $data;
    }

    /*
     * 获取主页信息
     * */
    public function getBbInfo()
    {

        try{
            $retuendata=[];
            $dbname = $this->configs['default']['database'];
            $tablepre  = $this->configs['default']['tablepre'];

            $sql = "SELECT table_schema, sum( data_length + index_length ) / 1024 / 1024 `size` FROM TABLES WHERE TABLE_SCHEMA = '{$dbname}' ";
            $res = $this->con->query($sql);
            $retuendata['bdzsize'] = $res->fetch();
            $sql = "SELECT `TABLE_NAME` as 'tab',DATA_LENGTH as 'a',TABLE_COMMENT as 'tabname',INDEX_LENGTH  as 'b' FROM TABLES  WHERE TABLE_SCHEMA = '{$dbname}'
 AND ( `TABLE_NAME` = '{$tablepre}member_account' OR  `TABLE_NAME` = '{$tablepre}member_go_record' OR  `TABLE_NAME` = '{$tablepre}shoplist_copy'  OR  `TABLE_NAME` = '{$tablepre}member_addmoney_record' 
  OR  `TABLE_NAME` = '{$tablepre}member_cashout' OR  `TABLE_NAME` = '{$tablepre}member_rechange' )";

            $st = $this->con->query($sql);
            $tablesize= $st->fetchAll();

            foreach ( $tablesize as $key => $value){
                $num = $value['a'] + $value['b'];
                $tbrl =  $num/1024/1024;
                $tablesize[$key]['size'] =$this->getSymbolByQuantity($num);
                $tablesize[$key]['percent'] =round($tbrl/$retuendata['bdzsize']['size'],3)*100 ;
            }
            $retuendata['tbsize'] = $tablesize;

            /*数据库占取硬盘容量百分比*/

            /* 获取磁盘 总容量 */
            $DIR_DATA['Z'] = disk_total_space('.');
            /* 获取磁盘剩余容量 */
            $DIR_DATA['S'] = disk_free_space('.');
            $DIR_DATA['Z_'] = $this->getSymbolByQuantity($DIR_DATA['Z']);
            $DIR_DATA['S_'] = $this->getSymbolByQuantity($DIR_DATA['S']);
            $DIR_DATA['Y_'] = $this->getSymbolByQuantity($DIR_DATA['Z']-$DIR_DATA['S']);
            $DIR_DATA['BFB'] =(1-round($DIR_DATA['S']/ $DIR_DATA['Z'],2))*100;

            $member_account = $this->db->GetOne('SELECT `time` FROM `@#_member_account` ORDER BY time asc'); //会员充值记录
            $member_go_record = $this->db->GetOne('SELECT `time` FROM `@#_member_go_record` ORDER BY time asc'); //购买记录
            $shoplist_copy = $this->db->GetOne('SELECT `q_end_time` as time FROM `@#_shoplist_copy` ORDER BY `q_end_time` asc'); //历史开奖记录
            $addmoney = $this->db->GetOne("SELECT `time` FROM `@#_member_addmoney_record` ORDER BY time asc");//消费记录
            $tixian = $this->db->GetOne("SELECT `time` FROM `@#_member_cashout` ORDER BY time asc");//提现记录
            $chongzhi = $this->db->GetOne("SELECT `time` FROM `@#_member_rechange` WHERE `content` like '%充值%' ORDER BY time asc");//充值奖励

            $tabcx = array(
                'go_member_account'        =>$member_account, //会员充值
                'go_member_go_record'       =>$member_go_record, //
                'go_shoplist_copy'  =>$shoplist_copy,//
                'go_member_addmoney_record'       =>$addmoney,
                'go_member_cashout'    =>$tixian,
                'go_member_rechange'      =>$chongzhi
            );
            foreach ($tabcx as $k=>$v){
                if($v){
                    $tabcx[$k]['time'] = date("Y-m-d H:i:s",$v['time']);
                }
            }
            $retuendata['tab'] = $tabcx;
            $retuendata['dir'] =$DIR_DATA;
            $retuendata['http']=$this->httpSta();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $this->success(1,'OK',$retuendata);
    }

    /**
     * 提交清理数据类型
     */
    public function sbmitclean()
    {
        $time = trim($_POST['time']);
        $time = strtotime($time);

        $tablb = $_POST['table'];
        if(empty($time)){
            $this->success(0,'清除时间不能为空');
        }
        if($time > time()){
            $this->success(0,'清除时间不能大于当前时间');
        }
        if(empty(implode(',',$tablb))){
            $this->success(0,'请选择需要清理的数据表');
        }

        $delsqlarr=[];
        $tablepre = $dbname = $this->configs['default']['tablepre'];
        foreach($tablb as $key=>$value){
            $str = explode(',',$value);
            $value = $str[1];
            if($value == 'go_shoplist_copy'){
                $sql = "SELECT q_end_time FROM `@#_shoplist_copy` WHERE `q_end_time` <  $time LIMIT 100";
                $conut = $this->db->GetList($sql);
                if(!empty($conut)){
                    $delsql ="DELETE FROM `{$tablepre}shoplist_copy` WHERE `q_end_time` <  $time LIMIT 5000";
                    $delar = array($str[0],$delsql);
                    array_push($delsqlarr,$delar);
                }
            }
            if($value =='go_member_account'){
                $sql = "SELECT `time` FROM `@#_member_account` WHERE `time` < $time LIMIT 100";
                $conut = $this->db->GetList($sql);
                if(!empty($conut)){
                    $delsql ="DELETE FROM `{$tablepre}member_account` WHERE `time`< $time LIMIT 5000";
                    $delar = array($str[0],$delsql);
                    array_push($delsqlarr,$delar);
                }
            }
            if($value=='go_member_go_record'){
                $sql = "SELECT `time` FROM `@#_member_go_record` WHERE `time`< $time LIMIT 100";
                $conut = $this->db->GetList($sql);
                if(!empty($conut)){
                    $delsql ="DELETE FROM `{$tablepre}member_go_record` WHERE `time`< $time LIMIT 5000";
                    $delar = array($str[0],$delsql);
                    array_push($delsqlarr,$delar);
                }
            }
            if($value=='go_member_addmoney_record'){
                $sql = "SELECT `time` FROM `@#_member_addmoney_record` WHERE `time` < $time LIMIT 100";
                $conut = $this->db->GetList($sql);
                if(!empty($conut)){
                    $delsql ="DELETE FROM `{$tablepre}member_addmoney_record` WHERE `time`< $time LIMIT 5000";
                    $delar = array($str[0],$delsql);
                    array_push($delsqlarr,$delar);
                }
            }
            if($value=='go_member_cashout'){
                $sql = "SELECT `time` FROM `@#_member_cashout` WHERE `time` < $time LIMIT 100";
                $conut = $this->db->GetList($sql);
                if(!empty($conut)){
                    $delsql ="DELETE FROM `{$tablepre}member_cashout` WHERE `time` < $time LIMIT 5000";
                    $delar = array($str[0],$delsql);
                    array_push($delsqlarr,$delar);
                }
            }
            if($value=='go_member_rechange'){
                $sql = "SELECT `time` FROM `@#_member_rechange` WHERE `time`< $time LIMIT 100";
                $conut = $this->db->GetList($sql);
                if(!empty($conut)){
                    $delsql ="DELETE FROM `{$tablepre}member_rechange` WHERE `time`< $time LIMIT 5000";
                    $delar = array($str[0],$delsql);
                    array_push($delsqlarr,$delar);
                }
            }
        }

        if(empty($delsqlarr)){
            $this->success(0,'暂无数据清理',$delsqlarr);
        }
        //任务加入数据库
        $it = time();
        $uuid = $this->gen_uuid();
        $row=[];
        foreach ($delsqlarr as $key=>$item){
            $sql = $item[1];
            $title = $item[0];
            $sql = "INSERT INTO `@#_sqldel` (`isaddtime`, `deltime`, `cont`, `title`,`type`,`uuid`,`sort`,`deltit`) VALUES ('$it', '$time', '$sql', '清理数据库','sql','$uuid',$key,'$title')";
            $res=$this->db->Query($sql);
            array_push($row,$res);
        }
        if(count($row) != count($delsqlarr)){
            $this->success(0,'操作失败');
        }
        $re =  $this->opendel($uuid);
        if($re){
            $this->success(1,'操作成功',$row);
        }else{
            $this->success(0,'启动失败 进程启动失败',$uuid);
        }
    }

    public function tabdel(){
        $id     = $_POST['id'];
        $uuid   = $_POST['uuid'];
        $sql = "DELETE FROM `@#_sqldel` WHERE `uuid` = '$uuid'";
        $res = $this->db->Query($sql);
        if($res){
            $this->Redis->redisDel('List_'.$uuid);
            $this->Redis->rediszDelete('del_sql_uuid',$uuid);
            $this->success(1,'成功');
        }
        $this->success(0,'删除失败',$sql);
    }

    /**
    修改状态 API
     */
    public function stop(){
        $uid = $_POST['uuid'];
        $state = $_POST['state'];
        if (empty($uid)){
            $this->success(0,'请求错误');
        }
        if (empty($state)){
            $this->success(0,'参数错误');
        }
        $res = $this->opendel($uid,$state);
        if ($res){
            $this->success(1,'修改成功',$res);
        }
        $this->success(0,'操作失败',$res);
    }

    /**
    修改状态
     */
    private function  opendel($uid,$state=2){
        $time = 2*5;
        $this->db->Autocommit_start();
        $sql = "UPDATE `@#_sqldel` SET `state` = '$state' WHERE `uuid` = '$uid'";
        $upres =  $this->db->Query($sql);
        if($upres){
            if($state==1){
                $this->Redis->redisDel('List_'.$uid);
                $this->Redis->rediszDelete('del_sql_uuid',$uid);
            }else{
                $deldb = $this->db->GetList("SELECT * FROM `@#_sqldel` WHERE `uuid` = '$uid'");
                $value = json_encode($deldb);
                $this->Redis->redisSet('List_'.$uid,$value);
                $this->Redis->redisSadd('del_sql_uuid',$uid);
                /**
                 * 准备数据验证签名
                 * 请勿更改  $signkey 会导致 投递任务不成功
                 */
                $signkey = 'REDACTED_CONFIGURE_LOCALLY';
                $array  = array(
                    'uuid'        => $uid,
                    'time'      => time(),
                    'key'       => md5(time()),
                    'deltiem'   => $time
                );
                $signKey = http_build_query($array).$signkey;
                $sign = hash("sha256", $signKey);
                $array['salt'] = $sign;
                $url = "http://localhost:8080/api/postdata/del";
                //投递消费 删除数据任务
                $postres = $this->postHttp($url,$array);
                $postres= json_decode($postres,true);
                if($postres['code']==0){
                    $this->Redis->rediszDelete('del_sql_uuid',$uid);
                    $this->Redis->redisDel('List_'.$uid);
                    $this->db->Autocommit_rollback();
                    return false;
                }
            }
            $this->db->Autocommit_commit();
            return true;
        }else{
            $this->db->Autocommit_rollback();
            return false;
        }
    }

    /**
     * 查看删除服务是否正常
     */
    private function httpSta(){
        try {
            $url = "http://localhost:8080/api/postdata/getStatus";
            //投递消费 删除数据任务
            $postres = $this->postHttp($url,['data'=>444]);

        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $postres?1:0;
    }
    /**
     *  请求接口
     */
    public function postHttp($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$postUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        $error_no = curl_errno($ch);
        if($error || $error_no){
            var_export($error);
            var_export($error_no);
        }   
        curl_close($ch);
        return $data;
    }

    public function postHttp2($url,$data){
        $ch = curl_init();//新建curl
        curl_setopt($ch, CURLOPT_URL, $url);//url
        curl_setopt($ch, CURLOPT_POST, 1);  //post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//post内容
        $res =  curl_exec($ch); //输出
        $arr= json_decode($res,true);
        curl_close($ch);
        return $arr;
    }

    /*
     * 生成UUId
     * */
    private function gen_uuid() {
        $uuid = array(
            'time_low'  => 0,
            'time_mid'  => 0,
            'time_hi'  => 0,
            'clock_seq_hi' => 0,
            'clock_seq_low' => 0,
            'node'   => array()
        );
        $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
        $uuid['time_mid'] = mt_rand(0, 0xffff);
        $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
        $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
        $uuid['clock_seq_low'] = mt_rand(0, 255);

        for ($i = 0; $i < 6; $i++) {
            $uuid['node'][$i] = mt_rand(0, 255);
        }
        $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
            $uuid['time_low'],
            $uuid['time_mid'],
            $uuid['time_hi'],
            $uuid['clock_seq_hi'],
            $uuid['clock_seq_low'],
            $uuid['node'][0],
            $uuid['node'][1],
            $uuid['node'][2],
            $uuid['node'][3],
            $uuid['node'][4],
            $uuid['node'][5]
        );
        return $uuid;
    }

    private function getSymbolByQuantity($bytes) {
        $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $exp = floor(log($bytes)/log(1024));
        return sprintf('%.2f '.$symbol[$exp], ($bytes/pow(1024, floor($exp))));
    }

    private function success($code = 1,$msg = '',$data=''){
        $data = array('code'=>$code,'msg'=>$msg,'data' =>$data);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function get_database(){
        $dbname = $this->configs['default']['database'];
        $tablepre  = $this->configs['default']['tablepre'];
        $sql = "SELECT table_schema, sum( data_length + index_length ) / 1024 / 1024 `size` FROM TABLES WHERE TABLE_SCHEMA = '{$dbname}' ";
        $res = $this->con->query($sql);
        $retuendata['bdzsize'] = $res->fetch();
        /*数据库占取硬盘容量百分比*/
        /* 获取磁盘 总容量 */
        $DIR_DATA['Z'] = disk_total_space('.');
        /* 获取磁盘剩余容量 */
        $DIR_DATA['S'] = disk_free_space('.');
        $DIR_DATA['Z_'] = $this->getSymbolByQuantity($DIR_DATA['Z']);
        $DIR_DATA['S_'] = $this->getSymbolByQuantity($DIR_DATA['S']);
        $DIR_DATA['Y_'] = $this->getSymbolByQuantity($DIR_DATA['Z']-$DIR_DATA['S']);
        $DIR_DATA['BFB'] =(1-round($DIR_DATA['S']/ $DIR_DATA['Z'],2))*100;
        $retuendata['dir'] =$DIR_DATA;
        $retuendata['http']=$this->httpSta();
        return $this->success(200,'获取成功',$retuendata);
    }

    /**
     * 数据表管理
     *
     * @return void
     */
    public function table_list(){
        //获取数据库 表列表
        $this->ment = [
            array("index","数据清理",ROUTE_M.'/'.ROUTE_C."/table_list")
        ];
        $delnum = $this->Redis->redisGet('mysqldelnum_key')['data'];
        $task_count = $this->db->GetOne("select count(distinct uuid) as count from `@#_sqldel` where type = 'sql' group by uuid");
        if(!$task_count)$task_count['count'] = 0;
        $dbname = $this->configs['default']['database'];
        $tablepre = $this->configs['default']['tablepre'];
        $p = getParams("p",1);
        $num = 50;
        $start = ($p-1)*$num;
        // 获取所有表，按大小从大到小排序，并过滤小于2M的表
        $sql_all = "select table_name as name,table_comment as comment,(data_length + index_length) AS `size`
        from information_schema.tables 
        where table_schema = '{$dbname}' 
        and table_name not in ('{$tablepre}admin','{$tablepre}ments')
        and (data_length + index_length) >= 2097152
        order by (data_length + index_length) desc";
        $all_tables = $this->db->getAll($sql_all);
        
        // 计算总数（过滤后）
        $total = count($all_tables);
        $page=System::load_sys_class('page');	
        $page->config($total,$num,$p,"0"); 
		if($p>$page->page){
			$p=$page->page;
		}
        
        // 分页处理
        $tables = array_slice($all_tables, $start, $num);
        
        $sql = "SELECT table_schema, sum( data_length + index_length ) / 1024 / 1024 `size` FROM TABLES WHERE TABLE_SCHEMA = '{$dbname}' ";
        $res = $this->con->query($sql);
        $retuendata['bdzsize'] = $res->fetch();
        foreach($tables as $key=>$val){
            $fields = $this->db->GetList("select COLUMN_NAME as filed_name from information_schema.columns where table_schema = '{$dbname}' and table_name = '{$val['name']}'");
            $fileds = array_column($fields,'filed_name');
            $tables[$key]['time'] = "/";
            $ifileds = array('time','create_time',"add_time");
            foreach($ifileds as $k=>$v){
                if(in_array($v,$fileds)){
                    $info = $this->db->GetOne("select $v from {$val['name']} order by time desc limit 1");
                    if($info){
                        $tables[$key]['time'] = is_numeric($info[$v])?date('Y-m-d H:i:s',$info[$v]):$info[$v];
                    }else{
                        $tables[$key]['time'] = "暂无记录";
                    }
                    break;
                }
            }
            $tables[$key]['name'] = str_replace($tablepre,'',$val['name']);
            $tables[$key]['size'] =$this->getSymbolByQuantity($val['size']);
            $tbrl = $val['size']/1024/1024;
            $tables[$key]['percent'] =round($tbrl/$retuendata['bdzsize']['size'],3)*100 ;
        }

        include $this->tpl(ROUTE_M,'database.table');
    }

    /**
     * 数据表详情
     */
    public function table_detail($val=''){
        $table = $_POST['table']?:$val;
        $fields = $this->db->getAll("SHOW FULL FIELDS FROM $table");
        // echo "SHOW FULL FIELDS FROM $table";
        if($val){
            return $fields;
        }
        return $this->success(200,'success',$fields);
        // include $this->tpl(ROUTE_M,'config.database.detail');
    }

    /**
     * 数据表添加
     * 
     * */
    public function table_add(){

    }

    /**
     * 数据表修改
     */
    public function table_edit(){

    }

    /**
     * 数据表删除
     * 
     * */
    public function table_del(){

    }

    /**
     * 清除数据
     *
     * @return void
     */
    public function table_clean(){
        $time = trim($_POST['time']);
        $time = strtotime($time);
        $tablb = $_POST['table'];
        if(empty($time)){
            $this->success(0,'清除时间不能为空');
        }
        if($time > time()){
            $this->success(0,'清除时间不能大于当前时间');
        }
        if(empty(implode(',',$tablb))){
            $this->success(0,'请选择需要清理的数据表');
        }
        $delsqlarr=[];
        $tablepre = $dbname = $this->configs['default']['tablepre'];
        foreach($tablb as $key=>$value){
            $tablename = $tablepre.$value;
            $field = "time";
            $fields = $this->db->GetList("select COLUMN_NAME as filed_name from information_schema.columns where table_name = '{$dbname}{$value}'");
            $fields = array_column($fields, 'filed_name');
            if(in_array('time',$fields)){
                switch($value){
                    case 'shoplist_copy':
                        $field = "q_end_time";
                        break;
                }   
                $delsql = "DELETE FROM `{$tablename}` WHERE `$field`< $time LIMIT 5000";
            }elseif(in_array('create_time',$fields)){
                $field  = "create_time";    
                $delsql = "DELETE FROM `{$tablename}` WHERE `$field`< $time LIMIT 5000";
            }else{
                $delsql = "DELETE FROM `{$tablename}` LIMIT 5000";
            }
            $delar = array($tablename,$delsql);
            array_push($delsqlarr,$delar);
        }
        if(empty($delsqlarr)){
            $this->success(0,'暂无数据清理',$delsqlarr);
        }
        //任务加入数据库
        $it = time();
        $uuid = $this->gen_uuid();
        $row=[];
        foreach ($delsqlarr as $key=>$item){
            $sql = $item[1];
            $title = $item[0];
            $sql = "INSERT INTO `@#_sqldel` (`isaddtime`, `deltime`, `cont`, `title`,`type`,`uuid`,`sort`,`deltit`) VALUES ('$it', '$time', '$sql', '清理数据库','sql','$uuid',$key,'$title')";
            $res=$this->db->Query($sql);
            array_push($row,$res);
        }
        if(count($row) != count($delsqlarr)){
            $this->success(0,'操作失败');
        }
        $re =  $this->opendel($uuid);
        if($re){
            $this->success(1,'操作成功',$row);
        }else{
            $this->success(0,'启动失败 进程启动失败',$uuid);
        }
    }

    
}