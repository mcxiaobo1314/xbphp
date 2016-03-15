<?php
/**
 * 模型
 * 此代码还能继续优化,谁知道多维数任串意组装成字符高效率办法，可以加群：114252528
 * @author wave
 */

 class Model extends BaseModel {

 	//绑定数组
 	public $bind = array();
	
	//表名
	protected $name = null;

	//別名
	protected $alias = null;

	//表名前缀
	protected $prefix = null;

	//连接数据
	protected $connect = null;

	//连接数据库
	static protected  $db = null;

	//获取参数
	protected $params = array();

	//获取SQL数组
	protected $cacheSql = array();

	static $link = null;


	/**
	 * 自动初始化
	 * @param string $name  表名
	 * @param string $prefix 前缀
	 * @param string $connect 连接数据库的类型
	 * @param string $alias 別名
	 * @author wave
	 */
	public function __construct($name = null,$prefix = null,$connect = null,$alias = null) {
		$this->_initialize();
		$this->prefix = $prefix;
		$this->name = $this->prefix.$name;
		$this->alias = $alias;
		$this->connect = $connect;
		if(empty(self::$db)) { //防止多次加載
			load('db.class.php',ROOT_PATH);  //加载连接数据库类
			if(!empty($this->connect)) {
			 	self::$db  =  !empty($this->connect) ? self::link($this->connect) : 1;   //进行初始化数据
			 }else {
			 	self::$db  = isset(config::$default) ? self::link(config::$default) : 1;   
			 }
		}
	}


	/**
	 * 设置键值
	 * @param string $key  键
	 * @param string $value  值
	 */
	public function __set($key,$value)	{
		switch ($key) {
			case 'data':
				$this->request->{$key} = $value;
 				break;
			default:
				$this->{$key} = $value;
				break;
		}
	}

	/**
	 * 毁消变量
	 * @param string $key 键
	 * @author wave
	 */
	public function __unset($key) {
		if(isset($this->request->{$key})) {
			unset($this->request->{$key});
		}else {
			unset($this->{$key});
		}
	}

	/**
	 * 析构方法
	 * @author wave
	 */
	public function __destruct() {
		unset($this->params);
	}



	/**
	 * 执行查询语句
	 * @return Array
	 * @author wave
	 */
	public function find() {
		$this->_isset();
		$this->bind();
		if(isset($this->params['alias'])) {
			$tkey = array_keys($this->params['alias']);
			$tvalue = array_values($this->params['alias']);
			$this->params['fields'] = str_replace($tkey, $tvalue, $this->params['fields']);
		}

		$this->alias =  !empty($this->alias) ? ' as '.$this->alias.' ' : '';
		$sql = 'select '. $this->params['fields'] . 
				' from ' . $this->name . $this->alias .
				' ' . $this->params['joins'] . 
				' ' . $this->params['where'] . 
				' ' . $this->params['group'] . 
				' ' . $this->params['having'].
				' ' . $this->params['order'] . 
				' ' . $this->params['limit'];
		return $this->query($sql);
	}


	/**
	 * 计算行数
	 * @return Array
	 * @author wave
	 */
	public function count() {
		$this->params['fields'] = 'count(*)';
		return $this->find();
	}

	/**
	 * 查詢單行數據
	 * @return Array
	 * @author wave
	 */
	public function first() {
		$this->params['limit'] = ' limit 1';
		return $this->find();
	}

	/**
	 * 保存方法
	 * @param Array $data 保存的数据
	 * @return resource
	 * @author wave
	 */
	public function save($data) {
		if(!empty($this->params['where'])) { //条件不为空 执行更新语句 
			if(is_array($data)) {
				$_data = '';
				foreach($data as $k => $v) {
					$_data .= '`' . $k . '`="' . $v . '",';
				}
				$_data = rtrim($_data,',');
			}else {
				$_data = $data;
			}
			$this->params['where'] = isset($this->params['where']) ? $this->params['where'] : '';
			$sql = 'updata `' . $this->name . '` set ' . $_data . ' ' .  $this->params['where'];
		}else {
			$sql = 'insert into `' . $this->name . '`(' . '`' . 
					implode('`,`', array_keys($data)) . '`' . 
					') values (' . '"' . 
					implode('","', array_values($data)) . 
					'"' . ')';
		}
		return $this->query($sql);
	}

	/**
	 * 批量更新多数据,多字段
	 * @param Array $data 要保存的数据
	 * @return int or boolean
	 * @author wave
	 */
	public function saveAll($data) {
		if(is_array($data) && !empty($data)) {
			$str = '';
			$arr = array();
			foreach($data as $k => $v) {
				if(empty($arr) && isset($v['key'])) {
					$arr[$v['key']] = ' WHEN '.$v['where'].' THEN "'.$v['value'].'" ';
				}
				if(!empty($v['key']) && !isset($arr[$v['key']])) {
					$arr[$v['key']] = ' WHEN '.$v['where'].' THEN "'.$v['value'].'" ';	
				}
			}

			if(!empty($arr)) {
				foreach ($arr as $key => $value) {
					$str .=  '`'.$key.'` = (CASE' .$value.' else `'.$key.'` END),';
				}
			}
			if($str) {
				$sql = 'update `'. $this->name.'` SET '.rtrim($str,',');
				return $this->query($sql);
			}
		}
	}

	/**
	 * 删除
	 * @return resource
	 * @author wave
	 */
	public function delete() {
		$this->params['where'] = isset($this->params['where']) ? $this->params['where'] : '';
		$sql = 'delete from `' . $this->name . '`' . ' ' . $this->params['where'];
		return $this->query($sql);
 	}

	/**
	 * 限制多少行
	 * @param Array or string $limit 限制行數
	 * @return object
	 * @author wave
	 */
	public function limit($limit) {
		$this->params['limit'] = 'limit ';
		if(is_array($limit)) {
			$this->params['limit'] .= implode(',', $limit);
		}else {
			$this->params['limit'] .= $limit;
		}
		return $this;
	}

	/**
	 * 條件
	 * @param Array or string $where 條件
	 * @return object
	 * @author wave
	 */
	public function where($where){
		$this->params['where'] = !empty($where) ?  'where ' : '';
		$alias = !empty($this->alias) ? $this->alias : $this->name;
		if(is_array($where)){
			foreach($where as $k => $v) {
				$k = $this->packsign($k);
				$this->params['where'] .= '(`'.$alias . '`.' . $k . '"' . $v . '") and';
			}
			$this->params['where'] = rtrim($this->params['where'],'and');
		}else {
			$this->params['where'] .= $where;
		}
		return $this;
	}

	/**
	 * 字段
	 * @param Array or string $fields 字段
	 * @return object
	 * @author wave
	 */
	public function field($fields = null) {
		$this->params['fields'] = null;
		if(is_array($fields)){
			foreach($fields as $key => $value) {
				$this->params['fields'] .= $value . ',';
			}
			$this->params['fields'] = rtrim($this->params['fields'],',');
		}else{
			$this->params['fields'] =  $fields;
		}
		return $this;
	}

	/**
	 * having
	 * @param Array or string $having
	 * @return object
	 * @author wave
	 */
	public function having($having) {
		$this->params['having'] = !empty($having) ?  'having ' : '';
		$alias = !empty($this->alias) ? $this->alias : $this->name;
		if(is_array($having)){
			foreach($having as $k => $v) {
				$k = $this->packsign($k);
				$this->params['having'] .= '(`' . $alias . '`.' . $k . '"' . $v . '") and';
			}
			$this->params['having'] = rtrim($this->params['having'],'and');
		}else {
			$this->params['having'] .= $having;
		}
		return $this;
	}

	/**
	 * 联表
	 * @param string or Array $joins 
	 * @return object
	 * @author wave
	 */
	public function joins($joins) {
		$this->params['joins'] = null;
		$this->params['alias'] = array();
		if(is_array($joins)) {
			foreach($joins as $key => $val) {
				isset($val['fileds']) ? $this->params['alias']['fileds'] = $val['fileds'] : '';
				if(isset($val['alias'])){
					$this->params['alias'][$val['table']] = $val['alias'];
					$this->params['joins'] .= $val['type'] .' join `' . $val['table'] . '` as `' . $val['alias'] . '` on ' . $val['where'] . ' ';
				}else {
					$this->params['joins'] .= $val['type'] .' join `' . $val['table'] . '` on ' . $val['where'] . ' ';
				}
			}
		}else {
			$this->params['joins'] = $joins;
		}
		return $this;
	}

	/**
	 * 分组
	 * @param string or Array $group 
	 * @return object
	 * @author wave
	 */
	public function group($group) {
		$this->params['group'] = 'group by ';
		if(is_array($group)) {
			$this->params['group'] .= implode(',', $group);
		}else {
			$this->params['group'] .= $group;
		}
		return $this;
	}

	/**
	 * 排序
	 * @param string $order 
	 * @return object
	 * @author wave
	 */
	public function order($order) {
		$this->params['order'] = 'order by ';
		$this->params['order'] .= $order;
		return $this;
	}

	/**
	 * 获取当前表的主键
	 * @return string
	 * @author wave
	 */
	public function getPk()	{
		$this->getfields();
		$content = Cache::read($this->name);
		if(is_array($content) && $content) {
			foreach ($content as $key => $value) {
				if($value['Key'] == 'PRI') {
					return $value['Field'];
				}
			}
		}
		return '';
	}

	/**
	 * 提交事物
	 * @author wave
	 */
	public function commit() {
		if(is_object(self::$db)) {
			return self::$db->commit();
		}else {
			return $this->query('COMMIT');
		}
	}

	/**
	 * 回滚事务
	 * @author wave
	 */
	public function rollback() 
	{
		if(is_object(self::$db)) {
			return self::$db->rollback();
		}else {
			return $this->query('ROLLBACK');
		}
	}

	/**
	 * 开启事物处理
	 * @author wave
	 */
	public function begin() 
	{
		if(is_object(self::$db)) {
			return self::$db->beginTransaction();
		}else {
			return $this->query('BEGIN');
		}
	}

	/**
	 * 执行sql
	 * @param string  $sql SQL语句
	 * @return resource
	 * @author wave
	 */
	public static function execute($sql) {
		return $this->query($sql);
	}

	/**
	 * 执行sql
	 * @param string  $sql SQL语句
	 * @return resource
	 * @author wave
	 */
	public function query($sql) {
		$start_time = microtime(true);
		$sql = trim($sql);
		$data = array();
		if(!is_object(self::$db)) {
			$query = mysql_query($sql);
			if(strpos($sql, 'select') !== false || strrpos($sql, 'show') !== false){
				if($query) {
					while($row = mysql_fetch_assoc($query)) {
						$data[] = $row;
					}
					$this->result($query); //释放资源
				}
			}else {
				if($query) {
					$data = true;
				}
			}
		}else {
			//只能执行查询语句
			if(strpos($sql, 'select') !== false || strrpos($sql, 'show') !== false){
				$query = self::$db->query($sql);
				if($query){
					$data = $query->fetchAll(PDO::FETCH_ASSOC);
				}
			}else {
				//执行操作语句
				$data = self::$db->exec($sql);
			}
		}	
		$end_time = microtime(true);
		//打开debug才记录SQL语句
		if(DEBUG) {
			$this->cacheSql['time'][] = $end_time - $start_time;
			$this->cacheSql['sql'][] = $sql;
			Cache::wirte('sql',function($sql){
				return $sql;
			},$this->cacheSql,1);
		}

		$this->params = array();
		return $data;
	}


	/**
	 * PDO预处理命令
	 * @param string  $sql 执行语句
	 * @author wave
	 */
	public function prepares($sql) {
		if(is_string($sql)) {
			self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$sql = str_replace('%table%', $this->name, $sql);
			$this->params['prepare'] = self::$db->prepare($sql);
			return $this->params['prepare'];
		}	
	}

	/**
	 * 綁定的函數
	 * @param Array $bind 要绑定的数组
	 * @param string $type 类型  是left right inner 默认是left
	 * @author wave
	 */
	protected function bind() {
		$data = array();
		if(is_array($this->bind)) {
			foreach($this->bind as $value) {
				$joins['type'] = isset($value['type']) ? $value['type'] : 'left';
				$joins['alias'] = isset($value['alias']) ? $value['alias'] : '';
				$joins['table'] = isset($value['table']) ? $value['table'] : '';
				$foreignKey = isset($value['foreignKey']) ? $value['foreignKey'] : '';
				$beRelated = isset($value['beRelated']) ? $value['beRelated'] : '';
				$alias = isset($joins['alias']) ? $joins['alias'] : $joins['table'];
				$joins['where'] = '`' . $alias . '`.`' . $foreignKey . 
								'`=`'. $this->name  . '`.`' . $beRelated .'`';
				if(!empty($joins['table']) && !empty($joins['where'])) {
					$data[] = $joins;
				}
			}
			
		}
		if(!empty($data)) {
			$this->joins($data);
		}
	}

	/**
	 * 自动释放内存
	 * @param resource $query 资源
	 * @return null
	 * @author wave
	 */
	protected function result($query) {
		return  isset($query) ? mysql_free_result($query) : '';
	}

	/**
	 * 关闭连接
	 * @param resource $db 资源
	 * @return null
	 * @author wave
	 */
	protected function colse($db) {
		return mysql_close($db);
	}


	/**
	 * 组装条件的符号
	 * @param string $params 条件的下标 如$conditions['a >'] = 1会自动拆分成a > 1
	 * @return Array
	 * @author wave
	 */
	protected function packsign($params)
	{
		if(preg_match('/(\<|\>|\>\=|\<=|\<\>|like)/', $params))
		{
			$fh = array_filter(explode(' ', $params));
			$params = '`'.$fh['0'].'` '.$fh['1'];
		}else {
			$params = '`'.$params.'`=';
		}
		return $params;
	}

	/**
	 * 判断param是否存在
	 * @author wave
	 */
	private function _isset() {
		$this->params['fields'] = isset($this->params['fields']) ? $this->params['fields'] : $this->getfields();
		$this->params['joins'] = isset($this->params['joins']) ? $this->params['joins'] : '';
		$this->params['where'] = isset($this->params['where']) ? $this->params['where'] : '';
		$this->params['group'] = isset($this->params['group']) ? $this->params['group'] : '';
		$this->params['having'] = isset($this->params['having']) ? $this->params['having'] : '';
		$this->params['order'] = isset($this->params['order']) ? $this->params['order'] : '';
		$this->params['limit'] = isset($this->params['limit']) ? $this->params['limit'] : '';
		$this->params['alias'] = isset($this->params['alias']) ? $this->params['alias'] : '';
		$this->params['fields'] .=	isset($this->params['alias']['fileds']) ? ','.$this->params['alias']['fileds'] : '';
	}

	/**
	 * 获取表的字段
	 * @return String
	 * @author wave
	 */
	protected function getfields() 
	{
		Cache::wirte($this->name,function($model){
			$sql  = 'show columns from '.$model['name'];
			$data  = $model['Model']->query($sql);
			return $data;
		},array('Model'=>$this,'name'=>$this->name),0);
		
		$data = Cache::read($this->name);
		
		$_fields = ' * ';

		if($data) {
			$_fields = '';
			$alias = !empty($this->alias) ? $this->alias : $this->name;
			foreach($data as $k => $v) {
				$_fields .= '`'.$alias.'`.`'.$v['Field'] . '`,';
			}
		}
		
		return rtrim($_fields,',');
	}

	/**
	 * 连接数据库
	 * @param Array $arr 数组
	 * @author wave
	 */
	protected static function link($arr)
	{
		if(self::$link == null) {
			self::$link = new db(isset($arr['host']) ? $arr['host'] : '',
				isset($arr['username']) ? $arr['username'] : '',
				isset($arr['pwd']) ? $arr['pwd'] : '',
				isset($arr['table']) ? $arr['table'] : '',
				isset($arr['charset']) ? $arr['charset'] : '',
				isset($arr['dbtype']) ? $arr['dbtype'] : '',
				isset($arr['port']) ? $arr['port'] : '',
				isset($arr['dns']) ? $arr['dns'] : ''
			);	
		}	
		if(EXTENSION == 1) {
			return self::$link->connect();
		}
		return self::$link->pdo_connect(); 
	}

	 // 回调方法 初始化模型
    protected function _initialize() {}
 }