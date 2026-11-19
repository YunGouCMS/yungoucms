<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','app','no');
class index extends base
{

    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();
    }

    // 获取系统基本配置
    public function init()  {
        $config = System::load_sys_config('system');
        $data['config']['login_facebook_account'] = $config['login_facebook_status']==2?'':$config['login_facebook_account']; // facebook授权登陆配置 - ID
        // $data['config']['login_facebook_key'] = $config['login_facebook_key']; // facebook授权登陆配置 - key
        $data['config']['login_google_account'] = $config['login_google_status']==2?'':$config['login_google_account']; // 谷歌授权登陆配置 - ID
        $data['config']['web_language'] = $config['web_language'];
        $data['config']['login_google_key'] = $config['login_google_key']; // 谷歌授权登陆配置 - key
        $data['config']['web_des'] = $config['web_des']; // 网站描述
        $data['config']['web_name'] = $config['web_name']; // 网站名称

        //边距
        $data["margin"]['margin_bottom'] = $config['margin_bottom'];
        $data["margin"]['margin_top'] = $config['margin_top'];

        $cfg = $this->db->GetList("select * from `@#_config` where 1");
        $cfg_data = array_column($cfg,'value', 'name');
        $data['config']['sys_message'] = $cfg_data['sys_message']; // 站内信开启状态 
        
        $host = G_HTTP_HOST;
        $codes = $this->db->GetList("select * from `@#_promotion_code` where `host` = :host", array('type'=>1,'key'=>''), array('host'=>$host));
        foreach ($codes as $code){
            $data["codes"][$code['type']] = $code['value'];
            $data[$code['type']] = $code['value'];
        }
        //货币单位
        $currency = $this->currency;
        $data['currency'] = $currency;
        $data['datetime'] = date('Y-m-d H:i:s');//服务器时间
        $data['time_zone'] = $this->time_zone;
        $this->jsonData->success('','',$data);
    }

    public function get_sound_effects(){
        $lists = $this->db->GetList("select * from `@#_sound_effects` where status = 1");
        foreach ($lists as $key => $value) {
            $lists[$key]['url'] = G_UPLOAD_PATH.$value['url'];
        }
        $this->jsonData->success('','',['lists'=>$lists]);
    }
    
    public function test(){
        $msg = $_POST['msg'];
        $this->jsonData->success('',$msg,['lists'=>1]);

    }

    /**
     * 版权信息
     *
     * @return void
     */
    public function copyrightInfo(){
        $res = $this->db->GetOne("select * from `@#_config` where `name` = 'web_copyright'");
        if(!$res){
            $res["value"] = "";
        }
        $this->jsonData->success('','',["info"=>$res['value']]);
    }

    //首页
    public function index(){
        $config = System::load_sys_config('system');
        //获取广告
        $shop_ad = $this->db->GetList("select * from `@#_wap` where  `type` = 0 and module = 1 and status=1 order by sort asc");
        //商品列表
        $shoplist1 = $this->db->Getlist("SELECT * FROM `@#_shoplist` where `online` ='1' AND `quality` = '0'  ORDER BY `order` DESC LIMIT 14");
        $arra = [];
        foreach($shoplist1 as $k=>$v){
            $arra[$k]['id'] = $v['id'];
            $arra[$k]['img'] = G_UPLOAD_PATH.'/'.$v['thumb'];
            $arra[$k]['title'] = $v['title'];
            $arra[$k]['integral'] = $v['s_score'];
             // $//去掉小数点后的.00,不为00的不去除
            if(strstr($v['money'],'.')){
                $moneyArr=explode('.',$v['money']);
                 if($moneyArr[1] == '00'){
                    $arra[$k]['money']=$moneyArr[0];
                }else{
                    $arra[$k]['money']=$v['money'];
                }
            }else{
                $arra[$k]['money']=$v['money'];
            }
            //$money=(int)$arra[$k]['money'];
            // $arra[$k]['money']=(string)$money;
            $arra[$k]['sales'] = strval($v['sales']+$v['fictitious']);
        }
        foreach($shop_ad as $k=>&$v){
            $v['img'] = G_UPLOAD_PATH.'/'.$v['img'];
            if(empty($v['link']) || $v['link'] == '#'){
                $v['link'] = '';
            }else{
                $v['link'] = G_WEB_PATH.'/'.$v['link'];
            }
        }
        $sql['name'] = '';
//        $sql['img'] = $this->db->GetOne("SELECT `link` FROM `@#_wap` where `title` ='联系客服' AND `status` = 1")['link'];
        $sql['img'] = G_UPLOAD_PATH.'/' . $this->db->GetOne("SELECT `img` FROM `@#_wap` where `id` ='6' AND `status` = 1")['img'];

        $data = [
            'time'=>(int)$config['disclosure_time'],
            'banner' => $shop_ad,
            'shop_list'=>$arra,
            'kefu'=>$sql,
        ];

        if($data){
            $data['gab3'] = System::load_sys_config('dataconfi')['gab3'];
            $this->jsonData->success('','成功',$data);
        }
    }

    /**
     * 导航栏
     * @return void
     */
    public function jifen_navigation(){
        $shop_ads = $this->db->GetList("select * from `@#_wap` where `type`=1 and module = 1 and `status` = 1 AND model = 2 AND (`language` = '$this->nowUserLanguage' or `language` = '') order by sort asc, id asc");
        if($shop_ads){
            foreach($shop_ads as &$vl){
                $vl['img'] = G_UPLOAD_PATH.'/'.$vl['img'];
            }
            $this->jsonData->success('','',$shop_ads);
        }else{
            $this->jsonData->error('','暂无数据');
        }
    }
    /**
     * 底部栏
     * @return void
     */
    public function bottom_bar(){
        $shop_ads = $this->db->GetList("select * from `@#_wap` where module = 2 and `status` = 1 AND (`language` = '$this->nowUserLanguage' or `language` = '' or `language` is null) order by sort asc, id asc");
        if($shop_ads){
            $lgs = $this->languages('footer');
            $arr = [
                    "/pages/indexs/index"=>"Home",
                    "/pages/glist/index"=>"Goods",
                    "/pages/cart/index"=>"cart",
                    "/pages/glist/userindex/index"=>"participated",
                    "/pages/latest_winning/index"=>"Unveiled",
                    "/pages/users/index"=>"My",
                ];
            foreach($shop_ads as &$vl){
                $vl['img'] = G_UPLOAD_PATH.'/'.$vl['img'];
                $vl['img1'] = G_UPLOAD_PATH.'/'.$vl['picarr'];
                $vl['title'] = $lgs[$arr[$vl['link']]];
            }
            $this->jsonData->success('','',$shop_ads);
        }else{
            $this->jsonData->error('','暂无数据');
        }
    }

    /**
     * 
     * 轮播图
     * @return void
     */
    public function jifen_banner(){
        $shop_ads = $this->db->GetList("select * from `@#_wap` where `type`=0 and module = 1 and `status` = 1 AND model = 2 AND (`language` = '$this->nowUserLanguage' or `language` = '') order by sort asc, id asc");
        if($shop_ads){
            foreach($shop_ads as &$vl){
                $vl['img'] = G_UPLOAD_PATH.'/'.$vl['img'];
            }
            $this->jsonData->success('','',$shop_ads);
        }else{
            $this->jsonData->error('','暂无数据');
        }
    }

    /*
     * 即将揭晓
     * */
    public function index_soon(){

        $shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc!=1 ");
        //最新获得
        $shoplist['listItems']=$this->db->GetList("SELECT * FROM `@#_shoplist_copy` WHERE `q_uid` is not null AND `q_showtime` = 'N' and ssc!=1 ORDER BY `q_end_time` DESC limit 0,3");
        if(empty($shoplist['listItems'])){

            $shoplist['code']=1;

        }else{

            foreach($shoplist['listItems'] as $key=>$val){

                //查询出购买次数

                $recodeinfo=$this->db->GetList("select `gonumber` from `@#_member_go_record` where `uid` ='$val[q_uid]'  and `shopid`='$val[id]' ");

                $goucount=0;

                foreach ($recodeinfo as $r) {
                    $goucount+=(int)$r['gonumber'];
                }
                $shoplist['listItems'][$key]['q_end_time']=date('Y-m-d H:i',$val['q_end_time']);

                $shoplist['listItems'][$key]['gonumber']=$goucount;

            }
            //即将揭晓
            $shoplist['code']=0;
            $shoplist['count']=$shopsum['total'];
        }
        $arr = [];
        foreach($shoplist['listItems'] as $k=>$v){
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['thumb'] = G_UPLOAD_PATH.'/'.$v['thumb'];
            $arr[$k]['time'] = '0';
        }
        $data = [
            'soon'=>$arr
        ];
        if($data){
            $this->jsonData->successCode('','',$data);
        }else{
            $arr = [];
            $this->jsonData->error('','',$arr);
        }
    }
    
    //积分首页
    public function integral_index(){
        $select =40;
        $p = !empty($_POST['page'])?$_POST['page']:0;
        $end=10;
        $star=($p)*$end;
        $uid=$_POST['uid'];
        $select_w = '';
        if($select == 10){
            $select_w = 'order by canyurenshu !=0 desc,ROUND(canyurenshu/zongrenshu,2) desc';
        }
        if($select == 20){
            $select_w = 'order by `qishu` desc';
        }
        if($select == 30){
            $select_w = 'order by `money` desc';
        }
        if($select == 40){
            $select_w = 'order by `order` desc';
        }
        if($select=='10'){
            $shoplist=$this->db->GetList("select * from `@#_shoplist` where ssc!=1 AND (`language` = '$this->nowUserLanguage' or `language` = '') AND `q_uid` is null $select_w limit $star,$end");
        }elseif($select=='20'){
            $shoplist=$this->db->GetList("select * from `@#_shoplist` where ssc!=1 AND (`language` = '$this->nowUserLanguage' or `language` = '') AND `q_uid` is null $select_w limit $star,$end");
        }elseif($select=='30') {
            $shoplist=$this->db->GetList("select * from `@#_shoplist` where ssc!=1 AND (`language` = '$this->nowUserLanguage' or `language` = '') AND `q_uid` is null $select_w limit $star,$end");
        }elseif ($select=='40') {
            $shoplist=$this->db->GetList("select * from `@#_shoplist` where ssc!=1 AND (`language` = '$this->nowUserLanguage' or `language` = '') AND `renqi`=1 and `q_uid` is null AND ispk !=1 $select_w limit $star,$end");
        }

        $field='`id`,`qishu`,`title`,`thumb`,`money`,`zongrenshu`,`shenyurenshu`,`canyurenshu`';

        if(!empty($uid)){
            $shoucang=$this->db->GetList("SELECT $field FROM `@#_shoplist` AS a LEFT JOIN `@#_shopcollect` AS b ON b.shopid=a.id where b.uid='$uid' order by b.time desc");
            if($shoucang){
                $ids = array_column($shoucang,'id');
            }
        }
        $max_renqi_qishu = 1;
        $max_renqi_qishu_id = 1;

        if($uid){
            foreach($shoplist as $k=>$v){

                $current=$this->db->GetOne("SELECT * FROM `@#_shopcollect` WHERE shopid=$v[id] AND uid=$uid");
                if($current){
                    $shoplist[$k]['current']='current';
                }else{
                    $shoplist[$k]['current']='';
                }
            }
        }

        if(!empty($shoplistrenqi)){
            foreach ($shoplistrenqi as $renqikey =>$renqiinfo){
                if($renqiinfo['qishu'] >= $max_renqi_qishu){
                    $max_renqi_qishu = $renqiinfo['qishu'];
                    $max_renqi_qishu_id = $renqikey;
                }
            }
            $shoplistrenqi[$max_renqi_qishu_id]['t_max_qishu'] = 1;
        }
        $num = count($shoplist);
        if($num%2 != 0){
            array_pop($shoplist);
        }
        $webname = $this->_cfg['web_name'];
        $is_shownotice = System::load_sys_config('system','is_shownotice');
        $qq = System::load_sys_config('system','qq');
        if($is_shownotice == '1'){
            $notice = System::load_sys_config('system','banner');
            // $notice = $webname.'：'.$notice;
        }else{
            $notice = "";
        }
        //logo
        $web_log = $this->db->GetOne("SELECT value FROM `@#_config` WHERE name='web_logo'");

        $web_search = $this->db->GetOne("SELECT `value` FROM `@#_config` WHERE name='web_search'");
        $web_search["value"] = json_decode($web_search["value"],true);
        $web_search["value"] = $web_search["value"][$this->nowUserLanguage];
        //获取首页弹出窗
        $is_popup_banner = System::load_sys_config('system','is_popup_banner');
        if($is_popup_banner == 1){
            $popup_banner = $this->db->GetList("select * from `@#_banner` where type = 1 and `status` = 1 AND (`language` = '$this->nowUserLanguage' or `language` = '') order by `id` desc");
            foreach($popup_banner as &$banner) {
                $banner['img'] = G_UPLOAD_PATH.'/'.'bannerimg/'. $banner['img'];
                $banner['link'] = str_replace('&amp;', '&', $banner['link']);
            }
        }else{
            $popup_banner = [];
        }
        //首页转盘
        $is_turntable = $this->getConfig("is_turntable")?:1;

        $prize_draw_banner = $this->db->GetOne("select * from `@#_banner` where type = 2 and `status` = 1 AND (`language` = '$this->nowUserLanguage' or `language` = '') order by `id` desc");
        $prize_draw_banner['img'] = G_UPLOAD_PATH.'/'.'bannerimg/'. $prize_draw_banner['img'];
        //获取抽奖次数
        if($this->uId){
            $winning = $this->db->GetOne("SELECT * from `@#_winning_probability` where `uid` = ".$this->uId);
            $zp_num = $winning['num']?:0;
        }else{
            $zp_num = 1;
        }

        //获取广告
        $shop_ads = $this->db->GetList("select * from `@#_wap` where  `status` = 1 and module = 1 AND model = 1 AND (`language` = '$this->nowUserLanguage' or `language` = '') order by sort asc, id asc");
        //重组banner
        $shop_ad = [];
        foreach($shop_ads as &$vs) {
            $vs['img'] = G_UPLOAD_PATH.'/'. $vs['img'];
            if(empty($vs['link']) || $vs['link'] == '#') {
                $vs['link'] = '';
            }
            $vs['picarr'] = @unserialize($vs['picarr']);
            $vs['data'] = @unserialize($vs['data']);
            $vs['data'] = array_values($vs['data']?:[]);
            $vs['link'] = str_replace('&amp;', '&', $vs['link']);
            foreach($vs['picarr']?:[] as $k=>$v){
                $vs["picarr"][$k] = G_UPLOAD_PATH.'/'. $v;
            }
            if($vs['type'] == 1) {
                $shop_ad['banner'][] = $vs;
            } else {
                $shop_ad['slide'][] = $vs;
            }
        }
        unset($shop_ads);

        //最新获得
        $arr = [];
        $shopqishu=$this->db->GetList("select * from `@#_shoplist_copy` where `q_end_time` !='' AND ssc!=1 ORDER BY `q_end_time` DESC LIMIT 8");

        $time = $this->db->GetOne("SELECT `value` as TIME FROM `@#_config` WHERE `name` ='goods_end_time'");
        //重组中奖商品的信息
        foreach($shopqishu as $k=>$v){
            $v['q_user'] = unserialize($v['q_user']);
            $v['username']=empty($v['q_user']['username'])?$v['q_user']['mobile']:$v['q_user']['username'];//假如电话为空就显示用户名
            $v['username'] = is_numeric($v['username'])?substr_replace($v['username'],'****',3,4):$v['username'];
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['name'] = $v['username'];
            $arr[$k]['img'] =  G_UPLOAD_PATH.'/'.$v['thumb'];
            $arr[$k]['term'] = $v['qishu'];
            $arr[$k]['time'] = $time['TIME'];
        }
        $arra = [];
        //重组商品数组
        foreach($shoplist as $k=>$v){
            $arra[$k]['id'] = $v['id'];
            $arra[$k]['img'] =  G_UPLOAD_PATH.'/'.$v['thumb'];//图片
            $arra[$k]['title'] = $v['title'];
            $arra[$k]['term'] = $v['qishu'];//期数
            // $arra[$k]['money'] = $v['money'];
            if(strstr($v['money'],'.')){
                $moneyArr=explode('.',$v['money']);
                 if($moneyArr[1] == '00'){
                    $arra[$k]['money']=$moneyArr[0];
                }else{
                    $arra[$k]['money']=$v['money'];
                }
            }else{
                $arra[$k]['money']=$v['money'];
            }
            $arra[$k]['proportion'] = round($v['canyurenshu']/$v['zongrenshu'],2);//进度条比例
            if(!empty($ids)){
                if(in_array($v['id'],$ids)){
                    $arra[$k]['is_check'] = true;
                }else{
                    $arra[$k]['is_check'] = false;
                }
            }else{
                $arra[$k]['is_check'] = false;
            }

        }
        //客服图片
        $kefu['name'] = 'QQ客服';
        // $kefu['img'] = G_UPLOAD_PATH ."/" . System::load_sys_config("system",'qqkfewm');
        $kefu_info = $this->db->GetOne("select `value` from `@#_config` where `name` = 'qqkfewm'");
        $kf_value = unserialize($kefu_info['value']);
        $kefu['img'] = G_UPLOAD_PATH ."/" .$kf_value[$this->nowUserLanguage]; 
        $kefu_info_wx = $this->db->GetOne("select `value` from `@#_config` where `name` = 'wxkfewm'");
        $kf_value = unserialize($kefu_info_wx['value']);
        $kefu_wx['name'] = '微信客服';
        $kefu_wx['img'] = G_UPLOAD_PATH ."/" .$kf_value[$this->nowUserLanguage]; 

        if($_SERVER['SERVER_ADDR'] == $_SERVER['SERVER_NAME']){
            $hrefs = $this->db->GetOne("select `value` from `@#_config` where `name` = 'web_path'");
            $href = $hrefs['value'];
        }else{
            $href = G_WEB_PATH;
        }
        $data = [
            'banner' => $shop_ad,//banner
            'prize_draw_num' => $zp_num,
            'prize_draw_banner' => $prize_draw_banner,
            'popup_banner' => $popup_banner,
            'is_turntable' => $is_turntable,
            'shopqishu'=>$arr,//最新揭晓
            'shopList' =>$arra,//商品列表
            'qq'=> $qq,// qq
            'kefu'=> $kefu,// 客服图片
            'kefu_wx'=> $kefu_wx,// 客服图片
            'new_wf'=>G_HTTP.G_HTTP_HOST.'/pay/tehui/wf/index2.html',//新手指引url
            'notice'=>$notice,//新手指引url
            'app'=>$href.'/pay/app/indextwo.php',
            'search_title'=>explode(',', $web_search['value']),
            'logo' => G_UPLOAD_PATH . '/' . $web_log['value'],
        ];
        if($data){
            $this->jsonData->success('','',$data);
        }else{
            $arr = [];
            $this->jsonData->successCode('','',$arr);
        }
    }
    //即将揭晓
    public function new_shop(){
        $maxSeconds = 0;

        $result = array();

        $result['errorCode'] = 0;

        $result['maxSeconds'] = $maxSeconds;

        $result['listItems'] = array();

        $times = (int)System::load_sys_config('system','goods_end_time');

        $time = time();

        $list = $this->db->getlist("select `qishu`,`xsjx_time`,`id`,`thumb`,`title`,`q_uid`,`q_user`,`q_end_time`,`money` from `@#_shoplist` where `q_showtime` = 'Y' and ssc !=1 AND id > '$maxSeconds' order by `q_end_time` DESC");

        foreach($list as $item) {

            if ( $result['maxSeconds'] == $maxSeconds ) {

                $result['maxSeconds'] = $item['id'];

            }

            if($item['xsjx_time']){

                $item['q_end_time'] += $times;

            }

            $data = array();

            $data['id'] = $item['id'];

            $data['qishu'] = $item['qishu'];

            $data['title'] = $item['title'];

            $data['money'] = $item['money'];

            $data['thumb'] = G_UPLOAD_PATH.'/'.$item['thumb'];

            $data['seconds'] = intval($item['q_end_time'] - $time);

            $arr[] = $data;
        }
        if($data){
            $this->jsonData->successCode('','',$arr);
        }else{
            $arr = [];
            $this->jsonData->successCode('','',$arr);
        }
    }
    //商品搜索
    public function search(){
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
        $page = !empty($_POST['page'])?intval($_POST['page']):0;
        $end = 10;
        $start = ($page)*$end;
        $like = '%'.$search.'%';
        $shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist_jifen` WHERE `title` LIKE :lik {$this->where_language} and delete_time is null order by `updatime` DESC LIMIT $start,$end", array('type'=>1,'key'=>''), array('lik'=>$like));

        $arra = [];
        foreach($shoplist as $k=>$v){
            $arra[$k]['id'] = $v['id'];
            $arra[$k]['img'] = G_UPLOAD_PATH.'/'.$v['thumb'];
            $arra[$k]['title'] = $v['title'];
            $arra[$k]['integral'] = $v['s_score'];//积分
            $arra[$k]['money'] = $v['money'];
        }
        if($shoplist){
            $this->jsonData->successCode('','',$arra);
        }else{
            $arr = [];
            $this->jsonData->successCode('','',$arr);
        }
    }
    //商品搜索
    public function searchjf(){
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
        $page = !empty($_POST['page'])?intval($_POST['page']):0;
        $end = 10;
        $start = ($page)*$end;
        $like = '%'.$search.'%';
        $shoplist = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `title` LIKE :lik AND (`language` = :lang or `language` = '') order by `id` DESC LIMIT $start,$end", array('type'=>1,'key'=>''), array('lik'=>$like,'lang'=>$this->nowUserLanguage));

        $arra = [];
        foreach($shoplist as $k=>$v){
            $arra[$k]['id'] = $v['id'];
            $arra[$k]['img'] = G_UPLOAD_PATH.'/'.$v['thumb'];
            $arra[$k]['title'] = $v['title'];
            $arra[$k]['qishu'] = $v['qishu'];
            $arra[$k]['money'] = $v['money'];
            $arra[$k]['canyurenshu'] = $v['canyurenshu'];
            $arra[$k]['zongrenshu'] = $v['zongrenshu'];
            $arra[$k]['shenyurenshu'] = $v['shenyurenshu'];

            $arra[$k]['proportion'] = strval(round($v['canyurenshu']/$v['zongrenshu'],2)) *100;
        }
        if($arra){
            $this->jsonData->successCode('','',$arra);
        }else{
            $arr = [];
            $this->jsonData->error('','',$arr);
        }
    }
    public function collection(){
        $configsearch = System::load_sys_config('search');
        $data['index'] = $configsearch['index']['max'];
        $this->jsonData->successCode('','',$data);
    }

    public function bannerDetail(){
        $id = intval($_POST['id']);
        $code = $_POST['code']?:'';
        if($code){
            $banner = $this->db->GetOne("select * from `@#_wap` where `code` = '$code' and (`language` = '{$this->nowUserLanguage}' or `language` = '') order by language desc");
        }
        if($id){
            $banner = $this->db->GetOne("select * from `@#_wap` where `id`=$id ");
        }
        if($banner){
            $banner['picarr'] = unserialize($banner['picarr']);
            foreach($banner['picarr'] as $k=>&$vs){
                $vs = G_UPLOAD_PATH.'/'. $vs;
            }
            $banner['data'] = @unserialize($banner['data']);
            if($banner['data'] && is_array($banner['data'])){
                $banner['data'] = array_values($banner['data']);
            }
            $this->jsonData->successCode('','获取成功',$banner);
        }else{
            $this->jsonData->error('','未找到',[]);
        }

    }

    /**
     * 获取拼团是否开启
     */
    public function get_group_status(){
        $status = System::load_sys_config("system",'is_group');
        $this->jsonData->successCode('','',["status"=>$status?:2]);
    }
    /**
     * 获取优惠购是否开启
     */
    public function get_preferential_status(){
        $status = System::load_sys_config("system",'is_preferential');
        $this->jsonData->successCode('','',["status"=>$status?:2]);
    }

    //获取社群
    public function get_community(){
        $community = $this->db->GetList("select * from `@#_community` where `status` = 1 order by `sort` DESC");
        foreach($community as $k=>$v){
            $community[$k]['img'] = G_UPLOAD_PATH.'/'.$v['img'];
        }
        $this->jsonData->success('','success',$community?:[]);
    }

    //获取地址列表
    public function get_address(){
        $lists = $this->db->GetList("select * from `@#_area` where `status` = 1 order by `id` DESC");
        $this->jsonData->success('','success',$lists?:[]);
    }

    //获取地址详情
    public function get_address_detail(){
        $language = $this->nowUserLanguage;
        $area = $this->db->GetOne("select * from `@#_area` where `language` = :lang or `language` = '' order by language desc", array('lang'=>$language));
        $id = isset($area['id']) ? (int)$area['id'] : (isset($_POST['id']) ? intval($_POST['id']) : 0);
        if ($id <= 0) {
            $this->jsonData->error('','参数错误',[]);
            return;
        }
        $info = $this->redis->redisGet("address_detail_{$id}");
        if($info['data']){
            $this->jsonData->success('','success',json_decode($info['data'],true)?:[]);
            return;
        }
        $info = $this->db->GetList("select `id`,`pid`,`name`,`desc`,`code` from `@#_area_details` where `area_id` = :aid", array('type'=>1,'key'=>''), array('aid'=>$id));
        $lists = buildTree($info);
        $this->redis->redisSetex("address_detail_{$id}",3600,json_encode($lists,true));
        $this->jsonData->success('','success',$lists?:[]);
    }

}