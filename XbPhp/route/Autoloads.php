<?php
/**
 * 自動加載核心文件
 * @author wave
 */

/**
 * 自动加载核心文件(非静态类)
 * @param string $class_name  文件名
 * @author wave
 */
function auoload($class_name) {
	Autoloads(ROOT.DS.ROOT_PATH.DS.ROOT_CONTROLLER.DS,$class_name,'.php');
	Autoloads(ROOT.DS.ROOT_PATH.DS.ROOT_MODEL.DS,$class_name,'.php');
	Autoloads(ROOT.DS.ROOT_PATH.DS.ROOT_VIEW.DS,$class_name,'.php');
	Autoloads(ROOT.DS.ROOT_PATH.DS.ROOT_COM.DS,$class_name,'.php');
	Autoloads(ROOT.DS.ROOT_PATH.DS.CACHE.DS,$class_name,'.php');

}

/**
 * 加载核心文件(非静态类)
 * @param string $path  路径
 * @param string $class_name 文件名
 * @param string $extensions 后缀名
 * @author wave
 */
function Autoloads($path,$class_name,$extensions = NULL) {
 	set_include_path ( get_include_path () . PATH_SEPARATOR . $path );  
    $extensions != NULL ? spl_autoload_extensions ( $extensions ) : '';  
    spl_autoload ( $class_name ); 
}


/**
 * 加载静态文件
 * @author wave
 */
function AutoloadsStatic() {
	load('config.php',APP_PATH.DS.DATABASE) ; //数据库配置文件
	load('Error.php',ROOT_PATH.DS.ROOT_ERROR.DS);					//錯誤
	load('AppModel.php',APP_PATH.DS.ROOT_MODEL.DS);					//模型
	load('AppController.php',APP_PATH.DS.ROOT_CONTROLLER.DS);		//控制器
	load('Socket.php',ROOT_PATH.DS.VENDOR.DS); //socket加载
}

spl_autoload_register('auoload');
AutoloadsStatic();

