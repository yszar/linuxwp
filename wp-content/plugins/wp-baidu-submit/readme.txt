=== WP BaiDu Submit ===

Contributors: Include
Tags: Baidu,Sitemap,Linksubmit,Seo
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://www.170mv.com

WP BaiDu Submit帮助具有百度站长平台链接提交权限的用户自动提交最新文章，以保证新链接可以及时被百度收录。


== Description ==

After installing WP BaiDu Submit, you can quickly and completely submit webpages to Baidu. It helps you with the following:
1) Baidu Spider will better understand and include your site.
2) Baidu Search will better display results from your site.

安装WP BaiDu Submit后，能又快又全的向百度提交网页，有助于：
1）百度 Spider 更好地了解您的网站，优化收录
2）网站在百度搜索上得到更好展现


== Screenshots ==

1. WP BaiDu Submit设置选项面板截图
2. 提交结果记录和百度蜘蛛抓取状态记录截图
3. 百度站长平台WP BaiDu Submit推送效果截图


== Frequently Asked Questions ==

= WP BaiDu Submit是什么？ =

WP BaiDu Submit是一款自动提交链接到百度站长平台的插件，帮助获得百度站长Sitemap权限的用户自动提交最新文章，加速百度收录。

= WP BaiDu Submit与百度官方百度sitemap插件有什么不同？ =

WP BaiDu Submit自动提交的是文章网址，百度sitemap推送的是结构化数据。使用百度sitemap对主机有一定要求，部分网站无法通过验证，WP BaiDu Submit没有这个问题。

= WP BaiDu Submit是免费的吗？ =

WP BaiDu Submit永久完全免费。

= 什么样的网站可以使用WP BaiDu Submit？ =

网站使用WP BaiDu Submit前，需要在百度站长平台获得Sitemap权限，有主动推送(实时)接口，关于主动推送的官方介绍：http://zhanzhang.baidu.com/college/articleinfo?id=336。
 
= 如何填写站点准入密匙？ =

请在百度站长平台：http://zhanzhang.baidu.com/linksubmit/index 选择对应的站点获取密匙，比如：3sM2Wity6fP8TbR0

= 更新已发布文章会重复提交吗？ =

不会，插件不会重复提交已发布的文章。

= 在哪里查看自动提交结果？ =

请在百度站长平台：http://zhanzhang.baidu.com/sitemap/pingindex 选择对应的站点查看提交结果，信息有延时。

= 提交返回信息怎么查看？ =

插件会自动记录返回的错误信息，在插件设置中查看。


== Installation ==

1. 上传 `wp_baidu_submit` 目录到 `/wp-content/plugins/` 目录
2. 在 WordPress 插件面板激活 `WP BaiDu Submit` 插件
3. 在插件设置中填写站点选项，勾选自动提交


== Upgrade Notice ==
暂无

== Changelog ==

= 1.2.1 =
* [完善]几处代码完善；

= 1.2 =
* [新增]开启提交结果记录控制选项；
* [修复]修复快速编辑时更新文章重复提交的BUG；
* [优化]几处代码优化；

= 1.1 =
* [新增]每日提交结果记录；
* [新增]每日百度蜘蛛抓取网址记录；

= 1.0 =
* [新增]自动提交最新发布文章链接到百度；
* [新增]开启自动提交控制选项；
* [新增]返回错误信息自动记录；
* [新增]更新文章不会重复提交；