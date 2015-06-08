<!DOCTYPE HTML>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>系统发生错误</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
html{ overflow-y: scroll; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
img{ border: 0; }
h1{ font-size: 28px; line-height: 48px; }
.small { width:1024px; height: auto; margin: 0 auto;}
.small h2{ text-align: center; font-size: 60px;}
.content{ padding-top: 10px; font-size: 16px; }
.info{ margin-bottom: 12px; font-size: 16px; }
.info .title{ margin-bottom: 3px; font-size: 16px;  }
.info .title h3{ color: #000; font-weight: 700; font-size: 16px; }
.info .text{ line-height: 24px; }
.copyright{ padding: 12px 48px; color: #999; text-align: right; font-size: 16px; }
.copyright a{ color: #000; text-decoration: none; font-size: 16px; }
</style>
</head>
<body>
<div class="small">
	<h2>╯﹏╰<h2>
	<h1><?php echo $arr['message'];?></h1>
	<div class="content">
		<div class="info">
			<div class="title">
				<h3>错误位置</h3>
			</div>
			<div class="text">
				<p>FILE: <?php echo $arr['file'] ;?> &#12288;第<?php echo $arr['line'];?>行</p>
			</div>
		</div>
	</div>
	<div class="copyright">
	<p><a title="官方网站" href="javascript:;">XbPhp</a><sup></sup>官方QQ群:114252528</p>
	</div>
</div>
</body>
</html>