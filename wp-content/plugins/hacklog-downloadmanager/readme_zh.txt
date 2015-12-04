
如果以前安装过wp-downloadmanager ，先禁用wp-downloadmanager。
然后运行一下 update_to_hacklog.php:
http://www.你的域名.com/wp-content/plugins/hacklog-downloadmanager/update_to_hacklog.php
提示：成功删除file_category列，现在你可以使用 wp-downloadmanager 荒野无灯修改版了-_-!
即可使用了。
然后可删除此文件。

如果没有安装过wp-downloadmanager就直接激活插件就是了。

== Changelog ==
=2.1.2=
修正jquery加载问题，按需加载jquery,在Twenty Eleven,iNove,simleDark等标准主题下均测试正常，在无jquery加载的主题下也测试过，均工作正常。

= 2.1.1 =
优化了js和css加载(压缩).
添加繁体中文语言包(zh_TW)，感谢 [冷.吉米](http://6ala.com "{ 六翼之章 }") @  { 六翼之章 } 提供。
增加了插件的对于不标准的WordPress主题的兼容性
完善：修改相应配置后rewrite规则自动更新


= 2.1.0 =
优化了js和css加载(默认情况下 : load css and js  singular only ,load js only there is download shortcode)


= 2.0.9 =
fixed the bug in download_file (Check headers sent JUST before sending file,not check in the start while is not going to send file )

= 2.0.8 =
fixed the bug that adding unnecessary slash to a remotefile in function download_file 

= 2.0.7 =
* use home_url() instead of site_url() in hacklogdm class.

= 2.0.6 =
* fixed the bug (can not find function hacklogdm::favorite_actions)in version 2.0.5 
* fixed the bug when the admin bar is on,it will take the place of the tabs on top of page download-upload-or-add.php,And now this will never happen.

= 2.0.5 =
added more file extension css rules and ignored .htaccess file in filetree view.
optimized the code.

= 2.0.4 =
added jquery fileTree capability

= 2.0.3 =
fixed the bug when use filename as download param there is double / in URL (unencoded)
use WP's site_url() function instead of use . to join strings.

= 2.0.2 =
*fixed three little bug and a translation error.

= 2.0.1 =
*add direct access check to avoid path exposed for security reasons.

= 2.0.0 =
1. version changed to 2.0.0,I did a lot of work to reform the code,to make it more readable.
2. fixed some litle bug
3. beautified the upload page in WordPress thickbox.
4. beautified the page navi.
5. fixed a bug that when search in the thickbox upload page,WP will stop us and say:"You don't have permission to do this".


= 1.5.6 =

2011/09/17
1. 版本号更改为 1.5.6
2. 修复以文件名方式为参数下载时错误地获取文件名失败的BUG
3. 增加：根据文件名方式下载时加密URL防盗链


= 1.5.5 =

2011/09/16
1. 版本号　1.5.5
2. 增加下载大文件（256M以下）支持
3. 增加文件大小为0判断、文件读取错误判断

= 1.5.4 =

2011/09/07
1. 版本号　1.5.4
2. 修正因上次失误造成不能正常解析包含多个ID的短代码的bug，如：
	`[download id="1,2,3"]`


= 1.5.3 =

2011/09/05
1. 版本号 1.5.3
2. 修正在上传文件时，如移动文件到相应目录失败，文件数据会错误地被添加进数据库的BUG
3. 增加popup显示方式
4. 增加自定义CSS功能

= 1.5.2 =

2011/04/26
1. 版本号 1.5.2
2. 修正thickbox窗口中左侧文字显示问题
3. thickbox窗口顶部新增一添加按钮，方便点击。
4. RSS中嵌入下载文件提示增加文件下载页面的URL
5. 下载选项增加对mu站点判断，下载路径增加对转移博客后地址是否存在的判断，若不存在则重置为默认路径(/path-to/wp-content/files)
6. 增加7z格式文件的icon
7. 更新文件时，遇重名文件，旧文件不再删除，，以 **.bak** 后缀附加重命名之 .(如 foo.rar.bak )

= 1.5.1 =

2011/03/15
1. 版本号修改为1.5.1
2. 修正Mysql4.0下无法正常安装插件的BUG
3. 修正一个小地方（先检测upgrade.php再检测upgrade-functions.php）

= 1.5.0 =

2011/02/18
1. 修正远程文件下载次数不统计的BUG
2. 更正版本号到1.5

2010/12/02
 1，修正远程文件不能正常添加的BUG
 2，修正重复文件检查BUG
 3，远程文件下载修改为直接REDIRECT，减小服务器负担

2010/10/30
 增加：在编辑文章时直接插入或上传文件然后插入文章的功能

2010/10/26
 1,“下载管理”增加了直接输入文件ID修改文件的功能
 2，“编辑文件”增加对文件是否存在的判断
 3,修正选择”使用固定链接“ 后不进后台点一下”设置“-》“固定链接” 无法正常下载文件的BUG

2010/10/24
 1,增加添加重复文件检测
 2，修改上传文件时不选择文件也插入一个ID的BUG

2010/06/13
 修正IE下中文名字乱码BUG 

2010/05/24
 修正防盗链bug

2010/05/21
 修正下载次数统计
 增加防盗链（强制HTTP来路检查）功能的配置选项

2010-5-18
 1、修改重命名机制，对于不非中文名的文件，基本保持原名，对于中文名的文件，重命名为年月日+文件名的md5值。(文件下载基本保持原名）
 2、后台新增丢失文件显示功能，以红色警告显示。
 2010-05-07:
 1,增加防盗链功能
 2,修正上传bug
 3,增加md5校验

 2010-05-06:
修正一处删除bug,(原版无法正常删除文件)
* `if(!unlink($file_path.$file)) {`  修正为：`if(!unlink($file_path.’/’.$file)) {`
* 修正不能正确处理中文文件名的bug
  原版无法正常上传和处理中文文件名的文件，修正之。

* 增强rewrite规则
  修正原版固定链接模式，可以自由定义固定链接。

* 增加下载远程文件到本地服务器的功能
	对于远程文件，可以选择是否要存储到本地服务器。

* 修复禁用和启用插件时的bug
 原版是禁用和启用插件后所有文件全部变为不可下载。这是不合需求的。

* 去掉了widget
	 感觉这个基本上用得很少，去掉了。

* 去掉了rss页面
	这个也基本不用吧。

* 去掉了冗余的模板设置页面
	原版的模板设置页面有一大堆东西……..  

* 去掉了分类
	我用了一两年了，从来没有用过它的分类功能。
	此功能如有需要，以后增加就是了。

* 修正的win下目录不正常的bug
	原版如果是在win服务器下，下载目录的路径是不对了。
	在options页面，stripslashes 后 \ 会变没了，此时如果更新配置，会造成路径错误。
	修正之：`str_replace(“\\”,’/',WP_CONTENT_DIR)`

* 将插件的options（配置选项）减少到5个


* 增加防盗链功能



http://ihacklog.com/
http://ihacklog.com/wordpress/plugins/hacklog-downloadmanager.html

											by 荒野无灯 
											2011-10-10
