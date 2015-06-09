<?php
/**
 * XbPhp控制器实例
 * 有问题或有建议可以加此群:114252528,底下代码只是测试案例。。。可以作为参考，在使用
 * 结合了cakephp与thinkphp的写法
 * 防注入写法请自行过滤
 * @author wave
 */

class IndexController extends AppController{
	/**
	 * 加载模型 示例:BcWz则会自动转换成bc_wz 这个可以一次引入多个模型,是第一次加载全部模型
	 * 加载组件:去AppController.php里面进行加载,如果单独在某个控制器加载组件会覆盖AppController
	 * 里面公用加载组件
	 */
	public $uses = array('BcWz','BcTest','BcNav');


	//要自动初始化函数,必须在构造函数里面写上parent::__construct()先初始化父类
	public function _initialize()
	{
		parent::_initialize();
	}


	public function test($id = null,$uid = null) { 
		//表单数据校验,对应的模型里面写校验规则,详情请看BcWzModel.php
		// $_POST['id'] = 'aaaa';
		// if(!$this->BcWz->validate()){
		// 	var_dump($this->BcWz->error);
		// }
		
		// echo $id."<br>";
		// echo $uid."<br>";
		//这个还是有问题
		// $this->BcWz->test();
		//加载模型也可以自己手动加载
		//这种是以首字母大写方式去加载 BcWz自动转化成bc_wz
		//$this->loadModel('BcWz');
		//这种是以把表前缀和表名进行分开方式进行加载
		//$this->loadModel('wz','bc_');
		//加载模型还有第三个参数，就是选择连接数据库
		//$this->loadModel('BcWz','',config::$default); 或 $this->loadModel('wz','bc_',config::$default);
		
		//执行SQL语句的是 加载模型的第一个参数
		//$this->loadModel('第一个参数');
		//$this->加载模型第一个参数->find();

		//字段默認是查詢全部
		// $fields = array('bc_wz.bc_id','bc_wz.bc_title'); 
		//联表查询
		// $joins = array(
		// 	array(
		// 		'type' => 'left',
		// 		'table' => 'bc_test',
		// 		'alias' => 'BcTest',
		// 		'where' => 'BcTest.id =bc_wz.bc_id'
		// 	)
		// );
		//条件
		// $where['bc_wz.bc_id >='] = 4;
		//分组
		// $group = array('bc_wz.bc_id');
		//执行查询语句
		// $data = $this->BcWz
		// 		->where($where)
		// 		->limit(array(1,2))
		// 		->field($fields)
		// 		->joins($joins)
		// 		->group($group)
		// 		->order('bc_wz.bc_id asc')
		// 		->find();
		// var_dump($data);

		/**
		 * 支持PDO的原生态的预处理命令(必须要修改配置把默认设置2为PDO) 
		 */
		// $bc_name = 'aaaas';
		// $bc_fid = '1';
		// $bind = $this->BcNav->prepares("INSERT INTO `%table%` ( `bc_name`,`bc_fid`) VALUES (:bc_name,:bc_fid)");
		// $bind->bindParam(':bc_name',$bc_name);
		// $bind->bindParam(':bc_fid',$bc_fid);
		// $bind->execute();

		//查詢但行數據
		//$this->BcWz->first();
		
		//更新语句
		//$this->BcWz->where($where)->save($_data);

		/**
		 * 批量更新数据
		 */
		// $data = array(
		// 	array(
		// 		'key' => 'bc_navid',
		// 		'where' => 'bc_id = 3 and bc_title = "usleep"',
		// 		'value' => '2'
		// 	),
		// 	array(
		// 		'key' => 'bc_navid',
		// 		'where' => 'bc_id = 4 and bc_title = "unpack"',
		// 		'value' => '2'
		// 	),
		// 	array(
		// 		'key' => 'bc_jianjie',
		// 		'where' => 'bc_id = 5 and bc_title = "foreach"',
		// 		'value' => '2222'
		// 	)
		// );

		// $this->BcWz->saveAll($data);

		//插入语句
		// $_data['bc_title'] = 1;
		// $_data['bc_connect'] = 1;
		// $_data['bc_jianjie'] = 1;
		// $_data['bc_navid'] = 1;
		// if($this->BcWz->save($_data)) {
		// 	echo '插入成功<br>';
		// }

		//事务提交
		//开启事务
		// $this->BcWz->begin();
		// $status = 1;
		// $_data['bc_title'] = 1;
		// $_data['bc_connect'] = 1;
		// $_data['bc_jianjie'] = 1;
		// $_data['bc_navid'] = 1;
		// if(!$this->BcWz->save($_data)) {
		// 	$status = 0;
		// }
		// if($status) {
		//事务提交
		// 	$this->BcWz->commit();
		// 	echo '成功<br>';
		// }else {
		//事务回滚
		// 	$this->BcWz->rollback();
		// 	echo '失败<br>';
		// }

		//删除语句
		// if($this->BcWz->where('bc_id = 585')->delete()) {
		// 	echo "删除成功";
		// }

		//获取表的主键
		//$this->BcWz->getPk();

		//执行原先SQL语句
		//$this->BcWz->query('select * from bc_wz');


		//必须要在加载Mail的组件,加载组件在AppController.php里面
		//目前经过测试只能用163邮箱，无法使用QQ邮箱
		// $mail_status = $this->Mail->mail_send(
		// 	array(
		// 		'address' => "smtp.163.com",    //邮箱SMTP的地址
		// 		'port' => 25,      //邮箱SMTP的端口号
		// 		'cc' => "****",   //定义的名字
		// 		'form' => "****@163.com",   //邮箱帐号
		// 		'pass' => "*****",   //邮箱密码
		// 		"to" => "*****@qq.com", //要发送给他人的邮箱
		// 		"title" => "测试XbPHP",  // 标题
		// 		"body" => "XbPHP框架,小波交流群:114252528" //内容
		// 	)
		// );
		// var_dump($mail_status);  //邮箱发送成功返回OK 失败返回报错信息

		//smarty模版引擎视图 配置找smarty配置的方式 
		//$this->view->smarty->参数 = '配置的信息';
		// $this->view->smarty->assign('abc',1111);
		// $this->view->smarty->display('test.html');

		//这是系统自带的模版引擎视图 
		// $this->view->assign('a',1);
		// $this->view->assign('b',2);
		// $varr = array('a'=>array('b'=>'ccc','c'=>'ddd'));
		// $this->view->assign('varr',$varr);
		// $earr = array('0'=>array('a'=>'测试1','b'=>'测试二'));
		// $this->view->assign('earr',$earr);
		// $arr = array('av'=>'abc2','bv'=>'edc3');
		// $root = array('root'=>XB_ROOT.'/view');
	
		// $this->view->assign('rootarr',$root);
		// $this->view->assign('arr',$arr);
		/*
		 *防止路径报错
		 * 定义公用常量到defined.php
		 */
		// $this->view->assign('root',XB_ROOT.'/view');

		// $this->view->assign('aroot','');
		//可以写成$this->view->display(加文件名不要加后缀); 
		//如:$this->view->display('index');
		//修改模版的文件的后缀(自带的模版引擎)
		//$this->view->suffix = '.html';
		//echo $this->view->compile_time;

		//无编译进行渲染模版模版里面就写echo $a
		// $this->view->render('',array(
		// 	'a' => 'cccc'
		// ));

		$this->view->display('index');

		//引入文件,以绝对路径的方式引入
		//第一个参数文件名,第二个参数文件的路径
		//load('tet.php','/aaa/bbb');


		//加载只能加载实例模型,必须要在MODEL文件有testModel.php才能加载
		// $wz = loadModel('wz');
		// $wz->test();

		//调用testModel公用方法
		//$this->loadModel('wz');
		//$this->wz->test();

		//写入Cookie
		//$this->Cookie->write('a','b');
		//读取cookie
		//echo $this->Cookie->read('a');
		//删除Cookie
		//$this->Cookie->delete('a');
		//写入
		//$this->Session->write('a','b');
		//读取
		//$this->Session->read('a');
		//删除
		//$this->Session->delete('a');
		//毁掉Session
		//$this->Session->destroy();
		//获取当前Session的ID
		//$this->Session->id();

		//可以接收POST的请求
		//dump($this->request->data);

		//输出
		//dump(array('1','2'));

		//调用AppController公用方法 任何控制器必须继承AppController都能调用
		//dump($this->add(1,5));

		//调用公AppModel方法,只要实例化的Model都能调用
		//$this->wz->sum(1,5);

		//打印当前访问的控制器
		//dump($this->request->controller);

		//打印当前访问的方法
		//dump($this->request->action);

		//判断请求，参数只能写post get ajax
		//$this->is('get'); 

		//设置缓存文件的目录
		//Cache::path('linshi'); 
		/*
		 *第一个参数是缓存文件的名字,第二个参数是回调函数必须有返回值,第三个是傳入参数,第三个是缓存文件的生存时间
		 */
		// Cache::wirte('aa',function($model){
		// 	return $model;
		// },$this,100);
		//读取缓存文件 
		//dump(Cache::read('aa')); 
		//删除缓存文件
		//Cache::del('aa');


		/*Socket使用方式*/
		//要连接的地址
		//Socket::$address = "127.0.0.1";
		//要连接的端口
		//Socket::$port = 80;
		//要发送数据
		//Socket::$data = "hello XbPhp"; 
		//Socket::send();
		//读取返回的数据
		//Socket::read();
		//关闭连接
		//Socket::colse();
	}

	public function about() {
		$this->view->display('about');
	}

	public function lists() {
		$this->view->display('article/list');
	}

	public function article($id = null) {
		if(empty($id)) {
			$id = 1;
		}
		$this->view->display('article/'.$id);
	}
	

}