<?php
	/**
	 * 设置路由规则
	 * 主要限制动态URL的方法参数的键名与伪静态是否要加键名
	 * @author wave
	 */

//伪静态路由规则演示,不写则用默认规则
$route = array(
	//伪静态路由规则，如:/Index/index/id/1/cid/2,则访问必须要加id/1/cid/2才能进行访问,不然会404
	// 'rewirte' => array(
		//Index是控制器名,test是方法名
		// 'Index'=> array(
			//	'test' => "/^id\/([a-zA-Z_0-9]+)\/cid\/([a-zA-Z_0-9]+)$/i"
		//)
	// ),
	//动态url规则,如:?m=Index&a=index&id=1&cid=2,则参数id和cid键名必须是这俩个改成其他就会404
	 // 'trends' => array(
	 // 	 'Index'=> array(
	 // 			'test' => "/^id\=([a-zA-Z_0-9]+)\&cid\=([a-zA-Z_0-9]+)$/i"
	 // 	)
	 // )
);

return isset($route) ? $route : '';