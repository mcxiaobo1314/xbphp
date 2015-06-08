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
	 * 404错误页面
	 * @author wave
	 */
	public static function show() {
		load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl');
		exit;
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


/**
 * 错误信息
 * @author wave
 */
function error() {
	Error::message();
	exit;
}


/**
 * 选择错误信息
 * @author wave
 */
function spl_error() {
	switch (ERROR) {
		case '0':
			error_reporting(0);
			register_shutdown_function('error');
			break;
		case '1':
			error_reporting(E_WARNING | E_NOTICE);
			register_shutdown_function('error');
			break;
		case '2':
			error_reporting(E_ALL);
			register_shutdown_function('error');
			break; 
	}
}
spl_error();