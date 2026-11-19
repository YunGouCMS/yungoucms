<?php

class validation
{
    /**
     * 判断是否是数字类型
     * @param string $data //参数验证
     * @return array
     */
    public function intValidation($data ='')
    {
        if (empty($data)) return ['code'=>2,'msg'=>'参数不能为空','data'=>''];

        if (is_numeric($data)){
            return ['code'=>1,'msg'=>'是数字类型','data'=>$data];
        }
        return ['code'=>3,'msg'=>'不是数字类型','data'=>$data];
    }

    /**
     * @param string $data //参数
     * @param int $length //长度
     * @param int $isChinese //中文判读 1 中文 2 纯英文
     */
    public function stringValibation($data = '',$length = 0,$isChinese = 1)
    {
        if (empty($data)) return ['code'=>2,'msg'=>'参数不能为空','data'=>''];

        $data = htmlspecialchars($data);
        //过滤特殊字符
        $res = $this->has_special_str($data);
        if (!$res) return ['code'=>2,'msg'=>'参数不能有特殊字符串','data'=>$data];

        if ($isChinese == 1 && $length > 0){
            $res = mb_strlen($data);
            if ($res >= $length){
                return ['code'=>11,'msg'=>'字符串大于等于长度','data'=>$data];
            }
            return ['code'=>12,'msg'=>'字符串小于','data'=>$data];
        }

        if ($isChinese == 2 && $length > 0){
            $res = strlen($data);
            if ($res >= $length){
                return ['code'=>21,'msg'=>'字符串大于等于长度','data'=>$data];
            }
            return ['code'=>22,'msg'=>'字符串小于','data'=>$data];
        }

        return ['code'=>1,'msg'=>'正常字符串','data'=>$data];
    }

    /**
     * 判断是否存在特殊字符，可判断中英文及特殊符号混合串
     * 存在返回true
     * @param string $str
     * @return boolean
     */
    private function has_special_str($str){
        if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$str)){
            return false;
        }
        return true;
    }
}