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
			'Mail'
	);

	/**
	 * 文章列表
	 */
	public $listArr = array(
		'7' => 'XbPhp定时文件缓存写法',
		'8' => 'XbPhp教新手如何快速搭建php环境',
		'10' => '如何在模型绑定其他模型?',
		'9' => '如何在控制器批量更新数据(支持多字段)?',
		'6' => '如何在控制器开启事务处理?',
		'5' => '如何在控制器执行刪除语句?',
		'4' => '如何在控制器执行插入或更新语句?',
		'3' => '如何在控制器执行查询语句?',
		'2' => '如何创建模型?',
		'1' => '如何创建控制器?',
		
		
		
		
		
		
	);


	public function _initialize()
	{
		header('Content-Type:text/html;charset=utf-8');
		$this->view->assign('header',XB_ROOT.'/view/');
		if(file_exists(ROOT.DS.'xbphp')) {
			$path = '/xbphp';
		}else {
			$path = '';
		}
		$this->view->assign('herf','http://'.$_SERVER['HTTP_HOST'].$path.'/home/webroot/');
		$this->view->assign('herfs','http://'.$_SERVER['HTTP_HOST'].$path.'/');
		$this->view->assign('listarr',$this->listArr);
		$num = rand(1,count($this->listArr)-1);
		$this->view->assign('num',$num);
		$this->view->assign('randtitle',$this->listArr[$num]);
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