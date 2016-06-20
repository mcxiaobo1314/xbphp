<?php
/**
 * 错误类
 * @author wave
 */


class Error {

	/**
	 * 输出错误信息
	 * @author wave
	 */
	public static function message() {
		$arr = error_get_last();
		if($arr != null) {
			self::logs($arr);
			$code = self::getCode($arr);
			$line = $arr['line'] - 10 >= 0 ?  $arr['line'] - 10 : 0;
			$line = $line == 0 ? $line+1 : $line;
			$jsData = read(ROOT.DS.ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'.DS.'js'.DS.'shCore.js');
			$jsData .= read(ROOT.DS.ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'.DS.'js'.DS.'shBrushPhp.js');
			$cssData = read(ROOT.DS.ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'.DS.'css'.DS.'shCore.css');
			$cssData .= read(ROOT.DS.ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'.DS.'css'.DS.'shThemeDefault.css');
			switch (ERROR) {
				case '1':
					require ROOT.DS.ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'.DS.'error.tpl';
					break;
				case '0':
					self::show();
					break;
			}
		}
	}

 	/**
 	 * 获取错误代码
 	 * @param Array $arr 错误信息
 	 * @param int $prevLine 获取前几行
 	 * @param int $nextLine 获取后几行
 	 * @author wave
 	 */
	private static function getCode($arr) {
		$str = '';
		if(is_array($arr) &&  isset($arr['line']) && isset($arr['file'])) {
			$errData = read($arr['file']);
			$errDataArr = explode("\r\n", $errData);
			$num = $arr['line'] - 10 >= 0 ?  $arr['line'] - 10 : 0;
			for($i =$num; $i<=$arr['line']+10; $i++) {
				if($arr['line'] - $i == 1) {
					$str .= trim($errDataArr[$i])."\r";
				}else {
					$str .= trim($errDataArr[$i])."\r";
				}
			}
		}
		return $str;
	}


	/**
	 * 404错误页面
	 * @author wave
	 */
	public static function show() {
		load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		exit;
	}

	/**
	 * 选择错误信息
	 * @author wave
	 */
	public  static function spl_error() {
		ini_set('display_errors','Off');
		switch (ERROR) {
			case '0':
				error_reporting(0);
				register_shutdown_function('Error::message');
				break;
			case '1':
				error_reporting(E_WARNING | E_NOTICE);
				register_shutdown_function('Error::message');
				break;
			case '2':
				error_reporting(E_ALL);
				register_shutdown_function('Error::message');
				break; 
		}
	}


	/**
	 * 記錄錯誤日記
	 * @param Array $logs 錯誤數組
	 * @author wave
	 */
	protected function logs($logs) {
		if(is_array($logs) && !empty($logs)) {
			$errinfo = sprintf('error_type:%d message:%s errfile:%s errline:%d',$logs['type'],$logs['message'],$logs['file'],$logs['line']);
			$date = date('Y-m-d H:i:s');
			
			if(!file_exists(ROOT.DS.APP_PATH.DS.LOGS.DS)) {
				mkdirs(LOGS);
			}

			file_put_contents(ROOT.DS.APP_PATH.DS.LOGS.DS.'logs.log', 'date:'.$date.' '.$errinfo."\r\n",FILE_APPEND);
		}
	}

}
Error::spl_error();