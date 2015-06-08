<?php
/**
 * 数据库连接类
 * @author wave
 */


class db 
{
	//数据库主机地址
	protected $host = null;

	//数据库帐号
	protected $username = null;

	//数据库密码
	protected $pwd = null;
	
	//数据库名
	protected $table = null;
	
	//数据库端口
	protected $port = null;

	//数据库编码
	protected $charset = null;

	protected $dbtype = null;

	protected $dns = null;



	/**
	 * 自动初始化
	 * @param string $host 数据库主机地址
	 * @param string $username 数据库帐号
	 * @param string $pwd 数据库密码
	 * @param string $table 数据库名
	 * @param int $port 数据库端口
	 * @param int $charset 编码
	 * @param string $dbtype 连接数据库类型
	 * @param string $dns 选择其他类型的数据库时候用
	 * @author wave
	 */
	public function __construct($host, $username, $pwd, $table, $charset, $dbtype = 'mysql', $port = 3306,$dns = null)
	{
		$this->host = $host;
		$this->username = $username;
		$this->pwd = $pwd;
		$this->table = $table;
		$this->charset = $charset;
		$this->port = $port;
		$this->dbtype = $dbtype;
		$this->dns = $dns;
	}

	/**
	 * 临时连接
	 * @author wave
	 */
	public function connect() 
	{
		$db = mysql_connect($this->host,$this->username,$this->pwd,true,131072) or die('database connection failed');
		mysql_select_db($this->table,$db) or die('The database table does not exist');
		mysql_query('SET NAMES '.$this->charset) or die('Please fill in the database code');
		return $db;
	}

	/**
	 * PDO连接数据库
	 * @author wave
	 */
	public function pdo_connect()
	{
		if(extension_loaded('pdo_mysql'))
		{

			if(empty($this->dns)){
				$dns = "{$this->dbtype}:host={$this->host};dbname={$this->table}";
			}else {
				$dns = $this->dns;
			}
			
			try{
				$pdo = new PDO($dns,$this->username,$this->pwd);
			}catch(PDOException $e){
				die($e->getMessage());
			}
			$pdo->query('set names '.$this->charset);
			return $pdo;
		}else {
			die('php.ini not extension pdo_mysql.dll');
		}
	}
}