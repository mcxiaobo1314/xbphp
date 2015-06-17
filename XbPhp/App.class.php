<?php
/**
 * 路由器与加载机制
 * @author wave
 */

class App 
{

	/**
	 * 每次初始化的执行加载和初始化
	 * @author wave
	 */
	public function __construct() {
		$params = self::getUrl();  //獲取URL參數數組
		$controller = null; //控制器路径
		$name = null;       //控制器名称
		$request = array(); //URL的参数
		//动态的URL的路由器
		if(!isset($params['rewirte'])) {
			$params[M] = isset($params[M]) ? $params[M] : M_INDEX;
			$params[A] = isset($params[A]) ? $params[A] : A_INDEX;
			$controller = $params[M].'Controller.php';
			$action = $params[A];
		}else {  
			//伪静态的URL的路由器
			if(!empty($params)) {
				unset($params['rewirte']);
				$params['0'] = isset($params['0']) ? $params['0'] : M_INDEX;
				$params['1'] = isset($params['1']) ? $params['1'] : A_INDEX;
				$controller = $params['0'].'Controller.php';
				$action = $params['1'];
			}	
		}
		//引入控制器,并初始化控制器
		if(!empty($action) && !empty($controller)) {
			if(load($controller,APP_PATH.DS.ROOT_CONTROLLER)) {
				$controller_name = isset($params[M]) ? $params[M] : $params['0'];
				$name = $controller_name.'Controller';
				$xb = Xbphp::run_cache($name);
			}
			if(isset($xb) && method_exists($xb,$action)) {
				$request = isset($params['params']) ? $params['params'] : self::replaceArr(array($controller_name,$action),'',$params);
				call_user_func_array(array($xb,$action),$request);
				//打开DEUG
				if(DEBUG) {
					self::debug();
				}
			}else {
				load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'); 
				exit;
			}
		}else {
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'); 
			exit;
		}
	}

	/**
	 * 程序运行(使用單例初始化程序)
	 * @author wave
	 */
	public static function Run() {
		return Xbphp::run_cache('App');
	}

	/**
	 * 获取GET的URL
	 * @return Array
	 * @author wave
	 */
	protected static function getUrl() {
		$pathinfo  = Xbphp::getServerUrl();
		if(empty($pathinfo)) {
			if(!empty($_GET) && isset($_GET[M]) && isset($_GET[A])) {
				$pArr =array_filter(self::replaceArr(array($_GET[M],$_GET[A]),'',$_GET));
				$params[M] = $_GET[M];
				$params[A] = $_GET[A];
				$params['params'] = $pArr; 
			}
		}else {
			$pathinfo['rewirte'] = true;
			$params = $pathinfo;
		}
		return !empty($params) ? $params : '';
	}


	/**
	 * debug方法
	 * @author wave
	 */
	protected static function debug() {
		$sqlArr = Cache::read('sql');
		$time = Cache::read('time');
		Cache::del('sql');
		require ROOT.DS.ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'.DS.'iframe.tpl';
	}


	/**
	 * 替换函数
	 * @param Array or string $arr 要替换的值
	 * @param Array or string $rArr 被替换的值
	 * @param Array or string $params 要替换的数据
	 * @return Array or string 
	 * @author wave
	 */
	protected static function replaceArr($arr,$rArr,$params) {
		if($arr && $params) {
			return array_filter(str_replace($arr,$rArr, $params));
		}
	}
}

