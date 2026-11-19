<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','app','no');
class language extends base
{
    //用户号语言
    public function nowUserLanguage(){
        $lg = $this->languages();
        if($lg) {
            if(is_array($lg)){
                foreach($lg as &$vl){
                    $vl = _unhtmtocode($vl);
                }
            }else{
                $lg = _unhtmtocode($lg);
            }
            $this->jsonData->success(200,'',$lg,true);
        } else {
            $this->jsonData->errCode(500,'');
        }
    }

    //语言选择
    public function languagSelection(){
        $lge = $this->db->GetLIst("SELECT * FROM `@#_language`WHERE status = 1 and type = 1 order by sort desc");
        $lgls = [];
        foreach ($lge as $k => $v) {
            $lgls[$k]['value'] = $v['lg_value'];
            $lgls[$k]['text'] = $v['lg_name'];
            $lgls[$k]['flag_status'] = $v['flag_status'];
            if($v['flag_status'] == 1){
                $flag = explode('/',$v['national_flag']);
                if(count($flag)<=1){
                    $lgls[$k]['national_flag'] = G_PLUGIN_PATH."/imgs/flag/".$v['national_flag'];
                }elseif(count($flag)>1){
                    $lgls[$k]['national_flag'] = G_UPLOAD_PATH."/".$v['national_flag'];
                }
            }else{
                $lgls[$k]['national_flag'] = '';
            }
        }

        $this->jsonData->successCode('','',$lgls);
    }

    /**
     * 用户选择语言
     * @return void
     */
    public function recordingLanguage(){
        $language = safe_replace($_POST['language']);
        $isLg = $this->db->GetOne("SELECT * FROM `go_language` where lg_value = '$language'");
        $lg = $this->languages('submit');
        if (empty($isLg)) $this->jsonData->success('',$lg['operation_failure']);
        $res = true;
        if (!empty($this->uId)){
            $uid = $this->uId;
            $res = $this->db->Query("UPDATE `@#_member` SET `language`='$language' where uid = '$uid' ");
        }
        if ($res) $this->jsonData->success('',$lg['suss_operation']);
        $this->jsonData->success('',$lg['operation_failure']);
    }

    /**
     * 协议请求
     */
    public function agreement(){
        $id = safe_replace($_POST['id']);

        //这里不知道要不要添加语言选择工鞥呢
        $data = $this->db->GetList("SELECT * FROM `@#_article` WHERE `id` = $id");
        $this->jsonData->success('','',$data);
    }

    public function getLgInfo(){
        $language = $this->nowUserLanguage;
        $lg = $this->db->GetOne("SELECT `lg_name`,`time_zone`,`country`,`national_flag`,`currency_unit`,`area_code` FROM `go_language` where lg_value = '$language' ");
        if($lg){
            $lg['national_flag'] = G_UPLOAD_PATH.'/'.$lg['national_flag'];
        }else{
            $this->jsonData->error();
        }
        $this->jsonData->success('','',$lg);
    }
}