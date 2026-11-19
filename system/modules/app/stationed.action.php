<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_app_class('base','app','no');
class stationed extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->check_info();
    }

    private function check_info(){
        $info = $this->db->GetOne("select uid,level from `@#_member` where `uid` = '$this->uId'");
        if($info['level'] != 3 && $info['level'] != -1){
           // $this->jsonData->error('','您不是入驻商');
        }
    }
    
    /**
     * 商品列表
     */
    public function goods_lists()
    {
        $sql = "select id,title,thumb,money,yunjiage,preferential_price from `@#_shoplist`";
        $list = $this->db->GetPage($sql,array('num'=>10,'page'=>intval($_GET['p']??1),'type'=>1));
        foreach ($list as $key => $val){
            $list[$key]['thumb'] = G_UPLOAD_PATH.'/'.$val['thumb'];
        }
        $this->jsonData->success('','',$list);
    }

    /**
     * 商品详情
     */
    public function goods_details()
    {
        $id = intval($_POST['id']);
        if(!$id){
            $this->jsonData->error('','参数错误');
        }
        $sql = "select * from `@#_shoplist` where id = {$id}";
        $info = $this->db->GetOne($sql);
        if(!$info){
            $sql = "select * from `@#_shoplist_del` where id = {$id}";
            $info = $this->db->GetOne($sql);
        }
        if(!$info){
            $this->jsonData->error('','商品不存在');
        }
        $info['thumb']  = G_UPLOAD_PATH.'/'.$info['thumb'];
        $picarr = mb_unserialize($info['picarr']);
        foreach ($picarr?:[] as $key => $value) {
            if  (!$value) continue;
            $picarr[$key] = G_UPLOAD_PATH.'/'.$value;
        }
        $info['picarr'] = $picarr;
        $this->jsonData->success('','',$info);
    }

    private function assemble_goods_data($goods=[]){
        $title = htmlspecialchars($_POST['title']); //标题
        $title = _htmtocode($title)?:$goods['title'];
        if(!$title)$this->jsonData->error('',"标题不能为空");
        $cateid = intval($_POST['cateid'])?:$goods['cateid']; //分类id
        if(!$cateid)$this->jsonData->error('',"请选择栏目");

        $brandid = intval($_POST['brand'])?:$goods['brandid'];  //品牌id

        $thumb = $_POST['thumb']?:$goods['thumb'];
        if(empty($thumb)){
            $this->jsonData->error('','缩略图不能为空');
        }
        $imgarr = $_POST['imgarrList'];
        $imgarr = explode(',',$imgarr);
        if(count($imgarr)>10){
            $this->jsonData->error('','最多上传10张 图片');
        }
        $picarr=serialize($imgarr);

        $title_color = htmlspecialchars($_POST['title_style_color']); //标题颜色
        $title_bold = htmlspecialchars($_POST['title_style_bold']); //标题加粗
        $title2 = htmlspecialchars($_POST['title2']);//副标题
        $is_pk = 0;
        $keywords = htmlspecialchars($_POST['keywords']); //关键词
        $description = htmlspecialchars($_POST['description']); //描述
        $content = editor_safe_replace(stripslashes($_POST['editorValue'])); //内容
        $content = str_replace("'","\'", $content);
        $content = str_replace('"','\"', $content);
        $money = intval($_POST['money'])?:$goods['money']; //商品价格
        if($money<=0){
            $this->jsonData->error('','商品价格不能小于等于0');
        }
        $yunjiage = intval($_POST['yunjiage'])?:$goods['yunjiage']; //商品云购价
        if($yunjiage<=0){
            $this->jsonData->error('','商品云购价不能小于等于0');
        }
        $maxqishu = intval($_POST['maxqishu'])?:$goods['maxqishu']; //最大期数
        $order = intval($_POST['order']); //排序
        $preferential_price = intval($_POST['preferential_price'])?:$goods['preferential_price']; //优惠价格
        if(!$preferential_price){
            $preferential_price = $money;
        }
        $ssc = 0;
        $canyurenshu = $goods["canyurenshu"]??0;
        $goods_key_pos = isset($_POST['pos']) ? 1 : 0;
        $goods_key_renqi = isset($_POST['renqi']) ? 1 : 0;
        $yunrenci = intval($_POST['yunrenci']);
        $language = $_POST['language'];
        if($is_pk){
            if(!$yunrenci){$this->jsonData->error('',"请设置人数");}
            $check_money=is_int($money/$yunrenci);
            if(!$check_money)$this->jsonData->error('',"商品价格不是".$yunrenci."的倍数");
            $yunjiage=$money/$yunrenci;
        }
        $zongrenshu = ceil($money/$yunjiage);
        $groups = '';
        for($i = 2;$i<=10;$i++){
            if($zongrenshu % $i == 0){
                if($groups){
                    $groups .= ",";
                }
                $groups .=$i;
            }
        }
        $is_group = $this->getConfig("is_group")?:2;
        if(!$groups && $is_group !=2 ){
            $this->jsonData->error('',"请修改价格，此价格无法进行拼团");
        }

        $title_style='';
        if($title_color){
            $title_style.='color:'.$title_color.';';
        }
        if($title_bold){
            $title_style.='font-weight:'.$title_bold.';';
        }
        if($_POST['xsjx_time'] != ''){
            $xsjx_time = strtotime($_POST['xsjx_time']) ? strtotime($_POST['xsjx_time']) : time();
            $xsjx_time_h = intval($_POST['xsjx_time_h']) ? $_POST['xsjx_time_h'] : 36000;
            $xsjx_time += $xsjx_time_h;
        }else{
            $xsjx_time = '0';
        }
        if($maxqishu > 65535){
            $this->jsonData->error('',"最大期数不能超过65535期");
        }
        if($money < $yunjiage) $this->jsonData->error('',"商品价格不能小于购买价格");
        $shenyurenshu = $goods['shenyurenshu']??$zongrenshu-$canyurenshu;
        if($zongrenshu==0 || ($zongrenshu-$canyurenshu)==0){
            $this->jsonData->error('',"云购价格不正确");
        }
        $time=time();   //商品添加时间
        $data = [
            'cateid' => $cateid,
            'brandid' => $brandid,
            'title' => $title,
            'title_style' => $title_style,
            'title2' => $title2,
            'keywords' => $keywords,
            'description' => $description,
            'money' => $money,
            'yunjiage' => $yunjiage,
            'zongrenshu' => $zongrenshu,
            'canyurenshu' => $canyurenshu,
            'shenyurenshu' => $shenyurenshu,
            'qishu' => 1,
            'maxqishu' => $maxqishu,
            'thumb' => $thumb,
            'picarr' => $picarr,
            'content' => $content,
            'xsjx_time'=> $xsjx_time,
            'pos'=> $goods_key_pos,
            'renqi' => $goods_key_renqi,
            'time'=> $time,
            'ispk' => $is_pk,
            'ssc' => $ssc,
            'language' => $language,
            'preferential_price' => $preferential_price,
            'groups' => $groups,
            'order' => $order,
        ];
        return $data;
    }
    /**
     * 添加商品
     */
    public function goods_add()
    {
        $data = $this->assemble_goods_data();
        $content_url = $data['content'];
        unset($data['content']);
        $sql = Assemblydata($data,"shoplist");
        $this->db->Autocommit_start();
        $query_1 = $this->db->Query($sql);
        $shopid = $this->db->insert_id();
        $q1 = $this->db->Query("INSERT INTO `@#_shoplist_del` select * from `@#_shoplist` where `id` = '$shopid'");
        $q2 = $this->db->Query("DELETE FROM `@#_shoplist` WHERE `id` = '$shopid' LIMIT 1");
        $q3 = $this->db->Query("UPDATE `@#_shoplist_del` SET `sid` = '$shopid' WHERE `id` = '$shopid'");
        $sql = Assemblydata([
            "create_time" => $data['time'],
            "uid" => $this->uId,
            "shopid" => $shopid,
            "content_url" => $content_url,
        ],"shoplist_add");
        $q4 = $this->db->Query($sql);
        if ($query_1 && $q1 && $q2 && $q3 && $q4) {
            $this->db->Autocommit_commit();
            $res = $this->db->GetOne("SELECT * FROM `@#_shoplist_del` WHERE `id` = '$shopid'");
            $this->jsonData->success('', '添加商品成功', $res);
        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('', '添加商品失败');
        }
    }

    /**
     * 编辑商品
     */
    public function goods_edit()
    {
        $id = _htmtocode($_POST['id']);
        $opt = "in";
        $goods = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$id'");
        if(!$goods){
            $goods = $this->db->GetOne("SELECT * FROM `@#_shoplist_del` WHERE `id` = '$id'");
            $opt = "up";
        } 
        if (!$goods) {
            $this->jsonData->error('', '获取商品失败');
        }
        $data = $this->assemble_goods_data($goods);
        $content_url = $data['content'];
        $data = array_merge( $goods,$data);
        unset($data['content']);
        $is_money = 0;$data_edit = [];
        $examine = $this->db->GetOne("SELECT * FROM `@#_shoplist_add` WHERE `shopid` = '$id' and uid = '$this->uId'");
        if($data['money'] != $goods['money'] || $data['yunjiage'] != $goods['yunjiage']){
            $data_edit = json_decode($examine['data'],true);
            $is_money = 1;
            if(!$data_edit['money']){
                $data_edit['money'] = $goods['money'];
                $data_edit['yunjiage'] = $goods['yunjiage'];
            }elseif($data_edit['money'] == $data['money'] && $data_edit['yunjiage'] == $data['yunjiage']){
                    $is_money = 0;
            }
            $data_edit = json_encode($data_edit);
        }
        $data['time'] = $goods['time'];
        $this->db->Autocommit_start();
        $res_goods_del = true;
        $data['q_uid'] = null;
        if($opt == "in"){
            $res_goods_del = $this->db->Query("DELETE FROM `@#_shoplist` WHERE `id` = '$id' LIMIT 1");
            $sql = Assemblydata($data, 'shoplist_del');
        }else{
            foreach ($goods as $key => $value) {
                if($data[$key] == $value){
                    unset($data[$key]);
                }
            }
            $data['id'] = $id;
            $sql = Assemblydata($data, 'shoplist_del',"up",[['id',$id]]);
        }
        $res_goods = $this->db->Query($sql);
        
        if($examine){
            $sql = Assemblydata([
                'status' => 1,
                'is_money' => $is_money,
                "create_time" => time(),
                'data' => $data_edit?:$examine['data'],
                "content_url" =>$content_url
            ],'shoplist_add',"up",[['id',$examine['id']]]);
        }else{
            $sql = Assemblydata([
                "create_time" => time(),
                "uid" => $this->uId,
                "shopid" => $goods['id'],
                "is_money" => $is_money,
                'data' => $data_edit,
                "content_url" =>$content_url
            ],"shoplist_add");
        }
        $res_examine= $this->db->Query($sql);
        if($res_goods && $res_goods_del && $res_examine){
            $this->db->Autocommit_commit();
            $this->jsonData->success('', '编辑商品成功');
        }else{
            $this->db->Autocommit_rollback();
            $this->jsonData->error('', '编辑商品失败',[$res_goods,$res_goods_del,$res_examine]);
        }
    }
}