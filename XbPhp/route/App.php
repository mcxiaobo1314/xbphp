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
		$request = array(); //URL的参数
		$num = 0; //动态URL访问
		config::$disableController = isset(config::$disableController) ? config::$disableController : array();
		//动态的URL的路由器
		if(!isset($params['rewirte'])) {
			$params[M] = isset($params[M]) ? $params[M] : M_INDEX;
			$params[M] = in_array($params[M], config::$disableController) ? M_INDEX : $params[M];
			$params[A] = isset($params[A]) ? $params[A] : A_INDEX;
			$controller = $params[M].'Controller.php';
			$action = $params[A];
		}else {  
			//伪静态的URL的路由器
			if(!empty($params)) {
				unset($params['rewirte']);
				$params['0'] = isset($params['0']) ? $params['0'] : M_INDEX;
				$params['0'] = in_array($params['0'], config::$disableController) ? M_INDEX : $params['0'];
				$params['1'] = isset($params['1']) ? $params['1'] : A_INDEX;
				if(strpos($params['1'],'?') !== false) {
					$str = substr($params['1'],strpos($params['1'],'?'));
					$params['1'] = str_replace($str,'',$params['1']);
				}
				$controller = $params['0'].'Controller.php';
				$action = $params['1'];
				$num = 1;
			}	
		}

		//引入控制器,并初始化控制器
		if(!empty($action) && !empty($controller)) {
			if(load($controller,APP_PATH.DS.ROOT_CONTROLLER)) {
				$controller_name = isset($params[M]) ? $params[M] : $params['0'];
				$xb = Xbphp::run_cache($controller_name.'Controller');
			}

			if(isset($xb) && method_exists($xb,$action)) {
				$paramArr = self::replaceArr(array($controller_name,$action),'',$params);
				$request = isset($params['params']) ? $params['params'] : $paramArr;
				$route = load('route.php',APP_PATH.DS.DATABASE.DS); //加载路由规则

				if(!empty($num) && isset($route['rewirte'][rtrim($controller,'Controller.php')][$action])) {
					$request = implode('/',$request);
					$this->route('rewirte',$request,$route,$controller,$action);
				}

				//动态url规则
				if(empty($num) && isset($route['trends'][rtrim($controller,'Controller.php')][$action])) {
					$this->route('trends',$request,$route,$controller,$action);
				}
				call_user_func_array(array($xb,$action),$request);
			}else {
				load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'); 
				exit;
			}
		}else {
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
			exit;
		}
		//打开DEUG
		if(DEBUG) {
			self::debug();
		}
	}

	/**
	 * 设置路由规则
	 * @param string $op 
	 * @param Array $request 请求的参数
	 * @param Array $route 路由
	 * @param string $controller
	 * @param string $action
	 */
	protected function route($op,&$request,$route,$controller,$action) {
		if(empty($request))  {
			return '';
		}
		
		$request = ($op === 'rewirte') ?  $request : http_build_query($request);
		
		if(!isset($route[$op][rtrim($controller,'Controller.php')][$action])) {
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		 	exit;
		}

		if(preg_match($route[$op][rtrim($controller,'Controller.php')][$action],$request,$arr)) {
			$request = array_values(array_filter(array_splice($arr,0,1)));
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
		if(isset($arr) && !empty($params)) {
			return array_filter(str_replace($arr,$rArr, $params));
		}
	}
}

