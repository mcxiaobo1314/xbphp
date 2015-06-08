<?php
/**
 * 配置文件
 * @author wave
 */
define('TMP','tmp');                                        //临时文件路径
define('M','m'); 											//模型
define('A','a'); 											//方法
define('TIME',300); 										//设置缓存文件生成时间
define('CACHE_DIR','tmp');                               	//设置文件缓存的目录
define('M_INDEX','Index'); 									//设置默认访问控制器
define('A_INDEX','test'); 									//设置默认访问方法
define('COMPRESS',1);                         				//压缩编译文件,1为压缩，0为不压缩
define('SMARTY',1);                             			//smarty模版引擎,1为开启，0为关闭
define('EXTENSION',2);                                		//可以填写1为MYSQL,2为PDO
define('SUFFIX','.html');                                	//定义模版的后缀名字 
define('LDELIMITER','<{');     		                      	//定义左边界符号   
define('RDELIMITER','}>');                        		    //定义右边界符号       
define('DEBUG',true);										//是否打开调试,默认为true为打开,false关闭
//伪静态规则截取URL的分隔符 如:url/XbPhp/xxx_xxx_xxx
define('SIGN','/');											
 /**
  * 0屏蔽错误(建议上线使用0)
  * 1是开启内置的错误信息(调试)
  */
define('ERROR',1);     