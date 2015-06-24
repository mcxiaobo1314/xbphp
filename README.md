# xbphp框架
xbphp框架
特色是：
  1.强大的定时文件缓存机制
  2.结合每个框架的优点进行组装
  3.跨平台与跨环境无需修改配置
  4.简单易上手
2015-06-09:新增表单校验规则,与YII2的写法差不多
2015-06-24:新增自定义重写URL参数校验
<h1>与其他框架不一样的地方就是定时文件缓存,特点:有效的帮你减少查询次数,静态化数据,提高效率,还实现延迟查询数据</h1><br>
xbphp框架说明:
  提取YII2的MODEL校验规则写法,进行表单校验,使用cakephp目录结构思想,在xbphp框架伪静态时候,不管在空间机使用还是服务器更安全,可以有效的防止访问的游客进行访问PHP文件,新增加了动态写法,使用thinkphp的动态路由写法,默认参数M是加载控制器名,a是控制器的方法名,后面还会更新,让xbphp框架更强大,核心文件小.<br>
xbphp框架使用教程地址:
  http://xbphp.nmfox.com/Index/lists 
  http://php.nmfox.com/Index/lists 
演示:
linux nginx演示网址:http://xbphp.nmfox.com/
linux apache演示网址:http://php.nmfox.com/
语法简单说明:
表单数据校验,对应的模型里面写校验规则,详情请看BcWzModel.php
$_POST['id'] = 'aaaa';
if(!$this->BcWz->validate()){
	var_dump($this->BcWz->error);
}

这个还是有问题
$this->BcWz->test();
加载模型也可以自己手动加载
这种是以首字母大写方式去加载 BcWz自动转化成bc_wz
$this->loadModel('BcWz');
这种是以把表前缀和表名进行分开方式进行加载
$this->loadModel('wz','bc_');
加载模型还有第三个参数，就是选择连接数据库
$this->loadModel('BcWz','',config::$default); 或 $this->loadModel('wz','bc_',config::$default);

执行SQL语句的是 加载模型的第一个参数
$this->loadModel('第一个参数');
$this->加载模型第一个参数->find();

字段默認是查詢全部
$fields = array('bc_wz.bc_id','bc_wz.bc_title'); 
联表查询
$joins = array(
	array(
		'type' => 'left',
		'table' => 'bc_test',
		'alias' => 'BcTest',
		'where' => 'BcTest.id =bc_wz.bc_id'
	)
);
条件
$where['bc_wz.bc_id >='] = 4;
分组
$group = array('bc_wz.bc_id');
执行查询语句
$data = $this->BcWz
		->where($where)
		->limit(array(1,2))
		->field($fields)
		->joins($joins)
		->group($group)
		->order('bc_wz.bc_id asc')
		->find();
var_dump($data);

/**
 * 支持PDO的原生态的预处理命令(必须要修改配置把默认设置2为PDO) 
 */
$bc_name = 'aaaas';
$bc_fid = '1';
$bind = $this->BcNav->prepares("INSERT INTO `%table%` ( `bc_name`,`bc_fid`) VALUES (:bc_name,:bc_fid)");
$bind->bindParam(':bc_name',$bc_name);
$bind->bindParam(':bc_fid',$bc_fid);
$bind->execute();

查詢但行數據
$this->BcWz->first();

更新语句
$this->BcWz->where($where)->save($_data);

/**
 * 批量更新数据
 */
$data = array(
	array(
		'key' => 'bc_navid',
		'where' => 'bc_id = 3 and bc_title = "usleep"',
		'value' => '2'
	),
	array(
		'key' => 'bc_navid',
		'where' => 'bc_id = 4 and bc_title = "unpack"',
		'value' => '2'
	),
	array(
		'key' => 'bc_jianjie',
		'where' => 'bc_id = 5 and bc_title = "foreach"',
		'value' => '2222'
	)
);

$this->BcWz->saveAll($data);

插入语句
$_data['bc_title'] = 1;
$_data['bc_connect'] = 1;
$_data['bc_jianjie'] = 1;
$_data['bc_navid'] = 1;
if($this->BcWz->save($_data)) {
	echo '插入成功<br>';
}

事务提交
开启事务
$this->BcWz->begin();
$status = 1;
$_data['bc_title'] = 1;
$_data['bc_connect'] = 1;
$_data['bc_jianjie'] = 1;
$_data['bc_navid'] = 1;
if(!$this->BcWz->save($_data)) {
	$status = 0;
}
if($status) {
事务提交
	$this->BcWz->commit();
	echo '成功<br>';
}else {
事务回滚
	$this->BcWz->rollback();
	echo '失败<br>';
}

删除语句
if($this->BcWz->where('bc_id = 585')->delete()) {
	echo "删除成功";
}

获取表的主键
$this->BcWz->getPk();

执行原先SQL语句
$this->BcWz->query('select * from bc_wz');

