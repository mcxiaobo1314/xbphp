<?php
/**
 * XbPhp模型示例
 * @author wave
 */
class BcWzModel  extends AppModel{

	/**
	 * 关联表，也就是绑定函数，可以实现每次绑定多个表
	 * @author wave
	 */
	public $bind = array(
		array(
			'type'  => 'left', //是联表查询的用类型 默认是left， 可以写left,right,inner
			'alias' => 'A',        //要绑定表的别名
			'table' => 'a',        //要绑定表名
			'foreignKey' => 'id',  //被绑定表的外键字段
			'beRelated' => 'bc_id'  //定义绑定表的关联字段
		)
		
	);


	public function test() {
		//echo '演示wzModel';
		//Model执行sql方法,不需要写MODEL的名称，会自动加载文件模型名字,
		 // $u = $this->first();
		//dump($this);
		// dump($u);
		echo "↑↑↑↑↑是ModelSQL执行的示例<br>---------------------";
	}



}