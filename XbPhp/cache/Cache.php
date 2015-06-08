<?php
/**
 * 缓存类
 * @author wave
 */
class Cache
{
	//缓存文件的路径
	public static $path = null;

	//缓存的前缀
	protected static $cache_prefix = 'Xb_Cache_';

	//缓存路径
	protected static $cache_dir = null;

	/**
	 * 初始化
	 * @author wave
	 */
	protected static function init() {
		$path = !empty(self::$path) ? self::$path : CACHE_DIR;
		//缓存路径
		self::$cache_dir = CACHE.DS.$path.DS;
		if(!file_exists(ROOT.DS.APP_PATH.DS.self::$cache_dir)) {
			mkdirs(self::$cache_dir);
		}
	}

	/**
	 * 设置缓存文件的路径
	 * @param string $name 路径名字
	 * @return string
	 * @author wave
	 */
	public static function path($name = null) {
		if(!empty($name)) {
			self::$path = $name;
		}
		return self::$path;
		
	}

	/**
	 * 写入缓存
	 * @param string $name 缓存文件名字
	 * @param function $callback 回调函数，必须要有返回值
	 * @param Array or string or Object $params 要传进去的参数
	 * @param int $time 写入的缓存时间
	 * @return array
	 * @author wave
	 */
	public static function wirte($name,$callback,$params = null,$time = TIME) {
		self::init();
		$file = ROOT.DS.APP_PATH.DS.self::$cache_dir;
		$content = '';
		if(!file_exists($file.self::$cache_prefix.$name)) {
			$content = $callback($params);
			if(!empty($content)) {
				file_put_contents($file.self::$cache_prefix.$name,serialize($content));
				return ;
			}
		}

		if(empty($time)) {
			return ;
		}

		if(file_exists($file.self::$cache_prefix.$name)) {
			$cache_time = strtotime(date('Y-m-d H:i:s')) - strtotime(date('Y-m-d H:i:s',filemtime($file.self::$cache_prefix.$name)));
			if(($cache_time / 1000) >= $time || $time == 1) {
				$content = $callback($params);
				if(!empty($content)) {
					file_put_contents($file.self::$cache_prefix.$name,serialize($content));
					return ;
				}
			}
		}
	}

	/**
	 * 读取缓存文件
	 * @param string $name 缓存文件名字
	 * @return array
	 * @author wave
	 */
	public static function read($name) {
		self::init();
		$file = ROOT.DS.APP_PATH.DS.self::$cache_dir;
		if(file_exists($file.self::$cache_prefix.$name)) {
			return unserialize(file_get_contents($file.self::$cache_prefix.$name));
		}
		return false;
	}

	/**
	 * 删除
	 * @param string $name 删除文件名字
	 * @author wave
	 */
	public static function del($name) {
		$path = !empty(self::$path) ? self::$path : CACHE_DIR;
		//缓存路径
		self::$cache_dir = CACHE.DS.$path.DS;
		if(file_exists(ROOT.DS.APP_PATH.DS.self::$cache_dir.self::$cache_prefix.$name)) {
			return unlink(ROOT.DS.APP_PATH.DS.self::$cache_dir.self::$cache_prefix.$name);
		}
		return false;
	}
}