<?php
/**
 * Cookie类
 * @author wave
 */

class CookieComponent 
{

	/**
	 * 写入Cookie 
	 * @param string $name 名字
	 * @param string $value  值
	 * @param int $expire 生存时间
	 * @param string $path 生存路径
	 * @param string $domain 域名
	 * @author wave
	 */
	public function write($name , $value = null , $expire = null , $path = '/' , $domain = null) 
	{
		if(isset($name))
		{
			setcookie($name,$value,$expire,$path,$domain);
			return true;
		}
		return false;
	}

	/**
	 * 读取COOKIE
	 * @param string $name 名字
	 * @author wave
	 */
	public function read($name) 
	{
		if(isset($name)) 
		{
			return $_COOKIE[$name];
		}
		return false;
	}

	/**
	 * 删除COOKIE
	 * @param string $name 名字
	 * @author wave
	 */
	public function delete($name) 
	{
		if(isset($name)) {
			$this->write($name,'',0);
			return true;
		}
		return false;
	}
}