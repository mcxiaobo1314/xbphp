<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<title>欢迎使用XbPhp框架</title>
<style>
	html,body,div,span,ul,li{
		padding: 0;
		margin: 0;
		background: #fff;
	}
	body{
		background-image:url(text.txt);
 		background-attachment:fixed; 
	}
	#hide {
		height: 103px;
		/*针对IE6写法*/
		_height:0px;
		_position:static;
	}
	#overlay {
		font-size: 14px;
		position:fixed;
		z-index:9999;
		bottom:0;
		width:100%;
		height:100px;
		border-top:3px solid #ccc;
		/*针对IE6写法*/
		 _position:absolute;
		_top: expression(documentElement.scrollTop + documentElement.clientHeight-this.offsetHeight); 
		_position:static;
	}

	#overlay #tit {
		width: 100%;
		height: 20px;
		line-height: 20px;
		text-indent:10px;
		border-bottom:3px solid #ccc;
	}
	#overlay #tit #title {
		float:left;
	}
	#overlay #tit #qun{
		float: right;
		margin-right: 10px;
	}
	#overlay #tit #qun span {
		font-weight: bold;
	}
	#overlay #content{
		height: 72px;
		/*overflow-y:auto;*/
	}
	#overlay #content ul li{
		text-indent: 10px;
		list-style-type: none;
		border-bottom: 1px dashed #ccc;
		width:100%;
		height:auto;
		line-height: 20px;
	}
</style>
<body>
	<div id="hide"></div>
	<div id="overlay">
		<div id="tit">
			<div id="title">欢迎使用XbPhp框架(IE6無法悬浮)</div>
			<div id="qun">
					欢迎小波IT交流群号:<span>114252528</span>
			</div>
		</div>
		<div id="content">
			<ul>
			<?php
				if(isset($sqlArr['sql'])) {
					foreach($sqlArr['sql'] as $k => $v) {
						$sqlTime	= round($sqlArr['time'][$k],4);
						echo '<li>'. ($k + 1) .'、 '.$v.' (<strong>'.$sqlTime.'s</strong>)</li>';
					}
				}
			?>
			</ul>
		</div>
	</div>

</body>
</html> 
