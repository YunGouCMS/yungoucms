<?php
/**
 * model.class.php	基础数据操作类
 * 使用方法将文件名改为model.class.php，并继承该类即可
 *
 * @copyright			(C) 2005-2010 BUSY
 * @license				
 * @lastmodify			2012-6-1
 */
// System::load_sys_class("mysql",'sys','no');
System::load_sys_class("PdoHelper",'sys','no');
class model {
	//数据库配置
	protected $db_config = '';
	//数据库连接
	protected $db ;
	//调用数据库的配置项
	protected $db_setting = 'default';
	//数据表名
	protected $table_name = '';
	//表前缀
	public  static $db_tablepre = '';	
	public  static $strtablepre = '';	
	public $Autocommit = '';
	public $sql_log = array();

	private $sql='';
	
	public function __construct() {
		
		if(empty($this->db_config)){
			$this->db_config = System::load_sys_config('database');
		}
		if (!isset($this->db_config[$this->db_setting])) {
			$this->db_setting = 'default';
		}	
		self::$strtablepre=System::load_sys_config('system','tablepre');
		self::$strtablepre=base64_decode(self::$strtablepre);
		self::$db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
		$this->table_name=$this->db_config[$this->db_setting]['database'];
		$this->db_config[$this->db_setting]['host'] = $this->db_config[$this->db_setting]['hostname'];
		$this->db_config[$this->db_setting]['prefix'] = self::$strtablepre;
		$this->db = new PdoHelper($this->db_config[$this->db_setting]);
	}
	
	public function __call($method, $args) {
	    if (method_exists($this->db, $method)) {
	        return call_user_func_array(array($this->db, $method), $args);
	    } else {
	        throw new Exception('Method ' . $method . ' does not exist');
	    }
	}

	final public function GetError(){
		return $this->db->error();
	}
	
	//获取单条数据（可选第二参数为绑定数组，防 SQL 注入）
	final public function GetOne($sql, $params = null){
		$this->sql = $sql;
		if (is_array($params)) {
			return $this->db->getRow($sql, $params);
		}
		return $this->db->getRow($sql);	
	}	

	//获取数据列表（可选第三参数为绑定数组）
	final public function GetList($sql, $info=array('type'=>1,'key'=>''), $params = null){
		$this->sql = $sql;
		if (is_array($params)) {
			$res = $this->db->getAll($sql, $params);
		} else {
			$res = $this->db->getAll($sql);
		}
		if(isset($info['key']) && $info['key'] != ''){
			$arr = [];		
			foreach($res as $k=>$v){
				$arr[$v[$info['key']]] = $v;
			}
			$res = $arr;
		}
		return 	$res;
	}
	
	//获取分页数据
	final public function GetPage($sql,$info=array('type'=>1,'key'=>'')){
		if(empty($sql))return false;
		$this->sql = $sql;
		if(!is_array($info))return false;		
		$page=intval($info['page']) ? intval($info['page']) : 1;
		if($page<=0){$page=1;}
		$num=(!empty($info['num'])) ? intval($info['num']) : 20;		
		// return $this->db->getPage($sql,[],$page,$num);
		$res = $this->db->getPage($sql,[],$page,$num);
		if(isset($info['key']) && $info['key'] != ''){
			$arr = [];		
			foreach($res as $k=>$v){
				$arr[$v[$info['key']]] = $v;
			}
			$res = $arr;
		}
		return 	$res;
	}
	//获取数据总数1
	final public function GetCount($sql,$field=''){	
		if(empty($sql))return false;		
		$this->sql = $sql;
		if(strpos($sql,"COUNT") !== false || strpos($sql,"count") !== false){
			$sql = preg_replace ("/^SELECT (.*) FROM/i", "SELECT COUNT(*) as count FROM",$sql);		
			if($field){
				$sql = preg_replace ("/^SELECT (.*) FROM/i", "SELECT COUNT($field) as count  FROM",$sql);		
			}
			$this->sql = $sql;
			$res = $this->db->getRow($sql);
			return $res['count'];
		}
		return $this->db->GetCount($sql);
	}

	//获取数据总数2
	final public function GetNum($sql){
		if(empty($sql))return false;
		$this->sql = $sql;
		return $this->GetCount($sql);
	}	
	
	static private function replacesql($sql){		
		static $sqllist=array();
		$key=md5($sql);
		if(isset($sqllist[$key])){
			return $sqllist[$key];
		}
		$sql=str_ireplace(self::$strtablepre,self::$db_tablepre,trim($sql));
		$sql=preg_replace("/\s(?=\s)/","\\1",$sql);
		return $sql;
		if(count($sqllist)>200){
			$sqllist=array();
		}	
		$sqllist[$key] = $sql;
		return $sqllist[$key];
	} 
	
	
	//返回查询资源结果集（可选第二参数为绑定数组，防 SQL 注入）
	public function Query($sql, $params = null){
		if(empty($sql))return false;
		$sql=self::replacesql($sql);
		if(defined("G_IN_ADMIN")){
			preg_match("/^UPDATE|^INSERT|^DELETE/i",$sql,$matches,PREG_OFFSET_CAPTURE);
			if(isset($matches[0][0])){
				$this->sql_log[] = $sql;
			}
		}
		if (is_array($params)) {
			return $this->db->exec($sql, $params);
		}
		return $this->db->exec($sql);
	}
	
	//返回插入最后一次的ID
	final public function insert_id(){	
		return $this->db->lastInsertId();
	}
	
	//影响的行数
	final public function affected_rows(){
		return $this->db->rowCount();
	}

	/*
	*	返回版本号
	*/
	public function GetVersion(){
		return $this->db->GetVersion();
	}
	
	public function __destruct(){
		if($this->db){
			$this->db->__destruct();
		}
	}
	
	
	final public function Autocommit_off(){		
		$this->Query('SET AUTOCOMMIT=1');
	}
	final public function Autocommit_no(){		
		$this->Query('SET AUTOCOMMIT=0');
	}	
	//开启事务
	final public function Autocommit_start(){		
		$this->db->beginTransaction();
		$this->Autocommit = 'start';
	}
	//成功执行
	final public function Autocommit_commit(){		
		$this->db->commit();
		$this->Autocommit = 'commit';
	}	
	//回滚事务
	final public function Autocommit_rollback(){
		$this->db->rollBack();
		$this->Autocommit = 'rollback';
	}

	final public function getSql(){
		return str_replace("@#","go",$this->sql);
	}

}
?>