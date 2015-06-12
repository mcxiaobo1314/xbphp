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
function loadModel($model = null) {
	static $model_arr = array();
	$model_name = null;
	if(!empty($model) && !in_array($model,$model_arr)) {
		$model_arr[$model] = $model.'Model.php';
	}
	if(isset($model_arr[$model]) && load($model_arr[$model],APP_PATH.DS.ROOT_MODEL.DS)) {
		$model_name = rtrim($model_arr[$model],'.php');
		return new $model_name();
	}
	return false;	
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
	if(!empty($name) && !in_array(APP_PATH.$name,$name_arr)) {
		$name_arr[APP_PATH.$name] = $name;
	}
	if(file_exists(realpath($file.DS.$name_arr[APP_PATH.$name]))) {
		return require realpath($file.DS.$name_arr[APP_PATH.$name]);
	}
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
			$val .= fread($fp,$size);
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
 * @param String $cut   要截取的字符串
 * @param int  $p       权限
 * @author wave
 */
function mkdirs($path,$cut = '/',$p = 0777) {
	$file = APP_PATH.DS;
	$arr = array_filter(explode($cut,$path));
	foreach($arr as $key => $val) {
		$file .= $val.DS;
		if(!file_exists(ROOT.DS.$file) && !is_dir(ROOT.DS.$file)) {
			@mkdir(ROOT.DS.$file,$p);
		}
	}
}
