<?php
/**
 * session类
 * @author wave
 */
class SessionComponent 
{
	public function __construct() 
	{
		session_start();
		$session = SESSIONS;
		if(isset($session))
		{
			$this->_set_path();
		}	
	}


	/**
	 * 写入session
	 * @param string $name 名字
	 * @param string $value 值
	 * @author wave
	 */
	public function write($name,$value)
	{
		if(isset($name) && isset($value))
		{
			$_SESSION[$name] = $value;
			return true;
		}
		return false;
	}

	/**
	 * 读取session
	 * @param string $name 名字
	 * @author wave
	 */
	public function read($name) 
	{
		if(isset($name)) 
		{
			return $_SESSION[$name];
		}
		return false;
	}

	/**
	 * 删除session
	 * @param string $name 名字
	 * @author wave
	 */
	public function delete($name)
	{
		if(isset($name))
		{
			unset($name);
			return true;
		}
		return false;
	}

	/**
	 * 毁掉session
	 * @author wave
	 */
	public function destroy() 
	{
		if(isset($_SESSION))
		{
			session_destroy();
			session_unset();
			return true;
		}
		return false;
	}


	/**
	 * 获取当前SESSION的ID
	 * @author wave
	 */
	public function id()
	{
		if(isset($_SESSION))
		{
			return session_id();
		}
		return false;
	}

	/**
	 * 定义SESSION保存的路径
	 * @author wave
	 */
	private function _set_path()
	{
		static $linkId = 0;
		if($linkId == 0)
		{
			if(!file_exists(ROOT.DS.APP_PATH.DS.CACHE.DS.SESSIONS)) 
			{
				mkdirs(CACHE.DS.SESSIONS);
			}
			session_save_path(ROOT.DS.APP_PATH.DS.CACHE.DS.SESSIONS);
			++$linkId;
		}
		
	}
}