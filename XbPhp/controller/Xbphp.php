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
		header("X-Powered-By:XbPHP");
		if(!empty($_SERVER['ORIG_PATH_INFO'])) {
			$url = $_SERVER['ORIG_PATH_INFO'];
		//windows or linux  nginx apache属性
		}elseif(!empty($_SERVER['PATH_INFO'])) {
			$url = $_SERVER['PATH_INFO'];
		}elseif(!empty($_SERVER['REQUEST_URI'])) {
			$url = $_SERVER['REQUEST_URI'];
		}
		
		$params = (strpos($url, '/') !== false) ? explode('/',ltrim(strip_tags($url),'/')) : '';
		if(!empty($params)) {
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
			 //删除目录文件
			if(isset($params['0']) && strtolower($params['0']) == strtolower(APP_PATH)) {
				array_splice($params,0,1);
			}
		}

		return is_array($params) ? array_values(array_filter($params)) : '';
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

	/**
	 * 获取框架加载完成的消耗的内存
	 * @return int
	 * @author wave
	 */
	public static function endMemory() {
		return memory_get_usage(true);
	}

	/**
	 * 获取框架消耗的内存
	 * @return string
	 * @author wave
	 */
	public static function memory($end = ''){
		if(!empty($end)) {
			$size = $end - START_MEMORY;
			$unit = array('b','kb','mb','gb','tb','pb'); 
			return round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i]; 
		} 
		
	}

	/**
	 * 转跳的URL
	 * @param string $url 要访问的URL
	 * @param int $type 类型 1为动态URL访问,2为伪静态访问
	 * @param Array $option 数组参数,下标为2开始,如果下标数字下于2则会失效
	 */
	public static function toUrl($url = '',$type = 1,$option = array()) {
		if(empty($url)) {
			return '';
		}
		if(strpos($url, '&') !== false && $type != 1){
			$url = (substr($url, 0,1) === '?') ? substr($url, 1) : $url;
		}
		switch ($type) {
			case 1: //为动态访问
				if(strpos($url, '/') !== false) {
					$urlArr = array_values(array_filter(explode('/', $url)));
					$dataArr = array();
					foreach ($urlArr as $key => $value) {
						switch ($key) {
							case 0:
								$dataArr[M] = $value;
								break;
							case 1:
								$dataArr[A] = $value;
								break;
							default:
								$key = (!empty($option) && isset($option[$key])) ? $option[$key] : $key;
								$dataArr[$key] = $value;
								break;
						}
					}
					$url = '?'.http_build_query($dataArr);
				}
				break;
			case 2:
				if(strpos($url, '&') !== false){
					$urlArr = explode('&', $url);
					$str  = '';
					foreach($urlArr as $key => $value) {
						$valArr = explode('=', $value);
						$str .= (!empty($option) && isset($option[$key])) ? '/'.$option[$key].'/'.$valArr[1] : '/'.$valArr[1];
					}
					$url = $str;
				}
				break;
		}
		return $url;
	}


}