# xbphp框架
通知:Xbphp从1.4.5版本开始,从新调整业务逻辑<br>
特色是:<br>
  1.强大的定时文件缓存机制<br>
  2.结合每个框架的优点进行组装<br>
  3.跨平台与跨环境无需修改配置<br>
  4.简单易上手<br>
2015-06-09:新增表单校验规则,与YII2的写法差不多<br>
2015-06-24:新增自定义重写URL参数校验<br>
2015-07-24:新增phprpc组件<br>
2015-07-28:新增控制器之间互相访问公用方法<br>
2016-04-10:<br>
1.对POST数组进行处理,除了单引号与双号自行过滤或转义<br>
2.对路由更智能,多级目录也可以找到准确的目录<br>
3.可以自由的禁用控制器在URL访问<br>
4.可以共用系统配置文件，只要删除HOME自定义目录下的databases文件就会加载系统配置文件<br>
<h1>与其他框架不一样的地方就是定时文件缓存,特点:有效的帮你减少查询次数,静态化数据,提高效率,还实现延迟查询数据</h1><br>
xbphp框架说明:<br>
  提取YII2的MODEL校验规则写法,进行表单校验,使用cakephp目录结构思想,在xbphp框架伪静态时候,不管在空间机使用还是服务器更安全,可以有效的防止访问的游客进行访问PHP文件,新增加了动态写法,使用thinkphp的动态路由写法,默认参数M是加载控制器名,a是控制器的方法名,后面还会更新,让xbphp框架更强大,核心文件小.<br>
xbphp框架使用教程地址:<br>
  http://xbphp.nmfox.com/Index/lists <br>
  http://php.nmfox.com/Index/lists <br>
  函数详解:http://xbphp.nmfox.com/bootstrap <br>
演示:<br>
linux nginx演示网址:http://xbphp.nmfox.com/<br>

linux apache演示网址:http://php.nmfox.com/<br>

