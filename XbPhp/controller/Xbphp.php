<?php
/**
 * 静态公共类
 * @author wave
 */
class Xbphp  {

	//初始化
	public static $init = array();

	/**
	 * 解析服務器URL伪静态
	 * @return String
	 * @author wave
	 */
	public static function getServerUrl() {
		if(!empty($_SERVER['ORIG_PATH_INFO'])) {
			$url = $_SERVER['ORIG_PATH_INFO'];
		//windows or linux  nginx apache属性
		}elseif(!empty($_SERVER['PATH_INFO'])) {
			$url = $_SERVER['PATH_INFO'];
		}elseif(!empty($_SERVER['REQUEST_URI'])) {
			$url = $_SERVER['REQUEST_URI'];
		}
		$params =  explode(SIGN,ltrim(strip_tags($url),'/'));
		if(strtolower($params['0']) == strtolower(basename(ROOT))) {
			array_splice($params,0,1);
		}
		if(strpos($params[count($params) - 1],'.') !== false) {
			$str = substr($params[count($params) - 1],strpos($params[count($params) - 1],'.'));
			$params[count($params)- 1] = str_replace($str,'',$params[count($params) - 1]);
		}
		if(strpos($params[count($params) - 1],'?') !== false) {
			$str = substr($params[count($params) - 1],strpos($params[count($params) - 1],'?'));
			$params[count($params)- 1] = str_replace($str,'',$params[count($params) - 1]);
		}
		return $params;
	}


	/**
	 * 缓存初始化对象
	 * @param string $obj 类名
	 * @return object
	 * @author wave
	 */
	public static function run_cache($obj) {
		if(!in_array($obj,self::$init)){
			self::$init[$obj] = new $obj();
		}
		return self::$init[$obj];
	}



}