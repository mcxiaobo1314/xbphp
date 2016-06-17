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
			$prevLine = 10;
			$code = self::getCode($arr,$prevLine);
			$line = $arr['line'] - $prevLine > 0 ? $arr['line'] - $prevLine+1 : 1;
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
	private static function getCode($arr,$prevLine = 3, $nextLine = 10) {
		$str = '';
		if(is_array($arr) &&  isset($arr['line']) && isset($arr['file'])) {
			$errData = read($arr['file']);
			$errDataArr = explode("\r\n", $errData);
			$count = count($errDataArr);
			if($count == 1) {
				return $errDataArr[0];
			}
			if($count < ($prevLine+$nextLine)) {
				$prevLine = ceil($count / 2);
				$nextLine = ($count - $prevLine - 1);
			}
			$num = $arr['line'] - $prevLine > 0 ? $arr['line'] - $prevLine : 1;
			for($i = $num; $i<=$arr['line']+$nextLine; $i++) {
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
				break;
			case '1':
				error_reporting(E_WARNING | E_NOTICE);
				break;
			case '2':
				error_reporting(E_ALL);
				break; 
		}
		register_shutdown_function('Error::message');
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