<?php

// 建议使用 php _test.php 命令行运行测试文件

require 'Ip2Region.php';
class region{
    public function getIp2Region($ip,$type=-1,$mode=0){
        $ip2region = new Ip2Region();
        switch ($type) {
            case '1':
                    $res = $ip2region->memorySearch($ip);
                    return json_encode($res);
                break;
            
            case '2':
                $res = $ip2region->simple($ip);
                return $res;
                break;
            case '3':
                $res = $ip2region->getCountrys($ip,$mode);
                return $res;
                break;
            default:
                $res = $ip2region->getCountry($ip);
                return $res;
                break;
        }
    }
}
