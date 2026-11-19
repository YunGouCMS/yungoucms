<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/5/19
 * Time: 10:51
 */
System::load_app_class('base','app','no');
class nav extends base
{

    public function __construct()
    {
        parent::__construct();
        // $this->jsonData = new returnData();

    }
    //超值特价
    public function specialOffer(){
        $p = !empty($_POST['page'])?$_POST['page']:0;
//        $end=10;
//        $star=($p)*$end;
        $title = "超值特价";
        $banner = G_UPLOAD_PATH."/banner/20220415/9a795a871414a0e00bd60e8538096265.png";
        $where = "`tejiachaozhi` ='1' ORDER BY `order` DESC ";
        $shopList = $this->db->Getlist("SELECT * FROM `@#_shoplist_jifen` where $where  and delete_time is null");
        $arr = [];
        //重组商品列表数组
        foreach($shopList as $k=>$v){
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['img'] = G_UPLOAD_PATH.'/'.$v['thumb'];
            $arr[$k]['title'] = $v['title'];
            $arr[$k]['integral'] = $v['s_score'];
            $arr[$k]['money'] = $v['money'];
        }
        $data = [
            'title'=>$title,
            'banner'=>$banner,
            'shopList'=>$arr,
        ];
        if($data){
            $this->jsonData->success('','成功',$data);
        }else{
            $arr = [];
            $this->jsonData->successCode('','成功',$arr);
        }
    }
    //获取积分
    public function integral(){
        $p = !empty($_POST['page'])?$_POST['page']:0;
        $end=10;
        $star=($p)*$end;
//        $where = "`cateid` ='167' ORDER BY `money` ASC LIMIT $star,$end";
        $sql = $this->db->Getlist("SELECT id,thumb as img,title,s_score as integral,money,sales FROM `@#_shoplist_jifen` where `cateid`=158  and delete_time is null ORDER BY `money` ASC LIMIT $star,$end");

        foreach($sql as $k=>&$v){
            $v['img'] = G_UPLOAD_PATH .'/'.$v['img'];
        }
        if($sql){
            $this->jsonData->successCode('','成功',$sql);
        }else{
            $arr = [];
            $this->jsonData->successCode('','成功',$arr);
        }
    }

}