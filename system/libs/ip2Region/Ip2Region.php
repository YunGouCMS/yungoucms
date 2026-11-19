<?php

/**
 * class Ip2Region
 * 为兼容老版本调度而创建
 * @author Anyon<zoujingli@qq.com>
 * @datetime 2022/07/18
 */
class Ip2Region
{
    /**
     * 查询实例对象
     * @var XdbSearcher
     */
    private $searcher;

    /**
     * 初始化构造方法
     * @throws Exception
     */
    public function __construct()
    {
        class_exists('XdbSearcher') or include __DIR__ . '/XdbSearcher.php';
        $this->searcher = XdbSearcher::newWithFileOnly(__DIR__ . '/ip2region.xdb');
    }

    /**
     * 兼容原 memorySearch 查询
     * @param string $ip
     * @return array
     * @throws Exception
     */
    public function memorySearch($ip)
    {
        return ['city_id' => 0, 'region' => $this->searcher->search($ip)];
    }

    /**
     * 兼容原 binarySearch 查询
     * @param string $ip
     * @return array
     * @throws Exception
     */
    public function binarySearch($ip)
    {
        return $this->memorySearch($ip);
    }

    /**
     * 兼容原 btreeSearch 查询
     * @param string $ip
     * @return array
     * @throws Exception
     */
    public function btreeSearch($ip)
    {
        return $this->memorySearch($ip);
    }

    /**
     * 直接查询并返回名称
     * @param string $ip
     * @return string
     * @throws \Exception
     */
    public function simple($ip)
    {
        $geo = $this->memorySearch($ip);
        $arr = explode('|', str_replace(['0|'], '|', isset($geo['region']) ? $geo['region'] : ''));
        if (($last = array_pop($arr)) === '内网IP') $last = '';
        return join('', $arr) . (empty($last) ? '' : "【{$last}】");
    }

    /**
     * destruct method
     * resource destroy
     */
    public function __destruct()
    {
        $this->searcher->close();
        unset($this->searcher);
    }

    public function getCountry($ip){
        $geo = $this->memorySearch($ip);
        $region = isset($geo['region']) ? $geo['region'] : '';
        $arr = explode("|",$region);
        return $arr[0];
    }

    /**
     * 返回国家省份城市
     * @param mixed $ip
     * @param mixed $mode 1返回省份城市 
     * @return string
     */
    public function getCountrys($ip,$mode=0,$join="-"){
        $geo = $this->memorySearch($ip);
        if(!$geo['region'])return false;
        $region = isset($geo['region']) ? $geo['region'] : '';
        $arr = explode("|",$region);
        $str = "";
        switch ($mode) {
            case 1:
                $str = $arr[2].$arr[3];
            break;
            default:
                return $arr[0].$join.$arr[2].$join.$arr[3];
        }
        $str = str_replace("0","",$str);
        return $str;
    }
}