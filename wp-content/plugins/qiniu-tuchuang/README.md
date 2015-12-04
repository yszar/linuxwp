####七牛图床插件
---
#####该插件让你直接在编辑器页面上传图片到七牛服务器
#####上传完成后自动在编辑器中添加图片，不需要设置全站CDN
#####功能简单，更方便

###介绍
---
#####详细介绍： [http://www.yangzhongchao.com/works/qiniu-tuchaung/](http://www.yangzhongchao.com/works/qiniu-tuchaung/)

###安装
---
####1. 上传 `qiniu-tuchaung`目录 到 `/wp-content/plugins/` 目录
####2. 在后台插件菜单激活该插件
####3. 在后台设置设置accesskey,screctkey,bucket,七牛绑定域名

###截图
---

1. 七牛镜像存储设置
![](http://img.yangzhongchao.com/img-2015-06-13-001.png)
2.编辑器
![](http://img.yangzhongchao.com/screenshot-2.png)

###更新日志
---
2015-6-5

初始版本
 
2015-6-6

修改代码，使其通过wordpress.org验证
修改文件位置
应用更高版本plupload

2015-6-9

精简代码
设置图片title,alt信息为文章标题

2015-6-13

修复上传页面的显示问题
更改设置页描述
上传图片如果存在自动添加
修复域名设置


###问题
---

#####不能上传？

>上传前要设置ak和sk还有bucket
不要有多余的 “/”哦

#####自动添加的图片title,alt信息？

>信息默认为文章标题，如果标题为空则为空

####为什么我上传的图片不显示？

>请确定编辑器是在可视化下，文本模式不能正常添加链接





 