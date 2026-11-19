<?php

defined('G_IN_SYSTEM')or exit('no');

System::load_app_class('admin',G_ADMIN_DIR,'no');

System::load_app_fun('global',G_ADMIN_DIR);

System::load_sys_fun('user');

class content extends admin {

    private $db;

    private $categorys;

    private $models;
    

    public function __construct(){

        parent::__construct();

        $this->db=System::load_sys_class('model');

        $this->ment=array(

            );

        $this->categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE model='2' order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));

        $this->models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));

    }

    //添加新文章

    public function article_add(){
        $this->ment=array(

            array("lists","文章管理",ROUTE_M.'/'.ROUTE_C."/article_list"),
            array("lists","文章分类",ROUTE_M."/category/lists/article"),
            array("insert","添加文章",ROUTE_M.'/'.ROUTE_C."/article_add"),

        );
        if(isset($_POST['dosubmit'])){
            $cateid = intval($_POST['cateid']);

            $title =  htmlspecialchars($_POST['title']);

            $title_color = htmlspecialchars($_POST['title_style_color']);

            $title_bold = htmlspecialchars($_POST['title_style_bold']);

            $title_style='';

            if($title_color){

                $title_style.='color:'.$title_color.';';

            }

            if($title_bold){

                $title_style.='font-weight:'.$title_bold.';';

            }

            $keywords = htmlspecialchars($_POST['keywords']);

            $description = htmlspecialchars($_POST['description']);
            
            $language = htmlspecialchars($_POST['language']);

            $keys = intval($_POST['keys']);

            $content = editor_safe_replace(stripslashes($_POST['editorValue']));
            //解决内容中的引号，防止sql出错
            $content = str_replace("'","\'", $content);
            $content = str_replace('"','\"', $content);

            $author = isset($_POST['zuoze']) ? htmlspecialchars($_POST['zuoze']) : '';

            $thumb = isset($_POST['thumb']) ? htmlspecialchars($_POST['thumb']) : '';

            $picarr = isset($_POST['uppicarr']) ? serialize($_POST['uppicarr']) : serialize(array());

            $posttime = strtotime($_POST['posttime']) ? strtotime($_POST['posttime']) : time();

            $hit = intval($_POST['hit']);

            $position_arr = isset($_POST['position']) ? $_POST['position'] : false;

            if(empty($title)){_message("标题不能为空");}

            if(!$cateid){_message("栏目不能为空");}

            $sql="INSERT INTO `@#_article` (`cateid`, `author`, `title`,`title_style`, `thumb`, `picarr`, `keywords`, `description`, `content`, `hit`, `order`, `posttime`,`language`,`keys`) VALUES ('$cateid', '$author', '$title','$title_style', '$thumb', '$picarr', '$keywords', '$description', '$content', '$hit', '1', '$posttime','$language','$keys')";
            if($this->db->Query($sql)){

                if($position_arr){

                    $posinfo=array();

                    $posinfo['id'] = $this->db->insert_id();

                    $posinfo['title'] = $title;

                    $posinfo['title_style'] = $title_style;

                    $posinfo['thumb'] = $thumb;

                    $position = System::load_app_model("position",'api');

                    $position->pos_insert($position_arr,$posinfo);

                }

                    _message("文章添加成功");

            }else{

                    _message("文章添加失败");

            }

            header("Cache-control: private");

        }
        $cateid=intval($this->segment(4));

        $categorys=$this->categorys;
        $categoryshtml = '';
		foreach($categorys as $id=>&$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
			if($cateid == $cat['cateid']){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			$categoryshtml .= "<option value='".$cat['cateid']."' $selected>".$cat['name']."</option>";
        }
        // $tree=System::load_sys_class('tree');

        // $tree->icon = array('│ ','├─ ','└─ ');

        // $tree->nbsp = '&nbsp;';

        // $categoryshtml="<option value='\$cateid' \$selected>\$spacer\$name</option>";

        // $tree->init($categorys);

        // $categoryshtml=$tree->get_tree(0,$categoryshtml,2);
        // $categoryshtml='<option value="0">≡ 请选择栏目 ≡</option>'.$categoryshtml;

        if($cateid){

            $cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");

            if(!$cateinfo)_message("参数不正确,没有这个栏目",G_ADMIN_PATH.'/'.ROUTE_C.'/addarticle');

            // $categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';

        }
        $sql = Assemblydata(['*'],'category_keys',"sel");
        $articleKeys = $this->db->getList($sql);
        $languages = $this->getLanguageList(1);
        include $this->tpl(ROUTE_M,'article.insert');

    }

    //文章编辑

    public function article_edit(){
        $id=intval($this->segment(4));
        $this->ment=array(
            array("lists","文章管理",ROUTE_M.'/'.ROUTE_C."/article_list"),
            array("lists","文章分类",ROUTE_M."/category/lists/article"),
            array("insert","添加文章",ROUTE_M.'/'.ROUTE_C."/article_add"),
            array("insert","修改文章",ROUTE_M.'/'.ROUTE_C."/article_edit/".$id),
        );
        $info=$this->db->GetOne("SELECT * FROM `@#_article` where `id`='$id' LIMIT 1");
        if(isset($_POST['dosubmit'])){
            $id = $_POST['id'];
            $cateid = intval($_POST['cateid']);
            $title =  htmlspecialchars($_POST['title']);
            $title_color = htmlspecialchars($_POST['title_style_color']);
            $title_bold = htmlspecialchars($_POST['title_style_bold']);
            $language = htmlspecialchars($_POST['language']);
            $keys = intval($_POST['keys']);
            $title_style='';
            if($title_color){
                $title_style.='color:'.$title_color.';';
            }
            if($title_bold){
                $title_style.='font-weight:'.$title_bold.';';
            }
            $keywords = htmlspecialchars($_POST['keywords']);
            $description = htmlspecialchars($_POST['description']);
            $content = editor_safe_replace(stripslashes($_POST['editorValue']));
            $content = str_replace("'","\'", $content);
            $content = str_replace('"','\"', $content);
            $author = isset($_POST['zuoze']) ? htmlspecialchars($_POST['zuoze']) : '';
            $thumb = isset($_POST['thumb']) ? htmlspecialchars($_POST['thumb']) : '';
            $picarr = isset($_POST['uppicarr']) ? serialize($_POST['uppicarr']) : serialize(array());
            $posttime = strtotime($_POST['posttime']) ? strtotime($_POST['posttime']) : time();
            $hit = intval($_POST['hit']);
            $position_arr = isset($_POST['position']) ? $_POST['position'] : false;
            if(empty($title)){_message("标题不能为空");}
            if(!$cateid){_message("栏目不能为空");}
            $sql="UPDATE `@#_article` SET `cateid`='$cateid', 
                                          `author`='$author', 
                                          `title`='$title',
                                          `title_style` = '$title_style', 
                                          `keywords`='$keywords', 
                                          `description`='$description',
                                          `thumb`='$thumb',
                                          `picarr`='$picarr',                                         
                                          `content`='$content', 
                                          `posttime`='$posttime',
                                          `hit` = '$hit',
                                          `language` = '$language',
                                          `keys` = '$keys'
                                          WHERE (`id`='$id')";
            $this->db->Query($sql);
            if($position_arr){
                    $posinfo=array();
                    $posinfo['id'] = $info['id'];
                    $posinfo['title'] = $title;
                    $posinfo['title_style'] = $title_style;
                    $posinfo['thumb'] = $thumb;
                    $position = System::load_app_model("position",'api');
                    $position->pos_insert($position_arr,$posinfo);
            }

            if($this->db->affected_rows()){
                _message("操作成功!");
            }else{
                _messageerror("操作失败!");
            }
            header("Cache-control: private");
        }
        if(!$info)_messageerror("参数错误");
        $cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$info[cateid]' LIMIT 1");
        $categorys=$this->categorys;
        $categoryshtml = '';
		foreach($categorys as &$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
			if($info['cateid'] == $cat['cateid']){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			$categoryshtml .= "<option value='".$cat['cateid']."' $selected>".$cat['name']."</option>";
        }
        $info['picarr']=unserialize($info['picarr']);
        $info['posttime'] = date("Y-m-d H:i:s",$info['posttime']);
        if($info['title_style']){
            if(stripos($info['title_style'],"font-weight:")!==false){
                $title_bold = 'bold';
            }else{
                $title_bold = '';
            }
            if(stripos($info['title_style'],"color:")!==false){
                $title_color = explode(';',$info['title_style']);
                $title_color = explode(':',$title_color[0]);
                $title_color = $title_color[1];
            }else{
                $title_color = '';
            }
        }else{
            $title_color='';
            $title_bold = '';
        }
        $sql = Assemblydata(['*'],'category_keys',"sel");
        $articleKeys = $this->db->getList($sql);
        $languages = $this->getLanguageList(1);
        include $this->tpl(ROUTE_M,'article.edit');
    }

    //文章列表
    public function article_list(){
        $this->ment=array(
                        array("lists","文章管理",ROUTE_M.'/'.ROUTE_C."/article_list"),
                         array("lists","文章分类",ROUTE_M."/category/lists/article"),
                        array("insert","添加文章",ROUTE_M.'/'.ROUTE_C."/article_add"),
        );
        $cateid=intval($this->segment(4));
        $list_where = '';
        if(!$cateid){
            $list_where = "1";
        }else{
            $list_where = "`cateid` = '$cateid'";
        }
        if(isset($_POST['sososubmit'])){
            $posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;
            $posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;
            $sotype = $_POST['sotype'];
            $sosotext = $_POST['sosotext'];
            if($posttime1 && $posttime2){
                if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");
                $list_where = "`posttime` > '$posttime1' AND `posttime` < '$posttime2'";
            }
            if($posttime1 && empty($posttime2)){
                $list_where = "`posttime` > '$posttime1'";
            }
            if($posttime2 && empty($posttime1)){
                $list_where = "`posttime` < '$posttime2'";
            }
            if(empty($posttime1) && empty($posttime2)){
                $list_where = false;
            }

            if(!empty($sosotext)){
                if($sotype == 'cateid'){
                    $sosotext = intval($sosotext);
                    if($list_where)
                        $list_where .= "AND `cateid` = '$sosotext'";
                    else
                        $list_where = "`cateid` = '$sosotext'";
                }
                if($sotype == 'catename'){
                    $sosotext = htmlspecialchars($sosotext);
                    $info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` = '$sosotext' LIMIT 1");

                    if($list_where && $info)
                        $list_where .= "AND `cateid` = '$info[cateid]'";
                    elseif ($info)
                        $list_where = "`cateid` = '$info[cateid]'";
                    else
                        $list_where = "1";
                }
                if($sotype == 'title'){
                    $sosotext = htmlspecialchars($sosotext);
                    $list_where = "`title` = '$sosotext'";
                }
                if($sotype == 'id'){
                    $sosotext = intval($sosotext);
                    $list_where = "`id` = '$sosotext'";
                }
            }else{
                if(!$list_where) $list_where='1';
            }
            if($lan = $_POST['language']){
                if($lan == "all"){
                    $list_where .= "";
                }elseif($lan == "general"){
                    $list_where .= " and (language = '' or language = null)";
                }else{
                    $list_where .= " and language = '$lan'";
                }
            }
        }
        $num=20;
        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_article` WHERE $list_where ");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $articlelist=$this->db->GetList("SELECT * FROM `@#_article` WHERE $list_where  order by `order` DESC");
        $language = $this->getLanguageList(1);
        $lgs_arr = array_column($language,"country","lg_value");
        $sql = Assemblydata(['*'],'category_keys',"sel");
        $articleKeys = $this->db->getList($sql);
        $articleKeys = array_column($articleKeys,"name","keys");
        
        foreach($articlelist as $k => $v){
          $id= $v['cateid'];
		  $categoryName=$this->db->GetOne("SELECT `name` FROM `@#_category` WHERE `cateid`= $id");
          $articlelist[$k]['name'] = $categoryName['name'];
          $articlelist[$k]['language'] = $lgs_arr[$v['language']]?:"通用";
          $articlelist[$k]['keys'] = $articleKeys[$v['keys']]?:"";
        }
		 // echo '<pre>';
		//  var_dump($articlelist);exit;
        include $this->tpl(ROUTE_M,'article.lists');
    }

    //模型
    public function model(){

        $models=$this->models;

        include $this->tpl(ROUTE_M,'content.model');

    }

    public function lists(){

        $categorys=$this->categorys;

        $models=$this->models;

        $tree=System::load_sys_class('tree');

        $tree->icon = array('│ ','├─ ','└─ ');

        $tree->nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

        foreach($categorys as $v){

            $v['typename']=cattype($v['model']);

            if($v['model']==-1 || $v['model']==-2){

                $v['seecontent']=G_ADMIN_PATH.'/category/editcate/';

                $v['addcontent']=G_ADMIN_PATH.'/category/editcate/';

            }

            if($v['model']>0){

                $v['seecontent']=G_ADMIN_PATH.'/'.ROUTE_C.'/get/';

                $v['addcontent']=G_ADMIN_PATH.'/'.ROUTE_C.'/add/';

                $v['model']=$models[$v['model']]['name'];

            }else{

                $v['model']='';

            }

            $categorys[$v['cateid']]=$v;

        }

        $html=<<<HTML

            <tr>

            <td align='center'>\$cateid</td>

            <td align='left'>\$spacer\$name</th>

            <td align='center'>\$typename</td>

            <td align='center'>\$model</td>

            <td align='center'></td>

            <td align='center'>               

                <a href='\$addcontent\$cateid\'>添加内容</a><span class='span_fenge lr5'>|</span>

                 <a href='\$seecontent\$cateid'>查看内容</a><span class='span_fenge lr5'></span>   

            </td>

          </tr>

HTML;

        $tree->init($categorys);

        $html=$tree->get_tree(0,$html);

        include $this->tpl(ROUTE_M,'content.list');

    }

    public function jifen_goods_list($type=0){
		$this->ment=array(
                    array("jifen_goods_list","直购商品列表",ROUTE_M.'/'.ROUTE_C."/jifen_goods_list"),
                    array("jifen_goods_add","添加直购商品",ROUTE_M.'/'.ROUTE_C."/jifen_goods_add"),
                    array("jifen_goods_add","回收商品列表",ROUTE_M.'/'.ROUTE_C."/jifen_goods_list_del"),
		);		
	    $cateid=$this->segment(4);
		$list_where = '';
		if(isset($_POST['sososubmit'])){			
			$posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;
			$posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;			
            if($posttime1 && $posttime2){
				if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");
			}
            if($posttime1){
                $list_where .= "  AND `a.time` > '$posttime1'";
            }
            if($posttime2){
                $list_where .= "  AND `a.time` < '$posttime2'";
            }
            $sosotext = htmlspecialchars($_POST['sosotext']);
            if($sosotext){
                $list_where .= " AND  `a.title` LIKE '%$sosotext%'";
            }
            $language = htmlspecialchars($_POST['language']);
            if($language){
                $list_where .= " AND  a.`language` = '$language'";
            }
        }
        if($type){
            $list_where.=" AND a.`delete_time` is not null";
        }else{
            $list_where.=" AND a.`delete_time` is null";
        }
		$num=20;
		$total=$this->db->GetCount("SELECT COUNT(a.*) FROM `@#_shoplist_jifen` a WHERE 1 $list_where"); 
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	
		$page->config($total,$num,$pagenum,"0");
		$shoplist=$this->db->GetPage("SELECT a.*,c.name as cate_name FROM `@#_shoplist_jifen` a left join `@#_category` c on c.cateid = a.cateid WHERE 1 $list_where ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $languages = $this->getLanguageList(1);
        $lg = array_column($languages,"country","lg_value");
        foreach($shoplist as $k => $v){
            // $shoplist[$k]['time'] = date("Y-m-d H:i:s", $v['time']);
            $shoplist[$k]['lan'] = $lg[$v['language']]? $lg[$v['language']] : "通用";
        }
        $cry = $this->getCurrencyList();
        data_format($shoplist,['money','disc_price'],"price",$cry);
		include $this->tpl(ROUTE_M,'shop.jifen_lists');
	}

    public function jifen_goods_add(){
        $this->ment=array(
             array("jifen_goods_list","直购商品列表",ROUTE_M.'/'.ROUTE_C."/jifen_goods_list"),
                    array("jifen_goods_add","添加直购商品",ROUTE_M.'/'.ROUTE_C."/jifen_goods_add"),
        );
        $lg = $this->getLanguageList(1);
        $category = $this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '3' ORDER BY `order` desc,`parentid` ASC,`order` ASC");
        if(isset($_POST['dosubmit'])){
            $data = $_POST;
            unset($data['dosubmit']);
            unset($data['submit']);
            unset($data['ajax_from']);
            unset($data['editorValue']);
            $data['picarr'] = serialize($data['imgarrList']);
            unset($data['imgarrList']);
            $content = editor_safe_replace(stripslashes($_POST['editorValue']));
            $content = str_replace("'","\'", $content);
            $data['content'] = str_replace('"','\"', $content);
            $data['time'] = $data['updatime'] = time();
            $data['sales'] = 0;
            $sql = Assemblydata($data,"shoplist_jifen");
            $this->db->Autocommit_start();
            $res = $this->db->Query($sql);
            $shopid = $this->db->insert_id();
            $query_3 = $this->db->Query("UPDATE `@#_shoplist_jifen` SET `sid` = '$shopid' where `id` = '$shopid'");
            if($res && $query_3){
                $this->db->Autocommit_commit();
                _message("添加成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_goods_list");
            }else{
                $this->db->Autocommit_rollback();
                _messageerror("添加失败");
            }
        }
        $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
		include $this->tpl(ROUTE_M,'shop.jifen_goods_add');
    }

    public function jifen_goods_edit(){
        $this->ment=array(
             array("jifen_goods_list","直购商品列表",ROUTE_M.'/'.ROUTE_C."/jifen_goods_list"),
                    array("jifen_goods_edit","修改直购商品",ROUTE_M.'/'.ROUTE_C."/jifen_goods_edit",2),
        );
        $lg = $this->getLanguageList(1);
        $category = $this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '3' ORDER BY `order` desc,`parentid` ASC,`order` ASC");
        $id = $this->segment(4);
        $tempData = $this->db->GetOne("SELECT * FROM `@#_shoplist_jifen` WHERE `id` = '$id' and delete_time is null ");
        if(!$tempData){
            _message("数据不存在");
        }
        $tempData['imgarrList'] = unserialize($tempData['picarr']);
        if(isset($_POST['dosubmit'])){
            $data = [
                "pos"   => 0 ,
                "renqi" => 0 ,
                "online"=> 0 ,
                "newhot"=> 0 ,
            ];
            $data = array_merge($data,$_POST);
            unset($data['submit']);
            unset($data['dosubmit']);
            unset($data['editorValue']);
            unset($data['ajax_from']);
            if(count($data['imgarrList'])>10){
                _messageerror('最多上传10张 图片');
            }
            $data['picarr'] = serialize($data['imgarrList']);
            unset($data['imgarrList']);
            $content = editor_safe_replace(stripslashes($_POST['editorValue']));
            $content = str_replace("'","\'", $content);
            $data['content'] = str_replace('"','\"', $content);
            $data['updatime'] = time();
            $sql = Assemblydata($data,"shoplist_jifen","up",[["id",$id]]);
            $this->db->Autocommit_start();
            $res = $this->db->Query($sql);
            // $query_3 = $this->db->Query("UPDATE `@#_shoplist_jifen` SET `sid` = '$id' where `id` = '$id'");
            if($res){
                $this->db->Autocommit_commit();
                _message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_goods_list");
            }else{
                $this->db->Autocommit_rollback();
                // Psql($sql);
                _messageerror("修改失败");
            }
        }
        $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
        include $this->tpl(ROUTE_M,'shop.jifen_goods_add');
    }

    public function jifen_goods_del(){
        $id = $this->segment(4);
        $sql = Assemblydata(['delete_time'=>time()],"shoplist_jifen",'up',[['id',$id]]);
        $this->db->Query($sql);
        // $this->db->Query("DELETE FROM `@#_shoplist_jifen` WHERE `id` = '$id'");
        _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_goods_list");
    }
    public function jifen_goods_del_real(){
        $id = $this->segment(4);
        $this->db->Query("DELETE FROM `@#_shoplist_jifen` WHERE `id` = '$id'");
        _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_goods_list_del");
    }
    public function jifen_goods_del_restore(){
        $id = $this->segment(4);
        $sql = Assemblydata(['delete_time'=>null],"shoplist_jifen",'up',[['id',$id]]);
        $this->db->Query($sql);
        _message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/jifen_goods_list_del");
    }

    public function jifen_goods_list_del(){
        $this->jifen_goods_list(1);
    }
        /*

    *   商品排序

    */

    public function jifengoods_listorder(){
        if($this->segment(4)=='dosubmit'){
            foreach($_POST['listorders'] as $id => $listorder){
                $id = intval($id);
                $listorder = intval($listorder);
                $this->db->Query("UPDATE `@#_shoplist_jifen` SET `order` = '$listorder' where `id` = '$id'");
            }
            _message("排序更新成功");
        }else{
            _messageerror("请排序");
        }
    }

    //商品列表
    public function goods_list(){
        $this->ment=array(
            array("lists","云购商品列表",ROUTE_M.'/'.ROUTE_C."/goods_list"),
            array("add","添加云购商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),
            // array("renqi","人气商品",ROUTE_M.'/'.ROUTE_C."/goods_list/renqi"),
            // array("xsjx","限时获得商品",ROUTE_M.'/'.ROUTE_C."/goods_list/xianshi"),
            array("qishu","期数正序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishuasc"),
            array("qishu","期数倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishu"),
            array('danjia','单价正序',ROUTE_M.'/'.ROUTE_C."/goods_list/danjiaasc"),
            array("danjia","单价倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/danjia"),
            array("money","商品价格倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/money"),
            array("money","商品价格正序",ROUTE_M.'/'.ROUTE_C."/goods_list/moneyasc"),
            array("money","期数已满商品",ROUTE_M.'/'.ROUTE_C."/goods_list/maxqishu"),

        );

        $cateid=$this->segment(4);
        $list_where = '';
        $posttime1_str = '';
        $posttime2_str = '';
        $sotype = '';
        $sosotext = '';
        $get_data = [];
        if(isset($_GET['posttime1']) || isset($_GET['sosotext']) || isset($_GET['sotype']) || isset($_GET['posttime2'])){
            $posttime1_str = $_GET['posttime1'];
            $posttime2_str = $_GET['posttime2'];
            $posttime1 = !empty($_GET['posttime1']) ? strtotime($_GET['posttime1']) : NULL;
            $posttime2 = !empty($_GET['posttime2']) ? strtotime($_GET['posttime2']) : NULL;
            $sotype = $_GET['sotype'];
            $sosotext = $_GET['sosotext'];
            $get_data['posttime1'] = !empty($posttime1_str)?$posttime1_str:'';
            $get_data['posttime2'] = !empty($posttime2_str)?$posttime2_str:'';
            $get_data['sotype'] = !empty($sotype)?$sotype:'';
            $get_data['sosotext'] = !empty($sosotext)?$sosotext:'';
        }
        
        if(isset($_POST['sososubmit'])){
            $posttime1_str = $_POST['posttime1'];
            $posttime2_str = $_POST['posttime2'];
            $posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;
            $posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;
            $sotype = $_POST['sotype'];
            $sosotext = $_POST['sosotext'];
            $get_data['language'] = $_POST['language']??'';
            $get_data['posttime1'] = !empty($posttime1_str)?$posttime1_str:'';
            $get_data['posttime2'] = !empty($posttime2_str)?$posttime2_str:'';
            $get_data['sotype'] = !empty($sotype)?$sotype:'';
            $get_data['sosotext'] = !empty($sosotext)?$sosotext:'';
        }
        if($get_data) {
            if($get_data['language']) {
                $list_where .= "`language` = '{$get_data['language']}' AND ";
            }
            if($posttime1 && $posttime2){
                if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");
                $list_where = "`time` > '$posttime1' AND `time` < '$posttime2' AND ";
            }    
            if($posttime1 && empty($posttime2)){
                $list_where = "`time` > '$posttime1' AND ";
            }    
            if($posttime2 && empty($posttime1)){
                $list_where = "`time` < '$posttime2' AND ";
            }
    
            // if(empty($posttime1) && empty($posttime2)){
            //     $list_where = false;
            // }
    
            if(!empty($sosotext)){
                if($sotype == 'cateid'){
                    $sosotext = intval($sosotext);
                    if($list_where)
                        $list_where .= " `cateid` = '$sosotext' AND ";
                    else
                        $list_where = "`cateid` = '$sosotext' AND ";
                }
    
                if($sotype == 'brandid'){
                    $sosotext = intval($sosotext);
                    if($list_where)
                        $list_where .= " `brandid` = '$sosotext' AND ";
                    else
                        $list_where = "`brandid` = '$sosotext' AND ";
                }
    
                if($sotype == 'brandname'){
                    $sosotext = htmlspecialchars($sosotext);
                    $info = $this->db->GetOne("SELECT * FROM `@#_brand` where `name` LIKE '%$sosotext%' LIMIT 1");
    
                    if($list_where && $info)
                        $list_where .= " `brandid` = '$info[id]' AND ";
                    elseif ($info)
                        $list_where = "`brandid` = '$info[id]' AND ";
                    else
                        $list_where = "1";
                }
    
                if($sotype == 'catename'){
                    $sosotext = htmlspecialchars($sosotext);
                    $info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` LIKE '%$sosotext%' LIMIT 1");
                    if($list_where && $info)
                        $list_where .= " `cateid` = '$info[cateid]' AND";
                    elseif ($info)
                        $list_where = "`cateid` = '$info[cateid]' AND";
                    else
                        $list_where = "1";
                }
    
                if($sotype == 'title'){
                    $sosotext = htmlspecialchars($sosotext);
                    $list_where = "`title` like '%$sosotext%' AND";
                }
    
                if($sotype == 'id'){
                    $sosotext = intval($sosotext);
                    $list_where = "`id` = '$sosotext' AND";
                }
            }else{
                if(!$list_where) $list_where='1 AND';
            }
        }

        if($cateid){
            if($cateid=='jiexiaook'){
                $list_where.= " `q_uid` is not null";
            }
            if($cateid=='maxqishu'){
                $list_where.= " `qishu` = `maxqishu` and `q_end_time` is not null ";
            }
            if($cateid=='renqi'){
                $list_where.= " `renqi` = '1' and `q_uid` is null";
            }
            if($cateid=='xianshi'){
                $list_where.= " `xsjx_time` != '0'";
            }
            if($cateid=='qishu'){
                $list_where.= " 1 and `q_uid` is null order by `qishu` DESC";
                // $this->ment[4][1]="期数正序";
                // $this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishuasc";
            }
            if($cateid=='qishuasc'){
                $list_where.= " 1 and `q_uid` is null order by `qishu` ASC";
                // $this->ment[4][1]="期数正序";
                // $this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishuasc";
            }
            if($cateid=='danjia'){
                $list_where.= " 1  and `q_uid` is null order by `yunjiage` DESC";
                // $this->ment[5][1]="单价正序";
                // $this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjiaasc";
            }
            if($cateid=='danjiaasc'){
                $list_where.= " 1 and `q_uid` is null order by `yunjiage` ASC";
                // $this->ment[5][1]="单价倒序";
                // $this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjia";
            }
            if($cateid=='money'){
                $list_where.= " 1 and `q_uid` is null order by `money` DESC";
                // $this->ment[6][1]="商品价格正序";
                // $this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/moneyasc";
            }
            if($cateid=='moneyasc'){
                $list_where.= " 1 and `q_uid` is null order by `money` ASC";
                // $this->ment[6][1]="商品价格正序";
                // $this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/moneyasc";
            }
            if($cateid==''){
                $list_where.= " `q_uid` is null  order by `id` DESC";
            }
            if(intval($cateid)){
                $list_where.= " `cateid` = '$cateid' order by `pos` desc";
            }
        }elseif($cate = _htmtocode($_POST['cate'])){
            $list_where.= " `cateid` = '$cate' order by `pos` desc";
        }else{
            // $list_where.= " `q_uid` is null  order by `cateid` DESC,`pos` desc";
            $list_where.= " `q_uid` is null  order by `order` desc,`id` DESC";
        }
        $num=100;
        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE ssc !=1 and ispk !=1 and $list_where");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0", $get_data);
        $field = "xsjx_time,cateid,canyurenshu,zongrenshu,yunjiage,qishu,maxqishu,money,id,sid,title,pos,renqi,`order`,preferential_price,language";
        if($cateid=='maxqishu'){
            $shoplist=$this->db->GetPage("SELECT $field FROM `@#_shoplist_copy` WHERE ssc !=1 and ispk !=1 and $list_where",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
            $oderShopList = $shoplist;
        }else{
            $shoplist=$this->db->GetPage("SELECT $field FROM `@#_shoplist` WHERE ssc !=1 and ispk !=1 and $list_where ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
            $oderShopLists = $this->db->GetList("SELECT qishu,sid FROM `@#_shoplist_copy` WHERE ssc !=1 and ispk !=1 limit 1000");
            $oderShopList = $oderShopLists;
        }
        $languages = $this->getLanguageList(1);
        $lgs = array_column($languages,"country","lg_value");
        $qishuArray = [];
        foreach($shoplist as $k => &$v){
            foreach ($oderShopList?:[] as $k1 => $v1) {
                if($v['sid'] == $v1['sid']){
                    $qishuArray[$v['sid']][] = $v1['qishu'];
                }
            }
            $v['oderQishu'] = min($qishuArray[$v['sid']]?:[0]) ?: $v['qishu'] ;
            $id = $v['cateid'];
            $v['fl'] = $this->db->GetOne("SELECT `name` FROM `@#_category` WHERE  `cateid`= $id");
            $v['lan'] = $lgs[$v['language']];
        }
        $formot_array = ["money","yunjiage","preferential_price"];
        // data_format($shoplist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right'],0);
        $cry = $this->getCurrencyList();
        data_format($shoplist,$formot_array,"price",$cry,'',0);
        $is_preferential = System::load_sys_config("system",'is_preferential');
        $cate_list = $this->db->GetList("SELECT `cateid`,`name` FROM `@#_category` where `model` = '1' and `parentid` = '0' order by `order` desc");
        include $this->tpl(ROUTE_M,'shop.lists');
    }

    // 商品批量删除
    public function shop_del(){
        $arr=$_POST['ids'];
//        $newstr = substr($arr,0,strlen($arr)-1);
        // file_put_contents('err.txt', '$newstr---'.var_export($newstr,TRUE)."\n", FILE_APPEND);
        $ids = explode(',',$arr);
        $this->db->Autocommit_start();
        foreach($ids as $id){
            $q1 = $this->db->Query("INSERT INTO `@#_shoplist_del` select * from `@#_shoplist` where `id` = '$id'");
        }
        $q2 = $this->db->Query("delete from `@#_shoplist` where id IN($arr)");
        if($q2){
        $this->db->Autocommit_commit();
            echo'0';
        }else{
        $this->db->Autocommit_rollback();
            echo '1';
        }
    }

    // 商品批量删除
    public function sscshop_del(){
        $arr=$_POST["ids"];
        $newstr = substr($arr,0,strlen($arr)-1);
      // file_put_contents('err.txt', '$newstr---'.var_export($newstr,TRUE)."\n", FILE_APPEND);
        $this->db->Autocommit_start();
        $q2 = $this->db->Query("delete from `@#_shoplist` where id IN($newstr)");
        if($q2){
        $this->db->Autocommit_commit();
            echo'0';
        }else{
        $this->db->Autocommit_rollback();
            echo '1';
        }
    }

    public function r_submit(){
        setcookie('gl_where','');
    }

    /**
     * 批量重置价格
     *
     */
    public function goods_batch_reset(){
        $ids = $_POST['ids'];
        if(empty($ids)) $this->success(0,'请传递正确参数');
        $ids = substr($ids,0,strlen($ids)-1);
        if(count(explode(',',$ids))>30) $this->success(0,'每次最多重置选择30个商品');
        $shopinfolist = $this->db->GetList("SELECT * FROM `@#_shoplist` where id IN ($ids)");
        if(empty($shopinfolist))$this->success(0,'查询错误');

        System::load_app_fun("content");

        foreach ($shopinfolist as $key=>$value){
            if($value['sid'] == 0){
                $this->db->Query("UPDATE `@#_shoplist` SET `sid` = $value[id] where `id` = $value[id]");
            }
            $table = $value['codes_table']; //获取当前期数表
            $sql = "SELECT uid,SUM(moneycount) AS money FROM `@#_member_go_record` WHERE `shopid` = ".$value['id']." GROUP BY `uid`";
            /*查询购买记录*/
            $lists = $this->db->GetList($sql);
            /*退款*/
            if(!empty($lists)){
                foreach ($lists as $k => $v) {
                    $money = $v['money'];
                    $uid = $v['uid'];
                    $time = time();
                    $pay_type = '商品'.$value['id'].'-'.$value['qishu'].'期开奖失败退'.$money.'元';
                    $this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
                    $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time");
                }
            }
            $q1 = $this->db->Query("DELETE FROM `@#_member_go_record` WHERE `shopid` = {$value['id']}");
            $q2 = $this->db->Query("DELETE FROM `@#_{$table}` WHERE `s_id` = {$value['id']}");
            $q3 = content_get_go_codes($value['zongrenshu'],3000,$value['id']);

            $zrs = $value['zongrenshu'];
            $money =$value['zongrenshu'];
            $spid = $value['id'];
            $yjg = $value['yunjiage'];
            $q4 = $this->db->Query("UPDATE `@#_shoplist` SET 

            `canyurenshu` = '0',

            `zongrenshu` = '$zrs', 

            `money` = '$money', 

            `yunjiage` = '$yjg',

            `shenyurenshu` = `zongrenshu`

            where `id` = '$spid'");

            if(!$q1 && !$q2 && !$q3 && !$q4){

                $this->db->Autocommit_rollback();

                $this->success(0,'重置失败');

            }

        }
        $this->db->Autocommit_commit();
        $this->success(1,'重置成功');
    }

    public function success($code = 1,$msg = '',$data=''){
        $data = array('code'=>$code,'msg'=>$msg,'data' =>$data);
        echo json_encode($data);
        exit();
    }

    /* PK商品列表 */
    public function pkgoods_list(){
        $this->ment=array(
            array("lists","PK商品列表",ROUTE_M.'/'.ROUTE_C."/pkgoods_list"),

            array("add","添加PK商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),
        );
        $num=20;
        $list_where = "`q_uid` is  null";

        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE ispk =1 and $list_where ");

        $page=System::load_sys_class('page');

        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}

        $page->config($total,$num,$pagenum,"0");

        $shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist` WHERE ispk =1 and $list_where  ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

        include $this->tpl(ROUTE_M,'shop.lists');
    }
    /* 时时彩商品列表 */
    public function sscgoods_list(){
        $this->ment=array(

            array("lists","时时彩商品",ROUTE_M.'/'.ROUTE_C."/sscgoods_list"),

            array("add","添加时时彩商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),
        );
        $list_where = '';

        $cateid=$this->segment(4);

        if(isset($_POST['sososubmit'])){

            $posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;

            $posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;

            $sotype = $_POST['sotype'];

            $sosotext = $_POST['sosotext'];

            if($posttime1 && $posttime2){

                if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");

                $list_where = "`time` > '$posttime1' AND `time` < '$posttime2' AND ";

            }

            if($posttime1 && empty($posttime2)){

                $list_where = "`time` > '$posttime1' AND ";

            }

            if($posttime2 && empty($posttime1)){

                $list_where = "`time` < '$posttime2' AND ";

            }

            if(!empty($sosotext)){

                if($sotype == 'cateid'){

                    $sosotext = intval($sosotext);

                    if($list_where)

                        $list_where .= " `cateid` = '$sosotext' AND ";

                    else

                        $list_where = "`cateid` = '$sosotext' AND ";

                }

                if($sotype == 'brandid'){

                    $sosotext = intval($sosotext);

                    if($list_where)

                        $list_where .= " `brandid` = '$sosotext' AND ";

                    else

                        $list_where = "`brandid` = '$sosotext' AND ";

                }

                if($sotype == 'brandname'){

                    $sosotext = htmlspecialchars($sosotext);

                    $info = $this->db->GetOne("SELECT * FROM `@#_brand` where `name` LIKE '%$sosotext%' LIMIT 1");

                    if($list_where && $info)

                        $list_where .= " `brandid` = '$info[id]' AND ";

                    elseif ($info)

                        $list_where = "`brandid` = '$info[id]' AND ";

                    else

                        $list_where = "1";

                }

                if($sotype == 'catename'){

                    $sosotext = htmlspecialchars($sosotext);

                    $info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` LIKE '%$sosotext%' LIMIT 1");

                    if($list_where && $info)

                        $list_where .= " `cateid` = '$info[cateid]' AND";

                    elseif ($info)

                        $list_where = "`cateid` = '$info[cateid]' AND";

                    else

                        $list_where = "1";

                }

                if($sotype == 'title'){

                    $sosotext = htmlspecialchars($sosotext);

                    $list_where = "`title` like '%$sosotext%' AND";

                }

                if($sotype == 'id'){

                    $sosotext = intval($sosotext);

                    $list_where = "`id` = '$sosotext' AND";

                }

            }else{

                if(!$list_where) $list_where='1 AND';

            }
            setcookie('gl_where',$list_where);
        }

        $w=$_COOKIE['gl_where'];
        if(!$list_where && $w){
            $list_where=stripslashes($w);
        }

        if($cateid){

            if($cateid=='jiexiaook'){

                $list_where.= " `q_uid` is not null";

            }

            if($cateid=='maxqishu'){

                $list_where.= " `qishu` = `maxqishu` and `q_end_time` is not null";

            }
            if($cateid=='renqi'){

                $list_where.= " `renqi` = '1' and `q_uid` is null";

            }
            if($cateid=='xianshi'){

                $list_where.= " `xsjx_time` != '0'";

            }
            if($cateid=='qishu'){

                $list_where.= " 1 and `q_uid` is null order by `qishu` DESC";

                $this->ment[4][1]="期数正序";

                $this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishuasc";

            }
            if($cateid=='qishuasc'){

                $list_where.= " 1 and `q_uid` is null order by `qishu` ASC";

                $this->ment[4][1]="期数倒序";

                $this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishu";

            }
            if($cateid=='danjia'){

                $list_where.= " 1  and `q_uid` is null order by `yunjiage` DESC";

                $this->ment[5][1]="单价正序";

                $this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjiaasc";

            }
            if($cateid=='danjiaasc'){

                $list_where.= " 1 and `q_uid` is null order by `yunjiage` ASC";

                $this->ment[5][1]="单价倒序";

                $this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjia";

            }
            if($cateid=='money'){

                $list_where.= " 1 and `q_uid` is null order by `money` DESC";

                $this->ment[6][1]="商品价格正序";

                $this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/moneyasc";

            }
            if($cateid=='moneyasc'){

                $list_where.= " 1 and `q_uid` is null order by `money` ASC";

                $this->ment[6][1]="商品价格倒序";

                $this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/money";

            }
            if($cateid==''){

                $list_where.= " `q_uid` is null  order by `id` DESC";

            }
            if(intval($cateid)){

                $list_where.= " `cateid` = '$cateid'";

            }

        }else{

            $list_where.= " `q_uid` is null  order by `id` DESC";

        }
        $num=20;

        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE ssc =1 and $list_where ");

        $page=System::load_sys_class('page');

        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}

        $page->config($total,$num,$pagenum,"0");

        $shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist` WHERE ssc =1 and $list_where  ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

        include $this->tpl(ROUTE_M,'shop.ssc_lists');
    }

    /* 单个商品的购买详细 */
    public function goods_go_one(){
        $gid = intval($this->segment(4));

        $this->ment = array(
            array("lists","往期商品列表",ROUTE_M."/content/goods_list"),
            array("lists","购买详情",ROUTE_M."/content/goods_go_one/".$gid),
        );

        $post_userdisp = 0;
        if ( isset($_POST["userdisp"]) ) {
            $post_userdisp = $_POST["userdisp"];
        }

        $ginfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' limit 1");
        if(!$ginfo){
            $ginfo = $this->db->GetOne("select * from `@#_shoplist_copy` where `id` = '$gid' limit 1");
        }
        if(!$ginfo)_message("没有找到这个商品");

        $total=$this->db->GetCount("select * from `@#_member_go_record` where `shopid` = '$gid' order by `id` DESC");

        $page=System::load_sys_class('page');

        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}

        $page->config($total,20,$pagenum,"0");

        $total_user = 0;
        if ( $post_userdisp <= 0 ) {
            $go_list = $this->db->GetPage("select * from `@#_member_go_record` where `shopid` = '$gid' order by `id` desc",array("num"=>20,"page"=>$pagenum,"type"=>1,"cache"=>0));
        } else {
            $total_user=$this->db->GetCount("select * from `@#_member_go_record` where `shopid` = '$gid' and `uid`='$post_userdisp' order by `id` desc");

            $go_list = $this->db->GetPage("select * from `@#_member_go_record` where `shopid` = '$gid' and `uid`='$post_userdisp' order by `id` desc",array("num"=>20,"page"=>$pagenum,"type"=>1,"cache"=>0));
        }

        $go_list_alluser = $this->db->GetList("select `username`, `uid` from `@#_member_go_record` where `shopid` = '$gid' order by `uid`");
        data_format($ginfo,['money','yunjiage'],"price",$this->currency['symbol_left'],$this->currency['symbol_right']);

        include $this->tpl(ROUTE_M,'shop.go_list');
    }

    public function goods_one_ok(){
        $gid = intval($this->segment(4));

        $ginfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' limit 1");

        if(!$ginfo)_messageerror("没有找到这个商品");

        $jinri_time = time();

        if($ginfo['xsjx_time']!='0')_messageerror("限时获得商品不能手动揭晓");

        if($ginfo['shenyurenshu']!='0')_messageerror("该商品还有剩余人数,不能手动揭晓！");

        if($ginfo['shenyurenshu']=='0' && (empty($ginfo['q_uid']) || $ginfo['q_uid']=='')){

            System::load_app_fun("pay","pay");

            $this->db->Autocommit_start();
            if($ginfo['ssc'] ==1){
                $ok = pay_insert_shop_ssc($ginfo);
            }else{
                $ok = pay_insert_shop($ginfo);
            }

            if(!$ok){

                $this->db->Autocommit_rollback();

                _messageerror("揭晓失败!");

            }else{

                $this->db->Autocommit_commit();

                _message("揭晓成功!");

            }

        }

    }

    //商品回收站 下架商品列表

    public function goods_del_list(){

        $this->ment=array(
                        array("goods_del_list","下架商品列表",ROUTE_M.'/'.ROUTE_C."/goods_del_list"),

                        array("lists","返回商品列表",ROUTE_M.'/'.ROUTE_C."/goods_list"),

                        array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

        );
        $num=20;
        $total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist_del` WHERE 1");
        $page=System::load_sys_class('page');
        if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
        $page->config($total,$num,$pagenum,"0");
        $shoplist=$this->db->GetPage("SELECT d.*,a.uid,a.status,a.id as examine_id FROM `@#_shoplist_del` d left join `@#_shoplist_add` a on a.shopid = d.id WHERE 1",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
        $formot_array = ["money","yunjiage","preferential_price"];
        $cry = $this->getCurrencyList();
        data_format($shoplist,$formot_array,"price",$cry);
        include $this->tpl(ROUTE_M,'shop.del');
    }

    //编辑商品
    public function goods_edit(){

        $this->ment=array(
            array("lists","云购商品列表",ROUTE_M.'/'.ROUTE_C."/goods_list"),
            array("insert","添加云购商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

        );

        $this->db->Autocommit_start();

        $shopid=intval($this->segment(4));

        $shopinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$shopid' and `qishu` order by `qishu` DESC LIMIT 1 for update");

        if($shopinfo['q_end_time'])_messageerror("该商品已经揭晓,不能修改!",G_MODULE_PATH.'/content/goods_list');

        if(!$shopinfo)_messageerror("参数不正确");

        if(isset($_POST['dosubmit'])){

           if(!empty($_POST['thumb'])) $thumb = $_POST['thumb'];

            if(!empty($_POST['imgarrList'])){
                $imgarr = $_POST['imgarrList'];
                if(count($imgarr)>10){
                    _messageerror('最多上传10张 图片');
                }
                $picarr=serialize($imgarr);
            }
            $cateid = intval($_POST['cateid']);

            $brandid = intval($_POST['brand']);

            $title = htmlspecialchars($_POST['title']);
            $title = _htmtocode($title);

            $title_color = htmlspecialchars($_POST['title_style_color']);

            $title_bold = htmlspecialchars($_POST['title_style_bold']);

            $title2 = htmlspecialchars($_POST['title2']);

            $keywords = htmlspecialchars($_POST['keywords']);

            $description = htmlspecialchars($_POST['description']);
            $order = htmlspecialchars($_POST['order']);

            $content = editor_safe_replace(stripslashes($_POST['editorValue']));
            $content = str_replace("'","\'", $content);
            $content = str_replace('"','\"', $content);
            $maxqishu = intval($_POST['maxqishu']) ? intval($_POST['maxqishu']) : 1;
            $preferential_price = intval($_POST['preferential_price']);
            if(!$preferential_price){
                $preferential_price = $shopinfo['money'];
                // _messageerror("优惠价不能为空");
            }
            $goods_key_pos = isset($_POST['goods_key']['pos']) ? 1 : 0;

            $language = $_POST['language'];
            // if(!$language)_messageerror("请选择语言");

            if(!$cateid)_messageerror("请选择栏目");

            if(!$brandid)_messageerror("请选择品牌");

            if(!$title)_messageerror("标题不能为空");

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
                _messageerror("最大期数不能超过65535期");
            }
            if($maxqishu < $shopinfo['qishu']){
                _messageerror("最期数不能小于当前期数！");
            }

            if ($goods_key_pos == 1) {
                $re = $this->db->GetOne("SELECT `pos` FROM `@#_shoplist` WHERE `id` = '$shopid'");
                if($re['pos'] == 0){
                    $re = $this->db->GetOne("SELECT count(id) as num FROM `@#_shoplist` WHERE `cateid`='$cateid' AND `pos`=1");
                    if ($re['num']>1) {
                        _messageerror("单个类别只能有2个推荐!");
                        exit;
                    }
                }
            }

            $sql="UPDATE `@#_shoplist` SET `cateid` = '$cateid',
                                           `brandid` = '$brandid',
                                           `title` = '$title',
                                           `title_style` = '$title_style',
                                           `title2` = '$title2',
                                           `keywords`='$keywords',
                                           `description`='$description',
                                           `thumb` = '$thumb',
                                           `picarr` = '$picarr',
                                           `content` = '$content',                                        
                                           `maxqishu` = '$maxqishu',
                                           `xsjx_time` = '$xsjx_time',
                                           `pos` = '$goods_key_pos',
                                           `language` = '$language',
                                           `preferential_price` = '$preferential_price',
                                           `order` = '$order'
                                            WHERE `id`='$shopid'

            ";
            $s_sid = $shopinfo['sid'];
            $this->db->Query("UPDATE `@#_shoplist` SET `maxqishu` = '$maxqishu' where `sid` = '$s_sid'");

            if($this->db->Query($sql)){
                $this->db->Autocommit_commit();
                _message("修改成功!",WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

            }else{
                $this->db->Autocommit_rollback();
                _messageerror("修改失败!");
            }
        }

        $cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$shopinfo[cateid]' LIMIT 1");

        $BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>'id'));

        $categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `order` desc,`parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
        foreach($categorys as $id=>&$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
        }
        // $tree=System::load_sys_class('tree');

        // $tree->icon = array('│ ','├─ ','└─ ');

        // $tree->nbsp = '&nbsp;';

        // $categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

        // $tree->init($categorys);

        // $categoryshtml=$tree->get_tree(0,$categoryshtml);

        // $categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';

        $lg = $this->db->GetPage("SELECT * FROM `@#_language` WHERE `status` = 1 and type = 1");

        if($shopinfo['title_style']){
            if(stripos($shopinfo['title_style'],"font-weight:")!==false){
                $title_bold = 'bold';
            }else{
                $title_bold = '';
            }

            if(stripos($shopinfo['title_style'],"color:")!==false){
                $title_color = explode(';',$shopinfo['title_style']);
                $title_color = explode(':',$title_color[0]);
                $title_color = $title_color[1];
            }else{
                $title_color = '';
            }
        }else{
            $title_color='';
            $title_bold = '';
        }
        $shopinfo['picarr'] = unserialize($shopinfo['picarr']);
        if($shopinfo['xsjx_time']){
            $shopinfo['xsjx_time_1'] = date("Y-m-d",$shopinfo['xsjx_time']);
            $shopinfo['xsjx_time_h'] = $shopinfo['xsjx_time'] - strtotime($shopinfo['xsjx_time_1']);
            $shopinfo['xsjx_time'] = $shopinfo['xsjx_time_1'];
        }else{
            $shopinfo['xsjx_time']='';
            $shopinfo['xsjx_time_h']=79200;
        }
        $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
        $shopinfo['preferential_price'] = ceil($shopinfo['preferential_price']);
                $formot_array = ["money","yunjiage"];
        data_format($shopinfo,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
        $is_preferential = System::load_sys_config("system",'is_preferential');
        include $this->tpl(ROUTE_M,'shop.edit');
    }

    //添加商品
    public function goods_add(){
        System::load_sys_fun('redisData');
        $redisClass = new redisData();
        $tempData = $redisClass->redisGet("tempData")['data'];
        if($tempData){
            $tempData = json_decode($tempData,true);
        }else{
            $tempData = [];
        }
        $is_group = $this->getConfig("is_group")?:2;

        if(isset($_POST['dosubmit'])){
            $tempData = json_encode($_POST);
            $redisClass->redisSetex("tempData","5",$tempData);
            $thumb = $_POST['thumb'];
            if(empty($thumb)){
                _messageerror('缩略图不能为空');
            }
            $imgarr = $_POST['imgarrList'];
            if(count($imgarr)>10){
                _messageerror('最多上传10张 图片');
            }
            $picarr=serialize($imgarr);
            $cateid = intval($_POST['cateid']);
            $brandid = intval($_POST['brand']);
            $title = htmlspecialchars($_POST['title']);
            $title = _htmtocode($title);
            $title_color = htmlspecialchars($_POST['title_style_color']);
            $title_bold = htmlspecialchars($_POST['title_style_bold']);
            $title2 = htmlspecialchars($_POST['title2']);
            // $is_pk = htmlspecialchars($_POST['is_pk']);
            $is_pk = 0;
            $keywords = htmlspecialchars($_POST['keywords']);
            $description = htmlspecialchars($_POST['description']);
            $content = editor_safe_replace(stripslashes($_POST['editorValue']));
            $content = str_replace("'","\'", $content);
            $content = str_replace('"','\"', $content);
            $money = intval($_POST['money']);
            $yunjiage = intval($_POST['yunjiage']);
            $maxqishu = intval($_POST['maxqishu']);
            $order = intval($_POST['order']);
            $preferential_price = intval($_POST['preferential_price']);
            if(!$preferential_price){
                $preferential_price = $money;
                // _messageerror("优惠价不能为空");
            }
            //  $ssc = htmlspecialchars($_POST['ssc']);
            $ssc = 0;
            $canyurenshu = 0;
            $goods_key_pos = isset($_POST['goods_key']['pos']) ? 1 : 0;
			$goods_key_renqi = isset($_POST['goods_key']['renqi']) ? 1 : 0;
            $yunrenci = intval($_POST['yunrenci']);
            $language = $_POST['language'];
            // if(!$language)_message("请选择语言");
            if($is_pk){
                if(!$yunrenci){_message("请设置人数");}
                $check_money=is_int($money/$yunrenci);
                if(!$check_money)_message("商品价格不是".$yunrenci."的倍数");
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
            if(!$groups && $is_group !=2 ){
                _message("请修改价格，此价格无法进行拼团");
            }

            if(!$cateid)_messageerror("请选择栏目");
            // if(!$brandid)_messageerror("请选择品牌");
            if(!$title)_messageerror("标题不能为空");
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
                _message("最大期数不能超过65535期");
            }
            if($money < $yunjiage) _message("商品价格不能小于购买价格");
            // $zongrenshu = ceil($money/$yunjiage);
            $codes_len = ceil($zongrenshu/3000);
            $shenyurenshu = $zongrenshu-$canyurenshu;

            if($zongrenshu==0 || ($zongrenshu-$canyurenshu)==0){
                _message("云购价格不正确");
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
            ];
            $sql = Assemblydata($data,"shoplist");
            $this->db->Autocommit_start();
            $query_1 = $this->db->Query($sql);
            $shopid = $this->db->insert_id();
            System::load_app_fun("content");
            $query_table = content_get_codes_table();
            if(!$query_table){
                $this->db->Autocommit_rollback();
                _message("云购码仓库不正确!");
            }
            $query_2 = content_get_go_codes($zongrenshu,3000,$shopid);
            $query_3 = $this->db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");

            if($query_1 && $query_2 && $query_3){
                $this->db->Autocommit_commit();
                $redisClass->redisSetex("tempData","2",0);
                _message("商品添加成功!");
            }else{
                $this->db->Autocommit_rollback();
                _messageerror("商品添加失败!");
            }
            header("Cache-control: private");
        }
        $cateid=intval($this->segment(4));
        $categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `order` desc, `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
        foreach($categorys as $id=>&$cat){
            $cat['languages'] = mb_unserialize($cat['languages']);
            $cat['name'] = $cat['languages'][$this->language_backend]??$cat['name'];
        }
        if($cateid){
            $cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");
            if(!$cateinfo)_message("参数不正确,没有这个栏目",G_ADMIN_PATH.'/'.ROUTE_C.'/addarticle');
            $BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where `cateid`='$cateid'",array("key"=>"id"));
        }else{
            $BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>"id"));
        }

        $lg = $this->db->GetPage("SELECT * FROM `@#_language` WHERE `status` = 1 and type =1");

        $this->ment=array(
            array("lists","云购商品列表",ROUTE_M.'/'.ROUTE_C."/goods_list"),
            array("insert","添加云购商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),
        );
        $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
        $is_preferential = System::load_sys_config("system",'is_preferential');
        include $this->tpl(ROUTE_M,'shop.insert');
    }
    public function upimgbox(){
        include $this->tpl(ROUTE_M,'upimgbox.add');
    }
    //商品图片上传
    public function upimage(){

        $data = htmlspecialchars($_POST['url']);
        $path=$data?$data:'shopimg';
        System::load_sys_class('upload','sys','no');
        if($_FILES['file']['error'] == '0'){
            upload::upload_config($path);
            $_FILES['file']['topic'] = $path;
            $_FILES['file']['user_id'] = $this->AdminInfo;
            $res=upload::go_uploads($_FILES['file']);
            if(!$res['ok']){
                $res['code']=0;
                echo json_encode($res);exit();
            }else{
                $data = array('code'=>1,'msg'=>$res['msg'],'data'=>['url'=>G_UPLOAD_PATH.'/'.$path.'/'.$res['dir'],'url2'=>$path.'/'.$res['dir']]);
                echo json_encode($data);
            }
        }
    }

    //商品设置

    public function goods_set(){
        $p_key = $this->segment(4);
        $p_val = intval($this->segment(5));
        $state = $_POST['state'];
        if($state != 1){
            $state = 0;
        }
        if(empty($p_key) || empty($p_val)){
            $this->jsonData->error();
            _messageerror("设置失败");
        }
        $tabel = 'shoplist';
        if(isset($_POST['model']) && $_POST['model']==2){
            $tabel = 'shoplist_jifen';
        }
        $query = true;
        switch($p_key){
            case 'unset':
                $query = $this->db->Query("UPDATE `@#_$tabel` SET `pos` = '0' where `id` = '$p_val'");
                break;
            case 'pos':
                $cateid = $this->db->GetOne("SELECT `cateid` FROM `@#_$tabel` WHERE `id` = '$p_val'");
                $cateid = $cateid['cateid'];
                $re = $this->db->GetOne("SELECT count(id) as num FROM `@#_$tabel` WHERE `cateid` = '$cateid' AND `pos`=1");
                if ($re['num'] > 1 && $state) {
                    $this->jsonData->error('','单个类别只能有2个推荐');
                    exit;
                }
                $query = $this->db->Query("UPDATE `@#_$tabel` SET `pos` = $state where `id` = '$p_val'");
                break;
            case 'renqi':
                $renqi = $this->db->GetOne("SELECT `renqi` FROM `@#_$tabel` WHERE `id` = '$p_val'");
                $set = $renqi['renqi']==1?0:1;
                $sql ="UPDATE `@#_$tabel` SET `renqi` = '$set' where `id` = '$p_val'";
                $query = $this->db->Query($sql);
                break;
            case 'status':
                $sql ="UPDATE `@#_$tabel` SET `status` = '$state' where `id` = '$p_val'";
                $query = $this->db->Query($sql);
                break;
        }
        if($query){
            $this->jsonData->success('200',"设置成功");
            _message("设置成功");
        }else{
            $this->jsonData->error();
            _messageerror("设置失败");

        }

    }

    //ajax 删除文章

    public function article_del(){

        $id=intval($this->segment(4));

        $this->db->Query("DELETE FROM `@#_article` WHERE (`id`='$id') LIMIT 1");

            if($this->db->affected_rows()){
                _message("删除成功");
                echo WEB_PATH.'/'.ROUTE_M."/content/article_list";

            }else{
                _messageerror("删除失败");
                echo "no";

            }

    }

    //ajax 下架商品

    public function goods_del(){

        $shopid=intval($this->segment(4));

        $this->db->Autocommit_start();

        $q1 = $this->db->Query("INSERT INTO `@#_shoplist_del` select * from `@#_shoplist` where `id` = '$shopid'");
        $q2 = $this->db->Query("DELETE FROM `@#_shoplist` WHERE `id` = '$shopid' LIMIT 1");
        if($q1 && $q2){

            $this->db->Autocommit_commit();

            _message("下架成功", WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

        }else{

            $this->db->Autocommit_rollback();

            _messageerror("下架失败", WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

        }

        exit;

    }
    //ajax 删除期数商品

    public function qishugoods_del(){

        $shopid=intval($this->segment(4));

        $this->db->Autocommit_start();

        $q1 = $this->db->Query("DELETE FROM `@#_shoplist_copy` WHERE `id` = '$shopid' LIMIT 1");

        $q2 = $this->db->Query("DELETE FROM `@#_member_go_record` WHERE `shopid` = '$shopid'");

        if($q1 && $q2){

            $this->db->Autocommit_commit();

            _message("删除成功", WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

        }else{

            $this->db->Autocommit_rollback();

            _message("删除失败", WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

        }

        exit;

    }
  
      public function qishugoods_del1(){

        $shopid=$_POST['id'];
        $this->db->Autocommit_start();

        $q1 = $this->db->Query("DELETE FROM `@#_shoplist_copy` WHERE `id` = '$shopid' LIMIT 1");

        $q2 = $this->db->Query("DELETE FROM `@#_member_go_record` WHERE `shopid` = '$shopid'");
        if($q1 && $q2){

            $this->db->Autocommit_commit();

            echo json_encode(['code'=>1,'msg'=>'删除成功']);
            exit;

        }else{

            $this->db->Autocommit_rollback();

            echo json_encode(['code'=>1,'msg'=>'删除成功']);
            exit;
        }

        exit;

    }

    /**
     * 入驻商添加商品审核
     */
    public function goods_add_examine(){
        $id = intval($this->segment(4));
        $status = $_POST['status']==1?2:3;
        $examine = $this->db->GetOne("select * from `@#_shoplist_add` where `id` = '$id'");
        $shopid = $examine['shopid'];
        $shop = $this->db->GetOne("select * from `@#_shoplist_del` where `id` = ".$shopid);
        $this->db->Autocommit_start();
        $result = [];
        if($status == 2){
            $result['in_shop'] = $this->db->Query("INSERT INTO `@#_shoplist` select * from `@#_shoplist_del` where `id` = '$shopid'");
            $result['del_shop_del'] = $this->db->Query("DELETE FROM `@#_shoplist_del` WHERE `id` = '$shopid' LIMIT 1");
            if(!$shop['codes_table']){
                System::load_app_fun("content");
                $query_table = content_get_codes_table();
                if(!$query_table){
                    $this->db->Autocommit_rollback();
                    _message("云购码仓库不正确!");
                }
                $$result['codes_table'] = content_get_go_codes($shop['zongrenshu'],3000,$shopid);
                $canyurenshu = $shop['canyurenshu'];
                $$result['up_shop'] = $this->db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");
            }elseif($examine['is_money']){
                //修改价格
                System::load_app_fun("content");
                $zongrenshu = $shop['zongrenshu'];
                $table = $shop['codes_table'];
                //清除正在拼团的商品
                $glist = $this->db->GetList("select * from `@#_orders_group` where `shopid` = '$shopid' and `group_status` = 1");
                if($glist){
                    foreach($glist as $vl){
                        $uid = $vl['uid'];
                        $money = $vl['money'];
                        $pay_type = '商品'.$shopid.'-'.$shop['qishu'].'期拼团失败退'.$money.'元';
                        $time = time();
                        //金额退回用户
                        $this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
                        //记录原因
                        $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time");
                    }
                    $result['del_order_group'] = $this->db->Query("DELETE FROM `@#_orders_group` WHERE `shopid` = '$shopid' and `group_status` = 1");
                }
                // 查询出所有的购买记录
                $lists = $this->db->GetList("SELECT uid,SUM(moneycount) AS money FROM `@#_member_go_record` WHERE `shopid` = '$shopid' GROUP BY `uid`");
                if(!empty($lists)){
                    foreach ($lists as $k => $v) {
                        $money = $v['money'];
                        $uid = $v['uid'];
                        $time = time();
                        $pay_type = '商品'.$shopid.'-'.$shop['qishu'].'期开奖失败退'.$money.'元';
                        $this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
                        $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time");
                    }
                    $result['del_order'] = $this->db->Query("DELETE FROM `@#_member_go_record` WHERE `shopid` = '$shopid'");
                }
                $result['del_codes_table'] = $this->db->Query("DELETE FROM `@#_{$table}` WHERE `s_id` = '$shopid'");
                $result['in_codes_table'] = content_get_go_codes($zongrenshu,3000,$shopid,"@#_" . $table);
                $result['up_shop'] = $this->db->Query("UPDATE `@#_shoplist` SET 
                    `canyurenshu` = '0',
                    `shenyurenshu` = `zongrenshu`
                    where `id` = '$shopid'");
            }
        }
        $sql = Assemblydata([
            'status'=>$status,
            "is_money" => 0,
            'data'=>null
        ],"shoplist_add",'up',[['id',$id]]);
        $result['up_shop_add'] = $this->db->Query($sql);
        foreach($result as $v){
            if(!$v){
                $this->db->Autocommit_rollback();
                _messageerror("审核失败!");
            }
        }
        $this->db->Autocommit_commit();
        _message("审核成功!");
    }

    // 撤销删除

    public function goods_del_key(){

        $shopid=intval($this->segment(4));

        $key=$this->segment(5);

        //撤销    

        if($key=='yes'){

            $this->db->Autocommit_start();

            $q1 = $this->db->Query("INSERT INTO `@#_shoplist` select * from `@#_shoplist_del` where `id` = '$shopid' LIMIT 1");

            $q2 = $this->db->Query("DELETE FROM `@#_shoplist_del` WHERE `id` = '$shopid' LIMIT 1");

            if(!$q1 || !$q2){

                $this->db->Autocommit_rollback();

                _message("操作失败");

            }else{

                $this->db->Autocommit_commit();

                _message("操作成功");

            }

        }

        //从数据库删除

        if($key=='no'){
            $shopinfo = $this->db->GetOne("SELECT FROM `@#_shoplist_del` WHERE `id` = '$shopid'");
            $sid = $shopinfo['sid'];
            $delshop =  $this->db->Query("DELETE FROM `@#_shoplist_del` WHERE `id` = '$shopid' LIMIT 1");
            $delshopcopy = $this->db->Query("DELETE FROM `@#_shoplist_copy` WHERE `sid` = '$sid'");
            _message("操作成功");

        }

    }

    //清空回收站

    public function goods_del_all(){

        $this->db->Query("TRUNCATE TABLE  `@#_shoplist_del`");

        _message("清空成功");

    }

    /*

    *   商品排序

    */

    public function goods_listorder(){

        if($this->segment(4)=='dosubmit'){

            foreach($_POST['listorders'] as $id => $listorder){

                $id = intval($id);

                $listorder = intval($listorder);

                $this->db->Query("UPDATE `@#_shoplist` SET `order` = '$listorder' where `id` = '$id'");

            }

            _message("排序更新成功");

        }else{

            _message("请排序");

        }

    }

    /*
`
    *   文章排序

    */

    public function article_listorder(){

        if($this->segment(4)=='dosubmit'){

            foreach($_POST['listorders'] as $id => $listorder){

                $id = intval($id);

                $listorder = intval($listorder);

                $this->db->Query("UPDATE `@#_article` SET `order` = '$listorder' where `id` = '$id'");

            }

            _message("排序更新成功");

        }else{

            _message("请排序");

        }

    }

    public function listorder(){

        if($this->segment(4)=='dosubmit'){

            foreach($_POST['listorders'] as $id => $listorder){

                $id = intval($id);

                $listorder = intval($listorder);

                $this->db->Query("UPDATE `@#_wap` SET `sort` = '$listorder' where `id` = '$id'");

            }

            _message("排序更新成功");

        }else{

            _message("请排序");

        }

    }

    /*

    *   商品最大期数修改

    *   ajax

    */

    public function goods_max_qishu(){

        if($this->segment(4)!='dosubmit'){

            echo json_encode(array("meg"=>"not key","err"=>"-1"));

            exit;

        }

        if(!isset($_POST['gid']) || !isset($_POST['qishu']) || !isset($_POST['money']) || !isset($_POST['onemoney'])){

            echo json_encode(array("meg"=>"参数不正确!","err"=>"-1"));

            exit;

        }

        $gid = abs(intval($_POST['gid']));

        $qishu = abs(intval($_POST['qishu']));

        $money = abs(intval($_POST['money']));

        $onemoney = abs(intval($_POST['onemoney']));

        if(!$gid || !$qishu || !$money || !$onemoney){

            echo json_encode(array("meg"=>"参数不正确!","err"=>"-1"));

            exit;

        }

        if($money < $onemoney){

            echo json_encode(array("meg"=>" 总价不能小于云购价!","err"=>"-1"));

            exit;

        }

        $info = $this->db->GetOne("SELECT * FROM `@#_shoplist_copy` where `id` = '$gid' and `q_end_time` is not null");

        if(!$info || ($info['qishu']!=$info['maxqishu'])){

            echo json_encode(array("meg"=>"没有该商品或还有剩余期数!","err"=>"-1"));

            exit;

        }

        if($qishu <= $info['qishu']){

            echo json_encode(array("meg"=>"期数不正确!","err"=>"-1"));

            exit;

        }

        $ret = $this->db->Query("UPDATE `@#_shoplist_copy` SET `maxqishu` = '$qishu' where `sid` = '$info[sid]'");

        if(!$ret){

            echo json_encode(array("meg"=>"期数更新失败!","err"=>"-1"));

            exit;

        }

        $info['maxqishu'] = $qishu;

        $info['money'] = $money;

        $info['yunjiage'] = $onemoney;

        $info['zongrenshu'] = ceil($money/$onemoney);

        System::load_app_fun("content");
        $this->db->Autocommit_start();
        $ret = content_add_shop_install($info);

        if($ret){
            $this->db->Autocommit_commit();

            echo json_encode(array("meg"=>"新建商品成功!","err"=>"1"));

            exit;

        }else{
            $this->db->Autocommit_rollback();

            echo json_encode(array("meg"=>"更新失败失败!","err"=>"-1"));

            exit;

        }

    }

    /**

    *   重置商品价格

    **/

    public function goods_set_money(){
        $gid = abs(intval($this->segment(4)));
        $this->ment=array(
            array("lists","商品列表",ROUTE_M.'/'.ROUTE_C."/goods_list"),
                        array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),
                        array("renqi","人气商品",ROUTE_M.'/'.ROUTE_C."/goods_list/renqi"),
//                        array("xsjx","限时获得商品",ROUTE_M.'/'.ROUTE_C."/goods_list/xianshi"),
                        array("qishu","期数倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishu"),
                        array("danjia","单价倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/danjia"),
                        array("money","商品价格倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/money"),
                        array("money","已揭晓",ROUTE_M.'/'.ROUTE_C."/goods_list/jiexiaook"),
                        array("money","期数已满商品",ROUTE_M.'/'.ROUTE_C."/goods_list/maxqishu"),
                        array("money","重置商品价格",ROUTE_M."/content/goods_set_money/".$gid),
        );
        $this->db->Autocommit_start();
        $shopinfo = $this->db->GetOne("SELECT * FROM `@#_shoplist` where `id` = '$gid' for update");
        if(!$shopinfo || !empty($shopinfo['q_uid'])){
            _message("参数不正确!",G_MODULE_PATH.'/content/goods_list');exit;
        }
        $is_group = $this->getConfig("is_group")?:2;
        if(isset($_POST['money']) || isset($_POST['yunjiage'])){
            $new_money = abs(intval($_POST['money']));
            $new_one_m = abs(intval($_POST['yunjiage']));
            if($new_one_m > $new_money){
                _message("单人次购买价格不能大于商品总价格!");
            }
            if(!$new_one_m || !$new_money){
                _message("价格填写错误!");
            }
            $groups = '';
            $zongrenshu = ceil($new_money/$new_one_m);
            for($i = 2;$i<=10;$i++){
                if($zongrenshu % $i == 0){
                    if($groups){
                        $groups .= ",";
                    }
                    $groups .=$i;
                }
            }
            if(!$groups && $is_group !=2 ){
                _message("请修改总价，此价格无法进行拼团");
            }
            System::load_app_fun("content");
            $number = $this->db->GetOne("select value from `@#_caches` where `key` = 'shopcodes_table'");
            $table = 'shopcodes_'.$number['value'];
            //清除正在拼团的商品
            $glist = $this->db->GetList("select * from `@#_orders_group` where `shopid` = '$gid' and `group_status` = 1");
            if($glist){
                foreach($glist as $vl){
                    $uid = $vl['uid'];
                    $money = $vl['money'];
                    $pay_type = '商品'.$shopinfo['id'].'-'.$shopinfo['qishu'].'期拼团失败退'.$money.'元';
                    $time = time();
                    //金额退回用户
                    $this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
                    //记录原因
                    $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time");
                }
                $q1 = $this->db->Query("DELETE FROM `@#_orders_group` WHERE `shopid` = '$gid' and `group_status` = 1");
            }
            // 查询出所有的购买记录
            $lists = $this->db->GetList("SELECT uid,SUM(moneycount) AS money FROM `@#_member_go_record` WHERE `shopid` = '$gid' GROUP BY `uid`");
            if(!empty($lists)){
                foreach ($lists as $k => $v) {
                    $money = $v['money'];
                    $uid = $v['uid'];
                    $time = time();
                    $pay_type = '商品'.$shopinfo['id'].'-'.$shopinfo['qishu'].'期开奖失败退'.$money.'元';
                    $this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
                    // $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`money`,`uid`,`pay_type`,`status`,`time`) VALUES ('$money', '$uid','$pay_type','已付款','$time')");
                    $this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time");
                }
            }
            $q1 = $this->db->Query("DELETE FROM `@#_member_go_record` WHERE `shopid` = '$gid'");
            $old_table = $shopinfo['codes_table'];
            $q2 = $this->db->Query("DELETE FROM `@#_{$old_table}` WHERE `s_id` = '$gid'");
            $q2_1 = $this->db->Query("DELETE FROM `@#_{$table}` WHERE `s_id` = '$gid'");
            $zongrenshu = ceil($new_money/$new_one_m);
            // var_dump($zongrenshu);
            // var_dump($shopinfo);exit;
            $q3 = $this->db->Query("UPDATE `@#_shoplist` SET 
            `canyurenshu` = '0',
            `zongrenshu` = '$zongrenshu', 
            `money` = '$new_money', 
            `yunjiage` = '$new_one_m', 
            `shenyurenshu` = `zongrenshu`,
            `groups` = '$groups',
            `codes_table` = '$table'
            where `id` = '$gid'");
            $table = "@#_" . $table;
            $q4 = content_get_go_codes($zongrenshu,3000,$shopinfo['id'],$table);
            // var_dump("DELETE FROM `@#_{$table}` WHERE `s_id` = '$gid'");exit;
            if($q1 && ($q2 || $q2_1) && $q3 && $q4){
                    $this->db->Autocommit_commit();
                    _message("更新成功!");
            }else{
                $this->db->Autocommit_rollback();
                _messageerror("更新失败!");
            }
        }
        $symbol = $this->currency['symbol_left']?:$this->currency['symbol_right'];
        if($shopinfo['language']){
            $res = $this->db->GetOne("select currency_unit from `@#_language` where `lg_value` = '$shopinfo[language]'");
            $symbol = $res['currency_unit']?:$symbol;
        }
        $shopinfo['money'] = ceil($shopinfo['money']);
        $shopinfo['yunjiage'] = ceil($shopinfo['yunjiage']);
        
        include $this->tpl(ROUTE_M,'shop.set_money');
    }
   public function open_submit(){
		$info=$this->db->GetList("SELECT * FROM `@#_shoplist` where canyurenshu=zongrenshu and q_showtime='N' and ssc=1");
       if($info){
         System::load_app_fun("pay","pay");
          foreach($info as $k=>$v){
              $ok=pay_insert_shop_ssc($v);
          }
         if($ok){
          echo 1;exit;
         }else{
          echo 3;exit;
         }

       }else{
          echo 2;exit;
       }

   }

    //同步商品
    public function synchronous(){
        $data = [
            'documentRoot'  => $_SERVER['DOCUMENT_ROOT'],
            'SynchronousData'   => 'SynchronousData'
        ];
        $documentRoot = json_encode($data);
        include $this->tpl(ROUTE_M,"shop.importdata");
    }

    public function upload_excel(){
        $file = $_FILES;
        $fileType = ['xls','excel','xlsx'];
        System::load_sys_fun('upload_file');
        $upload_file = new upload_file();
        $mkdir = '/statics/uploads/shopExcel/'. date("Ymd");
        $fileUrl = $upload_file->updateFiles($file,$fileType,$mkdir)[0];

        $synchronous = System::load_sys_config('synchronous');
        $fileNameData=explode('.',$file['file']['name']);
        $filetype=strtolower(array_pop($fileNameData));
        $excelData = $this->importFile($fileUrl,$filetype,$synchronous['synchronous']);
        System::load_sys_fun('redisData');
        $redisClass = new redisData();
        $serverUrl = getServerHttp(2);
        $redisClass->redisSet('localUrl',$serverUrl);
        //写入数据库操作
        $res = $this->setDbShopData($excelData,$synchronous['synchronousField'],$redisClass);

        foreach ($res as $v){
            $redisClass->redisHset('synchronousDataList',$v['id'],json_encode($v,true));
            $redisClass->redisSet('synchronousLanguage',$v['language']);
        }

        echo json_encode(['code'=>1,'msg'=>'Excel上传成功,准备数据导入']);
        exit;
    }

    //将导入的数据读取出来
    public function importFile($filename,$exts,$synchronous){
        //如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
            require_once 'PHPExcel/Reader/Excel5.php';
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else if ($exts == 'xlsx') {
            require_once 'PHPExcel/Reader/Excel2007.php';
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            _message('导入格式不正确');
            exit();
        }
        $PHPExcel = new \PHPExcel();
        //载入文件
        $PHPExcel = $PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        //获取总行数
        $allRow = $currentSheet->getHighestRow();

        //循环获取表中的数据，$currentRow 表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            foreach ($synchronous as $key=>$v){
                $address = $v . $currentRow;
                $cell = $currentSheet->getCell($address)->getValue();
                $data[$currentRow - 1][$v] = $cell;
            }
        }
        return $data;
    }

    /**
     * 同步商品数据到数据库
     * @param $shopData 商品数据
     */
    public function setDbShopData($shopData,$synchronous,$redisClass){
        //判断 商品类型是否存在数据库
        $cateName = [];
        $brandName = [];

        //获取远程商品类型url 获取远程url
        $downloadUrl = '';

        unset($shopData[0]);
        foreach ($shopData as $k => $v){
            $shopData[$k] = array_combine($synchronous,$v);

            !empty($shopData[$k]['cateName']) && $cateName[$shopData[$k]['cateName']] = $shopData[$k]['cateid'];
            !empty($shopData[$k]['brandName']) && $brandName[$shopData[$k]['brandName']] = $shopData[$k]['id'];

            $downloadUrl = $shopData[$k]['sid'];
        }
        //去除最后一条
        array_pop($shopData);

        //远程商品类型写入Redis
        $redisClass->redisSet('downloadUrl',$downloadUrl);
        $redisClass->redisSet('cateName',json_encode($cateName,true));
        $redisClass->redisSet('brandName',json_encode($brandName,true));

        return $shopData;
        //判断数据库是否有商品类型
        /*$shopLanguageData = $shopData[1]['language'];
        $this->db->Autocommit_start();

        //分类操作
        $classification = $this->classification($shopLanguageData,$remoteUrl,$cateName,$brandName);*/
    }

    /**
     * 商品分类操作
     * @param $shopLanguageData 商品语言
     * @param $remoteUrl  请求地址
     * @param $cateName 商品类型
     * @param $brandName 商品类型
     */
    public function classification($shopLanguageData,$remoteUrl,$cateName,$brandName){
        $getShopCate = $this->db->GetList("SELECT `language`,cateid ,`name` FROM go_category WHERE `language`='$shopLanguageData'");

        //请求云商品获取商品分类数据
        $remoteData = $this->getCategoryData($remoteUrl);

        if (empty($getShopCate) && !empty($remoteData['category'])){
            $remoteData['category'] = array_column($remoteData['category'],null,'cateid');
            //记录父类是否创建
            $parentClassId = [];
            foreach ($cateName as $v1){//循环商品分类
                foreach ($remoteData['category'] as $k1 => $v2){//循环远程获取类型分类
                    if ($v1 == $v2['name']){
                        //判断商品是否有上级
                        if ((!empty($v2['parentid']) || $v2['parentid'] > 0) && !in_array($v2['parentid'],$parentClassId)){
                            //创建父级类型
                            $parentSql = $this->setSql($remoteData['category'][$v2['parentid']]);
                            $parentCateRes = $this->db->Query($parentSql);
//                            $cateRes = $this->db->Query();

                        }
                    }
                }
                /*
                if (!$cateRes){
                    $this->db->Autocommit_rollback();
                    return ['code'=>2,'msg'=>'数据类型导入有误'];
                }*/
            }
        }
    }

    private function setSql($v2){
        return  "INSERT INTO  `@#_category` SET `language`='$v2[language]',`parentid`='$v2[parentid]',`channel`='$v2[channel]',`model`='$v2[model]',`name`='$v2[name]',`catdir`='$v2[catdir]',`pic_url`='$v2[pic_url]',`url`='$v2[url]',`info`='$v2[info]',`order`='$v2[order]'";
    }

    /**
     * 获取远程数据
     * @param $remoteUrl 云服务器接口
     */
    private function getCategoryData($remoteUrl){
        $data = json_decode(getCurl($remoteUrl),true);
        foreach ($data as $key => &$v){
            foreach ($v as &$v1){
                $v1 = json_decode($v1,true);
            }
        }
        return $data;
    }

    /**
     * 异常商品库
     */
    public function abnormal(){
        $this->ment = array(
            array("lists","异常商品列表",ROUTE_M.'/'.ROUTE_C."/abnormal"),
        );
        $datehh = date("Y-m-d H:i:s",strtotime("+1minutes"));
        $shoplist = [];
        $shoplist += $this->lottery_going_shoplist();
        $formot_array = ["money","yunjiage","preferential_price"];
        data_format($shoplist,$formot_array,"price",$this->currency['symbol_left'],$this->currency['symbol_right']);
        include $this->tpl(ROUTE_M,'abnormalshoplist');
    }

    //获取未开奖的商品
    public function lottery_going_shoplist(){
        $list=$this->db->GetList("SELECT * FROM `@#_shoplist` where `q_end_time` is not null and `q_showtime` = 'Y' AND ssc !=1 order by q_end_time asc limit 20");
        if (!$this) return [];
        foreach ($list as $key => $value) {
            $list[$key]['times']=$value['q_end_time'] - time();
            $list[$key]['error'] = '开奖失败';
            $list[$key]['errortype'] = 1;
        }
        return $list;
    }

    public function goods_tb(){
        $cateid=intval($this->segment(4));
        $categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));
        $tree=System::load_sys_class('tree');
        $tree->icon = array('│ ','├─ ','└─ ');
        $tree->nbsp = '&nbsp;';
        $categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";
        $tree->init($categorys);
        $categoryshtml=$tree->get_tree(0,$categoryshtml);
        $categoryshtml='<option value="0">≡ 请选择分类 ≡</option>'.$categoryshtml;

        if($cateid){
            $cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");
            if(!$cateinfo)_message("参数不正确,没有这个栏目",G_ADMIN_PATH.'/'.ROUTE_C.'/addarticle');
            $categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';
            $BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where `cateid`='$cateid'",array("key"=>"id"));
        }else{
            $BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>"id"));
        }
        $lg = $this->db->GetPage("SELECT * FROM `@#_language` WHERE `status` = 1");
        $this->ment=array(
            array("insert","商品采集",ROUTE_M.'/'.ROUTE_C."/goods_tb"),

        );
        if(isset($_POST["dosubmit"])){
            $itemid = $_POST['itemid'];
            // 商品详情
            $url = "http://www.taoyanhao.com/Api/Rest.ashx?type=taobaoItemDetail";
            //商品描述
            $url1 = "http://www.taoyanhao.com/Api/Rest.ashx?type=TaoItemDesc";
            $uid = "362929";
            $ticks = (string)round(microtime(true) * 1000);
            $secret = "REDACTED_CONFIGURE_LOCALLY";
            $token = md5($uid.$ticks.$itemid.$secret);
            $data = [
                "uid"=> $uid,
                "ticks"=> $ticks,
                "itemid"=> $itemid,
                "token"=>$token
            ];
            // $res = curlPost($url, $data);
            $res = $this->gs();
            if($res['success']){
                $info = $res['data'];
                $info['price'] = bcmul($info['price'],1.2,2);
                foreach($info['images'] as $k=>$v){   
                    $info['images'][$k] = downimg("shopimg","http:".$v);
                }
                // $content = curlPost($url1, $data);
                $content = $this->ct();
                if($content['success']){
                    $info['content'] = $content['data']['images'];
                }else{
                    $info['content'] = [];
                }
                $cont = "<span class='image-inline ck-widget ck-widget_with-resizer' contenteditable='false'>";
                foreach($info['content'] as $v){
                    $img_url = G_UPLOAD_PATH."/".downimg("shopimg","http:".$v);
                    $cont .= "<img src='$img_url'>";
                }
                $cont .= "</span>";
                $info['content'] = $cont;
            }else{
                // var_dump($res);
                _message("获取失败");
            }

        }
        include $this->tpl(ROUTE_M,'shop.tb');
    }
    
    public function gs(){
        $data = '{
            "data": {
                "itemid": "6409***67027",
                "title": "裙子新款2021年绵绸睡裙夏中老年加肥加大人造棉连衣裙遮肚显瘦裙",
                "images": ["//img.alicdn.com/imgextra/i4/3713831607/O1CN01WzfX1v1Nk3CzhtWEV_!!3713831607.jpg"],
                "shopName": "森***880",
                "shipFrom": "沧州",
                "categoryId": "50010850",
                "catName": "连衣裙",
                "sellCount": "5",
                "shopType": "C",
                "shopNick": "森***880",
                "shopLevel": "9",
                "price": "19.8",
                "rateSumLevel": "https://img.alicdn.com/newrank/s_blue_4.gif",
                "shopStarts": "2012-01-13 19:32:07",
                "shopGoodRate": "97.47%",
                "favors": "258",
                "totalQuantity": "6838",
                "userId": "817***772",
                "shopId": "70***742",
                "props": [{
                    "材质": "棉"
                },
                {
                    "尺码": "均码"
                },
                {
                    "图案": "花色"
                },
                {
                    "风格": "通勤"
                },
                {
                    "通勤": "民族风"
                },
                {
                    "领型": "圆领"
                },
                {
                    "腰型": "宽松腰"
                },
                {
                    "袖型": "常规"
                },
                {
                    "组合形式": "单件"
                },
                {
                    "成分含量": "95%以上"
                },
                {
                    "裙型": "其他"
                },
                {
                    "年份季节": "2021年夏季"
                },
                {
                    "袖长": "短袖"
                },
                {
                    "裙长": "中长裙"
                },
                {
                    "流行元素/工艺": "印花"
                }],
                "skus": [{
                    "skuId": "4769622562275",
                    "propPath": "20509:28383;1627207:12515873499",
                    "quantity": "189"
                },
                {
                    "skuId": "4769622562276",
                    "propPath": "20509:28383;1627207:12515873498",
                    "quantity": "193"
                },
                {
                    "skuId": "4769622562277",
                    "propPath": "20509:28383;1627207:12516427631",
                    "quantity": "196"
                }],
                "skuProps": [{
                    "pid": "20509",
                    "name": "尺码",
                    "values": [{
                        "vid": "28383",
                        "name": "均码 可收腰(适合90-18"
                    }]
                },
                {
                    "pid": "1627207",
                    "name": "颜色分类",
                    "values": [{
                        "vid": "12515873499",
                        "name": "玫红老孔雀",
                        "image": "//img.alicdn.com/imgextra/i2/817***772/O1CN01YbDuTt27Hcp58VfXd_!!817***772.jpg"
                    },
                    {
                        "vid": "12515873498",
                        "name": "天蓝老孔雀",
                        "image": "//img.alicdn.com/imgextra/i1/817***772/O1CN01MIfKBL27Hcp1OZv3W_!!817***772.jpg"
                    },
                    {
                        "vid": "12516427631",
                        "name": "大红老孔雀",
                        "image": "//img.alicdn.com/imgextra/i2/817***772/O1CN01jBV8GU27Hcp0vfV0N_!!817***772.jpg"
                    }]
                }]
            },
            "balance": "189813",
            "itemId": "6409***67027",
            "success": true,
            "time": "2021/11/15 11:56:20"
        }';
        return json_decode($data,true);
    }
    public function ct(){
        $data = '{
            "data": {
                "itemid": "658413642992",
                "images": ["//img.alicdn.com/imgextra/i4/3713831607/O1CN01WzfX1v1Nk3CzhtWEV_!!3713831607.jpg"]
            },
            "balance": "1797.5",
            "itemid": "658413642992",
            "success": true,
            "api": "TaoItemDesc",
            "time": "2022/10/9 1:44:45"
        }';
        return json_decode($data,true);
    }
}

?>