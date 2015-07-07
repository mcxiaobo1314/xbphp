<?php
 /**
  * phprpc组件
  * @author wave
  */
class RpcComponent {

	/**
	 * 默认加载phprpc组件
	 */
	public function __construct() {
		

	}


	/** 
	 * 创建phprpc服务端
	 * @return object
	 * @author wave
	 */
	public function server() {
		if(!load('phprpc_server.php',ROOT_PATH.DS.ROOT_LIBRARY.DS.'phprpc'.DS)) {
			return load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		}
		return new PHPRPC_Server(); 
	}

	/** 
	 * 创建phprpc客户端
	 * @param string $url 
	 * @return object
	 * @author wave
	 */
	public function client($url = '') {
		if(!load('phprpc_client.php',ROOT_PATH.DS.ROOT_LIBRARY.DS.'phprpc'.DS)) {
			return load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		}
		return new PHPRPC_Client($url);
	}

}