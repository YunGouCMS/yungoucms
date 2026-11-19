<?php

class redisData
{
    private static $_instance = null; //静态实例
    public $error = '';

    public function __construct(){ //私有的构造方法
        if(!self::$_instance){
            $this->likeRedis();
        }
    }

    private function likeRedis(){
        try{
            self::$_instance = new \Redis();
            $redisConfig = System::load_sys_config("database");
            $config = $redisConfig["redis"]; // redis配置信息；我这里是tp的
            self::$_instance->connect($config['host'],$config['port']);
            if(isset($config['password']) && $config['password']){
                self::$_instance->auth($config['password']);
            }
        }catch (\Exception $e){
           $this->error = $e->getMessage();
        }
    }
    /*
     * 禁止clone
     */
    private function __clone(){}

    /**
     * 监听端口
     * @param $field 监听字段
     **/
    public function redisWatch($field){
        return self::$_instance->watch($field);
    }

    /**
     * 启动事务
     **/
    public function redisMulti(){
        return self::$_instance->multi();
    }

    /**
     * 事务提交 返回值 1 0
     **/
    public function redisExec(){
        return self::$_instance->exec();
    }

    /**
     * 写入set
     * @param $key
     * @param $value
     */
    public function redisSet($key='',$value=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->set($key,$value)];
    }

    /**
     *定时删除的setex
     * @param string $key
     * @param  $time
     * @param string $value
     */
    public function  redisSetex($key='',$time='',$value=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->setex($key,$time,$value)];
    }

    /**
     * 判断key是否存在 常用于锁
     * @param string $key
     * @param string $value
     */
    public function  redisSetnx($key='',$value=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->setnx($key,$value)];
    }

    /**
     * 删除
     * @param null $instance
     */
    public static function redisDel($key)
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->del($key)];
    }

    /**
     *  获取字符串
     * @param string $key
     * @return array
     */
    public function redisGet($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=>1,'data'=>self::$_instance->get($key)];
    }

    /**
     * 队列左边写入
     * @param $key
     * @param $value
     * @return array
     */
    public function redisLpush($key = '',$value = ''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LPUSH($key,$value)];
    }

    /**
     * 队列右边写入
     * @param $key
     * @param $value
     * @return array
     */
    public function redisRpush($key = '',$value = ''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->RPUSH($key,$value)];
    }

    /**
     * 获取列表中值不会取出 【0，-1】 获取所有 【0，1】 获取一个值
     * @param string $key
     * @return array
     */
    public function redisLrange($key = '',$array = [0,-1]){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LRANGE($key,$array[0],$array[1])];
    }

    /**
     * 从队列左取出
     * @param $key
     * @return array
     */
    public function redisLpop($key){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LPOP($key)];
    }

    /**
     * 从队列左取出 阻塞方式
     * @param mixed $key
     * @return array{code: int, data: mixed|array{code: int, data: string}}
     */
    public function redisBlpop($key){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->BLPOP($key)];
    }

    /**
     * 从队列右取出
     * @param $key
     * @return array
     */
    public function redisRpop($key){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->RPOP($key)];
    }

    /**
     * 从队列左取出 阻塞方式
     * @param mixed $key
     * @return array{code: int, data: mixed|array{code: int, data: string}}
     */
    public function redisBrpop($key){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->BRPOP($key)];
    }

    /**
     * 获取队列中的指定下标值
     * @param string $key
     * @param int $value
     * @return array
     */
    public function redisLindex($key='',$value=0){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LINDEX($key,$value)];
    }

    /**
     * 获取列表长度
     * @param string $key
     * @return array
     */
    public function redisLlen($key=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LLEN($key)];
    }

    /**
     * 移除列表中指定value
     * @param string $key
     * @param int $num 匹配数量
     * @param string $value
     * @return array
     */
    public function redisLrem($key='',$num = 1,$value='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        if (empty($num)) return ['code'=>0,'data'=>'num no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LREM($key,$num,$value)];
    }

    /**
     * 截断列表，截断之后原有的列表修改
     * @param string $key
     * @param int[] $array
     * @return array
     */
    public function redisLtrim($key='',$array = [0,1])
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($array)) return ['code'=>0,'data'=>'array no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LTRIM($key,$array)];
    }

    /**
     * 将旧key上的最后一个数据移动到新key上来
     * @param string $key 旧key
     * @param string $newkey 新key
     * @return array
     */
    public function redisRpoplpush($key='',$newkey='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($newkey)) return ['code'=>0,'data'=>'newkey no null'];
        return ['code'=> 1 , 'data' => self::$_instance->RPOPLPUSH($key,$newkey)];
    }

    /**
     * 修改列表中的指定key的下标num的值
     * @param string $key
     * @param int $num 指定修改下标
     * @param string $value 修改的值
     * @return array
     */
    public function redisLset($key='',$num=0,$value=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($num)) return ['code'=>0,'data'=>'num no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        return ['code'=> 1 , 'data' => self::$_instance->LSET($key,$num,$value)];
    }

    /**
     * 添加集合
     * @param string $key
     * @param string $value
     * @return array
     */
    public function redisSadd($key='',$value='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        return ['code'=> 1 , 'data' => self::$_instance->SADD($key,$value)];
    }

    /**
     * 查看集合中的所有值
     * @param string $key
     * @return array
     */
    public function redisSmembers($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->SMEMBERS($key)];
    }

    /**
     * 查看集合中的数量
     * @param string $key
     * @return array
     */
    public function redisScard($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->SCARD($key)];
    }

    /**
     * 集合中随机抽取
     * @param string $key
     * @return array
     */
    public function redisSrandmember($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->SRANDMEMBER($key)];
    }

    /**
     * 集合中随机移除
     * @param string $key
     * @return array
     */
    public function redisSpop($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->SPOP($key)];
    }

    /**
     * 在集合中删除某个值
     */
    public function rediszDelete($jh='',$key=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->sRem($jh,$key)];
    }

    /**
     * 集合中key中的value移动到key2 如果key存在移动成功返回 1 如果不存在返回0
     * @param string $key
     * @param string $key2
     * @param string $value
     * @return array
     */
    public function redisSmove($key='',$key2='',$value='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($key2)) return ['code'=>0,'data'=>'key2 no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        return ['code'=> 1 , 'data' => self::$_instance->SMOVE($key,$key2,$value)];
    }

    /**
     * 集合中的差集交集并集
     * @param string $key
     * @param string $key2
     * @param int $type 默认差集 1交集 2并集
     * @return array
     */
    public function redisSetType($key='',$key2='',$type=0)
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($key2)) return ['code'=>0,'data'=>'key2 no null'];
        $setType = 'SDIFF'; //差集
        switch ($type){
            case 1:
                $setType = 'SINTER';//交集
                break;
            case 2:
                $setType = 'SUNION';//并集
                break;
        }

        return ['code'=> 1 , 'data' => self::$_instance->$setType($key,$key2)];
    }

    /**
     * 获取哈希
     * @param string $key
     * @param string $key2
     * @return array
     */
    public function redisHget($key='',$key2='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($key2)) return ['code'=>0,'data'=>'key2 no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HGET($key,$key2)];
    }

    /**
     * 哈希写入
     * @param string $key
     * @param string $key2
     * @param string $value
     * @return array
     */
    public function redisHset($key='',$key2='',$value='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($key2)) return ['code'=>0,'data'=>'key2 no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HSET($key,$key2,$value)];
    }

    /**
     * 获取哈希
     * @param string $key
     * @param string $value 多个值用空格隔开
     * @return array
     */
    public function redisHmset($key='', ...$value)
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($value)) return ['code'=>0,'data'=>'value no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HMSET($key,$value)];
    }

    /**
     * 删除哈希
     * @param string $key
     * @param string $key2
     * @return array
     */
    public function redisHdel($key='',$key2='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($key2)) return ['code'=>0,'data'=>'key2 no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HDEL($key,$key2)];
    }

    /**
     * 查看哈希中的所有key以及类容
     * @param string $key
     * @return array
     */
    public function redisHgetall($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HGETALL($key)];
    }

    /**
     * 获取哈希的数量
     * @param string $key
     * @return array
     */
    public function redisHlen($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HLEN($key)];
    }

    /**
     * 获取哈希中所有的key
     * @param string $key
     * @return array
     */
    public function redisHkeys($key='')
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HKEYS($key)];
    }

    /**
     * 哈希自增减
     * @param string $key
     * @param string $key2
     * @param int $num 正数为加 负数为减
     * @return array
     */
    public function redisHincrby($key='',$key2='',$num=1)
    {
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        if (empty($key2)) return ['code'=>0,'data'=>'key2 no null'];
        return ['code'=> 1 , 'data' => self::$_instance->HINCRBY($key,$key2,$num)];
    }

    /**
     * 返回剩余时间
     * @param mixed $key
     * @return array
     */
    public function redisTtl($key=''){
        if (empty($key)) return ['code'=>0,'data'=>'key no null'];
        return ['code'=> 1 , 'data' => self::$_instance->ttl($key)];
    }

}