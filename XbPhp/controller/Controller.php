<?php
/**
 * 控制器
 * @author wave
 */

class Controller {
	//引入模型的数组
	public $uses = array();

	//加载组件数组
	public $component = array();

	//视图类
	public $view = '';

	//初始化类
	static $init = array();

	public function __construct() {
		//加载组件或模型
		$this->AtuoLoads();
		//视图
		$this->views();
		//获取请求
		$this->request();
		//自动初始化回调函数
		$this->_initialize();
		
	}


	/**
	 * 设置键值
	 * @param string $key  键
	 * @param string $value  值
	 */
	public function __set($key,$value)
	{
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
	 * 判断请求是post get ajax
	 * @param string $val 请求的方式值只能写post get ajax
	 * @return boolean
	 * @author wave
	 */
	public function is($val) {
		switch ($val) {
			case 'ajax':
				if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
					return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : flase;
				}
				break;
			default:
				return (strtolower($_SERVER['REQUEST_METHOD']) === $val) ? true : false;
				break;
		}
	}


	/**
	 * 加载文件并初始化
	 * @param null $name 文件名
	 * @param null $path 路徑
	 * @author wave
	 */
	public function load($name = null, $path = null) {
		if(load($name,$path)) {
			$this->$name = $this->run_cache($name);
		}
	}


	/**
	 * 加载模型
	 * @param null $model 模型
	 * @param null $prefix 字段前缀
	 * @author wave
	 */
	public function loadModel($model = null,$prefix = null,$connect = null) {
		static $model_arr = array();
		$path = APP_PATH.DS.ROOT_MODEL.DS;
		if(!empty($model) && !in_array($model,$model_arr)) {
			$model_arr[$model] = $model.'Model.php';			
		}
		if(isset($model_arr[$model]) && load($model_arr[$model],$path)) {
			$obj_name  = rtrim($model_arr[$model],'.php');
			//改变$this->uses引入模型的名字
			$model = $this->change_model($model, $model_tem); 
			if(!isset(self::$init[$obj_name])) {
				self::$init[$obj_name] = new $obj_name($model,$prefix,$connect);
			}
			$model = str_replace('Model', '', get_class(self::$init[$obj_name]));
			$model = isset($model_tem) ? $model_tem : $obj_name;
			$this->$model = self::$init[$obj_name];
		}else {
			$model = $this->change_model($model, $model_tem);
			if(!isset(self::$init[$model])) {
				self::$init[$model] = new Model($model,$prefix,$connect);
			}
			$model_name = isset($model_tem) ? $model_tem : $model;
			$this->$model_name = self::$init[$model];
		}
		
	}

	/**
	 * 改变$this->uses引入的模型，在字符大写加下划线并转换小写
	 * @param string $model 要改变的模型名字
	 * @param string &$model_tem 保存原来的模型名字的地址
	 * @return string
	 * @author wave
	 */
	protected function change_model($model, &$model_tem) {
		$model_tem = $model;
		$arr =preg_split("/(?=[A-Z])/",$model); //以大写字母为分割符，拆分成数组
		if(!empty($arr)) {
			$str =strtolower(join('_',(array_values($arr))));
			if($str['0'] == '_') {
				$model = substr($str,strpos($str,$str['0'])+strlen($str['0']));
			}
		}
		return $model;
	}

	/**
	 * 获取请求方法控制器名字与方法
	 * @author wave
	 */
	protected function request() {
		//获取默认的数组
		$this->request->data =& $_POST;
		//获取默认的方法名字
		$this->request->action = A_INDEX;
		//获取默认的控制器名字
		$this->request->controller = M_INDEX;

		$this->params = isset($_SERVER['PATH_INFO']) ? explode(SIGN,ltrim(strip_tags($_SERVER['PATH_INFO']),'/')) : '';
		if(isset($this->params['0']) && $this->params['0'] == APP_PATH)  {
			//删除第一个URL的参数
			array_splice($this->params,0,1);
		}

		$params = $this->params;
		if(!empty($this->params) && preg_match('/[\.]/', $this->params[count($params) -1])) {
			$str = substr($params[count($params) - 1],strpos($params[count($params) - 1],'.'));
			$this->params[count($params)- 1] = str_replace($str,'',$params[count($params) - 1]);
		}

		//获取URL的访问的控制器
		if(isset($this->params['0'])) {
			$this->request->controller = $this->params['0'];
		}
		//获取URL的访问的方法
		if(isset($this->params['1'])) {
			$this->request->action = $this->params['1'];
		}
	}


	/**
	 * 加载组件
	 * @author wave
	 */
	protected function Components($object) {
		static $arr = array();
		if(!in_array($object,$arr)) {
			$arr[$object] = $object.'Component.php';
			if(load($arr[$object],ROOT_PATH.DS.COMP)) {
				$object_name = rtrim($arr[$object],'.php');
				$this->{$arr[$object]} = $this->run_cache($object_name);
			}
		}
	}


	/**
	 * 初始化视图类
	 * @return Object
	 * @author wave
	 */
	private function views() {
		if(class_exists('view')) {
			$this->view = $this->run_cache('view');
		}
	}

	/**
	 * 自动加载模型或组件
	 * @author wave
	 */
	protected function AtuoLoads() {
		if(!empty($this->uses)) {
			foreach($this->uses as $val) {
				$this->loadModel($val);
			}
		}
		if(!empty($this->component)) {
			foreach($this->component as $value) {
				$this->Components($value);
			}
		}
	}	

	/**
	 * 缓存初始化对象
	 * @param string $obj 类名
	 * @return object
	 * @author wave
	 */
	protected function run_cache($obj) {
		if(!in_array($obj,self::$init)){
			self::$init[$obj] = new $obj();
		}
		return self::$init[$obj];
	}

	protected function _initialize() {}
}