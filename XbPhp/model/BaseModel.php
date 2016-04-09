<?php
/**
 * 基类模型
 * @author wave
 */

class BaseModel {

	//错误信息
	public $error;

	//校验的规则
	protected $rules = array();

	//标识判断是否校验成功
	protected $flag = true;

	//获取数据
	public $data;

	protected function getData() {
		if(!empty($_POST)) {
			$this->data =& $_POST;
		}
		if(!empty($_GET)) {
			$this->data =& $_GET;
		}
	}

	/**
	 * 校验规则
	 * @return Array
	 * @author wave
	 */
	public function rules() {
		return array();
	}


	/**
	 * 验证数据
	 * @return boolean
	 * @author wave
	 */
	public function validate() {
		$this->getData();
		$this->rules = $this->rules();
		if(!empty($this->rules)) {
			$this->erg();
		}
		return empty($this->error) ? true : false;
	} 

	/**
	 * 进行遍历校验
	 * @author wave
	 */
	protected function erg() {
		foreach($this->rules as $val) {
			if(isset($val['pattern']) && !empty($val['pattern'])) {
				if(!empty($this->data)) {
					if(!preg_match($val['pattern'], $this->data[$val['0']])) {
						$this->flag = false;
						$this->error = $val['msg'];
						break;
					}
				}
			}else {
				$this->flag = $this->isType($val);
				if(!$this->flag) {
					$this->error = $val['msg'];
					break;
				}
			}
		}
	}

	/**
	 * 校验数据类型
	 * @param Array $val 传入的校验数组
	 * @return boolean
	 * @author wave
	 */
	protected function isType($val) {
		switch ($val['type']) {
			case 'int':
				$flag = is_int($val['0']) ? true : false;
				break;
			case 'float':
				$flag = is_float($val['0']) ? true : false;
				break;
			case 'string':
				$flag = is_string($val['0']) ? true : false;
				break;
			case 'num':
				$flag = is_numeric($val['0']) ? true : false;
				break;
		}
		return $flag;
	}



}