<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_sys_fun('user');

/**
 * 附件管理
 *
 * Class attachment
 */

class attachment extends admin {

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db=System::load_sys_class('model');
    }

    public function index(){
        $page = $_GET['page'];
        $limit = $_GET['limit'];
        $page = $page<0?1:$page;
        $limit = $limit< 0 ?20 :$limit;
        $p = ($page-1)*$limit;
        $item = $this->db->GetList("select * from `@#_attachment`  GROUP BY id LIMIT 5");
        $count = $this->db->GetOne("select count(*) as `count` from `@#_attachment`");
        foreach ($item as $k=>$v){
            $item[$k]['createtime'] = empty($v['createtime'])?'':date('Y-m-d H:i:s',$v['createtime']);
        }

//        p($item);exit();
        include $this->tpl(ROUTE_M,'attachment.index');
    }

    /**
     * @获取删除数据列表
     */
    public function getDelData()
    {
        $page = $_POST['page'];
        $limit = 40;
        $page = $page<0?1:$page;
        $limit = $limit< 0 ?20 :$limit;
        $p = ($page-1)*$limit;
        $sql = "select * from `@#_attachment`  GROUP BY id LIMIT {$p},{$limit}";
        $item = $this->db->GetList($sql);
        $count = $this->db->GetOne("select count(*) as `count` from `@#_attachment`");
        foreach ($item as $k=>$v){
            $item[$k]['createtime'] = empty($v['createtime'])?'':date('Y-m-d H:i:s',$v['createtime']);
        }
        $sqldata =[];
        $sqldata['code'] =0;
        $sqldata['data']=$item;
        $sqldata['count'] = $count['count'];
        $sqldata['msg']='';
        $data = json_encode($sqldata);
        echo $data;
    }

    /**
     * 编辑
     */
    public function edit(){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $value = $_POST['value'];
        if(empty($id) || empty($name) || empty($value)) $this->success(0,'条件不能为空');

        $sql = "UPDATE `@#_attachment` SET `$name` = '$value' WHERE `id` = $id";
        $res = $this->db->Query($sql);
        if($res){
            $this->success(1,'修改成功');
        }
        $this->success(0,'修改失败');
    }

    public function del(){
        $sid = $_POST['id'];
        $sid = implode(',',$sid);
        if(empty($sid)) $this->success(0,'条件不能为空');

        $getsql = "SELECT * FROM `go_attachment` WHERE id in($sid)";
        $filelist = $this->db->GetList($getsql);

        if(count($filelist)<1)$this->success(0,'没有查询到记录');
        $delfirl=[];
        foreach ($filelist as $v){

            $dir = G_APP_PATH."statics".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
            $dir.=$v['topic'];
            $dir.=DIRECTORY_SEPARATOR;
            $dir.=$v['url'];
            $url=iconv('utf-8','gbk',$dir);
            if(PATH_SEPARATOR == ':'){ //linux
                $status= unlink($dir);
            }else{  //Windows
                $status= unlink($url);
            }
            if($status){
                $id = $v['id'];
                $delsql = "DELETE FROM `@#_attachment` WHERE `id` = {$id}";
                $this->db->Query($delsql);
                $arr  = array('id' =>$id);
                $delfirl[]=$arr;
            }
            
        }
        if (count($delfirl) == count($filelist)) {
           $this->success(1,'删除成功',$delfirl);
        }
        $num =count($filelist)- count($delfirl);
        $this->success(1,'删除成功'.$num,$delfirl);
        //unlink
    }

    public function del2(){
        $filelist =$_POST['id'];
        foreach ($filelist as $v){
            $dir = G_APP_PATH."statics".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."shopimg";
            $dir.=DIRECTORY_SEPARATOR;
            $dir.=$v;
            $url=iconv('utf-8','gbk',$dir);
            if(PATH_SEPARATOR == ':'){ //linux
                $status= unlink($dir);
            }else{  //Windows
                $status= unlink($url);
            }

        }
        $this->success(1,'删除成功');
    }

    public function GetFile(){
        $dir = G_APP_PATH."statics".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."shopimg";
//        echo $dir;exit();
        $muflie = $this->scandirFolder($dir);

        $this->success(1,'',['data'=>$muflie]);

    }

    function scandirFolder($path){
        $list = [];
        $temp_list = scandir($path);
        foreach ($temp_list as $file){
            if ($file != ".." && $file != "."){
                if (is_dir($path . "/" . $file)){
                    //子文件夹，进行递归
                    $list[][$file] = $this->scandirFolder($path . "/" . $file);

                }else{
                    //根目录下的文件
                    $list[] = $file;
                }

            }
        }
        return $list;
    }

    private  function my_dir($dir)
        {
            $files = [];
            if (@$handle = opendir($dir)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != ".." && $file != ".") {
                        if (is_dir($dir . "/" . $file)) { //如果是子文件夹，进行递归
                            $files[$file] = $this->my_dir($dir . "/" . $file);
                        } else {
                            $files[] = $file;
                        }
                    }
                }
                closedir($handle);
            }
            return $files;
        }
    private function success($code = 1,$msg = '',$data=''){
        $data = array('code'=>$code,'msg'=>$msg,'data' =>$data);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit();
    }
}