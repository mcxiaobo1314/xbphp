<?php
/**
 * 公用模型
 * 注意模型里面是不能写__construct自动初始化的机制，否则会报错
 * @author wave
 */
class AppModel extends Model 
{
	public function _initialize()
	{
		//echo 'AppModel自动初始化';
	}

	/**
	 * 求和演示
	 * @param int $a
	 * @param int $b
	 * @return int
	 * @author wave
	 */
	public function sum($a,$b)
	{
		return $a * $b;
	}

}