<?php
/**
 * 自動加載核心文件
 * @author wave
 */
class XbphpAutoload {

	public static function init() {
		spl_autoload_register('self::autoload');
		self::AutoloadsStatic();
	}

	/**
	 * 自动加载核心文件(非静态类)
	 * @param string $class_name  文件名
	 * @author wave
	 */
	public static function autoload($class_name) {
		self::strposAutoload($class_name,'Controller',ROOT.DS.ROOT_PATH.DS.ROOT_CONTROLLER.DS);
		self::strposAutoload($class_name,'Model',ROOT.DS.ROOT_PATH.DS.ROOT_MODEL.DS);
		self::strposAutoload($class_name,'view',ROOT.DS.ROOT_PATH.DS.ROOT_VIEW.DS);
		self::strposAutoload($class_name,'Cache',ROOT.DS.ROOT_PATH.DS.CACHE.DS);
	}
	/**
	 * 截取文件名并判断加载文件是否存在
	 * @param string $class_name 文件名
	 * @param string $strpos_class_name 截取的文件名
	 * @param string $path 加载的路径
	 * @param string $extension 扩展名
	 * @author
	 */
	public static function strposAutoload($class_name,$strpos_class_name,$path,$extension = '.php') {
		if(strpos($class_name, $strpos_class_name) !== false) {
			if(file_exists($path.$class_name.'.php')) {
				require_once $path.$class_name.$extension;
			}
			
		}
	}


	/**
	 * 加载静态文件
	 * @author wave
	 */
	public static function AutoloadsStatic() {
		load('config.php',APP_PATH.DS.DATABASE) ; //数据库配置文件
		load('Error.php',ROOT_PATH.DS.ROOT_ERROR.DS);					//錯誤
		load('AppModel.php',APP_PATH.DS.ROOT_MODEL.DS);					//模型
		load('AppController.php',APP_PATH.DS.ROOT_CONTROLLER.DS);		//控制器
		load('Socket.php',ROOT_PATH.DS.VENDOR.DS); //socket加载
	}
}

XbphpAutoload::init();