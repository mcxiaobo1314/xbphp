<?php
/**
 * XbPhp控制器实例
 * @author wave
 */
class IndexController extends AppController{

	/**
	 * XbPhp幫助文件
	 * @author wave
	 */
	public function help() {
		$this->view->display('help');
	}

	/**
	 * XbPhp语法说名
	 * @author wave
	 */
	public function grammar() {
		$this->view->assign('herf','http://php.nmfox.com/home/webroot/images/logo.jpg');
		$this->view->display('grammar');
	}
}