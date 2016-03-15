<?php
/**
 * redis缓存
 * @author wave
 */
class Xredis {
	/**
	 * 初始化redis函数
	 * @param string  $host 主机IP
	 * @param int $port 端口
	 */
	public static function init() {
		if(class_exists('Redis')) {
			$host = !empty(config::$redis['host']) ? config::$redis['host'] : '127.0.0.1';
			$port = !empty(config::$redis['port']) ? config::$redis['port'] : 6379;
			$redis = XbPhp::run_cache('Redis');
			$redis->connect($host,$port);
			return $redis;
		}
	}
}


