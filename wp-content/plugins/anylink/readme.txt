=== anyLink ===
Contributors: SivaDu
Donate link: http://dudo.org/
Tags: seo, link sanitize, covert external links to internal links
Requires at least: 3.4
Tested up to: 4.2.2
Stable tag: 0.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

AnyLink is a Wordpress plugin which allow you to customise you external link like an internal one.

== Description ==

Anylink 是一款链接转换插件，它可以把长链接转换成短链接，也可以将外部链接转换成内部链接，同时还可以隐藏真实的链接地址。它不会修改wordpress自带的数据库，也不会修改文章中的任何内容，无论什么时候都不会影响到你数据的完整性。是同类软件中安全性较高、方便灵活的轻量级软件。

Anylink allows you to covert the external links in your Wordpress to internal links. Of course, it's automatically. It's advantage
is that Anylink Plugin doesn't destroy your data in Wordpress, which means once you removed it, you needn't do anything to your 
posts.

Also, you can customise the style of the link, such as its length, component, etc. You can customise the redirect type(http status) such as
301, 307 as well.

Mainly feature:

*   covert external links to internal links, e.g. http://wordpress.org -> http://yourdomain/goto/a1b2
*   customise the redirect category, e.g. you can change "goto" in the link above to any word you like
*   allow you change the components of the slug, by default it's 4 letters and numbers. e.g. a1b2
*   you can customise the redirect http status code, such as 301, 307

== Frequently Asked Questions == 
= What to do after installation? =
Once Anylink is installed in your wordpress, you need running scan for the first time. Anylink will scan all your posts and grab all the
external links.

== Screenshots ==

== Installation ==

1. Upload `anylink.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Scan all your post for the first time.

== Changelog ==

= 0.2.4 =
*   修复了使用第三方编辑器（如 UEditor）时造成的链接不能转换问题
*   修复了在PHP5.5下出现的代码中断问题

= 0.2 =
*   增加了对评论中出现的链接的转换
*   更新了部分代码
*   优化性能，减少资源开支

= 0.1.9 =
*   修改了Javascript跳转方式，不再依赖于.htaccess配置文件
*   优化了部分代码，性能得到一定提升

= 0.1.8 =
*   Fixed a bug which cause fatal error
*   修复了一个严重bug

= 0.1.7 =
*   Fixed some bugs may cause links missing
*   optmized codes
*   add the option which allows you set attribute REL of a link
*   为链接增加了rel选项功能
*   代码优化
*   修复了一个bug，该bug可能引起anylink把内链误判为外链

= 0.1.6 =
*	Fixed some bugs cause 404 error
*	Fixed a bug which may cause collison with other plugins
*	修复了一些可能会引起插件造成404错误的bug
*	修复了与其他插件存在潜在冲突的bug

= 0.1.5 =
*	Add a method anylink() you can call it anywhere. e.g. anylink( 'http://dudo.org', get_the_ID() ) you will get a coverted link
*	another example: <? echo function_exists('anylink') ? anylink( $externalUrl, get_the_ID()) : $externalUrl; ?>
*	增加了一个 anylink()方法，接受两个参数，第一个为需要转换的链接，第二个为文章ID，如果文章ID为空则默认为0
*	使用方法如下 <? echo function_exists('anylink') ? anylink( $externalUrl, get_the_ID()) : $externalUrl; ?>

= 0.1.4 =
*	Fully support custom post types.
*	You can custom which post types to be coverted which not.
*	Most important, you needn't regenerate index once you change these settings
*	Change log in Chinese below 以下为中文更新内容
*	增加了对自定义类型文章的支持
*	允许用户通过后台设置哪些类型的文章进行转换，哪些不需要进行转换
*	最重要的是每次你更改这些设置时都不需要重新生成索引。

= 0.1.3 =
*	Fixed redirect problems, e.g. you can redirect url like http://dudo.org/.../url=http://...

= 0.1.2 =
*	Fixed some bugs in previous version
*	Customed post type posts supported
*	Javascript to redirect a page is available now

= 0.1.1 =
*	Fixed some bugs in v0.1
*	Both English and Chinese languages are now supported
*	POT file is supplied, so you can tranlate it into your own language as well

= 0.1 =
*	Covert all the external links to internal links by default
*	Customise your link type
*	Customise redirect http status code

== Upgrade Notice ==

=0.1.3=
Fixed a bug which can cause url broken when redirecting

=0.1.1=
Fixed some bugs in v0.1
Both English and Chinese languages are now supported
POT file is supplied, so you can tranlate it into your own language as well

=0.1=
Main feature is developed.