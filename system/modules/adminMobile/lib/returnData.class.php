<?php

/**
 * 返回前端数据
 */
System::load_app_class("codeReturn",'app','no');
class returnData extends codeReturn
{
    /**
     * 成功  return data返回 []
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @param $type int 返回类型 1 array 2 json
     * @return void
     */
    public function successCode($code = null,$msg = '成功',$data = [],$type = 2){
        if(empty($code)) $code = codeReturn::SUCCESS;
        if ($type == 1){
            return ['code' => $code,'msg'=>$msg,'data'=>$data];

        }
        if ($type == 2){
            echo json_encode(['code' => $code,'msg'=>$msg,'data'=>$data]);
            exit();
        }
    }

    /**
     * 失败 return data返回 []
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @param $type int 返回类型 1 array 2 json
     * @return void
     */
    public function errCode($code = null,$msg = '失败',$data = [],$type = 2){
        if(empty($code)) $code = codeReturn::ERR_SERVER;
        if ($type == 1){
            return ['code' => $code,'msg'=>$msg,'data'=>$data];

        }
        if ($type == 2){
            echo  json_encode(['code' => $code,'msg'=>$msg,'data'=>$data]);
            exit();
        }
    }

    /**
     * 失败 return data返回 {}
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @return void
     */
    public function error($code = null,$msg = '失败',$data =[]){
        if(empty($code)) $code = codeReturn::ERR_SERVER;
        $data = (object)$data;
        echo json_encode(['code' => $code,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 成功 return data返回 {}
     * @param $code int 返回码
     * @param $msg string msg
     * @param $data string 数据
     * @return void
     */
    public function success($code = null,$msg = '成功',$data = []){
        if(empty($code)) $code = codeReturn::SUCCESS;
        $data = (object)$data;
        echo json_encode(['code' => $code,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
        exit();
    }
}