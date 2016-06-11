<?php
/**
 * 方法文件
 * @author wave
 */


/**
 * 加载模型
 * @param null $model 模型
 * @return object
 * @author wave
 */
function loadModel($model = null,$prefix = null,$connect = null) {
	static $model_arr = array();
	$model_name = $model.'Model.php';
	if(!load($model_name,APP_PATH.DS.ROOT_MODEL.DS)) {
		return false;
	}
	if(!isset($model_arr[$model])) {
		$model = change_model($model,$model_tem);
		$model_name = rtrim($model_name,'.php');
		$model_arr[$model] = new $model_name($model,$prefix,$connect);
		return $model_arr[$model];
	}
	return is_object($model_arr[$model]) ? $model_arr[$model] : false;	
}


/**
 * 输出
 * @param null $val 输出的变量
 * @author wave
 */
function dump($val = null) {
	echo '<pre>';
	var_dump($val);
	echo '</pre>';
}


/**
 * 加载文件
 * @param null $name 文件名
 * @param null $path 路径
 * @author wave
 */
function load($name = null , $path = null) {
	static $name_arr = array();
	$file = ROOT.DS.$path;
	$name_path = realpath($file.DS.$name);
	if(!empty($name) && !in_array($name_path,$name_arr)) {
		$name_arr[$name_path] = $name_path;
	}

	if(file_exists($name_arr[$name_path])) {
		return require $name_arr[$name_path];
	}
	unset($name_arr[$name_path]);
	return false;
}

/**
 * URL转跳方法
 * @param null $url 转跳的地址
 * @param null $time 时间
 * @param null $msg 提示的信息
 */
function redirect($url = null, $time = 0, $msg = '') {
	$url = str_replace(array("\n", "\r"), '', $url);
	if(!empty($msg)) {
		$msg  = "系统将在{$time}秒之后自动跳转到{$url}！";
		if($time === 0) {
			header('Location: '.$url);
			exit;
		}else {
 			header("refresh:{$time};url={$url}");
            echo($msg);
            exit;
		}
	}else {
		$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        exit($str);
	}
}

/**
 * 读取数据数据
 * @param String $path 文件路径
 * @param Sring $m 打开模式
 * @param int $size 读取的数据字节
 * @return Array
 * @author wave
 */
function read($path,$m ='r',$size=1024) {
	$val = '';
	if(file_exists($path)) {
		$fp = fopen($path,$m);
		while(!feof($fp)) {
			$val .= fgets($fp);
		}
		fclose($fp);
		clearstatcache();//清除文件缓存
		return $val;
	}
}

/**
 * 写入数据
 * @param String $path 文件路径
 * @param Sring $data 要保存的数据
 * @param int $m 模式
 * @return boolean
 * @author wave
 */
function write($path,$data,$m = 'r') {
	if(file_exists($path)) {
		$fp = fopen($path,$m);
		fwrite($fp, $data);
		fclose($fp);
		return true;
	}
	return false;
}

/**
 * 抓取页面
 * @param String $url 地址
 * @param string $request 请求类型 目前只支持post
 * @param Array $send_data 发送的数据
 * @return String
 * @author wave
 */
function curl($url,$request = null,$send_data = null) {
	if(!function_exists('curl_init')) {
		return false;
	} 
	switch ($request) {
		case 'post':
			$submit = CURLOPT_POST;
			$num = 1; 
			break;
		default:
			$submit = CURLOPT_HEADER;
			$num = 0;
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, $submit, $num);
	if($request == 'post') {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $send_data);
	}
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

/**
 * 创建树形目录文件夹
 * @param String $path  文件夹名字
 * @param int  $p       权限
 * @author wave
 */
function mkdirs($path,$p = 0777) {
	$file = ROOT.DS.APP_PATH.DS.$path.DS;
	if(!file_exists($file) && !is_dir($file)) {
		@mkdir($file,$p,true);
		if(function_exists('chmod')) {
			@chmod($file,$p);
		}
	}

	
}

/**
 * 改变$this->uses引入的模型，在字符大写加下划线并转换小写
 * @param string $model 要改变的模型名字
 * @param string &$model_tem 保存原来的模型名字的地址
 * @return string
 * @author wave
 */
function change_model($model, &$model_tem) {
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
