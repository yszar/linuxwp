=== 七牛图床 ===
Contributors: 杨中超
Donate link:https://qr.alipay.com/apl1u77qa7bbxv552e
Tags: 图床,七牛,qiniu
Requires at least: 3.0
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

七牛图床插件：编辑器中上传图片至七牛服务器

== Description ==

该插件让你直接在编辑器页面上传图片到七牛服务器

上传完成后自动在编辑器中添加图片，不需要设置全站CDN

功能简单，更方便

详细介绍： http://www.yangzhongchao.com/works/qiniu-tuchaung/


源码：https://github.com/yangzhongchao1011/qiniu-tuchaung


== Installation ==

1. 上传 `qiniu-tuchaung`目录 到 `/wp-content/plugins/` 目录
2. 在后台插件菜单激活该插件
3. 在后台设置设置accesskey,screctkey,bucket,七牛绑定域名

== Frequently Asked Questions ==
=不能上传？=

上传前要设置ak和sk还有bucket
不要有多余的 “/”哦

=自动添加的图片title,alt信息？=

信息默认为文章标题，如果标题为空则为空

=为什么我上传的图片不显示？=

请确定编辑器是在可视化下，文本模式不能正常添加链接

== Screenshots ==

1. 七牛图床设置
2. 编辑器界面


== Changelog ==

= 0.1 =

初始版本

=0.2= 

修改代码，使其通过wordpress.org验证
修改文件位置
应用更高版本plupload

=0.3=

精简代码
自动设置图片title,alt信息

=0.4=

修复上传页面的显示问题
更改设置页描述
上传图片如果存在自动添加

== Upgrade Notice ==

= 0.1 =

初始版本


= 0.2 =

修改文件位置
应用更高版本plupload

=0.3=

精简代码
设置图片title,alt信息为文章标题

=0.4=

修复上传页面的显示问题
更改设置页描述
上传图片如果存在自动添加

=0.5=

紧急修复图片链接