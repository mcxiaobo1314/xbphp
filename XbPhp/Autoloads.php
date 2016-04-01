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
function __autoload($class_name) {
	static  $config = array();
	if(empty($config) && !isset($config['include'])) {
		$config['include'] = array(
			ROOT.DS.ROOT_PATH.DS.ROOT_CONTROLLER.DS,
			ROOT.DS.ROOT_PATH.DS.ROOT_MODEL.DS,
			ROOT.DS.ROOT_PATH.DS.ROOT_VIEW.DS,
			ROOT.DS.ROOT_PATH.DS.ROOT_COM.DS,
			ROOT.DS.ROOT_PATH.DS,
			ROOT.DS.ROOT_PATH.DS.CACHE.DS
		);
	}
	set_include_path(get_include_path() . PATH_SEPARATOR .implode( PATH_SEPARATOR , $config['include']));
	include $class_name.'.php';
}


/**
 * 加载静态文件
 * @author wave
 */
function AutoloadsStatic() {
	load('config.php',APP_PATH.DS.DATABASE); //数据库配置文件
	load('Error.php',ROOT_PATH.DS.ROOT_ERROR.DS);					//錯誤
	load('AppModel.php',APP_PATH.DS.ROOT_MODEL.DS);					//模型
	load('AppController.php',APP_PATH.DS.ROOT_CONTROLLER.DS);		//控制器
}
AutoloadsStatic();

