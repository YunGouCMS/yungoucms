<?php



/*
	计算云购码表
*/
function content_get_codes_table(){
    $db = System::load_sys_class("model");
    $num = $db->GetOne("SELECT * from `@#_caches` where `key` = 'shopcodes_table' LIMIT 1");
    $table = 'shopcodes_'.$num['value'];
    $shopcodes_table = $db->GetOne("SHOW TABLE STATUS LIKE '@#_$table'");

    if(!$shopcodes_table || !$num) return false;
    if($shopcodes_table['Auto_increment'] >=99999){
        $num = intval($num['value'])+1;
        $shopcodes_table = 'shopcodes_'.$num;
        $q1 = $db->Query("		
				CREATE TABLE `@#_$shopcodes_table` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `s_id` int(10) unsigned NOT NULL,
				  `s_cid` smallint(5) unsigned NOT NULL,
				  `s_len` smallint(5) DEFAULT NULL,
				  `s_codes` text,
				  `s_codes_tmp` text,
				  PRIMARY KEY (`id`),
				  KEY `s_id` (`s_id`),
				  KEY `s_cid` (`s_cid`),
				  KEY `s_len` (`s_len`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
        $q2 = $db->Query("UPDATE `@#_caches` SET `value` = '$num' where `key` = 'shopcodes_table'");
        if(!$q1 || !$q2)return false;
    }else{
        $num = intval($num['value']);
        $shopcodes_table = 'shopcodes_'.$num;
    }
    return $shopcodes_table;

}

/*
   生成云购码
   CountNum @ 生成个数
   len 	    @ 生成长度
   sid	    @ 商品ID
*/
function content_get_go_codes($CountNum=null,$len=null,$sid=null,$table = ""){
    $db = System::load_sys_class("model");
    //$db->Query("set global max_allowed_packet = 2*1024*1024*10");
    if (empty($table)){
        $table = $db->GetOne("SELECT * from `@#_caches` where `key` = 'shopcodes_table' LIMIT 1");
        $table = '@#_shopcodes_'.$table['value'];
    }
    $c=$CountNum;
    $num = ceil($CountNum/$len);
    $code_i = $CountNum;
    if($num == 1){
        $codes=array();
        for($i=1;$i<=$CountNum;$i++){
            $codes[$i]=10000000+$i;
        }
        shuffle($codes);
        shuffle($codes);
        $codes=serialize($codes);
        $query = $db->Query("INSERT INTO `$table` (`s_id`, `s_cid`, `s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '1','$CountNum','$codes','$codes')");
        unset($codes);
        return $query;
    }else{
        $codes2=array();
        for($i=1;$i<=$c;$i++){
            $codes2[$i]=10000000+$i;
        }
        shuffle($codes2);
        shuffle($codes2);
        for($o=1;$o<=$num;$o++){
            $str=($o-1)*$len;
            for($y=0;$y<=($len-1);$y++){
                if(!$codes2[$str]){
                    break;
                }
                $codes[]=$codes2[$str];
                unset($codes2[$str]);
                $str++;

                //file_put_contents('ecode.txt','str'.$o.'--'.var_export($str,true).PHP_EOL,FILE_APPEND);
            }
            $l=count($codes);
            $codes=serialize($codes);
            $query_1 = $db->Query("INSERT INTO `$table` (`s_id`, `s_cid`, `s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '$o','$l','$codes','$codes')");
            unset($codes);
        }
        return $query_1;
    }
}

//content_get_go_codes(ceil($zongrenshu/3000),3000,$shopid);


/*
	 添加推荐位
*/
function content_add_position(){}

/*
 新建一期商品
 info 	 商品的ID 或者 商品的数组
 使用此函数注意传进来的的商品期数不等于最大期数
*/
function content_add_shop_install($info=null){

    $db = System::load_sys_class("model");

    /* unset($info['id']);
    unset($info['q_uid']);
    unset($info['q_user']);
    unset($info['q_user_code']);
    unset($info['q_content']);
    unset($info['q_counttime']);
    unset($info['q_end_time']);
    unset($info['zhiding']);
    unset($info['q_end_counttime']);
    unset($info['q_external_code']);
    unset($info['q_external_content']);

    $info['xsjx_time']=0;
    $info['time'] = time();
    $info['qishu'] = intval($info['qishu']);
    $info['qishu']++;
    $info['canyurenshu'] = '0';
    $info['shenyurenshu'] = $info['zongrenshu'];
    $info['codes_table'] = content_get_codes_table();
    $info['q_showtime']= 'N';
    $info['zhiding'] = '0';
    $keys  = $vals = '(';
    foreach($info as $key=>$val){
        $keys.="`$key`,";
        $vals.="'$val',";
    }
    $keys = rtrim($keys,',');
    $vals = rtrim($vals,',');
    $keys.= ')';
    $vals.= ')'; 

    $sql = "INSERT INTO `@#_shoplist` ".$keys." VALUES ".$vals;*/

    $sql = "INSERT INTO `@#_shoplist` select
     (SELECT MAX(`id`) + 1 FROM `@#_shoplist`) AS id,
    `sid`,
    `cateid`,
    `brandid`,
    `title`,
    `title_style`,
    `title2`,
    `keywords`,
    `description`,
    `money`,
    `yunjiage`,
    `zongrenshu`,
    `canyurenshu`,
    `shenyurenshu`,
    `def_renshu`,
    `qishu`,
    `maxqishu`,
    `thumb`,
    `picarr`,
    `content`,
    `codes_table`,
    `xsjx_time`,
    `pos`,
    `renqi`,
    `time`,
    `order`,
    `q_uid`,
    `q_user`,
    `q_user_code`,
    `q_content`,
    `q_counttime`,
    `q_end_time`,
    `q_showtime`,
    `zhiding`,
    `not_prize_id`,
    `goucount`,
    `buycount`,
    `ispk`,
    `q_external_code`,
    `q_external_content`,
    `q_end_counttime`,
    `ssc`,
    `send`,
    `xg`,
    `dh`,
    `dhbl`,
    `language`,
    `is_show`,
    `preferential_price`,
    `groups`
     from `@#_shoplist` where id = ".$info['id'];
    $q1 = $db->Query($sql);
    $id = $db->insert_id();
    $data['xsjx_time']='0';
    $data['time'] = time();
    $data['qishu'] = intval($info['qishu']);
    $data['qishu']++;
    $data['canyurenshu'] = '0';
    $data['shenyurenshu'] = $info['zongrenshu'];
    $data['codes_table'] = content_get_codes_table();
    $data['q_showtime']= 'N';
    $data['zhiding'] = '0';
    $data['q_uid'] = null;
    $data['q_user'] = '';
    $data['q_user_code'] = null;
    $data['q_content'] = null;
    $data['q_counttime'] = null;
    $data['q_end_time'] = null;
    $data['q_end_counttime'] = null;
    $data['q_external_code'] = null;
    $data['q_external_content'] = null;
    $sql2 = Assemblydata($data,'shoplist','up',[["id","=",$id]]);
    $sql2 = str_replace("`q_uid` = ''","`q_uid` = null",$sql2);
    $q3 = $db->Query($sql2);
    $q2 = content_get_go_codes($info['zongrenshu'],3000,$id);
    if($q1 && $q2){
        return true;
    }else{
        return false;
    }

}//




?>