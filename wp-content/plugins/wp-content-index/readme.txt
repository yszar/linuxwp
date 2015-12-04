=== Content Index for Wordpress ===
Contributors: wangrs
Donate link: http://codante.org/wordpress-plugin-wp-content-index
Tags: SEO, Links, index, content
Requires at least: 2.8.6
Tested up to: 2.60
Stable tag: 2.60

在文章中添加内容索引，索引全部根据heading标签生成。并且可以由用户来配置展现方式。

Add content in the article index, all generated under the heading tags. And can be configured by the user display mode.

== Description ==

我们都试图在文章中添加更多内容，但内容的增长降低了文章的可读性，通常人们在阅读时就渐渐的忘记了阅读到哪里。本插件很好的解决了这个问题。

We are trying to add more content in the article, but reduced the growth of content readability of the article, usually when people gradually forget to read the reading where. This plug-in solves this problem well.

== Installation ==

上传插件到plugins目录，在您博客的后台激活它，选择左侧菜单中的“文章内索引”，对插件进行配置。

Upload the wp-content-index plugin to your blog, Activate it, then goto Control Pannel ,choose the "content index", be configured according to your needs.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.60 2012-6-21 =

   1. 修复因为没有引入摘要而引起的自然编号混乱问题。
      Fix natural number confusion caused because there is no introduction of summary.

= 2.45 2011-10-9 =

   1. 将插件生效的状态保存到数据库，可以使用两套配置来控制目录列表的显示。plug-in effect the state saved to the database, you can use two sets of configurations to control the directory listing display.
   2. 过滤掉索引中以及heading标签中的超链接。Filter out the index and the heading tag hyperlinks.
   3. 尝试着解决内存溢出问题。Try to solve the problem of memory overflow.
   4. js重新命名了方法，避免冲突。Js method renamed to avoid conflicts.
   5. 降低生效优先级，解决与wp-keyword-link等插件的冲突问题。Lower effective priority, resolve wp-keyword-link and other plug-in conflicts.
   6. DB cache功能准备(未实装)。DB cache(not real equipment).

= 2.43 2011-2-9 =

   1. 添加一个选项，能够开启为文章中的heading标签添加层级序号的功能。To add the article in the heading tag level number, similar to the Office of the "1, 1.1, 1.1.1" and can be turned on or off in the management. Reservation style: content-index-heading content-index-heading-level-[1~6]
   2. 返回索引菜单的功能(未实装)。Back to the Index menu functions (not real equipment).

= 2.42 2010-12-21 =

   1. 初步开启局部控制，在特定文章中可以禁用索引功能。Preliminary open local control, in particular article can disable index function.

= 2.4 2010-9-21 =

   1. 层级控制选项(功能)实装。Level control option (function) actual equipment.

= 2.31 2010-8-9 =

   1. 修改部分js方法名称及全局变量名称，避免命名冲突。Modifications js method name and global variable names to avoid naming conflicts.

= 2.30 2010-7-21 =

   1. 去除原先要使用的广告代码;Remove the ad code to use the original;
   2. 添加信息反馈方法，当插件被激活时，会反馈您的域名、wordpress版本、管理员邮箱，以便我们之后更好的为您服务；Add information feedback method, when the plugin is activated, it will feedback your domain name, wordpress version, the administrator mailbox, so that we can better serve you later;
   3. 修改部分方法名，避免与本站开发的其他插件造成冲突。Modify the part of the method name, and site development to avoid causing conflicts with other plug-ins.

= 2.25 2010-7-16 =

   1. 保留索引中的空格；Retained in the index space;
   2. 为索引添加title属性。Add the title attribute for the index.

= 2.10 2010-6-30 =

   1. 后台样式全面升级，更加美观；Background style fully upgraded, more beautiful;
   2. 优化代码。Optimized code.

= 2.02 2010-6-28 =

   1. 在混合摘要的时候，将它的层级改变为正文中的最小层级。In summary, when mixed, will it change the level of the minimum level in the body.

= 2.01 2010-6-25 =

   1. 在管理后台添加自定义设置，可以根据需求设定索引框的位置、标题、序号、内容显示控制等；In the management of the background to add custom settings, you can set the index box on demand position, title, serial number, the content display control;
   2. 将上个版本中的“单级列表”形式，升级为可以展现附属关系的“层级嵌套”形式。Will be the last version of the "Single List" form, can show affiliation to upgrade the "nested hierarchy" form.

= 1.0 2010-5-25 =

   1. 将摘要添加到内容头部，并且识别为索引。The content of the summary to the head, and identified as the index.

= 0.4 2010-4-30 =

   1. 可识别空heading标签，添加索引但不显示；To identify empty heading tags, add the index but does not show;
   2. 识别自定义id的heading标签，将锚文本保留，并使用heading标签里的内容作为索引；Identify the custom id's heading tags, anchor text will be retained, and use the heading tag as the key;
   3. 初步识别层级关系，并且通过css定义各个层级的样式；Preliminary identification hierarchy and all levels through the css style definitions;
   4. 绑定到the_content。Bound to the_content.

= 0.28 2010-4-13 =

   1. 识别heading标签，并且将内容作为索引和锚文本。Identification heading tags, and content as the index and the anchor text.

= 0.1 2010-4-5 =

   1. 文章中找到标签，排列为序号索引；The article to find labels, arrange for the serial number of the index;
   2. 在模板中调用添加到页面中。In the template calls add to the page.
