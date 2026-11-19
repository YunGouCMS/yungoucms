<?php
class upload_file
{
    /**
     * 文件上传
     * @param $file 文件
     * @param $is_type 判断类型
     * @param $mkdir 路径
     */
    public function updateFiles($file,$is_type,$mkdir){
        $fileNameData=explode('.',$file['file']['name']);
        $filetype=strtolower(array_pop($fileNameData));

        if (in_array($filetype,$is_type)){
            //创建文件
            $fileName = $file['file']['name'];
            $mkdirName = $_SERVER['DOCUMENT_ROOT'].$mkdir;
            if (!is_dir($mkdirName)){
                mkdir($mkdirName,0777,true);
            }

            //判断文件是否存在
            $fileRootName = $mkdirName.'/'.$fileName;
            $resFile = move_uploaded_file($file['file']['tmp_name'],$fileRootName);
            if ($resFile){
                return [$fileRootName,$mkdir.$fileName];
            }
        }
    }
}