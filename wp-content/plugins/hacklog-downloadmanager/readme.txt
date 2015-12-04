=== Hacklog DownloadManager ===
Contributors: ihacklog
Donate link: http://ihacklog.com/donate
Tags: download, manager,file
Requires at least: 3.2.1
Tested up to: 3.3
Stable tag: 2.1.3

A download manager for your WordPress blog,modified from WP-DownloadManager originally by Lester 'GaMerZ' Chan.

== Description ==
Features: Adds a simple download manager to your WordPress blog.
similar to WP-DownloadManager,but I added more features and cut some of the not commonly used features.Especially that I rewote most of the code struct make the code much more readable and fast and added support for Chinese filename files.

* support popup display effect
* support upload and download Chinese-word-filename file
* support download count and alse has download stats
* support anti leech
* support custom display template and custom CSS

For MORE information,please see [changelog](http://wordpress.org/extend/plugins/hacklog-downloadmanager/changelog/ "changelog") and　[FAQ](http://wordpress.org/extend/plugins/hacklog-downloadmanager/faq/ "FAQ")
Your can also visit the [plugin homepage](http://ihacklog.com/?p=3775 "plugin homepage") for any questions about the plugin.

Simplified Chinese(zh_CN) language po and mo files. By [荒野无灯](http://ihacklog.com "荒野无灯weblog") @  荒野无灯weblog 

Traditional Chinese(zh_TW) language po and mo files. By [冷.吉米](http://6ala.com "{ 六翼之章 }") @  { 六翼之章 } 

* 2.1.3版增加对WP 3.0的支持
* 2.1.2版在各种主题下测试均通过，如有bug，欢迎大家反馈。
* 基本重构了代码，使代码比原插件可读性更强，效率更高。特别是添加了对中文文件名的文件的支持。
* 支持弹出层效果、支持上传和下载中文名文件。
* 有下载统计，支持设置下载防盗链。支持自定义CSS，支持自定义下载文件显示样式。
更多信息请查看 [changelog](http://wordpress.org/extend/plugins/hacklog-downloadmanager/changelog/ "changelog") 和　[FAQ](http://wordpress.org/extend/plugins/hacklog-downloadmanager/faq/ "FAQ")
你也可以访问[插件主页](http://ihacklog.com/?p=3775 "plugin homepage") 获取关于插件的更多信息，使用技巧等.

* 简体中文语言包(zh_CN) po 和 mo 文件由 [荒野无灯](http://ihacklog.com "荒野无灯weblog") @  荒野无灯weblog 提供。
* 繁體中文語系包(zh_TW) po 和 mo 文件由 [冷.吉米](http://6ala.com "{ 六翼之章 }") @  { 六翼之章 } 提供。

== Installation ==

1. Upload the whole fold `hacklog-downloadmanager` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Upload files in WordPress via click the  **add files**  button OR your can upload files through  FTP client  ,and then ,you can add the download file in your post.


== Screenshots ==

1. screenshot-12.png
2. screenshot-10.png
3. screenshot-1.png
4. screenshot-2.png
5. screenshot-3.png
6. screenshot-4.png
7. screenshot-5.png
8. screenshot-6.png
9. screenshot-7.png
10. screenshot-8.png
11. screenshot-9.png
12. screenshot-11.png

  



== Frequently Asked Questions ==

= why this plugin does not work OR why I can not upload files via the plugin ? =

please check if the **download path** and **download url** options value are correct in the plugin **download Options** Page.
and please be sure that `wp-content/files` directory has permission `0777`

= why the Download Nice Permalink doesnot work ? =

just go to **Settings** --> **Pamerlinks** Page and do nothing, then the plugin's **Download Nice Permalink** will work.

= How to change the display style ? =

You can modidify the display template via 'download options' page.
Here is a sample template :


	<p><table style="background-color:#e5e5e5;width:450px;">
	<tr>
	<td><img src="http://your-domain.com/wp-content/plugins/hacklog-downloadmanager/images/ext/%FILE_ICON%" a	lt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;<strong><a href="%FILE_DOWNLOAD_URL%">%FILE_NAME%</a></	strong> </td>
	<td>File size：%FILE_SIZE%</td>
	</tr>
	<tr>
	<td>updated：%FILE_UPDATED_DATE% </td><td>count：%FILE_HITS% times </td>
	</tr>
	<tr>
	<td>MD5 checksum：%FILE_HASH% </td><td>
	<a href="http://URL-for-download-MD5-checksum-tool" target="_blank">[MD5 checksum tool]</a>  </td>
	</tr>
	</table>
	</p>

= is there another sample custom CSS for popup ? =
Here is a sample custom CSS I'm using now:

	.hacklogdownload_downlinks { width:500px;}
	.hacklogdownload_down_link {margin-top:10px;background:#E0E2E4;border:1px solid #333300;padding:5px 5px 5px 20px;
	color:#222222;
	}
	.hacklogdownload_down_link a{
	color:#57d;
	}
	.hacklogdownload_views{color:#f00;}
	.hacklogdownload_box{
	padding:10px 0;
	border-bottom:1px solid #DDDDDD}
	.hacklogdownload_box_content{line-height:18px;padding:0 0 0 10px}
	.hacklogdownload_box_content p{margin:5px 0}	
	#facebox .content
	{
	width:600px;
	background: none repeat scroll 0 0 #E0E2E4;
	color: #333333;
	}
	#facebox .popup { border: 6px solid #444;}

with custom CSS options,You can change the view effect to whatever you want.


== Upgrade Notice ==

= 2.0.0 =
* 删除本插件原有文件，上传新版文件.

= 1.5.6 =			 
* 重新下载新版zip文档，覆盖本插件目录下面的php文件和po/mo文件即可。			

= 1.5.5 =				
* 覆盖本插件目录下面的hacklog-downloadmanager.php和　hacklog-downloadmanager-zh_CN.mo　、hacklog-downloadmanager-zh_CN.po文件即可。				   

= 1.5.4 =			    
* 覆盖本插件目录下面的hacklog-downloadmanager.php和　hacklog-downloadmanager-zh_CN.mo　、hacklog-downloadmanager.po文件即可。						   
= 1.5.3 =							
* 重新下载新版zip文档，覆盖本插件目录下面的php文件即可。						  
* 并进入后台“下载选项”做相关设置，点击“保存所有更改”。	


== Changelog ==
= 2.1.4 =
* fixed: a little changes.

= 2.1.3 =
* fixed: Quicktags button uses the new Quicktags API function.
* fixed: popup(iframe) upload page uses iframe_header() and iframe_footer() instead of writing repeat code.
* fixed: change the add download button near media button to be compatible with WP 3.0
* changed: changed the plugin name from Hacklog-DownloadManager to Hacklog DownloadManager

= 2.1.2 =
* fixed: jQuery loading bug.now the plugin works well under almost all themes.

= 2.1.1 =
* optimized js and css loading(compressed).
* added Traditional Chinese(zh_TW) language po and mo files. Thanks to [冷.吉米](http://6ala.com "{ 六翼之章 }") @  { 六翼之章 } 
* moved po and mo files to new sub-dir languages
* added: auto flush rewrite rules when the plugin download method option is changed
* provide increased compatibility with nonstandard WordPress theme.
* modified the default custom popup template CSS and HTML code


= 2.1.0 =
* optimized js loading AND stylesheets loading (default : load css and js  singular only ,load js only there is download shortcode)


= 2.0.9 =
* fixed the bug in download_file (Check headers sent JUST before sending file,not check in the start while is not going to send file )

= 2.0.8 =
* fixed the bug that adding unnecessary slash to a remotefile in function download_file 

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


					
