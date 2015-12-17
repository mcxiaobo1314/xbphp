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
		//控制器里面的公有方法可以互相访问;访问other控制器里面的aaa方法
		// $a = $this->requestAction('/other/aaa');
		// var_dump($a);
		
		//获取框架占用的内存
		//echo Xbphp::memory(Xbphp::endMemory());
		
		//需要统一控制,可以自己定义一个全局变量来控制
		//Xbphp::toUrl('写要访问的URL','1是动态,2是伪静态','要定义的键名') 
		//该例子演示动态URL转换伪静态
		//Xbphp::toUrl('?m=Index&a=test&a=aaa&c=dddd',2,array('2'=>'cid','3'=>'did'));
		//该例子演示了伪静态转换成动态URL
		//Xbphp::toUrl('/Index/test/aaa/dddd/',1,array('2'=>'cid','3'=>'did'));

		//调用phprpc,需要先加载组件,使用的是phprpc3.0.1 版本
		//服务端调用底下示例: 
		// $server = $this->Rpc->server();
		// var_dump($server);
		//客户端调用底下示例: 
		// $client = $this->Rpc->client('http://127.0.0.1/xbphp/Index/test');
		// var_dump($client);

		//表单数据校验,对应的模型里面写校验规则,详情请看BcWzModel.php
		// $_POST['id'] = 'aaaa';
		// if(!$this->BcWz->validate()){
		// 	var_dump($this->BcWz->error);
		// }

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
		// $fields = array('BcWz.bc_id','BcWz.bc_title'); 
		//联表查询
		// $joins = array(
		// 	array(
		// 		'type' => 'left',
		// 		'table' => 'bc_test',
		//		'fileds' => '`BcTest`.`bc_id`',
		// 		'alias' => 'BcTest',
		// 		'where' => 'BcTest.id =BcWz.bc_id'
		// 	)
		// );
		//条件
		// $where['BcWz.bc_id >='] = 4;
		//分组
		// $group = array('BcWz.bc_id');
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

		//這個是layout引入模版,可以让头部和底部公用
		//$this->view->renderLayout(array('a'=>"aaa"),'1.php');

		echo "欢迎使用xbphp";

		//引入文件,以绝对路径的方式引入
		//第一个参数文件名,第二个参数文件的路径
		//load('tet.php','/aaa/bbb');


		//加载只能加载实例模型
		//第一個參數表名,第二參數前綴,第三的連接數據
		// $wz = loadModel('BcWz');
		// $wz->find();

		//调用testModel公用方法
		// $this->loadModel('wz');
		// $this->wz->test();

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


	public function weidunjiemi() {
		$this->view->display('jm');
		//判断临时文件存放路径是否包含用户上传的文件
		if(!empty($_FILES["uploadfile"]["tmp_name"]) && is_uploaded_file(@$_FILES["uploadfile"]["tmp_name"])){
			//为了更高效，将信息存放在变量中
			$upfile=$_FILES["uploadfile"];//用一个数组类型的字符串存放上传文件的信息
			//print_r($upfile);//如果打印则输出类似这样的信息Array ( [name] => m.jpg [type] => image/jpeg [tmp_name] => C:\WINDOWS\Temp\php1A.tmp [error] => 0 [size] => 44905 )
			$name=$upfile["name"];//便于以后转移文件时命名
			$dArr = explode('.', $name);
			if($dArr[count($dArr) - 1] !== 'php') {
				echo '请上传PHP文件';exit;
			}
			$type=$upfile["type"];//上传文件的类型
			$size=$upfile["size"];//上传文件的大小
			$size = $upfile["size"] / 1024;
			if($size > 300) {
				echo '上传的文件不能大于300KB';exit;
			}
			$tmp_name=$upfile["tmp_name"];//用户上传文件的临时名称
			$error=$upfile["error"];//上传过程中的错误信息
			//echo $name;

			//如果文件符合要求并且上传过程中没有错误
			if($error=='0'){
				$path = ROOT.DS.APP_PATH.DS.'update'.DS.date('Y').DS.date('m').DS.date('d').DS;
				if(!file_exists($path)) {
					mkdirs('update'.DS.date('Y').DS.date('m').DS.date('d').DS);
				}
				//调用move_uploaded_file（）函数，进行文件转移
				$filename=$path.md5($name.rand(1000,999999).date('YmdHis')).'.txt';
				move_uploaded_file($tmp_name,$filename);
				//操作成功后，提示成功
				//echo "<script language=\"javascript\">alert('succeed')</script>";

				$lines = file($filename);//0,1,2行


				//第一次base64解密 
				$content=""; 
				if(!empty($lines) && isset($lines[1]) && preg_match("/O0O0000O0\('.*'\)/",$lines[1],$y)) 
				{
				 $content=str_replace("O0O0000O0('","",$y[0]);     
				 $content=str_replace("')","",$content);     
				 $content=base64_decode($content); 
				}else {
					echo '请上传威盾加密文件';exit;
				}
				//第一次base64解密后的内容中查找密钥 
				$decode_key=""; 
				if(!empty($content)  && preg_match("/\),'.*',/",$content,$k)) 
				{  
					$decode_key=str_replace("),'","",$k[0]);     
					$decode_key=str_replace("',","",$decode_key); 
				}
				//截取文件加密后的密文 
				$Secret=substr($lines[2],380); 
				//echo $Secret; 

				//直接还原密文输出 
				echo "解密后的代碼:<textarea cols=\"150\" rows=\"1000000\" style=\"overflow:auto\"><?php\n".htmlspecialchars(base64_decode(strtr($Secret, $decode_key, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/")))."?></textarea>";
			}
		}
	}
	

}