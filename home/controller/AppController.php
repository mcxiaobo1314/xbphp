<?php
/**
 * 公用控制器
 * @author wave
 */

class AppController extends Controller 
{
	//要加载的组件
	//session组件默认进行分开存储,请到index.php里面设置SESSIONS把值设置为空,就统一放到TMP文件下
	public $component = array(
			'Cookie',
			'Session',
			'Mail',
			'Rpc' //加载PHPRPC组件
	);


	public function _initialize()
	{
		header('Content-Type:text/html;charset=utf-8');
	}


	/**
	 * 演示加法方法
	 * @param int $a 整数
	 * @param int $b 整数
	 * @return int
	 * @author wave
	 */
	public function add($a,$b)
	{
		$a= intval($a);
		$b = intval($b);
		return $a+$b;
	}

}