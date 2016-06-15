<?php
/**
 * 视图
 * @author wave
 */
 
class view 
{
	//左边界符号
	public $left_delimiter = LDELIMITER;

	//右边界符号
	public $right_delimiter = RDELIMITER;

	//定义模版的后缀
	public $suffix  = SUFFIX;

	//用来保存变量
	public $_value = array();

	//获取编译消耗时间
	public $compile_time = null;

	public static $CacheData = array();

	//要替换的符号
	protected $replaced = array(
		'sign'=>array('[',']','.',"'",'"'),
		'foreach'=>array('as','=>')
	);

	public function __construct() {

		//定义根目录
		$this->root = ROOT.DS.APP_PATH.DS;
		//判断是否开启smarty
		if(SMARTY == 1) {
			$this->_get_smarty();
		}
	}
	
	/**
	 * 设置键值
	 * @param string $key  键
	 * @param string $value  值
	 */
	public function __set($key,$value) {

		$this->{$key} = $value;
	}

	/**
	 * 毁消变量
	 * @param string $key 键
	 * @author wave
	 */
	public function __unset($key) {

		unset($this->{$key});
	}

	/**
	 * 赋值变量
	 * @param String $key 定义模版变量
	 * @param String $value 要赋值给模版的变量
	 * @author wave
	 */
	public function assign($key,$val) {

		if(isset($key) && !empty($key) && !empty($val)) {
			$this->_value[$key] = $val;
		}
	}


	/**
	 * 渲染模版
	 * @param Array $array  自定义模版变量
	 * @param string $templateFile 模版路径
	 * @param int $cacheStatus 是否开启缓存 0否,1是
	 * @param time $time 缓存时间,0永久,其他为缓存多少秒
	 * @author wave
	 */
	public function render($array = array(),$templateFile = null,$cacheStatus = 0) {
		$tmpfile = !empty($templateFile) ? $templateFile : $this->_get_action();
		//模版文件路径
		$file_path = $this->root.ROOT_VIEW.DS.$tmpfile.$this->suffix;
		
		if(strpos($tmpfile, '.') !== false) {
			$file_path = $this->root.ROOT_VIEW.DS.$tmpfile;
		}

		if(!file_exists($file_path)) {
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'); 
			exit;
		}
		if(empty($cacheStatus)) {
			extract($array);
			require $file_path;	
		}else {
			echo $this->cacheHtml($tmpfile,$array,$file_path);
		}
		
	}



	/**
	 * layout渲染模版
	 * @param Array $array  自定义模版变量
	 * @param string $templateFile 模版路径
	 * @author wave
	 */
	public function renderLayout($array = array(),$templateFile = null) {
		extract($array);
		$file_path = $this->root.DS.'layout'.DS."index.php";
		require $file_path;
	}


	/**
	 * 引入模版返回代碼
	 * @param Array $array  自定义模版变量
	 * @param string $templateFile 模版路径
	 * @return HTML
	 * @author wave
	 */
	public function renderHtml($array = array(),$templateFile = null) {
		ob_start();
		$this->render($array,$templateFile);
		$content = ob_get_contents();
		ob_clean();
		return $content;

	}

	/**
	 * 引入模版
	 * @param String $templateFile 定义模版路径
	 * @author wave
	 */
	public function display($templateFile = null)  {
		$start_time = microtime(true); //获取开始时间
		$path = $this->_compresFile($templateFile);
		require $path;
		$end_time = microtime(true); //获取结束时间
		$this->compile_time = ' (display方法消耗了'.sprintf('%0.4f',($end_time - $start_time)).'秒)';
	}

	/**
	 * 缓存HTML
	 * @param String $file 缓存文件名
	 * @param String $array 缓存定义的变量
	 * @param String $include_path 缓存文件路径
	 * @author wave
	 */
	private function cacheHtml($file,$array = array(),$include_path) {
		$file = str_replace(array('/','\\','.'), '_', $file);
		if(!isset(self::$CacheData[$file])) {
			ob_start();
			if(!empty($array)) {
				extract($array);
			}

			require $include_path;	
			self::$CacheData[$file] = ob_get_contents();
			ob_clean();
		}
		return self::$CacheData[$file];
	}

	/**
     * 编译文件
     * @param string $templateFile 文件名
     * @return string
     * @author wave
	 */
	private function _compresFile($templateFile = null) {
		$tmpfile = !empty($templateFile) ? $templateFile : $this->_get_action();
		//编译文件路径
		$tmp_path  = $this->root.CACHE.DS.TEMPLATES;
		$file_path = $this->root.ROOT_VIEW.DS.$tmpfile.$this->suffix;
		//定义了文件路径
		if(strpos($templateFile, '.') !== false) {
			//模版文件路径
			$file_path = $this->root.ROOT_VIEW.DS.$tmpfile;
		}
		if(file_exists($templateFile)) {
			$file_path = $templateFile;
		}

		$tmp_name  = 'xb_'.md5($tmpfile).'.php';
		if(!file_exists($file_path)) {
			load('404.tpl',ROOT_PATH.DS.ROOT_ERROR.DS.'tpl'); 
			exit;
		}
		if(!file_exists($tmp_path)) {
			mkdirs(DS.CACHE.DS.TEMPLATES);
		}
		$file_path_time = filemtime($file_path);
		$tmp_path_time = 0;
		//判断缓存文件是否存在
		if(file_exists($tmp_path.DS.$tmp_name)) {
			$tmp_path_time = filemtime($tmp_path.DS.$tmp_name);
		}
		
		//判断编译文件是否存在，或者模版文件修改时间小于编译文件修改的时间
		if(!is_file($tmp_path.DS.$tmp_name) || ($file_path_time > $tmp_path_time)) 
		 {
			$html = $this->cacheHtml($tmp_name,array(),$file_path);
			//$html = file_get_contents($file_path);
			$html = $this->_include($html);
			$html = $this->_if($html);
			$html = $this->_foreach($html);
			$html = $this->_echo($html);
			$html = (COMPRESS == 1) ? $this->compress_html($html) : $html;
			file_put_contents($tmp_path.DS.$tmp_name,$html);
		}
		return $tmp_path.DS.$tmp_name;
	}

	/**
	 * 替换输出
	 * 例子<{$a}>
	 * @param string $html 要替换的HTML
	 * @return resource
	 * @author wave
	 */
	private function _echo($html,$flag = false) {
		$preg = $flag === false ? '(\$[a-zA-Z_][a-zA-Z0-9_]*)' : '(\$[a-zA-Z_][a-zA-Z0-9_]*\.[a-zA-Z_][a-zA-Z0-9_]*)';
		$replaced = '/'.$this->left_delimiter.$preg.$this->right_delimiter.'/is';
		if(preg_match_all($replaced,$html,$arr)) {
			if(!isset($arr[1]) && count($arr[1]) === 0) {
				return $html;
			}
			$arr[1] = str_replace(array('$'), '', $arr[1]);
			$replaceArr = array();
			$strArr = array();

			foreach($arr[1] as $k => $v) {
				if(strpos($v, '.') === false) {
					$replaceArr[] = '<?php echo $this->_value["'.$v.'"]; ?>';
				}else {
					$strArr[] = $this->left_delimiter .'$' . $v . $this->right_delimiter;
					$v = explode('.', $v);
					$replaceArr[] = '<?php echo $this->_value["'.$v[1].'"]; ?>';
					
				}
			}
			if(!empty($strArr)) {
				return array('strArr'=>$strArr , 'replaceArr'=>$replaceArr);
			}else {
				$html = str_replace($arr[0],$replaceArr,$html);
			}
		}
		return $html;
	}

	/**
	 * 替换引入函数
	 * 例子<{include file="路径加文件"}> 注意：路径带有模版变量则不编译该文件
	 * @param String $html 要替换的HTML
	 * @return HTML
	 * @author wave
	 */
	private function _include($html) {
		//正则替换include函数
		$replaced = '/'.$this->left_delimiter.'include\s+file=\"(([a-zA-Z0-9_\.\/]*)|(\$[a-zA-Z_][a-zA-Z0-9_\.\/]*))\"'.$this->right_delimiter.'/is';
		if(preg_match_all($replaced,$html,$arr)){
		    if(!isset($arr[1]) && count($arr[1]) === 0) {
		    	return $html;
		    }
	        $replaceArr = array();
	        foreach($arr[1] as $val) {
	            if(strpos($val, '$') !== false) {
	                $val = str_replace(array('$'), '', $val);
	                $valArr = explode('/', $val);
	                $val = !empty($valArr[0])  ? $this->_value["$valArr[0]"] : $this->_value["$val"]; 
	                $val .= !empty($valArr[1]) ? '/'.$valArr[1] : '';
	            }
            	$path_str = str_replace('\\','/',$this->_compresFile($val)); 
				$replaceArr[] = '<?php include "'.$path_str.'"; ?>';
 
	        } 
	        $html = str_replace($arr[0],$replaceArr,$html);
		}
		return $html;
	}

	/**
	 * 模版标签forach[支持嵌套,与smarty写法差多]
	 * <{foreach item=$arr key=$k val=$v}><{$arr.k}>---<{$arr.v}><{/foreach}>
	 * @param string $html 要替换的HTML的代码
	 * @return HTML
	 * @author wave
	 */
	private function _foreach($html) {
		$replaced = '/'.$this->left_delimiter.'foreach\s*item\=(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*key\=(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*val=(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*'.$this->right_delimiter.'\s*(.*?)\s*'.$this->left_delimiter.'\/foreach'.$this->right_delimiter.'/is';
		if(preg_match_all($replaced,$html,$arr,PREG_SET_ORDER)){
		    if(empty($arr)) {
		      return $html;
		    }
		    $strArr = array();
		    $replaceArr = array();
		    foreach($arr as $k => $v) {
		        $str = '<?php foreach(';
		        $firstStr = '';
		        foreach ($v as $key => $value) {
		              switch ($key) {
		                case 0:
		                  if(preg_match('/'.$this->left_delimiter.'foreach\s*item\=(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*key\=(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*val=(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*'.$this->right_delimiter.'/is', $value,$harr)){
		                  	 $strArr[] = !empty($harr[0]) ? $harr[0] : '';
		                  }
		                  break;
		                case 1:
		                  $value = str_replace(array('$'), '', $value);
		                  $firstStr = ' $this->_value["'.$value.'"]';
		                  $str .= $firstStr.' as ';
		                  break;
		                case 2:
		                  $value = str_replace(array('$'), '', $value);
		                  $str .= ' $this->_value["'.$value.'"] => ';
		                  break;
		                case 3:
		                  $value = str_replace(array('$'), '', $value);
		                  $str .= ' $this->_value["'.$value.'"] ){  ?>'; 
						  $replaceArr[] = $str;
						  break;
		                default:
		                  $listArr = $this->_echo($value,true);
		                  break;
		              }
		        }
		        
		    }
		    if(!empty($listArr)) {
		    	$html = str_replace($listArr['strArr'], $listArr['replaceArr'], $html);
		    	$html = str_replace($this->left_delimiter.'/foreach'.$this->right_delimiter, '<?php } ?>', $html);
		    }
		    $html = str_replace($strArr, $replaceArr, $html);
		  
		}
		return $html;
	}

	/**
	 * 替换IF语句[支持嵌套]
	 * 例子<{if $a == $b }>123<{else}>2323<{/if}> 
	 * @param string $html 值
	 * @return HTML
	 * @return wave
	 */

	private function _if($html) {

		$preg = '/'.$this->left_delimiter.'if\s+(.*?)\s*'.$this->right_delimiter.'(.*?)'.$this->left_delimiter.'\/if'.$this->right_delimiter.'/s';

		if(preg_match_all($preg,$html,$garr,PREG_SET_ORDER)) {
			$replace = array('=','>','<','!','|','&');
			foreach($garr as $value) {
				$rarr = $value['1'];
				$value['1'] =  $this->_replace_arr($replace,' ',$value['1'],' ');
				foreach($value['1'] as $v) {
					if(strpos($rarr,$v) !== false) {
						$rarr = substr_replace($rarr,'',strpos($rarr,$v),strlen($v));
					}
				}

				$rarr = $this->_replace_arr('','',$rarr,' ');
				$conditions = null;

				foreach($value['1'] as $key => $val)  { //进行遍历拼接

					$left_bracket = null;   //左边括号
					$right_bracket = null;  //右边括号

					if(strpos($val,'(') !== false) { //判断是否写了左边括号
						$left_bracket = '(';
					}

					if(strpos($val,')') !== false) { //判断是否写了右边括号 
						$right_bracket = ')';
					}

					if(strpos($val,'$') !== false) {

						$str = substr($val,1);
						$v_arr = $this->_replace_arr($this->replaced['sign'],' ',$str,' ');
						$_str = null;

						foreach($v_arr as $k => $v) {
							if($k == 0) {
								$_str .= '$this->_value["'.$v.'"]';
							}else{
								$_str .= '["'.$v.'"]';
							}
						}
						$val = str_replace($val,$left_bracket.$_str.$right_bracket,$val);
					}

					$conditions .= $val.' ';
					if(isset($rarr[$key])) {
						$conditions.= $rarr[$key].' ';
					}
				}

				$value['2'] = str_replace($this->left_delimiter.'else'.$this->right_delimiter,'<?php }else { ?>',$value['2']);
				$if = '<?php if('.$conditions.'){?>'.$value['2'].'<?php } ?>';
				$html = str_replace($value['0'],$if,$html);
			}

			$html = $this->_if($html);
		}

		return $html;
	}

   /** 
	* 压缩html : 清除换行符,清除制表符,去掉注释标记 
	* @param $string 
	* @return 压缩后的$string 
	* */ 
	protected function compress_html($string) {

		$string = str_replace("\r\n", '', $string); //清除换行符
		
		$string = str_replace("\n", '', $string); //清除换行符
		
		$string = str_replace("\t", '', $string); //清除制表符
		
		$pattern = array (
			"/> *([^ ]*) *</", //去掉注释标记
			"/[\s]+/",
			"/<!--[^!]*-->/",
			"/\" /",
			"/ \"/",
			"'/\*[^*]*\*/'"
		);

		$replace = array (
			">\\1<",
			" ",
			"",
			"\"",
			"\"",
			""
		);
		
		return preg_replace($pattern, $replace, $string);
	}

	/**
	 * 获取smarty模版引擎
	 * @author wave
	 */
	private function _get_smarty() {
		load('Smarty.class.php','vendors/Smarty/libs');
		$this->smarty = Xbphp::run_cache('Smarty');
		$this->smarty->cache_dir = $this->root.CACHE.DS.TEMPLATES;
		$this->smarty->template_dir = $this->root.ROOT_VIEW.DS;
		$this->smarty->compile_dir = $this->root.CACHE.DS.TMP;
		$this->smarty->left_delimiter = $this->left_delimiter;
		$this->smarty->right_delimiter = $this->right_delimiter;
	}

	/**
	 * 替换截取字符
	 * @param Array or String $replaced 要替换的
	 * @param Array or String $replace 被替换的
	 * @param String $str 查找字符串
	 * @param String $explode 截取的字符串
	 * @return Array
	 * @author wave
	 */
	private function _replace_arr($replaced,$replace,$str,$explode =' ') {
		
		if(!empty($replaced) && !empty($replace)) {
			$str = str_replace($replaced, $replace, $str);
		}

		$arr = array_filter(explode($explode, $str));
		$arr = array_values($arr);
		return $arr;
	}

	/**
	 * 获取当前访问的方法
	 * @return String
	 * @author wave
	 */
	private function _get_action() {

		$arr = Xbphp::getServerUrl();
		if(!isset($arr['0'])) {
			$arr['0'] = M_INDEX;
		}
		if(!isset($arr['1'])) {
			$arr['1'] = A_INDEX;
		}
		if(isset($_GET[M]) && !empty($_GET[M])) {
			$arr['0'] = $_GET[M];
		}
		if(isset($_GET[A]) && !empty($_GET[A])) {
			$arr['1'] = $_GET[A];
		} 
		return $arr['0'].DS.$arr['1'];
	}


}