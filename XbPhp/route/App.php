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
		$disableArr = !empty(config::$disableController) ? config::$disableController : array();
		$params['0'] =  !in_array($params['0'], $disableArr) ? $params['0'] : M_INDEX;
		$params['1'] = Xbphp::strposReplace($params['1'],'?');
		$controller = $params['0'].'Controller.php';
		$action = $params['1'];
		if(load($controller,APP_PATH.DS.ROOT_CONTROLLER)) {
			$controller_name = isset($params[M]) ? $params[M] : $params['0'];
			$xb = Xbphp::run_cache($controller_name.'Controller');
		}
		if(isset($xb) && method_exists($xb,$action)) {
			$request = self::replaceArr(array($controller_name,$action),'',$params);
			$request = !empty($params['params']) ? $params['params'] : $request;
			$_GET = !empty($_GET) ? array_merge($_GET,$request) : $_GET;
			$route = load('route.php',APP_PATH.DS.DATABASE.DS); //加载路由规则
			if(!empty($route[rtrim($controller,'Controller.php')][$action])) {
				$request = implode('/',$request);
				$arr = $this->route($request,$route,$controller,$action);
				$requestStr = isset($request[0]) ? $request[0] : '';
				$requestArr = explode('/', $requestStr);
				$keyArr = is_array($requestArr) ? array_diff($requestArr,$arr) : array();
				$_GET = !empty($keyArr) ?  array_merge($_GET,array_combine($keyArr, $arr)) : array_merge($_GET,$arr);
			}
			$request = !empty($request) ? $request : array(); 
			call_user_func_array(array($xb,$action),$request);
			//打开DEUG
			if(DEBUG) {
				self::debug();
			}
		}else{
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
			exit;
		}

	}

	/**
	 * 设置路由规则
	 * @param Array $request 请求的参数
	 * @param Array $route 路由
	 * @param string $controller
	 * @param string $action
	 */
	protected function route(&$request,$route,$controller,$action) {
		if(empty($request))  {
			return '';
		}
		$request = !is_array($request) ?  $request : ltrim(Xbphp::toUrl(http_build_query($request),2,array_keys($request)),'/');
		if(!isset($route[rtrim($controller,'Controller.php')][$action])) {
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		 	exit;
		}
		if(preg_match($route[rtrim($controller,'Controller.php')][$action],$request,$arr)) {
			$valueArr = isset($arr[0]) ? explode('/', $arr[0]) : array();
			$request = array_values(array_filter(array_splice($arr,0,1)));
			return $arr;
		}else {
		 	load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		 	exit;
		}
	}

	/**
	 * 获取GET的URL
	 * @return Array
	 * @author wave
	 */
	protected static function getUrl() {
		$pathinfo  = Xbphp::getServerUrl();
		if(!empty($pathinfo)) {
			$params = $pathinfo;
		}else {
			$_GET[M] = !empty($_GET[M]) ? $_GET[M] : M_INDEX;
			$_GET[A] = !empty($_GET[A]) ? $_GET[A] : A_INDEX;
			$pArr = array();
			$pArr =array_filter(self::replaceArr(array($_GET[M],$_GET[A]),'',$_GET));
			$params['0'] = $_GET[M];
			$params['1'] = $_GET[A];
			$params['params'] = $pArr; 
		}
		return $params;
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
		if(isset($arr) && !empty($params)) {
			return array_filter(str_replace($arr,$rArr, $params));
		}
	}

	public function __destruct(){
		unset(Xbphp::$init);
	}
}

