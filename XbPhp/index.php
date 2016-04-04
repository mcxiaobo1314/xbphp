<?php
/**
 * 入口文件
 * @author wave
 */

if(version_compare(PHP_VERSION,'5.2.0') < 0) {
	die('您的PHP版本低于5.2.0,请下载5.2.0以上的版本');
}
define('START_MEMORY',memory_get_usage(true)); //获取开始内存
date_default_timezone_set('PRC');
//定义默认目录
$root_path = 'home';

/**
 * 定义目录文件名字
 */	
define('ROOT',str_replace('\\','/', dirname(dirname(__FILE__))));//跟目录
define('ROOT_PATH',basename(dirname(__FILE__)));            //框架名
define('DS','/');   										//定义斜杠
define('DATABASE','databases');                           	//数据库连接文件路径
define('ROOT_CONF','conf');                           		//系統公用配置文件路徑
define('ROOT_MODEL','model');                             	//模型文件的路径
define('ROOT_CONTROLLER','controller'); 					//控制器文件的路径
define('ROOT_VIEW','view'); 								//视图文件的路径
define('ROOT_COM','common');  								//公用方法文件路径
define('ROOT_LIBRARY','library');  							//存放第三方代码
define('ROOT_ERROR','error');                               //错误信息提示文件路径
define('CACHE','cache');  									//定义缓存文件路径
define('TEMPLATES','templates_c');                          //编译文件路径
define('COMP','Component');                                 //组件文件路径 
define('SESSIONS','sessions');                              //定义SESSION文件路径     
define('ROUTE','route');                              		//定义路由文件路径 
define('VENDOR','vendor');                              	//定义加载其他类路径          
define('LOGS','logs');                              		//定义錯誤日記文件路径

//引入方法文件
include ROOT.DS.ROOT_PATH.DS.ROOT_COM.DS.'functions.php'; 

if(isset($_SERVER['REDIRECT_URL'])) {
	//这个是linux或windows自动获取目录
	$pathinfo = $_SERVER['REDIRECT_URL']; 
	$arr = array_values(array_filter(explode('/',ltrim(strip_tags($pathinfo),'/'))));
	if(isset($arr['0']) && strtolower($arr['0']) == strtolower(ROOT_PATH)) {
		array_splice($arr,0,1);
	}
	if(isset($arr['0']) && file_exists(ROOT.DS.$arr['0'].DS)) {
		define('APP_PATH',$arr['0']);  //获取URL访问
	}

}else { //单独LINUX动态记录目录
	$_SERVER['PHP_SELF'] = str_replace(array('/','index.php'), '', $_SERVER['PHP_SELF']);
	if(!empty($_SERVER['PHP_SELF'])) {
		$arr = array_values(array_filter(explode('/', $_SERVER['PHP_SELF'])));
		if(count($arr) >= 1) {
			if(strtolower($arr['0']) == strtolower(basename(ROOT))) {
				array_splice($arr, 0,1);
			}
			if(isset($arr['0']) && file_exists(ROOT.DS.$arr['0'].DS)) {
				define('APP_PATH', $arr['0']);
			}
		}
	}else { //windows nginx 偽靜態
		$arr =  array_values(array_filter(explode('/',ltrim(strip_tags($_SERVER['REQUEST_URI']),'/'))));
		if(isset($arr['0']) && strtolower($arr['0']) == strtolower(basename(ROOT))) {
			array_splice($arr,0,1);
		}

		if(!empty($arr)) {
			if(isset($arr['0']) && file_exists(ROOT.DS.$arr['0'].DS)) {
				define('APP_PATH', $arr['0']);
			}
		}
	}
}

if(!defined('APP_PATH') || APP_PATH == NULL) {
	define('APP_PATH',$root_path);
}

//判断目录是否存在
if(!defined('APP_PATH') || !file_exists(ROOT.DS.APP_PATH.DS)) {
	load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'); 
	exit;
}

//如果加载自定义配置文件不成功，则加载系统默认的配置文件
if(!load('configure.inc.php',APP_PATH.DS.DATABASE.DS)) {
	load('configure.inc.php',ROOT_PATH.DS.ROOT_CONF.DS);
}

load('defined.php',APP_PATH.DS.DATABASE.DS);
load('Autoloads.php',ROOT_PATH.DS.ROUTE.DS); //自動加載文件，不加載靜態類文件
load('Xbphp.php',ROOT_PATH.DS.ROUTE.DS); //路由核心类     
load('App.php',ROOT_PATH.DS.ROUTE.DS); //路由与加载机制 

//执行程序入口
Xbphp::run_cache('App');